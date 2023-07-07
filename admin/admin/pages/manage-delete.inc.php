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
if (isset($id) && $id) $del_id = array($id);
if ($admin["r_demo"]) throw new Exception($i18n->getMessage("error_access_denied"));
if (count($del_id)) {
	$dependencies = ModuleConfigHelper::findDependentEntities($dbTableWithoutPrefix);
	foreach ($del_id as $deleteId) {
		if ($loggingEnabled) {
			$result = $db->querySelect($loggingColumns, $dbTable, "id = %d", $deleteId);
			$item = $result->fetch_array(MYSQLI_ASSOC);
			$result->free();
			logAdminAction($website, LOG_TYPE_DELETE, $admin["name"], $entity, json_encode($item));}
		$db->queryDelete($dbTable, "id = %d", $deleteId);
		foreach ($dependencies as $dependency) {
			$fromTable = $website->getConfig("db_prefix") . "_" . $dependency["dbtable"];
			$whereCondition = $dependency["columnid"] . " = %d";
			if (strtolower($dependency["cascade"]) == "delete") $db->queryDelete($fromTable, $whereCondition, $deleteId);
			else $db->queryUpdate(array($dependency["columnid"] => 0), $fromTable, $whereCondition, $deleteId);}}
	echo createSuccessMessage($i18n->getMessage("manage_success_delete"), "");}
