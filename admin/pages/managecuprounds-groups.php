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
$mainTitle = $i18n->getMessage("managecuprounds_groups_navlabel");
echo "<h1>$mainTitle</h1>";
if (!$admin["r_admin"] && !$admin["r_demo"] && !$admin["r_spiele"]) throw new Exception($i18n->getMessage("error_access_denied"));
$roundid = (isset($_REQUEST["round"]) && is_numeric($_REQUEST["round"])) ? $_REQUEST["round"] : 0;
$result = $db->querySelect("R.id AS round_id,R.name AS round_name,firstround_date,secondround_date,C.id AS cup_id,C.name as cup_name",$website->getConfig("db_prefix")."_cup_round AS R INNER JOIN ".$website->getConfig("db_prefix")."_cup AS C ON C.id = R.cup_id",
	"R.id = %d", $roundid);
$round = $result->fetch_array();
if (!isset($round["round_name"])) throw new Exception("illegal round id");
echo "<h2>". $i18n->getMessage("entity_cup") . " - " . escapeOutput($round["round_name"]) . "</h2>";
echo htmlentities("<p><a href=\"?site=managecuprounds&cup=". $round["cup_id"] . "\" class=\"btn\">" . $i18n->getMessage("managecuprounds_groups_back") ."</a></p>");
$result = $db->querySelect("T.id AS team_id,T.name AS team_name,L.name AS league_name,L.land AS league_country",$website->getConfig("db_prefix") . "_verein AS T INNER JOIN " . $website->getConfig("db_prefix")."_liga AS L ON L.id = T.liga_id","1=1 ORDER BY team_name ASC");
while ($team = $result->fetch_array()) $teams[] = $team;
$formFields["name"] = ["type" => "text", "value" => "", "required" => "true"];
$generateFormFields = ["firstmatchday" => ["type" => "timestamp", "value" => "", "required" => "true"], "rounds" => ["type" => "number", "value" => "1", "required" => "true"],
"timebreak" >= ["type" => "number", "value" => 5, "required" => "true"]];
$showEditForm = FALSE;
if ($action == "create") {
	if ($admin["r_demo"]) throw new Exception($i18n->getMessage("validationerror_no_changes_as_demo"));
	try{foreach ($formFields as $fieldId => $fieldInfo) {
			$fieldValue = (isset($_POST[$fieldId])) ? $_POST[$fieldId] : "";
			FormBuilder::validateField($i18n, $fieldId, $fieldInfo, $fieldValue, "managecuprounds_group_label_");}
		$teamIds = $_POST["teams"];
		$inserTable = $website->getConfig("db_prefix") . "_cup_round_group";
		foreach($teamIds as $teamId) {
			$columns = ["cup_round_id" => $roundid,
			"team_id" => $teamId, "name" => $_POST["name"]];
			$db->queryInsert($columns, $inserTable);}}
	catch (Exception $e) { echo createErrorMessage($i18n->getMessage("subpage_error_alertbox_title") , $e->getMessage());}
	echo createSuccessMessage($i18n->getMessage("alert_save_success"), "");}
elseif ($action == "delete") {
	if ($admin["r_demo"]) throw new Exception($i18n->getMessage("validationerror_no_changes_as_demo"));
	$db->queryDelete($website->getConfig("db_prefix") . "_cup_round_group", "cup_round_id = %d AND name = '%s'", array($roundid, $_GET["group"]));
	echo createSuccessMessage($i18n->getMessage("manage_success_delete"), "");}
