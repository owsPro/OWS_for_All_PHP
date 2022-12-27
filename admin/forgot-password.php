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
$i18n = I18n::getInstance($website->getConfig('supported_languages'));
if (isset($_GET['lang'])) $i18n->setCurrentLanguage($_GET['lang']);
include(__DIR__ .'/..' . '/cache/adminmessages_'. $_GET['lang'] .'inc.php');
//+ owsPro - Include set language file
htmlentities(include(__DIR__ .'/..' . '/languages/messages_'. $_GET['lang'] .'.php'));

$inputEmail = (isset($_POST['inputEmail'])) ? trim($_POST['inputEmail']) : FALSE;
if ($inputEmail) {
	$now = $website->getNowAsTimestamp();
	if (count($errors) == 0) {
		$result = $db->querySelect(['id', 'passwort_neu_angefordert', 'name', 'passwort_salt'], $conf['db_prefix'] .'_admin', 'email = \'%s\'', $inputEmail);
		$admin = $result->fetch_array();
		if($result->num_rows < 1) $errors['inputEmail'] = $i18n->getMessage('sendpassword_admin_usernotfound');
		elseif ($admin['passwort_neu_angefordert'] > ($now-120*60)) $errors['inputEmail'] = $i18n->getMessage('sendpassword_admin_alreadysent');
		else {
			$newPassword = SecurityUtil::generatePassword();
			$hashedPw = SecurityUtil::hashPassword($newPassword, $admin['passwort_salt']);
			$db->queryUpdate(['passwort_neu' => $hashedPw, 'passwort_neu_angefordert' => $now], $conf['db_prefix'] .'_admin', 'id = %d', $admin['id']);
            try{_sendEmail($inputEmail, $newPassword, $website, $i18n);
            	header('location: login.php?newpwd=1');
            	die();}
            catch(Exception $e) {$errors['inputEmail'] = $e->getMessage();}}}}
function _sendEmail($email, $password, $website, $i18n) {
	$tplparameters['newpassword'] = $password;
	EmailHelper::sendSystemEmailFromTemplate($website, $i18n, $email, $i18n->getMessage('sendpassword_admin_email_subject'), 'sendpassword_admin', $tplparameters);} ?>
