<?php
/******************************************************************************

   This file is part of owsPro - a spinn-off of the OpenWebSoccer-Sim.

   Be sure to note the file README.rst !

-------------------------------------------------------------------------------

  Diese Datei ist Teil von owsPro - einem Ableger der OpenWebSoccer-Sim.

  Beachten Sie unbedingt die Datei README.rst !

******************************************************************************/
error_reporting(E_ALL);
define("BASE_FOLDER", __DIR__ ."/..");
define("PHP_MIN_VERSION","5.3.0");
define("WRITABLE_FOLDERS","generated/,uploads/club/,uploads/cup/,uploads/player/,uploads/sponsor/,uploads/stadium/,uploads/stadiumbuilder/,uploads/stadiumbuilding/,uploads/users/,admin/config/jobs.xml,admin/config/termsandconditions.xml");
define("DEFAULT_DB_PREFIX","");
define("CONFIGFILE",BASE_FOLDER."/generated/config.inc.php");
define("DDL_FULL","OWS.sql");
session_start();
$supportedLanguages = array("de" => "Deutsch", "en" => "English");
ignore_user_abort(TRUE);
set_time_limit(0);
?>
<!DOCTYPE html>
<html lang="de">
	<head>
		<title>OWS for All PHP Installation</title>
		<link href="../admin/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" />
		<meta charset="UTF-8">
		<style type="text/css">
			body {
				padding-top: 100px;
				padding-bottom: 40px;}</style></head>
	<body>
		<div class="container">
			<h1>owsPro Installation</h1>
			<hr>
			<?php
				$errors = array();
				$messagesIncluded = FALSE;
				if(isset($_SESSION["lang"])) {
					include("messages_" . $_SESSION["lang"] . ".inc.php");
					$messagesIncluded = $_SESSION["lang"];}
				$action = (isset($_REQUEST["action"])) ? $_REQUEST["action"] : "";
				if (!strlen($action) || substr($action, 0, 6) !== "action")$view = "printWelcomeScreen";
				else $view = $action();
				if(isset($_SESSION["lang"]) && $_SESSION["lang"] !== $messagesIncluded)	include("messages_" . $_SESSION["lang"] . ".inc.php");
				if (count($errors))foreach($errors as $error)echo "<div class=\"alert alert-error\">$error</div>";
				if (isset($messages))$view($messages);
				else $view();
			?>
			<hr>
			<footer>
				<p>Powered by <a href="https://github.com/owsPro/open-websoccer" target="_blank">owsPro</a><br>
				Forked from <a href="https://github.com/ihofmann/open-websoccer" target="_blank">ihofmann</a></p></footer></div>
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script src="../admin/bootstrap/js/bootstrap.min.js"></script></body></html>
<?php
/**
 * Step 1: Welcome Screen -> Language Selection
 */
function printWelcomeScreen() {
	global $supportedLanguages;

	echo "<h2>Sprache wählen / Choose language</h2>";

	echo "<form method=\"post\">";
	$first = TRUE;
	foreach ($supportedLanguages as $langId => $langLabel) {
		echo "<label class=\"radio\">";
		echo "<input type=\"radio\" name=\"lang\" id=\"$langId\" value=\"$langId\"";
		if ($first) {
			echo " checked";
			$first = FALSE;
		}
		echo "> $langLabel";
		echo "</label>";
	}

	echo "<button type=\"submit\" class=\"btn\">Wählen / Choose</button>";
	echo "<input type=\"hidden\" name=\"action\" value=\"actionSetLanguage\">";
	echo "</form>";
}

function actionSetLanguage() {
	if (!isset($_POST["lang"])) {
		global $errors;
		$errors[] = "Please select a language.";
		return "printWelcomeScreen";
	}

	global $supportedLanguages;
	$lang = $_POST["lang"];
	if (key_exists($lang, $supportedLanguages)) {
		$_SESSION["lang"] = $lang;
		return "printSystemCheck";
	}

	return "printWelcomeScreen";
}

/**
 * Step 2: System Check
 */
