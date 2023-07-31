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
error_reporting(E_ALL);
ini_set('session.cookie_httponly',1);
ini_set('session.use_only_cookies',1);
ini_set('session.cookie_secure',1);
session_start();
$supportedLanguages = array("de" => "Deutsch", "en" => "English");

ignore_user_abort(TRUE);
set_time_limit(0);

include($_SERVER['DOCUMENT_ROOT']. "/classes/DbConnection.class.php");

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

	?>

	<?php

	$requirments = array();

	$writableFiles = explode(",", WRITABLE_FOLDERS);
	foreach ($writableFiles as $writableFile) {
		$file =$_SERVER['DOCUMENT_ROOT']. "/" . $writableFile;

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
		echo "<input type=\"hidden\" name=\"action\" value=\"actionMoveFiles\">";
		echo "</form>";
	} else {
		echo "<p>". $messages["check_req_error"] . "</p>";
	}
}


function actionMoveFiles() {
	$fileNames = array("config.inc.php", "adminlog.php", "imprint.php", "entitylog.php");
	$oldDir = $_SERVER['DOCUMENT_ROOT']. "/admin/config/";
	$newDir = $_SERVER['DOCUMENT_ROOT']. "/generated/";
	foreach ($fileNames as $fileName) {
		if (file_exists($oldDir . $fileName)) {
			rename($oldDir . $fileName, $newDir . $fileName);
		}
	}
	return "printFinalPage";
}


/**
 * Final page
 */
function printFinalPage($messages) {
	include(CONFIGFILE);
	?>

	<div class="alert alert-success"><strong><?php echo $messages["final_success_alert"]; ?></strong></div>

	<div class="alert"><strong><?php echo $messages["final_success_note"]; ?></strong></div>

	<p><i class="icon-arrow-right"></i> <a href="<?php echo $conf["context_root"]; ?>/admin"><?php echo $messages["final_link"]; ?></a></p>
	<?php
}
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <title>Open WebSoccer-Sim Update Installation</title>
    <link href="../admin/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" />
    <meta charset="UTF-8">
    <style type="text/css">
      body {
        padding-top: 100px;
        padding-bottom: 40px;
      }
    </style>
  </head>
  <body>

	<div class="container">

		<h1>Open WebSoccer-Sim Update Installation</h1>

		<hr>

		<?php

		$errors = array();

		$messagesIncluded = FALSE;
		if(isset($_SESSION["lang"])) {
			include("messages_" . $_SESSION["lang"] . ".inc.php");
			$messagesIncluded = $_SESSION["lang"];
		}

		$action = (isset($_REQUEST["action"])) ? $_REQUEST["action"] : "";
		if (!strlen($action) || substr($action, 0, 6) !== "action") {
			$view = "printWelcomeScreen";
		} else {
			$view = $action();
		}

		if(isset($_SESSION["lang"]) && $_SESSION["lang"] !== $messagesIncluded) {
			include("messages_" . $_SESSION["lang"] . ".inc.php");
		}

		if (count($errors)) {
			foreach($errors as $error) {
				echo "<div class=\"alert alert-error\">$error</div>";
			}
		}

		if (isset($messages)) {
			$view($messages);
		} else {
			$view();
		}

		?>

      <hr>

      <footer>
        <p>Powered by <a href="http://www.websoccer-sim.com" target="_blank">Open WebSoccer-Sim</a></p>
      </footer>
	</div>

    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="../admin/bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
<?php
function is__writable($path) {
	if ($path{strlen($path)-1}=='/') // recursively return a temporary file path
		return is__writable($path.uniqid(mt_rand()).'.tmp');
	else if (is_dir($path))
		return is__writable($path.'/'.uniqid(mt_rand()).'.tmp');
	$rm = file_exists($path);
	$f = @fopen($path, 'a');
	if ($f===false)
		return false;
	fclose($f);
	if (!$rm)
		unlink($path);
	return true;}