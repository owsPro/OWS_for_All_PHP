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
$mainTitle = $i18n->getMessage("managecuprounds_navlabel");
$r_prefix = ""; #Prefix der Datei
echo "<h1>$mainTitle</h1>";
echo "<p><a href=\"?site=manage&entity=cup\" class=\"btn\">" . $i18n->getMessage("button_cancel") ."</a></p>";
if (!$admin["r_admin"] && !$admin["r_demo"] && !$admin["r_spiele"]) throw new Exception($i18n->getMessage("error_access_denied"));
$cupid = (isset($_REQUEST["cup"]) && is_numeric($_REQUEST["cup"])) ? $_REQUEST["cup"] : 0;
$result = $db->querySelect("name", $website->getConfig("db_prefix") . "_cup", "id = %d", $cupid);
$cup = $result->fetch_array();
if (!isset($cup["name"])) throw new Exception("illegal cup id"); ?>
<div class="alert alert-info">
	<h5><?php echo $i18n->getMessage("managecuprounds_infoalert_title"); ?></h5>
	<p><?php echo $i18n->getMessage("managecuprounds_infoalert_msg"); ?></p></div><?php
echo "<h2>". $i18n->getMessage("entity_cup") . ": " . escapeOutput($cup["name"]) . "</h2>";
$formFields = ["name" => ["type" => "text", "value" => "", "required" => "true"], "firstround_date" => ["type" => "timestamp", "value" => "", "required" => "true"], "create_secondround" => ["type" => "boolean", "value" => ""],
	"secondround_date" => ["type" => "timestamp", "value" => "", "required" => "false"], "groupmatches" => ["type" => "boolean", "value" => ""], "finalround" => ["type" => "boolean", "value" => ""]];
if ($action == "create") {
	if ($admin["r_demo"]) throw new Exception($i18n->getMessage("validationerror_no_changes_as_demo"));
	try {
		$dates = array();
		foreach ($formFields as $fieldId => $fieldInfo) {
			if ($fieldInfo["type"] == "timestamp") {
				$dateObj = DateTime::createFromFormat($website->getConfig("date_format") .", H:i", $_POST[$fieldId ."_date"] .", ". $_POST[$fieldId ."_time"]);
				$fieldValue = ($dateObj) ? $dateObj->getTimestamp() : 0;
				$dates[$fieldId] = $fieldValue;}
			else $fieldValue = (isset($_POST[$fieldId])) ? $_POST[$fieldId] : "";
			FormBuilder::validateField($i18n, $fieldId, $fieldInfo, $fieldValue, "managecuprounds_label_");}
		$columns = ["cup_id" => $cupid, "name" => $_POST["name"], "finalround" => (isset($_POST["finalround"]) && $_POST["finalround"] == "1") ? 1 : 0, "groupmatches" => (isset($_POST["groupmatches"]) && $_POST["groupmatches"] == "1") ? 1 : 0,
			"firstround_date" => $dates["firstround_date"]];
		if (isset($_POST["create_secondround"]) && $_POST["create_secondround"] == "1") $columns["secondround_date"] = $dates["secondround_date"];
		if (isset($_POST["round_generation"]) && isset($_POST["from_round_id"])) {
			if ($_POST["round_generation"] == "winners_from") $columns["from_winners_round_id"] = $_POST["from_round_id"];
			elseif ($_POST["round_generation"] == "loosers_from") $columns["from_loosers_round_id"] = $_POST["from_round_id"];}
		$db->queryInsert($columns, $website->getConfig("db_prefix") . "_cup_round");}
	catch (Exception $e) {echo createErrorMessage($i18n->getMessage("subpage_error_alertbox_title") , $e->getMessage());}}
elseif ($action == "delete") {
	if ($admin["r_demo"]) throw new Exception($i18n->getMessage("validationerror_no_changes_as_demo"));
	$db->queryDelete($website->getConfig("db_prefix") . "_cup_round", "id = %d", $_GET["id"]);
	echo createSuccessMessage($i18n->getMessage("manage_success_delete"), "");}
$result = $db->querySelect("*", $website->getConfig("db_prefix") . "_cup_round", "cup_id = %d ORDER BY firstround_date DESC", $cupid);
$hierarchy = array();
while ($round = $result->fetch_array()) {
	$hierarchy[$round["id"]]["round"] = $round;
	$isRoot = TRUE;
	if ($round["from_winners_round_id"] > 0) {
		$hierarchy[$round["from_winners_round_id"]]["winnerround"] = $round["id"];
		$isRoot = FALSE;}
	if ($round["from_loosers_round_id"] > 0) {
		$hierarchy[$round["from_loosers_round_id"]]["looserround"] = $round["id"];
		$isRoot = FALSE;}
	if ($isRoot) $rootIds[] = $round["id"];}
