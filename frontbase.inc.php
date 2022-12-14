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
define('PARAM_ACTION', 'action');
define('PARAM_PAGE', 'page');
define('PARAM_BLOCK', 'block');
define('PARAM_PAGENUMBER', 'pageno');
define('MSG_KEY_ERROR_PAGENOTFOUND', 'error_page_not_found');
require(BASE_FOLDER . '/admin/config/global.inc.php');
include(CONFIGCACHE_FILE_FRONTEND);

//+ owsPro - Include basic configuration and settings, which is supplemented or overwritten by include(CONFIGCACHE_FILE_FRONTEND);.
include(BASE_FOLDER . '/generated/settingsconfig.php');

$authenticatorClasses = explode(',', $website->getConfig('authentication_mechanism'));
foreach ($authenticatorClasses as $authenticatorClass) {
	$authenticatorClass = trim($authenticatorClass);
	if (!class_exists($authenticatorClass)) throw new Exception('Class not found: ' . $authenticatorClass);
	$authenticator = new $authenticatorClass($website);
	$authenticator->verifyAndUpdateCurrentUser($website->getUser()); }
$i18n = I18n::getInstance($website->getConfig('supported_languages'));
if ($website->getUser()->language != null) {
	try { $i18n->setCurrentLanguage($website->getUser()->language); }
	catch (Exception $e) {} }
include(sprintf(CONFIGCACHE_MESSAGES, $i18n->getCurrentLanguage()));
include(sprintf(CONFIGCACHE_ENTITYMESSAGES, $i18n->getCurrentLanguage()));

//+ owsPro - Provide statutory language.
include(sprintf(BASE_FOLDER . '/languages/messages_%s.php', $i18n->getCurrentLanguage()));
