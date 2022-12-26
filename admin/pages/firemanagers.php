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
ignore_user_abort(TRUE);
set_time_limit(300);
$mainTitle = $i18n->getMessage('firemanagers_navlabel');
if (!$admin['r_admin'] && !$admin['r_demo'] && !$admin[$page['permissionrole']]) throw new Exception($i18n->getMessage('error_access_denied'));
if (!$show) { ?>
	<h1><?php echo $mainTitle; ?></h1>
  <p><?php echo $i18n->getMessage('firemanagers_introduction'); ?></p>
  <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" class="form-horizontal">
	<input type="hidden" name="site" value="<?php echo $site; ?>">
	<fieldset>
    <legend><?php echo $i18n->getMessage('firemanagers_search_label'); ?></legend><?php
	$formFields = ['maxbudget' => ['type' => 'number', 'value' => (isset($_POST['maxbudget'])) ? escapeOutput($_POST['maxbudget']) : -1000000],
		'lastlogindays' => ['type' => 'number', 'value' => (isset($_POST['lastlogindays'])) ? escapeOutput($_POST['lastlogindays']) : 60], 'maxplayers' => ['type' => 'number', 'value' => (isset($_POST['maxplayers'])) ? escapeOutput($_POST['maxplayers']) : 15],
		'userid' => ['type' => 'foreign_key', 'value' => (isset($_POST['userid'])) ? escapeOutput($_POST['userid']) : '', 'entity' => 'users', 'jointable' => 'user', 'labelcolumns' => 'nick']];
	foreach ($formFields as $fieldId => $fieldInfo) echo FormBuilder::createFormGroup($i18n, $fieldId, $fieldInfo, $fieldInfo['value'], 'firemanagers_search_'); ?></fieldset>
	<div class="form-actions">
		<input type="submit" class="btn btn-primary" accesskey="s" title="Alt + s" value="<?php echo $i18n->getMessage('button_search'); ?>">
		<input type="reset" class="btn" value="<?php echo $i18n->getMessage('button_reset'); ?>"></div></form><?php
	if (!empty($_POST['maxbudget']) || !empty($_POST['lastlogindays']) || !empty($_POST['maxplayers']) || !empty($_POST['userid'])) {
		$columns = ['C.id' => 'team_id', 'C.name' => 'team_name', 'C.finanz_budget' => 'team_budget', '(SELECT COUNT(*) FROM '. $website->getConfig('db_prefix') . '_spieler AS PlayerTab WHERE PlayerTab.verein_id = C.id AND status = \'1\')' => 'team_players',
			'U.id' => 'user_id', 'U.nick' => 'user_nick', 'U.lastonline' => 'user_lastonline'];
		$fromTable = $website->getConfig('db_prefix') . '_verein AS C INNER JOIN ' . $website->getConfig('db_prefix') . '_user AS U ON U.id = C.user_id';
		$whereCondition = 'C.status = \'1\' AND (1=0';
		if (!empty($_POST['maxbudget'])) {
			$whereCondition .= ' OR C.finanz_budget < %d';
			$parameters[] = $_POST['maxbudget'];}
		if (!empty($_POST['lastlogindays'])) {
			$whereCondition .= ' OR U.lastonline < %d';
			$parameters[] = $website->getNowAsTimestamp() - $_POST['lastlogindays'] * 24 * 3600;}
		if (!empty($_POST['maxplayers'])) {
			$whereCondition .= ' OR (SELECT COUNT(*) FROM '. $website->getConfig('db_prefix') . '_spieler AS PlayerTab WHERE PlayerTab.verein_id = C.id AND status = \'1\') < %d';
			$parameters[] = $_POST['maxplayers'];}
		if (!empty($_POST['userid']) && $_POST['userid']) {
			$whereCondition .= ' OR U.id = %d';
			$parameters[] = $_POST['userid'];}
		$whereCondition .= ')';
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters, 50);
		if (!$result->num_rows) echo createInfoMessage($i18n->getMessage('firemanagers_search_nohits'), '');
		else { ?>
			<form action='<?php echo htmlentities($_SERVER['PHP_SELF']); ?>' method='post' class='form-horizontal' id='frmMain' name='frmMain'>
				<input type='hidden' name='site' value='<?php echo $site; ?>'>
				<input type='hidden' name='show' value='selectoptions'>
				<table class='table table-striped table-hover'>
					<thead>
						<tr>
							<th></th>
							<th><?php echo $i18n->getMessage('entity_club'); ?></th>
							<th><?php echo $i18n->getMessage('entity_users'); ?></th>
							<th><?php echo $i18n->getMessage('entity_users_lastonline'); ?></th>
							<th><?php echo $i18n->getMessage('entity_club_finanz_budget'); ?></th>
							<th><?php echo $i18n->getMessage('entity_player'); ?></th></tr></thead>
					<tbody><?php
					while ($row = $result->fetch_array()) {
						echo '<tr>';
						echo '<td><input type=\'checkbox\' name=\'selectedteams[]\' value=\''. $row['team_id'] . '\'/></td>';
						echo '<td class=\'tableRowSelectionCell\'><a href=\''. $website->getInternalUrl('team', 'id=' . $row['team_id']) . '\' target=\'_blank\'>'. escapeOutput($row['team_name']) . '</a></td>';
						echo '<td class=\'tableRowSelectionCell\'><a href=\''. $website->getInternalUrl('user', 'id=' . $row['user_id']) . '\' target=\'_blank\'>'. escapeOutput($row['user_nick']) . '</a></td>';
						echo '<td class=\'tableRowSelectionCell\'>'. $website->getFormattedDate($row['user_lastonline']) . '</td>';
						echo '<td class=\'tableRowSelectionCell\'>'. number_format($row['team_budget'], 0, ',', ' ') . '</td>';
						echo '<td class=\'tableRowSelectionCell\'>'. $row['team_players'] . '</td>';
						echo '</tr>' . PHP_EOL;} ?></tbody></table>
				<p><label class='checkbox'><input type='checkbox' name='selAll' value='1' onClick='selectAll()'><?php echo $i18n->getMessage('manage_select_all_label'); ?></label></p>
				<p><input type='submit' class='btn btn-primary' accesskey='l' title='Alt + l' value='<?php echo $i18n->getMessage('firemanagers_dismiss_button'); ?>'></p></form><?php }}}
