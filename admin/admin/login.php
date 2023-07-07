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
include(__DIR__ .'/..' . '/admin/config/global.inc.php');
include(__DIR__ .'/..' . '/admin/functions.inc.php');
include(CONFIGCACHE_FILE_ADMIN);
$i18n = I18n::getInstance($website->getConfig('supported_languages'));
if (isset($_GET['lang'])) {
	$i18n->setCurrentLanguage($_GET['lang']);
	switch ($_GET['lang']) {
        case ('de'): htmlentities(include(__DIR__ .'/..' . '/cache/adminmessages_de.inc.php')); htmlentities(include(__DIR__ .'/..' . '/languages/messages_de.php')); break;
        case ('en'): htmlentities(include(__DIR__ .'/..' . '/cache/adminmessages_en.inc.php')); htmlentities(include(__DIR__ .'/..' . '/languages/messages_en.php')); break;
        case ('es'): htmlentities(include(__DIR__ .'/..' . '/cache/adminmessages_es.inc.php')); htmlentities(include(__DIR__ .'/..' . '/languages/messages_es.php')); break;
        default: echo '<pre>										<b>This language is not yet fully supported!</b></pre>';}}
$inputUser = (isset($_POST['inputUser'])) ? $_POST['inputUser'] : FALSE;
$inputPassword = (isset($_POST['inputPassword'])) ? $_POST['inputPassword'] : FALSE;
$forwarded = (isset($_GET['forwarded']) && $_GET['forwarded'] == 1) ? TRUE : FALSE;
$loggedout = (isset($_GET['loggedout']) && $_GET['loggedout'] == 1) ? TRUE : FALSE;
$newpwd = (isset($_GET['newpwd']) && $_GET['newpwd'] == 1) ? TRUE : FALSE;
if ($inputUser or $inputPassword) {
	if (!$inputUser) $errors['inputUser'] = $i18n->getMessage('login_error_nousername');
	if (!$inputPassword) $errors['inputPassword'] = $i18n->getMessage('login_error_nopassword');
	if (count((array)$errors) == 0) {
		$columns = array('id', 'passwort', 'passwort_salt', 'passwort_neu', 'name');
		$fromTable = $conf['db_prefix'] .'_admin';
		$whereCondition = 'name = \'%s\'';
		$parameters = $inputUser;
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters);
		if($result->num_rows < 1) $errors['inputUser'] = $i18n->getMessage('login_error_unknownusername');
		else {
			$admin = $result->fetch_array();
			$hashedPw = SecurityUtil::hashPassword($inputPassword, $admin['passwort_salt']);
			if ($admin['passwort'] == $hashedPw || $admin['passwort_neu'] == $hashedPw) {
				//- owsPro - Error: session_regenerate_id(): Cannot regenerate session id - session is not active at PHP 8 prevent with session_start();
				//- session_regenerate_id();
				session_start();
				$_SESSION['valid'] = 1;
				$_SESSION['userid'] = $admin['id'];
				if ($admin['passwort_neu'] == $hashedPw) {
					$columns = array('passwort' => $hashedPw, 'passwort_neu_angefordert' => 0, 'passwort_neu' => '');
					$fromTable = $conf['db_prefix'] .'_admin';
					$whereCondition = 'id = %d';
					$parameter = $admin['id'];
					$db->queryUpdate($columns, $fromTable, $whereCondition, $parameter); }
				  if ($admin['name']) {
					$ip = getenv('REMOTE_ADDR');
					$content = $admin['name'] .', '. $ip .', '. date('d.m.y - H:i:s');
					$content .= "\n";
					$datei = '../generated/adminlog.php';
					$fp = fopen($datei, 'a+');
					if (filesize($datei)) $inhalt = fread($fp, filesize($datei));
					else $inhalt = '';
					$inhalt .= $content;
					fwrite($fp, $content);
					fclose($fp); }
				header('location: index.php'); }
			else {
				$errors['inputPassword'] = $i18n->getMessage('login_error_invalidpassword');
				sleep(5);}}}}