function printSystemCheck($messages) {
	echo "<h2>". $messages["check_title"] . "</h2>";

	$requirments = array();

	$requirments[] = array(
			"requirement" => $messages["check_req_php"],
			"min" => PHP_MIN_VERSION,
			"actual" => PHP_VERSION,
			"status" => (version_compare(PHP_VERSION, PHP_MIN_VERSION) > -1) ? "success" : "error"
	);

	$requirments[] = array(
			"requirement" => $messages["check_req_json"],
			"min" => $messages["check_req_yes"],
			"actual" => (function_exists("json_encode")) ? $messages["check_req_yes"] : $messages["check_req_no"],
			"status" => (function_exists("json_encode")) ? "success" : "error"
	);

	$requirments[] = array(
			"requirement" => $messages["check_req_gd"],
			"min" => $messages["check_req_yes"],
			"actual" => (function_exists("getimagesize")) ? $messages["check_req_yes"] : $messages["check_req_no"],
			"status" => (function_exists("getimagesize")) ? "success" : "error"
	);

	$requirments[] = array(
			"requirement" => $messages["check_req_safemode"],
			"min" => $messages["check_req_off"],
			"actual" => (!ini_get('safe_mode')) ? $messages["check_req_off"] : $messages["check_req_on"],
			"status" => (!ini_get('safe_mode')) ? "success" : "error"
	);

	$writableFiles = explode(",", WRITABLE_FOLDERS);
	foreach ($writableFiles as $writableFile) {
		$file = BASE_FOLDER . "/" . $writableFile;

		$requirments[] = array(
				"requirement" => $messages["check_req_writable"] . " <i>" . $writableFile . "</i>",
				"min" => $messages["check_req_yes"],
				"actual" => (is__writable($file)) ? $messages["check_req_yes"] : $messages["check_req_no"],
				"status" => (is__writable($file)) ? "success" : "error"
		);
	}

	?>

	<table class="table">
		<thead>
			<tr>
				<th><?php echo $messages["check_head_requirement"] ?></th>
				<th><?php echo $messages["check_head_required_value"] ?></th>
				<th><?php echo $messages["check_head_actual_value"] ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		$valid = TRUE;
		foreach($requirments as $requirement) {
			echo "<tr class=\"".  $requirement["status"] . "\">";
			echo "<td>" . $requirement["requirement"] . "</td>";
			echo "<td>" . $requirement["min"] . "</td>";
			echo "<td>" . $requirement["actual"] . "</td>";
			echo "</tr>";
			if ($requirement["status"] == "error") {
				$valid = FALSE;
			}
		}
		?>
		</tbody>
	</table>
	<?php
	if ($valid) {
		echo "<form method=\"post\">";
		echo "<button type=\"submit\" class=\"btn\">". $messages["button_next"] . "</button>";
		echo "<input type=\"hidden\" name=\"action\" value=\"actionGotoConfig\">";
		echo "</form>";
	} else {
		echo "<p>". $messages["check_req_error"] . "</p>";
	}
}
function actionGotoConfig(){return "printConfigForm";}
function printConfigForm($messages){
	?>
	<form method="post" class="form-horizontal">
		<fieldset>
			<legend><?php echo $messages["config_formtitle"]?></legend>
			<div class="control-group">
			    <label class="control-label" for="db_host"><?php echo $messages["label_db_host"]?></label>
			    <div class="controls">
			      <input type="text" id="db_host" name="db_host" required
			      	value="<?php echo (isset($_POST["db_host"]))?$_POST["db_host"]:"localhost"; ?>">
			      <span class="help-inline"><?php echo $messages["label_db_host_help"]?></span>
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="db_name"><?php echo $messages["label_db_name"]?></label>
			    <div class="controls">
			      <input type="text" id="db_name" name="db_name" required
			      	value="<?php echo (isset($_POST["db_name"]))?$_POST["db_name"]:"";?>">
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="db_user"><?php echo $messages["label_db_user"]?></label>
			    <div class="controls">
			      <input type="text" id="db_user" name="db_user" required
			      	value="<?php echo (isset($_POST["db_user"]))?$_POST["db_user"]:"";?>">
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="db_password"><?php echo $messages["label_db_password"]?></label>
			    <div class="controls">
			      <input type="text" id="db_password" name="db_password" required
			      	value="<?php echo (isset($_POST["db_password"]))?$_POST["db_password"]:"";?>">
			    </div>
			</div>
			<hr>
			<div class="control-group">
			    <label class="control-label" for="projectname"><?php echo $messages["label_projectname"]?></label>
			    <div class="controls">
			      <input type="text" id="projectname" name="projectname" required
			      	value="<?php echo(isset($_POST["projectname"]))?$_POST["projectname"]:"";?>">
			      <span class="help-inline"><?php echo $messages["label_projectname_help"]?></span>
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="projectname"><?php echo $messages["label_systememail"]?></label>
			    <div class="controls">
			      <input type="email" id="systememail" name="systememail" required
			      	value="<?php echo(isset($_POST["systememail"]))?$_POST["systememail"]:"";?>">
			      <span class="help-inline"><?php echo $messages["label_systememail_help"]?></span>
			    </div>
			</div>
			<?php $defaultUrl = "http://".$_SERVER["HTTP_HOST"];?>
			<div class="control-group">
			    <label class="control-label" for="url"><?php echo $messages["label_url"]?></label>
			    <div class="controls">
			      <input type="url" id="url" name="url" required
			      	value="<?php echo (isset($_POST["url"]))?$_POST["url"]:$defaultUrl;?>">
			      	<span class="help-inline"><?php echo $messages["label_url_help"]?></span>
			    </div>
			</div>
			<?php $defaultRoot=substr($_SERVER["REQUEST_URI"],0,strrpos($_SERVER["REQUEST_URI"],"/install"));?>
		</fieldset>

		<div class="form-actions">
		  <button type="submit" class="btn btn-primary"><?php echo $messages["button_next"]; ?></button>
		</div>

		<input type="hidden" name="action" value="actionSaveConfig">
	</form>

	<?php
}

