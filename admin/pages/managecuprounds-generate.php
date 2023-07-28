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

$mainTitle = Message("managecuprounds_generate_navlabel");

echo "<h1>$mainTitle</h1>";

if (!$admin["r_admin"] && !$admin["r_demo"] && !$admin["r_spiele"]) {
	throw new Exception(Message("error_access_denied"));
}

$roundid = (isset($_REQUEST["round"]) && is_numeric($_REQUEST["round"])) ? $_REQUEST["round"] : 0;

$result = $db->querySelect("R.id AS round_id,R.name AS round_name,firstround_date,secondround_date,C.id AS cup_id,C.name as cup_name",
		Config("db_prefix") . "_cup_round AS R INNER JOIN " .Config("db_prefix") . "_cup AS C ON C.id = R.cup_id",
		"R.id = %d", $roundid);
$round = $result->fetch_array();
$result->free();
if (!isset($round["round_name"])) {
	throw new Exception("illegal round id");
}

echo "<h2>". Message("entity_cup") . " - " . escapeOutput($round["round_name"]) . "</h2>";

// ****** Generate matches ***********
if ($action == "generate" && isset($_POST["teams"]) && is_array($_POST["teams"])) {
	if ($admin["r_demo"]) {
		throw new Exception(Message("validationerror_no_changes_as_demo"));
	}

	$teamIds = $_POST["teams"];
	shuffle($teamIds);

	$insertTable =Config("db_prefix") . "_spiel";

	// create combinations
	while(count($teamIds) > 1) {
		$homeTeamId = array_pop($teamIds);
		$guestTeamId = array_pop($teamIds);

		// create first round
		$db->queryInsert(array(
				"spieltyp" => "Pokalspiel",
				"pokalname" => $round["cup_name"],
				"pokalrunde" => $round["round_name"],
				"datum" => $round["firstround_date"],
				"home_verein" => $homeTeamId,
				"gast_verein" => $guestTeamId
				), $insertTable);

		// create second round
		if ($round["secondround_date"]) {
			$db->queryInsert(array(
					"spieltyp" => "Pokalspiel",
					"pokalname" => $round["cup_name"],
					"pokalrunde" => $round["round_name"],
					"datum" => $round["secondround_date"],
					"home_verein" => $guestTeamId,
					"gast_verein" => $homeTeamId
			), $insertTable);
		}
	}

	echo createSuccessMessage(Message("managecuprounds_generate_success"), "");
	echo "<p><a href=\"?site=managecuprounds&cup=". $round["cup_id"] . "\" class=\"btn btn-primary\">" . Message("managecuprounds_generate_success_overviewlink") ."</a></p>";
}

// ****** Display selection form ***********
?>

	<div id="noCupPossibleAlert" class="alert" style="display: none;">
		<h5><?php echo Message("managecuprounds_generate_noroundspossible"); ?></h5>
	</div>
	<div id="possibleCupRoundsAlert" class="alert alert-info" style="display: none;">
		<h5><?php echo Message("managecuprounds_generate_possiblerounds"); ?>: <span id="roundsNo">0</span></h5>
	</div>

  <form action="<?php echo escapeOutput($_SERVER['PHP_SELF']); ?>" method="post" class="form-horizontal">
    <input type="hidden" name="action" value="generate">
	<input type="hidden" name="site" value="<?php echo $site; ?>">
	<input type="hidden" name="round" value="<?php echo $roundid; ?>">

	<fieldset>
    	<legend><?php echo Message("managecuprounds_generate_formlabel"); ?> (<span id="numberOfTeamsSelected">0</span>)</legend>

		<div style="width: 600px; height: 300px; overflow: auto; border: 1px solid #cccccc;">
			<table class="table table-striped table-hover">
				<colgroup>
					<col style="width: 30px">
					<col>
					<col>
				</colgroup>
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th><?php echo Message("entity_club")?></th>
						<th><?php echo Message("entity_league")?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$result = $db->querySelect("T.id AS team_id,T.name AS team_name,L.name AS league_name,L.land AS league_country",
							Config("db_prefix") . "_verein AS T LEFT JOIN " .tConfig("db_prefix") . "_liga AS L ON L.id = T.liga_id",
							"1=1 ORDER BY team_name ASC");

					while ($team = $result->fetch_array()) {
						echo "<tr>";
						echo "<td><input type=\"checkbox\" class=\"teamForCupCheckbox\" name=\"teams[]\" value=\"". $team["team_id"] . "\"></td>";
						echo "<td class=\"tableRowSelectionCell\">". escapeOutput($team["team_name"]) . "</td>";
						echo "<td class=\"tableRowSelectionCell\">". escapeOutput($team["league_name"] . " (" . $team["league_country"] . ")") . "</td>";
						echo "</tr>";
					}
					$result->free();
					?>
				</tbody>
			</table>
		</div>
	</fieldset>
	<div class="form-actions">
		<input type="submit" class="btn btn-primary" accesskey="s" title="Alt + s" value="<?php echo Message("managecuprounds_generate_submitbutton"); ?>">
		<?php
		echo " <a href=\"?site=managecuprounds&cup=". $round["cup_id"] . "\" class=\"btn\">" . Message("button_cancel") ."</a>";
		?>
	</div>
  </form>