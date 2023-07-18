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
$entity = strtolower(trim($_REQUEST["entity"]));
if (!isset($adminpage[$entity]))throw new Exception("Illegal call - unknown entity");
$page = json_decode($adminpage[$entity], true);
if (!$admin["r_admin"] && !$admin["r_demo"] && !$admin[$page["permissionrole"]])throw new Exception($i18n->getMessage("error_access_denied"));
$configfile = BASE_FOLDER . '/admin/modules/'.$page["module"] ."/". MODULE_CONFIG_FILENAME;
if (!file_exists($configfile))throw new Exception("File does not exist: " . $configfile);
$xml = simplexml_load_file($configfile);
$entityConfig = $xml->xpath("//adminpage[@id = '". $entity . "']/entity[1]");
if (!$entityConfig)throw new Exception("No entity config found.");
$overviewConfig = $entityConfig[0]->xpath("overview[1]");
if (!$overviewConfig)throw new Exception("No overview config found.");
$loggingEnabled = (boolean) $overviewConfig[0]->attributes()->logging;
if ($loggingEnabled) {
	$loggingColumns = (string) $overviewConfig[0]->attributes()->loggingcolumns;
	if (!strlen($loggingColumns))throw new Exception($i18n->getMessage("entitylogging_nologgingcolumns"));}