function actionSaveConfig() {
	global $errors;
	global $messages;
	$requiredFields = array("db_host", "db_name", "db_user", "db_password", "projectname", "systememail", "url");
	foreach($requiredFields as $requiredField) {
		if (!isset($_POST[$requiredField]) || !strlen($_POST[$requiredField])) {
			$errors[] = $messages["requires_value"] . ": " . $messages["label_" . $requiredField];
		}
	}
	if (count($errors)) {
		return "printConfigForm";
	}
	if (file_exists(CONFIGFILE)) {
		include(CONFIGFILE);
	}
	if (isset($conf) && count($conf)) {
		$errors[] = $messages["err_already_installed"];
	}
	else{
		try{
			$db = DbConnection::getInstance();
			$db->connect($_POST["db_host"], $_POST["db_user"], $_POST["db_password"], $_POST["db_name"]);
			$db->close();
		}
		catch(Exception $e) {
			$errors[] = $messages["invalid_db_credentials"];
		}
	}
	if (count($errors))return "printConfigForm";
	$prefix = isset($_POST["db_prefix"]) ? $_POST["db_prefix"] : DEFAULT_DB_PREFIX;
	$filecontent="<?php" . PHP_EOL;
	$filecontent.="\$conf['db_host']=\"".$_POST["db_host"]."\";".PHP_EOL;
	$filecontent.="\$conf['db_user']=\"".$_POST["db_user"]."\";".PHP_EOL;
	$filecontent.="\$conf['db_passwort']=\"". $_POST["db_password"]."\";".PHP_EOL;
	$filecontent.="\$conf['db_name']=\"".$_POST["db_name"]."\";".PHP_EOL;
	$filecontent.="\$conf['db_prefix']=\"".$prefix."\";".PHP_EOL;
	$filecontent.="\$conf['context_root']=\"".""."\";".PHP_EOL;
	$filecontent.="\$conf['supported_languages'] = \"de,en\";".PHP_EOL;
	$filecontent.="\$conf['homepage']=\"".$_POST["url"]."\";".PHP_EOL;
	$filecontent.="\$conf['projectname']=\"".$_POST["projectname"]."\";".PHP_EOL;
	$filecontent.="\$conf['systememail']=\"".$_POST["systememail"]."\";".PHP_EOL;
	$filecontent.="\$conf['NUMBER_OF_PLAYERS']=\""."20"."\";".PHP_EOL;
	$filecontent.="\$conf['upload_clublogo_max_size']=\""."0"."\";".PHP_EOL;
	$filecontent.="\$conf['rename_club_enabled']=\"".""."\";".PHP_EOL;
	$filecontent.="\$conf['session_lifetime']=\""."7200"."\";".PHP_EOL;
	$filecontent.="\$conf['time_zone']=\""."Europe/Berlin"."\";".PHP_EOL;
	$filecontent.="\$conf['time_offset']=\""."0"."\";".PHP_EOL;
	$filecontent.="\$conf['date_format']=\""."d.m.Y"."\";".PHP_EOL;
	$filecontent.="\$conf['datetime_format']=\""."d.m.Y, H:i"."\";".PHP_EOL;
	$filecontent.="\$conf['time_format']=\""."H:i"."\";".PHP_EOL;
	$filecontent.="\$conf['password_protected']=\"".""."\";".PHP_EOL;
	$filecontent.="\$conf['password_protected_startpage']=\"".""."\";".PHP_EOL;
	$filecontent.="\$conf['privacypolicy_url']=\"".""."\";".PHP_EOL;
	$filecontent.="\$conf['game_currency']=\""."€"."\";".PHP_EOL;
	$filecontent.="\$conf['offline']=\""."online"."\";".PHP_EOL;
	$filecontent.="\$conf['offline_text']=\""."We are offline at the moment."."\";".PHP_EOL;
	$filecontent.="\$conf['offline_times']=\"".""."\";".PHP_EOL;
	$filecontent.="\$conf['enable_player_resignation']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['player_resignation_compensation_matches']=\""."10"."\";".PHP_EOL;
	$filecontent.="\$conf['formation_max_next_matches']=\""."3"."\";".PHP_EOL;
	$filecontent.="\$conf['formation_max_templates']=\""."10"."\";".PHP_EOL;
	$filecontent.="\$conf['login_type']=\""."username"."\";".PHP_EOL;
	$filecontent.="\$conf['login_method']=\""."DefaultUserLoginMethod"."\";".PHP_EOL;
	$filecontent.="\$conf['login_allow_sendingpassword']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['assign_team_automatically']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['max_number_teams_per_user']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['additional_team_min_highscore']=\""."100"."\";".PHP_EOL;
	$filecontent.="\$conf['skin']=\""."DefaultBootstrapSkin"."\";".PHP_EOL;
	$filecontent.="\$conf['entries_per_page']=\""."20"."\";".PHP_EOL;
	$filecontent.="\$conf['head_code']=\"".""."\";".PHP_EOL;
	$filecontent.="\$conf['frontend_ads_hide_for_premiumusers']=\"".""."\";".PHP_EOL;
	$filecontent.="\$conf['frontend_ads_code_sidebar']=\"".""."\";".PHP_EOL;
	$filecontent.="\$conf['frontend_ads_code_top']=\"".""."\";".PHP_EOL;
	$filecontent.="\$conf['frontend_ads_code_bottom']=\"".""."\";".PHP_EOL;
	$filecontent.="\$conf['joomlalogin_tableprefix']=\""."_"."\";".PHP_EOL;
	$filecontent.="\$conf['lending_enabled']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['lending_matches_min']=\""."5"."\";".PHP_EOL;
	$filecontent.="\$conf['lending_matches_max']=\""."20"."\";".PHP_EOL;
	$filecontent.="\$conf['messages_enabled']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['messages_break_minutes']=\""."2"."\";".PHP_EOL;
	$filecontent.="\$conf['no_transactions_for_teams_without_user']=\"".""."\";".PHP_EOL;
	$filecontent.="\$conf['nationalteams_enabled']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['notifications_max']=\""."5"."\";".PHP_EOL;
	$filecontent.="\$conf['contract_max_number_of_remaining_matches']=\""."15"."\";".PHP_EOL;
	$filecontent.="\$conf['hide_strength_attributes']=\"".""."\";".PHP_EOL;
	$filecontent.="\$conf['players_aging']=\""."birthday"."\";".PHP_EOL;
	$filecontent.="\$conf['premium_enabled']=\"".""."\";".PHP_EOL;
	$filecontent.="\$conf['premium_credit_unit']=\""."EUR"."\";".PHP_EOL;
	$filecontent.="\$conf['premium_infopage']=\""."premium-feature-requested"."\";".PHP_EOL;
	$filecontent.="\$conf['premium_price_options']=\""."5:10,20:50,50:150"."\";".PHP_EOL;
	$filecontent.="\$conf['premium_currency']=\""."EUR"."\";".PHP_EOL;
	$filecontent.="\$conf['premium_initial_credit']=\""."0"."\";".PHP_EOL;
	$filecontent.="\$conf['premium_exchangerate_gamecurrency']=\""."1000000"."\";".PHP_EOL;
	$filecontent.="\$conf['randomevents_interval_days']=\""."10"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_noformation_oneteam']=\""."computer"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_noformation_bothteams']=\""."computer"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_createformation_without_manager']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_createformation_without_manager_offensive']=\""."50"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_createformation_on_invalidsubmission']=\"".""."\";".PHP_EOL;
	$filecontent.="\$conf['sim_createformation_strength']=\""."100"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_income_trough_friendly']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_injured_after_friendly']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_tiredness_through_friendly']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_playerupdate_through_nationalteam']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_goaly_influence']=\""."20"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_shootprobability']=\""."100"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_cardsprobability']=\""."100"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_injuredprobability']=\""."100"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_weight_strength']=\""."40"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_weight_strengthTech']=\""."20"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_weight_strengthStamina']=\""."20"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_weight_strengthFreshness']=\""."10"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_weight_strengthSatisfaction']=\""."10"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_strength_reduction_secondary']=\""."10"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_strength_reduction_wrongposition']=\""."50"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_home_field_advantage']=\""."2"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_shootstrength_defense']=\""."70"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_shootstrength_midfield']=\""."90"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_shootstrength_striker']=\""."100"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_block_player_after_yellowcards']=\""."5"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_played_min_minutes']=\""."10"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_decrease_freshness']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_strengthchange_stamina']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_strengthchange_satisfaction']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_strengthchange_freshness_notplayed']=\""."4"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_maxmatches_injured']=\""."12"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_maxmatches_blocked']=\""."5"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_allow_livechanges']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_allow_offensivechanges']=\""."2"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_interval']=\""."2"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_max_matches_per_run']=\""."15"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_strategy']=\""."DefaultSimulationStrategy"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_simulation_observers']=\""."MatchReportSimulationObserver"."\";".PHP_EOL;
	$filecontent.="\$conf['sim_simulator_observers']=\""."MatchReportSimulatorObserver,DataUpdateSimulatorObserver"."\";".PHP_EOL;
	$filecontent.="\$conf['social_likebutton_twitter']=\"".""."\";".PHP_EOL;
	$filecontent.="\$conf['social_likebutton_xing']=\"".""."\";".PHP_EOL;
	$filecontent.="\$conf['sponsor_matches']=\""."15"."\";".PHP_EOL;
	$filecontent.="\$conf['sponsor_earliest_matchday']=\""."4"."\";".PHP_EOL;
	$filecontent.="\$conf['stadium_max_grand']=\""."60000"."\";".PHP_EOL;
	$filecontent.="\$conf['stadium_max_side']=\""."30000"."\";".PHP_EOL;
	$filecontent.="\$conf['stadium_max_vip']=\""."1000"."\";".PHP_EOL;
	$filecontent.="\$conf['stadium_cost_standing']=\""."100"."\";".PHP_EOL;
	$filecontent.="\$conf['stadium_cost_seats']=\""."200"."\";".PHP_EOL;
	$filecontent.="\$conf['stadium_cost_standing_grand']=\""."150"."\";".PHP_EOL;
	$filecontent.="\$conf['stadium_cost_seats_grand']=\""."300"."\";".PHP_EOL;
	$filecontent.="\$conf['stadium_cost_vip']=\""."2000"."\";".PHP_EOL;
	$filecontent.="\$conf['stadium_construction_delay']=\""."7"."\";".PHP_EOL;
	$filecontent.="\$conf['stadium_hide_builders_reliability']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['stadium_maintenanceinterval_pitch']=\""."2"."\";".PHP_EOL;
	$filecontent.="\$conf['stadium_maintenanceinterval_videowall']=\""."10"."\";".PHP_EOL;
	$filecontent.="\$conf['stadium_maintenanceinterval_seatsquality']=\""."5"."\";".PHP_EOL;
	$filecontent.="\$conf['stadium_maintenanceinterval_vipquality']=\""."5"."\";".PHP_EOL;
	$filecontent.="\$conf['stadium_pitch_price']=\""."10000"."\";".PHP_EOL;
	$filecontent.="\$conf['stadium_videowall_price']=\""."100000"."\";".PHP_EOL;
	$filecontent.="\$conf['stadium_seatsquality_price']=\""."10"."\";".PHP_EOL;
	$filecontent.="\$conf['stadium_vipquality_price']=\""."50"."\";".PHP_EOL;
	$filecontent.="\$conf['stadium_maintenance_priceincrease_per_level']=\""."10"."\";".PHP_EOL;
	$filecontent.="\$conf['stadium_pitch_effect']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['stadium_videowall_effect']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['stadium_seatsquality_effect']=\""."2"."\";".PHP_EOL;
	$filecontent.="\$conf['stadium_vipquality_effect']=\""."5"."\";".PHP_EOL;
	$filecontent.="\$conf['training_min_hours_between_execution']=\""."24"."\";".PHP_EOL;
	$filecontent.="\$conf['trainingcamp_min_days']=\""."3"."\";".PHP_EOL;
	$filecontent.="\$conf['trainingcamp_max_days']=\""."5"."\";".PHP_EOL;
	$filecontent.="\$conf['trainingcamp_booking_max_days_in_future']=\""."30"."\";".PHP_EOL;
	$filecontent.="\$conf['transfermarket_enabled']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['transfermarket_duration_days']=\""."7"."\";".PHP_EOL;
	$filecontent.="\$conf['transfermarket_computed_marketvalue']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['transfermarket_value_per_strength']=\""."10000"."\";".PHP_EOL;
	$filecontent.="\$conf['transfermarket_min_teamsize']=\""."18"."\";".PHP_EOL;
	$filecontent.="\$conf['transfermarket_max_transactions_between_users']=\""."2"."\";".PHP_EOL;
	$filecontent.="\$conf['transferoffers_enabled']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['transferoffers_transfer_stop_days']=\""."30"."\";".PHP_EOL;
	$filecontent.="\$conf['transferoffers_adminapproval_required']=\"".""."\";".PHP_EOL;
	$filecontent.="\$conf['transferoffers_contract_matches']=\""."20"."\";".PHP_EOL;
	$filecontent.="\$conf['authentication_mechanism']=\""."SessionBasedUserAuthentication"."\";".PHP_EOL;
	$filecontent.="\$conf['allow_userregistration']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['registration_url']=\"".""."\";".PHP_EOL;
	$filecontent.="\$conf['max_number_of_users']=\""."0"."\";".PHP_EOL;
	$filecontent.="\$conf['illegal_usernames']=\""."admin,administrator,test"."\";".PHP_EOL;
	$filecontent.="\$conf['user_picture_upload_enabled']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['user_picture_upload_maxsize_kb']=\""."512"."\";".PHP_EOL;
	$filecontent.="\$conf['highscore_win']=\""."5"."\";".PHP_EOL;
	$filecontent.="\$conf['highscore_draw']=\""."3"."\";".PHP_EOL;
	$filecontent.="\$conf['highscore_loss']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['webjobexecution_enabled']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['webjobexecution_key']=\""."-"."\";".PHP_EOL;
	$filecontent.="\$conf['wordpresslogin_tableprefix']=\""."wp_"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_enabled']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_scouting_enabled']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_matchrequests_enabled']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_min_age_professional']=\""."16"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_salary_per_strength']=\""."50"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_scouting_break_hours']=\""."24"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_scouting_success_probability']=\""."75"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_scouting_min_strength']=\""."5"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_scouting_max_strength']=\""."70"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_scouting_standard_deviation']=\""."5"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_scouting_min_age']=\""."14"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_professionalmove_matches']=\""."30"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_professionalmove_technique']=\""."50"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_professionalmove_stamina']=\""."60"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_professionalmove_freshness']=\""."100"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_professionalmove_satisfaction']=\""."100"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_matchrequest_max_open_requests']=\""."2"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_matchrequest_max_futuredays']=\""."14"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_matchrequest_allowedtimes']=\""."14:00,15:00"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_matchrequest_accept_hours_in_advance']=\""."2"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_match_maxperday']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_strengthchange_verygood']=\""."2"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_strengthchange_good']=\""."1"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_strengthchange_bad']=\""."-1"."\";".PHP_EOL;
	$filecontent.="\$conf['youth_strengthchange_verybad']=\""."-2"."\";".PHP_EOL;
	$fp = fopen(CONFIGFILE, 'w+');
	fwrite($fp, $filecontent);
	fclose($fp);
	if (file_exists(CONFIGFILE)) {
		return "printPreDbCreate";
	}
}

