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
					include($_SERVER['DOCUMENT_ROOT'].'/admin/config/global.inc.php');include($_SERVER['DOCUMENT_ROOT'].'/cache/wsconfigadmin.inc.php');$i18n=I18n::getInstance(Config('supported_languages'));if(isset($_GET['lang']))
					$i18n->setCurrentLanguage($_GET['lang']);include(sprintf($_SERVER['DOCUMENT_ROOT'].'/cache/adminmessages_%s.inc.php',$i18n->getCurrentLanguage()));include(sprintf($_SERVER['DOCUMENT_ROOT'].
					'/languages/messages_%s.php',$i18n->getCurrentLanguage()));$errors=[];$inputUser=(isset($_POST['inputUser']))?$_POST['inputUser']:FALSE;$inputPassword=(isset($_POST['inputPassword']))?$_POST['inputPassword']:FALSE;
					$forwarded=(isset($_GET['forwarded'])&&$_GET['forwarded']==1)?TRUE:FALSE;$loggedout=(isset($_GET['loggedout'])&&$_GET['loggedout']==1)?TRUE:FALSE;$newpwd=(isset($_GET['newpwd'])&&$_GET['newpwd']==1)?TRUE:FALSE;if($inputUser or$inputPassword){
					if(!$inputUser)$errors['inputUser']=Message('login_error_nousername');if(!$inputPassword)$errors['inputPassword']=Message('login_error_nopassword');if(count((array)$errors)==0){$columns=['id','passwort','passwort_salt','passwort_neu','name'];
					$fromTable=$conf['db_prefix'].'_admin';$whereCondition='name=\'%s\'';$parameters=$inputUser;$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters);if($result->num_rows<1)$errors['inputUser']=Message('login_error_unknownusername');
					else{$admin=$result->fetch_array();$hashedPw=SecurityUtil::hashPassword($inputPassword,$admin['passwort_salt']);if($admin['passwort']==$hashedPw||$admin['passwort_neu']==$hashedPw){ini_set('session.cookie_httponly',1);
					ini_set('session.use_only_cookies',1);ini_set('session.cookie_secure',1);session_start();$_SESSION['valid']=1;$_SESSION['userid']=$admin['id'];if($admin['passwort_neu']==$hashedPw){$columns=['passwort'=>$hashedPw,'passwort_neu_angefordert'=>0,
					'passwort_neu'=>''];$fromTable=$conf['db_prefix'].'_admin';$whereCondition='id=%d';$parameter=$admin['id'];$db->queryUpdate($columns,$fromTable,$whereCondition,$parameter);}if($admin['name']){$ip=getenv('REMOTE_ADDR');
					$content=$admin['name'].', '.$ip.', '.date('d.m.y - H:i:s');$content.='\n';$datei='../generated/adminlog.php';$fp=fopen($datei,'a+');if(filesize($datei))$inhalt=fread($fp,filesize($datei));else$inhalt='';$inhalt.=$content;fwrite($fp,$content);
					fclose($fp);}header('location:index.php');}else{$errors['inputPassword']=Message('login_error_invalidpassword');sleep(5);}}}}header('Content-type:text/html;charset=utf-8');?><!DOCTYPE html><html lang='de'><head>
    				<title><?php echo Message('login_title');?></title><link href='bootstrap/css/bootstrap.min.css'rel='stylesheet'media='screen'><link rel='shortcut icon'type='image/x-icon'href='../favicon.ico' /><meta charset='UTF-8'><style type='text/css'>
    				body{padding-top:100px;padding-bottom:40px;}</style></head><body><div class='container'><h1><?php echo Message('login_title');?></h1><?php if($forwarded)echo createWarningMessage(Message('login_alert_accessdenied_title'),
					Message('login_alert_accessdenied_content'));elseif($loggedout) echo createSuccessMessage(Message('login_alert_logoutsuccess_title'),Message('login_alert_logoutsuccess_content'));elseif($newpwd)
					echo createSuccessMessage(Message('login_alert_sentpassword_title'),Message('login_alert_sentpassword_content'));elseif(count((array)$errors)>0)echo createErrorMessage(Message('login_alert_error_title'),
					Message('login_alert_error_content'));flags('/admin/login.php?lang=');?><form action='login.php'method='post'class='form-horizontal'><div class='control-group<?php if(isset($errors['inputUser']))echo'error';?>'>
					<label class='control-label'for='inputUser'><?php echo Message('login_label_user');?></label><div class='controls'><input type='text'name='inputUser'id='inputUser'placeholder='<?php echo Message('login_label_user');?>'required></div></div>
		  			<div class='control-group<?php if(isset($errors['inputPassword']))echo'error';?>'><label class='control-label'for='inputPassword'><?php echo Message('login_label_password');?></label><div class='controls'>
			  		<input type='password'name='inputPassword'id='inputPassword'placeholder='<?php echo Message('login_label_password');?>'required></div></div><div class='control-group'><div class='controls'><button type='submit'class='btn'>
			  		<?php echo Message('login_button_logon');?></button></div></div></form><p><a href='forgot-password.php?lang=de'><?php echo Message('login_link_forgotpassword');?></a><hr><footer><p>Powered by <a href='https://github.com/owsPro/OWS_for_All_PHP'
        			target='_blank'>OWS_for_All_PHP</a></p></footer></div><script src='https://code.jquery.com/jquery-latest.min.js'></script><script src='bootstrap/js/bootstrap.min.js'></script></body></html>