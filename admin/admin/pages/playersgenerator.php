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
$mainTitle = $i18n->getMessage("playersgenerator_navlabel");
echo "<h1>$mainTitle</h1>";
if (!$admin["r_admin"] && !$admin["r_demo"] && !$admin[$page["permissionrole"]]) throw new Exception($i18n->getMessage("error_access_denied"));
$leagueid = (isset($_REQUEST["leagueid"])) ? $_REQUEST["leagueid"] : 0;
$teamid = (isset($_REQUEST["teamid"])) ? $_REQUEST["teamid"] : 0;
if (!$show) { ?>
    <form class="form-inline">
  		<label for="leagueid"><?php echo $i18n->getMessage("generator_label_league") ?></label>
  		<select name="leagueid" id="leagueid">
  			<option></option><?php
  			$result = $db->querySelect("id,land,name", $website->getConfig("db_prefix") . "_liga", "1 ORDER BY land ASC, name ASC", array());
  			while ($league = $result->fetch_array()) {
				echo "<option value=\"". $league["id"] . "\"";
				if ($leagueid == $league["id"]) echo " selected";
				echo ">". $league["land"] . " - " . $league["name"] . "</option>";}	?></select>
	  	<button type="submit" class="btn btn-primary"><?php echo $i18n->getMessage("button_display") ?></button>
	  	<a href="index.php?site=<?php echo $site ?>" class="btn"><?php echo $i18n->getMessage("button_reset") ?></a>
	  	<input type="hidden" name="site" value="<?php echo $site ?>" /></form>
	<p><a href="index.php?site=<?php echo $site ?>&show=generateform&transfermarket=1" class="btn"><?php echo $i18n->getMessage("playersgenerator_generator_for_transfermarket") ?></a></p><?php
  if ($leagueid > 0) {
	  $result = $db->querySelect(["T1.id" => "id", "T1.name" => "name", "(SELECT COUNT(*) FROM " . $conf['db_prefix'] . "_spieler AS S WHERE S.verein_id = T1.id)" => "playerscount"], $conf['db_prefix'] . "_verein AS T1", "T1.liga_id = %d ORDER BY T1.name ASC", $leagueid);
	  if (!$result->num_rows) echo "<p>" . $i18n->getMessage("playersgenerator_noteams") . "</p>";
	  else { ?>
	  	<p><a href="?site=<?php echo $site ?>&show=generateform&leagueid=<?php echo htmlentities($leagueid) ?>"
	  		class="btn"><?php echo $i18n->getMessage("playersgenerator_create_for_all_teams"); ?></a></p>
	  	<h4 style="margin-top:20px"><?php echo $i18n->getMessage("playersgenerator_create_for_single_teams"); ?></h4>
	    <table class="table table-striped">
	    	<thead>
	    		<tr>
	    			<th><?php echo $i18n->getMessage("entity_club_name"); ?></th>
	    			<th><?php echo $i18n->getMessage("playersgenerator_head_playerscount"); ?></th></tr></thead><tbody><?php
	  		while ($team = $result->fetch_array()) {
	  			echo "<tr>";
	  			echo htmlentities("<td><a href=\"?site=". $site . "&show=generateform&teamid=". $team["id"] . "\">". $team["name"] . "</a></td>");
	  			echo htmlentities("<td>". $team["playerscount"] . "</td>");
	  			echo "</tr>";} ?></tbody></table><?php }}}