/**
 * Step 4: Select whether migration or new creation
 */
function printPreDbCreate($messages) {
	?>
	<h2><?php echo $messages["predb_title"]; ?></h2>
	<form method="post">
		<label class="radio">
			<input type="radio" name="install" value="new" checked> <?php echo $messages["predb_label_new"]; ?>
		</label>
		<label class="radio">
			<input type="radio" name="install" value="migrate" disabled> <?php echo $messages["predb_label_migrate"]; ?>
		</label>
		<button type="submit" class="btn btn-primary"><?php echo $messages["button_next"]; ?></button>
		<input type="hidden" name="action" value="actionCreateDb">
	</form>
	<p><i class="icon-warning-sign"></i> <?php echo $messages["predb_label_warning"]; ?></p>
	<?php
}
function actionCreateDb() {
	include(CONFIGFILE);
	$db = DbConnection::getInstance();
	$db->connect($conf["db_host"], $conf["db_user"], $conf["db_passwort"], $conf["db_name"]);
	try {
		if ($_POST["install"] == "new") {
			loadAndExecuteDdl(DDL_FULL, $conf["db_prefix"], $db);
		} else {
			loadAndExecuteDdl(DDL_MIGRATION, $conf["db_prefix"], $db);
		}
	} catch(Exception $e) {
		global $errors;
		$errors[] = $e->getMessage();
		return "printPreDbCreate";
	}
	$db->close();
	return "printCreateUserForm";
}
function loadAndExecuteDdl($file,$prefix,DbConnection $db){
	$script=file_get_contents($file);
	if($prefix!==DEFAULT_DB_PREFIX)$script=str_replace(DEFAULT_DB_PREFIX."_",$prefix."_",$script);
	$queryResult=$db->connection->multi_query($script);
	while($db->connection->more_results()&&$db->connection->next_result());
	if(!$queryResult)throw new Exception("Database Query Error: ".$db->connection->error);
}
function printCreateUserForm($messages){
	?>
	<form method="post" class="form-horizontal">
		<fieldset>
			<legend><?php echo $messages["user_formtitle"] ?></legend>
			<div class="control-group">
			    <label class="control-label" for="name"><?php echo $messages["label_name"] ?></label>
			    <div class="controls">
			      <input type="text" id="name" name="name" required
			      	value="<?php echo (isset($_POST["name"])) ? $_POST["name"] : ""; ?>">
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="password"><?php echo $messages["label_password"] ?></label>
			    <div class="controls">
			      <input type="password" id="password" name="password" required
			      	value="<?php echo (isset($_POST["password"])) ? $_POST["password"] : ""; ?>">
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="email"><?php echo $messages["label_email"] ?></label>
			    <div class="controls">
			      <input type="email" id="email" name="email" required
			      	value="<?php echo (isset($_POST["email"])) ? $_POST["email"] : ""; ?>">
			    </div>
			</div>
		</fieldset>
		<div class="form-actions">
		  <button type="submit" class="btn btn-primary"><?php echo $messages["button_next"]; ?></button>
		</div>
		<input type="hidden" name="action" value="actionSaveUser">
	</form>
	<?php
}
function actionSaveUser() {
	global $errors;
	global $messages;
	$requiredFields = array("name", "password", "email");
	foreach($requiredFields as $requiredField) {
		if (!isset($_POST[$requiredField]) || !strlen($_POST[$requiredField])) {
			$errors[] = $messages["requires_value"] . ": " . $messages["label_" . $requiredField];
		}
	}
	if (count($errors)) {
		return "printCreateUserForm";
	}
	$salt = SecurityUtil::generatePasswordSalt();
	$password = SecurityUtil::hashPassword($_POST["password"], $salt);
	$columns["name"] = $_POST["name"];
	$columns["passwort"] = $password;
	$columns["passwort_salt"] = $salt;
	$columns["email"] = $_POST["email"];
	$columns["r_admin"] = "1";
	include(CONFIGFILE);
	$db = DbConnection::getInstance();
	$db->connect($conf["db_host"], $conf["db_user"], $conf["db_passwort"], $conf["db_name"]);
	$db->queryInsert($columns, $conf["db_prefix"] . "_admin");
	return "printFinalPage";
}
function printFinalPage($messages) {
	include(CONFIGFILE);
	?>
	<div class="alert alert-success"><strong><?php echo $messages["final_success_alert"]; ?></strong></div>
	<div class="alert"><strong><?php echo $messages["final_success_note"]; ?></strong></div>
	<p><i class="icon-arrow-right"></i> <a href="<?php echo $conf["context_root"]; ?>/admin"><?php echo $messages["final_link"]; ?></a></p>
	<?php
}
function is__writable($path){
	if($path[strlen($path)-1]=='/')return is__writable($path.uniqid(mt_rand()).'.tmp');
	elseif(is_dir($path))return is__writable($path.'/'.uniqid(mt_rand()).'.tmp');
	$rm=file_exists($path);
	$f=@fopen($path,'a');
	if($f===false)return false;
	fclose($f);
	if(!$rm)unlink($path);
	return true;}