header('Content-type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang='de'>
  <head>
    <title><?php echo $i18n->getMessage('login_title');?></title>
    <link href='bootstrap/css/bootstrap.min.css' rel='stylesheet' media='screen'>
    <link rel='shortcut icon' type='image/x-icon' href='../favicon.ico' />
    <meta charset='UTF-8'>
    <style type='text/css'>
    	body {padding-top: 100px; padding-bottom: 40px;}</style></head><body>
	<div class='container'>
		<h1><?php echo $i18n->getMessage('login_title');?></h1> <?php
if ($forwarded) echo createWarningMessage($i18n->getMessage('login_alert_accessdenied_title'), $i18n->getMessage('login_alert_accessdenied_content'));
elseif ($loggedout) echo createSuccessMessage($i18n->getMessage('login_alert_logoutsuccess_title'), $i18n->getMessage('login_alert_logoutsuccess_content'));
elseif ($newpwd) echo createSuccessMessage($i18n->getMessage('login_alert_sentpassword_title'), $i18n->getMessage('login_alert_sentpassword_content'));
elseif (count((array)$errors) > 0) echo createErrorMessage($i18n->getMessage('login_alert_error_title'), $i18n->getMessage('login_alert_error_content'))
//+ owsPro - Flags to select languages
?>
	<a href="/admin/login.php?lang=de"><img src="/img/flags/de.png" width="24" height="24" alt="deutsch" title="deutsch" /></a>
	<a href="/admin/login.php?lang=en"><img src="/img/flags/en.png" width="24" height="24" alt="english" title="english" /></a>
	<a href="/admin/login.php?lang=es"><img src="/img/flags/es.png" width="24" height="24" alt="español" title="español" /></a>
	<a href="/admin/login.php?lang=pt"><img src="/img/flags/pt.png" width="24" height="24" alt="português" title="português" /></a>
	<a href="/admin/login.php?lang=dk"><img src="/img/flags/dk.png" width="24" height="24" alt="dansk" title="dansk" /></a>
	<a href="/admin/login.php?lang=ee"><img src="/img/flags/ee.png" width="24" height="24" alt="eesti" title="eesti" /></a>
	<a href="/admin/login.php?lang=fi"><img src="/img/flags/fi.png" width="24" height="24" alt="suomalainen" title="suomalainen" /></a>
	<a href="/admin/login.php?lang=fr"><img src="/img/flags/fr.png" width="24" height="24" alt="français" title="français" /></a>
	<a href="/admin/login.php?lang=id"><img src="/img/flags/id.png" width="24" height="24" alt="indonesia" title="indonesia" /></a>
	<a href="/admin/login.php?lang=it"><img src="/img/flags/it.png" width="24" height="24" alt="italiano" title="italiano" /></a>
	<a href="/admin/login.php?lang=lv"><img src="/img/flags/lv.png" width="24" height="24" alt="latvieši" title="latvieši" /></a>
	<a href="/admin/login.php?lang=lt"><img src="/img/flags/lt.png" width="24" height="24" alt="lietuviškas" title="lietuviškas" /></a>
	<a href="/admin/login.php?lang=nl"><img src="/img/flags/nl.png" width="24" height="24" alt="nederlands" title="nederlands" /></a>
	<a href="/admin/login.php?lang=pl"><img src="/img/flags/pl.png" width="24" height="24" alt="polska" title="polska" /></a>

	<a href="/admin/login.php?lang=br"><img src="/img/flags/br.png" width="24" height="24" alt="???lang_label_br???" title="???lang_label_br???" /></a>
	<a href="/admin/login.php?lang=ro"><img src="/img/flags/ro.png" width="24" height="24" alt="???lang_label_ro???" title="???lang_label_ro???" /></a>
	<a href="/admin/login.php?lang=se"><img src="/img/flags/se.png" width="24" height="24" alt="???lang_label_se???" title="???lang_label_se???" /></a>

	<a href="/admin/login.php?lang=sk"><img src="/img/flags/sk.png" width="24" height="24" alt="???lang_label_sk???" title="???lang_label_sk???" /></a>
	<a href="/admin/login.php?lang=si"><img src="/img/flags/si.png" width="24" height="24" alt="???lang_label_si???" title="???lang_label_si???" /></a>
	<a href="/admin/login.php?lang=cz"><img src="/img/flags/cz.png" width="24" height="24" alt="???lang_label_cz???" title="???lang_label_cz???" /></a>

	<a href="/admin/login.php?lang=tr"><img src="/img/flags/tr.png" width="24" height="24" alt="???lang_label_tr???" title="???lang_label_tr???" /></a>
	<a href="/admin/login.php?lang=hu"><img src="/img/flags/hu.png" width="24" height="24" alt="???lang_label_hu???" title="???lang_label_hu???" /></a>
	<a href="/admin/login.php?lang=jp"><img src="/img/flags/jp.png" width="24" height="24" alt="???lang_label_jp???" title="???lang_label_jp???" /></a>
	<br><br>
		<form action='login.php' method='post' class='form-horizontal'>
		  <div class='control-group<?php if (isset($errors['inputUser'])) echo ' error'; ?>'>
			<label class='control-label' for='inputUser'><?php echo $i18n->getMessage('login_label_user');?></label>
			<div class='controls'>
			  <input type='text' name='inputUser' id='inputUser' placeholder='<?php echo $i18n->getMessage('login_label_user');?>' required></div></div>
		  <div class='control-group<?php if (isset($errors['inputPassword'])) echo ' error'; ?>'>
			<label class='control-label' for='inputPassword'><?php echo $i18n->getMessage('login_label_password');?></label>
			<div class='controls'>
			  <input type='password' name='inputPassword' id='inputPassword' placeholder='<?php echo $i18n->getMessage('login_label_password');?>' required></div></div>
		  <div class='control-group'>
			<div class='controls'>
			  <button type='submit' class='btn'><?php echo $i18n->getMessage('login_button_logon');?></button></div></div></form>
		<p><a href='forgot-password.php?lang=de'><?php echo $i18n->getMessage('login_link_forgotpassword');?></a>
      <hr>
      <footer>
        <p>Powered by <a href='https://github.com/owsPro/OWS_for_All_PHP' target='_blank'>OWS_for_All_PHP</a></p></footer></div>
    <script src='https://code.jquery.com/jquery-latest.min.js'></script>
    <script src='bootstrap/js/bootstrap.min.js'></script></body></html>
