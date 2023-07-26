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
	include($_SERVER['DOCUMENT_ROOT'].'/admin/config/global.inc.php');if(!Config('webjobexecution_enabled'))die('External job execution disabled');if(Config('offline')=='offline'){die('Site is in offline mode');if(!isset($_REQUEST['sectoken']))
	die('no security token provided');if(!isset($_REQUEST['jobid']))die('no job ID provided');$securityToken=$_REQUEST['sectoken'];$jobId=$_REQUEST['jobid'];if(Config('webjobexecution_key')!==$securityToken)die('invalid security token');
	$xml=simplexml_load_file($_SERVER['DOCUMENT_ROOT'].'/admin/config/jobs.xml');$jobConfig=$xml->xpath('//job[@id=\''.$jobId.'\']');if(!$jobConfig)die('Job config not found.');$jobClass=(string)$jobConfig[0]->attributes()->class;
	if(class_exists($jobClass)){$i18n=I18n::getInstance(Config('supported_languages'));$job=new $jobClass($website,$db,$i18n,$jobId);}else die('class not found: '.$jobClass);$job->execute();