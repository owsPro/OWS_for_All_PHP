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
function prepareFielfValueForSaving($fieldValue) {
	$preparedValue = trim($fieldValue);
	if (get_magic_quotes_gpc()) $preparedValue = stripslashes($fieldValue);
	return $preparedValue; }
$mainTitle = $i18n->getMessage('all_settings_title');
include(BASE_FOLDER . '/cache/settingsconfig.inc.php');

//+ owsPro - Provide basic settings, which is supplemented or overwritten with /cache/settingsconfig.inc.php'.
include(BASE_FOLDER . '/generated/settingsconfig.php');

if (!$admin['r_admin'] && !$admin['r_demo']) {
  echo '<p>'. $i18n->getMessage('error_access_denied') . '</p>';
  exit; }
if (!$show) {
	$tabs = array();
	foreach ($setting as $settingId => $settingData) {
		$settingInfo = json_decode($settingData, true);
		$tabs[$settingInfo['category']][$settingId] = $settingInfo; }
  ?>
  <h1><?php echo $mainTitle; ?></h1>
  <form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post' class='form-horizontal'>
    <input type='hidden' name='show' value='speichern'>
	<input type='hidden' name='site' value='<?php echo $site; ?>'>
	<ul class='nav nav-tabs'>
	<?php
	$firstTab = TRUE;
	foreach ($tabs as $tabId => $settings) {
		echo '<li';
		if ($firstTab) echo ' class=\'active\'';
		echo '><a href=\'#'. $tabId . '\' data-toggle=\'tab\'>'. $i18n->getMessage('settings_tab_' . $tabId) .'</a></li>';
		$firstTab = FALSE; }
	?>
	</ul>
	<div class='tab-content'>
	<?php
	$firstTab = TRUE;
	foreach ($tabs as $tabId => $settings) {
		echo '<div class=\'tab-pane';
		if ($firstTab) echo ' active';
		echo '\' id=\''. $tabId . '\'>';
		foreach ($settings as $settingId => $settingInfo) echo FormBuilder::createFormGroup($i18n, $settingId, $settingInfo, $website->getConfig($settingId), 'settings_label_');
		echo '</div>';
		$firstTab = FALSE; }
	?>
	</div>
	<div class='form-actions'>
		<input type='submit' class='btn btn-primary' accesskey='s' title='Alt + s' value='<?php echo $i18n->getMessage('button_save'); ?>'>
		<input type='reset' class='btn' value='<?php echo $i18n->getMessage('button_reset'); ?>'>
	</div>
  </form>
  <?php }
elseif ($show == 'speichern') {
  if ($admin['r_demo']) $err[] = $i18n->getMessage('validationerror_no_changes_as_demo');
  if (isset($err)) include('validationerror.inc.php');
  else {
  	$newSettings = array();
  	foreach ($setting as $settingId => $settingData) $newSettings[$settingId] = (isset($_POST[$settingId])) ? prepareFielfValueForSaving($_POST[$settingId]) : '';
  	$cf = ConfigFileWriter::getInstance($conf);
  	$cf->saveSettings($newSettings);
	include('success.inc.php');
	echo createWarningMessage($i18n->getMessage('settings_saved_note_restartjobs'), $i18n->getMessage('settings_saved_note_restartjobs_details')); }}