class DbConnection {
	public $connection;
	private static $_instance;
	private $_queryCache;
	static function getInstance(){
		if(self::$_instance==NULL)self::$_instance=new DbConnection();
		return self::$_instance;}
	private function __construct(){}
	function connect($host,$user,$password,$dbname){
		@$this->connection=new mysqli($host,$user,$password,$dbname);
		@$this->connection->set_charset('utf8');
		if(mysqli_connect_error())throw new Exception('Database Connection Error ('.mysqli_connect_errno().') '.mysqli_connect_error());}
	function close(){$this->connection->close();}
	function querySelect($columns, $fromTable,$whereCondition,$parameters=null,$limit=null){
		$queryStr=$this->buildQueryString($columns,$fromTable,$whereCondition,$parameters,$limit);
		return $this->executeQuery($queryStr);}
	function queryCachedSelect($columns,$fromTable,$whereCondition,$parameters=null,$limit=null){
		$queryStr=$this->buildQueryString($columns,$fromTable,$whereCondition,$parameters,$limit);
		if(isset($this->_queryCache[$queryStr]))return $this->_queryCache[$queryStr];
		$result=$this->executeQuery($queryStr);
		$rows=[];
		while($row=$result->fetch_array())$rows[]=$row;
		$result->free();
		$this->_queryCache[$queryStr]=$rows;
		return $rows;}
	function queryUpdate($columns,$fromTable,$whereCondition,$parameters){
		$queryStr='UPDATE '.$fromTable.' SET '.self::buildColumnsValueList($columns).' WHERE '.self::buildWherePart($whereCondition,$parameters);
		$this->executeQuery($queryStr);
		$this->_queryCache=[];}
	function queryDelete($fromTable,$whereCondition,$parameters){
		$queryStr='DELETE FROM '.$fromTable.' WHERE '.self::buildWherePart($whereCondition,$parameters);
		$this->executeQuery($queryStr);
		$this->_queryCache=[];}
	function queryInsert($columns,$fromTable){
		$queryStr='INSERT '.$fromTable.' SET '.$this->buildColumnsValueList($columns);
		$this->executeQuery($queryStr);}
	function getLastInsertedId(){return $this->connection->insert_id;}
	function buildQueryString($columns,$fromTable,$whereCondition,$parameters=null,$limit=null){
		$queryStr='SELECT ';
		if(is_array($columns)){
			$firstColumn=TRUE;
			foreach($columns as $dbName=>$aliasName){
				if(!$firstColumn)$queryStr=$queryStr.', ';
				else $firstColumn=FALSE;
				if(is_numeric($dbName))$dbName=$aliasName;
				$queryStr=$queryStr.$dbName.' AS '.$aliasName;}}
		else $queryStr = $queryStr.$columns;
		$queryStr=$queryStr.' FROM '.$fromTable.' WHERE ';
		$wherePart=self::buildWherePart($whereCondition,$parameters);
		if(!empty($limit))$wherePart=$wherePart.' LIMIT ' . $limit;
		$queryStr=$queryStr.$wherePart;
		return $queryStr;}
	function buildColumnsValueList($columns){
		$queryStr='';
		$firstColumn=TRUE;
		foreach($columns as $dbName=>$value){
			if(!$firstColumn)$queryStr=$queryStr.', ';
			else $firstColumn=FALSE;
			if(strlen($value))$columnValue='\''.$this->connection->real_escape_string($value).'\'';
			else $columnValue='DEFAULT';
			$queryStr=$queryStr.$dbName.'='.$columnValue;}
		return $queryStr;}
	function buildWherePart($whereCondition,$parameters){
		$maskedParameters=self::prepareParameters($parameters);
		return vsprintf($whereCondition,$maskedParameters);}
	function prepareParameters($parameters){
		if(!is_array($parameters))$parameters=[$parameters];
		$arrayLength=count($parameters);
		for($i=0;$i<$arrayLength;$i++)$parameters[$i]=$this->connection->real_escape_string(trim($parameters[$i]));
		return $parameters;}
	function executeQuery($queryStr){
		$queryResult = $this->connection->query($queryStr);
		if(!$queryResult)throw new Exception('Database Query Error: '.$this->connection->error);
		return $queryResult;}}
