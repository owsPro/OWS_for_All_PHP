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
					echo '<h1>' . Message('match_manage_reportitems') . '</h1>';

// Check if user has access to manage matches
if (!$admin['r_admin'] && !$admin['r_demo'] && !$admin['r_spiele']) {
    throw new Exception(Message('error_access_denied'));
}

echo '<p><a href=\'?site=manage&entity=match\' class=\'btn\'>' . Message('back_label') . '</a></p>';

// Get match ID from request
$matchId = (isset($_REQUEST['match']) && is_numeric($_REQUEST['match'])) ? $_REQUEST['match'] : 0;

// Get match details from database
$match = MatchesDataService::getMatchById($website, $db, $matchId, FALSE, FALSE);

// Check if match exists
if (!count($match)) {
    throw new Exception('illegal match id');
}

// Handle delete action
if ($action == 'delete') {
    // Check if user has demo access
    if ($admin['r_demo']) {
        throw new Exception(Message('validationerror_no_changes_as_demo'));
    }

    $itemId = (int)$_REQUEST['itemid'];
    $db->queryDelete(Config('db_prefix') . '_matchreport', 'id=%d', [$itemId]);

    echo createSuccessMessage(Message('manage_success_delete'), '');
}

// Handle create action
elseif ($action == 'create') {
    // Check if user has demo access
    if ($admin['r_demo']) {
        throw new Exception(Message('validationerror_no_changes_as_demo'));
    }

    $homeActive = ($match['match_home_id'] == $_POST['team_id']) ? '1' : '0';
    $message_id = (int)$_POST['message_id'];
    $minute = (int)$_POST['minute'];
    $playerNames = $_POST['playernames'];
    $goals = $_POST['intermediateresult'];

    if ($message_id && $minute) {
        $db->queryInsert(
            [
                'match_id' => $matchId,
                'message_id' => $message_id,
                'active_home' => $homeActive,
                'minute' => $minute,
                'goals' => $goals,
                'playernames' => $playerNames
            ],
            Config('db_prefix') . '_matchreport'
        );
    }
}

echo '<form action=\'' . htmlspecialchars((string)$_SERVER['PHP_SELF'], ENT_COMPAT, 'UTF-8') . '\' class=\'form-horizontal\' method=\'post\'>';
echo '<input type=\'hidden\' name=\'action\' value=\'create\'>';
echo '<input type=\'hidden\' name=\'site\' value=\'$site\'>';
echo '<input type=\'hidden\' name=\'match\' value=\'$matchId\'>';
echo '<fieldset>';
echo '<legend>' . Message('match_manage_createmessage_title') . '</legend>';

echo '<div class=\'control-group\'>';
echo '<label class=\'control-label\' for=\'team_id\'>' . Message('entity_player_verein_id') . '</label>';
echo '<div class=\'controls\'>';
echo '<select name=\'team_id\' id=\'team_id\'>';
echo '<option value=\'' . $match['match_home_id'] . '\'>' . htmlspecialchars((string)$match['match_home_name'], ENT_COMPAT, 'UTF-8') . '</option>';
echo '<option value=\'' . $match['match_guest_id'] . '\'>' . htmlspecialchars((string)$match['match_guest_name'], ENT_COMPAT, 'UTF-8') . '</option>';
echo '</select>';
echo '</div>';
echo '</div>';

echo createFormGroup($i18n, 'message_id', ['type' => 'foreign_key', 'jointable' => 'spiel_text', 'entity' => 'matchtext', 'labelcolumns' => 'aktion,nachricht'], '', 'match_manage_reportmsg_');
echo createFormGroup($i18n, 'playernames', ['type' => 'text'], '', 'match_manage_reportmsg_');
echo createFormGroup($i18n, 'minute', ['type' => 'number'], '', 'match_manage_reportmsg_');
echo createFormGroup($i18n, 'intermediateresult', ['type' => 'text'], '', 'match_manage_reportmsg_');

echo '</fieldset>';
echo '<div class=\'form-actions\'>';
echo '<button type=\'submit\' class=\'btn btn-primary\'>' . Message('button_save') . '</button>';
echo '</div>';
echo '</form>';

$reportItems = MatchesDataService::getMatchReportMessages($website, $db, $i18n, $matchId);

if (!count($reportItems)) {
    echo createInfoMessage('', Message('match_manage_reportitems_noitems'));
} else {
    echo '<table class=\'table table-bordered table-striped table-hover\'>';
    echo '<thead>';
    echo '<tr>';
    echo '<th>' . Message('match_manage_reportmsg_minute') . '</th>';
    echo '<th>' . Message('entity_matchtext_aktion') . '</th>';
    echo '<th>' . Message('entity_matchtext_nachricht') . '</th>';
    echo '<th>' . Message('match_manage_reportmsg_playernames') . '</th>';
    echo '<th>' . Message('match_manage_reportmsg_intermediateresult') . '</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    $homeTeam = htmlspecialchars((string)$match['match_home_name'], ENT_COMPAT, 'UTF-8');
    $guestTeam = htmlspecialchars((string)$match['match_guest_name'], ENT_COMPAT, 'UTF-8');

    foreach ($reportItems as $reportItem) {
        echo '<tr>';
        echo '<td>';
        echo '<a href=\'?site=$site&action=delete&match=$matchId&itemid=' . htmlspecialchars((string)$reportItem['report_id'], ENT_COMPAT, 'UTF-8') . '\' title=\''.Message('manage_delete').'\' class=\'deleteLink\'><i class=\'icon-trash\'></i></a> ';
        echo htmlspecialchars((string)$reportItem['minute'], ENT_COMPAT, 'UTF-8');
        echo '</td>';
        echo '<td><small>';
        if ($reportItem['active_home']) {
            echo $homeTeam;
        } else {
            echo $guestTeam;
        }
        echo '</small><br>';
        echo Message('option_' . htmlspecialchars((string)$reportItem['type'], ENT_COMPAT, 'UTF-8'));
        echo '</td>';
        echo '<td>' . htmlspecialchars((string)$reportItem['message'], ENT_COMPAT, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars((string)$reportItem['playerNames'], ENT_COMPAT, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars((string)$reportItem['goals'], ENT_COMPAT, 'UTF-8') . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
}