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
define("PARAM_SORTCOLUMN", "sortcolumn");
define("PARAM_SORTDIRECTION", "sortdir");
define("PARAM_PAGE", "page");
define("PARAM_RESETSORT", "resetsort");
$del_id = (isset($_REQUEST['del_id'])) ? $_REQUEST['del_id'] : array();
$sortColumn = FALSE;
$sortAscending = TRUE;
if (isset($_REQUEST[PARAM_RESETSORT]) && $_REQUEST[PARAM_RESETSORT] == 1) {
	unset($_SESSION[$entity . PARAM_SORTCOLUMN]);
	unset($_SESSION[$entity . PARAM_SORTDIRECTION]);}
else {
	if (isset($_REQUEST[PARAM_SORTCOLUMN])) {
		$sortColumn = $_REQUEST[PARAM_SORTCOLUMN];
		$_SESSION[$entity . PARAM_SORTCOLUMN] = $sortColumn;}
	 elseif (isset($_SESSION[$entity . PARAM_SORTCOLUMN])) $sortColumn = $_SESSION[$entity . PARAM_SORTCOLUMN];
	if (isset($_REQUEST[PARAM_SORTDIRECTION])) {
		$sortAscending = $_REQUEST[PARAM_SORTDIRECTION];
		$_SESSION[$entity . PARAM_SORTDIRECTION] = $sortAscending;}
	elseif (isset($_SESSION[$entity . PARAM_SORTDIRECTION])) $sortAscending = $_SESSION[$entity . PARAM_SORTDIRECTION];}
$entitycolumns = $overviewConfig[0]->xpath("column");
$addEnabled = ($overviewConfig[0]->attributes()->add == "false") ? FALSE : TRUE;
$deleteEnabled = ($overviewConfig[0]->attributes()->delete == "true") ? TRUE : FALSE;
$editEnabled = ($overviewConfig[0]->attributes()->edit == "true") ? TRUE : FALSE;
if ($addEnabled) echo "<p><a class=\"btn btn-small\" href=\"?site=". $site ."&entity=". $entity . "&show=add\"><i class=\"icon-file\"></i> ". $i18n->getMessage("manage_add") . "</a></p>";
$fields = $mainTableAlias . "id AS id";
$outputColumns = array();
$filterFields = array();
$openSearchForm = FALSE;
foreach ($entitycolumns as $column) {
	$attrs = $column->attributes();
	$fieldId = (string) $attrs["id"];
	$attrs["field"] = str_replace("{tablePrefix}", $conf["db_prefix"], $attrs["field"]);
	$fields .= ", " . $attrs["field"]." AS ". $fieldId;
	if (!$attrs["hidden"]) {
		$columnInfo["type"] = $attrs["type"];
		if (isset($attrs["converter"])) $columnInfo["converter"] = (string)$attrs["converter"];
		$columnInfo["sort"] = ($attrs["sort"] == "true") ? TRUE : FALSE;
		$outputColumns[$fieldId] = $columnInfo;}
	if ($attrs["filter"]) {
		$filterFieldInfo = ["type" => (string)$attrs["type"], "field" => (string)$attrs["field"], "selection" => (string)$attrs["selection"]];
		if (isset($_REQUEST["filterreset"]) && $_REQUEST["filterreset"] == "1") unset($_SESSION[$entity . $fieldId]);
		if (isset($_REQUEST[$fieldId])) {
			$filterFieldInfo["value"] = trim($_REQUEST[$fieldId]);
			$_SESSION[$entity . $fieldId] = $filterFieldInfo["value"];}
		elseif (isset($_SESSION[$entity . $fieldId])) $filterFieldInfo["value"] = $_SESSION[$entity . $fieldId];
		else $filterFieldInfo["value"] = NULL;
		$filterFields[$fieldId] = $filterFieldInfo;
		if ($filterFieldInfo["value"] !== NULL) $openSearchForm = TRUE;}}
if (count($filterFields)) include(__DIR__ . "/manage-searchform.inc.php");
if ($deleteEnabled && $action == "delete") include(__DIR__ . "/manage-delete.inc.php");
if (strlen($action) && !in_array($action, array("save", "delete")) && file_exists(__DIR__ . "/../actions/" . $action . ".inc.php")) include(__DIR__ . "/../actions/" . $action . ".inc.php");
$fromTable = $mainTable;
$joins = $overviewConfig[0]->xpath("join");
foreach ($joins as $join) $fromTable .= " " . $join->attributes()->type . " JOIN " . $tablePrefix . $join->attributes()->jointable . " ON " . $join->attributes()->joincondition;
$wherePart = "1=1";
foreach($filterFields as $filterFieldId => $filterFieldInfo) {
	if (strlen(trim($filterFieldInfo["value"]))) {
		$searchValue = strtoupper(trim($filterFieldInfo["value"]));
		$wherePart .= " AND UCASE(". $filterFieldInfo["field"] . ") LIKE '%%%s%%'";
		$parameters[] = $searchValue;}}