elseif ($action == "edit") $showEditForm = TRUE;
elseif ($action == "editsave") {
	if ($admin["r_demo"]) throw new Exception($i18n->getMessage("validationerror_no_changes_as_demo"));
	if (isset($_REQUEST["groupname"]) && strlen(trim($_REQUEST["groupname"]))) {
		$columns = ["name" => $_REQUEST["groupname"]];
		$db->queryUpdate($columns, $website->getConfig("db_prefix") . "_cup_round_group", "cup_round_id = %d AND name = '%s'", [$roundid, $_REQUEST["group"]]);
		$db->queryUpdate(["groupname" => $_REQUEST["groupname"]], $website->getConfig("db_prefix") . "_cup_round_group_next", "cup_round_id = %d AND groupname = '%s'", [$roundid, $_REQUEST["group"]]);
		$db->queryUpdate(["pokalgruppe" => $_REQUEST["groupname"]], $website->getConfig("db_prefix") . "_spiel", "pokalname = '%s' AND pokalrunde = '%s' AND pokalgruppe = '%s'", [$round["cup_name"], $round["round_name"], $_REQUEST["group"]]);
		echo createSuccessMessage($i18n->getMessage("alert_save_success"), "");}}
elseif ($action == "deletegroupassignment") {
	if ($admin["r_demo"]) throw new Exception($i18n->getMessage("validationerror_no_changes_as_demo"));
	$db->queryDelete($website->getConfig("db_prefix") . "_cup_round_group", "cup_round_id = %d AND name = '%s' AND team_id = %d", array($roundid, $_GET["group"], $_GET["teamid"]));
	echo createSuccessMessage($i18n->getMessage("manage_success_delete"), "");}
elseif ($action == "addteam") {
	if ($admin["r_demo"]) throw new Exception($i18n->getMessage("validationerror_no_changes_as_demo"));
	$columns = ["cup_round_id" => $roundid, "team_id" => $_POST["teamid"], "name" => $_POST["group"]];
	$db->queryInsert($columns, $website->getConfig("db_prefix") . "_cup_round_group");
	echo createSuccessMessage($i18n->getMessage("alert_save_success"), "");}
elseif ($action == "saveranks") {
	if ($admin["r_demo"]) throw new Exception($i18n->getMessage("validationerror_no_changes_as_demo"));
	$groupName = $_REQUEST["group"];
	$dbTable = $website->getConfig("db_prefix") . "_cup_round_group_next";
	$result = $db->querySelect("*", $dbTable, "cup_round_id = %d AND groupname = '%s'", array($roundid, $groupName));
	while ($rank = $result->fetch_array()) $ranks["" . $rank["rank"]] = $rank;
	$result = $db->querySelect("COUNT(*) AS teams", $website->getConfig("db_prefix") . "_cup_round_group", "cup_round_id = %d AND name = '%s'", array($roundid, $groupName));
	$hits = $result->fetch_array();
	$noOfTeams = ($hits["teams"]) ? $hits["teams"] : 0;
	for ($groupRank = 1; $groupRank <= $noOfTeams; $groupRank++) {
		if (!isset($_REQUEST["rank_" . $groupRank]) || !$_REQUEST["rank_" . $groupRank]) if (isset($ranks["" . $groupRank])) $db->queryDelete($dbTable, "cup_round_id = %d AND groupname = '%s' AND rank = %d", array($roundid, $groupName, $groupRank));
		elseif($_REQUEST["rank_" . $groupRank]) {
			$columns = ["cup_round_id" => $roundid, "groupname" => $groupName, "rank" => $groupRank, "target_cup_round_id" => $_REQUEST["rank_" . $groupRank]];
			if (isset($ranks["" . $groupRank])) $db->queryUpdate($columns, $dbTable, "cup_round_id = %d AND groupname = '%s' AND rank = %d", array($roundid, $groupName, $groupRank));
			else $db->queryInsert($columns, $dbTable);}}
	echo createSuccessMessage($i18n->getMessage("alert_save_success"), "");}
