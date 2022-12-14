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
if (version_compare(PHP_VERSION, '5.4.0') < 0){echo'Minimum PHP 5.4.0 !';exit;}
include(__DIR__ . '/frontbase.inc.php');
$isOffline = FALSE;
if ($website->getConfig('offline') == 'offline') $isOffline = TRUE;
else {
	$offlineTimeSpansConfig = $website->getConfig('offline_times');
	if (strlen($offlineTimeSpansConfig)) {
		$timeSpans = explode(',', $offlineTimeSpansConfig);
		$now = $website->getNowAsTimestamp();
		foreach ($timeSpans as $timeSpan) {
			$timeSpanElements = explode('-', trim($timeSpan));
			if (count($timeSpanElements) != 2) throw new Exception('Configuration error: Recurring offline mode time span must have a format like 15:00-17:00.');
			$fromTimestamp = strtotime('today ' . trim($timeSpanElements[0]));
			$toTimestamp = strtotime('today ' . trim($timeSpanElements[1]));
			if ($fromTimestamp <= $now && $now <= $toTimestamp) {
				$isOffline = TRUE;
				break;}}}}
if ($isOffline) {
	$parameters['offline_message'] = nl2br($website->getConfig('offline_text'));
	echo $website->getTemplateEngine($i18n)->loadTemplate('views/offline')->render($parameters);}
else {
	if (!isset($_SESSION['badgechecked']) && $website->getUser()->getRole() == ROLE_USER && $website->getUser()->getClubId($website, $db)) {
		$userId = $website->getUser()->id;
		$result = $db->querySelect('datum_anmeldung', $website->getConfig('db_prefix') . '_user', 'id = %d', $userId);
		$userinfo = $result->fetch_array();
		if ($userinfo['datum_anmeldung']) {
			$numberOfRegisteredDays = round(($website->getNowAsTimestamp() - $userinfo['datum_anmeldung']) / (3600 * 24));
			BadgesDataService::awardBadgeIfApplicable($website, $db, $userId, 'membership_since_x_days', $numberOfRegisteredDays);}
		$_SESSION['badgechecked'] = 1;}
	$pageId = $website->getRequestParameter(PARAM_PAGE);
	$pageId = PageIdRouter::getTargetPageId($website, $i18n, $pageId);
	$website->setPageId($pageId);
	$validationMessages = null;
	$actionId = $website->getRequestParameter(PARAM_ACTION);
	if ($actionId !== NULL) {
		try{$targetId = ActionHandler::handleAction($website, $db, $i18n, $actionId);
			if ($targetId != null) $pageId = $targetId;}
		catch (ValidationException $ve) {
			$validationMessages = $ve->getMessages();
			$website->addFrontMessage(new FrontMessage(MESSAGE_TYPE_ERROR, $i18n->getMessage('validation_error_box_title'), $i18n->getMessage('validation_error_box_message')));}
		catch (Exception $e) { $website->addFrontMessage(new FrontMessage(MESSAGE_TYPE_ERROR, $i18n->getMessage('errorpage_title'), $e->getMessage()));}}
	$website->setPageId($pageId);
	$navItems = NavigationBuilder::getNavigationItems($website, $i18n, $page, $pageId);
	$parameters['navItems'] = $navItems;
	$parameters['breadcrumbItems'] = BreadcrumbBuilder::getBreadcrumbItems($website, $i18n, $page, $pageId);
	header('Content-type: text/html; charset=utf-8');
	$viewHandler = new ViewHandler($website, $db, $i18n, $page, $block, $validationMessages);
	try{print_r($viewHandler->handlePage($pageId, $parameters));}
	catch (AccessDeniedException $e) {
		if ($website->getUser()->getRole() == ROLE_GUEST) {
			$website->addFrontMessage(new FrontMessage(MESSAGE_TYPE_ERROR, $e->getMessage(), ''));
			print_r($viewHandler->handlePage('login', $parameters));}
		else renderErrorPage($website, $i18n, $viewHandler, $e->getMessage(), $parameters);}
	catch (Exception $e) {renderErrorPage($website, $i18n, $viewHandler, $e->getMessage(), $parameters);}}
function renderErrorPage($website, $i18n, $viewHandler, $message, $parameters) {
	$parameters['title'] = $message;
	$parameters['message'] = '';
	print_r($website->getTemplateEngine($i18n, $viewHandler)->loadTemplate('error')->render($parameters));}
