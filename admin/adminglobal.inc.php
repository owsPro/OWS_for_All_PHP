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
define('OVERVIEW_SITE_SUFFIX', '_overview');
define('JOBS_CONFIG_FILE', BASE_FOLDER . '/admin/config/jobs.xml');
define('LOG_TYPE_EDIT', 'edit');
define('LOG_TYPE_DELETE', 'delete');
include(BASE_FOLDER . '/admin/config/global.inc.php');
//+ owsPro - Include configuration and settings for the Admin Centre.
// Additional configuration and settings, e.g. through add-ons.
include(BASE_FOLDER . '/cache/wsconfigadmin.inc.php');
// Basic configuration and settings, which are supplemented or overwritten by '/cache/wsconfigadmin.inc.php'.
include(BASE_FOLDER . '/generated/settingsconfig.php');
include(BASE_FOLDER . '/admin/functions.inc.php');
include(CONFIGCACHE_FILE_ADMIN);
$site = (isset($_REQUEST['site'])) ? $_REQUEST['site'] : '';
$show = (isset($_REQUEST['show'])) ? $_REQUEST['show'] : FALSE;
$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : null;
if (SecurityUtil::isAdminLoggedIn()) {
	$columns = '*';
	$fromTable = $conf['db_prefix'] .'_admin';
	$whereCondition = 'id = %d';
	$parameters = $_SESSION['userid'];
	$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters);
	$admin = $result->fetch_array();
	$result->free(); }
else {
	header('location: login.php?forwarded=1');
	exit; }
$i18n = I18n::getInstance($website->getConfig('supported_languages'));
if ($admin['lang']) {
	try { $i18n->setCurrentLanguage($admin['lang']); }
	catch (Exception $e) {} }
include(sprintf(CONFIGCACHE_ADMINMESSAGES, $i18n->getCurrentLanguage()));
include(sprintf(CONFIGCACHE_ENTITYMESSAGES, $i18n->getCurrentLanguage()));
//+ owsPro - Sets the set user language
include(sprintf(BASE_FOLDER . '/languages/messages_%s.php', $i18n->getCurrentLanguage()));
header('Content-type: text/html; charset=utf-8');
