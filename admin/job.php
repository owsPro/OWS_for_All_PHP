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
				include($_SERVER['DOCUMENT_ROOT'].'/admin/adminglobal.inc.php');if($admin['r_demo'])exit;$jobId=escapeOutput($_REQUEST['id']);$xml=simplexml_load_file($_SERVER['DOCUMENT_ROOT'].'/admin/config/jobs.xml');$jobConfig=$xml->xpath('//job[@id=\''.
				escaapeOutput($jobId).'\']');if(!$jobConfig)throw new Exception('Job config not found.');$jobClass=(string)$jobConfig[0]->attributes()->class;if(class_exists($jobClass))$job=new$jobClass($website,$db,$i18n,$jobId,$action!=='stop');
				else throw new Exception('class not found: '.$jobClass);if($action=='start')$job->start();elseif($action=='stop')$job->stop();