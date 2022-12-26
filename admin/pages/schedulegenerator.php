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
$mainTitle = $i18n->getMessage("schedulegenerator_navlabel");
echo "<h1>$mainTitle</h1>";
if (!$admin["r_admin"] && !$admin["r_demo"] && !$admin[$page["permissionrole"]]) throw new Exception($i18n->getMessage("error_access_denied"));
ignore_user_abort(TRUE);
set_time_limit(0);
if (!$show) { ?>
  <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" class="form-horizontal">
    <input type="hidden" name="show" value="generate">
	<input type="hidden" name="site" value="<?php echo $site; ?>">
	<fieldset>
    <legend><?php echo $i18n->getMessage("schedulegenerator_label"); ?></legend><?php
	$seasonDefaultName = date("Y");
	$formFields = ["league" => ["type" => "foreign_key", "labelcolumns" => "land,name", "jointable" => "liga", "entity" => "league", "value" => "", "required" => "true"], "seasonname" => ["type" => "text", "value" => $seasonDefaultName, "required" => "true"],
		"rounds" => ["type" => "number", "value" => 2, "required" => "true"], "firstmatchday" => ["type" => "timestamp", "value" => ""], "timebreak" => ["type" => "number", "value" => 5], "timebreak_rounds" => ["type" => "number", "value" => 0]];
	foreach ($formFields as $fieldId => $fieldInfo) echo FormBuilder::createFormGroup($i18n, $fieldId, $fieldInfo, $fieldInfo["value"], "schedulegenerator_label_"); ?></fieldset>
	<div class="form-actions">
		<input type="submit" class="btn btn-primary" accesskey="s" title="Alt + s" value="<?php echo $i18n->getMessage("generator_button"); ?>">
		<input type="reset" class="btn" value="<?php echo $i18n->getMessage("button_reset"); ?>"></div></form><?php }
elseif ($show == "generate") {
  if (!isset($_POST['league']) || $_POST['league'] <= 0) $err[] = $i18n->getMessage("generator_validationerror_noleague");
  if (!isset($_POST['rounds']) || $_POST['rounds'] <= 0 || $_POST['rounds'] > 10) $err[] = $i18n->getMessage("schedulegenerator_err_invalidrounds");
  if (!isset($_POST['timebreak']) || $_POST['timebreak'] <= 0 || $_POST['timebreak'] > 50) $err[] = $i18n->getMessage("schedulegenerator_err_invalidtimebreak");
  if ($admin['r_demo']) $err[] = $i18n->getMessage("validationerror_no_changes_as_demo");
  if (isset($err)) include("validationerror.inc.php");
  else {
  	$result = $db->querySelect("id", $website->getConfig("db_prefix") . "_verein", "liga_id = %d", $_POST['league']);
	if (!$result->num_rows) throw new Exception($i18n->getMessage("schedulegenerator_err__noteams"));
	while ($team = $result->fetch_array()) $teams[] = $team["id"];
	$schedule = ScheduleGenerator::createRoundRobinSchedule($teams);
	$numberOfMatchDaysPerRound = count($schedule);
	$rounds = (int) $_POST['rounds'];
	for ($round = 2; $round <= $rounds; $round++) {
		$startMatchday = count($schedule) + 1;
		$endMatchday = $startMatchday + $numberOfMatchDaysPerRound - 1;
		for ($matchday = $startMatchday; $matchday <= $endMatchday; $matchday++) {
			$originalMatchDay = $matchday - $numberOfMatchDaysPerRound;
			foreach ($schedule[$originalMatchDay] as $match) {
				$homeTeam = $match[1];
				$guestTeam = $match[0];
				$schedule[$matchday][] = array($homeTeam, $guestTeam);}}}
	$seasoncolumns = ["name" => $_POST["seasonname"], "liga_id" => $_POST["league"]];
	$db->queryInsert($seasoncolumns, $website->getConfig("db_prefix") . "_saison");
	$saisonId = $db->getLastInsertedId();
	$dateObj = DateTime::createFromFormat($website->getConfig("date_format") .", H:i", $_POST["firstmatchday_date"] .", ". $_POST["firstmatchday_time"]);
	$matchTimestamp = $dateObj->getTimestamp();
	$timeBreakSeconds = 3600 * 24 * $_POST['timebreak'];
	$matchTable = $website->getConfig("db_prefix") . "_spiel";
	foreach($schedule as $matchDay => $matches) {
		foreach ($matches as $match) {
			$homeTeam = $match[0];
			$guestTeam = $match[1];
			$teamcolumns = ["spieltyp" => "Ligaspiel", "liga_id" => $_POST["league"], "saison_id" => $saisonId, "spieltag" => $matchDay, "home_verein" => $homeTeam, "gast_verein" => $guestTeam, "datum" => $matchTimestamp];
			$db->queryInsert($teamcolumns, $matchTable);}
		$matchTimestamp += $timeBreakSeconds;
		if (($matchDay % $numberOfMatchDaysPerRound) == 0) $matchTimestamp += 3600 * 24 * $_POST['timebreak_rounds'];}
	echo createSuccessMessage($i18n->getMessage("generator_success"), "");
    echo "<p>&raquo; <a href=\"?site=". $site ."\">". $i18n->getMessage("back_label") . "</a></p>\n";}}
