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
echo "<h1>".  $i18n->getMessage("match_manage_playerstatistics") . "</h1>";
if (!$admin["r_admin"] && !$admin["r_demo"] && !$admin["r_spiele"]) throw new Exception($i18n->getMessage("error_access_denied"));
echo "<p><a href=\"?site=manage&entity=match\" class=\"btn\">". $i18n->getMessage("back_label") . "</a></p>";
$matchId = (isset($_REQUEST["match"]) && is_numeric($_REQUEST["match"])) ? $_REQUEST["match"] : 0;
$teamPrefixes = array("home", "guest");
$formFields = array("minuten_gespielt", "note", "tore", "assists", "karte_gelb", "karte_rot", "verletzt", "gesperrt", "ballcontacts", "wontackles", "shoots", "passes_successed", "passes_failed");
if ($action == "delete") {
	if ($admin["r_demo"]) throw new Exception($i18n->getMessage("validationerror_no_changes_as_demo"));
	$playerId = (int) $_REQUEST["player"];
	$db->queryDelete($website->getConfig("db_prefix") . "_spiel_berechnung", "spiel_id = %d AND spieler_id = %d", array($matchId, $playerId));
	echo createSuccessMessage($i18n->getMessage("manage_success_delete"), "");}
elseif ($action == "update") {
	if ($admin["r_demo"]) throw new Exception($i18n->getMessage("validationerror_no_changes_as_demo"));
	$updateTable = $website->getConfig("db_prefix") . "_spiel_berechnung";
	$matchUpdateTable = $website->getConfig("db_prefix") . "_spiel";
	foreach ($teamPrefixes as $teamPrefix) {
		if (!isset($_REQUEST[$teamPrefix . "_players"])) continue;
		$columnsPrefix = ($teamPrefix == "guest") ? "gast" : "home";
		$matchColumns = [$columnsPrefix . "_offensive" => (isset($_POST[$teamPrefix . "_offensive"])) ? $_POST[$teamPrefix . "_offensive"] : 0, $columnsPrefix . "_longpasses" => (isset($_POST[$teamPrefix . "_longpasses"])) ? $_POST[$teamPrefix . "_longpasses"] : 0,
			$columnsPrefix . "_counterattacks" => (isset($_POST[$teamPrefix . "_counterattacks"])) ? $_POST[$teamPrefix . "_counterattacks"] : 0];
		for ($subNo = 1; $subNo <= 3; $subNo++) {
			$matchColumns = [$columnsPrefix . "_w". $subNo . "_raus" => $_POST[$teamPrefix . "_sub". $subNo . "_out"], $columnsPrefix . "_w". $subNo . "_rein" => $_POST[$teamPrefix . "_sub". $subNo . "_in"],
				$columnsPrefix . "_w". $subNo . "_minute" => $_POST[$teamPrefix . "_sub". $subNo . "_minute"], $columnsPrefix . "_w". $subNo . "_condition" => $_POST[$teamPrefix . "_sub". $subNo . "_condition"]];}
		$db->queryUpdate($matchColumns, $matchUpdateTable, "id = %d", array($matchId));
		$playerIds = explode(";", $_REQUEST[$teamPrefix . "_players"]);
		foreach ($playerIds as $playerId) {
			$columns = ["position" => $_POST["pl" . $playerId . "_pos"], "feld" => $_POST["pl" . $playerId . "_feld"]];
			foreach ($formFields as $formField) $columns[$formField] = $_POST["pl" . $playerId . "_" . $formField];
			$db->queryUpdate($columns, $updateTable, "spiel_id = %d AND spieler_id = %d", array($matchId, $playerId));}}
	echo createSuccessMessage($i18n->getMessage("alert_save_success"), "");}
elseif ($action == "create") {
	if ($admin["r_demo"]) throw new Exception($i18n->getMessage("validationerror_no_changes_as_demo"));
	$teamId = (int) $_POST["team_id"];
	$playerId = (int) $_POST["playerid"];
	$position = $_POST["position"];
	if ($teamId && $playerId && strlen($position)) {
		$player = PlayersDataService::getPlayerById($website, $db, $playerId);
		$playerName = (strlen($player["player_pseudonym"])) ? $player["player_pseudonym"] : $player["player_firstname"] . " " . $player["player_lastname"];
		$db->queryInsert(["spiel_id" => $matchId, "spieler_id" => $playerId, "team_id" => $teamId, "position_main" => $position, "note" => 3.0, "name" => $playerName], $website->getConfig("db_prefix") . "_spiel_berechnung");}}