elseif ($show == "generateform") { ?>
  <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" class="form-horizontal">
    <input type="hidden" name="show" value="generate">
	<input type="hidden" name="site" value="<?php echo $site; ?>">
	<input type="hidden" name="teamid" value="<?php echo htmlentities($teamid); ?>">
	<input type="hidden" name="leagueid" value="<?php echo htmlentities($leagueid); ?>">
	<fieldset>
    <legend><?php echo $i18n->getMessage("generator_label"); ?></legend><?php
	$formFields = array();
	if (isset($_REQUEST["transfermarket"]) && $_REQUEST["transfermarket"]) $formFields = ["entity_player_nation" => ["type" => "text", "value" => ""],"player_age" => ["type" => "number", "value" => 25], "player_age_deviation" => ["type" => "number", "value" => 3],
		"entity_player_vertrag_gehalt" => ["type" => "number", "value" => 10000], "entity_player_vertrag_spiele" => ["type" => "number", "value" => 60], "entity_player_w_staerke" => ["type" => "percent", "value" => 50], "entity_player_w_technik" => ["type" => "percent",
		"value" => 50], "entity_player_w_kondition" => ["type" => "percent", "value" => 70], "entity_player_w_frische" => ["type" => "percent", "value" => 80], "entity_player_w_zufriedenheit" => ["type" => "percent", "value" => 80],
		"playersgenerator_label_deviation" => ["type" => "percent", "value" => 10], "option_T" => ["type" => "number", "value" => 2], "option_LV" => ["type" => "number", "value" => 2], "option_IV" => ["type" => "number", "value" => 4],
		"option_RV" => ["type" => "number", "value" => 2], "option_LM" => ["type" => "number", "value" => 2], "option_DM" => ["type" => "number", "value" => 2], "option_ZM" => ["type" => "number", "value" => 1], "option_OM" => ["type" => "number", "value" => 2],
		"option_RM" => ["type" => "number", "value" => 2], "option_LS" => ["type" => "number", "value" => 1], "option_MS" => ["type" => "number", "value" => 2], "option_RS" => ["type" => "number", "value" => 1]];
	foreach ($formFields as $fieldId => $fieldInfo) echo FormBuilder::createFormGroup($i18n, $fieldId, $fieldInfo, $fieldInfo["value"], ""); ?></fieldset>
	<div class="form-actions">
		<input type="submit" class="btn btn-primary" accesskey="s" title="Alt + s" value="<?php echo $i18n->getMessage("generator_button"); ?>">
		<input type="reset" class="btn" value="<?php echo $i18n->getMessage("button_reset"); ?>"></div></form><?php }
elseif ($show == "generate") {
  if ($admin['r_demo']) $err[] = $i18n->getMessage("validationerror_no_changes_as_demo");
  if (isset($err)) include("validationerror.inc.php");
  else {
	$strengths = ["strength" => $_POST['entity_player_w_staerke'], "technique" => $_POST['entity_player_w_technik'], "stamina" => $_POST['entity_player_w_kondition'], "freshness" => $_POST['entity_player_w_frische'],
		"satisfaction" => $_POST['entity_player_w_zufriedenheit']];
	$positions = ["T" => $_POST["option_T"], "LV" => $_POST["option_LV"], "IV" => $_POST["option_IV"], "RV" => $_POST["option_RV"], "LM" => $_POST["option_LM"], "ZM" => $_POST["option_ZM"], "RM" => $_POST["option_RM"], "DM" => $_POST["option_DM"],
		"OM" => $_POST["option_OM"], "LS" => $_POST["option_LS"], "MS" => $_POST["option_MS"], "RS" => $_POST["option_RS"]];
	if ($teamid > 0) DataGeneratorService::generatePlayers($website, $db, $teamid, $_POST['player_age'], $_POST['player_age_deviation'], $_POST['entity_player_vertrag_gehalt'], $_POST['entity_player_vertrag_spiele'], $strengths, $positions,
		$_POST["playersgenerator_label_deviation"]);
	elseif ($leagueid > 0) {
		$result = $db->querySelect("id", $conf['db_prefix'] . "_verein", "liga_id = %d", $leagueid);
		while ($team = $result->fetch_array()) DataGeneratorService::generatePlayers($website, $db, $team["id"], $_POST['player_age'], $_POST['player_age_deviation'], $_POST['entity_player_vertrag_gehalt'], $_POST['entity_player_vertrag_spiele'], $strengths, $positions,
			$_POST["playersgenerator_label_deviation"]);}
	else DataGeneratorService::generatePlayers($website, $db, 0, $_POST['player_age'], $_POST['player_age_deviation'], $_POST['entity_player_vertrag_gehalt'], $_POST['entity_player_vertrag_spiele'], $strengths, $positions, $_POST["playersgenerator_label_deviation"],
		$_POST['entity_player_nation']);
	echo createSuccessMessage($i18n->getMessage("generator_success"), "");
    echo htmlentities("<p>&raquo; <a href=\"?site=". $site ."&leagueid=". $leagueid . "\">". $i18n->getMessage("back_label") . "</a></p>\n");}}
