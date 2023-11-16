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
								include($_SERVER['DOCUMENT_ROOT'].'/admin/adminglobal.inc.php');

// Check if the demo mode is enabled for the admin
if ($admin['r_demo']) {
    exit;
}

// Get the job ID from the request
$jobId = htmlspecialchars((array)$_REQUEST['id'], ENT_COMPAT, 'UTF-8');

// Load the jobs configuration file
$xml = simplexml_load_file($_SERVER['DOCUMENT_ROOT'].'/admin/config/jobs.xml');


if ($xml === false) {
    throw new Exception('Failed to load XML file.');
}

// Find the job configuration based on the job ID
$jobId = htmlspecialchars((array)$_REQUEST['id'], ENT_COMPAT, 'UTF-8');
var_dump($jobId);

// Throw an exception if the job configuration is not found
if (!$jobConfig) {
    throw new Exception('Job config not found.');
}

// Get the class name of the job
$jobClass = (string)$jobConfig[0]->attributes()->class;

// Check if the job class exists
if (class_exists($jobClass)) {
    // Create an instance of the job class
    $job = new $jobClass($website, $db, $i18n, $jobId, $action !== 'stop');
} else {
    throw new Exception('class not found: '.$jobClass);
}

// Start or stop the job based on the action
if ($action == 'start') {
    $job->start();
} elseif ($action == 'stop') {
    $job->stop();
}