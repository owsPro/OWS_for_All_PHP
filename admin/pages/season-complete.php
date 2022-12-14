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
$mainTitle = $i18n->getMessage('season_complete_title');
if (isset($_REQUEST['id'])) $id = (int) $_REQUEST['id'];
echo '<h1>' . $mainTitle .'</h1>';
if (!$admin['r_admin'] && !$admin['r_demo'] && !$admin[$page['permissionrole']]) throw new Exception($i18n->getMessage('error_access_denied'));
if (!$show) { ?>
  <p><?php echo $i18n->getMessage('season_complete_introduction'); ?></p><?php
  $columns = ['S.id' => 'id', 'S.name' => 'name', 'L.name' => 'league_name'];
  $fromTable = $conf['db_prefix'] .'_saison AS S INNER JOIN ' . $conf['db_prefix'] .'_liga AS L ON L.id = S.liga_id';
  $whereCondition = 'S.beendet = \'0\' AND 0 = (SELECT COUNT(*) FROM '. $conf['db_prefix'] . '_spiel AS M WHERE M.berechnet = \'0\' AND M.saison_id = S.id) ORDER BY L.name ASC, S.name ASC';
  $result = $db->querySelect($columns, $fromTable, $whereCondition);
  if (!$result->num_rows) echo '<p><strong>' . $i18n->getMessage('season_complete_noseasons') . '</strong></p>';
  else { ?>
  <table class='table table-striped'>
  	<thead>
  		<tr>
  			<th><?php echo $i18n->getMessage('entity_season_name'); ?></th>
  			<th><?php echo $i18n->getMessage('entity_season_liga_id'); ?></th></tr></thead>
  	<tbody><?php
		while ($season = $result->fetch_array()) {
			echo '<tr>';
			echo '<td><a href=\'?site='. $site . '&show=select&id='. $season['id'] . '\'>'. $season['name'] . '</a></td>';
			echo '<td>'. $season['league_name'] . '</td>';
			echo '</tr>';} ?></tbody></table><?php }}
elseif ($show == 'select') {
	$columns = '*';
	$whereCondition = 'id = %d';
	$result = $db->querySelect($columns, $conf['db_prefix'] .'_saison', $whereCondition, $id, 1);
	$season = $result->fetch_array();
	if (!$season) throw new Exception('Invalid URL - Item does not exist.'); ?>
	<form action='<?php echo htmlentities($_SERVER['PHP_SELF']); ?>' method='post' class='form-horizontal'>
	<input type='hidden' name='show' value='complete'>
	<input type='hidden' name='id' value='<?php echo htmlentities($id); ?>'>
	<input type='hidden' name='site' value='<?php echo $site; ?>'>
	<fieldset>
	<legend><?php echo escapeOutput($season['name']); ?></legend><?php
	$formFields = ['playerdisableage' => ['type' => 'number', 'value' => 35, 'required' => 'false'], 'target_missed_firemanager' => ['type' => 'boolean', 'value' => 0, 'required' => 'false'],
		'target_missed_popularityreduction' => ['type' => 'percent', 'value' => 20, 'required' => 'false'], 'target_missed_penalty' => ['type' => 'number', 'value' => 0, 'required' => 'false'],
		'target_accomplished_reward' => ['type' => 'number', 'value' => 0, 'required' => 'false'], 'youthplayers_age_delete' => ['type' => 'number', 'value' => 19, 'required' => 'false']];
	foreach ($formFields as $fieldId => $fieldInfo) echo FormBuilder::createFormGroup($i18n, $fieldId, $fieldInfo, $fieldInfo['value'], 'season_complete_label_'); ?></fieldset>
	<div class='form-actions'>
		<input type='submit' class='btn btn-primary' accesskey='s' title='Alt + s' value='<?php echo $i18n->getMessage('season_complete_submit'); ?>'>
		<input type='reset' class='btn' value='<?php echo $i18n->getMessage('button_reset'); ?>'></div></form><?php }
