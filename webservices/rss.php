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
	include($_SERVER['DOCUMENT_ROOT'].'/admin/config/global.inc.php');include($_SERVER['DOCUMENT_ROOT'].'/cache/wsconfigfront.inc.php');$i18n=I18n::getInstance(Config('supported_languages'));$lang=$website->getRequestParameter('lang');if($lang){
	try{$i18n->setCurrentLanguage($lang);}catch(Exception$e){}}include(sprintf($_SERVER['DOCUMENT_ROOT'].'/cache/messages_%s.inc.php',$i18n->getCurrentLanguage()));include(sprintf($_SERVER['DOCUMENT_ROOT'].'/cache/entitymessages_%s.inc.php',$i18n->getCurrentLanguage()));
	$pageId=$website->getRequestParameter('page');$website->setPageId($pageId);header('Content-type:application/rss+xml;charset=utf-8');$viewHandler=new ViewHandler($website,$db,$i18n,$page,$block,null);try{echo$viewHandler->handlePage($pageId,[]);}
	catch(Exception$e){echo$e->getMessage();}