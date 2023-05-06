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
require_once(__DIR__.'/..'.'/admin/config/global.inc.php');
SecurityUtil::logoutAdmin();
$lang = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']);											// Get the preferred language from the browser
$supported=['en','de','es'];																				// Define a list of supported languages
if(in_array($lang,$supported))																				// Check if the preferred language is supported
	header('location:login.php?loggedout=1&lang=$lang');													// Execute the code for the preferred language
echo'<pre>										<b>This language is not yet fully supported!</b></pre>';	// Echo unsupported message
echo"<meta http-equiv='refresh' content='2;URL=login.php?loggedout=1&lang=de'/>";							// Execute the code for the standard language