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
echo "<h1>".  $i18n->getMessage("match_manage_complete") . "</h1>";
if (!$admin["r_admin"] && !$admin["r_demo"] && !$admin["r_spiele"]) throw new Exception($i18n->getMessage("error_access_denied"));
echo "<p><a href=\"?site=manage&entity=match\" class=\"btn\">". $i18n->getMessage("back_label") . "</a></p>";
$matchId = (isset($_REQUEST["match"]) && is_numeric($_REQUEST["match"])) ? $_REQUEST["match"] : 0;
$match = MatchesDataService::getMatchById($website, $db, $matchId, FALSE, FALSE);
if (!count($match)) throw new Exception("illegal match id");
if ($match["match_simulated"]) throw new Exception($i18n->getMessage("match_manage_complete_alreadysimulated"));
if ($action == "complete") {
	if ($admin["r_demo"]) throw new Exception($i18n->getMessage("validationerror_no_changes_as_demo"));
	$statTable = $website->getConfig("db_prefix") . "_spiel_berechnung";
	$result = $db->querySelect("SUM(tore) AS goals, MAX(minuten_gespielt) AS minutes", $statTable, "spiel_id = %d AND team_id = %d", [$matchId, $match["match_home_id"]]);
	$homeStat = $result->fetch_array();
	$result = $db->querySelect("SUM(tore) AS goals", $statTable, "spiel_id = %d AND team_id = %d", [$matchId, $match["match_guest_id"]]);
	$guestStat = $result->fetch_array();
	$db->queryUpdate(["minutes" => $homeStat["minutes"], "home_tore" => $homeStat["goals"], "gast_tore" => $guestStat["goals"], "berechnet" => "1"], $website->getConfig("db_prefix") . "_spiel", "id = %d", $matchId);
	$fromTable = $website->getConfig("db_prefix") ."_spiel AS M INNER JOIN " . $website->getConfig("db_prefix") ."_verein AS HOME_T ON HOME_T.id = M.home_verein INNER JOIN " . $website->getConfig("db_prefix") ."_verein AS GUEST_T ON GUEST_T.id = M.gast_verein";
	$columns = ["M.id" => "match_id", "M.spieltyp" => "type", "M.home_verein" => "home_id", "M.gast_verein" => "guest_id", "M.minutes" => "minutes", "M.soldout" => "soldout", "M.elfmeter" => "penaltyshooting", "M.pokalname" => "cup_name", "M.pokalrunde" => "cup_roundname",
		"M.pokalgruppe" => "cup_groupname", "M.player_with_ball" => "player_with_ball", "M.prev_player_with_ball" => "prev_player_with_ball", "M.home_tore" => "home_goals", "M.gast_tore" => "guest_goals", "M.home_offensive" => "home_offensive",
		"M.home_setup" => "home_setup", "M.home_noformation" => "home_noformation", "M.home_longpasses" => "home_longpasses", "M.home_counterattacks" => "home_counterattacks", "M.gast_offensive" => "guest_offensive", "M.guest_noformation" => "guest_noformation",
		"M.gast_setup" => "guest_setup", "M.gast_longpasses" => "guest_longpasses", "M.gast_counterattacks" => "guest_counterattacks", "HOME_T.name" => "home_name", "HOME_T.nationalteam" => "home_nationalteam", "GUEST_T.nationalteam" => "guest_nationalteam",
		"GUEST_T.name" => "guest_name"];
	for ($subNo = 1; $subNo <= 3; $subNo++) {
		$columns = ["M.home_w" . $subNo . "_raus" => "home_sub_" . $subNo . "_out", "M.home_w" . $subNo . "_rein" => "home_sub_" . $subNo . "_in", "M.home_w" . $subNo . "_minute" => "home_sub_" . $subNo . "_minute",
			"M.home_w" . $subNo . "_condition" => "home_sub_" . $subNo . "_condition", "M.gast_w" . $subNo . "_raus" => "guest_sub_" . $subNo . "_out", "M.gast_w" . $subNo . "_rein" => "guest_sub_" . $subNo . "_in",
			"M.gast_w" . $subNo . "_minute" => "guest_sub_" . $subNo . "_minute", "M.gast_w" . $subNo . "_condition" => "guest_sub_" . $subNo . "_condition"];}
	$result = $db->querySelect($columns, $fromTable, "M.id = %d", $matchId);
	$matchinfo = $result->fetch_array();
	$dummyVar = new DefaultSimulationStrategy($website);
	$matchModel = SimulationStateHelper::loadMatchState($website, $db, $matchinfo);
	if ($website->getRequestParameter("computetickets")) SimulationAudienceCalculator::computeAndSaveAudience($website, $db, $matchModel);
	if ($matchinfo["type"] == "Pokalspiel") SimulationCupMatchHelper::checkIfExtensionIsRequired($website, $db, $matchModel);
	$observer = new DataUpdateSimulatorObserver($website, $db);
	$observer->onMatchCompleted($matchModel);
	echo createSuccessMessage($i18n->getMessage("match_manage_complete_success"), "");}
echo htmlentities("<h3><a href=\"". $website->getInternalUrl("team", "id=" . $match["match_home_id"]) . "\" target=\"_blank\">". escapeOutput($match["match_home_name"]) . "</a> - <a href=\"". $website->getInternalUrl("team", "id=" . $match["match_guest_id"]) .
	"\" target=\"_blank\">". escapeOutput($match["match_guest_name"]) . "</a></h3>");
echo "<div class=\"well\">". $i18n->getMessage("match_manage_complete_intro") . "</div>";
echo htmlentities("<form action=\"". $_SERVER['PHP_SELF'] . "\" method=\"post\" class=\"form-horizontal\">");
echo "<input type=\"hidden\" name=\"site\" value=\"$site\"/>";
echo "<input type=\"hidden\" name=\"action\" value=\"complete\"/>";
echo htmlentities("<input type=\"hidden\" name=\"match\" value=\"$matchId\"/>");
echo FormBuilder::createFormGroup($i18n, "computetickets", array("type" => "boolean", "value" => "1"), "1", "match_manage_complete_");
echo "<div class=\"form-actions\">";
echo "<button type=\"submit\" class=\"btn btn-primary\">". $i18n->getMessage("match_manage_complete_button") . "</button>";
echo "</div></form>";
