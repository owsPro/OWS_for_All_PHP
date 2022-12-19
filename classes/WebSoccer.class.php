<?php
/*This file is part of "OWS for All PHP" (Rolf Joseph)
  https://github.com/owsPro/OWS_for_All_PHP/
  A spinn-off for PHP Versions 5.4 to 8.2 from:
  OpenWebSoccer-Sim(Ingo Hofmann), https://github.com/ihofmann/open-websoccer.

  "OWS for All PHP" is is distributed in WITHOUT ANY WARRANTY;
  without even the implied warranty of MERCHANTABILITY
  or FITNESS FOR A PARTICULAR PURPOSE.

  See GNU Lesser General Public License Version 3 http://www.gnu.org/licenses/

 - Features like the OpenWebSoccer from Ingo Hofmann
 - Automatic Twig template switcher that sets the correct Twig version depending on the PHP version used.
 - All files from the classes folder in one file
 - All comments removed from the original
 - Notes where changes were made: //- or //+
 - Source code adapted for more clarity.
 - Already much less source code.
 - Easier bug search and adaptations for future PHP versions.
 - Notes on deprecated parts of the code for "owsPro"
 - Some additional programme parts from "owsPro" are marked with //+ owsPro
 - Prepared for module assignment of classes by means of //+ Module: ...
 - The installation is from "owsPro", so that there is a high level of compatibility.
 - Additional configuration and settings, e.g. through add-ons and basic configuration and settings, which are supplemented or overwritten by '/cache/wsconfigadmin.inc.php'.
 - Terms and conditions translated into many languages.

 This version can still be used with the database prefix, which is removed in "owsPro"!
 There will be no updates ( only language translations are updated ),
 but possible bug fixes for this version!
=====================================================================================*/
define('ALLOWED_EXTENSIONS','jpg,jpeg,gif,png');
define('ALLOWED_PROFPIC_EXTENSIONS','jpg,jpeg,png');
define('BASE_FOLDER', __DIR__);
define('CACHE_FOLDER',$_SERVER['DOCUMENT_ROOT'].'/cache/templates');
define('CLUBPICTURE_UPLOAD_DIR',$_SERVER['DOCUMENT_ROOT'].'/uploads/club');
define('COOKIE_PREFIX','owsPro');
define('DEFAULT_PAGE_ID','home');
define('DEFAULT_PLAYER_AGE',20);
define('DEFAULT_YOUTH_OFFENSIVE',60);
define('DOUBLE_SUBMIT_CHECK_SECONDS',3);
define('DOUBLE_SUBMIT_CHECK_SESSIONKEY_ACTIONID','laction_id');
define('DOUBLE_SUBMIT_CHECK_SESSIONKEY_TIME','laction_time');
define('DUMMY_TEAM_ID',-1);
define('ENTERUSERNAME_PAGE_ID','enter-username');
define('ENVIRONMENT_GLOBAL_NAME','env');
define('FILE_CITYNAMES',$_SERVER['DOCUMENT_ROOT'].'/admin/config/names/%s/cities.txt');
define('FILE_FIRSTNAMES',$_SERVER['DOCUMENT_ROOT'].'/admin/config/names/%s/firstnames.txt');
define('FILE_LASTNAMES',$_SERVER['DOCUMENT_ROOT'].'/admin/config/names/%s/lastnames.txt');
define('FILE_PREFIXNAMES',$_SERVER['DOCUMENT_ROOT'].'/admin/config/names/%s/clubprefix.txt');
define('FILE_SUFFIXNAMES',$_SERVER['DOCUMENT_ROOT'].'/admin/config/names/%s/clubsuffix.txt');
define('I18N_GLOBAL_NAME','i18n');
define('INACTIVITY_PER_DAY_LOGIN',0.45);
define('INACTIVITY_PER_DAY_TRANSFERS',0.1);
define('INACTIVITY_PER_DAY_TACTICS',0.2);
define('INACTIVITY_PER_CONTRACTEXTENSION',5);
define('LANG_SESSION_PARAM','lang');
define('LOGIN_PAGE_ID','login');
define('MARK_DOWNGRADE_BALLPASS_FAILURE',0.25);
define('MARK_DOWNGRADE_GOAL_GOALY',0.5);
define('MARK_DOWNGRADE_SHOOTFAILURE',0.5);
define('MARK_DOWNGRADE_TACKLE_LOOSER',0.5);
define('MARK_IMPROVE_BALLPASS_SUCCESS',0.1);
define('MARK_IMPROVE_GOAL_SCORER',1);
define('MARK_IMPROVE_GOAL_PASSPLAYER',0.75);
define('MARK_IMPROVE_SHOOTFAILURE_GOALY',0.5);
define('MARK_IMPROVE_TACKLE_WINNER',0.25);
define('MAX_STRENGTH', 100);
define('MESSAGE_TYPE_ERROR','error');
define('MESSAGE_TYPE_INFO','info');
define('MESSAGE_TYPE_SUCCESS','success');
define('MESSAGE_TYPE_WARNING','warning');
define('MIN_NUMBER_OF_PLAYERS',9);
define('MINIMUM_SATISFACTION_FOR_EXTENSION',30);
define('MSG_KEY_ERROR_PAGENOTFOUND', 'error_page_not_found');
define('NAMES_DIRECTORY',$_SERVER['DOCUMENT_ROOT'].'/admin/config/names');
define('NEWS_ENTRIES_PER_PAGE',5);
define('NEWS_TEASER_MAXLENGTH',256);
define('NOTIFICATION_TARGETPAGE','transferoffers');
define('NOTIFICATION_TYPE','transferoffer');
define('NUMBER_OF_PLAYERS',20);
define('NUMBER_OF_TOP_NEWS',5);
define('PAGE_NAV_LABEL_SUFFIX','_navlabel');
define('PARAM_ACTION', 'action');
define('PARAM_BLOCK', 'block');
define('PARAM_PAGE', 'page');
define('PARAM_PAGENUMBER', 'pageno');
define('PLAYER_POSITION_GOALY', 'Torwart');
define('PLAYER_POSITION_DEFENCE', 'Abwehr');
define('PLAYER_POSITION_MIDFIELD', 'Mittelfeld');
define('PLAYER_POSITION_STRIKER', 'Sturm');
define('POINTS_DRAW',1);
define('POINTS_LOSS',0);
define('POINTS_WIN',3);
define('REMEMBERME_COOKIE_LIFETIME_DAYS',30);
define('ROLE_GUEST','guest');
define('ROLE_USER','user');
define('SATISFACTION_DECREASE',10);
define('SATISFACTION_INCREASE',10);
define('SESSION_PARAM_USERID','frontuserid');
define('SKIN_GLOBAL_NAME','skin');
define('SLEEP_SECONDS_ON_FAILURE',5);
define('SUB_CONDITION_DEFICIT','Deficit');
define('SUB_CONDITION_LEADING','Leading');
define('SUB_CONDITION_TIE','Tie');
define('TEMPLATE_SUBDIR_DEFAULT','default');
define('USER_STATUS_ENABLED',1);
define('USER_STATUS_UNCONFIRMED',2);
define('VIEWHANDLER_GLOBAL_NAME','viewHandler');
define('YOUTH_MATCH_TYPE','Youth');
define('YOUTH_STRENGTH_FRESHNESS',100);
define('YOUTH_STRENGTH_SATISFACTION',100);
define('YOUTH_STRENGTH_STAMINA',100);
//+ owsPro - we don´t need the PHP extension mbstring with this alternative
if (!function_exists('mb_strlen')) {
    function mb_strlen($str = '') {
    	preg_match_all("/./u", $str, $char);
    	return sizeof($char[0]); }}
if (!function_exists('mb_substr')) {
	//- Deprecated: Optional parameter declared before required parameter is implicitly treated as a required parameter
	//- function mb_substr($str = "", $maxstr, $length) {
	function mb_substr($maxstr, $length, $str = "") {
		if (!is_numeric($maxstr)) $strsize = 0;
		if (!is_numeric($length) && $length != NULL) $length = 0;
		preg_match_all("/./u", $str, $char);
		if ($length == NULL) $length = sizeof($char[0]);
		else {
			if ($length < 0) $length = sizeof($char[0]) + $length;
			else $length = $maxstr + $length; }
		for ($i = $maxstr; $i < $length; ++$i) $result .= $char[0][$i];
		return $result; }}
//+ owsPro - Minimum requirements
if(version_compare('5.4.0',phpversion(),'>=')){
	try{throw new Exception();}
	catch(Exception $e){echo' Minimum requirements: PHP 5.4';}
	exit;}
//+ owsPro - Ensures that the cache folder is present.
if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/cache')){mkdir($_SERVER['DOCUMENT_ROOT'].'/cache',0777,true);}
// Interfaces - obsolete in owsPro
interface ISimulationStrategy {
	function __construct($websoccer);
	function kickoff(SimulationMatch $match);
	function nextAction(SimulationMatch $match);
	function passBall(SimulationMatch $match);
	function tackle(SimulationMatch $match);
	function shoot(SimulationMatch $match);
	function penaltyShooting(SimulationMatch $match); }
interface ISimulatorObserver {
	function onSubstitution(SimulationMatch $match, SimulationSubstitution $substitution);
	function onMatchCompleted(SimulationMatch $match);
	function onBeforeMatchStarts(SimulationMatch $match); }
interface IUserAuthentication {
	function __construct($website);
	function verifyAndUpdateCurrentUser(User $currentUser);
	function logoutUser(User $currentUser); }
interface ISimulationObserver {
	function onGoal(SimulationMatch $match, SimulationPlayer $scorer, SimulationPlayer $goaly);
	function onShootFailure(SimulationMatch $match, SimulationPlayer $scorer, SimulationPlayer $goaly);
	function onAfterTackle(SimulationMatch $match, SimulationPlayer $winner, SimulationPlayer $looser);
	function onBallPassSuccess(SimulationMatch $match, SimulationPlayer $player);
	function onBallPassFailure(SimulationMatch $match, SimulationPlayer $player);
	function onInjury(SimulationMatch $match, SimulationPlayer $player, $numberOfMatches);
	function onYellowCard(SimulationMatch $match, SimulationPlayer $player);
	function onRedCard(SimulationMatch $match, SimulationPlayer $player, $matchesBlocked);
	function onPenaltyShoot(SimulationMatch $match, SimulationPlayer $player, SimulationPlayer $goaly, $successful);
	function onCorner(SimulationMatch $match, SimulationPlayer $concededByPlayer, SimulationPlayer $targetPlayer);
	function onFreeKick(SimulationMatch $match, SimulationPlayer $player, SimulationPlayer $goaly, $successful); }
interface IActionController {
	function __construct($i18n, $websoccer, $db);
	function executeAction($parameters); }
interface IConverter {
	function __construct($i18n, $websoccer);
	function toHtml($value);
	function toText($value);
	function toDbValue($value); }
interface IUserLoginMethod {
	function __construct($websoccer, $db);
	function authenticateWithEmail($email, $password);
	function authenticateWithUsername($nick, $password); }
interface ISkin {
	function __construct($websoccer);
	function getTemplatesSubDirectory();
	function getCssSources();
	function getJavaScriptSources();
	function getTemplate($templateName);
	function getImage($fileName); }
interface IValidator {
	function __construct($i18n, $websoccer, $value);
	function isValid();
	function getMessage(); }
interface IModel {
	function __construct($db, $i18n, $websoccer);
	function renderView();
	function getTemplateParameters(); }
//+ other classes Mudules:
//+
//+ owsPro - Check on login
class LoginCheck{
	function __construct($portable_hashes){
		$db=DbConnection::getInstance();
		$this->_db=$db;
		$this->itoa64='./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		$this->portable_hashes=$portable_hashes;
		$this->random_state=microtime();
		if(function_exists('getmypid'))$this->random_state.=getmypid();}
	function encode64($input,$count){
		$output='';
		$i=0;
		do{$value=ord($input[$i++]);
			$output.=$this->itoa64[$value&0x3f];
			if($i<$count)$value|=ord($input[$i])<<8;
			$output.=$this->itoa64[($value>>6)&0x3f];
			if($i++>=$count)break;
			if($i<$count)$value|=ord($input[$i])<<16;
			$output.=$this->itoa64[($value>>12)&0x3f];
			if($i++>=$count)break;
			$output.=$this->itoa64[($value>>18)&0x3f];}
		while($i<$count);
		return $output;}
	function crypt_private($password,$setting){
		$output='*0';
		if(substr($setting,0,2)===$output)$output='*1';
		$id=substr($setting,0,3);
		if($id!=='$P$'&&$id!=='$H$')return$output;
		$count_log2=strpos($this->itoa64,$setting[3]);
		if($count_log2<7||$count_log2>30)return $output;
		$count=1<<$count_log2;
		$salt=substr($setting,4,8);
		if(strlen($salt)!==8)return$output;
		$hash=md5($salt.$password,TRUE);
		do{$hash=md5($hash.$password,TRUE);}
		while(--$count);
		$output=substr($setting,0,12);
		$output.=$this->encode64($hash,16);
		return$output;}
	function CheckPassword($password,$stored_hash){
		$hash=$this->crypt_private($password,$stored_hash);
		if($hash[0]==='*')$hash=crypt($password,$stored_hash);
		return$hash===$stored_hash;}}
//+ Mudule: actionlogs
//+ Mudule: alltimetable
//+ Mudule: cancellation
//+ Mudule: clubs
//+ Mudule: clubslogo
//+ Mudule: clubsrename
//+ Mudule: core
//+ Mudule: cups
//+ Mudule: facebook
//+ Mudule: firemanagers
//+ Mudule: fireplayer
//+ Mudule: formation
//+ Mudule: formauthentication
//+ Mudule: freeclubs
//+ Mudule: frontend
//+ Mudule: frontendads
//+ Mudule: generator
//+ Mudule: googleplus
//+ Mudule: gravatar
//+ Mudule: halloffame
//+ Mudule: help
//+ Mudule: joomlalogin
class JoomlaUserLoginMethod extends LoginCheck{
	function authenticateWithEmail($email, $password){ return $this->_authenticate('LOWER(email) = \'%s\'', strtolower($email), $password);}
	function authenticateWithUsername($nick, $password){ return$this->_authenticate('username=\'%s\'', $nick, $password);}
	function _authenticate( $queryWhereCondition, $loginStr, $password){
		$result = $this->_db->querySelect('username, email, password', getConfig('joomlalogin_tableprefix') . 'users','activation=0 AND ' . $queryWhereCondition,$loginStr);
		$wpUser = $result->fetch_array();
		if (!$wpUser) return FALSE;
		self::CheckPassword($password, $wpUser['password']);
		$userEmail = strtolower($wpUser['email']);
		$userId = UsersDataService::getUserIdByEmail($this->_websoccer, $this->_db,$userEmail);
		if($userId>0) return $userId;
		return UsersDataService::createLocalUser($this->_websoccer, $this->_db, $wpUser['users'], $userEmail);}}
//+ Mudule: languageswitcher
//+ Mudule: leagues
//+ Mudule: lending
//+ Mudule: matches
//+ Mudule: messages
//+ Mudule: moneytransactions
//+ Mudule: nationalteams
//+ Mudule: news
//+ Mudule: notifications
//+ Mudule: office
//+ Mudule: players
//+ Mudule: playerssearch
//+ Mudule: premium
//+ Mudule: premiummicropayment
//+ Mudule: premiumpaypal
//+ Mudule: premiumsofortcom
//+ Mudule: profile
//+ Mudule: randomevents
//+ Mudule: rss
//+ Mudule: season
//+ Mudule: shoutbox
//+ Mudule: simulation
//+ Mudule: socialrecommendations
//+ Mudule: sponsor
//+ Mudule: stadium
//+ Mudule: stadiumenvironment
//+ Mudule: statistics
//+ Mudule: tables
//+ Mudule: teamofday
//+ Mudule: termsandconditions
//+ Mudule: training
//+ Mudule: trainingcamp
//+ Mudule: transfermarket
//+ Mudule: transferoffers
//+ Mudule: transfers
//+ Mudule: userabsence
//+ Mudule: userauthentication
//+ Mudule: userbadges
//+ Mudule: userregistration
//+ Mudule: users
//+ Mudule: usersonline
//+ Mudule: webjobexecution
//+ Mudule: wordpresslogin
//+ owsPro - Adaptation to the new Wordpress login method as with Joomla
class WordpressUserLoginMethod extends LoginCheck{
	function authenticateWithEmail($email, $password){ return $this->_authenticate('LOWER(user_email) = \'%s\'', strtolower($email), $password);}
	function authenticateWithUsername($nick, $password){ return $this->_authenticate('user_login=\'%s\'', $nick, $password);}
	function _authenticate($queryWhereCondition, $loginStr, $password){
		$result = $this->_db->querySelect('user_login,user_email, user_pass', getConfig('wordpresslogin_tableprefix') . 'users','user_status=0 AND ' . $queryWhereCondition,$loginStr);
		$wpUser=$result->fetch_array();
		if (!$wpUser) return FALSE;
		self::CheckPassword($password,$wpUser['user_pass']);
		$userEmail=strtolower($wpUser['user_email']);
		$userId=UsersDataService::getUserIdByEmail($this->_websoccer, $this->_db, $userEmail);
		if ($userId > 0) return $userId;
		return UsersDataService::createLocalUser($this->_websoccer, $this->_db, $wpUser['user_login'], $userEmail);}}
//+ Mudule: youth
class WebSoccer {
	private static $_instance;
	private $_skin;
	private $_pageId;
	private $_templateEngine;
	private $_frontMessages;
	private $_isAjaxRequest;
	private $_user;
	private $_contextParameters;
	static function getInstance() {
        if(self::$_instance == NULL) self::$_instance = new WebSoccer();
        return self::$_instance; }
    function __construct() { $this->_isAjaxRequest = FALSE; }
    function getUser() {
    	if ($this->_user == null) $this->_user = new User();
    	return $this->_user; }
	function getConfig($name) {
		global $conf;
		if (!isset($conf[$name])) throw new Exception('Missing configuration: ' . $name);
		return $conf[$name]; }
	function getAction($id) {
		global $action;
		if (!isset($action[$id])) throw new Exception('Action not found: ' . $id);
		return $action[$id]; }
	function getSkin() {
		if ($this->_skin == NULL) {
			$skinName = $this->getConfig('skin');
			if (class_exists($skinName)) $this->_skin = new $skinName($this);
			else throw new Exception('Configured skin \''. $skinName . '\' does not exist. Check the system settings.'); }
		return $this->_skin; }
	function getPageId() { return $this->_pageId; }
	function setPageId($pageId) { $this->_pageId = $pageId; }
	function getTemplateEngine($i18n, ViewHandler $viewHandler = null) {
		if ($this->_templateEngine == NULL) $this->_templateEngine = new TemplateEngine($this, $i18n, $viewHandler);
		return $this->_templateEngine; }
	function getRequestParameter($name) {
		if (isset($_REQUEST[$name])) {
			$value = trim($_REQUEST[$name]);
			if (strlen($value)) return $value; }
		return NULL; }
	function getInternalUrl($pageId = null, $queryString = '', $fullUrl = FALSE) {
		if ($pageId == null) $pageId = $this->getPageId();
		//- owsPro - Fatal error: Uncaught TypeError: strlen() expects parameter 1 to be string, null given
		//- if (strlen($queryString)) {
		if (strlen((string)$queryString)) $queryString = '&' . $queryString;
		if ($fullUrl) {
			$url = $this->getConfig('homepage') . $this->getConfig('context_root');
			//- owsPro - Fatal error: Uncaught TypeError: strlen() expects parameter 1 to be string, null given
			//- if ($pageId != 'home' || strlen($queryString)) {
			if ($pageId != 'home' || strlen((string)$queryString)) $url .= '/?page=' . $pageId . $queryString; }
		else $url = $this->getConfig('context_root') . '/?page=' . $pageId . $queryString;
		return $url; }
	function getInternalActionUrl($actionId, $queryString = '', $pageId = null, $fullUrl = FALSE) {
		if ($pageId == null) $pageId = $this->getRequestParameter('page');
		if (strlen($queryString)) $queryString = '&' . $queryString;
		$url = $this->getConfig('context_root') . '/?page=' . $pageId . $queryString .'&action=' . $actionId;
		if ($fullUrl) $url = $this->getConfig('homepage') . $url;
		return $url; }
	function getFormattedDate($timestamp = null) {
		if ($timestamp == null) $timestamp = $this->getNowAsTimestamp();
		return date($this->getConfig('date_format'), $timestamp); }
	function getFormattedDatetime($timestamp, $i18n = null) {
		if ($timestamp == null) $timestamp = $this->getNowAsTimestamp();
		if ($i18n != null) {
			$dateWord = StringUtil::convertTimestampToWord($timestamp, $this->getNowAsTimestamp(), $i18n);
			if (strlen($dateWord)) return $dateWord . ', ' . date($this->getConfig('time_format'), $timestamp); }
		return date($this->getConfig('datetime_format'), $timestamp); }
	function getNowAsTimestamp() { return time() +  $this->getConfig('time_offset'); }
	function resetConfigCache() {
		$i18n = I18n::getInstance($this->getConfig('supported_languages'));
		$cacheBuilder = new ConfigCacheFileWriter($i18n->getSupportedLanguages());
		$cacheBuilder->buildConfigCache(); }
	function addFrontMessage(FrontMessage $message) { $this->_frontMessages[] = $message; }
	function getFrontMessages() {
		if ($this->_frontMessages == null) $this->_frontMessages = array();
		return $this->_frontMessages; }
	function setAjaxRequest($isAjaxRequest) { $this->_isAjaxRequest = $isAjaxRequest; }
	function isAjaxRequest() { return $this->_isAjaxRequest; }
	function getContextParameters() {
		if ($this->_contextParameters == null) $this->_contextParameters = array();
		return $this->_contextParameters; }
	function addContextParameter($name, $value) {
		if ($this->_contextParameters == null) $this->_contextParameters = array();
		$this->_contextParameters[$name] = $value; }}
class AccessDeniedException extends Exception {
	function __construct($message, $code = 0) { parent::__construct($message, $code); }}
class ActionHandler {
	static function handleAction($website, $db, $i18n, $actionId) {
		if ($actionId == NULL) return;
		if (isset($_SESSION[DOUBLE_SUBMIT_CHECK_SESSIONKEY_ACTIONID]) && $_SESSION[DOUBLE_SUBMIT_CHECK_SESSIONKEY_ACTIONID] == $actionId && isset($_SESSION[DOUBLE_SUBMIT_CHECK_SESSIONKEY_TIME])
			&& ($_SESSION[DOUBLE_SUBMIT_CHECK_SESSIONKEY_TIME] + DOUBLE_SUBMIT_CHECK_SECONDS) > $website->getNowAsTimestamp()) {
			throw new Exception($i18n->getMessage('error_double_submit')); }
		$actionConfig = json_decode($website->getAction($actionId), true);
		$actionXml = ModuleConfigHelper::findModuleConfigAsXmlObject($actionConfig['module']);
		$user = $website->getUser();
		if (strpos($actionConfig['role'], 'admin') !== false) {
			if (!$user->isAdmin()) throw new AccessDeniedException($i18n->getMessage('error_access_denied')); }
		else {
			$requiredRoles = explode(',', $actionConfig['role']);
			if (!in_array($user->getRole(), $requiredRoles)) throw new AccessDeniedException($i18n->getMessage('error_access_denied')); }
		$params = $actionXml->xpath('//action[@id = "'. $actionId . '"]/param');
		$validatedParams = array();
		if ($params) $validatedParams = self::_validateParameters($params, $website, $i18n);
		$controllerName = $actionConfig['controller'];
		if (isset($actionConfig['premiumBalanceMin']) && $actionConfig['premiumBalanceMin']) return self::_handlePremiumAction($website, $db, $i18n, $actionId, $actionConfig['premiumBalanceMin'], $validatedParams, $controllerName);
		$actionReturn = self::_executeAction($website, $db, $i18n, $actionId, $controllerName, $validatedParams);
		if (isset($actionConfig['log']) && $actionConfig['log'] && $website->getUser()->id) ActionLogDataService::createOrUpdateActionLog($website, $db, $website->getUser()->id, $actionId);
		return $actionReturn; }
	static function _validateParameters($params, $website, $i18n) {
		$errorMessages = array();
		$validatedParams = array();
		foreach ($params as $param) {
			$paramId = (string) $param->attributes()->id;
			$type = (string) $param->attributes()->type;
			$required = ($param->attributes()->required == 'true');
			$min = (int) $param->attributes()->min;
			$max = (int) $param->attributes()->max;
			$validatorName = (string) $param->attributes()->validator;
			$paramValue = $website->getRequestParameter($paramId);
			if ($type == 'boolean') $paramValue = ($paramValue) ? '1' : '0';
			if ($required && $paramValue == null) $errorMessages[$paramId] = $i18n->getMessage('validation_error_required');
			elseif ($paramValue != null) {
				if ($type == 'text' && $min > 0 && strlen($paramValue) < $min) $errorMessages[$paramId] = sprintf($i18n->getMessage('validation_error_min_length'), $min);
				elseif ($type == 'text' && $max > 0 && strlen($paramValue) > $max) $errorMessages[$paramId] = sprintf($i18n->getMessage('validation_error_max_length'), $max);
				elseif ($type == 'number' && !is_numeric($paramValue)) $errorMessages[$paramId] = $i18n->getMessage('validation_error_not_a_number');
				elseif ($type == 'number' && $paramValue < $min) $errorMessages[$paramId] = $i18n->getMessage('validation_error_min_number', $min);
				elseif ($type == 'number' && $max > 0 && $paramValue > $max) $errorMessages[$paramId] = $i18n->getMessage('validation_error_max_number', $max);
				elseif ($type == 'url' && !filter_var($paramValue, FILTER_VALIDATE_URL)) $errorMessages[$paramId] = $i18n->getMessage('validation_error_not_a_url');
				elseif ($type == 'date') {
					$format = $website->getConfig('date_format');
					if (!DateTime::createFromFormat($format, $paramValue)) $errorMessages[$paramId] = $i18n->getMessage('validation_error_invaliddate', $format); }
				if (strlen($validatorName)) {
					if (!class_exists($validatorName)) throw new Exception('Validator not found: ' . $validatorName);
					$validator = new $validatorName($i18n, $website, $paramValue);
					if (!$validator->isValid()) $errorMessages[$paramId] = $validator->getMessage(); }}
			if (!isset($errorMessages[$paramId])) $validatedParams[$paramId] = $paramValue; }
		if (count($errorMessages)) throw new ValidationException($errorMessages);
		return $validatedParams; }
	static function _executeAction($website, $db, $i18n, $actionId, $controllerName, $validatedParams) {
		if (!class_exists($controllerName)) throw new Exception('Controller not found: ' . $controllerName);
		$_SESSION[DOUBLE_SUBMIT_CHECK_SESSIONKEY_ACTIONID] = $actionId;
		$_SESSION[DOUBLE_SUBMIT_CHECK_SESSIONKEY_TIME] = $website->getNowAsTimestamp();
		$controller = new $controllerName($i18n, $website, $db);
		return $controller->executeAction($validatedParams); }
	static function _handlePremiumAction($website, $db, $i18n, $actionId, $creditsRequired, $validatedParams, $controllerName) {
		if ($creditsRequired > $website->getUser()->premiumBalance) {
			$targetPage = $website->getConfig('premium_infopage');
			if (filter_var($targetPage, FILTER_VALIDATE_URL)) {
				header('location: ' . $targetPage);
				exit; }
			else {
				$website->addContextParameter('premium_balance_required', $creditsRequired);
				return $targetPage; }}
		if ($website->getRequestParameter('premiumconfirmed')) {
			PremiumDataService::debitAmount($website, $db, $website->getUser()->id, $creditsRequired, $actionId);
			return self::_executeAction($website, $db, $i18n, $actionId, $controllerName, $validatedParams); }
		$website->addContextParameter('premium_balance_required', $creditsRequired);
		$website->addContextParameter('actionparameters', $validatedParams);
		$website->addContextParameter('actionid', $actionId);
		$website->addContextParameter('srcpage', $website->getPageId());
		return 'premium-confirm-action'; }}
class BreadcrumbBuilder {
	static function getBreadcrumbItems($website, $i18n, $pages, $currentPageId) {
		if (!isset($pages[$currentPageId])) return;
		$items = array();
		$nextPageId = $currentPageId;
		while($nextPageId) {
			$pageConfig = json_decode($pages[$nextPageId], TRUE);
			$items[$nextPageId] = $i18n->getNavigationLabel($nextPageId);
			if (isset($pageConfig['parentItem']) && strlen($pageConfig['parentItem'])) $nextPageId = $pageConfig['parentItem'];
			else $nextPageId = FALSE; }
		return array_reverse($items); }}
class ConfigCacheFileWriter{
	private $_frontCacheFileWriter;
	private $_adminCacheFileWriter;
	private $_supportedLanguages;
	private $_messagesFileWriters;
	private $_adminMessagesFileWriters;
	private $_entityMessagesFileWriters;
	private $_settingsCacheFileWriter;
	private $_eventsCacheFileWriter;
	private $_newSettings;
	function __construct($supportedLanguages){
		$this->_frontCacheFileWriter=new FileWriter($_SERVER['DOCUMENT_ROOT'].'/cache/wsconfigfront.inc.php');
		$this->_adminCacheFileWriter=new FileWriter($_SERVER['DOCUMENT_ROOT'].'/cache/wsconfigadmin.inc.php');
		$this->_settingsCacheFileWriter=new FileWriter($_SERVER['DOCUMENT_ROOT'].'/cache/settingsconfig.inc.php');
		$this->_eventsCacheFileWriter=new FileWriter($_SERVER['DOCUMENT_ROOT'].'/cache/eventsconfig.inc.php');
		$this->_supportedLanguages=$supportedLanguages;
		$this->_messagesFileWriters=[];
		$this->_adminMessagesFileWriters=[];
		$this->_entityMessagesFileWriters=[];
		foreach($supportedLanguages as $language){
			$this->_messagesFileWriters[$language]=new FileWriter(sprintf($_SERVER['DOCUMENT_ROOT'].'/cache/messages_%s.inc.php',$language));
			$this->_adminMessagesFileWriters[$language]=new FileWriter(sprintf($_SERVER['DOCUMENT_ROOT'].'/cache/adminmessages_%s.inc.php',$language));
			$this->_entityMessagesFileWriters[$language]=new FileWriter(sprintf($_SERVER['DOCUMENT_ROOT'].'/cache/entitymessages_%s.inc.php',$language));}}
	function buildConfigCache(){
		$this->_writeFileStart($this->_frontCacheFileWriter);
		$this->_writeFileStart($this->_adminCacheFileWriter);
		$this->_writeFileStart($this->_settingsCacheFileWriter);
		$this->_writeFileStart($this->_eventsCacheFileWriter);
		foreach($this->_supportedLanguages as $language){
			$this->_writeMsgFileStart($this->_messagesFileWriters[$language]);
			$this->_writeMsgFileStart($this->_adminMessagesFileWriters[$language]);
			$this->_writeMsgFileStart($this->_entityMessagesFileWriters[$language]);}
		$this->_buildModulesConfig();
		$this->_writeFileEnd($this->_frontCacheFileWriter);
		$this->_writeFileEnd($this->_adminCacheFileWriter);
		$this->_writeFileEnd($this->_settingsCacheFileWriter);
		$this->_writeFileEnd($this->_eventsCacheFileWriter);
		foreach($this->_supportedLanguages as $language){
			$this->_writeMsgFileEnd($this->_messagesFileWriters[$language]);
			$this->_writeMsgFileEnd($this->_adminMessagesFileWriters[$language]);
			$this->_writeMsgFileEnd($this->_entityMessagesFileWriters[$language]);}
		if(is_array($this->_newSettings)&&count($this->_newSettings)){
			global $conf;
			$cf=ConfigFileWriter::getInstance($conf);
			$cf->saveSettings($this->_newSettings);}}
	function _writeFileStart($fileWriter){
		$fileWriter->writeLine('<?php');
		}
	function _writeMsgFileStart($fileWriter){
		$this->_writeFileStart($fileWriter);
		$fileWriter->writeLine('if(!isset($msg))$msg=[];');
		$fileWriter->writeLine('$msg=$msg+array(');}
	function _writeFileEnd($fileWriter){$fileWriter->writeLine('?>');}
	function _writeMsgFileEnd($fileWriter){
		$fileWriter->writeLine(');');
		$this->_writeFileEnd($fileWriter);}
	function _buildModulesConfig(){
		$modules=scandir($_SERVER['DOCUMENT_ROOT'].'/modules');
		foreach($modules as $module){
			if(is_dir($_SERVER['DOCUMENT_ROOT'].'/modules'.'/'.$module)){
				$files=scandir($_SERVER['DOCUMENT_ROOT'].'/modules'.'/'.$module);
				foreach($files as $file){
					$pathToFile=$_SERVER['DOCUMENT_ROOT'].'/modules'.'/'.$module.'/'.$file;
					if($file=='module.xml')$this->_processModule($pathToFile,$module);
					elseif(StringUtil::startsWith($file,'messages_'))$this->_processMessages($pathToFile,$this->_messagesFileWriters);
					elseif(StringUtil::startsWith($file,'adminmessages_'))$this->_processMessages($pathToFile,$this->_adminMessagesFileWriters);
					elseif(StringUtil::startsWith($file,'entitymessages_'))$this->_processMessages($pathToFile,$this->_entityMessagesFileWriters);}}}}
	function _processModule($file,$module){
		$doc=new DOMDocument();
		$loaded=@$doc->load($file,LIBXML_DTDLOAD|LIBXML_DTDVALID);
		//- owsPro - Fatal error: Uncaught TypeError: Unsupported operand types: string + string
		//- if(!$loaded)throw new Exception('Could not load XML config file: ' + $file);
		if(!$loaded)throw new Exception('Could not load XML config file: ' . $file);
		$isValid=$doc->validate();
		$this->_processItem($doc,'page',$this->_frontCacheFileWriter,$module);
		$this->_processItem($doc,'block',$this->_frontCacheFileWriter,$module);
		$this->_processItem($doc,'action',$this->_frontCacheFileWriter,$module);
		$this->_processItem($doc,'adminpage',$this->_adminCacheFileWriter,$module);
		$this->_processItem($doc,'setting',$this->_settingsCacheFileWriter,$module);
		$this->_processItem($doc,'eventlistener',$this->_eventsCacheFileWriter,$module);}
	function _processItem($doc,$itemName,$fileWriter,$module,$keyAttribute='id'){
		$items=$doc->getElementsByTagName($itemName);
		foreach($items as $item){
			$line=$this->_buildConfigLine($itemName,$keyAttribute,$item,$module);
			$fileWriter->writeLine($line);}}
	function _buildConfigLine($itemname,$keyAttribute,$xml,$module){
		if($itemname=='eventlistener')$line='$'.$itemname .'[\''.$xml->getAttribute('event').'\'][]';
		else{
			$id=$xml->getAttribute($keyAttribute);
			$line='$'.$itemname .'[\''.$xml->getAttribute($keyAttribute).'\']';}
		$itemAttrs=[];
		if($xml->hasAttributes()){
			$attrs=$xml->attributes;
			foreach($attrs as $attr)$itemAttrs[$attr->name]=$attr->value;}
		$parent=$xml->parentNode;
		if($parent->nodeName==$itemname)$itemAttrs['parentItem']=$parent->getAttribute($keyAttribute);
		if($xml->hasChildNodes()){
			$children=$xml->childNodes;
			$childrenIds='';
			$first=TRUE;
			foreach($children as $child){
				if($child->nodeName==$itemname){
					if(!$first)$childrenIds.=',';
					$childrenIds .= $child->getAttribute($keyAttribute);
					$first=FALSE;}
				elseif($child->nodeName=='script'||$child->nodeName=='css'){
					$childattrs=$child->attributes;
					$resourceRef=[];
					foreach($childattrs as $attr)$resourceRef[$attr->name]=$attr->value;
					$itemAttrs[$child->nodeName.'s'][]=$resourceRef;}}
			if(!$first)$itemAttrs['childrenIds']=$childrenIds;}
		$itemAttrs['module']=$module;
		$line.='=\''.json_encode($itemAttrs,JSON_HEX_QUOT).'\';';
		if($itemname=='setting'){
			global $conf;
			if(!isset($conf[$id])){
				$defaultValue='';
				if($xml->hasAttribute('default'))$defaultValue=$xml->getAttribute('default');
				$this->_newSettings[$id]=$defaultValue;}}
		return $line;}
	function _processMessages($file,$fileWriters){
		$doc=new DOMDocument();
		$loaded=@$doc->load($file);
		if(!$loaded)throw new Exception('Could not load XML messages file: '.$file);
		$lang=substr($file,strrpos($file,'_')+1,2);
		if(isset($fileWriters[$lang])){
			$messages=$doc->getElementsByTagName('message');
			$fileWriter=$fileWriters[$lang];
			foreach($messages as $message){
				$line='\''.$message->getAttribute('id').'\'=>\''. addslashes($this->_getInnerHtml($message)).'\',';
				$fileWriter->writeLine($line);}}}
	function _getInnerHtml($node){
		$innerHTML= '';
		$children=$node->childNodes;
		foreach($children as $child)$innerHTML .= $child->ownerDocument->saveXML($child);
		return $innerHTML;}
	function __destruct(){
		if($this->_frontCacheFileWriter){}
		if($this->_adminCacheFileWriter){}
		if($this->_settingsCacheFileWriter){}
		foreach($this->_supportedLanguages as $language){
			if($this->_messagesFileWriters[$language]){}
			if($this->_adminMessagesFileWriters[$language]){}
			if($this->_entityMessagesFileWriters[$language]){}}}}
class ConfigFileWriter {
	private static $_instance;
	private $_settings;
	static function getInstance($settings) {
        if(self::$_instance == NULL) self::$_instance = new ConfigFileWriter($settings);
        return self::$_instance; }
    function saveSettings($newSettings) {
    	foreach($newSettings as $settingId => $settingValue) $this->_settings[$settingId] = $settingValue;
    	$this->_writeToFile(); }
    function _writeToFile() {
    	$content = "<?php" . PHP_EOL;
    	foreach ($this->_settings as $id => $value) $content .= "\$conf[\"". $id . "\"] = \"". addslashes($value) ."\";". PHP_EOL;
    	$content .= "?>";
    	$fw = new FileWriter(GLOBAL_CONFIG_FILE);
    	$fw->writeLine($content);
    	$fw->close(); }
    function __construct($settings) { $this->_settings = $settings; }}
class ConverterFactory {
	private static $_createdConverters;
	static function getConverter($website, $i18n, $converter) {
		if (isset(self::$_createdConverters[$converter])) return self::$_createdConverters[$converter];
		if (class_exists($converter)) {
			$converterInstance = new $converter($i18n, $website);
			self::$_createdConverters[$converter] = $converterInstance;
			return $converterInstance; }
		throw new Exception("Converter not found: " . $converter); }}
class CookieHelper {
	static function createCookie($name, $value, $lifetimeInDays = null) {
		$expiry = ($lifetimeInDays != null) ? time() + 86400 * $lifetimeInDays : 0;
		setcookie(COOKIE_PREFIX . $name, $value, $expiry); }
	static function getCookieValue($name) {
		if (!isset($_COOKIE[COOKIE_PREFIX . $name])) return null;
		return $_COOKIE[COOKIE_PREFIX . $name]; }
	static function destroyCookie($name) {
		if (!isset($_COOKIE[COOKIE_PREFIX . $name])) return;
		setcookie(COOKIE_PREFIX . $name, '', time()-86400); }}
class DataUpdateSimulatorObserver {
	private $_teamsWithSoonEndingContracts;
	function __construct($websoccer, $db) {
		$this->_websoccer = $websoccer;
		$this->_db = $db;
		$this->_teamsWithSoonEndingContracts = array(); }
	function onBeforeMatchStarts(SimulationMatch $match) {
		if (($this->_websoccer->getConfig('sim_income_trough_friendly') || $match->type !== 'Freundschaft') && !$match->isAtForeignStadium) SimulationAudienceCalculator::computeAndSaveAudience($this->_websoccer, $this->_db, $match);
		$clubTable = $this->_websoccer->getConfig('db_prefix') . '_verein';
		$updateColumns = array();
		$result = $this->_db->querySelect('user_id', $clubTable, 'id = %d AND user_id > 0', $match->homeTeam->id);
		$homeUser = $result->fetch_array();
		if ($homeUser) $updateColumns['home_user_id'] = $homeUser['user_id'];
		$result = $this->_db->querySelect('user_id', $clubTable, 'id = %d AND user_id > 0', $match->guestTeam->id);
		$guestUser = $result->fetch_array();
		if ($guestUser) $updateColumns['gast_user_id'] = $guestUser['user_id'];
		if (count($updateColumns)) $this->_db->queryUpdate($updateColumns, $this->_websoccer->getConfig('db_prefix') . '_spiel', 'id = %d', $match->id); }
	function onMatchCompleted(SimulationMatch $match) {
		$isFriendlyMatch = ($match->type == 'Freundschaft');
		if ($isFriendlyMatch) {
			$this->updatePlayersOfFriendlymatch($match->homeTeam);
			$this->updatePlayersOfFriendlymatch($match->guestTeam); }
		else {
			$isTie = $match->homeTeam->getGoals() == $match->guestTeam->getGoals();
			$this->updatePlayers($match, $match->homeTeam, $match->homeTeam->getGoals() > $match->guestTeam->getGoals(), $isTie);
			$this->updatePlayers($match, $match->guestTeam, $match->homeTeam->getGoals() < $match->guestTeam->getGoals(), $isTie);
			if (!$match->homeTeam->isNationalTeam) $this->creditSponsorPayments($match->homeTeam, TRUE, $match->homeTeam->getGoals() > $match->guestTeam->getGoals());
			if (!$match->guestTeam->isNationalTeam) $this->creditSponsorPayments($match->guestTeam, FALSE, $match->homeTeam->getGoals() < $match->guestTeam->getGoals());
			if ($match->type == 'Ligaspiel') $this->updateTeams($match);
			elseif (strlen($match->cupRoundGroup)) {
				$this->updateTeamsOfCupGroupMatch($match);
				SimulationCupMatchHelper::checkIfMatchIsLastMatchOfGroupRoundAndCreateFollowingMatches($this->_websoccer, $this->_db, $match); }
			$this->updateUsers($match); }
		$this->_db->queryDelete($this->_websoccer->getConfig('db_prefix') . '_aufstellung', 'match_id = %d', $match->id); }
	function updatePlayersOfFriendlymatch(SimulationTeam $team) {
		if (!count($team->positionsAndPlayers)) return;
		foreach ($team->positionsAndPlayers as $position => $players) {
			foreach ($players as $player) $this->updatePlayerOfFriendlyMatch($player); }
		if (is_array($team->removedPlayers) && count($team->removedPlayers)) {
			foreach ($team->removedPlayers as $player) $this->updatePlayerOfFriendlyMatch($player); }}
	function updatePlayerOfFriendlyMatch(SimulationPlayer $player) {
		$columns = array();
		if ($this->_websoccer->getConfig('sim_tiredness_through_friendly')) {
			$columns['w_frische'] = $player->strengthFreshness;
			$minMinutes = (int) $this->_websoccer->getConfig('sim_played_min_minutes');
			$staminaChange = (int) $this->_websoccer->getConfig('sim_strengthchange_stamina');
			if ($player->getMinutesPlayed() >= $minMinutes) $columns['w_kondition'] = min(100, $player->strengthStamina + $staminaChange); }
		if ($player->injured > 0 && $this->_websoccer->getConfig('sim_injured_after_friendly')) $columns['verletzt'] = $player->injured;
		if (count($columns)) {
			$fromTable = $this->_websoccer->getConfig('db_prefix') . '_spieler';
			$this->_db->queryUpdate($columns, $fromTable, 'id = %d', $player->id); }}
	function updatePlayers(SimulationMatch $match, SimulationTeam $team, $isTeamWinner, $isTie) {
		$playersOnPitch = array();
		foreach ($team->positionsAndPlayers as $position => $players) {
			foreach ($players as $player) $playersOnPitch[$player->id] = $player; }
		if (is_array($team->removedPlayers) && count($team->removedPlayers)) {
			foreach ($team->removedPlayers as $player) $playersOnPitch[$player->id] = $player; }
		$totalSalary = 0;
		$pcolumns = 'id,vorname,nachname,kunstname,verein_id,vertrag_spiele,vertrag_gehalt,vertrag_torpraemie,w_zufriedenheit,w_frische,verletzt,gesperrt,gesperrt_cups,gesperrt_nationalteam,lending_fee,lending_matches,lending_owner_id';
		$fromTable = $this->_websoccer->getConfig('db_prefix') . '_spieler';
		if ($team->isNationalTeam) {
			$fromTable .= ' INNER JOIN ' . $this->_websoccer->getConfig('db_prefix') . '_nationalplayer AS NP ON NP.player_id = id';
			$whereCondition = 'NP.team_id = %d AND status = 1'; }
		else $whereCondition = 'verein_id = %d AND status = 1';
		$parameters = $team->id;
		$result = $this->_db->querySelect($pcolumns, $fromTable, $whereCondition, $parameters);
		while ($playerinfo = $result->fetch_array()) {
			$totalSalary += $playerinfo['vertrag_gehalt'];
			if (isset($playersOnPitch[$playerinfo['id']])) {
				$player = $playersOnPitch[$playerinfo['id']];
				if ($player->getGoals()) $totalSalary += $player->getGoals() * $playerinfo['vertrag_torpraemie']; }
			else $this->updatePlayerWhoDidNotPlay($match, $team->isNationalTeam, $playerinfo); }
		if (!$team->isNationalTeam) $this->deductSalary($team, $totalSalary);
		foreach ($playersOnPitch as $player) $this->updatePlayer($match, $player, $isTeamWinner, $isTie); }
	function updatePlayer(SimulationMatch $match, SimulationPlayer $player, $isTeamWinner, $isTie) {
		$fromTable = $this->_websoccer->getConfig('db_prefix') . '_spieler';
		$whereCondition = 'id = %d';
		$parameters = $player->id;
		$minMinutes = (int) $this->_websoccer->getConfig('sim_played_min_minutes');
		$blockYellowCards = (int) $this->_websoccer->getConfig('sim_block_player_after_yellowcards');
		$staminaChange = (int) $this->_websoccer->getConfig('sim_strengthchange_stamina');
		$satisfactionChange = (int) $this->_websoccer->getConfig('sim_strengthchange_satisfaction');
		if ($player->team->isNationalTeam) $columns['gesperrt_nationalteam'] = $player->blocked;
		elseif ($match->type == 'Pokalspiel') $columns['gesperrt_cups'] = $player->blocked;
		else $columns['gesperrt'] = $player->blocked;
		$pcolumns = 'id,vorname,nachname,kunstname,verein_id,vertrag_spiele,st_tore,st_assists,st_spiele,st_karten_gelb,st_karten_gelb_rot,st_karten_rot,sa_tore,sa_assists,sa_spiele,sa_karten_gelb,sa_karten_gelb_rot,sa_karten_rot,lending_fee,lending_owner_id,
			lending_matches';
		$result = $this->_db->querySelect($pcolumns, $fromTable, $whereCondition, $parameters);
		$playerinfo = $result->fetch_array();
		$columns['st_tore'] = $playerinfo['st_tore'] + $player->getGoals();
		$columns['sa_tore'] = $playerinfo['sa_tore'] + $player->getGoals();
		$columns['st_assists'] = $playerinfo['st_assists'] + $player->getAssists();
		$columns['sa_assists'] = $playerinfo['sa_assists'] + $player->getAssists();
		$columns['st_spiele'] = $playerinfo['st_spiele'] + 1;
		$columns['sa_spiele'] = $playerinfo['sa_spiele'] + 1;
		if ($player->redCard) {
			$columns['st_karten_rot'] = $playerinfo['st_karten_rot'] + 1;
			$columns['sa_karten_rot'] = $playerinfo['sa_karten_rot'] + 1; }
		elseif ($player->yellowCards) {
			if ($player->yellowCards == 2) {
				$columns['st_karten_gelb_rot'] = $playerinfo['st_karten_gelb_rot'] + 1;
				$columns['sa_karten_gelb_rot'] = $playerinfo['sa_karten_gelb_rot'] + 1;
				if ($player->team->isNationalTeam) $columns['gesperrt_nationalteam'] = '1';
				elseif ($match->type == 'Pokalspiel') $columns['gesperrt_cups'] = '1';
				else $columns['gesperrt'] = '1'; }
			elseif (!$player->team->isNationalTeam) {
				$columns['st_karten_gelb'] = $playerinfo['st_karten_gelb'] + 1;
				$columns['sa_karten_gelb'] = $playerinfo['sa_karten_gelb'] + 1;
				if ($match->type == 'Ligaspiel' && $blockYellowCards > 0 && $columns['sa_karten_gelb'] % $blockYellowCards == 0) $columns['gesperrt'] = 1; }}
		if (!$player->team->isNationalTeam) {
			$columns['vertrag_spiele'] = max(0, $playerinfo['vertrag_spiele'] - 1);
			if ($columns['vertrag_spiele'] == 5) $this->_teamsWithSoonEndingContracts[$player->team->id] = TRUE;}
		if (!$player->team->isNationalTeam || $this->_websoccer->getConfig('sim_playerupdate_through_nationalteam')) {
			$columns['w_frische'] = $player->strengthFreshness;
			$columns['verletzt'] = $player->injured;
			if ($player->getMinutesPlayed() >= $minMinutes) {
				$columns['w_kondition'] = min(100, $player->strengthStamina + $staminaChange);
				$columns['w_zufriedenheit'] = min(100, $player->strengthSatisfaction + $satisfactionChange); }
			else {
				$columns['w_kondition'] = max(1, $player->strengthStamina - $staminaChange);
				$columns['w_zufriedenheit'] = max(1, $player->strengthSatisfaction - $satisfactionChange); }
			if (!$isTie) {
				if ($isTeamWinner) $columns['w_zufriedenheit'] = min(100, $columns['w_zufriedenheit'] + $satisfactionChange);
				else $columns['w_zufriedenheit'] = max(1, $columns['w_zufriedenheit'] - $satisfactionChange); }}
		if (!$player->team->isNationalTeam && $playerinfo['lending_matches'] > 0) $this->handleBorrowedPlayer($columns, $playerinfo);
		$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $parameters); }
	function updatePlayerWhoDidNotPlay(SimulationMatch $match, $isNationalTeam, $playerinfo) {
		$fromTable = $this->_websoccer->getConfig('db_prefix') . '_spieler';
		$whereCondition = 'id = %d';
		$parameters = $playerinfo['id'];
		$satisfactionChange = (int) $this->_websoccer->getConfig('sim_strengthchange_satisfaction');
		if ($isNationalTeam) $columns['gesperrt_nationalteam'] = max(0, $playerinfo['gesperrt_nationalteam'] - 1);
		elseif ($match->type == 'Pokalspiel')
			$columns['gesperrt_cups'] = max(0, $playerinfo['gesperrt_cups'] - 1);
		else $columns['gesperrt'] = max(0, $playerinfo['gesperrt'] - 1);
		$columns['verletzt'] = max(0, $playerinfo['verletzt'] - 1);
		if (!$isNationalTeam) {
			$columns['vertrag_spiele'] = max(0, $playerinfo['vertrag_spiele'] - 1);
			if ($columns['vertrag_spiele'] == 5) $this->_teamsWithSoonEndingContracts[$playerinfo['id']] = TRUE; }
		if (!$isNationalTeam || $this->_websoccer->getConfig('sim_playerupdate_through_nationalteam')) {
			$columns['w_zufriedenheit'] = max(1, $playerinfo['w_zufriedenheit'] - $satisfactionChange);
			$columns['w_frische'] = min(100, $playerinfo['w_frische'] + $this->_websoccer->getConfig('sim_strengthchange_freshness_notplayed')); }
		if (!$isNationalTeam && $playerinfo['lending_matches'] > 0) $this->handleBorrowedPlayer($columns, $playerinfo);
		$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $parameters); }
	function deductSalary(SimulationTeam $team, $salary) {
		BankAccountDataService::debitAmount($this->_websoccer, $this->_db, $team->id,
			$salary,
			'match_salarypayment_subject',
			'match_salarypayment_sender'); }
	function updateTeams(SimulationMatch $match) {
		$fromTable = $this->_websoccer->getConfig('db_prefix') . '_verein';
		$whereCondition = 'id = %d';
		$tcolumns = 'st_tore,st_gegentore,st_spiele,st_siege,st_niederlagen,st_unentschieden,st_punkte,sa_tore,sa_gegentore,sa_spiele,sa_siege,sa_niederlagen,sa_unentschieden,sa_punkte';
		$result = $this->_db->querySelect($tcolumns, $fromTable, $whereCondition, $match->homeTeam->id);
		$home = $result->fetch_array();
		$result = $this->_db->querySelect($tcolumns, $fromTable, $whereCondition, $match->guestTeam->id);
		$guest = $result->fetch_array();
		$homeColumns['sa_spiele'] = $home['sa_spiele'] + 1;
		$homeColumns['st_spiele'] = $home['st_spiele'] + 1;
		$homeColumns['sa_tore'] = $home['sa_tore'] + $match->homeTeam->getGoals();
		$homeColumns['st_tore'] = $home['st_tore'] + $match->homeTeam->getGoals();
		$homeColumns['sa_gegentore'] = $home['sa_gegentore'] + $match->guestTeam->getGoals();
		$homeColumns['st_gegentore'] = $home['st_gegentore'] + $match->guestTeam->getGoals();
		$guestColumns['sa_spiele'] = $guest['sa_spiele'] + 1;
		$guestColumns['st_spiele'] = $guest['st_spiele'] + 1;
		$guestColumns['sa_tore'] = $guest['sa_tore'] + $match->guestTeam->getGoals();
		$guestColumns['st_tore'] = $guest['st_tore'] + $match->guestTeam->getGoals();
		$guestColumns['sa_gegentore'] = $guest['sa_gegentore'] + $match->homeTeam->getGoals();
		$guestColumns['st_gegentore'] = $guest['st_gegentore'] + $match->homeTeam->getGoals();
		if ($match->homeTeam->getGoals() > $match->guestTeam->getGoals()) {
			$homeColumns['sa_siege'] = $home['sa_siege'] + 1;
			$homeColumns['st_siege'] = $home['st_siege'] + 1;
			$homeColumns['sa_punkte'] = $home['sa_punkte'] + POINTS_WIN;
			$homeColumns['st_punkte'] = $home['st_punkte'] + POINTS_WIN;
			$guestColumns['sa_niederlagen'] = $guest['sa_niederlagen'] + 1;
			$guestColumns['st_niederlagen'] = $guest['st_niederlagen'] + 1;
			$guestColumns['sa_punkte'] = $guest['sa_punkte'] + POINTS_LOSS;
			$guestColumns['st_punkte'] = $guest['st_punkte'] + POINTS_LOSS; }
		elseif ($match->homeTeam->getGoals() == $match->guestTeam->getGoals()) {
			$homeColumns['sa_unentschieden'] = $home['sa_unentschieden'] + 1;
			$homeColumns['st_unentschieden'] = $home['st_unentschieden'] + 1;
			$homeColumns['sa_punkte'] = $home['sa_punkte'] + POINTS_DRAW;
			$homeColumns['st_punkte'] = $home['st_punkte'] + POINTS_DRAW;
			$guestColumns['sa_unentschieden'] = $guest['sa_unentschieden'] + 1;
			$guestColumns['st_unentschieden'] = $guest['st_unentschieden'] + 1;
			$guestColumns['sa_punkte'] = $guest['sa_punkte'] + POINTS_DRAW;
			$guestColumns['st_punkte'] = $guest['st_punkte'] + POINTS_DRAW; }
		else {
			$homeColumns['sa_niederlagen'] = $home['sa_niederlagen'] + 1;
			$homeColumns['st_niederlagen'] = $home['st_niederlagen'] + 1;
			$homeColumns['sa_punkte'] = $home['sa_punkte'] + POINTS_LOSS;
			$homeColumns['st_punkte'] = $home['st_punkte'] + POINTS_LOSS;
			$guestColumns['sa_siege'] = $guest['sa_siege'] + 1;
			$guestColumns['st_siege'] = $guest['st_siege'] + 1;
			$guestColumns['sa_punkte'] = $guest['sa_punkte'] + POINTS_WIN;
			$guestColumns['st_punkte'] = $guest['st_punkte'] + POINTS_WIN; }
		$this->_db->queryUpdate($homeColumns, $fromTable, $whereCondition, $match->homeTeam->id);
		$this->_db->queryUpdate($guestColumns, $fromTable, $whereCondition, $match->guestTeam->id); }
	function updateTeamsOfCupGroupMatch(SimulationMatch $match) {
		$fromTable = $this->_websoccer->getConfig('db_prefix') . '_cup_round_group AS G';
		$fromTable .= ' INNER JOIN ' . $this->_websoccer->getConfig('db_prefix') . '_cup_round AS R ON R.id = G.cup_round_id';
		$fromTable .= ' INNER JOIN ' . $this->_websoccer->getConfig('db_prefix') . '_cup AS C ON C.id = R.cup_id';
		$whereCondition = 'C.name = \'%s\' AND R.name = \'%s\' AND G.name = \'%s\' AND G.team_id = %d';
		$tcolumns = array( 'G.tab_points' => 'tab_points', 'G.tab_goals' => 'tab_goals', 'G.tab_goalsreceived' => 'tab_goalsreceived', 'G.tab_wins' => 'tab_wins', 'G.tab_draws' => 'tab_draws', 'G.tab_losses' => 'tab_losses' );
		$homeParameters = array($match->cupName, $match->cupRoundName, $match->cupRoundGroup, $match->homeTeam->id);
		$result = $this->_db->querySelect($tcolumns, $fromTable, $whereCondition, $homeParameters, 1);
		$home = $result->fetch_array();
		$guestParameters = array($match->cupName, $match->cupRoundName, $match->cupRoundGroup, $match->guestTeam->id);
		$result = $this->_db->querySelect($tcolumns, $fromTable, $whereCondition, $guestParameters, 1);
		$guest = $result->fetch_array();
		$homeColumns['tab_goals'] = $home['tab_goals'] + $match->homeTeam->getGoals();
		$homeColumns['tab_goalsreceived'] = $home['tab_goalsreceived'] + $match->guestTeam->getGoals();
		$guestColumns['tab_goals'] = $guest['tab_goals'] + $match->guestTeam->getGoals();
		$guestColumns['tab_goalsreceived'] = $guest['tab_goalsreceived'] + $match->homeTeam->getGoals();
		if ($match->homeTeam->getGoals() > $match->guestTeam->getGoals()) {
			$homeColumns['tab_wins'] = $home['tab_wins'] + 1;
			$homeColumns['tab_points'] = $home['tab_points'] + POINTS_WIN;
			$guestColumns['tab_losses'] = $guest['tab_losses'] + 1;
			$guestColumns['tab_points'] = $guest['tab_points'] + POINTS_LOSS; }
		elseif ($match->homeTeam->getGoals() == $match->guestTeam->getGoals()) {
			$homeColumns['tab_draws'] = $home['tab_draws'] + 1;
			$homeColumns['tab_points'] = $home['tab_points'] + POINTS_DRAW;
			$guestColumns['tab_draws'] = $guest['tab_draws'] + 1;
			$guestColumns['tab_points'] = $guest['tab_points'] + POINTS_DRAW; }
		else {
			$homeColumns['tab_losses'] = $home['tab_losses'] + 1;
			$homeColumns['tab_points'] = $home['tab_points'] + POINTS_LOSS;
			$guestColumns['tab_wins'] = $guest['tab_wins'] + 1;
			$guestColumns['tab_points'] = $guest['tab_points'] + POINTS_WIN; }
		$this->_db->queryUpdate($homeColumns, $fromTable, $whereCondition, $homeParameters);
		$this->_db->queryUpdate($guestColumns, $fromTable, $whereCondition, $guestParameters); }
	function creditSponsorPayments(SimulationTeam $team, $isHomeTeam, $teamIsWinner) {
		$columns = 'S.name AS sponsor_name, b_spiel,b_heimzuschlag,b_sieg,T.sponsor_spiele AS sponsor_matches';
		$fromTable = $this->_websoccer->getConfig('db_prefix') . '_verein AS T';
		$fromTable .= ' INNER JOIN ' . $this->_websoccer->getConfig('db_prefix') . '_sponsor AS S ON S.id = T.sponsor_id';
		$whereCondition = 'T.id = %d AND T.sponsor_spiele > 0';
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $team->id);
		$sponsor = $result->fetch_array();
		if (isset($sponsor['sponsor_matches'])) {
			$amount = $sponsor['b_spiel'];
			if ($isHomeTeam) $amount += $sponsor['b_heimzuschlag'];
			if ($teamIsWinner) $amount += $sponsor['b_sieg'];
			BankAccountDataService::creditAmount($this->_websoccer, $this->_db, $team->id, $amount, 'match_sponsorpayment_subject', $sponsor['sponsor_name']);
			$updatecolums['sponsor_spiele'] = max(0, $sponsor['sponsor_matches'] - 1);
			if ($updatecolums['sponsor_spiele'] == 0) $updatecolums['sponsor_id'] = '';
			$whereCondition = 'id = %d';
			$fromTable = $this->_websoccer->getConfig('db_prefix') . '_verein';
			$this->_db->queryUpdate($updatecolums, $fromTable, $whereCondition, $team->id); }}
	function updateUsers(SimulationMatch $match) {
		$highscoreWin = $this->_websoccer->getConfig('highscore_win');
		$highscoreLoss = $this->_websoccer->getConfig('highscore_loss');
		$highscoreDraw = $this->_websoccer->getConfig('highscore_draw');
		$columns = 'U.id AS u_id, U.highscore AS highscore, U.fanbeliebtheit AS popularity';
		$fromTable = $this->_websoccer->getConfig('db_prefix') . '_verein AS T';
		$fromTable .= ' INNER JOIN ' . $this->_websoccer->getConfig('db_prefix') . '_user AS U ON U.id = T.user_id';
		$whereCondition = 'T.id = %d';
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $match->homeTeam->id);
		$homeUser = $result->fetch_array();
		$updateTable = $this->_websoccer->getConfig('db_prefix') . '_user';
		$updateCondition = 'id = %d';
		$homeStrength = $match->homeTeam->computeTotalStrength($this->_websoccer, $match);
		$guestStrength = $match->guestTeam->computeTotalStrength($this->_websoccer, $match);
		if ($homeStrength) $homeGuestStrengthDiff = round(($homeStrength - $guestStrength) / $homeStrength * 100);
		else $homeGuestStrengthDiff = 0;
		if (!empty($homeUser['u_id']) && !$match->homeTeam->noFormationSet) {
			if ($match->homeTeam->getGoals() > $match->guestTeam->getGoals()) {
				$homeColumns['highscore'] = max(0, $homeUser['highscore'] + $highscoreWin);
				$popFactor = 1.1;
				if ($homeGuestStrengthDiff >= 20) $popFactor = 1.05;
				$homeColumns['fanbeliebtheit'] = min(100, round($homeUser['popularity'] * $popFactor));
				$goalsDiff = $match->homeTeam->getGoals() - $match->guestTeam->getGoals();
				BadgesDataService::awardBadgeIfApplicable($this->_websoccer, $this->_db, $homeUser['u_id'], 'win_with_x_goals_difference', $goalsDiff); }
			elseif ($match->homeTeam->getGoals() == $match->guestTeam->getGoals()) {
				$homeColumns['highscore'] = max(0, $homeUser['highscore'] + $highscoreDraw);
				$popFactor = 1.0;
				if ($homeGuestStrengthDiff >= 20) $popFactor = 0.95;
				elseif ($homeGuestStrengthDiff <= -20) $popFactor = 1.05;
				$homeColumns['fanbeliebtheit'] = min(100, round($homeUser['popularity'] * $popFactor)); }
			else {
				$homeColumns['highscore'] = max(0, $homeUser['highscore'] + $highscoreLoss);
				$popFactor = 0.95;
				if ($homeGuestStrengthDiff >= 20) $popFactor = 0.90;
				elseif ($homeGuestStrengthDiff <= -20) $popFactor = 1.00;
				$homeColumns['fanbeliebtheit'] = max(1, round($homeUser['popularity'] * $popFactor)); }
			if (!$match->homeTeam->isManagedByInterimManager) $this->_db->queryUpdate($homeColumns, $updateTable, $updateCondition, $homeUser['u_id']);
			if (isset($this->_teamsWithSoonEndingContracts[$match->homeTeam->id])) $this->notifyAboutSoonEndingContracts($homeUser['u_id'], $match->homeTeam->id); }
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $match->guestTeam->id);
		$guestUser = $result->fetch_array();
		if (!empty($guestUser['u_id']) && !$match->guestTeam->noFormationSet) {
			if ($match->guestTeam->getGoals() > $match->homeTeam->getGoals()) {
				$popFactor = 1.1;
				if ($homeGuestStrengthDiff <= -20) $popFactor = 1.05;
				$guestColumns['highscore'] = max(0, $guestUser['highscore'] + $highscoreWin);
				$guestColumns['fanbeliebtheit'] = min(100, round($guestUser['popularity'] * $popFactor));
				$goalsDiff = $match->guestTeam->getGoals() - $match->homeTeam->getGoals();
				BadgesDataService::awardBadgeIfApplicable($this->_websoccer, $this->_db, $guestUser['u_id'], 'win_with_x_goals_difference', $goalsDiff); }
			elseif ($match->guestTeam->getGoals() == $match->homeTeam->getGoals()) {
				$popFactor = 1.0;
				if ($homeGuestStrengthDiff <= -20) $popFactor = 0.95;
				elseif ($homeGuestStrengthDiff >= 20) $popFactor = 1.05;
				$guestColumns['highscore'] = max(0, $guestUser['highscore'] + $highscoreDraw);
				$guestColumns['fanbeliebtheit'] = min(100, round($guestUser['popularity'] * $popFactor)); }
			else {
				$guestColumns['highscore'] = max(0, $guestUser['highscore'] + $highscoreLoss);
				$popFactor = 0.95;
				if ($homeGuestStrengthDiff <= -20) $popFactor = 0.90;
				elseif ($homeGuestStrengthDiff >= 20) $popFactor = 1.00;
				$guestColumns['fanbeliebtheit'] = max(1, round($guestUser['popularity'] * $popFactor)); }
			if (!$match->guestTeam->isManagedByInterimManager) $this->_db->queryUpdate($guestColumns, $updateTable, $updateCondition, $guestUser['u_id']);
			if (isset($this->_teamsWithSoonEndingContracts[$match->guestTeam->id])) $this->notifyAboutSoonEndingContracts($guestUser['u_id'], $match->guestTeam->id); }}
	function handleBorrowedPlayer(&$columns, $playerinfo) {
		$columns['lending_matches'] = max(0, $playerinfo['lending_matches'] - 1);
		if ($columns['lending_matches'] == 0) {
			$columns['lending_fee'] = 0;
			$columns['lending_owner_id'] = 0;
			$columns['verein_id'] = $playerinfo['lending_owner_id'];
			$borrower = TeamsDataService::getTeamSummaryById($this->_websoccer, $this->_db, $playerinfo['verein_id']);
			$lender = TeamsDataService::getTeamSummaryById($this->_websoccer, $this->_db, $playerinfo['lending_owner_id']);
			$messageKey = 'lending_notification_return';
			$messageType = 'lending_return';
			$playerName = ($playerinfo['kunstname']) ? $playerinfo['kunstname'] : $playerinfo['vorname'] . ' ' . $playerinfo['nachname'];
			$messageData = array('player' => $playerName, 'borrower' => $borrower['team_name'], 'lender' => $lender['team_name']);
			if ($borrower['user_id']) NotificationsDataService::createNotification($this->_websoccer, $this->_db, $borrower['user_id'], $messageKey, $messageData, $messageType, 'player', 'id=' . $playerinfo['id']);
			if ($lender['user_id']) NotificationsDataService::createNotification($this->_websoccer, $this->_db, $lender['user_id'], $messageKey, $messageData, $messageType, 'player', 'id=' . $playerinfo['id']); }}
	function notifyAboutSoonEndingContracts($userId, $teamId) {
		NotificationsDataService::createNotification($this->_websoccer, $this->_db, $userId, 'notification_soon_ending_playercontracts', '', 'soon_ending_playercontracts', 'myteam', null, $teamId);
		unset($this->_teamsWithSoonEndingContracts[$teamId]); }
	function onSubstitution(SimulationMatch $match, SimulationSubstitution $substitution) {} }
class DbConnection {
	public $connection;
	private static $_instance;
	private $_queryCache;
	static function getInstance() {
		if(self::$_instance == NULL) self::$_instance = new DbConnection();
		return self::$_instance;}
	function __construct() {}
	function connect($host, $user, $password, $dbname) {
		@$this->connection = new mysqli($host, $user, $password, $dbname);
		@$this->connection->set_charset('utf8');
		if (mysqli_connect_error()) throw new Exception('Database Connection Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error()); }
	function close() { $this->connection->close(); }
	function querySelect($columns, $fromTable, $whereCondition, $parameters = null, $limit = null) {
		$queryStr = $this->buildQueryString($columns, $fromTable, $whereCondition, $parameters, $limit);
		return $this->executeQuery($queryStr); }
	function queryCachedSelect($columns, $fromTable, $whereCondition, $parameters = null, $limit = null) {
		$queryStr = $this->buildQueryString($columns, $fromTable, $whereCondition, $parameters, $limit);
		if (isset($this->_queryCache[$queryStr])) return $this->_queryCache[$queryStr];
		$result = $this->executeQuery($queryStr);
		$rows = array();
		while ($row = $result->fetch_array()) $rows[] = $row;
		$this->_queryCache[$queryStr] = $rows;
		return $rows; }
	function queryUpdate($columns, $fromTable, $whereCondition, $parameters) {
		$queryStr = 'UPDATE ' . $fromTable . ' SET ';
		$queryStr = $queryStr . self::buildColumnsValueList($columns);
		$queryStr = $queryStr . ' WHERE ';
		$wherePart = self::buildWherePart($whereCondition, $parameters);
		$queryStr = $queryStr . $wherePart;
		$this->executeQuery($queryStr);
		$this->_queryCache = array(); }
	function queryDelete($fromTable, $whereCondition, $parameters) {
		$queryStr = 'DELETE FROM ' . $fromTable;
		$queryStr = $queryStr . ' WHERE ';
		$wherePart = self::buildWherePart($whereCondition, $parameters);
		$queryStr = $queryStr . $wherePart;
		$this->executeQuery($queryStr);
		$this->_queryCache = array(); }
	function queryInsert($columns, $fromTable) {
		$queryStr = 'INSERT ' . $fromTable . ' SET ';
		$queryStr = $queryStr . $this->buildColumnsValueList($columns);
		$this->executeQuery($queryStr); }
	function getLastInsertedId() { return $this->connection->insert_id; }
	function buildQueryString($columns, $fromTable, $whereCondition, $parameters = null, $limit = null) {
		$queryStr = 'SELECT ';
		if (is_array($columns)) {
			$firstColumn = TRUE;
			foreach($columns as $dbName => $aliasName) {
				if (!$firstColumn) $queryStr = $queryStr .', ';
				else $firstColumn = FALSE;
				if (is_numeric($dbName)) $dbName = $aliasName;
				$queryStr = $queryStr . $dbName. ' AS '. $aliasName; }}
		else $queryStr = $queryStr . $columns;
		$queryStr = $queryStr . ' FROM ' . $fromTable . ' WHERE ';
		$wherePart = self::buildWherePart($whereCondition, $parameters);
		if (!empty($limit)) $wherePart = $wherePart . ' LIMIT ' . $limit;
		$queryStr = $queryStr . $wherePart;
		return $queryStr; }
	function buildColumnsValueList($columns) {
		$queryStr = '';
		$firstColumn = TRUE;
		foreach($columns as $dbName => $value) {
			if (!$firstColumn) $queryStr = $queryStr . ', ';
			else $firstColumn = FALSE;
			if (strlen($value)) $columnValue = '\'' . $this->connection->real_escape_string($value) .'\'';
			else $columnValue = 'DEFAULT';
			$queryStr = $queryStr . $dbName . '=' . $columnValue; }
		return $queryStr; }
	function buildWherePart($whereCondition, $parameters) {
		$maskedParameters = self::prepareParameters($parameters);
		return vsprintf($whereCondition, $maskedParameters); }
	function prepareParameters($parameters) {
		if(!is_array($parameters)) $parameters = array($parameters);
		$arrayLength = count($parameters);
		for($i = 0; $i < $arrayLength; $i++) $parameters[$i] = $this->connection->real_escape_string(trim($parameters[$i]));
		return $parameters; }
	function executeQuery($queryStr) {
		$queryResult = $this->connection->query($queryStr);
		if (!$queryResult) throw new Exception('Database Query Error: ' . $this->connection->error);
		return $queryResult; }}
class DbSessionManager{
	function __construct($db, $websoccer) {
		$this->_db = $db;
		$this->_websoccer = $websoccer;}
	//- owsPro -Deprecated: Return type of class function should either be compatibl
	#[ReturnTypeWillChange]
	function open($save_path, $session_name) { return TRUE; }
	//- owsPro - Deprecated: Return type of class function should either be compatibl
	#[ReturnTypeWillChange]
	function close() { return TRUE; }
	//- owsPro - Deprecated: Return type of class function should either be compatibl
	#[ReturnTypeWillChange]
	function destroy($sessionId) {
		$fromTable = $this->_websoccer->getConfig('db_prefix') . '_session';
		$whereCondition = 'session_id = \'%s\'';
		$this->_db->queryDelete($fromTable, $whereCondition, $sessionId);
		return TRUE;}
	//- owsPro - Deprecated: Return type of class function should either be compatibl
	#[ReturnTypeWillChange]
	function read($sessionId) {
		$columns = 'expires, session_data';
		$fromTable = $this->_websoccer->getConfig('db_prefix') . '_session';
		$whereCondition = 'session_id = \'%s\'';
		$data = '';
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $sessionId);
		if ($result->num_rows > 0) {
			$row = $result->fetch_array();
			if ($row['expires'] < $this->_websoccer->getNowAsTimestamp()) $this->destroy($sessionId);
			else {
				$data = $row['session_data'];
				if ($data == null) $data = '';}}
		return $data;}
	function validate_sid($key) {
		$columns = 'expires';
		$fromTable = $this->_websoccer->getConfig('db_prefix') . '_session';
		$whereCondition = 'session_id = \'%s\'';
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $key);
		if ($result->num_rows > 0) {
			$row = $result->fetch_array();
			if ($row['expires'] < $this->_websoccer->getNowAsTimestamp()) $this->destroy($key);
			else {
				return true;}}
		return FALSE;}
	//- owsPro - Deprecated: Return type of class function should either be compatibl
	#[ReturnTypeWillChange]
	function write($sessionId, $data) {
		$lifetime = (int) $this->_websoccer->getConfig('session_lifetime');
		$expiry = $this->_websoccer->getNowAsTimestamp() + $lifetime;
		$fromTable = $this->_websoccer->getConfig('db_prefix') . '_session';
		$columns['session_data'] = $data;
		$columns['expires'] = $expiry;
		if ($this->validate_sid($sessionId)) {
			$whereCondition = 'session_id = \'%s\'';
			$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $sessionId);}
		elseif(!empty($data)) {
			$columns['session_id'] = $sessionId;
			$this->_db->queryInsert($columns, $fromTable);}
		//+ owsPro - return with FALSE ist nessesary at PHP 8
		return FALSE;}
	//- owsPro - Deprecated: Return type of class function should either be compatibl
	#[ReturnTypeWillChange]
	function gc($maxlifetime) {
		$this->_deleteExpiredSessions();
		return true;}
	function _deleteExpiredSessions() {
		$fromTable = $this->_websoccer->getConfig('db_prefix') . '_session';
		$whereCondition = 'expires < %d';
		$this->_db->queryDelete($fromTable, $whereCondition, $this->_websoccer->getNowAsTimestamp());}}
class DefaultSimulationObserver {
	function onGoal(SimulationMatch $match, SimulationPlayer $scorer, SimulationPlayer $goaly) {
		$assistPlayer = ($match->getPreviousPlayerWithBall() !== NULL && $match->getPreviousPlayerWithBall()->team->id == $scorer->team->id) ? $match->getPreviousPlayerWithBall() : '';
		$scorer->improveMark(MARK_IMPROVE_GOAL_SCORER);
		$goaly->downgradeMark(MARK_DOWNGRADE_GOAL_GOALY);
		if (strlen($assistPlayer)) {
			$assistPlayer->improveMark(MARK_IMPROVE_GOAL_PASSPLAYER);
			$assistPlayer->setAssists($assistPlayer->getAssists() + 1); }
		$scorer->team->setGoals($scorer->team->getGoals() + 1);
		$scorer->setGoals($scorer->getGoals() + 1);
		$scorer->setShoots($scorer->getShoots() + 1); }
	function onShootFailure(SimulationMatch $match, SimulationPlayer $scorer, SimulationPlayer $goaly) {
		if ($scorer->getGoals() < 3) $scorer->downgradeMark(MARK_DOWNGRADE_SHOOTFAILURE);
		$goaly->improveMark(MARK_IMPROVE_SHOOTFAILURE_GOALY);
		if ($goaly->team->getGoals() > 3) $goaly->setMark(max(2.0, $goaly->getMark()));
		$scorer->setShoots($scorer->getShoots() + 1); }
	function onAfterTackle(SimulationMatch $match, SimulationPlayer $winner, SimulationPlayer $looser) {
		if ($looser->getGoals() > 0 && $looser->getGoals() < 3 && $looser->getAssists() > 0 && $looser->getAssists() < 3) $looser->downgradeMark(MARK_DOWNGRADE_TACKLE_LOOSER * 0.5);
		elseif ($looser->getGoals() < 3 && $looser->getAssists() < 3) $looser->downgradeMark(MARK_DOWNGRADE_TACKLE_LOOSER);
		$winner->improveMark(MARK_IMPROVE_TACKLE_WINNER);
		$winner->setWonTackles($winner->getWonTackles() + 1);
		$looser->setLostTackles($winner->getLostTackles() + 1); }
	function onBallPassSuccess(SimulationMatch $match, SimulationPlayer $player) {
		$player->improveMark(MARK_IMPROVE_BALLPASS_SUCCESS);
		$player->setPassesSuccessed($player->getPassesSuccessed() + 1); }
	function onBallPassFailure(SimulationMatch $match, SimulationPlayer $player) {
		if ($player->getGoals() < 2 && $player->getAssists() < 2 && ($player->getGoals() == 0 || $player->getAssists() == 0)) $player->downgradeMark(MARK_DOWNGRADE_BALLPASS_FAILURE);
		$player->setPassesFailed($player->getPassesFailed() + 1); }
	function onInjury(SimulationMatch $match, SimulationPlayer $player, $numberOfMatches) {
		$player->injured = $numberOfMatches;
		$substituted = SimulationHelper::createUnplannedSubstitutionForPlayer($match->minute + 1, $player);
		if (!$substituted) $player->team->removePlayer($player); }
	function onYellowCard(SimulationMatch $match, SimulationPlayer $player) {
		$player->yellowCards = $player->yellowCards + 1;
		if ($player->yellowCards == 2) {
			$player->downgradeMark(MARK_DOWNGRADE_TACKLE_LOOSER);
			$player->team->removePlayer($player); }}
	function onRedCard(SimulationMatch $match, SimulationPlayer $player, $matchesBlocked) {
		$player->redCard = 1;
		$player->blocked = $matchesBlocked;
		$player->team->removePlayer($player); }
	function onPenaltyShoot(SimulationMatch $match, SimulationPlayer $player, SimulationPlayer $goaly, $successful) {
		if ($successful) {
			$player->improveMark(MARK_IMPROVE_GOAL_SCORER);
			$player->team->setGoals($player->team->getGoals() + 1); }
		else {
			$player->downgradeMark(MARK_DOWNGRADE_SHOOTFAILURE);
			$goaly->improveMark(MARK_IMPROVE_SHOOTFAILURE_GOALY); }}
	function onCorner(SimulationMatch $match, SimulationPlayer $concededByPlayer, SimulationPlayer $targetPlayer) {
		$match->setPlayerWithBall($concededByPlayer);
		$concededByPlayer->improveMark(MARK_IMPROVE_BALLPASS_SUCCESS);
		$concededByPlayer->setPassesSuccessed($concededByPlayer->getPassesSuccessed() + 1); }
	function onFreeKick(SimulationMatch $match, SimulationPlayer $player, SimulationPlayer $goaly, $successful) {
		$player->setShoots($player->getShoots() + 1);
		if ($successful) {
			$player->improveMark(MARK_IMPROVE_GOAL_SCORER);
			$player->team->setGoals($player->team->getGoals() + 1);
			$player->setGoals($player->getGoals() + 1); }
		else {
			$player->downgradeMark(MARK_DOWNGRADE_SHOOTFAILURE);
			$goaly->improveMark(MARK_IMPROVE_SHOOTFAILURE_GOALY); }}}
class DefaultSimulationStrategy {
	private $_websoccer;
	private $_passTargetProbPerPosition;
	private $_opponentPositions;
	private $_shootStrengthPerPosition;
	private $_shootProbPerPosition;
	private $_observers;
	function __construct($websoccer) {
		$this->_websoccer = $websoccer;
		$this->_setPassTargetProbabilities();
		$this->_setOpponentPositions();
		$this->_setShootStrengthPerPosition();
		$this->_setShootProbPerPosition();
		$this->_observers = array(); }
	function attachObserver(ISimulationObserver $observer) { $this->_observers[] = $observer; }
	function kickoff(SimulationMatch $match) {
		$pHomeTeam[TRUE] = 50;
		$pHomeTeam[FALSE]  = 50;
		$team = SimulationHelper::selectItemFromProbabilities($pHomeTeam) ? $match->homeTeam : $match->guestTeam;
		$match->setPlayerWithBall(SimulationHelper::selectPlayer($team, PLAYER_POSITION_DEFENCE, null)); }
	function nextAction(SimulationMatch $match) {
		$player = $match->getPlayerWithBall();
		if ($player->position == PLAYER_POSITION_GOALY) return 'passBall';
		$opponentTeam = SimulationHelper::getOpponentTeam($player, $match);
		$opponentPosition = $this->_opponentPositions[$player->position];
		$noOfOwnPlayersInPosition = count($player->team->positionsAndPlayers[$player->position]);
		if (isset($opponentTeam->positionsAndPlayers[$opponentPosition])) $noOfOpponentPlayersInPosition = count($opponentTeam->positionsAndPlayers[$opponentPosition]);
		else $noOfOpponentPlayersInPosition = 0;
		$pTackle = 10;
		if ($noOfOpponentPlayersInPosition == $noOfOwnPlayersInPosition) $pTackle += 10;
		elseif ($noOfOpponentPlayersInPosition > $noOfOwnPlayersInPosition) $pTackle += 10 + 20 * ($noOfOpponentPlayersInPosition - $noOfOwnPlayersInPosition);
		$pAction['tackle'] = min($pTackle, 40);
		$pShoot = $this->_shootProbPerPosition[$player->position];
		$tacticInfluence = ($this->_getOffensiveStrength($player->team, $match) - $this->_getDefensiveStrength($opponentTeam, $match)) / 10;
		if ($player->team->counterattacks) $pShoot = round($pShoot * 0.5);
		$resultInfluence = ($player->team->getGoals() - $opponentTeam->getGoals()) * (0 - 5);
		if ($player->team->getGoals() < $opponentTeam->getGoals() && $player->team->morale) $resultInfluence += floor($player->team->morale / 100 * 5);
		if ($player->position == PLAYER_POSITION_STRIKER || $player->position == PLAYER_POSITION_MIDFIELD) $minShootProb = 5;
		else $minShootProb = 1;
		$pAction['shoot'] = round(max($minShootProb, min($pShoot + $tacticInfluence + $resultInfluence, 50)) * $this->_websoccer->getConfig('sim_shootprobability') / 100);
		$pAction['passBall'] = 100 - $pAction['tackle'] - $pAction['shoot'] ;
		return SimulationHelper::selectItemFromProbabilities($pAction); }
	function passBall(SimulationMatch $match) {
		$player = $match->getPlayerWithBall();
		$pFailed[FALSE] = round(($player->getTotalStrength($this->_websoccer, $match) + $player->strengthTech) / 2);
		if ($player->team->longPasses) $pFailed[FALSE] = round($pFailed[FALSE] * 0.7);
		$pFailed[TRUE] = 100 - $pFailed[FALSE];
		if (SimulationHelper::selectItemFromProbabilities($pFailed) == TRUE) {
			$opponentTeam = SimulationHelper::getOpponentTeam($player, $match);
			$targetPosition = $this->_opponentPositions[$player->position];
			$match->setPlayerWithBall(SimulationHelper::selectPlayer($opponentTeam, $targetPosition, null));
			foreach ($this->_observers as $observer) $observer->onBallPassFailure($match, $player);
			return FALSE; }
		$pTarget[PLAYER_POSITION_GOALY] = $this->_passTargetProbPerPosition[$player->position][PLAYER_POSITION_GOALY];
		$pTarget[PLAYER_POSITION_DEFENCE] = $this->_passTargetProbPerPosition[$player->position][PLAYER_POSITION_DEFENCE];
		$pTarget[PLAYER_POSITION_STRIKER] = $this->_passTargetProbPerPosition[$player->position][PLAYER_POSITION_STRIKER];
		if ($player->position != PLAYER_POSITION_GOALY) $pTarget[PLAYER_POSITION_STRIKER] += 10;
		$offensiveInfluence = round(10 - $player->team->offensive * 0.2);
		$pTarget[PLAYER_POSITION_DEFENCE] = $pTarget[PLAYER_POSITION_DEFENCE] + $offensiveInfluence;
		$pTarget[PLAYER_POSITION_MIDFIELD] = 100 - $pTarget[PLAYER_POSITION_STRIKER] - $pTarget[PLAYER_POSITION_DEFENCE] - $pTarget[PLAYER_POSITION_GOALY];
		$targetPosition = SimulationHelper::selectItemFromProbabilities($pTarget);
		$match->setPlayerWithBall(SimulationHelper::selectPlayer($player->team, $targetPosition, $player));
		foreach ($this->_observers as $observer) $observer->onBallPassSuccess($match, $player);
		return TRUE; }
	function tackle(SimulationMatch $match) {
		$player = $match->getPlayerWithBall();
		$opponentTeam = SimulationHelper::getOpponentTeam($player, $match);
		$targetPosition = $this->_opponentPositions[$player->position];
		$opponent = SimulationHelper::selectPlayer($opponentTeam, $targetPosition, null);
		$pWin[TRUE] = max(1, min(50 + $player->getTotalStrength($this->_websoccer, $match) - $opponent->getTotalStrength($this->_websoccer, $match), 99));
		$pWin[FALSE] = 100 - $pWin[TRUE];
		$result = SimulationHelper::selectItemFromProbabilities($pWin);
		foreach ($this->_observers as $observer) $observer->onAfterTackle($match, ($result) ? $player : $opponent, ($result) ? $opponent : $player);
		if ($result == TRUE) {
			$pTackle['yellow'] = round(max(1, min(20, round((100 - $opponent->strengthTech) / 2))) * $this->_websoccer->getConfig('sim_cardsprobability') / 100);
			if ($opponent->yellowCards > 0) $pTackle['yellow'] = round($pTackle['yellow'] / 2);
			$pTackle['red'] = 1;
			if ($pTackle['yellow']  > 15) $pTackle['red'] = 3;
			$pTackle['fair'] = 100 - $pTackle['yellow'] - $pTackle['red'];
			$tackled = SimulationHelper::selectItemFromProbabilities($pTackle);
			if ($tackled == 'yellow' || $tackled == 'red') {
				$pInjured[TRUE] = min(99, round(((100 - $player->strengthFreshness) / 3) * $this->_websoccer->getConfig('sim_injuredprobability') / 100));
				$pInjured[FALSE] = 100 - $pInjured[TRUE];
				$injured = SimulationHelper::selectItemFromProbabilities($pInjured);
				$blockedMatches = 0;
				if ($injured) {
					$maxMatchesInjured = (int) $this->_websoccer->getConfig('sim_maxmatches_injured');
					$pInjuredMatches[1] = 5;
					$pInjuredMatches[2] = 25;
					$pInjuredMatches[3] = 30;
					$pInjuredMatches[4] = 20;
					$pInjuredMatches[5] = 5;
					$pInjuredMatches[6] = 5;
					$pInjuredMatches[7] = 5;
					$pInjuredMatches[8] = 1;
					$pInjuredMatches[9] = 1;
					$pInjuredMatches[10] = 1;
					$pInjuredMatches[11] = 1;
					$pInjuredMatches[$maxMatchesInjured] = 1;
					$blockedMatches = SimulationHelper::selectItemFromProbabilities($pInjuredMatches);
					$blockedMatches = min($maxMatchesInjured, $blockedMatches); }
				foreach ($this->_observers as $observer) {
					if ($tackled == 'yellow') $observer->onYellowCard($match, $opponent);
					else {
						$maxMatchesBlocked = (int) $this->_websoccer->getConfig('sim_maxmatches_blocked');
						$minMatchesBlocked = min(1, $maxMatchesBlocked);
						$blockedMatchesRedCard = SimulationHelper::getMagicNumber($minMatchesBlocked, $maxMatchesBlocked);
						$observer->onRedCard($match, $opponent, $blockedMatchesRedCard); }
					if ($injured) {
						$observer->onInjury($match, $player, $blockedMatches);
						$match->setPlayerWithBall(SimulationHelper::selectPlayer($player->team, PLAYER_POSITION_MIDFIELD)); }}
				if ($player->position == PLAYER_POSITION_STRIKER) {
					$pPenalty[TRUE] = 10;
					$pPenalty[FALSE] = 90;
					if (SimulationHelper::selectItemFromProbabilities($pPenalty)) $this->foulPenalty($match, $player->team); }
				else {
					if ($player->team->freeKickPlayer != NULL) $freeKickScorer = $player->team->freeKickPlayer;
					else $freeKickScorer = SimulationHelper::selectPlayer($player->team, PLAYER_POSITION_MIDFIELD);
					$goaly = SimulationHelper::selectPlayer(SimulationHelper::getOpponentTeam($freeKickScorer, $match), PLAYER_POSITION_GOALY, null);
					$goalyInfluence = (int) $this->_websoccer->getConfig('sim_goaly_influence');
					$shootReduction = round($goaly->getTotalStrength($this->_websoccer, $match) * $goalyInfluence/100);
					$shootStrength = $freeKickScorer->getTotalStrength($this->_websoccer, $match);
					$pGoal[TRUE] = max(1, min($shootStrength - $shootReduction, 60));
					$pGoal[FALSE] = 100 - $pGoal[TRUE];
					$freeKickResult = SimulationHelper::selectItemFromProbabilities($pGoal);
					foreach ($this->_observers as $observer) $observer->onFreeKick($match, $freeKickScorer, $goaly, $freeKickResult);
					if ($freeKickResult) $this->_kickoff($match, $freeKickScorer);
					else $match->setPlayerWithBall($goaly); }}}
		else {
			$match->setPlayerWithBall($opponent);
			if ($player->position == PLAYER_POSITION_STRIKER && $opponent->team->counterattacks) {
				$counterAttempt[TRUE] = $player->team->offensive;
				$counterAttempt[FALSE] = 100 - $counterAttempt[TRUE];
				if (SimulationHelper::selectItemFromProbabilities($counterAttempt)) {
					if ($opponent->position == PLAYER_POSITION_DEFENCE) $match->setPlayerWithBall(SimulationHelper::selectPlayer($opponent->team, PLAYER_POSITION_STRIKER));
					$this->shoot($match); }}}
		return $result; }
	function shoot(SimulationMatch $match) {
		$player = $match->getPlayerWithBall();
		$goaly = SimulationHelper::selectPlayer(SimulationHelper::getOpponentTeam($player, $match), PLAYER_POSITION_GOALY, null);
		$goalyInfluence = (int) $this->_websoccer->getConfig('sim_goaly_influence');
		$shootReduction = round($goaly->getTotalStrength($this->_websoccer, $match) * $goalyInfluence/100);
		$shootStrength = round($player->getTotalStrength($this->_websoccer, $match) * $this->_shootStrengthPerPosition[$player->position] / 100);
		if ($player->position != PLAYER_POSITION_STRIKER || isset($player->team->positionsAndPlayers[PLAYER_POSITION_STRIKER]) && count($player->team->positionsAndPlayers[PLAYER_POSITION_STRIKER]) > 1) $shootStrength = $shootStrength +
			$player->getShoots() * 2 - $player->getGoals();
		if ($player->getGoals() > 1) $shootStrength = round($shootStrength / $player->getGoals());
		$pGoal[TRUE] = max(1, min($shootStrength - $shootReduction, 60));
		$pGoal[FALSE] = 100 - $pGoal[TRUE];
		$result = SimulationHelper::selectItemFromProbabilities($pGoal);
		if ($result == FALSE) {
			foreach ($this->_observers as $observer) $observer->onShootFailure($match, $player, $goaly);
			$match->setPlayerWithBall($goaly);
			$pCorner[TRUE] = round($player->strength / 2);
			$pCorner[FALSE] = 100 - $pCorner[TRUE];
			if (SimulationHelper::selectItemFromProbabilities($pCorner)) {
				if ($player->team->freeKickPlayer) $passingPlayer = $player->team->freeKickPlayer;
				else $passingPlayer = SimulationHelper::selectPlayer($player->team, PLAYER_POSITION_MIDFIELD);
				$targetPlayer = SimulationHelper::selectPlayer($player->team, PLAYER_POSITION_MIDFIELD, $passingPlayer);
				foreach ($this->_observers as $observer) $observer->onCorner($match, $passingPlayer, $targetPlayer);
				$match->setPlayerWithBall($targetPlayer); }}
		else {
			foreach ($this->_observers as $observer) $observer->onGoal($match, $player, $goaly);
			$this->_kickoff($match, $player); }
		return $result; }
	function penaltyShooting(SimulationMatch $match) {
		$shots = 0;
		$goalsHome = 0;
		$goalsGuest = 0;
		$playersHome = SimulationHelper::getPlayersForPenaltyShooting($match->homeTeam);
		$playersGuest = SimulationHelper::getPlayersForPenaltyShooting($match->guestTeam);
		$playerIndexHome = 0;
		$playerIndexGuest = 0;
		while ($shots <= 50) {
			$shots++;
			if ($this->_shootPenalty($match, $playersHome[$playerIndexHome])) $goalsHome++;
			if ($this->_shootPenalty($match, $playersGuest[$playerIndexGuest])) $goalsGuest++;
			if ($shots >= 5 && $goalsHome !== $goalsGuest) return TRUE;
			$playerIndexHome++;
			$playerIndexGuest++;
			if ($playerIndexHome >= count($playersHome)) $playerIndexHome = 0;
			if ($playerIndexGuest >= count($playersGuest)) $playerIndexGuest = 0; }}
	function foulPenalty(SimulationMatch $match, SimulationTeam $team) {
		$players = SimulationHelper::getPlayersForPenaltyShooting($team);
		$player = $players[0];
		$match->setPlayerWithBall($player);
		if ($this->_shootPenalty($match, $player)) $player->setGoals($player->getGoals() + 1);
		else {
			$goaly = SimulationHelper::selectPlayer(SimulationHelper::getOpponentTeam($player, $match), PLAYER_POSITION_GOALY, null);
			$match->setPlayerWithBall($goaly); }}
	function _setPassTargetProbabilities() {
		$this->_passTargetProbPerPosition[PLAYER_POSITION_GOALY][PLAYER_POSITION_GOALY] = 0;
		$this->_passTargetProbPerPosition[PLAYER_POSITION_GOALY][PLAYER_POSITION_DEFENCE] = 69;
		$this->_passTargetProbPerPosition[PLAYER_POSITION_GOALY][PLAYER_POSITION_MIDFIELD] = 30;
		$this->_passTargetProbPerPosition[PLAYER_POSITION_GOALY][PLAYER_POSITION_STRIKER] = 1;
		$this->_passTargetProbPerPosition[PLAYER_POSITION_DEFENCE][PLAYER_POSITION_GOALY] = 10;
		$this->_passTargetProbPerPosition[PLAYER_POSITION_DEFENCE][PLAYER_POSITION_DEFENCE] = 20;
		$this->_passTargetProbPerPosition[PLAYER_POSITION_DEFENCE][PLAYER_POSITION_MIDFIELD] = 65;
		$this->_passTargetProbPerPosition[PLAYER_POSITION_DEFENCE][PLAYER_POSITION_STRIKER] = 5;
		$this->_passTargetProbPerPosition[PLAYER_POSITION_MIDFIELD][PLAYER_POSITION_GOALY] = 1;
		$this->_passTargetProbPerPosition[PLAYER_POSITION_MIDFIELD][PLAYER_POSITION_DEFENCE] = 24;
		$this->_passTargetProbPerPosition[PLAYER_POSITION_MIDFIELD][PLAYER_POSITION_MIDFIELD] = 55;
		$this->_passTargetProbPerPosition[PLAYER_POSITION_MIDFIELD][PLAYER_POSITION_STRIKER] = 20;
		$this->_passTargetProbPerPosition[PLAYER_POSITION_STRIKER][PLAYER_POSITION_GOALY] = 0;
		$this->_passTargetProbPerPosition[PLAYER_POSITION_STRIKER][PLAYER_POSITION_DEFENCE] = 10;
		$this->_passTargetProbPerPosition[PLAYER_POSITION_STRIKER][PLAYER_POSITION_MIDFIELD] = 60;
		$this->_passTargetProbPerPosition[PLAYER_POSITION_STRIKER][PLAYER_POSITION_STRIKER] = 30; }
	function _setOpponentPositions() {
		$this->_opponentPositions[PLAYER_POSITION_GOALY] = PLAYER_POSITION_STRIKER;
		$this->_opponentPositions[PLAYER_POSITION_DEFENCE] = PLAYER_POSITION_STRIKER;
		$this->_opponentPositions[PLAYER_POSITION_MIDFIELD] = PLAYER_POSITION_MIDFIELD;
		$this->_opponentPositions[PLAYER_POSITION_STRIKER] = PLAYER_POSITION_DEFENCE; }
	function _setShootProbPerPosition() {
		$this->_shootProbPerPosition[PLAYER_POSITION_GOALY] = 0;
		$this->_shootProbPerPosition[PLAYER_POSITION_DEFENCE] = 5;
		$this->_shootProbPerPosition[PLAYER_POSITION_MIDFIELD] = 20;
		$this->_shootProbPerPosition[PLAYER_POSITION_STRIKER] = 35; }
	function _setShootStrengthPerPosition() {
		$this->_shootStrengthPerPosition[PLAYER_POSITION_GOALY] = 10;
		$this->_shootStrengthPerPosition[PLAYER_POSITION_DEFENCE] = $this->_websoccer->getConfig('sim_shootstrength_defense');
		$this->_shootStrengthPerPosition[PLAYER_POSITION_MIDFIELD] = $this->_websoccer->getConfig('sim_shootstrength_midfield');
		$this->_shootStrengthPerPosition[PLAYER_POSITION_STRIKER] = $this->_websoccer->getConfig('sim_shootstrength_striker'); }
	function _getOffensiveStrength($team, $match) {
		$strength = 0;
		if (isset($team->positionsAndPlayers[PLAYER_POSITION_MIDFIELD])) {
			$omPlayers = 0;
			foreach ($team->positionsAndPlayers[PLAYER_POSITION_MIDFIELD] as $player) {
				$mfStrength = $player->getTotalStrength($this->_websoccer, $match);
				if ($player->mainPosition == 'OM') {
					$omPlayers++;
					if ($omPlayers <= 3) $mfStrength = $mfStrength * 1.3;
					else $mfStrength = $mfStrength * 0.5; }
				elseif ($player->mainPosition == 'DM') $mfStrength = $mfStrength * 0.7;
				$strength += $mfStrength; }}
		$noOfStrikers = 0;
		if (isset($team->positionsAndPlayers[PLAYER_POSITION_STRIKER])) {
			foreach ($team->positionsAndPlayers[PLAYER_POSITION_STRIKER] as $player) {
				$noOfStrikers++;
				if ($noOfStrikers < 3) $strength += $player->getTotalStrength($this->_websoccer, $match) * 1.5;
				else $strength += $player->getTotalStrength($this->_websoccer, $match) * 0.5; }}
		$offensiveFactor = (80 + $team->offensive * 0.4) / 100;
		$strength = $strength * $offensiveFactor;
		return $strength; }
	function _getDefensiveStrength(SimulationTeam $team, $match) {
		$strength = 0;
		foreach ($team->positionsAndPlayers[PLAYER_POSITION_MIDFIELD] as $player) {
			$mfStrength = $player->getTotalStrength($this->_websoccer, $match);
			if ($player->mainPosition == 'OM') $mfStrength = $mfStrength * 0.7;
			elseif ($player->mainPosition == 'DM') $mfStrength = $mfStrength * 1.3;
			if ($team->counterattacks) $mfStrength = $mfStrength * 1.1;
			$strength += $mfStrength; }
		$noOfDefence = 0;
		foreach ($team->positionsAndPlayers[PLAYER_POSITION_DEFENCE] as $player) {
			$noOfDefence++;
			$strength += $player->getTotalStrength($this->_websoccer, $match); }
		if ($noOfDefence < 3) $strength = $strength * 0.5;
		elseif ($noOfDefence > 4) $strength = $strength * 1.5;
		$offensiveFactor = (130 - $team->offensive * 0.5) / 100;
		$strength = $strength * $offensiveFactor;
		return $strength; }
	function _shootPenalty(SimulationMatch $match, SimulationPlayer $player) {
		$goaly = SimulationHelper::selectPlayer(SimulationHelper::getOpponentTeam($player, $match), PLAYER_POSITION_GOALY, null);
		$goalyInfluence = (int) $this->_websoccer->getConfig('sim_goaly_influence');
		$shootReduction = round($goaly->getTotalStrength($this->_websoccer, $match) * $goalyInfluence/100);
		$pGoal[TRUE] = max(30, min($player->strength - $shootReduction, 80));
		$pGoal[FALSE] = 100 - $pGoal[TRUE];
		$result = SimulationHelper::selectItemFromProbabilities($pGoal);
		foreach ($this->_observers as $observer) $observer->onPenaltyShoot($match, $player, $goaly, $result);
		return $result;}
	function _kickoff(SimulationMatch $match, SimulationPlayer $scorer) { $match->setPlayerWithBall( SimulationHelper::selectPlayer(SimulationHelper::getOpponentTeam($scorer, $match), PLAYER_POSITION_DEFENCE, null)); }}
class EmailHelper {
	static function sendSystemEmailFromTemplate($websoccer, $i18n, $recipient, $subject, $templateName, $parameters) {
		$emailTemplateEngine = new TemplateEngine($websoccer, $i18n, null);
		$template = $emailTemplateEngine->loadTemplate('emails/' . $templateName);
		$content = $template->render($parameters);
		self::sendSystemEmail($websoccer, $recipient, $subject, $content); }
	static function sendSystemEmail($websoccer, $recipient, $subject, $content) {
		$fromName = $websoccer->getConfig('projectname');
		$fromEmail = $websoccer->getConfig('systememail');
		$headers   = array();
		$headers[] = 'Content-type: text/plain; charset = \'UTF-8\'';
		$headers[] = 'From: '. $fromName .' <'. $fromEmail . '>';
		$encodedsubject = '=?UTF-8?B?'.base64_encode($subject).'?=';
		if (@mail($recipient, $encodedsubject, $content, implode("\r\n", $headers)) == FALSE) throw new Exception('e-mail not sent.'); }}
require_once(__DIR__.'/facebooksdk/facebook.php');
class FacebookSdk {
	private static $_instance;
	private $_facebook;
	private $_websoccer;
	private $_userEmail;
	static function getInstance($websoccer) {
		if(self::$_instance == NULL) self::$_instance = new FacebookSdk($websoccer);
		return self::$_instance; }
	function __construct($websoccer) {
		$this->_websoccer = $websoccer;
		$this->_facebook = new Facebook(array( 'appId' => $websoccer->getConfig('facebook_appid'), 'secret' => $websoccer->getConfig('facebook_appsecret') )); }
	function getLoginUrl() { return $this->_facebook->getLoginUrl(array( 'scope' => 'email', 'redirect_uri' => $this->_websoccer->getInternalActionUrl('facebook-login', null, 'home', TRUE) )); }
	function getLogoutUrl() { return $this->_facebook->getLogoutUrl(array( 'next' => $this->_websoccer->getInternalActionUrl('facebook-logout', null, 'home', TRUE) )); }
	function getUserEmail() {
		if ($this->_userEmail == NULL) {
			$userId = $this->_facebook->getUser();
			if ($userId) {
				if (isset($_SESSION['fbemail'])) {
					$this->_userEmail = $_SESSION['fbemail'];
					return $this->_userEmail; }
				try{$fql = 'SELECT email from user where uid = ' . $userId;
					$ret_obj = $this->_facebook->api(array( 'method' => 'fql.query', 'query' => $fql, ));
					$this->_userEmail = $ret_obj[0]['email'];
					$_SESSION['fbemail'] = $this->_userEmail; }
				catch(FacebookApiException $e) { $this->_userEmail = ''; }}
			else $this->_userEmail = ''; }
		return $this->_userEmail; }
	function destroySession() { $this->_facebook->destroySession(); }}
class FileUploadHelper {
	static function uploadImageFile($i18n, $requestParameter, $targetFilename, $targetDirectory) {
		$filename = $_FILES[$requestParameter]['name'];
		$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		$allowedExtensions = explode(',', ALLOWED_EXTENSIONS);
		if (!in_array($ext, $allowedExtensions)) throw new Exception($i18n->getMessage('validationerror_imageupload_noimagefile'));
		$imagesize = getimagesize($_FILES[$requestParameter]['tmp_name']);
		if ($imagesize === FALSE) throw new Exception($i18n->getMessage('validationerror_imageupload_noimagefile'));
		$type = substr($imagesize['mime'], strrpos($imagesize['mime'], '/') + 1);
		if (!in_array($type, $allowedExtensions)) throw new Exception($i18n->getMessage('validationerror_imageupload_noimagefile'));
		$targetFilename .= '.' . $ext;
		self::_uploadFile($i18n, $requestParameter, $targetFilename, $targetDirectory);
		return $ext; }
	static function _uploadFile($i18n, $requestParameter, $targetFilename, $targetDirectory) {
		$errorcode = $_FILES[$requestParameter]['error'];
		if ($errorcode == UPLOAD_ERR_OK) {
			$tmp_name = $_FILES[$requestParameter]['tmp_name'];
			$name = $targetFilename;
			$uploaded = @move_uploaded_file($tmp_name, UPLOAD_FOLDER . $targetDirectory . '/'. $name);
			if (!$uploaded) throw new Exception($i18n->getMessage('error_file_upload_failed')); }
		else throw new Exception($i18n->getMessage('error_file_upload_failed')); }}
class FileWriter {
	private $_filePointer;
	function __construct($file, $truncateExistingFile = TRUE) {
		$this->_filePointer = @fopen($file, ($truncateExistingFile) ? 'w' : 'a');
		if ($this->_filePointer === FALSE) throw new Exception('Could not create or open file '. $file .'! Verify that the file or its folder is writable.'); }
	function writeLine($line) { if (@fwrite($this->_filePointer, $line . PHP_EOL) === FALSE) throw new Exception('Could not write line \''. $line . '\' into file '. $file .'!'); }
	function close() {
		//- owsPro - Fatal error: Uncaught TypeError: the function supplied resource is not a valid stream resource
		//- if ($this->_filePointer) {
		if ($this->_filePointer === FALSE) @fclose($this->_filePointer); }
	function __destruct() { $this->close(); }}
class FormBuilder {
	static function createFormGroup($i18n, $fieldId, $fieldInfo, $fieldValue, $labelKeyPrefix) {
		$type = $fieldInfo['type'];
		if ($type == 'timestamp' && isset($fieldInfo['readonly']) && $fieldInfo['readonly']) {
			$website = WebSoccer::getInstance();
			$dateFormat = $website->getConfig('datetime_format');
			if (!strlen($fieldValue)) $fieldValue = date($dateFormat);
			elseif (is_numeric($fieldValue)) $fieldValue = date($dateFormat, $fieldValue);
			$type = 'text'; }
		else if ($type == 'date' && strlen($fieldValue)) {
			if (StringUtil::startsWith($fieldValue, '0000')) $fieldValue = '';
			else {
				$dateObj = DateTime::createFromFormat('Y-m-d', $fieldValue);
				if ($dateObj !== FALSE) {
					$website = WebSoccer::getInstance();
					$dateFormat = $website->getConfig('date_format');
					$fieldValue = $dateObj->format($dateFormat); }}}
		echo '<div class=\'control-group\'>';
		$helpText = '';
		$inlineHelpKey = $labelKeyPrefix . $fieldId .'_help';
		if ($i18n->hasMessage($inlineHelpKey)) $helpText = '<span class=\'help-inline\'>'. $i18n->getMessage($inlineHelpKey) . '</span>';
		if ($type == 'boolean') {
			echo '<label class=\'checkbox\'>';
			echo '<input type=\'checkbox\' value=\'1\' name=\''. $fieldId . '\'';
			if ($fieldValue == '1') echo ' checked';
			echo '>';
			echo $i18n->getMessage($labelKeyPrefix . $fieldId);
			echo '</label>';
			echo $helpText; }
		else {
			$labelOutput = $i18n->getMessage($labelKeyPrefix . $fieldId);
			if (isset($fieldInfo['required']) && $fieldInfo['required'] == 'true') $labelOutput = '<strong>'. $labelOutput . '</strong>';
			echo '<label class=\'control-label\' for=\''. $fieldId . '\'>'. $labelOutput . '</label>';
			echo '<div class=\'controls\'>';
			switch ($type) {
				case 'foreign_key':
					self::createForeignKeyField($i18n, $fieldId, $fieldInfo, $fieldValue);
					break;
				case 'html':
				case 'textarea':
					$class = 'input-xxlarge';
					if ($type == 'html') $class = 'htmleditor';
					echo '<textarea id=\''. $fieldId . '\' name=\''. $fieldId . '\' wrap=\'virtual\' class=\''. $class .'\' rows=\'10\'>'. $fieldValue .'</textarea>';
					break;
				case 'timestamp':
					$website = WebSoccer::getInstance();
					$dateFormat = $website->getConfig('date_format');
					if (!$fieldValue) $fieldValue = $website->getNowAsTimestamp();
					echo '<div class=\'input-append date datepicker\'>';
					echo '<input type=\'text\' name=\''. $fieldId . '_date\' value=\''. date($dateFormat, $fieldValue) . '\' class=\'input-small\'>';
					echo '<span class=\'add-on\'><i class=\'icon-calendar\'></i></span>';
					echo '</div>';
					echo '<div class=\'input-append bootstrap-timepicker\'>';
					echo '<input type=\'text\' name=\''. $fieldId . '_time\' value=\''. date('H:i', $fieldValue) . '\' class=\'timepicker input-small\'>';
					echo '<span class=\'add-on\'><i class=\'icon-time\'></i></span>';
        			echo '</div>';
					break;
				case 'select':
					echo '<select id=\''. $fieldId . '\' name=\''. $fieldId . '\'>';
					$selection = explode(',', $fieldInfo['selection']);
					$selectValue = $fieldValue;
					echo '<option></option>';
					foreach ($selection as $selectItem) {
						$selectItem = trim($selectItem);
						echo '<option value=\''. $selectItem .'\'';
						if ($selectItem == $selectValue) echo ' selected';
						echo '>';
						$label = $selectItem;
						if ($i18n->hasMessage('option_' . $selectItem)) $label = $i18n->getMessage('option_' . $selectItem);
						echo $label . '</option>'; }
					echo '</select>';
					break;
				default:
					if (isset($fieldInfo['readonly']) && $fieldInfo['readonly']) echo '<span class=\'uneditable-input\'>'. escapeOutput($fieldValue) .'</span>';
					else {
						$additionalAttrs = '';
						$htmlType = $type;
						if($type == 'file' && strlen($fieldValue)) {
							global $entity;
							echo '[<a href=\'../uploads/' . $entity .'/'. escapeOutput($fieldValue) . '\' target=\'_blank\'>View</a>] '; }
						elseif($type == 'percent') {
							$htmlType = 'number';
							$additionalAttrs = 'class=\'input-mini\' min=\'0\' '; }
						elseif ($type == 'number') $additionalAttrs = 'class=\'input-small\' ';
						elseif ($type == 'date') {
							if ($type == 'date') echo '<div class=\'input-append date datepicker\'>';
							$htmlType ='text';
							$additionalAttrs = ' class=\'input-small\' '; }
						elseif ($type == 'tags') $additionalAttrs = ' class=\'input-tag\' data-provide=\'tag\' ';
						else $additionalAttrs = 'placeholder=\''. $i18n->getMessage($labelKeyPrefix . $fieldId) . '\' ';
						echo '<input type=\''. $htmlType . '\' id=\''. $fieldId . '\' '. $additionalAttrs . 'name=\''. $fieldId . '\' value=\'';
						if ($type != 'password') echo escapeOutput($fieldValue);
						echo '\'';
						if (isset($fieldInfo['required']) && $fieldInfo['required']) echo ' required';
						echo '>';
						if ($type == 'date') echo '<span class=\'add-on\'><i class=\'icon-calendar\'></i></span></div>'; }}
			if ($type == 'percent') echo ' % ';
			echo $helpText;
			echo '</div>'; }
		echo '</div>'; }
	static function validateField($i18n, $fieldId, $fieldInfo, $fieldValue, $labelKeyPrefix) {
		$textLength = strlen(trim($fieldValue));
		$isEmpty = !$textLength;
		if ($fieldInfo['type'] != 'boolean' && $fieldInfo['required'] && $isEmpty) throw new Exception(sprintf($i18n->getMessage('validationerror_required'), $i18n->getMessage($labelKeyPrefix . $fieldId)));
		if (!$isEmpty) {
			if ($fieldInfo['type'] == 'text' && $textLength > 255) throw new Exception(sprintf($i18n->getMessage('validationerror_text_too_long'), $i18n->getMessage($labelKeyPrefix . $fieldId)));
			if ($fieldInfo['type'] == 'email' && !filter_var($fieldValue, FILTER_VALIDATE_EMAIL)) throw new Exception($i18n->getMessage('validationerror_email'));
			if ($fieldInfo['type'] == 'url' && !filter_var($fieldValue, FILTER_VALIDATE_URL)) throw new Exception(sprintf($i18n->getMessage('validationerror_url'), $i18n->getMessage($labelKeyPrefix . $fieldId)));
			if ($fieldInfo['type'] == 'number' && !is_numeric($fieldValue)) throw new Exception(sprintf($i18n->getMessage('validationerror_number'), $i18n->getMessage($labelKeyPrefix . $fieldId)));
			if ($fieldInfo['type'] == 'percent' && filter_var($fieldValue, FILTER_VALIDATE_INT) === FALSE) throw new Exception(sprintf($i18n->getMessage('validationerror_percent'), $i18n->getMessage($labelKeyPrefix . $fieldId)));
			if ($fieldInfo['type'] == 'date') {
				$website = WebSoccer::getInstance();
				$format = $website->getConfig('date_format');
				if (!DateTime::createFromFormat($format, $fieldValue)) throw new Exception(sprintf($i18n->getMessage('validationerror_date'), $i18n->getMessage($labelKeyPrefix . $fieldId), $format)); }}
		if (isset($fieldInfo['validator']) && strlen($fieldInfo['validator'])) {
			$website = WebSoccer::getInstance();
			$validator = new $fieldInfo['validator']($i18n, $website, $fieldValue);
			if (!$validator->isValid()) throw new Exception($i18n->getMessage($labelKeyPrefix . $fieldId) . ': ' . $validator->getMessage()); }}
	static function createForeignKeyField($i18n, $fieldId, $fieldInfo, $fieldValue) {
		$website = WebSoccer::getInstance();
		$db = DbConnection::getInstance();
		$fromTable = $website->getConfig('db_prefix') .'_'. $fieldInfo['jointable'];
		$result = $db->querySelect('COUNT(*) AS hits', $fromTable, '1=1', '');
		$items = $result->fetch_array();
		if ($items['hits'] <= 20) {
			echo '<select id=\''. $fieldId . '\' name=\''. $fieldId . '\'>';
			echo '<option value=\'\'>' . $i18n->getMessage('manage_select_placeholder') . '</option>';
			$whereCondition = '1=1 ORDER BY '. $fieldInfo['labelcolumns'] . ' ASC';
			$result = $db->querySelect('id, ' . $fieldInfo['labelcolumns'], $fromTable, $whereCondition, '', 2000);
			while ($row = $result->fetch_array()) {
				$labels = explode(',', $fieldInfo['labelcolumns']);
				$label = '';
				$first = TRUE;
				foreach ($labels as $labelColumn) {
					if (!$first) $label .= ' - ';
					$first = FALSE;
					$label .= $row[trim($labelColumn)]; }
				echo '<option value=\''. $row['id'] . '\'';
				if ($fieldValue == $row['id']) echo ' selected';
				echo '>'. escapeOutput($label) . '</option>'; }
			echo '</select>'; }
		else echo '<input type=\'hidden\' class=\'pkpicker\' id=\''. $fieldId . '\' name=\''. $fieldId . '\' value=\'' . $fieldValue . '\' data-dbtable=\''. $fieldInfo['jointable'] . '\' data-labelcolumns=\''. $fieldInfo['labelcolumns'] . '\' data-placeholder=\'' .
				$i18n->getMessage('manage_select_placeholder') . '\'>';
		echo ' <a href=\'?site=manage&entity='. $fieldInfo['entity'] . '&show=add\' title=\''. $i18n->getMessage('manage_add') . '\'><i class=\'icon-plus-sign\'></i></a>'; }}
class FrontMessage {
	public $type;
	public $title;
	public $message;
	function __construct($type, $title, $message) {
		if ($type !== MESSAGE_TYPE_INFO && $type !== MESSAGE_TYPE_SUCCESS && $type !== MESSAGE_TYPE_ERROR && $type !== MESSAGE_TYPE_WARNING) throw new Exception('unknown FrontMessage type: ' . $type);
		$this->type = $type;
		$this->title = $title;
		$this->message = $message; }}
require_once(__DIR__.'/googleapi/Google_Client.php');
class GoogleplusSdk {
	private static $_instance;
	private $_client;
	private $_websoccer;
	private $_oauth2Service;
	static function getInstance($websoccer) {
		if(self::$_instance == NULL) self::$_instance = new GoogleplusSdk($websoccer);
		return self::$_instance; }
	function __construct($websoccer) {
		$this->_websoccer = $websoccer;
		$client = new Google_Client();
		$client->setApplicationName($this->_websoccer->getConfig('googleplus_appname'));
		$client->setClientId($this->_websoccer->getConfig('googleplus_clientid'));
		$client->setClientSecret($this->_websoccer->getConfig('googleplus_clientsecret'));
		$client->setRedirectUri($this->_websoccer->getInternalActionUrl('googleplus-login', null, 'home', TRUE));
		$client->setDeveloperKey($this->_websoccer->getConfig('googleplus_developerkey'));
		$client->setScopes(array('https://www.googleapis.com/auth/plus.login', 'https://www.googleapis.com/auth/userinfo.email'));
		$this->_oauth2Service = new Google_Oauth2Service($client);
		$this->_client = $client; }
	function getLoginUrl() { return $this->_client->createAuthUrl(); }
	function authenticateUser() {
		if (isset($_GET['code'])) {
			$this->_client->authenticate();
			$_SESSION['gptoken'] = $this->_client->getAccessToken(); }
		if (isset($_SESSION['gptoken'])) $this->_client->setAccessToken($_SESSION['gptoken']);
		if ($this->_client->getAccessToken()) {
			$userinfo = $this->_oauth2Service->userinfo->get();
			$email = $userinfo['email'];
			$_SESSION['gptoken'] = $this->_client->getAccessToken();
			if (strlen($email)) return $email; }
		return FALSE; }}
class I18n {
	private static $_instance;
	private $_currentLanguage;
	private $_supportedLanguages;
	static function getInstance($supportedLanguages) {
		if(self::$_instance == NULL) self::$_instance = new I18n($supportedLanguages);
		return self::$_instance; }
	function __construct($supportedLanguages) { $this->_supportedLanguages = array_map('trim', explode(',', $supportedLanguages)); }
	function getCurrentLanguage() {
		if ($this->_currentLanguage == null) {
			if (isset($_SESSION[LANG_SESSION_PARAM])) $lang = $_SESSION[LANG_SESSION_PARAM];
			elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) $lang = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
			else $lang = $this->_supportedLanguages[0];
			if (!in_array($lang, $this->_supportedLanguages)) $lang = $this->_supportedLanguages[0];
			$this->_currentLanguage = $lang; }
		return $this->_currentLanguage; }
	function setCurrentLanguage($language) {
		if ($language == $this->_currentLanguage) return;
		$lang = strtolower($language);
		if (!in_array($lang, $this->_supportedLanguages)) $lang = $this->getCurrentLanguage();
		$_SESSION[LANG_SESSION_PARAM] = $lang;
		$this->_currentLanguage = $lang; }
	function getMessage($messageKey, $paramaters = null) {
		global $msg;
		if (!$this->hasMessage($messageKey)) return '???' . $messageKey .'???';
		$message = stripslashes($msg[$messageKey]);
		if ($paramaters != null) $message = sprintf($message, $paramaters);
		return $message; }
	function hasMessage($messageKey) {
		global $msg;
		return isset($msg[$messageKey]); }
	function getNavigationLabel($pageId) { return $this->getMessage($pageId . PAGE_NAV_LABEL_SUFFIX); }
	function getSupportedLanguages() { return $this->_supportedLanguages; }}
class MatchReportSimulationObserver {
	private $_availableTexts;
	function __construct($websoccer, $db) {
		$this->_availableTexts = array();
		$this->_websoccer = $websoccer;
		$this->_db = $db;
		$fromTable = $websoccer->getConfig('db_prefix') . '_spiel_text';
		$columns = 'id, aktion AS actiontype';
		$result = $db->querySelect($columns, $fromTable, '1=1');
		while ($text = $result->fetch_array()) $this->_availableTexts[$text['actiontype']][] = $text['id']; }
	function onGoal(SimulationMatch $match, SimulationPlayer $scorer, SimulationPlayer $goaly) {
		$assistPlayerName = ($match->getPreviousPlayerWithBall() !== NULL && $match->getPreviousPlayerWithBall()->team->id == $scorer->team->id) ? $match->getPreviousPlayerWithBall()->name : '';
		if (strlen($assistPlayerName)) $this->_createMessage($match, 'Tor_mit_vorlage', array($scorer->name, $assistPlayerName), ($scorer->team->id == $match->homeTeam->id));
		else $this->_createMessage($match, 'Tor', array($scorer->name, $goaly->name), ($scorer->team->id == $match->homeTeam->id)); }
	function onShootFailure(SimulationMatch $match, SimulationPlayer $scorer, SimulationPlayer $goaly) {
		if (SimulationHelper::getMagicNumber(0, 1)) $this->_createMessage($match, 'Torschuss_daneben', array($scorer->name, $goaly->name), ($scorer->team->id == $match->homeTeam->id));
		else $this->_createMessage($match, 'Torschuss_auf_Tor', array($scorer->name, $goaly->name), ($scorer->team->id == $match->homeTeam->id)); }
	function onAfterTackle(SimulationMatch $match, SimulationPlayer $winner, SimulationPlayer $looser) {
		if (SimulationHelper::getMagicNumber(0, 1)) $this->_createMessage($match, 'Zweikampf_gewonnen', array($winner->name, $looser->name), ($winner->team->id == $match->homeTeam->id));
		else $this->_createMessage($match, 'Zweikampf_verloren', array($looser->name, $winner->name), ($looser->team->id == $match->homeTeam->id)); }
	function onBallPassSuccess(SimulationMatch $match, SimulationPlayer $player) {}
	function onBallPassFailure(SimulationMatch $match, SimulationPlayer $player) {
		if ($player->position != PLAYER_POSITION_GOALY) {
			$targetPlayer = SimulationHelper::selectPlayer($player->team, $player->position, $player);
			$this->_createMessage($match, 'Pass_daneben', array($player->name, $targetPlayer->name), ($player->team->id == $match->homeTeam->id)); }}
	function onInjury(SimulationMatch $match, SimulationPlayer $player, $numberOfMatches) { $this->_createMessage($match, 'Verletzung', array($player->name), ($player->team->id == $match->homeTeam->id)); }
	function onYellowCard(SimulationMatch $match, SimulationPlayer $player) {
		if ($player->yellowCards > 1) $this->_createMessage($match, 'Karte_gelb_rot', array($player->name), ($player->team->id == $match->homeTeam->id));
		else $this->_createMessage($match, 'Karte_gelb', array($player->name), ($player->team->id == $match->homeTeam->id)); }
	function onRedCard(SimulationMatch $match, SimulationPlayer $player, $matchesBlocked) { $this->_createMessage($match, 'Karte_rot', array($player->name), ($player->team->id == $match->homeTeam->id)); }
	function onPenaltyShoot(SimulationMatch $match, SimulationPlayer $player, SimulationPlayer $goaly, $successful) {
		if ($successful) $this->_createMessage($match, 'Elfmeter_erfolg', array($player->name, $goaly->name), ($player->team->id == $match->homeTeam->id));
		else $this->_createMessage($match, 'Elfmeter_verschossen', array($player->name, $goaly->name), ($player->team->id == $match->homeTeam->id)); }
	function onCorner(SimulationMatch $match, SimulationPlayer $concededByPlayer, SimulationPlayer $targetPlayer) { $this->_createMessage($match, 'Ecke', array($concededByPlayer->name, $targetPlayer->name), ($concededByPlayer->team->id == $match->homeTeam->id)); }
	function onFreeKick(SimulationMatch $match, SimulationPlayer $player, SimulationPlayer $goaly, $successful) {
		if ($successful) $this->_createMessage($match, 'Freistoss_treffer', array($player->name, $goaly->name), ($player->team->id == $match->homeTeam->id));
		else $this->_createMessage($match, 'Freistoss_daneben', array($player->name, $goaly->name), ($player->team->id == $match->homeTeam->id)); }
	function _createMessage($match, $messageType, $playerNames = null, $isHomeActive = TRUE) {
		if (!isset($this->_availableTexts[$messageType])) return;
		$texts = count($this->_availableTexts[$messageType]);
		$index = SimulationHelper::getMagicNumber(0, $texts - 1);
		$messageId = $this->_availableTexts[$messageType][$index];
		$players = '';
		if ($playerNames != null) $players = implode(';', $playerNames);
		$fromTable = $this->_websoccer->getConfig('db_prefix') . '_matchreport';
		$columns['match_id'] = $match->id;
		$columns['minute'] = $match->minute;
		$columns['message_id'] = $messageId;
		$columns['playernames'] = $players;
		$columns['goals'] = $match->homeTeam->getGoals() . ':' . $match->guestTeam->getGoals();
		$columns['active_home'] = $isHomeActive;
		$this->_db->queryInsert($columns, $fromTable); }}
class MatchReportSimulatorObserver {
	private $_availableTexts;
	function __construct($websoccer, $db) {
		$this->_availableTexts = array();
		$this->_websoccer = $websoccer;
		$this->_db = $db;
		$fromTable = $websoccer->getConfig('db_prefix') . '_spiel_text';
		$columns = 'id, aktion AS actiontype';
		$whereCondition = 'aktion = \'Auswechslung\'';
		$result = $db->querySelect($columns, $fromTable, $whereCondition);
		while ($text = $result->fetch_array()) $this->_availableTexts[$text['actiontype']][] = $text['id']; }
	function onSubstitution(SimulationMatch $match, SimulationSubstitution $substitution) { $this->_createMessage($match, 'Auswechslung',array($substitution->playerIn->name, $substitution->playerOut->name),($substitution->playerIn->team->id == $match->homeTeam->id)); }
	function _createMessage($match, $messageType, $playerNames = null, $isHomeActive = TRUE) {
		if (!isset($this->_availableTexts[$messageType])) return;
		$texts = count($this->_availableTexts[$messageType]);
		$index = SimulationHelper::getMagicNumber(0, $texts - 1);
		$messageId = $this->_availableTexts[$messageType][$index];
		$players = '';
		if ($playerNames != null) $players = implode(';', $playerNames);
		$fromTable = $this->_websoccer->getConfig('db_prefix') . '_matchreport';
		$columns['match_id'] = $match->id;
		$columns['minute'] = $match->minute;
		$columns['message_id'] = $messageId;
		$columns['playernames'] = $players;
		$columns['active_home'] = $isHomeActive;
		$this->_db->queryInsert($columns, $fromTable); }
	function onMatchCompleted(SimulationMatch $match) {}
	function onBeforeMatchStarts(SimulationMatch $match) {} }
class MatchSimulationExecutor {
	static function simulateOpenMatches($websoccer, $db) {
		$simulator = new Simulator($db, $websoccer);
		$strategy = $simulator->getSimulationStrategy();
		$simulationObservers = explode(',', $websoccer->getConfig('sim_simulation_observers'));
		foreach ($simulationObservers as $observerClassName) {
			$observerClass = trim($observerClassName);
			if (strlen($observerClass)) $strategy->attachObserver(new $observerClass($websoccer, $db)); }
		$simulatorObservers = explode(',', $websoccer->getConfig('sim_simulator_observers'));
		foreach ($simulatorObservers as $observerClassName) {
			$observerClass = trim($observerClassName);
			if (strlen($observerClass)) $simulator->attachObserver(new $observerClass($websoccer, $db)); }
		$fromTable = $websoccer->getConfig('db_prefix') .'_spiel AS M';
		$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') .'_verein AS HOME_T ON HOME_T.id = M.home_verein';
		$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') .'_verein AS GUEST_T ON GUEST_T.id = M.gast_verein';
		$fromTable .= ' LEFT JOIN ' . $websoccer->getConfig('db_prefix') .'_aufstellung AS HOME_F ON HOME_F.match_id = M.id AND HOME_F.verein_id = M.home_verein';
		$fromTable .= ' LEFT JOIN ' . $websoccer->getConfig('db_prefix') .'_aufstellung AS GUEST_F ON GUEST_F.match_id = M.id AND GUEST_F.verein_id = M.gast_verein';
		$columns['M.id'] = 'match_id';
		$columns['M.spieltyp'] = 'type';
		$columns['M.home_verein'] = 'home_id';
		$columns['M.gast_verein'] = 'guest_id';
		$columns['M.minutes'] = 'minutes';
		$columns['M.soldout'] = 'soldout';
		$columns['M.elfmeter'] = 'penaltyshooting';
		$columns['M.pokalname'] = 'cup_name';
		$columns['M.pokalrunde'] = 'cup_roundname';
		$columns['M.pokalgruppe'] = 'cup_groupname';
		$columns['M.stadion_id'] = 'custom_stadium_id';
		$columns['M.player_with_ball'] = 'player_with_ball';
		$columns['M.prev_player_with_ball'] = 'prev_player_with_ball';
		$columns['M.home_tore'] = 'home_goals';
		$columns['M.gast_tore'] = 'guest_goals';
		$columns['M.home_offensive'] = 'home_offensive';
		$columns['M.home_setup'] = 'home_setup';
		$columns['M.home_noformation'] = 'home_noformation';
		$columns['M.home_longpasses'] = 'home_longpasses';
		$columns['M.home_counterattacks'] = 'home_counterattacks';
		$columns['M.home_morale'] = 'home_morale';
		$columns['M.home_freekickplayer'] = 'home_freekickplayer';
		$columns['M.gast_offensive'] = 'guest_offensive';
		$columns['M.guest_noformation'] = 'guest_noformation';
		$columns['M.gast_setup'] = 'guest_setup';
		$columns['M.gast_longpasses'] = 'guest_longpasses';
		$columns['M.gast_counterattacks'] = 'guest_counterattacks';
		$columns['M.gast_morale'] = 'guest_morale';
		$columns['M.gast_freekickplayer'] = 'guest_freekickplayer';
		$columns['HOME_F.id'] = 'home_formation_id';
		$columns['HOME_F.offensive'] = 'home_formation_offensive';
		$columns['HOME_F.setup'] = 'home_formation_setup';
		$columns['HOME_F.longpasses'] = 'home_formation_longpasses';
		$columns['HOME_F.counterattacks'] = 'home_formation_counterattacks';
		$columns['HOME_F.freekickplayer'] = 'home_formation_freekickplayer';
		$columns['HOME_T.name'] = 'home_name';
		$columns['HOME_T.nationalteam'] = 'home_nationalteam';
		$columns['HOME_T.interimmanager'] = 'home_interimmanager';
		$columns['HOME_T.captain_id'] = 'home_captain_id';
		$columns['GUEST_T.nationalteam'] = 'guest_nationalteam';
		$columns['GUEST_T.name'] = 'guest_name';
		$columns['GUEST_T.captain_id'] = 'guest_captain_id';
		$columns['GUEST_T.interimmanager'] = 'guest_interimmanager';
		for ($playerNo = 1; $playerNo <= 11; $playerNo++) {
			$columns['HOME_F.spieler' . $playerNo] = 'home_formation_player' . $playerNo;
			$columns['HOME_F.spieler' . $playerNo . '_position'] = 'home_formation_player_pos_' . $playerNo;
			$columns['GUEST_F.spieler' . $playerNo] = 'guest_formation_player' . $playerNo;
			$columns['GUEST_F.spieler' . $playerNo . '_position'] = 'guest_formation_player_pos_' . $playerNo;
			if ($playerNo <= 5) {
				$columns['HOME_F.ersatz' . $playerNo] = 'home_formation_bench' . $playerNo;
				$columns['GUEST_F.ersatz' . $playerNo] = 'guest_formation_bench' . $playerNo; }}
		for ($subNo = 1; $subNo <= 3; $subNo++) {
			$columns['HOME_F.w' . $subNo . '_raus'] = 'home_formation_sub' . $subNo . '_out';
			$columns['HOME_F.w' . $subNo . '_rein'] = 'home_formation_sub' . $subNo . '_in';
			$columns['HOME_F.w' . $subNo . '_minute'] = 'home_formation_sub' . $subNo . '_minute';
			$columns['HOME_F.w' . $subNo . '_condition'] = 'home_formation_sub' . $subNo . '_condition';
			$columns['HOME_F.w' . $subNo . '_position'] = 'home_formation_sub' . $subNo . '_position';
			$columns['M.home_w' . $subNo . '_raus'] = 'home_sub_' . $subNo . '_out';
			$columns['M.home_w' . $subNo . '_rein'] = 'home_sub_' . $subNo . '_in';
			$columns['M.home_w' . $subNo . '_minute'] = 'home_sub_' . $subNo . '_minute';
			$columns['M.home_w' . $subNo . '_condition'] = 'home_sub_' . $subNo . '_condition';
			$columns['M.home_w' . $subNo . '_position'] = 'home_sub_' . $subNo . '_position';
			$columns['GUEST_F.w' . $subNo . '_raus'] = 'guest_formation_sub' . $subNo . '_out';
			$columns['GUEST_F.w' . $subNo . '_rein'] = 'guest_formation_sub' . $subNo . '_in';
			$columns['GUEST_F.w' . $subNo . '_minute'] = 'guest_formation_sub' . $subNo . '_minute';
			$columns['GUEST_F.w' . $subNo . '_condition'] = 'guest_formation_sub' . $subNo . '_condition';
			$columns['GUEST_F.w' . $subNo . '_position'] = 'guest_formation_sub' . $subNo . '_position';
			$columns['M.gast_w' . $subNo . '_raus'] = 'guest_sub_' . $subNo . '_out';
			$columns['M.gast_w' . $subNo . '_rein'] = 'guest_sub_' . $subNo . '_in';
			$columns['M.gast_w' . $subNo . '_minute'] = 'guest_sub_' . $subNo . '_minute';
			$columns['M.gast_w' . $subNo . '_condition'] = 'guest_sub_' . $subNo . '_condition';
			$columns['M.gast_w' . $subNo . '_position'] = 'guest_sub_' . $subNo . '_position'; }
		$columns['GUEST_F.id'] = 'guest_formation_id';
		$columns['GUEST_F.offensive'] = 'guest_formation_offensive';
		$columns['GUEST_F.setup'] = 'guest_formation_setup';
		$columns['GUEST_F.longpasses'] = 'guest_formation_longpasses';
		$columns['GUEST_F.counterattacks'] = 'guest_formation_counterattacks';
		$columns['GUEST_F.freekickplayer'] = 'guest_formation_freekickplayer';
		$whereCondition = 'M.berechnet != \'1\' AND M.blocked != \'1\' AND M.datum <= %d ORDER BY M.datum ASC';
		$parameters = $websoccer->getNowAsTimestamp();
		$interval = (int) $websoccer->getConfig('sim_interval');
		$maxMatches = (int) $websoccer->getConfig('sim_max_matches_per_run');
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters, $maxMatches);
		$matchesSimulated = 0;
		$lockArray = array('blocked' => '1');
		$unlockArray = array('blocked' => '0');
		$matchTable = $websoccer->getConfig('db_prefix') . '_spiel';
		while ($matchinfo = $result->fetch_array()) {
			$db->queryUpdate($lockArray, $matchTable, 'id = %d', $matchinfo['match_id']);
			$match = null;
			if ($matchinfo['minutes'] < 1) $match = self::createInitialMatchData($websoccer, $db, $matchinfo);
			else $match = SimulationStateHelper::loadMatchState($websoccer, $db, $matchinfo);
			if ($match != null) {
				$simulator->simulateMatch($match, $interval);
				SimulationStateHelper::updateState($websoccer, $db, $match); }
			$match->cleanReferences();
			unset($match);
			$db->queryUpdate($unlockArray, $matchTable, 'id = %d', $matchinfo['match_id']);
			$matchesSimulated++; }
		$maxYouthMatchesToSimulate = $maxMatches - $matchesSimulated;
		if ($maxYouthMatchesToSimulate) YouthMatchSimulationExecutor::simulateOpenYouthMatches($websoccer, $db, $maxYouthMatchesToSimulate); }
	static function handleBothTeamsHaveNoFormation($websoccer, $db, $homeTeam, $guestTeam, SimulationMatch $match) {
		$homeTeam->noFormationSet = TRUE;
		$guestTeam->noFormationSet = TRUE;
		if ($websoccer->getConfig('sim_noformation_bothteams') == 'computer') {
			SimulationFormationHelper::generateNewFormationForTeam($websoccer, $db, $homeTeam, $match->id);
			SimulationFormationHelper::generateNewFormationForTeam($websoccer, $db, $guestTeam, $match->id); }
		else $match->isCompleted = TRUE; }
	static function handleOneTeamHasNoFormation($websoccer, $db, $team, SimulationMatch $match) {
		$team->noFormationSet = TRUE;
		if ($websoccer->getConfig('sim_createformation_without_manager') && self::teamHasNoManager($websoccer, $db, $team->id)) SimulationFormationHelper::generateNewFormationForTeam($websoccer, $db, $team, $match->id);
		else {
			if ($websoccer->getConfig('sim_noformation_oneteam') == '0_0') $match->isCompleted = TRUE;
			elseif ($websoccer->getConfig('sim_noformation_oneteam') == '3_0') {
				$opponentTeam = ($match->homeTeam->id == $team->id) ? $match->guestTeam : $match->homeTeam;
				$opponentTeam->setGoals(3);
				$match->isCompleted = TRUE; }
			else SimulationFormationHelper::generateNewFormationForTeam($websoccer, $db, $team, $match->id); }}
	static function createInitialMatchData($websoccer, $db, $matchinfo) {
		$db->queryDelete($websoccer->getConfig('db_prefix') . '_spiel_berechnung', 'spiel_id = %d', $matchinfo['match_id']);
		$db->queryDelete($websoccer->getConfig('db_prefix') . '_matchreport', 'match_id = %d', $matchinfo['match_id']);
		$homeOffensive = ($matchinfo['home_formation_offensive'] > 0) ? $matchinfo['home_formation_offensive'] : $websoccer->getConfig('sim_createformation_without_manager_offensive');
		$guestOffensive = ($matchinfo['guest_formation_offensive'] > 0) ? $matchinfo['guest_formation_offensive'] : $websoccer->getConfig('sim_createformation_without_manager_offensive');
		$homeTeam = new SimulationTeam($matchinfo['home_id'], $homeOffensive);
		$homeTeam->setup = $matchinfo['home_formation_setup'];
		$homeTeam->isNationalTeam = $matchinfo['home_nationalteam'];
		$homeTeam->isManagedByInterimManager = $matchinfo['home_interimmanager'];
		$homeTeam->name = $matchinfo['home_name'];
		$homeTeam->longPasses = $matchinfo['home_formation_longpasses'];
		$homeTeam->counterattacks = $matchinfo['home_formation_counterattacks'];
		$guestTeam = new SimulationTeam($matchinfo['guest_id'], $guestOffensive);
		$guestTeam->setup = $matchinfo['guest_formation_setup'];
		$guestTeam->isNationalTeam = $matchinfo['guest_nationalteam'];
		$guestTeam->isManagedByInterimManager = $matchinfo['guest_interimmanager'];
		$guestTeam->name = $matchinfo['guest_name'];
		$guestTeam->longPasses = $matchinfo['guest_formation_longpasses'];
		$guestTeam->counterattacks = $matchinfo['guest_formation_counterattacks'];
		$match = new SimulationMatch($matchinfo['match_id'], $homeTeam, $guestTeam, 0);
		$match->type = $matchinfo['type'];
		$match->penaltyShootingEnabled = $matchinfo['penaltyshooting'];
		$match->cupName = $matchinfo['cup_name'];
		$match->cupRoundName = $matchinfo['cup_roundname'];
		$match->cupRoundGroup = $matchinfo['cup_groupname'];
		$match->isAtForeignStadium = ($matchinfo['custom_stadium_id']) ? TRUE : FALSE;
		if (!$matchinfo['home_formation_id'] && !$matchinfo['guest_formation_id']) self::handleBothTeamsHaveNoFormation($websoccer, $db, $homeTeam, $guestTeam, $match);
		elseif (!$matchinfo['home_formation_id']) {
			self::handleOneTeamHasNoFormation($websoccer, $db, $homeTeam, $match);
			self::addPlayers($websoccer, $db, $match->guestTeam, $matchinfo, 'guest');
			self::addSubstitution($websoccer, $db, $match->guestTeam, $matchinfo, 'guest'); }
		elseif (!$matchinfo['guest_formation_id']) {
			self::handleOneTeamHasNoFormation($websoccer, $db, $guestTeam, $match);
			self::addPlayers($websoccer, $db, $match->homeTeam, $matchinfo, 'home');
			self::addSubstitution($websoccer, $db, $match->homeTeam, $matchinfo, 'home'); }
		else {
			self::addPlayers($websoccer, $db, $match->homeTeam, $matchinfo, 'home');
			self::addPlayers($websoccer, $db, $match->guestTeam, $matchinfo, 'guest');
			self::addSubstitution($websoccer, $db, $match->homeTeam, $matchinfo, 'home');
			self::addSubstitution($websoccer, $db, $match->guestTeam, $matchinfo, 'guest'); }
		return $match; }
	static function addPlayers($websoccer, $db, SimulationTeam $team, $matchinfo, $columnPrefix) {
		$fromTable = $websoccer->getConfig('db_prefix') . '_spieler';
		$columns['verein_id'] = 'team_id';
		$columns['nation'] = 'nation';
		$columns['position'] = 'position';
		$columns['position_main'] = 'mainPosition';
		$columns['position_second'] = 'secondPosition';
		$columns['vorname'] = 'firstName';
		$columns['nachname'] = 'lastName';
		$columns['kunstname'] = 'pseudonym';
		$columns['w_staerke'] = 'strength';
		$columns['w_technik'] = 'technique';
		$columns['w_kondition'] = 'stamina';
		$columns['w_frische'] = 'freshness';
		$columns['w_zufriedenheit'] = 'satisfaction';
		$columns['st_spiele'] = 'matches_played';
		if ($websoccer->getConfig('players_aging') == 'birthday') $ageColumn = 'TIMESTAMPDIFF(YEAR,geburtstag,CURDATE())';
		else $ageColumn = 'age';
		$columns[$ageColumn] = 'age';
		$whereCondition = 'id = %d AND verletzt = 0';
		if ($team->isNationalTeam) $whereCondition .= ' AND gesperrt_nationalteam = 0';
		elseif ($matchinfo['type'] == 'Pokalspiel') $whereCondition .= ' AND gesperrt_cups = 0';
		elseif ($matchinfo['type'] != 'Freundschaft') $whereCondition .= ' AND gesperrt = 0';
		$positionMapping = SimulationHelper::getPositionsMapping();
		$addedPlayers = 0;
		for ($playerNo = 1; $playerNo <= 11; $playerNo++) {
			$playerId = $matchinfo[$columnPrefix . '_formation_player' . $playerNo];
			$mainPosition =  $matchinfo[$columnPrefix . '_formation_player_pos_' . $playerNo];
			$result = $db->querySelect($columns, $fromTable, $whereCondition, $playerId);
			$playerinfo = $result->fetch_array();
			if (isset($playerinfo['team_id']) && $playerinfo['team_id'] == $team->id || $team->isNationalTeam && $playerinfo['nation'] == $team->name) {
				$position = $positionMapping[$mainPosition];
				$strength = $playerinfo['strength'];
				if ($playerinfo['position'] != $position && $playerinfo['mainPosition'] != $mainPosition && $playerinfo['secondPosition'] != $mainPosition) $strength = round($strength * (1 - $websoccer->getConfig('sim_strength_reduction_wrongposition') / 100));
				elseif (strlen($playerinfo['mainPosition']) && $playerinfo['mainPosition'] != $mainPosition && ($playerinfo['position'] == $position || $playerinfo['secondPosition'] == $mainPosition)) {
					$strength = round($strength * (1 - $websoccer->getConfig('sim_strength_reduction_secondary') / 100)); }
				$player = new SimulationPlayer($playerId, $team, $position, $mainPosition, 3.0, $playerinfo['age'], $strength, $playerinfo['technique'], $playerinfo['stamina'], $playerinfo['freshness'], $playerinfo['satisfaction']);
				if (strlen($playerinfo['pseudonym'])) $player->name = $playerinfo['pseudonym'];
				else $player->name = $playerinfo['firstName'] . ' ' . $playerinfo['lastName'];
				$team->positionsAndPlayers[$player->position][] = $player;
				SimulationStateHelper::createSimulationRecord($websoccer, $db, $matchinfo['match_id'], $player);
				$addedPlayers++;
				if ($matchinfo[$columnPrefix . '_captain_id'] == $playerId) self::computeMorale($player, $playerinfo['matches_played']);
				if ($matchinfo[$columnPrefix . '_formation_freekickplayer'] == $playerId) $team->freeKickPlayer = $player; }}
		if ($addedPlayers < 11 && $websoccer->getConfig('sim_createformation_on_invalidsubmission')) {
			$db->queryDelete($websoccer->getConfig('db_prefix') . '_spiel_berechnung', 'spiel_id = %d AND team_id = %d', array($matchinfo['match_id'], $team->id));
			$team->positionsAndPlayers = array();
			SimulationFormationHelper::generateNewFormationForTeam($websoccer, $db, $team, $matchinfo['match_id']);
			$team->noFormationSet = TRUE;
			return; }
		for ($playerNo = 1; $playerNo <= 5; $playerNo++) {
			$playerId = $matchinfo[$columnPrefix . '_formation_bench' . $playerNo];
			$result = $db->querySelect($columns, $fromTable, $whereCondition, $playerId);
			$playerinfo = $result->fetch_array();
			if (isset($playerinfo['team_id']) && $playerinfo['team_id'] == $team->id || $team->isNationalTeam && $playerinfo['nation'] == $team->name) {
				$player = new SimulationPlayer($playerId, $team, $playerinfo['position'], $playerinfo['mainPosition'], 3.0, $playerinfo['age'], $playerinfo['strength'], $playerinfo['technique'], $playerinfo['stamina'],
					$playerinfo['freshness'], $playerinfo['satisfaction']);
				if (strlen($playerinfo['pseudonym'])) $player->name = $playerinfo['pseudonym'];
				else $player->name = $playerinfo['firstName'] . ' ' . $playerinfo['lastName'];
				$team->playersOnBench[$playerId] = $player;
				SimulationStateHelper::createSimulationRecord($websoccer, $db, $matchinfo['match_id'], $player, TRUE); }}}
	static function addSubstitution($websoccer, $db, SimulationTeam $team, $matchinfo, $columnPrefix) {
		for ($subNo = 1; $subNo <= 3; $subNo++) {
			if ($matchinfo[$columnPrefix . '_formation_sub' . $subNo . '_out']) {
				$out = $matchinfo[$columnPrefix . '_formation_sub' . $subNo . '_out'];
				$in = $matchinfo[$columnPrefix . '_formation_sub' . $subNo . '_in'];
				$minute = $matchinfo[$columnPrefix . '_formation_sub' . $subNo . '_minute'];
				$condition = $matchinfo[$columnPrefix . '_formation_sub' . $subNo . '_condition'];
				$position = $matchinfo[$columnPrefix . '_formation_sub' . $subNo . '_position'];
				if (isset($team->playersOnBench[$in])) {
					$playerIn = $team->playersOnBench[$in];
					$playerOut = self::findPlayerOnField($team, $out);
					if ($playerIn && $playerOut) {
						$sub = new SimulationSubstitution($minute, $playerIn, $playerOut, $condition, $position);
						$team->substitutions[] = $sub; }}}}}
	function findPlayerOnField(SimulationTeam $team, $playerId) {
		foreach ($team->positionsAndPlayers as $position => $players) {
			foreach ($players as $player) {
				if ($player->id == $playerId) return $player; }}
		return false; }
	static function teamHasNoManager($websoccer, $db, $teamId) {
		$columns = 'user_id';
		$fromTable = $websoccer->getConfig('db_prefix') . '_verein';
		$whereCondition = 'id = %d';
		$parameters = $teamId;
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters);
		$teaminfo = $result->fetch_array();
		return !(isset($teaminfo['user_id']) && $teaminfo['user_id']); }
	static function computeMorale(SimulationPlayer $captain, $matchesPlayed) {
		$morale = 0;
		$morale += ($captain->age - 16) * 5;
		$morale += min(40, round($matchesPlayed / 2));
		$morale = $morale * $captain->strength / 100;
		$morale = min(100, max(0, $morale));
		$captain->team->morale = $morale; }}
class ModuleConfigHelper {
	static function findDependentEntities($dbtable) {
		$modules = scandir(FOLDER_MODULES);
		$entities = array();
		foreach ($modules as $module) {
			if (is_dir(FOLDER_MODULES .'/'. $module)) {
				$files = scandir(FOLDER_MODULES .'/'. $module);
				foreach ($files as $file) {
					$pathToFile = FOLDER_MODULES .'/'. $module .'/' . $file;
					if ($file == MODULE_CONFIG_FILENAME) self::_findDependentEntity($entities, $pathToFile, $dbtable); }}}
		return $entities; }
	static function findModuleConfigAsXmlObject($moduleName) {
		$pathToFile = FOLDER_MODULES .'/'. $moduleName .'/'. MODULE_CONFIG_FILENAME;
		if (!file_exists($pathToFile)) throw new Exception('Config file for module \''. $moduleName . '\' not found.');
		return simplexml_load_file($pathToFile); }
	static function removeAliasFromDbTableName($tableName) {
		$spaceTablePos = strrpos($tableName, ' ');
		return ($spaceTablePos) ? substr($tableName, 0, strpos($tableName, ' ')) : $tableName; }
	static function _findDependentEntity(&$entities, $pathToFile, $dbtable) {
		$xml = simplexml_load_file($pathToFile);
		$foundFields = $xml->xpath('//field[@jointable = \''. $dbtable . '\']');
		if ($foundFields) {
			foreach ($foundFields as $field) {
				$entity = $field->xpath('../..');
				$entities[] = array('dbtable' => self::removeAliasFromDbTableName((string) $entity[0]->attributes()->dbtable), 'columnid' => (string) $field->attributes()->id, 'cascade' => (string) $field->attributes()->cascade); }}}}
class NavigationBuilder {
	static function getNavigationItems($website, $i18n, $pages, $currentPageId) {
		$items = array();
		$addedItemsCache = array();
		foreach ($pages as $pageId => $pageJson) self::_createItem($items, $addedItemsCache, $pageId, $pageJson, $website, $i18n, $currentPageId, $pages);
		usort($items, array('NavigationBuilder', 'sortByWeight'));
		foreach ($items as $item) {
			if ($item->children != null) usort($item->children, array('NavigationBuilder', 'sortByWeight')); }
		return $items; }
	static function _createItem(&$items, &$addedItemsCache, $pageId, $pageJson, $website, $i18n, $currentPageId, &$pages) {
		if (isset($addedItemsCache[$pageId])) return;
		$pageConfig = json_decode($pageJson, TRUE);
		$requiredRoles = explode(',', $pageConfig['role']);
		if (!in_array($website->getUser()->getRole(), $requiredRoles)) return;
		if (isset($pageConfig['parentItem']) && strlen($pageConfig['parentItem']) && !isset($addedItemsCache[$pageConfig['parentItem']])) {
			self::_createItem($items, $addedItemsCache, $pageConfig['parentItem'], $pages[$pageConfig['parentItem']], $website, $i18n, $currentPageId, $pages); }
		$isActive = ($currentPageId == $pageId);
		if ($isActive && isset($pageConfig['parentItem']) && strlen($pageConfig['parentItem']) && isset($addedItemsCache[$pageConfig['parentItem']])) $addedItemsCache[$pageConfig['parentItem']]->isActive = TRUE;
		if (!isset($pageConfig['navitem']) || $pageConfig['navitem'] != 'true') return;
		if (isset($pageConfig['navitemOnlyForConfigEnabled']) && !$website->getConfig($pageConfig['navitemOnlyForConfigEnabled'])) return;
		$itemWeight = (isset($pageConfig['navweight']) && strlen($pageConfig['navweight'])) ? $pageConfig['navweight'] : 0;
		$item = new NavigationItem($pageId, $i18n->getNavigationLabel($pageId), array(), $isActive, $itemWeight);
		$itemParent = (isset($pageConfig['parentItem']) && strlen($pageConfig['parentItem'])) ? $pageConfig['parentItem'] : null;
		self::_addToItems($items, $addedItemsCache, $item, $itemWeight, $itemParent); }
	static function _addToItems(&$items, &$addedItemsCache, $item, $itemWeight, $itemParent) {
		$listToAdd = &$items;
		if ($itemParent != null) $listToAdd = &$addedItemsCache[$itemParent]->children;
		$addedItemsCache[$item->pageId] = $item;
		$listToAdd[] = $item; }
	static function sortByWeight($a, $b) { return $a->weight - $b->weight; }}
class NavigationItem {
	public $pageId;
	public $label;
	public $children;
	public $isActive;
	public $weight;
	function __construct($pageId, $label, $children, $isActive, $weight) {
		$this->pageId = $pageId;
		$this->label = $label;
		$this->children = $children;
		$this->isActive = $isActive;
		$this->weight = $weight; }}
class PageIdRouter {
	static function getTargetPageId($websoccer, $i18n, $requestedPageId) {
		$pageId = $requestedPageId;
		if ($pageId == NULL) $pageId = DEFAULT_PAGE_ID;
		$user = $websoccer->getUser();
		if ($websoccer->getConfig('password_protected') && $user->getRole() == ROLE_GUEST) {
			$freePageIds = array(LOGIN_PAGE_ID, 'register', 'register-success', 'activate-user', 'forgot-password', 'imprint', 'logout', 'termsandconditions');
			if (!$websoccer->getConfig('password_protected_startpage')) $freePageIds[] = DEFAULT_PAGE_ID;
			if (!in_array($pageId, $freePageIds)) {
				$websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_WARNING, $i18n->getMessage('requireslogin_box_title'), $i18n->getMessage('requireslogin_box_message')));
				$pageId = LOGIN_PAGE_ID; }}
		if ($pageId == 'team' && $websoccer->getRequestParameter('id') == null) $pageId = 'leagues';
		if ($user->getRole() == ROLE_USER && !strlen($user->username)) $pageId = ENTERUSERNAME_PAGE_ID;
		return $pageId; }}
class Paginator {
	public $pages;
	public $pageNo;
	public $eps;
	private $_parameters;
	function getQueryString() {
		if ($this->_parameters != null) return http_build_query($this->_parameters);
		return ''; }
	function addParameter($name, $value) { $this->_parameters[$name] = $value; }
	function __construct($hits, $eps, $websoccer) {
		$this->eps = $eps;
		$this->pageNo = max(1, (int) $websoccer->getRequestParameter(PARAM_PAGENUMBER));
		if ($hits % $eps) $this->pages = floor($hits / $eps) + 1;
		else $this->pages = $hits / $eps; }
	function getFirstIndex() { return ($this->pageNo - 1) * $this->eps; }}
class PluginMediator {
	private static $_eventlistenerConfigs;
	static function dispatchEvent(AbstractEvent $event) {
		if (self::$_eventlistenerConfigs == null) {
			include(CONFIGCACHE_EVENTS);
			if (isset($eventlistener)) self::$_eventlistenerConfigs = $eventlistener;
			else self::$_eventlistenerConfigs = array(); }
		if (!count(self::$_eventlistenerConfigs)) return;
		$eventName = get_class($event);
		if (!isset(self::$_eventlistenerConfigs[$eventName])) return;
		$eventListeners = self::$_eventlistenerConfigs[$eventName];
		foreach ($eventListeners as $listenerConfigStr) {
			$listenerConfig = json_decode($listenerConfigStr, TRUE);
			if (method_exists($listenerConfig['class'], $listenerConfig['method'])) call_user_func($listenerConfig['class'] . '::' . $listenerConfig['method'], $event);
			else throw new Exception('Configured event listener must have function: ' . $listenerConfig['class'] . '::' . $listenerConfig['method']); }}}
class ScheduleGenerator {
	static function createRoundRobinSchedule($teamIds) {
		shuffle($teamIds);
		$noOfTeams = count($teamIds);
		if ($noOfTeams % 2 !== 0) {
			$teamIds[] = DUMMY_TEAM_ID;
			$noOfTeams++; }
		$noOfMatchDays = $noOfTeams - 1;
		$sortedMatchdays = array();
		foreach ($teamIds as $teamId) $sortedMatchdays[1][] = $teamId;
		for ($matchdayNo = 2; $matchdayNo <= $noOfMatchDays; $matchdayNo++) {
			$rowCenterWithoutFixedEnd = $noOfTeams / 2 - 1;
			for ($teamIndex = 0; $teamIndex < $rowCenterWithoutFixedEnd; $teamIndex++) {
				$targetIndex = $teamIndex + $noOfTeams / 2;
				$sortedMatchdays[$matchdayNo][] = $sortedMatchdays[$matchdayNo - 1][$targetIndex]; }
			for ($teamIndex = $rowCenterWithoutFixedEnd; $teamIndex < $noOfTeams - 1; $teamIndex++) {
				$targetIndex = 0 + $teamIndex - $rowCenterWithoutFixedEnd;
				$sortedMatchdays[$matchdayNo][] = $sortedMatchdays[$matchdayNo - 1][$targetIndex]; }
			$sortedMatchdays[$matchdayNo][] = $teamIds[count($teamIds) - 1]; }
		$schedule = array();
		$matchesNo = $noOfTeams / 2;
		for ($matchDayNo = 1; $matchDayNo <= $noOfMatchDays; $matchDayNo++) {
			$matches = array();
			for ($teamNo = 1; $teamNo <= $matchesNo; $teamNo++) {
				$homeTeam = $sortedMatchdays[$matchDayNo][$teamNo - 1];
				$guestTeam = $sortedMatchdays[$matchDayNo][count($teamIds) - $teamNo];
				if ($homeTeam == DUMMY_TEAM_ID || $guestTeam == DUMMY_TEAM_ID) continue;
				if ($teamNo === 1 && $matchDayNo % 2 == 0) {
					$swapTemp = $homeTeam;
					$homeTeam = $guestTeam;
					$guestTeam = $swapTemp; }
				$match = array($homeTeam, $guestTeam);
				$matches[] = $match; }
			$schedule[$matchDayNo] = $matches; }
		return $schedule; }}
class SecurityUtil {
	static function hashPassword($password, $salt) { return hash('sha256', $salt . hash('sha256', $password)); }
	static function isAdminLoggedIn() {
		if (isset($_SESSION['HTTP_USER_AGENT'])) {
			if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT'])) {
				self::logoutAdmin();
				return FALSE; }}
		else $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
	    return (isset($_SESSION['valid']) && $_SESSION['valid']); }
	static function logoutAdmin() {
	    $_SESSION = array();
	    session_destroy(); }
	static function generatePassword() {
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789%!=?';
		return substr(str_shuffle($chars), 0, 8); }
	static function generatePasswordSalt() { return substr(self::generatePassword(), 0, 4); }
	static function generateSessionToken($userId, $salt) {
		$useragent = (isset($_SESSION['HTTP_USER_AGENT'])) ? $_SESSION['HTTP_USER_AGENT'] : 'n.a.';
		return md5($salt . $useragent . $userId); }
	static function loginFrontUserUsingApplicationSession($websoccer, $userId) {
		$_SESSION['frontuserid'] = $userId;
		session_regenerate_id();
		$userProvider = new SessionBasedUserAuthentication($websoccer);
		$userProvider->verifyAndUpdateCurrentUser($websoccer->getUser()); }}
class SessionBasedUserAuthentication {
	private $_website;
	function __construct($website) { $this->_website = $website; }
	function verifyAndUpdateCurrentUser(User $currentUser) {
		$db = DbConnection::getInstance();
		$fromTable = $this->_website->getConfig('db_prefix') .'_user';
		if (!isset($_SESSION[SESSION_PARAM_USERID]) || !$_SESSION[SESSION_PARAM_USERID]) {
			$rememberMe = CookieHelper::getCookieValue('user');
			if ($rememberMe != null) {
				$columns = 'id, passwort_salt, nick, email, lang';
				$whereCondition = 'status = 1 AND tokenid = \'%s\'';
				$result = $db->querySelect($columns, $fromTable, $whereCondition, $rememberMe);
				$rememberedUser = $result->fetch_array();
				if (isset($rememberedUser['id'])) {
					$currentToken = SecurityUtil::generateSessionToken($rememberedUser['id'], $rememberedUser['passwort_salt']);
					if ($currentToken === $rememberMe) {
						$this->_login($rememberedUser, $db, $fromTable, $currentUser);
						return; }
					else {
						CookieHelper::destroyCookie('user');
						$columns = array('tokenid' => '');
						$whereCondition = 'id = %d';
						$parameter = $rememberedUser['id'];
						$db->queryUpdate($columns, $fromTable, $whereCondition, $parameter); }}
				else CookieHelper::destroyCookie('user'); }
			else return; }
		$userid = (isset($_SESSION[SESSION_PARAM_USERID])) ? $_SESSION[SESSION_PARAM_USERID] : 0;
		if (!$userid) return;
		$columns = 'id, nick, email, lang, premium_balance, picture';
		$whereCondition = 'status = 1 AND id = %d';
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $userid);
		if ($result->num_rows) {
			$userdata = $result->fetch_array();
			$this->_login($userdata, $db, $fromTable, $currentUser); }
		else $this->logoutUser($currentUser); }
	function logoutUser(User $currentUser) {
		if ($currentUser->getRole() == ROLE_USER) {
			$currentUser->id = null;
			$currentUser->username = null;
			$currentUser->email = null;
			$_SESSION = array();
			session_destroy();
			CookieHelper::destroyCookie('user'); }}
	function _login($userdata, $db, $fromTable, $currentUser) {
		$_SESSION[SESSION_PARAM_USERID] = $userdata['id'];
		$currentUser->id = $userdata['id'];
		$currentUser->username = $userdata['nick'];
		$currentUser->email = $userdata['email'];
		$currentUser->lang = $userdata['lang'];
		$currentUser->premiumBalance = $userdata['premium_balance'];
		$currentUser->setProfilePicture($this->_website, $userdata['picture']);
		$i18n = I18n::getInstance($this->_website->getConfig('supported_languages'));
		$i18n->setCurrentLanguage($userdata['lang']);
		$columns = array('lastonline' => $this->_website->getNowAsTimestamp(), 'lastaction' => $this->_website->getRequestParameter('page'));
		$whereCondition = 'id = %d';
		$parameter = $userdata['id'];
		$db->queryUpdate($columns, $fromTable, $whereCondition, $parameter); }}
class SimulationAudienceCalculator {
	static function computeAndSaveAudience($websoccer, $db, SimulationMatch $match) {
		$homeInfo = self::getHomeInfo($websoccer, $db, $match->homeTeam->id);
		if (!$homeInfo) return;
		$isAttractiveMatch = FALSE;
		if ($match->type == 'Pokalspiel') $isAttractiveMatch = TRUE;
		elseif ($match->type == 'Ligaspiel') {
			$tcolumns = 'sa_punkte';
			$fromTable = $websoccer->getConfig('db_prefix') . '_verein';
			$whereCondition = 'id = %d';
			$result = $db->querySelect($tcolumns, $fromTable, $whereCondition, $match->homeTeam->id);
			$home = $result->fetch_array();
			$result = $db->querySelect($tcolumns, $fromTable, $whereCondition, $match->guestTeam->id);
			$guest = $result->fetch_array();
			if (abs($home['sa_punkte'] - $guest['sa_punkte']) <= 9) $isAttractiveMatch = TRUE; }
		$maintenanceInfluence = $homeInfo['level_videowall'] * $websoccer->getConfig('stadium_videowall_effect');
		$maintenanceInfluenceSeats = (5 - $homeInfo['level_seatsquality']) * $websoccer->getConfig('stadium_seatsquality_effect');
		$maintenanceInfluenceVip = (5 - $homeInfo['level_vipquality']) * $websoccer->getConfig('stadium_vipquality_effect');
		$rateStands = self::computeRate($homeInfo['avg_price_stands'], $homeInfo['avg_sales_stands'], $homeInfo['price_stands'], $homeInfo['popularity'], $isAttractiveMatch, $maintenanceInfluence);
		$rateSeats = self::computeRate($homeInfo['avg_price_seats'], $homeInfo['avg_sales_seats'], $homeInfo['price_seats'], $homeInfo['popularity'], $isAttractiveMatch, $maintenanceInfluence - $maintenanceInfluenceSeats);
		$rateStandsGrand = self::computeRate($homeInfo['avg_price_stands'] * 1.2, $homeInfo['avg_sales_stands_grand'], $homeInfo['price_stands_grand'], $homeInfo['popularity'], $isAttractiveMatch, $maintenanceInfluence);
		$rateSeatsGrand = self::computeRate($homeInfo['avg_price_seats'] * 1.2, $homeInfo['avg_sales_seats_grand'], $homeInfo['price_seats_grand'], $homeInfo['popularity'], $isAttractiveMatch, $maintenanceInfluence - $maintenanceInfluenceSeats);
		$rateVip = self::computeRate($homeInfo['avg_price_vip'], $homeInfo['avg_sales_vip'], $homeInfo['price_vip'], $homeInfo['popularity'], $isAttractiveMatch, $maintenanceInfluence - $maintenanceInfluenceVip);
		$event = new TicketsComputedEvent($websoccer, $db, I18n::getInstance($websoccer->getConfig('supported_languages')), $match, $homeInfo['stadium_id'], $rateStands, $rateSeats, $rateStandsGrand, $rateSeatsGrand, $rateVip);
		PluginMediator::dispatchEvent($event);
		if ($rateStands == 1 && $rateSeats == 1 && $rateStandsGrand == 1 && $rateSeatsGrand == 1 && $rateVip == 1) $match->isSoldOut = TRUE;
		$tickets_stands = min(1, max(0, $rateStands)) * $homeInfo['places_stands'];
		$tickets_seats = min(1, max(0, $rateSeats)) * $homeInfo['places_seats'];
		$tickets_stands_grand = min(1, max(0, $rateStandsGrand)) * $homeInfo['places_stands_grand'];
		$tickets_seats_grand = min(1, max(0, $rateSeatsGrand)) * $homeInfo['places_seats_grand'];
		$tickets_vip = min(1, max(0, $rateVip)) * $homeInfo['places_vip'];
		$columns['last_steh'] = $tickets_stands;
		$columns['last_sitz'] = $tickets_seats;
		$columns['last_haupt_steh'] = $tickets_stands_grand;
		$columns['last_haupt_sitz'] = $tickets_seats_grand;
		$columns['last_vip'] = $tickets_vip;
		$fromTable = $websoccer->getConfig('db_prefix') . '_verein';
		$whereCondition = 'id = %d';
		$db->queryUpdate($columns, $fromTable, $whereCondition, $match->homeTeam->id);
		$mcolumns['zuschauer'] = $tickets_stands + $tickets_seats + $tickets_stands_grand + $tickets_seats_grand + $tickets_vip;
		$fromTable = $websoccer->getConfig('db_prefix') . '_spiel';
		$db->queryUpdate($mcolumns, $fromTable, $whereCondition, $match->id);
		$revenue = $tickets_stands * $homeInfo['price_stands'];
		$revenue += $tickets_seats * $homeInfo['price_seats'];
		$revenue += $tickets_stands_grand * $homeInfo['price_stands_grand'];
		$revenue += $tickets_seats_grand * $homeInfo['price_seats_grand'];
		$revenue += $tickets_vip * $homeInfo['price_vip'];
		BankAccountDataService::creditAmount($websoccer, $db, $match->homeTeam->id, $revenue, 'match_ticketrevenue_subject', 'match_ticketrevenue_sender');
		self::weakenPlayersDueToGrassQuality($websoccer, $homeInfo, $match);
		self::updateMaintenanceStatus($websoccer, $db, $homeInfo); }
	static function getHomeInfo($websoccer, $db, $teamId) {
		$fromTable = $websoccer->getConfig('db_prefix') . '_verein AS T';
		$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') . '_stadion AS S ON S.id = T.stadion_id';
		$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') . '_liga AS L ON L.id = T.liga_id';
		$fromTable .= ' LEFT JOIN ' . $websoccer->getConfig('db_prefix') . '_user AS U ON U.id = T.user_id';
		$whereCondition = 'T.id = %d';
		$columns['S.id'] = 'stadium_id';
		$columns['S.p_steh'] = 'places_stands';
		$columns['S.p_sitz'] = 'places_seats';
		$columns['S.p_haupt_steh'] = 'places_stands_grand';
		$columns['S.p_haupt_sitz'] = 'places_seats_grand';
		$columns['S.p_vip'] = 'places_vip';
		$columns['S.level_pitch'] = 'level_pitch';
		$columns['S.level_videowall'] = 'level_videowall';
		$columns['S.level_seatsquality'] = 'level_seatsquality';
		$columns['S.level_vipquality'] = 'level_vipquality';
		$columns['S.maintenance_pitch'] = 'maintenance_pitch';
		$columns['S.maintenance_videowall'] = 'maintenance_videowall';
		$columns['S.maintenance_seatsquality'] = 'maintenance_seatsquality';
		$columns['S.maintenance_vipquality'] = 'maintenance_vipquality';
		$columns['U.fanbeliebtheit'] = 'popularity';
		$columns['T.preis_stehen'] = 'price_stands';
		$columns['T.preis_sitz'] = 'price_seats';
		$columns['T.preis_haupt_stehen'] = 'price_stands_grand';
		$columns['T.preis_haupt_sitze'] = 'price_seats_grand';
		$columns['T.preis_vip'] = 'price_vip';
		$columns['L.p_steh'] = 'avg_sales_stands';
		$columns['L.p_sitz'] = 'avg_sales_seats';
		$columns['L.p_haupt_steh'] = 'avg_sales_stands_grand';
		$columns['L.p_haupt_sitz'] = 'avg_sales_seats_grand';
		$columns['L.p_vip'] = 'avg_sales_vip';
		$columns['L.preis_steh'] = 'avg_price_stands';
		$columns['L.preis_sitz'] = 'avg_price_seats';
		$columns['L.preis_vip'] = 'avg_price_vip';
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $teamId);
		$record = $result->fetch_array();
		return $record; }
	static function computeRate($avgPrice, $avgSales, $actualPrice, $fanpopularity, $isAttractiveMatch, $maintenanceInfluence) {
		$rate = 100 - pow((10 / (2.5 * $avgPrice) * $actualPrice), 2);
		$deviation = $avgSales - (100 - pow((10 / (2.5 * $avgPrice) * $avgPrice), 2));
		$rate = $rate + $deviation;
		if ($rate > 0) $rate = $rate - 10 + 1/5 * $fanpopularity;
		if ($isAttractiveMatch) $rate = $rate * 1.1;
		if ($rate > 0) $rate = $rate + $maintenanceInfluence;
		return min(100, max(0, $rate)) / 100; }
	static function updateMaintenanceStatus($websoccer, $db, $homeInfo) {
		$columns = array( 'maintenance_pitch' => $homeInfo['maintenance_pitch'] - 1, 'maintenance_videowall' => $homeInfo['maintenance_videowall'] - 1, 'maintenance_seatsquality' => $homeInfo['maintenance_seatsquality'] - 1,
			'maintenance_vipquality' => $homeInfo['maintenance_vipquality'] - 1 );
		$types = array('pitch', 'videowall', 'seatsquality', 'vipquality');
		foreach ($types as $type) {
			if ($columns['maintenance_' . $type] <= 0) {
				$columns['maintenance_' . $type] = $websoccer->getConfig('stadium_maintenanceinterval_' . $type);
				$columns['level_' . $type] = max(0, $homeInfo['level_' . $type] - 1); }}
		$db->queryUpdate($columns, $websoccer->getConfig('db_prefix') . '_stadion', 'id = %d', $homeInfo['stadium_id']); }
	static function weakenPlayersDueToGrassQuality($websoccer, $homeInfo, SimulationMatch $match) {
		$strengthChange = (5 - $homeInfo['level_pitch']) * $websoccer->getConfig('stadium_pitch_effect');
		if ($strengthChange && $match->type != 'Freundschaft') {
			$playersAndPositions = $match->homeTeam->positionsAndPlayers;
			foreach ($playersAndPositions as $positions => $players) {
				foreach ($players as $player) $player->strengthTech = max(1, $player->strengthTech - $strengthChange); }}}}
class SimulationCupMatchHelper {
	static function checkIfExtensionIsRequired($websoccer, $db, SimulationMatch $match) {
		if (strlen($match->cupRoundGroup)) return FALSE;
		$columns['home_tore'] = 'home_goals';
		$columns['gast_tore'] = 'guest_goals';
		$columns['berechnet'] = 'is_simulated';
		$whereCondition = 'home_verein = %d AND gast_verein = %d AND pokalname = \'%s\' AND pokalrunde = \'%s\'';
		$result = $db->querySelect($columns, $websoccer->getConfig('db_prefix') . '_spiel', $whereCondition, array($match->guestTeam->id, $match->homeTeam->id, $match->cupName, $match->cupRoundName), 1);
		$otherRound = $result->fetch_array();
		if (isset($otherRound['is_simulated']) && !$otherRound['is_simulated']) return FALSE;
		if (!$otherRound) {
			if ($match->homeTeam->getGoals() == $match->guestTeam->getGoals()) return TRUE;
			elseif ($match->homeTeam->getGoals() > $match->guestTeam->getGoals()) {
				self::createNextRoundMatchAndPayAwards($websoccer, $db, $match->homeTeam->id, $match->guestTeam->id, $match->cupName, $match->cupRoundName);
				return FALSE; }
			else {
				self::createNextRoundMatchAndPayAwards($websoccer, $db, $match->guestTeam->id, $match->homeTeam->id, $match->cupName, $match->cupRoundName);
				return FALSE; }}
		$totalHomeGoals = $match->homeTeam->getGoals();
		$totalGuestGoals = $match->guestTeam->getGoals();
		$totalHomeGoals += $otherRound['guest_goals'];
		$totalGuestGoals += $otherRound['home_goals'];
		$winnerTeam = null;
		$loserTeam = null;
		if ($totalHomeGoals > $totalGuestGoals) {
			$winnerTeam =  $match->homeTeam;
			$loserTeam =  $match->guestTeam; }
		elseif ($totalHomeGoals < $totalGuestGoals) {
			$winnerTeam =  $match->guestTeam;
			$loserTeam =  $match->homeTeam; }
		else {
			if ($otherRound['guest_goals'] > $match->guestTeam->getGoals()) {
				$winnerTeam =  $match->homeTeam;
				$loserTeam =  $match->guestTeam; }
			elseif ($otherRound['guest_goals'] < $match->guestTeam->getGoals()) {
				$winnerTeam =  $match->guestTeam;
				$loserTeam =  $match->homeTeam; }
			else return TRUE; }
		self::createNextRoundMatchAndPayAwards($websoccer, $db, $winnerTeam->id, $loserTeam->id, $match->cupName, $match->cupRoundName);
		return FALSE; }
	static function createNextRoundMatchAndPayAwards($websoccer, $db, $winnerTeamId, $loserTeamId, $cupName, $cupRound) {
		$columns['C.id'] = 'cup_id';
		$columns['C.winner_award'] = 'cup_winner_award';
		$columns['C.second_award'] = 'cup_second_award';
		$columns['C.perround_award'] = 'cup_perround_award';
		$columns['R.id'] = 'round_id';
		$columns['R.finalround'] = 'is_finalround';
		$fromTable = $websoccer->getConfig('db_prefix') . '_cup_round AS R';
		$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') . '_cup AS C ON C.id = R.cup_id';
		$result = $db->querySelect($columns, $fromTable, 'C.name = \'%s\' AND R.name = \'%s\'', array($cupName, $cupRound), 1);
		$round = $result->fetch_array();
		if (!$round) return;
		if ($round['cup_perround_award']) {
			BankAccountDataService::creditAmount($websoccer, $db, $winnerTeamId, $round['cup_perround_award'], 'cup_cuproundaward_perround_subject', $cupName);
			BankAccountDataService::creditAmount($websoccer, $db, $loserTeamId, $round['cup_perround_award'], 'cup_cuproundaward_perround_subject', $cupName); }
		$result = $db->querySelect('user_id', $websoccer->getConfig('db_prefix') . '_verein', 'id = %d', $winnerTeamId);
		$winnerclub = $result->fetch_array();
		$result = $db->querySelect('user_id', $websoccer->getConfig('db_prefix') . '_verein', 'id = %d', $loserTeamId);
		$loserclub = $result->fetch_array();
		$now = $websoccer->getNowAsTimestamp();
		if ($winnerclub['user_id']) $db->queryInsert(array('user_id' => $winnerclub['user_id'], 'team_id' => $winnerTeamId, 'cup_round_id' => $round['round_id'], 'date_recorded' => $now ), $websoccer->getConfig('db_prefix') .'_achievement');
		if ($loserclub['user_id']) $db->queryInsert(array('user_id' => $loserclub['user_id'], 'team_id' => $loserTeamId, 'cup_round_id' => $round['round_id'], 'date_recorded' => $now ), $websoccer->getConfig('db_prefix') .'_achievement');
		if ($round['is_finalround']) {
			if ($round['cup_winner_award']) BankAccountDataService::creditAmount($websoccer, $db, $winnerTeamId, $round['cup_winner_award'], 'cup_cuproundaward_winner_subject', $cupName);
			if ($round['cup_second_award']) BankAccountDataService::creditAmount($websoccer, $db, $loserTeamId, $round['cup_second_award'], 'cup_cuproundaward_second_subject', $cupName);
			$db->queryUpdate(array('winner_id' => $winnerTeamId), $websoccer->getConfig('db_prefix') . '_cup', 'id = %d', $round['cup_id']);
			if ($winnerclub['user_id']) BadgesDataService::awardBadgeIfApplicable($websoccer, $db, $winnerclub['user_id'], 'cupwinner'); }
		else {
			$columns = 'id,firstround_date,secondround_date,name';
			$fromTable = $websoccer->getConfig('db_prefix') . '_cup_round';
			$result = $db->querySelect($columns, $fromTable, 'from_winners_round_id = %d', $round['round_id'], 1);
			$winnerRound = $result->fetch_array();
			if (isset($winnerRound['id'])) self::createMatchForTeamAndRound($websoccer, $db, $winnerTeamId, $winnerRound['id'], $winnerRound['firstround_date'], $winnerRound['secondround_date'], $cupName, $winnerRound['name']);
			$result = $db->querySelect($columns, $fromTable, 'from_loosers_round_id = %d', $round['round_id'], 1);
			$loserRound = $result->fetch_array();
			if (isset($loserRound['id'])) self::createMatchForTeamAndRound($websoccer, $db, $loserTeamId, $loserRound['id'], $loserRound['firstround_date'], $loserRound['secondround_date'], $cupName, $loserRound['name']);
		}
	}
	static function checkIfMatchIsLastMatchOfGroupRoundAndCreateFollowingMatches($websoccer, $db, SimulationMatch $match) {
		if (!strlen($match->cupRoundGroup)) return;
		$result = $db->querySelect('COUNT(*) AS hits', $websoccer->getConfig('db_prefix') . '_spiel', 'berechnet = \'0\' AND pokalname = \'%s\' AND pokalrunde = \'%s\' AND id != %d', array($match->cupName, $match->cupRoundName, $match->id));
		$openMatches = $result->fetch_array();
		if (isset($openMatches['hits']) && $openMatches['hits']) return;
		$columns = array();
		$columns['N.cup_round_id'] = 'round_id';
		$columns['N.groupname'] = 'groupname';
		$columns['N.rank'] = 'rank';
		$columns['N.target_cup_round_id'] = 'target_cup_round_id';
		$fromTable = $websoccer->getConfig('db_prefix') . '_cup_round_group_next AS N';
		$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') . '_cup_round AS R ON N.cup_round_id = R.id';
		$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') . '_cup AS C ON R.cup_id = C.id';
		$result = $db->querySelect($columns, $fromTable, 'C.name = \'%s\' AND R.name = \'%s\'', array($match->cupName, $match->cupRoundName));
		$nextConfigs = array();
		while ($nextConfig = $result->fetch_array()) {
			$nextConfigs[$nextConfig['groupname']]['' . $nextConfig['rank']] = $nextConfig['target_cup_round_id'];
			$roundId = $nextConfig['round_id']; }
		$nextRoundTeams = array();
		foreach ($nextConfigs as $groupName => $rankings) {
			$teamsInGroup = CupsDataService::getTeamsOfCupGroupInRankingOrder($websoccer, $db, $roundId, $groupName);
			for ($teamRank = 1; $teamRank <= count($teamsInGroup); $teamRank++) {
				$configIndex = '' . $teamRank;
				if (isset($rankings[$configIndex])) {
					$team = $teamsInGroup[$teamRank - 1];
					$targetRound = $rankings[$configIndex];
					$nextRoundTeams[$targetRound][] = $team['id']; }}}
		$matchTable = $websoccer->getConfig('db_prefix') . '_spiel';
		$type = 'Pokalspiel';
		foreach ($nextRoundTeams as $nextRoundId => $teamIds) {
			$result = $db->querySelect('name,firstround_date,secondround_date', $websoccer->getConfig('db_prefix') . '_cup_round', 'id = %d', $nextRoundId);
			$roundInfo = $result->fetch_array();
			if (!$roundInfo) continue;
			$teams = $teamIds;
			shuffle($teams);
			while (count($teams) > 1) {
				$homeTeam = array_pop($teams);
				$guestTeam = array_pop($teams);
				$db->queryInsert(array('spieltyp' => $type, 'pokalname' => $match->cupName, 'pokalrunde' => $roundInfo['name'], 'home_verein' => $homeTeam, 'gast_verein' => $guestTeam, 'datum' => $roundInfo['firstround_date'] ), $matchTable);
				if ($roundInfo['secondround_date'])$db->queryInsert(array('spieltyp'=> $type,'pokalname'=> $match->cupName,'pokalrunde'=> $roundInfo['name'],'home_verein'=> $guestTeam,'gast_verein'=> $homeTeam,'datum'=> $roundInfo['secondround_date'] ),$matchTable); }}}
	static function createMatchForTeamAndRound($websoccer, $db, $teamId, $roundId, $firstRoundDate, $secondRoundDate, $cupName, $cupRound) {
		$pendingTable = $websoccer->getConfig('db_prefix') . '_cup_round_pending';
		$result = $db->querySelect('team_id', $pendingTable, 'cup_round_id = %d', $roundId, 1);
		$opponent = $result->fetch_array();
		if (!$opponent) $db->queryInsert(array('team_id' => $teamId, 'cup_round_id' => $roundId), $pendingTable);
		else {
			$matchTable = $websoccer->getConfig('db_prefix') . '_spiel';
			$type = 'Pokalspiel';
			if (SimulationHelper::selectItemFromProbabilities(array(1 => 50, 0 => 50))) {
				$homeTeam = $teamId;
				$guestTeam = $opponent['team_id']; }
			else {
				$homeTeam = $opponent['team_id'];
				$guestTeam = $teamId; }
			$db->queryInsert(array('spieltyp' => $type, 'pokalname' => $cupName, 'pokalrunde' => $cupRound, 'home_verein' => $homeTeam, 'gast_verein' => $guestTeam, 'datum' => $firstRoundDate ), $matchTable);
			if ($secondRoundDate) $db->queryInsert(array('spieltyp' => $type, 'pokalname' => $cupName, 'pokalrunde' => $cupRound, 'home_verein' => $guestTeam, 'gast_verein' => $homeTeam, 'datum' => $secondRoundDate ), $matchTable);
			$db->queryDelete($pendingTable, 'team_id = %d AND cup_round_id = %d', array($opponent['team_id'], $roundId)); }}}
class SimulationFormationHelper {
	static function generateNewFormationForTeam($websoccer, $db, SimulationTeam $team, $matchId) {
		$columns['id'] = 'id';
		$columns['position'] = 'position';
		$columns['position_main'] = 'mainPosition';
		$columns['vorname'] = 'firstName';
		$columns['nachname'] = 'lastName';
		$columns['kunstname'] = 'pseudonym';
		$columns['w_staerke'] = 'strength';
		$columns['w_technik'] = 'technique';
		$columns['w_kondition'] = 'stamina';
		$columns['w_frische'] = 'freshness';
		$columns['w_zufriedenheit'] = 'satisfaction';
		if ($websoccer->getConfig('players_aging') == 'birthday') $ageColumn = 'TIMESTAMPDIFF(YEAR,geburtstag,CURDATE())';
		else $ageColumn = 'age';
		$columns[$ageColumn] = 'age';
		if (!$team->isNationalTeam) {
			$fromTable = $websoccer->getConfig('db_prefix') . '_spieler';
			$whereCondition = 'verein_id = %d AND verletzt = 0 AND gesperrt = 0 AND status = 1 ORDER BY w_frische DESC';
			$parameters = $team->id;
			$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters); }
		else {
			$columnsStr = '';
			$firstColumn = TRUE;
			foreach($columns as $dbName => $aliasName) {
				if (!$firstColumn) $columnsStr = $columnsStr .', ';
				else $firstColumn = FALSE;
				$columnsStr = $columnsStr . $dbName. ' AS '. $aliasName; }
			$nation = $db->connection->escape_string($team->name);
			$dbPrefix = $websoccer->getConfig('db_prefix');
			$queryStr = '(SELECT ' . $columnsStr . ' FROM ' . $dbPrefix . '_spieler WHERE nation = \''. $nation . '\' AND position = \'Torwart\' ORDER BY w_staerke DESC, w_frische DESC LIMIT 1)';
			$queryStr .= ' UNION ALL (SELECT ' . $columnsStr . ' FROM ' . $dbPrefix . '_spieler WHERE nation = \''. $nation . '\' AND position = \'Abwehr\' ORDER BY w_staerke DESC, w_frische DESC LIMIT 4)';
			$queryStr .= ' UNION ALL (SELECT ' . $columnsStr . ' FROM ' . $dbPrefix . '_spieler WHERE nation = \''. $nation . '\' AND position = \'Mittelfeld\' ORDER BY w_staerke DESC, w_frische DESC LIMIT 4)';
			$queryStr .= ' UNION ALL (SELECT ' . $columnsStr . ' FROM ' . $dbPrefix . '_spieler WHERE nation = \''. $nation . '\' AND position = \'Sturm\' ORDER BY w_staerke DESC, w_frische DESC LIMIT 2)';
			$result = $db->executeQuery($queryStr); }
		$lvExists = FALSE;
		$rvExists = FALSE;
		$lmExists = FALSE;
		$rmExists = FALSE;
		$ivPlayers = 0;
		$zmPlayers = 0;
		while ($playerinfo = $result->fetch_array()) {
			$position = $playerinfo['position'];
			if ($position == PLAYER_POSITION_GOALY && isset($team->positionsAndPlayers[PLAYER_POSITION_GOALY]) && count($team->positionsAndPlayers[PLAYER_POSITION_GOALY]) == 1 || $position == PLAYER_POSITION_DEFENCE &&
				isset($team->positionsAndPlayers[PLAYER_POSITION_DEFENCE]) && count($team->positionsAndPlayers[PLAYER_POSITION_DEFENCE]) >= 4 || $position == PLAYER_POSITION_MIDFIELD && isset($team->positionsAndPlayers[PLAYER_POSITION_MIDFIELD])
				&& count($team->positionsAndPlayers[PLAYER_POSITION_MIDFIELD]) >= 4 || $position == PLAYER_POSITION_STRIKER && isset($team->positionsAndPlayers[PLAYER_POSITION_STRIKER]) && count($team->positionsAndPlayers[PLAYER_POSITION_STRIKER]) >= 2) {
				continue; }
			$mainPosition = $playerinfo['mainPosition'];
			if ($mainPosition == 'LV') {
				if ($lvExists) {
					$mainPosition = 'IV';
					$ivPlayers++;
					if ($ivPlayers == 3) {
						$mainPosition = 'RV';
						$rvExists = TRUE; }}
				else $lvExists = TRUE; }
			elseif ($mainPosition == 'RV') {
				if ($rvExists) {
					$mainPosition = 'IV';
					$ivPlayers++;
					if ($ivPlayers == 3) {
						$mainPosition = 'LV';
						$lvExists = TRUE; }}
				else $rvExists = TRUE; }
			elseif ($mainPosition == 'IV') {
				$ivPlayers++;
				if ($ivPlayers == 3) {
					if (!$rvExists) {
						$mainPosition = 'RV';
						$rvExists = TRUE; }
					else {
						$mainPosition = 'LV';
						$lvExists = TRUE; }}}
			elseif ($mainPosition == 'LM') {
				if ($lmExists) {
					$mainPosition = 'ZM';
					$zmPlayers++; }
				else $lmExists = TRUE; }
			elseif ($mainPosition == 'RM') {
				if ($rmExists) {
					$mainPosition = 'ZM';
					$zmPlayers++; }
				else $rmExists = TRUE; }
			elseif ($mainPosition == 'LS' || $mainPosition == 'RS') $mainPosition = 'MS';
			elseif ($mainPosition == 'ZM') {
				$zmPlayers++;
				if ($zmPlayers > 2) $mainPosition = 'DM'; }
			$player = new SimulationPlayer($playerinfo['id'], $team, $position, $mainPosition, 3.0, $playerinfo['age'], $playerinfo['strength'], $playerinfo['technique'], $playerinfo['stamina'], $playerinfo['freshness'], $playerinfo['satisfaction']);
			if (strlen($playerinfo['pseudonym'])) $player->name = $playerinfo['pseudonym'];
			else $player->name = $playerinfo['firstName'] . ' ' . $playerinfo['lastName'];
			$team->positionsAndPlayers[$player->position][] = $player;
			SimulationStateHelper::createSimulationRecord($websoccer, $db, $matchId, $player); }}}
class SimulationHelper {
	static function selectItemFromProbabilities($probabilities) {
		$magicNo = self::getMagicNumber();
		$oldBoundary = 0;
		foreach ($probabilities as $key => $probability) {
			$newBounday = $oldBoundary + $probability;
			if ($magicNo > $oldBoundary && $magicNo <= $newBounday) return $key;
			$oldBoundary = $newBounday; }
		return end($probabilities); }
	static function getMagicNumber($min = 1, $max = 100) {
		if ($min == $max) return $min;
		return mt_rand($min, $max); }
	static function selectPlayer($team, $position, $excludePlayer = null) {
		$players = array();
		if (isset($team->positionsAndPlayers[$position])) {
			if ($excludePlayer == null || $excludePlayer->position != $position) $players = $team->positionsAndPlayers[$position];
			else {
				foreach ($team->positionsAndPlayers[$position] as $player) {
					if ($player->id !== $excludePlayer->id) $players[] = $player; }}}
		$noOfPlayers = count($players);
		if ($noOfPlayers < 1) {
			if ($position == PLAYER_POSITION_STRIKER) return self::selectPlayer($team, PLAYER_POSITION_MIDFIELD, $excludePlayer);
			elseif ($position == PLAYER_POSITION_MIDFIELD) return self::selectPlayer($team, PLAYER_POSITION_DEFENCE, $excludePlayer);
			elseif ($position == PLAYER_POSITION_DEFENCE) return self::selectPlayer($team, PLAYER_POSITION_GOALY, $excludePlayer);
			foreach ($team->positionsAndPlayers as $pposition => $pplayers) {
				foreach ($pplayers as $player) {
					if ($player->id !== $excludePlayer->id) return $player; }}}
		$player = $players[SimulationHelper::getMagicNumber(0, $noOfPlayers - 1)];
		return $player; }
	static function getOpponentTeam($player, $match) { return ($match->homeTeam->id == $player->team->id) ? $match->guestTeam : $match->homeTeam; }
	static function getOpponentTeamOfTeam($team, $match) { return ($match->homeTeam->id == $team->id) ? $match->guestTeam : $match->homeTeam; }
	static function checkAndExecuteSubstitutions(SimulationMatch $match, SimulationTeam $team, $observers) {
		$substitutions = $team->substitutions;
		//- owsPro - Fatal error: Uncaught TypeError: count(): Argument #1 ($value) must be of type Countable|array, null given
		//- if (!count($substitutions)) {
		if (!count((array)$substitutions)) return;
		foreach ($substitutions as $substitution) {
			if ($substitution->minute == $match->minute && !isset($team->removedPlayers[$substitution->playerOut->id]) && isset($team->playersOnBench[$substitution->playerIn->id])) {
				if ($substitution->condition == SUB_CONDITION_TIE && $match->homeTeam->getGoals() != $match->guestTeam->getGoals()
						|| $substitution->condition == SUB_CONDITION_LEADING && $team->getGoals() <= self::getOpponentTeamOfTeam($team, $match)->getGoals()
						|| $substitution->condition == SUB_CONDITION_DEFICIT && $team->getGoals() >= self::getOpponentTeamOfTeam($team, $match)->getGoals()) {
					$substitution->minute = 999;
					continue; }
				$team->removePlayer($substitution->playerOut);
				if (strlen($substitution->position)) $mainPosition = $substitution->position;
				elseif (strlen($substitution->playerIn->mainPosition) && $substitution->playerIn->mainPosition != "-") $mainPosition = $substitution->playerIn->mainPosition;
				else $mainPosition = NULL;
				if ($mainPosition == NULL) $position = $substitution->playerIn->position;
				else {
					$positionMapping = self::getPositionsMapping();
					$position = $positionMapping[$mainPosition]; }
				$strength = $substitution->playerIn->strength;
				if ($position != $substitution->playerIn->position) $strength = round($strength * (1 - WebSoccer::getInstance()->getConfig("sim_strength_reduction_wrongposition") / 100));
				elseif ($mainPosition != NULL && $mainPosition != $substitution->playerIn->mainPosition) $strength = round($strength * (1 - WebSoccer::getInstance()->getConfig("sim_strength_reduction_secondary") / 100));
				$substitution->playerIn->position = $position;
				$substitution->playerIn->strength = $strength;
				$substitution->playerIn->mainPosition = $mainPosition;
				$team->positionsAndPlayers[$substitution->playerIn->position][] = $substitution->playerIn;
				unset($team->playersOnBench[$substitution->playerIn->id]);
				foreach ($observers as $observer) $observer->onSubstitution($match, $substitution); }}}
	static function createUnplannedSubstitutionForPlayer($minute, SimulationPlayer $playerOut) {
		$team = $playerOut->team;
		if (count($team->playersOnBench) < 1) return FALSE;
		$position = $playerOut->position;
		$player = self::selectPlayerFromBench($team->playersOnBench, $position);
		if ($player == NULL && $position == PLAYER_POSITION_STRIKER) {
			$player = self::selectPlayerFromBench($team->playersOnBench, PLAYER_POSITION_MIDFIELD);
			if ($player == NULL) $player = self::selectPlayerFromBench($team->playersOnBench, PLAYER_POSITION_DEFENCE); }
		else if ($player == NULL && $position == PLAYER_POSITION_MIDFIELD) {
			$player = self::selectPlayerFromBench($team->playersOnBench, PLAYER_POSITION_DEFENCE);
			if ($player == NULL) $player = self::selectPlayerFromBench($team->playersOnBench, PLAYER_POSITION_STRIKER); }
		else if ($player == NULL && $position == PLAYER_POSITION_DEFENCE) {
			$player = self::selectPlayerFromBench($team->playersOnBench, PLAYER_POSITION_MIDFIELD);
			if ($player == NULL) $player = self::selectPlayerFromBench($team->playersOnBench, PLAYER_POSITION_STRIKER); }
		if ($player == NULL) return FALSE;
		$newsub = new SimulationSubstitution($minute, $player, $playerOut);
		return self::addUnplannedSubstitution($minute, $newsub); }
	static function getPlayersForPenaltyShooting(SimulationTeam $team) {
		$players = array();
		$goalkeeper = null;
		foreach($team->positionsAndPlayers as $position => $playersAtPosition) {
			if ($position == PLAYER_POSITION_GOALY && count($playersAtPosition)) {
				$goalkeeper = $playersAtPosition[0];
				continue; }
			$players = array_merge($players, $playersAtPosition); }
		usort($players, array("SimulationHelper", "sortByStrength"));
		if ($goalkeeper != null) $players[] = $goalkeeper;
		return $players; }
	static function selectPlayerFromBench(&$players, $position) {
		foreach ($players as $player) {
			if ($player->position == $position) return $player; }
		return NULL; }
	static function addUnplannedSubstitution($minute, SimulationSubstitution $substitution) {
		$team = $substitution->playerIn->team;
		if (!isset($team->playersOnBench[$substitution->playerIn->id]) || $team->playersOnBench[$substitution->playerIn->id] == null) return FALSE;
		if (count($team->substitutions) < 3) {
			$team->substitutions[] = $substitution;
			return TRUE; }
		$index = 0;
		foreach ($team->substitutions as $existingSub) {
			if ($existingSub->minute > $minute && $existingSub->playerIn->id == $substitution->playerIn->id) {
				$team->substitutions[$index] = $substitution;
				return TRUE; }
			$index++; }
		$index = 0;
		foreach ($team->substitutions as $existingSub) {
			if ($existingSub->minute > $minute) {
				$team->substitutions[$index] = $substitution;
				return TRUE; }
			$index++; }
		return FALSE; }
	static function sortByStrength(SimulationPlayer $a, SimulationPlayer $b) { return $b->strength - $a->strength; }
	static function getPositionsMapping() {return array('T'=> 'Torwart','LV'=> 'Abwehr','IV'=> 'Abwehr','RV'=> 'Abwehr','DM'=> 'Mittelfeld','OM'=> 'Mittelfeld','ZM'=> 'Mittelfeld','LM'=> 'Mittelfeld','RM'=> 'Mittelfeld','LS'=> 'Sturm','MS'=> 'Sturm','RS'=> 'Sturm'); }}
class SimulationMatch {
	public $id;
	public $type;
	public $homeTeam;
	public $guestTeam;
	public $minute;
	public $penaltyShootingEnabled;
	public $isCompleted;
	public $cupName;
	public $cupRoundName;
	public $cupRoundGroup;
	public $isSoldOut;
	private $playerWithBall;
	private $previousPlayerWithBall;
	public $isAtForeignStadium;
    function __construct($id, $homeTeam, $guestTeam, $minute, $playerWithBall = null, $previousPlayerWithBall = null) {
    	$this->id = $id;
    	$this->homeTeam = $homeTeam;
    	$this->guestTeam = $guestTeam;
    	$this->minute = $minute;
    	$this->playerWithBall = $playerWithBall;
    	$this->previousPlayerWithBall = $previousPlayerWithBall;
    	$this->isCompleted = FALSE;
    	$this->penaltyShootingEnabled = FALSE;
    	$this->isSoldOut = FALSE; }
    function getPlayerWithBall() { return $this->playerWithBall; }
    function getPreviousPlayerWithBall() { return $this->previousPlayerWithBall; }
    function setPreviousPlayerWithBall($player) { $this->previousPlayerWithBall = $player; }
    function setPlayerWithBall($player) {
    	if ($this->playerWithBall !== NULL && $this->playerWithBall->id !== $player->id) {
    		$player->setBallContacts($player->getBallContacts() + 1);
    		$this->previousPlayerWithBall = $this->playerWithBall; }
    	$this->playerWithBall = $player; }
    function cleanReferences() {
    	$this->homeTeam->cleanReferences();
    	$this->guestTeam->cleanReferences();
    	unset($this->homeTeam);
    	unset($this->guestTeam);
    	unset($this->playerWithBall);
    	unset($this->previousPlayerWithBall); }}
class SimulationPlayer {
	public $id;
	public $team;
	public $name;
	public $position;
	public $mainPosition;
	public $age;
	public $strength;
	public $strengthTech;
	public $strengthStamina;
	public $strengthFreshness;
	public $strengthSatisfaction;
	public $yellowCards;
	public $redCard;
	public $injured;
	public $blocked;
	public $goals;
	private $minutesPlayed;
	private $totalStrength;
	private $mark;
	private $ballContacts;
	private $wonTackles;
	private $lostTackles;
	private $shoots;
	private $passesSuccessed;
	private $passesFailed;
	private $assists;
	private $needsStrengthRecomputation;
    function __construct($id, $team, $position, $mainPosition, $mark, $age, $strength, $strengthTech, $strengthStamina, $strengthFreshness, $strengthSatisfaction) {
    	$this->id = $id;
    	$this->team = $team;
    	$this->position = $position;
    	$this->mainPosition = $mainPosition;
    	$this->mark = $mark;
    	$this->age = $age;
    	$this->strength = $strength;
    	$this->strengthTech = $strengthTech;
    	$this->strengthStamina = $strengthStamina;
    	$this->strengthFreshness = $strengthFreshness;
    	$this->strengthSatisfaction = $strengthSatisfaction;
    	$this->injured = 0;
    	$this->blocked = 0;
    	$this->goals = 0;
    	$this->minutesPlayed = 0;
    	$this->ballContacts = 0;
    	$this->wonTackles = 0;
    	$this->lostTackles = 0;
    	$this->shoots = 0;
    	$this->passesSuccessed = 0;
    	$this->passesFailed = 0;
    	$this->assists = 0; }
    function getTotalStrength($websoccer, SimulationMatch $match) {
    	if ($this->totalStrength == null || $this->needsStrengthRecomputation == TRUE) $this->recomputeTotalStrength($websoccer,$match);
    	return $this->totalStrength; }
    function getMark() { return $this->mark; }
    function setMark($mark) {
    	if ($this->mark !== $mark) {
    		$this->mark = $mark;
    		$this->needsStrengthRecomputation = TRUE; }}
    function improveMark($improvement) {
    	$newMark = max((float) $this->mark - $improvement, 1);
    	$this->setMark($newMark); }
    function downgradeMark($downgrade) {
    	$newMark = min((float) $this->mark + $downgrade, 6);
    	$this->setMark($newMark); }
    function recomputeTotalStrength($websoccer, SimulationMatch $match) {
    	$mainStrength = $this->strength;
    	if ($match->isSoldOut && $this->team->id == $match->homeTeam->id) $mainStrength += $websoccer->getConfig("sim_home_field_advantage");
    	if ($this->team->noFormationSet) $mainStrength = round($mainStrength * $websoccer->getConfig("sim_createformation_strength") / 100);
    	$weightsSum = $websoccer->getConfig("sim_weight_strength") + $websoccer->getConfig("sim_weight_strengthTech") + $websoccer->getConfig("sim_weight_strengthStamina") + $websoccer->getConfig("sim_weight_strengthFreshness")
    		+ $websoccer->getConfig("sim_weight_strengthSatisfaction");
    	$totalStrength = $mainStrength * $websoccer->getConfig("sim_weight_strength");
    	$totalStrength += $this->strengthTech * $websoccer->getConfig("sim_weight_strengthTech");
    	$totalStrength += $this->strengthStamina * $websoccer->getConfig("sim_weight_strengthStamina");
    	$totalStrength += $this->strengthFreshness * $websoccer->getConfig("sim_weight_strengthFreshness");
    	$totalStrength += $this->strengthSatisfaction * $websoccer->getConfig("sim_weight_strengthSatisfaction");
    	$totalStrength = $totalStrength / $weightsSum;
    	$totalStrength = $totalStrength * (114 - 4 * $this->mark) / 100;
    	$this->totalStrength = min(100, round($totalStrength));
    	$this->needsStrengthRecomputation = FALSE; }
    function getWonTackles() { return $this->wonTackles; }
    function setWonTackles($wonTackles) { if ($this->wonTackles !== $wonTackles) $this->wonTackles = $wonTackles; }
    function getLostTackles() { return $this->lostTackles; }
    function setLostTackles($lostTackles) { if ($this->lostTackles !== $lostTackles) $this->lostTackles = $lostTackles; }
    function getPassesSuccessed() { return $this->passesSuccessed; }
    function setPassesSuccessed($passesSuccessed) { if ($this->passesSuccessed !== $passesSuccessed) $this->passesSuccessed = $passesSuccessed; }
    function getPassesFailed() { return $this->passesFailed; }
    function setPassesFailed($passesFailed) { if ($this->passesFailed !== $passesFailed) $this->passesFailed = $passesFailed; }
    function getShoots() { return $this->shoots; }
    function setShoots($shoots) { if ($this->shoots !== $shoots) $this->shoots = $shoots; }
    function getBallContacts() { return $this->ballContacts; }
    function setBallContacts($ballContacts) { if ($this->ballContacts !== $ballContacts) $this->ballContacts = $ballContacts; }
    function getGoals() { return $this->goals; }
    function setGoals($goals) { if ($this->goals !== $goals) $this->goals = $goals; }
    function getAssists() { return $this->assists; }
    function setAssists($assists) { if ($this->assists !== $assists) $this->assists = $assists; }
    function getMinutesPlayed() { return $this->minutesPlayed; }
    function setMinutesPlayed($minutesPlayed, $recomputeFreshness = TRUE) {
    	if ($this->minutesPlayed < $minutesPlayed) {
    		$this->minutesPlayed = $minutesPlayed;
    		if ($recomputeFreshness && $minutesPlayed % 20 == 0) {
     			if ($minutesPlayed == 20 && $this->position == PLAYER_POSITION_GOALY) {
    				$this->strengthFreshness = max(1, $this->strengthFreshness - 1);
    				$this->needsStrengthRecomputation = TRUE; }
    			elseif ($this->position != PLAYER_POSITION_GOALY) $this->looseFreshness(); }}}
    function cleanReferences() { unset($this->team); }
    function looseFreshness() {
    	$freshness = $this->strengthFreshness - 1;
    	if ($this->age > 32 && $this->position != PLAYER_POSITION_GOALY) $freshness -= 1;
    	if ($this->team->offensive >= 80 && ($this->position == PLAYER_POSITION_MIDFIELD || $this->position == PLAYER_POSITION_STRIKER)) $freshness -= 1;
    	if ($this->strengthStamina < 40) $freshness -= 1;
    	$freshness = max(1, $freshness);
    	$this->strengthFreshness = $freshness;
    	$this->needsStrengthRecomputation = TRUE; }
    function __toString() {
    	return "{id: ". $this->id .", team: ". $this->team->id . ", position: ". $this->position .", mark: ". $this->mark .", strength: " . $this->strength . ", strengthTech: " . $this->strengthTech . ", strengthStamina: " . $this->strengthStamina . ",
    		strengthFreshness: " . $this->strengthFreshness . ", strengthSatisfaction: " . $this->strengthSatisfaction . "}"; }}
class SimulationStateHelper {
	private static $_addedPlayers;
	static function createSimulationRecord($websoccer, $db, $matchId, SimulationPlayer $player, $onBench = FALSE) {
		$fromTable = $websoccer->getConfig('db_prefix') . '_spiel_berechnung';
		$db->queryInsert(self::getPlayerColumns($matchId, $player, ($onBench) ? 'Ersatzbank' : '1'), $fromTable); }
	static function updateState($websoccer, $db, SimulationMatch $match) {
		self::updateMatch($websoccer, $db, $match);
		self::updateTeamState($websoccer, $db, $match, $match->homeTeam);
		self::updateTeamState($websoccer, $db, $match, $match->guestTeam); }
	static function loadMatchState($websoccer, $db, $matchinfo) {
		$homeTeam = new SimulationTeam($matchinfo['home_id']);
		$guestTeam = new SimulationTeam($matchinfo['guest_id']);
		self::loadTeam($websoccer, $db, $matchinfo['match_id'], $homeTeam);
		self::loadTeam($websoccer, $db, $matchinfo['match_id'], $guestTeam);
		$homeTeam->setGoals($matchinfo['home_goals']);
		$homeTeam->offensive = $matchinfo['home_offensive'];
		$homeTeam->isNationalTeam = $matchinfo['home_nationalteam'];
		$homeTeam->isManagedByInterimManager = $matchinfo['home_interimmanager'];
		$homeTeam->noFormationSet = $matchinfo['home_noformation'];
		$homeTeam->setup = $matchinfo['home_setup'];
		$homeTeam->name = $matchinfo['home_name'];
		$homeTeam->longPasses = $matchinfo['home_longpasses'];
		$homeTeam->counterattacks = $matchinfo['home_counterattacks'];
		$homeTeam->morale = $matchinfo['home_morale'];
		$guestTeam->setGoals($matchinfo['guest_goals']);
		$guestTeam->offensive = $matchinfo['guest_offensive'];
		$guestTeam->isNationalTeam = $matchinfo['guest_nationalteam'];
		$guestTeam->isManagedByInterimManager = $matchinfo['guest_interimmanager'];
		$guestTeam->noFormationSet = $matchinfo['guest_noformation'];
		$guestTeam->setup = $matchinfo['guest_setup'];
		$guestTeam->name = $matchinfo['guest_name'];
		$guestTeam->longPasses = $matchinfo['guest_longpasses'];
		$guestTeam->counterattacks = $matchinfo['guest_counterattacks'];
		$guestTeam->morale = $matchinfo['guest_morale'];
		$match = new SimulationMatch($matchinfo['match_id'], $homeTeam, $guestTeam, $matchinfo['minutes']);
		$match->type = $matchinfo['type'];
		$match->penaltyShootingEnabled = $matchinfo['penaltyshooting'];
		$match->isSoldOut = $matchinfo['soldout'];
		$match->cupName = $matchinfo['cup_name'];
		$match->cupRoundName = $matchinfo['cup_roundname'];
		$match->cupRoundGroup = $matchinfo['cup_groupname'];
		$match->isAtForeignStadium = ($matchinfo['custom_stadium_id']) ? TRUE : FALSE;
		if ($matchinfo['player_with_ball'] && isset(self::$_addedPlayers[$matchinfo['player_with_ball']])) $match->setPlayerWithBall(self::$_addedPlayers[$matchinfo['player_with_ball']]);
		if ($matchinfo['prev_player_with_ball'] && isset(self::$_addedPlayers[$matchinfo['prev_player_with_ball']])) $match->setPreviousPlayerWithBall(self::$_addedPlayers[$matchinfo['prev_player_with_ball']]);
		if ($matchinfo['home_freekickplayer'] && isset(self::$_addedPlayers[$matchinfo['home_freekickplayer']])) $homeTeam->freeKickPlayer = self::$_addedPlayers[$matchinfo['home_freekickplayer']];
		if ($matchinfo['guest_freekickplayer'] && isset(self::$_addedPlayers[$matchinfo['guest_freekickplayer']])) $guestTeam->freeKickPlayer = self::$_addedPlayers[$matchinfo['guest_freekickplayer']];
		for ($subNo = 1; $subNo <= 3; $subNo++) {
			if ($matchinfo['home_sub_' . $subNo . '_out'] && isset(self::$_addedPlayers[$matchinfo['home_sub_' . $subNo . '_in']]) && isset(self::$_addedPlayers[$matchinfo['home_sub_' . $subNo . '_out']])) {
				$sub = new SimulationSubstitution($matchinfo['home_sub_' . $subNo . '_minute'], self::$_addedPlayers[$matchinfo['home_sub_' . $subNo . '_in']], self::$_addedPlayers[$matchinfo['home_sub_' . $subNo . '_out']],
					$matchinfo['home_sub_' . $subNo . '_condition'], $matchinfo['home_sub_' . $subNo . '_position']);
				$homeTeam->substitutions[] = $sub; }
			if ($matchinfo['guest_sub_' . $subNo . '_out'] && isset(self::$_addedPlayers[$matchinfo['guest_sub_' . $subNo . '_in']]) && isset(self::$_addedPlayers[$matchinfo['guest_sub_' . $subNo . '_out']])) {
				$sub = new SimulationSubstitution($matchinfo['guest_sub_' . $subNo . '_minute'], self::$_addedPlayers[$matchinfo['guest_sub_' . $subNo . '_in']], self::$_addedPlayers[$matchinfo['guest_sub_' . $subNo . '_out']],
					$matchinfo['guest_sub_' . $subNo . '_condition'], $matchinfo['guest_sub_' . $subNo . '_position']);
				$guestTeam->substitutions[] = $sub; }}
		self::$_addedPlayers = null;
		return $match; }
	static function updateMatch($websoccer, $db, SimulationMatch $match) {
		if ($match->isCompleted) $columns['berechnet'] = 1;
		$columns['minutes'] = $match->minute;
		$columns['soldout'] = ($match->isSoldOut) ? '1' : '0';
		$columns['home_tore'] = $match->homeTeam->getGoals();
		$columns['gast_tore'] = $match->guestTeam->getGoals();
		$columns['home_setup'] = $match->homeTeam->setup;
		$columns['gast_setup'] = $match->guestTeam->setup;
		$columns['home_offensive'] = $match->homeTeam->offensive;
		$columns['gast_offensive'] = $match->guestTeam->offensive;
		$columns['home_noformation'] = ($match->homeTeam->noFormationSet) ? '1' : '0';
		$columns['guest_noformation'] = ($match->guestTeam->noFormationSet) ? '1' : '0';
		$columns['home_longpasses'] = ($match->homeTeam->longPasses) ? '1' : '0';
		$columns['gast_longpasses'] = ($match->guestTeam->longPasses) ? '1' : '0';
		$columns['home_counterattacks'] = ($match->homeTeam->counterattacks) ? '1' : '0';
		$columns['gast_counterattacks'] = ($match->guestTeam->counterattacks) ? '1' : '0';
		$columns['home_morale'] = $match->homeTeam->morale;
		$columns['gast_morale'] = $match->guestTeam->morale;
		if ($match->getPlayerWithBall() != null) $columns['player_with_ball'] = $match->getPlayerWithBall()->id;
		else $columns['player_with_ball'] = 0;
		if ($match->getPreviousPlayerWithBall() != null) $columns['prev_player_with_ball'] = $match->getPreviousPlayerWithBall()->id;
		else $columns['prev_player_with_ball'] = 0;
		$columns['home_freekickplayer'] = ($match->homeTeam->freeKickPlayer != NULL) ? $match->homeTeam->freeKickPlayer->id : '';
		$columns['gast_freekickplayer'] = ($match->guestTeam->freeKickPlayer != NULL) ? $match->guestTeam->freeKickPlayer->id : '';
		if (is_array($match->homeTeam->substitutions)) {
			$subIndex = 1;
			foreach ($match->homeTeam->substitutions as $substitution) {
				$columns['home_w' . $subIndex . '_raus'] = $substitution->playerOut->id;
				$columns['home_w' . $subIndex . '_rein'] = $substitution->playerIn->id;
				$columns['home_w' . $subIndex . '_minute'] = $substitution->minute;
				$columns['home_w' . $subIndex . '_condition'] = $substitution->condition;
				$columns['home_w' . $subIndex . '_position'] = $substitution->position;
				$subIndex++; }}
		if (is_array($match->guestTeam->substitutions)) {
			$subIndex = 1;
			foreach ($match->guestTeam->substitutions as $substitution) {
				$columns['gast_w' . $subIndex . '_raus'] = $substitution->playerOut->id;
				$columns['gast_w' . $subIndex . '_rein'] = $substitution->playerIn->id;
				$columns['gast_w' . $subIndex . '_minute'] = $substitution->minute;
				$columns['gast_w' . $subIndex . '_condition'] = $substitution->condition;
				$columns['gast_w' . $subIndex . '_position'] = $substitution->position;
				$subIndex++;
			}
		}
		$fromTable = $websoccer->getConfig('db_prefix') . '_spiel';
		$whereCondition = 'id = %d';
		$parameters = $match->id;
		$db->queryUpdate($columns, $fromTable, $whereCondition, $parameters); }
	static function updateTeamState($websoccer, $db, SimulationMatch $match, SimulationTeam $team) {
		if (is_array($team->positionsAndPlayers)) {
			foreach ($team->positionsAndPlayers as $positions => $players) {
				foreach ($players as $player) self::updatePlayerState($websoccer, $db, $match->id, $player, '1'); }}
		if (is_array($team->playersOnBench)) {
			foreach ($team->playersOnBench as $player) self::updatePlayerState($websoccer, $db, $match->id, $player, 'Ersatzbank'); }
		if (is_array($team->removedPlayers)) {
			foreach ($team->removedPlayers as $player) self::updatePlayerState($websoccer, $db, $match->id, $player, 'Ausgewechselt'); }}
	static function updatePlayerState($websoccer, $db, $matchId, $player, $fieldArea) {
		$fromTable = $websoccer->getConfig('db_prefix') . '_spiel_berechnung';
		$whereCondition = 'spieler_id = %d AND spiel_id = %d';
		$parameters = array($player->id, $matchId);
		$columns = self::getPlayerColumns($matchId, $player, $fieldArea);
		$db->queryUpdate($columns, $fromTable, $whereCondition, $parameters); }
	static function getPlayerColumns($matchId, SimulationPlayer $player, $fieldArea) {
		$columns['spiel_id'] = $matchId;
		$columns['spieler_id'] = $player->id;
		$columns['team_id'] = $player->team->id;
		$columns['name'] = $player->name;
		$columns['note'] = $player->getMark();
		$columns['minuten_gespielt'] = $player->getMinutesPlayed();
		$columns['karte_gelb'] = $player->yellowCards;
		$columns['karte_rot'] = $player->redCard;
		$columns['verletzt'] = $player->injured;
		$columns['gesperrt'] = $player->blocked;
		$columns['tore'] = $player->getGoals();
		$columns['feld'] = $fieldArea;
		$columns['position'] = $player->position;
		$columns['position_main'] = $player->mainPosition;
		$columns['age'] = $player->age;
		$columns['w_staerke'] = $player->strength;
		$columns['w_technik'] = $player->strengthTech;
		$columns['w_kondition'] = $player->strengthStamina;
		$columns['w_frische'] = $player->strengthFreshness;
		$columns['w_zufriedenheit'] = $player->strengthSatisfaction;
		$columns['ballcontacts'] = $player->getBallContacts();
		$columns['wontackles'] = $player->getWonTackles();
		$columns['losttackles'] = $player->getLostTackles();
		$columns['shoots'] = $player->getShoots();
		$columns['passes_successed'] = $player->getPassesSuccessed();
		$columns['passes_failed'] = $player->getPassesFailed();
		$columns['assists'] = $player->getAssists();
		return $columns; }
	static function loadTeam($websoccer, $db, $matchId, SimulationTeam $team) {
		$columns['spieler_id'] = 'player_id';
		$columns['name'] = 'name';
		$columns['note'] = 'mark';
		$columns['minuten_gespielt'] = 'minutes_played';
		$columns['karte_gelb'] = 'yellow_cards';
		$columns['karte_rot'] = 'red_cards';
		$columns['verletzt'] = 'injured';
		$columns['gesperrt'] = 'blocked';
		$columns['tore'] = 'goals';
		$columns['feld'] = 'field_area';
		$columns['position'] = 'position';
		$columns['position_main'] = 'main_position';
		$columns['age'] = 'age';
		$columns['w_staerke'] = 'strength';
		$columns['w_technik'] = 'strength_tech';
		$columns['w_kondition'] = 'strength_stamina';
		$columns['w_frische'] = 'strength_freshness';
		$columns['w_zufriedenheit'] = 'strength_satisfaction';
		$columns['ballcontacts'] = 'ballcontacts';
		$columns['wontackles'] = 'wontackles';
		$columns['losttackles'] = 'losttackles';
		$columns['shoots'] = 'shoots';
		$columns['passes_successed'] = 'passes_successed';
		$columns['passes_failed'] = 'passes_failed';
		$columns['assists'] = 'assists';
		$fromTable = $websoccer->getConfig('db_prefix') . '_spiel_berechnung';
		$whereCondition = 'spiel_id = %d AND team_id = %d ORDER BY id ASC';
		$parameters = array($matchId, $team->id);
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters);
		while ($playerinfo = $result->fetch_array()) {
			$player = new SimulationPlayer($playerinfo['player_id'], $team, $playerinfo['position'], $playerinfo['main_position'], $playerinfo['mark'], $playerinfo['age'], $playerinfo['strength'], $playerinfo['strength_tech'],
				$playerinfo['strength_stamina'], $playerinfo['strength_freshness'], $playerinfo['strength_satisfaction']);
			$player->name = $playerinfo['name'];
			$player->setBallContacts($playerinfo['ballcontacts']);
			$player->setWonTackles($playerinfo['wontackles']);
			$player->setLostTackles($playerinfo['losttackles']);
			$player->setGoals($playerinfo['goals']);
			$player->setShoots($playerinfo['shoots']);
			$player->setPassesSuccessed($playerinfo['passes_successed']);
			$player->setPassesFailed($playerinfo['passes_failed']);
			$player->setAssists($playerinfo['assists']);
			$player->setMinutesPlayed($playerinfo['minutes_played'], FALSE);
			$player->yellowCards = $playerinfo['yellow_cards'];
			$player->redCard = $playerinfo['red_cards'];
			$player->injured = $playerinfo['injured'];
			$player->blocked = $playerinfo['blocked'];
			self::$_addedPlayers[$player->id] = $player;
			if ($playerinfo['field_area'] == 'Ausgewechselt') $team->removedPlayers[$player->id] = $player;
			elseif ($playerinfo['field_area'] == 'Ersatzbank') $team->playersOnBench[$player->id] = $player;
			else $team->positionsAndPlayers[$player->position][] = $player; }}}
class SimulationSubstitution {
	public $minute;
	public $playerIn;
	public $playerOut;
	public $condition;
	public $position;
    function __construct($minute, $playerIn, $playerOut, $condition = null, $position = null) {
    	$this->minute = $minute;
    	$this->playerIn = $playerIn;
    	$this->playerOut = $playerOut;
    	$this->position = $position;
    	if ($condition != null && in_array($condition, array(SUB_CONDITION_TIE, SUB_CONDITION_LEADING, SUB_CONDITION_DEFICIT))) $this->condition = $condition;
    	else $this->condition = null; }
    function __toString() { return '{minute: '. $this->minute . ', in: '. $this->playerIn .', out: '. $this->playerOut . '}'; }}
class SimulationTeam {
	public $id;
	public $name;
	public $isNationalTeam;
	public $isManagedByInterimManager;
	public $positionsAndPlayers;
	public $playersOnBench;
	public $offensive;
	public $setup;
	private $goals;
	public $substitutions;
	public $removedPlayers;
	public $noFormationSet;
	public $longPasses;
	public $counterattacks;
	public $morale;
	public $freeKickPlayer;
    function __construct($id, $offensive = null) {
    	$this->id = $id;
    	$this->offensive = $offensive;
    	$this->positionsAndPlayers[PLAYER_POSITION_GOALY] = array();
    	$this->positionsAndPlayers[PLAYER_POSITION_DEFENCE] = array();
    	$this->positionsAndPlayers[PLAYER_POSITION_MIDFIELD] = array();
    	$this->positionsAndPlayers[PLAYER_POSITION_STRIKER] = array();
    	$this->goals = 0;
    	$this->morale = 0;
    	$this->noFormationSet = FALSE;
    	$this->longPasses = FALSE;
    	$this->counterattacks = FALSE; }
    function getGoals() { return $this->goals; }
    function setGoals($goals) { if ($this->goals !== $goals) $this->goals = $goals; }
    function removePlayer($playerToRemove) {
    	$newPositionsAndAplayers = array();
    	$newPositionsAndAplayers[PLAYER_POSITION_GOALY] = array();
    	$newPositionsAndAplayers[PLAYER_POSITION_DEFENCE] = array();
    	$newPositionsAndAplayers[PLAYER_POSITION_MIDFIELD] = array();
    	$newPositionsAndAplayers[PLAYER_POSITION_STRIKER] = array();
    	foreach ($this->positionsAndPlayers as $position => $players) {
    		foreach ($players as $player) {
    			if ($player->id !== $playerToRemove->id) $newPositionsAndAplayers[$player->position][] = $player; }}
    	$this->positionsAndPlayers = $newPositionsAndAplayers;
    	$this->removedPlayers[$playerToRemove->id] = $playerToRemove;
    	if ($this->freeKickPlayer != NULL && $this->freeKickPlayer->id == $playerToRemove->id) $this->freeKickPlayer = NULL; }
    function computeTotalStrength($websoccer, SimulationMatch $match) {
    	$sum = 0;
    	foreach ($this->positionsAndPlayers as $position => $players) {
    		foreach ($players as $player) $sum += $player->getTotalStrength($websoccer, $match); }
    	return $sum; }
    function cleanReferences() {
    	unset($this->substitutions);
    	unset($this->positionsAndPlayers);
    	unset($this->playersOnBench);
    	unset($this->removedPlayers); }}
class Simulator {
	private $_simStrategy;
	private $_observers;
	function getSimulationStrategy() { return $this->_simStrategy; }
    function __construct($db, $websoccer) {
    	$strategyClass = $websoccer->getConfig('sim_strategy');
    	if (!class_exists($strategyClass)) throw new Exception('simulation strategy class not found: ' . $strategyClass);
    	$this->_websoccer = $websoccer;
    	$this->_db = $db;
    	$this->_simStrategy = new $strategyClass($websoccer);
    	$this->_simStrategy->attachObserver(new DefaultSimulationObserver());
    	$this->_observers = array(); }
    function attachObserver(ISimulatorObserver $observer) { $this->_observers[] = $observer; }
    function simulateMatch(SimulationMatch $match, $minutes) {
    	if ($match->minute == null) $match->minute = 0;
    	if ($match->minute == 0) {
	    	foreach ($this->_observers as $observer) $observer->onBeforeMatchStarts($match); }
    	if ($match->isCompleted) {
    		$this->completeMatch($match);
    		return; }
    	if (!$this->_hasPlayers($match->homeTeam) || !$this->_hasPlayers($match->guestTeam)) {
    		$this->completeMatch($match);
    		return; }
    	for ($simMinute = 1; $simMinute <= $minutes; $simMinute++) {
    		$match->minute = $match->minute + 1;
    		if ($match->minute == 1) $this->_simStrategy->kickoff($match);
    		else {
    			SimulationHelper::checkAndExecuteSubstitutions($match, $match->homeTeam, $this->_observers);
    			SimulationHelper::checkAndExecuteSubstitutions($match, $match->guestTeam, $this->_observers); }
    		$actionName = $this->_simStrategy->nextAction($match);
    		$this->_simStrategy->$actionName($match);
    		$this->_increaseMinutesPlayed($match->homeTeam);
    		$this->_increaseMinutesPlayed($match->guestTeam);
    		$lastMinute = 90 + SimulationHelper::getMagicNumber(1, 5);
    		if ($match->penaltyShootingEnabled || $match->type == 'Pokalspiel') {
    			if (($match->minute == 91 || $match->minute == 121)&& ($match->type != 'Pokalspiel' && $match->homeTeam->getGoals() != $match->guestTeam->getGoals() || $match->type == 'Pokalspiel' &&
    				!SimulationCupMatchHelper::checkIfExtensionIsRequired($this->_websoccer, $this->_db, $match))) {
    				$this->completeMatch($match);
    				break; }
    			elseif ($match->minute == 121 &&($match->type != 'Pokalspiel' && $match->homeTeam->getGoals()== $match->guestTeam->getGoals() || $match->type == 'Pokalspiel' && SimulationCupMatchHelper::checkIfExtensionIsRequired($this->_websoccer,$this->_db,$match))) {
    				$this->_simStrategy->penaltyShooting($match);
    				if ($match->type == 'Pokalspiel') {
    					if ($match->homeTeam->getGoals() > $match->guestTeam->getGoals()) SimulationCupMatchHelper::createNextRoundMatchAndPayAwards($this->_websoccer, $this->_db, $match->homeTeam->id, $match->guestTeam->id, $match->cupName, $match->cupRoundName);
    					else SimulationCupMatchHelper::createNextRoundMatchAndPayAwards($this->_websoccer, $this->_db, $match->guestTeam->id, $match->homeTeam->id, $match->cupName, $match->cupRoundName); }
    				$this->completeMatch($match);
    				break; }}
    		elseif ($match->minute >= $lastMinute) {
    			$this->completeMatch($match);
    			break; }}}
    function completeMatch($match) {
    	$match->isCompleted = TRUE;
    	foreach ($this->_observers as $observer) $observer->onMatchCompleted($match);
    	$event = new MatchCompletedEvent($this->_websoccer, $this->_db, I18n::getInstance($this->_websoccer->getConfig('supported_languages')), $match);
    	PluginMediator::dispatchEvent($event); }
    function _increaseMinutesPlayed(SimulationTeam $team) {
    	foreach ($team->positionsAndPlayers as $position => $players) {
    		foreach ($players as $player) $player->setMinutesPlayed($player->getMinutesPlayed() + 1, $this->_websoccer->getConfig('sim_decrease_freshness')); }}
    function _hasPlayers(SimulationTeam $team) {
    	if (!is_array($team->positionsAndPlayers) || count($team->positionsAndPlayers) == 0) return FALSE;
    	$noOfPlayers = 0;
    	foreach ($team->positionsAndPlayers as $position => $players) {
    		foreach ($players as $player) $noOfPlayers++; }
    	return ($noOfPlayers > 5); }}
class StringUtil {
	static function startsWith($message, $needle) { return !strncmp($message, $needle, strlen($needle)); }
	static function endsWith($message, $needle) {
		$length = strlen($needle);
		if ($length == 0) return true;
		return (substr($message, -$length) === $needle); }
	static function convertTimestampToWord($timestamp, $nowAsTimestamp, $i18n) {
		if ($timestamp >= strtotime('tomorrow', $nowAsTimestamp) + 24 * 3600) return '';
		if ($timestamp >= strtotime('tomorrow', $nowAsTimestamp)) return $i18n->getMessage('date_tomorrow');
		elseif ($timestamp >= strtotime('today', $nowAsTimestamp)) return $i18n->getMessage('date_today');
		elseif ($timestamp >= strtotime('yesterday', $nowAsTimestamp)) return $i18n->getMessage('date_yesterday');
		return ''; }}
//+ owsPro: New funtion - delDir($dir);
function delDir($dir){
	if(is_dir($dir)){
		$files=scandir($dir);
		foreach($files as$file){
			if($file!="."&&$file!=".."){
				if(filetype($dir."/".$file)=="dir")delDir($dir."/".$file);
				else unlink($dir."/".$file);}}
		rmdir($dir);}}
class TwigAutoloader{
	static function register(){spl_autoload_register([__CLASS__,'autoload'],true);}
	static function autoload($class){
		if(0!==strpos($class,'Twig')){return;}
		require($_SERVER['DOCUMENT_ROOT'].'/lib/2/Twig/'.str_replace(['Twig\\','\\',"\0"],['','/',''],$class).'.php');}}
class TemplateEngine {
	private $_environment;
	private $_skin;
	function __construct($env, $i18n, ViewHandler $viewHandler = null) {
		$this->_skin = $env->getSkin();
		$this->_initTwig();
		$this->_environment->addGlobal(I18N_GLOBAL_NAME, $i18n);
		$this->_environment->addGlobal(ENVIRONMENT_GLOBAL_NAME, $env);
		$this->_environment->addGlobal(SKIN_GLOBAL_NAME, $this->_skin);
		$this->_environment->addGlobal(VIEWHANDLER_GLOBAL_NAME, $viewHandler); }
	function loadTemplate($templateName) { return $this->_environment->loadTemplate($this->_skin->getTemplate($templateName)); }
	function clearCache() {
		if (file_exists(CACHE_FOLDER)) {
			//- owsPro - Fatal error: Uncaught Error: Call to undefined method Twig\Environment::clearCacheFiles()
			//- $this->_environment->clearCacheFiles();
			//+ owsPro - new function to delete the folder and set it then new to work
			delDir($_SERVER['DOCUMENT_ROOT'].'/cache');
			mkdir($_SERVER['DOCUMENT_ROOT'].'/cache'); }}
	function getEnvironment() { return $this->_environment; }
	function _initTwig() {
		$twigConfig = array('cache' => CACHE_FOLDER,);
		if(version_compare(PHP_VERSION, '7.1.0') >= 0){
			TwigAutoloader::register();
			$loader = new Twig\Loader\FilesystemLoader($_SERVER['DOCUMENT_ROOT'].'/templates/default');
			$this->_environment = new Twig\Environment($loader, $twigConfig);}
		elseif(version_compare(PHP_VERSION, '7.1.0') < 0){
			require_once($_SERVER['DOCUMENT_ROOT'].'/lib/1/Twig/Autoloader.php');
			Twig_Autoloader::register();
			$loader = new Twig_Loader_Filesystem($_SERVER['DOCUMENT_ROOT'].'/templates/default');
			$this->_environment = new Twig_Environment($loader, $twigConfig);} }
	function _addSettingsSupport() {
		$function = new Twig_SimpleFunction(CONFIG_FUNCTION_NAME, function ($key) {
			global $i18n;
			return $i18n->getMessage($key); });
		$this->_environment->addFunction($function); }}
class UrlUtil {
	static function buildCurrentUrlWithParameters($parameters) {
		$url = $_SERVER['PHP_SELF'] .'?';
		$first = TRUE;
		foreach($parameters as $parameterName => $parameterValue) {
			if (!$first) $url .= '&';
			$url .= $parameterName .'='. $parameterValue;
			$first = FALSE; }
		foreach($_GET as $parameterName => $parameterValue) {
			if (!isset($parameters[$parameterName])) $url .= '&' . $parameterName .'='. $parameterValue; }
		return escapeOutput($url); }}
class User {
	public $id;
	public $username;
	public $email;
	public $language;
	public $premiumBalance;
	private $_clubId;
	private $_profilePicture;
	private $_isAdmin;
	function __construct() {
		$this->premiumBalance = 0;
		$this->_isAdmin = NULL; }
	function getRole() {
		if ($this->id == null) return ROLE_GUEST;
		else return ROLE_USER; }
	function getClubId($websoccer = null, $db = null) {
		if ($this->id != null && $this->_clubId == null) {
			if (isset($_SESSION['clubid'])) $this->_clubId = $_SESSION['clubid'];
			elseif ($websoccer != null && $db != null) {
				$fromTable = $websoccer->getConfig('db_prefix') . '_verein';
				$whereCondition = 'status = 1 AND user_id = %d AND nationalteam != \'1\' ORDER BY interimmanager DESC';
				$columns = 'id';
				$result = $db->querySelect($columns, $fromTable, $whereCondition, $this->id, 1);
				$club = $result->fetch_array();
				if ($club) $this->setClubId($club['id']); }}
		return $this->_clubId; }
	function setClubId($clubId) {
		$_SESSION['clubid'] = $clubId;
		$this->_clubId = $clubId; }
	function getProfilePicture() {
		if ($this->_profilePicture == null) {
			if (strlen($this->email)) $this->_profilePicture = UsersDataService::getUserProfilePicture(WebSoccer::getInstance(), null, $this->email);
			else $this->_profilePicture = ''; }
		return $this->_profilePicture; }
	function setProfilePicture($websoccer, $fileName) { if (strlen($fileName)) $this->_profilePicture = UsersDataService::getUserProfilePicture($websoccer, $fileName, null); }
	function isAdmin() {
		if ($this->_isAdmin === NULL) {
			$websoccer = WebSoccer::getInstance();
			$db = DbConnection::getInstance();
			$result = $db->querySelect('id', $websoccer->getConfig('db_prefix') . '_admin', 'email = \'%s\' AND r_admin = \'1\'', $this->email);
			if ($result->num_rows) $this->_isAdmin = TRUE;
			else $this->_isAdmin = FALSE; }
		return $this->_isAdmin; }}
class ValidationException extends Exception {
	private $_messages;
    function __construct($messages) {
    	$this->_messages = $messages;
    	parent::__construct('Validation failed'); }
    function getMessages() { return $this->_messages; }}
class ViewHandler {
	private $_pages;
	private $_blocks;
	private $_validationMessages;
	function __construct($website, $db, $i18n, &$pages, &$blocks, $validationMessages = null) {
		$this->_website = $website;
		$this->_db = $db;
		$this->_i18n = $i18n;
		$this->_pages = $pages;
		$this->_blocks = $blocks;
		$this->_validationMessages = $validationMessages; }
	function handlePage($pageId, $parameters) {
		if ($pageId == NULL) return;
		if (!isset($this->_pages[$pageId])) throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		$pageConfig = json_decode($this->_pages[$pageId], TRUE);
		$requiredRoles = explode(',', $pageConfig['role']);
		if (!in_array($this->_website->getUser()->getRole(), $requiredRoles)) throw new AccessDeniedException($this->_i18n->getMessage('error_access_denied'));
		if (isset($pageConfig['premiumBalanceMin'])) {
			$minPremiumBalanceRequired = (int) $pageConfig['premiumBalanceMin'];
			if ($minPremiumBalanceRequired > $this->_website->getUser()->premiumBalance) {
				$targetPage = $this->_website->getConfig('premium_infopage');
				if (filter_var($targetPage, FILTER_VALIDATE_URL)) {
					header('location: ' . $targetPage);
					exit; }
				else {
					$this->_website->addContextParameter('premium_balance_required', $minPremiumBalanceRequired);
					return $this->handlePage($targetPage, $parameters); }}}
		$template = $this->_website->getTemplateEngine($this->_i18n, $this)->loadTemplate('views/' . $pageConfig['template']);
		if (isset($pageConfig['model'])) {
			$class = $pageConfig['model'];
			if (!class_exists($class)) throw new Exception('The model class \''. $class . '\' does not exist.');
			$model = new $class($this->_db, $this->_i18n, $this->_website);
			if (!$model->renderView()) return '';
			$parameters = array_merge($parameters, $model->getTemplateParameters()); }
		$parameters['validationMsg'] = $this->_validationMessages;
		$parameters['frontMessages'] = $this->_website->getFrontMessages();
		$parameters['ajaxRequest'] = $this->_website->isAjaxRequest();
		$parameters['blocks'] = $this->_getBlocksForPage($pageId);
		$scriptReferences = array();
		if (isset($pageConfig['scripts'])) {
			foreach ($pageConfig['scripts'] as $reference) {
				if ((DEBUG && (!isset($reference['productiononly']) || !$reference['productiononly'])) || (!DEBUG && (!isset($reference['debugonly']) || !$reference['debugonly']))) $scriptReferences[] = $reference['file']; }}
		$parameters['scriptReferences'] = $scriptReferences;
		$cssReferences = array();
		if (isset($pageConfig['csss'])) {
			foreach ($pageConfig['csss'] as $reference) {
				if ((DEBUG && (!isset($reference['productiononly']) || !$reference['productiononly'])) || (!DEBUG && (!isset($reference['debugonly']) || !$reference['debugonly']))) $cssReferences[] = $reference['file']; }}
		$parameters['cssReferences'] = $cssReferences;
		return $template->render($parameters); }
	function renderBlock($blockId, $viewConfig = null, $parameters = null) {
		if ($viewConfig == null) {
			if (!isset($this->_blocks[$blockId])) return '';
			$viewConfig = json_decode($this->_blocks[$blockId], true); }
		if ($parameters == null) $parameters = array();
		if (isset($viewConfig['model'])) {
			$class = $viewConfig['model'];
			if (!class_exists($class)) throw new Exception('The model class \''. $class . '\' does not exist.');
			$model = new $class($this->_db, $this->_i18n, $this->_website);
			if (!$model->renderView()) return '';
			$parameters = array_merge($parameters, $model->getTemplateParameters()); }
		$userRole = $this->_website->getUser()->getRole();
		$roles = explode(',', $viewConfig['role']);
		if (!in_array($userRole, $roles)) return '';
		$minPremiumBalanceRequired = (isset($viewConfig['premiumBalanceMin'])) ? $viewConfig['premiumBalanceMin'] : 0;
		if ($minPremiumBalanceRequired > $this->_website->getUser()->premiumBalance) return '';
		$parameters['validationMsg'] = $this->_validationMessages;
		$parameters['frontMessages'] = $this->_website->getFrontMessages();
		$template = $this->_website->getTemplateEngine($this->_i18n, $this)->loadTemplate('blocks/' . $viewConfig['template']);
		$parameters['blockId'] = $blockId;
		$output =$template->render($parameters);
		return $output; }
	function _getBlocksForPage($pageId) {
		$blocks = array();
		$userRole = $this->_website->getUser()->getRole();
		foreach($this->_blocks as $blockId => $blockData) {
			$blockConfig = json_decode($blockData, TRUE);
			$includepages = explode(',', $blockConfig['includepages']);
			$excludepages = (isset($blockConfig['excludepages'])) ? explode(',', $blockConfig['excludepages']) : array();
			$roles = explode(',', $blockConfig['role']);
			$minPremiumBalanceRequired = (isset($blockConfig['premiumBalanceMin'])) ? $blockConfig['premiumBalanceMin'] : 0;
			if (in_array($userRole, $roles)&&($includepages[0]== 'all' && !in_array($pageId, $excludepages) || in_array($pageId, $includepages)) && $minPremiumBalanceRequired <= $this->_website->getUser()->premiumBalance) $blocks[$blockConfig['area']][] = $blockConfig; }
		foreach($blocks as $uiblock => $blockdata) {
			if ($uiblock != 'custom') usort($blocks[$uiblock], array('ViewHandler', 'sortByWeight')); }
		return $blocks; }
	static function sortByWeight(&$a, &$b) {
		if (!isset($a['weight']) || !isset($b['weight'])) return 0;
		return $a['weight'] - $b['weight']; }}
class YouthMatchDataUpdateSimulatorObserver {
	function __construct($websoccer, $db) {
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function onBeforeMatchStarts(SimulationMatch $match) {}
	function onSubstitution(SimulationMatch $match, SimulationSubstitution $substitution) {
		YouthMatchesDataService::createMatchReportItem($this->_websoccer, $this->_db, $match->id, $match->minute, 'ymreport_substitution', array('in' => $substitution->playerIn->name, 'out' => $substitution->playerOut->name),
			$substitution->playerIn->team->id == $match->homeTeam->id);}
	function onMatchCompleted(SimulationMatch $match) {
		$this->_updateTeam($match, $match->homeTeam);
		$this->_updateTeam($match, $match->guestTeam);
		$columns = array('home_noformation'=>($match->homeTeam->noFormationSet) ? '1':'0','home_goals' => $match->homeTeam->getGoals(),'guest_noformation'=>($match->guestTeam->noFormationSet) ? '1':'0','guest_goals' => $match->guestTeam->getGoals(),'simulated' => '1' );
		$this->_db->queryUpdate($columns, $this->_websoccer->getConfig('db_prefix') . '_youthmatch', 'id = %d', $match->id); }
	function _updateTeam(SimulationMatch $match, SimulationTeam $team) {
		$salary = YouthPlayersDataService::computeSalarySumOfYouthPlayersOfTeam($this->_websoccer, $this->_db, $team->id);
		if ($salary) BankAccountDataService::debitAmount($this->_websoccer, $this->_db, $team->id, $salary, 'youthteam_salarypayment_subject', 'match_salarypayment_sender');
		if (is_array($team->positionsAndPlayers)) {
			foreach($team->positionsAndPlayers as $position => $players) {
				foreach ($players as $player) $this->_updatePlayer($match, $player, TRUE); }}
		if (is_array($team->removedPlayers)) {
			foreach ($team->removedPlayers as $player) $this->_updatePlayer($match, $player, FALSE); }}
	function _updatePlayer(SimulationMatch $match, SimulationPlayer $player, $isOnPitch) {
		$columns = array( 'name' => $player->name, 'position_main' => $player->mainPosition, 'grade' => $player->getMark(), 'minutes_played' => $player->getMinutesPlayed(), 'card_yellow' => $player->yellowCards, 'card_red' => $player->redCard,
			'goals' => $player->getGoals(), 'strength' => $player->strength, 'ballcontacts' => $player->getBallContacts(), 'wontackles' => $player->getWonTackles(), 'shoots' => $player->getShoots(), 'passes_successed' => $player->getPassesSuccessed(),
			'passes_failed' => $player->getPassesFailed(), 'assists' => $player->getAssists(), 'state' => ($isOnPitch) ? '1' : 'Ausgewechselt' );
		$this->_db->queryUpdate($columns, $this->_websoccer->getConfig('db_prefix') . '_youthmatch_player', 'match_id = %d AND player_id = %d', array($match->id, $player->id));
		if ($this->_websoccer->getConfig('sim_played_min_minutes') <= $player->getMinutesPlayed()) {
			$result = $this->_db->querySelect('*', $this->_websoccer->getConfig('db_prefix') . '_youthplayer', 'id = %d', $player->id);
			$playerinfo = $result->fetch_array();
			$strengthChange = $this->_computeStrengthChange($player);
			$event = new YouthPlayerPlayedEvent($this->_websoccer, $this->_db, I18n::getInstance($this->_websoccer->getConfig('supported_languages')), $player, $strengthChange);
			PluginMediator::dispatchEvent($event);
			$yellowRedCards = 0;
			if ($player->yellowCards == 2) {
				$yellowCards = 1;
				$yellowRedCards = 1; }
			else $yellowCards = $player->yellowCards;
			$strength = $playerinfo['strength'] + $strengthChange;
			$maxStrength = $this->_websoccer->getConfig('youth_scouting_max_strength');
			$minStrength = $this->_websoccer->getConfig('youth_scouting_min_strength');
			if ($strength > $maxStrength) {
				$strengthChange = 0;
				$strength = $maxStrength; }
			elseif ($strength < $minStrength) {
				$strengthChange = 0;
				$strength = $minStrength; }
			$columns = array('strength' => $strength, 'strength_last_change' => $strengthChange, 'st_goals' => $playerinfo['st_goals'] + $player->getGoals(), 'st_matches' => $playerinfo['st_matches'] + 1, 'st_assists' => $playerinfo['st_assists'] + $player->getAssists(),
				'st_cards_yellow' => $playerinfo['st_cards_yellow'] + $yellowCards, 'st_cards_yellow_red' => $playerinfo['st_cards_yellow_red'] + $yellowRedCards, 'st_cards_red' => $playerinfo['st_cards_red'] + $player->redCard );
			$this->_db->queryUpdate($columns, $this->_websoccer->getConfig('db_prefix') . '_youthplayer', 'id = %d', $player->id); }}
	function _computeStrengthChange(SimulationPlayer $player) {
		$mark = $player->getMark();
		if ($mark <= 1.3) return $this->_websoccer->getConfig('youth_strengthchange_verygood');
		elseif ($mark <= 2.3) return $this->_websoccer->getConfig('youth_strengthchange_good');
		elseif ($mark > 4.25 && $mark <= 5) return $this->_websoccer->getConfig('youth_strengthchange_bad');
		elseif ($mark > 5) return $this->_websoccer->getConfig('youth_strengthchange_verybad');
		return 0; }}
class YouthMatchReportSimulationObserver {
	function __construct($websoccer, $db) {
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function onGoal(SimulationMatch $match, SimulationPlayer $scorer, SimulationPlayer $goaly) {
		YouthMatchesDataService::createMatchReportItem($this->_websoccer, $this->_db, $match->id, $match->minute, 'ymreport_goal', array('scorer' => $scorer->name), $scorer->team->id == $match->homeTeam->id); }
	function onShootFailure(SimulationMatch $match, SimulationPlayer $scorer, SimulationPlayer $goaly) {
		YouthMatchesDataService::createMatchReportItem($this->_websoccer, $this->_db, $match->id, $match->minute, 'ymreport_attempt', array('scorer' => $scorer->name), $scorer->team->id == $match->homeTeam->id); }
	function onAfterTackle(SimulationMatch $match, SimulationPlayer $winner, SimulationPlayer $looser) {
		YouthMatchesDataService::createMatchReportItem($this->_websoccer, $this->_db, $match->id, $match->minute, 'ymreport_tackle', array('winner' => $winner->name, 'loser' => $looser->name), $winner->team->id == $match->homeTeam->id); }
	function onBallPassSuccess(SimulationMatch $match, SimulationPlayer $player) {}
	function onBallPassFailure(SimulationMatch $match, SimulationPlayer $player) {}
	function onInjury(SimulationMatch $match, SimulationPlayer $player, $numberOfMatches) {
		YouthMatchesDataService::createMatchReportItem($this->_websoccer, $this->_db, $match->id, $match->minute, 'ymreport_injury', array('player' => $player->name), $player->team->id == $match->homeTeam->id); }
	function onYellowCard(SimulationMatch $match, SimulationPlayer $player) {
		if ($player->yellowCards > 1) YouthMatchesDataService::createMatchReportItem($this->_websoccer, $this->_db, $match->id, $match->minute, 'ymreport_card_yellowred', array('player' => $player->name), $player->team->id == $match->homeTeam->id);
		else YouthMatchesDataService::createMatchReportItem($this->_websoccer, $this->_db, $match->id, $match->minute, 'ymreport_card_yellow', array('player' => $player->name), $player->team->id == $match->homeTeam->id); }
	function onRedCard(SimulationMatch $match, SimulationPlayer $player, $matchesBlocked) {
		YouthMatchesDataService::createMatchReportItem($this->_websoccer, $this->_db, $match->id, $match->minute, 'ymreport_card_red', array('player' => $player->name), $player->team->id == $match->homeTeam->id); }
	function onPenaltyShoot(SimulationMatch $match, SimulationPlayer $player, SimulationPlayer $goaly, $successful) {
		if ($successful) YouthMatchesDataService::createMatchReportItem($this->_websoccer, $this->_db, $match->id, $match->minute, 'ymreport_penalty_success', array('player' => $player->name), $player->team->id == $match->homeTeam->id);
		else YouthMatchesDataService::createMatchReportItem($this->_websoccer, $this->_db, $match->id, $match->minute, 'ymreport_penalty_failure', array('player' => $player->name), $player->team->id == $match->homeTeam->id); }
	function onCorner(SimulationMatch $match, SimulationPlayer $concededByPlayer, SimulationPlayer $targetPlayer) {}
	function onFreeKick(SimulationMatch $match, SimulationPlayer $player, SimulationPlayer $goaly, $successful) { if ($successful) $this->onGoal($match, $player, $goaly); }}
class YouthMatchSimulationExecutor {
	static function simulateOpenYouthMatches($websoccer, $db, $maxMatchesToSimulate) {
		if (!$websoccer->getConfig('youth_enabled')) return;
		$simulator = new Simulator($db, $websoccer);
		$simulator->attachObserver(new YouthMatchDataUpdateSimulatorObserver($websoccer, $db));
		$simulator->getSimulationStrategy()->attachObserver(new YouthMatchReportSimulationObserver($websoccer, $db));
		$result = $db->querySelect('*', $websoccer->getConfig('db_prefix') . '_youthmatch', 'simulated != \'1\' AND matchdate <= %d ORDER BY matchdate ASC', $websoccer->getNowAsTimestamp(), $maxMatchesToSimulate);
		while ($matchinfo = $result->fetch_array()) {
			$match = self::_createMatch($websoccer, $db, $matchinfo);
			if ($match != null) {
				$simulator->simulateMatch($match, 100);
				$match->cleanReferences();
				unset($match); }}}
	static function _createMatch($websoccer, $db, $matchinfo) {
		$homeTeam = new SimulationTeam($matchinfo['home_team_id'], DEFAULT_YOUTH_OFFENSIVE);
		$guestTeam = new SimulationTeam($matchinfo['guest_team_id'], DEFAULT_YOUTH_OFFENSIVE);
		$match = new SimulationMatch($matchinfo['id'], $homeTeam, $guestTeam, 0);
		$match->type = YOUTH_MATCH_TYPE;
		$match->penaltyShootingEnabled = FALSE;
		self::_addPlayers($websoccer, $db, $match, $homeTeam);
		self::_addSubstitutions($websoccer, $db, $match, $homeTeam, $matchinfo, 'home');
		self::_addPlayers($websoccer, $db, $match, $guestTeam);
		self::_addSubstitutions($websoccer, $db, $match, $guestTeam, $matchinfo, 'guest');
		return $match; }
	static function _addPlayers($websoccer, $db, SimulationMatch $match, SimulationTeam $team) {
		$fromTable = $websoccer->getConfig('db_prefix') . '_youthmatch_player AS MP';
		$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') . '_youthplayer AS P ON P.id = MP.player_id';
		$whereCondition = 'MP.match_id = %d AND MP.team_id = %d AND P.team_id = %d ORDER BY playernumber ASC';
		$parameters = array($match->id, $team->id, $team->id);
		$columns = array( 'P.id' => 'id', 'P.strength' => 'player_strength', 'P.firstname' => 'firstname', 'P.lastname' => 'lastname', 'P.position' => 'player_position', 'MP.position' => 'match_position', 'MP.position_main' => 'match_position_main',
			'MP.grade' => 'grade', 'MP.state' => 'state' );
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters);
		$addedFieldPlayers = 0;
		while ($playerinfo = $result->fetch_array()) {
			$name = $playerinfo['firstname'] . ' ' . $playerinfo['lastname'];
			$strength = $playerinfo['player_strength'];
			$technique = $strength;
			$position = $playerinfo['player_position'];
			$mainPosition = $playerinfo['match_position_main'];
			$player = new SimulationPlayer($playerinfo['id'], $team, $position, $mainPosition, $playerinfo['grade'], DEFAULT_PLAYER_AGE, $strength, $technique, YOUTH_STRENGTH_STAMINA, YOUTH_STRENGTH_FRESHNESS, YOUTH_STRENGTH_SATISFACTION);
			$player->name = $name;
			if ($playerinfo['state'] == 'Ersatzbank') $team->playersOnBench[$playerinfo['id']] = $player;
			else {
				if ($addedFieldPlayers == 0) {
					$player->position = 'Torwart';
					$player->mainPosition = 'T'; }
				else $player->position = $playerinfo['match_position'];
				if ($player->position != $playerinfo['player_position']) $player->strength = round($strength * (1 - $websoccer->getConfig('sim_strength_reduction_wrongposition') / 100));
				$team->positionsAndPlayers[$player->position][] = $player;
				$addedFieldPlayers++; }}
		if ($addedFieldPlayers < MIN_NUMBER_OF_PLAYERS) {
			$team->noFormationSet = TRUE;
			self::_createRandomFormation($websoccer, $db, $match, $team); }}
	static function _createRandomFormation($websoccer, $db, SimulationMatch $match, SimulationTeam $team) {
		$db->queryDelete($websoccer->getConfig('db_prefix') . '_youthmatch_player', 'match_id = %d AND team_id = %d', array($match->id, $team->id));
		$formationPositions = array('T', 'LV', 'IV', 'IV', 'RV', 'LM', 'ZM', 'ZM', 'RM', 'LS', 'RS');
		$positionMapping = SimulationHelper::getPositionsMapping();
		$players = YouthPlayersDataService::getYouthPlayersOfTeam($websoccer, $db, $team->id);
		$positionIndex = 0;
		foreach ($players as $playerinfo) {
			$mainPosition = $formationPositions[$positionIndex];
			$position = $positionMapping[$mainPosition];
			$player = new SimulationPlayer($playerinfo['id'], $team, $position, $mainPosition, 3.0, DEFAULT_PLAYER_AGE, $playerinfo['strength'], $playerinfo['strength'], YOUTH_STRENGTH_STAMINA, YOUTH_STRENGTH_FRESHNESS, YOUTH_STRENGTH_SATISFACTION);
			$player->name = $playerinfo['firstname'] . ' ' . $playerinfo['lastname'];
			if ($player->position != $playerinfo['position']) $player->strength = round($playerinfo['strength'] * (1 - $websoccer->getConfig('sim_strength_reduction_wrongposition') / 100));
			try {$columns = array('match_id' => $match->id, 'team_id' => $team->id, 'player_id' => $player->id, 'playernumber' => $positionIndex + 1, 'position' => $player->position, 'position_main' => $player->mainPosition, 'name' => $player->name );
				$db->queryInsert($columns, $websoccer->getConfig('db_prefix') . '_youthmatch_player');
				$team->positionsAndPlayers[$player->position][] = $player; }
			catch (Exception $e) {}
			$positionIndex++;
			if ($positionIndex == 11) break; }}
	static function _addSubstitutions($websoccer, $db, SimulationMatch $match, SimulationTeam $team, $matchinfo, $teamPrefix) {
		for ($subNo = 1; $subNo <= 3; $subNo++) {
			if ($matchinfo[$teamPrefix . '_s' . $subNo . '_out']) {
				$out = $matchinfo[$teamPrefix . '_s' . $subNo . '_out'];
				$in = $matchinfo[$teamPrefix . '_s' . $subNo . '_in'];
				$minute = $matchinfo[$teamPrefix . '_s' . $subNo . '_minute'];
				$condition = $matchinfo[$teamPrefix . '_s' . $subNo . '_condition'];
				$position = $matchinfo[$teamPrefix . '_s' . $subNo . '_position'];
				if (isset($team->playersOnBench[$in])) {
					$playerIn = $team->playersOnBench[$in];
					$playerOut = self::findPlayerOnField($team, $out);
					if ($playerIn && $playerOut) {
						$sub = new SimulationSubstitution($minute, $playerIn, $playerOut, $condition, $position);
						$team->substitutions[] = $sub; }}}}}
	function findPlayerOnField(SimulationTeam $team, $playerId) {
		foreach ($team->positionsAndPlayers as $position => $players) {
			foreach ($players as $player) {
				if ($player->id == $playerId) return $player; }}
		return false; }}
class AcceptStadiumConstructionWorkController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$user = $this->_websoccer->getUser();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		$construction = StadiumsDataService::getCurrentConstructionOrderOfTeam($this->_websoccer, $this->_db, $clubId);
		if ($construction == NULL || $construction["deadline"] > $this->_websoccer->getNowAsTimestamp()) throw new Exception($this->_i18n->getMessage("stadium_acceptconstruction_err_nonedue"));
		$pStatus["completed"] = $construction["builder_reliability"];
		$pStatus["notcompleted"] = 100 - $pStatus["completed"];
		$constructionResult = SimulationHelper::selectItemFromProbabilities($pStatus);
		if ($constructionResult == "notcompleted") {
			$newDeadline = $this->_websoccer->getNowAsTimestamp() + $this->_websoccer->getConfig("stadium_construction_delay") * 24 * 3600;
			$this->_db->queryUpdate(array("deadline" => $newDeadline), $this->_websoccer->getConfig("db_prefix") . "_stadium_construction", "id = %d", $construction["id"]);
			$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_WARNING, $this->_i18n->getMessage("stadium_acceptconstruction_notcompleted_title"), $this->_i18n->getMessage("stadium_acceptconstruction_notcompleted_details"))); }
		else {
			$stadium = StadiumsDataService::getStadiumByTeamId($this->_websoccer, $this->_db, $clubId);
			$columns = array();
			$columns["p_steh"] = $stadium["places_stands"] + $construction["p_steh"];
			$columns["p_sitz"] = $stadium["places_seats"] + $construction["p_sitz"];
			$columns["p_haupt_steh"] = $stadium["places_stands_grand"] + $construction["p_haupt_steh"];
			$columns["p_haupt_sitz"] = $stadium["places_seats_grand"] + $construction["p_haupt_sitz"];
			$columns["p_vip"] = $stadium["places_vip"] + $construction["p_vip"];
			$this->_db->queryUpdate($columns, $this->_websoccer->getConfig("db_prefix") . "_stadion", "id = %d", $stadium["stadium_id"]);
			$this->_db->queryDelete($this->_websoccer->getConfig("db_prefix") . "_stadium_construction", "id = %d", $construction["id"]);
			$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("stadium_acceptconstruction_completed_title"), $this->_i18n->getMessage("stadium_acceptconstruction_completed_details"))); }
		return null; }}
class AcceptYouthMatchRequestController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("youth_enabled") || !$this->_websoccer->getConfig("youth_matchrequests_enabled")) return NULL;
		$user = $this->_websoccer->getUser();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_youthmatch_request";
		$result = $this->_db->querySelect("*", $fromTable, "id = %d", $parameters["id"]);
		$request = $result->fetch_array();
		if (!$request) throw new Exception($this->_i18n->getMessage("youthteam_matchrequest_cancel_err_notfound"));
		if ($clubId == $request["team_id"]) throw new Exception($this->_i18n->getMessage("youthteam_matchrequest_accept_err_ownrequest"));
		if (YouthPlayersDataService::countYouthPlayersOfTeam($this->_websoccer, $this->_db, $clubId) < 11) throw new Exception($this->_i18n->getMessage("youthteam_matchrequest_create_err_notenoughplayers"));
		$maxMatchesPerDay = $this->_websoccer->getConfig("youth_match_maxperday");
		if (YouthMatchesDataService::countMatchesOfTeamOnSameDay($this->_websoccer, $this->_db, $clubId, $request["matchdate"]) >= $maxMatchesPerDay) throw new Exception($this->_i18n->getMessage("youthteam_matchrequest_err_maxperday_violated", $maxMatchesPerDay));
		$homeTeam = TeamsDataService::getTeamSummaryById($this->_websoccer, $this->_db, $request["team_id"]);
		$guestTeam = TeamsDataService::getTeamSummaryById($this->_websoccer, $this->_db, $clubId);
		if ($request["reward"]) {
			BankAccountDataService::debitAmount($this->_websoccer, $this->_db, $request["team_id"], $request["reward"], "youthteam_matchrequest_reward_subject", $guestTeam["team_name"]);
			BankAccountDataService::creditAmount($this->_websoccer, $this->_db, $clubId, $request["reward"], "youthteam_matchrequest_reward_subject", $homeTeam["team_name"]); }
		$this->_db->queryInsert(array("matchdate" => $request["matchdate"], "home_team_id" => $request["team_id"], "guest_team_id" => $clubId ), $this->_websoccer->getConfig("db_prefix") . "_youthmatch");
		$this->_db->queryDelete($fromTable, "id = %d", $parameters["id"]);
		NotificationsDataService::createNotification($this->_websoccer, $this->_db, $homeTeam["user_id"], "youthteam_matchrequest_accept_notification", array("team" => $guestTeam["team_name"], "date" => $this->_websoccer->getFormattedDatetime($request["matchdate"])),
			"youthmatch_accept", "youth-matches", null, $request["team_id"]);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("youthteam_matchrequest_accept_success"), $this->_i18n->getMessage("youthteam_matchrequest_accept_success_details")));
		return "youth-matches"; }}
class AddNationalPlayerController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db;}
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("nationalteams_enabled")) return NULL;
		$teamId = NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer, $this->_db);
		if (!$teamId) throw new Exception($this->_i18n->getMessage("nationalteams_user_requires_team"));
		$result = $this->_db->querySelect("name", $this->_websoccer->getConfig("db_prefix") . "_verein", "id = %d", $teamId);
		$team = $result->fetch_array();
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_spieler";
		$result = $this->_db->querySelect("nation", $fromTable, "id = %d", $parameters["id"]);
		$player = $result->fetch_array();
		if (!$player) throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		if ($player["nation"] != $team["name"]) throw new Exception("Player is from different nation.");
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_nationalplayer";
		$result = $this->_db->querySelect("COUNT(*) AS hits", $fromTable, "player_id = %d", $parameters["id"]);
		$players = $result->fetch_array();
		if ($players && $players["hits"]) throw new Exception($this->_i18n->getMessage("nationalteams_addplayer_err_alreadyinteam"));
		$result = $this->_db->querySelect("COUNT(*) AS hits", $fromTable, "team_id = %d", $teamId);
		$existingplayers = $result->fetch_array();
		if ($existingplayers["hits"] >= 30) throw new Exception($this->_i18n->getMessage("nationalteams_addplayer_err_toomanyplayer", 30));
		$this->_db->queryInsert(array("team_id" => $teamId, "player_id" => $parameters["id"] ), $fromTable);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("nationalteams_addplayer_success"), ""));
		return "nationalteam"; }}
class BookCampController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$now = $this->_websoccer->getNowAsTimestamp();
		$user = $this->_websoccer->getUser();
		$teamId = $user->getClubId($this->_websoccer, $this->_db);
		if ($teamId < 1) throw new Exception($this->_i18n->getMessage("feature_requires_team"));
		$team = TeamsDataService::getTeamSummaryById($this->_websoccer, $this->_db, $teamId);
		$min = $this->_websoccer->getConfig("trainingcamp_min_days");
		$max = $this->_websoccer->getConfig("trainingcamp_max_days");
		if ($parameters["days"] < $min || $parameters["days"] > $max) throw new Exception(sprintf($this->_i18n->getMessage("trainingcamp_booking_err_invaliddays"), $min, $max));
		$startDateObj = DateTime::createFromFormat($this->_websoccer->getConfig("date_format") . " H:i", $parameters["start_date"] . " 00:00");
		$startDateTimestamp = $startDateObj->getTimestamp();
		$endDateTimestamp = $startDateTimestamp + 3600 * 24 * $parameters["days"];
		if ($startDateTimestamp <= $now) throw new Exception($this->_i18n->getMessage("trainingcamp_booking_err_dateinpast"));
		$maxDate = $now + $this->_websoccer->getConfig("trainingcamp_booking_max_days_in_future") * 3600 * 24;
		if ($startDateTimestamp > $maxDate) throw new Exception($this->_i18n->getMessage("trainingcamp_booking_err_datetoofar", $this->_websoccer->getConfig("trainingcamp_booking_max_days_in_future")));
		$camp = TrainingcampsDataService::getCampById($this->_websoccer, $this->_db, $parameters["id"]);
		if (!$camp) throw new Exception("Illegal ID");
		$existingBookings = TrainingcampsDataService::getCampBookingsByTeam($this->_websoccer, $this->_db, $teamId);
		if (count($existingBookings)) throw new Exception($this->_i18n->getMessage("trainingcamp_booking_err_existingbookings"));
		$playersOfTeam = PlayersDataService::getPlayersOfTeamById($this->_websoccer, $this->_db, $teamId);
		$totalCosts = $camp["costs"] * $parameters["days"] * count($playersOfTeam);
		if ($totalCosts >= $team["team_budget"]) throw new Exception($this->_i18n->getMessage("trainingcamp_booking_err_tooexpensive"));
		$matches = MatchesDataService::getMatchesByTeamAndTimeframe($this->_websoccer, $this->_db, $teamId, $startDateTimestamp, $endDateTimestamp);
		if (count($matches)) throw new Exception($this->_i18n->getMessage("trainingcamp_booking_err_matcheswithintimeframe"));
		BankAccountDataService::debitAmount($this->_websoccer, $this->_db, $teamId, $totalCosts, "trainingcamp_booking_costs_subject", $camp["name"]);
		$columns["verein_id"] = $teamId;
		$columns["lager_id"] = $camp["id"];
		$columns["datum_start"] = $startDateTimestamp;
		$columns["datum_ende"] = $endDateTimestamp;
		$this->_db->queryInsert($columns, $this->_websoccer->getConfig("db_prefix") . "_trainingslager_belegung");
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("trainingcamp_booking_success"), ""));
		return "trainingcamp"; }}
class BorrowPlayerController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("lending_enabled")) return NULL;
		$user = $this->_websoccer->getUser();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		if ($clubId == null) throw new Exception($this->_i18n->getMessage("feature_requires_team"));
		$player = PlayersDataService::getPlayerById($this->_websoccer, $this->_db, $parameters["id"]);
		if ($clubId == $player["team_id"]) throw new Exception($this->_i18n->getMessage("lending_hire_err_ownplayer"));
		if ($player["lending_owner_id"] > 0) throw new Exception($this->_i18n->getMessage("lending_hire_err_borrowed_player"));
		if ($player["lending_fee"] == 0) throw new Exception($this->_i18n->getMessage("lending_hire_err_notoffered"));
		if ($player["player_transfermarket"] > 0) throw new Exception($this->_i18n->getMessage("lending_err_on_transfermarket"));
		if ($parameters["matches"] < $this->_websoccer->getConfig("lending_matches_min") || $parameters["matches"] > $this->_websoccer->getConfig("lending_matches_max")) {
			throw new Exception(sprintf($this->_i18n->getMessage("lending_hire_err_illegalduration"), $this->_websoccer->getConfig("lending_matches_min"), $this->_websoccer->getConfig("lending_matches_max"))); }
		if ($parameters["matches"] >= $player["player_contract_matches"]) throw new Exception($this->_i18n->getMessage("lending_hire_err_contractendingtoosoon", $player["player_contract_matches"]));
		$fee = $parameters["matches"] * $player["lending_fee"];
		$minBudget = $fee + 5 * $player["player_contract_salary"];
		$team = TeamsDataService::getTeamSummaryById($this->_websoccer, $this->_db, $clubId);
		if ($team["team_budget"] < $minBudget) throw new Exception($this->_i18n->getMessage("lending_hire_err_budget_too_low"));
		BankAccountDataService::debitAmount($this->_websoccer, $this->_db, $clubId, $fee, "lending_fee_subject", $player["team_name"]);
		BankAccountDataService::creditAmount($this->_websoccer, $this->_db, $player["team_id"], $fee, "lending_fee_subject", $team["team_name"]);
		$this->updatePlayer($player["player_id"], $player["team_id"], $clubId, $parameters["matches"]);
		$playerName = (strlen($player["player_pseudonym"])) ? $player["player_pseudonym"] : $player["player_firstname"] . " " . $player["player_lastname"];
		if ($player["team_user_id"]) { NotificationsDataService::createNotification($this->_websoccer, $this->_db, $player["team_user_id"], "lending_notification_lent", array("player" => $playerName, "matches" => $parameters["matches"], "newteam" => $team["team_name"]),
			"lending_lent", "player", "id=" . $player["player_id"]); }
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("lending_hire_success"), ""));
		return "myteam"; }
	function updatePlayer($playerId, $ownerId, $clubId, $matches) {
		$columns = array("lending_matches" => $matches, "lending_owner_id" => $ownerId, "verein_id" => $clubId);
		$fromTable = $this->_websoccer->getConfig("db_prefix") ."_spieler";
		$whereCondition = "id = %d";
		$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $playerId); }}
class BuyYouthPlayerController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db;}
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("youth_enabled")) return NULL;
		$user = $this->_websoccer->getUser();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		if ($clubId < 1) throw new Exception($this->_i18n->getMessage("feature_requires_team"));
		$player = YouthPlayersDataService::getYouthPlayerById($this->_websoccer, $this->_db, $this->_i18n, $parameters["id"]);
		if ($clubId == $player["team_id"]) throw new Exception($this->_i18n->getMessage("youthteam_buy_err_ownplayer"));
		$result = $this->_db->querySelect("user_id", $this->_websoccer->getConfig("db_prefix") . "_verein", "id = %d", $player["team_id"]);
		$playerteam = $result->fetch_array();
		$result->free_result();
		if ($playerteam["user_id"] == $user->id) throw new Exception($this->_i18n->getMessage("youthteam_buy_err_ownplayer_otherteam"));
		$team = TeamsDataService::getTeamSummaryById($this->_websoccer, $this->_db, $clubId);
		if ($team["team_budget"] <= $player["transfer_fee"]) throw new Exception($this->_i18n->getMessage("youthteam_buy_err_notenoughbudget"));
		$prevTeam = TeamsDataService::getTeamSummaryById($this->_websoccer, $this->_db, $player["team_id"]);
		BankAccountDataService::debitAmount($this->_websoccer, $this->_db, $clubId, $player["transfer_fee"], "youthteam_transferfee_subject", $prevTeam["team_name"]);
		BankAccountDataService::creditAmount($this->_websoccer, $this->_db, $player["team_id"], $player["transfer_fee"], "youthteam_transferfee_subject", $team["team_name"]);
		$this->_db->queryUpdate(array("team_id" => $clubId, "transfer_fee" => 0), $this->_websoccer->getConfig("db_prefix") . "_youthplayer", "id = %d", $parameters["id"]);
		NotificationsDataService::createNotification($this->_websoccer, $this->_db, $prevTeam["user_id"], "youthteam_transfer_notification", array("player" => $player["firstname"] . " " . $player["lastname"], "newteam" => $team["team_name"]), "youth_transfer", "team",
			"id=" . $clubId);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("youthteam_buy_success"), ""));
		return "youth-team"; }}
class CancelCampController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$user = $this->_websoccer->getUser();
		$teamId = $user->getClubId($this->_websoccer, $this->_db);
		if ($teamId < 1) throw new Exception($this->_i18n->getMessage("feature_requires_team"));
		$existingBookings = TrainingcampsDataService::getCampBookingsByTeam($this->_websoccer, $this->_db, $teamId);
		if (!count($existingBookings)) throw new Exception($this->_i18n->getMessage("trainingcamp_cancel_illegalid"));
		$deleted = FALSE;
		foreach ($existingBookings as $booking) {
			if ($booking["id"] == $parameters["bookingid"]) {
				$this->_db->queryDelete($this->_websoccer->getConfig("db_prefix") . "_trainingslager_belegung", "id = %d", $booking["id"]);
				$deleted = TRUE; }}
		if (!$deleted) throw new Exception($this->_i18n->getMessage("trainingcamp_cancel_illegalid"));
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("trainingcamp_cancel_success"), ""));
		return "trainingcamp"; }}
class CancelYouthMatchRequestController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("youth_enabled")) return NULL;
		$user = $this->_websoccer->getUser();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_youthmatch_request";
		$result = $this->_db->querySelect("*", $fromTable, "id = %d", $parameters["id"]);
		$request = $result->fetch_array();
		if (!$request) throw new Exception($this->_i18n->getMessage("youthteam_matchrequest_cancel_err_notfound"));
		if ($clubId != $request["team_id"]) throw new Exception("nice try");
		$this->_db->queryDelete($fromTable, "id = %d", $parameters["id"]);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("youthteam_matchrequest_cancel_success"), ""));
		return "youth-matchrequests"; }}
class ChooseAdditionalTeamController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$user = $this->_websoccer->getUser();
		$maxTeams = (int) $this->_websoccer->getConfig('max_number_teams_per_user');
		if (!$this->_websoccer->getConfig('assign_team_automatically') && $maxTeams > 1) throw new Exception($this->_i18n->getMessage('freeclubs_msg_error'));
		$minHighscore = (int) $this->_websoccer->getConfig('additional_team_min_highscore');
		if ($minHighscore) {
			$result = $this->_db->querySelect('highscore', $this->_websoccer->getConfig('db_prefix') . '_user', 'id = %d', $user->id);
			$userinfo = $result->fetch_array();
			if ($minHighscore > $userinfo['highscore']) throw new Exception($this->_i18n->getMessage('freeclubs_msg_error_minhighscore', $minHighscore)); }
		$fromTable = $this->_websoccer->getConfig('db_prefix') .'_verein';
		$result = $this->_db->querySelect('id,liga_id', $fromTable, 'user_id = %d', $user->id);
		$teamsOfUser = array();
		while ($teamOfUser = $result->fetch_array()) $teamsOfUser[$teamOfUser['liga_id']][] = $teamOfUser['id'];
		$result->free_result();
		if (count($teamsOfUser) >= $this->_websoccer->getConfig('max_number_teams_per_user')) throw new Exception($this->_i18n->getMessage('freeclubs_msg_error_max_number_of_teams', $maxTeams));
		$teamId = $parameters['teamId'];
		$result = $this->_db->querySelect('id,user_id,liga_id,interimmanager', $fromTable, 'id = %d AND status = 1', $teamId);
		$club = $result->fetch_array();
		if ($club['user_id'] && !$club['interimmanager']) throw new Exception($this->_i18n->getMessage('freeclubs_msg_error'));
		if (isset($teamsOfUser[$club['liga_id']])) throw new Exception($this->_i18n->getMessage('freeclubs_msg_error_no_club_from_same_league'));
		$this->_db->queryUpdate(array('user_id' => $user->id), $fromTable, "id = %d", $teamId);
		$user->setClubId($teamId);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage('freeclubs_msg_success'), ''));
		return 'office'; }}
class ChooseSponsorController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$user = $this->_websoccer->getUser();
		$teamId = $user->getClubId($this->_websoccer, $this->_db);
		if ($teamId < 1) return null;
		$sponsor = SponsorsDataService::getSponsorinfoByTeamId($this->_websoccer, $this->_db, $teamId);
		if ($sponsor) throw new Exception($this->_i18n->getMessage("sponsor_choose_stillcontract"));
		$teamMatchday = MatchesDataService::getMatchdayNumberOfTeam($this->_websoccer, $this->_db, $teamId);
		if ($teamMatchday < $this->_websoccer->getConfig("sponsor_earliest_matchday")) throw new Exception($this->_i18n->getMessage("sponsor_choose_tooearly", $this->_websoccer->getConfig("sponsor_earliest_matchday")));
		$sponsors = SponsorsDataService::getSponsorOffers($this->_websoccer, $this->_db, $teamId);
		$found = FALSE;
		foreach ($sponsors as $availableSponsor) {
			if ($availableSponsor["sponsor_id"] == $parameters["id"]) {
				$found = TRUE;
				break; }}
		if (!$found) throw new Exception($this->_i18n->getMessage("sponsor_choose_novalidsponsor"));
		$columns["sponsor_id"] = $parameters["id"];
		$columns["sponsor_spiele"] = $this->_websoccer->getConfig("sponsor_matches");
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_verein";
		$whereCondition = "id = %d";
		$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $teamId);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("sponsor_choose_success"), ""));
		return null; }}
class ChooseTeamController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$user = $this->_websoccer->getUser();
		if (!$this->_websoccer->getConfig("assign_team_automatically")) throw new Exception($this->_i18n->getMessage("freeclubs_msg_error"));
		if ($user->getClubId($this->_websoccer, $this->_db) > 0) throw new Exception($this->_i18n->getMessage("freeclubs_msg_error_user_is_already_manager"));
		$teamId = $parameters["teamId"];
		$fromTable = $this->_websoccer->getConfig("db_prefix") ."_verein";
		$whereCondition = "id = %d AND status = 1 AND (user_id = 0 OR user_id IS NULL OR interimmanager = '1')";
		$result = $this->_db->querySelect("id", $fromTable, $whereCondition, $teamId);
		$club = $result->fetch_array();
		if (!isset($club["id"])) throw new Exception($this->_i18n->getMessage("freeclubs_msg_error"));
		$columns = array();
		$columns["user_id"] = $user->id;
		$columns["interimmanager"] = "0";
		if (count($columns)) $this->_db->queryUpdate($columns, $fromTable, $whereCondition, $teamId);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("freeclubs_msg_success"), ""));
		return "office"; }}
class ChooseTrainerController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$user = $this->_websoccer->getUser();
		$teamId = $user->getClubId($this->_websoccer, $this->_db);
		if ($teamId < 1) throw new Exception($this->_i18n->getMessage("feature_requires_team"));
		if (TrainingDataService::countRemainingTrainingUnits($this->_websoccer, $this->_db, $teamId)) throw new Exception($this->_i18n->getMessage("training_choose_trainer_err_existing_units"));
		$trainer = TrainingDataService::getTrainerById($this->_websoccer, $this->_db, $parameters["id"]);
		if (!isset($trainer["id"])) throw new Exception("invalid ID");
		$numberOfUnits = (int) $parameters["units"];
		$totalCosts = $numberOfUnits * $trainer["salary"];
		$teamInfo = TeamsDataService::getTeamSummaryById($this->_websoccer, $this->_db, $teamId);
		if ($teamInfo["team_budget"] <= $totalCosts) throw new Exception($this->_i18n->getMessage("training_choose_trainer_err_too_expensive"));
		if ($trainer['premiumfee']) PremiumDataService::debitAmount($this->_websoccer, $this->_db, $user->id, $trainer['premiumfee'], "choose-trainer");
		BankAccountDataService::debitAmount($this->_websoccer, $this->_db, $teamId, $totalCosts, "training_trainer_salary_subject", $trainer["name"]);
		$columns["team_id"] = $teamId;
		$columns["trainer_id"] = $trainer["id"];
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_training_unit";
		for ($unitNo = 1; $unitNo <= $numberOfUnits; $unitNo++) $this->_db->queryInsert($columns, $fromTable);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("saved_message_title"), ""));
		return "training"; }}
class CreateYouthMatchRequestController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("youth_enabled") || !$this->_websoccer->getConfig("youth_matchrequests_enabled")) return NULL;
		$user = $this->_websoccer->getUser();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		if ($clubId < 1) throw new Exception($this->_i18n->getMessage("error_action_required_team"));
		$tooLateBoundary = $this->_websoccer->getNowAsTimestamp() + 3600 * 24 * (1 + $this->_websoccer->getConfig("youth_matchrequest_max_futuredays"));
		$validTimes = explode(",", $this->_websoccer->getConfig("youth_matchrequest_allowedtimes"));
		$timeIsValid = FALSE;
		$matchTime = date("H:i", $parameters["matchdate"]);
		foreach ($validTimes as $validTime) {
			if ($matchTime == trim($validTime)) {
				$timeIsValid = TRUE;
				break; }}
		if (!$timeIsValid || $parameters["matchdate"] > $tooLateBoundary) throw new Exception($this->_i18n->getMessage("youthteam_matchrequest_create_err_invaliddate"));
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_youthmatch_request";
		$result = $this->_db->querySelect("COUNT(*) AS hits", $fromTable, "team_id = %d", $clubId);
		$requests = $result->fetch_array();
		$maxNoOfRequests = (int) $this->_websoccer->getConfig("youth_matchrequest_max_open_requests");
		if ($requests && $requests["hits"] >= $maxNoOfRequests) throw new Exception($this->_i18n->getMessage("youthteam_matchrequest_create_err_too_many_open_requests", $maxNoOfRequests));
		if ($parameters["reward"]) {
			$team = TeamsDataService::getTeamSummaryById($this->_websoccer, $this->_db, $clubId);
			if ($team["team_budget"] <= $parameters["reward"]) throw new Exception($this->_i18n->getMessage("youthteam_matchrequest_create_err_budgetnotenough")); }
		if (YouthPlayersDataService::countYouthPlayersOfTeam($this->_websoccer, $this->_db, $clubId) < 11) throw new Exception($this->_i18n->getMessage("youthteam_matchrequest_create_err_notenoughplayers"));
		$maxMatchesPerDay = $this->_websoccer->getConfig("youth_match_maxperday");
		if (YouthMatchesDataService::countMatchesOfTeamOnSameDay($this->_websoccer, $this->_db, $clubId, $parameters["matchdate"]) >= $maxMatchesPerDay) throw new Exception($this->_i18n->getMessage("youthteam_matchrequest_err_maxperday_violated", $maxMatchesPerDay));
		$columns = array( "team_id" => $clubId, "matchdate" => $parameters["matchdate"], "reward" => $parameters["reward"] );
		$this->_db->queryInsert($columns, $fromTable);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("youthteam_matchrequest_create_success"), ""));
		return "youth-matchrequests"; }}
class DeleteMessageController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$id = $parameters["id"];
		$message = MessagesDataService::getMessageById($this->_websoccer, $this->_db, $id);
		if ($message == null) throw new Exception($this->_i18n->getMessage("messages_delete_invalidid"));
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_briefe";
		$whereCondition = "id = %d";
		$this->_db->queryDelete($fromTable, $whereCondition, $id);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("messages_delete_success"), ""));
		return null; }}
class DeleteProfilePictureController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("user_picture_upload_enabled")) throw new Exception("feature is not enabled.");
		$userId = $this->_websoccer->getUser()->id;
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_user";
		$whereCondition = "id = %d";
		$result = $this->_db->querySelect("picture", $fromTable, $whereCondition, $userId);
		$userinfo = $result->fetch_array();
		if (strlen($userinfo["picture"]) && file_exists(PROFPIC_UPLOADFOLDER . "/" . $userinfo["picture"])) unlink(PROFPIC_UPLOADFOLDER . "/" . $userinfo["picture"]);
		$this->_db->queryUpdate(array("picture" => ""), $fromTable, $whereCondition, $userId);
		$this->_websoccer->getUser()->setProfilePicture($this->_websoccer, null);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("delete-profile-picture_success"), ""));
		return "user"; }}
class DeleteShoutBoxMessageController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$this->_db->queryDelete($this->_websoccer->getConfig('db_prefix') . '_shoutmessage', 'id = %d', $parameters['mid']);
		return null; }}
class DirectTransferAcceptController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("transferoffers_enabled")) return;
		$clubId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		$result = $this->_db->querySelect("*", $this->_websoccer->getConfig("db_prefix") . "_transfer_offer", "id = %d AND rejected_date = 0 AND admin_approval_pending = '0'", $parameters["id"]);
		$offer = $result->fetch_array();
		$player = PlayersDataService::getPlayerById($this->_websoccer, $this->_db, $offer["player_id"]);
		if ($player["player_transfermarket"]) throw new Exception($this->_i18n->getMessage("transferoffer_err_unsellable"));
		if ($offer["offer_player1"] || $offer["offer_player2"]) {
			$team = TeamsDataService::getTeamSummaryById($this->_websoccer, $this->_db, $clubId);
			if ($offer["offer_player1"]) $this->checkExchangePlayer($offer["offer_player1"], $offer["sender_user_id"], $team["team_budget"]);
			if ($offer["offer_player2"]) $this->checkExchangePlayer($offer["offer_player2"], $offer["sender_user_id"], $team["team_budget"]); }
		$teamSize = TeamsDataService::getTeamSize($this->_websoccer, $this->_db, $clubId);
		if ($teamSize < $this->_websoccer->getConfig("transfermarket_min_teamsize")) throw new Exception($this->_i18n->getMessage("sell_player_teamsize_too_small", $teamSize));
		if ($this->_websoccer->getConfig("transferoffers_adminapproval_required")) {
			$this->_db->queryUpdate(array("admin_approval_pending" => "1"), $this->_websoccer->getConfig("db_prefix") . "_transfer_offer", "id = %d", $parameters["id"]);
			$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("transferoffer_accepted_title"), $this->_i18n->getMessage("transferoffer_accepted_message_approvalpending"))); }
		else {
			DirectTransfersDataService::executeTransferFromOffer($this->_websoccer, $this->_db, $parameters["id"]);
			$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("transferoffer_accepted_title"), $this->_i18n->getMessage("transferoffer_accepted_message"))); }
		return null; }
	function checkExchangePlayer($playerId, $userId, $teamBudget) {
		$player = PlayersDataService::getPlayerById($this->_websoccer, $this->_db, $playerId);
		$playerName = (strlen($player["player_pseudonym"])) ? $player["player_pseudonym"] : $player["player_firstname"] . " " . $player["player_lastname"];
		if ($player["player_transfermarket"]) throw new Exception($this->_i18n->getMessage("transferoffer_accept_err_exchangeplayer_on_transfermarket", $playerName));
		if ($player["team_user_id"] != $userId) throw new Exception($this->_i18n->getMessage("transferoffer_accept_err_exchangeplayer_notinteam", $playerName));
		$minBudget = 40 * $player["player_contract_salary"];
		if ($teamBudget < $minBudget) throw new Exception($this->_i18n->getMessage("transferoffer_accept_err_exchangeplayer_salarytoohigh", $playerName)); }}
class DirectTransferCancelController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db;}
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("transferoffers_enabled")) return;
		$userId = $this->_websoccer->getUser()->id;
		$result = $this->_db->querySelect("*", $this->_websoccer->getConfig("db_prefix") . "_transfer_offer", "id = %d AND sender_user_id = %d", array($parameters["id"], $userId));
		$offer = $result->fetch_array();
		if (!$offer) throw new Exception($this->_i18n->getMessage("transferoffers_offer_cancellation_notfound"));
		$this->_db->queryDelete($this->_websoccer->getConfig("db_prefix") . "_transfer_offer", "id = %d", $offer["id"]);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("transferoffers_offer_cancellation_success"), ""));
		return null; }}
class DirectTransferOfferController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("transferoffers_enabled")) return;
		$clubId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		if ($clubId == null) throw new Exception($this->_i18n->getMessage("feature_requires_team"));
		$player = PlayersDataService::getPlayerById($this->_websoccer, $this->_db, $this->_websoccer->getRequestParameter("id"));
		if (!$player["team_user_id"]) throw new Exception($this->_i18n->getMessage("transferoffer_err_nomanager"));
		if ($player["team_user_id"] == $this->_websoccer->getUser()->id) throw new Exception($this->_i18n->getMessage("transferoffer_err_ownplayer"));
		if ($player["player_unsellable"] || $player["player_transfermarket"]) throw new Exception($this->_i18n->getMessage("transferoffer_err_unsellable"));
		$this->checkIfThereAreAlreadyOpenOffersFromUser($player["team_id"]);
		$this->checkIfUserIsAllowedToSendAlternativeOffers($player["player_id"]);
		$this->checkPlayersTransferStop($player["player_id"]);
		if ($parameters["exchangeplayer1"]) $this->checkExchangePlayer($parameters["exchangeplayer1"]);
		if ($parameters["exchangeplayer2"]) $this->checkExchangePlayer($parameters["exchangeplayer2"]);
		if ($parameters["exchangeplayer1"] || $parameters["exchangeplayer2"]) {
			$teamSize = TeamsDataService::getTeamSize($this->_websoccer, $this->_db, $clubId);
			$numberOfSizeReduction = ($parameters["exchangeplayer2"]) ? 1 : 0;
			if ($teamSize < ($this->_websoccer->getConfig("transfermarket_min_teamsize") - $numberOfSizeReduction)) throw new Exception($this->_i18n->getMessage("sell_player_teamsize_too_small", $teamSize)); }
		$noOfTransactions = TransfermarketDataService::getTransactionsBetweenUsers($this->_websoccer, $this->_db, $player["team_user_id"], $this->_websoccer->getUser()->id);
		$maxTransactions = $this->_websoccer->getConfig("transfermarket_max_transactions_between_users");
		if ($noOfTransactions >= $maxTransactions) throw new Exception($this->_i18n->getMessage("transfer_bid_too_many_transactions_with_user", $noOfTransactions));
		$team = TeamsDataService::getTeamSummaryById($this->_websoccer, $this->_db, $clubId);
		$totalOffers = $this->getSumOfAllOpenOffers() + $parameters["amount"];
		if ($team["team_budget"] < $totalOffers) throw new Exception($this->_i18n->getMessage("transferoffer_err_totaloffers_too_high"));
		TeamsDataService::validateWhetherTeamHasEnoughBudgetForSalaryBid($this->_websoccer, $this->_db, $this->_i18n, $clubId, $player["player_contract_salary"]);
		DirectTransfersDataService::createTransferOffer($this->_websoccer, $this->_db, $player["player_id"], $this->_websoccer->getUser()->id, $clubId, $player["team_user_id"], $player["team_id"], $parameters["amount"], $parameters["comment"],
			$parameters["exchangeplayer1"], $parameters["exchangeplayer2"]);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("transferoffer_submitted_title"), $this->_i18n->getMessage("transferoffer_submitted_message")));
		return null; }
	function checkIfThereAreAlreadyOpenOffersFromUser($teamId) {
		if ($this->_websoccer->getConfig("transferoffers_adminapproval_required")) return;
		$result = $this->_db->querySelect("COUNT(*) AS hits", $this->_websoccer->getConfig("db_prefix") . "_transfer_offer", "rejected_date = 0 AND sender_user_id = %d AND receiver_club_id = %d", array($this->_websoccer->getUser()->id, $teamId));
		$count = $result->fetch_array();
		if ($count["hits"]) throw new Exception($this->_i18n->getMessage("transferoffer_err_open_offers_exist")); }
	function checkIfUserIsAllowedToSendAlternativeOffers($playerId) {
		$result = $this->_db->querySelect("COUNT(*) AS hits",$this->_websoccer->getConfig("db_prefix")."_transfer_offer","rejected_date>0 AND rejected_allow_alternative ='0' AND player_id = %d AND sender_user_id = %d",array($playerId, $this->_websoccer->getUser()->id));
		$count = $result->fetch_array();
		if ($count["hits"]) throw new Exception($this->_i18n->getMessage("transferoffer_err_noalternative_allowed")); }
	function checkPlayersTransferStop($playerId) {
		if ($this->_websoccer->getConfig("transferoffers_transfer_stop_days") < 1) return;
		$transferBoundary = $this->_websoccer->getNowAsTimestamp() - 24 * 3600 * $this->_websoccer->getConfig("transferoffers_transfer_stop_days");
		$result = $this->_db->querySelect("COUNT(*) AS hits", $this->_websoccer->getConfig("db_prefix") . "_transfer", "spieler_id = %d AND datum > %d", array($playerId, $transferBoundary));
		$count = $result->fetch_array();
		if ($count["hits"]) throw new Exception($this->_i18n->getMessage("transferoffer_err_transferstop", $this->_websoccer->getConfig("transferoffers_transfer_stop_days"))); }
	function checkExchangePlayer($playerId) {
		$player = PlayersDataService::getPlayerById($this->_websoccer, $this->_db, $playerId);
		$playerName = (strlen($player["player_pseudonym"])) ? $player["player_pseudonym"] : $player["player_firstname"] . " " . $player["player_lastname"];
		if ($player["player_transfermarket"] || $player["team_user_id"] != $this->_websoccer->getUser()->id) throw new Exception($this->_i18n->getMessage("transferoffer_err_exchangeplayer_on_transfermarket", $playerName));
		$result = $this->_db->querySelect("COUNT(*) AS hits", $this->_websoccer->getConfig("db_prefix") . "_transfer_offer", "rejected_date = 0 AND (offer_player1 = %d OR offer_player2 = %d)", array($playerId, $playerId, $playerId));
		$count = $result->fetch_array();
		if ($count["hits"]) throw new Exception($this->_i18n->getMessage("transferoffer_err_exchangeplayer_involved_in_other_offers", $playerName));
		try { $this->checkPlayersTransferStop($playerId); }
		catch(Exception $e) { throw new Exception($this->_i18n->getMessage("transferoffer_err_exchangeplayer_transferstop", $playerName)); }}
	function getSumOfAllOpenOffers() {
		$result = $this->_db->querySelect("SUM(offer_amount) AS amount", $this->_websoccer->getConfig("db_prefix") . "_transfer_offer", "rejected_date = 0 AND sender_user_id = %d", $this->_websoccer->getUser()->id);
		$sum = $result->fetch_array();
		if ($sum["amount"]) return $sum["amount"];
		return 0; }}
class DirectTransferRejectController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("transferoffers_enabled")) return;
		$clubId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		$result = $this->_db->querySelect("*", $this->_websoccer->getConfig("db_prefix") . "_transfer_offer", "id = %d AND receiver_club_id = %d", array($parameters["id"], $clubId));
		$offer = $result->fetch_array();
		if (!$offer) throw new Exception($this->_i18n->getMessage("transferoffers_offer_cancellation_notfound"));
		$this->_db->queryUpdate(array( "rejected_date" => $this->_websoccer->getNowAsTimestamp(), "rejected_message" => $parameters["comment"], "rejected_allow_alternative" => ($parameters["allow_alternative"]) ? 1 : 0 ),
			$this->_websoccer->getConfig("db_prefix") . "_transfer_offer", "id = %d", $offer["id"]);
		$player = PlayersDataService::getPlayerById($this->_websoccer, $this->_db, $offer["player_id"]);
		if ($player["player_pseudonym"]) $playerName = $player["player_pseudonym"];
		else $playerName = $player["player_firstname"] . " " . $player["player_lastname"];
		NotificationsDataService::createNotification($this->_websoccer, $this->_db, $offer["sender_user_id"], "transferoffer_notification_rejected", array("playername" => $playerName, "receivername" => $this->_websoccer->getUser()->username), "transferoffer",
			"transferoffers#sent");
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("transferoffers_offer_reject_success"), ""));
		return null; }}
class DisableAccountController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$clubId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		if ($clubId) $this->_db->queryUpdate(array("user_id" => '', "captain_id" => ''), $this->_websoccer->getConfig("db_prefix") . "_verein", "user_id = %d", $this->_websoccer->getUser()->id);
		$this->_db->queryUpdate(array("status" => "0"), $this->_websoccer->getConfig("db_prefix") . "_user", "id = %d", $this->_websoccer->getUser()->id);
		$authenticatorClasses = explode(",", $this->_websoccer->getConfig("authentication_mechanism"));
		foreach ($authenticatorClasses as $authenticatorClass) {
			$authenticatorClass = trim($authenticatorClass);
			if (!class_exists($authenticatorClass)) throw new Exception("Class not found: " . $authenticatorClass);
			$authenticator = new $authenticatorClass($this->_websoccer);
			$authenticator->logoutUser($this->_websoccer->getUser()); }
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("cancellation_success"), ""));
		return "home"; }}
class ExchangePremiumController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$user = $this->_websoccer->getUser();
		$exchangeRate = (int) $this->_websoccer->getConfig("premium_exchangerate_gamecurrency");
		if ($exchangeRate <= 0) throw new Exception("featue is disabled!");
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		if (!$clubId) throw new Exception($this->_i18n->getMessage("feature_requires_team"));
		$amount = $parameters["amount"];
		$balance = $user->premiumBalance;
		if ($balance < $amount) throw new Exception($this->_i18n->getMessage("premium-exchange_err_balancenotenough"));
		if ($parameters["validateonly"]) return "premium-exchange-confirm";
		BankAccountDataService::creditAmount($this->_websoccer, $this->_db, $clubId, $amount * $exchangeRate, "premium-exchange_team_subject", $user->username);
		PremiumDataService::debitAmount($this->_websoccer, $this->_db, $user->id, $amount, "exchange-premium");
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("premium-exchange_success"), ""));
		return "premiumaccount"; }}
class ExecuteTrainingController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$user = $this->_websoccer->getUser();
		$teamId = $user->getClubId($this->_websoccer, $this->_db);
		if ($teamId < 1) return null;
		$unit = TrainingDataService::getTrainingUnitById($this->_websoccer, $this->_db, $teamId, $parameters["id"]);
		if (!isset($unit["id"])) throw new Exception("invalid ID");
		if ($unit["date_executed"]) throw new Exception($this->_i18n->getMessage("training_execute_err_already_executed"));
		$previousExecution = TrainingDataService::getLatestTrainingExecutionTime($this->_websoccer, $this->_db, $teamId);
		$earliestValidExecution = $previousExecution + 3600 * $this->_websoccer->getConfig("training_min_hours_between_execution");
		$now = $this->_websoccer->getNowAsTimestamp();
		if ($now < $earliestValidExecution) throw new Exception($this->_i18n->getMessage("training_execute_err_too_early", $this->_websoccer->getFormattedDatetime($earliestValidExecution)));
		$campBookings = TrainingcampsDataService::getCampBookingsByTeam($this->_websoccer, $this->_db, $teamId);
		foreach ($campBookings as $booking) {
			if ($booking["date_start"] <= $now && $booking["date_end"] >= $now) throw new Exception($this->_i18n->getMessage("training_execute_err_team_in_training_camp")); }
		$liveMatch = MatchesDataService::getLiveMatchByTeam($this->_websoccer, $this->_db, $teamId);
		if (isset($liveMatch["match_id"])) throw new Exception($this->_i18n->getMessage("training_execute_err_match_simulating"));
		$trainer = TrainingDataService::getTrainerById($this->_websoccer, $this->_db, $unit["trainer_id"]);
		$columns["focus"] = $parameters["focus"];
		$unit["focus"] = $parameters["focus"];
		$columns["intensity"] = $parameters["intensity"];
		$unit["intensity"] = $parameters["intensity"];
		$this->trainPlayers($teamId, $trainer, $unit);
		$columns["date_executed"] = $now;
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_training_unit";
		$whereCondition = "id = %d";
		$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $unit["id"]);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("training_execute_success"), ""));
		return null; }
	function trainPlayers($teamId, $trainer, $unit) {
		$players = PlayersDataService::getPlayersOfTeamById($this->_websoccer, $this->_db, $teamId);
		$freshnessDecrease = round(1 + $unit["intensity"] / 100 * 5);
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_spieler";
		$whereCondition = "id = %d";
		$trainingEffects = array();
		foreach ($players as $player) {
			$effectFreshness = 0;
			$effectStamina = 0;
			$effectTechnique = 0;
			$effectSatisfaction = 0;
			if ($player["matches_injured"]) {
				$effectFreshness = 1;
				$effectStamina = -1; }
			else {
				if ($unit["focus"] == "FR") {
					$effectFreshness = 5;
					$effectStamina = -2;
					$effectSatisfaction = 1; }
				elseif ($unit["focus"] == "MOT") {
					$effectFreshness = 1;
					$effectStamina = -1;
					$effectSatisfaction = 5; }
				elseif ($unit["focus"] == "STA") {
					$effectSatisfaction = -1;
					$effectFreshness = -$freshnessDecrease;
					$staminaIncrease = 1;
					if ($unit["intensity"] > 50) {
						$successFactor = $unit["intensity"] * $trainer["p_stamina"] / 100;
						$pStamina[5] = $successFactor;
						$pStamina[1] = 100 - $successFactor;
						$staminaIncrease += SimulationHelper::selectItemFromProbabilities($pStamina); }
					$effectStamina = $staminaIncrease; }
				else {
					$effectFreshness = -$freshnessDecrease;
					if ($unit["intensity"] > 20) $effectStamina = 1;
					$techIncrease = 0;
					if ($unit["intensity"] > 75) {
						$successFactor = $unit["intensity"] * $trainer["p_technique"] / 100;
						$pTech[2] = $successFactor;
						$pTech[0] = 100 - $successFactor;
						$techIncrease += SimulationHelper::selectItemFromProbabilities($pTech); }
					$effectTechnique = $techIncrease; }}
			$event = new PlayerTrainedEvent($this->_websoccer, $this->_db, $this->_i18n, $player["id"], $teamId, $trainer["id"], $effectFreshness, $effectTechnique, $effectStamina, $effectSatisfaction);
			PluginMediator::dispatchEvent($event);
			$columns = array("w_frische" => min(100, max(1, $player["strength_freshness"] + $effectFreshness)), "w_technik" => min(100, max(1, $player["strength_technic"] + $effectTechnique)), "w_kondition" => min(100, max(1,
				$player["strength_stamina"] + $effectStamina)), "w_zufriedenheit" => min(100, max(1, $player["strength_satisfaction"] + $effectSatisfaction)) );
			$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $player["id"]);
			$trainingEffects[$player["id"]] = array("name" => ($player["pseudonym"]) ? $player["pseudonym"] : $player["firstname"] . " " . $player["lastname"], "freshness" => $effectFreshness, "technique" => $effectTechnique, "stamina" => $effectStamina,
				"satisfaction" => $effectSatisfaction); }
		$this->_websoccer->addContextParameter("trainingEffects", $trainingEffects); }}
class ExtendContractController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$user = $this->_websoccer->getUser();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		$player = PlayersDataService::getPlayerById($this->_websoccer, $this->_db, $parameters["id"]);
		if ($clubId != $player["team_id"]) throw new Exception("nice try");
		$satisfaction = $player["player_strength_satisfaction"];
		if ($satisfaction < MINIMUM_SATISFACTION_FOR_EXTENSION) throw new Exception($this->_i18n->getMessage("extend-contract_player_is_unhappy"));
		if ($player["player_transfermarket"]) throw new Exception($this->_i18n->getMessage("sell_player_already_on_list"));
		if ($parameters["salary"] < $player["player_contract_salary"]) throw new Exception($this->_i18n->getMessage("extend-contract_lower_than_current_salary"));
		$averageSalary = $this->getAverageSalary($player["player_strength"]);
		if ($player["player_contract_salary"] > $averageSalary) $salaryFactor = 1.10;
		else $salaryFactor = (200 - $satisfaction) / 100;
		$salaryFactor = max(1.1, $salaryFactor);
		$minSalary = round($player["player_contract_salary"] * $salaryFactor);
		if ($averageSalary < ($parameters["salary"] * 2)) {
			$minSalaryOfAverage = round(0.9 * $averageSalary);
			$minSalary = max($minSalary, $minSalaryOfAverage); }
		if ($parameters["salary"] < $minSalary) {
			$this->decreaseSatisfaction($player["player_id"], $player["player_strength_satisfaction"]);
			throw new Exception($this->_i18n->getMessage("extend-contract_salary_too_low")); }
		TeamsDataService::validateWhetherTeamHasEnoughBudgetForSalaryBid($this->_websoccer, $this->_db, $this->_i18n, $clubId, $parameters["salary"]);
		$minGoalBonus = $player["player_contract_goalbonus"] * 1.3;
		if ($parameters["goal_bonus"] < $minGoalBonus) throw new Exception($this->_i18n->getMessage("extend-contract_goalbonus_too_low"));
		$this->updatePlayer($player["player_id"], $player["player_strength_satisfaction"], $parameters["salary"], $parameters["goal_bonus"], $parameters["matches"]);
		UserInactivityDataService::resetContractExtensionField($this->_websoccer, $this->_db, $user->id);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("extend-contract_success"), ""));
		return null; }
	function getAverageSalary($playerStrength) {
		$columns = "AVG(vertrag_gehalt) AS average_salary";
		$fromTable = $this->_websoccer->getConfig("db_prefix") ."_spieler";
		$whereCondition = "w_staerke >= %d AND w_staerke <= %d AND status = 1";
		$parameters = array($playerStrength - 10, $playerStrength + 10);
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $parameters);
		$avg = $result->fetch_array();
		if (isset($avg["average_salary"])) return $avg["average_salary"];
		return $playerStrength; }
	function decreaseSatisfaction($playerId, $oldValue) {
		if ($oldValue <= SATISFACTION_DECREASE) return;
		$newValue = $oldValue - SATISFACTION_DECREASE;
		$columns["w_zufriedenheit"] = $newValue;
		$fromTable = $this->_websoccer->getConfig("db_prefix") ."_spieler";
		$whereCondition = "id = %d";
		$parameters = $playerId;
		$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $parameters); }
	function updatePlayer($playerId, $oldSatisfaction, $newSalary, $newGoalBonus, $newMatches) {
		$satisfaction = min(100, $oldSatisfaction + SATISFACTION_INCREASE);
		$columns["w_zufriedenheit"] = $satisfaction;
		$columns["vertrag_gehalt"] = $newSalary;
		$columns["vertrag_torpraemie"] = $newGoalBonus;
		$columns["vertrag_spiele"] = $newMatches;
		$fromTable = $this->_websoccer->getConfig("db_prefix") ."_spieler";
		$whereCondition = "id = %d";
		$parameters = $playerId;
		$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $parameters); }}
class ExtendStadiumController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$user = $this->_websoccer->getUser();
		$teamId = $user->getClubId($this->_websoccer, $this->_db);
		if ($teamId < 1) return null;
		if (!$parameters["side_standing"] && !$parameters["side_seats"] && !$parameters["grand_standing"] && !$parameters["grand_seats"] && !$parameters["vip"]) return null;
		$stadium = StadiumsDataService::getStadiumByTeamId($this->_websoccer, $this->_db, $teamId);
		if (!$stadium) return null;
		$seatsSide = $stadium["places_stands"] + $stadium["places_seats"] + $parameters["side_standing"] + $parameters["side_seats"];
		if ($seatsSide > $this->_websoccer->getConfig("stadium_max_side")) throw new Exception($this->_i18n->getMessage("stadium_extend_err_exceed_max_side", $this->_websoccer->getConfig("stadium_max_side")));
		$seatsGrand = $stadium["places_stands_grand"] + $stadium["places_seats_grand"] + $parameters["grand_standing"] + $parameters["grand_seats"];
		if ($seatsGrand > $this->_websoccer->getConfig("stadium_max_grand")) throw new Exception($this->_i18n->getMessage("stadium_extend_err_exceed_max_grand", $this->_websoccer->getConfig("stadium_max_grand")));
		$seatsVip = $stadium["places_vip"] + $parameters["vip"];
		if ($seatsVip > $this->_websoccer->getConfig("stadium_max_vip")) throw new Exception($this->_i18n->getMessage("stadium_extend_err_exceed_max_vip", $this->_websoccer->getConfig("stadium_max_vip")));
		if (StadiumsDataService::getCurrentConstructionOrderOfTeam($this->_websoccer, $this->_db, $teamId) != NULL) throw new Exception($this->_i18n->getMessage("stadium_extend_err_constructionongoing"));
		if (isset($parameters["validate-only"]) && $parameters["validate-only"]) return "stadium-extend-confirm";
		$builderId = $this->_websoccer->getRequestParameter("offerid");
		$offers = StadiumsDataService::getBuilderOffersForExtension($this->_websoccer, $this->_db, $teamId, (int) $this->_websoccer->getRequestParameter("side_standing"), (int) $this->_websoccer->getRequestParameter("side_seats"),
			(int) $this->_websoccer->getRequestParameter("grand_standing"), (int) $this->_websoccer->getRequestParameter("grand_seats"), (int) $this->_websoccer->getRequestParameter("vip"));
		if ($builderId == NULL || !isset($offers[$builderId])) throw new Exception("Illegal offer ID.");
		$offer = $offers[$builderId];
		$team = TeamsDataService::getTeamSummaryById($this->_websoccer, $this->_db, $teamId);
		$totalCosts = $offer["totalCosts"];
		if ($team["team_budget"] <= $totalCosts) throw new Exception($this->_i18n->getMessage("stadium_extend_err_too_expensive"));
		if ($offer["builder_premiumfee"]) PremiumDataService::debitAmount($this->_websoccer, $this->_db, $user->id, $offer["builder_premiumfee"], "extend-stadium");
		BankAccountDataService::debitAmount($this->_websoccer, $this->_db, $teamId, $totalCosts, "stadium_extend_transaction_subject", $offer["builder_name"]);
		$this->_db->queryInsert(array("team_id" => $teamId, "builder_id" => $builderId, "started" => $this->_websoccer->getNowAsTimestamp(), "deadline" => $offer["deadline"], "p_steh" => ($parameters["side_standing"]) ? $parameters["side_standing"] : 0,
			"p_sitz" => ($parameters["side_seats"]) ? $parameters["side_seats"] : 0, "p_haupt_steh" => ($parameters["grand_standing"]) ? $parameters["grand_standing"] : 0, "p_haupt_sitz" => ($parameters["grand_seats"]) ? $parameters["grand_seats"] : 0,
			"p_vip" => ($parameters["vip"]) ? $parameters["vip"] : 0 ), $this->_websoccer->getConfig("db_prefix") . "_stadium_construction");
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("stadium_extend_success"), ""));
		ActionLogDataService::createOrUpdateActionLog($this->_websoccer, $this->_db, $user->id, "extend-stadium");
		$seats = $parameters["side_standing"] + $parameters["side_seats"] + $parameters["grand_standing"] + $parameters["grand_seats"] + $parameters["vip"];
		BadgesDataService::awardBadgeIfApplicable($this->_websoccer, $this->_db, $user->id, 'stadium_construction_by_x', $seats);
		return "stadium"; }}
class FacebookLoginController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$userEmail = FacebookSdk::getInstance($this->_websoccer)->getUserEmail();
		if (!strlen($userEmail)) {
			$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_WARNING, $this->_i18n->getMessage("facebooklogin_failure"), ""));
			return "home"; }
		$userEmail = strtolower($userEmail);
		$userId = UsersDataService::getUserIdByEmail($this->_websoccer, $this->_db, $userEmail);
		if ($userId < 1) $userId = UsersDataService::createLocalUser($this->_websoccer, $this->_db, null, $userEmail);
		SecurityUtil::loginFrontUserUsingApplicationSession($this->_websoccer, $userId);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("facebooklogin_success"), ""));
		return (strlen($this->_websoccer->getUser()->username)) ? "office" : "enter-username"; }}
class FacebookLogoutController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (strlen(FacebookSdk::getInstance($this->_websoccer)->getUserEmail())) {
			$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_ERROR, $this->_i18n->getMessage("facebooklogout_failure"), ""));
			return null; }
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("facebooklogout_success"), $this->_i18n->getMessage("facebooklogout_success_details")));
		return "home"; }}
class FirePlayerController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("enable_player_resignation")) return;
		$user = $this->_websoccer->getUser();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		$player = PlayersDataService::getPlayerById($this->_websoccer, $this->_db, $parameters["id"]);
		if ($clubId != $player["team_id"]) throw new Exception("nice try");
		$teamSize = $this->getTeamSize($clubId);
		if ($teamSize <= $this->_websoccer->getConfig("transfermarket_min_teamsize")) throw new Exception($this->_i18n->getMessage("sell_player_teamsize_too_small", $teamSize));
		if ($this->_websoccer->getConfig("player_resignation_compensation_matches") > 0) {
			$compensation = $this->_websoccer->getConfig("player_resignation_compensation_matches") * $player["player_contract_salary"];
			$team = TeamsDataService::getTeamSummaryById($this->_websoccer, $this->_db, $clubId);
			if ($team["team_budget"] <= $compensation) throw new Exception($this->_i18n->getMessage("fireplayer_tooexpensive"));
			BankAccountDataService::debitAmount($this->_websoccer, $this->_db, $clubId, $compensation, "fireplayer_compensation_subject", $player["player_firstname"] . " " . $player["player_lastname"]); }
		$this->updatePlayer($player["player_id"]);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("fireplayer_success"), ""));
		return null; }
	function updatePlayer($playerId) {
		$columns["verein_id"] = "";
		$columns["vertrag_spiele"] = 0;
		$fromTable = $this->_websoccer->getConfig("db_prefix") ."_spieler";
		$whereCondition = "id = %d";
		$parameters = $playerId;
		$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $parameters); }
	function getTeamSize($clubId) {
		$columns = "COUNT(*) AS number";
		$fromTable = $this->_websoccer->getConfig("db_prefix") ."_spieler";
		$whereCondition = "verein_id = %d AND status = 1 AND transfermarkt != 1";
		$parameters = $clubId;
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $parameters);
		$players = $result->fetch_array();
		return $players["number"]; }}
class FireYouthPlayerController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("youth_enabled")) return NULL;
		$user = $this->_websoccer->getUser();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		$player = YouthPlayersDataService::getYouthPlayerById($this->_websoccer, $this->_db, $this->_i18n, $parameters["id"]);
		if ($clubId != $player["team_id"]) throw new Exception($this->_i18n->getMessage("youthteam_err_notownplayer"));
		$this->_db->queryDelete($this->_websoccer->getConfig("db_prefix") . "_youthplayer", "id = %d", $parameters["id"]);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("youthteam_fire_success"), ""));
		return "youth-team"; }}
class FormLoginController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$loginMethodClass = $this->_websoccer->getConfig("login_method");
		if (!class_exists($loginMethodClass)) throw new Exception("Login method class does not exist: " . $loginMethodClass);
		$loginMethod = new $loginMethodClass($this->_websoccer, $this->_db);
		if ($this->_websoccer->getConfig("login_type") == "email") $userId = $loginMethod->authenticateWithEmail($parameters["loginstr"], $parameters["loginpassword"]);
		else $userId = $loginMethod->authenticateWithUsername($parameters["loginstr"], $parameters["loginpassword"]);
		if (!$userId) {
			sleep(SLEEP_SECONDS_ON_FAILURE);
			throw new Exception($this->_i18n->getMessage("formlogin_invalid_data")); }
		SecurityUtil::loginFrontUserUsingApplicationSession($this->_websoccer, $userId);
		if (isset($parameters["rememberme"]) && $parameters["rememberme"] == 1) {
			$fromTable = $this->_websoccer->getConfig("db_prefix") . "_user";
			$whereCondition = "id = %d";
			$parameter = $userId;
			$result = $this->_db->querySelect("passwort_salt", $fromTable, $whereCondition, $parameter);
			$saltinfo = $result->fetch_array();
			$salt = $saltinfo["passwort_salt"];
			if (!strlen($salt)) $salt = SecurityUtil::generatePasswordSalt();
			$sessionToken = SecurityUtil::generateSessionToken($userId, $salt);
			$columns = array("tokenid" => $sessionToken, "passwort_salt" => $salt);
			$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $parameter);
			CookieHelper::createCookie("user", $sessionToken, REMEMBERME_COOKIE_LIFETIME_DAYS); }
		return (strlen($this->_websoccer->getUser()->username)) ? "office" : "enter-username"; }}
class GoogleplusLoginController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$userEmail = GoogleplusSdk::getInstance($this->_websoccer)->authenticateUser();
		if (!$userEmail) {
			$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_WARNING, $this->_i18n->getMessage("googlepluslogin_failure"), ""));
			return "home"; }
		$userEmail = strtolower($userEmail);
		$userId = UsersDataService::getUserIdByEmail($this->_websoccer, $this->_db, $userEmail);
		if ($userId < 1) $userId = UsersDataService::createLocalUser($this->_websoccer, $this->_db, null, $userEmail);
		SecurityUtil::loginFrontUserUsingApplicationSession($this->_websoccer, $userId);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("googlepluslogin_success"), ""));
		return (strlen($this->_websoccer->getUser()->username)) ? "office" : "enter-username"; }}
class LanguageSwitcherController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$lang = strtolower($parameters["lang"]);
		$this->_i18n->setCurrentLanguage($lang);
		$user = $this->_websoccer->getUser();
		if ($user->id != null) {
			$fromTable = $this->_websoccer->getConfig("db_prefix") ."_user";
			$columns = array("lang" => $lang);
			$whereCondition = "id = %d";
			$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $user->id); }
		global $msg;
		$msg = array();
		include(sprintf(CONFIGCACHE_MESSAGES, $lang));
		include(sprintf(CONFIGCACHE_ENTITYMESSAGES, $lang));
		include(sprintf(BASE_FOLDER . '/languages/messages_%s.php', $lang));
		return null; }}
class LendPlayerController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("lending_enabled")) return NULL;
		$user = $this->_websoccer->getUser();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		$player = PlayersDataService::getPlayerById($this->_websoccer, $this->_db, $parameters["id"]);
		if ($clubId != $player["team_id"]) throw new Exception($this->_i18n->getMessage("lending_err_notownplayer"));
		if ($player["lending_owner_id"] > 0) throw new Exception($this->_i18n->getMessage("lending_err_borrowed_player"));
		if ($player["lending_fee"] > 0) throw new Exception($this->_i18n->getMessage("lending_err_alreadyoffered"));
		if ($player["player_transfermarket"] > 0) throw new Exception($this->_i18n->getMessage("lending_err_on_transfermarket"));
		$teamSize = TeamsDataService::getTeamSize($this->_websoccer, $this->_db, $clubId);
		if ($teamSize <= $this->_websoccer->getConfig("transfermarket_min_teamsize")) throw new Exception($this->_i18n->getMessage("lending_err_teamsize_too_small", $teamSize));
		$minBidBoundary = round($player["player_marketvalue"] / 2);
		if ($player["player_contract_matches"] <= $this->_websoccer->getConfig("lending_matches_min")) throw new Exception($this->_i18n->getMessage("lending_err_contract_too_short"));
		$this->updatePlayer($player["player_id"], $parameters["fee"]);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("lend_player_success"), ""));
		return "myteam"; }
	function updatePlayer($playerId, $fee) {
		$columns = array("lending_fee" => $fee);
		$fromTable = $this->_websoccer->getConfig("db_prefix") ."_spieler";
		$whereCondition = "id = %d";
		$parameters = $playerId;
		$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $parameters); }}
class LogoutController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$authenticatorClasses = explode(",", $this->_websoccer->getConfig("authentication_mechanism"));
		foreach ($authenticatorClasses as $authenticatorClass) {
			$authenticatorClass = trim($authenticatorClass);
			if (!class_exists($authenticatorClass)) throw new Exception("Class not found: " . $authenticatorClass);
			$authenticator = new $authenticatorClass($this->_websoccer);
			$authenticator->logoutUser($this->_websoccer->getUser()); }
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("logout_message_title"), ""));
		return "home"; }}
class MarkAsUnsellableController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$user = $this->_websoccer->getUser();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		$player = PlayersDataService::getPlayerById($this->_websoccer, $this->_db, $parameters["id"]);
		if ($clubId != $player["team_id"]) throw new Exception("nice try");
		$columns["unsellable"] = 1;
		$fromTable = $this->_websoccer->getConfig("db_prefix") ."_spieler";
		$whereCondition = "id = %d";
		$parameters = $parameters["id"];
		$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $parameters);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("myteam_unsellable_player_success"), ""));
		return null; }}
class MicropaymentRedirectController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$projectId = trim($this->_websoccer->getConfig("micropayment_project"));
		if (!strlen($projectId)) throw new Exception("Configuration error: micropayment.de project ID is not specified.");
		$accessKey = trim($this->_websoccer->getConfig("micropayment_accesskey"));
		if (!strlen($accessKey)) throw new Exception("Configuration error: micropayment.de AccessKey is not specified.");
		$validModules = array();
		if ($this->_websoccer->getConfig("micropayment_call2pay_enabled")) $validModules[] = 'call2pay';
		if ($this->_websoccer->getConfig("micropayment_handypay_enabled")) $validModules[] = 'handypay';
		if ($this->_websoccer->getConfig("micropayment_ebank2pay_enabled")) $validModules[] = 'ebank2pay';
		if ($this->_websoccer->getConfig("micropayment_creditcard_enabled")) $validModules[] = 'creditcard';
		$module = FALSE;
		if (isset($_POST['module'])) {
			foreach ($_POST['module'] as $moduleId => $value ) $module = $moduleId; }
		if (!$module || !in_array($moduleId, $validModules)) throw new Exception('Illegal payment module.');
		$amount = $parameters['amount'];
		$priceOptions = explode(',', $this->_websoccer->getConfig('premium_price_options'));
		$validAmount = FALSE;
		if (count($priceOptions)) {
			foreach ($priceOptions as $priceOption) {
				$optionParts = explode(':', $priceOption);
				$realMoney = trim($optionParts[0]);
				if ($amount == $realMoney) $validAmount = TRUE; }}
		if (!$validAmount) throw new Exception("Invalid amount");
		if ($this->_websoccer->getConfig('premium_currency') != 'EUR') throw new Exception('Configuration Error: Only payments in EUR are supported.');
		$amount = $amount * 100;
		$paymentUrl = 'https://billing.micropayment.de/' . $module . '/event/?';
		$parameters = array('project' => $projectId, 'amount' => $amount, 'freeparam' => $this->_websoccer->getUser()->id );
		$queryStr = http_build_query($parameters);
		$seal = md5($parameters . $accessKey);
		$queryStr .= '&seal=' . $seal;
		$paymentUrl .= $queryStr;
		header('Location: '.$paymentUrl);
		exit;
		return null; }}
class MoveYouthPlayerToProfessionalController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("youth_enabled")) return NULL;
		$user = $this->_websoccer->getUser();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		$player = YouthPlayersDataService::getYouthPlayerById($this->_websoccer, $this->_db, $this->_i18n, $parameters["id"]);
		if ($clubId != $player["team_id"]) throw new Exception($this->_i18n->getMessage("youthteam_err_notownplayer"));
		if ($player["age"] < $this->_websoccer->getConfig("youth_min_age_professional")) throw new Exception($this->_i18n->getMessage("youthteam_makeprofessional_err_tooyoung", $this->_websoccer->getConfig("youth_min_age_professional")));
		if ($player["position"] == "Torwart") $validPositions = array("T");
		elseif ($player["position"] == "Abwehr") $validPositions = array("LV", "IV", "RV");
		elseif ($player["position"] == "Mittelfeld") $validPositions = array("LM", "RM", "DM", "OM", "ZM");
		else $validPositions = array("LS", "RS", "MS");
		if (!in_array($parameters["mainposition"], $validPositions)) throw new Exception($this->_i18n->getMessage("youthteam_makeprofessional_err_invalidmainposition"));
		$team = TeamsDataService::getTeamSummaryById($this->_websoccer, $this->_db, $clubId);
		if ($team["team_budget"] <= TeamsDataService::getTotalPlayersSalariesOfTeam($this->_websoccer, $this->_db, $clubId)) throw new Exception($this->_i18n->getMessage("youthteam_makeprofessional_err_budgettooless"));
		$this->createPlayer($player, $parameters["mainposition"]);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("youthteam_makeprofessional_success"), ""));
		return "myteam"; }
	function createPlayer($player, $mainPosition) {
		$time = strtotime("-". $player["age"] . " years", $this->_websoccer->getNowAsTimestamp());
		$birthday = date("Y-m-d", $time);
		$columns = array("verein_id" => $player["team_id"], "vorname" => $player["firstname"], "nachname" => $player["lastname"], "geburtstag" => $birthday, "age" => $player["age"], "position" => $player["position"], "position_main" => $mainPosition,
			"nation" => $player["nation"], "w_staerke" => $player["strength"], "w_technik" => $this->_websoccer->getConfig("youth_professionalmove_technique"), "w_kondition" => $this->_websoccer->getConfig("youth_professionalmove_stamina"),
			"w_frische" => $this->_websoccer->getConfig("youth_professionalmove_freshness"), "w_zufriedenheit" => $this->_websoccer->getConfig("youth_professionalmove_satisfaction"),
			"vertrag_gehalt" => $this->_websoccer->getConfig("youth_salary_per_strength") * $player["strength"], "vertrag_spiele" => $this->_websoccer->getConfig("youth_professionalmove_matches"), "vertrag_torpraemie" => 0, "status" => "1" );
		$this->_db->queryInsert($columns, $this->_websoccer->getConfig("db_prefix") ."_spieler");
		$this->_db->queryDelete($this->_websoccer->getConfig("db_prefix") ."_youthplayer", "id = %d", $player["id"]); }}
class OrderBuildingController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$buildingId = $parameters['id'];
		$user = $this->_websoccer->getUser();
		$teamId = $user->getClubId($this->_websoccer, $this->_db);
		if (!$teamId) throw new Exception($this->_i18n->getMessage("feature_requires_team"));
		$dbPrefix = $this->_websoccer->getConfig('db_prefix');
		$result = $this->_db->querySelect('*', $dbPrefix . '_stadiumbuilding', 'id = %d', $buildingId);
		$building = $result->fetch_array();
		if (!$building) throw new Exception('illegal building.');
		$team = TeamsDataService::getTeamSummaryById($this->_websoccer, $this->_db, $teamId);
		if ($team['team_budget'] <= $building['costs']) throw new Exception($this->_i18n->getMessage('stadiumenvironment_build_err_too_expensive'));
		$result = $this->_db->querySelect('*', $dbPrefix . '_buildings_of_team', 'team_id = %d AND building_id = %d', array($teamId, $buildingId));
		$buildingExists = $result->fetch_array();
		if ($buildingExists) throw new Exception($this->_i18n->getMessage('stadiumenvironment_build_err_already_exists'));
		if ($building['required_building_id']) {
			$result = $this->_db->querySelect('*', $dbPrefix . '_buildings_of_team', 'team_id = %d AND building_id = %d', array($teamId, $building['required_building_id']));
			$requiredBuildingExists = $result->fetch_array();
			if (!$requiredBuildingExists) throw new Exception($this->_i18n->getMessage('stadiumenvironment_build_err_requires_building')); }
		if ($building['premiumfee'] > $user->premiumBalance) throw new Exception($this->_i18n->getMessage('stadiumenvironment_build_err_premium_balance'));
		BankAccountDataService::debitAmount($this->_websoccer, $this->_db, $teamId, $building['costs'], 'building_construction_fee_subject', $building['name']);
		$constructionDeadline = $this->_websoccer->getNowAsTimestamp() + $building['construction_time_days'] * 24 * 3600;
		$this->_db->queryInsert(array('building_id' => $buildingId, 'team_id' => $teamId, 'construction_deadline' => $constructionDeadline ), $dbPrefix . '_buildings_of_team');
		if ($building['premiumfee']) PremiumDataService::debitAmount($this->_websoccer, $this->_db, $user->id, $building['premiumfee'], "order-building");
		if ($building['effect_fanpopularity'] != 0) {
			$result = $this->_db->querySelect('fanbeliebtheit', $dbPrefix . '_user', 'id = %d', $user->id, 1);
			$userinfo = $result->fetch_array();
			$popularity = min(100, max(1, $building['effect_fanpopularity'] + $userinfo['fanbeliebtheit']));
			$this->_db->queryUpdate(array('fanbeliebtheit' => $popularity), $dbPrefix . '_user', 'id = %d', $user->id); }
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("stadiumenvironment_build_success"), ""));
		return null; }}
class PaypalPaymentNotificationController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$req = 'cmd=_notify-validate';
		foreach ($_POST as $key => $value) {
			$value = urlencode(stripslashes($value));
			$req .= "&$key=$value"; }
		$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Host: ". $this->_websoccer->getConfig("paypal_host") . "\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
		$fp = fsockopen ($this->_websoccer->getConfig("paypal_url"), 443, $errno, $errstr, 30);
		if (!$fp) throw new Exception("Error on HTTP(S) request. Error: " . $errno . " " . $errstr);
		else {
			fputs ($fp, $header . $req);
			$response = "";
			while (!feof($fp)) {
				$res = fgets ($fp, 1024);
				$response .= $res;
				if (strcmp ($res, "VERIFIED") == 0) {
					if (strtolower($parameters["receiver_email"]) != strtolower($this->_websoccer->getConfig("paypal_receiver_email"))) {
						EmailHelper::sendSystemEmail($this->_websoccer, $this->_websoccer->getConfig("systememail"), "Failed PayPal confirmation: Invalid Receiver", "Invalid receiver: " . $parameters["receiver_email"]);
						throw new Exception("Receiver E-Mail not correct."); }
					if ($parameters["payment_status"] != "Completed") {
						EmailHelper::sendSystemEmail($this->_websoccer, $this->_websoccer->getConfig("systememail"), "Failed PayPal confirmation: Invalid Status", "A paypment notification has been sent, but has an invalid status: " . $parameters["payment_status"]);
						throw new Exception("Payment status not correct."); }
					$amount = $parameters["mc_gross"];
					$userId = $parameters["custom"];
					PremiumDataService::createPaymentAndCreditPremium($this->_websoccer, $this->_db, $userId, $amount, "paypal-notify");
					die(200); }
				elseif (strcmp ($res, "INVALID") == 0) throw new Exception("Payment is invalid"); }
			fclose ($fp);
			header('X-Error-Message: invalid paypal response: ' . $response, true, 500);
			die('X-Error-Message: invalid paypal response: ' . $response); }
		return null; }}
class PremiumActionDummyController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, "Premium action completed", "testparam1: " . $parameters["testparam1"] . " - testparam2: " . $parameters["testparam2"]));
		return null; }}
class RegisterFormController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("allow_userregistration")) throw new Exception($this->_i18n->getMessage("registration_disabled"));
		$illegalUsernames = explode(",", strtolower(str_replace(", ", ",", $this->_websoccer->getConfig("illegal_usernames"))));
		if (array_search(strtolower($parameters["nick"]), $illegalUsernames)) throw new Exception($this->_i18n->getMessage("registration_illegal_username"));
		if ($parameters["email"] !== $parameters["email_repeat"]) throw new Exception($this->_i18n->getMessage("registration_repeated_email_notmatching"));
		if ($parameters["pswd"] !== $parameters["pswd_repeat"]) throw new Exception($this->_i18n->getMessage("registration_repeated_password_notmatching"));
		if ($this->_websoccer->getConfig("register_use_captcha") && strlen($this->_websoccer->getConfig("register_captcha_publickey")) && strlen($this->_websoccer->getConfig("register_captcha_privatekey"))) {
			include_once(BASE_FOLDER . "/lib/recaptcha/recaptchalib.php");
			$captchaResponse = recaptcha_check_answer($this->_websoccer->getConfig("register_captcha_privatekey"), $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
			if (!$captchaResponse->is_valid) throw new Exception($this->_i18n->getMessage("registration_invalidcaptcha")); }
		$columns = "COUNT(*) AS hits";
		$fromTable = $this->_websoccer->getConfig("db_prefix") ."_user";
		$maxNumUsers = (int) $this->_websoccer->getConfig("max_number_of_users");
		if ($maxNumUsers > 0) {
			$wherePart = "status = 1";
			$result = $this->_db->querySelect($columns, $fromTable, $wherePart);
			$rows = $result->fetch_array();
			if ($rows["hits"] >= $maxNumUsers) throw new Exception($this->_i18n->getMessage("registration_max_number_users_exceeded")); }
		$wherePart = "UPPER(nick) = '%s' OR UPPER(email) = '%s'";
		$result = $this->_db->querySelect($columns, $fromTable, $wherePart, array(strtoupper($parameters["nick"]), strtoupper($parameters["email"])));
		$rows = $result->fetch_array();
		if ($rows["hits"]) throw new Exception($this->_i18n->getMessage("registration_user_exists"));
		$this->_createUser($parameters, $fromTable);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("register-success_message_title"), $this->_i18n->getMessage("register-success_message_content")));
		return "register-success"; }
	function _createUser($parameters, $fromTable) {
		$dbcolumns = array();
		$dbcolumns["nick"] = $parameters["nick"];
		$dbcolumns["email"] = strtolower($parameters["email"]);
		$dbcolumns["passwort_salt"] = SecurityUtil::generatePasswordSalt();
		$dbcolumns["passwort"] = SecurityUtil::hashPassword($parameters["pswd"], $dbcolumns["passwort_salt"]);
		$dbcolumns["datum_anmeldung"] = $this->_websoccer->getNowAsTimestamp();
		$dbcolumns["schluessel"] = str_replace("&", "_", SecurityUtil::generatePassword());
		$dbcolumns["status"] = 2;
		$dbcolumns["lang"] = $this->_i18n->getCurrentLanguage();
		if ($this->_websoccer->getConfig("premium_initial_credit")) $dbcolumns["premium_balance"] = $this->_websoccer->getConfig("premium_initial_credit");
		$this->_db->queryInsert($dbcolumns, $fromTable);
		$columns = "id";
		$wherePart = "email = '%s'";
		$result = $this->_db->querySelect($columns, $fromTable, $wherePart, $dbcolumns["email"]);
		$newuser = $result->fetch_array();
		$querystr = "key=" . $dbcolumns["schluessel"] ."&userid=" . $newuser["id"];
		$tplparameters["activationlink"] = $this->_websoccer->getInternalActionUrl("activate", $querystr, "activate-user", TRUE);
		EmailHelper::sendSystemEmailFromTemplate($this->_websoccer, $this->_i18n, $dbcolumns["email"], $this->_i18n->getMessage("activation_email_subject"), "useractivation", $tplparameters);
		$event = new UserRegisteredEvent($this->_websoccer, $this->_db, $this->_i18n, $newuser["id"], $dbcolumns["nick"], $dbcolumns["email"]);
		PluginMediator::dispatchEvent($event); }}
class RemoveFormationTemplateController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$teamId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		$dbTable = $this->_websoccer->getConfig('db_prefix') . '_aufstellung';
		$result = $this->_db->querySelect('verein_id', $dbTable, 'id = %d', $parameters['templateid']);
		$template = $result->fetch_array();
		if (!$template || $template['verein_id'] != $teamId) throw new Exception('illegal template ID');
		$this->_db->queryDelete($dbTable, 'id = %d', $parameters['templateid']);
		return null; }}
class RemoveNationalPlayerController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("nationalteams_enabled")) return NULL;
		$teamId = NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer, $this->_db);
		if (!$teamId) throw new Exception($this->_i18n->getMessage("nationalteams_user_requires_team"));
		$result = $this->_db->querySelect("name", $this->_websoccer->getConfig("db_prefix") . "_verein", "id = %d", $teamId);
		$team = $result->fetch_array();
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_spieler";
		$result = $this->_db->querySelect("nation", $fromTable, "id = %d", $parameters["id"]);
		$player = $result->fetch_array();
		if (!$player) throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		if ($player["nation"] != $team["name"]) throw new Exception("Player is from different nation.");
		$this->_db->queryDelete($this->_websoccer->getConfig("db_prefix") . "_nationalplayer", "player_id = %d AND team_id = %d", array($parameters["id"], $teamId));
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("nationalteams_removeplayer_success"), ""));
		return "nationalteam"; }}
class RemovePlayerFromTransfermarketController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("transfermarket_enabled")) return NULL;
		$user = $this->_websoccer->getUser();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		$player = PlayersDataService::getPlayerById($this->_websoccer, $this->_db, $parameters["id"]);
		if ($clubId != $player["team_id"]) throw new Exception("nice try");
		$highestBid = TransfermarketDataService::getHighestBidForPlayer($this->_websoccer, $this->_db, $parameters["id"], $player["transfer_start"], $player["transfer_end"]);
		if ($highestBid) throw new Exception($this->_i18n->getMessage("transfermarket_remove_err_bidexists"));
		$this->_db->queryUpdate(array('transfermarkt' => '0', 'transfer_start' => 0, 'transfer_ende' => 0 ), $this->_websoccer->getConfig('db_prefix') . '_spieler', 'id = %d', $parameters['id']);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("transfermarket_remove_success"), ""));
		return "myteam"; }}
class RemoveYouthPlayerFromMarketController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("youth_enabled")) return NULL;
		$user = $this->_websoccer->getUser();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		$player = YouthPlayersDataService::getYouthPlayerById($this->_websoccer, $this->_db, $this->_i18n, $parameters["id"]);
		if ($clubId != $player["team_id"]) throw new Exception($this->_i18n->getMessage("youthteam_err_notownplayer"));
		$this->updatePlayer($parameters["id"]);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("youthteam_removefrommarket_success"), ""));
		return "youth-team"; }
	function updatePlayer($playerId) {
		$columns = array("transfer_fee" => 0);
		$fromTable = $this->_websoccer->getConfig("db_prefix") ."_youthplayer";
		$whereCondition = "id = %d";
		$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $playerId); }}
class RenameClubController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig('rename_club_enabled')) throw new Exceltion("feature is disabled");
		$user = $this->_websoccer->getUser();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		$team = TeamsDataService::getTeamSummaryById($this->_websoccer, $this->_db, $clubId);
		if (!$team) return null;
		$short = strtoupper($parameters['kurz']);
		$this->_db->queryUpdate(array('name' => $parameters['name'], 'kurz' => $short), $this->_websoccer->getConfig('db_prefix') . '_verein', 'id = %d', $clubId);
		$this->_db->queryUpdate(array('S.name' => $parameters['stadium']), $this->_websoccer->getConfig('db_prefix') . '_verein AS C INNER JOIN ' . $this->_websoccer->getConfig('db_prefix') . '_stadion AS S ON S.id = C.stadion_id', 'C.id = %d', $clubId);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("rename-club_success"), ""));
		return 'league'; }}
class ReportAbsenceController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$userId = $this->_websoccer->getUser()->id;
		$deputyId = UsersDataService::getUserIdByNick($this->_websoccer, $this->_db, $parameters["deputynick"]);
		if ($deputyId < 1) throw new Exception($this->_i18n->getMessage("absence_err_invaliddeputy"));
		if ($userId == $deputyId) throw new Exception($this->_i18n->getMessage("absence_err_deputyisself"));
		AbsencesDataService::makeUserAbsent($this->_websoccer, $this->_db, $userId, $deputyId, $parameters['days']);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("absence_report_success"), ""));
		return null; }}
class ReturnFromAbsenceController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$userId = $this->_websoccer->getUser()->id;
		AbsencesDataService::confirmComeback($this->_websoccer, $this->_db, $userId);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("absence_return_success"), ""));
		return "office"; }}
class SaveFormationController {
	private $_addedPlayers;
	private $_isNationalTeam;
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db;
		$this->_addedPlayers = array();
		$this->_isNationalTeam = ($websoccer->getRequestParameter('nationalteam')) ? TRUE : FALSE; }
	function executeAction($parameters) {
		$user = $this->_websoccer->getUser();
		if ($this->_isNationalTeam) $teamId = NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer, $this->_db);
		else $teamId = $user->getClubId($this->_websoccer, $this->_db);
		$nextMatches = MatchesDataService::getNextMatches($this->_websoccer, $this->_db, $teamId, $this->_websoccer->getConfig('formation_max_next_matches'));
		if (!count($nextMatches)) throw new Exception($this->_i18n->getMessage('formation_err_nonextmatch'));
		$matchId = $parameters['id'];
		foreach ($nextMatches as $nextMatch) {
			if ($nextMatch['match_id'] == $matchId) {
				$matchinfo = $nextMatch;
				break; }}
		if (!isset($matchinfo)) throw new Exception('illegal match id');
		$players = PlayersDataService::getPlayersOfTeamById($this->_websoccer, $this->_db, $teamId, $this->_isNationalTeam, $matchinfo['match_type'] == 'cup', $matchinfo['match_type'] != 'friendly');
		$this->validatePlayer($parameters['player1'], $players);
		$this->validatePlayer($parameters['player2'], $players);
		$this->validatePlayer($parameters['player3'], $players);
		$this->validatePlayer($parameters['player4'], $players);
		$this->validatePlayer($parameters['player5'], $players);
		$this->validatePlayer($parameters['player6'], $players);
		$this->validatePlayer($parameters['player7'], $players);
		$this->validatePlayer($parameters['player8'], $players);
		$this->validatePlayer($parameters['player9'], $players);
		$this->validatePlayer($parameters['player10'], $players);
		$this->validatePlayer($parameters['player11'], $players);
		$this->validatePlayer($parameters['bench1'], $players, TRUE);
		$this->validatePlayer($parameters['bench2'], $players, TRUE);
		$this->validatePlayer($parameters['bench3'], $players, TRUE);
		$this->validatePlayer($parameters['bench4'], $players, TRUE);
		$this->validatePlayer($parameters['bench5'], $players, TRUE);
		$validSubstitutions = array();
		for ($subNo = 1; $subNo <= 3; $subNo++) {
			$playerIn = $parameters['sub' . $subNo .'_in'];
			$playerOut = $parameters['sub' . $subNo .'_out'];
			$playerMinute = $parameters['sub' . $subNo .'_minute'];
			if ($playerIn != null && $playerIn > 0 && $playerOut != null && $playerOut > 0 && $playerMinute != null && $playerMinute > 0) {
				$this->validateSubstitution($playerIn, $playerOut, $playerMinute, $players);
				$validSubstitutions[] = $subNo; }}
		$this->saveFormation($teamId, $matchinfo['match_id'], $parameters, $validSubstitutions);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage('saved_message_title'), ''));
		return null; }
	function validatePlayer($playerId, $players, $bench = FALSE) {
		if ($playerId == null || $playerId == 0) return;
		if (!isset($players[$playerId])) throw new Exception($this->_i18n->getMessage('formation_err_invalidplayer'));
		$position = $players[$playerId]['position'];
		if (isset($this->_addedPlayers[$position][$playerId])) throw new Exception($this->_i18n->getMessage('formation_err_duplicateplayer'));
		if ($players[$playerId]['matches_injured'] > 0 || $players[$playerId]['matches_blocked'] > 0) throw new Exception($this->_i18n->getMessage('formation_err_blockedplayer'));
		$this->_addedPlayers[$position][$playerId] = TRUE; }
	function validateSubstitution($playerIn, $playerOut, $minute, $players) {
		if (!isset($players[$playerIn]) || !isset($players[$playerOut]) || !isset($this->_addedPlayers[$players[$playerIn]['position']][$playerIn]) || !isset($this->_addedPlayers[$players[$playerOut]['position']][$playerOut])) {
			throw new Exception($this->_i18n->getMessage('formation_err_invalidplayer')); }
		if ($minute < 2 || $minute > 90) throw new Exception($this->_i18n->getMessage('formation_err_invalidsubstitutionminute')); }
	function saveFormation($teamId, $matchId, $parameters, $validSubstitutions) {
		$fromTable = $this->_websoccer->getConfig('db_prefix') .'_aufstellung';
		$columns['verein_id'] = $teamId;
		$columns['datum'] = $this->_websoccer->getNowAsTimestamp();
		$columns['offensive'] = $parameters['offensive'];
		$columns['setup'] = $parameters['setup'];
		$columns['longpasses'] = $parameters['longpasses'];
		$columns['counterattacks'] = $parameters['counterattacks'];
		$columns['freekickplayer'] = $parameters['freekickplayer'];
		for ($playerNo = 1; $playerNo <= 11; $playerNo++) {
			$columns['spieler' . $playerNo] = $parameters['player' . $playerNo];
			$columns['spieler' . $playerNo . '_position'] = $parameters['player' . $playerNo . '_pos']; }
		for ($playerNo = 1; $playerNo <= 5; $playerNo++) $columns['ersatz' . $playerNo] = $parameters['bench' . $playerNo];
		for ($subNo = 1; $subNo <= 3; $subNo++) {
			if (in_array($subNo, $validSubstitutions)) {
				$columns['w'. $subNo . '_raus'] = $parameters['sub' . $subNo .'_out'];
				$columns['w'. $subNo . '_rein'] = $parameters['sub' . $subNo .'_in'];
				$columns['w'. $subNo . '_minute'] = $parameters['sub' . $subNo .'_minute'];
				$columns['w'. $subNo . '_condition'] = $parameters['sub' . $subNo .'_condition'];
				$columns['w'. $subNo . '_position'] = $parameters['sub' . $subNo .'_position']; }
			else {
				$columns['w'. $subNo . '_raus'] = '';
				$columns['w'. $subNo . '_rein'] = '';
				$columns['w'. $subNo . '_minute'] = '';
				$columns['w'. $subNo . '_condition'] = '';
				$columns['w'. $subNo . '_position'] = ''; }}
		$result = $this->_db->querySelect('id', $fromTable, 'verein_id = %d AND match_id = %d', array($teamId, $matchId));
		$existingFormation = $result->fetch_array();
		if (isset($existingFormation['id'])) $this->_db->queryUpdate($columns, $fromTable, 'id = %d', $existingFormation['id']);
		else {
			$columns['match_id'] = $matchId;
			$this->_db->queryInsert($columns, $fromTable); }
		if (strlen($parameters['templatename'])) {
			$result = $this->_db->querySelect('COUNT(*) AS templates', $fromTable, 'verein_id = %d AND templatename IS NOT NULL', $teamId);
			$existingTemplates = $result->fetch_array();
			if ($existingTemplates && $existingTemplates['templates'] >= $this->_websoccer->getConfig('formation_max_templates')) {
				$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_WARNING,
					$this->_i18n->getMessage('formation_template_saving_failed_because_boundary_title', $this->_websoccer->getConfig('formation_max_templates')),
					$this->_i18n->getMessage('formation_template_saving_failed_because_boundary_details'))); }
			else {
				$columns['match_id'] = NULL;
				$columns['templatename'] = $parameters['templatename'];
				$this->_db->queryInsert($columns, $fromTable); }}}}
class SaveMatchChangesController {
	private $_addedPlayers;
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db;
		$this->_addedPlayers = array(); }
	function executeAction($parameters) {
		$user = $this->_websoccer->getUser();
		$teamId = $user->getClubId($this->_websoccer, $this->_db);
		$nationalTeamId = NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer, $this->_db);
		$matchId = $parameters['id'];
		$matchinfo = MatchesDataService::getMatchSubstitutionsById($this->_websoccer, $this->_db, $matchId);
		if (!isset($matchinfo['match_id'])) throw new Exception($this->_i18n->getMessage('formation_err_nonextmatch'));
		if ($matchinfo['match_home_id'] != $teamId && $matchinfo['match_guest_id'] != $teamId && $matchinfo['match_home_id'] != $nationalTeamId && $matchinfo['match_guest_id'] != $nationalTeamId) throw new Exception('nice try');
		if ($matchinfo['match_simulated']) throw new Exception($this->_i18n->getMessage('match_details_match_completed'));
		$columns = array();
		$teamPrefix = ($matchinfo['match_home_id'] == $teamId || $matchinfo['match_home_id'] == $nationalTeamId) ? 'home' : 'guest';
		$teamPrefixDb = ($matchinfo['match_home_id'] == $teamId || $matchinfo['match_home_id'] == $nationalTeamId) ? 'home' : 'gast';
		$occupiedSubPos = array();
		$existingFutureSubs = array();
		for ($subNo = 1; $subNo <= 3; $subNo++) {
			$existingMinute = (int) $matchinfo[$teamPrefix . '_sub'. $subNo . '_minute'];
			if ($existingMinute > 0 && $existingMinute <= $matchinfo['match_minutes']) $occupiedSubPos[$subNo] = TRUE;
			elseif ($existingMinute > 0) {
				$existingFutureSubs[$matchinfo[$teamPrefix . '_sub'. $subNo . '_out']] = array('minute' => $matchinfo[$teamPrefix . '_sub'. $subNo . '_minute'], 'in' => $matchinfo[$teamPrefix . '_sub'. $subNo . '_in'],
					'condition' => $matchinfo[$teamPrefix . '_sub'. $subNo . '_condition'], 'position' => $matchinfo[$teamPrefix . '_sub'. $subNo . '_position'], 'slot' => $subNo ); }}
		if (count($occupiedSubPos) < 3) {
			$nextPossibleMinute = $matchinfo['match_minutes'] + $this->_websoccer->getConfig('sim_interval') + 1;
			for ($subNo = 1; $subNo <= 3; $subNo++) {
				$newOut = (int) $parameters['sub'. $subNo . '_out'];
				$newIn = (int) $parameters['sub'. $subNo . '_in'];
				$newMinute = (int) $parameters['sub'. $subNo . '_minute'];
				$newCondition = $parameters['sub'. $subNo . '_condition'];
				$newPosition = $parameters['sub'. $subNo . '_position'];
				$slot = FALSE;
				$saveSub = TRUE;
				if (isset($existingFutureSubs[$newOut]) && $newIn == $existingFutureSubs[$newOut]['in'] && $newCondition == $existingFutureSubs[$newOut]['condition'] && $newMinute == $existingFutureSubs[$newOut]['minute']
					&& $newPosition == $existingFutureSubs[$newOut]['position']) {
					$saveSub = FALSE; }
				for ($slotNo = 1; $slotNo <= 3; $slotNo++) {
					if (!isset($occupiedSubPos[$slotNo])) {
						$slot = $slotNo;
						break; }}
				if ($slot && $newOut && $newIn && $newMinute) {
					if ($saveSub && $newMinute < $nextPossibleMinute) {
						$newMinute = $nextPossibleMinute;
						$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_WARNING, '', $this->_i18n->getMessage('match_details_changes_too_late_altered', $subNo))); }
					$columns[$teamPrefixDb . '_w'. $slot. '_raus'] = $newOut;
					$columns[$teamPrefixDb . '_w'. $slot. '_rein'] = $newIn;
					$columns[$teamPrefixDb . '_w'. $slot. '_minute'] = $newMinute;
					$columns[$teamPrefixDb . '_w'. $slot. '_condition'] = $newCondition;
					$columns[$teamPrefixDb . '_w'. $slot. '_position'] = $newPosition;
					$occupiedSubPos[$slot] = TRUE; }}}
		$prevOffensive = $matchinfo['match_'. $teamPrefix .'_offensive'];
		$prevLongpasses = $matchinfo['match_'. $teamPrefix .'_longpasses'];
		$prevCounterattacks = $matchinfo['match_'. $teamPrefix .'_counterattacks'];
		if (!$prevLongpasses) $prevLongpasses = '0';
		if (!$prevCounterattacks) $prevCounterattacks = '0';
		if ($prevOffensive !== $parameters['offensive'] || $prevLongpasses !== $parameters['longpasses'] || $prevCounterattacks !== $parameters['counterattacks']) {
			$alreadyChanged = $matchinfo['match_'. $teamPrefix .'_offensive_changed'];
			if ($alreadyChanged >= $this->_websoccer->getConfig('sim_allow_offensivechanges')) throw new Exception($this->_i18n->getMessage('match_details_changes_too_often', $this->_websoccer->getConfig('sim_allow_offensivechanges')));
			$columns[$teamPrefixDb .'_offensive'] = $parameters['offensive'];
			$columns[$teamPrefixDb .'_longpasses'] = $parameters['longpasses'];
			$columns[$teamPrefixDb .'_counterattacks'] = $parameters['counterattacks'];
			$columns[$teamPrefixDb .'_offensive_changed'] = $alreadyChanged + 1;
			$this->_createMatchReportMessage($user, $matchId, $matchinfo['match_minutes'], ($teamPrefix == 'home')); }
		$prevFreekickPlayer = $matchinfo['match_'. $teamPrefix .'_freekickplayer'];
		if ($parameters['freekickplayer'] && $parameters['freekickplayer'] != $prevFreekickPlayer) $columns[$teamPrefixDb .'_freekickplayer'] = $parameters['freekickplayer'];
		if (count($columns)) {
			$fromTable = $this->_websoccer->getConfig('db_prefix') . '_spiel';
			$whereCondition = 'id = %d';
			$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $matchId); }
		$this->_updatePlayerPosition($parameters, $matchId, $teamId);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage('saved_message_title'), ''));
		return "match"; }
	function _updatePlayerPosition($parameters, $matchId, $teamId) {
		$players = MatchesDataService::getMatchPlayerRecordsByField($this->_websoccer, $this->_db, $matchId, $teamId);
		$playersOnField = $players['field'];
		$submittedPositions = array();
		for ($playerNo = 1; $playerNo <= 11; $playerNo++) {
			$playerId = $parameters['player' . $playerNo];
			$playerPos = $parameters['player' . $playerNo . '_pos'];
			if ($playerId && $playerPos) $submittedPositions[$playerId] = $playerPos; }
		$updateTable = $this->_websoccer->getConfig('db_prefix') . '_spiel_berechnung';
		$whereCondition = 'id = %d';
		$setupMainMapping = array('T' => 'Torwart', 'LV' => 'Abwehr', 'RV' => 'Abwehr', 'IV' => 'Abwehr', 'DM' => 'Mittelfeld', 'LM' => 'Mittelfeld', 'ZM' => 'Mittelfeld', 'RM' => 'Mittelfeld', 'OM' => 'Mittelfeld', 'LS' => 'Sturm', 'MS' => 'Sturm', 'RS' => 'Sturm');
		foreach ($playersOnField as $player) {
			if (isset($submittedPositions[$player['id']])) {
				$newPos = $submittedPositions[$player['id']];
				$oldPos = $player['match_position_main'];
				if ($newPos != $oldPos) {
					$position = $setupMainMapping[$newPos];
					$strength = $player['strength'];
					if ($player['position'] != $position && $player['position_main'] != $newPos && $player['position_second'] != $newPos) $strength = round($strength * (1 - $this->_websoccer->getConfig('sim_strength_reduction_wrongposition') / 100));
					elseif (strlen($player['position_main']) && $player['position_main'] != $newPos && ($player['position'] == $position || $player['position_second'] == $newPos)) {
						$strength = round($strength * (1 - $this->_websoccer->getConfig('sim_strength_reduction_secondary') / 100)); }
					$this->_db->queryUpdate(array('position_main' => $newPos, 'position' => $position, 'w_staerke' => $strength), $updateTable, $whereCondition, $player['match_record_id']); }}}}
	function _createMatchReportMessage(User $user, $matchId, $minute, $isHomeTeam) {
		$result = $this->_db->querySelect('id', $this->_websoccer->getConfig('db_prefix') . '_spiel_text', 'aktion = \'Taktikaenderung\'');
		$messages = array();
		while ($message = $result->fetch_array()) $messages[] = $message['id'];
		if (!count($messages)) return;
		$messageId = $messages[array_rand($messages)];
		$this->_db->queryInsert(array('match_id' => $matchId, 'message_id' => $messageId, 'minute' => $minute, 'active_home' => $isHomeTeam, 'playernames' => $user->username ), $this->_websoccer->getConfig('db_prefix') . '_matchreport'); }}
class SaveProfileController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$user = $this->_websoccer->getUser();
		$columns = array();
		if ($parameters["newpassword"] != null) {
			$salt = SecurityUtil::generatePasswordSalt();
			$hashedPassword = SecurityUtil::hashPassword($parameters["newpassword"], $salt);
			$columns["passwort_salt"] = $salt;
			$columns["passwort"] = $hashedPassword; }
		if ($parameters["newemail"] != null) {
			$activationKey = SecurityUtil::generatePassword();
			$columns["schluessel"] = $activationKey;
			$columns["status"] = 2;
			$columns["email"] = $parameters["newemail"];
			$user->email = $parameters["newemail"];
			$querystr = "key=" . $columns["schluessel"] ."&userid=" . $user->id;
			$tplparameters["activationlink"] = $this->_websoccer->getInternalActionUrl("activate", $querystr, "activate-user", TRUE);
			EmailHelper::sendSystemEmailFromTemplate($this->_websoccer, $this->_i18n,
			$user->email,
			$this->_i18n->getMessage("activation_changedemail_subject"),
			"changed_email_activation",
			$tplparameters);
			$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_WARNING, $this->_i18n->getMessage("profile_changedemail_message_title"), $this->_i18n->getMessage("profile_changedemail_message_content"))); }
		$columns["name"] = $parameters["realname"];
		$columns["wohnort"] = $parameters["place"];
		$columns["land"] = $parameters["country"];
		$columns["beruf"] = $parameters["occupation"];
		$columns["interessen"] = $parameters["interests"];
		$columns["lieblingsverein"] = $parameters["favorite_club"];
		$columns["homepage"] = $parameters["homepage"];
		$columns["c_hideinonlinelist"] = $parameters["c_hideinonlinelist"];
		if ($parameters["birthday"]) {
			$dateObj = DateTime::createFromFormat($this->_websoccer->getConfig("date_format"), $parameters["birthday"]);
			$columns["geburtstag"] = $dateObj->format("Y-m-d"); }
		if (count($columns)) {
			$fromTable = $this->_websoccer->getConfig("db_prefix") ."_user";
			$whereCondition = "id = %d";
			$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $user->id); }
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("saved_message_title"), ""));
		return "profile"; }}
class SaveTicketsController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$user = $this->_websoccer->getUser();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		if (!$clubId) return null;
		$columns["preis_stehen"] = $parameters["p_stands"];
		$columns["preis_sitz"] = $parameters["p_seats"];
		$columns["preis_haupt_stehen"] = $parameters["p_stands_grand"];
		$columns["preis_haupt_sitze"] = $parameters["p_seats_grand"];
		$columns["preis_vip"] = $parameters["p_vip"];
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_verein";
		$whereCondition = "id = %d";
		$parameters = $clubId;
		$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $parameters);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("saved_message_title"), ""));
		return null; }}
class SaveUsernameController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (strlen($this->_websoccer->getUser()->username)) throw new Exception("user name is already set.");
		$illegalUsernames = explode(",", strtolower(str_replace(", ", ",", $this->_websoccer->getConfig("illegal_usernames"))));
		if (array_search(strtolower($parameters["nick"]), $illegalUsernames)) throw new Exception($this->_i18n->getMessage("registration_illegal_username"));
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_user";
		$wherePart = "UPPER(nick) = '%s'";
		$result = $this->_db->querySelect("COUNT(*) AS hits", $fromTable, $wherePart, strtoupper($parameters["nick"]));
		$rows = $result->fetch_array();
		if ($rows["hits"]) throw new Exception($this->_i18n->getMessage("registration_user_exists"));
		$this->_db->queryUpdate(array("nick" => $parameters["nick"]), $fromTable, "id = %d", $this->_websoccer->getUser()->id);
		$this->_websoccer->getUser()->username = $parameters["nick"];
		return "office"; }}
class SaveYouthFormationController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db;
		$this->_addedPlayers = array(); }
	function executeAction($parameters) {
		$user = $this->_websoccer->getUser();
		$teamId = $user->getClubId($this->_websoccer, $this->_db);
		$matchinfo = YouthMatchesDataService::getYouthMatchinfoById($this->_websoccer, $this->_db, $this->_i18n, $parameters["matchid"]);
		if ($matchinfo["home_team_id"] == $teamId) $teamPrefix = "home";
		elseif ($matchinfo["guest_team_id"] == $teamId) $teamPrefix = "guest";
		else throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		if ($matchinfo["matchdate"] < $this->_websoccer->getNowAsTimestamp() || $matchinfo["simulated"]) throw new Exception($this->_i18n->getMessage("youthformation_err_matchexpired"));
		$this->validatePlayer($parameters["player1"]);
		$this->validatePlayer($parameters["player2"]);
		$this->validatePlayer($parameters["player3"]);
		$this->validatePlayer($parameters["player4"]);
		$this->validatePlayer($parameters["player5"]);
		$this->validatePlayer($parameters["player6"]);
		$this->validatePlayer($parameters["player7"]);
		$this->validatePlayer($parameters["player8"]);
		$this->validatePlayer($parameters["player9"]);
		$this->validatePlayer($parameters["player10"]);
		$this->validatePlayer($parameters["player11"]);
		$this->validatePlayer($parameters["bench1"]);
		$this->validatePlayer($parameters["bench2"]);
		$this->validatePlayer($parameters["bench3"]);
		$this->validatePlayer($parameters["bench4"]);
		$this->validatePlayer($parameters["bench5"]);
		$validSubstitutions = array();
		for ($subNo = 1; $subNo <= 3; $subNo++) {
			$playerIn = $parameters["sub" . $subNo ."_in"];
			$playerOut = $parameters["sub" . $subNo ."_out"];
			$playerMinute = $parameters["sub" . $subNo ."_minute"];
			if ($playerIn != null && $playerIn > 0 && $playerOut != null && $playerOut > 0 && $playerMinute != null && $playerMinute > 0) {
				$this->validateSubstitution($playerIn, $playerOut, $playerMinute);
				$validSubstitutions[] = $subNo; }}
		$this->saveFormation($teamId, $parameters, $validSubstitutions, $matchinfo, $teamPrefix);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("saved_message_title"), ""));
		return null; }
	function validatePlayer($playerId) {
		if ($playerId == null || $playerId == 0) return;
		if (isset($this->_addedPlayers[$playerId])) throw new Exception($this->_i18n->getMessage("formation_err_duplicateplayer"));
		$this->_addedPlayers[$playerId] = TRUE; }
	function validateSubstitution($playerIn, $playerOut, $minute) {
		if (!isset($this->_addedPlayers[$playerIn]) || !isset($this->_addedPlayers[$playerOut])) throw new Exception($this->_i18n->getMessage("formation_err_invalidplayer"));
		if ($minute < 1 || $minute > 90) throw new Exception($this->_i18n->getMessage("formation_err_invalidsubstitutionminute")); }
	function saveFormation($teamId, $parameters, $validSubstitutions, $matchinfo, $teamPrefix) {$this->_db->queryDelete($this->_websoccer->getConfig("db_prefix") ."_youthmatch_player", "match_id = %d AND team_id = %d", array($parameters["matchid"], $teamId));
		$setupParts = explode("-",  $parameters["setup"]);
		if (count($setupParts) != 5) throw new Exception("illegal formation setup");
 		$mainPositionMapping = array(1 => "T");
 		if ($setupParts[0] == 1) $mainPositionMapping[2] = "IV";
 		elseif ($setupParts[0] == 2) {
 			$mainPositionMapping[2] = "IV";
 			$mainPositionMapping[3] = "IV"; }
 		elseif ($setupParts[0] == 3) {
 			$mainPositionMapping[2] = "LV";
 			$mainPositionMapping[3] = "IV";
 			$mainPositionMapping[4] = "RV"; }
 		else {
 			$mainPositionMapping[2] = "LV";
 			$mainPositionMapping[3] = "IV";
 			$mainPositionMapping[4] = "IV";
 			$mainPositionMapping[5] = "RV";
 			$setupParts[0] = 4; }
 		if ($setupParts[1] == 1) $mainPositionMapping[$setupParts[0] + 2] = "DM";
 		elseif ($setupParts[1] == 2) {
 			$mainPositionMapping[$setupParts[0] + 2] = "DM";
 			$mainPositionMapping[$setupParts[0] + 3] = "DM"; }
		else $setupParts[1] = 0;
 		if ($setupParts[2] == 1) $mainPositionMapping[$setupParts[0] + $setupParts[1] + 2] = "ZM";
 		elseif ($setupParts[2] == 2) {
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + 2] = "LM";
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + 3] = "RM"; }
 		elseif ($setupParts[2] == 3) {
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + 2] = "LM";
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + 3] = "ZM";
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + 4] = "RM"; }
 		elseif ($setupParts[2] == 4) {
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + 2] = "LM";
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + 3] = "ZM";
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + 4] = "ZM";
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + 5] = "RM"; }
 		else $setupParts[2] = 0;
 		if ($setupParts[3] == 1) $mainPositionMapping[$setupParts[0] + $setupParts[1] + $setupParts[2] + 2] = "OM";
 		elseif ($setupParts[3] == 2) {
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + $setupParts[2] + 2] = "OM";
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + $setupParts[2] + 3] = "OM"; }
 		else $setupParts[3] = 0;
 		if ($setupParts[4] == 1) $mainPositionMapping[$setupParts[0] + $setupParts[1] + $setupParts[2] + $setupParts[3] + 2] = "MS";
 		elseif ($setupParts[4] == 2) {
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + $setupParts[2] + $setupParts[3] + 2] = "MS";
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + $setupParts[2] + $setupParts[3] + 3] = "MS"; }
 		elseif ($setupParts[4] == 3) {
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + $setupParts[2] + $setupParts[3] + 2] = "LS";
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + $setupParts[2] + $setupParts[3] + 3] = "MS";
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + $setupParts[2] + $setupParts[3] + 4] = "RS";}
		$positionMapping = array( "T" => "Torwart", "LV" => "Abwehr", "IV" => "Abwehr", "RV" => "Abwehr", "DM" => "Mittelfeld", "OM" => "Mittelfeld", "ZM" => "Mittelfeld", "LM" => "Mittelfeld", "RM" => "Mittelfeld", "LS" => "Sturm", "MS" => "Sturm", "RS" => "Sturm" );
		for ($playerNo = 1; $playerNo <= 11; $playerNo++) {
			$mainPosition = $this->_websoccer->getRequestParameter("player" . $playerNo . "_pos");
			$position = $positionMapping[$mainPosition];
			$this->savePlayer($parameters["matchid"], $teamId, $parameters["player" . $playerNo], $playerNo, $position, $mainPosition, FALSE); }
		for ($playerNo = 1; $playerNo <= 5; $playerNo++) {
			if ($parameters["bench" . $playerNo]) $this->savePlayer($parameters["matchid"], $teamId, $parameters["bench" . $playerNo], $playerNo, "-", "-", TRUE); }
		$fromTable = $this->_websoccer->getConfig("db_prefix") ."_youthmatch";
		$columns = array();
		for ($subNo = 1; $subNo <= 3; $subNo++) {
			if (in_array($subNo, $validSubstitutions)) {
				$columns[$teamPrefix . "_s". $subNo . "_out"] = $parameters["sub" . $subNo ."_out"];
				$columns[$teamPrefix . "_s". $subNo . "_in"] = $parameters["sub" . $subNo ."_in"];
				$columns[$teamPrefix . "_s". $subNo . "_minute"] = $parameters["sub" . $subNo ."_minute"];
				$columns[$teamPrefix . "_s". $subNo . "_condition"] = $parameters["sub" . $subNo ."_condition"];
				$columns[$teamPrefix . "_s". $subNo . "_position"] = $this->_websoccer->getRequestParameter("sub" . $subNo ."_position"); }
			else {
				$columns[$teamPrefix . "_s". $subNo . "_out"] = "";
				$columns[$teamPrefix . "_s". $subNo . "_in"] = "";
				$columns[$teamPrefix . "_s". $subNo . "_minute"] = "";
				$columns[$teamPrefix . "_s". $subNo . "_condition"] = "";
				$columns[$teamPrefix . "_s". $subNo . "_position"] = ""; }}
		$this->_db->queryUpdate($columns, $fromTable, "id = %d", $parameters["matchid"]); }
	function savePlayer($matchId, $teamId, $playerId, $playerNumber, $position, $mainPosition, $onBench) {
		$columns = array("match_id" => $matchId,"team_id" => $teamId,"player_id" => $playerId,"playernumber" => $playerNumber,"position" => $position,"position_main" => $mainPosition,"state" => ($onBench) ? "Ersatzbank" : "1","strength" => 0,"name" => $playerId );
		$this->_db->queryInsert($columns, $this->_websoccer->getConfig("db_prefix") ."_youthmatch_player"); }}
class ScoutYouthPlayerController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("youth_enabled") && $this->_websoccer->getConfig("youth_scouting_enabled")) return NULL;
		$user = $this->_websoccer->getUser();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		if ($clubId < 1) throw new Exception($this->_i18n->getMessage("error_action_required_team"));
		$lastExecutionTimestamp = YouthPlayersDataService::getLastScoutingExecutionTime($this->_websoccer, $this->_db, $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db));
		$nextPossibleExecutionTimestamp = $lastExecutionTimestamp + $this->_websoccer->getConfig("youth_scouting_break_hours") * 3600;
		$now = $this->_websoccer->getNowAsTimestamp();
		if ($now < $nextPossibleExecutionTimestamp) throw new Exception($this->_i18n->getMessage("youthteam_scouting_err_breakviolation", $this->_websoccer->getFormattedDatetime($nextPossibleExecutionTimestamp)));
		$namesFolder = NAMES_DIRECTORY . "/" . $parameters["country"];
		if (!file_exists($namesFolder . "/firstnames.txt") || !file_exists($namesFolder . "/lastnames.txt")) throw new Exception($this->_i18n->getMessage("youthteam_scouting_err_invalidcountry"));
		$scout = YouthPlayersDataService::getScoutById($this->_websoccer, $this->_db, $this->_i18n, $parameters["scoutid"]);
		$team = TeamsDataService::getTeamSummaryById($this->_websoccer, $this->_db, $clubId);
		if ($team["team_budget"] <= $scout["fee"]) throw new Exception($this->_i18n->getMessage("youthteam_scouting_err_notenoughbudget"));
		BankAccountDataService::debitAmount($this->_websoccer, $this->_db, $clubId, $scout["fee"], "youthteam_scouting_fee_subject", $scout["name"]);
		$found = TRUE;
		$succesProbability = (int) $this->_websoccer->getConfig("youth_scouting_success_probability");
		if ($this->_websoccer->getConfig("youth_scouting_success_probability") < 100) $found = SimulationHelper::selectItemFromProbabilities(array(TRUE => $succesProbability, FALSE => 100 - $succesProbability ));
		if ($found) $this->createYouthPlayer($clubId, $scout, $parameters["country"]);
		else $this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_WARNING, $this->_i18n->getMessage("youthteam_scouting_failure"), ""));
		$this->_db->queryUpdate(array("scouting_last_execution" => $now), $this->_websoccer->getConfig("db_prefix") . "_verein", "id = %d", $clubId);
		return ($found) ? "youth-team" : "youth-scouting"; }
	function createYouthPlayer($clubId, $scout, $country) {
		$firstName = $this->getItemFromFile(NAMES_DIRECTORY . "/" . $country . "/firstnames.txt");
		$lastName = $this->getItemFromFile(NAMES_DIRECTORY . "/" . $country . "/lastnames.txt");
		$minStrength = (int) $this->_websoccer->getConfig("youth_scouting_min_strength");
		$maxStrength = (int) $this->_websoccer->getConfig("youth_scouting_max_strength");
		$scoutFactor = $scout["expertise"] / 100;
		$strength = $minStrength + round(($maxStrength - $minStrength) * $scoutFactor);
		$deviation = (int) $this->_websoccer->getConfig("youth_scouting_standard_deviation");
		$strength = $strength + SimulationHelper::getMagicNumber(0 - $deviation, $deviation);
		$strength = max($minStrength, min($maxStrength, $strength));
		if ($scout["speciality"] == "Torwart") $positionProbabilities = array("Torwart" => 40, "Abwehr" => 30, "Mittelfeld" => 25, "Sturm" => 5);
		elseif ($scout["speciality"] == "Abwehr") $positionProbabilities = array("Torwart" => 10, "Abwehr" => 50, "Mittelfeld" => 30, "Sturm" => 10);
		elseif ($scout["speciality"] == "Mittelfeld") $positionProbabilities = array("Torwart" => 10, "Abwehr" => 15, "Mittelfeld" => 60, "Sturm" => 15);
		elseif ($scout["speciality"] == "Sturm") $positionProbabilities = array("Torwart" => 5, "Abwehr" => 15, "Mittelfeld" => 40, "Sturm" => 40);
		else $positionProbabilities = array("Torwart" => 15, "Abwehr" => 30, "Mittelfeld" => 35, "Sturm" => 20);
		$position = SimulationHelper::selectItemFromProbabilities($positionProbabilities);
		$minAge = $this->_websoccer->getConfig("youth_scouting_min_age");
		$maxAge = $this->_websoccer->getConfig("youth_min_age_professional");
		$age = $minAge + SimulationHelper::getMagicNumber(0, abs($maxAge - $minAge));
		$this->_db->queryInsert(array("team_id" => $clubId, "firstname" => $firstName, "lastname" => $lastName, "age" => $age, "position" => $position, "nation" => $country, "strength" => $strength ), $this->_websoccer->getConfig("db_prefix") . "_youthplayer");
		$event = new YouthPlayerScoutedEvent($this->_websoccer, $this->_db, $this->_i18n, $clubId, $scout["id"], $this->_db->getLastInsertedId());
		PluginMediator::dispatchEvent($event);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("youthteam_scouting_success"), $this->_i18n->getMessage("youthteam_scouting_success_details", $firstName . " " . $lastName))); }
	function getItemFromFile($fileName) {
		$items = file($fileName, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$itemsCount = count($items);
		if (!$itemsCount) throw new Exception($this->_i18n->getMessage("youthteam_scouting_err_invalidcountry"));
		return $items[mt_rand(0, $itemsCount - 1)]; }}
class SelectCaptainController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$user = $this->_websoccer->getUser();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		$team = TeamsDataService::getTeamById($this->_websoccer, $this->_db, $clubId);
		$player = PlayersDataService::getPlayerById($this->_websoccer, $this->_db, $parameters["id"]);
		if ($clubId != $player["team_id"]) throw new Exception("nice try");
		$this->_db->queryUpdate(array("captain_id" => $parameters["id"]), $this->_websoccer->getConfig("db_prefix") . "_verein", "id = %d", $clubId);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("myteam_player_select_as_captain_success"), ""));
		if ($team["captain_id"] && $team["captain_id"] != $parameters["id"]) {
			$oldPlayer = PlayersDataService::getPlayerById($this->_websoccer, $this->_db, $team["captain_id"]);
			if ($oldPlayer["team_id"] == $clubId) {
				$newSatisfaction = round($oldPlayer["player_strength_satisfaction"] * 0.6);
				$this->_db->queryUpdate(array("w_zufriedenheit" => $newSatisfaction), $this->_websoccer->getConfig("db_prefix") . "_spieler", "id = %d", $oldPlayer["player_id"]);
				$playername = (strlen($oldPlayer["player_pseudonym"])) ? $oldPlayer["player_pseudonym"] : $oldPlayer["player_firstname"] . " " . $oldPlayer["player_lastname"];
				$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_WARNING, $this->_i18n->getMessage("myteam_player_select_as_captain_warning_old_captain", $playername), "")); }}
		return null; }}
class SelectTeamController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$user = $this->_websoccer->getUser();
		$teamId = $parameters['id'];
		if ($user->getClubId($this->_websoccer, $this->_db) == $teamId) return null;
		$fromTable = $this->_websoccer->getConfig("db_prefix") ."_verein";
		$whereCondition = "id = %d AND user_id = %d AND status = '1' AND nationalteam != '1'";
		$result = $this->_db->querySelect("id", $fromTable, $whereCondition, array($teamId, $user->id));
		$club = $result->fetch_array();
		if (!isset($club["id"])) throw new Exception("illegal club ID");
		$user->setClubId($teamId);
		return null; }}
class SellPlayerController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("transfermarket_enabled")) return NULL;
		$user = $this->_websoccer->getUser();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		$player = PlayersDataService::getPlayerById($this->_websoccer, $this->_db, $parameters["id"]);
		if ($clubId != $player["team_id"]) throw new Exception("nice try");
		if ($player["player_transfermarket"]) throw new Exception($this->_i18n->getMessage("sell_player_already_on_list"));
		if ($player["lending_fee"] > 0) throw new Exception($this->_i18n->getMessage("lending_err_alreadyoffered"));
		$teamSize = TeamsDataService::getTeamSize($this->_websoccer, $this->_db, $clubId);
		if ($teamSize <= $this->_websoccer->getConfig("transfermarket_min_teamsize")) throw new Exception($this->_i18n->getMessage("sell_player_teamsize_too_small", $teamSize));
		$minBidBoundary = round($player["player_marketvalue"] / 2);
		if ($parameters["min_bid"] < $minBidBoundary) throw new Exception($this->_i18n->getMessage("sell_player_min_bid_too_low"));
		$this->updatePlayer($player["player_id"], $parameters["min_bid"]);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("sell_player_success"), ""));
		return "transfermarket"; }
	function updatePlayer($playerId, $minBid) {
		$now = $this->_websoccer->getNowAsTimestamp();
		$columns["transfermarkt"] = 1;
		$columns["transfer_start"] = $now;
		$columns["transfer_ende"] = $now + 24 * 3600 * $this->_websoccer->getConfig("transfermarket_duration_days");
		$columns["transfer_mindestgebot"] = $minBid;
		$fromTable = $this->_websoccer->getConfig("db_prefix") ."_spieler";
		$whereCondition = "id = %d";
		$parameters = $playerId;
		$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $parameters); }}
class SellYouthPlayerController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("youth_enabled")) return NULL;
		$user = $this->_websoccer->getUser();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		$player = YouthPlayersDataService::getYouthPlayerById($this->_websoccer, $this->_db, $this->_i18n, $parameters["id"]);
		if ($clubId != $player["team_id"]) throw new Exception($this->_i18n->getMessage("youthteam_err_notownplayer"));
		if ($player["transfer_fee"]) throw new Exception($this->_i18n->getMessage("youthteam_sell_err_alreadyonmarket"));
		$this->updatePlayer($parameters["id"], $parameters["transfer_fee"]);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("youthteam_sell_success"), ""));
		return "youth-team"; }
	function updatePlayer($playerId, $transferFee) {
		$columns = array("transfer_fee" => $transferFee);
		$fromTable = $this->_websoccer->getConfig("db_prefix") ."_youthplayer";
		$whereCondition = "id = %d";
		$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $playerId); }}
class SendMessageController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$senderId = $this->_websoccer->getUser()->id;
		if (!$this->_websoccer->getConfig("messages_enabled")) throw new Exception($this->_i18n->getMessage("messages_err_messagesdisabled"));
		$recipientId = UsersDataService::getUserIdByNick($this->_websoccer, $this->_db, $parameters["nick"]);
		if ($recipientId < 1) throw new Exception($this->_i18n->getMessage("messages_send_err_invalidrecipient"));
		if ($senderId == $recipientId) throw new Exception($this->_i18n->getMessage("messages_send_err_sendtoyourself"));
		$now = $this->_websoccer->getNowAsTimestamp();
		$lastMessage = MessagesDataService::getLastMessageOfUserId($this->_websoccer, $this->_db, $senderId);
		$timebreakBoundary = $now - $this->_websoccer->getConfig("messages_break_minutes") * 60;
		if ($lastMessage != null && $lastMessage["date"] >= $timebreakBoundary) throw new Exception($this->_i18n->getMessage("messages_send_err_timebreak", $this->_websoccer->getConfig("messages_break_minutes")));
		$columns["empfaenger_id"] = $recipientId;
		$columns["absender_id"] = $senderId;
		$columns["datum"] = $now;
		$columns["betreff"] = $parameters["subject"];
		$columns["nachricht"] = $parameters["msgcontent"];
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_briefe";
		$columns["typ"] = "eingang";
		$this->_db->queryInsert($columns, $fromTable);
		$columns["typ"] = "ausgang";
		$this->_db->queryInsert($columns, $fromTable);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("messages_send_success"), ""));
		$_REQUEST["subject"] = "";
		$_REQUEST["msgcontent"] = "";
		$_REQUEST["nick"] = "";
		return null; }}
class SendPasswordController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("login_allow_sendingpassword")) throw new Exception("Action is disabled.");
		if ($this->_websoccer->getConfig("register_use_captcha") && strlen($this->_websoccer->getConfig("register_captcha_publickey")) && strlen($this->_websoccer->getConfig("register_captcha_privatekey"))) {
			include_once(BASE_FOLDER . "/lib/recaptcha/recaptchalib.php");
			$captchaResponse = recaptcha_check_answer($this->_websoccer->getConfig("register_captcha_privatekey"), $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
			if (!$captchaResponse->is_valid) throw new Exception($this->_i18n->getMessage("registration_invalidcaptcha")); }
		$email = $parameters["useremail"];
		$fromTable = $this->_websoccer->getConfig("db_prefix") ."_user";
		$columns = "id, passwort_salt, passwort_neu_angefordert";
		$wherePart = "UPPER(email) = '%s' AND status = 1";
		$result = $this->_db->querySelect($columns, $fromTable, $wherePart, strtoupper($email));
		$userdata = $result->fetch_array();
		if (!isset($userdata["id"])) {
			sleep(5);
			throw new Exception($this->_i18n->getMessage("forgot-password_email-not-found")); }
		$now = $this->_websoccer->getNowAsTimestamp();
		$timeBoundary = $now - 24 * 3600;
		if ($userdata["passwort_neu_angefordert"] > $timeBoundary) throw new Exception($this->_i18n->getMessage("forgot-password_already-sent"));
		$salt = $userdata["passwort_salt"];
		if (!strlen($salt)) $salt = SecurityUtil::generatePasswordSalt();
		$password = SecurityUtil::generatePassword();
		$hashedPassword = SecurityUtil::hashPassword($password, $salt);
		$columns = array("passwort_salt" => $salt, "passwort_neu_angefordert" => $now, "passwort_neu" => $hashedPassword);
		$whereCondition = "id = %d";
		$parameter = $userdata["id"];
		$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $parameter);
		$this->_sendEmail($email, $password);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("forgot-password_message_title"), $this->_i18n->getMessage("forgot-password_message_content")));
		return "login"; }
	function _sendEmail($email, $password) {
		$tplparameters["newpassword"] = $password;
		EmailHelper::sendSystemEmailFromTemplate($this->_websoccer, $this->_i18n, $email, $this->_i18n->getMessage("sendpassword_email_subject"), "sendpassword", $tplparameters); }}
class SendShoutBoxMessageController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$userId = $this->_websoccer->getUser()->id;
		$message = $parameters['msgtext'];
		$matchId = $parameters['id'];
		$date = $this->_websoccer->getNowAsTimestamp();
		$fromTable = $this->_websoccer->getConfig('db_prefix') . '_shoutmessage';
		$this->_db->queryInsert(array('user_id' => $userId, 'message' => $message, 'created_date' => $date, 'match_id' => $matchId ), $fromTable);
		if (!isset($_SESSION['msgdeleted'])) {
			$threshold = $date - 24 * 3600 * 14;
			$this->_db->queryDelete($fromTable, "created_date < %d", $threshold);
			$_SESSION['msgdeleted'] = 1; }
		return null; }}
require_once(dirname(__DIR__).'/lib/SofortLib-PHP-Payment-2.0.1/core/sofortLibNotification.inc.php');
require_once(dirname(__DIR__).'/lib/SofortLib-PHP-Payment-2.0.1/core/sofortLibTransactionData.inc.php');
class SofortComPaymentNotificationController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$configKey = trim($this->_websoccer->getConfig("sofortcom_configkey"));
		if (!strlen($configKey)) throw new Exception("Sofort.com configuration key is not configured.");
		$userId = $parameters['u'];
		$result = $this->_db->querySelect("id", $this->_websoccer->getConfig("db_prefix") . "_user", "id = %d", $userId);
		$user = $result->fetch_array();
		if (!$user) throw new Exception("illegal user id");
		$SofortLib_Notification = new SofortLibNotification();
		$TestNotification = $SofortLib_Notification->getNotification(file_get_contents('php://input'));
		$SofortLibTransactionData = new SofortLibTransactionData($configKey);
		$SofortLibTransactionData->addTransaction($TestNotification);
		$SofortLibTransactionData->sendRequest();
		if ($SofortLibTransactionData->isError()) {
			EmailHelper::sendSystemEmail($this->_websoccer, $this->_websoccer->getConfig("systememail"), "Failed Sofort.com payment notification", "Error: " . $SofortLibTransactionData->getError());
			throw new Exception($SofortLibTransactionData->getError()); }
		else {
			if ($SofortLibTransactionData->getStatus() != 'received') {
				EmailHelper::sendSystemEmail($this->_websoccer, $this->_websoccer->getConfig("systememail"), "Failed Sofort.com payment notification: invalid status", "Status: " . $SofortLibTransactionData->getStatus());
				throw new Exception("illegal status"); }
			$amount = $SofortLibTransactionData->getAmount();
			PremiumDataService::createPaymentAndCreditPremium($this->_websoccer, $this->_db, $userId, $amount, "sofortcom-notify"); }
		return null; }}
require_once(dirname(__DIR__) . '/lib/SofortLib-PHP-Payment-2.0.1/payment/sofortLibSofortueberweisung.inc.php');
class SofortComRedirectController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$configKey = trim($this->_websoccer->getConfig("sofortcom_configkey"));
		if (!strlen($configKey)) throw new Exception("Sofort.com configuration key is not configured.");
		$amount = $parameters['amount'];
		$priceOptions = explode(',', $this->_websoccer->getConfig('premium_price_options'));
		$validAmount = FALSE;
		if (count($priceOptions)) {
			foreach ($priceOptions as $priceOption) {
				$optionParts = explode(':', $priceOption);
				$realMoney = trim($optionParts[0]);
				if ($amount == $realMoney) $validAmount = TRUE; }}
		if (!$validAmount) throw new Exception("Invalid amount");
		$Sofortueberweisung = new Sofortueberweisung($configKey);
		$abortOrSuccessUrl = $this->_websoccer->getInternalUrl('premiumaccount', null, TRUE);
		$notifyUrl = $this->_websoccer->getInternalActionUrl('sofortcom-notify', 'u=' . $this->_websoccer->getUser()->id, 'home', TRUE);
		$Sofortueberweisung->setAmount($amount);
		$Sofortueberweisung->setCurrencyCode($this->_websoccer->getConfig("premium_currency"));
		$Sofortueberweisung->setReason($this->_websoccer->getConfig("projectname"));
		$Sofortueberweisung->setSuccessUrl($abortOrSuccessUrl, true);
		$Sofortueberweisung->setAbortUrl($abortOrSuccessUrl);
		$Sofortueberweisung->setNotificationUrl($notifyUrl, 'received');
		$Sofortueberweisung->sendRequest();
		if ($Sofortueberweisung->isError()) throw new Exception($Sofortueberweisung->getError());
		else {
			$paymentUrl = $Sofortueberweisung->getPaymentUrl();
			header('Location: '.$paymentUrl);
			exit; }
		return null; }}
class TransferBidController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig('transfermarket_enabled')) return;
		$user = $this->_websoccer->getUser();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		$playerId = $parameters['id'];
		if ($clubId < 1) throw new Exception($this->_i18n->getMessage('error_action_required_team'));
		$player = PlayersDataService::getPlayerById($this->_websoccer, $this->_db, $playerId);
		if ($user->id == $player['team_user_id']) throw new Exception($this->_i18n->getMessage('transfer_bid_on_own_player'));
		if (!$player['player_transfermarket']) throw new Exception($this->_i18n->getMessage('transfer_bid_player_not_on_list'));
		$now = $this->_websoccer->getNowAsTimestamp();
		if ($now > $player['transfer_end']) throw new Exception($this->_i18n->getMessage('transfer_bid_auction_ended'));
		$minSalary = $player['player_contract_salary'] * 1.1;
		if ($parameters['contract_salary'] < $minSalary) throw new Exception($this->_i18n->getMessage('transfer_bid_salary_too_less'));
		$minGoalBonus = $player['player_contract_goalbonus'] * 1.1;
		if ($parameters['contract_goal_bonus'] < $minGoalBonus) throw new Exception($this->_i18n->getMessage('transfer_bid_goalbonus_too_less'));
		if ($player['team_id'] > 0) {
			$noOfTransactions = TransfermarketDataService::getTransactionsBetweenUsers($this->_websoccer, $this->_db, $player['team_user_id'], $user->id);
			$maxTransactions = $this->_websoccer->getConfig('transfermarket_max_transactions_between_users');
			if ($noOfTransactions >= $maxTransactions) throw new Exception($this->_i18n->getMessage('transfer_bid_too_many_transactions_with_user', $noOfTransactions)); }
		$highestBid = TransfermarketDataService::getHighestBidForPlayer($this->_websoccer, $this->_db, $parameters['id'], $player['transfer_start'], $player['transfer_end']);
		if ($player['team_id'] > 0) {
			$minBid = $player['transfer_min_bid'] - 1;
			if (isset($highestBid['amount'])) $minBid = $highestBid['amount'];
			if ($parameters['amount'] <= $minBid) throw new Exception($this->_i18n->getMessage('transfer_bid_amount_must_be_higher', $minBid)); }
		elseif (isset($highestBid['contract_matches'])) {
			$ownBidValue = $parameters['handmoney'] + $parameters['contract_matches'] * $parameters['contract_salary'];
			$opponentSalary = $highestBid['hand_money'] + $highestBid['contract_matches'] * $highestBid['contract_salary'];
			if ($player['player_position'] == 'midfield' || $player['player_position'] == 'striker') {
				$ownBidValue += 10 * $parameters['contract_goal_bonus'];
				$opponentSalary += 10 * $highestBid['contract_goalbonus']; }
			if ($ownBidValue <= $opponentSalary) throw new Exception($this->_i18n->getMessage('transfer_bid_contract_conditions_too_low')); }
		TeamsDataService::validateWhetherTeamHasEnoughBudgetForSalaryBid($this->_websoccer, $this->_db, $this->_i18n, $clubId, $parameters['contract_salary']);
		$team = TeamsDataService::getTeamSummaryById($this->_websoccer, $this->_db, $clubId);
		$result = $this->_db->querySelect('SUM(abloese) + SUM(handgeld) AS bidsamount', $this->_websoccer->getConfig('db_prefix') .'_transfer_angebot', 'user_id = %d AND ishighest = \'1\'', $user->id);
		$bids = $result->fetch_array();
		if (isset($bids['bidsamount']) && ($parameters['handmoney'] + $parameters['amount'] + $bids['bidsamount']) >= $team['team_budget']) throw new Exception($this->_i18n->getMessage('transfer_bid_budget_for_all_bids_too_less'));
		$this->saveBid($playerId, $user->id, $clubId, $parameters);
		if (isset($highestBid['bid_id'])) $this->_db->queryUpdate(array('ishighest' => '0'), $this->_websoccer->getConfig('db_prefix') .'_transfer_angebot', 'id = %d', $highestBid['bid_id']);
		if (isset($highestBid['user_id']) && $highestBid['user_id']) {
			$playerName = (strlen($player['player_pseudonym'])) ? $player['player_pseudonym'] : $player['player_firstname'] . ' ' . $player['player_lastname'];
			NotificationsDataService::createNotification($this->_websoccer, $this->_db, $highestBid['user_id'], 'transfer_bid_notification_outbidden', array('player' => $playerName), 'transfermarket', 'transfer-bid', 'id=' . $playerId); }
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage('transfer_bid_success'), ''));
		return null; }
	function saveBid($playerId, $userId, $clubId, $parameters) {
		$columns['spieler_id'] = $playerId;
		$columns['user_id'] = $userId;
		$columns['datum'] = $this->_websoccer->getNowAsTimestamp();
		$columns['abloese'] = $parameters['amount'];
		$columns['handgeld'] = $parameters['handmoney'];
		$columns['vertrag_spiele'] = $parameters['contract_matches'];
		$columns['vertrag_gehalt'] = $parameters['contract_salary'];
		$columns['vertrag_torpraemie'] = $parameters['contract_goal_bonus'];
		$columns['verein_id'] = $clubId;
		$columns['ishighest'] = '1';
		$fromTable = $this->_websoccer->getConfig('db_prefix') .'_transfer_angebot';
		$this->_db->queryInsert($columns, $fromTable); }}
class UnmarkLendableController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db;}
	function executeAction($parameters) {
		$user = $this->_websoccer->getUser();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		$player = PlayersDataService::getPlayerById($this->_websoccer, $this->_db, $parameters["id"]);
		if ($clubId != $player["team_id"]) throw new Exception($this->_i18n->getMessage("lending_err_notownplayer"));
		if ($player["lending_owner_id"] > 0) throw new Exception($this->_i18n->getMessage("lending_err_borrowed_player"));
		$columns = array("lending_fee" => 0);
		$fromTable = $this->_websoccer->getConfig("db_prefix") ."_spieler";
		$whereCondition = "id = %d";
		$parameters = $parameters["id"];
		$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $parameters);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("lending_lendable_unmark_success"), ""));
		return null; }}
class UnmarkUnsellableController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$user = $this->_websoccer->getUser();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		$player = PlayersDataService::getPlayerById($this->_websoccer, $this->_db, $parameters["id"]);
		if ($clubId != $player["team_id"]) throw new Exception("nice try");
		$columns["unsellable"] = 0;
		$fromTable = $this->_websoccer->getConfig("db_prefix") ."_spieler";
		$whereCondition = "id = %d";
		$parameters = $parameters["id"];
		$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $parameters);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("myteam_remove_unsellable_player_success"), ""));
		return null; }}
class UpgradeStadiumController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$user = $this->_websoccer->getUser();
		$teamId = $user->getClubId($this->_websoccer, $this->_db);
		if ($teamId < 1) return null;
		$type = $parameters["type"];
		if (!in_array($type, array("pitch", "videowall", "seatsquality", "vipquality"))) throw new Exception("illegal parameter: type");
		$stadium = StadiumsDataService::getStadiumByTeamId($this->_websoccer, $this->_db, $teamId);
		if (!$stadium) return null;
		$existingLevel = $stadium["level_" . $type];
		if ($existingLevel >= 5) throw new Exception($this->_i18n->getMessage("stadium_upgrade_err_not_upgradable"));
		$costs = StadiumsDataService::computeUpgradeCosts($this->_websoccer, $type, $stadium);
		$team = TeamsDataService::getTeamSummaryById($this->_websoccer, $this->_db, $teamId);
		if ($team["team_budget"] <= $costs) throw new Exception($this->_i18n->getMessage("stadium_extend_err_too_expensive"));
		BankAccountDataService::debitAmount($this->_websoccer, $this->_db, $teamId, $costs, "stadium_upgrade_transaction_subject", $stadium["name"]);
		$maintenanceDue = (int) $this->_websoccer->getConfig("stadium_maintenanceinterval_" . $type);
		$this->_db->queryUpdate(array("level_" . $type => $existingLevel + 1, "maintenance_" . $type => $maintenanceDue ), $this->_websoccer->getConfig("db_prefix") . "_stadion", "id = %d", $stadium["stadium_id"]);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("stadium_upgrade_success"), $this->_i18n->getMessage("stadium_upgrade_success_details")));
		return "stadium"; }}
class UploadClubPictureController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("upload_clublogo_max_size")) throw new Exception("feature is not enabled.");
		$clubId = $this->_websoccer->getUser()->getClubId();
		if (!$clubId) throw new Exception("requires team");
		if (!isset($_FILES["picture"])) throw new Exception($this->_->getMessage("change-profile-picture_err_notprovied"));
		$errorcode = $_FILES["picture"]["error"];
		if ($errorcode == UPLOAD_ERR_FORM_SIZE) throw new Exception($this->_i18n->getMessage("change-profile-picture_err_illegalfilesize"));
		$filename = $_FILES["picture"]["name"];
		$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		$allowedExtensions = explode(",", ALLOWED_PROFPIC_EXTENSIONS);
		if (!in_array($ext, $allowedExtensions)) throw new Exception($this->_i18n->getMessage("change-profile-picture_err_illegalfiletype"));
		$imagesize = getimagesize($_FILES["picture"]["tmp_name"]);
		if ($imagesize === FALSE) throw new Exception($this->_i18n->getMessage("change-profile-picture_err_illegalfiletype"));
		$type = substr($imagesize["mime"], strrpos($imagesize["mime"], "/") + 1);
		if (!in_array($type, $allowedExtensions)) throw new Exception($this->_i18n->getMessage("change-profile-picture_err_illegalfiletype"));
		$maxFilesize = $this->_websoccer->getConfig("upload_clublogo_max_size") * 1024;
		if ($_POST["MAX_FILE_SIZE"] != $maxFilesize || $_FILES["picture"]["size"] > $maxFilesize) throw new Exception($this->_i18n->getMessage("change-profile-picture_err_illegalfilesize"));
		if ($errorcode == UPLOAD_ERR_OK) {
			$tmp_name = $_FILES["picture"]["tmp_name"];
			$name = md5($clubId . time()) . "." . $ext;
			$uploaded = @move_uploaded_file($tmp_name, CLUBPICTURE_UPLOAD_DIR . "/" . $name);
			if (!$uploaded) throw new Exception($this->_i18n->getMessage("change-profile-picture_err_failed"));}
		else throw new Exception($this->_i18n->getMessage("change-profile-picture_err_failed"));
		if ($imagesize[0] != 120 || $imagesize[1] != 120) $this->resizeImage(CLUBPICTURE_UPLOAD_DIR . "/". $name, 120, $imagesize[0], $imagesize[1], $ext == "png");
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_verein";
		$whereCondition = "id = %d";
		$result = $this->_db->querySelect("bild", $fromTable, $whereCondition, $clubId);
		$clubinfo = $result->fetch_array();
		if (strlen($clubinfo["bild"]) && file_exists(CLUBPICTURE_UPLOAD_DIR . "/" . $clubinfo["bild"])) unlink(CLUBPICTURE_UPLOAD_DIR . "/" . $clubinfo["bild"]);
		$this->_db->queryUpdate(array("bild" => $name), $fromTable, $whereCondition, $clubId);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("upload-clublogo_success"), ""));
		return null; }
	function resizeImage($file, $width, $oldWidth, $oldHeight, $isPng) {
		if (!$isPng) $src = imagecreatefromjpeg($file);
		else $src = imagecreatefrompng($file);
		$target = imagecreatetruecolor($width, $width);
		imagecopyresampled($target, $src, 0, 0, 0, 0, $width, $width, $oldWidth, $oldHeight);
		if (!$isPng) imagejpeg($target, $file);
		else imagepng($target, $file); }}
class UploadProfilePictureController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		if (!$this->_websoccer->getConfig("user_picture_upload_enabled")) throw new Exception("feature is not enabled.");
		if (!isset($_FILES["picture"])) throw new Exception($this->_->getMessage("change-profile-picture_err_notprovied"));
		$errorcode = $_FILES["picture"]["error"];
		if ($errorcode == UPLOAD_ERR_FORM_SIZE) throw new Exception($this->_i18n->getMessage("change-profile-picture_err_illegalfilesize"));
		$filename = $_FILES["picture"]["name"];
		$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		$allowedExtensions = explode(",", ALLOWED_PROFPIC_EXTENSIONS);
		if (!in_array($ext, $allowedExtensions)) throw new Exception($this->_i18n->getMessage("change-profile-picture_err_illegalfiletype"));
		$imagesize = getimagesize($_FILES["picture"]["tmp_name"]);
		if ($imagesize === FALSE) throw new Exception($this->_i18n->getMessage("change-profile-picture_err_illegalfiletype"));
		$type = substr($imagesize["mime"], strrpos($imagesize["mime"], "/") + 1);
		if (!in_array($type, $allowedExtensions)) throw new Exception($this->_i18n->getMessage("change-profile-picture_err_illegalfiletype"));
		$maxFilesize = $this->_websoccer->getConfig("user_picture_upload_maxsize_kb") * 1024;
		if ($_POST["MAX_FILE_SIZE"] != $maxFilesize || $_FILES["picture"]["size"] > $maxFilesize) throw new Exception($this->_i18n->getMessage("change-profile-picture_err_illegalfilesize"));
		$userId = $this->_websoccer->getUser()->id;
		if ($errorcode == UPLOAD_ERR_OK) {
			$tmp_name = $_FILES["picture"]["tmp_name"];
			$name = md5($userId . time()) . "." . $ext;
			$uploaded = @move_uploaded_file($tmp_name, PROFPIC_UPLOADFOLDER . "/". $name);
			if (!$uploaded) throw new Exception($this->_i18n->getMessage("change-profile-picture_err_failed")); }
		else throw new Exception($this->_i18n->getMessage("change-profile-picture_err_failed"));
		if ($imagesize[0] != 120 || $imagesize[1] != 120) $this->resizeImage(PROFPIC_UPLOADFOLDER . "/". $name, 120, $imagesize[0], $imagesize[1], $ext == "png");
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_user";
		$whereCondition = "id = %d";
		$result = $this->_db->querySelect("picture", $fromTable, $whereCondition, $userId);
		$userinfo = $result->fetch_array();
		if (strlen($userinfo["picture"]) && file_exists(PROFPIC_UPLOADFOLDER . "/" . $userinfo["picture"])) unlink(PROFPIC_UPLOADFOLDER . "/" . $userinfo["picture"]);
		$this->_db->queryUpdate(array("picture" => $name), $fromTable, $whereCondition, $userId);
		$this->_websoccer->getUser()->setProfilePicture($this->_websoccer, $name);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("change-profile-picture_success"), ""));
		return "user"; }
	function resizeImage($file, $width, $oldWidth, $oldHeight, $isPng) {
		if (!$isPng) $src = imagecreatefromjpeg($file);
		else $src = imagecreatefrompng($file);
		$target = imagecreatetruecolor($width, $width);
		imagecopyresampled($target, $src, 0, 0, 0, 0, $width, $width, $oldWidth, $oldHeight);
		if (!$isPng) imagejpeg($target, $file);
		else imagepng($target, $file); }}
class UserActivationController {
	function __construct($i18n, $websoccer, $db) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_db = $db; }
	function executeAction($parameters) {
		$key = $parameters["key"];
		$userid = $parameters["userid"];
		$fromTable = $this->_websoccer->getConfig("db_prefix") ."_user";
		$columns = "id";
		$wherePart = "schluessel = '%s' AND id = %d AND status = 2";
		$parameters = array($key, $userid);
		$result = $this->_db->querySelect($columns, $fromTable, $wherePart, $parameters);
		$userdata = $result->fetch_array();
		if (!isset($userdata["id"])) {
			sleep(5);
			throw new Exception($this->_i18n->getMessage("activate-user_user-not-found")); }
		$columns = array("status" => 1);
		$whereCondition = "id = %d";
		$parameter = $userdata["id"];
		$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $parameter);
		$this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_SUCCESS, $this->_i18n->getMessage("activate-user_message_title"), $this->_i18n->getMessage("activate-user_message_content")));
		return null; }}
class CupRoundsLinkConverter {
	private $_i18n;
	private $_websoccer;
	function __construct($i18n, $websoccer) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer; }
	function toHtml($row) {
		$output = ' <a href=\'?site=managecuprounds&cup='. $row['id']. '\' title=\''. $this->_i18n->getMessage('manage_show_details') . '\' >'. $row['entity_cup_rounds'] .' <i class=\'icon-tasks\'></i></a>';
		return $output; }
	function toText($value) { return $value; }
	function toDbValue($value) { return $this->toText($value); }}
class AdminPasswordConverter {
	private $_i18n;
	private $_websoccer;
	function __construct($i18n, $websoccer) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer; }
	function toHtml($value) { return $this->toText($value); }
	function toText($value) { return $value; }
	function toDbValue($value) {
		if (isset($_POST['id']) && $_POST['id']) {
			$db = DbConnection::getInstance();
			$columns = 'passwort, passwort_salt';
			$fromTable = $this->_websoccer->getConfig('db_prefix') .'_admin';
			$whereCondition = 'id = %d';
			$result = $db->querySelect($columns, $fromTable, $whereCondition, $_POST['id'], 1);
			$admin = $result->fetch_array();
			if (strlen($value)) $passwort = SecurityUtil::hashPassword($value, $admin['passwort_salt']);
			else $passwort = $admin['passwort']; }
		else $passwort = SecurityUtil::hashPassword($value, '');
		return $passwort; }}
class InactivityConverter {
	private $_i18n;
	private $_websoccer;
	function __construct($i18n, $websoccer) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer; }
	function toHtml($row) {
		if (!is_array($row)) return (int)$value . '%';
		$rate = (int)$this->_format($row['entity_user_inactivity']);
		$color = $this->_color($rate);
		$output = '<a href=\'#actPopup'. $row['id']. '\' role=\'button\' data-toggle=\'modal\' title=\''. $this->_i18n->getMessage('manage_show_details') . '\' style=\'color: '. $color .'\'>'.$rate .' %</a>';
		$output .= $this->_renderInActivityPopup($row);
		return $output; }
	function toText($value) { return $value; }
	function toDbValue($value) { return $this->toText($value); }
	function _color($rate) {
		if ($rate <= 10) return 'green';
		elseif ($rate <= 40) return 'black';
		elseif ($rate <= 70) return 'orange';
		else return 'red'; }
	function _renderInActivityPopup($row) {
		$popup = '';
		$popup .= '<div id=\'actPopup'. $row['id']. '\' class=\'modal hide fade\' tabindex=\'-1\' role=\'dialog\' aria-labelledby=\'actPopupLabel\' aria-hidden=\'true\'>';
		$popup .= '<div class=\'modal-header\'><button type=\'button\' class=\'close\' data-dismiss=\'modal\' aria-hidden=\'true\' title=\''. $this->_i18n->getMessage('button_close') . '\'>&times;</button>';
		$popup .= '<h3 id=\'actPopupLabel'. $row['id']. '\'>'. $this->_i18n->getMessage('entity_user_inactivity') . ': '. escapeOutput($row['entity_users_nick']) . '</h3></div>';
		$popup .= '<div class=\'modal-body\'>';
		$gesamt = $row['entity_user_inactivity_login'] + $row['entity_user_inactivity_aufstellung'] + $row['entity_user_inactivity_transfer']+ $row['entity_user_inactivity_vertragsauslauf'];
		$popup .= '<table class=\'table table-bordered\'>
          <thead><tr>
            <th>'. $this->_i18n->getMessage('popup_user_inactivity_title_action') . '</th>
            <th>'. $this->_i18n->getMessage('entity_user_inactivity') . '</th>
          </tr></thead>
          <tbody><tr>
            <td><b>'. $this->_i18n->getMessage('entity_user_inactivity_login') . '</b><br>
            <small>'. $this->_i18n->getMessage('entity_users_lastonline') . ': '. date('d.m.y, H:i',$row['entity_users_lastonline']) .'</small></td>
            <td style=\'text-align: center; font-weight: bold; color: '. $this->_color($this->_format($row['entity_user_inactivity_login'])) .'\'>'.$this->_format($row['entity_user_inactivity_login']).' %</td>
          </tr>
          <tr>
            <td><b>'. $this->_i18n->getMessage('entity_user_inactivity_aufstellung') . '</b></td>
            <td style=\'text-align: center; font-weight: bold; color: '.$this->_color($this->_format($row['entity_user_inactivity_aufstellung'])).'\'>'.$this->_format($row['entity_user_inactivity_aufstellung']).' %</td>
          </tr>
          <tr>
            <td><b>'. $this->_i18n->getMessage('entity_user_inactivity_transfer') . '</b><br>
            <small>'. sprintf($this->_i18n->getMessage('entity_user_inactivity_transfer_check'), date('d.m.y, H:i',$row['entity_user_inactivity_transfer_check'])) . '</small></td>
            <td style=\'text-align: center; font-weight: bold; color: '.$this->_color($this->_format($row['entity_user_inactivity_transfer'])).'\'>'.$this->_format($row['entity_user_inactivity_transfer']).' %</td>
          </tr>
          <tr>
            <td><b>'. $this->_i18n->getMessage('entity_user_inactivity_vertragsauslauf') . '</b></td>
            <td style=\'text-align: center; font-weight: bold; color: '.$this->_color($this->_format($row['entity_user_inactivity_vertragsauslauf'])).'\'>'.$this->_format($row['entity_user_inactivity_vertragsauslauf']).' %</td>
          </tr></tbody>
          <tfoot>
          <tr>
            <td><b>'. $this->_i18n->getMessage('popup_user_inactivity_total') . '</b></td>
            <td style=\'text-align: center; font-weight: bold; color: '.$this->_color($this->_format($gesamt)).'\'>'.$this->_format($gesamt).' %';
		if ($gesamt > 100) $popup .= '<br/>(' . $gesamt .'%)';
		$popup .= '</td>
          </tr>
		</tfoot>
        </table>';
		$popup .= '</div>';
		$popup .= '<div class=\'modal-footer\'><button class=\'btn btn-primary\' data-dismiss=\'modal\' aria-hidden=\'true\'>'. $this->_i18n->getMessage('button_close') . '</button></div>';
		$popup .= '</div>';
		return $popup; }
	function _format($rate) {
		$rate = ($rate) ? $rate : 0;
		if ($rate < 0) $rate = 0;
		elseif ($rate > 100) $rate = 100;
		return $rate; }}
class MatchReportLinkConverter {
	private $_i18n;
	private $_websoccer;
	function __construct($i18n, $websoccer) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer; }
	function toHtml($row) {
		$output = '<div class=\'btn-group\'>';
		$output .= '<a class=\'btn btn-small dropdown-toggle\' data-toggle=\'dropdown\' href=\'#\'>';
		$output .= $this->_i18n->getMessage('entity_match_matchreportitems') . ' <span class=\'caret\'></span>';
		$output .= '</a>';
		$output .= '<ul class=\'dropdown-menu\'>';
		$output .= '<li><a href=\'?site=manage-match-playerstatistics&match='. $row['id']. '\'><i class=\'icon-cog\'></i> '. $this->_i18n->getMessage('match_manage_playerstatistics') .'</a></li>';
		$output .= '<li><a href=\'?site=manage-match-reportitems&match='. $row['id']. '\'><i class=\'icon-th-list\'></i> '. $this->_i18n->getMessage('match_manage_reportitems') .'</a></li>';
		if (!$row['entity_match_berechnet']) $output .= '<li><a href=\'?site=manage-match-complete&match='. $row['id']. '\'><i class=\'icon-ok-sign\'></i> '. $this->_i18n->getMessage('match_manage_complete') .'</a></li>';
		$output .= '</ul>';
		$output .= '</div>';
		return $output; }
	function toText($value) { return $value; }
	function toDbValue($value) { return $this->toText($value); }}
class MoneyTransactionConverter {
	private $_i18n;
	private $_websoccer;
	function __construct($i18n, $websoccer) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer; }
	function toHtml($value) { return $this->toText($value); }
	function toText($value) { return $value; }
	function toDbValue($value) {
		$amount = (int) $value;
		if (isset($_POST['verein_id']) && $_POST['verein_id']) {
			$db = DbConnection::getInstance();
			$columns = 'finanz_budget';
			$fromTable = $this->_websoccer->getConfig('db_prefix') .'_verein';
			$whereCondition = 'id = %d';
			$result = $db->querySelect($columns, $fromTable, $whereCondition, $_POST['verein_id'], 1);
			$team = $result->fetch_array();
			$budget = $team['finanz_budget'] + $amount;
			$updatecolumns = array('finanz_budget' => $budget);
			$db->queryUpdate($updatecolumns, $fromTable, $whereCondition, $_POST['verein_id']); }
		return $amount; }}
class PaymentSenderMessageConverter {
	private $_i18n;
	private $_websoccer;
	function __construct($i18n, $websoccer) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer; }
	function toHtml($row) {
		include(sprintf(CONFIGCACHE_MESSAGES, $this->_i18n->getCurrentLanguage()));
		if (isset($msg[$row['entity_transaction_absender']])) return $msg[$row['entity_transaction_absender']];
		return $row['entity_transaction_absender']; }
	function toText($value) { return $value; }
	function toDbValue($value) { return $this->toText($value); }}
class PaymentSubjectMessageConverter {
	private $_i18n;
	private $_websoccer;
	function __construct($i18n, $websoccer) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer; }
	function toHtml($row) {
		include(sprintf(CONFIGCACHE_MESSAGES, $this->_i18n->getCurrentLanguage()));
		if (isset($msg[$row['entity_transaction_verwendung']])) return $msg[$row['entity_transaction_verwendung']];
		return $row['entity_transaction_verwendung']; }
	function toText($value) { return $value; }
	function toDbValue($value) { return $this->toText($value); }}
class PremiumTransactionConverter {
	private $_i18n;
	private $_websoccer;
	function __construct($i18n, $websoccer) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer; }
	function toHtml($value) { return $this->toText($value); }
	function toText($value) { return $value; }
	function toDbValue($value) {
		$amount = (int) $value;
		if (isset($_POST['user_id']) && $_POST['user_id']) {
			$db = DbConnection::getInstance();
			$columns = 'premium_balance';
			$fromTable = $this->_websoccer->getConfig('db_prefix') .'_user';
			$whereCondition = 'id = %d';
			$result = $db->querySelect($columns, $fromTable, $whereCondition, $_POST['user_id'], 1);
			$user = $result->fetch_array();
			$budget = $user['premium_balance'] + $amount;
			$updatecolumns = array('premium_balance' => $budget);
			$db->queryUpdate($updatecolumns, $fromTable, $whereCondition, $_POST['user_id']); }
		return $amount; }}
class TransferOfferApprovalLinkConverter {
	private $_i18n;
	private $_websoccer;
	function __construct($i18n, $websoccer) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer; }
	function toHtml($row) {
		if ($row['entity_transfer_offer_admin_approval_pending']) {
			$output = ' <a href=\'?site=manage&entity=transfer_offer&action=transferofferapprove&id='. $row['id']. '\' class=\'btn btn-small btn-success\'><i class=\'icon-ok icon-white\'></i> '. $this->_i18n->getMessage('button_approve') .'</a>'; }
		else $output = '<i class=\'icon-ban-circle\'></i>';
		return $output; }
	function toText($value) { return $value; }
	function toDbValue($value) { return $this->toText($value); }}
class UserPasswordConverter {
	private $_i18n;
	private $_websoccer;
	function __construct($i18n, $websoccer) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer; }
	function toHtml($value) { return $this->toText($value); }
	function toText($value) { return $value; }
	function toDbValue($value) {
		if (isset($_POST['id']) && $_POST['id']) {
			$db = DbConnection::getInstance();
			$columns = 'passwort, passwort_salt';
			$fromTable = $this->_websoccer->getConfig('db_prefix') .'_user';
			$whereCondition = 'id = %d';
			$result = $db->querySelect($columns, $fromTable, $whereCondition, $_POST['id'], 1);
			$user = $result->fetch_array();
			if (strlen($value)) $passwort = SecurityUtil::hashPassword($value, $user['passwort_salt']);
			else $passwort = $user['passwort']; }
		else $passwort = SecurityUtil::hashPassword($value, '');
		return $passwort; }}
abstract class AbstractEvent {
	public $websoccer;
	public $db;
	public $i18n;
	function __construct($websoccer, $db, $i18n) {
		$this->websoccer = $websoccer;
		$this->db = $db;
		$this->i18n = $i18n; }}
class MatchCompletedEvent extends AbstractEvent {
	public $match;
	function __construct($websoccer, $db, $i18n, SimulationMatch $match) {
		parent::__construct($websoccer, $db, $i18n);
		$this->match = $match; }}
class PlayerTrainedEvent extends AbstractEvent {
	public $playerId;
	public $teamId;
	public $trainerId;
	public $effectFreshness;
	public $effectTechnique;
	public $effectStamina;
	public $effectSatisfaction;
	function __construct($websoccer, $db, $i18n, $playerId, $teamId, $trainerId, &$effectFreshness, &$effectTechnique, &$effectStamina, &$effectSatisfaction) {
		parent::__construct($websoccer, $db, $i18n);
		$this->playerId = $playerId;
		$this->teamId = $teamId;
		$this->trainerId = $trainerId;
		$this->effectFreshness =& $effectFreshness;
		$this->effectTechnique =& $effectTechnique;
		$this->effectStamina =& $effectStamina;
		$this->effectSatisfaction =& $effectSatisfaction; }}
class SeasonOfTeamCompletedEvent extends AbstractEvent {
	public $teamId;
	public $seasonId;
	public $rank;
	function __construct($websoccer, $db, $i18n, $teamId, $seasonId, $rank) {
		parent::__construct($websoccer, $db, $i18n);
		$this->teamId = $teamId;
		$this->seasonId = $seasonId;
		$this->rank = $rank; }}
class TicketsComputedEvent extends AbstractEvent {
	public $match;
	public $stadiumId;
	public $rateStands;
	public $rateSeats;
	public $rateStandsGrand;
	public $rateSeatsGrand;
	public $rateVip;
	function __construct($websoccer, $db, $i18n, SimulationMatch $match, $stadiumId, &$rateStands, &$rateSeats, &$rateStandsGrand, &$rateSeatsGrand, &$rateVip) {
		parent::__construct($websoccer, $db, $i18n);
		$this->match = $match;
		$this->stadiumId = $stadiumId;
		$this->rateStands =& $rateStands;
		$this->rateSeats =& $rateSeats;
		$this->rateStandsGrand =& $rateStandsGrand;
		$this->rateSeatsGrand =& $rateSeatsGrand;
		$this->rateVip =& $rateVip; }}
class UserRegisteredEvent extends AbstractEvent {
	public $userId;
	public $username;
	public $email;
	function __construct($websoccer, $db, $i18n, $userid, $username, $email) {
		parent::__construct($websoccer, $db, $i18n);
		$this->userId = $userid;
		$this->username = $username;
		$this->email = $email; }}
class YouthPlayerPlayedEvent extends AbstractEvent {
	public $player;
	public $strengthChange;
	function __construct($websoccer, $db, $i18n, SimulationPlayer $player, &$strengthChange) {
		parent::__construct($websoccer, $db, $i18n);
		$this->player = $player;
		$this->strengthChange =& $strengthChange; }}
class YouthPlayerScoutedEvent extends AbstractEvent {
	public $teamId;
	public $scoutId;
	public $playerId;
	function __construct($websoccer, $db, $i18n, $teamId, $scoutId, $playerId) {
		parent::__construct($websoccer, $db, $i18n);
		$this->teamId = $teamId;
		$this->scoutId = $scoutId;
		$this->playerId = $playerId; }}
abstract class AbstractJob {
	protected $_websoccer;
	protected $_db;
	protected $_i18n;
	private $_id;
	private $_interval;
	function __construct($websoccer, $db, $i18n, $jobId, $errorOnAlreadyRunning = TRUE) {
		$this->_websoccer = $websoccer;
		$this->_db = $db;
		$this->_i18n = $i18n;
		$this->_id = $jobId;
		$xmlConfig = $this->getXmlConfig();
		if ($errorOnAlreadyRunning) {
			$initTime = (int) $xmlConfig->attributes()->inittime;
			$now = $websoccer->getNowAsTimestamp();
			$timeoutTime = $now - 5 * 60;
			if ($initTime > $timeoutTime) throw new Exception('Another instance of this job is already running.');
			$this->replaceAttribute('inittime', $now); }
		$interval = (int) $xmlConfig->attributes()->interval;
		$this->_interval = $interval * 60;
		ignore_user_abort(TRUE);
		set_time_limit(0);
		gc_enable(); }
	function __destruct() {
		$this->_ping($this->_websoccer->getNowAsTimestamp());
		$this->replaceAttribute('inittime', 0); }
	function start() {
		$this->replaceAttribute('stop', '0');
		$this->replaceAttribute('error', '');
		$this->_ping(0);
		do {$xmlConfig = $this->getXmlConfig();
			$stop = (int) $xmlConfig->attributes()->stop;
			if ($stop === 1) $this->stop();
			$now = $this->_websoccer->getNowAsTimestamp();
			$lastPing = (int) $xmlConfig->attributes()->last_ping;
			if ($lastPing > 0) {
				$myOwnLastPing = $now - $this->_interval + 5;
				if ($lastPing > $myOwnLastPing) exit; }
			$this->_ping($now);
			try{$this->_db->close();
				$this->_db->connect($this->_websoccer->getConfig('db_host'), $this->_websoccer->getConfig('db_user'), $this->_websoccer->getConfig('db_passwort'), $this->_websoccer->getConfig('db_name'));
				$this->execute();
				gc_collect_cycles(); }
			catch (Exception $e) {
				$this->replaceAttribute('error', $e->getMessage());
				$this->stop(); }
			$this->_db->close();
			sleep($this->_interval); } while(true); }
	function stop() {
		$this->replaceAttribute('stop', '1');
		exit; }
	function _ping($time) { $this->replaceAttribute('last_ping', $time); }
	function getXmlConfig() {
		$xml = simplexml_load_file(JOBS_CONFIG_FILE);
		$xmlConfig = $xml->xpath('//job[@id = \''. $this->_id . '\']');
		if (!$xmlConfig) throw new Exception('Job config not found.');
		return $xmlConfig[0]; }
	function replaceAttribute($name, $value) {
		$fp = fopen(BASE_FOLDER . '/admin/config/lockfile.txt', 'r');
		flock($fp, LOCK_EX);
		$xml = simplexml_load_file(JOBS_CONFIG_FILE);
		if ($xml === FALSE) {
			$errorMessages = '';
			$errors = libxml_get_errors();
			foreach ($errors as $error) $errorMessages = $errorMessages . "\n" . $error;
			throw new Exception('Job with ID \'' . $this->_id . '\': Could not update attribute \'' . $name . '\' with value \'' . $value . '\'. Errors: ' . $errorMessages); }
		$xmlConfig = $xml->xpath('//job[@id = \''. $this->_id . '\']');
		$xmlConfig[0][$name] = $value;
		$successfulWritten = $xml->asXML(JOBS_CONFIG_FILE);
		if (!$successfulWritten) throw new Exception('Job with ID \'' . $this->_id . '\': Could not save updated attribute \'' . $name . '\' with value \'' . $value . '\'.');
		flock($fp, LOCK_UN); }
	abstract function execute(); }
class AcceptStadiumConstructionWorkJob extends AbstractJob {
	function execute() {
		$this->checkStadiumConstructions();
		$this->checkTrainingCamps(); }
	function checkStadiumConstructions() {
		$constructions = StadiumsDataService::getDueConstructionOrders($this->_websoccer, $this->_db);
		$newDeadline = $this->_websoccer->getNowAsTimestamp() + $this->_websoccer->getConfig('stadium_construction_delay') * 24 * 3600;
		foreach ($constructions as $construction) {
			$pStatus = array();
			$pStatus['completed'] = $construction['builder_reliability'];
			$pStatus['notcompleted'] = 100 - $pStatus['completed'];
			$constructionResult = SimulationHelper::selectItemFromProbabilities($pStatus);
			if ($constructionResult == 'notcompleted') {
				$this->_db->queryUpdate(array('deadline' => $newDeadline), $this->_websoccer->getConfig('db_prefix') . '_stadium_construction', 'id = %d', $construction['id']);
				if ($construction['user_id']) NotificationsDataService::createNotification($this->_websoccer, $this->_db, $construction['user_id'], 'stadium_construction_notification_delay', null, 'stadium_construction', 'stadium'); }
			else {
				$stadium = StadiumsDataService::getStadiumByTeamId($this->_websoccer, $this->_db, $construction['team_id']);
				$columns = array();
				$columns['p_steh'] = $stadium['places_stands'] + $construction['p_steh'];
				$columns['p_sitz'] = $stadium['places_seats'] + $construction['p_sitz'];
				$columns['p_haupt_steh'] = $stadium['places_stands_grand'] + $construction['p_haupt_steh'];
				$columns['p_haupt_sitz'] = $stadium['places_seats_grand'] + $construction['p_haupt_sitz'];
				$columns['p_vip'] = $stadium['places_vip'] + $construction['p_vip'];
				$this->_db->queryUpdate($columns, $this->_websoccer->getConfig('db_prefix') . '_stadion', 'id = %d', $stadium['stadium_id']);
				$this->_db->queryDelete($this->_websoccer->getConfig('db_prefix') . '_stadium_construction', 'id = %d', $construction['id']);
				if ($construction['user_id']) NotificationsDataService::createNotification($this->_websoccer, $this->_db, $construction['user_id'], 'stadium_construction_notification_completed', null, 'stadium_construction', 'stadium'); }}}
	function checkTrainingCamps() {
		$fromTable = $this->_websoccer->getConfig('db_prefix') . '_trainingslager_belegung AS B';
		$fromTable .= ' INNER JOIN ' . $this->_websoccer->getConfig('db_prefix') . '_trainingslager AS C ON C.id = B.lager_id';
		$columns['B.id'] = 'id';
		$columns['B.datum_start'] = 'date_start';
		$columns['B.datum_ende'] = 'date_end';
		$columns['B.verein_id'] = 'team_id';
		$columns['C.name'] = 'name';
		$columns['C.land'] = 'country';
		$columns['C.preis_spieler_tag'] = 'costs';
		$columns['C.p_staerke'] = 'effect_strength';
		$columns['C.p_technik'] = 'effect_strength_technique';
		$columns['C.p_kondition'] = 'effect_strength_stamina';
		$columns['C.p_frische'] = 'effect_strength_freshness';
		$columns['C.p_zufriedenheit'] = 'effect_strength_satisfaction';
		$whereCondition = 'B.datum_ende < %d';
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $this->_websoccer->getNowAsTimestamp());
		while ($booking = $result->fetch_array()) TrainingcampsDataService::executeCamp($this->_websoccer, $this->_db, $booking['team_id'], $booking); }}
class AddPlayerWithoutTeamToTransfermarketJob extends AbstractJob {
	function execute() { TransfermarketDataService::movePlayersWithoutTeamToTransfermarket($this->_websoccer, $this->_db); }}
class ExecuteTransfersJob extends AbstractJob {
	function execute() { TransfermarketDataService::executeOpenTransfers($this->_websoccer, $this->_db); }}
class SimulateMatchesJob extends AbstractJob {
	function execute() { MatchSimulationExecutor::simulateOpenMatches($this->_websoccer, $this->_db); }}
class UpdateStatisticsJob extends AbstractJob {
	function execute() {
		$pointsWin = 3;
		$statisticTable = $this->_websoccer->getConfig('db_prefix') . '_team_league_statistics';
		$clubTable = $this->_websoccer->getConfig('db_prefix') . '_verein';
		$matchTable = $this->_websoccer->getConfig('db_prefix') . '_spiel';
		$query = "REPLACE INTO $statisticTable
SELECT team_id,
	season_id,
	(home_wins * $pointsWin + home_draws + guest_wins * $pointsWin + guest_draws) AS total_points,
	(home_goals + guest_goals) AS total_goals,
	(home_goalsreceived + guest_goalsreceived) AS total_goalsreceived,
	(home_goals + guest_goals - home_goalsreceived - guest_goalsreceived) AS total_goalsdiff,
	(home_wins + guest_wins) AS total_wins,
	(home_draws + guest_draws) AS total_draws,
	(home_losses + guest_losses) AS total_losses,
	(home_wins * $pointsWin + home_draws) AS home_points,
	home_goals,
	home_goalsreceived,
	(home_goals - home_goalsreceived) AS home_goalsdiff,
	home_wins,
	home_draws,
	home_losses,
	(guest_wins * $pointsWin + guest_draws) AS guest_points,
	guest_goals,
	guest_goalsreceived,
	(guest_goals - guest_goalsreceived) AS guest_goalsdiff,
	guest_wins,
	guest_draws,
	guest_losses
FROM (SELECT C.id AS team_id, M.saison_id AS season_id,
		SUM(CASE WHEN M.home_verein = C.id AND M.home_tore > M.gast_tore THEN 1 ELSE 0 END) AS home_wins,
		SUM(CASE WHEN M.home_verein = C.id AND M.home_tore < M.gast_tore THEN 1 ELSE 0 END) AS home_losses,
		SUM(CASE WHEN M.home_verein = C.id AND M.home_tore = M.gast_tore THEN 1 ELSE 0 END) AS home_draws,
		SUM(CASE WHEN M.home_verein = C.id THEN M.home_tore ELSE 0 END) AS home_goals,
		SUM(CASE WHEN M.home_verein = C.id THEN M.gast_tore ELSE 0 END) AS home_goalsreceived,
		SUM(CASE WHEN M.gast_verein = C.id AND M.gast_tore > M.home_tore THEN 1 ELSE 0 END) AS guest_wins,
		SUM(CASE WHEN M.gast_verein = C.id AND M.gast_tore < M.home_tore THEN 1 ELSE 0 END) AS guest_losses,
		SUM(CASE WHEN M.gast_verein = C.id AND M.home_tore = M.gast_tore THEN 1 ELSE 0 END) AS guest_draws,
		SUM(CASE WHEN M.gast_verein = C.id THEN M.gast_tore ELSE 0 END) AS guest_goals,
		SUM(CASE WHEN M.gast_verein = C.id THEN M.home_tore ELSE 0 END) AS guest_goalsreceived
	FROM $clubTable AS C
	INNER JOIN $matchTable AS M ON M.home_verein = C.id OR M.gast_verein = C.id
	WHERE M.saison_id > 0 AND M.berechnet = '1'
	GROUP BY C.id, M.saison_id) AS matches";
		$this->_db->executeQuery($query);
		$strengthQuery = ' UPDATE '. $this->_websoccer->getConfig('db_prefix') .'_verein c INNER JOIN (';
		$strengthQuery .= ' SELECT verein_id, AVG( w_staerke ) AS strength_avg';
		$strengthQuery .= ' FROM '. $this->_websoccer->getConfig('db_prefix') .'_spieler';
		$strengthQuery .= ' GROUP BY verein_id';
		$strengthQuery .= ' ) AS r ON r.verein_id = c.id';
		$strengthQuery .= ' SET c.strength = r.strength_avg';
		$this->_db->executeQuery($strengthQuery); }}
class UserInactivityCheckJob extends AbstractJob {
	function execute() {
		$users = UsersDataService::getActiveUsersWithHighscore($this->_websoccer, $this->_db, 0, 1000);
		foreach ($users as $user) UserInactivityDataService::computeUserInactivity($this->_websoccer, $this->_db, $user['id']); }}
class DefaultUserLoginMethod {
	function __construct($website, $db) {
		$this->_websoccer = $website;
		$this->_db = $db; }
	function authenticateWithEmail($email, $password) { return $this->authenticate('UPPER(email)', strtoupper($email), $password); }
	function authenticateWithUsername($nick, $password) { return $this->authenticate('nick', $nick, $password); }
	function authenticate($column, $loginstr, $password) {
		$fromTable = $this->_websoccer->getConfig('db_prefix') .'_user';
		$columns = 'id, passwort, passwort_neu, passwort_salt';
		$wherePart = $column . ' = \'%s\' AND status = 1';
		$parameter = $loginstr;
		$result = $this->_db->querySelect($columns, $fromTable, $wherePart, $parameter);
		$userdata = $result->fetch_array();
		if (!$userdata['id']) return FALSE;
		$inputPassword = SecurityUtil::hashPassword($password, $userdata['passwort_salt']);
		if ($inputPassword != $userdata['passwort'] && $inputPassword != $userdata['passwort_neu']) return FALSE;
		if ($userdata['passwort_neu'] == $inputPassword) {
			$columns = array('passwort' => $inputPassword, 'passwort_neu_angefordert' => 0, 'passwort_neu' => '');
			$whereCondition = 'id = %d';
			$parameter = $userdata['id'];
			$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $parameter); }
		return $userdata['id']; }}
class DemoUserLoginMethod {
	function __construct($website, $db) {
		$this->_websoccer = $website;
		$this->_db = $db; }
	function authenticateWithEmail($email, $password) {
		$mysqli = new mysqli($this->_websoccer->getConfig('db_host'), $this->_websoccer->getConfig('db_user'), $this->_websoccer->getConfig('db_passwort'), $this->_websoccer->getConfig('db_name'));
		$escapedEMail = $mysqli->real_escape_string($email);
		$dbresult = $mysqli->query('SELECT password FROM mydummy_table WHERE email = \'' . $escapedEMail . '\'');
		if (!$dbresult) throw new Exception('Database Query Error: ' . $mysqli->error);
		$myUser = $dbresult->fetch_array();
		$mysqli->close();
		if (!$myUser) return FALSE;
		if ($myUser['password'] != md5($password)) return FALSE;
		$existingUserId = UsersDataService::getUserIdByEmail($this->_websoccer, $this->_db, strtolower($email));
		if ($existingUserId > 0) return $existingUserId;
		return UsersDataService::createLocalUser($this->_websoccer, $this->_db, null, $email); }
	function authenticateWithUsername($nick, $password) { throw new Exception('Unsupported action.'); }}
class StadiumEnvironmentPlugin {
	static function addTrainingBonus(PlayerTrainedEvent $event) {
		$bonus = self::getBonusSumFromBuildings($event->websoccer, $event->db, 'effect_training', $event->teamId);
		$event->effectSatisfaction += $bonus;
		$event->effectFreshness += $bonus; }
	static function addYouthPlayerSkillBonus(YouthPlayerScoutedEvent $event) {
		$bonus = self::getBonusSumFromBuildings($event->websoccer, $event->db, 'effect_youthscouting', $event->teamId);
		if ($bonus != 0) {
			$playerTable = $event->websoccer->getConfig('db_prefix') . '_youthplayer';
			$result = $event->db->querySelect('strength', $playerTable, 'id = %d', $event->playerId);
			$player = $result->fetch_array();
			if ($player) {
				$minStrength = (int) $event->websoccer->getConfig('youth_scouting_min_strength');
				$maxStrength = (int) $event->websoccer->getConfig('youth_scouting_max_strength');
				$strength = max($minStrength, min($maxStrength, $player['strength'] + $bonus));
				if ($strength != $player['strength']) $event->db->queryUpdate(array('strength' => $strength), $playerTable, 'id = %d', $event->playerId); }}}
	static function addTicketsBonus(TicketsComputedEvent $event) {
		$bonus = self::getBonusSumFromBuildings($event->websoccer, $event->db, 'effect_tickets', $event->match->homeTeam->id);
		if ($bonus == 0) return;
		$bonus = $bonus / 100;
		if ($event->rateSeats) $event->rateSeats = max(0.0, min(1.0, $event->rateSeats + $bonus));
		if ($event->rateStands) $event->rateStands = max(0.0, min(1.0, $event->rateStands + $bonus));
		if ($event->rateSeatsGrand) $event->rateSeatsGrand = max(0.0, min(1.0, $event->rateSeatsGrand + $bonus));
		if ($event->rateStandsGrand) $event->rateStandsGrand = max(0.0, min(1.0, $event->rateStandsGrand + $bonus));
		if ($event->rateVip) $event->rateVip = max(0.0, min(1.0, $event->rateVip + $bonus)); }
	static function creditAndDebitAfterHomeMatch(MatchCompletedEvent $event) {
		if ($event->match->type == 'Freundschaft' || $event->match->homeTeam->isNationalTeam) return;
		$homeTeamId = $event->match->homeTeam->id;
		$sum = self::getBonusSumFromBuildings($event->websoccer, $event->db, 'effect_income', $homeTeamId);
		if ($sum > 0) BankAccountDataService::creditAmount($event->websoccer, $event->db, $homeTeamId, $sum, 'stadiumenvironment_matchincome_subject', $event->websoccer->getConfig('projectname'));
		else BankAccountDataService::debitAmount($event->websoccer, $event->db, $homeTeamId, abs($sum), 'stadiumenvironment_costs_per_match_subject', $event->websoccer->getConfig('projectname')); }
	static function handleInjuriesAfterMatch(MatchCompletedEvent $event) {
		if ($event->match->type == 'Freundschaft' || $event->match->homeTeam->isNationalTeam) return;
		$homeTeamId = $event->match->homeTeam->id;
		$sumHome = self::getBonusSumFromBuildings($event->websoccer, $event->db, 'effect_injury', $homeTeamId);
		$guestTeamId = $event->match->guestTeam->id;
		$sumGuest = self::getBonusSumFromBuildings($event->websoccer, $event->db, 'effect_injury', $guestTeamId);
		if ($sumHome > 0 || $sumGuest > 0) {
			$playerTable = $event->websoccer->getConfig('db_prefix') . '_spieler';
			$result = $event->db->querySelect('id,verein_id AS team_id,verletzt AS injured', $playerTable, '(verein_id = %d OR verein_id = %d) AND verletzt > 0', array($homeTeamId, $guestTeamId));
			while ($player = $result->fetch_array()) {
				$reduction = 0;
				if ($sumHome > 0 && $player['team_id'] == $homeTeamId) $reduction = $sumHome;
				elseif ($sumGuest > 0 && $player['team_id'] == $guestTeamId) $reduction = $sumGuest;
				if ($reduction > 0) {
					$injured = max(0, $player['injured'] - $reduction);
					$event->db->queryUpdate(array('verletzt' => $injured), $playerTable, 'id = %d', $player['id']); }}
	 }}
	static function getBonusSumFromBuildings($websoccer, $db, $attributeName, $teamId) {
		$dbPrefix = $websoccer->getConfig('db_prefix');
		$result = $db->querySelect('SUM(' . $attributeName . ') AS attrSum', $dbPrefix . '_buildings_of_team INNER JOIN '. $dbPrefix . '_stadiumbuilding ON id = building_id', 'team_id = %d AND construction_deadline < %d', array($teamId, $websoccer->getNowAsTimestamp()));
		$resArray = $result->fetch_array();
		if ($resArray) return $resArray['attrSum'];
		return 0; }}
class DefaultBootstrapSkin {
	protected $_websoccer;
	function __construct($websoccer) { $this->_websoccer = $websoccer; }
	function getTemplatesSubDirectory() { return 'default'; }
	function getCssSources() {
		$files[] = '//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css';
		$files[] = '//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css';
		$dir = $this->_websoccer->getConfig('context_root') . '/css/';
		$files[] = $dir . 'defaultskin.css';
		$files[] = $dir . 'websoccer.css';
		$files[] = $dir . 'bootstrap-responsive.min.css';
		return $files; }
	function getJavaScriptSources() {
		$dir = $this->_websoccer->getConfig('context_root') . '/js/';
		$files[] = '//code.jquery.com/jquery-1.11.1.min.js';
		if (DEBUG) {
			$files[] = $dir . 'bootstrap.min.js';
			$files[] = $dir . 'jquery.blockUI.js';
			$files[] = $dir . 'wsbase.js'; }
		else $files[] = $dir . 'websoccer.min.js';
		return $files; }
	function getTemplate($templateName) { return $templateName .'.twig'; }
	function getImage($fileName) {
		if (file_exists(BASE_FOLDER . '/img/' . $fileName)) return $this->_websoccer->getConfig('context_root') . '/img/' . $fileName;
		return FALSE; }
	function __toString() { return 'DefaultBootstrapSkin'; }}
class GreenBootstrapSkin extends DefaultBootstrapSkin {
	function getCssSources() {
		$dir = $this->_websoccer->getConfig('context_root') . '/css/';
		$files[] = $dir . 'bootstrap_green.css';
		$files[] = $dir . 'websoccer.css';
		$files[] = $dir . 'bootstrap-responsive.min.css';
		$files[] = '//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css';
		if ($this->_websoccer->getPageId() == 'formation' || $this->_websoccer->getPageId() == 'training') $files[] = $dir . 'slider.css';
		if ($this->_websoccer->getPageId() == 'formation' || $this->_websoccer->getPageId() == 'youth-formation' || $this->_websoccer->getPageId() == 'teamoftheday') {
			$files[] = $dir . 'formation.css';
			$files[] = $dir . 'bootstrap-switch.css'; }
		return $files; }}
class SchedioartFootballSkin extends DefaultBootstrapSkin {
	function getTemplatesSubDirectory() { return 'schedio'; }
	function getCssSources() {
		$dir = $this->_websoccer->getConfig('context_root') . '/css/';
		if (DEBUG) {
			$files[] = $dir . 'schedioart/bootstrap.css';
			$files[] = $dir . 'schedioart/schedioartskin.css';
			$files[] = $dir . 'websoccer.css';
			$files[] = $dir . 'bootstrap-responsive.min.css'; }
		else $files[] = $dir . 'schedioart/theme.min.css';
		$files[] = '//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css';
		return $files; }}
class EmailValidator {
	private $_i18n;
	private $_websoccer;
	private $_value;
	function __construct($i18n, $websoccer, $value) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_value = $value; }
	function isValid() { return filter_var($this->_value, FILTER_VALIDATE_EMAIL); }
	function getMessage() { return $this->_i18n->getMessage('validation_error_email'); }}
class PasswordValidator {
	private $_i18n;
	private $_websoccer;
	private $_value;
	function __construct($i18n, $websoccer, $value) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_value = $value; }
	function isValid() {
		if (!preg_match('/[A-Za-z]/', $this->_value) || !preg_match('/[0-9]/', $this->_value)) return FALSE;
		$blacklist = array('test123', 'abc123', 'passw0rd', 'passw0rt');
		if (in_array(strtolower($this->_value), $blacklist)) return FALSE;
		return TRUE; }
	function getMessage() { return $this->_i18n->getMessage('validation_error_password'); }}
class UniqueCupNameValidator {
	private $_i18n;
	private $_websoccer;
	private $_value;
	function __construct($i18n, $websoccer, $value) {
		$this->_i18n = $i18n;
		$this->_websoccer = $websoccer;
		$this->_value = $value; }
	function isValid() {
		$db = DbConnection::getInstance();
		$result = $db->querySelect('id', $this->_websoccer->getConfig('db_prefix') . '_cup', 'name = \'%s\'', $this->_value, 1);
		$cups = $result->fetch_array();
		if (isset($cups['id']) && (!isset($_POST['id']) || $_POST['id'] !== $cups['id'])) return FALSE;
		$result = $db->querySelect('COUNT(*) AS hits', $this->_websoccer->getConfig('db_prefix') . '_spiel', 'pokalname = \'%s\'', $this->_value);
		$matches = $result->fetch_array();
		if ($matches['hits'] && !isset($_POST['id'])) return FALSE;
		return TRUE; }
	function getMessage() { return $this->_i18n->getMessage('validation_error_uniquecupname'); }}
//+ owsPro: Base for all models, sets the required basic functions, which are overwritten by the executing class, depending on the required change of the basic code. */
class Model{
	//- owsPro: '__construct'  Available for compatibility reasons and in owsPro obsolet
	function __construct($db, $i18n, $websoccer){ $this->_websoccer=$websoccer; $this->_db=$db; $this->_i18n=$i18n; }
	function getTemplateParameters() { return array(); }
	function renderView(){ return TRUE; }}
class AbsenceAlertModel extends Model {
	function getTemplateParameters() { return array('absence' => $this->_absence); }
	function renderView() {
		$this->_absence = AbsencesDataService::getCurrentAbsenceOfUser($this->_websoccer, $this->_db, $this->_websoccer->getUser()->id);
		return ($this->_absence != NULL); }}
class AbsenceModel extends Model {
	function getTemplateParameters() {
		$absence = AbsencesDataService::getCurrentAbsenceOfUser($this->_websoccer, $this->_db, $this->_websoccer->getUser()->id);
		$deputyName = '';
		if ($absence && $absence['deputy_id']) {
			$result = $this->_db->querySelect('nick', $this->_websoccer->getConfig('db_prefix') . '_user', 'id = %d', $absence['deputy_id']);
			$deputy = $result->fetch_array();
			$deputyName = $deputy['nick']; }
		return array('currentAbsence' => $absence, 'deputyName' => $deputyName); }}
class AdsModel extends Model { function renderView() { return ($this->_websoccer->getUser()->premiumBalance == 0 || !$this->_websoccer->getConfig('frontend_ads_hide_for_premiumusers')); }}
class AlltimeTableModel extends Model {
	function getTemplateParameters() {
		$teams = TeamsDataService::getTeamsOfLeagueOrderedByAlltimeTableCriteria($this->_websoccer, $this->_db, $this->_leagueId, $this->_type);
		return array("leagueId" => $this->_leagueId, "teams" => $teams); }
	function renderView() {
		$this->_leagueId = (int) $this->_websoccer->getRequestParameter("id");
		$this->_type = $this->_websoccer->getRequestParameter("type");
		$clubId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		if ($this->_leagueId == 0 && $clubId > 0) {
			$result = $db->querySelect("liga_id", $this->_websoccer->getConfig("db_prefix") . "_verein", "id = %d", $clubId, 1);
			$club = $result->fetch_array();
			$this->_leagueId = $club["liga_id"]; }
		return ($this->_leagueId  > 0); }}
class AllUserActivitiesModel extends Model { function getTemplateParameters() { return array("activities" => ActionLogDataService::getLatestActionLogs($this->_websoccer, $this->_db, 5)); }}
class BadgesModel extends Model{
	function getTemplateParameters() {
		$result = $this->_db->querySelect('*', $this->_websoccer->getConfig('db_prefix') . '_badge', '1 ORDER BY event ASC, level ASC');
		$badges = array();
		while ($badge = $result->fetch_array()) $badges[] = $badge;
		return array("badges" => $badges); }}
class ClubSearchModel extends Model {
	function getTemplateParameters() {
		$query = $this->_websoccer->getRequestParameter("query");
		$teams = TeamsDataService::findTeamNames($this->_websoccer, $this->_db, $query);
		return array("items" => $teams); }}
class CupGroupDetailsModel extends Model{
	function getTemplateParameters() {
		$cupRoundId = $this->_websoccer->getRequestParameter("roundid");
		$cupGroup = $this->_websoccer->getRequestParameter("group");
		$columns = "C.name AS cup_name, R.name AS round_name";
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_cup_round AS R";
		$fromTable .= " INNER JOIN " . $this->_websoccer->getConfig("db_prefix") . "_cup AS C ON C.id = R.cup_id";
		$result = $this->_db->querySelect($columns, $fromTable, "R.id = %d", $cupRoundId);
		$round = $result->fetch_array();
		$matches = MatchesDataService::getMatchesByCupRoundAndGroup($this->_websoccer, $this->_db, $round["cup_name"], $round["round_name"], $cupGroup);
		return array("matches" => $matches, "groupteams" => CupsDataService::getTeamsOfCupGroupInRankingOrder($this->_websoccer, $this->_db, $cupRoundId, $cupGroup));}}
class CupResultsModel extends Model {
	function getTemplateParameters() {
		$cupName = $this->_websoccer->getRequestParameter('cup');
		$cupRound = $this->_websoccer->getRequestParameter('round');
		$columns['C.logo'] = 'cup_logo';
		$columns['R.id'] = 'round_id';
		$columns['R.firstround_date'] = 'firstround_date';
		$columns['R.secondround_date'] = 'secondround_date';
		$columns['R.finalround'] = 'is_finalround';
		$columns['R.groupmatches'] = 'is_groupround';
		$columns['PREVWINNERS.name'] = 'prev_round_winners';
		$columns['PREVLOOSERS.name'] = 'prev_round_loosers';
		$fromTable = $this->_websoccer->getConfig('db_prefix') . '_cup_round AS R';
		$fromTable .= ' INNER JOIN ' . $this->_websoccer->getConfig('db_prefix') . '_cup AS C ON C.id = R.cup_id';
		$fromTable .= ' LEFT JOIN ' . $this->_websoccer->getConfig('db_prefix') . '_cup_round AS PREVWINNERS ON PREVWINNERS.id = R.from_winners_round_id';
		$fromTable .= ' LEFT JOIN ' . $this->_websoccer->getConfig('db_prefix') . '_cup_round AS PREVLOOSERS ON PREVLOOSERS.id = R.from_loosers_round_id';
		$result = $this->_db->querySelect($columns, $fromTable, 'C.name = \'%s\' AND R.name = \'%s\'', array($cupName, $cupRound), 1);
		$round = $result->fetch_array();
		$groups = array();
		$preSelectedGroup = '';
		if ($round['is_groupround']) {
			$userTeamId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
			$result = $this->_db->querySelect('name,team_id', $this->_websoccer->getConfig('db_prefix') . '_cup_round_group', 'cup_round_id = %d ORDER BY name ASC', array($round['round_id']));
			while ($group = $result->fetch_array()) {
				if (!isset($groups[$group['name']])) $groups[$group['name']] = $group['name'];
				if ($group['team_id'] == $userTeamId) $preSelectedGroup = $group['name']; }
			$matches = array(); }
		else $matches = MatchesDataService::getMatchesByCupRound($this->_websoccer, $this->_db, $cupName, $cupRound);
		return array('matches' => $matches, 'round' => $round, 'groups' => $groups, 'preSelectedGroup' => $preSelectedGroup); }}
class CupsListModel extends Model { function getTemplateParameters() { return array('cups' => MatchesDataService::getCupRoundsByCupname($this->_websoccer, $this->_db)); }}
class DirectTransferOfferModel extends Model {
	function getTemplateParameters() {
		$players = array();
		if ($this->_websoccer->getRequestParameter("loadformdata")) $players = PlayersDataService::getPlayersOfTeamByPosition($this->_websoccer, $this->_db, $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db));
		return array("players" => $players, "player" => $this->_player); }
		function renderView() {
		$playerId = (int) $this->_websoccer->getRequestParameter("id");
		if ($playerId < 1) throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		$this->_player = PlayersDataService::getPlayerById($this->_websoccer, $this->_db, $playerId);
		if (!$this->_websoccer->getConfig("transferoffers_enabled")) return FALSE;
		return (!$this->_player["player_unsellable"] && $this->_player["team_user_id"] > 0 && $this->_player["team_user_id"] !== $this->_websoccer->getUser()->id && !$this->_player["player_transfermarket"] && $this->_player["lending_owner_id"] == 0); }}
class FacebookLoginModel extends Model {
	function getTemplateParameters() { return array("loginurl" => FacebookSdk::getInstance($this->_websoccer)->getLoginUrl()); }
	function renderView() { return $this->_websoccer->getConfig("facebook_enable_login"); }}
class FinancesModel extends Model {
	function getTemplateParameters() {
		$teamId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		if ($teamId < 1) throw new Exception($this->_i18n->getMessage("feature_requires_team"));
		$team = TeamsDataService::getTeamSummaryById($this->_websoccer, $this->_db, $teamId);
		$count = BankAccountDataService::countAccountStatementsOfTeam($this->_websoccer, $this->_db, $teamId);
		$eps = $this->_websoccer->getConfig("entries_per_page");
		$paginator = new Paginator($count, $eps, $this->_websoccer);
		if ($count > 0) $statements = BankAccountDataService::getAccountStatementsOfTeam($this->_websoccer, $this->_db, $teamId, $paginator->getFirstIndex(), $eps);
		else $statements = array();
		return array("budget" => $team["team_budget"], "statements" => $statements, "paginator" => $paginator); }}
class FinancesSummaryModel extends Model {
	function getTemplateParameters() {
		$minDate = $this->_websoccer->getNowAsTimestamp() - 365 * 3600 * 24;
		$columns = array('verwendung' => 'subject', 'SUM(betrag)' => 'balance', 'AVG(betrag)' => 'avgAmount' );
		$result = $this->_db->querySelect($columns, $this->_websoccer->getConfig('db_prefix') . '_konto', 'verein_id = %d AND datum > %d GROUP BY verwendung HAVING COUNT(*) > 5', array($this->_teamId, $minDate));
		$majorPositions = array();
		while ($position = $result->fetch_array()) $majorPositions[] = $position;
		return array('majorPositions' => $majorPositions); }
	function renderView() {
		$this->_teamId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		return ($this->_teamId > 0); }}
class FindNationalPlayersModel extends Model {
	function getTemplateParameters() {
		$teamId = NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer, $this->_db);
		if (!$teamId) throw new Exception($this->_i18n->getMessage("nationalteams_user_requires_team"));
		$result = $this->_db->querySelect("name", $this->_websoccer->getConfig("db_prefix") . "_verein", "id = %d", $teamId);
		$team = $result->fetch_array();
		$firstName = $this->_websoccer->getRequestParameter("fname");
		$lastName = $this->_websoccer->getRequestParameter("lname");
		$position = $this->_websoccer->getRequestParameter("position");
		$mainPosition = $this->_websoccer->getRequestParameter("position_main");
		$playersCount = NationalteamsDataService::findPlayersCount($this->_websoccer, $this->_db, $team["name"], $teamId, $firstName, $lastName, $position, $mainPosition);
		$eps = $this->_websoccer->getConfig("entries_per_page");
		$paginator = new Paginator($playersCount, $eps, $this->_websoccer);
		$paginator->addParameter("fname", $firstName);
		$paginator->addParameter("lname", $lastName);
		$paginator->addParameter("position", $position);
		$paginator->addParameter("position_main", $mainPosition);
		$paginator->addParameter("search", "true");
		if ($playersCount > 0) $players = NationalteamsDataService::findPlayers($this->_websoccer, $this->_db, $team["name"], $teamId, $firstName, $lastName, $position, $mainPosition, $paginator->getFirstIndex(), $eps);
		else $players = array();
		return array("team_name" => $team["name"], "playersCount" => $playersCount, "players" => $players, "paginator" => $paginator); }
	function renderView() { return $this->_websoccer->getConfig("nationalteams_enabled"); }}
class ForgotPasswordModel extends Model {
	function getTemplateParameters() {
		$parameters = array();
		if ($this->_websoccer->getConfig("register_use_captcha") && strlen($this->_websoccer->getConfig("register_captcha_publickey")) && strlen($this->_websoccer->getConfig("register_captcha_privatekey"))) {
			include_once(BASE_FOLDER . "/lib/recaptcha/recaptchalib.php");
			$useSsl = (!empty($_SERVER["HTTPS"]));
			$captchaCode = recaptcha_get_html($this->_websoccer->getConfig("register_captcha_publickey"), null, $useSsl);
			$parameters["captchaCode"] = $captchaCode; }
		return $parameters; }
	function renderView() { return $this->_websoccer->getConfig("login_allow_sendingpassword"); }}
class FormationModel extends Model {
	function getTemplateParameters() {
		if ($this->_nationalteam) $clubId = NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer, $this->_db);
		else $clubId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		$nextMatches = MatchesDataService::getNextMatches($this->_websoccer, $this->_db, $clubId, $this->_websoccer->getConfig('formation_max_next_matches'));
		if (!count($nextMatches)) throw new Exception($this->_i18n->getMessage('next_match_block_no_nextmatch'));
		$matchId = $this->_websoccer->getRequestParameter('id');
		if (!$matchId) $matchinfo = $nextMatches[0];
		else {
			foreach ($nextMatches as $nextMatch) {
				if ($nextMatch['match_id'] == $matchId) {
					$matchinfo = $nextMatch;
					break; }}}
		if (!isset($matchinfo)) throw new Exception('illegal match id');
		$players = null;
		if ($clubId > 0) {
			if ($this->_nationalteam) $players = NationalteamsDataService::getNationalPlayersOfTeamByPosition($this->_websoccer, $this->_db, $clubId);
			else $players = PlayersDataService::getPlayersOfTeamByPosition($this->_websoccer, $this->_db, $clubId, 'DESC', count($matchinfo) && $matchinfo['match_type'] == 'cup', (isset($matchinfo['match_type']) && $matchinfo['match_type'] != 'friendly')); }
		if ($this->_websoccer->getRequestParameter('templateid')) $formation = FormationDataService::getFormationByTemplateId($this->_websoccer, $this->_db, $clubId, $this->_websoccer->getRequestParameter('templateid'));
		else $formation = FormationDataService::getFormationByTeamId($this->_websoccer, $this->_db, $clubId, $matchinfo['match_id']);
		for ($benchNo = 1; $benchNo <= 5; $benchNo++) {
			if ($this->_websoccer->getRequestParameter('bench' . $benchNo)) $formation['bench' . $benchNo] = $this->_websoccer->getRequestParameter('bench' . $benchNo);
			elseif (!isset($formation['bench' . $benchNo])) $formation['bench' . $benchNo] = ''; }
		for ($subNo = 1; $subNo <= 3; $subNo++) {
			if ($this->_websoccer->getRequestParameter('sub' . $subNo .'_out')) {
				$formation['sub' . $subNo .'_out'] = $this->_websoccer->getRequestParameter('sub' . $subNo .'_out');
				$formation['sub' . $subNo .'_in'] = $this->_websoccer->getRequestParameter('sub' . $subNo .'_in');
				$formation['sub' . $subNo .'_minute'] = $this->_websoccer->getRequestParameter('sub' . $subNo .'_minute');
				$formation['sub' . $subNo .'_condition'] = $this->_websoccer->getRequestParameter('sub' . $subNo .'_condition');
				$formation['sub' . $subNo .'_position'] = $this->_websoccer->getRequestParameter('sub' . $subNo .'_position'); }
			elseif (!isset($formation['sub' . $subNo .'_out'])) {
				$formation['sub' . $subNo .'_out'] = '';
				$formation['sub' . $subNo .'_in'] = '';
				$formation['sub' . $subNo .'_minute'] = '';
				$formation['sub' . $subNo .'_condition'] = '';
				$formation['sub' . $subNo .'_position'] = ''; }}
		$setup = $this->getFormationSetup($formation);
		$criteria = $this->_websoccer->getRequestParameter('preselect');
		if ($criteria !== NULL) {
			if ($criteria == 'strongest') $sortColumn = 'w_staerke';
			elseif ($criteria == 'freshest') $sortColumn = 'w_frische';
			else $sortColumn = 'w_zufriedenheit';
			$proposedPlayers = FormationDataService::getFormationProposalForTeamId($this->_websoccer, $this->_db, $clubId, $setup['defense'], $setup['dm'], $setup['midfield'], $setup['om'], $setup['striker'], $setup['outsideforward'], $sortColumn, 'DESC',
				$this->_nationalteam, (isset($matchinfo['match_type']) && $matchinfo['match_type'] == 'cup'));
			for ($playerNo = 1; $playerNo <= 11; $playerNo++) {
				$playerIndex = $playerNo - 1;
				if (isset($proposedPlayers[$playerIndex])) {
					$formation['player' . $playerNo] = $proposedPlayers[$playerIndex]['id'];
					$formation['player' . $playerNo . '_pos'] = $proposedPlayers[$playerIndex]['position']; }}
			for ($benchNo = 1; $benchNo <= 5; $benchNo++) $formation['bench' . $benchNo] = '';
			for ($subNo = 1; $subNo <= 3; $subNo++) {
				$formation['sub' . $subNo .'_out'] = '';
				$formation['sub' . $subNo .'_in'] = '';
				$formation['sub' . $subNo .'_minute'] = '';
				$formation['sub' . $subNo .'_condition'] = '';
				$formation['sub' . $subNo .'_position'] = ''; }}
		if ($this->_websoccer->getRequestParameter('freekickplayer')) $formation['freekickplayer'] = $this->_websoccer->getRequestParameter('freekickplayer');
		elseif (!isset($formation['freekickplayer'])) $formation['freekickplayer'] = '';
		if ($this->_websoccer->getRequestParameter('offensive')) $formation['offensive'] = $this->_websoccer->getRequestParameter('offensive');
		elseif (!isset($formation['offensive'])) $formation['offensive'] = 40;
		if ($this->_websoccer->getRequestParameter('longpasses')) $formation['longpasses'] = $this->_websoccer->getRequestParameter('longpasses');
		if ($this->_websoccer->getRequestParameter('counterattacks')) $formation['counterattacks'] = $this->_websoccer->getRequestParameter('counterattacks');
		for ($playerNo = 1; $playerNo <= 11; $playerNo++) {
			if ($this->_websoccer->getRequestParameter('player' . $playerNo)) {
				$formation['player' . $playerNo] = $this->_websoccer->getRequestParameter('player' . $playerNo);
				$formation['player' . $playerNo . '_pos'] = $this->_websoccer->getRequestParameter('player' . $playerNo . '_pos'); }
			elseif (!isset($formation['player' . $playerNo])) {
				$formation['player' . $playerNo] = '';
				$formation['player' . $playerNo . '_pos'] = ''; }}
		return array('nextMatches' => $nextMatches, 'next_match' => $matchinfo, 'previous_matches' => MatchesDataService::getPreviousMatches($matchinfo, $this->_websoccer, $this->_db), 'players' => $players, 'formation' => $formation, 'setup' => $setup,
			'captain_id' => TeamsDataService::getTeamCaptainIdOfTeam($this->_websoccer, $this->_db, $clubId)); }
	function getFormationSetup($formation) {
		$setup = array('defense' => 4, 'dm' => 1, 'midfield' => 3, 'om' => 1, 'striker' => 1, 'outsideforward' => 0);
		if ($this->_websoccer->getRequestParameter('formation_defense') !== NULL) {
			$setup['defense'] = (int) $this->_websoccer->getRequestParameter('formation_defense');
			$setup['dm'] = (int) $this->_websoccer->getRequestParameter('formation_defensemidfield');
			$setup['midfield'] = (int) $this->_websoccer->getRequestParameter('formation_midfield');
			$setup['om'] = (int) $this->_websoccer->getRequestParameter('formation_offensivemidfield');
			$setup['striker'] = (int) $this->_websoccer->getRequestParameter('formation_forward');
			$setup['outsideforward'] = (int) $this->_websoccer->getRequestParameter('formation_outsideforward'); }
		elseif ($this->_websoccer->getRequestParameter('setup') !== NULL) {
			$setupParts = explode('-', $this->_websoccer->getRequestParameter('setup'));
			$setup['defense'] = (int) $setupParts[0];
			$setup['dm'] = (int) $setupParts[1];
			$setup['midfield'] = (int) $setupParts[2];
			$setup['om'] = (int) $setupParts[3];
			$setup['striker'] = (int) $setupParts[4];
			$setup['outsideforward'] = (int) $setupParts[5]; }
		elseif (isset($formation['setup']) && strlen($formation['setup'])) {
			$setupParts = explode('-', $formation['setup']);
			$setup['defense'] = (int) $setupParts[0];
			$setup['dm'] = (int) $setupParts[1];
			$setup['midfield'] = (int) $setupParts[2];
			$setup['om'] = (int) $setupParts[3];
			$setup['striker'] = (int) $setupParts[4];
			if (count($setupParts) > 5) $setup['outsideforward'] = (int) $setupParts[5];
			else $setup['outsideforward'] = 0; }
		$altered = FALSE;
		while (($noOfPlayers = $setup['defense'] + $setup['dm'] + $setup['midfield'] + $setup['om'] + $setup['striker'] + $setup['outsideforward']) != 10) {
			if ($noOfPlayers > 10) {
				if ($setup['striker'] > 1) $setup['striker'] = $setup['striker'] - 1;
				elseif ($setup['outsideforward'] > 1) $setup['outsideforward'] = 0;
				elseif ($setup['om'] > 1) $setup['om'] = $setup['om'] - 1;
				elseif ($setup['dm'] > 1) $setup['dm'] = $setup['dm'] - 1;
				elseif ($setup['midfield'] > 2) $setup['midfield'] = $setup['midfield'] - 1;
				else $setup['defense'] = $setup['defense'] - 1; }
			else {
				if ($setup['defense'] < 4) $setup['defense'] = $setup['defense'] + 1;
				elseif ($setup['midfield'] < 4) $setup['midfield'] = $setup['midfield'] + 1;
				elseif ($setup['dm'] < 2) $setup['dm'] = $setup['dm'] + 1;
				elseif ($setup['om'] < 2) $setup['om'] = $setup['om'] + 1;
				else $setup['striker'] = $setup['striker'] + 1; }
			$altered = TRUE; }
		if ($altered) $this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_WARNING, $this->_i18n->getMessage('formation_setup_altered_warn_title'), $this->_i18n->getMessage('formation_setup_altered_warn_details')));
		return $setup; }
	function renderView() {
		$this->_nationalteam = ($websoccer->getRequestParameter('nationalteam')) ? TRUE : FALSE;
		return TRUE; }}
class FormationTemplatesModel extends Model {
	function getTemplateParameters() {
		$templates = array();
		$result = $this->_db->querySelect('id, datum AS date, templatename', $this->_websoccer->getConfig('db_prefix') . '_aufstellung', 'verein_id = %d AND templatename IS NOT NULL ORDER BY datum DESC', $this->_websoccer->getUser()->getClubId($this->_websoccer,
			$this->_db));
		while ($template = $result->fetch_array()) $templates[] = $template;
		return array('templates' => $templates); }}
class FreeClubsModel extends Model {
	function getTemplateParameters() { return array("countries" => TeamsDataService::getTeamsWithoutUser($this->_websoccer, $this->_db)); }}
class GoogleplusLoginModel extends Model {
	function getTemplateParameters() { return array("loginurl" => GoogleplusSdk::getInstance($this->_websoccer)->getLoginUrl()); }
	function renderView() { return $this->_websoccer->getConfig("googleplus_enable_login"); }}
class HallOfFameModel extends Model {
	function getTemplateParameters() {
		$leagues = array();
		$cups = array();
		$columns = array('L.name' => 'league_name', 'L.land' => 'league_country', 'S.name' => 'season_name', 'C.id' => 'team_id', 'C.name' => 'team_name', 'C.bild' => 'team_picture' );
		$fromTable = $this->_websoccer->getConfig('db_prefix') . '_saison AS S';
		$fromTable .= ' INNER JOIN ' . $this->_websoccer->getConfig('db_prefix') . '_liga AS L ON L.id = S.liga_id';
		$fromTable .= ' INNER JOIN ' . $this->_websoccer->getConfig('db_prefix') . '_verein AS C ON C.id = S.platz_1_id';
		$whereCondition = 'S.beendet = \'1\' ORDER BY L.land ASC, L.name ASC, S.id DESC';
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition);
		while ($season = $result->fetch_array()) $leagues[$season['league_name']][] = $season;
		$columns = array('CUP.name' => 'cup_name', 'C.id' => 'team_id', 'C.name' => 'team_name', 'C.bild' => 'team_picture' );
		$fromTable = $this->_websoccer->getConfig('db_prefix') . '_cup AS CUP';
		$fromTable .= ' INNER JOIN ' . $this->_websoccer->getConfig('db_prefix') . '_verein AS C ON C.id = CUP.winner_id';
		$whereCondition = 'CUP.winner_id IS NOT NULL ORDER BY CUP.id DESC';
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition);
		while ($cup = $result->fetch_array()) $cups[] = $cup;
		return array('leagues' => $leagues, 'cups' => $cups); }}
class HighscoreModel extends Model {
	function getTemplateParameters() {
		$count = UsersDataService::countActiveUsersWithHighscore($this->_websoccer, $this->_db);
		$eps = $this->_websoccer->getConfig("entries_per_page");
		$paginator = new Paginator($count, $eps, $this->_websoccer);
		if ($count > 0) $users = UsersDataService::getActiveUsersWithHighscore($this->_websoccer, $this->_db, $paginator->getFirstIndex(), $eps);
		else $users = array();
		return array("users" => $users, "paginator" => $paginator); }}
class ImprintModel extends Model {
	function getTemplateParameters() {
		$filecontent = "";
		if (file_exists(IMPRINT_FILE)) $filecontent = file_get_contents(IMPRINT_FILE);
		return array("imprint_content" => $filecontent); }}
class LanguageSwitcherModel extends Model {
	function renderView() { return (count($this->_i18n->getSupportedLanguages()) > 1); }}
class LastMatchModel extends Model {
	function getTemplateParameters() {
		$matchinfo = MatchesDataService::getLastMatch($this->_websoccer, $this->_db);
		return array("last_match" => $matchinfo); }}
class LastTransfersModel extends Model {
	function getTemplateParameters() {
		$teamId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		$transfers = TransfermarketDataService::getLastCompletedTransfers($this->_websoccer, $this->_db, $teamId);
		return array("completedtransfers" => $transfers); }}
class LatestResultsBlockModel extends Model {
	function getTemplateParameters() {
		$matches = MatchesDataService::getLatestMatches($this->_websoccer, $this->_db, 5, TRUE);
		return array('matches' => $matches); }}
class LatestResultsModel extends Model {
	function getTemplateParameters() {
		$matches = MatchesDataService::getLatestMatches($this->_websoccer, $this->_db);
		return array("matches" => $matches); }}
class LeagueDetailsModel extends Model{
	function getTemplateParameters() {
		$league = null;
		$leagueId = (int) $this->_websoccer->getRequestParameter("id");
		if ($leagueId == 0) {
			$clubId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
			if ($clubId > 0) {
				$result = $this->_db->querySelect("liga_id", $this->_websoccer->getConfig("db_prefix") . "_verein", "id = %d", $clubId, 1);
				$club = $result->fetch_array();
				$leagueId = $club["liga_id"]; }}
		if ($leagueId > 0) {
			$league = LeagueDataService::getLeagueById($this->_websoccer, $this->_db, $leagueId);
			if (!isset($league["league_id"])) throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND)); }
		return array("league" => $league, "leagues" => LeagueDataService::getLeaguesSortedByCountry($this->_websoccer, $this->_db)); }}
class LeagueSelectionModel extends Model {
	function getTemplateParameters() {
		$fromTable = $this->_websoccer->getConfig("db_prefix") ."_liga";
		$whereCondition = "land = '%s' ORDER BY name ASC";
		$leagues = array();
		$result = $this->_db->querySelect("id, name", $fromTable, $whereCondition, $this->_country);
		while ($league = $result->fetch_array()) $leagues[] = $league;
		return array("leagues" => $leagues); }
	function renderView() {
		$this->_country = $this->_websoccer->getRequestParameter("country");
		return (strlen($this->_country)); }}
class LeaguesListModel extends Model {
	function getTemplateParameters() {
		$fromTable = $this->_websoccer->getConfig("db_prefix") ."_liga";
		$whereCondition = "1=1 ORDER BY land ASC, name ASC";
		$leagues = array();
		$result = $this->_db->querySelect("id, land AS country, name", $fromTable, $whereCondition, array());
		while ($league = $result->fetch_array()) $leagues[$league["country"]][] = $league;
		return array("countries" => $leagues); }}
class LeaguesOverviewModel extends Model {
	function getTemplateParameters() {
		$fromTable = $this->_websoccer->getConfig("db_prefix") ."_liga";
		$whereCondition = "1=1 GROUP BY land ORDER BY land ASC";
		$columns["land"] = "name";
		$columns["COUNT(*)"] = "noOfLeagues";
		$countries = array();
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition);
		while ($country = $result->fetch_array()) $countries[] = $country;
		return array("countries" => $countries); }}
class LeagueTableModel extends Model {
	function getTemplateParameters() {
		$markers = array();
		$teams = array();
		if ($this->_seasonId == null && $this->_type == null) {
			$teams = TeamsDataService::getTeamsOfLeagueOrderedByTableCriteria($this->_websoccer, $this->_db, $this->_leagueId);
			$fromTable = $this->_websoccer->getConfig("db_prefix") ."_tabelle_markierung";
			$columns["bezeichnung"] = "name";
			$columns["farbe"] = "color";
			$columns["platz_von"] = "place_from";
			$columns["platz_bis"] = "place_to";
			$whereCondition = "liga_id = %d ORDER BY place_from ASC";
			$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $this->_leagueId);
			while ($marker = $result->fetch_array()) $markers[] = $marker; }
		else {
			$seasonId = 0;
			if ($this->_seasonId == null) {
				$result = $this->_db->querySelect("id", $this->_websoccer->getConfig("db_prefix") ."_saison", "liga_id = %d AND beendet = '0' ORDER BY name DESC", $this->_leagueId, 1);
				$season = $result->fetch_array();
				if ($season["id"]) $seasonId = $season["id"]; }
			else $seasonId = $this->_seasonId;
			if ($seasonId) $teams = TeamsDataService::getTeamsOfSeasonOrderedByTableCriteria($this->_websoccer, $this->_db, $seasonId, $this->_type); }
		$seasons = array();
		$result = $this->_db->querySelect("id,name", $this->_websoccer->getConfig("db_prefix") ."_saison", "liga_id = %d AND beendet = '1' ORDER BY name DESC", $this->_leagueId);
		while ($season = $result->fetch_array()) $seasons[] = $season;
		return array("leagueId" => $this->_leagueId, "teams" => $teams, "markers" => $markers, "seasons" => $seasons); }
	function renderView() {
		$this->_leagueId = (int) $this->_websoccer->getRequestParameter("id");
		$this->_seasonId = $this->_websoccer->getRequestParameter("seasonid");
		$this->_type = $this->_websoccer->getRequestParameter("type");
		$clubId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		if ($this->_leagueId == 0 && $clubId > 0) {
			$result = $db->querySelect("liga_id", $this->_websoccer->getConfig("db_prefix") . "_verein", "id = %d", $clubId, 1);
			$club = $result->fetch_array();
			$this->_leagueId = $club["liga_id"]; }
		return ($this->_leagueId  > 0); }}
class LentPlayersModel extends Model {
	function getTemplateParameters() {
		$teamId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		$columns = array("P.id" => "id", "vorname" => "firstname", "nachname" => "lastname", "kunstname" => "pseudonym", "position" => "position", "position_main" => "position_main", "position_second" => "position_second", "nation" => "player_nationality",
			"lending_matches" => "lending_matches", "lending_fee" => "lending_fee", "C.id" => "team_id", "C.name" => "team_name" );
		if ($this->_websoccer->getConfig('players_aging') == 'birthday') $ageColumn = 'TIMESTAMPDIFF(YEAR,geburtstag,CURDATE())';
		else $ageColumn = 'age';
		$columns[$ageColumn] = 'age';
		$dbPrefix = $this->_websoccer->getConfig("db_prefix");
		$fromTable = $dbPrefix . "_spieler P INNER JOIN " . $dbPrefix . "_verein C ON C.id = P.verein_id";
		$whereCondition = "P.status = 1 AND lending_owner_id = %d";
		$whereCondition .= " ORDER BY lending_matches ASC, position ASC, position_main ASC, nachname ASC, vorname ASC";
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $teamId, 50);
		$players = array();
		while ($player = $result->fetch_array()) {
			$player["position"] = PlayersDataService::_convertPosition($player["position"]);
			$players[] = $player; }
		return array('lentplayers' => $players); }}
class LiveMatchBlockModel extends Model {
	function getTemplateParameters() { return array("match" => $this->_match); }
	function renderView() {
		$this->_match = MatchesDataService::getLiveMatch($this->_websoccer, $this->_db);;
		return (count($this->_match)); }}
class MatchChangesModel extends FormationModel {
	function getTemplateParameters() {
		$matchId = (int) $this->_websoccer->getRequestParameter('id');
		if ($matchId < 1) throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		$match = MatchesDataService::getMatchSubstitutionsById($this->_websoccer, $this->_db, $matchId);
		if ($match['match_simulated']) throw new Exception($this->_i18n->getMessage('match_details_match_completed'));
		$teamId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		if ($match['match_home_id'] !== $teamId && $match['match_guest_id'] !== $teamId) $teamId = NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer, $this->_db);
		if ($teamId !== $match['match_home_id'] && $match['match_guest_id'] !== $teamId) throw new Exception('illegal match');
		$teamPrefix = ($teamId == $match['match_home_id']) ? 'home' : 'guest';
		$players = MatchesDataService::getMatchPlayerRecordsByField($this->_websoccer, $this->_db, $matchId, $teamId);
		$playersOnField = $players['field'];
		$playersOnBench = (isset($players['bench'])) ? $players['bench'] : array();
		$formation = array();
		if ($this->_websoccer->getRequestParameter('freekickplayer')) $formation['freekickplayer'] = $this->_websoccer->getRequestParameter('freekickplayer');
		else $formation['freekickplayer'] = $match['match_' . $teamPrefix . '_freekickplayer'];
		if ($this->_websoccer->getRequestParameter('offensive')) $formation['offensive'] = $this->_websoccer->getRequestParameter('offensive');
		else $formation['offensive'] = $match['match_' . $teamPrefix . '_offensive'];
		if ($this->_websoccer->getRequestParameter('longpasses')) $formation['longpasses'] = $this->_websoccer->getRequestParameter('longpasses');
		else $formation['longpasses'] = $match['match_' . $teamPrefix . '_longpasses'];
		if ($this->_websoccer->getRequestParameter('counterattacks')) $formation['counterattacks'] = $this->_websoccer->getRequestParameter('counterattacks');
		else $formation['counterattacks'] = $match['match_' . $teamPrefix . '_counterattacks'];
		$playerNo = 0;
		foreach ($playersOnField as $player) {
			$playerNo++;
			$formation['player' . $playerNo] = $player['id'];
			$formation['player' . $playerNo . '_pos'] = $player['match_position_main']; }
		$setup = array('defense' => 6, 'dm' => 3, 'midfield' => 4, 'om' => 3, 'striker' => 2, 'outsideforward' => 2);
		$setupMainMapping = array('LV' => 'defense', 'RV' => 'defense', 'IV' => 'defense', 'DM' => 'dm', 'LM' => 'midfield', 'ZM' => 'midfield', 'RM' => 'midfield', 'OM' => 'om', 'LS' => 'outsideforward', 'MS' => 'striker', 'RS' => 'outsideforward');
		$setupPosMapping = array('Abwehr' => 'defense', 'Mittelfeld' => 'midfield', 'Sturm' => 'striker');
		for ($playerNo = 1; $playerNo <= 11; $playerNo++) {
			if ($this->_websoccer->getRequestParameter('player' . $playerNo) > 0) {
				$formation['player' . $playerNo] = $this->_websoccer->getRequestParameter('player' . $playerNo);
				$formation['player' . $playerNo . '_pos'] = $this->_websoccer->getRequestParameter('player' . $playerNo . '_pos'); }}
		$benchNo = 0;
		foreach ($playersOnBench as $player) {
			$benchNo++;
			$formation['bench' . $benchNo] = $player['id']; }
		for ($benchNo = 1; $benchNo <= 5; $benchNo++) {
			if ($this->_websoccer->getRequestParameter('bench' . $benchNo)) $formation['bench' . $benchNo] = $this->_websoccer->getRequestParameter('bench' . $benchNo);
			elseif (!isset($formation['bench' . $benchNo])) $formation['bench' . $benchNo] = ''; }
		for ($subNo = 1; $subNo <= 3; $subNo++) {
			if ($this->_websoccer->getRequestParameter('sub' . $subNo .'_out')) {
				$formation['sub' . $subNo .'_out'] = $this->_websoccer->getRequestParameter('sub' . $subNo .'_out');
				$formation['sub' . $subNo .'_in'] = $this->_websoccer->getRequestParameter('sub' . $subNo .'_in');
				$formation['sub' . $subNo .'_minute'] = $this->_websoccer->getRequestParameter('sub' . $subNo .'_minute');
				$formation['sub' . $subNo .'_condition'] = $this->_websoccer->getRequestParameter('sub' . $subNo .'_condition');
				$formation['sub' . $subNo .'_position'] = $this->_websoccer->getRequestParameter('sub' . $subNo .'_position'); }
			elseif (isset($match[$teamPrefix . '_sub' . $subNo .'_out'])) {
				$formation['sub' . $subNo .'_out'] = $match[$teamPrefix . '_sub' . $subNo .'_out'];
				$formation['sub' . $subNo .'_in'] = $match[$teamPrefix . '_sub' . $subNo .'_in'];
				$formation['sub' . $subNo .'_minute'] = $match[$teamPrefix . '_sub' . $subNo .'_minute'];
				$formation['sub' . $subNo .'_condition'] = $match[$teamPrefix . '_sub' . $subNo .'_condition'];
				$formation['sub' . $subNo .'_position'] = $match[$teamPrefix . '_sub' . $subNo .'_position']; }
			else {
				$formation['sub' . $subNo .'_out'] = '';
				$formation['sub' . $subNo .'_in'] = '';
				$formation['sub' . $subNo .'_minute'] = '';
				$formation['sub' . $subNo .'_condition'] = '';
				$formation['sub' . $subNo .'_position'] = ''; }}
		return array('setup' => $setup, 'players' => $players, 'formation' => $formation, 'minute' => $match['match_minutes']); }}
class MatchDayResultsModel extends Model {
	function getTemplateParameters() {
		$matches = MatchesDataService::getMatchesByMatchday($this->_websoccer, $this->_db, $this->_seasonId, $this->_matchday);
		return array("matches" => $matches); }
	function renderView() {
		$this->_seasonId = (int) $this->_websoccer->getRequestParameter("seasonid");
		$this->_matchday = (int) $this->_websoccer->getRequestParameter("matchday");
		return ($this->_seasonId > 0 && $this->_matchday > 0); }}
class MatchDetailsModel extends Model {
	function getTemplateParameters() {
		$matchId = (int) $this->_websoccer->getRequestParameter('id');
		if ($matchId < 1) throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		$match = MatchesDataService::getMatchById($this->_websoccer, $this->_db, $matchId);
		if (!isset($match['match_id'])) throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		$allowTacticChanges = FALSE;
		$reportmessages = array();
		if ($match['match_minutes'] > 0) {
			$reportmessages = MatchesDataService::getMatchReportMessages($this->_websoccer, $this->_db, $this->_i18n, $matchId);
			$userTeamId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
			$userNationalTeamId = NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer, $this->_db);
			if (!$match['match_simulated'] && $this->_websoccer->getConfig('sim_allow_livechanges') && ($match['match_home_id'] == $userTeamId || $match['match_guest_id'] == $userTeamId || $match['match_home_id'] == $userNationalTeamId || $match['match_guest_id']
				== $userNationalTeamId)) $allowTacticChanges = TRUE;}
		$homeStrikerMessages = array();
		$guestStrikerMessages = array();
		foreach ($reportmessages as $reportMessage) {
			$type = $reportMessage['type'];
			if ($type == 'Tor' || $type == 'Tor_mit_vorlage' || $type == 'Elfmeter_erfolg' || $type == 'Freistoss_treffer') {
				if ($reportMessage['active_home']) array_unshift($homeStrikerMessages, $reportMessage);
				else array_unshift($guestStrikerMessages, $reportMessage); }}
		return array('match' => $match, 'reportmessages' => $reportmessages, 'allowTacticChanges' => $allowTacticChanges, 'homeStrikerMessages' => $homeStrikerMessages, 'guestStrikerMessages' => $guestStrikerMessages); }}
class MatchPlayersModel extends Model {
	function getTemplateParameters() {
		$matchId = (int) $this->_websoccer->getRequestParameter("id");
		if ($matchId < 1) throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		$match = MatchesDataService::getMatchById($this->_websoccer, $this->_db, $matchId, FALSE, TRUE);
		$home_players = MatchesDataService::getMatchReportPlayerRecords($this->_websoccer, $this->_db, $matchId, $match["match_home_id"]);
		$guest_players = MatchesDataService::getMatchReportPlayerRecords($this->_websoccer, $this->_db, $matchId, $match["match_guest_id"]);
		if ($match["match_simulated"]) {
			$result = $this->_db->querySelect("player_id", $this->_websoccer->getConfig("db_prefix") . "_teamoftheday", "season_id = %d AND matchday = %d", array($match["match_season_id"], $match["match_matchday"]));
			$topPlayerIds = array();
			while ($topmember = $result->fetch_array()) $topPlayerIds[] = $topmember["player_id"];
			if (count($topPlayerIds)) {
				for($playerIndex = 0; $playerIndex < count($home_players); $playerIndex++) {
					if (in_array($home_players[$playerIndex]["id"], $topPlayerIds)) $home_players[$playerIndex]["is_best_player_of_day"] = TRUE; }
				for($playerIndex = 0; $playerIndex < count($guest_players); $playerIndex++) {
					if (in_array($guest_players[$playerIndex]["id"], $topPlayerIds)) $guest_players[$playerIndex]["is_best_player_of_day"] = TRUE; }}}
		return array("match" => $match, "home_players" => $home_players, "guest_players" => $guest_players); }}
class MatchPreviewModel extends Model {
	function getTemplateParameters() {
		$latestMatchesHome = $this->_getLatestMatchesByTeam($this->_match['match_home_id']);
		$latestMatchesGuest = $this->_getLatestMatchesByTeam($this->_match['match_guest_id']);
		return array('match' => $this->_match, 'latestMatchesHome' => $latestMatchesHome, 'latestMatchesGuest' => $latestMatchesGuest, 'homeUser' => $this->_getUserInfoByTeam($this->_match['match_home_id']),
			'guestUser' => $this->_getUserInfoByTeam($this->_match['match_guest_id'])); }
	function renderView() {
		$matchId = (int) $this->_websoccer->getRequestParameter('id');
		$this->_match = MatchesDataService::getMatchById($this->_websoccer, $this->_db, $matchId);
		return ($this->_match['match_simulated'] != '1' && !$this->_match['match_minutes']); }
	function _getLatestMatchesByTeam($teamId) {
		$whereCondition = "M.berechnet = 1 AND (HOME.id = %d OR GUEST.id = %d)";
		$parameters = array($teamId, $teamId);
		if ($this->_match['match_season_id']) {
			$whereCondition .= ' AND M.saison_id = %d';
			$parameters[] = $this->_match['match_season_id']; }
		elseif (strlen($this->_match['match_cup_name'])) {
			$whereCondition .= ' AND M.pokalname = \'%s\'';
			$parameters[] = $this->_match['match_cup_name']; }
		else $whereCondition .= ' AND M.spieltyp = \'Freundschaft\'';
		$whereCondition .= " ORDER BY M.datum DESC";
		return MatchesDataService::getMatchesByCondition($this->_websoccer, $this->_db, $whereCondition, $parameters, 5); }
	function _getUserInfoByTeam($teamId) {
		$columns = 'U.id AS user_id, nick, email, picture';
		$fromTable = $this->_websoccer->getConfig('db_prefix') . '_user AS U INNER JOIN ' . $this->_websoccer->getConfig('db_prefix') . '_verein AS C ON C.user_id = U.id';
		$result = $this->_db->querySelect($columns, $fromTable, 'C.id = %d', $teamId);
		$user = $result->fetch_array();
		if ($user) $user['picture'] = UsersDataService::getUserProfilePicture($this->_websoccer, $user['picture'], $user['email'], 120);
		return $user; }}
class MatchStatisticsModel extends Model {
	function getTemplateParameters() {
		$matchId = (int) $this->_websoccer->getRequestParameter("id");
		if ($matchId < 1) throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		$match = MatchesDataService::getMatchById($this->_websoccer, $this->_db, $matchId);
		$columns["SUM(shoots)"] = "shoots";
		$columns["SUM(ballcontacts)"] = "ballcontacts";
		$columns["SUM(wontackles)"] = "wontackles";
		$columns["SUM(passes_successed)"] = "passes_successed";
		$columns["SUM(passes_failed)"] = "passes_failed";
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_spiel_berechnung";
		$whereCondition = "spiel_id = %d AND team_id = %d";
		$parameters = array($matchId, $match["match_home_id"]);
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $parameters);
		$homeStatistics = $result->fetch_array();
		$parameters = array($matchId, $match["match_guest_id"]);
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $parameters);
		$guestStatistics = $result->fetch_array();
		return array("match" => $match, "homeStatistics" => $homeStatistics, "guestStatistics" => $guestStatistics); }}
class MessageDetailsModel extends Model {
	function getTemplateParameters() {
		$id = $this->_websoccer->getRequestParameter("id");
		$message = MessagesDataService::getMessageById($this->_websoccer, $this->_db, $id);
		if ($message && !$message["seen"]) {
			$columns["gelesen"] = "1";
			$fromTable = $this->_websoccer->getConfig("db_prefix") . "_briefe";
			$whereCondition = "id = %d";
			$this->_db->queryUpdate($columns, $fromTable, $whereCondition, $id); }
		return array("message" => $message); }}
class MessagesInboxModel extends Model {
	function getTemplateParameters() {
		$count = MessagesDataService::countInboxMessages($this->_websoccer, $this->_db);
		$eps = $this->_websoccer->getConfig("entries_per_page");
		$paginator = new Paginator($count, $eps, $this->_websoccer);
		if ($count > 0) $messages = MessagesDataService::getInboxMessages($this->_websoccer, $this->_db, $paginator->getFirstIndex(), $eps);
		else $messages = array();
		return array("messages" => $messages, "paginator" => $paginator); }}
class MessagesOutboxModel extends Model {
	function getTemplateParameters() {
		$count = MessagesDataService::countOutboxMessages($this->_websoccer, $this->_db);
		$eps = $this->_websoccer->getConfig("entries_per_page");
		$paginator = new Paginator($count, $eps, $this->_websoccer);
		$paginator->addParameter("block", "messages-outbox");
		if ($count > 0) $messages = MessagesDataService::getOutboxMessages($this->_websoccer, $this->_db, $paginator->getFirstIndex(), $eps);
		else $messages = array();
		return array("messages" => $messages, "paginator" => $paginator); }}
class MyScheduleModel extends Model {
	function getTemplateParameters() {
		$matches = array();
		$paginator = null;
		$clubId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		$whereCondition = '(home_verein = %d OR gast_verein = %d) AND berechnet != \'1\'';
		$parameters = array($clubId, $clubId);
		$result = $this->_db->querySelect('COUNT(*) AS hits', $this->_websoccer->getConfig('db_prefix') . '_spiel', $whereCondition, $parameters);
		$matchesCnt = $result->fetch_array();
		if ($matchesCnt) $count = $matchesCnt['hits'];
		else $count = 0;
		if ($count) {
			$whereCondition .= ' ORDER BY M.datum ASC';
			$eps = $this->_websoccer->getConfig("entries_per_page");
			$paginator = new Paginator($count, $eps, $this->_websoccer);
			$matches = MatchesDataService::getMatchesByCondition($this->_websoccer, $this->_db, $whereCondition, $parameters, $paginator->getFirstIndex() . ',' . $eps); }
		return array("matches" => $matches, "paginator" => $paginator); }}
class MyTeamModel extends Model {
	function getTemplateParameters() {
		$teamId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		$captain_id = TeamsDataService::getTeamCaptainIdOfTeam($this->_websoccer, $this->_db, $teamId);
		$players = array();
		if ($teamId > 0) $players = PlayersDataService::getPlayersOfTeamById($this->_websoccer, $this->_db, $teamId);
		return array("players" => $players, "captain_id" => $captain_id); }}
class MyTransferBidsModel extends Model {
	function getTemplateParameters() {
		$teamId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		$bids = TransfermarketDataService::getCurrentBidsOfTeam($this->_websoccer, $this->_db, $teamId);
		return array("bids" => $bids); }}
class MyTransfersModel extends Model {
	function getTemplateParameters() {
		$teamId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		$transfers = TransfermarketDataService::getCompletedTransfersOfTeam($this->_websoccer, $this->_db, $teamId);
		return array("completedtransfers" => $transfers); }}
class MyYouthTeamModel extends Model {
	function getTemplateParameters() {
		$teamId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		$players = array();
		if ($teamId > 0) {
			$players = YouthPlayersDataService::getYouthPlayersOfTeam($this->_websoccer, $this->_db, $teamId);
			$noOfPlayers = count($players);
			for ($playerIndex = 0; $playerIndex < $noOfPlayers; $playerIndex++) $players[$playerIndex]["nation_flagfile"] = PlayersDataService::getFlagFilename($players[$playerIndex]["nation"]); }
		return array("players" => $players); }
	function renderView() { return $this->_websoccer->getConfig("youth_enabled"); }}
class NationalMatchResultsModel extends Model {
	function getTemplateParameters() {
		$teamId = NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer, $this->_db);
		if (!$teamId) throw new Exception($this->_i18n->getMessage("nationalteams_user_requires_team"));
		$matchesCount = NationalteamsDataService::countSimulatedMatches($this->_websoccer, $this->_db, $teamId);
		$eps = 5;
		$paginator = new Paginator($matchesCount, $eps, $this->_websoccer);
		$paginator->addParameter("block", "national-match-results");
		$matches = array();
		if ($matchesCount) $matches = NationalteamsDataService::getSimulatedMatches($this->_websoccer, $this->_db, $teamId, $paginator->getFirstIndex(), $eps);
		return array("paginator" => $paginator, "matches" => $matches); }
	function renderView() { return $this->_websoccer->getConfig("nationalteams_enabled"); }}
class NationalNextMatchesModel extends Model {
	function getTemplateParameters() {
		$teamId = NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer, $this->_db);
		if (!$teamId) throw new Exception($this->_i18n->getMessage("nationalteams_user_requires_team"));
		$matchesCount = NationalteamsDataService::countNextMatches($this->_websoccer, $this->_db, $teamId);
		$eps = 5;
		$paginator = new Paginator($matchesCount, $eps, $this->_websoccer);
		$paginator->addParameter("block", "national-next-matches");
		$matches = array();
		if ($matchesCount) $matches = NationalteamsDataService::getNextMatches($this->_websoccer, $this->_db, $teamId, $paginator->getFirstIndex(), $eps);
		return array("paginator" => $paginator, "matches" => $matches); }
	function renderView() { return $this->_websoccer->getConfig("nationalteams_enabled"); }}
class NationalNextMatchModel extends Model {
	function getTemplateParameters() {
		$matches = NationalteamsDataService::getNextMatches($this->_websoccer, $this->_db, $this->_teamId, 0, 1);
		return array('match' => $matches[0]); }
	function renderView() {
		if (!$this->_websoccer->getConfig('nationalteams_enabled')) return FALSE;
		$this->_teamId = NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer, $this->_db);
		if (!$this->_teamId) return FALSE;
		$matchesCount = NationalteamsDataService::countNextMatches($this->_websoccer, $this->_db, $this->_teamId);
		if (!$matchesCount) return FALSE;
		return TRUE; }}
class NationalPlayersModel extends Model {
	function getTemplateParameters() {
		$teamId = NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer, $this->_db);
		if (!$teamId) throw new Exception($this->_i18n->getMessage("nationalteams_user_requires_team"));
		$result = $this->_db->querySelect("name", $this->_websoccer->getConfig("db_prefix") . "_verein", "id = %d", $teamId);
		$team = $result->fetch_array();
		return array("team_name" => $team["name"], "players" => NationalteamsDataService::getNationalPlayersOfTeamByPosition($this->_websoccer, $this->_db, $teamId)); }
	function renderView() { return $this->_websoccer->getConfig("nationalteams_enabled"); }}
class NationalTeamMatchesModel extends Model {
	function getTemplateParameters() {
		$teamId = NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer, $this->_db);
		if (!$teamId) throw new Exception($this->_i18n->getMessage("nationalteams_user_requires_team"));
		return array(); }
	function renderView() { return $this->_websoccer->getConfig("nationalteams_enabled"); }}
class NewsDetailsModel extends Model {
	function getTemplateParameters() {
		$tablePrefix = $this->_websoccer->getConfig("db_prefix") . "_";
		$fromTable = $tablePrefix . "news AS NewsTab";
		$fromTable .= " LEFT JOIN " . $tablePrefix . "admin AS AdminTab ON NewsTab.autor_id = AdminTab.id";
		$whereCondition = "NewsTab.id = %d AND status = 1";
		$parameters = (int) $this->_websoccer->getRequestParameter("id");
		$result = $this->_db->querySelect("NewsTab.*, AdminTab.name AS author_name", $fromTable, $whereCondition, $parameters);
		$item = $result->fetch_array();
		if (!$item) throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		$message = $item["nachricht"];
		if ($item["c_br"]) $message = nl2br($message);
		if ($item["c_links"]) $message = $this->_strToLink($message);
		$relatedLinks = array();
		if ($item["linktext1"] && $item["linkurl1"]) $relatedLinks[$item["linkurl1"]] = $item["linktext1"];
		if ($item["linktext2"] && $item["linkurl2"]) $relatedLinks[$item["linkurl2"]] = $item["linktext2"];
		if ($item["linktext3"] && $item["linkurl3"]) $relatedLinks[$item["linkurl3"]] = $item["linktext3"];
		$article = array("id" => $item["id"], "title" => $item["titel"], "date" => $this->_websoccer->getFormattedDate($item["datum"]), "message" => $message, "author_name" => $item["author_name"]);
		return array("article" => $article, "relatedLinks" => $relatedLinks); }
	function _strToLink($str) {
	  $str = preg_replace("#([\t\r\n ])([a-z0-9]+?){1}://([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)#i", '\1<a href="\2://\3" target="_blank">\2://\3</a>', $str);
	  $str = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $str);
	  return $str; }}
class NewsListModel extends Model {
	function getTemplateParameters() {
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_news";
		$whereCondition = "status = %d";
		$parameters = "1";
		$result = $this->_db->querySelect("COUNT(*) AS hits", $fromTable, $whereCondition, $parameters);
		$rows = $result->fetch_array();
		$eps = NEWS_ENTRIES_PER_PAGE;
		$paginator = new Paginator($rows["hits"], $eps, $this->_websoccer);
		$columns = "id, titel, datum, nachricht";
		$whereCondition .= " ORDER BY datum DESC";
		$limit = $paginator->getFirstIndex() .",". $eps;
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $parameters, $limit);
		$articles = array();
		while ($article = $result->fetch_array()) $articles[] = array("id" => $article["id"], "title" => $article["titel"], "date" => $this->_websoccer->getFormattedDate($article["datum"]), "teaser" => $this->_shortenMessage($article["nachricht"]));
		return array("articles" => $articles, "paginator" => $paginator); }
	function _shortenMessage($message) {
		if (strlen($message) > NEWS_TEASER_MAXLENGTH) {
			$message = wordwrap($message, NEWS_TEASER_MAXLENGTH);
			$message = substr($message, 0, strpos($message, "\n")) . "..."; }
		return $message; }}
class NextMatchModel extends Model {
	function getTemplateParameters() {
		if ($this->_websoccer->getRequestParameter("nationalteam")) $clubId = NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer, $this->_db);
		else $clubId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		$matchinfo = MatchesDataService::getNextMatch($this->_websoccer, $this->_db, $clubId);
		if (count($matchinfo)) $matchinfo["previous_matches"] = MatchesDataService::getPreviousMatches($matchinfo, $this->_websoccer, $this->_db);
		return $matchinfo; }}
class NotificationsModel extends Model {
	function getTemplateParameters() {
		$user = $this->_websoccer->getUser();
		$notifications = NotificationsDataService::getLatestNotifications($this->_websoccer, $this->_db, $this->_i18n, $user->id, $user->getClubId($this->_websoccer, $this->_db), $this->_websoccer->getConfig("notifications_max"));
		$this->_db->queryUpdate(array("seen" => "1"), $this->_websoccer->getConfig("db_prefix") . "_notification", "user_id = %d", $this->_websoccer->getUser()->id);
		return array("notifications" => $notifications); }}
class OfficeModel extends Model{
	function getTemplateParameters() {
		$user = $this->_websoccer->getUser();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		if ($clubId) RandomEventsDataService::createEventIfRequired($this->_websoccer, $this->_db, $user->id);
		return array(); }}
class PaypalLinkModel extends Model {
	function getTemplateParameters() {
		$userId = $this->_websoccer->getUser()->id;
		$linkCode = $this->_websoccer->getConfig("paypal_buttonhtml");
		$customField = "<input type=\"hidden\" name=\"custom\" value=\"". $userId . "\">";
		$notifyUrlField = "<input type=\"hidden\" name=\"notify_url\" value=\"". $this->_websoccer->getInternalActionUrl("paypal-notify", null, null, TRUE) . "\">";
		$linkCode = str_replace("</form>", $notifyUrlField . $customField . "</form>", $linkCode);
		return array("linkCode" => $linkCode); }
	function renderView() { return $this->_websoccer->getConfig("paypal_enabled"); }}
class PlayerDetailsModel extends Model {
	function getTemplateParameters() {
		$playerId = (int) $this->_websoccer->getRequestParameter("id");
		if ($playerId < 1) throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		$player = PlayersDataService::getPlayerById($this->_websoccer, $this->_db, $playerId);
		if (!isset($player["player_id"])) throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		return array("player" => $player); }}
class PlayerDetailsWithDependenciesModel extends Model {
	function getTemplateParameters() {
		$playerId = (int) $this->_websoccer->getRequestParameter("id");
		if ($playerId < 1) throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		$player = PlayersDataService::getPlayerById($this->_websoccer, $this->_db, $playerId);
		if (!isset($player["player_id"])) throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		$grades = $this->_getGrades($playerId);
		$transfers = TransfermarketDataService::getCompletedTransfersOfPlayer($this->_websoccer, $this->_db, $playerId);
		return array("player" => $player, "grades" => $grades, "completedtransfers" => $transfers); }
	function _getGrades($playerId) {
		$grades = array();
		$fromTable = $this->_websoccer->getConfig("db_prefix") ."_spiel_berechnung";
		$columns = "note AS grade";
		$whereCondition = "spieler_id = %d AND minuten_gespielt > 0 ORDER BY id DESC";
		$parameters = $playerId;
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $parameters, 10);
		while ($grade = $result->fetch_array()) $grades[] = $grade["grade"];
		$grades = array_reverse($grades);
		return $grades; }}
class PlayersSearchModel extends Model {
	function getTemplateParameters() {
		$playersCount = PlayersDataService::findPlayersCount($this->_websoccer, $this->_db, $this->_firstName, $this->_lastName, $this->_club, $this->_position, $this->_strength, $this->_lendableOnly);
		$eps = $this->_websoccer->getConfig("entries_per_page");
		$paginator = new Paginator($playersCount, $eps, $this->_websoccer);
		$paginator->addParameter("block", "playerssearch-results");
		$paginator->addParameter("fname", $this->_firstName);
		$paginator->addParameter("lname", $this->_lastName);
		$paginator->addParameter("club", $this->_club);
		$paginator->addParameter("position", $this->_position);
		$paginator->addParameter("strength", $this->_strength);
		$paginator->addParameter("lendable", $this->_lendableOnly);
		if ($playersCount > 0) $players = PlayersDataService::findPlayers($this->_websoccer, $this->_db, $this->_firstName, $this->_lastName, $this->_club, $this->_position, $this->_strength, $this->_lendableOnly, $paginator->getFirstIndex(), $eps);
		else $players = array();
		return array("playersCount" => $playersCount, "players" => $players, "paginator" => $paginator); }
	function renderView() {
		$this->_firstName = $this->_websoccer->getRequestParameter("fname");
		$this->_lastName = $this->_websoccer->getRequestParameter("lname");
		$this->_club = $this->_websoccer->getRequestParameter("club");
		$this->_position = $this->_websoccer->getRequestParameter("position");
		$this->_strength = $this->_websoccer->getRequestParameter("strength");
		$this->_lendableOnly = ($this->_websoccer->getRequestParameter("lendable") == "1") ? TRUE : FALSE;
		return ($this->_firstName !== null || $this->_lastName !== null || $this->_club !== null || $this->_position !== null || $this->_strength !== null || $this->_lendableOnly); }}
class PlayerStatisticsModel extends Model {
	function getTemplateParameters() {
		$playerId = (int) $this->_websoccer->getRequestParameter('id');
		if ($playerId < 1) throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		$leagueStatistics = array();
		$cupStatistics = array();
		$columns = array('L.name' => 'league_name', 'SEAS.name' => 'season_name', 'M.pokalname' => 'cup_name', 'COUNT(S.id)' => 'matches', 'SUM(S.assists)' => 'assists', 'AVG(S.note)' => 'grade', 'SUM(S.tore)' => 'goals', 'SUM(S.karte_gelb)' => 'yellowcards',
			'SUM(S.karte_rot)' => 'redcards', 'SUM(S.shoots)' => 'shoots', 'SUM(S.passes_successed)' => 'passes_successed', 'SUM(S.passes_failed)' => 'passes_failed' );
		$fromTable = $this->_websoccer->getConfig('db_prefix') . '_spiel_berechnung AS S';
		$fromTable .= ' INNER JOIN ' . $this->_websoccer->getConfig('db_prefix') . '_spiel AS M ON M.id = S.spiel_id';
		$fromTable .= ' LEFT JOIN ' . $this->_websoccer->getConfig('db_prefix') . '_saison AS SEAS ON SEAS.id = M.saison_id';
		$fromTable .= ' LEFT JOIN ' . $this->_websoccer->getConfig('db_prefix') . '_liga AS L ON SEAS.liga_id = L.id';
		$whereCondition = 'S.spieler_id = %d AND S.minuten_gespielt > 0 AND ((M.spieltyp = \'Pokalspiel\' AND M.pokalname IS NOT NULL AND M.pokalname != \'\') OR (M.spieltyp = \'Ligaspiel\' AND SEAS.id IS NOT NULL)) GROUP BY IFNULL(M.pokalname,\'\'),
			SEAS.id ORDER BY L.name ASC, SEAS.id ASC, M.pokalname ASC';
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $playerId);
		while ($statistic = $result->fetch_array()) {
			if (strlen($statistic['league_name'])) $leagueStatistics[] = $statistic;
			else $cupStatistics[] = $statistic; }
		return array('leagueStatistics' => $leagueStatistics, 'cupStatistics' => $cupStatistics); }}
class PremiumAccountModel extends Model {
	function getTemplateParameters() {
		$userId = $this->_websoccer->getUser()->id;
		$count = PremiumDataService::countAccountStatementsOfUser($this->_websoccer, $this->_db, $userId);
		$eps = $this->_websoccer->getConfig("entries_per_page");
		$paginator = new Paginator($count, $eps, $this->_websoccer);
		if ($count > 0) $statements = PremiumDataService::getAccountStatementsOfUser($this->_websoccer, $this->_db, $userId, $paginator->getFirstIndex(), $eps);
		else $statements = array();
		return array("statements" => $statements, "paginator" => $paginator, "payments" => PremiumDataService::getPaymentsOfUser($this->_websoccer, $this->_db, $userId, 5)); }}
class ProfileBlockModel extends Model {
	function getTemplateParameters() {
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_user";
		$user = $this->_websoccer->getUser();
		$columns = "fanbeliebtheit AS user_popularity, highscore AS user_highscore";
		$whereCondition = "id = %d";
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $user->id, 1);
		$userinfo = $result->fetch_array();
		$clubId = $user->getClubId($this->_websoccer, $this->_db);
		$team = null;
		if ($clubId > 0) $team = TeamsDataService::getTeamSummaryById($this->_websoccer, $this->_db, $clubId);
		$unseenMessages = MessagesDataService::countUnseenInboxMessages($this->_websoccer, $this->_db);
		$unseenNotifications = NotificationsDataService::countUnseenNotifications($this->_websoccer, $this->_db, $user->id, $clubId);
		return array("profile" => $userinfo, "userteam" => $team, "unseenMessages" => $unseenMessages, "unseenNotifications" => $unseenNotifications); }
	function renderView() { return (strlen($this->_websoccer->getUser()->username)) ? TRUE : FALSE; }}
class ProfileModel extends Model {
	function getTemplateParameters() {
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_user";
		$user = $this->_websoccer->getUser();
		$columns["name"] = "realname";
		$columns["wohnort"] = "place";
		$columns["land"] = "country";
		$columns["geburtstag"] = "birthday";
		$columns["beruf"] = "occupation";
		$columns["interessen"] = "interests";
		$columns["lieblingsverein"] = "favorite_club";
		$columns["homepage"] = "homepage";
		$columns["c_hideinonlinelist"] = "c_hideinonlinelist";
		$whereCondition = "id = %d";
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $user->id, 1);
		$userinfo = $result->fetch_array();
		if (!strlen($userinfo["birthday"]) || substr($userinfo["birthday"], 0, 4) == "0000") $userinfo["birthday"] = "";
		else $userinfo["birthday"] = DateTime::createFromFormat("Y-m-d", $userinfo["birthday"])->format($this->_websoccer->getConfig("date_format"));
		foreach ($columns as $dbColumn) {
			if ($this->_websoccer->getRequestParameter($dbColumn)) $userinfo[$dbColumn] = $this->_websoccer->getRequestParameter($dbColumn); }
		return array("user" => $userinfo); }}
class ProjectStatisticsModel extends Model {
	function getTemplateParameters() {
		return array("usersOnline" => UsersDataService::countOnlineUsers($this->_websoccer, $this->_db), "usersTotal" => UsersDataService::countTotalUsers($this->_websoccer, $this->_db), "numberOfLeagues" => LeagueDataService::countTotalLeagues($this->_websoccer,
			$this->_db), "numberOfFreeTeams" => TeamsDataService::countTeamsWithoutManager($this->_websoccer, $this->_db)); }}
class RegisterFormModel extends Model {
	function getTemplateParameters() {
		if (!$this->_websoccer->getConfig("allow_userregistration")) throw new Exception($this->_i18n->getMessage("registration_disabled"));
		$parameters = array();
		if ($this->_websoccer->getConfig("register_use_captcha") && strlen($this->_websoccer->getConfig("register_captcha_publickey")) && strlen($this->_websoccer->getConfig("register_captcha_privatekey"))) {
			include_once(BASE_FOLDER . "/lib/recaptcha/recaptchalib.php");
			$useSsl = (!empty($_SERVER["HTTPS"]));
			$captchaCode = recaptcha_get_html($this->_websoccer->getConfig("register_captcha_publickey"), null, $useSsl);
			$parameters["captchaCode"] = $captchaCode; }
		return $parameters; }}
class RssResultsOfUserModel extends Model {
	function getTemplateParameters() {
		$userId = (int) $this->_websoccer->getRequestParameter('id');
		$matches = MatchesDataService::getLatestMatchesByUser($this->_websoccer, $this->_db, $userId);
		$items = array();
		foreach ($matches as $match) $items[] = array('url' => $this->_websoccer->getInternalUrl('match', 'id=' . $match['id'], TRUE), 'title' => $match['home_team'] . ' - ' . $match['guest_team'] . ' (' . $match['home_goals'] . ':' . $match['guest_goals'] . ')',
			'date' => gmdate(DATE_RSS, $match['date']));
		return array('items' => $items); }}
class SeasonsOfLeagueModel extends Model {
	function getTemplateParameters() {
		$fromTable = $this->_websoccer->getConfig("db_prefix") ."_saison AS S";
		$fromTable .= " INNER JOIN " . $this->_websoccer->getConfig("db_prefix") ."_liga AS L ON L.id = S.liga_id";
		$whereCondition = "S.liga_id = %d ORDER BY beendet DESC, name ASC";
		$seasons = array();
		$result = $this->_db->querySelect("S.id AS id, S.name AS name, L.name AS league_name", $fromTable, $whereCondition, $this->_leagueId);
		while ($season = $result->fetch_array()) $seasons[] = $season;
		$league_name = "";
		if (isset($seasons[0]["league_name"])) $league_name = $seasons[0]["league_name"];
		$currentMatchDay = 0;
		$maxMatchDay = 0;
		if ($this->_websoccer->getRequestParameter("seasonid") != null) {
			$seasonId = $this->_websoccer->getRequestParameter("seasonid");
			$fromTable = $this->_websoccer->getConfig("db_prefix") ."_spiel";
			$condition = "saison_id = %d";
			$result = $this->_db->querySelect("MAX(spieltag) AS maxMatchday", $fromTable, $condition, $seasonId);
			$match = $result->fetch_array();
			$maxMatchDay = $match["maxMatchday"];
			$result = $this->_db->querySelect("MAX(spieltag) AS currentMatchday", $fromTable, $condition . " AND berechnet = '1'", $seasonId);
			$match = $result->fetch_array();
			$currentMatchDay = $match["currentMatchday"]; }
		return array("seasons" => $seasons, "league_name" => $league_name, "currentMatchDay" => $currentMatchDay, "maxMatchDay" => $maxMatchDay); }
	function renderView() {
		$this->_leagueId = (int) $this->_websoccer->getRequestParameter("leagueid");
		return ($this->_leagueId > 0); }}
class ShoutboxLeagueModel extends Model {
	function getTemplateParameters() {
		$messages = array();
		$tablePrefix = $this->_websoccer->getConfig('db_prefix');
		$fromTable = $tablePrefix . '_shoutmessage AS MESSAGE';
		$fromTable .= ' INNER JOIN ' . $tablePrefix . '_user AS U ON U.id = MESSAGE.user_id';
		$fromTable .= ' INNER JOIN ' . $tablePrefix . '_spiel AS M ON M.id = MESSAGE.match_id';
		$fromTable .= ' INNER JOIN ' . $tablePrefix . '_verein AS HOME ON HOME.id = M.home_verein';
		$fromTable .= ' INNER JOIN ' . $tablePrefix . '_verein AS GUEST ON GUEST.id = M.gast_verein';
		$fromTable .= ' INNER JOIN ' . $tablePrefix . '_saison AS SEASON ON (M.saison_id = SEASON.id)';
		$fromTable .= ' INNER JOIN ' . $tablePrefix . '_liga AS L ON (L.id = SEASON.liga_id)';
		$whereCondition = 'L.id = %d ORDER BY MESSAGE.created_date DESC';
		$columns = array('MESSAGE.id' => 'message_id', 'MESSAGE.message' => 'message', 'MESSAGE.created_date' => 'date', 'U.id' => 'user_id', 'U.nick' => 'user_name', 'HOME.name' => 'home_name', 'GUEST.name' => 'guest_name', 'M.id' => 'match_id' );
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $this->_leagueId, 20);
		while ($message = $result->fetch_array()) $messages[] = $message;
		return array("messages" => $messages, "hidesubmit" => TRUE); }
	function renderView() {
		$this->_leagueId = $this->_websoccer->getRequestParameter('id');
		return ($this->_leagueId != NULL); }}
class ShoutboxModel extends Model {
	function getTemplateParameters() {
		$messages = array();
		$tablePrefix = $this->_websoccer->getConfig('db_prefix');
		$fromTable = $tablePrefix . '_shoutmessage AS MESSAGE';
		$fromTable .= ' INNER JOIN ' . $tablePrefix . '_user AS U ON U.id = MESSAGE.user_id';
		$fromTable .= ' INNER JOIN ' . $tablePrefix . '_spiel AS M ON M.id = MESSAGE.match_id';
		$fromTable .= ' INNER JOIN ' . $tablePrefix . '_verein AS HOME ON HOME.id = M.home_verein';
		$fromTable .= ' INNER JOIN ' . $tablePrefix . '_verein AS GUEST ON GUEST.id = M.gast_verein';
		$fromTable .= ' INNER JOIN ' . $tablePrefix . '_spiel AS REFERENCE ON (M.saison_id IS NOT NULL AND M.saison_id = REFERENCE.saison_id OR M.pokalname IS NOT NULL AND M.pokalname != \'\' AND  M.pokalname
			= REFERENCE.pokalname OR REFERENCE.spieltyp = \'Freundschaft\' AND M.spieltyp = REFERENCE.spieltyp)';
		$whereCondition = 'REFERENCE.id = %d ORDER BY MESSAGE.created_date DESC';
		$columns = array( 'MESSAGE.id' => 'message_id', 'MESSAGE.message' => 'message', 'MESSAGE.created_date' => 'date', 'U.id' => 'user_id', 'U.nick' => 'user_name', 'HOME.name' => 'home_name', 'GUEST.name' => 'guest_name', 				'M.id' => 'match_id' );
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $this->_matchId, 20);
		while ($message = $result->fetch_array()) $messages[] = $message;
		return array("messages" => $messages); }
	function renderView() {
		$this->_matchId = $this->_websoccer->getRequestParameter('id');
		return ($this->_matchId != NULL); }}
class SponsorModel extends Model {
	function getTemplateParameters() {
		$teamId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		if ($teamId < 1) throw new Exception($this->_i18n->getMessage("feature_requires_team"));
		$sponsor = SponsorsDataService::getSponsorinfoByTeamId($this->_websoccer, $this->_db, $teamId);
		$sponsors = array();
		$teamMatchday = 0;
		if (!$sponsor) {
			$teamMatchday = MatchesDataService::getMatchdayNumberOfTeam($this->_websoccer, $this->_db, $teamId);
			if ($teamMatchday >= $this->_websoccer->getConfig("sponsor_earliest_matchday")) $sponsors = SponsorsDataService::getSponsorOffers($this->_websoccer, $this->_db, $teamId); }
		return array("sponsor" => $sponsor, "sponsors" => $sponsors, "teamMatchday" => $teamMatchday); }}
class StadiumEnvironmentModel extends Model {
	function getTemplateParameters() {
		$teamId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		if ($teamId < 1) throw new Exception($this->_i18n->getMessage("feature_requires_team"));
		$dbPrefix = $this->_websoccer->getConfig('db_prefix');
		$existingBuildings = array();
		$result = $this->_db->querySelect('*', $dbPrefix . '_buildings_of_team INNER JOIN '. $dbPrefix . '_stadiumbuilding ON id = building_id', 'team_id = %d ORDER BY construction_deadline DESC', $teamId);
		$now = $this->_websoccer->getNowAsTimestamp();
		while ($building = $result->fetch_array()) {
			$building['under_construction'] = $now < $building['construction_deadline'];
			$existingBuildings[] = $building; }
		$availableBuildings = array();
		$result = $this->_db->querySelect('*', $dbPrefix . '_stadiumbuilding', 'id NOT IN (SELECT building_id FROM ' . $dbPrefix . '_buildings_of_team WHERE team_id = %d) ' . ' AND (required_building_id IS NULL OR required_building_id IN (SELECT building_id FROM ' .
			$dbPrefix . '_buildings_of_team WHERE team_id = %d AND construction_deadline < %d))' . ' ORDER BY name ASC', array($teamId, $teamId, $now));
		while ($building = $result->fetch_array()) {
			if ($this->_i18n->hasMessage($building['name'])) $building['name'] = $this->_i18n->getMessage($building['name']);
			if ($this->_i18n->hasMessage($building['description'])) $building['description'] = $this->_i18n->getMessage($building['description']);
			$availableBuildings[] = $building; }
		return array('existingBuildings' => $existingBuildings, 'availableBuildings' => $availableBuildings); }}
class StadiumExtensionModel extends Model {
	function getTemplateParameters() {
		$teamId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		$offers = StadiumsDataService::getBuilderOffersForExtension($this->_websoccer, $this->_db, $teamId, (int) $this->_websoccer->getRequestParameter("side_standing"), (int) $this->_websoccer->getRequestParameter("side_seats"),
			(int) $this->_websoccer->getRequestParameter("grand_standing"), (int) $this->_websoccer->getRequestParameter("grand_seats"), (int) $this->_websoccer->getRequestParameter("vip"));
		return array("offers" => $offers); }}
class StadiumModel extends Model {
	function getTemplateParameters() {
		$teamId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		$stadium = StadiumsDataService::getStadiumByTeamId($this->_websoccer, $this->_db, $teamId);
		$construction = StadiumsDataService::getCurrentConstructionOrderOfTeam($this->_websoccer, $this->_db, $teamId);
		$upgradeCosts = array();
		if ($stadium) {
			$upgradeCosts["pitch"] = StadiumsDataService::computeUpgradeCosts($this->_websoccer, "pitch", $stadium);
			$upgradeCosts["videowall"] = StadiumsDataService::computeUpgradeCosts($this->_websoccer, "videowall", $stadium);
			$upgradeCosts["seatsquality"] = StadiumsDataService::computeUpgradeCosts($this->_websoccer, "seatsquality", $stadium);
			$upgradeCosts["vipquality"] = StadiumsDataService::computeUpgradeCosts($this->_websoccer, "vipquality", $stadium); }
		return array("stadium" => $stadium, "construction" => $construction, "upgradeCosts" => $upgradeCosts); }}
class TableHistoryModel extends Model {
	function getTemplateParameters() {
		$teamId = (int) $this->_websoccer->getRequestParameter('id');
		if ($teamId < 1) throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		$team = TeamsDataService::getTeamById($this->_websoccer, $this->_db, $teamId);
		if (!isset($team['team_id'])) throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		$result = $this->_db->querySelect('id', $this->_websoccer->getConfig('db_prefix') .'_saison', 'liga_id = %d AND beendet = \'0\' ORDER BY name DESC', $team['team_league_id'], 1);
		$season = $result->fetch_array();
		$history = array();
		if ($season) {
			$columns = array('H.matchday' => 'matchday', 'H.rank' => 'rank' );
			$fromTable = $this->_websoccer->getConfig('db_prefix') .'_leaguehistory AS H';
			$result = $this->_db->querySelect('matchday, rank', $fromTable, 'season_id = %d AND team_id = %s ORDER BY matchday ASC', array($season['id'], $team['team_id']));
			while ($historyRecord = $result->fetch_array()) $history[] = $historyRecord; }
		$result = $this->_db->querySelect('COUNT(*) AS cnt', $this->_websoccer->getConfig('db_prefix') .'_verein', 'liga_id = %d AND status = \'1\'', $team['team_league_id'], 1);
		$teams = $result->fetch_array();
		return array('teamName' => $team['team_name'], 'history' => $history, 'noOfTeamsInLeague' => $teams['cnt'], 'leagueid' => $team['team_league_id']); }}
class TeamDetailsModel extends Model {
	function getTemplateParameters() {
		$teamId = (int) $this->_websoccer->getRequestParameter('id');
		if ($teamId < 1) throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		$team = TeamsDataService::getTeamById($this->_websoccer, $this->_db, $teamId);
		if (!isset($team['team_id'])) throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		$stadium = StadiumsDataService::getStadiumByTeamId($this->_websoccer, $this->_db, $teamId);
		if ($team['is_nationalteam']) {
			$dbPrefix = $this->_websoccer->getConfig('db_prefix') ;
			$result = $this->_db->querySelect('AVG(P.w_staerke) AS avgstrength', $dbPrefix . '_spieler AS P INNER JOIN ' . $dbPrefix . '_nationalplayer AS NP ON P.id = NP.player_id', 'NP.team_id = %d', $team['team_id']);
			$players = $result->fetch_array();
			if ($players) $team['team_strength'] = $players['avgstrength']; }
		if (!$team['is_nationalteam']) $playerfacts = $this->getPlayerFacts($teamId);
		else $playerfacts = array();
		$team['victories'] = $this->getVictories($team['team_id'], $team['team_league_id']);
		$team['cupvictories'] = $this->getCupVictories($team['team_id']);
		return array('team' => $team, 'stadium' => $stadium, 'playerfacts' => $playerfacts); }
	function getVictories($teamId, $leagueId) {
		$fromTable = $this->_websoccer->getConfig('db_prefix') .'_saison AS S INNER JOIN ' . $this->_websoccer->getConfig('db_prefix') . '_liga AS L ON L.id = S.liga_id';
		$columns['S.name'] = 'season_name';
		$columns['L.name'] = 'league_name';
		$columns['platz_1_id'] = 'season_first';
		$columns['platz_2_id'] = 'season_second';
		$columns['platz_3_id'] = 'season_third';
		$columns['platz_4_id'] = 'season_fourth';
		$columns['platz_5_id'] = 'season_fivth';
		$whereCondition = 'beendet = 1 AND (platz_1_id = %d OR platz_2_id = %d OR platz_3_id = %d OR platz_4_id = %d OR platz_5_id = %d)';
		$parameters = array($teamId, $teamId, $teamId, $teamId, $teamId);
		$victories = array();
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $parameters);
		while ($season = $result->fetch_array()) {
			$place = 1;
			if ($season['season_second'] == $teamId) $place = 2;
			elseif ($season['season_third'] == $teamId) $place = 3;
			elseif ($season['season_fourth'] == $teamId) $place = 4;
			elseif ($season['season_fivth'] == $teamId) $place = 5;
			$victories[] = array('season_name' => $season['season_name'], 'season_place' => $place, 'league_name' => $season['league_name']); }
		return $victories; }
	function getCupVictories($teamId) {
		$fromTable = $this->_websoccer->getConfig('db_prefix') .'_cup';
		$whereCondition = 'winner_id = %d ORDER BY name ASC';
		$result = $this->_db->querySelect('id AS cup_id,name AS cup_name,logo AS cup_logo', $fromTable, $whereCondition, $teamId);
		$victories = array();
		while ($cup = $result->fetch_array()) $victories[] = $cup;;
		return $victories; }
	function getPlayerFacts($teamId) {
		$columns = array('COUNT(*)' => 'numberOfPlayers' );
		if ($this->_websoccer->getConfig('players_aging') == 'birthday') $ageColumn = 'TIMESTAMPDIFF(YEAR,geburtstag,CURDATE())';
		else $ageColumn = 'age';
		$columns['AVG(' . $ageColumn . ')'] = 'avgAge';
		if ($this->_websoccer->getConfig('transfermarket_computed_marketvalue')) {
			$columns['SUM(w_staerke)'] = 'sumStrength';
			$columns['SUM(w_technik)'] = 'sumTechnique';
			$columns['SUM(w_frische)'] = 'sumFreshness';
			$columns['SUM(w_zufriedenheit)'] = 'sumSatisfaction';
			$columns['SUM(w_kondition)'] = 'sumStamina'; }
		else $columns['SUM(marktwert)'] = 'sumMarketValue';
		$result = $this->_db->querySelect($columns, $this->_websoccer->getConfig('db_prefix') .'_spieler', 'verein_id = %d AND status = \'1\'', $teamId);
		$playerfacts = $result->fetch_array();
		if ($this->_websoccer->getConfig('transfermarket_computed_marketvalue')) $playerfacts['sumMarketValue'] = $this->computeMarketValue($playerfacts['sumStrength'], $playerfacts['sumTechnique'], $playerfacts['sumFreshness'], $playerfacts['sumSatisfaction'],
			$playerfacts['sumStamina']);
		if ($playerfacts['numberOfPlayers'] > 0) $playerfacts['avgMarketValue'] = $playerfacts['sumMarketValue'] / $playerfacts['numberOfPlayers'];
		else $playerfacts['avgMarketValue'] = 0;
		return $playerfacts; }
	function computeMarketValue($strength, $technique, $freshness, $satisfaction, $stamina) {
		$weightStrength = $this->_websoccer->getConfig('sim_weight_strength');
		$weightTech = $this->_websoccer->getConfig('sim_weight_strengthTech');
		$weightStamina = $this->_websoccer->getConfig('sim_weight_strengthStamina');
		$weightFreshness = $this->_websoccer->getConfig('sim_weight_strengthFreshness');
		$weightSatisfaction = $this->_websoccer->getConfig('sim_weight_strengthSatisfaction');
		$totalStrength = $weightStrength * $strength;
		$totalStrength += $weightTech * $technique;
		$totalStrength += $weightStamina * $freshness;
		$totalStrength += $weightFreshness * $satisfaction;
		$totalStrength += $weightSatisfaction * $stamina;
		$totalStrength /= $weightStrength + $weightTech + $weightStamina + $weightFreshness + $weightSatisfaction;
		return $totalStrength * $this->_websoccer->getConfig('transfermarket_value_per_strength'); }}
class TeamHistoryModel extends Model {
	function getTemplateParameters() {
		$columns = array('U.id' => 'user_id', 'U.nick' => 'user_name', 'L.name' => 'league_name', 'SEASON.name' => 'season_name', 'A.rank' => 'season_rank', 'A.id' => 'achievement_id', 'A.date_recorded' => 'achievement_date', 'CUP.name' => 'cup_name',
			'CUPROUND.name' => 'cup_round_name' );
		$tablePrefix = $this->_websoccer->getConfig('db_prefix');
		$fromTable = $tablePrefix . '_achievement AS A';
		$fromTable .= ' LEFT JOIN ' . $tablePrefix . '_saison AS SEASON ON SEASON.id = A.season_id';
		$fromTable .= ' LEFT JOIN ' . $tablePrefix . '_liga AS L ON SEASON.liga_id = L.id';
		$fromTable .= ' LEFT JOIN ' . $tablePrefix . '_cup_round AS CUPROUND ON CUPROUND.id = A.cup_round_id';
		$fromTable .= ' LEFT JOIN ' . $tablePrefix . '_cup AS CUP ON CUP.id = CUPROUND.cup_id';
		$fromTable .= ' LEFT JOIN ' . $tablePrefix . '_user AS U ON U.id = A.user_id';
		$whereCondition = 'A.team_id = %d ORDER BY A.date_recorded DESC';
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $this->_teamId);
		$leagues = array();
		$cups = array();
		while ($achievement = $result->fetch_array()) {
			if (strlen($achievement['league_name'])) $leagues[$achievement['league_name']][] = $achievement;
			elseif (!isset($cups[$achievement['cup_name']])) $cups[$achievement['cup_name']] = $achievement;
			else $this->_db->queryDelete($tablePrefix . '_achievement', 'id = %d', $achievement['achievement_id']); }
		return array("leagues" => $leagues, "cups" => $cups); }
	function renderView() {
		$this->_teamId = (int) $this->_websoccer->getRequestParameter("teamid");
		return $this->_teamId > 0; }}
class TeamOfTheDayModel extends Model {
	function getTemplateParameters() {
		$players = array();
		$positions;
		$leagues = LeagueDataService::getLeaguesSortedByCountry($this->_websoccer, $this->_db);
		$leagueId = $this->_websoccer->getRequestParameter("leagueid");
		if (!$leagueId) {
			$clubId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
			if ($clubId > 0) {
				$result = $this->_db->querySelect("liga_id", $this->_websoccer->getConfig("db_prefix") . "_verein", "id = %d", $clubId, 1);
				$club = $result->fetch_array();
				$leagueId = $club["liga_id"]; }}
		$seasons = array();
		$seasonId = $this->_websoccer->getRequestParameter("seasonid");
		if ($leagueId) {
			$fromTable = $this->_websoccer->getConfig("db_prefix") ."_saison";
			$whereCondition = "liga_id = %d ORDER BY name ASC";
			$result = $this->_db->querySelect("id, name, beendet", $fromTable, $whereCondition, $leagueId);
			while ($season = $result->fetch_array()) {
				$seasons[] = $season;
				if (!$seasonId && !$season["beendet"]) $seasonId = $season["id"]; }}
		$matchday = $this->_websoccer->getRequestParameter("matchday");
		$maxMatchDay = 0;
		$openMatchesExist = FALSE;
		if ($seasonId) {
			$result = $this->_db->querySelect("MAX(spieltag) AS max_matchday", $this->_websoccer->getConfig("db_prefix") . "_spiel", "saison_id = %d AND berechnet = '1'", $seasonId);
			$matches = $result->fetch_array();
			if ($matches) {
				$maxMatchDay = $matches["max_matchday"];
				if (!$matchday) $matchday = $maxMatchDay;
				$result = $this->_db->querySelect("COUNT(*) AS hits", $this->_websoccer->getConfig("db_prefix") . "_spiel", "saison_id = %d AND spieltag = %d AND berechnet != '1'", array($seasonId, $matchday));
				$openmatches = $result->fetch_array();
				if ($openmatches && $openmatches["hits"]) $openMatchesExist = TRUE;
				else $this->getTeamOfTheDay($seasonId, $matchday, $players); }}
		return array("leagues" => $leagues, "leagueId" => $leagueId, "seasons" => $seasons, "seasonId" => $seasonId, "maxMatchDay" => $maxMatchDay, "matchday" => $matchday, "openMatchesExist" => $openMatchesExist, "players" => $players); }
	function getTeamOfTheDay($seasonId, $matchday, &$players) {
		$seasonId = (int) $seasonId;
		if ($matchday == -1) {
			$this->findPlayersForTeamOfSeason($seasonId, array("T"), 1, $players);
			$this->findPlayersForTeamOfSeason($seasonId, array("LV"), 1, $players);
			$this->findPlayersForTeamOfSeason($seasonId, array("IV"), 2, $players);
			$this->findPlayersForTeamOfSeason($seasonId, array("RV"), 1, $players);
			$this->findPlayersForTeamOfSeason($seasonId, array("LM"), 1, $players);
			$this->findPlayersForTeamOfSeason($seasonId, array("DM", "ZM", "OM"), 2, $players);
			$this->findPlayersForTeamOfSeason($seasonId, array("RM"), 1, $players);
			$this->findPlayersForTeamOfSeason($seasonId, array("LS", "MS", "RS"), 2, $players);
			return; }
		$columns = array( "S.id" => "statistic_id", "S.spieler_id" => "player_id", "S.name" => "player_name", "P.picture" => "picture", "S.position" => "position", "S.position_main" => "position_main", "S.note" => "grade", "S.tore" => "goals", "S.assists" => "assists",
			"T.name" => "team_name", "T.bild" => "team_picture", "(SELECT COUNT(*) FROM ". $this->_websoccer->getConfig("db_prefix") . "_teamoftheday AS STAT WHERE STAT.season_id = $seasonId AND STAT.player_id = S.spieler_id)" => "memberoftopteam" );
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_teamoftheday AS C";
		$fromTable .= " INNER JOIN " . $this->_websoccer->getConfig("db_prefix") . "_spiel_berechnung AS S ON S.id = C.statistic_id";
		$fromTable .= " INNER JOIN " . $this->_websoccer->getConfig("db_prefix") . "_spiel AS M ON M.id = S.spiel_id";
		$fromTable .= " INNER JOIN " . $this->_websoccer->getConfig("db_prefix") . "_verein AS T ON T.id = S.team_id";
		$fromTable .= " LEFT JOIN " . $this->_websoccer->getConfig("db_prefix") . "_spieler AS P ON P.id = S.spieler_id";
		$result = $this->_db->querySelect($columns, $fromTable, "C.season_id = %d AND C.matchday = %d", array($seasonId, $matchday));
		while ($player = $result->fetch_array()) $players[] = $player;
		if (!count($players)) {
			$this->findPlayers($columns, $seasonId, $matchday, array("T"), 1, $players);
			$this->findPlayers($columns, $seasonId, $matchday, array("LV"), 1, $players);
			$this->findPlayers($columns, $seasonId, $matchday, array("IV"), 2, $players);
			$this->findPlayers($columns, $seasonId, $matchday, array("RV"), 1, $players);
			$this->findPlayers($columns, $seasonId, $matchday, array("LM"), 1, $players);
			$this->findPlayers($columns, $seasonId, $matchday, array("DM", "ZM", "OM"), 2, $players);
			$this->findPlayers($columns, $seasonId, $matchday, array("RM"), 1, $players);
			$this->findPlayers($columns, $seasonId, $matchday, array("LS", "MS", "RS"), 2, $players); }}
	function findPlayers($columns, $seasonId, $matchday, $mainPositions, $limit, &$players) {
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_spiel_berechnung AS S";
		$fromTable .= " INNER JOIN " . $this->_websoccer->getConfig("db_prefix") . "_spiel AS M ON M.id = S.spiel_id";
		$fromTable .= " INNER JOIN " . $this->_websoccer->getConfig("db_prefix") . "_verein AS T ON T.id = S.team_id";
		$fromTable .= " LEFT JOIN " . $this->_websoccer->getConfig("db_prefix") . "_spieler AS P ON P.id = S.spieler_id";
		$whereCondition = "M.saison_id = %d AND M.spieltag = %d AND (S.position_main = '";
		$whereCondition .= implode("' OR S.position_main = '", $mainPositions);
		$whereCondition .= "') ORDER BY S.note ASC, S.tore DESC, S.assists DESC, S.wontackles DESC";
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, array($seasonId, $matchday), $limit);
		while ($player = $result->fetch_array()) {
			$players[] = $player;
			$this->_db->queryInsert(array("season_id" => $seasonId, "matchday" => $matchday, "position_main" => $player["position_main"], "statistic_id" => $player["statistic_id"], "player_id" => $player["player_id"]
				), $this->_websoccer->getConfig("db_prefix") . "_teamoftheday"); }}
	function findPlayersForTeamOfSeason($seasonId, $mainPositions, $limit, &$players) {
		$columns = array( "P.id" => "player_id", "P.vorname" => "firstname", "P.nachname" => "lastname", "P.kunstname" => "pseudonym", "P.picture" => "picture", "P.position" => "position", "C.position_main" => "position_main", "T.name" => "team_name",
			"T.bild" => "team_picture", "(SELECT COUNT(*) FROM ". $this->_websoccer->getConfig("db_prefix") . "_teamoftheday AS STAT WHERE STAT.season_id = $seasonId AND STAT.player_id = P.id)" => "memberoftopteam" );
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_teamoftheday AS C";
		$fromTable .= " INNER JOIN " . $this->_websoccer->getConfig("db_prefix") . "_spieler AS P ON P.id = C.player_id";
		$fromTable .= " LEFT JOIN " . $this->_websoccer->getConfig("db_prefix") . "_verein AS T ON T.id = P.verein_id";
		$whereCondition = "C.season_id = %d AND (C.position_main = '";
		$whereCondition .= implode("' OR C.position_main = '", $mainPositions);
		$whereCondition .= "') ";
		foreach ($players as $foundPlayer) $whereCondition .= " AND  P.id != " . $foundPlayer['player_id'];
		$whereCondition .= " GROUP BY P.id ORDER BY memberoftopteam DESC";
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $seasonId, $limit);
		while ($player = $result->fetch_array()) {
			$player["player_name"] = (strlen($player["pseudonym"])) ? $player["pseudonym"] : $player["firstname"] . " " . $player["lastname"];
			$players[] = $player; }}}
class TeamPlayersModel extends Model {
	function getTemplateParameters() {
		$isNationalTeam = ($this->_websoccer->getRequestParameter("nationalteam")) ? TRUE : FALSE;
		$players = PlayersDataService::getPlayersOfTeamById($this->_websoccer, $this->_db, $this->_teamid, $isNationalTeam);
		return array("players" => $players); }
	function renderView() {
		$this->_teamid = (int) $this->_websoccer->getRequestParameter("teamid");
		return ($this->_teamid > 0); }}
class TeamResultsModel extends Model {
	function getTemplateParameters() {
		$matches = MatchesDataService::getLatestMatchesByTeam($this->_websoccer, $this->_db, $this->_teamId);
		return array("matches" => $matches); }
	function renderView() {
		$this->_teamId = (int) $this->_websoccer->getRequestParameter("teamid");
		return $this->_teamId > 0; }}
class TeamTransfersModel extends Model {
	function getTemplateParameters() {
		$teamId = $this->_websoccer->getRequestParameter("teamid");
		if ($teamId > 0) $transfers = TransfermarketDataService::getCompletedTransfersOfTeam($this->_websoccer, $this->_db, $teamId);
		return array("completedtransfers" => $transfers); }}
class TermsAndConditionsModel extends Model {
	function getTemplateParameters() {
		$termsFile = BASE_FOLDER . "/admin/config/termsandconditions.xml";
		if (!file_exists($termsFile)) throw new Exception("File does not exist: " . $termsFile);
		$xml = simplexml_load_file($termsFile);
		$termsConfig = $xml->xpath("//pagecontent[@lang = '". $this->_i18n->getCurrentLanguage() . "'][1]");
		if (!$termsConfig) throw new Exception($this->_i18n->getMessage("termsandconditions_err_notavilable"));
		$terms = (string) $termsConfig[0];
		return array("terms" => nl2br($terms)); }}
class TicketsModel extends Model {
	function getTemplateParameters() {
		$teamId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		if ($teamId < 1) throw new Exception($this->_i18n->getMessage("feature_requires_team"));
		$columns["T.preis_stehen"] = "p_stands";
		$columns["T.preis_sitz"] = "p_seats";
		$columns["T.preis_haupt_stehen"] = "p_stands_grand";
		$columns["T.preis_haupt_sitze"] = "p_seats_grand";
		$columns["T.preis_vip"] = "p_vip";
		$columns["T.last_steh"] = "l_stands";
		$columns["T.last_sitz"] = "l_seats";
		$columns["T.last_haupt_steh"] = "l_stands_grand";
		$columns["T.last_haupt_sitz"] = "l_seats_grand";
		$columns["T.last_vip"] = "l_vip";
		$columns["S.p_steh"] = "s_stands";
		$columns["S.p_sitz"] = "s_seats";
		$columns["S.p_haupt_steh"] = "s_stands_grand";
		$columns["S.p_haupt_sitz"] = "s_seats_grand";
		$columns["S.p_vip"] = "s_vip";
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_verein AS T";
		$fromTable .= " LEFT JOIN " . $this->_websoccer->getConfig("db_prefix") . "_stadion AS S ON S.id = T.stadion_id";
		$whereCondition = "T.id = %d";
		$parameters = $teamId;
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $parameters);
		$tickets = $result->fetch_array();
		if ($this->_websoccer->getRequestParameter("p_stands")) $tickets["p_stands"] =  $this->_websoccer->getRequestParameter("p_stands");
		if ($this->_websoccer->getRequestParameter("p_seats")) $tickets["p_seats"] =  $this->_websoccer->getRequestParameter("p_seats");
		if ($this->_websoccer->getRequestParameter("p_stands_grand")) $tickets["p_stands_grand"] =  $this->_websoccer->getRequestParameter("p_stands_grand");
		if ($this->_websoccer->getRequestParameter("p_seats_grand")) $tickets["p_seats_grand"] =  $this->_websoccer->getRequestParameter("p_seats_grand");
		if ($this->_websoccer->getRequestParameter("p_vip")) $tickets["p_vip"] =  $this->_websoccer->getRequestParameter("p_vip");
		return array("tickets" => $tickets); }}
class TodaysMatchesModel extends Model {
	function getTemplateParameters() {
		$matches = array();
		$paginator = null;
		$count = MatchesDataService::countTodaysMatches($this->_websoccer, $this->_db);
		if ($count) {
			$eps = $this->_websoccer->getConfig("entries_per_page");
			$paginator = new Paginator($count, $eps, $this->_websoccer);
			$matches = MatchesDataService::getTodaysMatches($this->_websoccer, $this->_db, $paginator->getFirstIndex(), $eps); }
		return array("matches" => $matches, "paginator" => $paginator); }}
class TopNewsListModel extends Model {
	function getTemplateParameters() {
		$fromTable = $this->_websoccer->getConfig("db_prefix") . "_news";
		$columns = "id, titel, datum";
		$whereCondition = "status = 1 ORDER BY datum DESC";
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, array(), NUMBER_OF_TOP_NEWS);
		$articles = array();
		while ($article = $result->fetch_array()) $articles[] = array("id" => $article["id"], "title" => $article["titel"], "date" => $this->_websoccer->getFormattedDate($article["datum"]));
		return array("topnews" => $articles); }}
class TopScorersModel extends Model {
	function getTemplateParameters() {
		return array('players' => PlayersDataService::getTopScorers($this->_websoccer, $this->_db, NUMBER_OF_PLAYERS, $this->_websoccer->getRequestParameter('leagueid')), 'leagues' => LeagueDataService::getLeaguesSortedByCountry($this->_websoccer, $this->_db)); }}
class TopStrikersModel extends Model {
	function getTemplateParameters() {
		return array("players" => PlayersDataService::getTopStrikers($this->_websoccer, $this->_db, NUMBER_OF_PLAYERS, $this->_websoccer->getRequestParameter("leagueid")), "leagues" => LeagueDataService::getLeaguesSortedByCountry($this->_websoccer, $this->_db)); }}
class TrainerDetailsModel extends Model {
	function getTemplateParameters() {
		$trainerId = $this->_websoccer->getRequestParameter("id");
		$trainer = TrainingDataService::getTrainerById($this->_websoccer, $this->_db, $trainerId);
		if (!isset($trainer["id"])) throw new Exception(MSG_KEY_ERROR_PAGENOTFOUND);
		return array("trainer" => $trainer); }}
class TrainingCampsDetailsModel extends Model {
	function getTemplateParameters() {
		$camp = TrainingcampsDataService::getCampById($this->_websoccer, $this->_db, $this->_websoccer->getRequestParameter("id"));
		if (!$camp) throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		$defaultDate = $this->_websoccer->getNowAsTimestamp() + 24 * 3600;
		return array("camp" => $camp, "defaultDate" => $defaultDate); }}
class TrainingCampsModel extends Model {
	function getTemplateParameters() {
		$user = $this->_websoccer->getUser();
		$teamId = $user->getClubId($this->_websoccer, $this->_db);
		if ($teamId < 1) throw new Exception($this->_i18n->getMessage("feature_requires_team"));
		$camps = array();
		$bookedCamp = array();
		$bookedCamps = TrainingcampsDataService::getCampBookingsByTeam($this->_websoccer, $this->_db, $teamId);
		$listCamps = TRUE;
		if (count($bookedCamps)) {
			$bookedCamp = $bookedCamps[0];
			if ($bookedCamp["date_end"] < $this->_websoccer->getNowAsTimestamp()) {
				TrainingcampsDataService::executeCamp($this->_websoccer, $this->_db, $teamId, $bookedCamp);
				$bookedCamp = array(); }
			else $listCamps = FALSE; }
		if ($listCamps) $camps = TrainingcampsDataService::getCamps($this->_websoccer, $this->_db);
		return array("bookedCamp" => $bookedCamp, "camps" => $camps); }}
class TrainingModel extends Model {
	function getTemplateParameters() {
		$teamId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		$lastExecution = TrainingDataService::getLatestTrainingExecutionTime($this->_websoccer, $this->_db, $teamId);
		$unitsCount = TrainingDataService::countRemainingTrainingUnits($this->_websoccer, $this->_db, $teamId);
		$paginator = null;
		$trainers = null;
		$training_unit = TrainingDataService::getValidTrainingUnit($this->_websoccer, $this->_db, $teamId);
		if (!isset($training_unit["id"])) {
			$count = TrainingDataService::countTrainers($this->_websoccer, $this->_db);
			$eps = $this->_websoccer->getConfig("entries_per_page");
			$paginator = new Paginator($count, $eps, $this->_websoccer);
			if ($count > 0) $trainers = TrainingDataService::getTrainers($this->_websoccer, $this->_db, $paginator->getFirstIndex(), $eps); }
		else $training_unit["trainer"] = TrainingDataService::getTrainerById($this->_websoccer, $this->_db, $training_unit["trainer_id"]);
		$trainingEffects = array();
		$contextParameters = $this->_websoccer->getContextParameters();
		if (isset($contextParameters["trainingEffects"])) $trainingEffects = $contextParameters["trainingEffects"];
		return array("unitsCount" => $unitsCount, "lastExecution" => $lastExecution, "training_unit" => $training_unit, "trainers" => $trainers, "paginator" => $paginator, "trainingEffects" => $trainingEffects); }}
class TransferBidModel extends Model {
	function getTemplateParameters() {
		$highestBid = TransfermarketDataService::getHighestBidForPlayer($this->_websoccer, $this->_db, $this->_player["player_id"], $this->_player["transfer_start"], $this->_player["transfer_end"]);
		return array("player" => $this->_player, "highestbid" => $highestBid); }
	function renderView() {
		$playerId = (int) $this->_websoccer->getRequestParameter("id");
		if ($playerId < 1) throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		$this->_player = PlayersDataService::getPlayerById($this->_websoccer, $this->_db, $playerId);
		return ($this->_player["transfer_end"] > $this->_websoccer->getNowAsTimestamp()); }}
class TransfermarketOverviewModel extends Model {
	function getTemplateParameters() {
		$teamId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		if ($teamId < 1) throw new Exception($this->_i18n->getMessage("feature_requires_team"));
		$positionInput = $this->_websoccer->getRequestParameter("position");
		$positionFilter = null;
		if ($positionInput == "goaly") $positionFilter = "Torwart";
		elseif ($positionInput == "defense") $positionFilter = "Abwehr";
		elseif ($positionInput == "midfield") $positionFilter = "Mittelfeld";
		elseif ($positionInput == "striker") $positionFilter = "Sturm";
		$count = PlayersDataService::countPlayersOnTransferList($this->_websoccer, $this->_db, $positionFilter);
		$eps = $this->_websoccer->getConfig("entries_per_page");
		$paginator = new Paginator($count, $eps, $this->_websoccer);
		if ($positionFilter != null) $paginator->addParameter("position", $positionInput);
		if ($count > 0) $players = PlayersDataService::getPlayersOnTransferList($this->_websoccer, $this->_db, $paginator->getFirstIndex(), $eps, $positionFilter);
		else $players = array();
		return array("transferplayers" => $players, "playerscount" => $count, "paginator" => $paginator); }
	function renderView() { return ($this->_websoccer->getConfig("transfermarket_enabled") == 1); }}
class TransferOffersModel extends Model {
	function getTemplateParameters() {
		$clubId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		$offers = array();
		$count = DirectTransfersDataService::countReceivedOffers($this->_websoccer, $this->_db, $clubId);
		$eps = $this->_websoccer->getConfig("entries_per_page");
		$paginator = new Paginator($count, $eps, $this->_websoccer);
		if ($count > 0) $offers = DirectTransfersDataService::getReceivedOffers($this->_websoccer, $this->_db, $paginator->getFirstIndex(), $eps, $clubId);
		else $offers = array();
		return array("offers" => $offers, "paginator" => $paginator); }
	function renderView() { return ($this->_websoccer->getConfig("transferoffers_enabled") == 1); }}
class TransferOffersSentModel extends Model {
	function getTemplateParameters() {
		$clubId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		$userId = $this->_websoccer->getUser()->id;
		$offers = array();
		$count = DirectTransfersDataService::countSentOffers($this->_websoccer, $this->_db, $clubId, $userId);
		$eps = $this->_websoccer->getConfig("entries_per_page");
		$paginator = new Paginator($count, $eps, $this->_websoccer);
		$paginator->addParameter("block", "directtransfer-sentoffers");
		if ($count > 0) $offers = DirectTransfersDataService::getSentOffers($this->_websoccer, $this->_db, $paginator->getFirstIndex(), $eps, $clubId, $userId);
		else $offers = array();
		return array("offers" => $offers, "paginator" => $paginator); }
	function renderView() { return ($this->_websoccer->getConfig("transferoffers_enabled") == 1); }}
class UserActivitiesModel extends Model { function getTemplateParameters() { return array("activities" => ActionLogDataService::getActionLogsOfUser($this->_websoccer, $this->_db, $this->_websoccer->getRequestParameter('userid'))); }}
class UserClubsSelectionModel extends Model {
	function getTemplateParameters() {
		$whereCondition = "id = %d";
		$result = $this->_db->querySelect("id,name", $this->_websoccer->getConfig("db_prefix") . "_verein", "user_id = %d AND status = '1' AND nationalteam != '1' ORDER BY name ASC", $this->_websoccer->getUser()->id);
		$teams = array();
		while ($team = $result->fetch_array()) $teams[] = $team;
		return array("userteams" => $teams); }
	function renderView() { return (strlen($this->_websoccer->getUser()->username)) ? TRUE : FALSE; }}
class UserDetailsModel extends Model {
	function getTemplateParameters() {
		$userId = (int) $this->_websoccer->getRequestParameter('id');
		if ($userId < 1) $userId = $this->_websoccer->getUser()->id;
		$user = UsersDataService::getUserById($this->_websoccer, $this->_db, $userId);
		if (!isset($user['id'])) throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		$fromTable = $this->_websoccer->getConfig('db_prefix') . '_verein';
		$whereCondition = 'user_id = %d AND status = \'1\' AND nationalteam != \'1\' ORDER BY name ASC';
		$result = $this->_db->querySelect('id,name', $fromTable, $whereCondition, $userId);
		$teams = array();
		while ($team = $result->fetch_array()) $teams[] = $team;
		if ($this->_websoccer->getConfig('nationalteams_enabled')) {
			$columns = 'id,name';
			$fromTable = $this->_websoccer->getConfig('db_prefix') . '_verein';
			$whereCondition = 'user_id = %d AND nationalteam = \'1\'';
			$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $userId, 1);
			$nationalteam = $result->fetch_array();
			if (isset($nationalteam['id'])) $user['nationalteam'] = $nationalteam; }
		$result = $this->_db->querySelect('name, description, level, date_rewarded, event', $this->_websoccer->getConfig('db_prefix') . '_badge INNER JOIN ' . $this->_websoccer->getConfig('db_prefix') . '_badge_user ON id = badge_id', 'user_id = %d ORDER BY level DESC,
			date_rewarded ASC', $userId);
		$badges = array();
		while ($badge = $result->fetch_array()) {
			if (!isset($badges[$badge['event']])) $badges[$badge['event']] = $badge; }
		return array('user' => $user, 'userteams' => $teams, 'absence' => AbsencesDataService::getCurrentAbsenceOfUser($this->_websoccer, $this->_db, $userId), 'badges' => $badges); }}
class UserHistoryModel extends Model {
	function getTemplateParameters() {
		$columns = array('TEAM.id' => 'team_id', 'TEAM.name' => 'team_name', 'L.name' => 'league_name', 'SEASON.name' => 'season_name', 'A.rank' => 'season_rank', 'A.id' => 'achievement_id', 'A.date_recorded' => 'achievement_date', 'CUP.name' => 'cup_name',
			'CUPROUND.name' => 'cup_round_name' );
		$tablePrefix = $this->_websoccer->getConfig('db_prefix');
		$fromTable = $tablePrefix . '_achievement AS A';
		$fromTable .= ' INNER JOIN ' . $tablePrefix . '_verein AS TEAM ON TEAM.id = A.team_id';
		$fromTable .= ' LEFT JOIN ' . $tablePrefix . '_saison AS SEASON ON SEASON.id = A.season_id';
		$fromTable .= ' LEFT JOIN ' . $tablePrefix . '_liga AS L ON SEASON.liga_id = L.id';
		$fromTable .= ' LEFT JOIN ' . $tablePrefix . '_cup_round AS CUPROUND ON CUPROUND.id = A.cup_round_id';
		$fromTable .= ' LEFT JOIN ' . $tablePrefix . '_cup AS CUP ON CUP.id = CUPROUND.cup_id';
		$whereCondition = 'A.user_id = %d ORDER BY A.date_recorded DESC';
		$result = $this->_db->querySelect($columns, $fromTable, $whereCondition, $this->_userId);
		$leagues = array();
		$cups = array();
		while ($achievement = $result->fetch_array()) {
			if (strlen($achievement['league_name'])) $leagues[$achievement['league_name']][] = $achievement;
			elseif (!isset($cups[$achievement['cup_name']])) $cups[$achievement['cup_name']] = $achievement;
			else $this->_db->queryDelete($tablePrefix . '_achievement', 'id = %d', $achievement['achievement_id']); }
		return array("leagues" => $leagues, "cups" => $cups); }
	function renderView() {
		$this->_userId = (int) $this->_websoccer->getRequestParameter("userid");
		return $this->_userId > 0; }}
class UserNickSearchModel extends Model {
	function getTemplateParameters() {
		$query = $this->_websoccer->getRequestParameter("query");
		$users = UsersDataService::findUsernames($this->_websoccer, $this->_db, $query);
		return array("items" => $users); }}
class UserResultsModel extends Model {
	function getTemplateParameters() {
		$matches = MatchesDataService::getLatestMatchesByUser($this->_websoccer, $this->_db, $this->_userId);
		return array("matches" => $matches); }
	function renderView() {
		$this->_userId = (int) $this->_websoccer->getRequestParameter("userid");
		return $this->_userId > 0; }}
class UserTransfersModel extends Model {
	function getTemplateParameters() {
		$userId = $this->_websoccer->getRequestParameter("userid");
		if ($userId > 0) $transfers = TransfermarketDataService::getCompletedTransfersOfUser($this->_websoccer, $this->_db, $userId);
		return array("completedtransfers" => $transfers); }}
class WhoIsOnlineModel extends Model {
	function getTemplateParameters() {
		$count = UsersDataService::countOnlineUsers($this->_websoccer, $this->_db);
		$eps = $this->_websoccer->getConfig("entries_per_page");
		$paginator = new Paginator($count, $eps, $this->_websoccer);
		if ($count > 0) $users = UsersDataService::getOnlineUsers($this->_websoccer, $this->_db, $paginator->getFirstIndex(), $eps);
		else $users = array();
		return array("users" => $users, "paginator" => $paginator); }}
class YouthMarketplaceModel extends Model {
	function getTemplateParameters() {
		$positionFilter = $this->_websoccer->getRequestParameter("position");
		$count = YouthPlayersDataService::countTransferableYouthPlayers($this->_websoccer, $this->_db, $positionFilter);
		$eps = $this->_websoccer->getConfig("entries_per_page");
		$paginator = new Paginator($count, $eps, $this->_websoccer);
		if ($positionFilter != null) $paginator->addParameter("position", $positionFilter);
		$players = YouthPlayersDataService::getTransferableYouthPlayers($this->_websoccer, $this->_db, $paginator->getFirstIndex(), $eps,  $positionFilter);
		return array("players" => $players, "paginator" => $paginator); }
	function renderView() { return $this->_websoccer->getConfig("youth_enabled"); }}
class YouthMatchesModel extends Model {
	function getTemplateParameters() {
		$clubId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		$count = YouthMatchesDataService::countMatchesOfTeam($this->_websoccer, $this->_db, $clubId);
		$eps = $this->_websoccer->getConfig("entries_per_page");
		$paginator = new Paginator($count, $eps, $this->_websoccer);
		$matches = YouthMatchesDataService::getMatchesOfTeam($this->_websoccer, $this->_db, $clubId, $paginator->getFirstIndex(), $eps);
		return array("matches" => $matches, "paginator" => $paginator); }
	function renderView() { return $this->_websoccer->getConfig("youth_enabled"); }}
class YouthMatchFormationModel extends Model {
	function getTemplateParameters() {
		$clubId = $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db);
		$matchinfo = YouthMatchesDataService::getYouthMatchinfoById($this->_websoccer, $this->_db, $this->_i18n, $this->_websoccer->getRequestParameter("matchid"));
		if ($matchinfo["home_team_id"] == $clubId) $teamPrefix = "home";
		elseif ($matchinfo["guest_team_id"] == $clubId) $teamPrefix = "guest";
		else throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		if ($matchinfo["matchdate"] <= $this->_websoccer->getNowAsTimestamp() || $matchinfo["simulated"]) throw new Exception($this->_i18n->getMessage("youthformation_err_matchexpired"));
		$players = null;
		if ($clubId > 0) $players = YouthPlayersDataService::getYouthPlayersOfTeamByPosition($this->_websoccer, $this->_db, $clubId, "DESC");
		$formation = $this->_getFormation($teamPrefix, $matchinfo);
		for ($benchNo = 1; $benchNo <= 5; $benchNo++) {
			if ($this->_websoccer->getRequestParameter("bench" . $benchNo)) $formation["bench" . $benchNo] = $this->_websoccer->getRequestParameter("bench" . $benchNo);
			elseif (!isset($formation["bench" . $benchNo])) {
				$formation["bench" . $benchNo] = ""; }}
		$setup = $this->getFormationSetup($formation);
		for ($playerNo = 1; $playerNo <= 11; $playerNo++) {
			if ($this->_websoccer->getRequestParameter("player" . $playerNo)) {
				$formation["player" . $playerNo] = $this->_websoccer->getRequestParameter("player" . $playerNo);
				$formation["player" . $playerNo . "_pos"] = $this->_websoccer->getRequestParameter("player" . $playerNo . "_pos"); }
			elseif (!isset($formation["player" . $playerNo])) {
				$formation["player" . $playerNo] = "";
				$formation["player" . $playerNo . "_pos"] = ""; }}
		return array("matchinfo" => $matchinfo, "players" => $players, "formation" => $formation, "setup" => $setup, "youthFormation" => TRUE); }
	function getFormationSetup($formation) {
		$setup = array("defense" => 4, "dm" => 1, "midfield" => 3, "om" => 1, "striker" => 1);
		if ($this->_websoccer->getRequestParameter("formation_defense") !== NULL) {
			$setup["defense"] = (int) $this->_websoccer->getRequestParameter("formation_defense");
			$setup["dm"] = (int) $this->_websoccer->getRequestParameter("formation_defensemidfield");
			$setup["midfield"] = (int) $this->_websoccer->getRequestParameter("formation_midfield");
			$setup["om"] = (int) $this->_websoccer->getRequestParameter("formation_offensivemidfield");
			$setup["striker"] = (int) $this->_websoccer->getRequestParameter("formation_forward"); }
		elseif ($this->_websoccer->getRequestParameter("setup") !== NULL) {
			$setupParts = explode("-", $this->_websoccer->getRequestParameter("setup"));
			$setup["defense"] = (int) $setupParts[0];
			$setup["dm"] = (int) $setupParts[1];
			$setup["midfield"] = (int) $setupParts[2];
			$setup["om"] = (int) $setupParts[3];
			$setup["striker"] = (int) $setupParts[4]; }
		elseif (isset($formation["setup"]) && strlen($formation["setup"])) {
			$setupParts = explode("-", $formation["setup"]);
			$setup["defense"] = (int) $setupParts[0];
			$setup["dm"] = (int) $setupParts[1];
			$setup["midfield"] = (int) $setupParts[2];
			$setup["om"] = (int) $setupParts[3];
			$setup["striker"] = (int) $setupParts[4]; }
		$altered = FALSE;
		while (($noOfPlayers = $setup["defense"] + $setup["dm"] + $setup["midfield"] + $setup["om"] + $setup["striker"]) != 10) {
			if ($noOfPlayers > 10) {
				if ($setup["striker"] > 1) $setup["striker"] = $setup["striker"] - 1;
				elseif ($setup["om"] > 1) $setup["om"] = $setup["om"] - 1;
				elseif ($setup["dm"] > 1) $setup["dm"] = $setup["dm"] - 1;
				elseif ($setup["midfield"] > 2) $setup["midfield"] = $setup["midfield"] - 1;
				else $setup["defense"] = $setup["defense"] - 1; }
			else {
				if ($setup["defense"] < 4) $setup["defense"] = $setup["defense"] + 1;
				elseif ($setup["midfield"] < 4) $setup["midfield"] = $setup["midfield"] + 1;
				elseif ($setup["dm"] < 2) $setup["dm"] = $setup["dm"] + 1;
				elseif ($setup["om"] < 2) $setup["om"] = $setup["om"] + 1;
				else $setup["striker"] = $setup["striker"] + 1; }
			$altered = TRUE; }
		if ($altered) $this->_websoccer->addFrontMessage(new FrontMessage(MESSAGE_TYPE_WARNING, $this->_i18n->getMessage("formation_setup_altered_warn_title"), $this->_i18n->getMessage("formation_setup_altered_warn_details")));
		return $setup; }
	function _getFormation($teamPrefix, $matchinfo) {
		$formation = array();
		for ($subNo = 1; $subNo <= 3; $subNo++) {
			if ($this->_websoccer->getRequestParameter("sub" . $subNo ."_out")) {
				$formation["sub" . $subNo ."_out"] = $this->_websoccer->getRequestParameter("sub" . $subNo ."_out");
				$formation["sub" . $subNo ."_in"] = $this->_websoccer->getRequestParameter("sub" . $subNo ."_in");
				$formation["sub" . $subNo ."_minute"] = $this->_websoccer->getRequestParameter("sub" . $subNo ."_minute");
				$formation["sub" . $subNo ."_condition"] = $this->_websoccer->getRequestParameter("sub" . $subNo ."_condition");
				$formation["sub" . $subNo ."_position"] = $this->_websoccer->getRequestParameter("sub" . $subNo ."_position"); }
			else {
				$formation["sub" . $subNo ."_out"] = $matchinfo[$teamPrefix . "_s" . $subNo ."_out"];
				$formation["sub" . $subNo ."_in"] = $matchinfo[$teamPrefix . "_s" . $subNo ."_in"];
				$formation["sub" . $subNo ."_minute"] = $matchinfo[$teamPrefix . "_s" . $subNo ."_minute"];
				$formation["sub" . $subNo ."_condition"] = $matchinfo[$teamPrefix . "_s" . $subNo ."_condition"];
				$formation["sub" . $subNo ."_position"] = $matchinfo[$teamPrefix . "_s" . $subNo ."_position"]; }}
		$setup = array("defense" => 0, "dm" => 0, "midfield" => 0, "om" => 0, "striker" => 0);
		$result = $this->_db->querySelect("*", $this->_websoccer->getConfig("db_prefix") . "_youthmatch_player", "match_id = %d AND team_id = %d", array($matchinfo["id"], $matchinfo[$teamPrefix . "_team_id"]));
		while ($player = $result->fetch_array()) {
			if ($player["state"] == "Ersatzbank") $formation["bench" . $player["playernumber"]] = $player["player_id"];
			else {
				$formation["player" . $player["playernumber"]] = $player["player_id"];
				$formation["player" . $player["playernumber"] . "_pos"] = $player["position_main"];
				$mainPosition = $player["position_main"];
				$position = $player["position"];
				if ($position == "Abwehr") $setup["defense"] = $setup["defense"] + 1;
				elseif ($position == "Sturm") $setup["striker"] = $setup["striker"] + 1;
				elseif ($position == "Mittelfeld") {
					if ($mainPosition == "DM") $setup["dm"] = $setup["dm"] + 1;
					elseif ($mainPosition == "OM") $setup["om"] = $setup["om"] + 1;
					else $setup["midfield"] = $setup["midfield"] + 1; }}}
		$setPlayers = $setup["defense"] + $setup["striker"] + $setup["dm"] + $setup["om"] + $setup["midfield"];
		if ($setPlayers > 0) $formation["setup"] = $setup["defense"] . "-" . $setup["dm"] . "-" . $setup["midfield"] . "-" . $setup["om"] . "-" . $setup["striker"];
		return $formation; }}
class YouthMatchReportModel extends Model {
	function getTemplateParameters() {
		$match = YouthMatchesDataService::getYouthMatchinfoById($this->_websoccer, $this->_db, $this->_i18n, $this->_websoccer->getRequestParameter("id"));
		$players = array();
		$statistics = array();
		$result = $this->_db->querySelect("*", $this->_websoccer->getConfig("db_prefix") . "_youthmatch_player", "match_id = %d AND minutes_played > 0 ORDER BY playernumber ASC", $match["id"]);
		while ($playerinfo = $result->fetch_array()) {
			if ($playerinfo["team_id"] == $match["home_team_id"]) $teamPrefix = "home";
			else $teamPrefix = "guest";
			if (!isset($statistics[$teamPrefix])) {
				$statistics[$teamPrefix]["avg_strength"] = 0;
				$statistics[$teamPrefix]["ballcontacts"] = 0;
				$statistics[$teamPrefix]["wontackles"] = 0;
				$statistics[$teamPrefix]["shoots"] = 0;
				$statistics[$teamPrefix]["passes_successed"] = 0;
				$statistics[$teamPrefix]["passes_failed"] = 0;
				$statistics[$teamPrefix]["assists"] = 0; }
			$players[$teamPrefix][] = $playerinfo;
			$statistics[$teamPrefix]["avg_strength"] = $statistics[$teamPrefix]["avg_strength"] + $playerinfo["strength"];
			$statistics[$teamPrefix]["ballcontacts"] = $statistics[$teamPrefix]["ballcontacts"] + $playerinfo["ballcontacts"];
			$statistics[$teamPrefix]["wontackles"] = $statistics[$teamPrefix]["wontackles"] + $playerinfo["wontackles"];
			$statistics[$teamPrefix]["shoots"] = $statistics[$teamPrefix]["shoots"] + $playerinfo["shoots"];
			$statistics[$teamPrefix]["passes_successed"] = $statistics[$teamPrefix]["passes_successed"] + $playerinfo["passes_successed"];
			$statistics[$teamPrefix]["passes_failed"] = $statistics[$teamPrefix]["passes_failed"] + $playerinfo["passes_failed"];
			$statistics[$teamPrefix]["assists"] = $statistics[$teamPrefix]["assists"] + $playerinfo["assists"]; }
		if (isset($statistics["guest"]["wontackles"]) && isset($statistics["home"]["wontackles"])) {
			$statistics["home"]["losttackles"] = $statistics["guest"]["wontackles"];
			$statistics["guest"]["losttackles"] = $statistics["home"]["wontackles"]; }
		if (isset($statistics["guest"]["avg_strength"]) && isset($statistics["home"]["avg_strength"])) {
			$statistics["home"]["avg_strength"] = round($statistics["home"]["avg_strength"] / count($players["home"]));
			$statistics["guest"]["avg_strength"] = round($statistics["guest"]["avg_strength"] / count($players["guest"])); }
		if (isset($statistics["guest"]["ballcontacts"]) && isset($statistics["home"]["ballcontacts"])) {
			$statistics["home"]["ballpossession"] = round($statistics["home"]["ballcontacts"] * 100 / ($statistics["home"]["ballcontacts"] + $statistics["guest"]["ballcontacts"]));
			$statistics["guest"]["ballpossession"] = round($statistics["guest"]["ballcontacts"] * 100 / ($statistics["home"]["ballcontacts"] + $statistics["guest"]["ballcontacts"])); }
		$reportMessages = YouthMatchesDataService::getMatchReportItems($this->_websoccer, $this->_db, $this->_i18n, $match["id"]);
		return array("match" => $match, "players" => $players, "statistics" => $statistics, "reportMessages" => $reportMessages); }
	function renderView() { return $this->_websoccer->getConfig("youth_enabled"); }}
class YouthMatchRequestsCreateModel extends Model {
	function getTemplateParameters() {
		$timeOptions = array();
		$maxDays = $this->_websoccer->getConfig("youth_matchrequest_max_futuredays");
		$times = explode(",", $this->_websoccer->getConfig("youth_matchrequest_allowedtimes"));
		$validTimes = array();
		foreach($times as $time) $validTimes[] = explode(":", $time);
		$dateOptions = array();
		$dateObj = new DateTime();
		$dateFormat = $this->_websoccer->getConfig("datetime_format");
		for ($day = 1; $day <= $maxDays; $day++) {
			$dateObj->add(new DateInterval('P1D'));
			foreach ($validTimes as $validTime) {
				$hour = $validTime[0];
				$minute = $validTime[1];
				$dateObj->setTime($hour, $minute);
				$dateOptions[$dateObj->getTimestamp()] = $dateObj->format($dateFormat); }}
		return array("dateOptions" => $dateOptions); }
	function renderView() { return $this->_websoccer->getConfig("youth_enabled"); }}
class YouthMatchRequestsModel extends Model {
	function getTemplateParameters() {
		YouthPlayersDataService::deleteInvalidOpenMatchRequests($this->_websoccer, $this->_db);
		$count = YouthPlayersDataService::countMatchRequests($this->_websoccer, $this->_db);
		$eps = $this->_websoccer->getConfig("entries_per_page");
		$paginator = new Paginator($count, $eps, $this->_websoccer);
		$requests = YouthPlayersDataService::getMatchRequests($this->_websoccer, $this->_db, $paginator->getFirstIndex(), $eps);
		return array("requests" => $requests, "paginator" => $paginator); }
	function renderView() { return $this->_websoccer->getConfig("youth_enabled") && $this->_websoccer->getConfig("youth_matchrequests_enabled"); }}
class YouthPlayerDetailsModel extends Model {
	function getTemplateParameters() {
		$playerId = (int) $this->_websoccer->getRequestParameter("id");
		if ($playerId < 1) throw new Exception($this->_i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		$player = YouthPlayersDataService::getYouthPlayerById($this->_websoccer, $this->_db, $this->_i18n, $playerId);
		return array("player" => $player); }
	function renderView() { return $this->_websoccer->getConfig("youth_enabled"); }}
class YouthPlayersOfTeamModel extends Model {
	function getTemplateParameters() {
		$teamId = $this->_websoccer->getRequestParameter("teamid");
		$players = array();
		if ($teamId > 0) $players = YouthPlayersDataService::getYouthPlayersOfTeam($this->_websoccer, $this->_db, $teamId);
		return array("players" => $players); }
	function renderView() { return $this->_websoccer->getConfig("youth_enabled"); }}
class YouthScoutingModel extends Model {
	function getTemplateParameters() {
		$lastExecutionTimestamp = YouthPlayersDataService::getLastScoutingExecutionTime($this->_websoccer, $this->_db, $this->_websoccer->getUser()->getClubId($this->_websoccer, $this->_db));
		$nextPossibleExecutionTimestamp = $lastExecutionTimestamp + $this->_websoccer->getConfig("youth_scouting_break_hours") * 3600;
		$now = $this->_websoccer->getNowAsTimestamp();
		$scouts = array();
		$countries = array();
		$scoutingPossible = ($nextPossibleExecutionTimestamp <= $now);
		if ($scoutingPossible) {
			$scoutId = (int) $this->_websoccer->getRequestParameter("scoutid");
			if ($scoutId > 0) $countries = YouthPlayersDataService::getPossibleScoutingCountries();
			else $scouts = YouthPlayersDataService::getScouts($this->_websoccer, $this->_db); }
		return array("lastExecutionTimestamp" => $lastExecutionTimestamp, "nextPossibleExecutionTimestamp" => $nextPossibleExecutionTimestamp, "scoutingPossible" => $scoutingPossible, "scouts" => $scouts, "countries" => $countries); }
	function renderView() { return $this->_websoccer->getConfig("youth_enabled") && $this->_websoccer->getConfig("youth_scouting_enabled"); }}
class AbsencesDataService {
	static function getCurrentAbsenceOfUser($websoccer, $db, $userId) {
		$result = $db->querySelect('*', $websoccer->getConfig('db_prefix') . '_userabsence', 'user_id = %d ORDER BY from_date DESC', $userId, 1);
		$absence = $result->fetch_array();
		return $absence; }
	static function makeUserAbsent($websoccer, $db, $userId, $deputyId, $days) {
		$fromDate = $websoccer->getNowAsTimestamp();
		$toDate = $fromDate + 24 * 3600 * $days;
		$db->queryInsert(array('user_id' => $userId, 'deputy_id' => $deputyId, 'from_date' => $fromDate, 'to_date' => $toDate ), $websoccer->getConfig('db_prefix') . '_userabsence');
		$db->queryUpdate(array('user_id' => $deputyId, 'user_id_actual' => $userId ), $websoccer->getConfig('db_prefix') . '_verein', 'user_id = %d', $userId);
		$user = UsersDataService::getUserById($websoccer, $db, $userId);
		NotificationsDataService::createNotification($websoccer, $db, $deputyId, 'absence_notification', array('until' => $toDate, 'user' => $user['nick']), 'absence', 'user'); }
	static function confirmComeback($websoccer, $db, $userId) {
		$absence = self::getCurrentAbsenceOfUser($websoccer, $db, $userId);
		if (!$absence) return;
		$db->queryUpdate(array('user_id' => $userId,'user_id_actual' => NULL ), $websoccer->getConfig('db_prefix') . '_verein', 'user_id_actual = %d', $userId);
		$db->queryDelete($websoccer->getConfig('db_prefix') . '_userabsence', 'user_id', $userId);
		if ($absence['deputy_id']) {
			$user = UsersDataService::getUserById($websoccer, $db, $userId);
			NotificationsDataService::createNotification($websoccer, $db, $absence['deputy_id'], 'absence_comeback_notification', array('user' => $user['nick']), 'absence', 'user'); }}}
class ActionLogDataService {
	static function getActionLogsOfUser($websoccer, $db, $userId, $limit = 10) {
		$fromTable = $websoccer->getConfig('db_prefix') . '_useractionlog AS L';
		$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') . '_user AS U ON U.id = L.user_id';
		$columns = array('L.id' => 'log_id', 'L.action_id' => 'action_id', 'L.user_id' => 'user_id', 'L.created_date' => 'created_date', 'U.nick' => 'user_name' );
		$result = $db->querySelect($columns, $fromTable, 'L.user_id = %d ORDER BY L.created_date DESC', $userId, $limit);
		$logs = array();
		while ($log = $result->fetch_array()) $logs[] = $log;
		return $logs; }
	static function getLatestActionLogs($websoccer, $db, $limit = 10) {
		$fromTable = $websoccer->getConfig('db_prefix') . '_useractionlog AS L';
		$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') . '_user AS U ON U.id = L.user_id';
		$columns = array('L.id' => 'log_id', 'L.action_id' => 'action_id', 'L.user_id' => 'user_id', 'L.created_date' => 'created_date', 'U.nick' => 'user_name' );
		$result = $db->querySelect($columns, $fromTable, '1 ORDER BY L.id DESC', null, $limit);
		$logs = array();
		while ($log = $result->fetch_array()) $logs[] = $log;
		return $logs; }
	static function createOrUpdateActionLog($websoccer, $db, $userId, $actionId) {
		$fromTable = $websoccer->getConfig('db_prefix') . '_useractionlog';
		$deleteTimeThreshold = $websoccer->getNowAsTimestamp() - 24 * 3600 * 20;
		$db->queryDelete($fromTable, 'user_id = %d AND created_date < %d', array($userId, $deleteTimeThreshold));
		$timeThreshold = $websoccer->getNowAsTimestamp() - 30 * 60;
		$result = $db->querySelect('id', $fromTable, 'user_id = %d AND action_id = \'%s\' AND created_date >= %d ORDER BY created_date DESC', array($userId, $actionId, $timeThreshold), 1);
		$lastLog = $result->fetch_array();
		if ($lastLog) $db->queryUpdate(array('created_date' => $websoccer->getNowAsTimestamp()), $fromTable, 'id = %d', $lastLog['id']);
		else $db->queryInsert(array('user_id' => $userId, 'action_id' => $actionId, 'created_date' => $websoccer->getNowAsTimestamp() ), $fromTable); }}
class BadgesDataService {
	static function awardBadgeIfApplicable($websoccer, $db, $userId, $badgeEvent, $benchmark = NULL) {
		$badgeTable = $websoccer->getConfig('db_prefix') . '_badge';
		$badgeUserTable = $websoccer->getConfig('db_prefix') . '_badge_user';
		$parameters = array($badgeEvent);
		$whereCondition = 'event = \'%s\'';
		if ($benchmark !== NULL) {
			$whereCondition .= ' AND event_benchmark <= %d';
			$parameters[] = $benchmark; }
		$whereCondition .= ' ORDER BY level DESC';
		$result = $db->querySelect('id, name, level', $badgeTable, $whereCondition, $parameters, 1);
		$badge = $result->fetch_array();
		if (!$badge) return;
		$fromTable = $badgeTable . ' INNER JOIN ' . $badgeUserTable . ' ON id = badge_id';
		$whereCondition = 'user_id = %d AND event = \'%s\' AND level >= \'%s\'';
		$result = $db->querySelect('COUNT(*) AS hits', $fromTable, $whereCondition, array($userId, $badgeEvent, $badge['level']), 1);
		$userBadges = $result->fetch_array();
		if ($userBadges && $userBadges['hits']) return;
		self::awardBadge($websoccer, $db, $userId, $badge['id']); }
	static function awardBadge($websoccer, $db, $userId, $badgeId) {
		$badgeUserTable = $websoccer->getConfig('db_prefix') . '_badge_user';
		$db->queryInsert(array('user_id' => $userId, 'badge_id' => $badgeId, 'date_rewarded' => $websoccer->getNowAsTimestamp() ), $badgeUserTable);
		NotificationsDataService::createNotification($websoccer, $db, $userId, 'badge_notification', null, 'badge', 'user', 'id=' . $userId); }}
class BankAccountDataService {
	static function countAccountStatementsOfTeam($websoccer, $db, $teamId) {
		$columns = "COUNT(*) AS hits";
		$fromTable = $websoccer->getConfig("db_prefix") . "_konto";
		$whereCondition = "verein_id = %d";
		$parameters = $teamId;
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters);
		$statements = $result->fetch_array();
		if (isset($statements["hits"])) return $statements["hits"];
		return 0; }
	static function getAccountStatementsOfTeam($websoccer, $db, $teamId, $startIndex, $entries_per_page) {
		$columns["absender"] = "sender";
		$columns["betrag"] = "amount";
		$columns["datum"] = "date";
		$columns["verwendung"] = "subject";
		$limit = $startIndex .",". $entries_per_page;
		$fromTable = $websoccer->getConfig("db_prefix") . "_konto";
		$whereCondition = "verein_id = %d ORDER BY datum DESC";
		$parameters = $teamId;
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters, $limit);
		$statements = array();
		while ($statement = $result->fetch_array()) $statements[] = $statement;
		return $statements; }
	static function creditAmount($websoccer, $db, $teamId, $amount, $subject, $sender) {
		if ($amount == 0) return;
		$team = TeamsDataService::getTeamSummaryById($websoccer, $db, $teamId);
		if (!isset($team["team_id"])) throw new Exception("team not found: " . $teamId);
		if ($amount < 0) throw new Exception("amount illegal: " . $amount);
		else self::createTransaction($websoccer, $db, $team, $teamId, $amount, $subject, $sender); }
	static function debitAmount($websoccer, $db, $teamId, $amount, $subject, $sender) {
		if ($amount == 0) return;
		$team = TeamsDataService::getTeamSummaryById($websoccer, $db, $teamId);
		if (!isset($team["team_id"])) throw new Exception("team not found: " . $teamId);
		if ($amount < 0) throw new Exception("amount illegal: " . $amount);
		$amount = 0 - $amount;
		self::createTransaction($websoccer, $db, $team, $teamId, $amount, $subject, $sender); }
	static function createTransaction($websoccer, $db, $team, $teamId, $amount, $subject, $sender) {
		if (!$team["user_id"] && $websoccer->getConfig("no_transactions_for_teams_without_user")) return;
		$fromTable = $websoccer->getConfig("db_prefix") ."_konto";
		$columns["verein_id"] = $teamId;
		$columns["absender"] = $sender;
		$columns["betrag"] = $amount;
		$columns["datum"] = $websoccer->getNowAsTimestamp();
		$columns["verwendung"] = $subject;
		$db->queryInsert($columns, $fromTable);
		$newBudget = $team["team_budget"] + $amount;
		$updateColumns["finanz_budget"] = $newBudget;
		$fromTable = $websoccer->getConfig("db_prefix") ."_verein";
		$whereCondition = "id = %d";
		$parameters = $teamId;
		$db->queryUpdate($updateColumns, $fromTable, $whereCondition, $parameters); }}
class CupsDataService {
	static function getTeamsOfCupGroupInRankingOrder($websoccer, $db, $roundId, $groupName) {
		$fromTable = $websoccer->getConfig("db_prefix") . "_cup_round_group AS G";
		$fromTable .= " INNER JOIN " . $websoccer->getConfig("db_prefix") . "_verein AS T ON T.id = G.team_id";
		$fromTable .= " LEFT JOIN " . $websoccer->getConfig("db_prefix") . "_user AS U ON U.id = T.user_id";
		$whereCondition = "G.cup_round_id = %d AND G.name = '%s'";
		$whereCondition .= "ORDER BY G.tab_points DESC, (G.tab_goals - G.tab_goalsreceived) DESC, G.tab_wins DESC, T.st_punkte DESC";
		$parameters = array($roundId, $groupName);
		$columns["T.id"] = "id";
		$columns["T.name"] = "name";
		$columns["T.user_id"] = "user_id";
		$columns["U.nick"] = "user_name";
		$columns["G.tab_points"] = "score";
		$columns["G.tab_goals"] = "goals";
		$columns["G.tab_goalsreceived"] = "goals_received";
		$columns["G.tab_wins"] = "wins";
		$columns["G.tab_draws"] = "draws";
		$columns["G.tab_losses"] = "defeats";
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters);
		$teams = array();
		while($team = $result->fetch_array()) $teams[] = $team;
		return $teams; }}
class DataGeneratorService {
	static function generateTeams($websoccer, $db, $numberOfTeams, $leagueId, $budget, $generateStadium, $stadiumNamePattern, $stadiumStands, $stadiumSeats, $stadiumStandsGrand, $stadiumSeatsGrand, $stadiumVip) {
		$result = $db->querySelect('*', $websoccer->getConfig('db_prefix') . '_liga', 'id = %d', $leagueId);
		$league = $result->fetch_array();
		if (!$league) throw new Exception('illegal league ID');
		$country = $league['land'];
		$cities = self::_getLines(FILE_CITYNAMES, $country);
		$prefixes = self::_getLines(FILE_PREFIXNAMES, $country);
		$suffixes = array();
		try {$suffixes = self::_getLines(FILE_SUFFIXNAMES, $country);}
		catch(Exception $e) {}
		for ($teamNo = 1; $teamNo <= $numberOfTeams; $teamNo++) {
			$cityName = self::_getItemFromArray($cities);
			self::_createTeam($websoccer, $db, $league, $country, $cityName, $prefixes, $suffixes, $budget, $generateStadium, $stadiumNamePattern, $stadiumStands, $stadiumSeats, $stadiumStandsGrand, $stadiumSeatsGrand, $stadiumVip); }}
	static function generatePlayers($websoccer, $db, $teamId, $age, $ageDeviation, $salary, $contractDuration, $strengths, $positions, $maxDeviation, $nationality = NULL) {
		if (strlen($nationality)) $country = $nationality;
		else {
			$fromTable = $websoccer->getConfig('db_prefix') . '_verein AS T';
			$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') . '_liga AS L ON L.id = T.liga_id';
			$result = $db->querySelect('L.land AS country', $fromTable, 'T.id = %d', $teamId);
			$league = $result->fetch_array();
			if (!$league) throw new Exception('illegal team ID');
			$country = $league['country']; }
		$firstNames = self::_getLines(FILE_FIRSTNAMES, $country);
		$lastNames = self::_getLines(FILE_LASTNAMES, $country);
		$mainPositions['T'] = 'Torwart';
		$mainPositions['LV'] = 'Abwehr';
		$mainPositions['IV'] = 'Abwehr';
		$mainPositions['RV'] = 'Abwehr';
		$mainPositions['LM'] = 'Mittelfeld';
		$mainPositions['ZM'] = 'Mittelfeld';
		$mainPositions['OM'] = 'Mittelfeld';
		$mainPositions['DM'] = 'Mittelfeld';
		$mainPositions['RM'] = 'Mittelfeld';
		$mainPositions['LS'] = 'Sturm';
		$mainPositions['MS'] = 'Sturm';
		$mainPositions['RS'] = 'Sturm';
		foreach($positions as $mainPosition => $numberOfPlayers) {
			for ($playerNo = 1; $playerNo <= $numberOfPlayers; $playerNo++) {
				$playerAge = $age + self::_getRandomDeviationValue($ageDeviation);
				$time = strtotime('-' . $playerAge . ' years', $websoccer->getNowAsTimestamp());
				$birthday = date('Y-m-d', $time);
				$firstName = self::_getItemFromArray($firstNames);
				$lastName = self::_getItemFromArray($lastNames);
				self::_createPlayer($websoccer, $db, $teamId, $firstName, $lastName, $mainPositions[$mainPosition], $mainPosition, $strengths, $country, $playerAge, $birthday, $salary, $contractDuration, $maxDeviation); }}}
	static function _getLines($fileName, $country) {
		$filePath = sprintf($fileName, $country);
		if (!file_exists($filePath)) self::_throwException('generator_err_filedoesnotexist', $filePath);
		$items = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		if (!count($items)) self::_throwException('generator_err_emptyfile', $filePath);
		return $items; }
	static function _getItemFromArray($items) {
		$itemsCount = count($items);
		if ($itemsCount) return $items[mt_rand(0, $itemsCount - 1)];
		return FALSE; }
	static function _throwException($messageKey, $parameter = null) {
		$websoccer = WebSoccer::getInstance();
		$i18n = I18n::getInstance($websoccer->getConfig('supported_languages'));
		throw new Exception($i18n->getMessage($messageKey, $parameter)); }
	static function _createTeam($websoccer, $db, $league, $country, $cityName, $prefixes, $suffixes, $budget, $generateStadium, $stadiumNamePattern, $stadiumStands, $stadiumSeats, $stadiumStandsGrand, $stadiumSeatsGrand, $stadiumVip) {
		$teamName = $cityName;
		$shortName = strtoupper(substr($cityName, 0, 3));
		if (rand(0, 1) && count($suffixes)) $teamName .= ' ' . self::_getItemFromArray($suffixes);
		else $teamName = self::_getItemFromArray($prefixes) . ' ' . $teamName;
		$stadiumId = 0;
		if ($generateStadium) {
			$stadiumName = sprintf($stadiumNamePattern, $cityName);
			$stadiumcolumns['name'] = $stadiumName;
			$stadiumcolumns['stadt'] = $cityName;
			$stadiumcolumns['land'] = $country;
			$stadiumcolumns['p_steh'] = $stadiumStands;
			$stadiumcolumns['p_sitz'] = $stadiumSeats;
			$stadiumcolumns['p_haupt_steh'] = $stadiumStandsGrand;
			$stadiumcolumns['p_haupt_sitz'] = $stadiumSeatsGrand;
			$stadiumcolumns['p_vip'] = $stadiumVip;
			$fromTable = $websoccer->getConfig('db_prefix') . '_stadion';
			$db->queryInsert($stadiumcolumns, $fromTable);
			$stadiumId = $db->getLastInsertedId(); }
		$teamcolumns['name'] = $teamName;
		$teamcolumns['kurz'] = $shortName;
		$teamcolumns['liga_id'] = $league['id'];
		$teamcolumns['stadion_id'] = $stadiumId;
		$teamcolumns['finanz_budget'] = $budget;
		$teamcolumns['preis_stehen'] = $league['preis_steh'];
		$teamcolumns['preis_sitz'] = $league['preis_sitz'];
		$teamcolumns['preis_haupt_stehen'] = $league['preis_steh'];
		$teamcolumns['preis_haupt_sitze'] = $league['preis_sitz'];
		$teamcolumns['preis_vip'] = $league['preis_vip'];
		$teamcolumns['status'] = '1';
		$fromTable = $websoccer->getConfig('db_prefix') . '_verein';
		$db->queryInsert($teamcolumns, $fromTable);
		echo '<p>' . $teamName . ' (' . $shortName . ')</p>'; }
	static function _createPlayer($websoccer, $db, $teamId, $firstName, $lastName, $position, $mainPosition, $strengths, $country, $age, $birthday, $salary, $contractDuration, $maxDeviation) {
		$columns['vorname'] = $firstName;
		$columns['nachname'] = $lastName;
		$columns['geburtstag'] = $birthday;
		$columns['age'] = $age;
		$columns['position'] = $position;
		$columns['position_main'] = $mainPosition;
		$columns['nation'] = $country;
		$columns['w_staerke'] = max(1, min(100, $strengths['strength'] + self::_getRandomDeviationValue($maxDeviation)));
		$columns['w_technik'] = max(1, min(100, $strengths['technique'] + self::_getRandomDeviationValue($maxDeviation)));
		$columns['w_kondition'] = max(1, min(100, $strengths['stamina'] + self::_getRandomDeviationValue($maxDeviation)));
		$columns['w_frische'] = max(1, min(100, $strengths['freshness'] + self::_getRandomDeviationValue($maxDeviation)));
		$columns['w_zufriedenheit'] = max(1, min(100, $strengths['satisfaction'] + self::_getRandomDeviationValue($maxDeviation)));
		$columns['vertrag_gehalt'] = $salary;
		$columns['vertrag_spiele'] = $contractDuration;
		$columns['status'] = '1';
		if ($teamId) $columns['verein_id'] = $teamId;
		else {
			$columns['transfermarkt'] = '1';
			$columns['transfer_start'] = $websoccer->getNowAsTimestamp();
			$columns['transfer_ende'] = $columns['transfer_start'] + $websoccer->getConfig('transfermarket_duration_days') * 24 * 3600; }
		$fromTable = $websoccer->getConfig('db_prefix') . '_spieler';
		$db->queryInsert($columns, $fromTable); }
	static function _getRandomDeviationValue($maxDeviation) {
		if ($maxDeviation <= 0) return 0;
		return mt_rand(0 - $maxDeviation, $maxDeviation); }}
class DirectTransfersDataService {
	static function createTransferOffer($websoccer, $db, $playerId, $senderUserId, $senderClubId, $receiverUserId, $receiverClubId, $offerAmount, $offerMessage, $offerPlayerId1 = null, $offerPlayerId2 = null) {
		$columns = array("player_id" => $playerId, "sender_user_id" => $senderUserId, "sender_club_id" => $senderClubId, "receiver_club_id" => $receiverClubId, "submitted_date" => $websoccer->getNowAsTimestamp(), "offer_amount" => $offerAmount,
			"offer_message" => $offerMessage, "offer_player1" => $offerPlayerId1, "offer_player2" => $offerPlayerId2 );
		$db->queryInsert($columns, $websoccer->getConfig("db_prefix") . "_transfer_offer");
		$sender = UsersDataService::getUserById($websoccer, $db, $senderUserId);
		NotificationsDataService::createNotification($websoccer, $db, $receiverUserId, "transferoffer_notification_offerreceived", array("sendername" => $sender["nick"]), NOTIFICATION_TYPE, NOTIFICATION_TARGETPAGE, null, $receiverClubId); }
	static function executeTransferFromOffer($websoccer, $db, $offerId) {
		$result = $db->querySelect("*", $websoccer->getConfig("db_prefix") . "_transfer_offer", "id = %d", $offerId);
		$offer = $result->fetch_array();
		if (!$offer) return;
		$currentTeam = TeamsDataService::getTeamSummaryById($websoccer, $db, $offer["receiver_club_id"]);
		$targetTeam = TeamsDataService::getTeamSummaryById($websoccer, $db, $offer["sender_club_id"]);
		self::_transferPlayer($websoccer, $db, $offer["player_id"], $offer["sender_club_id"], $offer["sender_user_id"], $currentTeam["user_id"], $offer["receiver_club_id"], $offer["offer_amount"], $offer["offer_player1"], $offer["offer_player2"]);
		BankAccountDataService::creditAmount($websoccer, $db, $offer["receiver_club_id"], $offer["offer_amount"], "directtransfer_subject", $targetTeam["team_name"]);
		BankAccountDataService::debitAmount($websoccer, $db, $offer["sender_club_id"], $offer["offer_amount"], "directtransfer_subject", $currentTeam["team_name"]);
		if ($offer["offer_player1"]) self::_transferPlayer($websoccer, $db, $offer["offer_player1"], $offer["receiver_club_id"], $currentTeam["user_id"], $targetTeam["user_id"], $offer["sender_club_id"], 0, $offer["player_id"]);
		if ($offer["offer_player2"]) self::_transferPlayer($websoccer, $db, $offer["offer_player2"], $offer["receiver_club_id"], $currentTeam["user_id"], $targetTeam["user_id"], $offer["sender_club_id"], 0, $offer["player_id"]);
		$db->queryDelete($websoccer->getConfig("db_prefix") . "_transfer_offer", "player_id = %d", $offer["player_id"]);
		$player = PlayersDataService::getPlayerById($websoccer, $db, $offer["player_id"]);
		if ($player["player_pseudonym"]) $playerName = $player["player_pseudonym"];
		else $playerName = $player["player_firstname"] . " " . $player["player_lastname"];
		NotificationsDataService::createNotification($websoccer, $db, $currentTeam["user_id"], "transferoffer_notification_executed", array("playername" => $playerName), NOTIFICATION_TYPE, "player", "id=" . $offer["player_id"], $currentTeam["team_id"]);
		NotificationsDataService::createNotification($websoccer, $db, $offer["sender_user_id"], "transferoffer_notification_executed", array("playername" => $playerName), NOTIFICATION_TYPE, "player", "id=" . $offer["player_id"], $targetTeam['team_id']);
		TransfermarketDataService::awardUserForTrades($websoccer, $db, $currentTeam["user_id"]);
		TransfermarketDataService::awardUserForTrades($websoccer, $db, $offer["sender_user_id"]); }
	static function _transferPlayer($websoccer, $db, $playerId, $targetClubId, $targetUserId, $currentUserId, $currentClubId, $amount, $exchangePlayer1 = 0, $exchangePlayer2 = 0) {
		$db->queryUpdate(array("verein_id" => $targetClubId, "vertrag_spiele" => $websoccer->getConfig("transferoffers_contract_matches")), $websoccer->getConfig("db_prefix") . "_spieler", "id = %d", $playerId);
		$db->queryInsert(array("bid_id" => 0, "datum" => $websoccer->getNowAsTimestamp(), "spieler_id" => $playerId, "seller_user_id" => $currentUserId, "seller_club_id" => $currentClubId, "buyer_user_id" => $targetUserId, "buyer_club_id" => $targetClubId,
			"directtransfer_amount" => $amount, "directtransfer_player1" => $exchangePlayer1, "directtransfer_player2" => $exchangePlayer2 ), $websoccer->getConfig("db_prefix") . "_transfer"); }
	static function countReceivedOffers($websoccer, $db, $clubId) {
		$columns = "COUNT(*) AS hits";
		$fromTable = $websoccer->getConfig("db_prefix") . "_transfer_offer";
		$whereCondition = "receiver_club_id = %d AND (rejected_date = 0 OR admin_approval_pending = '1')";
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $clubId);
		$players = $result->fetch_array();
		if (isset($players["hits"])) return $players["hits"];
		return 0; }
	static function getReceivedOffers($websoccer, $db, $startIndex, $entries_per_page, $clubId) {
		$whereCondition = "O.receiver_club_id = %d AND (O.rejected_date = 0 OR O.admin_approval_pending = '1')";
		$parameters = array($clubId);
		return self::_queryOffers($websoccer, $db, $startIndex, $entries_per_page, $whereCondition, $parameters); }
	static function countSentOffers($websoccer, $db, $clubId, $userId) {
		$columns = "COUNT(*) AS hits";
		$fromTable = $websoccer->getConfig("db_prefix") . "_transfer_offer";
		$whereCondition = "sender_club_id = %d AND sender_user_id = %d";
		$result = $db->querySelect($columns, $fromTable, $whereCondition, array($clubId, $userId));
		$players = $result->fetch_array();
		if (isset($players["hits"])) return $players["hits"];
		return 0; }
	static function getSentOffers($websoccer, $db, $startIndex, $entries_per_page, $clubId, $userId) {
		$whereCondition = "O.sender_club_id = %d AND O.sender_user_id = %d";
		$parameters = array($clubId, $userId);
		return self::_queryOffers($websoccer, $db, $startIndex, $entries_per_page, $whereCondition, $parameters); }
	static function _queryOffers($websoccer, $db, $startIndex, $entries_per_page, $whereCondition, $parameters) {
		$columns = array("O.id" => "offer_id", "O.submitted_date" => "offer_submitted_date", "O.offer_amount" => "offer_amount", "O.offer_message" => "offer_message", "O.rejected_date" => "offer_rejected_date", "O.rejected_message" => "offer_rejected_message",
			"O.rejected_allow_alternative" => "offer_rejected_allow_alternative", "O.admin_approval_pending" => "offer_admin_approval_pending", "P.id" => "player_id", "P.vorname" => "player_firstname", "P.nachname" => "player_lastname",
			"P.kunstname" => "player_pseudonym", "P.vertrag_gehalt" => "player_salary", "P.marktwert" => "player_marketvalue", "P.w_staerke" => "player_strength", "P.w_technik" => "player_strength_technique", "P.w_kondition" => "player_strength_stamina",
			"P.w_frische" => "player_strength_freshness", "P.w_zufriedenheit" => "player_strength_satisfaction", "P.position_main" => "player_position_main", "SU.id" => "sender_user_id", "SU.nick" => "sender_user_name", "SC.id" => "sender_club_id",
			"SC.name" => "sender_club_name", "RU.id" => "receiver_user_id", "RU.nick" => "receiver_user_name", "RC.id" => "receiver_club_id", "RC.name" => "receiver_club_name", "EP1.id" => "explayer1_id", "EP1.vorname" => "explayer1_firstname",
			"EP1.nachname" => "explayer1_lastname", "EP1.kunstname" => "explayer1_pseudonym", "EP2.id" => "explayer2_id", "EP2.vorname" => "explayer2_firstname", "EP2.nachname" => "explayer2_lastname", "EP2.kunstname" => "explayer2_pseudonym" );
		$fromTable = $websoccer->getConfig("db_prefix") . "_transfer_offer AS O";
		$fromTable .= " INNER JOIN " . $websoccer->getConfig("db_prefix") . "_spieler AS P ON P.id = O.player_id";
		$fromTable .= " INNER JOIN " . $websoccer->getConfig("db_prefix") . "_user AS SU ON SU.id = O.sender_user_id";
		$fromTable .= " INNER JOIN " . $websoccer->getConfig("db_prefix") . "_verein AS SC ON SC.id = O.sender_club_id";
		$fromTable .= " INNER JOIN " . $websoccer->getConfig("db_prefix") . "_verein AS RC ON RC.id = O.receiver_club_id";
		$fromTable .= " INNER JOIN " . $websoccer->getConfig("db_prefix") . "_user AS RU ON RU.id = RC.user_id";
		$fromTable .= " LEFT JOIN " . $websoccer->getConfig("db_prefix") . "_spieler AS EP1 ON EP1.id = O.offer_player1";
		$fromTable .= " LEFT JOIN " . $websoccer->getConfig("db_prefix") . "_spieler AS EP2 ON EP2.id = O.offer_player2";
		$whereCondition .= " ORDER BY O.submitted_date DESC";
		$limit = $startIndex .",". $entries_per_page;
		$offers = array();
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters, $limit);
		while ($offer = $result->fetch_array()) {
			$offer["player_marketvalue"] = PlayersDataService::getMarketValue($websoccer, $offer);
			$offers[] = $offer; }
		return $offers; }}
class FormationDataService {
	static function getFormationByTeamId($websoccer, $db, $teamId, $matchId) {
		$whereCondition = 'verein_id = %d AND match_id = %d';
		$parameters = array($teamId, $matchId);
		return self::_getFormationByCondition($websoccer, $db, $whereCondition, $parameters); }
	static function getFormationByTemplateId($websoccer, $db, $teamId, $templateId) {
		$whereCondition = 'id = %d AND verein_id = %d';
		$parameters = array($templateId, $teamId);
		return self::_getFormationByCondition($websoccer, $db, $whereCondition, $parameters); }
	static function _getFormationByCondition($websoccer, $db, $whereCondition, $parameters) {
		$fromTable = $websoccer->getConfig('db_prefix') . '_aufstellung';
		$columns['id'] = 'id';
		$columns['offensive'] = 'offensive';
		$columns['setup'] = 'setup';
		$columns['longpasses'] = 'longpasses';
		$columns['counterattacks'] = 'counterattacks';
		$columns['freekickplayer'] = 'freekickplayer';
		for ($playerNo = 1; $playerNo <= 11; $playerNo++) {
			$columns['spieler' . $playerNo] = 'player' . $playerNo;
			$columns['spieler' . $playerNo . '_position'] = 'player' . $playerNo . '_pos'; }
		for ($playerNo = 1; $playerNo <= 5; $playerNo++) $columns['ersatz' . $playerNo] = 'bench' . $playerNo;
		for ($subNo = 1; $subNo <= 3; $subNo++) {
			$columns['w'. $subNo . '_raus'] = 'sub' . $subNo .'_out';
			$columns['w'. $subNo . '_rein'] = 'sub' . $subNo .'_in';
			$columns['w'. $subNo . '_minute'] = 'sub' . $subNo .'_minute';
			$columns['w'. $subNo . '_condition'] = 'sub' . $subNo .'_condition';
			$columns['w'. $subNo . '_position'] = 'sub' . $subNo .'_position'; }
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters, 1);
		$formation = $result->fetch_array();
		if (!$formation) $formation = array();
		return $formation; }
	static function getFormationProposalForTeamId($websoccer, $db, $teamId, $setupDefense, $setupDM, $setupMidfield, $setupOM, $setupStriker, $setupOutsideforward, $sortColumn, $sortDirection = 'DESC',
		$isNationalteam = FALSE, $isCupMatch = FALSE) {
		$columns = 'id,position,position_main,position_second';
		if (!$isNationalteam) {
			$fromTable = $websoccer->getConfig('db_prefix') . '_spieler';
			$whereCondition = 'verein_id = %d AND gesperrt';
			if ($isCupMatch) $whereCondition .= '_cups';
			$whereCondition .= ' = 0 AND verletzt = 0 AND status = 1'; }
		else {
			$fromTable = $websoccer->getConfig('db_prefix') . '_spieler AS P';
			$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') . '_nationalplayer AS NP ON NP.player_id = P.id';
			$whereCondition = 'NP.team_id = %d AND gesperrt_nationalteam = 0 AND verletzt = 0 AND status = 1'; }
		$whereCondition .=	' ORDER BY '. $sortColumn . ' ' . $sortDirection;
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $teamId);
		$openPositions['T'] = 1;
		if ($setupDefense < 4) {
			$openPositions['IV'] = $setupDefense;
			$openPositions['LV'] = 0;
			$openPositions['RV'] = 0; }
		else {
			$openPositions['LV'] = 1;
			$openPositions['RV'] = 1;
			$openPositions['IV'] = $setupDefense - 2; }
		$openPositions['DM'] = $setupDM;
		$openPositions['OM'] = $setupOM;
		if ($setupMidfield == 1) {
			$openPositions['ZM'] = 1;}
		elseif ($setupMidfield == 2) {
			$openPositions['LM'] = 1;
			$openPositions['RM'] = 1; }
		elseif ($setupMidfield == 3) {
			$openPositions['LM'] = 1;
			$openPositions['ZM'] = 1;
			$openPositions['RM'] = 1; }
		elseif ($setupMidfield >= 4) {
			$openPositions['LM'] = 1;
			$openPositions['ZM'] = $setupMidfield - 2;
			$openPositions['RM'] = 1; }
		else {
			$openPositions['LM'] = 0;
			$openPositions['ZM'] = 0;
			$openPositions['RM'] = 0; }
		$openPositions['MS'] = $setupStriker;
		if ($setupOutsideforward == 2) {
			$openPositions['LS'] = 1;
			$openPositions['RS'] = 1; }
		else {
			$openPositions['LS'] = 0;
			$openPositions['RS'] = 0; }
		$players = array();
		$unusedPlayers = array();
		while ($player = $result->fetch_array()) {
			$added = FALSE;
			if (!strlen($player['position_main'])) {
				if ($player['position'] == 'Torwart') $possiblePositions = array('T');
				elseif ($player['position'] == 'Abwehr') $possiblePositions = array('LV', 'IV', 'RV');
				elseif ($player['position'] == 'Mittelfeld') $possiblePositions = array('RM', 'ZM', 'LM', 'RM', 'DM', 'OM');
				else $possiblePositions = array('LS', 'MS', 'RS');
				foreach($possiblePositions as $possiblePosition) {
					if ($openPositions[$possiblePosition]) {
						$openPositions[$possiblePosition] = $openPositions[$possiblePosition] - 1;
						$players[] = array('id' => $player['id'], 'position' => $possiblePosition);
						$added = TRUE;
						break; }}}
			elseif (strlen($player['position_main']) && isset($openPositions[$player['position_main']]) && $openPositions[$player['position_main']]) {
				$openPositions[$player['position_main']] = $openPositions[$player['position_main']] - 1;
				$players[] = array('id' => $player['id'], 'position' => $player['position_main']);
				$added = TRUE; }
			if (!$added && strlen($player['position_second'])) $unusedPlayers[] = $player; }
		foreach ($openPositions as $position => $requiredPlayers) {
			for ($i = 0; $i < $requiredPlayers; $i++) {
				for ($playerIndex = 0; $playerIndex < count($unusedPlayers); $playerIndex++) {
					if ($unusedPlayer[$playerIndex]['position_second'] == $position) {
						$players[] = array('id' => $unusedPlayer[$playerIndex]['id'], 'position' => $unusedPlayer[$playerIndex]['position_second']);
						unset($unusedPlayer[$playerIndex]);
						break; }}}}
		return $players; }}
class LeagueDataService {
	static function getLeagueById($websoccer, $db, $leagueId) {
		$fromTable = $websoccer->getConfig("db_prefix") . "_liga AS L";
		$whereCondition = "L.id = %d";
		$parameters = $leagueId;
		$columns["L.id"] = "league_id";
		$columns["L.name"] = "league_name";
		$columns["L.kurz"] = "league_short";
		$columns["L.land"] = "league_country";
		$leagueinfos = $db->queryCachedSelect($columns, $fromTable, $whereCondition, $parameters, 1);
		$league = (isset($leagueinfos[0])) ? $leagueinfos[0] : array();
		return $league; }
	static function getLeaguesSortedByCountry($websoccer, $db) {
		$fromTable = $websoccer->getConfig("db_prefix") . "_liga AS L";
		$whereCondition = "1 ORDER BY league_country ASC, league_name ASC";
		$columns["L.id"] = "league_id";
		$columns["L.name"] = "league_name";
		$columns["L.kurz"] = "league_short";
		$columns["L.land"] = "league_country";
		return $db->queryCachedSelect($columns, $fromTable, $whereCondition); }
	static function countTotalLeagues($websoccer, $db) {
		$result = $db->querySelect("COUNT(*) AS hits", $websoccer->getConfig("db_prefix") . "_liga", "1=1");
		$leagues = $result->fetch_array();
		if (isset($leagues["hits"])) return $leagues["hits"];
		return 0; }}
class MatchesDataService {
	static function getNextMatches($websoccer, $db, $clubId, $maxNumber) {
		$fromTable = self::_getFromPart($websoccer);
		$whereCondition = 'M.berechnet != \'1\' AND (HOME.id = %d OR GUEST.id = %d) AND M.datum > %d ORDER BY M.datum ASC';
		$parameters = array($clubId, $clubId, $websoccer->getNowAsTimestamp());
		$columns['M.id'] = 'match_id';
		$columns['M.datum'] = 'match_date';
		$columns['M.spieltyp'] = 'match_type';
		$columns['HOME.id'] = 'match_home_id';
		$columns['HOME.name'] = 'match_home_name';
		$columns['GUEST.id'] = 'match_guest_id';
		$columns['GUEST.name'] = 'match_guest_name';
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters, $maxNumber);
		$matches = array();
		while ($match = $result->fetch_array()) {
			$match['match_type'] = self::_convertLeagueType($match['match_type']);
			$matches[] = $match; }
	return $matches; }
	static function getNextMatch($websoccer, $db, $clubId) {
		$fromTable = self::_getFromPart($websoccer);
		$formationTable = $websoccer->getConfig('db_prefix') . '_aufstellung';
		$fromTable .= ' LEFT JOIN ' . $formationTable . ' AS HOME_F ON HOME_F.verein_id = HOME.id AND HOME_F.match_id = M.id';
		$fromTable .= ' LEFT JOIN ' . $formationTable . ' AS GUEST_F ON GUEST_F.verein_id = GUEST.id AND GUEST_F.match_id = M.id';
		$whereCondition = 'M.berechnet != \'1\' AND (HOME.id = %d OR GUEST.id = %d) AND M.datum > %d ORDER BY M.datum ASC';
		$parameters = array($clubId, $clubId, $websoccer->getNowAsTimestamp());
		$columns['M.id'] = 'match_id';
		$columns['M.datum'] = 'match_date';
		$columns['M.spieltyp'] = 'match_type';
		$columns['HOME.id'] = 'match_home_id';
		$columns['HOME.name'] = 'match_home_name';
		$columns['HOME_F.id'] = 'match_home_formation_id';
		$columns['GUEST.id'] = 'match_guest_id';
		$columns['GUEST.name'] = 'match_guest_name';
		$columns['GUEST_F.id'] = 'match_guest_formation_id';
		$matchinfos = $db->queryCachedSelect($columns, $fromTable, $whereCondition, $parameters, 1);
		if (!count($matchinfos)) $matchinfo = array();
		else {
			$matchinfo = $matchinfos[0];
			$matchinfo['match_type'] = self::_convertLeagueType($matchinfo['match_type']); }
		return $matchinfo; }
	static function getLiveMatch($websoccer, $db) {
		$fromTable = self::_getFromPart($websoccer);
		$whereCondition = 'M.berechnet != \'1\' AND M.minutes > 0 AND (HOME.user_id = %d OR GUEST.user_id = %d) AND M.datum < %d ORDER BY M.datum DESC';
		$parameters = array($websoccer->getUser()->id, $websoccer->getUser()->id, $websoccer->getNowAsTimestamp());
		$columns['M.id'] = 'match_id';
		$columns['M.datum'] = 'match_date';
		$columns['M.spieltyp'] = 'match_type';
		$columns['HOME.id'] = 'match_home_id';
		$columns['HOME.name'] = 'match_home_name';
		$columns['GUEST.id'] = 'match_guest_id';
		$columns['GUEST.name'] = 'match_guest_name';
		$matchinfos = $db->queryCachedSelect($columns, $fromTable, $whereCondition, $parameters, 1);
		if (!count($matchinfos)) $matchinfo = array();
		else {
			$matchinfo = $matchinfos[0];
			$matchinfo['match_type'] = self::_convertLeagueType($matchinfo['match_type']); }
		return $matchinfo; }
	static function getMatchById($websoccer, $db, $matchId, $loadStadiumInfo = TRUE, $loadSeasonInfo = FALSE) {
		$fromTable = self::_getFromPart($websoccer);
		if ($loadStadiumInfo) {
			$fromTable .= ' LEFT JOIN '. $websoccer->getConfig('db_prefix') . '_stadion AS S ON  S.id = IF(M.stadion_id IS NOT NULL, M.stadion_id, HOME.stadion_id)';
			$columns['S.name'] = 'match_stadium_name'; }
		if ($loadSeasonInfo) {
			$fromTable .= ' LEFT JOIN '. $websoccer->getConfig('db_prefix') . '_saison AS SEASON ON SEASON.id = M.saison_id';
			$columns['SEASON.name'] = 'match_season_name';
			$columns['SEASON.liga_id'] = 'match_league_id'; }
		$whereCondition = 'M.id = %d';
		$parameters = $matchId;
		$columns['M.id'] = 'match_id';
		$columns['M.datum'] = 'match_date';
		$columns['M.spieltyp'] = 'match_type';
		$columns['HOME.id'] = 'match_home_id';
		$columns['HOME.name'] = 'match_home_name';
		$columns['HOME.nationalteam'] = 'match_home_nationalteam';
		$columns['HOME.bild'] = 'match_home_picture';
		$columns['GUEST.id'] = 'match_guest_id';
		$columns['GUEST.name'] = 'match_guest_name';
		$columns['GUEST.nationalteam'] = 'match_guest_nationalteam';
		$columns['GUEST.bild'] = 'match_guest_picture';
		$columns['M.pokalname'] = 'match_cup_name';
		$columns['M.pokalrunde'] = 'match_cup_round';
		$columns['M.spieltag'] = 'match_matchday';
		$columns['M.saison_id'] = 'match_season_id';
		$columns['M.berechnet'] = 'match_simulated';
		$columns['M.home_tore'] = 'match_goals_home';
		$columns['M.gast_tore'] = 'match_goals_guest';
		$columns['M.bericht'] = 'match_deprecated_report';
		$columns['M.minutes'] = 'match_minutes';
		$columns['M.home_noformation'] = 'match_home_noformation';
		$columns['M.guest_noformation'] = 'match_guest_noformation';
		$columns['M.zuschauer'] = 'match_audience';
		$columns['M.soldout'] = 'match_soldout';
		$columns['M.elfmeter'] = 'match_penalty_enabled';
		$columns['M.home_offensive'] = 'match_home_offensive';
		$columns['M.gast_offensive'] = 'match_guest_offensive';
		$columns['M.home_longpasses'] = 'match_home_longpasses';
		$columns['M.gast_longpasses'] = 'match_guest_longpasses';
		$columns['M.home_counterattacks'] = 'match_home_counterattacks';
		$columns['M.gast_counterattacks'] = 'match_guest_counterattacks';
		for ($subNo = 1; $subNo <= 3; $subNo++) {
			$columns['M.home_w' . $subNo . '_raus'] = 'match_home_sub' . $subNo . '_out';
			$columns['M.home_w' . $subNo . '_rein'] = 'match_home_sub' . $subNo . '_in';
			$columns['M.home_w' . $subNo . '_minute'] = 'match_home_sub' . $subNo . '_minute';
			$columns['M.home_w' . $subNo . '_condition'] = 'match_home_sub' . $subNo . '_condition';
			$columns['M.gast_w' . $subNo . '_raus'] = 'match_guest_sub' . $subNo . '_out';
			$columns['M.gast_w' . $subNo . '_rein'] = 'match_guest_sub' . $subNo . '_in';
			$columns['M.gast_w' . $subNo . '_minute'] = 'match_guest_sub' . $subNo . '_minute';
			$columns['M.gast_w' . $subNo . '_condition'] = 'match_guest_sub' . $subNo . '_condition'; }
		$matchinfos = $db->queryCachedSelect($columns, $fromTable, $whereCondition, $parameters, 1);
		$match = (isset($matchinfos[0])) ? $matchinfos[0] : array();
		if (isset($match['match_type'])) $match['match_type'] = self::_convertLeagueType($match['match_type']);
		return $match; }
	static function getMatchSubstitutionsById($websoccer, $db, $matchId) {
		$fromTable = $websoccer->getConfig('db_prefix') . '_spiel AS M';
		$whereCondition = 'M.id = %d';
		$parameters = $matchId;
		$columns['M.id'] = 'match_id';
		$columns['M.home_verein'] = 'match_home_id';
		$columns['M.gast_verein'] = 'match_guest_id';
		$columns['M.berechnet'] = 'match_simulated';
		$columns['M.minutes'] = 'match_minutes';
		$columns['M.home_offensive'] = 'match_home_offensive';
		$columns['M.home_offensive_changed'] = 'match_home_offensive_changed';
		$columns['M.home_longpasses'] = 'match_home_longpasses';
		$columns['M.home_counterattacks'] = 'match_home_counterattacks';
		$columns['M.home_freekickplayer'] = 'match_home_freekickplayer';
		$columns['M.gast_offensive_changed'] = 'match_guest_offensive_changed';
		$columns['M.gast_offensive'] = 'match_guest_offensive';
		$columns['M.gast_longpasses'] = 'match_guest_longpasses';
		$columns['M.gast_counterattacks'] = 'match_guest_counterattacks';
		$columns['M.gast_freekickplayer'] = 'match_guest_freekickplayer';
		for ($subNo = 1; $subNo <= 3; $subNo++) {
			$columns['M.home_w'. $subNo . '_raus'] = 'home_sub'. $subNo . '_out';
			$columns['M.home_w'. $subNo . '_rein'] = 'home_sub'. $subNo . '_in';
			$columns['M.home_w'. $subNo . '_minute'] = 'home_sub'. $subNo . '_minute';
			$columns['M.home_w'. $subNo . '_condition'] = 'home_sub'. $subNo . '_condition';
			$columns['M.home_w'. $subNo . '_position'] = 'home_sub'. $subNo . '_position';
			$columns['M.gast_w'. $subNo . '_raus'] = 'guest_sub'. $subNo . '_out';
			$columns['M.gast_w'. $subNo . '_rein'] = 'guest_sub'. $subNo . '_in';
			$columns['M.gast_w'. $subNo . '_minute'] = 'guest_sub'. $subNo . '_minute';
			$columns['M.gast_w'. $subNo . '_condition'] = 'guest_sub'. $subNo . '_condition';
			$columns['M.gast_w'. $subNo . '_position'] = 'guest_sub'. $subNo . '_position'; }
		$matchinfos = $db->queryCachedSelect($columns, $fromTable, $whereCondition, $parameters, 1);
		$match = (isset($matchinfos[0])) ? $matchinfos[0] : array();
		return $match; }
	static function getLastMatch($websoccer, $db) {
		$whereCondition = 'M.berechnet = 1 AND (HOME.user_id = %d OR GUEST.user_id = %d) AND M.datum < %d ORDER BY M.datum DESC';
		$parameters = array($websoccer->getUser()->id, $websoccer->getUser()->id, $websoccer->getNowAsTimestamp());
		return self::_getMatchSummaryByCondition($websoccer, $db, $whereCondition, $parameters); }
	static function getLiveMatchByTeam($websoccer, $db, $teamId) {
		$whereCondition = 'M.berechnet != 1 AND (HOME.id = %d OR GUEST.id = %d) AND M.minutes > 0 ORDER BY M.datum DESC';
		$parameters = array($teamId, $teamId);
		return self::_getMatchSummaryByCondition($websoccer, $db, $whereCondition, $parameters); }
	static function _getMatchSummaryByCondition($websoccer, $db, $whereCondition, $parameters) {
		$fromTable = self::_getFromPart($websoccer);
		$columns['M.id'] = 'match_id';
		$columns['M.datum'] = 'match_date';
		$columns['M.spieltyp'] = 'match_type';
		$columns['HOME.id'] = 'match_home_id';
		$columns['HOME.name'] = 'match_home_name';
		$columns['GUEST.id'] = 'match_guest_id';
		$columns['GUEST.name'] = 'match_guest_name';
		$columns['M.home_tore'] = 'match_goals_home';
		$columns['M.gast_tore'] = 'match_goals_guest';
		$matchinfos = $db->queryCachedSelect($columns, $fromTable, $whereCondition, $parameters, 1);
		if (!count($matchinfos)) $matchinfo = array();
		else {
			$matchinfo = $matchinfos[0];
			$matchinfo['match_type'] = self::_convertLeagueType($matchinfo['match_type']); }
		return $matchinfo; }
	static function getPreviousMatches($matchinfo, $websoccer, $db) {
		$fromTable = self::_getFromPart($websoccer);
		$whereCondition = 'M.berechnet = 1 AND (HOME.id = %d AND GUEST.id = %d OR HOME.id = %d AND GUEST.id = %d) ORDER BY M.datum DESC';
		$parameters = array($matchinfo['match_home_id'], $matchinfo['match_guest_id'], $matchinfo['match_guest_id'], $matchinfo['match_home_id']);
		$columns['M.id'] = 'id';
		$columns['HOME.name'] = 'home_team';
		$columns['GUEST.name'] = 'guest_team';
		$columns['M.home_tore'] = 'home_goals';
		$columns['M.gast_tore'] = 'guest_goals';
		$matches = array();
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters, 4);
		while ($matchinfo = $result->fetch_array()) $matches[] = $matchinfo;
		return $matches; }
	static function getCupRoundsByCupname($websoccer, $db) {
		$columns['C.name'] = 'cup';
		$columns['R.name'] = 'round';
		$columns['R.firstround_date'] = 'round_date';
		$fromTable = $websoccer->getConfig('db_prefix') . '_cup_round AS R ';
		$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') . '_cup AS C ON C.id = R.cup_id';
		$result = $db->querySelect($columns, $fromTable, 'archived != \'1\' ORDER BY cup ASC, round_date ASC');
		$cuprounds = array();
		while ($cup = $result->fetch_array()) $cuprounds[$cup['cup']][] = $cup['round'];
		return $cuprounds; }
	static function getMatchesByMatchday($websoccer, $db, $seasonId, $matchDay) {
		$whereCondition = 'M.saison_id = %d AND M.spieltag = %d  ORDER BY M.datum ASC';
		$parameters = array($seasonId, $matchDay);
		return self::getMatchesByCondition($websoccer, $db, $whereCondition, $parameters, 50); }
	static function getMatchesByCupRound($websoccer, $db, $cupName, $cupRound) {
		$whereCondition = 'M.pokalname = \'%s\' AND M.pokalrunde = \'%s\'  ORDER BY M.datum ASC';
		$parameters = array($cupName, $cupRound);
		return self::getMatchesByCondition($websoccer, $db, $whereCondition, $parameters, 50); }
	static function getMatchesByCupRoundAndGroup($websoccer, $db, $cupName, $cupRound, $cupGroup) {
		$whereCondition = 'M.pokalname = \'%s\' AND M.pokalrunde = \'%s\' AND M.pokalgruppe = \'%s\' ORDER BY M.datum ASC';
		$parameters = array($cupName, $cupRound, $cupGroup);
		return self::getMatchesByCondition($websoccer, $db, $whereCondition, $parameters, 50); }
	static function getLatestMatches($websoccer, $db, $limit = 20, $ignoreFriendlies = FALSE) {
		$whereCondition = 'M.berechnet = 1';
		if ($ignoreFriendlies) $whereCondition .= ' AND M.spieltyp != \'Freundschaft\'';
		$whereCondition .= ' ORDER BY M.datum DESC';
		$parameters = array();
		return self::getMatchesByCondition($websoccer, $db, $whereCondition, $parameters, $limit); }
	static function getLatestMatchesByUser($websoccer, $db, $userId) {
		$whereCondition = 'M.berechnet = 1 AND (M.home_user_id = %d OR M.gast_user_id = %d) ORDER BY M.datum DESC';
		$parameters = array($userId, $userId);
		return self::getMatchesByCondition($websoccer, $db, $whereCondition, $parameters, 20); }
	static function getLatestMatchesByTeam($websoccer, $db, $teamId) {
		$whereCondition = 'M.berechnet = 1 AND (HOME.id = %d OR GUEST.id = %d) ORDER BY M.datum DESC';
		$parameters = array($teamId, $teamId);
		return self::getMatchesByCondition($websoccer, $db, $whereCondition, $parameters, 20); }
	static function getTodaysMatches($websoccer, $db, $startIndex, $entries_per_page) {
		$startTs = mktime (0, 0, 1, date('n'), date('j'), date('Y'));
		$endTs = $startTs + 3600 * 24;
		$whereCondition = 'M.datum >= %d AND M.datum < %d ORDER BY M.datum ASC';
		$parameters = array($startTs, $endTs);
		$limit = $startIndex .','. $entries_per_page;
		return self::getMatchesByCondition($websoccer, $db, $whereCondition, $parameters, $limit); }
	static function countTodaysMatches($websoccer, $db) {
		$startTs = mktime (0, 0, 1, date('n'), date('j'), date('Y'));
		$endTs = $startTs + 3600 * 24;
		$whereCondition = 'M.datum >= %d AND M.datum < %d';
		$parameters = array($startTs, $endTs);
		$result = $db->querySelect('COUNT(*) AS hits', $websoccer->getConfig('db_prefix') . '_spiel AS M', $whereCondition, $parameters);
		$matches = $result->fetch_array();
		if ($matches) return $matches['hits'];
		return 0; }
	static function getMatchesByTeamAndTimeframe($websoccer, $db, $teamId, $dateStart, $dateEnd) {
		$whereCondition = '(HOME.id = %d OR GUEST.id = %d) AND datum >= %d AND datum <= %d ORDER BY M.datum DESC';
		$parameters = array($teamId, $teamId, $dateStart, $dateEnd);
		return self::getMatchesByCondition($websoccer, $db, $whereCondition, $parameters, 20); }
	static function getMatchdayNumberOfTeam($websoccer, $db, $teamId) {
		$columns = 'spieltag AS matchday';
		$fromTable = $websoccer->getConfig('db_prefix') . '_spiel';
		$whereCondition = 'spieltyp = \'Ligaspiel\' AND berechnet = 1 AND (home_verein = %d OR gast_verein = %d) ORDER BY datum DESC';
		$parameters = array($teamId, $teamId);
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters, 1);
		$matches = $result->fetch_array();
		if ($matches) return (int) $matches['matchday'];
		return 0; }
	static function getMatchReportPlayerRecords($websoccer, $db, $matchId, $teamId) {
		$fromTable = $websoccer->getConfig('db_prefix') . '_spiel_berechnung AS M';
		$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') . '_spieler AS P ON P.id = M.spieler_id';
		$columns['P.id'] = 'id';
		$columns['P.vorname'] = 'firstName';
		$columns['P.nachname'] = 'lastName';
		$columns['P.kunstname'] = 'pseudonym';
		$columns['P.position'] = 'position';
		$columns['M.position_main'] = 'position_main';
		$columns['M.note'] = 'grade';
		$columns['M.tore'] = 'goals';
		$columns['M.verletzt'] = 'injured';
		$columns['M.gesperrt'] = 'blocked';
		$columns['M.karte_gelb'] = 'yellowCards';
		$columns['M.karte_rot'] = 'redCard';
		$columns['M.feld'] = 'playstatus';
		$columns['M.minuten_gespielt'] = 'minutesPlayed';
		$columns['M.assists'] = 'assists';
		$columns['M.ballcontacts'] = 'ballcontacts';
		$columns['M.wontackles'] = 'wontackles';
		$columns['M.losttackles'] = 'losttackles';
		$columns['M.shoots'] = 'shoots';
		$columns['M.passes_successed'] = 'passes_successed';
		$columns['M.passes_failed'] = 'passes_failed';
		$columns['M.age'] = 'age';
		$columns['M.w_staerke'] = 'strength';
		$order = 'field(M.position_main, \'T\', \'LV\', \'IV\', \'RV\', \'DM\', \'LM\', \'ZM\', \'RM\', \'OM\', \'LS\', \'MS\', \'RS\')';
		$whereCondition = 'M.spiel_id = %d AND M.team_id = %d AND M.feld != \'Ersatzbank\' ORDER BY ' . $order . ', M.id ASC';
		$parameters = array($matchId, $teamId);
		$players = $db->queryCachedSelect($columns, $fromTable, $whereCondition, $parameters);
		return $players; }
	static function getMatchPlayerRecordsByField($websoccer, $db, $matchId, $teamId) {
		$fromTable = $websoccer->getConfig('db_prefix') . '_spiel_berechnung AS M';
		$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') . '_spieler AS P ON P.id = M.spieler_id';
		$columns = array('P.id' => 'id', 'P.vorname' => 'firstname', 'P.nachname' => 'lastname', 'P.kunstname' => 'pseudonym', 'P.verletzt' => 'matches_injured', 'P.position' => 'position', 'P.position_main' => 'position_main', 'P.position_second' => 'position_second',
			'P.w_staerke' => 'strength', 'P.w_technik' => 'strength_technique', 'P.w_kondition' => 'strength_stamina', 'P.w_frische' => 'strength_freshness', 'P.w_zufriedenheit' => 'strength_satisfaction', 'P.nation' => 'player_nationality', 'P.picture' => 'picture',
			'P.sa_tore' => 'st_goals', 'P.sa_spiele' => 'st_matches', 'P.sa_karten_gelb' => 'st_cards_yellow', 'P.sa_karten_gelb_rot' => 'st_cards_yellow_red', 'P.sa_karten_rot' => 'st_cards_red', 'M.id' => 'match_record_id', 'M.position' => 'match_position',
			'M.position_main' => 'match_position_main', 'M.feld' => 'field', 'M.note' => 'grade' );
		if ($websoccer->getConfig('players_aging') == 'birthday') $ageColumn = 'TIMESTAMPDIFF(YEAR,P.geburtstag,CURDATE())';
		else $ageColumn = 'P.age';
		$columns[$ageColumn] = 'age';
		$whereCondition = 'M.spiel_id = %d AND M.team_id = %d AND M.feld != \'Ausgewechselt\' ORDER BY field(M.position_main, \'T\', \'LV\', \'IV\', \'RV\', \'DM\', \'LM\', \'ZM\', \'RM\', \'OM\', \'LS\', \'MS\', \'RS\'), M.id ASC';
		$parameters = array($matchId, $teamId);
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters);
		$players = array();
		while ($player = $result->fetch_array()) {
			$field = ($player['field'] === '1') ? 'field' : 'bench';
			$player['position'] = PlayersDataService::_convertPosition($player['position']);
			$players[$field][] = $player; }
		return $players; }
	static function getMatchReportMessages($websoccer, $db, $i18n, $matchId) {
		$fromTable = $websoccer->getConfig('db_prefix') . '_matchreport AS R';
		$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') . '_spiel_text AS T ON R.message_id = T.id';
		$columns['R.id'] = 'report_id';
		$columns['R.minute'] = 'minute';
		$columns['R.playernames'] = 'playerNames';
		$columns['R.goals'] = 'goals';
		$columns['T.nachricht'] = 'message';
		$columns['T.aktion'] = 'type';
		$columns['R.active_home'] = 'active_home';
		$whereCondition = 'R.match_id = %d ORDER BY R.minute DESC, R.id DESC';
		$parameters = $matchId;
		$reportmessages = array();
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters);
		$match = null;
		//- owsPro - Fatal error: Uncaught Error: Call to undefined function get_magic_quotes_gpc()
		//- $removeSlashes = get_magic_quotes_gpc();
		while ($reportmessage = $result->fetch_array()) {
			$players = explode(';', $reportmessage['playerNames']);
			$rmsg = $reportmessage['message'];
			//- owsPro - we have to remove ist at PHP 8
			//- if ($removeSlashes) {
			//- 	$rmsg = stripslashes($rmsg);
			//- }
			$msgKey = strip_tags($rmsg);
			if ($i18n->hasMessage($msgKey)) $rmsg = $i18n->getMessage($msgKey);
			for($playerIndex = 1; $playerIndex <= count($players); $playerIndex++) $rmsg = str_replace('{sp' . $playerIndex . '}', $players[$playerIndex - 1], $rmsg);
			if (strpos($rmsg, '{ma1}') || strpos($rmsg, '{ma2}')) {
				if ($match == null) $match = self::getMatchById($websoccer, $db, $matchId, FALSE);
				if ($reportmessage['active_home']) {
					$rmsg = str_replace('{ma1}', $match['match_home_name'], $rmsg);
					$rmsg = str_replace('{ma2}', $match['match_guest_name'], $rmsg); }
				else {
					$rmsg = str_replace('{ma1}', $match['match_guest_name'], $rmsg);
					$rmsg = str_replace('{ma2}', $match['match_home_name'], $rmsg); }}
			$reportmessage['message'] = $rmsg;
			$reportmessages[] = $reportmessage; }
		return $reportmessages; }
	static function getMatchesByCondition($websoccer, $db, $whereCondition, $parameters, $limit) {
		$fromTable = self::_getFromPart($websoccer);
		$columns['M.id'] = 'id';
		$columns['M.spieltyp'] = 'type';
		$columns['M.pokalname'] = 'cup_name';
		$columns['M.pokalrunde'] = 'cup_round';
		$columns['M.home_noformation'] = 'home_noformation';
		$columns['M.guest_noformation'] = 'guest_noformation';
		$columns['HOME.name'] = 'home_team';
		$columns['HOME.bild'] = 'home_team_picture';
		$columns['HOME.id'] = 'home_id';
		$columns['HOMEUSER.id'] = 'home_user_id';
		$columns['HOMEUSER.nick'] = 'home_user_nick';
		$columns['HOMEUSER.email'] = 'home_user_email';
		$columns['HOMEUSER.picture'] = 'home_user_picture';
		$columns['GUEST.name'] = 'guest_team';
		$columns['GUEST.bild'] = 'guest_team_picture';
		$columns['GUEST.id'] = 'guest_id';
		$columns['GUESTUSER.id'] = 'guest_user_id';
		$columns['GUESTUSER.nick'] = 'guest_user_nick';
		$columns['GUESTUSER.email'] = 'guest_user_email';
		$columns['GUESTUSER.picture'] = 'guest_user_picture';
		$columns['M.home_tore'] = 'home_goals';
		$columns['M.gast_tore'] = 'guest_goals';
		$columns['M.berechnet'] = 'simulated';
		$columns['M.minutes'] = 'minutes';
		$columns['M.datum'] = 'date';
		$matches = array();
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters, $limit);
		while ($matchinfo = $result->fetch_array()) {
			$matchinfo['home_user_picture'] = UsersDataService::getUserProfilePicture($websoccer, $matchinfo['home_user_picture'], $matchinfo['home_user_email']);
			$matchinfo['guest_user_picture'] = UsersDataService::getUserProfilePicture($websoccer, $matchinfo['guest_user_picture'], $matchinfo['guest_user_email']);
			$matches[] = $matchinfo; }
		return $matches; }
	static function _getFromPart($websoccer) {
		$tablePrefix = $websoccer->getConfig('db_prefix');
		$fromTable = $tablePrefix . '_spiel AS M';
		$fromTable .= ' INNER JOIN ' . $tablePrefix . '_verein AS HOME ON M.home_verein = HOME.id';
		$fromTable .= ' INNER JOIN ' . $tablePrefix . '_verein AS GUEST ON M.gast_verein = GUEST.id';
		$fromTable .= ' LEFT JOIN ' . $tablePrefix . '_user AS HOMEUSER ON M.home_user_id = HOMEUSER.id';
		$fromTable .= ' LEFT JOIN ' . $tablePrefix . '_user AS GUESTUSER ON M.gast_user_id = GUESTUSER.id';
		return $fromTable; }
	static function _convertLeagueType($dbValue) {
		switch ($dbValue) {
			case 'Ligaspiel': return 'league';
			case 'Pokalspiel': return 'cup';
			case 'Freundschaft': return 'friendly'; }}}
class MessagesDataService {
	static function getInboxMessages($websoccer, $db, $startIndex, $entries_per_page) {
		$whereCondition = "L.empfaenger_id = %d AND L.typ = 'eingang' ORDER BY L.datum DESC";
		$parameters = $websoccer->getUser()->id;
		return self::getMessagesByCondition($websoccer, $db, $startIndex, $entries_per_page, $whereCondition, $parameters); }
	static function getOutboxMessages($websoccer, $db, $startIndex, $entries_per_page) {
		$whereCondition = "L.absender_id = %d AND L.typ = 'ausgang' ORDER BY L.datum DESC";
		$parameters = $websoccer->getUser()->id;
		return self::getMessagesByCondition($websoccer, $db, $startIndex, $entries_per_page, $whereCondition, $parameters); }
	static function getMessageById($websoccer, $db, $id) {
		$whereCondition = "(L.empfaenger_id = %d OR L.absender_id = %d) AND L.id = %d";
		$userId = $websoccer->getUser()->id;
		$parameters = array($userId, $userId, $id);
		$messages = self::getMessagesByCondition($websoccer, $db, 0, 1, $whereCondition, $parameters);
		if (count($messages)) return $messages[0];
		return null; }
	static function getLastMessageOfUserId($websoccer, $db, $userId) {
		$whereCondition = "L.absender_id = %d ORDER BY L.datum DESC";
		$userId = $websoccer->getUser()->id;
		$messages = self::getMessagesByCondition($websoccer, $db, 0, 1, $whereCondition, $userId);
		if (count($messages)) return $messages[0];
		return null; }
	static function getMessagesByCondition($websoccer, $db, $startIndex, $entries_per_page, $whereCondition, $parameters) {
		$columns["L.id"] = "message_id";
		$columns["L.betreff"] = "subject";
		$columns["L.nachricht"] = "content";
		$columns["L.datum"] = "date";
		$columns["L.gelesen"] = "seen";
		$columns["R.id"] = "recipient_id";
		$columns["R.nick"] = "recipient_name";
		$columns["S.id"] = "sender_id";
		$columns["S.nick"] = "sender_name";
		$fromTable = $websoccer->getConfig("db_prefix") . "_briefe AS L";
		$fromTable .= " INNER JOIN " . $websoccer->getConfig("db_prefix") . "_user AS R ON R.id = L.empfaenger_id";
		$fromTable .= " LEFT JOIN " . $websoccer->getConfig("db_prefix") . "_user AS S ON S.id = L.absender_id";
		$limit = $startIndex .",". $entries_per_page;
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters, $limit);
		$messages = array();
		while ($message = $result->fetch_array()) $messages[] = $message;
		return $messages; }
	static function countInboxMessages($websoccer, $db) {
		$userId = $websoccer->getUser()->id;
		$columns = "COUNT(*) AS hits";
		$fromTable = $websoccer->getConfig("db_prefix") . "_briefe AS L";
		$whereCondition = "L.empfaenger_id = %d AND typ = 'eingang'";
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $userId);
		$letters = $result->fetch_array();
		if (isset($letters["hits"])) return $letters["hits"];
		return 0; }
	static function countUnseenInboxMessages($websoccer, $db) {
		$userId = $websoccer->getUser()->id;
		$columns = "COUNT(*) AS hits";
		$fromTable = $websoccer->getConfig("db_prefix") . "_briefe AS L";
		$whereCondition = "L.empfaenger_id = %d AND typ = 'eingang' AND gelesen = '0'";
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $userId);
		$letters = $result->fetch_array();
		if (isset($letters["hits"])) return $letters["hits"];
		return 0; }
	static function countOutboxMessages($websoccer, $db) {
		$userId = $websoccer->getUser()->id;
		$columns = "COUNT(*) AS hits";
		$fromTable = $websoccer->getConfig("db_prefix") . "_briefe AS L";
		$whereCondition = "L.absender_id = %d AND typ = 'ausgang'";
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $userId);
		$letters = $result->fetch_array();
		if (isset($letters["hits"])) return $letters["hits"];
		return 0; }}
class NationalteamsDataService {
	static function getNationalTeamManagedByCurrentUser($websoccer, $db) {
		$result = $db->queryCachedSelect("id", $websoccer->getConfig("db_prefix") . "_verein", "user_id = %d AND nationalteam = '1'", $websoccer->getUser()->id, 1);
		if (count($result)) return $result[0]["id"];
		return NULL; }
	static function getNationalPlayersOfTeamByPosition($websoccer, $db, $clubId, $positionSort = "ASC") {
		$columns = array("P.id" => "id", "vorname" => "firstname", "nachname" => "lastname", "kunstname" => "pseudonym", "verletzt" => "matches_injured", "gesperrt_nationalteam" => "matches_blocked", "position" => "position", "position_main" => "position_main",
			"position_second" => "position_second", "w_staerke" => "strength", "w_technik" => "strength_technique", "w_kondition" => "strength_stamina", "w_frische" => "strength_freshness", "w_zufriedenheit" => "strength_satisfaction",
			"transfermarkt" => "transfermarket", "nation" => "player_nationality", "picture" => "picture", "P.sa_tore" => "st_goals", "P.sa_spiele" => "st_matches", "P.sa_karten_gelb" => "st_cards_yellow", "P.sa_karten_gelb_rot" => "st_cards_yellow_red",
			"P.sa_karten_rot" => "st_cards_red", "marktwert" => "marketvalue", "verein_id" => "team_id", "C.name" => "team_name" );
		if ($websoccer->getConfig('players_aging') == 'birthday') $ageColumn = 'TIMESTAMPDIFF(YEAR,geburtstag,CURDATE())';
		else $ageColumn = 'age';
		$columns[$ageColumn] = 'age';
		$fromTable = $websoccer->getConfig("db_prefix") . "_spieler AS P";
		$fromTable .= " INNER JOIN " . $websoccer->getConfig("db_prefix") . "_nationalplayer AS NP ON NP.player_id = P.id";
		$fromTable .= " LEFT JOIN " . $websoccer->getConfig("db_prefix") . "_verein AS C ON C.id = P.verein_id";
		$whereCondition = "P.status = 1 AND NP.team_id = %d ORDER BY position ". $positionSort . ", position_main ASC, nachname ASC, vorname ASC";
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $clubId, 50);
		$players = array();
		while ($player = $result->fetch_array()) {
			$player["position"] = PlayersDataService::_convertPosition($player["position"]);
			$player["player_nationality_filename"] = PlayersDataService::getFlagFilename($player["player_nationality"]);
			$player["marketvalue"] = PlayersDataService::getMarketValue($websoccer, $player, "");
			$players[$player["position"]][] = $player; }
		return $players; }
	static function findPlayersCount($websoccer, $db, $nationality, $teamId, $firstName, $lastName, $position, $mainPosition) {
		$columns = "COUNT(*) AS hits";
		$result = self::executeFindQuery($websoccer, $db, $columns, 1, $nationality, $teamId, $firstName, $lastName, $position, $mainPosition);
		$players = $result->fetch_array();
		if (isset($players["hits"])) return $players["hits"];
		return 0; }
	static function findPlayers($websoccer, $db, $nationality, $teamId, $firstName, $lastName, $position, $mainPosition, $startIndex, $entries_per_page) {
		$columns["P.id"] = "id";
		$columns["P.vorname"] = "firstname";
		$columns["P.nachname"] = "lastname";
		$columns["P.kunstname"] = "pseudonym";
		$columns["P.position"] = "position";
		$columns["P.position_main"] = "position_main";
		$columns["P.position_second"] = "position_second";
		$columns["P.w_staerke"] = "strength";
		$columns["P.w_technik"] = "strength_technique";
		$columns["P.w_kondition"] = "strength_stamina";
		$columns["P.w_frische"] = "strength_freshness";
		$columns["P.w_zufriedenheit"] = "strength_satisfaction";
		$columns["C.id"] = "team_id";
		$columns["C.name"] = "team_name";
		$limit = $startIndex .",". $entries_per_page;
		$result = self::executeFindQuery($websoccer, $db, $columns, $limit, $nationality, $teamId, $firstName, $lastName, $position, $mainPosition);
		$players = array();
		while ($player = $result->fetch_array()) {
			$player["position"] = PlayersDataService::_convertPosition($player["position"]);
			$players[] = $player; }
		return $players; }
	static function executeFindQuery($websoccer, $db, $columns, $limit, $nationality, $teamId, $firstName, $lastName, $position, $mainPosition) {
		$whereCondition = "P.status = 1 AND P.nation = '%s' AND P.verletzt = 0 AND P.id NOT IN (SELECT player_id FROM ". $websoccer->getConfig("db_prefix") . "_nationalplayer WHERE team_id = %d)";
		$parameters = array();
		$parameters[] = $nationality;
		$parameters[] = $teamId;
		if ($firstName != null) {
			$firstName = ucfirst($firstName);
			$whereCondition .= " AND P.vorname LIKE '%s%%'";
			$parameters[] = $firstName; }
		if ($lastName != null) {
			$lastName = ucfirst($lastName);
			$whereCondition .= " AND (P.nachname LIKE '%s%%' OR P.kunstname LIKE '%s%%')";
			$parameters[] = $lastName;
			$parameters[] = $lastName; }
		if ($position != null) {
			$whereCondition .= " AND P.position = '%s'";
			$parameters[] = $position; }
		if ($mainPosition != null) {
			$whereCondition .= " AND (P.position_main = '%s' OR P.position_second = '%s')";
			$parameters[] = $mainPosition;
			$parameters[] = $mainPosition; }
		$whereCondition .= " ORDER BY w_staerke DESC, w_technik DESC";
		$fromTable = $websoccer->getConfig("db_prefix") . "_spieler AS P";
		$fromTable .= " LEFT JOIN " . $websoccer->getConfig("db_prefix") . "_verein AS C ON C.id = P.verein_id";
		return $db->querySelect($columns, $fromTable, $whereCondition, $parameters, $limit); }
	static function countNextMatches($websoccer, $db, $teamId) {
		$columns = "COUNT(*) AS hits";
		$fromTable = $websoccer->getConfig("db_prefix") . "_spiel";
		$result = $db->querySelect($columns, $fromTable, "(home_verein = %d OR gast_verein = %d) AND datum > %d", array($teamId, $teamId, $websoccer->getNowAsTimestamp()));
		$matches = $result->fetch_array();
		if (isset($matches["hits"])) return $matches["hits"];
		return 0; }
	static function getNextMatches($websoccer, $db, $teamId, $startIndex, $eps) {
		$whereCondition = "(home_verein = %d OR gast_verein = %d) AND datum > %d ORDER BY datum ASC";
		return MatchesDataService::getMatchesByCondition($websoccer, $db, $whereCondition, array($teamId, $teamId, $websoccer->getNowAsTimestamp()), $startIndex . "," . $eps); }
	static function countSimulatedMatches($websoccer, $db, $teamId) {
		$columns = "COUNT(*) AS hits";
		$fromTable = $websoccer->getConfig("db_prefix") . "_spiel";
		$result = $db->querySelect($columns, $fromTable, "(home_verein = %d OR gast_verein = %d) AND berechnet = '1'", array($teamId, $teamId));
		$matches = $result->fetch_array();
		if (isset($matches["hits"])) return $matches["hits"];
		return 0; }
	static function getSimulatedMatches($websoccer, $db, $teamId, $startIndex, $eps) {
		$whereCondition = "(home_verein = %d OR gast_verein = %d) AND berechnet = '1' ORDER BY datum DESC";
		return MatchesDataService::getMatchesByCondition($websoccer, $db, $whereCondition, array($teamId, $teamId), $startIndex . "," . $eps); }}
class NotificationsDataService {
	static function createNotification($websoccer, $db, $userId, $messageKey, $messageData = null, $type = null, $targetPageId = null, $targetPageQueryString = null, $teamId = null) {
		$columns = array('user_id' => $userId, 'eventdate' => $websoccer->getNowAsTimestamp(), 'message_key' => $messageKey );
		if ($messageData != null) $columns['message_data'] = json_encode($messageData);
		if ($type != null) $columns['eventtype'] = $type;
		if ($targetPageId != null) $columns['target_pageid'] = $targetPageId;
		if ($targetPageQueryString != null) $columns['target_querystr'] = $targetPageQueryString;
		if ($teamId != null) $columns['team_id'] = $teamId;
		$db->queryInsert($columns, $websoccer->getConfig('db_prefix') . '_notification'); }
	static function countUnseenNotifications($websoccer, $db, $userId, $teamId) {
		$result = $db->querySelect('COUNT(*) AS hits', $websoccer->getConfig('db_prefix') . '_notification', 'user_id = %d AND seen = \'0\' AND (team_id = %d OR team_id IS NULL)', array($userId, $teamId));
		$rows = $result->fetch_array();
		if ($rows) return $rows['hits'];
		return 0; }
	static function getLatestNotifications($websoccer, $db, $i18n, $userId, $teamId, $limit) {
		$result = $db->querySelect('*', $websoccer->getConfig('db_prefix') . '_notification', 'user_id = %d AND (team_id = %d OR team_id IS NULL) ORDER BY eventdate DESC', array($userId, $teamId), $limit);
		$notifications = array();
		while ($row = $result->fetch_array()) {
			$notification = array( 'id' => $row['id'], 'eventdate' => $row['eventdate'], 'eventtype' => $row['eventtype'], 'seen' => $row['seen'] );
			if ($i18n->hasMessage($row['message_key'])) $message = $i18n->getMessage($row['message_key']);
			else $message = $row['message_key'];
			if (strlen($row['message_data'])) {
				$messageData = json_decode($row['message_data'], true);
				if ($messageData) {
					foreach ($messageData as $placeholderName => $placeholderValue) $message = str_replace('{' . $placeholderName . '}', htmlspecialchars($placeholderValue, ENT_COMPAT, 'UTF-8'), $message); }}
			$notification['message'] = $message;
			$link = '';
			if ($row['target_pageid']) {
				if ($row['target_querystr']) $link = $websoccer->getInternalUrl($row['target_pageid'], $row['target_querystr']);
				else $link = $websoccer->getInternalUrl($row['target_pageid']); }
			$notification['link'] = $link;
			$notifications[] = $notification; }
		return $notifications; }}
class PlayersDataService {
	static function getPlayersOfTeamByPosition($websoccer, $db, $clubId, $positionSort = 'ASC', $considerBlocksForCups = FALSE, $considerBlocks = TRUE) {
		$columns = array('id' => 'id', 'vorname' => 'firstname', 'nachname' => 'lastname', 'kunstname' => 'pseudonym', 'verletzt' => 'matches_injured', 'position' => 'position', 'position_main' => 'position_main', 'position_second' => 'position_second',
			'w_staerke' => 'strength', 'w_technik' => 'strength_technique', 'w_kondition' => 'strength_stamina', 'w_frische' => 'strength_freshness', 'w_zufriedenheit' => 'strength_satisfaction', 'transfermarkt' => 'transfermarket', 'nation' => 'player_nationality',
			'picture' => 'picture', 'sa_tore' => 'st_goals', 'sa_spiele' => 'st_matches', 'sa_karten_gelb' => 'st_cards_yellow', 'sa_karten_gelb_rot' => 'st_cards_yellow_red', 'sa_karten_rot' => 'st_cards_red', 'marktwert' => 'marketvalue' );
		if ($websoccer->getConfig('players_aging') == 'birthday') $ageColumn = 'TIMESTAMPDIFF(YEAR,geburtstag,CURDATE())';
		else $ageColumn = 'age';
		$columns[$ageColumn] = 'age';
		if ($considerBlocksForCups) $columns['gesperrt_cups'] = 'matches_blocked';
		elseif ($considerBlocks) $columns['gesperrt'] = 'matches_blocked';
		$fromTable = $websoccer->getConfig('db_prefix') . '_spieler';
		$whereCondition = 'status = 1 AND verein_id = %d ORDER BY position '. $positionSort . ', position_main ASC, nachname ASC, vorname ASC';
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $clubId, 50);
		$players = array();
		while ($player = $result->fetch_array()) {
			$player['position'] = self::_convertPosition($player['position']);
			$player['player_nationality_filename'] = self::getFlagFilename($player['player_nationality']);
			$player['marketvalue'] = self::getMarketValue($websoccer, $player, '');
			$players[$player['position']][] = $player; }
		return $players; }
	static function getPlayersOfTeamById($websoccer, $db, $clubId, $nationalteam = FALSE, $considerBlocksForCups = FALSE, $considerBlocks = TRUE) {
		$columns = array( 'id' => 'id', 'vorname' => 'firstname', 'nachname' => 'lastname', 'kunstname' => 'pseudonym', 'verletzt' => 'matches_injured', 'position' => 'position', 'position_main' => 'position_main', 'position_second' => 'position_second',
			'w_staerke' => 'strength', 'w_technik' => 'strength_technic', 'w_kondition' => 'strength_stamina', 'w_frische' => 'strength_freshness', 'w_zufriedenheit' => 'strength_satisfaction', 'transfermarkt' => 'transfermarket', 'nation' => 'player_nationality',
			'picture' => 'picture', 'sa_tore' => 'st_goals', 'sa_spiele' => 'st_matches', 'sa_karten_gelb' => 'st_cards_yellow', 'sa_karten_gelb_rot' => 'st_cards_yellow_red', 'sa_karten_rot' => 'st_cards_red', 'marktwert' => 'marketvalue',
			'vertrag_spiele' => 'contract_matches', 'vertrag_gehalt' => 'contract_salary', 'unsellable' => 'unsellable', 'lending_matches' => 'lending_matches', 'lending_fee' => 'lending_fee','lending_owner_id' => 'lending_owner_id','transfermarkt' => 'transfermarket' );
		if ($websoccer->getConfig('players_aging') == 'birthday') $ageColumn = 'TIMESTAMPDIFF(YEAR,geburtstag,CURDATE())';
		else $ageColumn = 'age';
		$columns[$ageColumn] = 'age';
		if (!$nationalteam) {
			if ($considerBlocksForCups) $columns['gesperrt_cups'] = 'matches_blocked';
			elseif ($considerBlocks) $columns['gesperrt'] = 'matches_blocked';
			else $columns['\'0\''] = 'matches_blocked';
			$fromTable = $websoccer->getConfig('db_prefix') . '_spieler';
			$whereCondition = 'status = 1 AND verein_id = %d'; }
		else {
			$columns['gesperrt_nationalteam'] = 'matches_blocked';
			$fromTable = $websoccer->getConfig('db_prefix') . '_spieler AS P';
			$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') . '_nationalplayer AS NP ON NP.player_id = P.id';
			$whereCondition = 'status = 1 AND NP.team_id = %d'; }
		$whereCondition .= ' ORDER BY position ASC, position_main ASC, nachname ASC, vorname ASC';
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $clubId, 50);
		$players = array();
		while ($player = $result->fetch_array()) {
			$player['position'] = self::_convertPosition($player['position']);
			$players[$player['id']] = $player; }
		return $players; }
	static function getPlayersOnTransferList($websoccer, $db, $startIndex, $entries_per_page, $positionFilter = null) {
		$columns['P.id'] = 'id';
		$columns['P.vorname'] = 'firstname';
		$columns['P.nachname'] = 'lastname';
		$columns['P.kunstname'] = 'pseudonym';
		$columns['P.position'] = 'position';
		$columns['P.position_main'] = 'position_main';
		$columns['P.vertrag_gehalt'] = 'contract_salary';
		$columns['P.vertrag_torpraemie'] = 'contract_goalbonus';
		$columns['P.w_staerke'] = 'strength';
		$columns['P.w_technik'] = 'strength_technique';
		$columns['P.w_kondition'] = 'strength_stamina';
		$columns['P.w_frische'] = 'strength_freshness';
		$columns['P.w_zufriedenheit'] = 'strength_satisfaction';
		$columns['P.transfermarkt'] = 'transfermarket';
		$columns['P.marktwert'] = 'marketvalue';
		$columns['P.transfer_start'] = 'transfer_start';
		$columns['P.transfer_ende'] = 'transfer_deadline';
		$columns['P.transfer_mindestgebot'] = 'min_bid';
		$columns['C.id'] = 'team_id';
		$columns['C.name'] = 'team_name';
		$fromTable = $websoccer->getConfig('db_prefix') . '_spieler AS P';
		$fromTable .= ' LEFT JOIN ' . $websoccer->getConfig('db_prefix') . '_verein AS C ON C.id = P.verein_id';
		$whereCondition = 'P.status = 1 AND P.transfermarkt = 1 AND P.transfer_ende > %d';
		$parameters[] = $websoccer->getNowAsTimestamp();
		if ($positionFilter != null) {
			$whereCondition .= ' AND P.position = \'%s\'';
			$parameters[] = $positionFilter; }
		$whereCondition .= ' ORDER BY P.transfer_ende ASC, P.nachname ASC, P.vorname ASC';
		$limit = $startIndex .','. $entries_per_page;
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters, $limit);
		$players = array();
		while ($player = $result->fetch_array()) {
			$player['position'] = self::_convertPosition($player['position']);
			$player['highestbid'] = TransfermarketDataService::getHighestBidForPlayer($websoccer, $db, $player['id'], $player['transfer_start'], $player['transfer_deadline']);
			$players[] = $player; }
		return $players; }
	static function countPlayersOnTransferList($websoccer, $db, $positionFilter = null) {
		$columns = 'COUNT(*) AS hits';
		$fromTable = $websoccer->getConfig('db_prefix') . '_spieler AS P';
		$whereCondition = 'P.status = 1 AND P.transfermarkt = 1 AND P.transfer_ende > %d';
		$parameters[] = $websoccer->getNowAsTimestamp();
		if ($positionFilter != null) {
			$whereCondition .= ' AND P.position = \'%s\'';
			$parameters[] = $positionFilter; }
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters);
		$players = $result->fetch_array();
		if (isset($players['hits'])) return $players['hits'];
		return 0; }
	static function getPlayerById($websoccer, $db, $playerId) {
		$columns['P.id'] = 'player_id';
		$columns['P.vorname'] = 'player_firstname';
		$columns['P.nachname'] = 'player_lastname';
		$columns['P.kunstname'] = 'player_pseudonym';
		$columns['P.position'] = 'player_position';
		$columns['P.position_main'] = 'player_position_main';
		$columns['P.position_second'] = 'player_position_second';
		$columns['P.geburtstag'] = 'player_birthday';
		$columns['P.nation'] = 'player_nationality';
		$columns['P.picture'] = 'player_picture';
		if ($websoccer->getConfig('players_aging') == 'birthday') $ageColumn = 'TIMESTAMPDIFF(YEAR,P.geburtstag,CURDATE())';
		else $ageColumn = 'P.age';
		$columns[$ageColumn] = 'player_age';
		$columns['P.verletzt'] = 'player_matches_injured';
		$columns['P.gesperrt'] = 'player_matches_blocked';
		$columns['P.gesperrt_cups'] = 'player_matches_blocked_cups';
		$columns['P.gesperrt_nationalteam'] = 'player_matches_blocked_nationalteam';
		$columns['P.vertrag_gehalt'] = 'player_contract_salary';
		$columns['P.vertrag_spiele'] = 'player_contract_matches';
		$columns['P.vertrag_torpraemie'] = 'player_contract_goalbonus';
		$columns['P.w_staerke'] = 'player_strength';
		$columns['P.w_technik'] = 'player_strength_technique';
		$columns['P.w_kondition'] = 'player_strength_stamina';
		$columns['P.w_frische'] = 'player_strength_freshness';
		$columns['P.w_zufriedenheit'] = 'player_strength_satisfaction';
		$columns['P.sa_tore'] = 'player_season_goals';
		$columns['P.sa_assists'] = 'player_season_assists';
		$columns['P.sa_spiele'] = 'player_season_matches';
		$columns['P.sa_karten_gelb'] = 'player_season_yellow';
		$columns['P.sa_karten_gelb_rot'] = 'player_season_yellow_red';
		$columns['P.sa_karten_rot'] = 'player_season_red';
		$columns['P.st_tore'] = 'player_total_goals';
		$columns['P.st_assists'] = 'player_total_assists';
		$columns['P.st_spiele'] = 'player_total_matches';
		$columns['P.st_karten_gelb'] = 'player_total_yellow';
		$columns['P.st_karten_gelb_rot'] = 'player_total_yellow_red';
		$columns['P.st_karten_rot'] = 'player_total_red';
		$columns['P.transfermarkt'] = 'player_transfermarket';
		$columns['P.marktwert'] = 'player_marketvalue';
		$columns['P.transfer_start'] = 'transfer_start';
		$columns['P.transfer_ende'] = 'transfer_end';
		$columns['P.transfer_mindestgebot'] = 'transfer_min_bid';
		$columns['P.history'] = 'player_history';
		$columns['P.unsellable'] = 'player_unsellable';
		$columns['P.lending_owner_id'] = 'lending_owner_id';
		$columns['L.name'] = 'lending_owner_name';
		$columns['P.lending_fee'] = 'lending_fee';
		$columns['P.lending_matches'] = 'lending_matches';
		$columns['C.id'] = 'team_id';
		$columns['C.name'] = 'team_name';
		$columns['C.finanz_budget'] = 'team_budget';
		$columns['C.user_id'] = 'team_user_id';
		$columns['(SELECT CONCAT(AVG(S.note), \';\', SUM(S.assists)) FROM ' . $websoccer->getConfig('db_prefix') . '_spiel_berechnung AS S WHERE S.spieler_id = P.id AND S.minuten_gespielt > 0 AND S.note > 0)'] = 'matches_info';
		$fromTable = $websoccer->getConfig('db_prefix') . '_spieler AS P';
		$fromTable .= ' LEFT JOIN ' . $websoccer->getConfig('db_prefix') . '_verein AS C ON C.id = P.verein_id';
		$fromTable .= ' LEFT JOIN ' . $websoccer->getConfig('db_prefix') . '_verein AS L ON L.id = P.lending_owner_id';
		$whereCondition = 'P.status = 1 AND P.id = %d';
		$players = $db->queryCachedSelect($columns, $fromTable, $whereCondition, $playerId, 1);
		if (count($players)) {
			$player = $players[0];
			$player['player_position'] = self::_convertPosition($player['player_position']);
			$player['player_marketvalue'] = self::getMarketValue($websoccer, $player);
			$player['player_nationality_filename'] = self::getFlagFilename($player['player_nationality']);
			$matchesInfo = explode(';', $player['matches_info']);
			//- owsPro - Fatal error: Uncaught TypeError: round(): Argument #1 ($num) must be of type int|float, string given
			//- $player['player_avg_grade'] = round($matchesInfo[0], 2);
			$player['player_avg_grade'] = round((int)$matchesInfo[0], 2);
			if (isset($matchesInfo[1])) $player['player_assists'] = $matchesInfo[1];
			else $player['player_assists'] = 0; }
		else $player = array();
		return $player; }
	static function getTopStrikers($websoccer, $db, $limit = 20, $leagueId = null) {
		$parameters = array();
		$columns['P.id'] = 'id';
		$columns['P.vorname'] = 'firstname';
		$columns['P.nachname'] = 'lastname';
		$columns['P.kunstname'] = 'pseudonym';
		$columns['P.sa_tore'] = 'goals';
		$columns['P.sa_spiele'] = 'matches';
		$columns['P.transfermarkt'] = 'transfermarket';
		$columns['C.id'] = 'team_id';
		$columns['C.name'] = 'team_name';
		$fromTable = $websoccer->getConfig('db_prefix') . '_spieler AS P';
		$fromTable .= ' LEFT JOIN ' . $websoccer->getConfig('db_prefix') . '_verein AS C ON C.id = P.verein_id';
		$whereCondition = 'P.status = 1 AND P.sa_tore > 0';
		if ($leagueId != null) {
			$whereCondition .= ' AND liga_id = %d';
			$parameters[] = (int) $leagueId; }
		$whereCondition .= ' ORDER BY P.sa_tore DESC, P.sa_spiele ASC';
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters, $limit);
		$players = array();
		while ($player = $result->fetch_array()) $players[] = $player;
		return $players; }
	static function getTopScorers($websoccer, $db, $limit = 20, $leagueId = null) {
		$parameters = array();
		$columns['P.id'] = 'id';
		$columns['P.vorname'] = 'firstname';
		$columns['P.nachname'] = 'lastname';
		$columns['P.kunstname'] = 'pseudonym';
		$columns['P.sa_tore'] = 'goals';
		$columns['P.sa_assists'] = 'assists';
		$columns['P.sa_spiele'] = 'matches';
		$columns['(P.sa_tore + P.sa_assists)'] = 'score';
		$columns['P.transfermarkt'] = 'transfermarket';
		$columns['C.id'] = 'team_id';
		$columns['C.name'] = 'team_name';
		$fromTable = $websoccer->getConfig('db_prefix') . '_spieler AS P';
		$fromTable .= ' LEFT JOIN ' . $websoccer->getConfig('db_prefix') . '_verein AS C ON C.id = P.verein_id';
		$whereCondition = 'P.status = \'1\' AND (P.sa_tore + P.sa_assists) > 0';
		if ($leagueId != null) {
			$whereCondition .= ' AND liga_id = %d';
			$parameters[] = (int) $leagueId; }
		$whereCondition .= ' ORDER BY score DESC, P.sa_assists DESC, P.sa_tore DESC, P.sa_spiele ASC, P.id ASC';
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters, $limit);
		$players = array();
		while ($player = $result->fetch_array()) $players[] = $player;
		return $players; }
	static function findPlayers($websoccer, $db, $firstName, $lastName, $clubName, $position, $strengthMax, $lendableOnly, $startIndex, $entries_per_page) {
		$columns['P.id'] = 'id';
		$columns['P.vorname'] = 'firstname';
		$columns['P.nachname'] = 'lastname';
		$columns['P.kunstname'] = 'pseudonym';
		$columns['P.position'] = 'position';
		$columns['P.position_main'] = 'position_main';
		$columns['P.position_second'] = 'position_second';
		$columns['P.transfermarkt'] = 'transfermarket';
		$columns['P.unsellable'] = 'unsellable';
		$columns['P.w_staerke'] = 'strength';
		$columns['P.w_technik'] = 'strength_technique';
		$columns['P.w_kondition'] = 'strength_stamina';
		$columns['P.w_frische'] = 'strength_freshness';
		$columns['P.w_zufriedenheit'] = 'strength_satisfaction';
		$columns['P.vertrag_gehalt'] = 'contract_salary';
		$columns['P.vertrag_spiele'] = 'contract_matches';
		$columns['P.lending_owner_id'] = 'lending_owner_id';
		$columns['P.lending_fee'] = 'lending_fee';
		$columns['P.lending_matches'] = 'lending_matches';
		$columns['C.id'] = 'team_id';
		$columns['C.name'] = 'team_name';
		$limit = $startIndex .','. $entries_per_page;
		$result = self::executeFindQuery($websoccer, $db, $columns, $limit, $firstName, $lastName, $clubName, $position, $strengthMax, $lendableOnly);
		$players = array();
		while ($player = $result->fetch_array()) {
			$player['position'] = self::_convertPosition($player['position']);
			$players[] = $player; }
		return $players; }
	static function findPlayersCount($websoccer, $db, $firstName, $lastName, $clubName, $position, $strengthMax, $lendableOnly) {
		$columns = 'COUNT(*) AS hits';
		$result = self::executeFindQuery($websoccer, $db, $columns, 1, $firstName, $lastName, $clubName, $position, $strengthMax, $lendableOnly);
		$players = $result->fetch_array();
		if (isset($players['hits'])) return $players['hits'];
		return 0; }
	static function executeFindQuery($websoccer, $db, $columns, $limit, $firstName, $lastName, $clubName, $position, $strengthMax, $lendableOnly) {
		$whereCondition = 'P.status = 1';
		$parameters = array();
		if ($firstName != null) {
			$firstName = ucfirst($firstName);
			$whereCondition .= ' AND P.vorname LIKE \'%s%%\'';
			$parameters[] = $firstName; }
		if ($lastName != null) {
			$lastName = ucfirst($lastName);
			$whereCondition .= ' AND (P.nachname LIKE \'%s%%\' OR P.kunstname LIKE \'%s%%\')';
			$parameters[] = $lastName;
			$parameters[] = $lastName; }
		if ($clubName != null) {
			$whereCondition .= ' AND C.name = \'%s\'';
			$parameters[] = $clubName; }
		if ($position != null) {
			$whereCondition .= ' AND P.position = \'%s\'';
			$parameters[] = $position; }
		if ($strengthMax != null && $websoccer->getConfig('hide_strength_attributes') !== '1') {
			$strengthMinValue = $strengthMax - 20;
			$strengthMaxValue = $strengthMax;
			$whereCondition .= ' AND P.w_staerke > %d AND P.w_staerke <= %d';
			$parameters[] = $strengthMinValue;
			$parameters[] = $strengthMaxValue; }
		if ($lendableOnly) $whereCondition .= ' AND P.lending_fee > 0 AND (P.lending_owner_id IS NULL OR P.lending_owner_id = 0)';
		$fromTable = $websoccer->getConfig('db_prefix') . '_spieler AS P';
		$fromTable .= ' LEFT JOIN ' . $websoccer->getConfig('db_prefix') . '_verein AS C ON C.id = P.verein_id';
		return $db->querySelect($columns, $fromTable, $whereCondition, $parameters, $limit); }
	static function _convertPosition($dbPosition) {
		switch ($dbPosition) {
			case 'Torwart': return 'goaly';
			case 'Abwehr': return 'defense';
			case 'Mittelfeld': return 'midfield';
			default: return 'striker'; }}
	static function getMarketValue($websoccer, $player, $columnPrefix = 'player_') {
		if (!$websoccer->getConfig('transfermarket_computed_marketvalue')) return $player[$columnPrefix . 'marketvalue'];
		$totalStrength = $websoccer->getConfig('sim_weight_strength') * $player[$columnPrefix . 'strength'];
		$totalStrength += $websoccer->getConfig('sim_weight_strengthTech') * $player[$columnPrefix . 'strength_technique'];
		$totalStrength += $websoccer->getConfig('sim_weight_strengthStamina') * $player[$columnPrefix . 'strength_stamina'];
		$totalStrength += $websoccer->getConfig('sim_weight_strengthFreshness') * $player[$columnPrefix . 'strength_freshness'];
		$totalStrength += $websoccer->getConfig('sim_weight_strengthSatisfaction') * $player[$columnPrefix . 'strength_satisfaction'];
		$totalStrength /= $websoccer->getConfig('sim_weight_strength') + $websoccer->getConfig('sim_weight_strengthTech') + $websoccer->getConfig('sim_weight_strengthStamina') + $websoccer->getConfig('sim_weight_strengthFreshness')
			+ $websoccer->getConfig('sim_weight_strengthSatisfaction');
		return $totalStrength * $websoccer->getConfig('transfermarket_value_per_strength'); }
	static function getFlagFilename($nationality) {
		if (!strlen($nationality)) return $nationality;
		$filename = str_replace('??', 'Ae', $nationality);
		$filename = str_replace('??', 'Oe', $filename);
		$filename = str_replace('??', 'Ue', $filename);
		$filename = str_replace('??', 'ae', $filename);
		$filename = str_replace('??', 'oe', $filename);
		$filename = str_replace('??', 'ue', $filename);
		return $filename; }}
class PremiumDataService {
	static function countAccountStatementsOfUser($websoccer, $db, $userId) {
		$columns = 'COUNT(*) AS hits';
		$fromTable = $websoccer->getConfig('db_prefix') . '_premiumstatement';
		$whereCondition = 'user_id = %d';
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $userId);
		$statements = $result->fetch_array();
		if (isset($statements['hits'])) return $statements['hits'];
		return 0; }
	static function getAccountStatementsOfUser($websoccer, $db, $userId, $startIndex, $entries_per_page) {
		$limit = $startIndex .','. $entries_per_page;
		$fromTable = $websoccer->getConfig('db_prefix') . '_premiumstatement';
		$whereCondition = 'user_id = %d ORDER BY created_date DESC';
		$result = $db->querySelect('*', $fromTable, $whereCondition, $userId, $limit);
		$statements = array();
		while ($statement = $result->fetch_array()) $statements[] = $statement;
		return $statements; }
	static function creditAmount($websoccer, $db, $userId, $amount, $subject, $data = null) {
		if ($amount == 0) return;
		$user = UsersDataService::getUserById($websoccer, $db, $userId);
		if (!isset($user['premium_balance'])) throw new Exception('user not found: ' . $userId);
		if ($amount < 0) throw new Exception('amount illegal: ' . $amount);
		else self::createTransaction($websoccer, $db, $user, $userId, $amount, $subject, $data); }
	static function debitAmount($websoccer, $db, $userId, $amount, $subject, $data = null) {
		if ($amount == 0) return;
		$user = UsersDataService::getUserById($websoccer, $db, $userId);
		if (!isset($user['premium_balance'])) throw new Exception('user not found: ' . $userId);
		if ($amount < 0) throw new Exception('amount illegal: ' . $amount);
		if ($user['premium_balance'] < $amount) {
			$i18n = I18n::getInstance($websoccer->getConfig('supported_languages'));
			throw new Exception($i18n->getMessage('premium_balance_notenough')); }
		$amount = 0 - $amount;
		self::createTransaction($websoccer, $db, $user, $userId, $amount, $subject, $data); }
	static function createTransaction($websoccer, $db, $user, $userId, $amount, $subject, $data) {
		$fromTable = $websoccer->getConfig('db_prefix') .'_premiumstatement';
		$columns = array('user_id' => $userId, 'action_id' => $subject, 'amount' => $amount, 'created_date' => $websoccer->getNowAsTimestamp(), 'subject_data' => json_encode($data) );
		$db->queryInsert($columns, $fromTable);
		$newBudget = $user['premium_balance'] + $amount;
		$updateColumns = array('premium_balance' => $newBudget);
		$fromTable = $websoccer->getConfig('db_prefix') .'_user';
		$whereCondition = 'id = %d';
		$parameters = $userId;
		$db->queryUpdate($updateColumns, $fromTable, $whereCondition, $parameters);
		if ($userId == $websoccer->getUser()->id) $websoccer->getUser()->premiumBalance = $newBudget; }
	static function createPaymentAndCreditPremium($websoccer, $db, $userId, $amount, $subject) {
		if ($amount <= 0) throw new Exception('Illegal amount: ' . $amount);
		$realAmount = $amount * 100;
		$db->queryInsert(array('user_id' => $userId, 'amount' => $realAmount, 'created_date' => $websoccer->getNowAsTimestamp() ), $websoccer->getConfig('db_prefix') . '_premiumpayment');
		$priceOptions = explode(',', $websoccer->getConfig('premium_price_options'));
		if (count($priceOptions)) {
			foreach ($priceOptions as $priceOption) {
				$optionParts = explode(':', $priceOption);
				$realMoney = trim($optionParts[0]);
				$realMoneyAmount = $realMoney * 100;
				$premiumMoney = trim($optionParts[1]);
				if ($realAmount == $realMoneyAmount) {
					self::creditAmount($websoccer, $db, $userId, $premiumMoney, $subject);
					return; }}}
		throw new Exception('No price option found for amount: ' . $amount); }
	static function getPaymentsOfUser($websoccer, $db, $userId, $limit) {
		$fromTable = $websoccer->getConfig('db_prefix') . '_premiumpayment';
		$whereCondition = 'user_id = %d ORDER BY created_date DESC';
		$result = $db->querySelect('*', $fromTable, $whereCondition, $userId, $limit);
		$statements = array();
		while ($statement = $result->fetch_array()) {
			$statement['amount'] = $statement['amount'] / 100;
			$statements[] = $statement; }
		return $statements; }}
class RandomEventsDataService {
	static function createEventIfRequired($websoccer, $db, $userId) {
		$eventsInterval = (int) $websoccer->getConfig('randomevents_interval_days');
		if ($eventsInterval < 1) return;
		$result = $db->querySelect('id', $websoccer->getConfig('db_prefix') . '_verein', 'user_id = %d AND status = \'1\'', $userId);
		$clubIds = array();
		while ($club = $result->fetch_array()) $clubIds[] = $club['id'];
		if (!count($clubIds)) return;
		$clubId = $clubIds[array_rand($clubIds)];
		$now = $websoccer->getNowAsTimestamp();
		$result = $db->querySelect('datum_anmeldung', $websoccer->getConfig('db_prefix') . '_user', 'id = %d', $userId, 1);
		$user = $result->fetch_array();
		if ($user['datum_anmeldung'] >= ($now - 24 * 3600)) return;
		$result = $db->querySelect('occurrence_date', $websoccer->getConfig('db_prefix') . '_randomevent_occurrence', 'user_id = %d ORDER BY occurrence_date DESC', $userId, 1);
		$latestEvent = $result->fetch_array();
		if ($latestEvent && $latestEvent['occurrence_date'] >= ($now - 24 * 3600 * $eventsInterval)) return;
		self::_createAndExecuteEvent($websoccer, $db, $userId, $clubId);
		if ($latestEvent) {
			$deleteBoundary = $now - 24 * 3600 * 10 * $eventsInterval;
			$db->queryDelete($websoccer->getConfig('db_prefix') . '_randomevent_occurrence', 'user_id = %d AND occurrence_date < %d', array($userId, $deleteBoundary)); } }
	static function _createAndExecuteEvent($websoccer, $db, $userId, $clubId) {
		$result = $db->querySelect('*', $websoccer->getConfig('db_prefix') . '_randomevent', 'weight > 0 AND id NOT IN (SELECT event_id FROM ' . $websoccer->getConfig('db_prefix') . '_randomevent_occurrence WHERE user_id = %d) ORDER BY RAND()', $userId, 100);
		$events = array();
		while ($event = $result->fetch_array()) {
			for ($i = 1; $i <= $event['weight']; $i++) $events[] = $event; }
		if (!count($events)) return;
		$randomEvent = $events[array_rand($events)];
		self::_executeEvent($websoccer, $db, $userId, $clubId, $randomEvent);
		$db->queryInsert(array('user_id' => $userId, 'team_id' => $clubId, 'event_id' => $randomEvent['id'], 'occurrence_date' => $websoccer->getNowAsTimestamp() ), $websoccer->getConfig('db_prefix') . '_randomevent_occurrence'); }
	static function _executeEvent($websoccer, $db, $userId, $clubId, $event) {
		$notificationType = 'randomevent';
		$subject = $event['message'];
		if ($event['effect'] == 'money') {
			$amount = $event['effect_money_amount'];
			$sender = $websoccer->getConfig('projectname');
			if ($amount > 0) BankAccountDataService::creditAmount($websoccer, $db, $clubId, $amount, $subject, $sender);
			else BankAccountDataService::debitAmount($websoccer, $db, $clubId, $amount * (0-1), $subject, $sender);
			NotificationsDataService::createNotification($websoccer, $db, $userId, $subject, null, $notificationType, 'finances', null, $clubId); }
		else {
			$result = $db->querySelect('id, vorname, nachname, kunstname, w_frische, w_kondition, w_zufriedenheit', $websoccer->getConfig('db_prefix') . '_spieler', 'verein_id = %d AND gesperrt = 0 AND verletzt = 0 AND status = \'1\' ORDER BY RAND()', $clubId, 1);
			$player = $result->fetch_array();
			if (!$player) return;
			switch ($event['effect']) {
				case 'player_injured': $columns = array('verletzt' => $event['effect_blocked_matches']);
					break;
				case 'player_blocked': $columns = array('gesperrt' => $event['effect_blocked_matches']);
					break;
				case 'player_happiness': $columns = array('w_zufriedenheit' => max(1, min(100, $player['w_zufriedenheit'] + $event['effect_skillchange'])));
					break;
				case 'player_fitness': $columns = array('w_frische' => max(1, min(100, $player['w_frische'] + $event['effect_skillchange'])));
					break;
				case 'player_stamina': $columns = array('w_kondition' => max(1, min(100, $player['w_kondition'] + $event['effect_skillchange'])));
					break; }
			if (!isset($columns)) return;
			$db->queryUpdate($columns, $websoccer->getConfig('db_prefix') . '_spieler', 'id = %d', $player['id']);
			$playerName = (strlen($player['kunstname'])) ? $player['kunstname'] : $player['vorname'] . ' ' . $player['nachname'];
			NotificationsDataService::createNotification($websoccer, $db, $userId, $subject, array('playername' => $playerName), $notificationType, 'player', 'id=' . $player['id'], $clubId); }}}
class SponsorsDataService {
	static function getSponsorinfoByTeamId($websoccer, $db, $clubId) {
		$columns["T.sponsor_spiele"] = "matchdays";
		$columns["S.id"] = "sponsor_id";
		$columns["S.name"] = "name";
		$columns["S.b_spiel"] = "amount_match";
		$columns["S.b_heimzuschlag"] = "amount_home_bonus";
		$columns["S.b_sieg"] = "amount_win";
		$columns["S.b_meisterschaft"] = "amount_championship";
		$columns["S.bild"] = "picture";
		$fromTable = $websoccer->getConfig("db_prefix") . "_sponsor AS S";
		$fromTable .= " INNER JOIN " . $websoccer->getConfig("db_prefix") . "_verein AS T ON T.sponsor_id = S.id";
		$whereCondition = "T.id = %d AND T.sponsor_spiele > 0";
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $clubId, 1);
		$sponsor = $result->fetch_array();
		return $sponsor; }
	static function getSponsorOffers($websoccer, $db, $teamId) {
		$team = TeamsDataService::getTeamSummaryById($websoccer, $db, $teamId);
		$teamRank = TeamsDataService::getTableRankOfTeam($websoccer, $db, $teamId);
		$columns["S.id"] = "sponsor_id";
		$columns["S.name"] = "name";
		$columns["S.b_spiel"] = "amount_match";
		$columns["S.b_heimzuschlag"] = "amount_home_bonus";
		$columns["S.b_sieg"] = "amount_win";
		$columns["S.b_meisterschaft"] = "amount_championship";
		$fromTable = $websoccer->getConfig("db_prefix") . "_sponsor AS S";
		$whereCondition = "S.liga_id = %d AND (S.min_platz = 0 OR S.min_platz >= %d)" . " AND (S.max_teams <= 0 OR S.max_teams > (SELECT COUNT(*) FROM " . $websoccer->getConfig("db_prefix") . "_verein AS T WHERE T.sponsor_id = S.id AND T.sponsor_spiele > 0))"
			. " ORDER BY S.b_spiel DESC";
		$parameters = array($team["team_league_id"], $teamRank);
		return $db->queryCachedSelect($columns, $fromTable, $whereCondition, $parameters, 20); }}
class StadiumsDataService {
	static function getStadiumByTeamId($websoccer, $db, $clubId) {
		if (!$clubId) return NULL;
		$columns["S.id"] = "stadium_id";
		$columns["S.name"] = "name";
		$columns["S.picture"] = "picture";
		$columns["S.p_steh"] = "places_stands";
		$columns["S.p_sitz"] = "places_seats";
		$columns["S.p_haupt_steh"] = "places_stands_grand";
		$columns["S.p_haupt_sitz"] = "places_seats_grand";
		$columns["S.p_vip"] = "places_vip";
		$columns["S.level_pitch"] = "level_pitch";
		$columns["S.level_videowall"] = "level_videowall";
		$columns["S.level_seatsquality"] = "level_seatsquality";
		$columns["S.level_vipquality"] = "level_vipquality";
		$fromTable = $websoccer->getConfig("db_prefix") . "_stadion AS S";
		$fromTable .= " INNER JOIN " . $websoccer->getConfig("db_prefix") . "_verein AS T ON T.stadion_id = S.id";
		$whereCondition = "T.id = %d";
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $clubId, 1);
		$stadium = $result->fetch_array();
		return $stadium; }
	static function getBuilderOffersForExtension($websoccer, $db, $clubId, $newSideStanding = 0, $newSideSeats = 0, $newGrandStanding = 0, $newGrandSeats = 0, $newVips = 0) {
		$offers = array();
		$totalNew = $newSideStanding + $newSideSeats + $newGrandStanding + $newGrandSeats + $newVips;
		if (!$totalNew) return $offers;
		$stadium = self::getStadiumByTeamId($websoccer, $db, $clubId);
		$existingCapacity = $stadium["places_stands"] + $stadium["places_seats"] + $stadium["places_stands_grand"] + $stadium["places_seats_grand"] + $stadium["places_vip"];
		$result = $db->querySelect("*", $websoccer->getConfig("db_prefix") . "_stadium_builder", "min_stadium_size <= %d AND (max_stadium_size = 0 OR max_stadium_size >= %d)", array($existingCapacity, $existingCapacity));
		while ($builder = $result->fetch_array()) {
			$constructionTime = max($builder["construction_time_days_min"], $builder["construction_time_days"] * ceil($totalNew / 5000));
			$costsPerSeat = $builder["cost_per_seat"];
			$costsSideStanding = $newSideStanding * ($websoccer->getConfig("stadium_cost_standing") + $costsPerSeat);
			$costsSideSeats = $newSideSeats * ($websoccer->getConfig("stadium_cost_seats") + $costsPerSeat);
			$costsGrandStanding = $newGrandStanding * ($websoccer->getConfig("stadium_cost_standing_grand") + $costsPerSeat);
			$costsGrandSeats = $newGrandSeats * ($websoccer->getConfig("stadium_cost_seats_grand") + $costsPerSeat);
			$costsVip = $newVips * ($websoccer->getConfig("stadium_cost_vip") + $costsPerSeat);
			$offer = array("builder_id" => $builder["id"], "builder_name" => $builder["name"], "builder_picture" => $builder["picture"], "builder_premiumfee" => $builder["premiumfee"], "deadline" => $websoccer->getNowAsTimestamp() + $constructionTime * 24 * 3600,
				"deadline_days" => $constructionTime, "reliability" => $builder["reliability"], "fixedcosts" => $builder["fixedcosts"], "costsSideStanding" => $costsSideStanding, "costsSideSeats" => $costsSideSeats, "costsGrandStanding" => $costsGrandStanding,
				"costsGrandSeats" => $costsGrandSeats, "costsVip" => $costsVip, "totalCosts" => $builder["fixedcosts"] + $costsSideStanding + $costsSideSeats + $costsGrandStanding + $costsGrandSeats + $costsVip );
			$offers[$builder["id"]] = $offer; }
		return $offers; }
	static function getCurrentConstructionOrderOfTeam($websoccer, $db, $clubId) {
		$fromTable = $websoccer->getConfig("db_prefix") . "_stadium_construction AS C";
		$fromTable .= " INNER JOIN " . $websoccer->getConfig("db_prefix") . "_stadium_builder AS B ON B.id = C.builder_id";
		$result = $db->querySelect("C.*, B.name AS builder_name, B.reliability AS builder_reliability", $fromTable, "C.team_id = %d", $clubId);
		$order = $result->fetch_array();
		if ($order) return $order;
		else return NULL;}
	static function getDueConstructionOrders($websoccer, $db) {
		$fromTable = $websoccer->getConfig("db_prefix") . "_stadium_construction AS C";
		$fromTable .= " INNER JOIN " . $websoccer->getConfig("db_prefix") . "_stadium_builder AS B ON B.id = C.builder_id";
		$fromTable .= " INNER JOIN " . $websoccer->getConfig("db_prefix") . "_verein AS T ON T.id = C.team_id";
		$result = $db->querySelect("C.*, T.user_id AS user_id, B.reliability AS builder_reliability", $fromTable, "C.deadline <= %d", $websoccer->getNowAsTimestamp());
		$orders = array();
		while ($order = $result->fetch_array()) $orders[] = $order;
		return $orders; }
	static function computeUpgradeCosts($websoccer, $type, $stadium) {
		$existingLevel = $stadium["level_" . $type];
		if ($existingLevel >= 5) return 0;
		$baseCost = $websoccer->getConfig("stadium_". $type . "_price");
		if ($type == "seatsquality") $baseCost = $baseCost * ($stadium["places_seats"] + $stadium["places_seats_grand"]);
		elseif ($type == "vipquality") $baseCost = $baseCost * $stadium["places_vip"];
		$additionFactor = $websoccer->getConfig("stadium_maintenance_priceincrease_per_level") * $existingLevel / 100;
		return round($baseCost + $baseCost * $additionFactor); }}
class TeamsDataService {
	static function getTeamById($websoccer, $db, $teamId) {
		$fromTable = self::_getFromPart($websoccer);
		$whereCondition = 'C.id = %d AND C.status = 1';
		$parameters = $teamId;
		$columns['C.id'] = 'team_id';
		$columns['C.bild'] = 'team_logo';
		$columns['C.name'] = 'team_name';
		$columns['C.kurz'] = 'team_short';
		$columns['C.strength'] = 'team_strength';
		$columns['C.finanz_budget'] = 'team_budget';
		$columns['C.min_target_rank'] = 'team_min_target_rank';
		$columns['C.nationalteam'] = 'is_nationalteam';
		$columns['C.captain_id'] = 'captain_id';
		$columns['C.interimmanager'] = 'interimmanager';
		$columns['C.history'] = 'team_history';
		$columns['L.name'] = 'team_league_name';
		$columns['L.id'] = 'team_league_id';
		$columns['SPON.name'] = 'team_sponsor_name';
		$columns['SPON.bild'] = 'team_sponsor_picture';
		$columns['SPON.id'] = 'team_sponsor_id';
		$columns['U.nick'] = 'team_user_name';
		$columns['U.id'] = 'team_user_id';
		$columns['U.email'] = 'team_user_email';
		$columns['U.picture'] = 'team_user_picture';
		$columns['DEPUTY.nick'] = 'team_deputyuser_name';
		$columns['DEPUTY.id'] = 'team_deputyuser_id';
		$columns['DEPUTY.email'] = 'team_deputyuser_email';
		$columns['DEPUTY.picture'] = 'team_deputyuser_picture';
		$columns['C.sa_tore'] = 'team_season_goals';
		$columns['C.sa_gegentore'] = 'team_season_againsts';
		$columns['C.sa_spiele'] = 'team_season_matches';
		$columns['C.sa_siege'] = 'team_season_wins';
		$columns['C.sa_niederlagen'] = 'team_season_losses';
		$columns['C.sa_unentschieden'] = 'team_season_draws';
		$columns['C.sa_punkte'] = 'team_season_score';
		$columns['C.st_tore'] = 'team_total_goals';
		$columns['C.st_gegentore'] = 'team_total_againsts';
		$columns['C.st_spiele'] = 'team_total_matches';
		$columns['C.st_siege'] = 'team_total_wins';
		$columns['C.st_niederlagen'] = 'team_total_losses';
		$columns['C.st_unentschieden'] = 'team_total_draws';
		$columns['C.st_punkte'] = 'team_total_score';
		$teaminfos = $db->queryCachedSelect($columns, $fromTable, $whereCondition, $parameters, 1);
		$team = (isset($teaminfos[0])) ? $teaminfos[0] : array();
		if (isset($team['team_user_email'])) $team['user_picture'] = UsersDataService::getUserProfilePicture($websoccer, $team['team_user_picture'], $team['team_user_email'], 20);
		if (isset($team['team_deputyuser_email'])) $team['deputyuser_picture'] = UsersDataService::getUserProfilePicture($websoccer, $team['team_deputyuser_picture'], $team['team_deputyuser_email'], 20);
		return $team; }
	static function getTeamSummaryById($websoccer, $db, $teamId) {
		if (!$teamId) return NULL;
		$tablePrefix = $websoccer->getConfig('db_prefix');
		$fromTable = $tablePrefix . '_verein AS C';
		$fromTable .= ' LEFT JOIN ' . $tablePrefix . '_liga AS L ON C.liga_id = L.id';
		$whereCondition = 'C.status = 1 AND C.id = %d';
		$parameters = $teamId;
		$columns['C.id'] = 'team_id';
		$columns['C.name'] = 'team_name';
		$columns['C.finanz_budget'] = 'team_budget';
		$columns['C.bild'] = 'team_picture';
		$columns['C.user_id'] = 'user_id';
		$columns['L.name'] = 'team_league_name';
		$columns['L.id'] = 'team_league_id';
		$teaminfos = $db->queryCachedSelect($columns, $fromTable, $whereCondition, $parameters, 1);
		$team = (isset($teaminfos[0])) ? $teaminfos[0] : array();
		return $team; }
	static function getTeamsOfLeagueOrderedByTableCriteria($websoccer, $db, $leagueId) {
		$result = $db->querySelect('id', $websoccer->getConfig('db_prefix') .'_saison', 'liga_id = %d AND beendet = \'0\' ORDER BY name DESC', $leagueId, 1);
		$season = $result->fetch_array();
		$fromTable = $websoccer->getConfig('db_prefix') . '_verein AS C';
		$fromTable .= ' LEFT JOIN ' . $websoccer->getConfig('db_prefix') . '_user AS U ON C.user_id = U.id';
		$fromTable .= ' LEFT JOIN ' . $websoccer->getConfig('db_prefix') . '_leaguehistory AS PREVDAY ON (PREVDAY.team_id = C.id AND PREVDAY.matchday = (C.sa_spiele - 1)';
		if ($season) $fromTable .= ' AND PREVDAY.season_id = ' . $season['id'];
		$fromTable .= ')';
		$columns = array();
		$columns['C.id'] = 'id';
		$columns['C.name'] = 'name';
		$columns['C.sa_punkte'] = 'score';
		$columns['C.sa_tore'] = 'goals';
		$columns['C.sa_gegentore'] = 'goals_received';
		$columns['(C.sa_tore - C.sa_gegentore)'] = 'goals_diff';
		$columns['C.sa_siege'] = 'wins';
		$columns['C.sa_niederlagen'] = 'defeats';
		$columns['C.sa_unentschieden'] = 'draws';
		$columns['C.sa_spiele'] = 'matches';
		$columns['C.bild'] = 'picture';
		$columns['U.id'] = 'user_id';
		$columns['U.nick'] = 'user_name';
		$columns['U.email'] = 'user_email';
		$columns['U.picture'] = 'user_picture';
		$columns['PREVDAY.rank'] = 'previous_rank';
		$whereCondition = 'C.liga_id = %d AND C.status = \'1\' ORDER BY score DESC, goals_diff DESC, wins DESC, draws DESC, goals DESC, name ASC';
		$parameters = $leagueId;
		$teams = array();
		$now = $websoccer->getNowAsTimestamp();
		$updateHistory = FALSE;
		if ($season && (!isset($_SESSION['leaguehist']) || $_SESSION['leaguehist'] < ($now - 600))) {
			$_SESSION['leaguehist'] = $now;
			$updateHistory = TRUE;
			$queryTemplate = 'REPLACE INTO ' . $websoccer->getConfig('db_prefix') . '_leaguehistory ';
			$queryTemplate .= '(team_id, season_id, user_id, matchday, rank) ';
			$queryTemplate .= 'VALUES (%d, ' . $season['id'] . ', %s, %d, %d);'; }
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters);
		$rank = 0;
		while ($team = $result->fetch_array()) {
			$rank++;
			$team['user_picture'] = UsersDataService::getUserProfilePicture($websoccer, $team['user_picture'], $team['user_email'], 20);
			$teams[] = $team;
			if ($updateHistory && $team['matches']) {
				$userId = ($team['user_id']) ? $team['user_id'] : 'DEFAULT';
				$query = sprintf($queryTemplate, $team['id'], $userId, $team['matches'], $rank);
				$db->executeQuery($query); }}
		return $teams; }
	static function getTeamsOfSeasonOrderedByTableCriteria($websoccer, $db, $seasonId, $type) {
		$fromTable = $websoccer->getConfig('db_prefix') . '_team_league_statistics AS S';
		$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') . '_verein AS C ON C.id = S.team_id';
		$fromTable .= ' LEFT JOIN ' . $websoccer->getConfig('db_prefix') . '_user AS U ON C.user_id = U.id';
		$whereCondition = 'S.season_id = %d';
		$parameters = $seasonId;
		$columns['C.id'] = 'id';
		$columns['C.name'] = 'name';
		$columns['C.bild'] = 'picture';
		$fieldPrefix = 'total';
		if ($type == 'home') $fieldPrefix = 'home';
		elseif ($type == 'guest') $fieldPrefix = 'guest';
		$columns['S.' . $fieldPrefix . '_points'] = 'score';
		$columns['S.' . $fieldPrefix . '_goals'] = 'goals';
		$columns['S.' . $fieldPrefix . '_goalsreceived'] = 'goals_received';
		$columns['S.' . $fieldPrefix . '_goalsdiff'] = 'goals_diff';
		$columns['S.' . $fieldPrefix . '_wins'] = 'wins';
		$columns['S.' . $fieldPrefix . '_draws'] = 'draws';
		$columns['S.' . $fieldPrefix . '_losses'] = 'defeats';
		$columns['(S.' . $fieldPrefix . '_wins + S.' . $fieldPrefix . '_draws + S.' . $fieldPrefix . '_losses)'] = 'matches';
		$columns['U.id'] = 'user_id';
		$columns['U.nick'] = 'user_name';
		$columns['U.email'] = 'user_email';
		$columns['U.picture'] = 'user_picture';
		$teams = array();
		$whereCondition .= ' ORDER BY score DESC, goals_diff DESC, wins DESC, draws DESC, goals DESC, name ASC';
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters);
		while ($team = $result->fetch_array()) {
			$team['user_picture'] = UsersDataService::getUserProfilePicture($websoccer, $team['user_picture'], $team['user_email'], 20);
			$teams[] = $team; }
		return $teams; }
	static function getTeamsOfLeagueOrderedByAlltimeTableCriteria($websoccer, $db, $leagueId, $type = null) {
		$fromTable = $websoccer->getConfig('db_prefix') . '_team_league_statistics AS S';
		$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') . '_verein AS C ON C.id = S.team_id';
		$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') . '_saison AS SEASON ON SEASON.id = S.season_id';
		$fromTable .= ' LEFT JOIN ' . $websoccer->getConfig('db_prefix') . '_user AS U ON C.user_id = U.id';
		$whereCondition = 'SEASON.liga_id = %d';
		$parameters = $leagueId;
		$columns['C.id'] = 'id';
		$columns['C.name'] = 'name';
		$columns['C.bild'] = 'picture';
		$fieldPrefix = 'total';
		if ($type == 'home') $fieldPrefix = 'home';
		elseif ($type == 'guest') $fieldPrefix = 'guest';
		$columns['SUM(S.' . $fieldPrefix . '_points)'] = 'score';
		$columns['SUM(S.' . $fieldPrefix . '_goals)'] = 'goals';
		$columns['SUM(S.' . $fieldPrefix . '_goalsreceived)'] = 'goals_received';
		$columns['SUM(S.' . $fieldPrefix . '_goalsdiff)'] = 'goals_diff';
		$columns['SUM(S.' . $fieldPrefix . '_wins)'] = 'wins';
		$columns['SUM(S.' . $fieldPrefix . '_draws)'] = 'draws';
		$columns['SUM(S.' . $fieldPrefix . '_losses)'] = 'defeats';
		$columns['SUM((S.' . $fieldPrefix . '_wins + S.' . $fieldPrefix . '_draws + S.' . $fieldPrefix . '_losses))'] = 'matches';
		$columns['U.id'] = 'user_id';
		$columns['U.nick'] = 'user_name';
		$columns['U.email'] = 'user_email';
		$columns['U.picture'] = 'user_picture';
		$teams = array();
		$whereCondition .= ' GROUP BY C.id ORDER BY score DESC, goals_diff DESC, wins DESC, draws DESC, goals DESC, name ASC';
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters);
		while ($team = $result->fetch_array()) {
			$team['user_picture'] = UsersDataService::getUserProfilePicture($websoccer, $team['user_picture'], $team['user_email'], 20);
			$teams[] = $team; }
		return $teams; }
	static function getTableRankOfTeam($websoccer, $db, $teamId) {
		$subQuery = '(SELECT COUNT(*) FROM ' . $websoccer->getConfig('db_prefix') . '_verein AS T2 WHERE T2.liga_id = T1.liga_id AND (T2.sa_punkte > T1.sa_punkte OR T2.sa_punkte = T1.sa_punkte AND (T2.sa_tore - T2.sa_gegentore) > (T1.sa_tore - T1.sa_gegentore)'
			. ' OR T2.sa_punkte = T1.sa_punkte AND (T2.sa_tore - T2.sa_gegentore) = (T1.sa_tore - T1.sa_gegentore) AND T2.sa_siege > T1.sa_siege'
			. ' OR T2.sa_punkte = T1.sa_punkte AND (T2.sa_tore - T2.sa_gegentore) = (T1.sa_tore - T1.sa_gegentore) AND T2.sa_siege = T1.sa_siege AND T2.sa_tore > T1.sa_tore))';
		$columns = $subQuery . ' + 1 AS RNK';
		$fromTable = $websoccer->getConfig('db_prefix') . '_verein AS T1';
		$whereCondition = 'T1.id = %d';
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $teamId);
		$teamRank = $result->fetch_array();
		if ($teamRank) return (int) $teamRank['RNK'];
		return 0; }
	static function getTeamsWithoutUser($websoccer, $db) {
		$fromTable = $websoccer->getConfig('db_prefix') . '_verein AS C';
		$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') . '_liga AS L ON C.liga_id = L.id';
		$fromTable .= ' LEFT JOIN ' . $websoccer->getConfig('db_prefix') . '_stadion AS S ON C.stadion_id = S.id';
		$whereCondition = 'nationalteam != \'1\' AND (C.user_id = 0 OR C.user_id IS NULL OR C.interimmanager = \'1\') AND C.status = 1';
		$columns['C.id'] = 'team_id';
		$columns['C.name'] = 'team_name';
		$columns['C.finanz_budget'] = 'team_budget';
		$columns['C.bild'] = 'team_picture';
		$columns['C.strength'] = 'team_strength';
		$columns['L.id'] = 'league_id';
		$columns['L.name'] = 'league_name';
		$columns['L.land'] = 'league_country';
		$columns['S.p_steh'] = 'stadium_p_steh';
		$columns['S.p_sitz'] = 'stadium_p_sitz';
		$columns['S.p_haupt_steh'] = 'stadium_p_haupt_steh';
		$columns['S.p_haupt_sitz'] = 'stadium_p_haupt_sitz';
		$columns['S.p_vip'] = 'stadium_p_vip';
		$whereCondition .= ' ORDER BY league_country ASC, league_name ASC, team_name ASC';
		$teams = array();
		$result = $db->querySelect($columns, $fromTable, $whereCondition, array(), 300);
		while ($team = $result->fetch_array()) $teams[$team['league_country']][] = $team;
		return $teams; }
	static function countTeamsWithoutManager($websoccer, $db) {
		$result = $db->querySelect('COUNT(*) AS hits', $websoccer->getConfig('db_prefix') . '_verein', '(user_id = 0 OR user_id IS NULL) AND status = 1');
		$teams = $result->fetch_array();
		if (isset($teams['hits'])) return $teams['hits'];
		return 0; }
	static function findTeamNames($websoccer, $db, $query) {
		$columns = 'name';
		$fromTable = $websoccer->getConfig('db_prefix') . '_verein';
		$whereCondition = 'UPPER(name) LIKE \'%s%%\' AND status = 1';
		$result = $db->querySelect($columns, $fromTable, $whereCondition, strtoupper($query), 10);
		$teams = array();
		while($team = $result->fetch_array()) $teams[] = $team['name'];
		return $teams; }
	static function getTeamSize($websoccer, $db, $clubId) {
		$columns = 'COUNT(*) AS number';
		$fromTable = $websoccer->getConfig('db_prefix') .'_spieler';
		$whereCondition = 'verein_id = %d AND status = \'1\' AND transfermarkt != \'1\' AND lending_fee = 0';
		$parameters = $clubId;
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters);
		$players = $result->fetch_array();
		return ($players['number']) ? $players['number'] : 0; }
	static function getTotalPlayersSalariesOfTeam($websoccer, $db, $clubId) {
		$columns = 'SUM(vertrag_gehalt) AS salary';
		$fromTable = $websoccer->getConfig('db_prefix') .'_spieler';
		$whereCondition = 'verein_id = %d AND status = \'1\'';
		$parameters = $clubId;
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters);
		$players = $result->fetch_array();
		return ($players['salary']) ? $players['salary'] : 0; }
	static function getTeamCaptainIdOfTeam($websoccer, $db, $clubId) {
		$result = $db->querySelect('captain_id', $websoccer->getConfig('db_prefix') .'_verein', 'id = %d', $clubId);
		$team = $result->fetch_array();
		return (isset($team['captain_id'])) ? $team['captain_id'] : 0; }
	static function validateWhetherTeamHasEnoughBudgetForSalaryBid($websoccer, $db, $i18n, $clubId, $salary) {
		$result = $db->querySelect('SUM(vertrag_gehalt) AS salary_sum', $websoccer->getConfig('db_prefix') .'_spieler', 'verein_id = %d', $clubId);
		$players = $result->fetch_array();
		$minBudget = ($players['salary_sum'] + $salary) * 2;
		$team = self::getTeamSummaryById($websoccer, $db, $clubId);
		if ($team['team_budget'] < $minBudget) throw new Exception($i18n->getMessage("extend-contract_cannot_afford_offer")); }
	static function _getFromPart($websoccer) {
		$tablePrefix = $websoccer->getConfig('db_prefix');
		$fromTable = $tablePrefix . '_verein AS C';
		$fromTable .= ' LEFT JOIN ' . $tablePrefix . '_liga AS L ON C.liga_id = L.id';
		$fromTable .= ' LEFT JOIN ' . $tablePrefix . '_sponsor AS SPON ON C.sponsor_id = SPON.id';
		$fromTable .= ' LEFT JOIN ' . $tablePrefix . '_user AS U ON C.user_id = U.id';
		$fromTable .= ' LEFT JOIN ' . $tablePrefix . '_user AS DEPUTY ON C.user_id_actual = DEPUTY.id';
		return $fromTable; }}
class TrainingcampsDataService {
	static function getCamps($websoccer, $db) {
		$fromTable = $websoccer->getConfig("db_prefix") . "_trainingslager";
		$whereCondition = "1=1 ORDER BY name ASC";
		$camps = array();
		$result = $db->querySelect(self::_getColumns(), $fromTable, $whereCondition);
		while ($camp = $result->fetch_array()) $camps[] = $camp;
		return $camps; }
	static function getCampBookingsByTeam($websoccer, $db, $teamId) {
		$fromTable = $websoccer->getConfig("db_prefix") . "_trainingslager_belegung AS B";
		$fromTable .= " INNER JOIN " . $websoccer->getConfig("db_prefix") . "_trainingslager AS C ON C.id = B.lager_id";
		$columns["B.id"] = "id";
		$columns["B.datum_start"] = "date_start";
		$columns["B.datum_ende"] = "date_end";
		$columns["C.name"] = "name";
		$columns["C.land"] = "country";
		$columns["C.preis_spieler_tag"] = "costs";
		$columns["C.p_staerke"] = "effect_strength";
		$columns["C.p_technik"] = "effect_strength_technique";
		$columns["C.p_kondition"] = "effect_strength_stamina";
		$columns["C.p_frische"] = "effect_strength_freshness";
		$columns["C.p_zufriedenheit"] = "effect_strength_satisfaction";
		$whereCondition = "B.verein_id = %d ORDER BY B.datum_start DESC";
		$camps = array();
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $teamId);
		while ($camp = $result->fetch_array()) $camps[] = $camp;
		return $camps; }
	static function getCampById($websoccer, $db, $campId) {
		$fromTable = $websoccer->getConfig("db_prefix") . "_trainingslager";
		$whereCondition = "id = %d";
		$result = $db->querySelect(self::_getColumns(), $fromTable, $whereCondition, $campId);
		$camp = $result->fetch_array();
		return $camp; }
	static function executeCamp($websoccer, $db, $teamId, $bookingInfo) {
		$players = PlayersDataService::getPlayersOfTeamById($websoccer, $db, $teamId);
		if (count($players)) {
			$playerTable = $websoccer->getConfig("db_prefix") . "_spieler";
			$updateCondition = "id = %d";
			$duration = round(($bookingInfo["date_end"] - $bookingInfo["date_start"]) / (24 * 3600));
			foreach ($players as $player) {
				if ($player["matches_injured"] > 0) continue;
				$columns = array();
				$columns["w_staerke"] = min(100, max(1, $bookingInfo["effect_strength"] *  $duration + $player["strength"]));
				$columns["w_technik"] = min(100, max(1, $bookingInfo["effect_strength_technique"] *  $duration + $player["strength_technic"]));
				$columns["w_kondition"] = min(100, max(1, $bookingInfo["effect_strength_stamina"] *  $duration + $player["strength_stamina"]));
				$columns["w_frische"] = min(100, max(1, $bookingInfo["effect_strength_freshness"] *  $duration + $player["strength_freshness"]));
				$columns["w_zufriedenheit"] = min(100, max(1, $bookingInfo["effect_strength_satisfaction"] *  $duration + $player["strength_satisfaction"]));
				$db->queryUpdate($columns, $playerTable, $updateCondition, $player["id"]); }}
		$db->queryDelete($websoccer->getConfig("db_prefix") . "_trainingslager_belegung", "id = %d", $bookingInfo["id"]); }
	static function _getColumns() {
		$columns["id"] = "id";
		$columns["name"] = "name";
		$columns["land"] = "country";
		$columns["preis_spieler_tag"] = "costs";
		$columns["p_staerke"] = "effect_strength";
		$columns["p_technik"] = "effect_strength_technique";
		$columns["p_kondition"] = "effect_strength_stamina";
		$columns["p_frische"] = "effect_strength_freshness";
		$columns["p_zufriedenheit"] = "effect_strength_satisfaction";
		return $columns; }}
class TrainingDataService {
	static function countTrainers($websoccer, $db) {
		$fromTable = $websoccer->getConfig("db_prefix") . "_trainer";
		$whereCondition = "1=1";
		$columns = "COUNT(*) AS hits";
		$result = $db->querySelect($columns, $fromTable, $whereCondition);
		$trainers = $result->fetch_array();
		return $trainers["hits"]; }
	static function getTrainers($websoccer, $db, $startIndex, $entries_per_page) {
		$fromTable = $websoccer->getConfig("db_prefix") . "_trainer";
		$whereCondition = "1=1 ORDER BY salary DESC";
		$columns = "*";
		$limit = $startIndex .",". $entries_per_page;
		$trainers = array();
		$result = $db->querySelect($columns, $fromTable, $whereCondition, null, $limit);
		while ($trainer = $result->fetch_array()) $trainers[] = $trainer;
		return $trainers; }
	static function getTrainerById($websoccer, $db, $trainerId) {
		$fromTable = $websoccer->getConfig("db_prefix") . "_trainer";
		$whereCondition = "id = %d";
		$columns = "*";
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $trainerId);
		$trainer = $result->fetch_array();
		return $trainer; }
	static function countRemainingTrainingUnits($websoccer, $db, $teamId) {
		$columns = "COUNT(*) AS hits";
		$fromTable = $websoccer->getConfig("db_prefix") . "_training_unit";
		$whereCondition = "team_id = %d AND date_executed = 0 OR date_executed IS NULL";
		$parameters = $teamId;
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters);
		$units = $result->fetch_array();
		return $units["hits"]; }
	static function getLatestTrainingExecutionTime($websoccer, $db, $teamId) {
		$columns = "date_executed";
		$fromTable = $websoccer->getConfig("db_prefix") . "_training_unit";
		$whereCondition = "team_id = %d AND date_executed > 0 ORDER BY date_executed DESC";
		$parameters = $teamId;
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters, 1);
		$unit = $result->fetch_array();
		if (isset($unit["date_executed"])) return $unit["date_executed"];
		else return 0; }
	static function getValidTrainingUnit($websoccer, $db, $teamId) {
		$columns = "id,trainer_id";
		$fromTable = $websoccer->getConfig("db_prefix") . "_training_unit";
		$whereCondition = "team_id = %d AND date_executed = 0 OR date_executed IS NULL ORDER BY id ASC";
		$parameters = $teamId;
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters, 1);
		$unit = $result->fetch_array();
		return $unit; }
	static function getTrainingUnitById($websoccer, $db, $teamId, $unitId) {
		$columns = "*";
		$fromTable = $websoccer->getConfig("db_prefix") . "_training_unit";
		$whereCondition = "id = %d AND team_id = %d";
		$parameters = array($unitId, $teamId);
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters, 1);
		$unit = $result->fetch_array();
		return $unit; }}
class TransfermarketDataService {
	static function getHighestBidForPlayer($websoccer, $db, $playerId, $transferStart, $transferEnd) {
		$columns['B.id'] = 'bid_id';
		$columns['B.abloese'] = 'amount';
		$columns['B.handgeld'] = 'hand_money';
		$columns['B.vertrag_spiele'] = 'contract_matches';
		$columns['B.vertrag_gehalt'] = 'contract_salary';
		$columns['B.vertrag_torpraemie'] = 'contract_goalbonus';
		$columns['B.datum'] = 'date';
		$columns['C.id'] = 'team_id';
		$columns['C.name'] = 'team_name';
		$columns['U.id'] = 'user_id';
		$columns['U.nick'] = 'user_name';
		$fromTable = $websoccer->getConfig('db_prefix') . '_transfer_angebot AS B';
		$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') . '_verein AS C ON C.id = B.verein_id';
		$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') . '_user AS U ON U.id = B.user_id';
		$whereCondition = 'B.spieler_id = %d AND B.datum >= %d AND B.datum <= %d ORDER BY B.datum DESC';
		$parameters = array($playerId, $transferStart, $transferEnd);
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters, 1);
		$bid = $result->fetch_array();
		return $bid; }
	static function getCurrentBidsOfTeam($websoccer, $db, $teamId) {
		$columns['B.abloese'] = 'amount';
		$columns['B.handgeld'] = 'hand_money';
		$columns['B.vertrag_spiele'] = 'contract_matches';
		$columns['B.vertrag_gehalt'] = 'contract_salary';
		$columns['B.vertrag_torpraemie'] = 'contract_goalbonus';
		$columns['B.datum'] = 'date';
		$columns['B.ishighest'] = 'ishighest';
		$columns['P.id'] = 'player_id';
		$columns['P.vorname'] = 'player_firstname';
		$columns['P.nachname'] = 'player_lastname';
		$columns['P.kunstname'] = 'player_pseudonym';
		$columns['P.transfer_ende'] = 'auction_end';
		$fromTable = $websoccer->getConfig('db_prefix') . '_transfer_angebot AS B';
		$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') . '_verein AS C ON C.id = B.verein_id';
		$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') . '_spieler AS P ON P.id = B.spieler_id';
		$whereCondition = 'C.id = %d AND P.transfer_ende >= %d ORDER BY B.datum DESC, P.transfer_ende ASC';
		$parameters = array($teamId, $websoccer->getNowAsTimestamp());
		$bids = array();
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters, 20);
		while ($bid = $result->fetch_array()) {
			if (!isset($bids[$bid['player_id']])) $bids[$bid['player_id']] = $bid; }
		return $bids; }
	static function getLatestBidOfUser($websoccer, $db, $userId) {
		$columns['B.abloese'] = 'amount';
		$columns['B.handgeld'] = 'hand_money';
		$columns['B.vertrag_spiele'] = 'contract_matches';
		$columns['B.vertrag_gehalt'] = 'contract_salary';
		$columns['B.vertrag_torpraemie'] = 'contract_goalbonus';
		$columns['B.datum'] = 'date';
		$columns['P.id'] = 'player_id';
		$columns['P.vorname'] = 'player_firstname';
		$columns['P.nachname'] = 'player_lastname';
		$columns['P.transfer_ende'] = 'auction_end';
		$fromTable = $websoccer->getConfig('db_prefix') . '_transfer_angebot AS B';
		$fromTable .= ' INNER JOIN ' . $websoccer->getConfig('db_prefix') . '_spieler AS P ON P.id = B.spieler_id';
		$whereCondition = 'B.user_id = %d ORDER BY B.datum DESC';
		$parameters = $userId;
		$bids = array();
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters, 1);
		$bid = $result->fetch_array();
		return $bid; }
	static function getCompletedTransfersOfUser($websoccer, $db, $userId) {
		$whereCondition = 'T.buyer_user_id = %d OR T.seller_user_id = %d ORDER BY T.datum DESC';
		$parameters = array($userId, $userId);
		return self::getCompletedTransfers($websoccer, $db, $whereCondition, $parameters); }
	static function getCompletedTransfersOfTeam($websoccer, $db, $teamId) {
		$whereCondition = 'SELLER.id = %d OR BUYER.id = %d ORDER BY T.datum DESC';
		$parameters = array($teamId, $teamId);
		return self::getCompletedTransfers($websoccer, $db, $whereCondition, $parameters); }
	static function getCompletedTransfersOfPlayer($websoccer, $db, $playerId) {
		$whereCondition = 'T.spieler_id = %d ORDER BY T.datum DESC';
		$parameters = array($playerId);
		return self::getCompletedTransfers($websoccer, $db, $whereCondition, $parameters); }
	static function getLastCompletedTransfers($websoccer, $db) {
		$whereCondition = '1=1 ORDER BY T.datum DESC';
		return self::getCompletedTransfers($websoccer, $db, $whereCondition, array()); }
	static function getCompletedTransfers($websoccer, $db, $whereCondition, $parameters) {
		$transfers = array();
		$columns['T.datum'] = 'transfer_date';
		$columns['P.id'] = 'player_id';
		$columns['P.vorname'] = 'player_firstname';
		$columns['P.nachname'] = 'player_lastname';
		$columns['SELLER.id'] = 'from_id';
		$columns['SELLER.name'] = 'from_name';
		$columns['BUYER.id'] = 'to_id';
		$columns['BUYER.name'] = 'to_name';
		$columns['T.directtransfer_amount'] = 'directtransfer_amount';
		$columns['EP1.id'] = 'exchangeplayer1_id';
		$columns['EP1.kunstname'] = 'exchangeplayer1_pseudonym';
		$columns['EP1.vorname'] = 'exchangeplayer1_firstname';
		$columns['EP1.nachname'] = 'exchangeplayer1_lastname';
		$columns['EP2.id'] = 'exchangeplayer2_id';
		$columns['EP2.kunstname'] = 'exchangeplayer2_pseudonym';
		$columns['EP2.vorname'] = 'exchangeplayer2_firstname';
		$columns['EP2.nachname'] = 'exchangeplayer2_lastname';
		$fromTable = $websoccer->getConfig('db_prefix') . '_transfer AS T';
		$fromTable .= ' INNER JOIN ' .$websoccer->getConfig('db_prefix') . '_spieler AS P ON P.id = T.spieler_id';
		$fromTable .= ' INNER JOIN ' .$websoccer->getConfig('db_prefix') . '_verein AS BUYER ON BUYER.id = T.buyer_club_id';
		$fromTable .= ' LEFT JOIN ' .$websoccer->getConfig('db_prefix') . '_verein AS SELLER ON SELLER.id = T.seller_club_id';
		$fromTable .= ' LEFT JOIN ' .$websoccer->getConfig('db_prefix') . '_spieler AS EP1 ON EP1.id = T.directtransfer_player1';
		$fromTable .= ' LEFT JOIN ' .$websoccer->getConfig('db_prefix') . '_spieler AS EP2 ON EP2.id = T.directtransfer_player2';
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters, 20);
		while ($transfer = $result->fetch_array()) {
			$transfer['hand_money'] = 0;
			$transfer['amount'] = $transfer['directtransfer_amount'];
			$transfers[] = $transfer; }
		return $transfers; }
	static function movePlayersWithoutTeamToTransfermarket($websoccer, $db) {
		$columns['unsellable'] = 0;
		$columns['lending_fee'] = 0;
		$columns['lending_owner_id'] = 0;
		$columns['lending_matches'] = 0;
		$fromTable = $websoccer->getConfig('db_prefix') . '_spieler';
		$whereCondition = 'status = 1 AND (transfermarkt != \'1\' AND (verein_id = 0 OR verein_id IS NULL) OR transfermarkt != \'1\' AND verein_id > 0 AND vertrag_spiele < 1 OR transfermarkt = \'1\' AND verein_id > 0 AND vertrag_spiele < 1)';
		$result = $db->querySelect('id, verein_id', $fromTable, $whereCondition);
		while($player = $result->fetch_array()) {
			$team = TeamsDataService::getTeamSummaryById($websoccer, $db, $player['verein_id']);
			if ($team == NULL || $team['user_id']) {
				if ($team['user_id']) UserInactivityDataService::increaseContractExtensionField($websoccer, $db, $team['user_id']);
				$columns['transfermarkt'] = '1';
				$columns['transfer_start'] = $websoccer->getNowAsTimestamp();
				$columns['transfer_ende'] = $columns['transfer_start'] + 24 * 3600 * $websoccer->getConfig('transfermarket_duration_days');
				$columns['transfer_mindestgebot'] = 0;
				$columns['verein_id'] = ''; }
			else {
				$columns['transfermarkt'] = '0';
				$columns['transfer_start'] = '0';
				$columns['transfer_ende'] = '0';
				$columns['vertrag_spiele'] = '5';
				$columns['verein_id'] = $player['verein_id']; }
			$db->queryUpdate($columns, $fromTable, 'id = %d', $player['id']); }}
	static function executeOpenTransfers($websoccer, $db) {
		$columns['P.id'] = 'player_id';
		$columns['P.transfer_start'] = 'transfer_start';
		$columns['P.transfer_ende'] = 'transfer_end';
		$columns['P.vorname'] = 'first_name';
		$columns['P.nachname'] = 'last_name';
		$columns['P.kunstname'] = 'pseudonym';
		$columns['C.id'] = 'team_id';
		$columns['C.name'] = 'team_name';
		$columns['C.user_id'] = 'team_user_id';
		$fromTable = $websoccer->getConfig('db_prefix') . '_spieler AS P';
		$fromTable .= ' LEFT JOIN ' . $websoccer->getConfig('db_prefix') . '_verein AS C ON C.id = P.verein_id';
		$whereCondition = 'P.transfermarkt = \'1\' AND P.status = \'1\' AND P.transfer_ende < %d';
		$parameters = $websoccer->getNowAsTimestamp();
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters, 50);
		while ($player = $result->fetch_array()) {
			$bid = self::getHighestBidForPlayer($websoccer, $db, $player['player_id'], $player['transfer_start'], $player['transfer_end']);
			if (!isset($bid['bid_id'])) self::extendDuration($websoccer, $db, $player['player_id']);
			else self::transferPlayer($websoccer, $db, $player, $bid); }}
	static function getTransactionsBetweenUsers($websoccer, $db, $user1, $user2) {
		$columns = 'COUNT(*) AS number';
		$fromTable = $websoccer->getConfig('db_prefix') .'_transfer';
		$whereCondition = 'datum >= %d AND (seller_user_id = %d AND buyer_user_id = %d OR seller_user_id = %d AND buyer_user_id = %d)';
		$parameters = array($websoccer->getNowAsTimestamp() - 30 * 3600 * 24, $user1, $user2, $user2, $user1);
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters);
		$transactions = $result->fetch_array();
		if (isset($transactions['number'])) return $transactions['number'];
		return 0; }
	static function awardUserForTrades($websoccer, $db, $userId) {
		$result = $db->querySelect('COUNT(*) AS hits', $websoccer->getConfig('db_prefix') . '_transfer', 'buyer_user_id = %d OR seller_user_id = %d', array($userId, $userId));
		$transactions = $result->fetch_array();
		if (!$transactions || !$transactions['hits']) return;
		BadgesDataService::awardBadgeIfApplicable($websoccer, $db, $userId, 'x_trades', $transactions['hits']); }
	function extendDuration($websoccer, $db, $playerId) {
		$fromTable = $websoccer->getConfig('db_prefix') . '_spieler';
		$columns['transfer_ende'] = $websoccer->getNowAsTimestamp() + 24 * 3600 * $websoccer->getConfig('transfermarket_duration_days');
		$whereCondition = 'id = %d';
		$db->queryUpdate($columns, $fromTable, $whereCondition, $playerId); }
	function transferPlayer($websoccer, $db, $player, $bid) {
		$playerName = (strlen($player['pseudonym'])) ? $player['pseudonym'] : $player['first_name'] . ' ' . $player['last_name'];
		if ($player['team_id'] < 1) {
			if ($bid['hand_money'] > 0) BankAccountDataService::debitAmount($websoccer, $db, $bid['team_id'], $bid['hand_money'], 'transfer_transaction_subject_handmoney', $playerName); }
		else {
			BankAccountDataService::debitAmount($websoccer, $db, $bid['team_id'], $bid['amount'], 'transfer_transaction_subject_fee', $player['team_name']);
			BankAccountDataService::creditAmount($websoccer, $db, $player['team_id'], $bid['amount'], 'transfer_transaction_subject_fee', $bid['team_name']); }
		$fromTable = $websoccer->getConfig('db_prefix') . '_spieler';
		$columns['transfermarkt'] = 0;
		$columns['transfer_start'] = 0;
		$columns['transfer_ende'] = 0;
		$columns['verein_id'] = $bid['team_id'];
		$columns['vertrag_spiele'] = $bid['contract_matches'];
		$columns['vertrag_gehalt'] = $bid['contract_salary'];
		$columns['vertrag_torpraemie'] = $bid['contract_goalbonus'];
		$whereCondition = 'id = %d';
		$db->queryUpdate($columns, $fromTable, $whereCondition, $player['player_id']);
		$logcolumns['spieler_id'] = $player['player_id'];
		$logcolumns['seller_user_id'] = $player['team_user_id'];
		$logcolumns['seller_club_id'] = $player['team_id'];
		$logcolumns['buyer_user_id'] = $bid['user_id'];
		$logcolumns['buyer_club_id'] = $bid['team_id'];
		$logcolumns['datum'] = $websoccer->getNowAsTimestamp();
		$logcolumns['directtransfer_amount'] = $bid['amount'];
		$logTable = $websoccer->getConfig('db_prefix') . '_transfer';
		$db->queryInsert($logcolumns, $logTable);
		NotificationsDataService::createNotification($websoccer, $db, $bid['user_id'], 'transfer_bid_notification_transfered', array('player' => $playerName), 'transfermarket', 'player', 'id=' . $player['player_id']);
		$db->queryDelete($websoccer->getConfig('db_prefix') . '_transfer_angebot', 'spieler_id = %d', $player['player_id']);
		self::awardUserForTrades($websoccer, $db, $bid['user_id']);
		if ($player['team_user_id']) self::awardUserForTrades($websoccer, $db, $player['team_user_id']); }}
class UserInactivityDataService {
	static function getUserInactivity($websoccer, $db, $userId) {
		$columns["id"] = "id";
		$columns["login"] = "login";
		$columns["login_check"] = "login_check";
		$columns["aufstellung"] = "tactics";
		$columns["transfer"] = "transfer";
		$columns["transfer_check"] = "transfer_check";
		$columns["vertragsauslauf"] = "contractextensions";
		$fromTable = $websoccer->getConfig("db_prefix") . "_user_inactivity";
		$whereCondition = "user_id = %d";
		$parameters = $userId;
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters, 1);
		$inactivity = $result->fetch_array();
		if (!$inactivity) {
			$newcolumns["user_id"] = $userId;
			$db->queryInsert($newcolumns, $fromTable);
			return self::getUserInactivity($websoccer, $db, $userId); }
		return $inactivity; }
	static function computeUserInactivity($websoccer, $db, $userId) {
		$inactivity = self::getUserInactivity($websoccer, $db, $userId);
		$now = $websoccer->getNowAsTimestamp();
		$checkBoundary = $now - 24 * 3600;
		$updatecolumns = array();
		$user = UsersDataService::getUserById($websoccer, $db, $userId);
		if ($inactivity["login_check"] < $checkBoundary) {
			$inactiveDays = round(($now - $user["lastonline"]) / (24 * 3600));
			$updatecolumns["login"] = min(100, round($inactiveDays * INACTIVITY_PER_DAY_LOGIN));
			$updatecolumns["login_check"] = $now;
			$formationTable = $websoccer->getConfig("db_prefix") . "_aufstellung AS F";
			$formationTable .= " INNER JOIN " . $websoccer->getConfig("db_prefix") . "_verein AS T ON T.id = F.verein_id";
			$result = $db->querySelect("F.datum AS date", $formationTable, "T.user_id = %d", $userId);
			$formation = $result->fetch_array();
			if ($formation) {
				$inactiveDays = round(($now - $formation["date"]) / (24 * 3600));
				$updatecolumns["aufstellung"] = min(100, round($inactiveDays * INACTIVITY_PER_DAY_TACTICS)); }}
		if ($inactivity["transfer_check"] < $checkBoundary) {
			$bid = TransfermarketDataService::getLatestBidOfUser($websoccer, $db, $userId);
			$transferBenchmark = $user["registration_date"];
			if ($bid) $transferBenchmark = $bid["date"];
			$inactiveDays = round(($now - $transferBenchmark) / (24 * 3600));
			$updatecolumns["transfer"] = min(100, round($inactiveDays * INACTIVITY_PER_DAY_TRANSFERS));
			$updatecolumns["transfer_check"] = $now; }
		if (count($updatecolumns)) {
			$fromTable = $websoccer->getConfig("db_prefix") . "_user_inactivity";
			$db->queryUpdate($updatecolumns, $fromTable, "id = %d", $inactivity["id"]); }}
	static function resetContractExtensionField($websoccer, $db, $userId) {
		$inactivity = self::getUserInactivity($websoccer, $db, $userId);
		$updatecolumns["vertragsauslauf"] = 0;
		$fromTable = $websoccer->getConfig("db_prefix") . "_user_inactivity";
		$db->queryUpdate($updatecolumns, $fromTable, "id = %d", $inactivity["id"]); }
	static function increaseContractExtensionField($websoccer, $db, $userId) {
		$inactivity = self::getUserInactivity($websoccer, $db, $userId);
		$updatecolumns["vertragsauslauf"] = min(100, $inactivity["contractextensions"] + INACTIVITY_PER_CONTRACTEXTENSION);
		$fromTable = $websoccer->getConfig("db_prefix") . "_user_inactivity";
		$db->queryUpdate($updatecolumns, $fromTable, "id = %d", $inactivity["id"]); }}
class UsersDataService {
	static function createLocalUser($websoccer, $db, $nick = null, $email = null) {
		$username = trim($nick);
		$emailAddress = strtolower(trim($email));
		if (!strlen($username) && !strlen($emailAddress)) throw new Exception("UsersDataService::createBlankUser(): Either user name or e-mail must be provided in order to create a new internal user.");
		if (strlen($username) && self::getUserIdByNick($websoccer, $db, $username) > 0) throw new Exception("Nick name is already in use.");
		if (strlen($emailAddress) && self::getUserIdByEmail($websoccer, $db, $emailAddress) > 0) throw new Exception("E-Mail address is already in use.");
		$i18n = I18n::getInstance($websoccer->getConfig("supported_languages"));
		$columns = array("nick" => $username, "email" => $emailAddress, "status" => "1", "datum_anmeldung" => $websoccer->getNowAsTimestamp(), "lang" => $i18n->getCurrentLanguage() );
		if ($websoccer->getConfig("premium_initial_credit")) $columns["premium_balance"] = $websoccer->getConfig("premium_initial_credit");
		$db->queryInsert($columns, $websoccer->getConfig("db_prefix") . "_user");
		if (strlen($username)) $userId = self::getUserIdByNick($websoccer, $db, $username);
		else $userId = self::getUserIdByEmail($websoccer, $db, $emailAddress);
		$event = new UserRegisteredEvent($websoccer, $db, I18n::getInstance($websoccer->getConfig("supported_languages")), $userId, $username, $emailAddress);
		PluginMediator::dispatchEvent($event);
		return $userId; }
	static function countActiveUsersWithHighscore($websoccer, $db) {
		$columns = "COUNT(id) AS hits";
		$fromTable = $websoccer->getConfig("db_prefix") . "_user";
		$whereCondition = "status = 1 AND highscore > 0 GROUP BY id";
		$result = $db->querySelect($columns, $fromTable, $whereCondition);
		if (!$result) $users = 0;
		else $users = $result->num_rows;
		return $users; }
	static function getActiveUsersWithHighscore($websoccer, $db, $startIndex, $entries_per_page) {
		$columns["U.id"] = "id";
		$columns["nick"] = "nick";
		$columns["email"] = "email";
		$columns["U.picture"] = "picture";
		$columns["highscore"] = "highscore";
		$columns["datum_anmeldung"] = "registration_date";
		$columns["C.id"] = "team_id";
		$columns["C.name"] = "team_name";
		$columns["C.bild"] = "team_picture";
		$limit = $startIndex .",". $entries_per_page;
		$fromTable = $websoccer->getConfig("db_prefix") . "_user AS U";
		//- owsPro - we have to change at PHP 8
		//- $fromTable .= " LEFT JOIN " . $websoccer->getConfig("db_prefix") . "_verein AS C ON C.user_id = U.id";
		$fromTable .= " LEFT JOIN " . $websoccer->getConfig("db_prefix") . "_verein AS C ON C.id = U.id";
		$whereCondition = "U.status = 1 AND highscore > 0 GROUP BY id ORDER BY highscore DESC, datum_anmeldung ASC";
		$result = $db->querySelect($columns, $fromTable, $whereCondition, null, $limit);
		$users = array();
		while ($user = $result->fetch_array()) {
			$user["picture"] = self::getUserProfilePicture($websoccer, $user["picture"], $user["email"]);
			$users[] = $user; }
		return $users; }
	static function getUserById($websoccer, $db, $userId) {
		$columns["id"] = "id";
		$columns["nick"] = "nick";
		$columns["email"] = "email";
		$columns["highscore"] = "highscore";
		$columns["fanbeliebtheit"] = "popularity";
		$columns["datum_anmeldung"] = "registration_date";
		$columns["lastonline"] = "lastonline";
		$columns["picture"] = "picture";
		$columns["history"] = "history";
		$columns["name"] = "name";
		$columns["wohnort"] = "place";
		$columns["land"] = "country";
		$columns["geburtstag"] = "birthday";
		$columns["beruf"] = "occupation";
		$columns["interessen"] = "interests";
		$columns["lieblingsverein"] = "favorite_club";
		$columns["homepage"] = "homepage";
		$columns["premium_balance"] = "premium_balance";
		$fromTable = $websoccer->getConfig("db_prefix") . "_user";
		$whereCondition = "id = %d AND status = 1";
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $userId);
		$user = $result->fetch_array();
		if ($user) {
			$user["picture_uploadfile"] = $user["picture"];
			$user["picture"] = self::getUserProfilePicture($websoccer, $user["picture"], $user["email"], 120); }
		return $user; }
	static function getUserIdByNick($websoccer, $db, $nick) {
		$columns = "id";
		$fromTable = $websoccer->getConfig("db_prefix") . "_user";
		$whereCondition = "nick = '%s' AND status = 1";
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $nick);
		$user = $result->fetch_array();
		if (isset($user["id"])) return $user["id"];
		return -1; }
	static function getUserIdByEmail($websoccer, $db, $email) {
		$columns = "id";
		$fromTable = $websoccer->getConfig("db_prefix") . "_user";
		$whereCondition = "email = '%s' AND status = 1";
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $email);
		$user = $result->fetch_array();
		if (isset($user["id"])) return $user["id"];
		return -1; }
	static function findUsernames($websoccer, $db, $nickStart) {
		$columns = "nick";
		$fromTable = $websoccer->getConfig("db_prefix") . "_user";
		$whereCondition = "UPPER(nick) LIKE '%s%%' AND status = 1";
		$result = $db->querySelect($columns, $fromTable, $whereCondition, strtoupper($nickStart), 10);
		$users = array();
		while($user = $result->fetch_array()) $users[] = $user["nick"];
		return $users; }
	static function getUserProfilePicture($websoccer, $fileName, $email, $size = 40) {
		if (strlen($fileName)) return $websoccer->getConfig("context_root") . "/uploads/users/" . $fileName;
		return self::getGravatarUserProfilePicture($websoccer, $email, $size); }
	static function getGravatarUserProfilePicture($websoccer, $email, $size = 40) {
		if (strlen($email) && $websoccer->getConfig("gravatar_enable")) {
			if (empty($_SERVER['HTTPS'])) $picture = "http://www.";
			else $picture = "https://secure.";
			$picture .= "gravatar.com/avatar/" . md5(strtolower($email));
			$picture .= "?s=" . $size;
			$picture .= "&d=mm";
			return $picture; }
		else return null; }
	static function countOnlineUsers($websoccer, $db) {
		$timeBoundary = $websoccer->getNowAsTimestamp() - 15 * 60;
		$result = $db->querySelect("COUNT(*) AS hits", $websoccer->getConfig("db_prefix") . "_user", "lastonline >= %d", $timeBoundary);
		$users = $result->fetch_array();
		if (isset($users["hits"])) return $users["hits"];
		return 0; }
	static function getOnlineUsers($websoccer, $db, $startIndex, $entries_per_page) {
		$timeBoundary = $websoccer->getNowAsTimestamp() - 15 * 60;
		$columns["U.id"] = "id";
		$columns["nick"] = "nick";
		$columns["email"] = "email";
		$columns["U.picture"] = "picture";
		$columns["lastonline"] = "lastonline";
		$columns["lastaction"] = "lastaction";
		$columns["c_hideinonlinelist"] = "hideinonlinelist";
		$columns["C.id"] = "team_id";
		$columns["C.name"] = "team_name";
		$columns["C.bild"] = "team_picture";
		$limit = $startIndex .",". $entries_per_page;
		$fromTable = $websoccer->getConfig("db_prefix") . "_user AS U";
		//- owsPro - we have to change at PHP 8
		//- $fromTable .= " LEFT JOIN " . $websoccer->getConfig("db_prefix") . "_verein AS C ON C.user_id = U.id";
		$fromTable .= " LEFT JOIN " . $websoccer->getConfig("db_prefix") . "_verein AS C ON C.id = U.id";
		$whereCondition = "U.status = 1 AND lastonline >= %d GROUP BY id ORDER BY lastonline DESC";
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $timeBoundary, $limit);
		$users = array();
		while ($user = $result->fetch_array()) {
			$user["picture"] = self::getUserProfilePicture($websoccer, $user["picture"], $user["email"]);
			$users[] = $user; }
		return $users; }
	static function countTotalUsers($websoccer, $db) {
		$result = $db->querySelect("COUNT(*) AS hits", $websoccer->getConfig("db_prefix") . "_user", "status = 1");
		$users = $result->fetch_array();
		if (isset($users["hits"])) return $users["hits"];
		return 0; }}
class YouthMatchesDataService {
	static function getYouthMatchinfoById($websoccer, $db, $i18n, $matchId) {
		$columns = "M.*, HOME.name AS home_team_name, GUEST.name AS guest_team_name";
		$fromTable = $websoccer->getConfig("db_prefix") . "_youthmatch AS M";
		$fromTable .= " INNER JOIN " . $websoccer->getConfig("db_prefix") . "_verein AS HOME ON HOME.id = M.home_team_id";
		$fromTable .= " INNER JOIN " . $websoccer->getConfig("db_prefix") . "_verein AS GUEST ON GUEST.id = M.guest_team_id";
		$result = $db->querySelect($columns, $fromTable, "M.id = %d", $matchId);
		$match = $result->fetch_array();
		if (!$match) throw new Exception($i18n->getMessage(MSG_KEY_ERROR_PAGENOTFOUND));
		return $match; }
	static function countMatchesOfTeamOnSameDay($websoccer, $db, $teamId, $timestamp) {
		$fromTable = $websoccer->getConfig("db_prefix") . "_youthmatch";
		$dateObj = new DateTime();
		$dateObj->setTimestamp($timestamp);
		$dateObj->setTime(0, 0, 0);
		$minTimeBoundary = $dateObj->getTimestamp();
		$dateObj->setTime(23, 59, 59);
		$maxTimeBoundary = $dateObj->getTimestamp();
		$result = $db->querySelect("COUNT(*) AS hits", $fromTable, "(home_team_id = %d OR guest_team_id = %d) AND matchdate BETWEEN %d AND %d", array($teamId, $teamId, $minTimeBoundary, $maxTimeBoundary));
		$rows = $result->fetch_array();
		if ($rows) return $rows["hits"];
		return 0; }
	static function countMatchesOfTeam($websoccer, $db, $teamId) {
		$fromTable = $websoccer->getConfig("db_prefix") . "_youthmatch";
		$result = $db->querySelect("COUNT(*) AS hits", $fromTable, "(home_team_id = %d OR guest_team_id = %d)", array($teamId, $teamId));
		$rows = $result->fetch_array();
		if ($rows) return $rows["hits"];
		return 0; }
	static function getMatchesOfTeam($websoccer, $db, $teamId, $startIndex, $entries_per_page) {
		$tablePrefix = $websoccer->getConfig("db_prefix");
		$fromTable = $tablePrefix . "_youthmatch AS M";
		$fromTable .= " INNER JOIN " . $tablePrefix . "_verein AS HOME ON M.home_team_id = HOME.id";
		$fromTable .= " INNER JOIN " . $tablePrefix . "_verein AS GUEST ON M.guest_team_id = GUEST.id";
		$fromTable .= " LEFT JOIN " . $tablePrefix . "_user AS HOMEUSER ON HOME.user_id = HOMEUSER.id";
		$fromTable .= " LEFT JOIN " . $tablePrefix . "_user AS GUESTUSER ON GUEST.user_id = GUESTUSER.id";
		$columns["M.id"] = "match_id";
		$columns["HOME.name"] = "home_team";
		$columns["HOME.bild"] = "home_team_picture";
		$columns["HOME.id"] = "home_id";
		$columns["HOMEUSER.id"] = "home_user_id";
		$columns["HOMEUSER.nick"] = "home_user_nick";
		$columns["HOMEUSER.email"] = "home_user_email";
		$columns["HOMEUSER.picture"] = "home_user_picture";
		$columns["GUEST.name"] = "guest_team";
		$columns["GUEST.bild"] = "guest_team_picture";
		$columns["GUEST.id"] = "guest_id";
		$columns["GUESTUSER.id"] = "guest_user_id";
		$columns["GUESTUSER.nick"] = "guest_user_nick";
		$columns["GUESTUSER.email"] = "guest_user_email";
		$columns["GUESTUSER.picture"] = "guest_user_picture";
		$columns["M.home_goals"] = "home_goals";
		$columns["M.guest_goals"] = "guest_goals";
		$columns["M.simulated"] = "simulated";
		$columns["M.matchdate"] = "date";
		$matches = array();
		$limit = $startIndex . "," . $entries_per_page;
		$result = $db->querySelect($columns, $fromTable, "(home_team_id = %d OR guest_team_id = %d) ORDER BY M.matchdate DESC", array($teamId, $teamId), $limit);
		while ($matchinfo = $result->fetch_array()) {
			$matchinfo["home_user_picture"] = UsersDataService::getUserProfilePicture($websoccer, $matchinfo["home_user_picture"], $matchinfo["home_user_email"]);
			$matchinfo["guest_user_picture"] = UsersDataService::getUserProfilePicture($websoccer, $matchinfo["guest_user_picture"], $matchinfo["guest_user_email"]);
			$matches[] = $matchinfo; }
		return $matches; }
	static function createMatchReportItem($websoccer, $db, $matchId, $minute, $messageKey, $messageData = null, $isHomeTeamWithBall = FALSE) {
		$messageDataStr = "";
		if (is_array($messageData)) $messageDataStr = json_encode($messageData);
		$columns = array("match_id" => $matchId, "minute" => $minute, "message_key" => $messageKey, "message_data" => $messageDataStr, "home_on_ball" => ($isHomeTeamWithBall) ? "1" : "0" );
		$db->queryInsert($columns, $websoccer->getConfig("db_prefix") . "_youthmatch_reportitem"); }
	static function getMatchReportItems($websoccer, $db, $i18n, $matchId) {
		$result = $db->querySelect("*", $websoccer->getConfig("db_prefix") . "_youthmatch_reportitem", "match_id = %d ORDER BY minute ASC", $matchId);
		$items = array();
		while ($item = $result->fetch_array()) {
			$message = $i18n->getMessage($item["message_key"]);
			if (strlen($item["message_data"])) {
				$messageData = json_decode($item["message_data"], true);
				if ($messageData) {
					foreach ($messageData as $placeholderName => $placeholderValue) $message = str_replace("{" . $placeholderName . "}", htmlspecialchars($placeholderValue, ENT_COMPAT, "UTF-8"), $message); }}
			$items[] = array("minute" => $item["minute"], "active_home" => $item["home_on_ball"], "message_key" => $item["message_key"], "message" => $message ); }
		return $items; }}
class YouthPlayersDataService {
	static function getYouthPlayerById($websoccer, $db, $i18n, $playerId) {
		$fromTable = $websoccer->getConfig("db_prefix") . "_youthplayer";
		$players = $db->queryCachedSelect("*", $fromTable, "id = %d", $playerId);
		if (!count($players)) throw new Exception($i18n->getMessage("error_page_not_found"));
		return $players[0]; }
	static function getYouthPlayersOfTeam($websoccer, $db, $teamId) {
		$fromTable = $websoccer->getConfig("db_prefix") . "_youthplayer";
		$whereCondition = "team_id = %d ORDER BY position ASC, lastname ASC, firstname ASC";
		$players = $db->queryCachedSelect("*", $fromTable, $whereCondition, $teamId);
		return $players; }
	static function countYouthPlayersOfTeam($websoccer, $db, $teamId) {
		$fromTable = $websoccer->getConfig("db_prefix") . "_youthplayer";
		$result = $db->querySelect("COUNT(*) AS hits", $fromTable, "team_id = %d", $teamId);
		$players = $result->fetch_array();
		if ($players) return $players["hits"];
		return 0; }
	static function computeSalarySumOfYouthPlayersOfTeam($websoccer, $db, $teamId) {
		$fromTable = $websoccer->getConfig("db_prefix") . "_youthplayer";
		$result = $db->querySelect("SUM(strength) AS strengthsum", $fromTable, "team_id = %d", $teamId);
		$players = $result->fetch_array();
		if ($players) return $players["strengthsum"] * $websoccer->getConfig("youth_salary_per_strength");
		return 0; }
	static function getYouthPlayersOfTeamByPosition($websoccer, $db, $clubId, $positionSort = "ASC") {
		$columns = "*";
		$fromTable = $websoccer->getConfig("db_prefix") . "_youthplayer";
		$whereCondition = "team_id = %d ORDER BY position ". $positionSort . ", lastname ASC, firstname ASC";
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $clubId, 50);
		$players = array();
		while ($player = $result->fetch_array()) {
			$player["position"] = PlayersDataService::_convertPosition($player["position"]);
			$player["player_nationality"] = $player["nation"];
			$player["player_nationality_filename"] = PlayersDataService::getFlagFilename($player["nation"]);
			$players[$player["position"]][] = $player; }
		return $players; }
	static function countTransferableYouthPlayers($websoccer, $db, $positionFilter = NULL) {
		$fromTable = $websoccer->getConfig("db_prefix") . "_youthplayer";
		$parameters = "";
		$whereCondition = "transfer_fee > 0";
		if ($positionFilter != NULL) {
			$whereCondition .= " AND position = '%s'";
			$parameters = $positionFilter; }
		$result = $db->querySelect("COUNT(*) AS hits", $fromTable, $whereCondition, $parameters);
		$players = $result->fetch_array();
		if ($players) return $players["hits"];
		return 0; }
	//- owsPro - Deprecated: Optional parameter declared before required parameter is implicitly treated as a required parameter
	//- static function getTransferableYouthPlayers($websoccer, $db, $positionFilter = NULL, $startIndex, $entries_per_page) {
	static function getTransferableYouthPlayers($websoccer, $db, $startIndex, $entries_per_page, $positionFilter = NULL ) {
		$columns = array( "P.id" => "player_id", "P.firstname" => "firstname", "P.lastname" => "lastname", "P.position" => "position", "P.nation" => "nation", "P.transfer_fee" => "transfer_fee", "P.age" => "age", "P.strength" => "strength",
			"P.st_matches" => "st_matches", "P.st_goals" => "st_goals", "P.st_assists" => "st_assists", "P.st_cards_yellow" => "st_cards_yellow", "P.st_cards_yellow_red" => "st_cards_yellow_red", "P.st_cards_red" => "st_cards_red", "P.team_id" => "team_id",
			"C.name" => "team_name", "C.bild" => "team_picture", "C.user_id" => "user_id", "U.nick" => "user_nick", "U.email" => "user_email", "U.picture" => "user_picture" );
		$fromTable = $websoccer->getConfig("db_prefix") . "_youthplayer AS P";
		$fromTable .= " INNER JOIN " . $websoccer->getConfig("db_prefix") . "_verein AS C ON C.id = P.team_id";
		$fromTable .= " LEFT JOIN " . $websoccer->getConfig("db_prefix") . "_user AS U ON U.id = C.user_id";
		$parameters = "";
		$whereCondition = "P.transfer_fee > 0";
		if ($positionFilter != NULL) {
			$whereCondition .= " AND P.position = '%s'";
			$parameters = $positionFilter; }
		$whereCondition .= " ORDER BY P.strength DESC";
		$players = array();
		$limit = $startIndex .",". $entries_per_page;
		$result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters, $limit);
		while ($player = $result->fetch_array()) {
			$player["user_picture"] = UsersDataService::getUserProfilePicture($websoccer, $player["user_picture"], $player["user_email"], 20);
			$player["nation_flagfile"] = PlayersDataService::getFlagFilename($player["nation"]);
			$players[] = $player; }
		return $players; }
	static function getScouts($websoccer, $db, $sortColumns = "expertise DESC, name ASC") {
		$result = $db->querySelect("*", $websoccer->getConfig("db_prefix") . "_youthscout", "1=1 ORDER BY " . $sortColumns);
		$scouts = array();
		while ($scout = $result->fetch_array()) $scouts[] = $scout;
		return $scouts; }
	static function getScoutById($websoccer, $db, $i18n, $scoutId) {
		$result = $db->querySelect("*", $websoccer->getConfig("db_prefix") . "_youthscout", "id = %d", $scoutId);
		$scout = $result->fetch_array();
		if (!$scout) throw new Exception($i18n->getMessage("youthteam_scouting_err_invalidscout"));
		return $scout; }
	static function getLastScoutingExecutionTime($websoccer, $db, $teamId) {
		$result = $db->querySelect("scouting_last_execution", $websoccer->getConfig("db_prefix") . "_verein", "id = %d", $teamId);
		$scouted = $result->fetch_array();
		if (!$scouted) return 0;
		return $scouted["scouting_last_execution"]; }
	static function getPossibleScoutingCountries() {
		$iterator = new DirectoryIterator(BASE_FOLDER . "/admin/config/names/");
		$countries = array();
		while($iterator->valid()) {
			if ($iterator->isDir() && !$iterator->isDot()) $countries[] = $iterator->getFilename();
			$iterator->next(); }
		return $countries; }
	static function countMatchRequests($websoccer, $db) {
		$fromTable = $websoccer->getConfig("db_prefix") . "_youthmatch_request";
		$result = $db->querySelect("COUNT(*) AS hits", $fromTable, "1=1");
		$requests = $result->fetch_array();
		if ($requests) return $requests["hits"];
		return 0; }
	static function getMatchRequests($websoccer, $db, $startIndex, $entries_per_page) {
		$columns = array("R.id" => "request_id", "R.matchdate" => "matchdate", "R.reward" => "reward", "C.name" => "team_name", "C.id" => "team_id", "U.id" => "user_id", "U.nick" => "user_nick", "U.email" => "user_email", "U.picture" => "user_picture" );
		$fromTable = $websoccer->getConfig("db_prefix") . "_youthmatch_request AS R";
		$fromTable .= " INNER JOIN " . $websoccer->getConfig("db_prefix") . "_verein AS C ON C.id = R.team_id";
		$fromTable .= " INNER JOIN " . $websoccer->getConfig("db_prefix") . "_user AS U ON U.id = C.user_id";
		$whereCondition = "1=1 ORDER BY R.matchdate ASC";
		$requests = array();
		$limit = $startIndex .",". $entries_per_page;
		$result = $db->querySelect($columns, $fromTable, $whereCondition, null, $limit);
		while ($request = $result->fetch_array()) {
			$request["user_picture"] = UsersDataService::getUserProfilePicture($websoccer, $request["user_picture"], $request["user_email"]);
			$requests[] = $request; }
		return $requests; }
	static function deleteInvalidOpenMatchRequests($websoccer, $db) {
		$timeBoundary = $websoccer->getNowAsTimestamp() + $websoccer->getConfig("youth_matchrequest_accept_hours_in_advance");
		$db->queryDelete($websoccer->getConfig("db_prefix") . "_youthmatch_request", "matchdate <= %d", $timeBoundary); }}