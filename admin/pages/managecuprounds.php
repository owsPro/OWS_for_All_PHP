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

//##### Infos zur Rubrik #####
$mainTitle = Message("managecuprounds_navlabel");
$r_prefix = ""; #Prefix der Datei

echo "<h1>$mainTitle</h1>";

echo "<p><a href=\"?site=manage&entity=cup\" class=\"btn\">" . Message("button_cancel") ."</a></p>";

if (!$admin["r_admin"] && !$admin["r_demo"] && !$admin["r_spiele"]) {
	throw new Exception(Message("error_access_denied"));
}

$cupid = (isset($_REQUEST["cup"]) && is_numeric($_REQUEST["cup"])) ? $_REQUEST["cup"] : 0;

$result = $db->querySelect("name",Config("db_prefix") . "_cup", "id = %d", $cupid);
$cup = $result->fetch_array();
$result->free();
if (!isset($cup["name"])) {
	throw new Exception("illegal cup id");
}

?>

<div class="alert alert-info">
	<h5><?php echo Message("managecuprounds_infoalert_title"); ?></h5>
	<p><?php echo Message("managecuprounds_infoalert_msg"); ?></p>
</div>

<?php

echo "<h2>". Message("entity_cup") . ": " . escapeOutput($cup["name"]) . "</h2>";

// configure create form
$formFields = array();
$formFields["name"] = array("type" => "text", "value" => "", "required" => "true");
$formFields["firstround_date"] = array("type" => "timestamp", "value" => "", "required" => "true");
$formFields["create_secondround"] = array("type" => "boolean", "value" => "");
$formFields["secondround_date"] = array("type" => "timestamp", "value" => "", "required" => "false");
$formFields["groupmatches"] = array("type" => "boolean", "value" => "");
$formFields["finalround"] = array("type" => "boolean", "value" => "");

// Action: create new round
if ($action == "create") {
	if ($admin["r_demo"]) {
		throw new Exception(Message("validationerror_no_changes_as_demo"));
	}

	try {
		$dates = array();

		// validate fields
		foreach ($formFields as $fieldId => $fieldInfo) {

			if ($fieldInfo["type"] == "timestamp") {
				$dateObj = DateTime::createFromFormat(Config("date_format") .", H:i",
						$_POST[$fieldId ."_date"] .", ". $_POST[$fieldId ."_time"]);
				$fieldValue = ($dateObj) ? $dateObj->getTimestamp() : 0;

				$dates[$fieldId] = $fieldValue;

			} else {
				$fieldValue = (isset($_POST[$fieldId])) ? $_POST[$fieldId] : "";
			}

			validateField($i18n, $fieldId, $fieldInfo, $fieldValue, "managecuprounds_label_");
		}

		// save
		$columns = array();

		$columns["cup_id"] = $cupid;
		$columns["name"] = $_POST["name"];
		$columns["finalround"] = (isset($_POST["finalround"]) && $_POST["finalround"] == "1") ? 1 : 0;
		$columns["groupmatches"] = (isset($_POST["groupmatches"]) && $_POST["groupmatches"] == "1") ? 1 : 0;

		$columns["firstround_date"] = $dates["firstround_date"];
		if (isset($_POST["create_secondround"]) && $_POST["create_secondround"] == "1") {
			$columns["secondround_date"] = $dates["secondround_date"];
		}

		if (isset($_POST["round_generation"]) && isset($_POST["from_round_id"])) {

			if ($_POST["round_generation"] == "winners_from") {
				$columns["from_winners_round_id"] = $_POST["from_round_id"];
			} elseif ($_POST["round_generation"] == "loosers_from") {
				$columns["from_loosers_round_id"] = $_POST["from_round_id"];
			}

		}

		$db->queryInsert($columns,Config("db_prefix") . "_cup_round");

	} catch (Exception $e) {
		echo twig_escape_filter((createErrorMessage(Message("subpage_error_alertbox_title") , $e->getMessage()));
	}

// Action: delete
} elseif ($action == "delete") {
	if ($admin["r_demo"]) {
		throw new Exception(Message("validationerror_no_changes_as_demo"));
	}

	$db->queryDelete(Config("db_prefix") . "_cup_round", "id = %d", $_GET["id"]);

	echo createSuccessMessage(Message("manage_success_delete"), "");
}

// get existing rounds as hierarchy
$result = $db->querySelect("*",Config("db_prefix") . "_cup_round", "cup_id = %d ORDER BY firstround_date DESC", $cupid);
$hierarchy = array();
while ($round = $result->fetch_array()) {
	$hierarchy[$round["id"]]["round"] = $round;

	$isRoot = TRUE;
	if ($round["from_winners_round_id"] > 0) {
		$hierarchy[$round["from_winners_round_id"]]["winnerround"] = $round["id"];
		$isRoot = FALSE;
	}
	if ($round["from_loosers_round_id"] > 0) {
		$hierarchy[$round["from_loosers_round_id"]]["looserround"] = $round["id"];
		$isRoot = FALSE;
	}

	if ($isRoot) {
		$rootIds[] = $round["id"];
	}
}
$result->free();

// list rounds
if (isset($rootIds)) {
	echo "<div id=\"rounds\">";
	foreach ($rootIds as $rootId)CupRound($hierarchy[$rootId]);
	echo "</div>";
}



// Create new round
?>
  <form action="<?php echo escapeOutput($_SERVER['PHP_SELF']); ?>" method="post" class="form-horizontal">
    <input type="hidden" name="action" value="create">
	<input type="hidden" name="site" value="<?php echo $site; ?>">
	<input type="hidden" name="cup" value="<?php echo escapeOutput($cupid); ?>">

	<fieldset>
    <legend><?php echo Message("managecuprounds_label_create"); ?></legend>

	<?php
	foreach ($formFields as $fieldId => $fieldInfo) {
		echo createFormGroup($i18n, $fieldId, $fieldInfo, $fieldInfo["value"], "managecuprounds_label_");
	}
	?>
	<hr>

	<?php
	echo createFormGroup($i18n, "round_generation", array(
			"type" => "select",
			"selection" => "self,winners_from,loosers_from,generate_from_groups",
			"value" => "",
			"required" => "true"
		), $fieldInfo["value"], "managecuprounds_label_");


	?>

	<div class="control-group">
		<label class="control-label" for="from_round_id"><?php echo Message("managecuprounds_label_previous_round")?></label>

		<div class="controls">
			<select name="from_round_id" id="from_round_id">
				<option></option>
				<?php
				foreach ($hierarchy as $roundId => $roundInfo) {
					echo "<option value=\"". $roundId . "\">". escapeOutput($roundInfo["round"]["name"]) . "</option>\n";
				}
				?>
			</select>
		</div>
	</div>
	</fieldset>
	<div class="form-actions">
		<input type="submit" class="btn btn-primary" accesskey="s" title="Alt + s" value="<?php echo Message("button_save"); ?>">
	</div>
  </form>

	<?php
?>