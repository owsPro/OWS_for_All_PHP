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
define('BASE_FOLDER', __DIR__ .'/..');
define('JOB','//job[@id = \''. $jobId . '\']');
include(BASE_FOLDER . '/admin/adminglobal.inc.php');
if ($admin['r_demo']) exit;
$jobId = $_REQUEST['id'];
$xml = simplexml_load_file(JOBS_CONFIG_FILE);
$jobConfig = $xml->xpath(JOB);
if (!$jobConfig) throw new Exception('Job config not found.');
$jobClass = (string) $jobConfig[0]->attributes()->class;
if (class_exists($jobClass)) $job = new $jobClass($website, $db, $i18n, $jobId, $action !== 'stop');
else throw new Exception('class not found: ' . $jobClass);
if ($action == 'start') $job->start();
elseif ($action == 'stop') $job->stop();

