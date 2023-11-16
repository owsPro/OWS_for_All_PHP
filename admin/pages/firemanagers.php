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
								if(!$admin['r_admin']&&!$admin['r_demo']&&!$admin[$page['permissionrole']])throw new Exception(Message('error_access_denied'));if(!$show){$mainTitle=Message('firemanagers_navlabel');echo"<h1>$mainTitle</h1>";?>
  								<p><?php echo Message('firemanagers_introduction');?></p> <form action='<?php echo ESC($_SERVER['PHP_SELF']);?>'method='post'class='form-horizontal'><input type='hidden'name='site'value='<?php echo$site;?>'><fieldset><legend><?php
    							echo Message('firemanagers_search_label');?></legend><?php $formFields['maxbudget']=['type'=>'number','value'=>(isset($_POST['maxbudget']))?ESC($_POST['maxbudget']):-1000000];$formFields['lastlogindays']=['type'=>'number',
    							'value'=>(isset($_POST['lastlogindays']))?ESC($_POST['lastlogindays']):60];$formFields['maxplayers']=['type'=>'number','value'=>(isset($_POST['maxplayers']))?ESC($_POST['maxplayers']):15];$formFields['userid']=
    							['type'=>'foreign_key','value'=>(isset($_POST['userid']))?ESC($_POST['userid']):'','entity'=>'users','jointable'=>'user','labelcolumns'=>'nick'];foreach($formFields as$fieldId=>$fieldInfo)echo createFormGroup($i18n,
								$fieldId,$fieldInfo,$fieldInfo['value'],'firemanagers_search_');?></fieldset><div class='form-actions'><input type='submit'class='btn btn-primary'accesskey='s'title='Alt + s'value='<?php echo Message('button_search');?>'>
								<input type='reset'class='btn'value='<?php echo Message('button_reset');?>'></div></form><?php if(!empty($_POST['maxbudget'])||!empty($_POST['lastlogindays'])||!empty($_POST['maxplayers'])||!empty($_POST['userid'])){$columns=[];
								$columns=['C.id'=>'team_id','C.name'=>'team_name','C.finanz_budget'=>'team_budget','(SELECT COUNT(*)FROM '.Config('db_prefix').'_spieler AS PlayerTab WHERE PlayerTab.verein_id=C.id AND status=\'1\')'=>'team_players','U.id'=>'user_id',
								'U.nick'=>'user_nick','U.lastonline'=>'user_lastonline'];$fromTable=Config('db_prefix').'_verein AS C INNER JOIN '.Config('db_prefix').'_user AS U ON U.id=C.user_id';$whereCondition='C.status=\'1\'AND(1=0';$parameters=[];
								if(!empty($_POST['maxbudget'])){$whereCondition.=' OR C.finanz_budget<%d';$parameters[]=$_POST['maxbudget'];}if(!empty($_POST['lastlogindays'])){$whereCondition.=' OR U.lastonline<%d';$parameters[]=Timestamp()-$_POST['lastlogindays']
								*24*3600;}if(!empty($_POST['maxplayers'])){$whereCondition.=' OR (SELECT COUNT(*)FROM '.Config('db_prefix').'_spieler AS PlayerTab WHERE PlayerTab.verein_id=C.id AND status=\'1\')<%d';$parameters[]=$_POST['maxplayers'];}
								if(!empty($_POST['userid'])&&$_POST['userid']){$whereCondition.=' OR U.id=%d';$parameters[]=$_POST['userid'];}$whereCondition.=')';$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters,50);if(!$result->num_rows)
								echo createInfoMessage(Message('firemanagers_search_nohits'),'');else{?><form action='<?php echo ESC($_SERVER['PHP_SELF']);?>'method='post'class='form-horizontal'id='frmMain'name='frmMain'><input type='hidden'name='site'
								value='<?php echo $site;?>'><input type='hidden'name='show'value='selectoptions'><table class='table table-striped table-hover'><thead><tr><th></th><th><?php echo Message('entity_club');?></th><th><?php echo Message('entity_users');?></th>
								<th><?php echo Message('entity_users_lastonline');?></th><th><?php echo Message('entity_club_finanz_budget');?></th><th><?php echo Message('entity_player');?></th></tr></thead><tbody><?php while($row=$result->fetch_array()){echo'<tr>
								<td><input type=\'checkbox\'name=\'selectedteams[]\'value=\''.$row['team_id'].'\'/></td><td class=\'tableRowSelectionCell\'><a href=\''.iUrl('team','id='.$row['team_id']).'\'target=\'_blank\'>'.ESC($row['team_name']).'</a></td>
								<td class=\'tableRowSelectionCell\'><a href=\''.iUrl('user','id='.$row['user_id']).'\'target=\'_blank\'>'.ESC($row['user_nick']).'</a></td><td class=\'tableRowSelectionCell\'>'.FormattedDate($row['user_lastonline']).'</td>
								<td class=\'tableRowSelectionCell\'>'.number_format($row['team_budget'],0,',',' ').'</td><td class=\'tableRowSelectionCell\'>'.$row['team_players'].'</td></tr>'.PHP_EOL;}?></tbody></table><p><label class='checkbox'><input type='checkbox'
								name='selAll'value='1'onClick='selectAll()'><?php echo Message('manage_select_all_label');?></label></p><p><input type='submit'class='btn btn-primary'accesskey='l'title='Alt + l'value='<?php echo Message('firemanagers_dismiss_button');?>'>
								</p></form><?php }$result->free();}}elseif($show=='selectoptions'){if(!isset($_POST['selectedteams'])||!count($_POST['selectedteams'])){echo createErrorMessage(Message('firemanagers_dismiss_noneselected'),'');echo'<a href=\'?site='.$site.
								'\'class=\'btn\'>'.Message('back_label').'</a>';}else{?><form action='<?php echo ESC($_SERVER['PHP_SELF']);?>'method='post'class='form-horizontal'><input type='hidden'name='site'value='<?php echo$site;?>'><input type='hidden'
								name='show'value='dismiss'><input type='hidden'name='teamids'value='<?php echo implode(',',ESC($_POST['selectedteams']))?>'><fieldset><legend><?php echo Message('firemanagers_dismiss_label');?></legend><?php$formFields=[];
								$formFields['disableusers']=['type'=>'boolean','value'=>0];$formFields['minbudget']=['type'=>'number','value'=>1000000];$formFields['message_subject']=['type'=>'text','value'=>''];$formFields['message_content']=['type'=>'textarea',
								'value'=>''];foreach($formFields as$fieldId=>$fieldInfo)echo createFormGroup($i18n,$fieldId,$fieldInfo,$fieldInfo['value'],'firemanagers_dismiss_');?></fieldset><fieldset><legend><?php echo
								Message('firemanagers_dismiss_label_players');?></legend><?php $formFields=[];$formFields['mincontractmatches']=['type'=>'number','value'=>15];$formFields['strength_min_freshness']=['type'=>'percent','value'=>70];
								$formFields['strength_min_stamina']=['type'=>'percent','value'=>70];$formFields['strength_min_satisfaction']=['type'=>'percent','value'=>70];foreach($formFields as$fieldId=>$fieldInfo)echo createFormGroup($i18n,$fieldId,
								$fieldInfo,$fieldInfo['value'],'firemanagers_dismiss_');?></fieldset><fieldset><legend><?php echo Message('firemanagers_dismiss_label_minteamsize');?></legend><p><?php echo Message('firemanagers_dismiss_label_minteamsize_intro');?></p>
								<?php $formFields=[];$formFields['option_T']=['type'=>'number','value'=>2];$formFields['option_LV']=['type'=>'number','value'=>1];$formFields['option_IV']=['type'=>'number','value'=>2];$formFields['option_RV']=['type'=>'number',
								'value'=>1];$formFields['option_LM']=['type'=>'number','value'=>1];$formFields['option_DM']=['type'=>'number','value'=>1];$formFields['option_ZM']=['type'=>'number','value'=>1];$formFields['option_OM']=['type'=>'number','value'=>1];
								$formFields['option_RM']=['type'=>'number','value'=>1];$formFields['option_LS']=['type'=>'number','value'=>1];$formFields['option_MS']=['type'=>'number','value'=>1];$formFields['option_RS']=['type'=>'number','value'=>1];
								$formFields['player_age']=['type'=>'number','value'=>25];$formFields['player_age_deviation']=['type'=>'number','value'=>3];$formFields['entity_player_vertrag_gehalt']=['type'=>'number','value'=>10000];
								$formFields['entity_player_vertrag_spiele']=['type'=>'number','value'=>60];$formFields['entity_player_w_staerke']=['type'=>'percent','value'=>50];$formFields['entity_player_w_technik']=['type'=>'percent','value'=>50];
								$formFields['entity_player_w_kondition']=['type'=>'percent','value'=>70];$formFields['entity_player_w_frische']=['type'=>'percent','value'=>80];$formFields['entity_player_w_zufriedenheit']=['type'=>'percent','value'=>80];
								$formFields['playersgenerator_label_deviation']=['type'=>'percent','value'=>10];foreach($formFields as$fieldId=>$fieldInfo)echo createFormGroup($i18n,$fieldId,$fieldInfo,$fieldInfo['value'],'');?></fieldset>
								<div class='form-actions'><input type='submit'class='btn btn-primary'accesskey='s'title='Alt + s'value='<?php echo Message('firemanagers_dismiss_button');?>'><a class='btn'href='?site=<?php echo $site;?>'><?php echo
								Message('button_cancel');?></a></div></form><?php }}elseif($show=='dismiss'){if($admin['r_demo'])$err[]=Message('validationerror_no_changes_as_demo');if(isset($err))include('validationerror.inc.php');else{$strengths['strength']=
								$_POST['entity_player_w_staerke'];$strengths['technique']=$_POST['entity_player_w_technik'];$strengths['stamina']=$_POST['entity_player_w_kondition'];$strengths['freshness']=$_POST['entity_player_w_frische'];$strengths['satisfaction']=
								$_POST['entity_player_w_zufriedenheit'];$teamIds=explode(',',$_POST['teamids']);foreach($teamIds as$teamId){$team=TeamsDataService::getTeamSummaryById($website,$db,$teamId);$teamcolumns=['user_id'=>'','captain_id'=>'','finanz_budget'=>
								(!empty($_POST['minbudget']))?max($_POST['minbudget'],$team['team_budget']):$team['team_budget']];$db->queryUpdate($teamcolumns,Config('db_prefix').'_verein','id=%d',$teamId);if(isset($_POST['disableusers'])&&$_POST['disableusers'])
								$db->queryUpdate(array('status'=>'0'),Config('db_prefix').'_user','id=%d',$team['user_id']);if(!empty($_POST['message_subject'])&&!empty($_POST['message_content'])){$db->queryInsert(['empfaenger_id'=>$team['user_id'],'absender_name'=>
								Config('projectname'),'absender_id'=>'','datum'=>Timestamp(),'betreff'=>trim($_POST['message_subject']),'nachricht'=>trim($_POST['message_content'])],Config('db_prefix').'_briefe');}$positionsCount=[];$positionsCount=['T'=>0,'LV'=>0,
								'IV'=>0,'RV'=>0,'LM'=>0,'ZM'=>0,'RM'=>0,'DM'=>0,'OM'=>0,'LS'=>0,'MS'=>0,'RS'=>0];$result=$db->querySelect('id,position_main,w_kondition,w_frische,w_zufriedenheit,vertrag_spiele',Config('db_prefix').'_spieler','verein_id=%d AND status=
								\'1\'',$teamId);while($player=$result->fetch_array()){$updateRequired=FALSE;$freshness=$player['w_frische'];if(!empty($_POST['strength_min_freshness'])&&$_POST['strength_min_freshness']>$freshness){$updateRequired=TRUE;$freshness=
								$_POST['strength_min_freshness'];}$stamina=$player['w_kondition'];if(!empty($_POST['strength_min_stamina'])&&$_POST['strength_min_stamina']>$stamina){$updateRequired=TRUE;$stamina=$_POST['strength_min_stamina'];}$satisfaction=
								$player['w_zufriedenheit'];if(!empty($_POST['strength_min_satisfaction'])&&$_POST['strength_min_satisfaction']>$satisfaction){$updateRequired=TRUE;$satisfaction=$_POST['strength_min_satisfaction'];}$contractMatches=
								$player['vertrag_spiele'];if(!empty($_POST['mincontractmatches'])&&$_POST['mincontractmatches']>$contractMatches){$updateRequired=TRUE;$contractMatches=$_POST['mincontractmatches'];}$db->queryUpdate(['w_frische'=>$freshness,'w_kondition'=>
								$stamina,'w_zufriedenheit'=>$satisfaction,'vertrag_spiele'=>$contractMatches],Config('db_prefix').'_spieler','id=%d',$player['id']);if(strlen($player['position_main']))$positionsCount[$player['position_main']]=$positionsCount[$player[
								'position_main']]+1;}$result->free();$positions=[];$positions['T']=(!empty($_POST['option_T']))?max(0,$_POST['option_T']-$positionsCount['T']):0;$positions['LV']=(!empty($_POST['option_LV']))?max(0,$_POST['option_LV']-
								$positionsCount['LV']):0;$positions['IV']=(!empty($_POST['option_IV']))?max(0,$_POST['option_IV']-$positionsCount['IV']):0;$positions['RV']=(!empty($_POST['option_RV']))?max(0,$_POST['option_RV']-$positionsCount['RV']):0;$positions['LM']=
								(!empty($_POST['option_LM']))?max(0,$_POST['option_LM']-$positionsCount['LM']):0;$positions['ZM']=(!empty($_POST['option_ZM']))?max(0,$_POST['option_ZM']-$positionsCount['ZM']):0;$positions['RM']=(!empty($_POST['option_RM']))?
								max(0,$_POST['option_RM']-$positionsCount['RM']):0;$positions['DM']=(!empty($_POST['option_DM']))?max(0,$_POST['option_DM']-$positionsCount['DM']):0;$positions['OM']=(!empty($_POST['option_OM']))?max(0,$_POST['option_OM']-
								$positionsCount['OM']):0;$positions['LS']=(!empty($_POST['option_LS']))?max(0,$_POST['option_LS']-$positionsCount['LS']):0;$positions['MS']=(!empty($_POST['option_MS']))?max(0,$_POST['option_MS']-$positionsCount['MS']):0;$positions['RS']=
								(!empty($_POST['option_RS']))?max(0,$_POST['option_RS']-$positionsCount['RS']):0;$playersToGenerate=FALSE;foreach($positions as$posCount){if($posCount>0){$playersToGenerate=TRUE;break;}}if($playersToGenerate)
								generatePlayers($website,$db,$teamId,$_POST['player_age'],$_POST['player_age_deviation'],$_POST['entity_player_vertrag_gehalt'],$_POST['entity_player_vertrag_spiele'],$strengths,$positions,
								$_POST['playersgenerator_label_deviation']);}echo createSuccessMessage(Message('firemanagers_dismiss_success'),'');echo'<p>&raquo; <a href=\'?site='.$site.'\'>'.Message('back_label').'</a></p>';}}
