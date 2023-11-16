<?php
/*This file is part of "OWS for All PHP" (Rolf Joseph)
  https://github.com/owsPro/OWS_for_All_PHP/
  A spinn-off for PHP Versions 5.4 to 8.2 from:
  OpenWebSoccer-Sim(Ingo Hofmann), https://github.com/ihofmann/open-websoccer.

  "OWS for All PHP" is is distributed in WITHOUT ANY WARRANTY;
  without even the implied warranty of MERCHANTABILITY
  or FITNESS FOR A PARTICULAR PURPOSE.

  See GNU Lesser General Public License Version 3 http://www.gnu.org/licenses/

*****************************************************************************/
$entity = [];
if (isset($_REQUEST['entity'])) {
    $entity = strtolower(trim($_REQUEST['entity']));
}

if (!isset($adminpage[$entity])) {
    throw new Exception('Illegal call - unknown entity');
}

$page = json_decode($adminpage[$entity], true);

if (!$admin['r_admin'] && !$admin['r_demo'] && !$admin[$page['permissionrole']]) {
    throw new Exception(Message('error_access_denied'));
}

$configfile = $_SERVER['DOCUMENT_ROOT'] . '/admin/modules/' . $page['module'] . '/' . 'module.xml';

if (!file_exists($configfile)) {
    throw new Exception('File does not exist: ' . $configfile);
}

$xml = simplexml_load_file($configfile);
$entityConfig = $xml->xpath("//adminpage[@id = '".$entity."']/entity[1]");

if (!$entityConfig) {
    throw new Exception('No entity config found.');
}

$overviewConfig = $entityConfig[0]->xpath('overview[1]');

if (!$overviewConfig) {
    throw new Exception('No overview config found.');
}

$loggingEnabled = (boolean)$overviewConfig[0]->attributes()->logging;

if ($loggingEnabled) {
    $loggingColumns = (string)$overviewConfig[0]->attributes()->loggingcolumns;

    if (!strlen($loggingColumns)) {
        throw new Exception(Message('entitylogging_nologgingcolumns'));
    }
}

if (isset($_REQUEST['id'])) {
    $id = (int)$_REQUEST['id'];
}

echo '<h1>' . Message('entity_'.$entity) . '</h1>';

$tablePrefix = Config('db_prefix') . '_';
$mainTable = $tablePrefix . $entityConfig[0]->attributes()->dbtable;
$spaceTablePos = strrpos($mainTable, ' ');
$mainTableAlias = ($spaceTablePos) ? substr($mainTable, $spaceTablePos) . '.' : '';
$dbTableWithoutPrefix = removeAliasFromDbTableName($entityConfig[0]->attributes()->dbtable);
$dbTable = $tablePrefix . $dbTableWithoutPrefix;
$showOverview = TRUE;