elseif ($show == 'selectoptions') {
	if (!isset($_POST['selectedteams']) || !count($_POST['selectedteams'])) {
		echo createErrorMessage($i18n->getMessage('firemanagers_dismiss_noneselected'), '');
		echo '<a href=\'?site='. $site .'\' class=\'btn\'>'. $i18n->getMessage('back_label') .'</a>';}
	else { ?>
  <form action='<?php echo htmlentities($_SERVER['PHP_SELF']); ?>' method='post' class='form-horizontal'>
	<input type='hidden' name='site' value='<?php echo $site; ?>'>
	<input type='hidden' name='show' value='dismiss'>
	<input type='hidden' name='teamids' value='<?php echo hmtlspezialchar(implode(',', $_POST['selectedteams'])) ?>'>
		<fieldset>
    		<legend><?php echo $i18n->getMessage('firemanagers_dismiss_label'); ?></legend><?php
	$formFields = ['disableusers' => ['type' => 'boolean', 'value' => 0], 'minbudget' => ['type' => 'number', 'value' => 1000000], 'message_subject' => ['type' => 'text', 'value' => ''], 'message_content' => ['type' => 'textarea', 'value' => '']];
	foreach ($formFields as $fieldId => $fieldInfo) echo FormBuilder::createFormGroup($i18n, $fieldId, $fieldInfo, $fieldInfo['value'], 'firemanagers_dismiss_'); ?></fieldset>
		<fieldset>
    		<legend><?php echo $i18n->getMessage('firemanagers_dismiss_label_players'); ?></legend><?php
	$formFields = ['mincontractmatches' => ['type' => 'number','value' => 15],'strength_min_freshness' => ['type' => 'percent','value' => 70],'strength_min_stamina' => ['type' => 'percent','value' => 70],'strength_min_satisfaction' => ['type' => 'percent', 'value' => 70]];
	foreach ($formFields as $fieldId => $fieldInfo) echo FormBuilder::createFormGroup($i18n, $fieldId, $fieldInfo, $fieldInfo['value'], 'firemanagers_dismiss_'); ?></fieldset>
		<fieldset>
    		<legend><?php echo $i18n->getMessage('firemanagers_dismiss_label_minteamsize'); ?></legend>
    		<p><?php echo $i18n->getMessage('firemanagers_dismiss_label_minteamsize_intro'); ?></p><?php
	$formFields = ['option_T' => ['type' => 'number', 'value' => 2], 'option_LV' => ['type' => 'number', 'value' => 1], 'option_IV' => ['type' => 'number', 'value' => 2], 'option_RV' => ['type' => 'number', 'value' => 1], 'option_LM' => ['type' => 'number', 'value' => 1],
		'option_DM' => ['type' => 'number', 'value' => 1], 'option_ZM' => ['type' => 'number', 'value' => 1], 'option_OM' => ['type' => 'number', 'value' => 1], 'option_RM' => ['type' => 'number', 'value' => 1], 'option_LS' => ['type' => 'number', 'value' => 1],
		'option_MS' => ['type' => 'number', 'value' => 1], 'option_RS' => ['type' => 'number', 'value' => 1], 'player_age' => ['type' => 'number', 'value' => 25], 'player_age_deviation' => ['type' => 'number', 'value' => 3],
		'entity_player_vertrag_gehalt' => ['type' => 'number', 'value' => 10000], 'entity_player_vertrag_spiele' => ['type' => 'number', 'value' => 60], 'entity_player_w_staerke' => ['type' => 'percent', 'value' => 50],
		'entity_player_w_technik' => ['type' => 'percent', 'value' => 50], 'entity_player_w_kondition' => ['type' => 'percent', 'value' => 70], 'entity_player_w_frische' => ['type' => 'percent', 'value' => 80],
		'entity_player_w_zufriedenheit' => ['type' => 'percent', 'value' => 80], 'playersgenerator_label_deviation' => ['type' => 'percent', 'value' => 10]];
	foreach ($formFields as $fieldId => $fieldInfo) echo FormBuilder::createFormGroup($i18n, $fieldId, $fieldInfo, $fieldInfo['value'], ''); ?></fieldset>
	<div class='form-actions'>
		<input type='submit' class='btn btn-primary' accesskey='s' title='Alt + s' value='<?php echo $i18n->getMessage('firemanagers_dismiss_button'); ?>'>
		<a class='btn' href='?site=<?php echo $site; ?>'><?php echo $i18n->getMessage('button_cancel'); ?></a></div></form><?php }}