if (isset($rootIds)) {
	echo "<div id=\"rounds\">";
	foreach ($rootIds as $rootId) renderRound($hierarchy[$rootId]);
	echo "</div>";}
function renderRound($roundNode) {
	global $i18n;
	global $website;
	global $hierarchy;
	global $site;
	global $cupid;
	global $cup;
	global $action;
	global $db;
	echo "<div class=\"cupround\">";
	$showEditForm = FALSE;
	if($action == "edit" && $_REQUEST["id"] == $roundNode["round"]["id"]) $showEditForm = TRUE;
	elseif($action == "edit-save" && $_REQUEST["id"] == $roundNode["round"]["id"]) {
		if (isset($admin["r_demo"]) && $admin["r_demo"]) throw new Exception($i18n->getMessage("validationerror_no_changes_as_demo"));
		$showEditForm = TRUE;
		$columns = ["name" => $_POST["name"], "finalround" => (isset($_POST["finalround"]) && $_POST["finalround"] == "1") ? 1 : 0, "groupmatches" => (isset($_POST["groupmatches"]) && $_POST["groupmatches"] == "1") ? 1 : 0];
		$firstDateObj = DateTime::createFromFormat($website->getConfig("date_format") .", H:i", $_POST["firstround_date_date"] .", ". $_POST["firstround_date_time"]);
		$columns["firstround_date"] = $firstDateObj->getTimestamp();
		if (isset($_POST["secondround_date_date"])) {
			$secondDateObj = DateTime::createFromFormat($website->getConfig("date_format") .", H:i", $_POST["secondround_date_date"] .", ". $_POST["secondround_date_time"]);
			$columns["secondround_date"] = $secondDateObj->getTimestamp();}
		$db->queryUpdate($columns, $website->getConfig("db_prefix") . "_cup_round", "id = %d", $roundNode["round"]["id"]);
		if ($roundNode["round"]["name"] !== $_POST["name"]) $db->queryUpdate(["pokalrunde" => $_POST["name"]], $website->getConfig("db_prefix") . "_spiel", "pokalname = '%s' AND pokalrunde = '%s'",[$cup["name"], $roundNode["round"]["name"]]);
		$result = $db->querySelect("*", $website->getConfig("db_prefix") . "_cup_round", "id = %d", $roundNode["round"]["id"]);
		$roundNode["round"] = $result->fetch_array();
		$showEditForm = FALSE;}
	if($showEditForm) { ?>
		  <form action="<?php echo hmtlspezialchar($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" method="post" class="form-horizontal">
	    <input type="hidden" name="action" value="edit-save">
		<input type="hidden" name="site" value="<?php echo $site; ?>">
		<input type="hidden" name="cup" value="<?php echo hmtlspezialchar($cupid); ?>">
		<input type="hidden" name="id" value="<?php echo $roundNode["round"]["id"]; ?>"><?php
		$formFields = ["name" => ["type" => "text", "value" => $roundNode["round"]["name"], "required" => "true"], "firstround_date" => ["type" => "timestamp", "value" => $roundNode["round"]["firstround_date"], "required" => "true"]];
		if ($roundNode["round"]["secondround_date"]) $formFields["secondround_date"] = array("type" => "timestamp", "value" => $roundNode["round"]["secondround_date"], "required" => "false");
		$formFields = ["finalround" => ["type" => "boolean", "value" => $roundNode["round"]["finalround"]], "groupmatches" => ["type" => "boolean", "value" => $roundNode["round"]["groupmatches"]]];
		foreach ($formFields as $fieldId => $fieldInfo) echo FormBuilder::createFormGroup($i18n, $fieldId, $fieldInfo, $fieldInfo["value"], "managecuprounds_label_"); ?>
		<div class="control-group">
			<div class="controls">
				<input type="submit" class="btn btn-primary" accesskey="s" title="Alt + s" value="<?php echo $i18n->getMessage("button_save"); ?>">
				<a href="<?php echo hmtlspezialchar("?site=" . $site . "&cup=" . $cupid); ?>" class="btn"><?php echo $i18n->getMessage("button_cancel"); ?></a></div></div></form><?php }
	else {
		echo "<p><strong>";
		if ($roundNode["round"]["finalround"] == "1") echo "<em>";
		echo escapeOutput($roundNode["round"]["name"]);
		if ($roundNode["round"]["finalround"] == "1") echo "</em>";
		echo "</strong>";
		echo hmtlspezialchar(" <a href=\"?site=". $site . "&cup=". $cupid . "&action=edit&id=". $roundNode["round"]["id"] . "\" title=\"". $i18n->getMessage("manage_edit") . "\"><i class=\"icon-pencil\"></i></a>");
		echo hmtlspezialchar(" <a class=\"deleteLink\" href=\"?site=". $site . "&cup=". $cupid . "&action=delete&id=". $roundNode["round"]["id"] . "\" title=\"". $i18n->getMessage("manage_delete") . "\"><i class=\"icon-trash\"></i></a>");
		echo "</p>";
		echo "<ul>";
		echo "<li><em>" . $i18n->getMessage("managecuprounds_label_firstround_date")  . ":</em> ". date($website->getFormattedDatetime($roundNode["round"]["firstround_date"])) . "</li>";
		if ($roundNode["round"]["secondround_date"]) echo "<li><em>" . $i18n->getMessage("managecuprounds_label_secondround_date")  . ":</em> ". date($website->getFormattedDatetime($roundNode["round"]["secondround_date"])) . "</li>";
		$matchesUrl = "?site=manage&entity=match&" . http_build_query(array(
				"entity_match_pokalname" => escapeOutput($cup["name"]),
				"entity_match_pokalrunde" => escapeOutput($roundNode["round"]["name"])));
		echo "<li><a href=\"$matchesUrl\">". $i18n->getMessage("managecuprounds_show_matches") . "</a></li>";
		echo "</ul>";
		$addMatchUrl = "?site=manage&entity=match&show=add&" . http_build_query(array(
				"pokalname" => escapeOutput($cup["name"]),
				"pokalrunde" => escapeOutput($roundNode["round"]["name"]),
				"spieltyp" => "Pokalspiel"));
		if (!$roundNode["round"]["groupmatches"]) {
			echo "<p><a href=\"$addMatchUrl\" class=\"btn btn-mini\"><i class=\"icon-plus-sign\"></i> ". $i18n->getMessage("managecuprounds_add_match") . "</a>";
			echo " <a href=\"?site=managecuprounds-generate&round=". $roundNode["round"]["id"] . "\" class=\"btn btn-mini\"><i class=\"icon-random\"></i> ". $i18n->getMessage("managecuprounds_generate_matches") . "</a>";
			echo "</p>";}
		else {
			echo "<p><a href=\"?site=managecuprounds-groups&round=". $roundNode["round"]["id"] . "\" class=\"btn btn-mini\"><i class=\"icon-list\"></i> ". $i18n->getMessage("managecuprounds_manage_groups") . "</a>";
			echo "</p>";}
		if (isset($roundNode["winnerround"])) {
				echo "<p><em>". $i18n->getMessage("managecuprounds_next_round_winners") . ":</em></p>\n";
				renderRound($hierarchy[$roundNode["winnerround"]]);}
		if (isset($roundNode["looserround"])) {
				echo "<p><em>". $i18n->getMessage("managecuprounds_next_round_loosers") . ":</em></p>\n";
				renderRound($hierarchy[$roundNode["looserround"]]);}}
	echo "</div>";} ?>
  <form action="<?php echo hmtlspezialchar($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" method="post" class="form-horizontal">
    <input type="hidden" name="action" value="create">
	<input type="hidden" name="site" value="<?php echo $site; ?>">
	<input type="hidden" name="cup" value="<?php echo hmtlspezialchar($cupid); ?>">
	<fieldset>
    <legend><?php echo $i18n->getMessage("managecuprounds_label_create"); ?></legend><?php
	foreach ($formFields as $fieldId => $fieldInfo) echo FormBuilder::createFormGroup($i18n, $fieldId, $fieldInfo, $fieldInfo["value"], "managecuprounds_label_"); ?>
	<hr><?php
	echo FormBuilder::createFormGroup($i18n, "round_generation", ["type" => "select", "selection" => "self,winners_from,loosers_from,generate_from_groups", "value" => "", "required" => "true"], $fieldInfo["value"], "managecuprounds_label_"); ?>
	<div class="control-group">
		<label class="control-label" for="from_round_id"><?php echo $i18n->getMessage("managecuprounds_label_previous_round")?></label>
		<div class="controls">
			<select name="from_round_id" id="from_round_id">
				<option></option><?php
				foreach ($hierarchy as $roundId => $roundInfo) echo "<option value=\"". $roundId . "\">". escapeOutput($roundInfo["round"]["name"]) . "</option>\n"; ?></select></div></div></fieldset>
	<div class="form-actions">
		<input type="submit" class="btn btn-primary" accesskey="s" title="Alt + s" value="<?php echo $i18n->getMessage("button_save"); ?>"></div></form><?php
