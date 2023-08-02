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
					$mainTitle=Message('jobs_navlabel');if(!$admin['r_admin']&&!$admin['r_demo']&&!$admin[$page['permissionrole']])throw new Exception(Message('error_access_denied'));if(!$show){?><h1><?php echo$mainTitle;?></h1><p><?php echo 
					Message('jobs_introduction');?></p><div class='alert'><?php echo Message('jobs_warning');?></div><?php if($action=='execute'&&!$admin['r_demo']){$jobId=$_REQUEST['id'];$xml=simplexml_load_file($_SERVER['DOCUMENT_ROOT'].'/admin/config/jobs.xml');
					$jobConfig=$xml->xpath(escapeOutput("//job[@id='".$jobId."']"));if(!$jobConfig)throw new Exception("Job config not found.");$jobClass=(string)$jobConfig[0]->attributes()->class;if(class_exists($jobClass))$job=new$jobClass($website,$db,$i18n,$jobId);
					else throw new Exception("class not found: ".$jobClass);$job->execute();echo createSuccessMessage(Message('jobs_executed'),'');}?><table class='table table-striped'><thead><tr><th><?php echo Message('jobs_head_name');?></th><th><?php echo 
					Message('jobs_head_last_execution');?></th><th><?php echo Message('jobs_head_interval');?></th><th><?php echo Message('jobs_head_status');?></th><th><?php echo Message('jobs_head_startstop');?></th></tr></thead><tbody><?php $doc=new DOMDocument();
					$loaded=@$doc->load($_SERVER['DOCUMENT_ROOT'].'/admin/config/jobs.xml');if(!$loaded)throw new Exception('Could not load XML config file: '+$_SERVER['DOCUMENT_ROOT'].'/admin/config/jobs.xml');$items=$doc->getElementsByTagName('job');
					$now=$website->getNowAsTimestamp();foreach($items as$item){echo'<tr>';$jobid=(string)$item->getAttribute('id');$i18nJobNameAttr='name_'.$i18n->getCurrentLanguage();if($item->hasAttribute($i18nJobNameAttr))$name=
					(string)$item->getAttribute($i18nJobNameAttr);else$name=(string)$item->getAttribute('name');$class=(string)$item->getAttribute('class');$interval=(string)$item->getAttribute('interval');$lastPing=(int)$item->getAttribute('last_ping');
					$error=(string)$item->getAttribute('error');$stop=(string)$item->getAttribute('stop');$minPing=$now-$interval*60-5;$running=($stop==0&&$lastPing>$minPing);if($running)$status="<span class=\"label label-success\">".Message('jobs_status_running').
					'</span>';else$status="<span class=\"label label-important\">".Message('jobs_status_notrunning').'</span>';echo'<td>'.$name;if(strlen($error))echo createErrorMessage(Message('subpage_error_title'),$error);echo'</td>';echo'<td>';
					if($lastPing)echo$website->getFormattedDatetime($lastPing);else echo'-';echo'</td>';echo'<td>'.$interval.' '.Message('unit_minutes').'</td>';echo'<td>'.$status.'</td>';echo'<td>';if($running)echo"<a href=\"job.php?action=stop&id=".$jobid.
					"\"class=\"btn startStopJobLink\">".Message("jobs_button_stop")."</a>";else{echo"<a href=\"job.php?action=start&id=".$jobid."\"class=\"btn btn-primary startStopJobLink\">".Message("jobs_button_start")."</a>";echo"<a href=\"?site=".$site.
					"&action=execute&id=".$jobid."\"class=\"btn\">".Message("jobs_button_execute_once")."</a>";}echo'</td>';echo'</tr>';}?></tbody></table><?php }