elseif ($show == 'dismiss') {
  if ($admin['r_demo']) $err[] = $i18n->getMessage('validationerror_no_changes_as_demo');
  if (isset($err)) include('validationerror.inc.php');
  else {
	$strengths = ['strength' => $_POST['entity_player_w_staerke'],'technique' => $_POST['entity_player_w_technik'],'stamina' => $_POST['entity_player_w_kondition'],'freshness' => $_POST['entity_player_w_frische'],'satisfaction' => $_POST['entity_player_w_zufriedenheit']];
	$teamIds = explode(',', $_POST['teamids']);
	foreach($teamIds as $teamId) {
		$team = TeamsDataService::getTeamSummaryById($website, $db, $teamId);
		$teamcolumns = ['user_id' => '', 'captain_id' => '', 'finanz_budget' => (!empty($_POST['minbudget'])) ? max($_POST['minbudget'], $team['team_budget']) : $team['team_budget']];
		$db->queryUpdate($teamcolumns, $website->getConfig('db_prefix') . '_verein', 'id = %d', $teamId);
		if (isset($_POST['disableusers']) && $_POST['disableusers']) $db->queryUpdate(array('status' => '0'), $website->getConfig('db_prefix') . '_user', 'id = %d', $team['user_id']);
		if (!empty($_POST['message_subject']) && !empty($_POST['message_content'])) $db->queryInsert(['empfaenger_id' => $team['user_id'],'absender_name' => $website->getConfig('projectname'),'absender_id' => '','datum' => $website->getNowAsTimestamp(),
			'betreff' => trim($_POST['message_subject']),'nachricht' => trim($_POST['message_content'])], $website->getConfig('db_prefix') . '_briefe');
		$positionsCount = ['T' => 0, 'LV' => 0, 'IV' => 0, 'RV' => 0, 'LM' => 0, 'ZM' => 0, 'RM' => 0, 'DM' => 0, 'OM' => 0, 'LS' => 0, 'MS' => 0, 'RS' => 0];
		$result = $db->querySelect('id, position_main, w_kondition, w_frische, w_zufriedenheit, vertrag_spiele', $website->getConfig('db_prefix') . '_spieler', 'verein_id = %d AND status = \'1\'', $teamId);
		while ($player = $result->fetch_array()) {
			$updateRequired = FALSE;
			$freshness = $player['w_frische'];
			if (!empty($_POST['strength_min_freshness']) && $_POST['strength_min_freshness'] > $freshness) {
				$updateRequired = TRUE;
				$freshness = $_POST['strength_min_freshness'];}
			$stamina = $player['w_kondition'];
			if (!empty($_POST['strength_min_stamina']) && $_POST['strength_min_stamina'] > $stamina) {
				$updateRequired = TRUE;
				$stamina = $_POST['strength_min_stamina'];}
			$satisfaction = $player['w_zufriedenheit'];
			if (!empty($_POST['strength_min_satisfaction']) && $_POST['strength_min_satisfaction'] > $satisfaction) {
				$updateRequired = TRUE;
				$satisfaction = $_POST['strength_min_satisfaction'];}
			$contractMatches = $player['vertrag_spiele'];
			if (!empty($_POST['mincontractmatches']) && $_POST['mincontractmatches'] > $contractMatches) {
				$updateRequired = TRUE;
				$contractMatches = $_POST['mincontractmatches'];}
			$db->queryUpdate(['w_frische' => $freshness, 'w_kondition' => $stamina, 'w_zufriedenheit' => $satisfaction, 'vertrag_spiele' => $contractMatches], $website->getConfig('db_prefix') . '_spieler', 'id = %d', $player['id']);
			if (strlen($player['position_main'])) $positionsCount[$player['position_main']] = $positionsCount[$player['position_main']] + 1;}
		$positions = ['T' => (!empty($_POST['option_T'])) ? max(0, $_POST['option_T'] - $positionsCount['T']) : 0, 'LV' => (!empty($_POST['option_LV'])) ? max(0, $_POST['option_LV'] - $positionsCount['LV']) : 0,
			'IV' => (!empty($_POST['option_IV'])) ? max(0, $_POST['option_IV'] - $positionsCount['IV']) : 0, 'RV' => (!empty($_POST['option_RV'])) ? max(0, $_POST['option_RV'] - $positionsCount['RV']) : 0,
			'LM' => (!empty($_POST['option_LM'])) ? max(0, $_POST['option_LM'] - $positionsCount['LM']) : 0, 'ZM' => (!empty($_POST['option_ZM'])) ? max(0, $_POST['option_ZM'] - $positionsCount['ZM']) : 0,
			'RM' => (!empty($_POST['option_RM'])) ? max(0, $_POST['option_RM'] - $positionsCount['RM']) : 0, 'DM' => (!empty($_POST['option_DM'])) ? max(0, $_POST['option_DM'] - $positionsCount['DM']) : 0,
			'OM' => (!empty($_POST['option_OM'])) ? max(0, $_POST['option_OM'] - $positionsCount['OM']) : 0, 'LS' => (!empty($_POST['option_LS'])) ? max(0, $_POST['option_LS'] - $positionsCount['LS']) : 0,
			'MS' => (!empty($_POST['option_MS'])) ? max(0, $_POST['option_MS'] - $positionsCount['MS']) : 0, 'RS' => (!empty($_POST['option_RS'])) ? max(0, $_POST['option_RS'] - $positionsCount['RS']) : 0];
		$playersToGenerate = FALSE;
		foreach($positions as $posCount) {
			if ($posCount > 0) {
				$playersToGenerate = TRUE;
				break;}}
		if ($playersToGenerate) DataGeneratorService::generatePlayers($website, $db, $teamId, $_POST['player_age'], $_POST['player_age_deviation'], $_POST['entity_player_vertrag_gehalt'], $_POST['entity_player_vertrag_spiele'], $strengths, $positions,
			$_POST['playersgenerator_label_deviation']);}
	echo createSuccessMessage($i18n->getMessage('firemanagers_dismiss_success'), '');
    echo '<p>&raquo; <a href=\'?site='. $site .'\'>'. $i18n->getMessage('back_label') . '</a></p>';}}
