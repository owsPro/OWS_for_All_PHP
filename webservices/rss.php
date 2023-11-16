<?php
/*This file is part of "OWS for All PHP" (Rolf Joseph)
  https://github.com/owsPro/OWS_for_All_PHP/
  A spinn-off for PHP Versions 5.5 to 8.2 from:
  OpenWebSoccer-Sim(Ingo Hofmann), https://github.com/ihofmann/open-websoccer.

  "OWS for All PHP" is is distributed in WITHOUT ANY WARRANTY;
  without even the implied warranty of MERCHANTABILITY
  or FITNESS FOR A PARTICULAR PURPOSE.

  See GNU Lesser General Public License Version 3 http://www.gnu.org/licenses/

*****************************************************************************/
	include($_SERVER['DOCUMENT_ROOT'].'/admin/config/global.inc.php');
include($_SERVER['DOCUMENT_ROOT'].'/cache/wsconfigfront.inc.php');

// Create an instance of the I18n class with the supported languages configuration
$i18n = I18n::getInstance(Config('supported_languages'));

// Get the requested language from the request
$lang = Request('lang');

// If a language is specified, set it as the current language
if ($lang) {
    try {
        $i18n->setCurrentLanguage($lang);
    } catch (Exception $e) {
        // Handle any exceptions that occur when setting the current language
    }
}

// Include the language messages for the current language
include(sprintf($_SERVER['DOCUMENT_ROOT'].'/cache/messages_%s.inc.php', $i18n->getCurrentLanguage()));

// Include the entity messages for the current language
include(sprintf($_SERVER['DOCUMENT_ROOT'].'/cache/entitymessages_%s.inc.php', $i18n->getCurrentLanguage()));

// Get the requested page ID from the request
$pageId = Request('page');

// Set the page ID
setPageId($pageId);

// Set the response content type to RSS
header('Content-type:application/rss+xml;charset=utf-8');

// Create a new instance of the ViewHandler class
$viewHandler = new ViewHandler($website, $db, $i18n, $page, $block, null);

try {
    // Handle the requested page and echo the result
    echo $viewHandler->handlePage($pageId, []);
} catch (Exception $e) {
    // Handle any exceptions that occur during page handling
    echo print_r($e->getMessage());
}