if ($show == 'add' || $show == 'edit') {
    $showOverview = FALSE;
    $enableFileUpload = FALSE;
    $fields = $entityConfig[0]->xpath('editform/field');
    $formFields = [];

    foreach ($fields as $field) {
        $attrs = $field->attributes();

        if ($show == 'add' && (boolean)$attrs['editonly']) {
            continue;
        }

        $roles = (string)$attrs['roles'];

        if (strlen($roles) && (!isset($admin['r_admin']) || !$admin['r_admin'])) {
            $rolesArr = explode(',', $roles);
            $hasRole = FALSE;

            foreach ($rolesArr as $requiredRole) {
                if (isset($admin[$requiredRole]) && $admin[$requiredRole]) {
                    $hasRole = TRUE;
                    break;
                }
            }

            if ($hasRole === FALSE) {
                continue;
            }
        }

        $fieldId = (string)$attrs['id'];
        $fieldInfo = [];
        $fieldInfo['type'] = (string)$attrs['type'];
        $fieldInfo['required'] = ($attrs['required'] == 'true' && !($show == 'edit' && $fieldInfo['type'] == 'password'));
        $fieldInfo['readonly'] = (boolean)$attrs['readonly'];
        $fieldInfo['jointable'] = (string)$attrs['jointable'];
        $fieldInfo['entity'] = (string)$attrs['entity'];
        $fieldInfo['labelcolumns'] = (string)$attrs['labelcolumns'];
        $fieldInfo['selection'] = (string)$attrs['selection'];
        $fieldInfo['converter'] = (string)$attrs['converter'];
        $fieldInfo['validator'] = (string)$attrs['validator'];
        $fieldInfo['default'] = (string)$attrs['default'];

        if ($fieldInfo['type'] == 'file') {
            $enableFileUpload = TRUE;
        }

        $formFields[$fieldId] = $fieldInfo;
    }

    $labelPrefix = 'entity_' . $entity . '_';

    if ($action == 'save') {
        try {
            if ($admin['r_demo']) {
                throw new Exception(Message('validationerror_no_changes_as_demo'));
            }

            $dbcolumns = [];

            foreach ($formFields as $fieldId => $fieldInfo) {
                if ($fieldInfo['readonly']) {
                    continue;
                }

                if ($fieldInfo['type'] == 'timestamp') {
                    $dateObj = DateTime::createFromFormat(Config('date_format') . ', H:i', $_POST[$fieldId . '_date'] . ', ' . $_POST[$fieldId . '_time']);
                    $fieldValue = ($dateObj) ? $dateObj->getTimestamp() : 0;
                } elseif ($fieldInfo['type'] == 'boolean') {
                    $fieldValue = (isset($_POST[$fieldId])) ? '1' : '0';
                } else {
                    $fieldValue = (isset($_POST[$fieldId])) ? $_POST[$fieldId] : '';
                }

                validateField($i18n, $fieldId, $fieldInfo, $fieldValue, $labelPrefix);

                if (strlen($fieldInfo['converter'])) {
                    $converter = new $fieldInfo['converter']($i18n, $website);
                    $fieldValue = $converter->toDbValue($fieldValue);
                }

                if (strlen($fieldValue) && $fieldInfo['type'] == 'date') {
                    $dateObj = DateTime::createFromFormat(Config('date_format'), $fieldValue);
                    $fieldValue = $dateObj->format('Y-m-d');
                } elseif ($fieldInfo['type'] == 'timestamp' && $fieldInfo['readonly'] && $show == 'add') {
                    $fieldValue = Timestamp();
                } elseif ($fieldInfo['type'] == 'file') {
                    if (isset($_FILES[$fieldId]) && isset($_FILES[$fieldId]['tmp_name']) && strlen($_FILES[$fieldId]['tmp_name'])) {
                        $fieldValue = hash('sha256', $entity . '-' . Timestamp());
                        $fieldValue .= '.' . uploadImageFile($i18n, $fieldId, $fieldValue, $entity);
                    } else {
                        continue;
                    }
                }

                if (!$fieldInfo['readonly'] or $fieldInfo['readonly'] && $fieldInfo['type'] == 'timestamp' && $show == 'add') {
                    $dbcolumns[$fieldId] = $fieldValue;
                }
            }

            if ($show == 'add') {
                $db->queryInsert($dbcolumns, $dbTable);
            } else {
                $whereCondition = 'id=%d';
                $parameter = $id;
                $db->queryUpdate($dbcolumns, $dbTable, $whereCondition, $parameter);

                if ($loggingEnabled) {
                    $result = $db->querySelect($loggingColumns, $dbTable, $whereCondition, $parameter);
                    $item = $result->fetch_array(MYSQLI_ASSOC);
                    $result->free();
                    logAdminAction($website, 'edit', $admin['name'], $entity, json_encode($item));
                }
            }

            echo createSuccessMessage(Message('alert_save_success'), '');
            $showOverview = TRUE;
        } catch (Exception $e) {
            echo createErrorMessage(Message('subpage_error_alertbox_title'), $e->getMessage());
        }
    }
}