elseif ($action == "generate") {
	if ($admin["r_demo"]) throw new Exception($i18n->getMessage("validationerror_no_changes_as_demo"));
	$dummyVar = new DefaultSimulationStrategy($website);
	$match = MatchesDataService::getMatchById($website, $db, $matchId, FALSE, FALSE);
	$teamPrefix = (in_array($_GET["team"], array("home", "guest"))) ? $_GET["team"] : "home";
	$team = new SimulationTeam($match["match_". $teamPrefix ."_id"]);
	$team->isNationalTeam = $match["match_". $teamPrefix ."_nationalteam"];
	$formationcolumns = ["offensive" => "offensive", "longpasses" => "longpasses", "counterattacks" => "counterattacks", "setup" => $teamPrefix . "_formation_setup"];
	for ($playerNo = 1; $playerNo <= 11; $playerNo++) $formationcolumns["spieler" . $playerNo] = $teamPrefix . "_formation_player" . $playerNo;
	for ($playerNo = 1; $playerNo <= 5; $playerNo++) $formationcolumns["ersatz" . $playerNo] = $teamPrefix . "_formation_bench" . $playerNo;
	for ($subNo = 1; $subNo <= 3; $subNo++) {
		$formationcolumns = ["w" . $subNo . "_raus" => $teamPrefix . "_sub" . $subNo . "_out", "w" . $subNo . "_rein" => $teamPrefix . "_sub" . $subNo . "_in", "w" . $subNo . "_minute" => $teamPrefix . "_sub" . $subNo . "_minute",
			"w" . $subNo . "_condition" => $teamPrefix . "_sub" . $subNo . "_condition"];}
	$result = $db->querySelect($formationcolumns, $website->getConfig("db_prefix") . "_aufstellung", "verein_id = %d", $team->id, 1);
	$formation = $result->fetch_array();
	if (!$formation) throw new Exception($i18n->getMessage("match_manage_playerstatistics_noformationavailable"));
	$formation["match_id"] = $matchId;
	$formation["type"] = $match["match_type"];
	$columnsPrefix = ($teamPrefix == "guest") ? "gast" : "home";
	$matchColumns = [$columnsPrefix . "_offensive" => $formation["offensive"], $columnsPrefix . "_longpasses" => $formation["longpasses"], $columnsPrefix . "_counterattacks" => $formation["counterattacks"]];
	for ($subNo = 1; $subNo <= 3; $subNo++) {
		$matchColumns = [$columnsPrefix . "_w". $subNo . "_raus" => $formation[$teamPrefix . "_sub". $subNo . "_out"], $columnsPrefix . "_w". $subNo . "_rein" => $formation[$teamPrefix . "_sub". $subNo . "_in"],
			$columnsPrefix . "_w". $subNo . "_minute" => $formation[$teamPrefix . "_sub". $subNo . "_minute"], $columnsPrefix . "_w". $subNo . "_condition" => $formation[$teamPrefix . "_sub". $subNo . "_condition"]];}
	$db->queryUpdate($matchColumns, $website->getConfig("db_prefix") . "_spiel", "id = %d", array($matchId));
	MatchSimulationExecutor::addPlayers($website, $db, $team, $formation, $teamPrefix);}
