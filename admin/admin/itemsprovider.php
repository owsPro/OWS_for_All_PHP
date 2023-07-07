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
define('MAX_ITEMS', 20);
include(__DIR__ .'/..' . '/admin/adminglobal.inc.php');
$dbTable = $_GET['dbtable'];
if(!strlen($dbTable) || preg_match('/^([a-zA-Z1-9_])+$/', $dbTable) == 0) throw new Exception('Illegal parameter: dbtable');
$labelColumns = $_GET['labelcolumns'];
if(!strlen($labelColumns) || preg_match('/^([a-zA-Z1-9_, ])+$/', $labelColumns) == 0) throw new Exception('Illegal parameter: labelcolumns');
$search = (isset($_GET['search'])) ? strtolower($_GET['search']) : '';
$itemId = (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) ? $_GET['itemid'] : 0;
$labels = explode(',', $labelColumns);
$whereCondition = '';
if ($itemId > 0) {
	$whereCondition = 'id = %d';
	$queryParameters = $_GET['itemid'];}
elseif (!strlen($search)) {
	$whereCondition = '1=1';
	$queryParameters = '';}
else {
	$first = TRUE;
	foreach ($labels as $labelColumn) {
		if (!$first) $whereCondition .= ' OR ';
		$first = FALSE;
		$whereCondition .= 'LOWER(' . $labelColumn . ') LIKE \'%%%s%%\'';
		$queryParameters[] = $search;}}
$whereCondition .= ' ORDER BY '. $labelColumns . ' ASC';
$result = $db->querySelect('id, ' . $labelColumns, $website->getConfig('db_prefix') . '_' . $dbTable, $whereCondition, $queryParameters, MAX_ITEMS);
while($item = $result->fetch_array()) {
	$label = '';
	$first = TRUE;
	foreach ($labels as $labelColumn) {
		if (!$first) $label .= ' - ';
		$first = FALSE;
		$label .= $item[trim($labelColumn)];}
	$items[] = array('id' => $item['id'], 'text' => $label);}
echo json_encode($items);