if ($show == 'add') {
    if (!$showOverview) {
        ?>
        <form action='<?php echo escapeOutput($_SERVER['PHP_SELF']); ?>' method='post' class='form-horizontal' <?php if ($enableFileUpload) echo 'enctype=\'multipart/form-data\''; ?>>
            <input type='hidden' name='show' value='<?php echo $show; ?>'>
            <input type='hidden' name='entity' value='<?php echo $entity; ?>'>
            <input type='hidden' name='action' value='save'>
            <input type='hidden' name='site' value='<?php echo $site; ?>'>
            <fieldset>
                <legend><?php echo Message('manage_add_title'); ?></legend>
                <?php
                foreach ($formFields as $fieldId => $fieldInfo) {
                    $fieldValue = null;

                    if (isset($_REQUEST[$fieldId])) {
                        $fieldValue = $_REQUEST[$fieldId];
                    } elseif (!count($_POST) && strlen($fieldInfo['default'])) {
                        $fieldValue = $fieldInfo['default'];
                    }

                    echo escapeOutput(createFormGroup($i18n, $fieldId, $fieldInfo, $fieldValue, $labelPrefix));
                }
                ?>
            </fieldset>
            <div class='form-actions'>
                <input type='submit' class='btn btn-primary' accesskey='s' title='Alt + s' value='<?php echo Message('button_save'); ?>'>
                <a class='btn' href='?site=<?php echo $site; ?>&entity=<?php echo $entity; ?>'><?php echo Message('button_cancel'); ?></a>
            </div>
        </form>
        <?php
    }
} elseif ($show == "edit") {
    if (!$showOverview) {
        $columns = '*';
        $whereCondition = 'id=%d';
        $result = $db->querySelect($columns, $dbTable, $whereCondition, $id, 1);
        $row = $result->fetch_array();

        if (!$row) {
            throw new Exception('Invalid URL - Item does not exist.');
        }

        $result->free();
        ?>
        <form action='<?php echo escapeOutput($_SERVER['PHP_SELF']); ?>#item<?php echo escapeOutput($row['id']); ?>' method='post' class='form-horizontal' <?php if ($enableFileUpload) echo 'enctype=\'multipart/form-data\''; ?>>
            <input type='hidden' name='show' value='<?php echo $show; ?>'>
            <input type='hidden' name='entity' value='<?php echo $entity; ?>'>
            <input type='hidden' name='action' value='save'>
            <input type='hidden' name='id' value='<?php echo escapeOutput($id); ?>'>
            <input type='hidden' name='site' value='<?php echo $site; ?>'>
            <?php
            if (isset($_REQUEST['page'])) {
                ?><input type='hidden' name='page' value='<?php echo escapeOutput($_REQUEST['page']); ?>'><?php
            }
            ?>
            <fieldset>
                <legend><?php echo Message('manage_edit_title'); ?></legend>
                <?php
                foreach ($formFields as $fieldId => $fieldInfo) {
                    $fieldValue = ($action == 'save' && isset($_POST[$fieldId])) ? $_POST[$fieldId] : $row[$fieldId];
                    echo escapeOutput(createFormGroup($i18n, $fieldId, $fieldInfo, $fieldValue, $labelPrefix));
                }
                ?>
            </fieldset>
            <div class='form-actions'>
                <input type='submit' class='btn btn-primary' accesskey='s' title='Alt + s' value='<?php echo Message('button_save'); ?>'>
                <a class='btn' href='?site=<?php echo $site; ?>&entity=<?php echo $entity; ?>'><?php echo Message('button_cancel'); ?></a>
            </div>
        </form>
        <?php
    }
}



