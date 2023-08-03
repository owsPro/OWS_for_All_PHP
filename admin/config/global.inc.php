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
	include($_SERVER['DOCUMENT_ROOT'].'/owsPro.php');error_reporting(E_ERROR);spl_autoload_register('classes_autoloader');include($_SERVER['DOCUMENT_ROOT'].'/generated/config.inc.php');if(!isset($conf)){header('location: install/index.php');exit;}
	$page=null;$action=null;$block=null;try{$website=WebSoccer::getInstance();if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/cache/wsconfigfront.inc.php'))$website->resetConfigCache();}catch(Exception$e){try{$log=new FileWriter('errorlog.txt');
	$log->writeLine('Website Configuration Error: '.$e->getMessage());$log->close();}catch(Exception$e){}header('HTTP/1.0 500 Error');die();}try{$db=DbConnection::getInstance();$db->connect(Config('db_host'),Config('db_user'),Config('db_passwort'),Config('db_name'));}
	catch(Exception$e){try{$log=new FileWriter('dberrorlog.txt');$log->writeLine('DB Error: '.$e->getMessage());$log->close();}catch(Exception$e){}die('<h1>Sorry, our data base is currently not available</h1><p>We are working on it.</p>');}
	$handler=new DbSessionManager($db,$website);session_set_save_handler(array($handler,'open'),array($handler,'close'),array($handler,'read'),array($handler,'write'),array($handler,'destroy'),array($handler,'gc'));register_shutdown_function('session_write_close');
	ini_set('session.cookie_httponly',1);ini_set('session.use_only_cookies',1);ini_set('session.cookie_secure',1);session_start();try{date_default_timezone_set(Config('time_zone'));}catch(Exception$e){}