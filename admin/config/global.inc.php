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
define('DEBUG', FALSE);
if (DEBUG) error_reporting(E_ALL);
else error_reporting(E_ERROR);
function classes_autoloader($class) {
	$subforder = '';
	if (substr($class, -9) === 'Converter') $subforder = 'converters/';
	elseif (substr($class, -4) === 'Skin') $subforder = 'skins/';
	elseif (substr($class, -5) === 'Model') $subforder = 'models/';
	elseif (substr($class, -9) === 'Validator') $subforder = 'validators/';
	elseif (substr($class, -10) === 'Controller') $subforder = 'actions/';
	elseif (substr($class, -7) === 'Service') $subforder = 'services/';
	elseif (substr($class, -3) === 'Job') $subforder = 'jobs/';
	elseif (substr($class, -11) === 'LoginMethod') $subforder = 'loginmethods/';
	elseif (substr($class, -5) === 'Event') $subforder = 'events/';
	elseif (substr($class, -6) === 'Plugin') $subforder = 'plugins/';
	include(__DIR__ .'/../..' . '/classes/' . $subforder . $class . '.class.php'); }
spl_autoload_register('classes_autoloader');
define('FOLDER_MODULES', __DIR__ .'/../..' . '/modules');
define('MODULE_CONFIG_FILENAME', 'module.xml');
define('GLOBAL_CONFIG_FILE', __DIR__ .'/../..' . '/generated/config.inc.php');
define('CONFIGCACHE_FILE_FRONTEND', __DIR__ .'/../..' . '/cache/wsconfigfront.inc.php');
define('CONFIGCACHE_FILE_ADMIN', __DIR__ .'/../..' . '/cache/wsconfigadmin.inc.php');
define('CONFIGCACHE_MESSAGES', __DIR__ .'/../..' . '/cache/messages_%s.inc.php');
define('CONFIGCACHE_ADMINMESSAGES', __DIR__ .'/../..' . '/cache/adminmessages_%s.inc.php');
define('CONFIGCACHE_ENTITYMESSAGES', __DIR__ .'/../..' . '/cache/entitymessages_%s.inc.php');
define('CONFIGCACHE_SETTINGS', __DIR__ .'/../..' . '/cache/settingsconfig.inc.php');
define('CONFIGCACHE_EVENTS', __DIR__ .'/../..' . '/cache/eventsconfig.inc.php');
define('UPLOAD_FOLDER', __DIR__ .'/../..' . '/uploads/');
define('IMPRINT_FILE', __DIR__ .'/../..' . '/generated/imprint.php');
define('TEMPLATES_FOLDER', __DIR__ .'/../..' . '/templates');
define('PROFPIC_UPLOADFOLDER', UPLOAD_FOLDER . 'users');
include(GLOBAL_CONFIG_FILE);
if (!isset($conf)) {
	header('location: install/index.php');
	exit; }
$page = null;
$action = null;
$block = null;
try {
	$website = WebSoccer::getInstance();
	if (!file_exists(CONFIGCACHE_FILE_FRONTEND)) $website->resetConfigCache(); }
catch(Exception $e) {
	try {
		$log = new FileWriter('errorlog.txt');
		$log->writeLine('Website Configuration Error: ' . $e->getMessage());
		$log->close(); }
	catch(Exception $e) {}
	header('HTTP/1.0 500 Error');
	die(); }
try {
	$db = DbConnection::getInstance();
	$db->connect($website->getConfig('db_host'), $website->getConfig('db_user'), $website->getConfig('db_passwort'), $website->getConfig('db_name')); }
catch(Exception $e) {
	try {
		$log = new FileWriter('dberrorlog.txt');
		$log->writeLine('DB Error: ' . $e->getMessage());
		$log->close(); }
	catch(Exception $e) {}
	die('<h1>Sorry, our data base is currently not available</h1><p>We are working on it.</p>'); }
$handler = new DbSessionManager($db, $website);
session_set_save_handler(array($handler, 'open'), array($handler, 'close'), array($handler, 'read'), array($handler, 'write'), array($handler, 'destroy'), array($handler, 'gc') );

//- owsPro - Error handling for PHP 8
//- Fatal error:  Uncaught TypeError: Session callback must have a return value of type bool, null returned in [no active file]:
//- register_shutdown_function('session_write_close');

session_start();
try { date_default_timezone_set($website->getConfig('time_zone')); }
catch (Exception $e) {}
