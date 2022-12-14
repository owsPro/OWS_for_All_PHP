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
require(__DIR__ . '/frontbase.inc.php');
$output['messages'] = '';
$output['content'] = '';
header('Content-type: application/json; charset=utf-8');
if ($website->getConfig('offline') !== 'offline') {
	$validationMessages = null;
	$actionId = $website->getRequestParameter(PARAM_ACTION);
	if ($actionId !== NULL) {
		try { ActionHandler::handleAction($website, $db, $i18n, $actionId); }
		catch (ValidationException $ve) {
			$validationMessages = $ve->getMessages();
			$website->addFrontMessage(new FrontMessage(MESSAGE_TYPE_ERROR, $i18n->getMessage('validation_error_box_title'), $i18n->getMessage('validation_error_box_message')));}
		catch (Exception $e) {$website->addFrontMessage(new FrontMessage(MESSAGE_TYPE_ERROR, $i18n->getMessage('errorpage_title'), $e->getMessage()));}}
	$viewHandler = new ViewHandler($website, $db, $i18n, $page, $block, $validationMessages);
	try{$blockId = $website->getRequestParameter(PARAM_BLOCK);
		if (strlen($blockId) && isset($block[$blockId])) $output['content'] = $viewHandler->renderBlock($blockId, json_decode($block[$blockId], TRUE), $parameters);
		else {
			$pageId = $website->getRequestParameter(PARAM_PAGE);
			if ($pageId != null) {
				$website->setPageId($pageId);
				$output['content'] = $viewHandler->handlePage($pageId, $parameters);}}}
	catch (Exception $e) {
		$website->addFrontMessage(new FrontMessage(MESSAGE_TYPE_ERROR, $i18n->getMessage('errorpage_title'), $e->getMessage()));
		$output['messages'] = $e->getMessage();}}
if ($website->getRequestParameter('contentonly')) echo $output['content'];
else {
	$output['messages'] = $viewHandler->renderBlock('messagesblock', json_decode($block['messagesblock'], TRUE));
	echo json_encode($output);}