$columns = "G.name AS group_name, C.name AS team_name, C.id AS team_id";
$fromTable = $website->getConfig("db_prefix") . "_cup_round_group AS G INNER JOIN " . $website->getConfig("db_prefix") . "_verein AS C ON C.id = G.team_id";
$whereCondition = "G.cup_round_id = %d ORDER BY G.name ASC, C.name ASC";
$result = $db->querySelect($columns, $fromTable, $whereCondition, $roundid);
while ($group = $result->fetch_array()) $groups[$group["group_name"]][] = $group;
if (count($groups)) {
	$result = $db->querySelect("*", $website->getConfig("db_prefix") . "_cup_round", "cup_id = %d AND id != %d ORDER BY firstround_date ASC", array($round["cup_id"], $round["round_id"]));
	while ($roundItem = $result->fetch_array()) $rounds[] = $roundItem;
	$result = $db->querySelect("*", $website->getConfig("db_prefix") . "_cup_round_group_next", "cup_round_id = %d", array($roundid));
	$rankConfigs = array();
	while ($rankConfig = $result->fetch_array()) $rankConfigs[$rankConfig["groupname"]][$rankConfig["rank"]] = $rankConfig["target_cup_round_id"] ?>
	<table class="table table-bordered">
		<colgroup>
			<col>
			<col>
			<col>
			<col style="width: 20px">
			<col style="width: 20px"></colgroup>
		<thead>
			<tr>
				<th><?php echo $i18n->getMessage("managecuprounds_group_label_name"); ?></th>
				<th><?php echo $i18n->getMessage("managecuprounds_group_label_teams"); ?></th>
				<th><?php echo $i18n->getMessage("managecuprounds_groups_nextrounds"); ?></th>
				<th>&nbsp;</th>
				<th>&nbsp;</th></tr></thead>
		<tbody><?php
		foreach($groups as $groupName => $groupItems) {
			echo "<tr>";
			echo "<td>";
			if ($showEditForm && $_REQUEST["group"] == $groupName) {
				$nameValue = (isset($_REQUEST["groupname"])) ? $_REQUEST["groupname"] : $groupName; ?>
				<form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" class="form-inline">
					<input type="hidden" name="action" value="editsave">
					<input type="hidden" name="site" value="<?php echo $site; ?>">
					<input type="hidden" name="round" value="<?php echo htmlentities($roundid); ?>">
					<input type="hidden" name="group" value="<?php echo escapeOutput($groupName); ?>">
					<input id="groupname" name="groupname" type="text" value="<?php echo escapeOutput($nameValue) ?>">
					<input type="submit" class="btn" value="<?php echo $i18n->getMessage("button_save"); ?>"></form><?php }
			else echo escapeOutput($groupName);
			echo "</td>";
			echo "<td><ul>";
			$noOfTeams = 0;
			foreach ($groupItems as $groupItem) {
				echo htmlentities("<li>" . escapeOutput($groupItem["team_name"]) . " <a class=\"deleteLink\" href=\"?site=". $site . "&round=". $roundid . "&group=". urlencode(escapeOutput($groupName)) . "&action=deletegroupassignment&teamid=". $groupItem["team_id"] .
					"\" title=\"" . $i18n->getMessage("manage_delete") . "\"><i class=\"icon-remove-sign\"></i></a></li>");
				$noOfTeams++;}
			echo "</ul>\n";?>
			<form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" class="form-inline">
				<input type="hidden" name="action" value="addteam">
				<input type="hidden" name="site" value="<?php echo $site; ?>">
				<input type="hidden" name="round" value="<?php echo htmlentities($roundid); ?>">
				<input type="hidden" name="group" value="<?php echo escapeOutput($groupName); ?>"><?php
				FormBuilder::createForeignKeyField($i18n, "teamid", array("entity" => "club", "jointable" => "verein", "labelcolumns" => "name"), ""); ?>
				<input type="submit" class="btn btn-small" value="<?php echo $i18n->getMessage("managecuprounds_groups_addteam"); ?>"></form><?php
			echo "</td>";
			echo "<td>"; ?>
			<form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" class="form-inline">
				<input type="hidden" name="action" value="saveranks">
				<input type="hidden" name="site" value="<?php echo $site; ?>">
				<input type="hidden" name="round" value="<?php echo htmlentities($roundid); ?>">
				<input type="hidden" name="group" value="<?php echo escapeOutput($groupName); ?>"><?php
			echo "<ol>";
			for ($rank = 1; $rank <= $noOfTeams; $rank++) {
				echo "<li><select name=\"rank_$rank\"><option></option>";
				foreach ($rounds as $roundItem) {
					echo "<option value=\"" . $roundItem["id"] . "\"";
					if (isset($rankConfigs[$groupName][$rank]) && $rankConfigs[$groupName][$rank] == $roundItem["id"]) echo " selected";
					echo ">". escapeOutput($roundItem["name"]) . "</option>";}
				echo "</select></li>\n";}
			echo "</ol>"; ?>
			<input type="submit" class="btn btn-small" value="<?php echo $i18n->getMessage("button_save"); ?>"></form><?php
			echo "</td>";
			echo htmlentities("<td><a href=\"?site=". $site . "&round=". $roundid . "&action=edit&group=". urlencode(escapeOutput($groupName)) . "\" title=\"". $i18n->getMessage("manage_edit") . "\"><i class=\"icon-pencil\"></i></a></td>");
			echo htmlentities("<td><a class=\"deleteLink\" href=\"?site=". $site . "&round=". $roundid . "&group=". urlencode(escapeOutput($groupName)) . "&action=delete\" title=\"". $i18n->getMessage("manage_delete") . "\"><i class=\"icon-trash\"></i></a></td>");
			echo "</tr>\n";} ?></tbody></table><?php
	if ($action == "generateschedule") {
		if ($admin["r_demo"]) throw new Exception($i18n->getMessage("validationerror_no_changes_as_demo"));
		$rounds = (int) $_POST['rounds'];
		$dateObj = DateTime::createFromFormat($website->getConfig("date_format") .", H:i", $_POST["firstmatchday_date"] .", ". $_POST["firstmatchday_time"]);
		$timeBreakSeconds = 3600 * 24 * $_POST['timebreak'];
		$dbTable = $website->getConfig("db_prefix") . "_spiel";
		foreach($groups as $groupName => $groupItems) {
			$db->queryDelete($dbTable, "pokalname = '%s' AND pokalrunde = '%s' AND pokalgruppe = '%s' AND berechnet = '0'", array($round["cup_name"], $round["round_name"], $groupName));
			foreach($groupItems as $groupItem) $teamIds[] = $groupItem["team_id"];
			$schedule = ScheduleGenerator::createRoundRobinSchedule($teamIds);
			$numberOfMatchDaysPerRound = count($schedule);
			for ($roundNo = 2; $roundNo <= $rounds; $roundNo++) {
				$startMatchday = count($schedule) + 1;
				$endMatchday = $startMatchday + $numberOfMatchDaysPerRound - 1;
				for ($matchday = $startMatchday; $matchday <= $endMatchday; $matchday++) {
					$originalMatchDay = $matchday - $numberOfMatchDaysPerRound;
					foreach ($schedule[$originalMatchDay] as $match) {
						$homeTeam = $match[1];
						$guestTeam = $match[0];
						$schedule[$matchday][] = array($homeTeam, $guestTeam);}}}
			$matchTimestamp = $dateObj->getTimestamp();
			foreach($schedule as $matchDay => $matches) {
				foreach ($matches as $match) {
					$homeTeam = $match[0];
					$guestTeam = $match[1];
					$matchcolumns = ["spieltyp" => "Pokalspiel", "pokalname" => $round["cup_name"], "pokalrunde" => $round["round_name"], "pokalgruppe" => $groupName, "home_verein" => $homeTeam, "gast_verein" => $guestTeam, "datum" => $matchTimestamp];
					$db->queryInsert($matchcolumns, $dbTable);}
				$matchTimestamp += $timeBreakSeconds;}}
		echo createSuccessMessage($i18n->getMessage("alert_save_success"), "");}
	$result = $db->querySelect("COUNT(*) AS hits", $website->getConfig("db_prefix") . "_spiel", "pokalname = '%s' AND pokalrunde = '%s'", array($round["cup_name"], $round["round_name"]));
	$matches = $result->fetch_array();
	$matchesUrl = "?site=manage&entity=match&" . http_build_query(array("entity_match_pokalname" => escapeOutput($round["cup_name"]), "entity_match_pokalrunde" => escapeOutput($round["round_name"]))); ?>
	<div class="well">
		<?php if (isset($matches["hits"]) && $matches["hits"]) { ?>
		<p><a href="<?php echo $matchesUrl; ?>"><strong><?php echo htmlentities($matches["hits"]); ?></strong> <?php echo $i18n->getMessage("managecuprounds_groups_created_matches"); ?></a></p>
		<?php } ?>
		<p><a href="#generateModal" role="button" class="btn" data-toggle="modal"><?php echo $i18n->getMessage("managecuprounds_groups_open_generate_matches_popup"); ?></a></p></div>
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" method="post" class="form-horizontal">
	    <input type="hidden" name="action" value="generateschedule">
		<input type="hidden" name="site" value="<?php echo $site; ?>">
		<input type="hidden" name="round" value="<?php echo htmlentities($roundid); ?>">
		<div id="generateModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="generateModalLabel" aria-hidden="true">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">??</button>
		    <h3 id="generateModalLabel"><?php echo $i18n->getMessage("managecuprounds_groups_generate_title"); ?></h3></div>
		  <div class="modal-body">
		  	<div class="alert">
		  		<?php echo $i18n->getMessage("managecuprounds_groups_generate_alert"); ?></div><?php
			foreach ($generateFormFields as $fieldId => $fieldInfo) echo FormBuilder::createFormGroup($i18n, $fieldId, $fieldInfo, $fieldInfo["value"], "schedulegenerator_label_"); ?></div>
		  <div class="modal-footer">
		    <button class="btn btn-primary" type="submit"><?php echo $i18n->getMessage("managecuprounds_groups_generate_submit"); ?></button>
		    <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo $i18n->getMessage("button_cancel"); ?></button></div></div></form><?php } ?>
  <form action="<?php echo htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" method="post" class="form-horizontal">
    <input type="hidden" name="action" value="create">
	<input type="hidden" name="site" value="<?php echo $site; ?>">
	<input type="hidden" name="round" value="<?php echo htmlentities($roundid); ?>">
	<fieldset>
    <legend><?php echo $i18n->getMessage("managecuprounds_groups_label_create"); ?></legend><?php
	foreach ($formFields as $fieldId => $fieldInfo) echo FormBuilder::createFormGroup($i18n, $fieldId, $fieldInfo, $fieldInfo["value"], "managecuprounds_group_label_"); ?>
	<div>
		<label><?php echo $i18n->getMessage("managecuprounds_groups_label_selectteams"); ?></label></div>
		<div style="width: 600px; height: 300px; overflow: auto; border: 1px solid #cccccc;">
			<table class="table table-striped table-hover">
				<colgroup>
					<col style="width: 30px">
					<col>
					<col></colgroup>
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th><?php echo $i18n->getMessage("entity_club")?></th>
						<th><?php echo $i18n->getMessage("entity_league")?></th></tr></thead>
				<tbody><?php
					foreach ($teams as $team) {
						echo "<tr>";
						echo "<td><input type=\"checkbox\" name=\"teams[]\" value=\"". $team["team_id"] . "\"></td>";
						echo "<td class=\"tableRowSelectionCell\">". escapeOutput($team["team_name"]) . "</td>";
						echo "<td class=\"tableRowSelectionCell\">". escapeOutput($team["league_name"] . " (" . $team["league_country"] . ")") . "</td>";
						echo "</tr>\n";} ?></tbody></table></div></fieldset>
	<div class="form-actions">
		<input type="submit" class="btn btn-primary" accesskey="s" title="Alt + s" value="<?php echo $i18n->getMessage("button_save"); ?>"><?php
	echo htmlentities("<a href=\"?site=managecuprounds&cup=". $round["cup_id"] . "\" class=\"btn\">" . $i18n->getMessage("button_cancel") ."</a>"); ?></div></form>