class SecurityUtil{
	static function hashPassword($password,$salt){return hash('sha256',$salt.hash('sha256',$password));}
	static function isAdminLoggedIn(){
		if(isset($_SESSION['HTTP_USER_AGENT'])) {
			if($_SESSION['HTTP_USER_AGENT']!=md5($_SERVER['HTTP_USER_AGENT'])){
				self::logoutAdmin();
				return FALSE;}}
		else $_SESSION['HTTP_USER_AGENT']=md5($_SERVER['HTTP_USER_AGENT']);
		return(isset($_SESSION['valid'])&&$_SESSION['valid']);}
	static function logoutAdmin(){
		$_SESSION=[];
		session_destroy();}
	static function generatePassword(){
		$chars='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789%!=?#';
		return substr(str_shuffle($chars),0,8);}
	static function generatePasswordSalt(){return substr(self::generatePassword(),0,4);}
	static function generateSessionToken($userId,$salt){
		$useragent=(isset($_SESSION['HTTP_USER_AGENT']))?$_SESSION['HTTP_USER_AGENT']:'n.a.';
		return md5($salt.$useragent.$userId);}
	static function loginFrontUserUsingApplicationSession(WebSoccer $websoccer,$userId){
		$_SESSION['frontuserid']=$userId;
		session_regenerate_id();
		$userProvider=new SessionBasedUserAuthentication($websoccer);
		$userProvider->verifyAndUpdateCurrentUser($websoccer->getUser());}}