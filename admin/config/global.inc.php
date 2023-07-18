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
define('BASEFOLDER', __DIR__ .'/../..');
define('DEBUG', FALSE);

if (DEBUG) {
	error_reporting(E_ALL);
} else {
	error_reporting(E_ERROR);
}

// loads required classes on demand
function classes_autoloader($class) {

	$subforder = '';

	if (substr($class, -9) === 'Converter') {
		$subforder = 'converters/';
	} else if (substr($class, -4) === 'Skin') {
		$subforder = 'skins/';
	} else if (substr($class, -5) === 'Model') {
		$subforder = 'models/';
	} else if (substr($class, -9) === 'Validator') {
		$subforder = 'validators/';
	} else if (substr($class, -10) === 'Controller') {
		$subforder = 'actions/';
	} else if (substr($class, -7) === 'Service') {
		$subforder = 'services/';
	} else if (substr($class, -3) === 'Job') {
		$subforder = 'jobs/';
	} else if (substr($class, -11) === 'LoginMethod') {
		$subforder = 'loginmethods/';
	} else if (substr($class, -5) === 'Event') {
		$subforder = 'events/';
	} else if (substr($class, -6) === 'Plugin') {
		$subforder = 'plugins/';
	}

	@include(BASEFOLDER . '/classes/' . $subforder . $class . '.class.php');
}
spl_autoload_register('classes_autoloader');

// constants
define('FOLDER_MODULES', BASEFOLDER . '/modules');
define('MODULE_CONFIG_FILENAME', 'module.xml');
define('GLOBAL_CONFIG_FILE', BASEFOLDER . '/generated/config.inc.php');
define('CONFIGCACHE_FILE_FRONTEND', BASEFOLDER . '/cache/wsconfigfront.inc.php');
define('CONFIGCACHE_FILE_ADMIN', BASEFOLDER . '/cache/wsconfigadmin.inc.php');
define('CONFIGCACHE_MESSAGES', BASEFOLDER . '/cache/messages_%s.inc.php');
define('CONFIGCACHE_ADMINMESSAGES', BASEFOLDER . '/cache/adminmessages_%s.inc.php');
define('CONFIGCACHE_ENTITYMESSAGES', BASEFOLDER . '/cache/entitymessages_%s.inc.php');
define('CONFIGCACHE_SETTINGS', BASEFOLDER . '/cache/settingsconfig.inc.php');
define('CONFIGCACHE_EVENTS', BASEFOLDER . '/cache/eventsconfig.inc.php');
define('UPLOAD_FOLDER', BASEFOLDER . '/uploads/');
define('IMPRINT_FILE', BASEFOLDER . '/generated/imprint.php');
define('TEMPLATES_FOLDER', BASEFOLDER . '/templates');
define('PROFPIC_UPLOADFOLDER', UPLOAD_FOLDER . 'users');

// dependencies
include(GLOBAL_CONFIG_FILE);
if (!isset($conf)) {
	header('location: install/index.php');
	exit;
}

$page = null;
$action = null;
$block = null;

// init application
try {
	$website = WebSoccer::getInstance();
	if (!file_exists(CONFIGCACHE_FILE_FRONTEND)) {
		$website->resetConfigCache();
	}
} catch(Exception $e) {
	// write to log
	try {
		$log = new FileWriter('errorlog.txt');
		$log->writeLine('Website Configuration Error: ' . $e->getMessage());
		$log->close();
	} catch(Exception $e) {
		// ignore
	}
	header('HTTP/1.0 500 Error');
	die();
}

// connect to DB
try {
	$db = DbConnection::getInstance();
	$db->connect(Config('db_host'),Config('db_user'),Config('db_passwort'),Config('db_name'));
} catch(Exception $e) {
	// write to log
	try {
		$log = new FileWriter('dberrorlog.txt');
		$log->writeLine('DB Error: ' . $e->getMessage());
		$log->close();
	} catch(Exception $e) {
		// ignore
	}
	die('<h1>Sorry, our data base is currently not available</h1><p>We are working on it.</p>');
}

// register own session handler
$handler = new DbSessionManager($db, $website);
session_set_save_handler(
	array($handler, 'open'),
	array($handler, 'close'),
	array($handler, 'read'),
	array($handler, 'write'),
	array($handler, 'destroy'),
	array($handler, 'gc')
);

// the following prevents unexpected effects when using objects as save handlers
// see http://php.net/manual/en/function.session-set-save-handler.php
register_shutdown_function('session_write_close');
session_start();

// always set time zone in order to prevent PHP warnings
try {
	date_default_timezone_set(Config('time_zone'));
} catch (Exception $e) {
	// do not set time zone. This Exception can appear in particular when updating from older version.
}

?>