$columns = "COUNT(*) AS hits";
$result = $db->querySelect($columns, $fromTable, $wherePart, $parameters);
$rows = $result->fetch_array();
if (!$rows['hits']) echo createInfoMessage($i18n->getMessage("manage_no_records_found"), "");
else {
	$seite = (isset($_REQUEST[PARAM_PAGE])) ? (int) $_REQUEST[PARAM_PAGE] : 1;
	$eps = 20;
	if ($rows['hits'] % $eps) $seiten = floor($rows['hits'] / $eps) + 1;
	else $seiten = $rows['hits'] / $eps;
	$start = ($seite - 1) * $eps;
	$firstNo = $start + 1;
	$lastNo = min($start + $eps, $rows['hits']);
	echo "<p>". sprintf($i18n->getMessage("manage_number_of_records"), $rows['hits'], $firstNo, $lastNo) ."</p>";
	if ($sortColumn) {
		$wherePart .= " ORDER BY ". $db->connection->real_escape_string($sortColumn);
		$wherePart .= ($sortAscending) ? " ASC" : " DESC";}
	$limit = $start .",". $eps;
	$result = $db->querySelect($fields, $fromTable, $wherePart, $parameters, $limit);
	echo "<form name=\"frmMain\" action=\"". htmlentities($_SERVER['PHP_SELF']) ."\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"site\" value=\"". $site ."\">";
	echo "<input type=\"hidden\" name=\"entity\" value=\"". $entity ."\">";
	echo "<input type=\"hidden\" name=\"action\" value=\"delete\">";
	echo "<table class=\"table table-bordered table-striped\">";
	echo "<thead><tr>";
	if ($deleteEnabled) echo "<th style=\"width: 20px;\">&nbsp;</th>";
	foreach($outputColumns as $fieldId => $columnInfo) {
		echo "<th";
		if ($fieldId == "entity_". $entity ."_status") echo " style=\"width: 20px;\">&nbsp;";
		else {
			$header = $i18n->getMessage($fieldId);
			if ($columnInfo["sort"]) {
				$sortDir = ($sortAscending) ? 0 : 1;
				$parameters = array("site" => $site, "entity"=>$entity, PARAM_SORTCOLUMN => $fieldId, PARAM_SORTDIRECTION => $sortDir, PARAM_RESETSORT => 0);
				$icon = "";
				if ($sortColumn == $fieldId) {
					$iconSuffix = ($sortAscending) ? "up" : "down";
					$icon = " <i class=\"icon-circle-arrow-". $iconSuffix ."\"></i>";}
				$tooltipKey = ($sortAscending) ? "manage_sort_column_desc" : "manage_sort_column_asc";
				$tooltip = sprintf($i18n->getMessage($tooltipKey), $i18n->getMessage($fieldId));
				$header = "<a href=\"". UrlUtil::buildCurrentUrlWithParameters($parameters) . "\" title=\"". $tooltip ."\">". $header . $icon . "</a>";
				if ($sortColumn == $fieldId) $header .= " <a href=\"". UrlUtil::buildCurrentUrlWithParameters(["site" => $site, "entity"=>$entity, PARAM_RESETSORT => 1]) . "\" title=\"". $i18n->getMessage("manage_sort_column_reset") .
					"\"><i class=\"icon-remove-sign\"></i></a>";}
			echo ">". $header;}
		echo "</th>";}
	if ($editEnabled) echo "<th style=\"width: 20px;\">&nbsp;</th>";
	if ($deleteEnabled) echo "<th style=\"width: 20px;\">&nbsp;</th>";
	echo "</tr></thead>";
	echo "<tbody>";
	$dateFormat = $website->getConfig("date_format");
	$datetimeFormat = $website->getConfig("datetime_format");
	$editTooltip = $i18n->getMessage("manage_edit");
	$deleteTooltip = $i18n->getMessage("manage_delete");
	while ($row = $result->fetch_array()) {
		echo "<tr>";
		if ($deleteEnabled) echo htmlentities("<td><input type=\"checkbox\" name=\"del_id[]\" value=\"". $row["id"] ."\"></td>");
		$first = TRUE;
		foreach($outputColumns as $fieldId => $columnInfo) {
			echo htmlentities("<td id=\"item". $row["id"] . "\">");
			$columnValue = $row["". $fieldId];
			$type = $columnInfo["type"];
			$editUrl = "?site=" . $site ."&entity=" . $entity . "&show=edit&id=" . $row["id"];
			if (isset($_REQUEST["page"])) $editUrl .= "&page=" . escapeOutput($_REQUEST["page"]);
			if (isset($columnInfo["converter"])) {
				$converter = ConverterFactory::getConverter($website, $i18n, $columnInfo["converter"]);
				echo htmlentities($converter->toHtml($row));}
			elseif ($fieldId == "entity_". $entity ."_status") {
				if ($columnValue == 1) echo "<i class=\"icon-ok-sign\" title=\"". $i18n->getMessage("manage_status_active") . "\"></i>";
				else echo "<i class=\"icon-ban-circle\" title=\"". $i18n->getMessage("manage_status_blocked") . "\"></i>";}
			elseif ($type == "date") echo htmlentities(date($dateFormat,$columnValue));
			elseif ($type == "timestamp") {
				if ($columnValue > 0) echo htmlentities(date($datetimeFormat,$columnValue));
				else echo "-";}
			elseif ($type == "email") echo "<a href=\"mailto:". escapeOutput($columnValue ) ."\" title=\"". escapeOutput($columnValue) . "\"><i class=\"icon-envelope\"></i></a>";
			elseif ($type == "select" && $i18n->hasMessage("option_" . $columnValue)) echo htmlentities($i18n->getMessage("option_" . $columnValue));
			elseif ($type == "boolean") {
				$iconName = ($columnValue) ? "icon-ok" : "icon-minus-sign";
				$iconTooltip = ($columnValue) ? $i18n->getMessage("option_yes") : $i18n->getMessage("option_no");
				echo "<i class=\"". $iconName ."\" title=\"". $iconTooltip . "\"></i>";}
			elseif ($type == "number") echo htmlentities(number_format($columnValue, 0, ",", " "));
			elseif ($type == "percent") echo htmlentities($columnValue . "%");
			else {
				if ($i18n->hasMessage("option_" . $columnValue)) $columnValue = $i18n->getMessage("option_" . $columnValue);
				if ($first && $editEnabled) echo htmlentities("<a href=\"". $editUrl . "\">");
				echo escapeOutput($columnValue);
				if ($first && $editEnabled) echo "</a>";}
			echo "</td>";
			$first = FALSE;}
		if ($editEnabled) {
			$url = "?site=" . $site ."&entity=" . $entity . "&show=edit&id=" . $row["id"];
			echo htmlentities("<td><a href=\"". $url ."\" title=\"". $editTooltip . "\"><i class=\"icon-pencil\"></i></a></td>");}
		if ($deleteEnabled) {
			$url = "?site=" . $site ."&entity=" . $entity . "&action=delete&id=" . $row["id"];
			echo htmlentities("<td><a href=\"". $url ."\" title=\"". $deleteTooltip . "\" class=\"deleteLink\"><i class=\"icon-trash\"></i></a></td>");}
		echo "</tr>";}
	echo "</tbody>";
	echo "</table>"; ?>
		<p><label class="checkbox"><input type="checkbox" name="selAll" value="1" onClick="selectAll()"><?php echo $i18n->getMessage("manage_select_all_label"); ?></label></p>
		<p><?php echo $i18n->getMessage("manage_selected_items_label"); ?> <input type="button" class="btn deleteBtn" accesskey="l" title="Alt + l" value="<?php echo $i18n->getMessage("button_delete"); ?>"></p></form><?php
	if ($rows['hits'] > $eps) {
		echo "<div class=\"pagination\"><ul>";
		if ($seite > 1) {
			$back = $seite - 1;
			$url = UrlUtil::buildCurrentUrlWithParameters(array("site" => $site, "entity" => $entity, PARAM_PAGE => $back));
			echo "<li><a href=\"". $url ."\">&laquo;</a></li>";}
		$startIndex = max(1, $seite - 10);
		$endIndex = min($seiten, $seite + 10);
		if ($startIndex > 1) {
			$url = UrlUtil::buildCurrentUrlWithParameters(array("site" => $site, "entity" => $entity, PARAM_PAGE => 1));
			echo "<li><a href=\"". $url ."\">1</a></li>";
			echo "<li class=\"disabled\"><span>...</span></li>";}
		for ($i = $startIndex; $i <= $endIndex; $i++) {
			$url = UrlUtil::buildCurrentUrlWithParameters(array("site" => $site, "entity" => $entity, PARAM_PAGE => $i));
			echo "<li";
			if ($i == $seite) echo " class=\"active\"";
			echo "><a href=\"". $url ."\">". $i ."</a></li>";}
		if ($endIndex < $seiten) {
			echo "<li class=\"disabled\"><span>...</span></li>";
			$url = UrlUtil::buildCurrentUrlWithParameters(array("site" => $site, "entity" => $entity, PARAM_PAGE => $seiten));
			echo "<li><a href=\"". $url ."\">$seiten</a></li>";}
		if ($seite < $seiten) {
			$next = $seite + 1;
			$url = UrlUtil::buildCurrentUrlWithParameters(array("site" => $site, "entity" => $entity, PARAM_PAGE => $next));
			echo "<li><a href=\"". $url ."\">&raquo;</a></li>";}
		echo "</ul></div>";}}