elseif ($show == 'complete') {
	if ($admin['r_demo']) $err[] = $i18n->getMessage('validationerror_no_changes_as_demo');
	if (isset($err)) include('validationerror.inc.php');
	else {
		$columns = '*';
		$whereCondition = 'id = %d AND beendet = \'0\'';
		$result = $db->querySelect($columns, $conf['db_prefix'] .'_saison', $whereCondition, $id, 1);
		$season = $result->fetch_array();
		if (!$season) throw new Exception('Invalid request - Item does not exist.');
		$result->free();
		$seasoncolumns['beendet'] = '1';
		$playersSql = 'UPDATE ' . $conf['db_prefix'] .'_spieler AS P INNER JOIN ' . $conf['db_prefix'] .'_verein AS T ON T.id = P.verein_id SET ';
		$playerResetColumns = array('P.sa_tore', 'P.sa_spiele', 'P.sa_karten_gelb', 'P.sa_karten_gelb_rot', 'P.sa_karten_rot', 'P.sa_assists');
		foreach ($playerResetColumns as $playerResetColumn) $playersSql .= $playerResetColumn . '= 0, ';
		$playersSql .= ' P.age = P.age + 1 WHERE T.liga_id = ' . $season['liga_id'];
		$db->executeQuery($playersSql);
		$playersSql = 'UPDATE ' . $conf['db_prefix'] .'_spieler AS P SET ';
		$firstColumn = TRUE;
		foreach ($playerResetColumns as $playerResetColumn) {
			if ($firstColumn) $firstColumn = FALSE;
			else $playersSql .= ', ';
			$playersSql .= $playerResetColumn . '= 0';}
		$playersSql .= ' WHERE P.status = \'1\' AND (P.verein_id = 0 OR P.verein_id IS NULL)';
		$db->executeQuery($playersSql);
		$retirementAge = (int) $_POST['playerdisableage'];
		if ($retirementAge > 0) {
			$ageColumn = 'age';
			if ($conf['players_aging'] == 'birthday') $ageColumn = 'TIMESTAMPDIFF(YEAR,geburtstag,CURDATE())';
			$retiredcolumns = ['P.status' => '0', 'P.verein_id' => ''];
			$whereCondition = 'T.liga_id = %d AND ' . $retirementAge . ' <= ' . $ageColumn;
			$db->queryUpdate($retiredcolumns, $conf['db_prefix'] .'_spieler AS P INNER JOIN ' . $conf['db_prefix'] .'_verein AS T ON T.id = P.verein_id', $whereCondition, $season['liga_id']);}
		$result = $db->querySelect('target_league_id,platz_von AS rank_from,platz_bis AS rank_to', $conf['db_prefix'] .'_tabelle_markierung', 'liga_id = %d AND target_league_id IS NOT NULL AND target_league_id > 0', $season['liga_id']);
		$moveConfigs = array();
		while ($moveConfig = $result->fetch_array()) $moveConfigs[] = $moveConfig;
		$result->free();
		$columns = 'id, sponsor_id, min_target_rank, user_id';
		$fromTable = $conf['db_prefix'] .'_verein';
		$whereCondition = 'liga_id = %d AND sa_spiele > 0 ORDER BY sa_punkte DESC, (sa_tore - sa_gegentore) DESC, sa_siege DESC, sa_unentschieden DESC, sa_tore DESC';
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $season['liga_id']);
		$maxYouthAge = (int) $_POST['youthplayers_age_delete'];
		$rank = 1;
		while($team = $result->fetch_array()) {
			if ($rank <= 5) {
				$seasoncolumns['platz_' . $rank . '_id'] = $team['id'];
				if ($rank === 1 && $team['sponsor_id']) {
					$sponsorres = $db->querySelect('name, b_meisterschaft', $conf['db_prefix'] .'_sponsor', 'id = %d', $team['sponsor_id']);
					$sponsor = $sponsorres->fetch_array();
					if ($sponsor) BankAccountDataService::creditAmount($website, $db, $team['id'], $sponsor['b_meisterschaft'], 'sponsor_championship_bonus_subject', $sponsor['name']);}}
			foreach ($moveConfigs as $moveConfig) {
				if ($moveConfig['rank_from'] <= $rank && $moveConfig['rank_to'] >= $rank) {
					$teamcolumns = ['liga_id' => $moveConfig['target_league_id'], 'sa_tore' => 0, 'sa_gegentore' => 0, 'sa_spiele' => 0, 'sa_siege' => 0, 'sa_niederlagen' => 0, 'sa_unentschieden' => 0, 'sa_punkte' => 0];
					$db->queryUpdate($teamcolumns, $conf['db_prefix'] .'_verein', 'id = %d', $team['id']);
					break;}}
			if ($team['user_id'] > 0) {
				$res = $db->querySelect('id', $conf['db_prefix'] .'_badge', 'event = \'completed_season_at_x\' AND event_benchmark = ' . $rank . ' AND id NOT IN (SELECT badge_id FROM ' . $conf['db_prefix'] .'_badge_user WHERE user_id = ' . $team['user_id'] . ')',null, 1);
				$badge = $res->fetch_array();
				if ($badge) BadgesDataService::awardBadge($website, $db, $team['user_id'], $badge['id']);
				$db->queryInsert(['user_id' => $team['user_id'],'team_id' => $team['id'],'season_id' => $season['id'],'rank' => $rank,'date_recorded' => $website->getNowAsTimestamp()], $conf['db_prefix'] .'_achievement');
				if ($team['min_target_rank'] > 0 && $team['min_target_rank'] < $rank) {
					if (isset($_POST['target_missed_firemanager']) && $_POST['target_missed_firemanager']) $db->queryUpdate(array('user_id' => ''), $conf['db_prefix'] .'_verein', 'id = %d', $team['id']);
					if ($_POST['target_missed_popularityreduction'] > 0) {
						$userres = $db->querySelect('fanbeliebtheit', $conf['db_prefix'] .'_user', 'id = %d', $team['user_id']);
						$manager = $userres->fetch_array();
						if ($manager) {
							$popularity = max(1, $manager['fanbeliebtheit'] - $_POST['target_missed_popularityreduction']);
							$db->queryUpdate(array('fanbeliebtheit' => $popularity), $conf['db_prefix'] .'_user', 'id = %d', $team['user_id']);}}
					if ($_POST['target_missed_penalty'] > 0) BankAccountDataService::debitAmount($website, $db, $team['id'], $_POST['target_missed_penalty'], 'seasontarget_failed_penalty_subject', $website->getConfig('projectname'));}
				elseif ($team['min_target_rank'] > 0 && $team['min_target_rank'] >= $rank && $_POST['target_accomplished_reward'] > 0) BankAccountDataService::creditAmount($website, $db, $team['id'], $_POST['target_accomplished_reward'],
					'seasontarget_accomplished_reward_subject', $website->getConfig('projectname'));}
			$youthresult = $db->querySelect('id,age', $conf['db_prefix'] . '_youthplayer', 'team_id = %d', $team['id']);
			while ($youthplayer = $youthresult->fetch_array()) {
				$playerage = $youthplayer['age'] + 1;
				if ($maxYouthAge > 0 && $maxYouthAge <= $playerage) $db->queryDelete($conf['db_prefix'] . '_youthplayer', 'id = %d', $youthplayer['id']);
				else $db->queryUpdate(array('age' => $playerage), $conf['db_prefix'] . '_youthplayer', 'id = %d', $youthplayer['id']);}
			$youthresult->free();
			$event = new SeasonOfTeamCompletedEvent($website, $db, $i18n, $team['id'], $season['id'], $rank);
			PluginMediator::dispatchEvent($event);
			$rank++;}
		$teamcolumns = ['sa_tore' => 0, 'sa_gegentore' => 0, 'sa_spiele' => 0, 'sa_siege' => 0, 'sa_niederlagen' => 0, 'sa_unentschieden' => 0, 'sa_punkte' => 0];
		$db->queryUpdate($teamcolumns, $conf['db_prefix'] .'_verein', 'liga_id = %d', $season['liga_id']);
		$db->queryUpdate($seasoncolumns, $conf['db_prefix'] .'_saison', 'id = %d', $season['id']);
		echo createSuccessMessage($i18n->getMessage('alert_save_success'), '');
		echo '<p>&raquo; <a href=\'?site='. $site .'\'>'. $i18n->getMessage('back_label') . '</a></p>';}}
