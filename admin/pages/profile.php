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
$mainTitle = $i18n->getMessage("profile_title");
if (!$show) { ?>
  <h1><?php echo $mainTitle; ?></h1>
  <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" class="form-horizontal">
    <input type="hidden" name="show" value="save">
	<input type="hidden" name="site" value="<?php echo $site; ?>">
	<fieldset>
    	<legend><?php echo escapeOutput($admin['name']); ?></legend><?php
	$formFields = ["email" => ["type" => "email", "value" => $admin['email'], "required" => "true"],
	"newpassword" => ["type" => "password", "value" => ""],
	"repeatpassword" => ["type" => "password", "value" => ""],
	"language" => ["type" => "select", "value" => $admin["lang"], "selection" => $website->getConfig("supported_languages")]];
	foreach ($formFields as $fieldId => $fieldInfo) echo FormBuilder::createFormGroup($i18n, $fieldId, $fieldInfo, $fieldInfo["value"], "profile_label_"); ?></fieldset>
	<div class="form-actions">
		<input type="submit" class="btn btn-primary" accesskey="s" title="Alt + s" value="<?php echo $i18n->getMessage("button_save"); ?>">
		<input type="reset" class="btn" value="<?php echo $i18n->getMessage("button_reset"); ?>"></div></form><?php }
elseif ($show == "save") {
  if (!$_POST['email']) $err[] = $i18n->getMessage("profile_validationerror_email");
  if ($_POST['newpassword'] && (strlen(trim($_POST['newpassword'])) < 5)) $err[] = $i18n->getMessage("profile_validationerror_password_too_short");
  if ($_POST['newpassword'] != $_POST['repeatpassword']) $err[] = $i18n->getMessage("profile_validationerror_wrong_repeated_password");
  if ($admin['r_demo']) $err[] = $i18n->getMessage("validationerror_no_changes_as_demo");
  if (isset($err)) include("validationerror.inc.php");
  else {
    echo "<h1>". $mainTitle ." &raquo; ". $i18n->getMessage("subpage_save_title") . "</h1>";
    $fromTable = $conf['db_prefix'] ."_admin";
    $whereCondition = "id = %d";
    $parameter = $admin['id'];
    if ($_POST['newpassword']) {
		if (!strlen($admin["passwort_salt"])) {
			$salt = SecurityUtil::generatePasswordSalt();
			$db->queryUpdate(array("passwort_salt" => $salt), $fromTable, $whereCondition, $parameter);}
		else $salt = $admin["passwort_salt"];
		$passwort = SecurityUtil::hashPassword(trim($_POST['newpassword']), $salt);}
    else $passwort = $admin['passwort'];
	$columns = array("passwort" => $passwort, "email" => $_POST['email'], "lang" => $_POST['language']);
	$db->queryUpdate($columns, $fromTable, $whereCondition, $parameter);
	echo createSuccessMessage($i18n->getMessage("alert_save_success"), "");
    echo "<p>&raquo; <a href=\"?site=". $site ."\">". $i18n->getMessage("back_label") . "</a></p>\n";}}
