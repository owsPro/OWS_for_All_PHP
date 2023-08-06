<?php
/*This file is part of "OWS for All PHP" (Rolf Joseph)
  https://github.com/owsPro/OWS_for_All_PHP/
  A spinn-off for PHP Versions 5.5 to 8.2 from:
  OpenWebSoccer-Sim(Ingo Hofmann), https://github.com/ihofmann/open-websoccer.

  "OWS for All PHP" is is distributed in WITHOUT ANY WARRANTY;
  without even the implied warranty of MERCHANTABILITY
  or FITNESS FOR A PARTICULAR PURPOSE.

  See GNU Lesser General Public License Version 3 http://www.gnu.org/licenses/

*****************************************************************************/
								if(version_compare(PHP_VERSION,'5.5.0')<0){echo'Minimum PHP 5.5.0 !';exit;}include($_SERVER['DOCUMENT_ROOT'].'/frontbase.inc.php');$isOffline=FALSE;if(Config('offline')=='offline')$isOffline=TRUE;else{$offlineTimeSpansConfig=
								Config('offline_times');if(strlen($offlineTimeSpansConfig)){$timeSpans=explode(',',$offlineTimeSpansConfig);$now=Timestamp();foreach($timeSpans as$timeSpan){$timeSpanElements=explode('-',trim($timeSpan));if(count($timeSpanElements)!=2)
								throw new Exception('Configuration error: Recurring offline mode time span must have a format like 15:00-17:00.');$fromTimestamp=strtotime('today '.trim($timeSpanElements[0]));$toTimestamp=strtotime('today '.trim($timeSpanElements[1]));
								if($fromTimestamp<=$now&&$now<=$toTimestamp){$isOffline=TRUE;break;}}}}if($isOffline){$parameters['offline_message']=nl2br(Config('offline_text'));echo $website->getTemplateEngine($i18n)->loadTemplate('views/offline')->render($parameters);}
								else{if(!isset($_SESSION['badgechecked'])&&$website->getUser()->getRole()=='user'&&$website->getUser()->getClubId($website,$db)){$userId=$website->getUser()->id;$result=$db->querySelect('datum_anmeldung',Config('db_prefix').'_user','id=%d',
								$userId);$userinfo=$result->fetch_array();if($userinfo['datum_anmeldung']){$numberOfRegisteredDays=round((Timestamp()-$userinfo['datum_anmeldung'])/(3600*24));BadgesDataService::awardBadgeIfApplicable($website,$db,$userId,
								'membership_since_x_days',$numberOfRegisteredDays);}$_SESSION['badgechecked']=1;}$pageId=Request('page');$pageId=PageIdRouter::getTargetPageId($website,$i18n,$pageId);setPageId($pageId);$validationMessages=null;$actionId=Request('action');
								if($actionId!==NULL){try{$targetId=ActionHandler::handleAction($website,$db,$i18n,$actionId);if($targetId!=null)$pageId=$targetId;}catch(ValidationException$ve){$validationMessages=$ve->getMessages();
								$website->addFrontMessage(new FrontMessage('error',Message('validation_error_box_title'),Message('validation_error_box_message')));}catch(Exception$e){$website->addFrontMessage(new FrontMessage('error',Message('errorpage_title'),
								$e->getMessage()));}}setPageId($pageId);$navItems=NavigationBuilder::getNavigationItems($website,$i18n,$page,$pageId);$parameters['navItems']=$navItems;$parameters['breadcrumbItems']=BreadcrumbBuilder::getBreadcrumbItems($website,$i18n,$page,
								$pageId);header('Content-type:text/html;charset=utf-8');$viewHandler=new ViewHandler($website,$db,$i18n,$page,$block,$validationMessages);try{print_r($viewHandler->handlePage($pageId,$parameters));}catch(AccessDeniedException$e){
								if($website->getUser()->getRole()=='guest'){$website->addFrontMessage(new FrontMessage('error',$e->getMessage(),''));print_r($viewHandler->handlePage('login',$parameters));}else renderErrorPage($website,$i18n,$viewHandler,$e->getMessage(),
								$parameters);}catch (Exception $e){renderErrorPage($website,$i18n,$viewHandler,$e->getMessage(),$parameters);}}