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
					require($_SERVER['DOCUMENT_ROOT'].'/admin/config/global.inc.php');include($_SERVER['DOCUMENT_ROOT'].'/cache/wsconfigfront.inc.php');include($_SERVER['DOCUMENT_ROOT'].'/cache/settingsconfig.inc.php');include($_SERVER['DOCUMENT_ROOT'].
					'/settingsconfig.php');$authenticatorClasses=explode(',',Config('authentication_mechanism'));foreach($authenticatorClasses as$authenticatorClass){$authenticatorClass=trim($authenticatorClass);if(!class_exists($authenticatorClass))
					throw new Exception('Class not found: '.$authenticatorClass);$authenticator=new $authenticatorClass($website);$authenticator->verifyAndUpdateCurrentUser($website->getUser());}$i18n=I18n::getInstance(Config('supported_languages'));
					if($website->getUser()->language!=null){try{$i18n->setCurrentLanguage($website->getUser()->language);}catch(Exception$e){}}include(sprintf($_SERVER['DOCUMENT_ROOT'].'/cache/messages_%s.inc.php',$i18n->getCurrentLanguage()));
					include(sprintf($_SERVER['DOCUMENT_ROOT'].'/cache/entitymessages_%s.inc.php',$i18n->getCurrentLanguage()));include(sprintf($_SERVER['DOCUMENT_ROOT'].'/languages/messages_%s.php',$i18n->getCurrentLanguage()));