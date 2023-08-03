<?php
/*This file is part of 'OWS for All PHP' (Rolf Joseph)
  https://github.com/owsPro/OWS_for_All_PHP/
  A spinn-off for PHP Versions 5.4 to 8.2 from:
  OpenWebSoccer-Sim(Ingo Hofmann), https://github.com/ihofmann/open-websoccer.

  'OWS for All PHP' is is distributed in WITHOUT ANY WARRANTY;
  without even the implied warranty of MERCHANTABILITY
  or FITNESS FOR A PARTICULAR PURPOSE.

  See GNU Lesser General Public License Version 3 http://www.gnu.org/licenses/

*****************************************************************************/
					echo'<h1>'.Message('match_manage_reportitems').'</h1>';if(!$admin['r_admin']&&!$admin['r_demo']&&!$admin['r_spiele'])throw new Exception(Message('error_access_denied'));echo'<p><a href=\'?site=manage&entity=match\'class=\'btn\'>'.
					Message('back_label').'</a></p>';$matchId=(isset($_REQUEST['match'])&&is_numeric($_REQUEST['match']))?$_REQUEST['match']:0;$match=MatchesDataService::getMatchById($website,$db,$matchId,FALSE,FALSE);if(!count($match))
					throw new Exception('illegal match id');if($action=='delete'){if($admin['r_demo'])throw new Exception(Message('validationerror_no_changes_as_demo'));$itemId=(int)$_REQUEST['itemid'];$db->queryDelete(Config('db_prefix').'_matchreport','id=%d',
					[$itemId]);echo createSuccessMessage(Message('manage_success_delete'),'');}elseif($action=='create'){if($admin['r_demo'])throw new Exception(Message('validationerror_no_changes_as_demo'));$homeActive=($match['match_home_id']==$_POST['team_id'])?
					'1':'0';$message_id=(int)$_POST['message_id'];$minute=(int)$_POST['minute'];$playerNames=$_POST['playernames'];$goals=$_POST['intermediateresult'];if($message_id&&$minute){$db->queryInsert(['match_id'=>$matchId,'message_id'=>$message_id,
					'active_home'=>$homeActive,'minute'=>$minute,'goals'=>$goals,'playernames'=>$playerNames],Config('db_prefix').'_matchreport');}}echo'<form action=\''.$_SERVER['PHP_SELF'].'\'class=\'form-horizontal\'method=\'post\'>
					<input type=\'hidden\' name=\'action\'value=\'create\'><input type=\'hidden\'name=\'site\'value=\'$site\'><input type=\'hidden\'name=\'match\'value=\'$matchId\'><fieldset><legend>'.Message('match_manage_createmessage_title').'</legend>
					<div class=\'control-group\'><label class=\'control-label\'for=\'team_id\'>'.Message('entity_player_verein_id').'</label><div class=\'controls\'><select name=\'team_id\'id=\'team_id\'><option value=\''.$match['match_home_id'].'\'>'.
					escapeOutput($match['match_home_name']).'</option><option value=\''.$match['match_guest_id'].'\'>'.escapeOutput($match['match_guest_name']).'</option></select></div></div>';echo FormBuilder::createFormGroup($i18n,'message_id',
					['type'=>'foreign_key','jointable'=>'spiel_text','entity'=>'matchtext','labelcolumns'=>'aktion,nachricht'],'','match_manage_reportmsg_');echo FormBuilder::createFormGroup($i18n,'playernames',['type'=>'text'],'','match_manage_reportmsg_');
					echo FormBuilder::createFormGroup($i18n,'minute',['type'=>'number'],'','match_manage_reportmsg_');echo FormBuilder::createFormGroup($i18n,'intermediateresult',['type'=>'text'],'','match_manage_reportmsg_');echo'</fieldset>
					<div class=\'form-actions\'><button type=\'submit\'class=\'btn btn-primary\'>'.Message('button_save').'</button></div></form>';$reportItems=MatchesDataService::getMatchReportMessages($website,$db,$i18n,$matchId);
					if(!count($reportItems))echo createInfoMessage('',Message('match_manage_reportitems_noitems'));else{echo'<table class=\'table table-bordered table-striped table-hover\'><thead><tr><th>'.Message('match_manage_reportmsg_minute').'</th>
					<th>'.Message('entity_matchtext_aktion').'</th><th>'.Message('entity_matchtext_nachricht').'</th><th>'.Message('match_manage_reportmsg_playernames').'</th><th>'.Message('match_manage_reportmsg_intermediateresult').'</th></tr></thead><tbody>';
					$homeTeam=escapeOutput($match['match_home_name']);$guestTeam=escapeOutput($match['match_guest_name']);foreach($reportItems as$reportItem){echo'<tr><td><a href=\'?site=$site&action=delete&match=$matchId&itemid='.escapeOutput($reportItem['report_id']).
	  				'\'title=\''.Message('manage_delete').'\'class=\'deleteLink\'><i class=\'icon-trash\'></i></a> '.escapeOutput($reportItem['minute']).'</td><td><small>';if($reportItem['active_home'])echo$homeTeam;else echo$guestTeam;echo'</small><br>';
	  				echo Message('option_'.$reportItem['type']);echo'</td><td>'.escapeOutput($reportItem['message']).'</td><td>'.escapeOutput($reportItem['playerNames']).'</td><td>'.escape($reportItem['goals']).'</td></tr>';}echo'</tbody></table>';}
