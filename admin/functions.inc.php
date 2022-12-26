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
function escapeOutput($message) { return htmlspecialchars($message, ENT_COMPAT, 'UTF-8');}
function createWarningMessage($title, $message) { return createMessage('warning', $title, $message);}
function createInfoMessage($title, $message) { return createMessage('info', $title, $message);}
function createErrorMessage($title, $message) { return createMessage('error', $title, $message);}
function createSuccessMessage($title, $message) { return createMessage('success', $title, $message);}
function createMessage($severity, $title, $message) {
  $html = '<div class=\'alert alert-'. $severity . '\'>';
  $html .= '<button type=\'button\' class=\'close\' data-dismiss=\'alert\'>&times;</button>';
  $html .= '<h4>'. $title .'</h4>';
  $html .= $message;
  $html .= '</div>';
  return $html;}
function logAdminAction(WebSoccer $websoccer, $type, $username, $entity, $entityValue) {
	$userIp = getenv('REMOTE_ADDR');
	$message = $websoccer->getFormattedDatetime($websoccer->getNowAsTimestamp()) . ';' . $username . ';' . $userIp . ';' . $type . ';' . $entity . ';' . $entityValue;
	$file = BASE_FOLDER . '/generated/entitylog.php';
	$fw = new FileWriter($file, FALSE);
	$fw->writeLine($message);
	$fw->close();}