$match = MatchesDataService::getMatchById($website, $db, $matchId, FALSE, FALSE);
if (!count($match)) throw new Exception("illegal match id");
$positions = array('T','LV','IV', 'RV', 'LM', 'DM', 'ZM', 'OM', 'RM', 'LS', 'MS', 'RS');
echo htmlentities("<form action=\"". $_SERVER['PHP_SELF'] . "\" class=\"form-horizontal\" method=\"post\">");
echo "<input type=\"hidden\" name=\"action\" value=\"create\">";
echo "<input type=\"hidden\" name=\"site\" value=\"$site\">";
echo htmlentities("<input type=\"hidden\" name=\"match\" value=\"$matchId\">");
echo "<fieldset><legend>". $i18n->getMessage("match_manage_createplayer_title") ."</legend>";
echo "<div class=\"control-group\">";
echo "<label class=\"control-label\" for=\"team_id\">". $i18n->getMessage("entity_player_verein_id") . "</label>";
echo "<div class=\"controls\">";
echo "<select name=\"team_id\" id=\"team_id\">";
echo htmlentities("<option value=\"". $match["match_home_id"] . "\">". escapeOutput($match["match_home_name"]) . "</option>");
echo htmlentities("<option value=\"". $match["match_guest_id"] . "\">". escapeOutput($match["match_guest_name"]) . "</option>");
echo "</select>";
echo "</div>";
echo "</div>";
echo FormBuilder::createFormGroup($i18n, "playerid", array("type" => "foreign_key", "jointable" => "spieler", "entity" => "player", "labelcolumns" => "vorname,nachname,kunstname"), "", "match_manage_createplayer_label_");
echo "<div class=\"control-group\">";
echo "<label class=\"control-label\" for=\"position\">". $i18n->getMessage("entity_player_position_main") . "</label>";
echo "<div class=\"controls\">";
echo "<select name=\"position\" id=\"position\">";
foreach ($positions as $position) echo htmlentities("<option value=\"". $position . "\">". $i18n->getMessage("option_" . $position). "</option>");
echo "</select>";
echo "</div>";
echo "</div>";
echo "</fieldset>";
echo "<div class=\"form-actions\">";
echo "<button type=\"submit\" class=\"btn btn-primary\">". $i18n->getMessage("button_save") . "</button>";
echo "</div></form>";
echo htmlentities("<form action=\"". $_SERVER['PHP_SELF'] . "\" method=\"post\">");
echo "<input type=\"hidden\" name=\"site\" value=\"$site\"/>";
echo "<input type=\"hidden\" name=\"action\" value=\"update\"/>";
echo htmlentities("<input type=\"hidden\" name=\"match\" value=\"$matchId\"/>");
foreach ($teamPrefixes as $teamPrefix) {
	echo htmlentities("<h2><a href=\"". $website->getInternalUrl("team", "id=" . $match["match_". $teamPrefix . "_id"]) . "\" target=\"_blank\">". escapeOutput($match["match_". $teamPrefix . "_name"]) . "</a></h2>");
	echo "<div class=\"form-horizontal\">";
	echo htmlentities(FormBuilder::createFormGroup($i18n, $teamPrefix . "_offensive", array("type" => "number", "value" => $match["match_". $teamPrefix . "_offensive"]), $match["match_". $teamPrefix . "_offensive"], "formation_"));
	echo htmlentities(FormBuilder::createFormGroup($i18n, $teamPrefix . "_longpasses", array("type" => "boolean", "value" => $match["match_". $teamPrefix . "_longpasses"]), $match["match_". $teamPrefix . "_longpasses"], "formation_"));
	echo htmlentities(FormBuilder::createFormGroup($i18n, $teamPrefix . "_counterattacks", array("type" => "boolean", "value" => $match["match_". $teamPrefix . "_counterattacks"]), $match["match_". $teamPrefix . "_counterattacks"], "formation_"));
	echo "</div>";
	$playerTable = $website->getConfig("db_prefix") . "_spiel_berechnung SB";
	$playerTable .= " INNER JOIN " . $website->getConfig("db_prefix") . "_spieler S ON S.id = SB.spieler_id";
	$result = $db->querySelect("SB.*", $playerTable, "spiel_id = %d AND team_id = %d ORDER BY feld ASC, field(SB.position_main, 'T', 'LV', 'IV', 'RV', 'DM', 'LM', 'ZM', 'RM', 'OM', 'LS', 'MS', 'RS')", array($matchId, $match["match_". $teamPrefix . "_id"]));
	$playersCount = $result->num_rows;
	if (!$playersCount) {
		echo createInfoMessage("", $i18n->getMessage("match_manage_playerstatistics_noitems"));
		$fresult = $db->querySelect("COUNT(*) AS hits", $website->getConfig("db_prefix") . "_aufstellung", "verein_id = %d", $match["match_". $teamPrefix . "_id"]);
		$formationCount = $fresult->fetch_array();
		if ($formationCount && $formationCount["hits"]) echo htmlentities("<p><a href=\"?site=$site&match=$matchId&team=$teamPrefix&action=generate\" class=\"btn\"><i class=\"icon-hand-right\"></i> ".
			$i18n->getMessage("match_manage_playerstatistics_createfromfrmation") . "</a></p>");
		else echo "<p><i class=\"icon-warning-sign\"></i> ". $i18n->getMessage("match_manage_playerstatistics_noformationavailable") . "</p>";}
	else {
		echo "<table class=\"table table-bordered table-striped table-hover\">";
		echo "<thead>";
		echo "<tr>";
		echo "<th>". $i18n->getMessage("entity_player_position") . "</th>";
		echo "<th>". $i18n->getMessage("entity_player") . "</th>";
		foreach ($formFields as $formField) echo "<th>". $i18n->getMessage("match_manage_" . $formField) . "</th>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";
		$playerIds = array();
		while ($player = $result->fetch_array()) {
			$playerIds[] = $player["spieler_id"];
			$players[$player["spieler_id"]] = $player["name"];
			$fieldPrefix = "pl" . $player["spieler_id"];
			echo "<tr>";
			echo "<td>";
			echo htmlentities("<select name=\"" . $fieldPrefix . "_pos\" class=\"input-medium\">");
			foreach ($positions as $position) {
				echo htmlentities("<option value=\"". $position . "\"");
				if ($position == $player["position_main"]) echo " selected";
				echo htmlentities(">". $i18n->getMessage("option_" . $position). "</option>");}
			echo "</select><br/>";
			echo htmlentities("<select name=\"" . $fieldPrefix . "_feld\" class=\"input-medium\">");
			echo "<option value=\"1\"";
			if ($player["feld"] === "1") echo " selected";
			echo ">". $i18n->getMessage("match_manage_position_field_1") . "</option>";
			echo "<option value=\"Ersatzbank\"";
			if ($player["feld"] === "Ersatzbank") echo " selected";
			echo ">". $i18n->getMessage("match_manage_position_field_bench") . "</option>";
			echo "<option value=\"Ausgewechselt\"";
			if ($player["feld"] === "Ausgewechselt") echo " selected";
			echo ">". $i18n->getMessage("match_manage_position_field_substituted") . "</option>";
			echo "</select></td>";
			echo htmlentities("<td>". $player["name"]);
			echo htmlentities(" <a href=\"?site=$site&action=delete&match=$matchId&player=". $player["spieler_id"] . "\" title=\"". $i18n->getMessage("manage_delete") . "\" class=\"deleteLink\"><i class=\"icon-trash\"></i></a>");
			echo "</td>";
			foreach ($formFields as $formField) echo htmlentities("<td><input type=\"text\" class=\"input-mini\" name=\"". $fieldPrefix . "_". $formField . "\" title=\"". $i18n->getMessage("match_manage_" . $formField) . "\" value=\"". $player[$formField]."\"/></td>");
			echo "</tr>";}
		echo "</tbody>";
		echo "</table>";
		echo htmlentities("<input type=\"hidden\" name=\"". $teamPrefix . "_players\" value=\"". implode(";", $playerIds) . "\"/>");
		echo "<h4>". $i18n->getMessage("match_manage_substitutions") . "</h4>"; ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th><?php echo $i18n->getMessage("match_manage_substitutions_out") ?></th>
					<th><?php echo $i18n->getMessage("match_manage_substitutions_in") ?></th>
					<th><?php echo $i18n->getMessage("match_manage_substitutions_condition") ?></th>
					<th><?php echo $i18n->getMessage("match_manage_substitutions_minute") ?></th></tr></thead>
			<tbody><?php
				for ($subNo = 1; $subNo <= 3; $subNo++) {
					echo "<tr>";
					echo htmlentities("<td><select name=\"". $teamPrefix . "_sub" . $subNo . "_out\"><option> </option>");
					foreach ($players as $playerId => $playerName) {
						echo "<option value=\"". $playerId . "\"";
						if ($match["match_". $teamPrefix . "_sub" . $subNo . "_out"] == $playerId) echo " selected";
						echo ">". escapeOutput($playerName) . "</option>";}
					echo "</select></td>";
					echo htmlentities("<td><select name=\"". $teamPrefix . "_sub" . $subNo . "_in\"><option> </option>");
					foreach ($players as $playerId => $playerName) {
						echo "<option value=\"". $playerId . "\"";
						if ($match["match_". $teamPrefix . "_sub" . $subNo . "_in"] == $playerId) echo " selected";
						echo ">". escapeOutput($playerName) . "</option>";}
					echo "</select></td>";
					echo htmlentities("<td><select name=\"". $teamPrefix . "_sub" . $subNo . "_condition\"><option> </option>");
					echo "<option value=\"Tie\"";
					if ($match["match_". $teamPrefix . "_sub" . $subNo . "_condition"] == "Tie") echo " selected";
					echo ">". $i18n->getMessage("match_manage_substitutions_condition_tie") . "</option>";
					echo "<option value=\"Leading\"";
					if ($match["match_". $teamPrefix . "_sub" . $subNo . "_condition"] == "Leading") echo " selected";
					echo ">". $i18n->getMessage("match_manage_substitutions_condition_leading") . "</option>";
					echo "<option value=\"Deficit\"";
					if ($match["match_". $teamPrefix . "_sub" . $subNo . "_condition"] == "Deficit") echo " selected";
					echo ">". $i18n->getMessage("match_manage_substitutions_condition_deficit") . "</option>";
					echo "</td>";
					echo htmlentities("<td><input class=\"input-mini\" type=\"number\" name=\"". $teamPrefix . "_sub" . $subNo . "_minute\" value=\"". $match["match_". $teamPrefix . "_sub" . $subNo . "_minute"] . "\"/></td>");
					echo "</tr>";} ?></tbody></table><?php }}
echo "<div class=\"form-actions\">";
echo "<button type=\"submit\" class=\"btn btn-primary\">". $i18n->getMessage("button_save") . "</button>";
echo " <button type=\"reset\" class=\"btn\">". $i18n->getMessage("button_reset") . "</button>";
echo "</div></form>";