if ($showOverview) {
    $del_id = isset($_REQUEST['del_id']) ? $_REQUEST['del_id'] : [];
    $sortColumn = false;
    $sortAscending = true;

    if (isset($_REQUEST['resetsort']) && $_REQUEST['resetsort'] == 1) {
        unset($_SESSION[$entity . 'sortcolumn']);
        unset($_SESSION[$entity . 'sortdir']);
    } else {
        if (isset($_REQUEST['sortcolumn'])) {
            $sortColumn = $_REQUEST['sortcolumn'];
            $_SESSION[$entity . 'sortcolumn'] = $sortColumn;
        } elseif (isset($_SESSION[$entity . 'sortcolumn'])) {
            $sortColumn = $_SESSION[$entity . 'sortcolumn'];
        }

        if (isset($_REQUEST['sortdir'])) {
            $sortAscending = $_REQUEST['sortdir'];
            $_SESSION[$entity . 'sortdir'] = $sortAscending;
        } elseif (isset($_SESSION[$entity . 'sortdir'])) {
            $sortAscending = $_SESSION[$entity . 'sortdir'];
        }
    }

    $entitycolumns = $overviewConfig[0]->xpath('column');
    $addEnabled = $overviewConfig[0]->attributes()->add != 'false';
    $deleteEnabled = $overviewConfig[0]->attributes()->delete == 'true';
    $editEnabled = $overviewConfig[0]->attributes()->edit == 'true';

    if ($addEnabled) {
        echo "<p><a class=\"btn btn-small\" href=\"?site=" . $site . '&entity=' . $entity . "&show=add\"><i class=\"icon-file\"></i> " . Message('manage_add') . "</a></p>";
    }

    $fields = $mainTableAlias . 'id AS id';
    $outputColumns = [];
    $filterFields = [];
    $openSearchForm = false;

    foreach ($entitycolumns as $column) {
        $attrs = $column->attributes();
        $fieldId = (string) $attrs['id'];
        $attrs['field'] = str_replace('{tablePrefix}', $conf['db_prefix'], $attrs['field']);
        $fields .= ', ' . $attrs['field'] . ' AS ' . $fieldId;
        $columnInfo = [];

        if (!$attrs['hidden']) {
            $columnInfo['type'] = $attrs['type'];

            if (isset($attrs['converter'])) {
                $columnInfo['converter'] = (string) $attrs['converter'];
            }

            $columnInfo['sort'] = $attrs['sort'] == 'true' ? true : false;
            $outputColumns[$fieldId] = $columnInfo;
        }

        if ($attrs['filter']) {
            $filterFieldInfo['type'] = (string) $attrs['type'];
            $filterFieldInfo['field'] = (string) $attrs['field'];
            $filterFieldInfo['selection'] = (string) $attrs['selection'];

            if (isset($_REQUEST['filterreset']) && $_REQUEST['filterreset'] == '1') {
                unset($_SESSION[$entity . $fieldId]);
            }

            if (isset($_REQUEST[$fieldId])) {
                $filterFieldInfo['value'] = trim($_REQUEST[$fieldId]);
                $_SESSION[$entity . $fieldId] = $filterFieldInfo['value'];
            } elseif (isset($_SESSION[$entity . $fieldId])) {
                $filterFieldInfo['value'] = $_SESSION[$entity . $fieldId];
            } else {
                $filterFieldInfo['value'] = null;
            }

            $filterFields[$fieldId] = $filterFieldInfo;

            if ($filterFieldInfo['value'] !== null) {
                $openSearchForm = true;
            }
        }
    }

    if (count($filterFields)) {
        ?>
        <div class='accordion' id='searchFrm'>
            <div class='accordion-group'>
                <div class='accordion-heading'>
                    <a class='accordion-toggle' data-toggle='collapse' data-parent='#searchFrm' href='#collapseOne' title='<?php echo Message('manage_search_collapse'); ?>'>
                        <i class='icon-filter'></i><?php echo Message('manage_search_title'); ?>
                    </a>
                </div>
                <div id='collapseOne' class='accordion-body collapse<?php if ($openSearchForm) echo 'in' ?>'>
                    <div class='accordion-inner'>
                        <form class='form-horizontal' name='frmSearch' action='<?php echo escapeOutput($_SERVER['PHP_SELF']); ?>' method='get'>
                            <input type='hidden' name='site' value='<?php echo $site; ?>'>
                            <input type='hidden' name='entity' value='<?php echo $entity; ?>'>
                            <?php
                            foreach ($filterFields as $filterFieldId => $filterFieldInfo) {
                                if ($filterFieldInfo['type'] !== 'timestamp' && $filterFieldInfo['type'] !== 'date') {
                                    echo createFormGroup($i18n, $filterFieldId, $filterFieldInfo, $filterFieldInfo['value'], '');
                                }
                            }
                            ?>
                            <div class='control-group'>
                                <div class='controls'>
                                    <button type='submit' class='btn btn-primary'><?php echo Message('button_search'); ?></button>
                                    <a href='?site=<?php echo $site; ?>&entity=<?php echo $entity; ?>&filterreset=1' class='btn'><?php echo Message('button_reset'); ?></a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    if ($deleteEnabled && $action == 'delete') {
        if (isset($id) && $id) {
            $del_id = array($id);
        }

        if ($admin['r_demo']) {
            throw new Exception(Message('error_access_denied'));
        }

        if (count($del_id)) {
            $dependencies = findDependentEntities($dbTableWithoutPrefix);

            foreach ($del_id as $deleteId) {
                if ($loggingEnabled) {
                    $result = $db->querySelect($loggingColumns, $dbTable, "id=%d", $deleteId);
                    $item = $result->fetch_array(MYSQLI_ASSOC);
                    $result->free();
                    logAdminAction($website, 'delete', $admin['name'], $entity, json_encode($item));
                }

                $db->queryDelete($dbTable, 'id=%d', $deleteId);

                foreach ($dependencies as $dependency) {
                    $fromTable = Config('db_prefix') . '_' . $dependency['dbtable'];
                    $whereCondition = $dependency['columnid'] . '=%d';

                    if (strtolower($dependency['cascade']) == 'delete') {
                        $db->queryDelete($fromTable, $whereCondition, $deleteId);
                    } else {
                        $db->queryUpdate(array($dependency['columnid'] => 0), $fromTable, $whereCondition, $deleteId);
                    }
                }
            }

            echo createSuccessMessage(Message('manage_success_delete'), '');
        }
    }

    $fromTable = $mainTable;
    $joins = $overviewConfig[0]->xpath('join');

    foreach ($joins as $join) {
        $fromTable .= " " . $join->attributes()->type . ' JOIN ' . $tablePrefix . $join->attributes()->jointable . ' ON ' . $join->attributes()->joincondition;
    }

    $wherePart = '1=1';
    $parameters = [];

    foreach ($filterFields as $filterFieldId => $filterFieldInfo) {
        if (strlen(trim($filterFieldInfo['value']))) {
            $searchValue = strtoupper(trim($filterFieldInfo['value']));
            $wherePart .= ' AND UCASE(' . $filterFieldInfo['field'] . ") LIKE '%%%s%%'";
            $parameters[] = $searchValue;
        }
    }

    $columns = 'COUNT(*) AS hits';
    $result = $db->querySelect($columns, $fromTable, $wherePart, $parameters);
    $rows = $result->fetch_array();
    $result->free();

    if (!$rows['hits']) {
        echo createInfoMessage(Message('manage_no_records_found'), '');
    } else {
        $seite = isset($_REQUEST['page']) ? (int) $_REQUEST['page'] : 1;
        $eps = 20;

        if ($rows['hits'] % $eps) {
            $seiten = floor($rows['hits'] / $eps) + 1;
        } else {
            $seiten = $rows['hits'] / $eps;
        }

        $start = ($seite - 1) * $eps;
        $firstNo = $start + 1;
        $lastNo = min($start + $eps, $rows['hits']);

        echo '<p>' . sprintf(Message('manage_number_of_records'), $rows['hits'], $firstNo, $lastNo) . '</p>';

        if ($sortColumn) {
            $wherePart .= ' ORDER BY ' . $db->connection->real_escape_string($sortColumn);
            $wherePart .= $sortAscending ? ' ASC' : ' DESC';
        }

        $limit = $start . ',' . $eps;
        $result = $db->querySelect($fields, $fromTable, $wherePart, $parameters, $limit);

        echo "<form name=\"frmMain\" action=\"" . escapeOutput($_SERVER['PHP_SELF']) . "\" method=\"post\">";
        echo "<input type=\"hidden\" name=\"site\" value=\"" . $site . "\">";
        echo "<input type=\"hidden\" name=\"entity\" value=\"" . $entity . "\">";
        echo "<input type=\"hidden\" name=\"action\" value=\"delete\">";
        echo "<table class=\"table table-bordered table-striped\">";
        echo '<thead><tr>';

        if ($deleteEnabled) {
            echo "<th style=\"width:20px;\">&nbsp;</th>";
        }

        foreach ($outputColumns as $fieldId => $columnInfo) {
            echo '<th';

            if ($fieldId == 'entity_' . $entity . '_status') {
                echo "style=\"width:20px;\">&nbsp;";
            } else {
                $header = Message($fieldId);

                if ($columnInfo['sort']) {
                    $sortDir = $sortAscending ? 0 : 1;
                    $parameters = array(
                        'site' => $site,
                        'entity' => $entity,
                        'sortcolumn' => $fieldId,
                        'sortdir' => $sortDir,
                        'resetsort' => 0
                    );
                    $icon = '';

                    if ($sortColumn == $fieldId) {
                        $iconSuffix = $sortAscending ? 'up' : 'down';
                        $icon = " <i class=\"icon-circle-arrow-" . $iconSuffix . "\"></i>";
                    }

                    $tooltipKey = $sortAscending ? 'manage_sort_column_desc' : 'manage_sort_column_asc';
                    $tooltip = sprintf(Message($tooltipKey), Message($fieldId));
                    $header = "<a href=\"" . buildCurrentUrlWithParameters($parameters) . "\" title=\"" . $tooltip . "\">" . $header . $icon . "</a>";

                    if ($sortColumn == $fieldId) {
                        $header .= " <a href=\"" . buildCurrentUrlWithParameters(array('site' => $site, 'entity' => $entity, 'resetsort' => 1)) . "\" title=\"" . Message('manage_sort_column_reset') . "\"><i class=\"icon-remove-sign\"></i></a>";
                    }
                }

                echo '>' . $header;
            }

            echo '</th>';
        }

        if ($editEnabled) {
            echo "<th style=\"width:20px;\">&nbsp;</th>";
        }

        if ($deleteEnabled) {
            echo "<th style=\"width:20px;\">&nbsp;</th>";
        }

        echo '</tr></thead>';
        echo '<tbody>';

        $dateFormat = Config('date_format');
        $datetimeFormat = Config('datetime_format');
        $editTooltip = Message('manage_edit');
        $deleteTooltip = Message('manage_delete');

        while ($row = $result->fetch_array()) {
            echo '<tr>';

            if ($deleteEnabled) {
                echo "<td><input type=\"checkbox\" name=\"del_id[]\" value=\"" . escapeOutput($row['id']) . "\"></td>";
            }

            $first = true;

            foreach ($outputColumns as $fieldId => $columnInfo) {
                echo "<td id=\"item" . escapeOutput($row['id']) . "\">";
                $columnValue = $row['' . $fieldId];
                $type = $columnInfo['type'];
                $editUrl = '?site=' . $site . '&entity=' . $entity . '&show=edit&id=' . $row['id'];

                if (isset($_REQUEST['page'])) {
                    $editUrl .= '&page=' . escapeOutput($_REQUEST['page']);
                }

                if (isset($columnInfo['converter'])) {
                    $converter = getConverter($website, $i18n, $columnInfo['converter']);
                    echo escapeOutput($converter->toHtml($row));
                } elseif ($fieldId == 'entity_' . $entity . '_status') {
                    if ($columnValue == 1) {
                        echo "<i class=\"icon-ok-sign\" title=\"" . Message('manage_status_active') . "\"></i>";
                    } else {
                        echo "<i class=\"icon-ban-circle\" title=\"" . Message("manage_status_blocked") . "\"></i>";
                    }
                } elseif ($type == 'date') {
                    echo escapeOutput(date($dateFormat, $columnValue));
                } elseif ($type == 'timestamp') {
                    if ($columnValue) {
                        echo escapeOutput(date($datetimeFormat, $columnValue));
                    } else {
                        echo '-';
                    }
                } elseif ($type == 'email') {
                    echo "<a href=\"mailto:" . escapeOutput($columnValue) . "\" title=\"" . escapeOutput($columnValue) . "\"><i class=\"icon-envelope\"></i></a>";
                } elseif ($type == 'select' && hasMessage('option_' . $columnValue)) {
                    echo Message('option_' . escapeOutput($columnValue));
                } elseif ($type == 'boolean') {
                    $iconName = $columnValue ? 'icon-ok' : 'icon-minus-sign';
                    $iconTooltip = $columnValue ? Message('option_yes') : Message('option_no');
                    echo "<i class=\"" . $iconName . "\" title=\"" . $iconTooltip . "\"></i>";
                } elseif ($type == 'number') {
                    echo number_format(escapeOutput($columnValue), 0, ',', ' ');
                } elseif ($type == 'percent') {
                    echo escapeOutput($columnValue) . '%';
                } else {
                    if (hasMessage('option_' . $columnValue)) {
                        $columnValue = Message('option_' . $columnValue);
                    }

                    if ($first && $editEnabled) {
                        echo "<a href=\"" . escapeOutput($editUrl) . "\">";
                    }

                    echo escapeOutput($columnValue);

                    if ($first && $editEnabled) {
                        echo '</a>';
                    }
                }

                echo '</td>';
                $first = false;
            }

            if ($editEnabled) {
                $url = '?site=' . $site . '&entity=' . $entity . '&show=edit&id=' . $row['id'];
                echo "<td><a href=\"" . escapeOutput($url) . "\" title=\"" . $editTooltip . "\"><i class=\"icon-pencil\"></i></a></td>";
            }

            if ($deleteEnabled) {
                $url = '?site=' . $site . '&entity=' . $entity . '&action=delete&id=' . $row['id'];
                echo "<td><a href=\"" . escapeOutput($url) . "\" title=\"" . escapeOutput($deleteTooltip) . "\" class=\"deleteLink\"><i class=\"icon-trash\"></i></a></td>";
            }

            echo '</tr>';
        }

        echo '</tbody>';
        $result->free();
        echo '</table>';
        ?>
        <p>
            <label class='checkbox'>
                <input type='checkbox' name='selAll' value='1' onClick='selectAll()'>
                <?php echo Message('manage_select_all_label'); ?>
            </label>
        </p>
        <p>
            <?php echo Message('manage_selected_items_label'); ?>
            <input type='button' class='btn deleteBtn' accesskey='l' title='Alt + l' value='<?php echo Message('button_delete'); ?>'>
        </p>
        </form>
        <?php

        if ($rows['hits'] > $eps) {
            echo '<div class=\'pagination\'><ul>';

            if ($seite > 1) {
                $back = $seite - 1;
                $url = buildCurrentUrlWithParameters(array('site' => $site, 'entity' => $entity, 'page' => $back));
                echo '<li><a href=\'' . $url . '\'>&laquo;</a></li>';
            }

            $startIndex = max(1, $seite - 10);
            $endIndex = min($seiten, $seite + 10);

            if ($startIndex > 1) {
                $url = buildCurrentUrlWithParameters(array('site' => $site, 'entity' => $entity, 'page' => 1));
                echo '<li><a href=\'' . $url . '\'>1</a></li>';
                echo '<li class=\'disabled\'><span>...</span></li>';
            }

            for ($i = $startIndex; $i <= $endIndex; ++$i) {
                $url = buildCurrentUrlWithParameters(array('site' => $site, 'entity' => $entity, 'page' => $i));
                echo '<li';

                if ($i == $seite) {
                    echo ' class=\'active\'';
                }

                echo '><a href=\'' . $url . '\'>' . $i . '</a></li>';
            }

            if ($endIndex < $seiten) {
                echo '<li class=\'disabled\'><span>...</span></li>';
                $url = buildCurrentUrlWithParameters(array('site' => $site, 'entity' => $entity, 'page' => $seiten));
                echo '<li><a href=\'' . $url . '\'>End</a></li>';
            }

            if ($seite < $seiten) {
                $next = $seite + 1;
                $url = buildCurrentUrlWithParameters(array('site' => $site, 'entity' => $entity, 'page' => $next));
                echo '<li><a href=\'' . escapeOutput($url) . '\'>&raquo;</a></li>';
            }

            echo '</ul></div>';
        }
    }
}
