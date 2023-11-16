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

$mainTitle = Message('all_logging_title');

if (!$admin['r_admin'] && !$admin['r_demo']) {
  echo '<p>'. Message('error_access_denied') . '</p>';
  exit;
}

if (!$show) {

  ?>

  <h1><?php echo $mainTitle; ?></h1>

  <p><?php echo Message('all_logging_intro'); ?></p>

  <?php

  $datei = '../generated/adminlog.php';

  if (!file_exists($datei)) echo createErrorMessage(Message('alert_error_title'), Message('all_logging_filenotfound'));
  elseif ($admin['r_demo']) echo createErrorMessage(Message('error_access_denied'), '');
  else {

    if ($action == 'leeren') {

      $fp = fopen($datei, 'w+');
      $ip = getenv('REMOTE_ADDR');
      $content = 'Truncated by '. $admin['name'] .' (id: '. $admin['id'] . '), '. $ip .', '. date('d.m.y - H:i:s');
      fwrite($fp, $content);
      fclose($fp);

      if ($fp) echo createSuccessMessage(Message('all_logging_alert_logfile_truncated'), '');
      else echo createErrorMessage(Message('alert_error_title'), Message('all_logging_error_not_truncated'));

    }

    $datei_gr = filesize($datei);
    $gr_kb = round($datei_gr / 1024);
    if ($datei_gr && !$gr_kb) $gr_kb = 1;

    echo '<div class=\'well\'>'. sprintf(Message('all_logging_filesize'), number_format($gr_kb, 0, ' ', ',')) .'</div>';

    if (!$datei_gr) echo '<p>'. Message('empty_list') . '</p>';
    else {

      ?>

      <form action='<?php echo escapeOutput($_SERVER['PHP_SELF']); ?>' method='post'>
        <input type='hidden' name='action' value='leeren'>
		<input type='hidden' name='site' value='<?php echo $site; ?>'>
        <p><input type='submit' class='btn' value='<?php echo Message('all_logging_button_empty_file'); ?>'></p>

      </form>

      <p>(<?php echo Message('all_logging_only_last_entries_shown'); ?>)</p>

            <table class='table table-bordered table-striped'>
              <tr>
                <th><?php echo Message('all_logging_label_no'); ?></th>
                <th><?php echo Message('all_logging_label_user'); ?></th>
                <th><?php echo Message('all_logging_label_ip'); ?></th>
                <th><?php echo Message('all_logging_label_time'); ?></th>
              </tr>
              <?php

              $file = file($datei);
              $lines = count($file);
              $min = $lines - 50;
              if ($min < 0) $min = 0;

              for ($i = $lines-1; $i >= $min; $i--) {
				$line = $file[$i];

                $row = explode(', ', $line);

				$n = $i + 1;
                echo '<tr>
                  <td><b>'. $n .'</b></td>
                  <td>'. escapeOutput($row[0]) .'</td>
                  <td>'. escapeOutput($row[1]) .'</td>
                  <td>'. escapeOutput($row[2]) .'</td>
                </tr>';
              }

              ?>
            </table>

      <?php

    }

  }

}


?>