<!DOCTYPE html>
<html lang='de'>
  <head>
    <title>AdminCenter - <?php echo $i18n->getMessage('sendpassword_admin_title'); ?></title>
    <link href='bootstrap/css/bootstrap.min.css' rel='stylesheet' media='screen'>
    <meta charset='UTF-8'>
    <link rel='shortcut icon' type='image/x-icon' href='../favicon.ico' />
    <style type='text/css'>
      body {padding-top: 100px; padding-bottom: 40px;}</style></head>
  <body>
	<div class='container'>
		<h1><?php echo $i18n->getMessage('sendpassword_admin_title'); ?></h1>
	<a href="/admin/forgot-password.php?lang=de"><img src="/img/flags/de.png" width="24" height="24" alt="Deutsch" title="Deutsch" /></a>
	<a href="/admin/forgot-password.php?lang=en"><img src="/img/flags/en.png" width="24" height="24" alt="English" title="English" /></a>
	<a href="/admin/forgot-password.php?lang=es"><img src="/img/flags/es.png" width="24" height="24" alt="???lang_label_es???" title="???lang_label_es???" /></a>
	<a href="/admin/forgot-password.php?lang=dk"><img src="/img/flags/dk.png" width="24" height="24" alt="???lang_label_dk???" title="???lang_label_dk???" /></a>
	<a href="/admin/forgot-password.php?lang=ee"><img src="/img/flags/ee.png" width="24" height="24" alt="???lang_label_ee???" title="???lang_label_ee???" /></a>
	<a href="/admin/forgot-password.php?lang=fi"><img src="/img/flags/fi.png" width="24" height="24" alt="???lang_label_fi???" title="???lang_label_fi???" /></a>
	<a href="/admin/forgot-password.php?lang=fr"><img src="/img/flags/fr.png" width="24" height="24" alt="???lang_label_fr???" title="???lang_label_fr???" /></a>
	<a href="/admin/forgot-password.php?lang=id"><img src="/img/flags/id.png" width="24" height="24" alt="???lang_label_id???" title="???lang_label_id???" /></a>
	<a href="/admin/forgot-password.php?lang=it"><img src="/img/flags/it.png" width="24" height="24" alt="???lang_label_it???" title="???lang_label_it???" /></a>
	<a href="/admin/forgot-password.php?lang=lv"><img src="/img/flags/lv.png" width="24" height="24" alt="???lang_label_lv???" title="???lang_label_lv???" /></a>
	<a href="/admin/forgot-password.php?lang=lt"><img src="/img/flags/lt.png" width="24" height="24" alt="???lang_label_lt???" title="???lang_label_lt???" /></a>
	<a href="/admin/forgot-password.php?lang=nl"><img src="/img/flags/nl.png" width="24" height="24" alt="???lang_label_nl???" title="???lang_label_nl???" /></a>
	<a href="/admin/forgot-password.php?lang=pl"><img src="/img/flags/pl.png" width="24" height="24" alt="???lang_label_pl???" title="???lang_label_pl???" /></a>
	<a href="/admin/forgot-password.php?lang=pt"><img src="/img/flags/pt.png" width="24" height="24" alt="???lang_label_pt???" title="???lang_label_pt???" /></a>
	<a href="/admin/forgot-password.php?lang=br"><img src="/img/flags/br.png" width="24" height="24" alt="???lang_label_br???" title="???lang_label_br???" /></a>
	<a href="/admin/forgot-password.php?lang=ro"><img src="/img/flags/ro.png" width="24" height="24" alt="???lang_label_ro???" title="???lang_label_ro???" /></a>
	<a href="/admin/forgot-password.php?lang=se"><img src="/img/flags/se.png" width="24" height="24" alt="???lang_label_se???" title="???lang_label_se???" /></a>
	<a href="/admin/forgot-password.php?lang=sk"><img src="/img/flags/sk.png" width="24" height="24" alt="???lang_label_sk???" title="???lang_label_sk???" /></a>
	<a href="/admin/forgot-password.php?lang=si"><img src="/img/flags/si.png" width="24" height="24" alt="???lang_label_si???" title="???lang_label_si???" /></a>
	<a href="/admin/forgot-password.php?lang=cz"><img src="/img/flags/cz.png" width="24" height="24" alt="???lang_label_cz???" title="???lang_label_cz???" /></a>
	<a href="/admin/forgot-password.php?lang=tr"><img src="/img/flags/tr.png" width="24" height="24" alt="???lang_label_tr???" title="???lang_label_tr???" /></a>
	<a href="/admin/forgot-password.php?lang=hu"><img src="/img/flags/hu.png" width="24" height="24" alt="???lang_label_hu???" title="???lang_label_hu???" /></a>
	<a href="/admin/forgot-password.php?lang=jp"><img src="/img/flags/jp.png" width="24" height="24" alt="???lang_label_jp???" title="???lang_label_jp???" /></a>
	<br><br><?php
if (count((array)$errors) > 0) {
	foreach($errors as $key => $message) echo createErrorMessage($i18n->getMessage('subpage_error_title'), $message);} ?>
		<p><?php echo $i18n->getMessage('sendpassword_admin_intro'); ?></p>
		<form action='forgot-password.php' method='post' class='form-horizontal'>
		  <div class='control-group<?php if (isset($errors['inputEmail'])) echo ' error'; ?>'>
			<label class='control-label' for='inputEmail'><?php echo $i18n->getMessage('sendpassword_admin_label_email'); ?></label>
			<div class='controls'>
			  <input type='email' name='inputEmail' id='inputEmail' placeholder='E-Mail' value='<?php echo escapeOutput($inputEmail); ?>'></div></div>
		  <div class='control-group'>
			<div class='controls'>
			  <button type='submit' class='btn'><?php echo $i18n->getMessage('sendpassword_admin_button'); ?></button></div></div></form>
		<p><a href='login.php?lang=de'><?php echo $i18n->getMessage('sendpassword_admin_loginlink'); ?></a>
      <hr>
      <footer>
        <p>Powered by <a href='http://www.websoccer-sim.com' target='_blank'>OpenWebSoccer-Sim</a></p></footer></div>
    <script src='https://code.jquery.com/jquery-latest.min.js'></script>
    <script src='bootstrap/js/bootstrap.min.js'></script></body></html>