if (isset($_REQUEST['id'])) $id = (int) $_REQUEST['id'];
echo "<h1>". $i18n->getMessage("entity_". $entity)  ."</h1>";
$tablePrefix =Config("db_prefix") ."_";
$mainTable = $tablePrefix . $entityConfig[0]->attributes()->dbtable;
$spaceTablePos = strrpos($mainTable, " ");
$mainTableAlias = ($spaceTablePos) ? substr($mainTable, $spaceTablePos) . "." : "";
$dbTableWithoutPrefix=ModuleConfigHelper::removeAliasFromDbTableName($entityConfig[0]->attributes()->dbtable);
$dbTable=$tablePrefix.$dbTableWithoutPrefix;
$showOverview=TRUE;
if($show=='add'||$show=='edit'){
	$showOverview=FALSE;$enableFileUpload=FALSE;$fields=$entityConfig[0]->xpath('editform/field');$formFields=[];foreach($fields as$field){$attrs=$field->attributes();if($show=='add'&&(boolean)$attrs['editonly'])continue;$roles=(string)$attrs['roles'];
	if(strlen($roles)&&(!isset($admin['r_admin'])||!$admin['r_admin'])){$rolesArr=explode(',',$roles);$hasRole=FALSE;foreach($rolesArr as$requiredRole){if(isset($admin[$requiredRole])&&$admin[$requiredRole]){$hasRole=TRUE;break;}}if($hasRole===FALSE)continue;}
	$fieldId=(string)$attrs['id'];$fieldInfo=[];$fieldInfo['type']=(string)$attrs['type'];$fieldInfo['required']=($attrs['required']=='true'&&!($show=='edit'&&$fieldInfo['type']=='password'));$fieldInfo['readonly']=(boolean)$attrs['readonly'];
	$fieldInfo['jointable']=(string)$attrs['jointable'];$fieldInfo['entity']=(string)$attrs['entity'];$fieldInfo['labelcolumns']=(string)$attrs['labelcolumns'];$fieldInfo['selection']=(string)$attrs['selection'];$fieldInfo['converter']=(string)$attrs['converter'];
	$fieldInfo['validator']=(string)$attrs['validator'];$fieldInfo['default']=(string)$attrs['default'];if($fieldInfo['type']=='file')$enableFileUpload=TRUE;$formFields[$fieldId]=$fieldInfo;}$labelPrefix='entity_'.$entity.'_';
if($action=='save'){
	try{if($admin['r_demo'])throw new Exception($i18n->getMessage('validationerror_no_changes_as_demo'));$dbcolumns=[];foreach($formFields as$fieldId=>$fieldInfo){if($fieldInfo['readonly'])continue;if($fieldInfo['type']=='timestamp'){
	$dateObj=DateTime::createFromFormat(Config('date_format').', H:i',$_POST[$fieldId.'_date'].', '.$_POST[$fieldId.'_time']);$fieldValue=($dateObj)?$dateObj->getTimestamp():0;}elseif($fieldInfo['type']=='boolean')$fieldValue=(isset($_POST[$fieldId]))?'1':'0';
				else $fieldValue = (isset($_POST[$fieldId])) ? $_POST[$fieldId] : "";
				FormBuilder::validateField($i18n, $fieldId, $fieldInfo, $fieldValue, $labelPrefix);
				if (strlen($fieldInfo["converter"])) {
					$converter = new $fieldInfo["converter"]($i18n, $website);
					$fieldValue = $converter->toDbValue($fieldValue);}
				if (strlen($fieldValue) && $fieldInfo["type"] == "date") {
					$dateObj = DateTime::createFromFormat(Config("date_format"), $fieldValue);
					$fieldValue = $dateObj->format("Y-m-d");}
				 elseif ($fieldInfo["type"] == "timestamp" && $fieldInfo["readonly"] && $show == "add")$fieldValue = $website->getNowAsTimestamp();
				 else if ($fieldInfo["type"] == "file") {
					if (isset($_FILES[$fieldId]) && isset($_FILES[$fieldId]["tmp_name"]) && strlen($_FILES[$fieldId]["tmp_name"])) {
						$fieldValue = md5($entity . "-". $website->getNowAsTimestamp());
						$fieldValue .= "." . FileUploadHelper::uploadImageFile($i18n, $fieldId, $fieldValue, $entity);}
					else continue;}
				if (!$fieldInfo["readonly"] or $fieldInfo["readonly"] && $fieldInfo["type"] == "timestamp"  && $show == "add")$dbcolumns[$fieldId] = $fieldValue;}
			if ($show == "add")$db->queryInsert($dbcolumns, $dbTable);
			else {
				$whereCondition = "id = %d";
				$parameter = $id;
				$db->queryUpdate($dbcolumns, $dbTable, $whereCondition, $parameter);
				if ($loggingEnabled) {
					$result = $db->querySelect($loggingColumns, $dbTable, $whereCondition, $parameter);
					$item = $result->fetch_array(MYSQLI_ASSOC);
					$result->free();
					logAdminAction($website, LOG_TYPE_EDIT, $admin["name"], $entity, json_encode($item));}}
			echo createSuccessMessage($i18n->getMessage("alert_save_success"), "");
			$showOverview = TRUE;}
		catch (Exception $e) {echo createErrorMessage($i18n->getMessage("subpage_error_alertbox_title"), $e->getMessage());}}
}
if($show=='add'){
	if(!$showOverview){ ?><form action='<?php echo$_SERVER['PHP_SELF'];?>'method='post'class='form-horizontal'<?php if($enableFileUpload)echo'enctype=\'multipart/form-data\'';?>><input type='hidden'name='show'value='<?php echo$show;?>'>
<input type='hidden'name='entity'value='<?php echo$entity;?>'><input type='hidden'name='action'value='save'><input type='hidden'name='site"value='<?php echo$site;?>'><fieldset><legend><?php echo$i18n->getMessage('manage_add_title');?></legend><?php
foreach($formFields as$fieldId=>$fieldInfo){$fieldValue=null;if(isset($_REQUEST[$fieldId]))$fieldValue=$_REQUEST[$fieldId];elseif(!count($_POST)&&strlen($fieldInfo['default']))$fieldValue=$fieldInfo['default'];
echo FormBuilder::createFormGroup($i18n,$fieldId,$fieldInfo,$fieldValue,$labelPrefix);}?></fieldset><div class='form-actions'><input type='submit'class='btn btn-primary'accesskey='s'title='Alt + s'value='<?php echo$i18n->getMessage('button_save');?>'>
<a class='btn'href='?site=<?php echo$site;?>&entity=<?php echo $entity;?>'><?php echo$i18n->getMessage('button_cancel');?></a></div></form><?php }
}
elseif($show=="edit"){
	if(!$showOverview){$columns='*';$whereCondition='id=%d';$result=$db->querySelect($columns,$dbTable,$whereCondition,$id,1);$row=$result->fetch_array();if(!$row)throw new Exception('Invalid URL - Item does not exist.');$result->free();?>
<form action='<?php echo$_SERVER['PHP_SELF'];?>#item<?php echo$row['id'];?>'method='post'class='form-horizontal'<?php if($enableFileUpload)echo'enctype=\'multipart/form-data\'';?>><input type='hidden'name='show'value='<?php echo$show;?>'>
<input type='hidden'name='entity'value='<?php echo$entity;?>'><input type='hidden'name='action'value='save'><input type='hidden'name='id'value='<?php echo$id;?>'><input type='hidden'name='site'value='<?php echo$site;?>'><?php if(isset($_REQUEST['page'])){ ?>
<input type='hidden'name='page'value='<?php echo escapeOutput($_REQUEST['page']);?>'><?php }?><fieldset><legend><?php echo$i18n->getMessage('manage_edit_title');?></legend><?php foreach($formFields as$fieldId=>$fieldInfo){
$fieldValue=($action=='save'&&isset($_POST[$fieldId]))?$_POST[$fieldId]:$row[$fieldId];echo FormBuilder::createFormGroup($i18n,$fieldId,$fieldInfo,$fieldValue,$labelPrefix);}?></fieldset><div class='form-actions'>
<input type='submit'class='btn btn-primary'accesskey='s'title='Alt + s'value='<?php echo$i18n->getMessage('button_save');?>'><a class='btn'href='?site=<?php echo$site;?>&entity=<?php echo$entity;?>'><?php echo$i18n->getMessage('button_cancel');?></a></div></form><?php }
}
if($showOverview){
	include(__DIR__ . "/manage-overview.inc.php");
}