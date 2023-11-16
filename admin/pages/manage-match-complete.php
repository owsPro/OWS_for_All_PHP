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
							echo'<h1>'.Message('match_manage_complete').'</h1>';if(!$admin['r_admin']&&!$admin['r_demo']&&!$admin['r_spiele'])throw new Exception(Message('error_access_denied'));echo'<p><a href=\'?site=manage&entity=match\'class=\'btn\'>'.
							Message('back_label').'</a></p>';$matchId=(isset($_REQUEST['match'])&&is_numeric($_REQUEST['match']))?$_REQUEST['match']:0;$match=MatchesDataService::getMatchById($website,$db,$matchId,FALSE,FALSE);if(!count($match)throw new Exception(
							'illegal match id');if($match['match_simulated'])throw new Exception(Message('match_manage_complete_alreadysimulated'));if($action=='complete'){if($admin['r_demo'])throw new Exception(Message('validationerror_no_changes_as_demo'));$statTable=
							Config('db_prefix').'_spiel_berechnung';$result=$db->querySelect('SUM(tore)AS goals,MAX(minuten_gespielt)AS minutes',$statTable,'spiel_id=%d AND team_id=%d',[$matchId,$match['match_home_id']]);$homeStat=$result->fetch_array();$result->free();
							$result=$db->querySelect('SUM(tore)AS goals',$statTable,'spiel_id=%d AND team_id=%d',[$matchId,$match['match_guest_id']]);$guestStat=$result->fetch_array();$result->free();$db->queryUpdate(['minutes'=>$homeStat['minutes'],
							'home_tore'=>$homeStat['goals'],'gast_tore'=>$guestStat['goals'],'berechnet'=>'1'],Config('db_prefix').'_spiel','id=%d',$matchId);$fromTable=Config('db_prefix').'_spiel AS M INNER JOIN '.Config('db_prefix').
							'_verein AS HOME_T ON HOME_T.id=M.home_verein INNER JOIN '.Config('db_prefix').'_verein AS GUEST_T ON GUEST_T.id=M.gast_verein';['M.id'=>'match_id','M.spieltyp'=>'type','M.home_verein'=>'home_id','M.gast_verein'=>'guest_id',
							'M.minutes'=>'minutes','M.soldout'=>'soldout','M.elfmeter'=>'penaltyshooting','M.pokalname'=>'cup_name','M.pokalrunde'=>'cup_roundname','M.pokalgruppe'=>'cup_groupname','M.player_with_ball'=>'player_with_ball',
							'M.prev_player_with_ball'=>'prev_player_with_ball','M.home_tore'=>'home_goals','M.gast_tore'=>'guest_goals','M.home_offensive'=>'home_offensive','M.home_setup'=>'home_setup','M.home_noformation'=>'home_noformation',
							'M.home_longpasses'=>'home_longpasses','M.home_counterattacks'=>'home_counterattacks','M.gast_offensive'=>'guest_offensive','M.guest_noformation'=>'guest_noformation','M.gast_setup'=>'guest_setup','M.gast_longpasses'=>'guest_longpasses',
							'M.gast_counterattacks'=>'guest_counterattacks','HOME_T.name'=>'home_name','HOME_T.nationalteam'=>'home_nationalteam','GUEST_T.nationalteam'=>'guest_nationalteam','GUEST_T.name'=>'guest_name'];for($subNo=1;$subNo<=3;++$subNo){
							['M.home_w'.$subNo.'_raus'=>'home_sub_'.$subNo.'_out','M.home_w'.$subNo.'_rein'=>'home_sub_'.$subNo.'_in','M.home_w'.$subNo.'_minute'=>'home_sub_'.$subNo.'_minute','M.home_w'.$subNo.'_condition'=>'home_sub_'.$subNo.'_condition','M.gast_w'.
							$subNo.'_raus'=>'guest_sub_'.$subNo.'_out','M.gast_w'.$subNo.'_rein'=>'guest_sub_'.$subNo.'_in','M.gast_w'.$subNo.'_minute'=>'guest_sub_'.$subNo.'_minute','M.gast_w'.$subNo.'_condition'=>'guest_sub_'.$subNo.'_condition'];}$result=
							$db->querySelect($columns,$fromTable,'M.id=%d',$matchId);$matchinfo=$result->fetch_array();$result->free();$dummyVar=new DefaultSimulationStrategy($website);$matchModel=loadMatchState($website,$db,$matchinfo);if(RequestParameter(
							'computetickets'))computeAndSaveAudience($website,$db,$matchModel);if($matchinfo['type']=='Pokalspiel')checkIfExtensionIsRequired($website,$db,$matchModel);$observer=new DataUpdateSimulatorObserver($website,$db);
							$observer->onMatchCompleted($matchModel);echo createSuccessMessage(Message('match_manage_complete_success'),'');}echo"<h3><a href=\"".iUrl('team','id='.$match['match_home_id'])."\"target=\'_blank\'>".escapeOutput($match['match_home_name']).
							"</a> - <a href=\"".iUrl('team','id='.$match['match_guest_id'])."\"target=\'_blank\'>".escapeOutput($match['match_guest_name'])."</a></h3><div class=\"well\">".Message('match_manage_complete_intro')."</div><form action=\"".$_SERVER['PHP_SELF'].
							"\"method=\'post\'class=\'form-horizontal\'><input type=\"hidden\"name=\'site\'value=\'$site\'/><input type=\"hidden\"name=\'action\'value=\'complete\'/><input type=\"hidden\"name=\'match\'value=\'$matchId\'/>";echo createFormGroup($i18n,
							'computetickets',['type'=>'boolean','value'=>'1'],'1','match_manage_complete_');echo'<div class=\'form-actions\'><button type=\'submit\'class=\'btn btn-primary\'>'.Message('match_manage_complete_button').'</button></div></form>';