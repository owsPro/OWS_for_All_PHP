<?php
/*This file is part of "OWS for All PHP" (Rolf Joseph)
  https://github.com/owsPro/OWS_for_All_PHP/
  A spinn-off for PHP Versions 5.5 to 8.2 from:
  OpenWebSoccer-Sim(Ingo Hofmann), https://github.com/ihofmann/open-websoccer.

  "OWS for All PHP" is is distributed in WITHOUT ANY WARRANTY;
  without even the implied warranty of MERCHANTABILITY
  or FITNESS FOR A PARTICULAR PURPOSE.

  See GNU Lesser General Public License Version 3 http://www.gnu.org/licenses/

 - Features like the OpenWebSoccer from Ingo Hofmann
 - Automatic Twig version switcher that sets the correct Twig version depending on the PHP version used, actual PHP 8.2 with Twig 3.6.0.
 - All files from the classes folder in one file
 - All comments removed from the original
 - Source code adapted for more clarity.
 - Already much less source code.
 - Easier bug search and adaptations for future PHP versions.
 - The installation is a high level of compatibility.
 - Additional configuration and settings, e.g. through add-ons and basic configuration and settings, which are supplemented or overwritten by '/cache/wsconfigadmin.inc.php'.
 - Terms and conditions translated into many languages.

 This version can still be used with the database prefix!
=====================================================================================*/
@include($_SERVER['DOCUMENT_ROOT'].'/generated/config.inc.php');
class Value{static$pageId,$_createdConverters,$_eventlistenerConfigs,$_addedPlayers;}
			// set_exception_handler('TopLevelException');
			// function TopLevelException($exception){throw new Exception('An undisclosed exception error has occurred!');echo$e->errorMessage();echo'<b>Exception:</b>'.$exception->getMessage();}
			function connectDB($host,$user,$password,$dbname){Value::$connection=new mysqli($db->connect(Config('db_host'),Config('db_user'),Config('db_passwort'),Config('db_name')));Value::$connection->set_charset('utf8');if(mysqli_connect_error()){
							throw new Exception('Die Datenbank ist zur Zeit nicht verf�gbar: ('.mysqli_connect_errno().') '.mysqli_connect_error());}}
			function Config($name){global$conf;if(!isset($conf[$name]))throw new Exception('Missing configuration: '.$name);return$conf[$name];}
			function Message($messageKey,$paramaters=NULL){global$msg;if(!hasMessage($messageKey)){return'???'.$messageKey.'???';}$message=stripslashes($msg[$messageKey]);if($paramaters!=NULL){$message=sprintf($message,$paramaters);}return$message;}
			function hasMessage($messageKey){global$msg;return isset($msg[$messageKey]);}
			function Request($name){if(isset($_REQUEST[$name])){$value=trim($_REQUEST[$name]);if(strlen($value)){return$value;}}return NULL;}
			function aUrl($actionId,$queryString='',$pageId=NULL,$fullUrl=FALSE){if($pageId==NULL)$pageId=Request('page');if(strlen($queryString))$queryString='&'.$queryString;$url=Config('context_root').'/?page='.$pageId.$queryString.'&action='.$actionId;
							if($fullUrl)$url=Config('homepage').$url;return $url;}
			function iUrl($pageId=0,$queryString='',$fullUrl=FALSE){if(strlen($queryString))$queryString='&'.$queryString;if($fullUrl){$url=Config('homepage').Config('context_root');if($pageId!='home'||strlen($queryString))$url.='/?page='.$pageId.$queryString;}
							else$url=Config('context_root').'/?page='.$pageId.$queryString;return $url;}
			function FormattedDate($timestamp){return date(Config('date_format'),$timestamp);}
			function Datetime($timestamp){return date('d.m.Y,H:i',$timestamp);}
			function Timestamp(){return time()+Config('time_offset');}
			function PageId(){return Value::$pageId;}
			function setPageId($pageId){Value::$pageId=$pageId;}
			function owsProVersion(){return'owsPro 8.2.8.29.07.13';}
			if(!function_exists('mb_strlen')){function mb_strlen($str=''){preg_match_all("/./u",$str,$char);return sizeof($char[0]);}}
			if(!function_exists('mb_substr')){function mb_substr($maxstr,$length,$str=""){if(!is_numeric($maxstr))$strsize=0;if(!is_numeric($length)&&$length!=NULL)$length=0;preg_match_all("/./u",$str,$char);if($length==NULL)$length=sizeof($char[0]);
			else{if($length<0)$length=sizeof($char[0])+$length;else$length=$maxstr+$length;}for($i=$maxstr;$i<$length;++$i)$result.=$char[0][$i];return$result;}}
			if(!function_exists('mb_strtolower')){function mb_strtolower($string){return strtolower($string);}}
			if(version_compare('5.5.0',phpversion(),'>=')){try{throw new Exception();}catch(Exception$e){echo' Minimum requirements: PHP 5.5';}exit;}
			if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/cache')){mkdir($_SERVER['DOCUMENT_ROOT'].'/cache',0777,true);}
// Static class functions separated out as procedural functions and still available as static function via call to procedural function for old code compatibility.
class ActionHandler{
	 static function handleAction($website,$db,$i18n,$actionId){handleAction($website,$db,$i18n,$actionId);}
	 static function validateParameters($params,$website,$i18n){validateParameters($params,$website,$i18n);}
	 static function executeAction($website,$db,$i18n,$actionId,$controllerName,$validatedParams){executeAction($website,$db,$i18n,$actionId,$controllerName,$validatedParams);}
	 static function handlePremiumAction($website,$db,$i18n,$actionId,$creditsRequired,$validatedParams,$controllerName){handlePremiumAction($website,$db,$i18n,$actionId,$creditsRequired,$validatedParams,$controllerName);}}
			function handleAction($website,$db,$i18n,$actionId){if($actionId==NULL)return;if(isset($_SESSION['laction_id'])&&$_SESSION['laction_id']==$actionId&&isset($_SESSION['laction_time'])&&($_SESSION['laction_time']+config('DOUBLE_SUBMIT_CHECK_SECONDS'))>
							Timestamp()){throw new Exception(Message('error_double_submit'));}$actionConfig=json_decode($website->getAction($actionId),true);$actionXml=findModuleConfigAsXmlObject($actionConfig['module']);
							$user=$website->getUser();if(strpos($actionConfig['role'],'admin')!==false){if(!$user->isAdmin())throw new AccessDeniedException(Message('error_access_denied'));}else{$requiredRoles=explode(',',$actionConfig['role']);
							if(!in_array($user->getRole(),$requiredRoles))throw new AccessDeniedException(Message('error_access_denied'));}$params=$actionXml->xpath('//action[@id="'.$actionId.'"]/param');$validatedParams=[];
							if($params)$validatedParams=validateParameters($params,$website,$i18n);$controllerName=$actionConfig['controller'];if(isset($actionConfig['premiumBalanceMin'])&&$actionConfig['premiumBalanceMin'])return
							handlePremiumAction($website,$db,$i18n,$actionId,$actionConfig['premiumBalanceMin'],$validatedParams,$controllerName);$actionReturn=executeAction($website,$db,$i18n,$actionId,$controllerName,$validatedParams);
							if(isset($actionConfig['log'])&&$actionConfig['log'] &&$website->getUser()->id)createOrUpdateActionLog($website,$db,$website->getUser()->id,$actionId);return$actionReturn;}
			function validateParameters($params,$website,$i18n){$errorMessages=[];$validatedParams=[];foreach($params as$param){$paramId=(string)$param->attributes()->id;$type=(string)$param->attributes()->type;$required=($param->attributes()->required=='true');
							$min=(int)$param->attributes()->min;$max=(int)$param->attributes()->max;$validatorName=(string)$param->attributes()->validator;$paramValue=Request($paramId);if($type=='boolean')$paramValue=($paramValue)?'1':'0';
							if($required&&$paramValue==null)$errorMessages[$paramId]=Message('validation_error_required');elseif($paramValue!=null){if($type=='text'&&$min>0&&strlen($paramValue)<$min)$errorMessages[$paramId]=
							sprintf(Message('validation_error_min_length'),$min);elseif($type=='text'&&$max>0&&strlen($paramValue)>$max)$errorMessages[$paramId]=sprintf(Message('validation_error_max_length'),$max);elseif($type=='number'&&!is_numeric($paramValue))
							$errorMessages[$paramId]=Message('validation_error_not_a_number');elseif($type=='number'&&$paramValue<$min)$errorMessages[$paramId]=Message('validation_error_min_number',$min);elseif($type=='number'&&$max>0&&$paramValue>$max)
							$errorMessages[$paramId]=Message('validation_error_max_number',$max);elseif($type=='url'&&!filter_var($paramValue,FILTER_VALIDATE_URL))$errorMessages[$paramId]=Message('validation_error_not_a_url');elseif($type=='date'){
							$format=Config('date_format');if(!DateTime::createFromFormat($format,$paramValue))$errorMessages[$paramId]=Message('validation_error_invaliddate',$format);}if(strlen($validatorName)){if(!class_exists($validatorName))
							throw new Exception('Validator not found: '.$validatorName);$validator=new $validatorName($i18n,$website,$paramValue);if(!$validator->isValid())$errorMessages[$paramId]=$validator->getMessage();}}if(!isset($errorMessages[$paramId]))
							$validatedParams[$paramId]=$paramValue;}if(count($errorMessages))throw new ValidationException($errorMessages);return$validatedParams;}
			function executeAction($website,$db,$i18n,$actionId,$controllerName,$validatedParams){if(!class_exists($controllerName))throw new Exception('Controller not found: '.$controllerName);$_SESSION['laction_id']=$actionId;$_SESSION['laction_time']=
	 						Timestamp();$controller=new $controllerName($i18n,$website,$db);return$controller->executeAction($validatedParams);}
			function handlePremiumAction($website,$db,$i18n,$actionId,$creditsRequired,$validatedParams,$controllerName){if($creditsRequired>$website->getUser()->premiumBalance){$targetPage=Config('premium_infopage');if(filter_var($targetPage,FILTER_VALIDATE_URL)){
							header('location: '.$targetPage);exit;}else{$website->addContextParameter('premium_balance_required',$creditsRequired);return$targetPage;}}if(Request('premiumconfirmed')){PremiumDataService::debitAmount($website,$db,
							$website->getUser()->id,$creditsRequired,$actionId);return executeAction($website,$db,$i18n,$actionId,$controllerName,$validatedParams);}$website->addContextParameter('premium_balance_required',$creditsRequired);
							$website->addContextParameter('actionparameters',$validatedParams);$website->addContextParameter('actionid',$actionId);$website->addContextParameter('srcpage',PageId());return'premium-confirm-action';}
class BreadcrumbBuilder{
	 static function getBreadcrumbItems($website,$i18n,$pages,$currentPageId){getBreadcrumbItems($website,$i18n,$pages,$currentPageId);}}
function getBreadcrumbItems($website,$i18n,$pages,$currentPageId){if(!isset($pages[$currentPageId]))return;$items=[];$nextPageId=$currentPageId;while($nextPageId){$pageConfig=json_decode($pages[$nextPageId],TRUE);$items[$nextPageId]=
							$i18n->getNavigationLabel($nextPageId);if(isset($pageConfig['parentItem'])&&strlen($pageConfig['parentItem']))$nextPageId=$pageConfig['parentItem'];else$nextPageId=FALSE;}return array_reverse($items);}
class ConverterFactory{
	 static function getConverter($website,$i18n,$converter){getConverter($website,$i18n,$converter);}}
			function getConverter($website,$i18n,$converter){if(isset(Value::$_createdConverters[$converter]))return Value::$_createdConverters[$converter];if(class_exists($converter)){$converterInstance=new $converter($i18n,$website);
							Value::$_createdConverters[$converter]=$converterInstance;return$converterInstance;}throw new Exception('Converter not found: '.$converter);}
class CookieHelper{
	 static function createCookie($name,$value,$lifetimeInDays=null){createCookie($name,$value,$lifetimeInDays=null);}
	 static function getCookieValue($name){getCookieValue($name);}
	 static function destroyCookie($name){destroyCookie($name);}}
			function createCookie($name,$value,$lifetimeInDays=null){$expiry=($lifetimeInDays!=null)?time()+86400*$lifetimeInDays:0;setcookie('owsPro'.$name,$value,$expiry,null,null,true,true);}
			function getCookieValue($name){if(!isset($_COOKIE['owsPro'.$name]))return null;return$_COOKIE['owsPro'.$name];}
			function destroyCookie($name){if(!isset($_COOKIE['owsPro'.$name]))return;setcookie('owsPro'.$name,'',time()-86400,null,null,true,true);}
class EmailHelper{
	 static function sendSystemEmailFromTemplate($websoccer,$i18n,$recipient,$subject,$templateName,$parameters){sendSystemEmailFromTemplate($websoccer,$i18n,$recipient,$subject,$templateName,$parameters);}
	 static function sendSystemEmail($websoccer,$recipient,$subject,$content){sendSystemEmail($websoccer,$recipient,$subject,$content);}}
     		function sendSystemEmailFromTemplate($websoccer,$i18n,$recipient,$subject,$templateName,$parameters){$emailTemplateEngine=new TemplateEngine($websoccer,$i18n,null);$template=$emailTemplateEngine->loadTemplate('emails/'.$templateName);
							$content=$template->render($parameters);sendSystemEmail($websoccer,$recipient,$subject,$content);}
			function sendSystemEmail($websoccer,$recipient,$subject,$content){$fromName=Config('projectname');$fromEmail=Config('systememail');$headers=[];$headers[]='Content-type:text/plain;charset=\'UTF-8\'';$headers[]='From: '.$fromName.' <'.$fromEmail.'>';
							$encodedsubject='=?UTF-8?B?'.base64_encode($subject).'?=';if(@mail($recipient,$encodedsubject,$content,implode("\r\n",$headers))==FALSE)throw new Exception('e-mail not sent.');}
class FileUploadHelper{
	 static function uploadImageFile($i18n,$requestParameter,$targetFilename,$targetDirectory){uploadImageFile($i18n,$requestParameter,$targetFilename,$targetDirectory);}
	 static function uploadFile($i18n,$requestParameter,$targetFilename,$targetDirectory){uploadFile($i18n,$requestParameter,$targetFilename,$targetDirectory);}}
			function uploadImageFile($i18n,$requestParameter,$targetFilename,$targetDirectory){$filename=$_FILES[$requestParameter]['name'];$ext=strtolower(pathinfo($filename,PATHINFO_EXTENSION));$allowedExtensions=explode(',','jpg,jpeg,gif,png');
							if(!in_array($ext,$allowedExtensions))throw new Exception(Message('validationerror_imageupload_noimagefile'));$imagesize=getimagesize($_FILES[$requestParameter]['tmp_name']);if($imagesize===FALSE)throw new Exception(
							Message('validationerror_imageupload_noimagefile'));$type=substr($imagesize['mime'],strrpos($imagesize['mime'],'/')+ 1);if(!in_array($type,$allowedExtensions))throw new Exception(Message('validationerror_imageupload_noimagefile'));
							$targetFilename.='.'.$ext;uploadFile($i18n,$requestParameter,$targetFilename,$targetDirectory);return$ext;}
			function uploadFile($i18n,$requestParameter,$targetFilename,$targetDirectory){$errorcode=$_FILES[$requestParameter]['error'];if($errorcode==UPLOAD_ERR_OK){$tmp_name=$_FILES[$requestParameter]['tmp_name'];$name=$targetFilename;$uploaded=@move_uploaded_file(
							$tmp_name,$_SERVER['DOCUMENT_ROOT'].'/uploads/' .$targetDirectory.'/'.$name);if(!$uploaded)throw new Exception(Message('error_file_upload_failed'));}else throw new Exception(Message('error_file_upload_failed'));}

class FormBuilder{
	 static function createFormGroup($i18n,$fieldId,$fieldInfo,$fieldValue,$labelKeyPrefix){createFormGroup($i18n,$fieldId,$fieldInfo,$fieldValue,$labelKeyPrefix);}
	 static function validateField($i18n,$fieldId,$fieldInfo,$fieldValue,$labelKeyPrefix){validateField($i18n,$fieldId,$fieldInfo,$fieldValue,$labelKeyPrefix);}
	 static function createForeignKeyField($i18n,$fieldId,$fieldInfo,$fieldValue){createForeignKeyField($i18n,$fieldId,$fieldInfo,$fieldValue);}}
			function createFormGroup($i18n,$fieldId,$fieldInfo,$fieldValue,$labelKeyPrefix){$type=$fieldInfo['type'];if($type=='timestamp'&&isset($fieldInfo['readonly'])&&$fieldInfo['readonly']){$website=WebSoccer::getInstance();$dateFormat=Config('datetime_format');
							if(!strlen($fieldValue))$fieldValue=date($dateFormat);elseif(is_numeric($fieldValue))$fieldValue=date($dateFormat,$fieldValue);$type='text';}elseif($type=='date'&&strlen($fieldValue)){if(startsWith($fieldValue,'0000'))
							$fieldValue='';else{$dateObj=DateTime::createFromFormat('Y-m-d',$fieldValue);if($dateObj!==FALSE){$website=WebSoccer::getInstance();$dateFormat=$website->getConfig('date_format');$fieldValue=$dateObj->format($dateFormat);}}}
							echo'<div class=\'control-group\'>';$helpText='';$inlineHelpKey=$labelKeyPrefix.$fieldId.'_help';if(hasMessage($inlineHelpKey))$helpText='<span class=\'help-inline\'>'.Message($inlineHelpKey).'</span>';if($type=='boolean'){
							echo'<label class=\'checkbox\'><input type=\'checkbox\'value=\'1\'name=\''.$fieldId.'\'';if($fieldValue=='1')echo'checked';echo'>';echo Message($labelKeyPrefix.$fieldId);echo'</label>';echo$helpText;}else{$labelOutput=
							Message($labelKeyPrefix.$fieldId);if(isset($fieldInfo['required'])&&$fieldInfo['required']=='true')$labelOutput='<strong>'.$labelOutput.'</strong>';echo'<label class=\'control-label\'for=\''.$fieldId.'\'>'.$labelOutput.'</label>
							<div class=\'controls\'>';switch($type){case'foreign_key':createForeignKeyField($i18n,$fieldId,$fieldInfo,$fieldValue);break;case'html':case'textarea':$class='input-xxlarge';if($type=='html')$class='htmleditor';echo'<textarea id=\''.$fieldId.
							'\'name=\''.$fieldId.'\'wrap=\'virtual\'class=\''.$class.'\'rows=\'10\'>'.$fieldValue.'</textarea>';break;case'timestamp':$website=WebSoccer::getInstance();$dateFormat=Config('date_format');if(!$fieldValue)$fieldValue=Timestamp();
							echo'<div class=\'input-append date datepicker\'><input type=\'text\'name=\''.$fieldId.'_date\'value=\''.date($dateFormat,$fieldValue).'\'class=\'input-small\'><span class=\'add-on\'><i class=\'icon-calendar\'></i></span></div>
							<div class=\'input-append bootstrap-timepicker\'><input type=\'text\'name=\''.$fieldId.'_time\'value=\''.date('H:i',$fieldValue).'\'class=\'timepicker input-small\'><span class=\'add-on\'><i class=\'icon-time\'></i></span></div>';break;
							case'select':echo'<select id=\''.$fieldId.'\'name=\''.$fieldId.'\'>';$selection=explode(',',$fieldInfo['selection']);$selectValue=$fieldValue;echo'<option></option>';foreach($selection as$selectItem){$selectItem=trim($selectItem);
							echo'<option value=\''.$selectItem.'\'';if($selectItem==$selectValue)echo'selected';echo'>';$label=$selectItem;if(hasMessage('option_'.$selectItem))$label=Message('option_'.$selectItem);echo$label.'</option>';}echo'</select>';break;default:
							if(isset($fieldInfo['readonly'])&&$fieldInfo['readonly'])echo'<span class=\'uneditable-input\'>'.escapeOutput($fieldValue).'</span>';else{$additionalAttrs='';$htmlType=$type;if($type=='file'&&strlen($fieldValue)){global$entity;
							echo'[<a href=\'../uploads/'.$entity.'/'.escapeOutput($fieldValue).'\'target=\'_blank\'>View</a>] ';}elseif($type=='percent'){$htmlType='number';$additionalAttrs='class=\'input-mini\'min=\'0\' ';}elseif($type=='number')
							$additionalAttrs='class=\'input-small\' ';elseif($type=='date'){if($type=='date')echo'<div class=\'input-append date datepicker\'>';$htmlType='text';$additionalAttrs=' class=\'input-small\' ';}elseif($type=='tags')$additionalAttrs=
							'class=\'input-tag\'data-provide=\'tag\' ';else$additionalAttrs='placeholder=\''.Message($labelKeyPrefix.$fieldId).'\' ';echo'<input type=\''.$htmlType.'\'id=\''.$fieldId.'\' '.$additionalAttrs.'name=\''.$fieldId.'\'value=\'';
							if($type!='password')echo escapeOutput($fieldValue);echo'\'';if(isset($fieldInfo['required'])&&$fieldInfo['required'])echo'required';echo'>';if($type=='date')echo '<span class=\'add-on\'><i class=\'icon-calendar\'></i></span></div>';}}
							if($type=='percent')echo' % ';echo$helpText;echo'</div>';}echo'</div>';}
			function validateField($i18n,$fieldId,$fieldInfo,$fieldValue,$labelKeyPrefix){$textLength=strlen(trim($fieldValue));$isEmpty=!$textLength;if($fieldInfo['type']!='boolean'&&$fieldInfo['required']&&$isEmpty)throw new Exception(sprintf(
							Message('validationerror_required'),Message($labelKeyPrefix.$fieldId)));if(!$isEmpty){if($fieldInfo['type']=='text'&&$textLength>255)throw new Exception(sprintf(Message('validationerror_text_too_long'),Message($labelKeyPrefix.$fieldId)));
							if($fieldInfo['type']=='email'&&!filter_var($fieldValue,FILTER_VALIDATE_EMAIL))throw new Exception(Message('validationerror_email'));if($fieldInfo['type']=='url'&&!filter_var($fieldValue,FILTER_VALIDATE_URL))throw new Exception(sprintf(
							Message('validationerror_url'),Message($labelKeyPrefix.$fieldId)));if($fieldInfo['type']=='number'&&!is_numeric($fieldValue))throw new Exception(sprintf(Message('validationerror_number'),Message($labelKeyPrefix.$fieldId)));
							if($fieldInfo['type']=='percent'&&filter_var($fieldValue,FILTER_VALIDATE_INT)===FALSE)throw new Exception(sprintf(Message('validationerror_percent'),Message($labelKeyPrefix.$fieldId)));if($fieldInfo['type']=='date'){$website=
							WebSoccer::getInstance();$format=Config('date_format');if(!DateTime::createFromFormat($format,$fieldValue))throw new Exception(sprintf(Message('validationerror_date'),Message($labelKeyPrefix.$fieldId),$format));}}if(isset($fieldInfo[
							'validator'])&&strlen($fieldInfo['validator'])){$website=WebSoccer::getInstance();$validator=new $fieldInfo['validator']($i18n,$website,$fieldValue);if(!$validator->isValid())throw new Exception(Message($labelKeyPrefix.$fieldId).': '.
							$validator->getMessage());}}
			function createForeignKeyField($i18n,$fieldId,$fieldInfo,$fieldValue){$website=WebSoccer::getInstance();$db=DbConnection::getInstance();$fromTable=Config('db_prefix').'_'.$fieldInfo['jointable'];$result=$db->querySelect('COUNT(*)AS hits',$fromTable,'1=1','');
							$items=$result->fetch_array();$result->free();if($items['hits']<=20){echo'<select id=\''.$fieldId.'\'name=\''.$fieldId.'\'><option value=\'\'>'.Message('manage_select_placeholder').'</option>';$whereCondition='1=1 ORDER BY '.
							$fieldInfo['labelcolumns'].' ASC';$result=$db->querySelect('id, '.$fieldInfo['labelcolumns'],$fromTable,$whereCondition,'',2000);while($row=$result->fetch_array()){$labels=explode(',',$fieldInfo['labelcolumns']);$label='';$first=TRUE;
							foreach($labels as$labelColumn){if(!$first)$label.=' - ';$first=FALSE;$label.= $row[trim($labelColumn)];}echo'<option value=\''.$row['id'].'\'';if($fieldValue==$row['id'])echo'selected';echo'>'.escapeOutput($label).'</option>';}
							$result->free();echo'</select>';}else echo'<input type=\'hidden\'class=\'pkpicker\'id=\''.$fieldId.'\' name=\''.$fieldId.'\'value=\''.$fieldValue.'\'data-dbtable=\''.$fieldInfo['jointable'].'\' data-labelcolumns=\''. $fieldInfo['labelcolumns']
							.'\'data-placeholder=\''.Message('manage_select_placeholder').'\'>';echo'<a href=\'?site=manage&entity='.$fieldInfo['entity'].'&show=add\'title=\''.Message('manage_add').'\'><i class=\'icon-plus-sign\'></i></a>';}
class ModuleConfigHelper{
	 static function findDependentEntities($dbtable){findDependentEntities($dbtable);}
	 static function findModuleConfigAsXmlObject($moduleName){findModuleConfigAsXmlObject($moduleName);}
	 static function removeAliasFromDbTableName($tableName){removeAliasFromDbTableName($tableName);}
	 static function findDependentEntity(&$entities,$pathToFile,$dbtable){findDependentEntity($entities,$pathToFile,$dbtable);}}
			function findDependentEntities($dbtable){$modules=scandir($_SERVER['DOCUMENT_ROOT'].'/modules');$entities=[];foreach($modules as$module){if(is_dir($_SERVER['DOCUMENT_ROOT'].'/modules'.'/'.$module)){$files=scandir($_SERVER['DOCUMENT_ROOT'].'/modules'.'/'.
							$module);foreach($files as$file){$pathToFile=$_SERVER['DOCUMENT_ROOT'].'/modules'.'/'.$module.'/'.$file;if($file=='module.xml')findDependentEntity($entities,$pathToFile,$dbtable);}}}return$entities;}
			function findModuleConfigAsXmlObject($moduleName){$pathToFile=$_SERVER['DOCUMENT_ROOT'].'/modules'.'/'.$moduleName.'/'.'module.xml';if(!file_exists($pathToFile))throw new Exception('Config file for module \''.$moduleName.'\' not found.');
							return simplexml_load_file($pathToFile);}
			function removeAliasFromDbTableName($tableName){$spaceTablePos=strrpos($tableName,' ');return($spaceTablePos)?substr($tableName,0,strpos($tableName,' ')): $tableName;}
			function findDependentEntity(&$entities,$pathToFile,$dbtable){$xml=simplexml_load_file($pathToFile);$foundFields=$xml->xpath('//field[@jointable=\''.$dbtable.'\']');if($foundFields){foreach($foundFields as$field){$entity=$field->xpath('../..');
							$entities[]=['dbtable'=>removeAliasFromDbTableName((string)$entity[0]->attributes()->dbtable),'columnid'=>(string)$field->attributes()->id,'cascade'=>(string)$field->attributes()->cascade];}}}
class NavigationBuilder{
	 static function getNavigationItems($website,$i18n,$pages,$currentPageId){getNavigationItems($website,$i18n,$pages,$currentPageId);}
	 static function createItem(&$items,&$addedItemsCache,$pageId,$pageJson,$website,$i18n,$currentPageId,&$pages){createItem($items,$addedItemsCache,$pageId,$pageJson,$website,$i18n,$currentPageId,$pages);}
	 static function addToItems(&$items,&$addedItemsCache,$item,$itemWeight,$itemParent){$listToAdd=&$items;if($itemParent!=null)$listToAdd=&$addedItemsCache[$itemParent]->children;$addedItemsCache[$item->pageId]=$item;$listToAdd[]=$item;}
	 static function sortByWeight($a,$b){sortByWeight($a,$b);}}
			function getNavigationItems($website,$i18n,$pages,$currentPageId){$items=[];$addedItemsCache=[];foreach($pages as$pageId=>$pageJson)createItem($items,$addedItemsCache,$pageId,$pageJson,$website,$i18n,$currentPageId,$pages);
							usort($items,['NavigationBuilder','sortByWeight']);foreach($items as$item){if($item->children!=null)usort($item->children,['NavigationBuilder','sortByWeight']);}return$items;}
			function createItem(&$items,&$addedItemsCache,$pageId,$pageJson,$website,$i18n,$currentPageId,&$pages){if(isset($addedItemsCache[$pageId]))return;$pageConfig=json_decode($pageJson,TRUE);$requiredRoles=explode(',',$pageConfig['role']);
							if(!in_array($website->getUser()->getRole(),$requiredRoles))return;if(isset($pageConfig['parentItem'])&&strlen($pageConfig['parentItem'])&&!isset($addedItemsCache[$pageConfig['parentItem']])){createItem($items,$addedItemsCache,
							$pageConfig['parentItem'],$pages[$pageConfig['parentItem']],$website,$i18n,$currentPageId,$pages);}$isActive=($currentPageId==$pageId);if($isActive &&isset($pageConfig['parentItem'])&&strlen($pageConfig['parentItem'])&&
							isset($addedItemsCache[$pageConfig['parentItem']]))$addedItemsCache[$pageConfig['parentItem']]->isActive=TRUE;if(!isset($pageConfig['navitem'])||$pageConfig['navitem']!='true')return;if(isset($pageConfig['navitemOnlyForConfigEnabled'])&&
							!Config($pageConfig['navitemOnlyForConfigEnabled']))return;$itemWeight=(isset($pageConfig['navweight'])&&strlen($pageConfig['navweight']))?$pageConfig['navweight']:0;$item=new NavigationItem($pageId,$i18n->getNavigationLabel($pageId),[],
							$isActive,$itemWeight);$itemParent=(isset($pageConfig['parentItem'])&&strlen($pageConfig['parentItem']))?$pageConfig['parentItem']:null;addToItems($items,$addedItemsCache,$item,$itemWeight,$itemParent);}
			function addToItems(&$items,&$addedItemsCache,$item,$itemWeight,$itemParent){$listToAdd=&$items;if($itemParent!=null)$listToAdd=&$addedItemsCache[$itemParent]->children;$addedItemsCache[$item->pageId]=$item;$listToAdd[]=$item;}
			function sortByWeight($a,$b){return$a->weight-$b->weight;}
class PluginMediator{
	 static function dispatchEvent($event){dispatchEvent($event);}}
			function dispatchEvent($event){if(Value::$_eventlistenerConfigs==null){include($_SERVER['DOCUMENT_ROOT'].'/cache/eventsconfig.inc.php');if(isset($eventlistener))Value::$_eventlistenerConfigs=$eventlistener;else Value::$_eventlistenerConfigs=[];}
							if(!count(Value::$_eventlistenerConfigs))return;$eventName=get_class($event);if(!isset(Value::$_eventlistenerConfigs[$eventName]))return;$eventListeners=Value::$_eventlistenerConfigs[$eventName];foreach($eventListeners as$listenerConfigStr){
							$listenerConfig=json_decode($listenerConfigStr, TRUE);if(method_exists($listenerConfig['class'],$listenerConfig['method']))call_user_func($listenerConfig['class'].'::'.$listenerConfig['method'],$event);else throw new Exception(
							'Configured event listener must have function: '.$listenerConfig['class'].'::'.$listenerConfig['method']);}}
class ScheduleGenerator{
	 static function createRoundRobinSchedule($teamIds){createRoundRobinSchedule($teamIds);}}
			function createRoundRobinSchedule($teamIds){shuffle($teamIds);$noOfTeams=count($teamIds);if($noOfTeams%2!==0){$teamIds[]=-1;++$noOfTeams;}$noOfMatchDays=$noOfTeams-1;$sortedMatchdays=[];foreach($teamIds as$teamId)$sortedMatchdays[1][]=$teamId;
							for($matchdayNo=2;$matchdayNo<=$noOfMatchDays;++$matchdayNo){$rowCenterWithoutFixedEnd=$noOfTeams/2-1;for($teamIndex=0;$teamIndex<$rowCenterWithoutFixedEnd;++$teamIndex){$targetIndex=$teamIndex+$noOfTeams/2;$sortedMatchdays[$matchdayNo][]=
							$sortedMatchdays[$matchdayNo-1][$targetIndex];}for($teamIndex=$rowCenterWithoutFixedEnd;$teamIndex<$noOfTeams-1;++$teamIndex){$targetIndex=0+$teamIndex-$rowCenterWithoutFixedEnd;$sortedMatchdays[$matchdayNo][]=
							$sortedMatchdays[$matchdayNo-1][$targetIndex];}$sortedMatchdays[$matchdayNo][]=$teamIds[count($teamIds)-1];}$schedule=[];$matchesNo=$noOfTeams/2;for($matchDayNo=1;$matchDayNo<=$noOfMatchDays;++$matchDayNo){$matches=[];for($teamNo=1;
							$teamNo<=$matchesNo;++$teamNo){$homeTeam=$sortedMatchdays[$matchDayNo][$teamNo-1];$guestTeam=$sortedMatchdays[$matchDayNo][count($teamIds)-$teamNo];if($homeTeam==-1||$guestTeam==-1)continue;if($teamNo===1&&$matchDayNo%2==0){
							$swapTemp=$homeTeam;$homeTeam=$guestTeam;$guestTeam=$swapTemp;}$match=array($homeTeam,$guestTeam);$matches[]=$match;}$schedule[$matchDayNo]=$matches;}return$schedule;}
class SecurityUtil{
	 static function hashPassword($password,$salt){hashPassword($password,$salt);}
	 static function isAdminLoggedIn(){isAdminLoggedIn();}
	 static function logoutAdmin(){logoutAdmin();}
	 static function generatePassword(){generatePassword();}
	 static function generatePasswordSalt(){generatePasswordSalt();}
	 static function generateSessionToken($userId,$salt){generateSessionToken($userId,$salt);}
	 static function loginFrontUserUsingApplicationSession($websoccer,$userId){loginFrontUserUsingApplicationSession($websoccer,$userId);}}
	 		function hashPassword($password,$salt){return hash('sha256',$salt.hash('sha256',$password));}
	 		function isAdminLoggedIn(){if(isset($_SESSION['HTTP_USER_AGENT'])){if($_SESSION['HTTP_USER_AGENT']!=hash('sha256',$_SERVER['HTTP_USER_AGENT'])){logoutAdmin();return FALSE;}}else$_SESSION['HTTP_USER_AGENT']=hash('sha256',$_SERVER['HTTP_USER_AGENT']);
	    					return(isset($_SESSION['valid'])&&$_SESSION['valid']);}
	 		function logoutAdmin(){$_SESSION=[];session_destroy();}
	 		function generatePassword(){$chars='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789%!=?';return substr(str_shuffle($chars),0,8);}
	 		function generatePasswordSalt(){return substr(generatePassword(),0,4);}
	 		function generateSessionToken($userId,$salt){$useragent=(isset($_SESSION['HTTP_USER_AGENT']))?$_SESSION['HTTP_USER_AGENT']:'n.a.';return hash('sha256',$salt.$useragent.$userId);}
	 		function loginFrontUserUsingApplicationSession($websoccer,$userId){$_SESSION['frontuserid']=$userId;session_regenerate_id();$userProvider=new SessionBasedUserAuthentication($websoccer);$userProvider->verifyAndUpdateCurrentUser($websoccer->getUser());}
class PageIdRouter{
	 static function getTargetPageId($websoccer,$i18n,$requestedPageId){getTargetPageId($websoccer,$i18n,$requestedPageId);}}
			function getTargetPageId($websoccer,$i18n,$requestedPageId){$pageId=$requestedPageId;if($pageId==NULL)$pageId=Config('DEFAULT_PAGE_ID');$user=$websoccer->getUser();if(Config('password_protected')&&$user->getRole()=='guest'){$freePageIds=array('login',
	 						'register','register-success','activate-user','forgot-password','imprint','logout','termsandconditions');if(!Config('password_protected_startpage'))$freePageIds[]=config('DEFAULT_PAGE_ID');if(!in_array($pageId,$freePageIds)){
							$websoccer->addFrontMessage(new FrontMessage('warning',Message('requireslogin_box_title'),Message('requireslogin_box_message')));$pageId='login';}}if($pageId=='team'&&Request('id')==null)$pageId='leagues';if($user->getRole()=='user'&&
							!strlen($user->username))$pageId='enter-username';return$pageId;}
class MatchSimulationExecutor{
	 static function simulateOpenMatches($websoccer,$db){simulateOpenMatches($websoccer,$db);}
	 static function handleBothTeamsHaveNoFormation($websoccer,$db,$homeTeam,$guestTeam,$match){handleBothTeamsHaveNoFormation($websoccer,$db,$homeTeam,$guestTeam,$match);}
	 static function handleOneTeamHasNoFormation($websoccer,$db,$team,$match){handleOneTeamHasNoFormation($websoccer,$db,$team,$match);}
	 static function createInitialMatchData($websoccer,$db,$matchinfo){createInitialMatchData($websoccer,$db,$matchinfo);}
	 static function addPlayers($websoccer,$db,$team,$matchinfo,$columnPrefix){addPlayers($websoccer,$db,$team,$matchinfo,$columnPrefix);}
	 static function addSubstitution($websoccer,$db,$team,$matchinfo,$columnPrefix){addSubstitution($websoccer,$db,$team,$matchinfo,$columnPrefix);}
			function findPlayerOnField(Team$team,$playerId){findPlayerOnField($team,$playerId);}
	 static function teamHasNoManager($websoccer,$db,$teamId){teamHasNoManager($websoccer,$db,$teamId);}
	 static function computeMorale($captain,$matchesPlayed){computeMorale($captain,$matchesPlayed);}}
			function simulateOpenMatches($websoccer,$db){$simulator=new Simulator($db,$websoccer);$strategy=$simulator->getSimulationStrategy();$simulationObservers=explode(',',Config('sim_simulation_observers'));foreach($simulationObservers as$observerClassName){
							$observerClass=trim($observerClassName);if(strlen($observerClass))$strategy->attachObserver(new $observerClass($websoccer,$db));}$simulatorObservers=explode(',',Config('sim_simulator_observers'));
							foreach($simulatorObservers as$observerClassName){$observerClass=trim($observerClassName);if(strlen($observerClass))$simulator->attachObserver(new $observerClass($websoccer,$db));}$columns=['M.id'=>'match_id','M.spieltyp'=>'type',
							'M.home_verein'=>'home_id','M.gast_verein'=>'guest_id','M.minutes'=>'minutes','M.soldout'=>'soldout','M.elfmeter'=>'penaltyshooting','M.pokalname'=>'cup_name','M.pokalrunde'=>'cup_roundname','M.pokalgruppe'=>'cup_groupname',
							'M.stadion_id'=>'custom_stadium_id','M.player_with_ball'=>'player_with_ball','M.prev_player_with_ball'=>'prev_player_with_ball','M.home_tore'=>'home_goals','M.gast_tore'=>'guest_goals','M.home_offensive'=>'home_offensive',
							'M.home_setup'=>'home_setup','M.home_noformation'=>'home_noformation','M.home_longpasses'=>'home_longpasses','M.home_counterattacks'=>'home_counterattacks','M.home_morale'=>'home_morale','M.home_freekickplayer'=>'home_freekickplayer',
							'M.gast_offensive'=>'guest_offensive','M.guest_noformation'=>'guest_noformation','M.gast_setup'=>'guest_setup','M.gast_longpasses'=>'guest_longpasses','M.gast_counterattacks'=>'guest_counterattacks','M.gast_morale'=>'guest_morale',
							'M.gast_freekickplayer'=>'guest_freekickplayer','HOME_F.id'=>'home_formation_id','HOME_F.offensive'=>'home_formation_offensive','HOME_F.setup'=>'home_formation_setup','HOME_F.longpasses'=>'home_formation_longpasses',
							'HOME_F.counterattacks'=>'home_formation_counterattacks','HOME_F.freekickplayer'=>'home_formation_freekickplayer','HOME_T.name'=>'home_name','HOME_T.nationalteam'=>'home_nationalteam','HOME_T.interimmanager'=>'home_interimmanager',
							'HOME_T.captain_id'=>'home_captain_id','GUEST_T.nationalteam'=>'guest_nationalteam','GUEST_T.name'=>'guest_name','GUEST_T.captain_id'=>'guest_captain_id','GUEST_T.interimmanager'=>'guest_interimmanager'];for($playerNo=1;$playerNo<=11;
							++$playerNo){$columns=['HOME_F.spieler'.$playerNo=>'home_formation_player'.$playerNo,'HOME_F.spieler'.$playerNo.'_position'=>'home_formation_player_pos_'.$playerNo,'GUEST_F.spieler'.$playerNo=>'guest_formation_player'.$playerNo,
							'GUEST_F.spieler'.$playerNo.'_position'=>'guest_formation_player_pos_'.$playerNo];if($playerNo<=5){$columns=['HOME_F.ersatz'.$playerNo=>'home_formation_bench'.$playerNo,'GUEST_F.ersatz'.$playerNo=>'guest_formation_bench'.$playerNo];}}
							for($subNo=1;$subNo<=3;++$subNo){$columns=['HOME_F.w'.$subNo.'_raus'=>'home_formation_sub'.$subNo.'_out','HOME_F.w'.$subNo.'_rein'=>'home_formation_sub'.$subNo.'_in','HOME_F.w'.$subNo.'_minute'=>'home_formation_sub'.$subNo.'_minute',
							'HOME_F.w'.$subNo.'_condition'=>'home_formation_sub'.$subNo.'_condition','HOME_F.w'.$subNo.'_position'=>'home_formation_sub'.$subNo.'_position','M.home_w'.$subNo.'_raus'=>'home_sub_'.$subNo.'_out','M.home_w'.$subNo.'_rein'=>'home_sub_'.
							$subNo.'_in','M.home_w'.$subNo.'_minute'=>'home_sub_'.$subNo.'_minute','M.home_w'.$subNo.'_condition'=>'home_sub_'.$subNo.'_condition','M.home_w'.$subNo.'_position'=>'home_sub_'.$subNo.'_position','GUEST_F.w'.$subNo.
							'_raus'=>'guest_formation_sub'.$subNo.'_out','GUEST_F.w'.$subNo.'_rein'=>'guest_formation_sub'.$subNo.'_in','GUEST_F.w'.$subNo.'_minute'=>'guest_formation_sub'.$subNo.'_minute','GUEST_F.w'.$subNo.'_condition'=>'guest_formation_sub'.
							$subNo.'_condition','GUEST_F.w'.$subNo.'_position'=>'guest_formation_sub'.$subNo.'_position','M.gast_w'.$subNo.'_raus'=>'guest_sub_'.$subNo.'_out','M.gast_w'.$subNo.'_rein'=>'guest_sub_'.$subNo.'_in','M.gast_w'.$subNo.'_minute'=>'guest_sub_'.
							$subNo.'_minute','M.gast_w'.$subNo.'_condition'=>'guest_sub_'.$subNo.'_condition','M.gast_w'.$subNo.'_position'=>'guest_sub_'.$subNo.'_position'];}$columns=['GUEST_F.id'=>'guest_formation_id','GUEST_F.offensive'=>'guest_formation_offensive',
							'GUEST_F.setup'=>'guest_formation_setup','GUEST_F.longpasses'=>'guest_formation_longpasses','GUEST_F.counterattacks'=>'guest_formation_counterattacks'];$result=$db->querySelect($columns,Config('db_prefix').'_spiel AS M INNER JOIN '.
							Config('db_prefix').'_verein AS HOME_T ON HOME_T.id=M.home_verein INNER JOIN '.Config('db_prefix').'_verein AS GUEST_T ON GUEST_T.id=M.gast_verein LEFT JOIN '.Config('db_prefix').
							'_aufstellung AS HOME_F ON HOME_F.match_id=M.id AND HOME_F.verein_id=M.home_verein LEFT JOIN '.Config('db_prefix').'_aufstellung AS GUEST_F ON GUEST_F.match_id=M.id AND GUEST_F.verein_id=M.gast_verein',
							'M.berechnet!=\'1\'AND M.blocked!=\'1\'AND M.datum<=%d ORDER BY M.datum ASC',Timestamp(),(int)Config('sim_max_matches_per_run'));$matchesSimulated=0;while($matchinfo=$result->fetch_array()){$db->queryUpdate(['blocked'=>'1'],
							Config('db_prefix').'_spiel','id=%d',$matchinfo['match_id']);$match=null;if($matchinfo['minutes']<1)$match=createInitialMatchData($websoccer,$db,$matchinfo);else $match=loadMatchState($websoccer,$db,$matchinfo);
							if($match!=null){$simulator->simulateMatch($match,(int)Config('sim_interval'));updateState($websoccer,$db,$match);}$match->cleanReferences();unset($match);$db->queryUpdate(['blocked'=>'0'],Config('db_prefix').
							'_spiel','id=%d',$matchinfo['match_id']);++$matchesSimulated;}$maxYouthMatchesToSimulate=$maxMatches-$matchesSimulated;if($maxYouthMatchesToSimulate)simulateOpenYouthMatches($websoccer,$db,
							$maxYouthMatchesToSimulate);}
			function handleBothTeamsHaveNoFormation($websoccer,$db,$homeTeam,$guestTeam,SimulationMatch$match){$homeTeam->noFormationSet=TRUE;$guestTeam->noFormationSet=TRUE;if(Config('sim_noformation_bothteams')=='computer'){
							generateNewFormationForTeam($websoccer,$db,$homeTeam,$match->id);generateNewFormationForTeam($websoccer,$db,$guestTeam,$match->id);}else$match->isCompleted=TRUE;}
			function handleOneTeamHasNoFormation($websoccer,$db,$team,SimulationMatch$match){$team->noFormationSet=TRUE;if(Config('sim_createformation_without_manager')&&teamHasNoManager($websoccer,$db,$team->id))
							generateNewFormationForTeam($websoccer,$db,$team,$match->id);else{if(Config('sim_noformation_oneteam')=='0_0')$match->isCompleted=TRUE;elseif(Config('sim_noformation_oneteam')=='3_0'){$opponentTeam=($match->homeTeam->id==$team->id)? 									   $match->guestTeam:$match->homeTeam;$opponentTeam->setGoals(3);$match->isCompleted=TRUE;}else generateNewFormationForTeam($websoccer,$db,$team,$match->id);}}
			function createInitialMatchData($websoccer,$db,$matchinfo){$db->queryDelete(Config('db_prefix').'_spiel_berechnung','spiel_id=%d',$matchinfo['match_id']);$db->queryDelete(Config('db_prefix').'_matchreport','match_id=%d',$matchinfo['match_id']);
							$homeOffensive=($matchinfo['home_formation_offensive']>0)?$matchinfo['home_formation_offensive']:Config('sim_createformation_without_manager_offensive');$guestOffensive=($matchinfo['guest_formation_offensive']>0)?
							$matchinfo['guest_formation_offensive']:Config('sim_createformation_without_manager_offensive');$homeTeam=new SimulationTeam($matchinfo['home_id'],$homeOffensive);$homeTeam->setup=$matchinfo['home_formation_setup'];
							$homeTeam->isNationalTeam=$matchinfo['home_nationalteam'];$homeTeam->isManagedByInterimManager=$matchinfo['home_interimmanager'];$homeTeam->name=$matchinfo['home_name'];$homeTeam->longPasses=$matchinfo['home_formation_longpasses'];
							$homeTeam->counterattacks=$matchinfo['home_formation_counterattacks'];$guestTeam=new SimulationTeam($matchinfo['guest_id'],$guestOffensive);$guestTeam->setup=$matchinfo['guest_formation_setup'];$guestTeam->isNationalTeam=
							$matchinfo['guest_nationalteam'];$guestTeam->isManagedByInterimManager=$matchinfo['guest_interimmanager'];$guestTeam->name=$matchinfo['guest_name'];$guestTeam->longPasses=$matchinfo['guest_formation_longpasses'];$guestTeam->counterattacks=
							$matchinfo['guest_formation_counterattacks'];$match=new SimulationMatch($matchinfo['match_id'],$homeTeam,$guestTeam,0);$match->type=$matchinfo['type'];$match->penaltyShootingEnabled=$matchinfo['penaltyshooting'];$match->cupName=
							$matchinfo['cup_name'];$match->cupRoundName=$matchinfo['cup_roundname'];$match->cupRoundGroup=$matchinfo['cup_groupname'];$match->isAtForeignStadium=($matchinfo['custom_stadium_id'])?TRUE:FALSE;if(!$matchinfo['home_formation_id']&&
							!$matchinfo['guest_formation_id'])handleBothTeamsHaveNoFormation($websoccer,$db,$homeTeam,$guestTeam,$match);elseif(!$matchinfo['home_formation_id']){handleOneTeamHasNoFormation($websoccer,$db,$homeTeam,$match);addPlayers($websoccer,$db,
							$match->guestTeam,$matchinfo,'guest');addSubstitution($websoccer,$db,$match->guestTeam,$matchinfo,'guest');}elseif(!$matchinfo['guest_formation_id']){handleOneTeamHasNoFormation($websoccer,$db,$guestTeam,$match);addPlayers($websoccer,$db,
							$match->homeTeam,$matchinfo,'home');addSubstitution($websoccer,$db,$match->homeTeam,$matchinfo,'home');}else{addPlayers($websoccer,$db,$match->homeTeam,$matchinfo,'home');addPlayers($websoccer,$db,$match->guestTeam,$matchinfo,'guest');
							addSubstitution($websoccer,$db,$match->homeTeam,$matchinfo,'home');addSubstitution($websoccer,$db,$match->guestTeam,$matchinfo,'guest');}return$match;}
			function addPlayers($websoccer,$db,SimulationTeam $team,$matchinfo,$columnPrefix){$fromTable=Config('db_prefix').'_spieler';$columns=['verein_id'=>'team_id','nation'=>'nation','position'=>'position','position_main'=>'mainPosition',
							'position_second'=>'secondPosition','vorname'=>'firstName','nachname'=>'lastName','kunstname'=>'pseudonym','w_staerke'=>'strength','w_technik'=>'technique','w_kondition'=>'stamina','w_frische'=>'freshness','w_zufriedenheit'=>'satisfaction',
							'st_spiele'=>'matches_played'];if(Config('players_aging')=='birthday')$ageColumn='TIMESTAMPDIFF(YEAR,geburtstag,CURDATE())';else$ageColumn='age';$columns[$ageColumn]='age';$whereCondition='id=%d AND verletzt=0';
							if($team->isNationalTeam)$whereCondition.=' AND gesperrt_nationalteam=0';elseif($matchinfo['type']=='Pokalspiel')$whereCondition.=' AND gesperrt_cups=0';elseif($matchinfo['type']!='Freundschaft')$whereCondition.=' AND gesperrt=0';
							$positionMapping=getPositionsMapping();$addedPlayers=0;for($playerNo=1;$playerNo<=11;++$playerNo){$playerId=$matchinfo[$columnPrefix.'_formation_player'.$playerNo];$mainPosition=$matchinfo[$columnPrefix.
							'_formation_player_pos_'.$playerNo];$result=$db->querySelect($columns,$fromTable,$whereCondition,$playerId);$playerinfo=$result->fetch_array();if(isset($playerinfo['team_id'])&&$playerinfo['team_id']==$team->id||
							$team->isNationalTeam &&$playerinfo['nation']==$team->name){$position=$positionMapping[$mainPosition];$strength=$playerinfo['strength'];if($playerinfo['position']!=$position &&$playerinfo['mainPosition']!=$mainPosition &&
							$playerinfo['secondPosition']!=$mainPosition)$strength=round($strength*(1-Config('sim_strength_reduction_wrongposition')/100));elseif(strlen($playerinfo['mainPosition'])&&$playerinfo['mainPosition']!=$mainPosition &&
							($playerinfo['position']==$position||$playerinfo['secondPosition']==$mainPosition)){$strength=round($strength*(1-Config('sim_strength_reduction_secondary')/100));}$player=new SimulationPlayer($playerId,$team,$position,$mainPosition, 3.0,
							$playerinfo['age'],$strength,$playerinfo['technique'],$playerinfo['stamina'],$playerinfo['freshness'],$playerinfo['satisfaction']);if(strlen($playerinfo['pseudonym']))$player->name=$playerinfo['pseudonym'];
							else $player->name=$playerinfo['firstName'].' '.$playerinfo['lastName'];$team->positionsAndPlayers[$player->position][]=$player;createSimulationRecord($websoccer,$db,$matchinfo['match_id'],$player);++$addedPlayers;
							if($matchinfo[$columnPrefix.'_captain_id']==$playerId)computeMorale($player,$playerinfo['matches_played']);if($matchinfo[$columnPrefix.'_formation_freekickplayer']==$playerId)$team->freeKickPlayer=$player;}}if($addedPlayers<11&&
							Config('sim_createformation_on_invalidsubmission')){$db->queryDelete(Config('db_prefix').'_spiel_berechnung','spiel_id=%d AND team_id=%d',[$matchinfo['match_id'],$team->id]);$team->positionsAndPlayers=[];
							generateNewFormationForTeam($websoccer,$db,$team,$matchinfo['match_id']);$team->noFormationSet=TRUE;return;}for($playerNo=1;$playerNo<=5;++$playerNo){$playerId=$matchinfo[$columnPrefix.'_formation_bench'.$playerNo];
							$result=$db->querySelect($columns,$fromTable,$whereCondition,$playerId);$playerinfo=$result->fetch_array();if(isset($playerinfo['team_id'])&&$playerinfo['team_id']==$team->id||$team->isNationalTeam &&$playerinfo['nation']==$team->name){
							$player=new SimulationPlayer($playerId,$team,$playerinfo['position'],$playerinfo['mainPosition'],3.0,$playerinfo['age'],$playerinfo['strength'],$playerinfo['technique'],$playerinfo['stamina'],$playerinfo['freshness'],
							$playerinfo['satisfaction']);if(strlen($playerinfo['pseudonym']))$player->name=$playerinfo['pseudonym'];else$player->name=$playerinfo['firstName'].' '.$playerinfo['lastName'];$team->playersOnBench[$playerId]=$player;
							createSimulationRecord($websoccer,$db,$matchinfo['match_id'],$player,TRUE);}}}
			function addSubstitution($websoccer,$db,SimulationTeam$team,$matchinfo,$columnPrefix){for($subNo=1;$subNo<=3;++$subNo){if($matchinfo[$columnPrefix.'_formation_sub'.$subNo.'_out']){$out=$matchinfo[$columnPrefix.'_formation_sub'.$subNo.'_out'];
							$in=$matchinfo[$columnPrefix.'_formation_sub'.$subNo.'_in'];$minute=$matchinfo[$columnPrefix.'_formation_sub'.$subNo.'_minute'];$condition=$matchinfo[$columnPrefix.'_formation_sub'.$subNo.'_condition'];
							$position=$matchinfo[$columnPrefix.'_formation_sub'.$subNo.'_position'];if(isset($team->playersOnBench[$in])){$playerIn=$team->playersOnBench[$in];$playerOut=findPlayerOnField($team,$out);if($playerIn&&$playerOut){
							$sub=new SimulationSubstitution($minute,$playerIn,$playerOut,$condition,$position);$team->substitutions[]=$sub;}}}}}
			function findPlayerOnField(SimulationTeam $team,$playerId){foreach($team->positionsAndPlayers as$position=>$players){foreach($players as$player){if($player->id==$playerId)return$player;}}return false;}
			function teamHasNoManager($websoccer,$db,$teamId){$result=$db->querySelect('user_id',Config('db_prefix').'_verein',$whereCondition,$teamId);$teaminfo=$result->fetch_array();return!(isset($teaminfo['user_id'])&&$teaminfo['user_id']);}
			function computeMorale(SimulationPlayer$captain,$matchesPlayed){$morale=0;$morale+=($captain->age-16)*5;$morale+=min(40,round($matchesPlayed/2));$morale=$morale*$captain->strength/100;$morale=min(100,max(0,$morale));$captain->team->morale=$morale;}

class SimulationAudienceCalculator{
	 static function computeAndSaveAudience($websoccer,$db,$match){computeAndSaveAudience($websoccer,$db,$match);}
	 static function getHomeInfo($websoccer,$db,$teamId){getHomeInfo($websoccer,$db,$teamId);}
	 static function computeRate($avgPrice,$avgSales,$actualPrice,$fanpopularity,$isAttractiveMatch,$maintenanceInfluence){computeRate($avgPrice,$avgSales,$actualPrice,$fanpopularity,$isAttractiveMatch,$maintenanceInfluence);}
	 static function updateMaintenanceStatus($websoccer,$db,$homeInfo){updateMaintenanceStatus($websoccer,$db,$homeInfo);}
	 static function weakenPlayersDueToGrassQuality($websoccer,$homeInfo,$match){weakenPlayersDueToGrassQuality($websoccer,$homeInfo,$match);}}
	 		function computeAndSaveAudience($websoccer,$db,$match){$homeInfo=getHomeInfo($websoccer,$db,$match->homeTeam->id);if(!$homeInfo)return;$isAttractiveMatch=FALSE;if($match->type=='Pokalspiel')$isAttractiveMatch=TRUE;elseif($match->type=='Ligaspiel'){
							$result=$db->querySelect('sa_punkte',Config('db_prefix').'_verein','id=%d',$match->homeTeam->id);$home=$result->fetch_array();$result=$db->querySelect('sa_punkte',Config('db_prefix').'_verein','id=%d',$match->guestTeam->id);
							$guest=$result->fetch_array();if(abs($home['sa_punkte']-$guest['sa_punkte'])<=9)$isAttractiveMatch=TRUE;}$maintenanceInfluence=$homeInfo['level_videowall']*Config('stadium_videowall_effect');$maintenanceInfluenceSeats=
							(5-$homeInfo['level_seatsquality'])*Config('stadium_seatsquality_effect');$maintenanceInfluenceVip=(5-$homeInfo['level_vipquality'])*Config('stadium_vipquality_effect');$rateStands=computeRate($homeInfo['avg_price_stands'],
							$homeInfo['avg_sales_stands'],$homeInfo['price_stands'],$homeInfo['popularity'],$isAttractiveMatch,$maintenanceInfluence);$rateSeats=computeRate($homeInfo['avg_price_seats'],$homeInfo['avg_sales_seats'],$homeInfo['price_seats'],
							$homeInfo['popularity'],$isAttractiveMatch,$maintenanceInfluence-$maintenanceInfluenceSeats);$rateStandsGrand=computeRate($homeInfo['avg_price_stands']*1.2,$homeInfo['avg_sales_stands_grand'],$homeInfo['price_stands_grand'],
							$homeInfo['popularity'],$isAttractiveMatch,$maintenanceInfluence);$rateSeatsGrand=computeRate($homeInfo['avg_price_seats']*1.2,$homeInfo['avg_sales_seats_grand'],$homeInfo['price_seats_grand'],$homeInfo['popularity'],$isAttractiveMatch,
							$maintenanceInfluence-$maintenanceInfluenceSeats);$rateVip=selfcomputeRate($homeInfo['avg_price_vip'],$homeInfo['avg_sales_vip'],$homeInfo['price_vip'],$homeInfo['popularity'],$isAttractiveMatch,
							$maintenanceInfluence-$maintenanceInfluenceVip);$event=new TicketsComputedEvent($websoccer,$db,I18n::getInstance(Config('supported_languages')),$match,$homeInfo['stadium_id'],$rateStands,$rateSeats,$rateStandsGrand,$rateSeatsGrand,$rateVip);
							dispatchEvent($event);if($rateStands==1&&$rateSeats==1&&$rateStandsGrand==1&&$rateSeatsGrand==1&&$rateVip==1)$match->isSoldOut=TRUE;$tickets_stands=min(1,max(0,$rateStands))*$homeInfo['places_stands'];$tickets_seats=min(1,max(0,$rateSeats))*
							$homeInfo['places_seats'];$tickets_stands_grand=min(1,max(0,$rateStandsGrand))*$homeInfo['places_stands_grand'];$tickets_seats_grand=min(1,max(0,$rateSeatsGrand))*$homeInfo['places_seats_grand'];$tickets_vip=min(1,max(0,$rateVip))*
							$homeInfo['places_vip'];$db->queryUpdate(['last_steh'=>$tickets_stands,'last_sitz'=>$tickets_seats,'last_haupt_steh'=>$tickets_stands_grand,'last_haupt_sitz'=>$tickets_seats_grand,'last_vip'=>$tickets_vip],Config('db_prefix').
							'_verein','id=%d',$match->homeTeam->id);$db->queryUpdate(['zuschauer'=>$tickets_stands+$tickets_seats+$tickets_stands_grand+$tickets_seats_grand+$tickets_vip],Config('db_prefix').'_spiel','id=%d',$match->id);
							$revenue=$tickets_stands*$homeInfo['price_stands'];$revenue+=$tickets_seats*$homeInfo['price_seats'];$revenue+=$tickets_stands_grand*$homeInfo['price_stands_grand'];$revenue+=$tickets_seats_grand*$homeInfo['price_seats_grand'];
							$revenue+=$tickets_vip*$homeInfo['price_vip'];creditAmount($websoccer,$db,$match->homeTeam->id,$revenue,'match_ticketrevenue_subject','match_ticketrevenue_sender');
							weakenPlayersDueToGrassQuality($websoccer,$homeInfo,$match);updateMaintenanceStatus($websoccer,$db,$homeInfo);}
			function getHomeInfo($websoccer,$db,$teamId){$result=$db->querySelect(['S.id'=>'stadium_id','S.p_steh'=>'places_stands','S.p_sitz'=>'places_seats','S.p_haupt_steh'=>'places_stands_grand','S.p_haupt_sitz'=>'places_seats_grand','S.p_vip'=>'places_vip',
	 						'S.level_pitch'=>'level_pitch','S.level_videowall'=>'level_videowall','S.level_seatsquality'=>'level_seatsquality','S.level_vipquality'=>'level_vipquality','S.maintenance_pitch'=>'maintenance_pitch',
	 						'S.maintenance_videowall'=>'maintenance_videowall','S.maintenance_seatsquality'=>'maintenance_seatsquality','S.maintenance_vipquality'=>'maintenance_vipquality','U.fanbeliebtheit'=>'popularity','T.preis_stehen'=>'price_stands',
	 						'T.preis_sitz'=>'price_seats','T.preis_haupt_stehen'=>'price_stands_grand','T.preis_haupt_sitze'=>'price_seats_grand','T.preis_vip'=>'price_vip','L.p_steh'=>'avg_sales_stands','L.p_sitz'=>'avg_sales_seats',
	 						'L.p_haupt_steh'=>'avg_sales_stands_grand','L.p_haupt_sitz'=>'avg_sales_seats_grand','L.p_vip'=>'avg_sales_vip','L.preis_steh'=>'avg_price_stands','L.preis_sitz'=>'avg_price_seats','L.preis_vip'=>'avg_price_vip'],Config('db_prefix').
	 						'_verein AS T INNER JOIN '.Config('db_prefix').'_stadion AS S ON S.id=T.stadion_id INNER JOIN '.Config('db_prefix').'_liga AS L ON L.id=T.liga_id LEFT JOIN '.Config('db_prefix').'_user AS U ON U.id=T.user_id','T.id=%d',$teamId);
							$record=$result->fetch_array();return$record;}
	 		function computeRate($avgPrice,$avgSales,$actualPrice,$fanpopularity,$isAttractiveMatch,$maintenanceInfluence){$rate=100-pow((10/(2.5*$avgPrice)*$actualPrice),2);$deviation=$avgSales-(100-pow((10/(2.5*$avgPrice)*$avgPrice),2));$rate=$rate+$deviation;
							if($rate)$rate=$rate-10+1/5*$fanpopularity;if($isAttractiveMatch)$rate=$rate*1.1;if($rate)$rate=$rate+$maintenanceInfluence;return min(100,max(0,$rate))/100;}
	 		function updateMaintenanceStatus($websoccer,$db,$homeInfo){$columns=['maintenance_pitch'=>$homeInfo['maintenance_pitch']-1,'maintenance_videowall'=>$homeInfo['maintenance_videowall']-1,'maintenance_seatsquality'=>$homeInfo['maintenance_seatsquality']-1,
							'maintenance_vipquality'=>$homeInfo['maintenance_vipquality']-1];$types=['pitch','videowall','seatsquality','vipquality'];foreach($types as$type){if($columns['maintenance_'.$type]<=0){$columns['maintenance_'.$type]=
							Config('stadium_maintenanceinterval_'.$type);$columns['level_'.$type]=max(0,$homeInfo['level_'.$type]-1);}}$db->queryUpdate($columns,Config('db_prefix').'_stadion','id=%d',$homeInfo['stadium_id']);}
	 		function weakenPlayersDueToGrassQuality($websoccer,$homeInfo,SimulationMatch$match){$strengthChange=(5-$homeInfo['level_pitch'])*Config('stadium_pitch_effect');if($strengthChange&&$match->type!='Freundschaft'){$playersAndPositions=
							$match->homeTeam->positionsAndPlayers;foreach($playersAndPositions as$positions=>$players){foreach($players as$player)$player->strengthTech=max(1,$player->strengthTech-$strengthChange);}}}

class SimulationCupMatchHelper{
	 static	function checkIfExtensionIsRequired($websoccer,$db,$match){checkIfExtensionIsRequired($websoccer,$db,$match);}
	 static function createNextRoundMatchAndPayAwards($websoccer,$db,$winnerTeamId,$loserTeamId,$cupName,$cupRound){createNextRoundMatchAndPayAwards($websoccer,$db,$winnerTeamId,$loserTeamId,$cupName,$cupRound);}
	 static function checkIfMatchIsLastMatchOfGroupRoundAndCreateFollowingMatches($websoccer,$db,$match){checkIfMatchIsLastMatchOfGroupRoundAndCreateFollowingMatches($websoccer,$db,$match);}
	 static function createMatchForTeamAndRound($websoccer,$db,$teamId,$roundId,$firstRoundDate,$secondRoundDate,$cupName,$cupRound){createMatchForTeamAndRound($websoccer,$db,$teamId,$roundId,$firstRoundDate,$secondRoundDate,$cupName,$cupRound);}}
	 		function checkIfExtensionIsRequired($websoccer,$db,SimulationMatch$match){if(strlen($match->cupRoundGroup))return FALSE;$result=$db->querySelect(['home_tore'=>'home_goals','gast_tore'=>'guest_goals','berechnet'=>'is_simulated'],Config('db_prefix').
							'_spiel','home_verein=%d AND gast_verein=%d AND pokalname=\'%s\'AND pokalrunde=\'%s\'',[$match->guestTeam->id,$match->homeTeam->id,$match->cupName,$match->cupRoundName],1);$otherRound=$result->fetch_array();
							if(isset($otherRound['is_simulated'])&&!$otherRound['is_simulated'])return FALSE;if(!$otherRound){if($match->homeTeam->getGoals()==$match->guestTeam->getGoals())return TRUE;elseif($match->homeTeam->getGoals()>$match->guestTeam->getGoals()){
							createNextRoundMatchAndPayAwards($websoccer,$db,$match->homeTeam->id,$match->guestTeam->id,$match->cupName,$match->cupRoundName);return FALSE;}else{createNextRoundMatchAndPayAwards($websoccer,$db,$match->guestTeam->id,$match->homeTeam->id,
							$match->cupName,$match->cupRoundName);return FALSE;}}$totalHomeGoals=$match->homeTeam->getGoals();$totalGuestGoals=$match->guestTeam->getGoals();$totalHomeGoals+=$otherRound['guest_goals'];$totalGuestGoals+=$otherRound['home_goals'];
							$winnerTeam=null;$loserTeam=null;if($totalHomeGoals>$totalGuestGoals){$winnerTeam=$match->homeTeam;$loserTeam=$match->guestTeam;}elseif($totalHomeGoals<$totalGuestGoals){$winnerTeam=$match->guestTeam;$loserTeam=$match->homeTeam;}else{
							if($otherRound['guest_goals']>$match->guestTeam->getGoals()){$winnerTeam=$match->homeTeam;$loserTeam=$match->guestTeam;}elseif($otherRound['guest_goals']<$match->guestTeam->getGoals()){$winnerTeam=$match->guestTeam;$loserTeam=
							$match->homeTeam;}else return TRUE;}createNextRoundMatchAndPayAwards($websoccer,$db,$winnerTeam->id,$loserTeam->id,$match->cupName,$match->cupRoundName);return FALSE;}
	 		function createNextRoundMatchAndPayAwards($websoccer,$db,$winnerTeamId,$loserTeamId,$cupName,$cupRound){['C.id'=>'cup_id','C.winner_award'=>'cup_winner_award','C.second_award'=>'cup_second_award','C.perround_award'=>'cup_perround_award','R.id'=>'round_id',
							'R.finalround'=>'is_finalround'];$result=$db->querySelect($columns,Config('db_prefix').'_cup_round AS R INNER JOIN '.Config('db_prefix').'_cup AS C ON C.id=R.cup_id','C.name=\'%s\'AND R.name=\'%s\'',[$cupName,$cupRound],1);
							$round=$result->fetch_array();if(!$round)return;if($round['cup_perround_award']){creditAmount($websoccer,$db,$winnerTeamId,$round['cup_perround_award'],'cup_cuproundaward_perround_subject',$cupName);
							creditAmount($websoccer,$db,$loserTeamId,$round['cup_perround_award'],'cup_cuproundaward_perround_subject',$cupName);}$result=$db->querySelect('user_id',Config('db_prefix').'_verein', 'id=%d',$winnerTeamId);
							$winnerclub=$result->fetch_array();$result=$db->querySelect('user_id',Config('db_prefix').'_verein','id=%d',$loserTeamId);$loserclub=$result->fetch_array();$now=Timestamp();
							if($winnerclub['user_id'])$db->queryInsert(array('user_id'=>$winnerclub['user_id'],'team_id'=>$winnerTeamId, 'cup_round_id'=>$round['round_id'],'date_recorded'=>$now),Config('db_prefix').'_achievement');
							if($loserclub['user_id'])$db->queryInsert(array('user_id'=>$loserclub['user_id'],'team_id'=>$loserTeamId, 'cup_round_id'=>$round['round_id'],'date_recorded'=>$now),Config('db_prefix').'_achievement');if($round['is_finalround']){
							if($round['cup_winner_award'])creditAmount($websoccer,$db,$winnerTeamId,$round['cup_winner_award'],'cup_cuproundaward_winner_subject',$cupName);if($round['cup_second_award'])
							creditAmount($websoccer,$db,$loserTeamId,$round['cup_second_award'],'cup_cuproundaward_second_subject',$cupName);$db->queryUpdate(['winner_id'=>$winnerTeamId],Config('db_prefix').'_cup','id=%d',$round['cup_id']);
							if($winnerclub['user_id'])awardBadgeIfApplicable($websoccer,$db,$winnerclub['user_id'],'cupwinner');}else{$columns='id,firstround_date,secondround_date,name';$fromTable=Config('db_prefix').'_cup_round';
							$result=$db->querySelect($columns,$fromTable,'from_winners_round_id=%d',$round['round_id'],1);$winnerRound=$result->fetch_array();if(isset($winnerRound['id']))createMatchForTeamAndRound($websoccer,$db,$winnerTeamId,$winnerRound['id'],
							$winnerRound['firstround_date'],$winnerRound['secondround_date'],$cupName,$winnerRound['name']);$result=$db->querySelect($columns,$fromTable, 'from_loosers_round_id=%d',$round['round_id'],1);$loserRound=$result->fetch_array();
							if(isset($loserRound['id']))createMatchForTeamAndRound($websoccer,$db,$loserTeamId,$loserRound['id'],$loserRound['firstround_date'],$loserRound['secondround_date'],$cupName,$loserRound['name']);}}
	 		function checkIfMatchIsLastMatchOfGroupRoundAndCreateFollowingMatches($websoccer,$db,SimulationMatch$match){if(!strlen($match->cupRoundGroup))return;$result=$db->querySelect('COUNT(*)AS hits',Config('db_prefix').'_spiel',
							'berechnet=\'0\'AND pokalname=\'%s\'AND pokalrunde=\'%s\'AND id!=%d',[$match->cupName,$match->cupRoundName,$match->id]);$openMatches=$result->fetch_array();$result->free();if(isset($openMatches['hits'])&&$openMatches['hits'])return;
							$result=$db->querySelect(['N.cup_round_id'=>'round_id','N.groupname'=>'groupname','N.rank'=>'rank','N.target_cup_round_id'=>'target_cup_round_id'],Config('db_prefix').'_cup_round_group_next AS N INNER JOIN '.Config('db_prefix').
							'_cup_round AS R ON N.cup_round_id=R.id INNER JOIN '.Config('db_prefix').'_cup AS C ON R.cup_id = C.id','C.name=\'%s\'AND R.name=\'%s\'',[$match->cupName,$match->cupRoundName]);$nextConfigs=[];while($nextConfig=$result->fetch_array()){
							$nextConfigs[$nextConfig['groupname']][''.$nextConfig['rank']]=$nextConfig['target_cup_round_id'];$roundId=$nextConfig['round_id'];}$result->free();$nextRoundTeams=[];foreach($nextConfigs as$groupName=>$rankings){
							$teamsInGroup=getTeamsOfCupGroupInRankingOrder($websoccer,$db,$roundId,$groupName);for($teamRank=1;$teamRank<=count($teamsInGroup);$teamRank++){$configIndex=''.$teamRank;if(isset($rankings[$configIndex])){
							$team=$teamsInGroup[$teamRank-1];$targetRound=$rankings[$configIndex];$nextRoundTeams[$targetRound][]=$team['id'];}}}$matchTable=Config('db_prefix').'_spiel';$type='Pokalspiel';foreach($nextRoundTeams as$nextRoundId=>$teamIds){
							$result=$db->querySelect('name,firstround_date,secondround_date',Config('db_prefix').'_cup_round','id = %d',$nextRoundId);$roundInfo=$result->fetch_array();$result->free();if(!$roundInfo)continue;$teams=$teamIds;shuffle($teams);
							while(count($teams)>1){$homeTeam=array_pop($teams);$guestTeam=array_pop($teams);$db->queryInsert(['spieltyp'=>$type,'pokalname'=>$match->cupName,'pokalrunde'=>$roundInfo['name'],'home_verein'=>$homeTeam,'gast_verein'=>$guestTeam,
							'datum'=>$roundInfo['firstround_date']],$matchTable);if($roundInfo['secondround_date']){$db->queryInsert(['spieltyp'=>$type,'pokalname'=>$match->cupName,'pokalrunde'=>$roundInfo['name'],'home_verein'=>$guestTeam,'gast_verein'=>$homeTeam,
							'datum'=>$roundInfo['secondround_date']],$matchTable);}}}}
	 		function createMatchForTeamAndRound($websoccer,$db,$teamId,$roundId,$firstRoundDate,$secondRoundDate,$cupName,$cupRound){$pendingTable=Config('db_prefix').'_cup_round_pending';$result=$db->querySelect('team_id',$pendingTable,'cup_round_id=%d',$roundId,1);
							$opponent=$result->fetch_array();if(!$opponent)$db->queryInsert(['team_id'=>$teamId,'cup_round_id'=>$roundId],$pendingTable);else{$matchTable=Config('db_prefix').'_spiel';$type='Pokalspiel';if(selectItemFromProbabilities(array(1=>50,0=>50))){
							$homeTeam=$teamId;$guestTeam=$opponent['team_id'];}else{$homeTeam=$opponent['team_id'];$guestTeam=$teamId;}$db->queryInsert(['spieltyp'=>$type,'pokalname'=>$cupName,'pokalrunde'=>$cupRound,'home_verein'=>$homeTeam,'gast_verein'=>$guestTeam,
							'datum'=>$firstRoundDate],$matchTable);if($secondRoundDate)$db->queryInsert(['spieltyp'=>$type,'pokalname'=>$cupName,'pokalrunde'=>$cupRound,'home_verein'=>$guestTeam,'gast_verein'=>$homeTeam,'datum'=>$secondRoundDate],$matchTable);
							$db->queryDelete($pendingTable,'team_id=%d AND cup_round_id=%d',[$opponent['team_id'],$roundId]);}}

class SimulationFormationHelper{
	 static function generateNewFormationForTeam($websoccer,$db,$team,$matchId){generateNewFormationForTeam($websoccer,$db,$team,$matchId);}}
			function generateNewFormationForTeam($websoccer,$db,$team,$matchId){$columns=['id'=>'id','position'=>'position','position_main'=>'mainPosition','vorname'=>'firstName','nachname'=>'lastName','kunstname'=>'pseudonym','w_staerke'=>'strength',
							'w_technik'=>'technique','w_kondition'=>'stamina','w_frische'=>'freshness','w_zufriedenheit'=>'satisfaction'];if(Config('players_aging')=='birthday')$ageColumn='TIMESTAMPDIFF(YEAR,geburtstag,CURDATE())';else$ageColumn='age';
							$columns[$ageColumn]='age';if(!$team->isNationalTeam){$result=$db->querySelect($columns,Config('db_prefix').'_spieler','verein_id=%d AND verletzt=0 AND gesperrt=0 AND status=1 ORDER BY w_frische DESC',$team->id);}else{$columnsStr='';
							$firstColumn=TRUE;foreach($columns as$dbName=>$aliasName){if(!$firstColumn)$columnsStr=$columnsStr.', ';else$firstColumn=FALSE;$columnsStr=$columnsStr.$dbName.' AS '.$aliasName;}$nation=$db->connection->escape_string($team->name);
							$queryStr='(SELECT '.$columnsStr.' FROM '.Config('db_prefix').'_spieler WHERE nation=\''.$nation.'\' AND position=\'Torwart\' ORDER BY w_staerke DESC, w_frische DESC LIMIT 1)';$queryStr.=' UNION ALL (SELECT '.$columnsStr.' FROM '.
							Config('db_prefix').'_spieler WHERE nation=\''.$nation.'\' AND position=\'Abwehr\' ORDER BY w_staerke DESC, w_frische DESC LIMIT 4)';$queryStr.=' UNION ALL (SELECT '.$columnsStr.' FROM '.Config('db_prefix').'_spieler WHERE nation=\''.
							$nation.'\' AND position=\'Mittelfeld\' ORDER BY w_staerke DESC, w_frische DESC LIMIT 4)';$queryStr.=' UNION ALL (SELECT '.$columnsStr.' FROM '.Config('db_prefix').'_spieler WHERE nation=\''.$nation.
							'\'AND position=\'Sturm\'ORDER BY w_staerke DESC,w_frische DESC LIMIT 2)';$result=$db->executeQuery($queryStr);}$lvExists=FALSE;$rvExists=FALSE;$lmExists=FALSE;$rmExists=FALSE;$ivPlayers=0;$zmPlayers=0;
							while($playerinfo=$result->fetch_array()){$position=$playerinfo['position'];if($position==config('PLAYER_POSITION_GOALY')&&isset($team->positionsAndPlayers[Config('PLAYER_POSITION_GOALY')])&&count($team->positionsAndPlayers[
							Config('PLAYER_POSITION_GOALY')])==1||$position==config('PLAYER_POSITION_DEFENCE')&&isset($team->positionsAndPlayers[config('PLAYER_POSITION_DEFENCE')])&&count($team->positionsAndPlayers[config('PLAYER_POSITION_DEFENCE')])>=4||
							$position==config('PLAYER_POSITION_MIDFIELD')&&isset($team->positionsAndPlayers[Config('PLAYER_POSITION_MIDFIELD')])&&count($team->positionsAndPlayers[Config('PLAYER_POSITION_MIDFIELD')])>= 4||$position==Config('PLAYER_POSITION_STRIKER')&&
							isset($team->positionsAndPlayers[Config('PLAYER_POSITION_STRIKER')])&&count($team->positionsAndPlayers[config('PLAYER_POSITION_STRIKER')])>= 2){continue;}$mainPosition=$playerinfo['mainPosition'];if($mainPosition=='LV'){if($lvExists){
							$mainPosition='IV';++$ivPlayers;if($ivPlayers==3){$mainPosition='RV';$rvExists=TRUE;}}else$lvExists=TRUE;}elseif($mainPosition=='RV'){if($rvExists){$mainPosition='IV';++$ivPlayers;if($ivPlayers==3){$mainPosition='LV';$lvExists=TRUE;}}
							else$rvExists=TRUE;}elseif($mainPosition=='IV'){++$ivPlayers;if($ivPlayers==3){if(!$rvExists){$mainPosition='RV';$rvExists=TRUE;}else{$mainPosition='LV';$lvExists=TRUE;}}}elseif($mainPosition=='LM'){if($lmExists){$mainPosition='ZM';
							++$zmPlayers;}else$lmExists=TRUE;}elseif($mainPosition=='RM'){if($rmExists){$mainPosition='ZM';++$zmPlayers;}else$rmExists=TRUE;}elseif($mainPosition=='LS'||$mainPosition=='RS')$mainPosition='MS';elseif($mainPosition=='ZM'){++$zmPlayers;
							if($zmPlayers>2)$mainPosition='DM';}$player=new SimulationPlayer($playerinfo['id'],$team,$position,$mainPosition, 3.0,$playerinfo['age'],$playerinfo['strength'],$playerinfo['technique'],$playerinfo['stamina'],$playerinfo['freshness'],
							$playerinfo['satisfaction']);if(strlen($playerinfo['pseudonym']))$player->name=$playerinfo['pseudonym'];else $player->name=$playerinfo['firstName'].' '.$playerinfo['lastName'];$team->positionsAndPlayers[$player->position][]=$player;
							createSimulationRecord($websoccer,$db,$matchId,$player);}}

class SimulationHelper{
	 static function selectItemFromProbabilities($probabilities){selectItemFromProbabilities($probabilities);}
	 static function getMagicNumber($min=1,$max=100){getMagicNumber($min=1,$max=100);}
	 static function selectPlayer($team,$position,$excludePlayer=null){selectPlayer($team,$position,$excludePlayer=null);}
	 static function getOpponentTeam($player,$match){getOpponentTeam($player,$match);}
	 static function getOpponentTeamOfTeam($team,$match){getOpponentTeamOfTeam($team,$match);}
	 static function checkAndExecuteSubstitutions($match,$team,$observers){checkAndExecuteSubstitutions($match,$team,$observers);}
	 static function createUnplannedSubstitutionForPlayer($minute,$playerOut){createUnplannedSubstitutionForPlayer($minute,$playerOut);}
	 static function getPlayersForPenaltyShooting($team){getPlayersForPenaltyShooting($team);}
	 static function selectPlayerFromBench($players,$position){selectPlayerFromBench($players,$position);}
	 static function addUnplannedSubstitution($minute,$substitution){addUnplannedSubstitution($minute,$substitution);}
	 static function sortByStrength($a,$b){sortByStrength($a,$b);}
	 static function getPositionsMapping(){getPositionsMapping();}}
			function selectItemFromProbabilities($probabilities){$magicNo=getMagicNumber();$oldBoundary=0;foreach($probabilities as$key=>$probability){$newBounday=$oldBoundary+$probability;if($magicNo>$oldBoundary&&$magicNo<=$newBounday)return$key;
							$oldBoundary=$newBounday;}return end($probabilities);}
			function getMagicNumber($min=1,$max=100){if($min==$max)return$min;return mt_rand($min,$max);}
			function selectPlayer($team,$position,$excludePlayer=null){$players=[];if(isset($team->positionsAndPlayers[$position])){if($excludePlayer==null||$excludePlayer->position!=$position)$players=$team->positionsAndPlayers[$position];
	 						else{foreach($team->positionsAndPlayers[$position] as$player){if($player->id!==$excludePlayer->id)$players[]=$player;}}}$noOfPlayers=count($players);if($noOfPlayers<1){if($position==Config('PLAYER_POSITION_STRIKER'))
							return selectPlayer($team,Config('PLAYER_POSITION_MIDFIELD'),$excludePlayer);elseif($position==Config('PLAYER_POSITION_MIDFIELD'))return selectPlayer($team,Config('PLAYER_POSITION_DEFENCE'),$excludePlayer);elseif($position==
							Config('PLAYER_POSITION_DEFENCE'))return selectPlayer($team,Config('PLAYER_POSITION_GOALY'),$excludePlayer);foreach($team->positionsAndPlayers as$pposition=>$pplayers){foreach($pplayers as$player){if($player->id!==$excludePlayer->id)
							return$player;}}}$player=$players[getMagicNumber(0,$noOfPlayers-1)];return$player;}
	 		function getOpponentTeam($player,$match){return($match->homeTeam->id==$player->team->id)?$match->guestTeam:$match->homeTeam;}
	 		function getOpponentTeamOfTeam($team,$match){return($match->homeTeam->id==$team->id)?$match->guestTeam:$match->homeTeam;}
	 		function checkAndExecuteSubstitutions(SimulationMatch$match,SimulationTeam$team,$observers){$substitutions=$team->substitutions;if(!count((array)$substitutions))return;foreach($substitutions as$substitution){if($substitution->minute==$match->minute&&
							!isset($team->removedPlayers[$substitution->playerOut->id])&&isset($team->playersOnBench[$substitution->playerIn->id])){if($substitution->condition=='Tie'&&$match->homeTeam->getGoals()!=$match->guestTeam->getGoals()||$substitution->condition
						 	=='Leading'&&$team->getGoals()<=getOpponentTeamOfTeam($team,$match)->getGoals()||$substitution->condition=='Deficit'&&$team->getGoals()>=getOpponentTeamOfTeam($team,$match)->getGoals()){$substitution->minute=999;continue;}
							$team->removePlayer($substitution->playerOut);if(strlen($substitution->position))$mainPosition=$substitution->position;elseif(strlen($substitution->playerIn->mainPosition)&&$substitution->playerIn->mainPosition!="-")$mainPosition=
							$substitution->playerIn->mainPosition;else$mainPosition=NULL;if($mainPosition==NULL)$position=$substitution->playerIn->position;else{$positionMapping=getPositionsMapping();$position=$positionMapping[$mainPosition];}$strength=
							$substitution->playerIn->strength;if($position!=$substitution->playerIn->position)$strength=round($strength*(1-Config('sim_strength_reduction_wrongposition')/100));elseif($mainPosition!=NULL&&$mainPosition!=
							$substitution->playerIn->mainPosition)$strength=round($strength*(1-Config('sim_strength_reduction_secondary')/100));$substitution->playerIn->position=$position;$substitution->playerIn->strength=$strength;$substitution->playerIn->mainPosition=
							$mainPosition;$team->positionsAndPlayers[$substitution->playerIn->position][]=$substitution->playerIn;unset($team->playersOnBench[$substitution->playerIn->id]);foreach($observers as$observer)$observer->onSubstitution($match,$substitution);}}}
	 		function createUnplannedSubstitutionForPlayer($minute,SimulationPlayer$playerOut){$team=$playerOut->team;if((array)count($team->playersOnBench)<1)return FALSE;$position=$playerOut->position;$player=selectPlayerFromBench($team->playersOnBench,$position);
							if($player==NULL&&$position==config('PLAYER_POSITION_STRIKER')){$player=selectPlayerFromBench($team->playersOnBench,Config('PLAYER_POSITION_MIDFIELD'));if($player==NULL)$player=selectPlayerFromBench($team->playersOnBench,
							Config('PLAYER_POSITION_DEFENCE'));}elseif($player==NULL &&$position==Config('PLAYER_POSITION_MIDFIELD')){$player=selectPlayerFromBench($team->playersOnBench,Config('PLAYER_POSITION_DEFENCE'));if($player==NULL)
							$player=selectPlayerFromBench($team->playersOnBench,Config('PLAYER_POSITION_STRIKER'));}elseif($player==NULL&&$position==Config('PLAYER_POSITION_DEFENCE')){$player=selectPlayerFromBench($team->playersOnBench,
							config('PLAYER_POSITION_MIDFIELD'));if($player==NULL)$player=selectPlayerFromBench($team->playersOnBench,Config('PLAYER_POSITION_STRIKER'));}if($player==NULL)return FALSE;$newsub=new SimulationSubstitution($minute,$player,$playerOut);
							return addUnplannedSubstitution($minute,$newsub);}
	 		function getPlayersForPenaltyShooting(SimulationTeam $team){$players=[];$goalkeeper=null;foreach($team->positionsAndPlayers as$position=>$playersAtPosition){if($position==Config('PLAYER_POSITION_GOALY')&&count($playersAtPosition)){$goalkeeper=
							$playersAtPosition[0];continue;}$players=array_merge($players,$playersAtPosition);}usort($players,['SimulationHelper','sortByStrength']);if($goalkeeper!=null)$players[]=$goalkeeper;return$players;}
	 		function selectPlayerFromBench(&$players,$position){foreach($players as$player){if($player->position==$position)return$player;}return NULL;}
	 		function addUnplannedSubstitution($minute,SimulationSubstitution$substitution){$team=$substitution->playerIn->team;if(!isset($team->playersOnBench[$substitution->playerIn->id])||$team->playersOnBench[$substitution->playerIn->id]==null)return FALSE;
							if(count($team->substitutions)<3){$team->substitutions[]=$substitution;return TRUE;}$index=0;foreach($team->substitutions as$existingSub){if($existingSub->minute>$minute&&$existingSub->playerIn->id==$substitution->playerIn->id){
							$team->substitutions[$index]=$substitution;return TRUE;}++$index;}$index=0;foreach($team->substitutions as$existingSub){if($existingSub->minute>$minute){$team->substitutions[$index]=$substitution;return TRUE;}++$index;}return FALSE;}
	 		function sortByStrength(SimulationPlayer $a, SimulationPlayer $b){ return$b->strength - $a->strength;}
	 		function getPositionsMapping(){return array('T'=>'Torwart','LV'=>'Abwehr','IV'=>'Abwehr','RV'=>'Abwehr','DM'=>'Mittelfeld','OM'=>'Mittelfeld','ZM'=>'Mittelfeld','LM'=>'Mittelfeld','RM'=>'Mittelfeld','LS'=>'Sturm','MS'=>'Sturm','RS'=>'Sturm');}

class SimulationStateHelper{
	 static function createSimulationRecord($websoccer,$db,$matchId,$player,$onBench=FALSE){createSimulationRecord($websoccer,$db,$matchId,$player,$onBench=FALSE);}
	 static function updateState($websoccer,$db,$match){updateState($websoccer,$db,$match);}
	 static function loadMatchState($websoccer,$db,$matchinfo){loadMatchState($websoccer,$db,$matchinfo);}
	 static function updateMatch($websoccer,$db,$match){updateMatch($websoccer,$db,$match);}
	 static function updateTeamState($websoccer,$db,$match,$team){updateTeamState($websoccer,$db,$match,$team);}
	 static function updatePlayerState($websoccer,$db,$matchId,$player,$fieldArea){updatePlayerState($websoccer,$db,$matchId,$player,$fieldArea);}
	 static function getPlayerColumns($matchId,$player,$fieldArea){getPlayerColumns($matchId,$player,$fieldArea);}
	 static function loadTeam($websoccer,$db,$matchId,$team){loadTeam($websoccer,$db,$matchId,$team);}}
			function createSimulationRecord($websoccer,$db,$matchId,$player,$onBench=FALSE){$db->queryInsert(getPlayerColumns($matchId,$player,($onBench)?'Ersatzbank':'1'),Config('db_prefix').'_spiel_berechnung');}
			function updateState($websoccer,$db,$match){updateMatch($websoccer,$db,$match);updateTeamState($websoccer,$db,$match,$match->homeTeam);updateTeamState($websoccer,$db,$match,$match->guestTeam);}
			function loadMatchState($websoccer,$db,$matchinfo){$homeTeam=new SimulationTeam($matchinfo['home_id']);$guestTeam=new SimulationTeam($matchinfo['guest_id']);loadTeam($websoccer,$db,$matchinfo['match_id'],$homeTeam);loadTeam($websoccer,$db,
							$matchinfo['match_id'],$guestTeam);$homeTeam->setGoals($matchinfo['home_goals']);$homeTeam->offensive=$matchinfo['home_offensive'];$homeTeam->isNationalTeam=$matchinfo['home_nationalteam'];$homeTeam->isManagedByInterimManager=
							$matchinfo['home_interimmanager'];$homeTeam->noFormationSet=$matchinfo['home_noformation'];$homeTeam->setup=$matchinfo['home_setup'];$homeTeam->name=$matchinfo['home_name'];$homeTeam->longPasses=$matchinfo['home_longpasses'];
							$homeTeam->counterattacks=$matchinfo['home_counterattacks'];$homeTeam->morale=$matchinfo['home_morale'];$guestTeam->setGoals($matchinfo['guest_goals']);$guestTeam->offensive=$matchinfo['guest_offensive'];$guestTeam->isNationalTeam=
							$matchinfo['guest_nationalteam'];$guestTeam->isManagedByInterimManager=$matchinfo['guest_interimmanager'];$guestTeam->noFormationSet=$matchinfo['guest_noformation'];$guestTeam->setup=$matchinfo['guest_setup'];$guestTeam->name=
							$matchinfo['guest_name'];$guestTeam->longPasses=$matchinfo['guest_longpasses'];$guestTeam->counterattacks=$matchinfo['guest_counterattacks'];$guestTeam->morale=$matchinfo['guest_morale'];$match=new SimulationMatch($matchinfo['match_id'],
							$homeTeam,$guestTeam,$matchinfo['minutes']);$match->type=$matchinfo['type'];$match->penaltyShootingEnabled=$matchinfo['penaltyshooting'];$match->isSoldOut=$matchinfo['soldout'];$match->cupName=$matchinfo['cup_name'];$match->cupRoundName=
							$matchinfo['cup_roundname'];$match->cupRoundGroup=$matchinfo['cup_groupname'];$match->isAtForeignStadium=($matchinfo['custom_stadium_id'])?TRUE:FALSE;if($matchinfo['player_with_ball']&&isset(Value::$_addedPlayers[
							$matchinfo['player_with_ball']]))$match->setPlayerWithBall(Value::$_addedPlayers[$matchinfo['player_with_ball']]);if($matchinfo['prev_player_with_ball']&&isset(Value::$_addedPlayers[$matchinfo['prev_player_with_ball']]))
							$match->setPreviousPlayerWithBall(Value::$_addedPlayers[$matchinfo['prev_player_with_ball']]);if($matchinfo['home_freekickplayer']&&isset(Value::$_addedPlayers[$matchinfo['home_freekickplayer']]))$homeTeam->freeKickPlayer=
							Value::$_addedPlayers[$matchinfo['home_freekickplayer']];if($matchinfo['guest_freekickplayer']&&isset(Value::$_addedPlayers[$matchinfo['guest_freekickplayer']]))$guestTeam->freeKickPlayer=Value::$_addedPlayers[
							$matchinfo['guest_freekickplayer']];for($subNo=1;$subNo<=3;++$subNo){if($matchinfo['home_sub_'.$subNo.'_out']&&isset(Value::$_addedPlayers[$matchinfo['home_sub_'.$subNo.'_in']])&&isset(Value::$_addedPlayers[$matchinfo['home_sub_'.$subNo.
							'_out']])){$sub=new SimulationSubstitution($matchinfo['home_sub_'.$subNo.'_minute'],Value::$_addedPlayers[$matchinfo['home_sub_'.$subNo.'_in']],Value::$_addedPlayers[$matchinfo['home_sub_'.$subNo.'_out']],$matchinfo['home_sub_'.$subNo.
							'_condition'],$matchinfo['home_sub_'.$subNo.'_position']);$homeTeam->substitutions[]=$sub;}if($matchinfo['guest_sub_'.$subNo.'_out']&&isset(Value::$_addedPlayers[$matchinfo['guest_sub_'.$subNo.'_in']])&&isset(Value::$_addedPlayers[
							$matchinfo['guest_sub_'.$subNo.'_out']])){$sub=new SimulationSubstitution($matchinfo['guest_sub_'.$subNo.'_minute'],Value::$_addedPlayers[$matchinfo['guest_sub_'.$subNo.'_in']],Value::$_addedPlayers[$matchinfo['guest_sub_'.$subNo.'_out']],
							$matchinfo['guest_sub_'.$subNo.'_condition'],$matchinfo['guest_sub_'.$subNo.'_position']);$guestTeam->substitutions[]=$sub;}}Value::$_addedPlayers=null;return$match;}
			function updateMatch($websoccer,$db,$match){if($match->isCompleted)$columns['berechnet']=1;$columns=['minutes'=>$match->minute,'soldout'=>($match->isSoldOut)?'1':'0','home_tore'=>$match->homeTeam->getGoals(),'gast_tore'=>$match->guestTeam->getGoals(),
							'home_setup'=>$match->homeTeam->setup,'gast_setup'=>$match->guestTeam->setup,'home_offensive'=>$match->homeTeam->offensive,'gast_offensive'=>$match->guestTeam->offensive,'home_noformation'=>($match->homeTeam->noFormationSet)?'1':'0',
							'guest_noformation'=>($match->guestTeam->noFormationSet)?'1':'0','home_longpasses'=>($match->homeTeam->longPasses)?'1':'0','gast_longpasses'=>($match->guestTeam->longPasses)?'1':'0','home_counterattacks'=>($match->homeTeam->counterattacks)?
							'1':'0','gast_counterattacks'=>($match->guestTeam->counterattacks)?'1':'0','home_morale'=>$match->homeTeam->morale,'gast_morale'=>$match->guestTeam->morale];if($match->getPlayerWithBall()!=null)$columns['player_with_ball']=
							$match->getPlayerWithBall()->id;else$columns['player_with_ball']=0;if($match->getPreviousPlayerWithBall()!=null)$columns['prev_player_with_ball']=$match->getPreviousPlayerWithBall()->id;else$columns['prev_player_with_ball']=0;
							$columns['home_freekickplayer']=($match->homeTeam->freeKickPlayer!=NULL)?$match->homeTeam->freeKickPlayer->id : '';$columns['gast_freekickplayer']=($match->guestTeam->freeKickPlayer!=NULL)?$match->guestTeam->freeKickPlayer->id : '';
							if(is_array($match->homeTeam->substitutions)){foreach($match->homeTeam->substitutions as$substitution){$columns=['home_w'.$subIndex.'_raus'=>$substitution->playerOut->id,'home_w'.$subIndex.'_rein'=>$substitution->playerIn->id,
							'home_w'.$subIndex.'_minute'=>$substitution->minute,'home_w'.$subIndex.'_condition'=>$substitution->condition,'home_w'.$subIndex.'_position'=>$substitution->position];++$subIndex;}}if(is_array($match->guestTeam->substitutions)){
							foreach($match->guestTeam->substitutions as$substitution){$columns=['gast_w'.$subIndex.'_raus'=>$substitution->playerOut->id,'gast_w'.$subIndex.'_rein'=>$substitution->playerIn->id,'gast_w'.$subIndex.'_minute'=>$substitution->minute,
							'gast_w'.$subIndex.'_condition'=>$substitution->condition,'gast_w'.$subIndex.'_position'=>$substitution->position];++$subIndex;}}$db->queryUpdate($columns,Config('db_prefix').'_spiel','id=%d',$match->id);}
			function updateTeamState($websoccer,$db,$match,$team){if(is_array($team->positionsAndPlayers)){foreach($team->positionsAndPlayers as$positions=>$players){foreach($players as$player)updatePlayerState($websoccer,$db,$match->id,$player,'1');}}
							if(is_array($team->playersOnBench)){foreach($team->playersOnBench as$player)updatePlayerState($websoccer,$db,$match->id,$player,'Ersatzbank');}if(is_array($team->removedPlayers)){foreach($team->removedPlayers as$player)
							updatePlayerState($websoccer,$db,$match->id,$player,'Ausgewechselt');}}
			function updatePlayerState($websoccer,$db,$matchId,$player,$fieldArea){$db->queryUpdate(getPlayerColumns($matchId,$player,$fieldArea),Config('db_prefix').'_spiel_berechnung','spieler_id=%d AND spiel_id=%d',[$player->id,$matchId]);}
			function getPlayerColumns($matchId,$player,$fieldArea){return['spiel_id'=>$matchId,'spieler_id'=>$player->id,'team_id'=>$player->team->id,'name'=>$player->name,'note'=>$player->getMark(),'minuten_gespielt'=>$player->getMinutesPlayed(),'karte_gelb'=>$player->
							yellowCards,'karte_rot'=>$player->redCard,'verletzt'=>$player->injured,'gesperrt'=>$player->blocked,'tore'=>$player->getGoals(),'feld'=>$fieldArea,'position'=>$player->position,'position_main'=>$player->mainPosition,'age'=>$player->age,
							'w_staerke'=>$player->strength,'w_technik'=>$player->strengthTech,'w_kondition'=>$player->strengthStamina,'w_frische'=>$player->strengthFreshness,'w_zufriedenheit'=>$player->strengthSatisfaction,'ballcontacts'=>$player->getBallContacts(),
							'wontackles'=>$player->getWonTackles(),'losttackles'=>$player->getLostTackles(),'shoots'=>$player->getShoots(),'passes_successed'=>$player->getPassesSuccessed(),'passes_failed'=>$player->getPassesFailed(),'assists'=>$player->getAssists()];}
			function loadTeam($websoccer,$db,$matchId,$team){$result=$db->querySelect(['spieler_id'=>'player_id','name'=>'name','note'=>'mark','minuten_gespielt'=>'minutes_played','karte_gelb'=>'yellow_cards','karte_rot'=>'red_cards','verletzt'=>'injured',
							'gesperrt'=>'blocked','tore'=>'goals','feld'=>'field_area','position'=>'position','position_main'=>'main_position','age'=>'age','w_staerke'=>'strength','w_technik'=>'strength_tech','w_kondition'=>'strength_stamina',
							'w_frische'=>'strength_freshness','w_zufriedenheit'=>'strength_satisfaction','ballcontacts'=>'ballcontacts','wontackles'=>'wontackles','losttackles'=>'losttackles','shoots'=>'shoots','passes_successed'=>'passes_successed',
							'passes_failed'=>'passes_failed','assists'=>'assists'],Config('db_prefix').'_spiel_berechnung','spiel_id=%d AND team_id=%d ORDER BY id ASC',[$matchId,$team->id]);while($playerinfo=$result->fetch_array()){$player=new SimulationPlayer(
							$playerinfo['player_id'],$team,$playerinfo['position'],$playerinfo['main_position'],$playerinfo['mark'],$playerinfo['age'],$playerinfo['strength'],$playerinfo['strength_tech'],$playerinfo['strength_stamina'],$playerinfo['strength_freshness'],
							$playerinfo['strength_satisfaction']);$player->name=$playerinfo['name'];$player->setBallContacts($playerinfo['ballcontacts']);$player->setWonTackles($playerinfo['wontackles']);$player->setLostTackles($playerinfo['losttackles']);
							$player->setGoals($playerinfo['goals']);$player->setShoots($playerinfo['shoots']);$player->setPassesSuccessed($playerinfo['passes_successed']);$player->setPassesFailed($playerinfo['passes_failed']);$player->setAssists($playerinfo['assists']);
							$player->setMinutesPlayed($playerinfo['minutes_played'],FALSE);$player->yellowCards=$playerinfo['yellow_cards'];$player->redCard=$playerinfo['red_cards'];$player->injured=$playerinfo['injured'];$player->blocked=$playerinfo['blocked'];
							Value::$_addedPlayers[$player->id]=$player;if($playerinfo['field_area']=='Ausgewechselt')$team->removedPlayers[$player->id]=$player;elseif($playerinfo['field_area']=='Ersatzbank')$team->playersOnBench[$player->id]=$player;
							else $team->positionsAndPlayers[$player->position][]=$player;}}

class StringUtil{
	 static function startsWith($message,$needle){startsWith($message,$needle);}
	 static function endsWith($message,$needle){endsWith($message,$needle);}
	 static function convertTimestampToWord($timestamp,$nowAsTimestamp,$i18n){convertTimestampToWord($timestamp,$nowAsTimestamp,$i18n);}}
			function startsWith($message,$needle){return!strncmp($message,$needle,strlen($needle));}
	 		function endsWith($message,$needle){$length=strlen($needle);if($length==0)return true;return(substr($message,-$length)===$needle);}
	 		function convertTimestampToWord($timestamp,$nowAsTimestamp,$i18n){if($timestamp>=strtotime('tomorrow',$nowAsTimestamp)+24*3600)return'';if($timestamp>=strtotime('tomorrow',$nowAsTimestamp))return Message('date_tomorrow');elseif($timestamp>=strtotime('today',
	 						$nowAsTimestamp))return Message('date_today');elseif($timestamp>=strtotime('yesterday',$nowAsTimestamp))return Message('date_yesterday');return'';}

class TwigAutoloader{
	static function register(){spl_autoload_register([__CLASS__,'autoload'],true);}
	static function autoload($class){if(0!==strpos($class,'Twig')){return;}require($_SERVER['DOCUMENT_ROOT'].'/lib/Twig3/'.str_replace(['Twig\\','\\',"\0"],['','/',''],$class).'.php');}}

class UrlUtil{
	 static function buildCurrentUrlWithParameters($parameters){buildCurrentUrlWithParameters($parameters);}}
			function buildCurrentUrlWithParameters($parameters){$url=htmlentities($_SERVER['PHP_SELF']).'?';$first=TRUE;foreach($parameters as$parameterName=>$parameterValue){if(!$first)$url.='&';$url.=$parameterName.'='.$parameterValue;$first=FALSE;}
							foreach($_GET as$parameterName=>$parameterValue){if(!isset($parameters[$parameterName]))$url.='&'.$parameterName.'='.$parameterValue;}return escapeOutput($url);}

class YouthMatchSimulationExecutor{
	 static function simulateOpenYouthMatches($websoccer,$db,$maxMatchesToSimulate){simulateOpenYouthMatches($websoccer,$db,$maxMatchesToSimulate);}
	 static function createMatch($websoccer,$db,$matchinfo){createMatch($websoccer,$db,$matchinfo);}
	 static function addPlayers($websoccer,$db,$match,$team){YouthAddPlayers($websoccer,$db,$match,$team);}
	 static function createRandomFormation($websoccer,$db,$match,$team){createRandomFormation($websoccer,$db,$match,$team);}
	 static function addSubstitutions($websoccer,$db,$match,$team,$matchinfo,$teamPrefix){addSubstitutions($websoccer,$db,$match,$team,$matchinfo,$teamPrefix);}
	 		function findPlayerOnField($team,$playerId){findPlayerOnField($team,$playerId);}}
	 		function simulateOpenYouthMatches($websoccer,$db,$maxMatchesToSimulate){if(!Config('youth_enabled'))return;$simulator=new Simulator($db,$websoccer);$simulator->attachObserver(new YouthMatchDataUpdateSimulatorObserver($websoccer,$db));
							$simulator->getSimulationStrategy()->attachObserver(new YouthMatchReportSimulationObserver($websoccer,$db));$result=$db->querySelect('*',Config('db_prefix').'_youthmatch','simulated!=\'1\'AND matchdate<=%d ORDER BY matchdate ASC',Timestamp(),
							$maxMatchesToSimulate);while($matchinfo=$result->fetch_array()){$match=createMatch($websoccer,$db,$matchinfo);if($match!=null){$simulator->simulateMatch($match,100);$match->cleanReferences();unset($match);}}}
	 		function createMatch($websoccer,$db,$matchinfo){$homeTeam=new SimulationTeam($matchinfo['home_team_id'],Config('DEFAULT_YOUTH_OFFENSIVE'));$guestTeam=new SimulationTeam($matchinfo['guest_team_id'],Config('DEFAULT_YOUTH_OFFENSIVE'));
							$match=new SimulationMatch($matchinfo['id'],$homeTeam,$guestTeam,0);$match->type='Youth';$match->penaltyShootingEnabled=FALSE;YouthAddPlayers($websoccer,$db,$match,$homeTeam);addSubstitutions($websoccer,$db,$match,$homeTeam,$matchinfo,'home');
							YouthAddPlayers($websoccer,$db,$match,$guestTeam);addSubstitutions($websoccer,$db,$match,$guestTeam,$matchinfo,'guest');return$match;}
	 		function YouthAddPlayers($websoccer,$db,$match,$team){$result=$db->querySelect(['P.id'=>'id','P.strength'=>'player_strength','P.firstname'=>'firstname','P.lastname'=>'lastname','P.position'=>'player_position','MP.position'=>'match_position',
							'MP.position_main'=>'match_position_main','MP.grade'=>'grade','MP.state'=>'state'],Config('db_prefix').'_youthmatch_player AS MP INNER JOIN '.Config('db_prefix').'_youthplayer AS P ON P.id=MP.player_id','MP.match_id=%d AND MP.
							team_id=%d AND P.team_id=%d ORDER BY playernumber ASC',[$match->id,$team->id,$team->id]);$addedFieldPlayers=0;while($playerinfo=$result->fetch_array()){$name=$playerinfo['firstname'].' '.$playerinfo['lastname'];
							$strength=$playerinfo['player_strength'];$technique=$strength;$position=$playerinfo['player_position'];$mainPosition=$playerinfo['match_position_main'];$player=new SimulationPlayer($playerinfo['id'],$team,$position,$mainPosition,
							$playerinfo['grade'],config('DEFAULT_PLAYER_AGE'),$strength,$technique,Config('YOUTH_STRENGTH_STAMINA'),Config('YOUTH_STRENGTH_FRESHNESS'),Config('YOUTH_STRENGTH_SATISFACTION'));$player->name=$name;if($playerinfo['state']=='Ersatzbank')
							$team->playersOnBench[$playerinfo['id']]=$player;else{if($addedFieldPlayers==0){$player->position='Torwart';$player->mainPosition='T';}else$player->position=$playerinfo['match_position'];if($player->position!=$playerinfo['player_position'])
							$player->strength=round($strength*(1-Config('sim_strength_reduction_wrongposition')/100));$team->positionsAndPlayers[$player->position][]=$player;++$addedFieldPlayers;}}if($addedFieldPlayers<config('MIN_NUMBER_OF_PLAYERS')){
							$team->noFormationSet=TRUE;createRandomFormation($websoccer,$db,$match,$team);}}
	 		function createRandomFormation($websoccer,$db,$match,$team){$db->queryDelete(Config('db_prefix').'_youthmatch_player','match_id=%d AND team_id=%d',[$match->id,$team->id]);$formationPositions=['T','LV','IV','IV','RV','LM','ZM','ZM','RM','LS','RS'];
							$positionMapping=getPositionsMapping();$players=YouthPlayersDataService::getYouthPlayersOfTeam($websoccer,$db,$team->id);$positionIndex=0;foreach($players as$playerinfo){$mainPosition=$formationPositions[$positionIndex];
							$position=$positionMapping[$mainPosition];$player=new SimulationPlayer($playerinfo['id'],$team,$position,$mainPosition,3.0,Config('DEFAULT_PLAYER_AGE'),$playerinfo['strength'],$playerinfo['strength'],Config('YOUTH_STRENGTH_STAMINA'),
							Config('YOUTH_STRENGTH_FRESHNESS'),Config('YOUTH_STRENGTH_SATISFACTION'));$player->name=$playerinfo['firstname'].' '.$playerinfo['lastname'];if($player->position!=$playerinfo['position'])$player->strength=round($playerinfo['strength']*
							(1-Config('sim_strength_reduction_wrongposition')/100));try{$columns=array('match_id'=>$match->id,'team_id'=>$team->id,'player_id'=>$player->id,'playernumber'=>$positionIndex+1,'position'=>$player->position, 'position_main'=>$player->
							mainPosition,'name'=>$player->name);$db->queryInsert($columns,Config('db_prefix').'_youthmatch_player');$team->positionsAndPlayers[$player->position][]=$player;}catch(Exception$e){}++$positionIndex;if($positionIndex==11)break;}}
	 		function addSubstitutions($websoccer,$db,$match,$team,$matchinfo,$teamPrefix){for($subNo=1;$subNo<=3;++$subNo){if($matchinfo[$teamPrefix.'_s'.$subNo.'_out']){$out=$matchinfo[$teamPrefix.'_s'.$subNo.'_out'];$in=$matchinfo[$teamPrefix.'_s'.$subNo.'_in'];
							$minute=$matchinfo[$teamPrefix.'_s'.$subNo.'_minute'];$condition=$matchinfo[$teamPrefix.'_s'.$subNo.'_condition'];$position=$matchinfo[$teamPrefix.'_s'.$subNo.'_position'];if(isset($team->playersOnBench[$in])){$playerIn=$team->
							playersOnBench[$in];$playerOut=findPlayerOnField($team,$out);if($playerIn&&$playerOut){$sub=new SimulationSubstitution($minute,$playerIn,$playerOut,$condition,$position);$team->substitutions[]=$sub;}}}}}

class StadiumEnvironmentPlugin{
	 static function addTrainingBonus($event){addTrainingBonus($event);}
	 static function addYouthPlayerSkillBonus($event){addYouthPlayerSkillBonus($event);}
	 static function addTicketsBonus($event){addTicketsBonus($event);}
	 static function creditAndDebitAfterHomeMatch($event){creditAndDebitAfterHomeMatch($event);}
	 static function handleInjuriesAfterMatch($event){handleInjuriesAfterMatch($event);}
	 static function getBonusSumFromBuildings($websoccer,$db,$attributeName,$teamId){getBonusSumFromBuildings($websoccer,$db,$attributeName,$teamId);}}
	 		function addTrainingBonus(PlayerTrainedEvent$event){$bonus=etBonusSumFromBuildings($event->websoccer,$event->db,'effect_training',$event->teamId);$event->effectSatisfaction+=$bonus;$event->effectFreshness+=$bonus;}
	 		function addYouthPlayerSkillBonus(YouthPlayerScoutedEvent$event){$bonus=getBonusSumFromBuildings($event->websoccer,$event->db,'effect_youthscouting',$event->teamId);if($bonus!=0){$playerTable=Config('db_prefix').'_youthplayer';
							$result=$event->db->querySelect('strength',$playerTable,'id=%d',$event->playerId);$player=$result->fetch_array();if($player){$minStrength=(int)Config('youth_scouting_min_strength');$maxStrength=(int)Config('youth_scouting_max_strength');
							$strength=max($minStrength,min($maxStrength,$player['strength']+$bonus));if($strength!=$player['strength'])$event->db->queryUpdate(array('strength'=>$strength),$playerTable,'id=%d',$event->playerId);}}}
	 		function addTicketsBonus(TicketsComputedEvent$event){$bonus=getBonusSumFromBuildings($event->websoccer,$event->db,'effect_tickets',$event->match->homeTeam->id);if($bonus==0)return;$bonus=$bonus/100;
							if($event->rateSeats)$event->rateSeats=max(0.0,min(1.0,$event->rateSeats+$bonus));if($event->rateStands)$event->rateStands=max(0.0,min(1.0,$event->rateStands+$bonus));if($event->rateSeatsGrand)$event->rateSeatsGrand=
							max(0.0,min(1.0,$event->rateSeatsGrand+$bonus));if($event->rateStandsGrand)$event->rateStandsGrand=max(0.0,min(1.0,$event->rateStandsGrand+$bonus));if($event->rateVip)$event->rateVip=max(0.0,min(1.0,$event->rateVip+$bonus));}
	 		function creditAndDebitAfterHomeMatch(MatchCompletedEvent$event){if($event->match->type=='Freundschaft'||$event->match->homeTeam->isNationalTeam)return;$homeTeamId=$event->match->homeTeam->id;$sum=getBonusSumFromBuildings($event->websoccer,
		 					$event->db,'effect_income',$homeTeamId);if($sum)creditAmount($event->websoccer,$event->db,$homeTeamId,$sum,'stadiumenvironment_matchincome_subject',Config('projectname'));else
		 					debitAmount($event->websoccer,$event->db,$homeTeamId,abs($sum),'stadiumenvironment_costs_per_match_subject',Config('projectname'));}
	 		function handleInjuriesAfterMatch(MatchCompletedEvent$event){if($event->match->type=='Freundschaft'||$event->match->homeTeam->isNationalTeam)return;$homeTeamId=$event->match->homeTeam->id;$sumHome=getBonusSumFromBuildings($event->websoccer,
		 					$event->db,'effect_injury',$homeTeamId);$guestTeamId=$event->match->guestTeam->id;$sumGuest=getBonusSumFromBuildings($event->websoccer,$event->db,'effect_injury',$guestTeamId);if($sumHome>0||$sumGuest){
		 					$playerTable=Config('db_prefix').'_spieler';$result=$event->db->querySelect('id,verein_id AS team_id,verletzt AS injured',$playerTable,'(verein_id=%d OR verein_id=%d)AND verletzt>0',array($homeTeamId,$guestTeamId));
							while($player=$result->fetch_array()){$reduction=0;if($sumHome>0&&$player['team_id']==$homeTeamId)$reduction=$sumHome;elseif($sumGuest>0&&$player['team_id']==$guestTeamId)$reduction=$sumGuest;if($reduction){
							$injured=max(0,$player['injured']-$reduction);$event->db->queryUpdate(array('verletzt'=>$injured),$playerTable,'id=%d',$player['id']);}}}}
	 		function getBonusSumFromBuildings($websoccer,$db,$attributeName,$teamId){$dbPrefix=Config('db_prefix');$result=$db->querySelect('SUM('.$attributeName.')AS attrSum',$dbPrefix.'_buildings_of_team INNER JOIN '.$dbPrefix.'_stadiumbuilding ON id=building_id',
		 					'team_id=%d AND construction_deadline<%d',array($teamId,Timestamp()));$resArray=$result->fetch_array();if($resArray)return$resArray['attrSum'];return 0;}
class AbsencesDataService{
	 static function getCurrentAbsenceOfUser($websoccer,$db,$userId){getCurrentAbsenceOfUser($websoccer,$db,$userId);}
	 static function makeUserAbsent($websoccer,$db,$userId,$deputyId,$days){makeUserAbsent($websoccer,$db,$userId,$deputyId,$days);}
	 static function confirmComeback($websoccer,$db,$userId){confirmComeback($websoccer,$db,$userId);}}
	 		function getCurrentAbsenceOfUser($websoccer,$db,$userId){$result=$db->querySelect('*',Config('db_prefix').'_userabsence','user_id=%d ORDER BY from_date DESC',$userId,1);$absence=$result->fetch_array();return$absence;}
	 		function makeUserAbsent($websoccer,$db,$userId,$deputyId,$days){$toDate=Timestamp()+24*3600*$days;$db->queryInsert(['user_id'=>$userId,'deputy_id'=>$deputyId,'from_date'=>$fromDate,'to_date'=>$toDate],Config('db_prefix').'_userabsence');
							$db->queryUpdate(['user_id'=>$deputyId,'user_id_actual'=>$userId],Config('db_prefix').'_verein','user_id=%d',$userId);$user=UsersDataService::getUserById($websoccer,$db,$userId);
							NotificationsDataService::createNotification($websoccer,$db,$deputyId,'absence_notification',['until'=>$toDate,'user'=>$user['nick']],'absence','user');}
	 		function confirmComeback($websoccer,$db,$userId){$absence=getCurrentAbsenceOfUser($websoccer,$db,$userId);if(!$absence)return;$db->queryUpdate(['user_id'=>$userId,'user_id_actual'=>NULL],Config('db_prefix').'_verein','user_id_actual=%d',$userId);
							$db->queryDelete(Config('db_prefix').'_userabsence','user_id',$userId);if($absence['deputy_id']){$user=UsersDataService::getUserById($websoccer,$db,$userId);
							NotificationsDataService::createNotification($websoccer,$db,$absence['deputy_id'],'absence_comeback_notification',['user'=>$user['nick']],'absence','user');}}

class ActionLogDataService{
	 static function getActionLogsOfUser($websoccer,$db,$userId,$limit=10){getActionLogsOfUser($websoccer,$db,$userId,$limit=10);}
	 static function getLatestActionLogs($websoccer,$db,$limit=10){getLatestActionLogs($websoccer,$db,$limit=10);}
	 static function createOrUpdateActionLog($websoccer,$db,$userId,$actionId){createOrUpdateActionLog($websoccer,$db,$userId,$actionId);}}
	 		function getActionLogsOfUser($websoccer,$db,$userId,$limit=10){$result=$db->querySelect(['L.id'=>'log_id','L.action_id'=>'action_id','L.user_id'=>'user_id','L.created_date'=>'created_date','U.nick'=>'user_name'],Config('db_prefix').
	 						'_useractionlog AS L INNER JOIN '.Config('db_prefix').'_user AS U ON U.id=L.user_id','L.user_id=%d ORDER BY L.created_date DESC',$userId,$limit);$logs=[];while($log=$result->fetch_array())$logs[]=$log;return$logs;}
	 		function getLatestActionLogs($websoccer,$db,$limit=10){$result=$db->querySelect(['L.id'=>'log_id','L.action_id'=>'action_id','L.user_id'=>'user_id','L.created_date'=>'created_date','U.nick'=>'user_name'],Config('db_prefix').
	 						'_useractionlog AS L INNER JOIN '.Config('db_prefix').'_user AS U ON U.id=L.user_id','1 ORDER BY L.id DESC',null,$limit);$logs=[];while($log=$result->fetch_array())$logs[]=$log;return$logs;}
			function createOrUpdateActionLog($websoccer,$db,$userId,$actionId){$db->queryDelete(Config('db_prefix').'_useractionlog','user_id=%d AND created_date<%d',[$userId,Timestamp()-24*3600*20]);$result=$db->querySelect('id',Config('db_prefix').'_useractionlog',
	 						'user_id=%d AND action_id=\'%s\'AND created_date>=%d ORDER BY created_date DESC',[$userId,$actionId,Timestamp()-30*60],1);$lastLog=$result->fetch_array();if($lastLog)$db->queryUpdate(array('created_date'=>Timestamp()),Config('db_prefix').
	 						'_useractionlog','id=%d',$lastLog['id']);else$db->queryInsert(['user_id'=>$userId,'action_id'=>$actionId,'created_date'=>Timestamp()],Config('db_prefix').'_useractionlog');}
class BadgesDataService{
	 static function awardBadgeIfApplicable($websoccer,$db,$userId,$badgeEvent,$benchmark=NULL){awardBadgeIfApplicable($websoccer,$db,$userId,$badgeEvent,$benchmark=NULL);}
	 static function awardBadge($websoccer,$db,$userId,$badgeId){awardBadge($websoccer,$db,$userId,$badgeId);}}
			function awardBadgeIfApplicable($websoccer,$db,$userId,$badgeEvent,$benchmark=NULL){$parameters=array($badgeEvent);$whereCondition='event=\'%s\'';if($benchmark!==NULL){$whereCondition.=' AND event_benchmark <= %d';$parameters[]=$benchmark;}
							$whereCondition.=' ORDER BY level DESC';$result=$db->querySelect('id, name, level',Config('db_prefix').'_badge',$whereCondition,$parameters,1);$badge=$result->fetch_array();if(!$badge)return;$whereCondition=
							'user_id=%d AND event=\'%s\'AND level>=\'%s\'';$result=$db->querySelect('COUNT(*)AS hits',Config('db_prefix').'_badge'.' INNER JOIN '.Config('db_prefix').'_badge_user'.' ON id=badge_id',$whereCondition,[$userId,$badgeEvent,$badge['level']],1);
							$userBadges=$result->fetch_array();if($userBadges&&$userBadges['hits'])return;awardBadge($websoccer,$db,$userId,$badge['id']);}
	 		function awardBadge($websoccer,$db,$userId,$badgeId){$db->queryInsert(['user_id'=>$userId,'badge_id'=>$badgeId,'date_rewarded'=>Timestamp()],Config('db_prefix').'_badge_user');NotificationsDataService::createNotification($websoccer,$db,$userId,
	 						'badge_notification',null,'badge','user','id='.$userId);}

class BankAccountDataService{
	 static function countAccountStatementsOfTeam($websoccer,$db,$teamId){countAccountStatementsOfTeam($websoccer,$db,$teamId);}
	 static function getAccountStatementsOfTeam($websoccer,$db,$teamId,$startIndex,$entries_per_page){getAccountStatementsOfTeam($websoccer,$db,$teamId,$startIndex,$entries_per_page);}
	 static function creditAmount($websoccer,$db,$teamId,$amount,$subject,$sender){creditAmount($websoccer,$db,$teamId,$amount,$subject,$sender);}
	 static function debitAmount($websoccer,$db,$teamId,$amount,$subject,$sender){debitAmount($websoccer,$db,$teamId,$amount,$subject,$sender);}
	 static function createTransaction($websoccer,$db,$team,$teamId,$amount,$subject,$sender){createTransaction($websoccer,$db,$team,$teamId,$amount,$subject,$sender);}}
			function countAccountStatementsOfTeam($websoccer,$db,$teamId){$result=$db->querySelect('COUNT(*)AS hits',Config('db_prefix').'_konto','verein_id=%d',$teamId);$statements=$result->fetch_array();if(isset($statements['hits']))return$statements['hits'];return 0;}
	 		function getAccountStatementsOfTeam($websoccer,$db,$teamId,$startIndex,$entries_per_page){$result=$db->querySelect(['absender'=>'sender','betrag'=>'amount','datum'=>'date','verwendung'=>'subject'],Config('db_prefix').'_konto',
	 						'verein_id=%d ORDER BY datum DESC',$teamId,$startIndex.','.$entries_per_page);$statements=[];while($statement=$result->fetch_array())$statements[]=$statement;return$statements;}
			function creditAmount($websoccer,$db,$teamId,$amount,$subject,$sender){if($amount==0)return;$team=TeamsDataService::getTeamSummaryById($websoccer,$db,$teamId);if(!isset($team['team_id']))throw new Exception('team not found: '.$teamId);
							if($amount<0)throw new Exception('amount illegal: '.$amount);else createTransaction($websoccer,$db,$team,$teamId,$amount,$subject,$sender);}
	 		function debitAmount($websoccer,$db,$teamId,$amount,$subject,$sender){if($amount==0)return;$team=TeamsDataService::getTeamSummaryById($websoccer,$db,$teamId);if(!isset($team['team_id']))throw new Exception('team not found: '.$teamId);
							if($amount<0)throw new Exception('amount illegal: '.$amount);$amount=0-$amount;createTransaction($websoccer,$db,$team,$teamId,$amount,$subject,$sender);}
	 		function createTransaction($websoccer,$db,$team,$teamId,$amount,$subject,$sender){if(!$team['user_id']&&Config('no_transactions_for_teams_without_user'))return;$db->queryInsert(['verein_id'=>$teamId,'absender'=>$sender,
	 						'betrag'=>$amount,'datum'=>Timestamp(),'verwendung'=>$subject],Config('db_prefix').'_konto');$updateColumns['finanz_budget']=$team['team_budget']+$amount;$db->queryUpdate($updateColumns,Config('db_prefix').'_verein','id=%d',$teamId);}
class CupsDataService{
	 static function getTeamsOfCupGroupInRankingOrder($websoccer,$db,$roundId,$groupName){getTeamsOfCupGroupInRankingOrder($websoccer,$db,$roundId,$groupName);}}
	 		function getTeamsOfCupGroupInRankingOrder($websoccer,$db,$roundId,$groupName){$result=$db->querySelect(['T.id'=>'id','T.name'=>'name','T.user_id'=>'user_id','U.nick'=>'user_name','G.tab_points'=>'score','G.tab_goals'=>'goals',
	 						'G.tab_goalsreceived'=>'goals_received','G.tab_wins'=>'wins','G.tab_draws'=>'draws','G.tab_losses'=>'defeats'],Config('db_prefix').'_cup_round_group AS G INNER JOIN '.Config('db_prefix').'_verein AS T ON T.id=G.team_id LEFT JOIN '.
	 						Config('db_prefix').'_user AS U ON U.id=T.user_id',"G.cup_round_id=%d AND G.name='%s'ORDER BY G.tab_points DESC,(G.tab_goals-G.tab_goalsreceived)DESC,G.tab_wins DESC,T.st_punkte DESC",[$roundId,$groupName]);$teams=[];
							while($team=$result->fetch_array())$teams[]=$team;return$teams;}

function generatePlayers($websoccer,$db,$teamId,$age,$ageDeviation,$salary,$contractDuration,$strengths,$positions,$maxDeviation,$nationality=NULL){
							if(strlen($nationality))$country=$nationality; // Check if nationality is provided, otherwise fetch it from the database
    						else{
        						$result=$db->querySelect('L.land AS country',Config('db_prefix').'_verein AS T INNER JOIN '.Config('db_prefix').'_liga AS L ON L.id=T.liga_id','T.id=%d',$teamId);
        						$league=$result->fetch_array();
        						if(!$league)throw new Exception('illegal team ID');
        						$country=$league['country'];}
								$firstNames=getLines($_SERVER['DOCUMENT_ROOT'].'/admin/config/names/%s/firstnames.txt',$country);$lastNames=getLines($_SERVER['DOCUMENT_ROOT'].'/admin/config/names/%s/lastnames.txt',$country); // Get first names and last names based on country
    							$mainPositions=['T'=>'Torwart','LV'=>'Abwehr','IV'=>'Abwehr','RV'=>'Abwehr','LM'=>'Mittelfeld','ZM'=>'Mittelfeld','OM'=>'Mittelfeld','DM'=>'Mittelfeld','RM'=>'Mittelfeld','LS'=>'Sturm','MS'=>'Sturm','RS'=>'Sturm']; // Define main positions
								foreach($positions as$mainPosition=>$numberOfPlayers){ // Generate players for each main position
								for($playerNo=1;$playerNo<=$numberOfPlayers;++$playerNo){
            						$birthday=date('Y-m-d',strtotime('-'.$age+getRandomDeviationValue($ageDeviation.' years',Timestamp()))); // Generate player age with deviation
            						$firstName=getItemFromArray($firstNames);$lastName=getItemFromArray($lastNames); // Get random first name and last name
            						createPlayer($websoccer,$db,$teamId,$firstName,$lastName,$mainPositions[$mainPosition],$mainPosition,$strengths,$country,$playerAge,$birthday,$salary,$contractDuration,$maxDeviation);}}} // Create player
function getLines($fileName,$country){$filePath=sprintf($fileName,$country);if(!file_exists($filePath))throwException('generator_err_filedoesnotexist',$filePath);$items=file($filePath,FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);if(!count($items))
							throwException('generator_err_emptyfile',$filePath);return$items;}
function getRandomDeviationValue($maxDeviation){if($maxDeviation<=0)return 0;return mt_rand(0-$maxDeviation,$maxDeviation);}
function getItemFromArray($items){$itemsCount=count($items);if($itemsCount)return$items[mt_rand(0,$itemsCount-1)];return FALSE;}
function createPlayer($websoccer,$db,$teamId,$firstName,$lastName,$position,$mainPosition,$strengths,$country,$age,$birthday,$salary,$contractDuration,$maxDeviation){$columns=['vorname'=>$firstName,'nachname'=>$lastName,'geburtstag'=>$birthday,'age'=>$age,
	 						'position'=>$position,'position_main'=>$mainPosition,'nation'=>$country,'w_staerke'=>max(1,min(100,$strengths['strength']+getRandomDeviationValue($maxDeviation))),'w_technik'=>max(1,min(100,$strengths['technique']+
	 						getRandomDeviationValue($maxDeviation))),'w_kondition'=>max(1,min(100,$strengths['stamina']+getRandomDeviationValue($maxDeviation))),'w_frische'=>max(1,min(100,$strengths['freshness']+getRandomDeviationValue($maxDeviation))),
							'w_zufriedenheit'=>max(1,min(100,$strengths['satisfaction']+getRandomDeviationValue($maxDeviation))),'vertrag_gehalt'=>$salary,'vertrag_spiele'=>$contractDuration,'status'=>'1'];if($teamId)$columns=['verein_id'=>$teamId];
							else$columns=['transfermarkt'=>'1','transfer_start'=>Timestamp(),'transfer_ende'=>$columns['transfer_start']+Config('transfermarket_duration_days')*24*3600];$db->queryInsert($columns,Config('db_prefix').'_spieler');}
function generateTeams($websoccer,$db,$numberOfTeams,$leagueId,$budget,$generateStadium,$stadiumNamePattern,$stadiumStands,$stadiumSeats,$stadiumStandsGrand,$stadiumSeatsGrand,$stadiumVip){$result=$db->querySelect('*',Config('db_prefix').'_liga','id=%d',
	 						$leagueId);$league=$result->fetch_array();if(!$league)throw new Exception('illegal league ID');$country=$league['land'];$cities=getLines($_SERVER['DOCUMENT_ROOT'].'/admin/config/names/%s/cities.txt',$country);
							$prefixes=getLines($_SERVER['DOCUMENT_ROOT'].'/admin/config/names/%s/clubprefix.txt',$country);$suffixes=[];try{$suffixes=getLines($_SERVER['DOCUMENT_ROOT'].'/admin/config/names/%s/clubsuffix.txt',$country);}catch(Exception$e){}
							for($teamNo=1;$teamNo<=$numberOfTeams;++$teamNo){$cityName=getItemFromArray($cities);createTeam($websoccer,$db,$league,$country,$cityName,$prefixes,$suffixes,$budget,$generateStadium,$stadiumNamePattern,$stadiumStands,$stadiumSeats,
							$stadiumStandsGrand,$stadiumSeatsGrand,$stadiumVip);}}
function throwException($messageKey,$parameter=null){$websoccer=WebSoccer::getInstance();$i18n=I18n::getInstance(Config('supported_languages'));throw new Exception(Message($messageKey,$parameter));}
function createTeam($websoccer,$db,$league,$country,$cityName,$prefixes,$suffixes,$budget,$generateStadium,$stadiumNamePattern,$stadiumStands,$stadiumSeats,$stadiumStandsGrand,$stadiumSeatsGrand,$stadiumVip){$teamName=$cityName;$shortName=
							strtoupper(substr($cityName,0,3));if(rand(0,1)&&count($suffixes))$teamName.=' '.getItemFromArray($suffixes);else$teamName=getItemFromArray($prefixes).' '.$teamName;$stadiumId=0;if($generateStadium){$stadiumName=
							sprintf($stadiumNamePattern,$cityName);$db->queryInsert(['name'=>$stadiumName,'stadt'=>$cityName,'land'=>$country,'p_steh'=>$stadiumStands,'p_sitz'=>$stadiumSeats,'p_haupt_steh'=>$stadiumStandsGrand,'p_haupt_sitz'=>$stadiumSeatsGrand,
							'p_vip'=>$stadiumVip],Config('db_prefix').'_stadion');$stadiumId=$db->getLastInsertedId();}$db->queryInsert(['name'=>$teamName,'kurz'=>$shortName,'liga_id'=>$league['id'],'stadion_id'=>$stadiumId,'finanz_budget'=>$budget,
							'preis_stehen'=>$league['preis_steh'],'preis_sitz'=>$league['preis_sitz'],'preis_haupt_stehen'=>$league['preis_steh'],'preis_haupt_sitze'=>$league['preis_sitz'],'preis_vip'=>$league['preis_vip'],'status'=>'1'],Config('db_prefix').'_verein');
							echo'<p>'.$teamName.' ('.$shortName.')</p>';}

class DirectTransfersDataService{
	 static function createTransferOffer($websoccer,$db,$playerId,$senderUserId,$senderClubId,$receiverUserId,$receiverClubId,$offerAmount,$offerMessage,$offerPlayerId1=null,$offerPlayerId2=null){createTransferOffer($websoccer,$db,$playerId,$senderUserId,$senderClubId,
	 						$receiverUserId,$receiverClubId,$offerAmount,$offerMessage,$offerPlayerId1=null,$offerPlayerId2=null);}
	 static function executeTransferFromOffer($websoccer,$db,$offerId){executeTransferFromOffer($websoccer,$db,$offerId);}
	 static function transferPlayer($websoccer,$db,$playerId,$targetClubId,$targetUserId,$currentUserId,$currentClubId,$amount,$exchangePlayer1=0,$exchangePlayer2=0){transferPlayer($websoccer,$db,$playerId,$targetClubId,$targetUserId,$currentUserId,$currentClubId,$amount,
	 						$exchangePlayer1=0,$exchangePlayer2=0);}
	 static function countReceivedOffers($websoccer,$db,$clubId){countReceivedOffers($websoccer,$db,$clubId);}
	 static function getReceivedOffers($websoccer,$db,$startIndex,$entries_per_page,$clubId){getReceivedOffers($websoccer,$db,$startIndex,$entries_per_page,$clubId);}
	 static function countSentOffers($websoccer,$db,$clubId,$userId){countSentOffers($websoccer,$db,$clubId,$userId);}
	 static function getSentOffers($websoccer,$db,$startIndex,$entries_per_page,$clubId,$userId){getSentOffers($websoccer,$db,$startIndex,$entries_per_page,$clubId,$userId);}
	 static function queryOffers($websoccer,$db,$startIndex,$entries_per_page,$whereCondition,$parameters){queryOffers($websoccer,$db,$startIndex,$entries_per_page,$whereCondition,$parameters);}}
			function createTransferOffer($websoccer,$db,$playerId,$senderUserId,$senderClubId,$receiverUserId,$receiverClubId,$offerAmount,$offerMessage,$offerPlayerId1=null,$offerPlayerId2=null){$db->queryInsert(['player_id'=>$playerId,'sender_user_id'=>$senderUserId,
							'sender_club_id'=>$senderClubId,'receiver_club_id'=>$receiverClubId,'submitted_date'=>Timestamp(),'offer_amount'=>$offerAmount,'offer_message'=>$offerMessage,'offer_player1'=>$offerPlayerId1,'offer_player2'=>$offerPlayerId2],
							Config('db_prefix').'_transfer_offer');$sender=UsersDataService::getUserById($websoccer,$db,$senderUserId);NotificationsDataService::createNotification($websoccer,$db,$receiverUserId,'transferoffer_notification_offerreceived',
							['sendername'=>$sender['nick']],Config('NOTIFICATION_TYPE'),Config('NOTIFICATION_TARGETPAGE'),null,$receiverClubId);}
			function executeTransferFromOffer($websoccer,$db,$offerId){$result=$db->querySelect('*',Config('db_prefix').'_transfer_offer','id=%d',$offerId);$offer=$result->fetch_array();if(!$offer)return;$currentTeam=TeamsDataService::getTeamSummaryById($websoccer,$db,
							$offer['receiver_club_id']);$targetTeam=TeamsDataService::getTeamSummaryById($websoccer,$db,$offer['sender_club_id']);transferPlayer($websoccer,$db,$offer['player_id'],$offer['sender_club_id'],$offer['sender_user_id'],$currentTeam['user_id'],
							$offer['receiver_club_id'],$offer['offer_amount'],$offer['offer_player1'],$offer['offer_player2']);creditAmount($websoccer,$db,$offer['receiver_club_id'],$offer['offer_amount'],'directtransfer_subject',$targetTeam['team_name']);
							debitAmount($websoccer,$db,$offer['sender_club_id'],$offer['offer_amount'],'directtransfer_subject',$currentTeam['team_name']);if($offer['offer_player1'])transferPlayer($websoccer,$db,$offer['offer_player1'],$offer['receiver_club_id'],
							$currentTeam['user_id'],$targetTeam['user_id'],$offer['sender_club_id'],0,$offer['player_id']);if($offer['offer_player2'])transferPlayer($websoccer,$db,$offer['offer_player2'],$offer['receiver_club_id'],$currentTeam['user_id'],
							$targetTeam['user_id'],$offer['sender_club_id'],0,$offer['player_id']);$db->queryDelete(Config('db_prefix').'_transfer_offer','player_id=%d',$offer['player_id']);$player=PlayersDataService::getPlayerById($websoccer,$db,$offer['player_id']);
							if($player['player_pseudonym'])$playerName=$player['player_pseudonym'];else$playerName=$player['player_firstname'].' '.$player['player_lastname'];NotificationsDataService::createNotification($websoccer,$db,$currentTeam['user_id'],
							'transferoffer_notification_executed',['playername'=>$playerName],config('NOTIFICATION_TYPE'),'player','id='.$offer['player_id'],$currentTeam['team_id']);NotificationsDataService::createNotification($websoccer,$db,$offer['sender_user_id'],
							'transferoffer_notification_executed',['playername'=>$playerName],config('NOTIFICATION_TYPE'),'player','id='.$offer['player_id'],$targetTeam['team_id']);TransfermarketDataService::awardUserForTrades($websoccer,$db,$currentTeam['user_id']);
							TransfermarketDataService::awardUserForTrades($websoccer,$db,$offer['sender_user_id']);}
			function transferPlayer($websoccer,$db,$playerId,$targetClubId,$targetUserId,$currentUserId,$currentClubId,$amount,$exchangePlayer1=0,$exchangePlayer2=0){$db->queryUpdate(['verein_id'=>$targetClubId,'vertrag_spiele'=>Config('transferoffers_contract_matches')],
							Config('db_prefix').'_spieler','id=%d',$playerId);$db->queryInsert(['bid_id'=>0,'datum'=>Timestamp(),'spieler_id'=>$playerId,'seller_user_id'=>$currentUserId,'seller_club_id'=>$currentClubId,'buyer_user_id'=>$targetUserId,
							'buyer_club_id'=>$targetClubId,'directtransfer_amount'=>$amount,'directtransfer_player1'=>$exchangePlayer1,'directtransfer_player2'=>$exchangePlayer2],Config('db_prefix').'_transfer');}
			function countReceivedOffers($websoccer,$db,$clubId){$result=$db->querySelect('COUNT(*)AS hits',Config('db_prefix').'_transfer_offer',"receiver_club_id=%d AND(rejected_date=0 OR admin_approval_pending='1')",$clubId);$players=$result->fetch_array();
							if(isset($players['hits']))return$players['hits'];return 0;}
			function getReceivedOffers($websoccer,$db,$startIndex,$entries_per_page,$clubId){return queryOffers($websoccer,$db,$startIndex,$entries_per_page,"O.receiver_club_id=%d AND(O.rejected_date=0 OR O.admin_approval_pending='1')",[$clubId]);}
			function countSentOffers($websoccer,$db,$clubId,$userId){$result=$db->querySelect('COUNT(*)AS hits',Config('db_prefix').'_transfer_offer','sender_club_id=%d AND sender_user_id=%d',[$clubId,$userId]);$players=$result->fetch_array();
							if(isset($players['hits']))return$players['hits'];return 0;}
			function getSentOffers($websoccer,$db,$startIndex,$entries_per_page,$clubId,$userId){return queryOffers($websoccer,$db,$startIndex,$entries_per_page,'O.sender_club_id=%d AND O.sender_user_id=%d',[$clubId,$userId]);}
			function queryOffers($websoccer,$db,$startIndex,$entries_per_page,$whereCondition,$parameters){$columns=['O.id'=>'offer_id','O.submitted_date'=>'offer_submitted_date','O.offer_amount'=>'offer_amount','O.offer_message'=>'offer_message','O.rejected_date'=>
							'offer_rejected_date','O.rejected_message'=>'offer_rejected_message','O.rejected_allow_alternative'=>'offer_rejected_allow_alternative','O.admin_approval_pending'=>'offer_admin_approval_pending','P.id'=>'player_id','P.vorname'=>
							'player_firstname','P.nachname'=>'player_lastname','P.kunstname'=>'player_pseudonym','P.vertrag_gehalt'=>'player_salary','P.marktwert'=>'player_marketvalue','P.w_staerke'=>'player_strength','P.w_technik'=>'player_strength_technique',
							'P.w_kondition'=>'player_strength_stamina','P.w_frische'=>'player_strength_freshness','P.w_zufriedenheit'=>'player_strength_satisfaction','P.position_main'=>'player_position_main','SU.id'=>'sender_user_id','SU.nick'=>'sender_user_name',
							'SC.id'=>'sender_club_id','SC.name'=>'sender_club_name','RU.id'=>'receiver_user_id','RU.nick'=>'receiver_user_name','RC.id'=>'receiver_club_id','RC.name'=>'receiver_club_name','EP1.id'=>'explayer1_id','EP1.vorname'=>'explayer1_firstname',
							'EP1.nachname'=>'explayer1_lastname','EP1.kunstname'=>'explayer1_pseudonym','EP2.id'=>'explayer2_id','EP2.vorname'=>'explayer2_firstname','EP2.nachname'=>'explayer2_lastname','EP2.kunstname'=>'explayer2_pseudonym'];$whereCondition.=
							' ORDER BY O.submitted_date DESC';$offers=[];$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters,$startIndex.",".$entries_per_page);while($offer=$result->fetch_array()){$offer['player_marketvalue']=
							PlayersDataService::getMarketValue($websoccer,$offer);$offers[]=$offer;}return$offers;}

class FormationDataService{
	static function getFormationByTeamId($websoccer,$db,$teamId,$matchId){
		$whereCondition='verein_id=%d AND match_id=%d';
		$parameters=array($teamId,$matchId);
		return self::_getFormationByCondition($websoccer,$db,$whereCondition,$parameters);}
	static function getFormationByTemplateId($websoccer,$db,$teamId,$templateId){
		$whereCondition='id=%d AND verein_id=%d';
		$parameters=array($templateId,$teamId);
		return self::_getFormationByCondition($websoccer,$db,$whereCondition,$parameters);}
	static function _getFormationByCondition($websoccer,$db,$whereCondition,$parameters){
		$fromTable=Config('db_prefix').'_aufstellung';
		$columns['id']='id';
		$columns['offensive']='offensive';
		$columns['setup']='setup';
		$columns['longpasses']='longpasses';
		$columns['counterattacks']='counterattacks';
		$columns['freekickplayer']='freekickplayer';
		for($playerNo=1; $playerNo <= 11;++$playerNo){
			$columns['spieler'.$playerNo]='player'.$playerNo;
			$columns['spieler'.$playerNo.'_position']='player'.$playerNo.'_pos';}
		for($playerNo=1; $playerNo <= 5;++$playerNo)$columns['ersatz'.$playerNo]='bench'.$playerNo;
		for($subNo=1; $subNo <= 3;++$subNo){
			$columns['w'.$subNo.'_raus']='sub'.$subNo.'_out';
			$columns['w'.$subNo.'_rein']='sub'.$subNo.'_in';
			$columns['w'.$subNo.'_minute']='sub'.$subNo.'_minute';
			$columns['w'.$subNo.'_condition']='sub'.$subNo.'_condition';
			$columns['w'.$subNo.'_position']='sub'.$subNo.'_position';}
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters, 1);
		$formation=$result->fetch_array();
		if(!$formation)$formation=[];
		return$formation;}
	static function getFormationProposalForTeamId($websoccer,$db,$teamId,$setupDefense,$setupDM,$setupMidfield,$setupOM,$setupStriker,$setupOutsideforward,$sortColumn,$sortDirection='DESC',
		$isNationalteam=FALSE,$isCupMatch=FALSE){
		$columns='id,position,position_main,position_second';
		if(!$isNationalteam){
			$fromTable=Config('db_prefix').'_spieler';
			$whereCondition='verein_id=%d AND gesperrt';
			if($isCupMatch)$whereCondition.='_cups';
			$whereCondition.='=0 AND verletzt=0 AND status=1';}
		else{
			$fromTable=Config('db_prefix').'_spieler AS P INNER JOIN '.Config('db_prefix').'_nationalplayer AS NP ON NP.player_id=P.id';
			$whereCondition='NP.team_id=%d AND gesperrt_nationalteam=0 AND verletzt=0 AND status=1';}
		$whereCondition .=	' ORDER BY '.$sortColumn.' '.$sortDirection;
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$teamId);
		$openPositions['T']=1;
		if($setupDefense<4){
			$openPositions['IV']=$setupDefense;
			$openPositions['LV']=0;
			$openPositions['RV']=0;}
		else{
			$openPositions['LV']=1;
			$openPositions['RV']=1;
			$openPositions['IV']=$setupDefense - 2;}
		$openPositions['DM']=$setupDM;
		$openPositions['OM']=$setupOM;
		if($setupMidfield==1){
			$openPositions['ZM']=1;}
		elseif($setupMidfield==2){
			$openPositions['LM']=1;
			$openPositions['RM']=1;}
		elseif($setupMidfield==3){
			$openPositions['LM']=1;
			$openPositions['ZM']=1;
			$openPositions['RM']=1;}
		elseif($setupMidfield >= 4){
			$openPositions['LM']=1;
			$openPositions['ZM']=$setupMidfield - 2;
			$openPositions['RM']=1;}
		else{
			$openPositions['LM']=0;
			$openPositions['ZM']=0;
			$openPositions['RM']=0;}
		$openPositions['MS']=$setupStriker;
		if($setupOutsideforward==2){
			$openPositions['LS']=1;
			$openPositions['RS']=1;}
		else{
			$openPositions['LS']=0;
			$openPositions['RS']=0;}
		$players=[];
		$unusedPlayers=[];
		while($player=$result->fetch_array()){
			$added=FALSE;
			if(!strlen($player['position_main'])){
				if($player['position']=='Torwart')$possiblePositions=array('T');
				elseif($player['position']=='Abwehr')$possiblePositions=array('LV', 'IV', 'RV');
				elseif($player['position']=='Mittelfeld')$possiblePositions=array('RM', 'ZM', 'LM', 'RM', 'DM', 'OM');
				else $possiblePositions=array('LS', 'MS', 'RS');
				foreach($possiblePositions as $possiblePosition){
					if($openPositions[$possiblePosition]){
						$openPositions[$possiblePosition]=$openPositions[$possiblePosition] - 1;
						$players[]=array('id'=>$player['id'],'position'=>$possiblePosition);
						$added=TRUE;
						break;}}}
			elseif(strlen($player['position_main'])&&isset($openPositions[$player['position_main']])&&$openPositions[$player['position_main']]){
				$openPositions[$player['position_main']]=$openPositions[$player['position_main']] - 1;
				$players[]=array('id'=>$player['id'],'position'=>$player['position_main']);
				$added=TRUE;}
			if(!$added &&strlen($player['position_second']))$unusedPlayers[]=$player;}
		foreach($openPositions as $position=>$requiredPlayers){
			for($i=0; $i<$requiredPlayers;++$i){
				for($playerIndex=0; $playerIndex<count($unusedPlayers);++$playerIndex){
					if($unusedPlayer[$playerIndex]['position_second']==$position){
						$players[]=array('id'=>$unusedPlayer[$playerIndex]['id'],'position'=>$unusedPlayer[$playerIndex]['position_second']);
						unset($unusedPlayer[$playerIndex]);
						break;}}}}
		return$players;}}

class LeagueDataService {
	static function getLeagueById($websoccer,$db,$leagueId){
		$fromTable=Config("db_prefix")."_liga AS L";
		$whereCondition="L.id=%d";
		$parameters=$leagueId;
		$columns["L.id"]="league_id";
		$columns["L.name"]="league_name";
		$columns["L.kurz"]="league_short";
		$columns["L.land"]="league_country";
		$leagueinfos=$db->queryCachedSelect($columns,$fromTable,$whereCondition,$parameters, 1);
		$league=(isset($leagueinfos[0]))? $leagueinfos[0] : array();
		return$league;}
	static function getLeaguesSortedByCountry($websoccer,$db){
		$fromTable=Config("db_prefix")."_liga AS L";
		$whereCondition="1 ORDER BY league_country ASC, league_name ASC";
		$columns["L.id"]="league_id";
		$columns["L.name"]="league_name";
		$columns["L.kurz"]="league_short";
		$columns["L.land"]="league_country";
		return$db->queryCachedSelect($columns,$fromTable,$whereCondition);}
	static function countTotalLeagues($websoccer,$db){
		$result=$db->querySelect("COUNT(*)AS hits",Config("db_prefix")."_liga","1=1");
		$leagues=$result->fetch_array();
		if(isset($leagues["hits"]))return$leagues["hits"];
		return 0;}}

class MessagesDataService {
	static function getInboxMessages($websoccer,$db,$startIndex,$entries_per_page){
		$whereCondition="L.empfaenger_id=%d AND L.typ='eingang' ORDER BY L.datum DESC";
		$parameters=$websoccer->getUser()->id;
		return self::getMessagesByCondition($websoccer,$db,$startIndex,$entries_per_page,$whereCondition,$parameters);}
	static function getOutboxMessages($websoccer,$db,$startIndex,$entries_per_page){
		$whereCondition="L.absender_id=%d AND L.typ='ausgang' ORDER BY L.datum DESC";
		$parameters=$websoccer->getUser()->id;
		return self::getMessagesByCondition($websoccer,$db,$startIndex,$entries_per_page,$whereCondition,$parameters);}
	static function getMessageById($websoccer,$db,$id){
		$whereCondition="(L.empfaenger_id=%d OR L.absender_id=%d)AND L.id=%d";
		$userId=$websoccer->getUser()->id;
		$parameters=array($userId,$userId,$id);
		$messages=self::getMessagesByCondition($websoccer,$db, 0, 1,$whereCondition,$parameters);
		if(count($messages))return$messages[0];
		return null;}
	static function getLastMessageOfUserId($websoccer,$db,$userId){
		$whereCondition="L.absender_id=%d ORDER BY L.datum DESC";
		$userId=$websoccer->getUser()->id;
		$messages=self::getMessagesByCondition($websoccer,$db, 0, 1,$whereCondition,$userId);
		if(count($messages))return$messages[0];
		return null;}
	static function getMessagesByCondition($websoccer,$db,$startIndex,$entries_per_page,$whereCondition,$parameters){
		$columns["L.id"]="message_id";
		$columns["L.betreff"]="subject";
		$columns["L.nachricht"]="content";
		$columns["L.datum"]="date";
		$columns["L.gelesen"]="seen";
		$columns["R.id"]="recipient_id";
		$columns["R.nick"]="recipient_name";
		$columns["S.id"]="sender_id";
		$columns["S.nick"]="sender_name";
		$fromTable=Config("db_prefix")."_briefe AS L INNER JOIN ".Config("db_prefix")."_user AS R ON R.id=L.empfaenger_id LEFT JOIN ".Config("db_prefix")."_user AS S ON S.id=L.absender_id";
		$limit=$startIndex .",".$entries_per_page;
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters,$limit);
		$messages=[];
		while($message=$result->fetch_array())$messages[]=$message;
		return$messages;}
	static function countInboxMessages($websoccer,$db){
		$userId=$websoccer->getUser()->id;
		$columns="COUNT(*)AS hits";
		$fromTable=Config("db_prefix")."_briefe AS L";
		$whereCondition="L.empfaenger_id=%d AND typ='eingang'";
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$userId);
		$letters=$result->fetch_array();
		if(isset($letters["hits"]))return$letters["hits"];
		return 0;}
	static function countUnseenInboxMessages($websoccer,$db){
		$userId=$websoccer->getUser()->id;
		$columns="COUNT(*)AS hits";
		$fromTable=Config("db_prefix")."_briefe AS L";
		$whereCondition="L.empfaenger_id=%d AND typ='eingang' AND gelesen='0'";
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$userId);
		$letters=$result->fetch_array();
		if(isset($letters["hits"]))return$letters["hits"];
		return 0;}
	static function countOutboxMessages($websoccer,$db){
		$userId=$websoccer->getUser()->id;
		$columns="COUNT(*)AS hits";
		$fromTable=Config("db_prefix")."_briefe AS L";
		$whereCondition="L.absender_id=%d AND typ='ausgang'";
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$userId);
		$letters=$result->fetch_array();
		if(isset($letters["hits"]))return$letters["hits"];
		return 0;}}

class NationalteamsDataService {
	static function getNationalTeamManagedByCurrentUser($websoccer,$db){
		$result=$db->queryCachedSelect("id",Config("db_prefix")."_verein","user_id=%d AND nationalteam='1'",$websoccer->getUser()->id, 1);
		if(count($result))return$result[0]["id"];
		return NULL;}
	static function getNationalPlayersOfTeamByPosition($websoccer,$db,$clubId,$positionSort="ASC"){
		$columns=array("P.id"=>"id","vorname"=>"firstname","nachname"=>"lastname","kunstname"=>"pseudonym","verletzt"=>"matches_injured","gesperrt_nationalteam"=>"matches_blocked","position"=>"position","position_main"=>"position_main",
			"position_second"=>"position_second","w_staerke"=>"strength","w_technik"=>"strength_technique","w_kondition"=>"strength_stamina","w_frische"=>"strength_freshness","w_zufriedenheit"=>"strength_satisfaction",
			"transfermarkt"=>"transfermarket","nation"=>"player_nationality","picture"=>"picture","P.sa_tore"=>"st_goals","P.sa_spiele"=>"st_matches","P.sa_karten_gelb"=>"st_cards_yellow","P.sa_karten_gelb_rot"=>"st_cards_yellow_red",
			"P.sa_karten_rot"=>"st_cards_red","marktwert"=>"marketvalue","verein_id"=>"team_id","C.name"=>"team_name");
		if(Config('players_aging')=='birthday')$ageColumn='TIMESTAMPDIFF(YEAR,geburtstag,CURDATE())';
		else $ageColumn='age';
		$columns[$ageColumn]='age';
		$fromTable=Config("db_prefix")."_spieler AS P INNER JOIN ".Config("db_prefix")."_nationalplayer AS NP ON NP.player_id=P.id LEFT JOIN ".Config("db_prefix")."_verein AS C ON C.id=P.verein_id";
		$whereCondition="P.status=1 AND NP.team_id=%d ORDER BY position ".$positionSort.",position_main ASC, nachname ASC, vorname ASC";
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$clubId, 50);
		$players=[];
		while($player=$result->fetch_array()){
			$player["position"]=PlayersDataService::_convertPosition($player["position"]);
			$player["player_nationality_filename"]=PlayersDataService::getFlagFilename($player["player_nationality"]);
			$player["marketvalue"]=PlayersDataService::getMarketValue($websoccer,$player,"");
			$players[$player["position"]][]=$player;}
		return$players;}
	static function findPlayersCount($websoccer,$db,$nationality,$teamId,$firstName,$lastName,$position,$mainPosition){
		$columns="COUNT(*)AS hits";
		$result=self::executeFindQuery($websoccer,$db,$columns, 1,$nationality,$teamId,$firstName,$lastName,$position,$mainPosition);
		$players=$result->fetch_array();
		if(isset($players["hits"]))return$players["hits"];
		return 0;}
	static function findPlayers($websoccer,$db,$nationality,$teamId,$firstName,$lastName,$position,$mainPosition,$startIndex,$entries_per_page){
		$columns["P.id"]="id";
		$columns["P.vorname"]="firstname";
		$columns["P.nachname"]="lastname";
		$columns["P.kunstname"]="pseudonym";
		$columns["P.position"]="position";
		$columns["P.position_main"]="position_main";
		$columns["P.position_second"]="position_second";
		$columns["P.w_staerke"]="strength";
		$columns["P.w_technik"]="strength_technique";
		$columns["P.w_kondition"]="strength_stamina";
		$columns["P.w_frische"]="strength_freshness";
		$columns["P.w_zufriedenheit"]="strength_satisfaction";
		$columns["C.id"]="team_id";
		$columns["C.name"]="team_name";
		$limit=$startIndex .",".$entries_per_page;
		$result=self::executeFindQuery($websoccer,$db,$columns,$limit,$nationality,$teamId,$firstName,$lastName,$position,$mainPosition);
		$players=[];
		while($player=$result->fetch_array()){
			$player["position"]=PlayersDataService::_convertPosition($player["position"]);
			$players[]=$player;}
		return$players;}
	static function executeFindQuery($websoccer,$db,$columns,$limit,$nationality,$teamId,$firstName,$lastName,$position,$mainPosition){
		$whereCondition="P.status=1 AND P.nation='%s' AND P.verletzt=0 AND P.id NOT IN (SELECT player_id FROM ".Config("db_prefix")."_nationalplayer WHERE team_id=%d)";
		$parameters=[];
		$parameters[]=$nationality;
		$parameters[]=$teamId;
		if($firstName!=null){
			$firstName=ucfirst($firstName);
			$whereCondition.=" AND P.vorname LIKE '%s%%'";
			$parameters[]=$firstName;}
		if($lastName!=null){
			$lastName=ucfirst($lastName);
			$whereCondition.=" AND(P.nachname LIKE '%s%%' OR P.kunstname LIKE '%s%%')";
			$parameters[]=$lastName;
			$parameters[]=$lastName;}
		if($position!=null){
			$whereCondition.=" AND P.position='%s'";
			$parameters[]=$position;}
		if($mainPosition!=null){
			$whereCondition.=" AND(P.position_main='%s' OR P.position_second='%s')";
			$parameters[]=$mainPosition;
			$parameters[]=$mainPosition;}
		$whereCondition.=" ORDER BY w_staerke DESC, w_technik DESC";
		$fromTable=Config("db_prefix")."_spieler AS P LEFT JOIN ".Config("db_prefix")."_verein AS C ON C.id=P.verein_id";
		return$db->querySelect($columns,$fromTable,$whereCondition,$parameters,$limit);}
	static function countNextMatches($websoccer,$db,$teamId){
		$columns="COUNT(*)AS hits";
		$fromTable=Config("db_prefix")."_spiel";
		$result=$db->querySelect($columns,$fromTable,"(home_verein=%d OR gast_verein=%d)AND datum>%d",array($teamId,$teamId,Timestamp()));
		$matches=$result->fetch_array();
		if(isset($matches["hits"]))return$matches["hits"];
		return 0;}
	static function getNextMatches($websoccer,$db,$teamId,$startIndex,$eps){
		$whereCondition="(home_verein=%d OR gast_verein=%d)AND datum>%d ORDER BY datum ASC";
		return MatchesDataService::getMatchesByCondition($websoccer,$db,$whereCondition,array($teamId,$teamId,Timestamp()),$startIndex.",".$eps);}
	static function countSimulatedMatches($websoccer,$db,$teamId){
		$columns="COUNT(*)AS hits";
		$fromTable=Config("db_prefix")."_spiel";
		$result=$db->querySelect($columns,$fromTable,"(home_verein=%d OR gast_verein=%d)AND berechnet='1'",array($teamId,$teamId));
		$matches=$result->fetch_array();
		if(isset($matches["hits"]))return$matches["hits"];
		return 0;}
	static function getSimulatedMatches($websoccer,$db,$teamId,$startIndex,$eps){
		$whereCondition="(home_verein=%d OR gast_verein=%d)AND berechnet='1' ORDER BY datum DESC";
		return MatchesDataService::getMatchesByCondition($websoccer,$db,$whereCondition,array($teamId,$teamId),$startIndex.",".$eps);}}

class NotificationsDataService {
	static function createNotification($websoccer,$db,$userId,$messageKey,$messageData=null,$type=null,$targetPageId=null,$targetPageQueryString=null,$teamId=null){
		$columns=array('user_id'=>$userId, 'eventdate'=>Timestamp(), 'message_key'=>$messageKey);
		if($messageData!=null)$columns['message_data']=json_encode($messageData);
		if($type!=null)$columns['eventtype']=$type;
		if($targetPageId!=null)$columns['target_pageid']=$targetPageId;
		if($targetPageQueryString!=null)$columns['target_querystr']=$targetPageQueryString;
		if($teamId!=null)$columns['team_id']=$teamId;
		$db->queryInsert($columns,Config('db_prefix').'_notification');}
	static function countUnseenNotifications($websoccer,$db,$userId,$teamId){
		$result=$db->querySelect('COUNT(*)AS hits',Config('db_prefix').'_notification', 'user_id=%d AND seen=\'0\' AND(team_id=%d OR team_id IS NULL)',array($userId,$teamId));
		$rows=$result->fetch_array();
		if($rows)return$rows['hits'];
		return 0;}
	static function getLatestNotifications($websoccer,$db,$i18n,$userId,$teamId,$limit){
		$result=$db->querySelect('*',Config('db_prefix').'_notification', 'user_id=%d AND(team_id=%d OR team_id IS NULL)ORDER BY eventdate DESC',array($userId,$teamId),$limit);
		$notifications=[];
		while($row=$result->fetch_array()){
			$notification=array('id'=>$row['id'],'eventdate'=>$row['eventdate'],'eventtype'=>$row['eventtype'],'seen'=>$row['seen']);
			if(Message($row['message_key']))$message=Message($row['message_key']);
			else $message=$row['message_key'];
			if(strlen($row['message_data'])){
				$messageData=json_decode($row['message_data'],true);
				if($messageData){
					foreach($messageData as $placeholderName=>$placeholderValue)$message=str_replace('{'.$placeholderName.'}', escapeOutput($placeholderValue, ENT_COMPAT, 'UTF-8'),$message);}}
			$notification['message']=$message;
			$link='';
			if($row['target_pageid']){
				if($row['target_querystr'])$link=iUrl($row['target_pageid'],$row['target_querystr']);
				else $link=$websoccer->iUrl($row['target_pageid']);}
			$notification['link']=$link;
			$notifications[]=$notification;}
		return$notifications;}}

class PremiumDataService {
	static function countAccountStatementsOfUser($websoccer,$db,$userId){
		$columns='COUNT(*)AS hits';
		$fromTable=Config('db_prefix').'_premiumstatement';
		$whereCondition='user_id=%d';
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$userId);
		$statements=$result->fetch_array();
		if(isset($statements['hits']))return$statements['hits'];
		return 0;}
	static function getAccountStatementsOfUser($websoccer,$db,$userId,$startIndex,$entries_per_page){
		$limit=$startIndex.','.$entries_per_page;
		$fromTable=Config('db_prefix').'_premiumstatement';
		$whereCondition='user_id=%d ORDER BY created_date DESC';
		$result=$db->querySelect('*',$fromTable,$whereCondition,$userId,$limit);
		$statements=[];
		while($statement=$result->fetch_array())$statements[]=$statement;
		return$statements;}
	static function creditAmount($websoccer,$db,$userId,$amount,$subject,$data=null){
		if($amount==0)return;
		$user=UsersDataService::getUserById($websoccer,$db,$userId);
		if(!isset($user['premium_balance']))throw new Exception('user not found: '.$userId);
		if($amount<0)throw new Exception('amount illegal: '.$amount);
		else self::createTransaction($websoccer,$db,$user,$userId,$amount,$subject,$data);}
	static function debitAmount($websoccer,$db,$userId,$amount,$subject,$data=null){
		if($amount==0)return;
		$user=UsersDataService::getUserById($websoccer,$db,$userId);
		if(!isset($user['premium_balance']))throw new Exception('user not found: '.$userId);
		if($amount<0)throw new Exception('amount illegal: '.$amount);
		if($user['premium_balance']<$amount){
			$i18n=I18n::getInstance(Config('supported_languages'));
			throw new Exception(Message('premium_balance_notenough'));}
		$amount=0 - $amount;
		self::createTransaction($websoccer,$db,$user,$userId,$amount,$subject,$data);}
	static function createTransaction($websoccer,$db,$user,$userId,$amount,$subject,$data){
		$fromTable=Config('db_prefix').'_premiumstatement';
		$columns=array('user_id'=>$userId, 'action_id'=>$subject, 'amount'=>$amount, 'created_date'=>Timestamp(), 'subject_data'=>json_encode($data));
		$db->queryInsert($columns,$fromTable);
		$newBudget=$user['premium_balance'] + $amount;
		$updateColumns=array('premium_balance'=>$newBudget);
		$fromTable=Config('db_prefix').'_user';
		$whereCondition='id=%d';
		$parameters=$userId;
		$db->queryUpdate($updateColumns,$fromTable,$whereCondition,$parameters);
		if($userId==$websoccer->getUser()->id)$websoccer->getUser()->premiumBalance=$newBudget;}
	static function createPaymentAndCreditPremium($websoccer,$db,$userId,$amount,$subject){
		if($amount <= 0)throw new Exception('Illegal amount: '.$amount);
		$realAmount=$amount*100;
		$db->queryInsert(array('user_id'=>$userId, 'amount'=>$realAmount, 'created_date'=>Timestamp()),Config('db_prefix').'_premiumpayment');
		$priceOptions=explode(',',Config('premium_price_options'));
		if(count($priceOptions)){
			foreach($priceOptions as$priceOption){
				$optionParts=explode(':',$priceOption);
				$realMoney=trim($optionParts[0]);
				$realMoneyAmount=$realMoney*100;
				$premiumMoney=trim($optionParts[1]);
				if($realAmount==$realMoneyAmount){
					self::creditAmount($websoccer,$db,$userId,$premiumMoney,$subject);
					return;}}}
		throw new Exception('No price option found for amount: '.$amount);}
	static function getPaymentsOfUser($websoccer,$db,$userId,$limit){
		$fromTable=Config('db_prefix').'_premiumpayment';
		$whereCondition='user_id=%d ORDER BY created_date DESC';
		$result=$db->querySelect('*',$fromTable,$whereCondition,$userId,$limit);
		$statements=[];
		while($statement=$result->fetch_array()){
			$statement['amount']=$statement['amount'] / 100;
			$statements[]=$statement;}
		return$statements;}}

class RandomEventsDataService {
	static function createEventIfRequired($websoccer,$db,$userId){
		$eventsInterval=(int)Config('randomevents_interval_days');
		if($eventsInterval<1)return;
		$result=$db->querySelect('id',Config('db_prefix').'_verein', 'user_id=%d AND status=\'1\'',$userId);
		$clubIds=[];
		while($club=$result->fetch_array())$clubIds[]=$club['id'];
		if(!count($clubIds))return;
		$clubId=$clubIds[array_rand($clubIds)];
		$now=Timestamp();
		$result=$db->querySelect('datum_anmeldung',Config('db_prefix').'_user', 'id=%d',$userId, 1);
		$user=$result->fetch_array();
		if($user['datum_anmeldung'] >=($now - 24*3600))return;
		$result=$db->querySelect('occurrence_date',Config('db_prefix').'_randomevent_occurrence', 'user_id=%d ORDER BY occurrence_date DESC',$userId, 1);
		$latestEvent=$result->fetch_array();
		if($latestEvent &&$latestEvent['occurrence_date'] >=($now - 24*3600*$eventsInterval))return;
		self::_createAndExecuteEvent($websoccer,$db,$userId,$clubId);
		if($latestEvent){
			$deleteBoundary=$now - 24*3600*10*$eventsInterval;
			$db->queryDelete(Config('db_prefix').'_randomevent_occurrence', 'user_id=%d AND occurrence_date<%d',array($userId,$deleteBoundary));} }
	static function _createAndExecuteEvent($websoccer,$db,$userId,$clubId){
		$result=$db->querySelect('*',Config('db_prefix').'_randomevent', 'weight>0 AND id NOT IN (SELECT event_id FROM '.Config('db_prefix').'_randomevent_occurrence WHERE user_id=%d)ORDER BY RAND()',$userId, 100);
		$events=[];
		while($event=$result->fetch_array()){
			for($i=1; $i <= $event['weight'];++$i)$events[]=$event;}
		if(!count($events))return;
		$randomEvent=$events[array_rand($events)];
		self::_executeEvent($websoccer,$db,$userId,$clubId,$randomEvent);
		$db->queryInsert(array('user_id'=>$userId, 'team_id'=>$clubId, 'event_id'=>$randomEvent['id'],'occurrence_date'=>Timestamp()),Config('db_prefix').'_randomevent_occurrence');}
	static function _executeEvent($websoccer,$db,$userId,$clubId,$event){
		$notificationType='randomevent';
		$subject=$event['message'];
		if($event['effect']=='money'){
			$amount=$event['effect_money_amount'];
			$sender=Config('projectname');
			if($amount)creditAmount($websoccer,$db,$clubId,$amount,$subject,$sender);
			else debitAmount($websoccer,$db,$clubId,$amount*(0-1),$subject,$sender);
			NotificationsDataService::createNotification($websoccer,$db,$userId,$subject, null,$notificationType, 'finances', null,$clubId);}
		else{
			$result=$db->querySelect('id, vorname, nachname, kunstname, w_frische, w_kondition, w_zufriedenheit',Config('db_prefix').'_spieler', 'verein_id=%d AND gesperrt=0 AND verletzt=0 AND status=\'1\' ORDER BY RAND()',$clubId, 1);
			$player=$result->fetch_array();
			if(!$player)return;
			switch($event['effect']){
				case 'player_injured': $columns=array('verletzt'=>$event['effect_blocked_matches']);
					break;
				case 'player_blocked': $columns=array('gesperrt'=>$event['effect_blocked_matches']);
					break;
				case 'player_happiness': $columns=array('w_zufriedenheit'=>max(1, min(100,$player['w_zufriedenheit'] + $event['effect_skillchange'])));
					break;
				case 'player_fitness': $columns=array('w_frische'=>max(1, min(100,$player['w_frische'] + $event['effect_skillchange'])));
					break;
				case 'player_stamina': $columns=array('w_kondition'=>max(1, min(100,$player['w_kondition'] + $event['effect_skillchange'])));
					break;}
			if(!isset($columns))return;
			$db->queryUpdate($columns,Config('db_prefix').'_spieler', 'id=%d',$player['id']);
			$playerName=(strlen($player['kunstname']))? $player['kunstname']:$player['vorname'].' '.$player['nachname'];
			NotificationsDataService::createNotification($websoccer,$db,$userId,$subject,array('playername'=>$playerName),$notificationType, 'player', 'id='.$player['id'],$clubId);}}}

class SponsorsDataService{
	 static function getSponsorinfoByTeamId($websoccer,$db,$clubId){$result=$db->querySelect(['T.sponsor_spiele'=>'matchdays','S.id'=>'sponsor_id','S.name'=>'name','S.b_spiel'=>'amount_match','S.b_heimzuschlag'=>'amount_home_bonus','S.b_sieg'=>'amount_win',
	 			     		'S.b_meisterschaft'=>'amount_championship','S.bild'=>'picture'],Config('db_prefix').'_sponsor AS S INNER JOIN '.Config('db_prefix').'_verein AS T ON T.sponsor_id=S.id','T.id=%d AND T.sponsor_spiele>0',$clubId,1);
							$sponsor=$result->fetch_array();return$sponsor;}
	 static function getSponsorOffers($websoccer,$db,$teamId){$team=TeamsDataService::getTeamSummaryById($websoccer,$db,$teamId);$teamRank=TeamsDataService::getTableRankOfTeam($websoccer,$db,$teamId);return$db->queryCachedSelect(['S.id'=>'sponsor_id','S.name'=>'name',
	 						'S.b_spiel'=>'amount_match','S.b_heimzuschlag'=>'amount_home_bonus','S.b_sieg'=>'amount_win','S.b_meisterschaft'=>'amount_championship'],Config('db_prefix').'_sponsor AS S','S.liga_id=%d AND(S.min_platz=0 OR S.min_platz>=%d)'.
	 						' AND(S.max_teams<=0 OR S.max_teams>(SELECT COUNT(*)FROM '.Config('db_prefix').'_verein AS T WHERE T.sponsor_id=S.id AND T.sponsor_spiele>0))'.'ORDER BY S.b_spiel DESC',[$team['team_league_id'],$teamRank],20);}}

class StadiumsDataService {
	static function getStadiumByTeamId($websoccer,$db,$clubId){
		if(!$clubId)return NULL;
		$columns['S.id']='stadium_id';
		$columns['S.name']='name';
		$columns['S.picture']='picture';
		$columns['S.p_steh']='places_stands';
		$columns['S.p_sitz']='places_seats';
		$columns['S.p_haupt_steh']='places_stands_grand';
		$columns['S.p_haupt_sitz']='places_seats_grand';
		$columns['S.p_vip']='places_vip';
		$columns['S.level_pitch']='level_pitch';
		$columns['S.level_videowall']='level_videowall';
		$columns['S.level_seatsquality']='level_seatsquality';
		$columns['S.level_vipquality']='level_vipquality';
		$fromTable=Config('db_prefix').'_stadion AS S INNER JOIN '.Config('db_prefix').'_verein AS T ON T.stadion_id=S.id';
		$whereCondition='T.id=%d';
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$clubId, 1);
		$stadium=$result->fetch_array();
		return$stadium;}
	static function getBuilderOffersForExtension($websoccer,$db,$clubId,$newSideStanding=0,$newSideSeats=0,$newGrandStanding=0,$newGrandSeats=0,$newVips=0){
		$offers=[];
		$totalNew=$newSideStanding + $newSideSeats + $newGrandStanding + $newGrandSeats + $newVips;
		if(!$totalNew)return$offers;
		$stadium=self::getStadiumByTeamId($websoccer,$db,$clubId);
		$existingCapacity=$stadium['places_stands'] + $stadium['places_seats'] + $stadium['places_stands_grand'] + $stadium['places_seats_grand'] + $stadium['places_vip'];
		$result=$db->querySelect('*',Config('db_prefix').'_stadium_builder','min_stadium_size <= %d AND(max_stadium_size=0 OR max_stadium_size >= %d)',array($existingCapacity,$existingCapacity));
		while($builder=$result->fetch_array()){
			$constructionTime=max($builder['construction_time_days_min'],$builder['construction_time_days']*ceil($totalNew / 5000));
			$costsPerSeat=$builder['cost_per_seat'];
			$costsSideStanding=$newSideStanding*(Config('stadium_cost_standing')+ $costsPerSeat);
			$costsSideSeats=$newSideSeats*(Config('stadium_cost_seats')+ $costsPerSeat);
			$costsGrandStanding=$newGrandStanding*(Config('stadium_cost_standing_grand')+ $costsPerSeat);
			$costsGrandSeats=$newGrandSeats*(Config('stadium_cost_seats_grand')+ $costsPerSeat);
			$costsVip=$newVips*(Config('stadium_cost_vip')+ $costsPerSeat);
			$offer=array('builder_id'=>$builder['id'],'builder_name'=>$builder['name'],'builder_picture'=>$builder['picture'],'builder_premiumfee'=>$builder['premiumfee'],'deadline'=>Timestamp()+ $constructionTime*24*3600,
				'deadline_days'=>$constructionTime,'reliability'=>$builder['reliability'],'fixedcosts'=>$builder['fixedcosts'],'costsSideStanding'=>$costsSideStanding,'costsSideSeats'=>$costsSideSeats,'costsGrandStanding'=>$costsGrandStanding,
				'costsGrandSeats'=>$costsGrandSeats,'costsVip'=>$costsVip,'totalCosts'=>$builder['fixedcosts'] + $costsSideStanding + $costsSideSeats + $costsGrandStanding + $costsGrandSeats + $costsVip);
			$offers[$builder['id']]=$offer;}
		return$offers;}
	static function getCurrentConstructionOrderOfTeam($websoccer,$db,$clubId){
		$fromTable=Config('db_prefix').'_stadium_construction AS C INNER JOIN '.Config('db_prefix').'_stadium_builder AS B ON B.id=C.builder_id';
		$result=$db->querySelect('C.*, B.name AS builder_name, B.reliability AS builder_reliability',$fromTable,'C.team_id=%d',$clubId);
		$order=$result->fetch_array();
		if($order)return$order;
		else return NULL;}
	static function getDueConstructionOrders($websoccer,$db){
		$fromTable=Config('db_prefix').'_stadium_construction AS C INNER JOIN '.Config('db_prefix').'_stadium_builder AS B ON B.id=C.builder_id INNER JOIN '.Config('db_prefix').'_verein AS T ON T.id=C.team_id';
		$result=$db->querySelect('C.*, T.user_id AS user_id, B.reliability AS builder_reliability',$fromTable,'C.deadline <= %d',Timestamp());
		$orders=[];
		while($order=$result->fetch_array())$orders[]=$order;
		return$orders;}
	static function computeUpgradeCosts($websoccer,$type,$stadium){
		$existingLevel=$stadium['level_'.$type];
		if($existingLevel >= 5)return 0;
		$baseCost=Config('stadium_'.$type.'_price');
		if($type=='seatsquality')$baseCost=$baseCost*($stadium['places_seats'] + $stadium['places_seats_grand']);
		elseif($type=='vipquality')$baseCost=$baseCost*$stadium['places_vip'];
		$additionFactor=Config('stadium_maintenance_priceincrease_per_level')* $existingLevel / 100;
		return round($baseCost + $baseCost*$additionFactor);}}

class TeamsDataService {
	static function getTeamById($websoccer,$db,$teamId){
		$fromTable=self::_getFromPart($websoccer);
		$whereCondition='C.id=%d AND C.status=1';
		$parameters=$teamId;
		$columns['C.id']='team_id';
		$columns['C.bild']='team_logo';
		$columns['C.name']='team_name';
		$columns['C.kurz']='team_short';
		$columns['C.strength']='team_strength';
		$columns['C.finanz_budget']='team_budget';
		$columns['C.min_target_rank']='team_min_target_rank';
		$columns['C.nationalteam']='is_nationalteam';
		$columns['C.captain_id']='captain_id';
		$columns['C.interimmanager']='interimmanager';
		$columns['C.history']='team_history';
		$columns['L.name']='team_league_name';
		$columns['L.id']='team_league_id';
		$columns['SPON.name']='team_sponsor_name';
		$columns['SPON.bild']='team_sponsor_picture';
		$columns['SPON.id']='team_sponsor_id';
		$columns['U.nick']='team_user_name';
		$columns['U.id']='team_user_id';
		$columns['U.email']='team_user_email';
		$columns['U.picture']='team_user_picture';
		$columns['DEPUTY.nick']='team_deputyuser_name';
		$columns['DEPUTY.id']='team_deputyuser_id';
		$columns['DEPUTY.email']='team_deputyuser_email';
		$columns['DEPUTY.picture']='team_deputyuser_picture';
		$columns['C.sa_tore']='team_season_goals';
		$columns['C.sa_gegentore']='team_season_againsts';
		$columns['C.sa_spiele']='team_season_matches';
		$columns['C.sa_siege']='team_season_wins';
		$columns['C.sa_niederlagen']='team_season_losses';
		$columns['C.sa_unentschieden']='team_season_draws';
		$columns['C.sa_punkte']='team_season_score';
		$columns['C.st_tore']='team_total_goals';
		$columns['C.st_gegentore']='team_total_againsts';
		$columns['C.st_spiele']='team_total_matches';
		$columns['C.st_siege']='team_total_wins';
		$columns['C.st_niederlagen']='team_total_losses';
		$columns['C.st_unentschieden']='team_total_draws';
		$columns['C.st_punkte']='team_total_score';
		$teaminfos=$db->queryCachedSelect($columns,$fromTable,$whereCondition,$parameters, 1);
		$team=(isset($teaminfos[0]))? $teaminfos[0] : array();
		if(isset($team['team_user_email']))$team['user_picture']=UsersDataService::getUserProfilePicture($websoccer,$team['team_user_picture'],$team['team_user_email'],20);
		if(isset($team['team_deputyuser_email']))$team['deputyuser_picture']=UsersDataService::getUserProfilePicture($websoccer,$team['team_deputyuser_picture'],$team['team_deputyuser_email'],20);
		return$team;}
	static function getTeamSummaryById($websoccer,$db,$teamId){
		if(!$teamId)return NULL;
		$tablePrefix=Config('db_prefix');
		$fromTable=$tablePrefix.'_verein AS C LEFT JOIN '.$tablePrefix.'_liga AS L ON C.liga_id=L.id';
		$whereCondition='C.status=1 AND C.id=%d';
		$parameters=$teamId;
		$columns['C.id']='team_id';
		$columns['C.name']='team_name';
		$columns['C.finanz_budget']='team_budget';
		$columns['C.bild']='team_picture';
		$columns['C.user_id']='user_id';
		$columns['L.name']='team_league_name';
		$columns['L.id']='team_league_id';
		$teaminfos=$db->queryCachedSelect($columns,$fromTable,$whereCondition,$parameters, 1);
		$team=(isset($teaminfos[0]))? $teaminfos[0] : array();
		return$team;}
	static function getTeamsOfLeagueOrderedByTableCriteria($websoccer,$db,$leagueId){
		$result=$db->querySelect('id',Config('db_prefix').'_saison', 'liga_id=%d AND beendet=\'0\' ORDER BY name DESC',$leagueId, 1);
		$season=$result->fetch_array();
		$fromTable=Config('db_prefix').'_verein AS C LEFT JOIN '.Config('db_prefix').'_user AS U ON C.user_id=U.id LEFT JOIN ' .Config('db_prefix').'_leaguehistory AS PREVDAY ON (PREVDAY.team_id=C.id AND PREVDAY.matchday=(C.sa_spiele - 1)';
		if($season)$fromTable.=' AND PREVDAY.season_id='.$season['id'];
		$fromTable.=')';
		$columns=[];
		$columns['C.id']='id';
		$columns['C.name']='name';
		$columns['C.sa_punkte']='score';
		$columns['C.sa_tore']='goals';
		$columns['C.sa_gegentore']='goals_received';
		$columns['(C.sa_tore - C.sa_gegentore)']='goals_diff';
		$columns['C.sa_siege']='wins';
		$columns['C.sa_niederlagen']='defeats';
		$columns['C.sa_unentschieden']='draws';
		$columns['C.sa_spiele']='matches';
		$columns['C.bild']='picture';
		$columns['U.id']='user_id';
		$columns['U.nick']='user_name';
		$columns['U.email']='user_email';
		$columns['U.picture']='user_picture';
		$columns['PREVDAY.rank']='previous_rank';
		$whereCondition='C.liga_id=%d AND C.status=\'1\' ORDER BY score DESC, goals_diff DESC, wins DESC, draws DESC, goals DESC, name ASC';
		$parameters=$leagueId;
		$teams=[];
		$now=Timestamp();
		$updateHistory=FALSE;
		if($season &&(!isset($_SESSION['leaguehist'])|| $_SESSION['leaguehist']<($now - 600))){
			$_SESSION['leaguehist']=$now;
			$updateHistory=TRUE;
			$queryTemplate='REPLACE INTO '.Config('db_prefix').'_leaguehistory ';
			$queryTemplate.='(team_id, season_id, user_id, matchday, rank)';
			$queryTemplate.='VALUES (%d, '.$season['id'].', %s, %d, %d);';}
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters);
		$rank=0;
		while($team=$result->fetch_array()){
			++$rank;
			$team['user_picture']=UsersDataService::getUserProfilePicture($websoccer,$team['user_picture'],$team['user_email'],20);
			$teams[]=$team;
			if($updateHistory &&$team['matches']){
				$userId=($team['user_id'])? $team['user_id'] : 'DEFAULT';
				$query=sprintf($queryTemplate,$team['id'],$userId,$team['matches'],$rank);
				$db->executeQuery($query);}}
		return$teams;}
	static function getTeamsOfSeasonOrderedByTableCriteria($websoccer,$db,$seasonId,$type){
		$fromTable=Config('db_prefix').'_team_league_statistics AS S INNER JOIN '.Config('db_prefix').'_verein AS C ON C.id=S.team_id LEFT JOIN '.Config('db_prefix').'_user AS U ON C.user_id=U.id';
		$whereCondition='S.season_id=%d';
		$parameters=$seasonId;
		$columns['C.id']='id';
		$columns['C.name']='name';
		$columns['C.bild']='picture';
		$fieldPrefix='total';
		if($type=='home')$fieldPrefix='home';
		elseif($type=='guest')$fieldPrefix='guest';
		$columns['S.'.$fieldPrefix.'_points']='score';
		$columns['S.'.$fieldPrefix.'_goals']='goals';
		$columns['S.'.$fieldPrefix.'_goalsreceived']='goals_received';
		$columns['S.'.$fieldPrefix.'_goalsdiff']='goals_diff';
		$columns['S.'.$fieldPrefix.'_wins']='wins';
		$columns['S.'.$fieldPrefix.'_draws']='draws';
		$columns['S.'.$fieldPrefix.'_losses']='defeats';
		$columns['(S.'.$fieldPrefix.'_wins + S.'.$fieldPrefix.'_draws + S.'.$fieldPrefix.'_losses)']='matches';
		$columns['U.id']='user_id';
		$columns['U.nick']='user_name';
		$columns['U.email']='user_email';
		$columns['U.picture']='user_picture';
		$teams=[];
		$whereCondition.=' ORDER BY score DESC, goals_diff DESC, wins DESC, draws DESC, goals DESC, name ASC';
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters);
		while($team=$result->fetch_array()){
			$team['user_picture']=UsersDataService::getUserProfilePicture($websoccer,$team['user_picture'],$team['user_email'],20);
			$teams[]=$team;}
		return$teams;}
	static function getTeamsOfLeagueOrderedByAlltimeTableCriteria($websoccer,$db,$leagueId,$type=null){
		$fromTable=Config('db_prefix').'_team_league_statistics AS S INNER JOIN '.Config('db_prefix').'_verein AS C ON C.id=S.team_id INNER JOIN ' .Config('db_prefix').
			'_saison AS SEASON ON SEASON.id=S.season_id LEFT JOIN '.Config('db_prefix').'_user AS U ON C.user_id=U.id';
		$whereCondition='SEASON.liga_id=%d';
		$parameters=$leagueId;
		$columns['C.id']='id';
		$columns['C.name']='name';
		$columns['C.bild']='picture';
		$fieldPrefix='total';
		if($type=='home')$fieldPrefix='home';
		elseif($type=='guest')$fieldPrefix='guest';
		$columns['SUM(S.'.$fieldPrefix.'_points)']='score';
		$columns['SUM(S.'.$fieldPrefix.'_goals)']='goals';
		$columns['SUM(S.'.$fieldPrefix.'_goalsreceived)']='goals_received';
		$columns['SUM(S.'.$fieldPrefix.'_goalsdiff)']='goals_diff';
		$columns['SUM(S.'.$fieldPrefix.'_wins)']='wins';
		$columns['SUM(S.'.$fieldPrefix.'_draws)']='draws';
		$columns['SUM(S.'.$fieldPrefix.'_losses)']='defeats';
		$columns['SUM((S.'.$fieldPrefix.'_wins + S.'.$fieldPrefix.'_draws + S.'.$fieldPrefix.'_losses))']='matches';
		$columns['U.id']='user_id';
		$columns['U.nick']='user_name';
		$columns['U.email']='user_email';
		$columns['U.picture']='user_picture';
		$teams=[];
		$whereCondition.=' GROUP BY C.id ORDER BY score DESC, goals_diff DESC, wins DESC, draws DESC, goals DESC, name ASC';
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters);
		while($team=$result->fetch_array()){
			$team['user_picture']=UsersDataService::getUserProfilePicture($websoccer,$team['user_picture'],$team['user_email'],20);
			$teams[]=$team;}
		return$teams;}
	static function getTableRankOfTeam($websoccer,$db,$teamId){
		$subQuery='(SELECT COUNT(*)FROM '.Config('db_prefix').'_verein AS T2 WHERE T2.liga_id=T1.liga_id AND(T2.sa_punkte>T1.sa_punkte OR T2.sa_punkte=T1.sa_punkte AND(T2.sa_tore-T2.sa_gegentore)>(T1.sa_tore-T1.sa_gegentore)'
			.'OR T2.sa_punkte=T1.sa_punkte AND(T2.sa_tore-T2.sa_gegentore)=(T1.sa_tore-T1.sa_gegentore)AND T2.sa_siege>T1.sa_siege'
			.'OR T2.sa_punkte=T1.sa_punkte AND(T2.sa_tore-T2.sa_gegentore)=(T1.sa_tore-T1.sa_gegentore)AND T2.sa_siege=T1.sa_siege AND T2.sa_tore>T1.sa_tore))';
		$columns=$subQuery.' + 1 AS RNK';
		$fromTable=Config('db_prefix').'_verein AS T1';
		$whereCondition='T1.id=%d';
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$teamId);
		$teamRank=$result->fetch_array();
		if($teamRank)return (int)$teamRank['RNK'];
		return 0;}
	static function getTeamsWithoutUser($websoccer,$db){
		$fromTable=Config('db_prefix').'_verein AS C INNER JOIN '.Config('db_prefix').'_liga AS L ON C.liga_id=L.id LEFT JOIN '.Config('db_prefix').'_stadion AS S ON C.stadion_id=S.id';
		$whereCondition='nationalteam!=\'1\' AND(C.user_id=0 OR C.user_id IS NULL OR C.interimmanager=\'1\')AND C.status=1';
		$columns['C.id']='team_id';
		$columns['C.name']='team_name';
		$columns['C.finanz_budget']='team_budget';
		$columns['C.bild']='team_picture';
		$columns['C.strength']='team_strength';
		$columns['L.id']='league_id';
		$columns['L.name']='league_name';
		$columns['L.land']='league_country';
		$columns['S.p_steh']='stadium_p_steh';
		$columns['S.p_sitz']='stadium_p_sitz';
		$columns['S.p_haupt_steh']='stadium_p_haupt_steh';
		$columns['S.p_haupt_sitz']='stadium_p_haupt_sitz';
		$columns['S.p_vip']='stadium_p_vip';
		$whereCondition.=' ORDER BY league_country ASC, league_name ASC, team_name ASC';
		$teams=[];
		$result=$db->querySelect($columns,$fromTable,$whereCondition,array(), 300);
		while($team=$result->fetch_array())$teams[$team['league_country']][]=$team;
		return$teams;}
	static function countTeamsWithoutManager($websoccer,$db){
		$result=$db->querySelect('COUNT(*)AS hits',Config('db_prefix').'_verein', '(user_id=0 OR user_id IS NULL)AND status=1');
		$teams=$result->fetch_array();
		if(isset($teams['hits']))return$teams['hits'];
		return 0;}
	static function findTeamNames($websoccer,$db,$query){
		$columns='name';
		$fromTable=Config('db_prefix').'_verein';
		$whereCondition='UPPER(name)LIKE \'%s%%\' AND status=1';
		$result=$db->querySelect($columns,$fromTable,$whereCondition, strtoupper($query), 10);
		$teams=[];
		while($team=$result->fetch_array())$teams[]=$team['name'];
		return$teams;}
	static function getTeamSize($websoccer,$db,$clubId){
		$columns='COUNT(*)AS number';
		$fromTable=Config('db_prefix').'_spieler';
		$whereCondition='verein_id=%d AND status=\'1\' AND transfermarkt!=\'1\' AND lending_fee=0';
		$parameters=$clubId;
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters);
		$players=$result->fetch_array();
		return($players['number'])? $players['number']:0;}
	static function getTotalPlayersSalariesOfTeam($websoccer,$db,$clubId){
		$columns='SUM(vertrag_gehalt)AS salary';
		$fromTable=Config('db_prefix').'_spieler';
		$whereCondition='verein_id=%d AND status=\'1\'';
		$parameters=$clubId;
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters);
		$players=$result->fetch_array();
		return($players['salary'])? $players['salary']:0;}
	static function getTeamCaptainIdOfTeam($websoccer,$db,$clubId){
		$result=$db->querySelect('captain_id',Config('db_prefix').'_verein', 'id=%d',$clubId);
		$team=$result->fetch_array();
		return (isset($team['captain_id']))? $team['captain_id']:0;}
	static function validateWhetherTeamHasEnoughBudgetForSalaryBid($websoccer,$db,$i18n,$clubId,$salary){
		$result=$db->querySelect('SUM(vertrag_gehalt)AS salary_sum',Config('db_prefix').'_spieler', 'verein_id=%d',$clubId);
		$players=$result->fetch_array();
		$minBudget=($players['salary_sum'] + $salary)* 2;
		$team=self::getTeamSummaryById($websoccer,$db,$clubId);
		if($team['team_budget']<$minBudget)throw new Exception(Message('extend-contract_cannot_afford_offer'));}
	static function _getFromPart($websoccer){
		$tablePrefix=Config('db_prefix');
		$fromTable=$tablePrefix.'_verein AS C LEFT JOIN '.$tablePrefix.'_liga AS L ON C.liga_id=L.id LEFT JOIN '.$tablePrefix.'_sponsor AS SPON ON C.sponsor_id=SPON.id LEFT JOIN '.$tablePrefix.'_user AS U ON C.user_id=U.id LEFT JOIN ' .
			$tablePrefix.'_user AS DEPUTY ON C.user_id_actual=DEPUTY.id';
		return$fromTable;}}

class TrainingcampsDataService {
	static function getCamps($websoccer,$db){
		$fromTable=Config('db_prefix').'_trainingslager';
		$whereCondition='1=1 ORDER BY name ASC';
		$camps=[];
		$result=$db->querySelect(self::_getColumns(),$fromTable,$whereCondition);
		while($camp=$result->fetch_array())$camps[]=$camp;
		return$camps;}
	static function getCampBookingsByTeam($websoccer,$db,$teamId){
		$fromTable=Config('db_prefix').'_trainingslager_belegung AS B INNER JOIN '.Config('db_prefix').'_trainingslager AS C ON C.id=B.lager_id';
		$columns['B.id']='id';
		$columns['B.datum_start']='date_start';
		$columns['B.datum_ende']='date_end';
		$columns['C.name']='name';
		$columns['C.land']='country';
		$columns['C.preis_spieler_tag']='costs';
		$columns['C.p_staerke']='effect_strength';
		$columns['C.p_technik']='effect_strength_technique';
		$columns['C.p_kondition']='effect_strength_stamina';
		$columns['C.p_frische']='effect_strength_freshness';
		$columns['C.p_zufriedenheit']='effect_strength_satisfaction';
		$whereCondition='B.verein_id=%d ORDER BY B.datum_start DESC';
		$camps=[];
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$teamId);
		while($camp=$result->fetch_array())$camps[]=$camp;
		return$camps;}
	static function getCampById($websoccer,$db,$campId){
		$fromTable=Config('db_prefix').'_trainingslager';
		$whereCondition='id=%d';
		$result=$db->querySelect(self::_getColumns(),$fromTable,$whereCondition,$campId);
		$camp=$result->fetch_array();
		return$camp;}
	static function executeCamp($websoccer,$db,$teamId,$bookingInfo){
		$players=PlayersDataService::getPlayersOfTeamById($websoccer,$db,$teamId);
		if(count($players)){
			$playerTable=Config('db_prefix').'_spieler';
			$updateCondition='id=%d';
			$duration=round(($bookingInfo['date_end'] - $bookingInfo['date_start'])/(24*3600));
			foreach($players as$player){
				if($player['matches_injured'])continue;
				$columns=[];
				$columns['w_staerke']=min(100, max(1,$bookingInfo['effect_strength']* $duration + $player['strength']));
				$columns['w_technik']=min(100, max(1,$bookingInfo['effect_strength_technique']* $duration + $player['strength_technic']));
				$columns['w_kondition']=min(100, max(1,$bookingInfo['effect_strength_stamina']* $duration + $player['strength_stamina']));
				$columns['w_frische']=min(100, max(1,$bookingInfo['effect_strength_freshness']* $duration + $player['strength_freshness']));
				$columns['w_zufriedenheit']=min(100, max(1,$bookingInfo['effect_strength_satisfaction']* $duration + $player['strength_satisfaction']));
				$db->queryUpdate($columns,$playerTable,$updateCondition,$player['id']);}}
		$db->queryDelete(Config('db_prefix').'_trainingslager_belegung','id=%d',$bookingInfo['id']);}
	static function _getColumns(){
		$columns['id']='id';
		$columns['name']='name';
		$columns['land']='country';
		$columns['preis_spieler_tag']='costs';
		$columns['p_staerke']='effect_strength';
		$columns['p_technik']='effect_strength_technique';
		$columns['p_kondition']='effect_strength_stamina';
		$columns['p_frische']='effect_strength_freshness';
		$columns['p_zufriedenheit']='effect_strength_satisfaction';
		return$columns;}}

class TrainingDataService{
	 static function countTrainers($websoccer,$db){$result=$db->querySelect('COUNT(*)AS hits',Config('db_prefix').'_trainer','1=1');$trainers=$result->fetch_array();return$trainers['hits'];}
	 static function getTrainers($websoccer,$db,$startIndex,$entries_per_page){$trainers=[];$result=$db->querySelect('*',Config('db_prefix').'_trainer','1=1 ORDER BY salary DESC',null,$startIndex.','.$entries_per_page);
	 						while($trainer=$result->fetch_array())$trainers[]=$trainer;return$trainers;}
	 static function getTrainerById($websoccer,$db,$trainerId){$result=$db->querySelect('*',Config('db_prefix').'_trainer','id=%d',$trainerId);$trainer=$result->fetch_array();return$trainer;}
	 static function countRemainingTrainingUnits($websoccer,$db,$teamId){$result=$db->querySelect('COUNT(*)AS hits',Config('db_prefix').'_training_unit','team_id=%d AND date_executed=0 OR date_executed IS NULL',$teamId);$units=$result->fetch_array();
	 						return$units['hits'];}
	 static function getLatestTrainingExecutionTime($websoccer,$db,$teamId){$result=$db->querySelect('date_executed',Config('db_prefix').'_training_unit','team_id=%d AND date_executed>0 ORDER BY date_executed DESC',$teamId,1);$unit=$result->fetch_array();
							if(isset($unit['date_executed']))return$unit['date_executed'];else return 0;}
	 static function getValidTrainingUnit($websoccer,$db,$teamId){$result=$db->querySelect('id,trainer_id',Config('db_prefix').'_training_unit','team_id=%d AND date_executed=0 OR date_executed IS NULL ORDER BY id ASC',$teamId,1);$unit=$result->fetch_array();return$unit;}
	 static function getTrainingUnitById($websoccer,$db,$teamId,$unitId){$result=$db->querySelect('*',Config('db_prefix').'_training_unit','id=%d AND team_id=%d',[$unitId,$teamId],1);$unit=$result->fetch_array();return$unit;}}

class TransfermarketDataService {
	static function getHighestBidForPlayer($websoccer,$db,$playerId,$transferStart,$transferEnd){
		$columns['B.id']='bid_id';
		$columns['B.abloese']='amount';
		$columns['B.handgeld']='hand_money';
		$columns['B.vertrag_spiele']='contract_matches';
		$columns['B.vertrag_gehalt']='contract_salary';
		$columns['B.vertrag_torpraemie']='contract_goalbonus';
		$columns['B.datum']='date';
		$columns['C.id']='team_id';
		$columns['C.name']='team_name';
		$columns['U.id']='user_id';
		$columns['U.nick']='user_name';
		$fromTable=Config('db_prefix').'_transfer_angebot AS B INNER JOIN '.Config('db_prefix').'_verein AS C ON C.id=B.verein_id INNER JOIN '.Config('db_prefix').'_user AS U ON U.id=B.user_id';
		$whereCondition='B.spieler_id=%d AND B.datum >= %d AND B.datum <= %d ORDER BY B.datum DESC';
		$parameters=array($playerId,$transferStart,$transferEnd);
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters, 1);
		$bid=$result->fetch_array();
		return$bid;}
	static function getCurrentBidsOfTeam($websoccer,$db,$teamId){
		$columns['B.abloese']='amount';
		$columns['B.handgeld']='hand_money';
		$columns['B.vertrag_spiele']='contract_matches';
		$columns['B.vertrag_gehalt']='contract_salary';
		$columns['B.vertrag_torpraemie']='contract_goalbonus';
		$columns['B.datum']='date';
		$columns['B.ishighest']='ishighest';
		$columns['P.id']='player_id';
		$columns['P.vorname']='player_firstname';
		$columns['P.nachname']='player_lastname';
		$columns['P.kunstname']='player_pseudonym';
		$columns['P.transfer_ende']='auction_end';
		$fromTable=Config('db_prefix').'_transfer_angebot AS B INNER JOIN '.Config('db_prefix').'_verein AS C ON C.id=B.verein_id INNER JOIN '.Config('db_prefix').'_spieler AS P ON P.id=B.spieler_id';
		$whereCondition='C.id=%d AND P.transfer_ende >= %d ORDER BY B.datum DESC, P.transfer_ende ASC';
		$parameters=array($teamId,Timestamp());
		$bids=[];
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters, 20);
		while($bid=$result->fetch_array()){
			if(!isset($bids[$bid['player_id']]))$bids[$bid['player_id']]=$bid;}
		return$bids;}
	static function getLatestBidOfUser($websoccer,$db,$userId){
		$columns['B.abloese']='amount';
		$columns['B.handgeld']='hand_money';
		$columns['B.vertrag_spiele']='contract_matches';
		$columns['B.vertrag_gehalt']='contract_salary';
		$columns['B.vertrag_torpraemie']='contract_goalbonus';
		$columns['B.datum']='date';
		$columns['P.id']='player_id';
		$columns['P.vorname']='player_firstname';
		$columns['P.nachname']='player_lastname';
		$columns['P.transfer_ende']='auction_end';
		$fromTable=Config('db_prefix').'_transfer_angebot AS B INNER JOIN '.Config('db_prefix').'_spieler AS P ON P.id=B.spieler_id';
		$whereCondition='B.user_id=%d ORDER BY B.datum DESC';
		$parameters=$userId;
		$bids=[];
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters, 1);
		$bid=$result->fetch_array();
		return$bid;}
	static function getCompletedTransfersOfUser($websoccer,$db,$userId){$whereCondition='T.buyer_user_id=%d OR T.seller_user_id=%d ORDER BY T.datum DESC';$parameters=array($userId,$userId);return self::getCompletedTransfers($websoccer,$db,$whereCondition,$parameters);}
	static function getCompletedTransfersOfTeam($websoccer,$db,$teamId){$whereCondition='SELLER.id=%d OR BUYER.id=%d ORDER BY T.datum DESC';$parameters=array($teamId,$teamId);return self::getCompletedTransfers($websoccer,$db,$whereCondition,$parameters);}
	static function getCompletedTransfersOfPlayer($websoccer,$db,$playerId){$whereCondition='T.spieler_id=%d ORDER BY T.datum DESC';$parameters=array($playerId);return self::getCompletedTransfers($websoccer,$db,$whereCondition,$parameters);}
	static function getLastCompletedTransfers($websoccer,$db){$whereCondition='1=1 ORDER BY T.datum DESC';return self::getCompletedTransfers($websoccer,$db,$whereCondition,array());}
	static function getCompletedTransfers($websoccer,$db,$whereCondition,$parameters){
		$transfers=[];
		$columns['T.datum']='transfer_date';
		$columns['P.id']='player_id';
		$columns['P.vorname']='player_firstname';
		$columns['P.nachname']='player_lastname';
		$columns['SELLER.id']='from_id';
		$columns['SELLER.name']='from_name';
		$columns['BUYER.id']='to_id';
		$columns['BUYER.name']='to_name';
		$columns['T.directtransfer_amount']='directtransfer_amount';
		$columns['EP1.id']='exchangeplayer1_id';
		$columns['EP1.kunstname']='exchangeplayer1_pseudonym';
		$columns['EP1.vorname']='exchangeplayer1_firstname';
		$columns['EP1.nachname']='exchangeplayer1_lastname';
		$columns['EP2.id']='exchangeplayer2_id';
		$columns['EP2.kunstname']='exchangeplayer2_pseudonym';
		$columns['EP2.vorname']='exchangeplayer2_firstname';
		$columns['EP2.nachname']='exchangeplayer2_lastname';
		$fromTable=Config('db_prefix').'_transfer AS T INNER JOIN '.Config('db_prefix').'_spieler AS P ON P.id=T.spieler_id INNER JOIN ' .Config('db_prefix').
			'_verein AS BUYER ON BUYER.id=T.buyer_club_id LEFT JOIN '.Config('db_prefix').'_verein AS SELLER ON SELLER.id=T.seller_club_id LEFT JOIN ' .Config('db_prefix').
			'_spieler AS EP1 ON EP1.id=T.directtransfer_player1 LEFT JOIN '.Config('db_prefix').'_spieler AS EP2 ON EP2.id=T.directtransfer_player2';
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters, 20);
		while($transfer=$result->fetch_array()){
			$transfer['hand_money']=0;
			$transfer['amount']=$transfer['directtransfer_amount'];
			$transfers[]=$transfer;}
		return$transfers;}
	static function movePlayersWithoutTeamToTransfermarket($websoccer,$db){
		$columns['unsellable']=0;
		$columns['lending_fee']=0;
		$columns['lending_owner_id']=0;
		$columns['lending_matches']=0;
		$fromTable=Config('db_prefix').'_spieler';
		$whereCondition='status=1 AND(transfermarkt!=\'1\' AND(verein_id=0 OR verein_id IS NULL)OR transfermarkt!=\'1\' AND verein_id>0 AND vertrag_spiele<1 OR transfermarkt=\'1\' AND verein_id>0 AND vertrag_spiele<1)';
		$result=$db->querySelect('id, verein_id',$fromTable,$whereCondition);
		while($player=$result->fetch_array()){
			$team=TeamsDataService::getTeamSummaryById($websoccer,$db,$player['verein_id']);
			if($team==NULL||$team['user_id']){
				if($team['user_id'])UserInactivityDataService::increaseContractExtensionField($websoccer,$db,$team['user_id']);
				$columns['transfermarkt']='1';
				$columns['transfer_start']=Timestamp();
				$columns['transfer_ende']=$columns['transfer_start'] + 24*3600 *Config('transfermarket_duration_days');
				$columns['transfer_mindestgebot']=0;
				$columns['verein_id']='';}
			else{
				$columns['transfermarkt']='0';
				$columns['transfer_start']='0';
				$columns['transfer_ende']='0';
				$columns['vertrag_spiele']='5';
				$columns['verein_id']=$player['verein_id'];}
			$db->queryUpdate($columns,$fromTable, 'id=%d',$player['id']);}}
	static function executeOpenTransfers($websoccer,$db){
		$columns['P.id']='player_id';
		$columns['P.transfer_start']='transfer_start';
		$columns['P.transfer_ende']='transfer_end';
		$columns['P.vorname']='first_name';
		$columns['P.nachname']='last_name';
		$columns['P.kunstname']='pseudonym';
		$columns['C.id']='team_id';
		$columns['C.name']='team_name';
		$columns['C.user_id']='team_user_id';
		$fromTable=Config('db_prefix').'_spieler AS P LEFT JOIN '.Config('db_prefix').'_verein AS C ON C.id=P.verein_id';
		$whereCondition='P.transfermarkt=\'1\' AND P.status=\'1\' AND P.transfer_ende<%d';
		$parameters=Timestamp();
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters, 50);
		while($player=$result->fetch_array()){
			$bid=self::getHighestBidForPlayer($websoccer,$db,$player['player_id'],$player['transfer_start'],$player['transfer_end']);
			if(!isset($bid['bid_id']))self::extendDuration($websoccer,$db,$player['player_id']);
			else self::transferPlayer($websoccer,$db,$player,$bid);}}
	static function getTransactionsBetweenUsers($websoccer,$db,$user1,$user2){
		$columns='COUNT(*)AS number';
		$fromTable=Config('db_prefix').'_transfer';
		$whereCondition='datum >= %d AND(seller_user_id=%d AND buyer_user_id=%d OR seller_user_id=%d AND buyer_user_id=%d)';
		$parameters=array(Timestamp()- 30*3600*24,$user1,$user2,$user2,$user1);
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters);
		$transactions=$result->fetch_array();
		if(isset($transactions['number']))return$transactions['number'];
		return 0;}
	static function awardUserForTrades($websoccer,$db,$userId){
		$result=$db->querySelect('COUNT(*)AS hits',Config('db_prefix').'_transfer', 'buyer_user_id=%d OR seller_user_id=%d',array($userId,$userId));
		$transactions=$result->fetch_array();
		if(!$transactions||!$transactions['hits'])return;
		awardBadgeIfApplicable($websoccer,$db,$userId, 'x_trades',$transactions['hits']);}
	function extendDuration($websoccer,$db,$playerId){
		$fromTable=Config('db_prefix').'_spieler';
		$columns['transfer_ende']=Timestamp()+ 24*3600*Config('transfermarket_duration_days');
		$whereCondition='id=%d';
		$db->queryUpdate($columns,$fromTable,$whereCondition,$playerId);}
	function transferPlayer($websoccer,$db,$player,$bid){
		$playerName=(strlen($player['pseudonym']))? $player['pseudonym']:$player['first_name'].' '.$player['last_name'];
		if($player['team_id']<1){
			if($bid['hand_money'])debitAmount($websoccer,$db,$bid['team_id'],$bid['hand_money'],'transfer_transaction_subject_handmoney',$playerName);}
		else{
			debitAmount($websoccer,$db,$bid['team_id'],$bid['amount'],'transfer_transaction_subject_fee',$player['team_name']);
			creditAmount($websoccer,$db,$player['team_id'],$bid['amount'],'transfer_transaction_subject_fee',$bid['team_name']);}
		$fromTable=Config('db_prefix').'_spieler';
		$columns['transfermarkt']=0;
		$columns['transfer_start']=0;
		$columns['transfer_ende']=0;
		$columns['verein_id']=$bid['team_id'];
		$columns['vertrag_spiele']=$bid['contract_matches'];
		$columns['vertrag_gehalt']=$bid['contract_salary'];
		$columns['vertrag_torpraemie']=$bid['contract_goalbonus'];
		$whereCondition='id=%d';
		$db->queryUpdate($columns,$fromTable,$whereCondition,$player['player_id']);
		$logcolumns['spieler_id']=$player['player_id'];
		$logcolumns['seller_user_id']=$player['team_user_id'];
		$logcolumns['seller_club_id']=$player['team_id'];
		$logcolumns['buyer_user_id']=$bid['user_id'];
		$logcolumns['buyer_club_id']=$bid['team_id'];
		$logcolumns['datum']=Timestamp();
		$logcolumns['directtransfer_amount']=$bid['amount'];
		$logTable=Config('db_prefix').'_transfer';
		$db->queryInsert($logcolumns,$logTable);
		NotificationsDataService::createNotification($websoccer,$db,$bid['user_id'],'transfer_bid_notification_transfered',array('player'=>$playerName), 'transfermarket', 'player', 'id='.$player['player_id']);
		$db->queryDelete(Config('db_prefix').'_transfer_angebot', 'spieler_id=%d',$player['player_id']);
		self::awardUserForTrades($websoccer,$db,$bid['user_id']);
		if($player['team_user_id'])self::awardUserForTrades($websoccer,$db,$player['team_user_id']);}}

class UserInactivityDataService {
	static function getUserInactivity($websoccer,$db,$userId){
		$columns['id']='id';
		$columns['login']='login';
		$columns['login_check']='login_check';
		$columns['aufstellung']='tactics';
		$columns['transfer']='transfer';
		$columns['transfer_check']='transfer_check';
		$columns['vertragsauslauf']='contractextensions';
		$fromTable=Config('db_prefix').'_user_inactivity';
		$whereCondition='user_id=%d';
		$parameters=$userId;
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters, 1);
		$inactivity=$result->fetch_array();
		if(!$inactivity){
			$newcolumns['user_id']=$userId;
			$db->queryInsert($newcolumns,$fromTable);
			return self::getUserInactivity($websoccer,$db,$userId);}
		return$inactivity;}
	static function computeUserInactivity($websoccer,$db,$userId){
		$inactivity=self::getUserInactivity($websoccer,$db,$userId);
		$now=Timestamp();
		$checkBoundary=$now - 24*3600;
		$updatecolumns=[];
		$user=UsersDataService::getUserById($websoccer,$db,$userId);
		if($inactivity['login_check']<$checkBoundary){
			$inactiveDays=round(($now - $user['lastonline'])/(24*3600));
			$updatecolumns['login']=min(100, round($inactiveDays*config('INACTIVITY_PER_DAY_LOGIN')));
			$updatecolumns['login_check']=$now;
			$formationTable=Config('db_prefix').'_aufstellung AS F';
			$formationTable.=' INNER JOIN '.Config('db_prefix').'_verein AS T ON T.id=F.verein_id';
			$result=$db->querySelect('F.datum AS date',$formationTable,'T.user_id=%d',$userId);
			$formation=$result->fetch_array();
			if($formation){
				$inactiveDays=round(($now - $formation['date'])/(24*3600));
				$updatecolumns['aufstellung']=min(100, round($inactiveDays*config('INACTIVITY_PER_DAY_TACTICS')));}}
		if($inactivity['transfer_check']<$checkBoundary){
			$bid=TransfermarketDataService::getLatestBidOfUser($websoccer,$db,$userId);
			$transferBenchmark=$user['registration_date'];
			if($bid)$transferBenchmark=$bid['date'];
			$inactiveDays=round(($now - $transferBenchmark)/(24*3600));
			$updatecolumns['transfer']=min(100, round($inactiveDays*config('INACTIVITY_PER_DAY_TRANSFERS')));
			$updatecolumns['transfer_check']=$now;}
		if(count($updatecolumns)){
			$fromTable=Config('db_prefix').'_user_inactivity';
			$db->queryUpdate($updatecolumns,$fromTable,'id=%d',$inactivity['id']);}}
	static function resetContractExtensionField($websoccer,$db,$userId){
		$inactivity=self::getUserInactivity($websoccer,$db,$userId);
		$updatecolumns['vertragsauslauf']=0;
		$fromTable=Config('db_prefix').'_user_inactivity';
		$db->queryUpdate($updatecolumns,$fromTable,'id=%d',$inactivity['id']);}
	static function increaseContractExtensionField($websoccer,$db,$userId){
		$inactivity=self::getUserInactivity($websoccer,$db,$userId);
		$updatecolumns['vertragsauslauf']=min(100,$inactivity['contractextensions']+config('INACTIVITY_PER_CONTRACTEXTENSION'));
		$fromTable=Config('db_prefix').'_user_inactivity';
		$db->queryUpdate($updatecolumns,$fromTable,'id=%d',$inactivity['id']);}}

class UsersDataService {
	static function createLocalUser($websoccer,$db,$nick=null,$email=null){
		$username=trim($nick);
		$emailAddress=strtolower(trim($email));
		if(!strlen($username)&&!strlen($emailAddress))throw new Exception('UsersDataService::createBlankUser(): Either user name or e-mail must be provided in order to create a new internal user.');
		if(strlen($username)&&self::getUserIdByNick($websoccer,$db,$username))throw new Exception('Nick name is already in use.');
		if(strlen($emailAddress)&&self::getUserIdByEmail($websoccer,$db,$emailAddress))throw new Exception('E-Mail address is already in use.');
		$i18n=I18n::getInstance(Config('supported_languages'));
		$columns=array('nick'=>$username,'email'=>$emailAddress,'status'=>'1','datum_anmeldung'=>Timestamp(),'lang'=>$i18n->getCurrentLanguage());
		if(Config('premium_initial_credit'))$columns['premium_balance']=Config('premium_initial_credit');
		$db->queryInsert($columns,Config('db_prefix').'_user');
		if(strlen($username))$userId=self::getUserIdByNick($websoccer,$db,$username);
		else $userId=self::getUserIdByEmail($websoccer,$db,$emailAddress);
		$event=new UserRegisteredEvent($websoccer,$db, I18n::getInstance(Config('supported_languages')),$userId,$username,$emailAddress);
		dispatchEvent($event);
		return$userId;}
	static function countActiveUsersWithHighscore($websoccer,$db){
		$columns='COUNT(id)AS hits';
		$fromTable=Config('db_prefix').'_user';
		$whereCondition='status=1 AND highscore>0 GROUP BY id';
		$result=$db->querySelect($columns,$fromTable,$whereCondition);
		if(!$result)$users=0;
		else $users=$result->num_rows;
		return$users;}
	static function getActiveUsersWithHighscore($websoccer,$db,$startIndex,$entries_per_page){
		$columns['U.id']='id';
		$columns['nick']='nick';
		$columns['email']='email';
		$columns['U.picture']='picture';
		$columns['highscore']='highscore';
		$columns['datum_anmeldung']='registration_date';
		$columns['C.id']='team_id';
		$columns['C.name']='team_name';
		$columns['C.bild']='team_picture';
		$limit=$startIndex .','.$entries_per_page;
		$fromTable=Config('db_prefix').'_user AS U LEFT JOIN '.Config('db_prefix').'_verein AS C ON C.id=U.id';
		$whereCondition='U.status=1 AND highscore>0 GROUP BY id ORDER BY highscore DESC, datum_anmeldung ASC';
		$result=$db->querySelect($columns,$fromTable,$whereCondition, null,$limit);
		$users=[];
		while($user=$result->fetch_array()){
			$user['picture']=self::getUserProfilePicture($websoccer,$user['picture'],$user['email']);
			$users[]=$user;}
		return$users;}
	static function getUserById($websoccer,$db,$userId){
		$columns['id']='id';
		$columns['nick']='nick';
		$columns['email']='email';
		$columns['highscore']='highscore';
		$columns['fanbeliebtheit']='popularity';
		$columns['datum_anmeldung']='registration_date';
		$columns['lastonline']='lastonline';
		$columns['picture']='picture';
		$columns['history']='history';
		$columns['name']='name';
		$columns['wohnort']='place';
		$columns['land']='country';
		$columns['geburtstag']='birthday';
		$columns['beruf']='occupation';
		$columns['interessen']='interests';
		$columns['lieblingsverein']='favorite_club';
		$columns['homepage']='homepage';
		$columns['premium_balance']='premium_balance';
		$fromTable=Config('db_prefix').'_user';
		$whereCondition='id=%d AND status=1';
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$userId);
		$user=$result->fetch_array();
		if($user){
			$user['picture_uploadfile']=$user['picture'];
			$user['picture']=self::getUserProfilePicture($websoccer,$user['picture'],$user['email'],120);}
		return$user;}
	static function getUserIdByNick($websoccer,$db,$nick){
		$columns='id';
		$fromTable=Config('db_prefix').'_user';
		$whereCondition='nick="%s"AND status=1';
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$nick);
		$user=$result->fetch_array();
		if(isset($user['id']))return$user['id'];
		return -1;}
	static function getUserIdByEmail($websoccer,$db,$email){
		$columns='id';
		$fromTable=Config('db_prefix').'_user';
		$whereCondition='email="%s"AND status=1';
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$email);
		$user=$result->fetch_array();
		if(isset($user['id']))return$user['id'];
		return -1;}
	static function findUsernames($websoccer,$db,$nickStart){
		$columns='nick';
		$fromTable=Config('db_prefix').'_user';
		$whereCondition='UPPER(nick)LIKE "%s%%" AND status=1';
		$result=$db->querySelect($columns,$fromTable,$whereCondition, strtoupper($nickStart), 10);
		$users=[];
		while($user=$result->fetch_array())$users[]=$user['nick'];
		return$users;}
	static function getUserProfilePicture($websoccer,$fileName,$email,$size=40){
		if(strlen($fileName))return Config('context_root').'/uploads/users/'.$fileName;
		return self::getGravatarUserProfilePicture($websoccer,$email,$size);}
	static function getGravatarUserProfilePicture($websoccer,$email,$size=40){
		if(strlen($email)&&Config('gravatar_enable')){
			if(empty($_SERVER['HTTPS']))$picture='http://www.';
			else $picture='https://secure.';
			$picture.='gravatar.com/avatar/'.hash('sha256',strtolower($email));
			$picture.='?s='.$size;
			$picture.='&d=mm';
			return$picture;}
		else return null;}
	static function countOnlineUsers($websoccer,$db){
		$timeBoundary=Timestamp()- 15*60;
		$result=$db->querySelect('COUNT(*)AS hits',Config('db_prefix').'_user','lastonline >= %d',$timeBoundary);
		$users=$result->fetch_array();
		if(isset($users['hits']))return$users['hits'];
		return 0;}
	static function getOnlineUsers($websoccer,$db,$startIndex,$entries_per_page){
		$timeBoundary=Timestamp()- 15*60;
		$columns['U.id']='id';
		$columns['nick']='nick';
		$columns['email']='email';
		$columns['U.picture']='picture';
		$columns['lastonline']='lastonline';
		$columns['lastaction']='lastaction';
		$columns['c_hideinonlinelist']='hideinonlinelist';
		$columns['C.id']='team_id';
		$columns['C.name']='team_name';
		$columns['C.bild']='team_picture';
		$limit=$startIndex .','.$entries_per_page;
		$fromTable=Config('db_prefix').'_user AS U LEFT JOIN '.Config('db_prefix').'_verein AS C ON C.id=U.id';
		$whereCondition='U.status=1 AND lastonline >= %d GROUP BY id ORDER BY lastonline DESC';
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$timeBoundary,$limit);
		$users=[];
		while($user=$result->fetch_array()){
			$user['picture']=self::getUserProfilePicture($websoccer,$user['picture'],$user['email']);
			$users[]=$user;}
		return$users;}
	static function countTotalUsers($websoccer,$db){
		$result=$db->querySelect('COUNT(*)AS hits',Config('db_prefix').'_user','status=1');
		$users=$result->fetch_array();
		if(isset($users['hits']))return$users['hits'];
		return 0;}}

class MatchesDataService {
	static function getNextMatches($websoccer,$db,$clubId,$maxNumber){
		$fromTable=self::_getFromPart($websoccer);
		$whereCondition='M.berechnet!=\'1\' AND(HOME.id=%d OR GUEST.id=%d)AND M.datum>%d ORDER BY M.datum ASC';
		$parameters=array($clubId,$clubId,Timestamp());
		$columns['M.id']='match_id';
		$columns['M.datum']='match_date';
		$columns['M.spieltyp']='match_type';
		$columns['HOME.id']='match_home_id';
		$columns['HOME.name']='match_home_name';
		$columns['GUEST.id']='match_guest_id';
		$columns['GUEST.name']='match_guest_name';
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters,$maxNumber);
		$matches=[];
		while($match=$result->fetch_array()){
			$match['match_type']=self::_convertLeagueType($match['match_type']);
			$matches[]=$match;}
	return$matches;}
	static function getNextMatch($websoccer,$db,$clubId){
		$fromTable=self::_getFromPart($websoccer);
		$formationTable=Config('db_prefix').'_aufstellung';
		$fromTable.=' LEFT JOIN '.$formationTable.' AS HOME_F ON HOME_F.verein_id=HOME.id AND HOME_F.match_id=M.id LEFT JOIN '.$formationTable.' AS GUEST_F ON GUEST_F.verein_id=GUEST.id AND GUEST_F.match_id=M.id';
		$whereCondition='M.berechnet!=\'1\' AND(HOME.id=%d OR GUEST.id=%d)AND M.datum>%d ORDER BY M.datum ASC';
		$parameters=array($clubId,$clubId,Timestamp());
		$columns['M.id']='match_id';
		$columns['M.datum']='match_date';
		$columns['M.spieltyp']='match_type';
		$columns['HOME.id']='match_home_id';
		$columns['HOME.name']='match_home_name';
		$columns['HOME_F.id']='match_home_formation_id';
		$columns['GUEST.id']='match_guest_id';
		$columns['GUEST.name']='match_guest_name';
		$columns['GUEST_F.id']='match_guest_formation_id';
		$matchinfos=$db->queryCachedSelect($columns,$fromTable,$whereCondition,$parameters, 1);
		if(!count($matchinfos))$matchinfo=[];
		else{
			$matchinfo=$matchinfos[0];
			$matchinfo['match_type']=self::_convertLeagueType($matchinfo['match_type']);}
		return$matchinfo;}
	static function getLiveMatch($websoccer,$db){
		$fromTable=self::_getFromPart($websoccer);
		$whereCondition='M.berechnet!=\'1\' AND M.minutes>0 AND(HOME.user_id=%d OR GUEST.user_id=%d)AND M.datum<%d ORDER BY M.datum DESC';
		$parameters=array($websoccer->getUser()->id,$websoccer->getUser()->id,Timestamp());
		$columns['M.id']='match_id';
		$columns['M.datum']='match_date';
		$columns['M.spieltyp']='match_type';
		$columns['HOME.id']='match_home_id';
		$columns['HOME.name']='match_home_name';
		$columns['GUEST.id']='match_guest_id';
		$columns['GUEST.name']='match_guest_name';
		$matchinfos=$db->queryCachedSelect($columns,$fromTable,$whereCondition,$parameters, 1);
		if(!count($matchinfos))$matchinfo=[];
		else{
			$matchinfo=$matchinfos[0];
			$matchinfo['match_type']=self::_convertLeagueType($matchinfo['match_type']);}
		return$matchinfo;}
	static function getMatchById($websoccer,$db,$matchId,$loadStadiumInfo=TRUE,$loadSeasonInfo=FALSE){
		$fromTable=self::_getFromPart($websoccer);
		if($loadStadiumInfo){
			$fromTable.=' LEFT JOIN '.Config('db_prefix').'_stadion AS S ON  S.id=IF(M.stadion_id IS NOT NULL, M.stadion_id, HOME.stadion_id)';
			$columns['S.name']='match_stadium_name';}
		if($loadSeasonInfo){
			$fromTable.=' LEFT JOIN '.Config('db_prefix').'_saison AS SEASON ON SEASON.id=M.saison_id';
			$columns['SEASON.name']='match_season_name';
			$columns['SEASON.liga_id']='match_league_id';}
		$whereCondition='M.id=%d';
		$parameters=$matchId;
		$columns['M.id']='match_id';
		$columns['M.datum']='match_date';
		$columns['M.spieltyp']='match_type';
		$columns['HOME.id']='match_home_id';
		$columns['HOME.name']='match_home_name';
		$columns['HOME.nationalteam']='match_home_nationalteam';
		$columns['HOME.bild']='match_home_picture';
		$columns['GUEST.id']='match_guest_id';
		$columns['GUEST.name']='match_guest_name';
		$columns['GUEST.nationalteam']='match_guest_nationalteam';
		$columns['GUEST.bild']='match_guest_picture';
		$columns['M.pokalname']='match_cup_name';
		$columns['M.pokalrunde']='match_cup_round';
		$columns['M.spieltag']='match_matchday';
		$columns['M.saison_id']='match_season_id';
		$columns['M.berechnet']='match_simulated';
		$columns['M.home_tore']='match_goals_home';
		$columns['M.gast_tore']='match_goals_guest';
		$columns['M.bericht']='match_deprecated_report';
		$columns['M.minutes']='match_minutes';
		$columns['M.home_noformation']='match_home_noformation';
		$columns['M.guest_noformation']='match_guest_noformation';
		$columns['M.zuschauer']='match_audience';
		$columns['M.soldout']='match_soldout';
		$columns['M.elfmeter']='match_penalty_enabled';
		$columns['M.home_offensive']='match_home_offensive';
		$columns['M.gast_offensive']='match_guest_offensive';
		$columns['M.home_longpasses']='match_home_longpasses';
		$columns['M.gast_longpasses']='match_guest_longpasses';
		$columns['M.home_counterattacks']='match_home_counterattacks';
		$columns['M.gast_counterattacks']='match_guest_counterattacks';
		for($subNo=1; $subNo <= 3;++$subNo){
			$columns['M.home_w'.$subNo.'_raus']='match_home_sub'.$subNo.'_out';
			$columns['M.home_w'.$subNo.'_rein']='match_home_sub'.$subNo.'_in';
			$columns['M.home_w'.$subNo.'_minute']='match_home_sub'.$subNo.'_minute';
			$columns['M.home_w'.$subNo.'_condition']='match_home_sub'.$subNo.'_condition';
			$columns['M.gast_w'.$subNo.'_raus']='match_guest_sub'.$subNo.'_out';
			$columns['M.gast_w'.$subNo.'_rein']='match_guest_sub'.$subNo.'_in';
			$columns['M.gast_w'.$subNo.'_minute']='match_guest_sub'.$subNo.'_minute';
			$columns['M.gast_w'.$subNo.'_condition']='match_guest_sub'.$subNo.'_condition';}
		$matchinfos=$db->queryCachedSelect($columns,$fromTable,$whereCondition,$parameters, 1);
		$match=(isset($matchinfos[0]))? $matchinfos[0] : array();
		if(isset($match['match_type']))$match['match_type']=self::_convertLeagueType($match['match_type']);
		return$match;}
	static function getMatchSubstitutionsById($websoccer,$db,$matchId){
		$fromTable=Config('db_prefix').'_spiel AS M';
		$whereCondition='M.id=%d';
		$parameters=$matchId;
		$columns['M.id']='match_id';
		$columns['M.home_verein']='match_home_id';
		$columns['M.gast_verein']='match_guest_id';
		$columns['M.berechnet']='match_simulated';
		$columns['M.minutes']='match_minutes';
		$columns['M.home_offensive']='match_home_offensive';
		$columns['M.home_offensive_changed']='match_home_offensive_changed';
		$columns['M.home_longpasses']='match_home_longpasses';
		$columns['M.home_counterattacks']='match_home_counterattacks';
		$columns['M.home_freekickplayer']='match_home_freekickplayer';
		$columns['M.gast_offensive_changed']='match_guest_offensive_changed';
		$columns['M.gast_offensive']='match_guest_offensive';
		$columns['M.gast_longpasses']='match_guest_longpasses';
		$columns['M.gast_counterattacks']='match_guest_counterattacks';
		$columns['M.gast_freekickplayer']='match_guest_freekickplayer';
		for($subNo=1; $subNo <= 3;++$subNo){
			$columns['M.home_w'.$subNo.'_raus']='home_sub'.$subNo.'_out';
			$columns['M.home_w'.$subNo.'_rein']='home_sub'.$subNo.'_in';
			$columns['M.home_w'.$subNo.'_minute']='home_sub'.$subNo.'_minute';
			$columns['M.home_w'.$subNo.'_condition']='home_sub'.$subNo.'_condition';
			$columns['M.home_w'.$subNo.'_position']='home_sub'.$subNo.'_position';
			$columns['M.gast_w'.$subNo.'_raus']='guest_sub'.$subNo.'_out';
			$columns['M.gast_w'.$subNo.'_rein']='guest_sub'.$subNo.'_in';
			$columns['M.gast_w'.$subNo.'_minute']='guest_sub'.$subNo.'_minute';
			$columns['M.gast_w'.$subNo.'_condition']='guest_sub'.$subNo.'_condition';
			$columns['M.gast_w'.$subNo.'_position']='guest_sub'.$subNo.'_position';}
		$matchinfos=$db->queryCachedSelect($columns,$fromTable,$whereCondition,$parameters, 1);
		$match=(isset($matchinfos[0]))? $matchinfos[0] : array();
		return$match;}
	static function getLastMatch($websoccer,$db){
		$whereCondition='M.berechnet=1 AND(HOME.user_id=%d OR GUEST.user_id=%d)AND M.datum<%d ORDER BY M.datum DESC';
		$parameters=array($websoccer->getUser()->id,$websoccer->getUser()->id,Timestamp());
		return self::_getMatchSummaryByCondition($websoccer,$db,$whereCondition,$parameters);}
	static function getLiveMatchByTeam($websoccer,$db,$teamId){
		$whereCondition='M.berechnet!=1 AND(HOME.id=%d OR GUEST.id=%d)AND M.minutes>0 ORDER BY M.datum DESC';
		$parameters=array($teamId,$teamId);
		return self::_getMatchSummaryByCondition($websoccer,$db,$whereCondition,$parameters);}
	static function _getMatchSummaryByCondition($websoccer,$db,$whereCondition,$parameters){
		$fromTable=self::_getFromPart($websoccer);
		$columns['M.id']='match_id';
		$columns['M.datum']='match_date';
		$columns['M.spieltyp']='match_type';
		$columns['HOME.id']='match_home_id';
		$columns['HOME.name']='match_home_name';
		$columns['GUEST.id']='match_guest_id';
		$columns['GUEST.name']='match_guest_name';
		$columns['M.home_tore']='match_goals_home';
		$columns['M.gast_tore']='match_goals_guest';
		$matchinfos=$db->queryCachedSelect($columns,$fromTable,$whereCondition,$parameters, 1);
		if(!count($matchinfos))$matchinfo=[];
		else{
			$matchinfo=$matchinfos[0];
			$matchinfo['match_type']=self::_convertLeagueType($matchinfo['match_type']);}
		return$matchinfo;}
	static function getPreviousMatches($matchinfo,$websoccer,$db){
		$fromTable=self::_getFromPart($websoccer);
		$whereCondition='M.berechnet=1 AND(HOME.id=%d AND GUEST.id=%d OR HOME.id=%d AND GUEST.id=%d)ORDER BY M.datum DESC';
		$parameters=array($matchinfo['match_home_id'],$matchinfo['match_guest_id'],$matchinfo['match_guest_id'],$matchinfo['match_home_id']);
		$columns['M.id']='id';
		$columns['HOME.name']='home_team';
		$columns['GUEST.name']='guest_team';
		$columns['M.home_tore']='home_goals';
		$columns['M.gast_tore']='guest_goals';
		$matches=[];
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters, 4);
		while($matchinfo=$result->fetch_array())$matches[]=$matchinfo;
		return$matches;}
	static function getCupRoundsByCupname($websoccer,$db){
		$columns['C.name']='cup';
		$columns['R.name']='round';
		$columns['R.firstround_date']='round_date';
		$fromTable=Config('db_prefix').'_cup_round AS R INNER JOIN '.Config('db_prefix').'_cup AS C ON C.id=R.cup_id';
		$result=$db->querySelect($columns,$fromTable, 'archived!=\'1\' ORDER BY cup ASC, round_date ASC');
		$cuprounds=[];
		while($cup=$result->fetch_array())$cuprounds[$cup['cup']][]=$cup['round'];
		return$cuprounds;}
	static function getMatchesByMatchday($websoccer,$db,$seasonId,$matchDay){
		$whereCondition='M.saison_id=%d AND M.spieltag=%d  ORDER BY M.datum ASC';
		$parameters=array($seasonId,$matchDay);
		return self::getMatchesByCondition($websoccer,$db,$whereCondition,$parameters, 50);}
	static function getMatchesByCupRound($websoccer,$db,$cupName,$cupRound){
		$whereCondition='M.pokalname=\'%s\' AND M.pokalrunde=\'%s\'  ORDER BY M.datum ASC';
		$parameters=array($cupName,$cupRound);
		return self::getMatchesByCondition($websoccer,$db,$whereCondition,$parameters, 50);}
	static function getMatchesByCupRoundAndGroup($websoccer,$db,$cupName,$cupRound,$cupGroup){
		$whereCondition='M.pokalname=\'%s\' AND M.pokalrunde=\'%s\' AND M.pokalgruppe=\'%s\' ORDER BY M.datum ASC';
		$parameters=array($cupName,$cupRound,$cupGroup);
		return self::getMatchesByCondition($websoccer,$db,$whereCondition,$parameters, 50);}
	static function getLatestMatches($websoccer,$db,$limit=20,$ignoreFriendlies=FALSE){
		$whereCondition='M.berechnet=1';
		if($ignoreFriendlies)$whereCondition.=' AND M.spieltyp!=\'Freundschaft\'';
		$whereCondition.=' ORDER BY M.datum DESC';
		$parameters=[];
		return self::getMatchesByCondition($websoccer,$db,$whereCondition,$parameters,$limit);}
	static function getLatestMatchesByUser($websoccer,$db,$userId){
		$whereCondition='M.berechnet=1 AND(M.home_user_id=%d OR M.gast_user_id=%d)ORDER BY M.datum DESC';
		$parameters=array($userId,$userId);
		return self::getMatchesByCondition($websoccer,$db,$whereCondition,$parameters, 20);}
	static function getLatestMatchesByTeam($websoccer,$db,$teamId){
		$whereCondition='M.berechnet=1 AND(HOME.id=%d OR GUEST.id=%d)ORDER BY M.datum DESC';
		$parameters=array($teamId,$teamId);
		return self::getMatchesByCondition($websoccer,$db,$whereCondition,$parameters, 20);}
	static function getTodaysMatches($websoccer,$db,$startIndex,$entries_per_page){
		$startTs=mktime (0, 0, 1, date('n'), date('j'), date('Y'));
		$endTs=$startTs + 3600*24;
		$whereCondition='M.datum >= %d AND M.datum<%d ORDER BY M.datum ASC';
		$parameters=array($startTs,$endTs);
		$limit=$startIndex.','.$entries_per_page;
		return self::getMatchesByCondition($websoccer,$db,$whereCondition,$parameters,$limit);}
	static function countTodaysMatches($websoccer,$db){
		$startTs=mktime (0, 0, 1, date('n'), date('j'), date('Y'));
		$endTs=$startTs + 3600*24;
		$whereCondition='M.datum >= %d AND M.datum<%d';
		$parameters=array($startTs,$endTs);
		$result=$db->querySelect('COUNT(*)AS hits',Config('db_prefix').'_spiel AS M',$whereCondition,$parameters);
		$matches=$result->fetch_array();
		if($matches)return$matches['hits'];
		return 0;}
	static function getMatchesByTeamAndTimeframe($websoccer,$db,$teamId,$dateStart,$dateEnd){
		$whereCondition='(HOME.id=%d OR GUEST.id=%d)AND datum >= %d AND datum <= %d ORDER BY M.datum DESC';
		$parameters=array($teamId,$teamId,$dateStart,$dateEnd);
		return self::getMatchesByCondition($websoccer,$db,$whereCondition,$parameters, 20);}
	static function getMatchdayNumberOfTeam($websoccer,$db,$teamId){
		$columns='spieltag AS matchday';
		$fromTable=Config('db_prefix').'_spiel';
		$whereCondition='spieltyp=\'Ligaspiel\' AND berechnet=1 AND(home_verein=%d OR gast_verein=%d)ORDER BY datum DESC';
		$parameters=array($teamId,$teamId);
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters, 1);
		$matches=$result->fetch_array();
		if($matches)return (int)$matches['matchday'];
		return 0;}
	static function getMatchReportPlayerRecords($websoccer,$db,$matchId,$teamId){
		$fromTable=Config('db_prefix').'_spiel_berechnung AS M INNER JOIN '.Config('db_prefix').'_spieler AS P ON P.id=M.spieler_id';
		$columns['P.id']='id';
		$columns['P.vorname']='firstName';
		$columns['P.nachname']='lastName';
		$columns['P.kunstname']='pseudonym';
		$columns['P.position']='position';
		$columns['M.position_main']='position_main';
		$columns['M.note']='grade';
		$columns['M.tore']='goals';
		$columns['M.verletzt']='injured';
		$columns['M.gesperrt']='blocked';
		$columns['M.karte_gelb']='yellowCards';
		$columns['M.karte_rot']='redCard';
		$columns['M.feld']='playstatus';
		$columns['M.minuten_gespielt']='minutesPlayed';
		$columns['M.assists']='assists';
		$columns['M.ballcontacts']='ballcontacts';
		$columns['M.wontackles']='wontackles';
		$columns['M.losttackles']='losttackles';
		$columns['M.shoots']='shoots';
		$columns['M.passes_successed']='passes_successed';
		$columns['M.passes_failed']='passes_failed';
		$columns['M.age']='age';
		$columns['M.w_staerke']='strength';
		$order='field(M.position_main, \'T\', \'LV\', \'IV\', \'RV\', \'DM\', \'LM\', \'ZM\', \'RM\', \'OM\', \'LS\', \'MS\', \'RS\')';
		$whereCondition='M.spiel_id=%d AND M.team_id=%d AND M.feld!=\'Ersatzbank\' ORDER BY '.$order.', M.id ASC';
		$parameters=array($matchId,$teamId);
		$players=$db->queryCachedSelect($columns,$fromTable,$whereCondition,$parameters);
		return$players;}
	static function getMatchPlayerRecordsByField($websoccer,$db,$matchId,$teamId){
		$fromTable=Config('db_prefix').'_spiel_berechnung AS M INNER JOIN '.Config('db_prefix').'_spieler AS P ON P.id=M.spieler_id';
		$columns=array('P.id'=>'id', 'P.vorname'=>'firstname', 'P.nachname'=>'lastname', 'P.kunstname'=>'pseudonym', 'P.verletzt'=>'matches_injured', 'P.position'=>'position', 'P.position_main'=>'position_main', 'P.position_second'=>'position_second',
			'P.w_staerke'=>'strength', 'P.w_technik'=>'strength_technique', 'P.w_kondition'=>'strength_stamina', 'P.w_frische'=>'strength_freshness', 'P.w_zufriedenheit'=>'strength_satisfaction', 'P.nation'=>'player_nationality', 'P.picture'=>'picture',
			'P.sa_tore'=>'st_goals', 'P.sa_spiele'=>'st_matches', 'P.sa_karten_gelb'=>'st_cards_yellow', 'P.sa_karten_gelb_rot'=>'st_cards_yellow_red', 'P.sa_karten_rot'=>'st_cards_red', 'M.id'=>'match_record_id', 'M.position'=>'match_position',
			'M.position_main'=>'match_position_main', 'M.feld'=>'field', 'M.note'=>'grade');
		if(Config('players_aging')=='birthday')$ageColumn='TIMESTAMPDIFF(YEAR,P.geburtstag,CURDATE())';
		else $ageColumn='P.age';
		$columns[$ageColumn]='age';
		$whereCondition='M.spiel_id=%d AND M.team_id=%d AND M.feld!=\'Ausgewechselt\' ORDER BY field(M.position_main, \'T\', \'LV\', \'IV\', \'RV\', \'DM\', \'LM\', \'ZM\', \'RM\', \'OM\', \'LS\', \'MS\', \'RS\'), M.id ASC';
		$parameters=array($matchId,$teamId);
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters);
		$players=[];
		while($player=$result->fetch_array()){
			$field=($player['field']==='1')? 'field' : 'bench';
			$player['position']=PlayersDataService::_convertPosition($player['position']);
			$players[$field][]=$player;}
		return$players;}
	static function getMatchReportMessages($websoccer,$db,$i18n,$matchId){
		$fromTable=Config('db_prefix').'_matchreport AS R INNER JOIN '.Config('db_prefix').'_spiel_text AS T ON R.message_id=T.id';
		$columns['R.id']='report_id';
		$columns['R.minute']='minute';
		$columns['R.playernames']='playerNames';
		$columns['R.goals']='goals';
		$columns['T.nachricht']='message';
		$columns['T.aktion']='type';
		$columns['R.active_home']='active_home';
		$whereCondition='R.match_id=%d ORDER BY R.minute DESC, R.id DESC';
		$parameters=$matchId;
		$reportmessages=[];
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters);
		$match=null;
		while($reportmessage=$result->fetch_array()){
			$players=explode(';',$reportmessage['playerNames']);
			$rmsg=$reportmessage['message'];
			$msgKey=strip_tags($rmsg);
			if(Message($msgKey))$rmsg=Message($msgKey);
			for($playerIndex=1; $playerIndex <= count($players);++$playerIndex)$rmsg=str_replace('{sp'.$playerIndex.'}',$players[$playerIndex - 1],$rmsg);
			if(strpos($rmsg, '{ma1}')|| strpos($rmsg, '{ma2}')){
				if($match==null)$match=self::getMatchById($websoccer,$db,$matchId, FALSE);
				if($reportmessage['active_home']){
					$rmsg=str_replace('{ma1}',$match['match_home_name'],$rmsg);
					$rmsg=str_replace('{ma2}',$match['match_guest_name'],$rmsg);}
				else{
					$rmsg=str_replace('{ma1}',$match['match_guest_name'],$rmsg);
					$rmsg=str_replace('{ma2}',$match['match_home_name'],$rmsg);}}
			$reportmessage['message']=$rmsg;
			$reportmessages[]=$reportmessage;}
		return$reportmessages;}
	static function getMatchesByCondition($websoccer,$db,$whereCondition,$parameters,$limit){
		$fromTable=self::_getFromPart($websoccer);
		$columns['M.id']='id';
		$columns['M.spieltyp']='type';
		$columns['M.pokalname']='cup_name';
		$columns['M.pokalrunde']='cup_round';
		$columns['M.home_noformation']='home_noformation';
		$columns['M.guest_noformation']='guest_noformation';
		$columns['HOME.name']='home_team';
		$columns['HOME.bild']='home_team_picture';
		$columns['HOME.id']='home_id';
		$columns['HOMEUSER.id']='home_user_id';
		$columns['HOMEUSER.nick']='home_user_nick';
		$columns['HOMEUSER.email']='home_user_email';
		$columns['HOMEUSER.picture']='home_user_picture';
		$columns['GUEST.name']='guest_team';
		$columns['GUEST.bild']='guest_team_picture';
		$columns['GUEST.id']='guest_id';
		$columns['GUESTUSER.id']='guest_user_id';
		$columns['GUESTUSER.nick']='guest_user_nick';
		$columns['GUESTUSER.email']='guest_user_email';
		$columns['GUESTUSER.picture']='guest_user_picture';
		$columns['M.home_tore']='home_goals';
		$columns['M.gast_tore']='guest_goals';
		$columns['M.berechnet']='simulated';
		$columns['M.minutes']='minutes';
		$columns['M.datum']='date';
		$matches=[];
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters,$limit);
		while($matchinfo=$result->fetch_array()){
			$matchinfo['home_user_picture']=UsersDataService::getUserProfilePicture($websoccer,$matchinfo['home_user_picture'],$matchinfo['home_user_email']);
			$matchinfo['guest_user_picture']=UsersDataService::getUserProfilePicture($websoccer,$matchinfo['guest_user_picture'],$matchinfo['guest_user_email']);
			$matches[]=$matchinfo;}
		return$matches;}
	static function _getFromPart($websoccer){
		$tablePrefix=Config('db_prefix');
		$fromTable=$tablePrefix.'_spiel AS M INNER JOIN '.$tablePrefix.'_verein AS HOME ON M.home_verein=HOME.id INNER JOIN '.$tablePrefix.'_verein AS GUEST ON M.gast_verein=GUEST.id LEFT JOIN '.$tablePrefix.'_user AS HOMEUSER ON M.home_user_id=HOMEUSER.id LEFT JOIN '.$tablePrefix.'_user AS GUESTUSER ON M.gast_user_id=GUESTUSER.id';
		return$fromTable;}
	static function _convertLeagueType($dbValue){
		switch($dbValue){
			case 'Ligaspiel': return 'league';
			case 'Pokalspiel': return 'cup';
			case 'Freundschaft': return 'friendly';}}}

class YouthMatchesDataService {
	static function getYouthMatchinfoById($websoccer,$db,$i18n,$matchId){
		$columns='M.*, HOME.name AS home_team_name, GUEST.name AS guest_team_name';
		$fromTable=Config('db_prefix').'_youthmatch AS M INNER JOIN '.Config('db_prefix'). '_verein AS HOME ON HOME.id=M.home_team_id INNER JOIN '.Config('db_prefix'). '_verein AS GUEST ON GUEST.id=M.guest_team_id';
		$result=$db->querySelect($columns,$fromTable,'M.id=%d',$matchId);
		$match=$result->fetch_array();
		if(!$match)throw new Exception(Message('error_page_not_found'));
		return$match;}
	static function countMatchesOfTeamOnSameDay($websoccer,$db,$teamId,$timestamp){
		$fromTable=Config('db_prefix').'_youthmatch';
		$dateObj=new DateTime();
		$dateObj->setTimestamp($timestamp);
		$dateObj->setTime(0, 0, 0);
		$minTimeBoundary=$dateObj->getTimestamp();
		$dateObj->setTime(23, 59, 59);
		$maxTimeBoundary=$dateObj->getTimestamp();
		$result=$db->querySelect('COUNT(*)AS hits',$fromTable,'(home_team_id=%d OR guest_team_id=%d)AND matchdate BETWEEN %d AND %d',array($teamId,$teamId,$minTimeBoundary,$maxTimeBoundary));
		$rows=$result->fetch_array();
		if($rows)return$rows['hits'];
		return 0;}
	static function countMatchesOfTeam($websoccer,$db,$teamId){
		$fromTable=Config('db_prefix').'_youthmatch';
		$result=$db->querySelect('COUNT(*)AS hits',$fromTable,'(home_team_id=%d OR guest_team_id=%d)',array($teamId,$teamId));
		$rows=$result->fetch_array();
		if($rows)return$rows['hits'];
		return 0;}
	static function getMatchesOfTeam($websoccer,$db,$teamId,$startIndex,$entries_per_page){
		$tablePrefix=Config('db_prefix');
		$fromTable=$tablePrefix.'_youthmatch AS M INNER JOIN '.$tablePrefix.'_verein AS HOME ON M.home_team_id=HOME.id INNER JOIN '.$tablePrefix.'_verein AS GUEST ON M.guest_team_id=GUEST.id LEFT JOIN '.$tablePrefix .
			'_user AS HOMEUSER ON HOME.user_id=HOMEUSER.id LEFT JOIN '.$tablePrefix.'_user AS GUESTUSER ON GUEST.user_id=GUESTUSER.id';
		$columns['M.id']='match_id';
		$columns['HOME.name']='home_team';
		$columns['HOME.bild']='home_team_picture';
		$columns['HOME.id']='home_id';
		$columns['HOMEUSER.id']='home_user_id';
		$columns['HOMEUSER.nick']='home_user_nick';
		$columns['HOMEUSER.email']='home_user_email';
		$columns['HOMEUSER.picture']='home_user_picture';
		$columns['GUEST.name']='guest_team';
		$columns['GUEST.bild']='guest_team_picture';
		$columns['GUEST.id']='guest_id';
		$columns['GUESTUSER.id']='guest_user_id';
		$columns['GUESTUSER.nick']='guest_user_nick';
		$columns['GUESTUSER.email']='guest_user_email';
		$columns['GUESTUSER.picture']='guest_user_picture';
		$columns['M.home_goals']='home_goals';
		$columns['M.guest_goals']='guest_goals';
		$columns['M.simulated']='simulated';
		$columns['M.matchdate']='date';
		$matches=[];
		$limit=$startIndex.','.$entries_per_page;
		$result=$db->querySelect($columns,$fromTable,'(home_team_id=%d OR guest_team_id=%d)ORDER BY M.matchdate DESC',array($teamId,$teamId),$limit);
		while($matchinfo=$result->fetch_array()){
			$matchinfo['home_user_picture']=UsersDataService::getUserProfilePicture($websoccer,$matchinfo['home_user_picture'],$matchinfo['home_user_email']);
			$matchinfo['guest_user_picture']=UsersDataService::getUserProfilePicture($websoccer,$matchinfo['guest_user_picture'],$matchinfo['guest_user_email']);
			$matches[]=$matchinfo;}
		return$matches;}
	static function createMatchReportItem($websoccer,$db,$matchId,$minute,$messageKey,$messageData=null,$isHomeTeamWithBall=FALSE){
		$messageDataStr='';
		if(is_array($messageData))$messageDataStr=json_encode($messageData);
		$columns=array('match_id'=>$matchId,'minute'=>$minute,'message_key'=>$messageKey,'message_data'=>$messageDataStr,'home_on_ball'=>($isHomeTeamWithBall)? '1' : '0');
		$db->queryInsert($columns,Config('db_prefix').'_youthmatch_reportitem');}
	static function getMatchReportItems($websoccer,$db,$i18n,$matchId){
		$result=$db->querySelect('*',Config('db_prefix').'_youthmatch_reportitem','match_id=%d ORDER BY minute ASC',$matchId);
		$items=[];
		while($item=$result->fetch_array()){
			$message=Message($item['message_key']);
			if(strlen($item['message_data'])){
				$messageData=json_decode($item['message_data'],true);
				if($messageData){
					foreach($messageData as$placeholderName=>$placeholderValue)$message=str_replace('{'.$placeholderName.'}',escapeOutput($placeholderValue, ENT_COMPAT,'UTF-8'),$message);}}
			$items[]=array('minute'=>$item['minute'],'active_home'=>$item['home_on_ball'],'message_key'=>$item['message_key'],'message'=>$message);}
		return$items;}}

class PlayersDataService {
	static function getPlayersOfTeamByPosition($websoccer,$db,$clubId,$positionSort='ASC',$considerBlocksForCups=FALSE,$considerBlocks=TRUE){
		$columns=array('id'=>'id', 'vorname'=>'firstname', 'nachname'=>'lastname', 'kunstname'=>'pseudonym', 'verletzt'=>'matches_injured', 'position'=>'position', 'position_main'=>'position_main', 'position_second'=>'position_second',
			'w_staerke'=>'strength', 'w_technik'=>'strength_technique', 'w_kondition'=>'strength_stamina', 'w_frische'=>'strength_freshness', 'w_zufriedenheit'=>'strength_satisfaction', 'transfermarkt'=>'transfermarket', 'nation'=>'player_nationality',
			'picture'=>'picture', 'sa_tore'=>'st_goals', 'sa_spiele'=>'st_matches', 'sa_karten_gelb'=>'st_cards_yellow', 'sa_karten_gelb_rot'=>'st_cards_yellow_red', 'sa_karten_rot'=>'st_cards_red', 'marktwert'=>'marketvalue');
		if(Config('players_aging')=='birthday')$ageColumn='TIMESTAMPDIFF(YEAR,geburtstag,CURDATE())';
		else $ageColumn='age';
		$columns[$ageColumn]='age';
		if($considerBlocksForCups)$columns['gesperrt_cups']='matches_blocked';
		elseif($considerBlocks)$columns['gesperrt']='matches_blocked';
		$fromTable=Config('db_prefix').'_spieler';
		$whereCondition='status=1 AND verein_id=%d ORDER BY position '.$positionSort.', position_main ASC, nachname ASC, vorname ASC';
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$clubId, 50);
		$players=[];
		while($player=$result->fetch_array()){
			$player['position']=self::_convertPosition($player['position']);
			$player['player_nationality_filename']=self::getFlagFilename($player['player_nationality']);
			$player['marketvalue']=self::getMarketValue($websoccer,$player, '');
			$players[$player['position']][]=$player;}
		return$players;}
	static function getPlayersOfTeamById($websoccer,$db,$clubId,$nationalteam=FALSE,$considerBlocksForCups=FALSE,$considerBlocks=TRUE){
		$columns=array('id'=>'id', 'vorname'=>'firstname', 'nachname'=>'lastname', 'kunstname'=>'pseudonym', 'verletzt'=>'matches_injured', 'position'=>'position', 'position_main'=>'position_main', 'position_second'=>'position_second',
			'w_staerke'=>'strength', 'w_technik'=>'strength_technic', 'w_kondition'=>'strength_stamina', 'w_frische'=>'strength_freshness', 'w_zufriedenheit'=>'strength_satisfaction', 'transfermarkt'=>'transfermarket', 'nation'=>'player_nationality',
			'picture'=>'picture', 'sa_tore'=>'st_goals', 'sa_spiele'=>'st_matches', 'sa_karten_gelb'=>'st_cards_yellow', 'sa_karten_gelb_rot'=>'st_cards_yellow_red', 'sa_karten_rot'=>'st_cards_red', 'marktwert'=>'marketvalue',
			'vertrag_spiele'=>'contract_matches', 'vertrag_gehalt'=>'contract_salary', 'unsellable'=>'unsellable', 'lending_matches'=>'lending_matches', 'lending_fee'=>'lending_fee','lending_owner_id'=>'lending_owner_id','transfermarkt'=>'transfermarket');
		if(Config('players_aging')=='birthday')$ageColumn='TIMESTAMPDIFF(YEAR,geburtstag,CURDATE())';
		else $ageColumn='age';
		$columns[$ageColumn]='age';
		if(!$nationalteam){
			if($considerBlocksForCups)$columns['gesperrt_cups']='matches_blocked';
			elseif($considerBlocks)$columns['gesperrt']='matches_blocked';
			else $columns['\'0\'']='matches_blocked';
			$fromTable=Config('db_prefix').'_spieler';
			$whereCondition='status=1 AND verein_id=%d';}
		else{
			$columns['gesperrt_nationalteam']='matches_blocked';
			$fromTable=Config('db_prefix').'_spieler AS P INNER JOIN '.Config('db_prefix').'_nationalplayer AS NP ON NP.player_id=P.id';
			$whereCondition='status=1 AND NP.team_id=%d';}
		$whereCondition.=' ORDER BY position ASC, position_main ASC, nachname ASC, vorname ASC';
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$clubId, 50);
		$players=[];
		while($player=$result->fetch_array()){
			$player['position']=self::_convertPosition($player['position']);
			$players[$player['id']]=$player;}
		return$players;}
	static function getPlayersOnTransferList($websoccer,$db,$startIndex,$entries_per_page,$positionFilter=null){
		$columns['P.id']='id';
		$columns['P.vorname']='firstname';
		$columns['P.nachname']='lastname';
		$columns['P.kunstname']='pseudonym';
		$columns['P.position']='position';
		$columns['P.position_main']='position_main';
		$columns['P.vertrag_gehalt']='contract_salary';
		$columns['P.vertrag_torpraemie']='contract_goalbonus';
		$columns['P.w_staerke']='strength';
		$columns['P.w_technik']='strength_technique';
		$columns['P.w_kondition']='strength_stamina';
		$columns['P.w_frische']='strength_freshness';
		$columns['P.w_zufriedenheit']='strength_satisfaction';
		$columns['P.transfermarkt']='transfermarket';
		$columns['P.marktwert']='marketvalue';
		$columns['P.transfer_start']='transfer_start';
		$columns['P.transfer_ende']='transfer_deadline';
		$columns['P.transfer_mindestgebot']='min_bid';
		$columns['C.id']='team_id';
		$columns['C.name']='team_name';
		$fromTable=Config('db_prefix').'_spieler AS P LEFT JOIN '.Config('db_prefix').'_verein AS C ON C.id=P.verein_id';
		$whereCondition='P.status=1 AND P.transfermarkt=1 AND P.transfer_ende>%d';
		$parameters[]=Timestamp();
		if($positionFilter!=null){
			$whereCondition.=' AND P.position=\'%s\'';
			$parameters[]=$positionFilter;}
		$whereCondition.=' ORDER BY P.transfer_ende ASC, P.nachname ASC, P.vorname ASC';
		$limit=$startIndex.','.$entries_per_page;
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters,$limit);
		$players=[];
		while($player=$result->fetch_array()){
			$player['position']=self::_convertPosition($player['position']);
			$player['highestbid']=TransfermarketDataService::getHighestBidForPlayer($websoccer,$db,$player['id'],$player['transfer_start'],$player['transfer_deadline']);
			$players[]=$player;}
		return$players;}
	static function countPlayersOnTransferList($websoccer,$db,$positionFilter=null){
		$columns='COUNT(*)AS hits';
		$fromTable=Config('db_prefix').'_spieler AS P';
		$whereCondition='P.status=1 AND P.transfermarkt=1 AND P.transfer_ende>%d';
		$parameters[]=Timestamp();
		if($positionFilter!=null){
			$whereCondition.=' AND P.position=\'%s\'';
			$parameters[]=$positionFilter;}
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters);
		$players=$result->fetch_array();
		if(isset($players['hits']))return$players['hits'];
		return 0;}
	static function getPlayerById($websoccer,$db,$playerId){
		$columns['P.id']='player_id';
		$columns['P.vorname']='player_firstname';
		$columns['P.nachname']='player_lastname';
		$columns['P.kunstname']='player_pseudonym';
		$columns['P.position']='player_position';
		$columns['P.position_main']='player_position_main';
		$columns['P.position_second']='player_position_second';
		$columns['P.geburtstag']='player_birthday';
		$columns['P.nation']='player_nationality';
		$columns['P.picture']='player_picture';
		if(Config('players_aging')=='birthday')$ageColumn='TIMESTAMPDIFF(YEAR,P.geburtstag,CURDATE())';
		else $ageColumn='P.age';
		$columns[$ageColumn]='player_age';
		$columns['P.verletzt']='player_matches_injured';
		$columns['P.gesperrt']='player_matches_blocked';
		$columns['P.gesperrt_cups']='player_matches_blocked_cups';
		$columns['P.gesperrt_nationalteam']='player_matches_blocked_nationalteam';
		$columns['P.vertrag_gehalt']='player_contract_salary';
		$columns['P.vertrag_spiele']='player_contract_matches';
		$columns['P.vertrag_torpraemie']='player_contract_goalbonus';
		$columns['P.w_staerke']='player_strength';
		$columns['P.w_technik']='player_strength_technique';
		$columns['P.w_kondition']='player_strength_stamina';
		$columns['P.w_frische']='player_strength_freshness';
		$columns['P.w_zufriedenheit']='player_strength_satisfaction';
		$columns['P.sa_tore']='player_season_goals';
		$columns['P.sa_assists']='player_season_assists';
		$columns['P.sa_spiele']='player_season_matches';
		$columns['P.sa_karten_gelb']='player_season_yellow';
		$columns['P.sa_karten_gelb_rot']='player_season_yellow_red';
		$columns['P.sa_karten_rot']='player_season_red';
		$columns['P.st_tore']='player_total_goals';
		$columns['P.st_assists']='player_total_assists';
		$columns['P.st_spiele']='player_total_matches';
		$columns['P.st_karten_gelb']='player_total_yellow';
		$columns['P.st_karten_gelb_rot']='player_total_yellow_red';
		$columns['P.st_karten_rot']='player_total_red';
		$columns['P.transfermarkt']='player_transfermarket';
		$columns['P.marktwert']='player_marketvalue';
		$columns['P.transfer_start']='transfer_start';
		$columns['P.transfer_ende']='transfer_end';
		$columns['P.transfer_mindestgebot']='transfer_min_bid';
		$columns['P.history']='player_history';
		$columns['P.unsellable']='player_unsellable';
		$columns['P.lending_owner_id']='lending_owner_id';
		$columns['L.name']='lending_owner_name';
		$columns['P.lending_fee']='lending_fee';
		$columns['P.lending_matches']='lending_matches';
		$columns['C.id']='team_id';
		$columns['C.name']='team_name';
		$columns['C.finanz_budget']='team_budget';
		$columns['C.user_id']='team_user_id';
		$columns['(SELECT CONCAT(AVG(S.note), \';\', SUM(S.assists))FROM '.Config('db_prefix').'_spiel_berechnung AS S WHERE S.spieler_id=P.id AND S.minuten_gespielt>0 AND S.note>0)']='matches_info';
		$fromTable=Config('db_prefix').'_spieler AS P LEFT JOIN '.Config('db_prefix').'_verein AS C ON C.id=P.verein_id LEFT JOIN '.Config('db_prefix').'_verein AS L ON L.id=P.lending_owner_id';
		$whereCondition='P.status=1 AND P.id=%d';
		$players=$db->queryCachedSelect($columns,$fromTable,$whereCondition,$playerId, 1);
		if(count($players)){
			$player=$players[0];
			$player['player_position']=self::_convertPosition($player['player_position']);
			$player['player_marketvalue']=self::getMarketValue($websoccer,$player);
			$player['player_nationality_filename']=self::getFlagFilename($player['player_nationality']);
			$matchesInfo=explode(';',$player['matches_info']);
			$player['player_avg_grade']=round((int)$matchesInfo[0],2);
			if(isset($matchesInfo[1]))$player['player_assists']=$matchesInfo[1];
			else $player['player_assists']=0;}
		else $player=[];
		return$player;}
	static function getTopStrikers($websoccer,$db,$limit=20,$leagueId=null){
		$parameters=[];
		$columns['P.id']='id';
		$columns['P.vorname']='firstname';
		$columns['P.nachname']='lastname';
		$columns['P.kunstname']='pseudonym';
		$columns['P.sa_tore']='goals';
		$columns['P.sa_spiele']='matches';
		$columns['P.transfermarkt']='transfermarket';
		$columns['C.id']='team_id';
		$columns['C.name']='team_name';
		$fromTable=Config('db_prefix').'_spieler AS P LEFT JOIN '.Config('db_prefix').'_verein AS C ON C.id=P.verein_id';
		$whereCondition='P.status=1 AND P.sa_tore>0';
		if($leagueId!=null){
			$whereCondition.=' AND liga_id=%d';
			$parameters[]=(int)$leagueId;}
		$whereCondition.=' ORDER BY P.sa_tore DESC, P.sa_spiele ASC';
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters,$limit);
		$players=[];
		while($player=$result->fetch_array())$players[]=$player;
		return$players;}
	static function getTopScorers($websoccer,$db,$limit=20,$leagueId=null){
		$parameters=[];
		$columns['P.id']='id';
		$columns['P.vorname']='firstname';
		$columns['P.nachname']='lastname';
		$columns['P.kunstname']='pseudonym';
		$columns['P.sa_tore']='goals';
		$columns['P.sa_assists']='assists';
		$columns['P.sa_spiele']='matches';
		$columns['(P.sa_tore + P.sa_assists)']='score';
		$columns['P.transfermarkt']='transfermarket';
		$columns['C.id']='team_id';
		$columns['C.name']='team_name';
		$fromTable=Config('db_prefix').'_spieler AS P LEFT JOIN '.Config('db_prefix').'_verein AS C ON C.id=P.verein_id';
		$whereCondition='P.status=\'1\' AND(P.sa_tore + P.sa_assists)>0';
		if($leagueId!=null){
			$whereCondition.=' AND liga_id=%d';
			$parameters[]=(int)$leagueId;}
		$whereCondition.=' ORDER BY score DESC, P.sa_assists DESC, P.sa_tore DESC, P.sa_spiele ASC, P.id ASC';
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$parameters,$limit);
		$players=[];
		while($player=$result->fetch_array())$players[]=$player;
		return$players;}
	static function findPlayers($websoccer,$db,$firstName,$lastName,$clubName,$position,$strengthMax,$lendableOnly,$startIndex,$entries_per_page){
		$columns['P.id']='id';
		$columns['P.vorname']='firstname';
		$columns['P.nachname']='lastname';
		$columns['P.kunstname']='pseudonym';
		$columns['P.position']='position';
		$columns['P.position_main']='position_main';
		$columns['P.position_second']='position_second';
		$columns['P.transfermarkt']='transfermarket';
		$columns['P.unsellable']='unsellable';
		$columns['P.w_staerke']='strength';
		$columns['P.w_technik']='strength_technique';
		$columns['P.w_kondition']='strength_stamina';
		$columns['P.w_frische']='strength_freshness';
		$columns['P.w_zufriedenheit']='strength_satisfaction';
		$columns['P.vertrag_gehalt']='contract_salary';
		$columns['P.vertrag_spiele']='contract_matches';
		$columns['P.lending_owner_id']='lending_owner_id';
		$columns['P.lending_fee']='lending_fee';
		$columns['P.lending_matches']='lending_matches';
		$columns['C.id']='team_id';
		$columns['C.name']='team_name';
		$limit=$startIndex.','.$entries_per_page;
		$result=self::executeFindQuery($websoccer,$db,$columns,$limit,$firstName,$lastName,$clubName,$position,$strengthMax,$lendableOnly);
		$players=[];
		while($player=$result->fetch_array()){
			$player['position']=self::_convertPosition($player['position']);
			$players[]=$player;}
		return$players;}
	static function findPlayersCount($websoccer,$db,$firstName,$lastName,$clubName,$position,$strengthMax,$lendableOnly){
		$columns='COUNT(*)AS hits';
		$result=self::executeFindQuery($websoccer,$db,$columns, 1,$firstName,$lastName,$clubName,$position,$strengthMax,$lendableOnly);
		$players=$result->fetch_array();
		if(isset($players['hits']))return$players['hits'];
		return 0;}
	static function executeFindQuery($websoccer,$db,$columns,$limit,$firstName,$lastName,$clubName,$position,$strengthMax,$lendableOnly){
		$whereCondition='P.status=1';
		$parameters=[];
		if($firstName!=null){
			$firstName=ucfirst($firstName);
			$whereCondition.=' AND P.vorname LIKE \'%s%%\'';
			$parameters[]=$firstName;}
		if($lastName!=null){
			$lastName=ucfirst($lastName);
			$whereCondition.=' AND(P.nachname LIKE \'%s%%\' OR P.kunstname LIKE \'%s%%\')';
			$parameters[]=$lastName;
			$parameters[]=$lastName;}
		if($clubName!=null){
			$whereCondition.=' AND C.name=\'%s\'';
			$parameters[]=$clubName;}
		if($position!=null){
			$whereCondition.=' AND P.position=\'%s\'';
			$parameters[]=$position;}
		if($strengthMax!=null &&Config('hide_strength_attributes')!=='1'){
			$strengthMinValue=$strengthMax - 20;
			$strengthMaxValue=$strengthMax;
			$whereCondition.=' AND P.w_staerke>%d AND P.w_staerke <= %d';
			$parameters[]=$strengthMinValue;
			$parameters[]=$strengthMaxValue;}
		if($lendableOnly)$whereCondition.=' AND P.lending_fee>0 AND(P.lending_owner_id IS NULL OR P.lending_owner_id=0)';
		$fromTable=Config('db_prefix').'_spieler AS P LEFT JOIN '.Config('db_prefix').'_verein AS C ON C.id=P.verein_id';
		return$db->querySelect($columns,$fromTable,$whereCondition,$parameters,$limit);}
	static function _convertPosition($dbPosition){
		switch($dbPosition){
			case 'Torwart': return 'goaly';
			case 'Abwehr': return 'defense';
			case 'Mittelfeld': return 'midfield';
			default: return 'striker';}}
	static function getMarketValue($websoccer,$player,$columnPrefix='player_'){
		if(!Config('transfermarket_computed_marketvalue'))return$player[$columnPrefix.'marketvalue'];
		$totalStrength=Config('sim_weight_strength')* $player[$columnPrefix.'strength'];
		$totalStrength += Config('sim_weight_strengthTech')* $player[$columnPrefix.'strength_technique'];
		$totalStrength += Config('sim_weight_strengthStamina')* $player[$columnPrefix.'strength_stamina'];
		$totalStrength += Config('sim_weight_strengthFreshness')* $player[$columnPrefix.'strength_freshness'];
		$totalStrength += Config('sim_weight_strengthSatisfaction')* $player[$columnPrefix.'strength_satisfaction'];
		$totalStrength /= Config('sim_weight_strength')+Config('sim_weight_strengthTech')+Config('sim_weight_strengthStamina')+Config('sim_weight_strengthFreshness')+Config('sim_weight_strengthSatisfaction');
		return$totalStrength *Config('transfermarket_value_per_strength');}
	static function getFlagFilename($nationality){
		if(!strlen($nationality))return$nationality;
		$filename=str_replace('??', 'Ae',$nationality);
		$filename=str_replace('??', 'Oe',$filename);
		$filename=str_replace('??', 'Ue',$filename);
		$filename=str_replace('??', 'ae',$filename);
		$filename=str_replace('??', 'oe',$filename);
		$filename=str_replace('??', 'ue',$filename);
		return$filename;}}

class YouthPlayersDataService {
	 static function getYouthPlayerById($websoccer,$db,$i18n,$playerId){$players=$db->queryCachedSelect('*',Config('db_prefix').'_youthplayer','id=%d',$playerId);if(!count($players))throw new Exception(Message('error_page_not_found'));return$players[0];}
	 static function getYouthPlayersOfTeam($websoccer,$db,$teamId){$players=$db->queryCachedSelect('*',Config('db_prefix').'_youthplayer','team_id=%d ORDER BY position ASC,lastname ASC,firstname ASC',$teamId);return$players;}
	 static function countYouthPlayersOfTeam($websoccer,$db,$teamId){$result=$db->querySelect('COUNT(*)AS hits',Config('db_prefix').'_youthplayer','team_id=%d',$teamId);$players=$result->fetch_array();if($players)return$players['hits'];return 0;}
	 static function computeSalarySumOfYouthPlayersOfTeam($websoccer,$db,$teamId){$result=$db->querySelect('SUM(strength)AS strengthsum',Config('db_prefix').'_youthplayer','team_id=%d',$teamId);$players=$result->fetch_array();if($players)return$players['strengthsum']*
							Config('youth_salary_per_strength');return 0;}
	 static function getYouthPlayersOfTeamByPosition($websoccer,$db,$clubId,$positionSort='ASC'){$result=$db->querySelect('*',Config('db_prefix').'_youthplayer','team_id=%d ORDER BY position '.$positionSort.',lastname ASC,firstname ASC',$clubId,50);$players=[];
							while($player=$result->fetch_array()){$player['position']=PlayersDataService::_convertPosition($player['position']);$player['player_nationality']=$player['nation'];$player['player_nationality_filename']=PlayersDataService::getFlagFilename(
							$player['nation']);$players[$player['position']][]=$player;}return$players;}
	 static function countTransferableYouthPlayers($websoccer,$db,$positionFilter=NULL){$parameters='';$whereCondition='transfer_fee>0';if($positionFilter!=NULL){$whereCondition.=' AND position="%s"';$parameters=$positionFilter;}$result=$db->querySelect(
	 						'COUNT(*)AS hits',Config('db_prefix').'_youthplayer',$whereCondition,$parameters);$players=$result->fetch_array();if($players)return$players['hits'];return 0;}
	 static function getTransferableYouthPlayers($websoccer,$db,$startIndex,$entries_per_page,$positionFilter=NULL){$parameters='';$whereCondition='P.transfer_fee>0';if($positionFilter!=NULL){$whereCondition.=' AND P.position="%s"';$parameters=$positionFilter;}
							$whereCondition.=' ORDER BY P.strength DESC';$players=[];$result=$db->querySelect(['P.id'=>'player_id','P.firstname'=>'firstname','P.lastname'=>'lastname','P.position'=>'position','P.nation'=>'nation','P.transfer_fee'=>'transfer_fee',
							'P.age'=>'age','P.strength'=>'strength','P.st_matches'=>'st_matches','P.st_goals'=>'st_goals','P.st_assists'=>'st_assists','P.st_cards_yellow'=>'st_cards_yellow','P.st_cards_yellow_red'=>'st_cards_yellow_red','P.st_cards_red'=>'st_cards_red',
							'P.team_id'=>'team_id','C.name'=>'team_name','C.bild'=>'team_picture','C.user_id'=>'user_id','U.nick'=>'user_nick','U.email'=>'user_email','U.picture'=>'user_picture'],Config('db_prefix').'_youthplayer AS P INNER JOIN '.Config('db_prefix').
							'_verein AS C ON C.id=P.team_id LEFT JOIN '.Config('db_prefix').'_user AS U ON U.id=C.user_id',$whereCondition,$parameters,$startIndex.','.$entries_per_page);while($player=$result->fetch_array()){$player['user_picture']=
							UsersDataService::getUserProfilePicture($websoccer,$player['user_picture'],$player['user_email'],20);$player['nation_flagfile']=PlayersDataService::getFlagFilename($player['nation']);$players[]=$player;}return$players;}
	 static function getScouts($websoccer,$db,$sortColumns='expertise DESC,name ASC'){$result=$db->querySelect('*',Config('db_prefix').'_youthscout','1=1 ORDER BY '.$sortColumns);$scouts=[];while($scout=$result->fetch_array())$scouts[]=$scout;return$scouts;}
	 static function getScoutById($websoccer,$db,$i18n,$scoutId){$result=$db->querySelect('*',Config('db_prefix').'_youthscout','id=%d',$scoutId);$scout=$result->fetch_array();if(!$scout)throw new Exception(Message('youthteam_scouting_err_invalidscout'));return$scout;}
	 static function getLastScoutingExecutionTime($websoccer,$db,$teamId){$result=$db->querySelect('scouting_last_execution',Config('db_prefix').'_verein','id=%d',$teamId);$scouted=$result->fetch_array();if(!$scouted)return 0;return$scouted['scouting_last_execution'];}
	 static function getPossibleScoutingCountries(){$iterator=new DirectoryIterator($_SERVER['DOCUMENT_ROOT'].'/admin/config/names/');$countries=[];while($iterator->valid()){if($iterator->isDir()&&!$iterator->isDot())$countries[]=$iterator->getFilename();
	 						$iterator->next();}return$countries;}
	 static function countMatchRequests($websoccer,$db){$result=$db->querySelect('COUNT(*)AS hits',Config('db_prefix').'_youthmatch_request','1=1');$requests=$result->fetch_array();if($requests)return$requests['hits'];return 0;}
	 static function getMatchRequests($websoccer,$db,$startIndex,$entries_per_page){$requests=[];$result=$db->querySelect(['R.id'=>'request_id','R.matchdate'=>'matchdate','R.reward'=>'reward','C.name'=>'team_name','C.id'=>'team_id','U.id'=>'user_id','U.nick'=>
	 						'user_nick','U.email'=>'user_email','U.picture'=>'user_picture'],Config('db_prefix').'_youthmatch_request AS R INNER JOIN '.Config('db_prefix').'_verein AS C ON C.id=R.team_id INNER JOIN '.Config('db_prefix').'_user AS U ON U.id=C.user_id',
	 						'1=1 ORDER BY R.matchdate ASC',null,$startIndex.','.$entries_per_page);while($request=$result->fetch_array()){$request['user_picture']=UsersDataService::getUserProfilePicture($websoccer,$request['user_picture'],$request['user_email']);
							$requests[]=$request;}return$requests;}
	 static function deleteInvalidOpenMatchRequests($websoccer,$db){$timeBoundary=Timestamp()+Config('youth_matchrequest_accept_hours_in_advance');$db->queryDelete(Config('db_prefix').'_youthmatch_request','matchdate<=%d',$timeBoundary);}}

// Static class functions separated out as procedural functions and still available as static function via call to procedural function for old code compatibility.
class ConfigFileWriter{private static$_instance,$_settings;
	 static function getInstance($settings){if(self::$_instance==NULL)self::$_instance=new ConfigFileWriter($settings);return self::$_instance;}
    		function saveSettings($newSettings){foreach($newSettings as$settingId=>$settingValue)$this->_settings[$settingId]=$settingValue;$this->_writeToFile();}
    		function _writeToFile(){$content="<?php".PHP_EOL;foreach($this->_settings as$id=>$value)$content.="\$conf[\"".$id."\"]=\"".addslashes($value)."\";".PHP_EOL;$content.="?>";$fw=new FileWriter($_SERVER['DOCUMENT_ROOT'].'/generated/config.inc.php');
    						$fw->writeLine($content);$fw->close();}
    		function __construct($settings){ $this->_settings=$settings;}}
class LoginCheck{
	  		function __construct($portable_hashes){$db=DbConnection::getInstance();$this->_db=$db;$this->itoa64='./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';$this->portable_hashes=$portable_hashes;$this->random_state=microtime();
							if(function_exists('getmypid'))$this->random_state.=getmypid();}
			function encode64($input,$count){$output='';$i=0;do{$value=ord($input[++$i]);$output.=$this->itoa64[$value&0x3f];if($i<$count)$value|=ord($input[$i])<<8;$output.=$this->itoa64[($value>>6)&0x3f];if(++$i>=$count)break;if($i<$count)$value|=ord($input[$i])<<16;
							$output.=$this->itoa64[($value>>12)&0x3f];if(++$i>=$count)break;$output.=$this->itoa64[($value>>18)&0x3f];}while($i<$count);return$output;}
			function crypt_private($password,$setting){$output='*0';if(substr($setting,0,2)===$output)$output='*1';$id=substr($setting,0,3);if($id!=='$P$'&&$id!=='$H$')return$output;$count_log2=strpos($this->itoa64,$setting[3]);if($count_log2<7||$count_log2>30)
							return$output;$count=1<<$count_log2;$salt=substr($setting,4,8);if(strlen($salt)!==8)return$output;$hash=hash('sha256',$salt.$password,TRUE);do{$hash=hash('sha256',$hash.$password,TRUE);}while(--$count);$output=substr($setting,0,12);
							$output.=$this->encode64($hash,16);return$output;}
			function CheckPassword($password,$stored_hash){$hash=$this->crypt_private($password,$stored_hash);if($hash[0]==='*')$hash=crypt($password,$stored_hash);return$hash===$stored_hash;}}
			function BrowserLanguage($allowed_languages,$default_language,$lang_variable=null,$strict_mode=true){if($lang_variable===null)$lang_variable=$_SERVER['HTTP_ACCEPT_LANGUAGE'];if(empty($lang_variable))return$default_language;$accepted_languages=
							preg_split('/,\s*/',$lang_variable);$current_lang=$default_language;$current_q=0;foreach($accepted_languages as$accepted_language){$res=preg_match('/^([a-z]{1,8}(?:-[a-z]{1,8})*)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i',
							$accepted_language,$matches);if(!$res)continue;$lang_code=explode('-',$matches[1]);if(isset($matches[2]))$lang_quality=(float)$matches[2];else$lang_quality=1.0;while(count($lang_code)){if(in_array(strtolower(join('-',$lang_code))
							,$allowed_languages)){if($lang_quality>$current_q){$current_lang=strtolower(join('-',$lang_code));$current_q=$lang_quality;break;}}if($strict_mode)break;array_pop($lang_code);}}return$current_lang;}
class JoomlaUserLoginMethod extends LoginCheck{
			function authenticateWithEmail($email,$password){return$this->_authenticate('LOWER(email)=\'%s\'',strtolower($email),$password);}
			function authenticateWithUsername($nick,$password){return$this->_authenticate('username=\'%s\'',$nick,$password);}
			function _authenticate($queryWhereCondition,$loginStr,$password){$result=$this->_db->querySelect('username,email,password',Config('joomlalogin_tableprefix').'users','activation=0 AND '.$queryWhereCondition,$loginStr);$wpUser=$result->fetch_array();
							if(!$wpUser)return FALSE;self::CheckPassword($password,$wpUser['password']);$userEmail=strtolower($wpUser['email']);$userId=UsersDataService::getUserIdByEmail($this->_websoccer,$this->_db,$userEmail);if($userId)return$userId;
							return UsersDataService::createLocalUser($this->_websoccer,$this->_db,$wpUser['users'],$userEmail);}}
class WordpressUserLoginMethod extends LoginCheck{
			function authenticateWithEmail($email,$password){ return$this->_authenticate('LOWER(user_email)=\'%s\'',strtolower($email),$password);}
			function authenticateWithUsername($nick,$password){ return$this->_authenticate('user_login=\'%s\'',$nick,$password);}
			function _authenticate($queryWhereCondition,$loginStr,$password){$result=$this->_db->querySelect('user_login,user_email,user_pass',Config('wordpresslogin_tableprefix').'users','user_status=0 AND '.$queryWhereCondition,$loginStr);
							$wpUser=$result->fetch_array();if(!$wpUser)return FALSE;self::CheckPassword($password,$wpUser['user_pass']);$userEmail=strtolower($wpUser['user_email']);$userId=UsersDataService::getUserIdByEmail($this->_websoccer,$this->_db,$userEmail);
							if($userId)return$userId;return UsersDataService::createLocalUser($this->_websoccer,$this->_db,$wpUser['user_login'],$userEmail);}}
class WebSoccer{private static$_instance;private$_skin,$_pageId,$_templateEngine,$_frontMessages,$_user,$_contextParameters;
			static function getInstance(){if(self::$_instance==NULL)self::$_instance=new WebSoccer();return self::$_instance;}
    		function getUser(){if($this->_user==null)$this->_user=new User();return$this->_user;}
			function getConfig($name){return Config($name);}
			function getAction($id){global$action;if(!isset($action[$id]))throw new Exception('Action not found: '.$id);return$action[$id];}
			function getSkin(){if($this->_skin==NULL){$skinName=Config('skin');if(class_exists($skinName))$this->_skin=new$skinName($this);else throw new Exception('Configured skin \''.$skinName.'\' does not exist. Check the system settings.');}return$this->_skin;}
			function getPageId(){return$this->_pageId;}
			function setPageId($pageId){$this->_pageId=$pageId;}
			function getTemplateEngine($i18n,ViewHandler$viewHandler=null){if($this->_templateEngine==NULL)$this->_templateEngine=new TemplateEngine($this,$i18n,$viewHandler);return$this->_templateEngine;}
			function getRequestParameter($name){if(isset($_REQUEST[$name])){$value=trim($_REQUEST[$name]);if(strlen($value))return$value;}return NULL;}
			function getInternalUrl($pageId=null,$queryString='',$fullUrl=FALSE){if($pageId==null)$pageId=PageId();if(strlen((string)$queryString))$queryString='&'.$queryString;if($fullUrl){$url=Config('homepage').Config('context_root');
							if($pageId!='home'||strlen((string)$queryString))$url.='/?page='.$pageId.$queryString;}else$url=Config('context_root').'/?page='.$pageId.$queryString;return$url;}
			function getInternalActionUrl($actionId,$queryString='',$pageId=null,$fullUrl=FALSE){if($pageId==null)$pageId=Request('page');if(strlen($queryString))$queryString='&'.$queryString;$url=Config('context_root').'/?page='.$pageId.$queryString
							.'&action='.$actionId;if($fullUrl)$url=Config('homepage').$url;return$url;}
			function getFormattedDate($timestamp=null){if($timestamp==null)$timestamp=Timestamp();return date(Config('date_format'),$timestamp);}
			function getFormattedDatetime($timestamp,$i18n=null){if($timestamp==null)$timestamp=Timestamp();if($i18n!=null){$dateWord=convertTimestampToWord($timestamp,$tTimestamp(),$i18n);if(strlen($dateWord))
							return$dateWord.', '.date(Config('time_format'),$timestamp);}return date(Config('datetime_format'),$timestamp);}
			function getNowAsTimestamp(){return time()+Config('time_offset');}
			function resetConfigCache(){$i18n=I18n::getInstance(Config('supported_languages'));$cacheBuilder=new ConfigCacheFileWriter($i18n->getSupportedLanguages());$cacheBuilder->buildConfigCache();}
			function addFrontMessage(FrontMessage $message){$this->_frontMessages[]=$message;}
			function getFrontMessages(){if($this->_frontMessages==null)$this->_frontMessages=[];return$this->_frontMessages;}
			function getContextParameters(){if($this->_contextParameters==null)$this->_contextParameters=[];return$this->_contextParameters;}
			function addContextParameter($name,$value){if($this->_contextParameters==null)$this->_contextParameters=[];$this->_contextParameters[$name]=$value;}}
class AccessDeniedException extends Exception{
			function __construct($message,$code=0){parent::__construct($message,$code);}}
class ConfigCacheFileWriter{private$_frontCacheFileWriter,$_adminCacheFileWriter,$_supportedLanguages,$_messagesFileWriters,$_adminMessagesFileWriters,$_entityMessagesFileWriters,$_settingsCacheFileWriter,$_eventsCacheFileWriter,$_newSettings;
			function __construct($supportedLanguages){$this->_frontCacheFileWriter=new FileWriter($_SERVER['DOCUMENT_ROOT'].'/cache/wsconfigfront.inc.php');$this->_adminCacheFileWriter=new FileWriter($_SERVER['DOCUMENT_ROOT'].'/cache/wsconfigadmin.inc.php');
							$this->_settingsCacheFileWriter=new FileWriter($_SERVER['DOCUMENT_ROOT'].'/cache/settingsconfig.inc.php');$this->_eventsCacheFileWriter=new FileWriter($_SERVER['DOCUMENT_ROOT'].'/cache/eventsconfig.inc.php');
							$this->_supportedLanguages=$supportedLanguages;$this->_messagesFileWriters=[];$this->_adminMessagesFileWriters=[];$this->_entityMessagesFileWriters=[];foreach($supportedLanguages as$language){$this->_messagesFileWriters[$language]=
							new FileWriter(sprintf($_SERVER['DOCUMENT_ROOT'].'/cache/messages_%s.inc.php',$language));$this->_adminMessagesFileWriters[$language]=new FileWriter(sprintf($_SERVER['DOCUMENT_ROOT'].'/cache/adminmessages_%s.inc.php',$language));
							$this->_entityMessagesFileWriters[$language]=new FileWriter(sprintf($_SERVER['DOCUMENT_ROOT'].'/cache/entitymessages_%s.inc.php',$language));}}
			function buildConfigCache(){$this->_writeFileStart($this->_frontCacheFileWriter);$this->_writeFileStart($this->_adminCacheFileWriter);$this->_writeFileStart($this->_settingsCacheFileWriter);$this->_writeFileStart($this->_eventsCacheFileWriter);
							foreach($this->_supportedLanguages as$language){$this->_writeMsgFileStart($this->_messagesFileWriters[$language]);$this->_writeMsgFileStart($this->_adminMessagesFileWriters[$language]);
							$this->_writeMsgFileStart($this->_entityMessagesFileWriters[$language]);}$this->_buildModulesConfig();$this->_writeFileEnd($this->_frontCacheFileWriter);$this->_writeFileEnd($this->_adminCacheFileWriter);
							$this->_writeFileEnd($this->_settingsCacheFileWriter);$this->_writeFileEnd($this->_eventsCacheFileWriter);foreach($this->_supportedLanguages as$language){$this->_writeMsgFileEnd($this->_messagesFileWriters[$language]);
							$this->_writeMsgFileEnd($this->_adminMessagesFileWriters[$language]);$this->_writeMsgFileEnd($this->_entityMessagesFileWriters[$language]);}if(is_array($this->_newSettings)&&count($this->_newSettings)){global $conf;
							$cf=ConfigFileWriter::getInstance($conf);$cf->saveSettings($this->_newSettings);}}
			function _writeFileStart($fileWriter){$fileWriter->writeLine('<?php');}
			function _writeMsgFileStart($fileWriter){$this->_writeFileStart($fileWriter);$fileWriter->writeLine('if(!isset($msg))$msg=[];');$fileWriter->writeLine('$msg=$msg+array(');}
			function _writeFileEnd($fileWriter){$fileWriter->writeLine('?>');}
			function _writeMsgFileEnd($fileWriter){$fileWriter->writeLine(');');$this->_writeFileEnd($fileWriter);}
			function _buildModulesConfig(){$modules=scandir($_SERVER['DOCUMENT_ROOT'].'/modules');foreach($modules as$module){if(is_dir($_SERVER['DOCUMENT_ROOT'].'/modules'.'/'.$module)){$files=scandir($_SERVER['DOCUMENT_ROOT'].'/modules'.'/'.$module);
							foreach($files as$file){$pathToFile=$_SERVER['DOCUMENT_ROOT'].'/modules'.'/'.$module.'/'.$file;if($file=='module.xml')$this->_processModule($pathToFile,$module);
							elseif(startsWith($file,'messages_'))$this->_processMessages($pathToFile,$this->_messagesFileWriters);elseif(startsWith($file,'adminmessages_'))$this->_processMessages($pathToFile,$this->_adminMessagesFileWriters);
							elseif(startsWith($file,'entitymessages_'))$this->_processMessages($pathToFile,$this->_entityMessagesFileWriters);}}}}
			function _processModule($file,$module){$doc=new DOMDocument();$loaded=@$doc->load($file,LIBXML_DTDLOAD|LIBXML_DTDVALID);if(!$loaded)throw new Exception('Could not load XML config file: '.$file);$isValid=$doc->validate();
							$this->_processItem($doc,'page',$this->_frontCacheFileWriter,$module);$this->_processItem($doc,'block',$this->_frontCacheFileWriter,$module);$this->_processItem($doc,'action',$this->_frontCacheFileWriter,$module);
							$this->_processItem($doc,'adminpage',$this->_adminCacheFileWriter,$module);$this->_processItem($doc,'setting',$this->_settingsCacheFileWriter,$module);$this->_processItem($doc,'eventlistener',$this->_eventsCacheFileWriter,$module);}
			function _processItem($doc,$itemName,$fileWriter,$module,$keyAttribute='id'){$items=$doc->getElementsByTagName($itemName);foreach($items as$item){$line=$this->_buildConfigLine($itemName,$keyAttribute,$item,$module);$fileWriter->writeLine($line);}}
			function _buildConfigLine($itemname,$keyAttribute,$xml,$module){if($itemname=='eventlistener')$line='$'.$itemname.'[\''.$xml->getAttribute('event').'\'][]';else{$id=$xml->getAttribute($keyAttribute);
							$line='$'.$itemname.'[\''.$xml->getAttribute($keyAttribute).'\']';}$itemAttrs=[];if($xml->hasAttributes()){$attrs=$xml->attributes;foreach($attrs as$attr)$itemAttrs[$attr->name]=$attr->value;}$parent=$xml->parentNode;
							if($parent->nodeName==$itemname)$itemAttrs['parentItem']=$parent->getAttribute($keyAttribute);if($xml->hasChildNodes()){$children=$xml->childNodes;$childrenIds='';$first=TRUE;foreach($children as$child){if($child->nodeName==$itemname){
							if(!$first)$childrenIds.=',';$childrenIds.=$child->getAttribute($keyAttribute);$first=FALSE;}elseif($child->nodeName=='script'||$child->nodeName=='css'){$childattrs=$child->attributes;$resourceRef=[];foreach($childattrs as$attr)
							$resourceRef[$attr->name]=$attr->value;$itemAttrs[$child->nodeName.'s'][]=$resourceRef;}}if(!$first)$itemAttrs['childrenIds']=$childrenIds;}$itemAttrs['module']=$module;$line.='=\''.json_encode($itemAttrs,JSON_HEX_QUOT).'\';';
							if($itemname=='setting'){global $conf;if(!isset($conf[$id])){$defaultValue='';if($xml->hasAttribute('default'))$defaultValue=$xml->getAttribute('default');$this->_newSettings[$id]=$defaultValue;}}return$line;}
			function _processMessages($file,$fileWriters){$doc=new DOMDocument();$loaded=@$doc->load($file);if(!$loaded)throw new Exception('Could not load XML messages file: '.$file);$lang=substr($file,strrpos($file,'_')+1,2);if(isset($fileWriters[$lang])){
							$messages=$doc->getElementsByTagName('message');$fileWriter=$fileWriters[$lang];foreach($messages as$message){$line='\''.$message->getAttribute('id').'\'=>\''. addslashes($this->_getInnerHtml($message)).'\',';$fileWriter->writeLine($line);}}}
			function _getInnerHtml($node){$innerHTML='';$children=$node->childNodes;foreach($children as$child)$innerHTML.=$child->ownerDocument->saveXML($child);return$innerHTML;}
			function __destruct(){if($this->_frontCacheFileWriter){}if($this->_adminCacheFileWriter){}if($this->_settingsCacheFileWriter){}foreach($this->_supportedLanguages as$language){if($this->_messagesFileWriters[$language]){}
							if($this->_adminMessagesFileWriters[$language]){}if($this->_entityMessagesFileWriters[$language]){}}}}
class DataUpdateSimulatorObserver{private$_teamsWithSoonEndingContracts;
			function __construct($websoccer,$db){$this->_websoccer=$websoccer;$this->_db=$db;$this->_teamsWithSoonEndingContracts=[];}
			function onBeforeMatchStarts(SimulationMatch $match){if((Config('sim_income_trough_friendly')||$match->type!=='Freundschaft')&&!$match->isAtForeignStadium)computeAndSaveAudience($this->_websoccer,$this->_db,$match);$clubTable=
							Config('db_prefix').'_verein';$updateColumns=[];$result=$this->_db->querySelect('user_id',$clubTable,'id=%d AND user_id>0',$match->homeTeam->id);$homeUser=$result->fetch_array();if($homeUser)$updateColumns['home_user_id']=
							$homeUser['user_id'];$result=$this->_db->querySelect('user_id',$clubTable,'id=%d AND user_id>0',$match->guestTeam->id);$guestUser=$result->fetch_array();if($guestUser)$updateColumns['gast_user_id']=$guestUser['user_id'];
							if(count($updateColumns))$this->_db->queryUpdate($updateColumns,Config('db_prefix').'_spiel','id=%d',$match->id);}
			function onMatchCompleted(SimulationMatch$match){$isFriendlyMatch=($match->type=='Freundschaft');if($isFriendlyMatch){$this->updatePlayersOfFriendlymatch($match->homeTeam);$this->updatePlayersOfFriendlymatch($match->guestTeam);}else{
							$isTie=$match->homeTeam->getGoals()==$match->guestTeam->getGoals();$this->updatePlayers($match,$match->homeTeam,$match->homeTeam->getGoals()>$match->guestTeam->getGoals(),$isTie);$this->updatePlayers($match,$match->guestTeam,
							$match->homeTeam->getGoals()<$match->guestTeam->getGoals(),$isTie);if(!$match->homeTeam->isNationalTeam)$this->creditSponsorPayments($match->homeTeam,TRUE,$match->homeTeam->getGoals()>$match->guestTeam->getGoals());
							if(!$match->guestTeam->isNationalTeam)$this->creditSponsorPayments($match->guestTeam,FALSE,$match->homeTeam->getGoals()<$match->guestTeam->getGoals());if($match->type=='Ligaspiel')$this->updateTeams($match);
							elseif(strlen($match->cupRoundGroup)){$this->updateTeamsOfCupGroupMatch($match);checkIfMatchIsLastMatchOfGroupRoundAndCreateFollowingMatches($this->_websoccer,$this->_db,$match);}$this->updateUsers($match);}
							$this->_db->queryDelete(Config('db_prefix').'_aufstellung', 'match_id=%d',$match->id);}
			function updatePlayersOfFriendlymatch(SimulationTeam $team){if(!count($team->positionsAndPlayers))return;foreach($team->positionsAndPlayers as$position=>$players){foreach($players as$player)$this->updatePlayerOfFriendlyMatch($player);}
							if(is_array($team->removedPlayers)&&count($team->removedPlayers)){foreach($team->removedPlayers as$player)$this->updatePlayerOfFriendlyMatch($player);}}
			function updatePlayerOfFriendlyMatch(SimulationPlayer $player){$columns=[];if(Config('sim_tiredness_through_friendly')){$columns['w_frische']=$player->strengthFreshness;$minMinutes=(int)Config('sim_played_min_minutes');
							$staminaChange=(int)Config('sim_strengthchange_stamina');if($player->getMinutesPlayed()>=$minMinutes)$columns['w_kondition']=min(100,$player->strengthStamina+$staminaChange);}if($player->injured>0&&Config('sim_injured_after_friendly'))
							$columns['verletzt']=$player->injured;if(count($columns)){$fromTable=Config('db_prefix').'_spieler';$this->_db->queryUpdate($columns,$fromTable,'id=%d',$player->id);}}
			function updatePlayers(SimulationMatch $match,SimulationTeam $team,$isTeamWinner,$isTie){$playersOnPitch=[];foreach($team->positionsAndPlayers as$position=>$players){foreach($players as$player)$playersOnPitch[$player->id]=$player;}
							if(is_array($team->removedPlayers)&&count($team->removedPlayers)){foreach($team->removedPlayers as$player)$playersOnPitch[$player->id]=$player;}$totalSalary=0;
							$pcolumns='id,vorname,nachname,kunstname,verein_id,vertrag_spiele,vertrag_gehalt,vertrag_torpraemie,w_zufriedenheit,w_frische,verletzt,gesperrt,gesperrt_cups,gesperrt_nationalteam,lending_fee,lending_matches,lending_owner_id';
							$fromTable=Config('db_prefix').'_spieler';if($team->isNationalTeam){$fromTable.=' INNER JOIN '.Config('db_prefix').'_nationalplayer AS NP ON NP.player_id=id';$whereCondition='NP.team_id=%d AND status=1';}
							else$whereCondition='verein_id=%d AND status=1';$parameters=$team->id;$result=$this->_db->querySelect($pcolumns,$fromTable,$whereCondition,$parameters);while($playerinfo=$result->fetch_array()){$totalSalary+=$playerinfo['vertrag_gehalt'];
							if(isset($playersOnPitch[$playerinfo['id']])){$player=$playersOnPitch[$playerinfo['id']];if($player->getGoals())$totalSalary+=$player->getGoals()*$playerinfo['vertrag_torpraemie'];}else$this->updatePlayerWhoDidNotPlay($match,
							$team->isNationalTeam,$playerinfo);}if(!$team->isNationalTeam)$this->deductSalary($team,$totalSalary);foreach($playersOnPitch as$player)$this->updatePlayer($match,$player,$isTeamWinner,$isTie);}
			function updatePlayer(SimulationMatch $match,SimulationPlayer$player,$isTeamWinner,$isTie){$fromTable=Config('db_prefix').'_spieler';$whereCondition='id=%d';$parameters=$player->id;$minMinutes=(int)Config('sim_played_min_minutes');
							$blockYellowCards=(int)Config('sim_block_player_after_yellowcards');$staminaChange=(int)Config('sim_strengthchange_stamina');$satisfactionChange=(int)Config('sim_strengthchange_satisfaction');if($player->team->isNationalTeam)
							$columns['gesperrt_nationalteam']=$player->blocked;elseif($match->type=='Pokalspiel')$columns['gesperrt_cups']=$player->blocked;else$columns['gesperrt']=$player->blocked;$pcolumns='id,vorname,nachname,kunstname,verein_id,vertrag_spiele,
							st_tore,st_assists,st_spiele,st_karten_gelb,st_karten_gelb_rot,st_karten_rot,sa_tore,sa_assists,sa_spiele,sa_karten_gelb,sa_karten_gelb_rot,sa_karten_rot,lending_fee,lending_owner_id,lending_matches';
							$result=$this->_db->querySelect($pcolumns,$fromTable,$whereCondition,$parameters);$playerinfo=$result->fetch_array();$columns['st_tore']=$playerinfo['st_tore']+$player->getGoals();$columns['sa_tore']=$playerinfo['sa_tore']+
							$player->getGoals();$columns['st_assists']=$playerinfo['st_assists']+$player->getAssists();$columns['sa_assists']=$playerinfo['sa_assists']+$player->getAssists();$columns['st_spiele']=$playerinfo['st_spiele']+1;
							$columns['sa_spiele']=$playerinfo['sa_spiele']+1;if($player->redCard){$columns['st_karten_rot']=$playerinfo['st_karten_rot']+1;$columns['sa_karten_rot']=$playerinfo['sa_karten_rot']+1;}elseif($player->yellowCards){
							if($player->yellowCards==2){$columns['st_karten_gelb_rot']=$playerinfo['st_karten_gelb_rot']+1;$columns['sa_karten_gelb_rot']=$playerinfo['sa_karten_gelb_rot']+1;if($player->team->isNationalTeam)$columns['gesperrt_nationalteam']='1';
							elseif($match->type=='Pokalspiel')$columns['gesperrt_cups']='1';else$columns['gesperrt']='1';}elseif(!$player->team->isNationalTeam){$columns['st_karten_gelb']=$playerinfo['st_karten_gelb']+1;
							$columns['sa_karten_gelb']=$playerinfo['sa_karten_gelb']+1;if($match->type=='Ligaspiel'&&$blockYellowCards>0&&$columns['sa_karten_gelb']%$blockYellowCards==0)$columns['gesperrt']=1;}}if(!$player->team->isNationalTeam){
							$columns['vertrag_spiele']=max(0,$playerinfo['vertrag_spiele']-1);if($columns['vertrag_spiele']==5)$this->_teamsWithSoonEndingContracts[$player->team->id]=TRUE;}if(!$player->team->isNationalTeam||
							Config('sim_playerupdate_through_nationalteam')){$columns['w_frische']=$player->strengthFreshness;$columns['verletzt']=$player->injured;if($player->getMinutesPlayed()>=$minMinutes){$columns['w_kondition']=min(100,
							$player->strengthStamina+$staminaChange);$columns['w_zufriedenheit']=min(100,$player->strengthSatisfaction+$satisfactionChange);}else{$columns['w_kondition']=max(1,$player->strengthStamina- $staminaChange);
							$columns['w_zufriedenheit']=max(1,$player->strengthSatisfaction-$satisfactionChange);}if(!$isTie){if($isTeamWinner)$columns['w_zufriedenheit']=min(100,$columns['w_zufriedenheit']+$satisfactionChange);
							else $columns['w_zufriedenheit']=max(1,$columns['w_zufriedenheit']-$satisfactionChange);}}if(!$player->team->isNationalTeam&&$playerinfo['lending_matches'])$this->handleBorrowedPlayer($columns,$playerinfo);
							$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$parameters);}
			function updatePlayerWhoDidNotPlay(SimulationMatch$match,$isNationalTeam,$playerinfo){$fromTable=Config('db_prefix').'_spieler';$whereCondition='id=%d';$parameters=$playerinfo['id'];$satisfactionChange=(int)Config('sim_strengthchange_satisfaction');
							if($isNationalTeam)$columns['gesperrt_nationalteam']=max(0,$playerinfo['gesperrt_nationalteam']-1);elseif($match->type=='Pokalspiel')$columns['gesperrt_cups']=max(0,$playerinfo['gesperrt_cups']-1);else$columns['gesperrt']=max(0,
							$playerinfo['gesperrt']-1);$columns['verletzt']=max(0,$playerinfo['verletzt']-1);if(!$isNationalTeam){$columns['vertrag_spiele']=max(0,$playerinfo['vertrag_spiele']-1);if($columns['vertrag_spiele']==5)
							$this->_teamsWithSoonEndingContracts[$playerinfo['id']]=TRUE;}if(!$isNationalTeam ||Config('sim_playerupdate_through_nationalteam')){$columns['w_zufriedenheit']=max(1,$playerinfo['w_zufriedenheit']-$satisfactionChange);$columns['w_frische']=
							min(100,$playerinfo['w_frische']+Config('sim_strengthchange_freshness_notplayed'));}if(!$isNationalTeam&&$playerinfo['lending_matches'])$this->handleBorrowedPlayer($columns,$playerinfo);$this->_db->queryUpdate($columns,$fromTable,
							$whereCondition,$parameters);}
			function deductSalary(SimulationTeam $team,$salary){debitAmount($this->_websoccer,$this->_db,$team->id,$salary,'match_salarypayment_subject','match_salarypayment_sender');}
			function updateTeams(SimulationMatch $match){$fromTable=Config('db_prefix').'_verein';$whereCondition='id=%d';$tcolumns='st_tore,st_gegentore,st_spiele,st_siege,st_niederlagen,st_unentschieden,st_punkte,sa_tore,sa_gegentore,sa_spiele,sa_siege,
							sa_niederlagen,sa_unentschieden,sa_punkte';$result=$this->_db->querySelect($tcolumns,$fromTable,$whereCondition,$match->homeTeam->id);$home=$result->fetch_array();$result=$this->_db->querySelect($tcolumns,$fromTable,$whereCondition,
							$match->guestTeam->id);$guest=$result->fetch_array();$homeColumns['sa_spiele']=$home['sa_spiele']+1;$homeColumns['st_spiele']=$home['st_spiele']+1;$homeColumns['sa_tore']=$home['sa_tore']+$match->homeTeam->getGoals();
							$homeColumns['st_tore']=$home['st_tore']+$match->homeTeam->getGoals();$homeColumns['sa_gegentore']=$home['sa_gegentore']+$match->guestTeam->getGoals();$homeColumns['st_gegentore']=$home['st_gegentore']+$match->guestTeam->getGoals();
							$guestColumns['sa_spiele']=$guest['sa_spiele']+1;$guestColumns['st_spiele']=$guest['st_spiele']+1;$guestColumns['sa_tore']=$guest['sa_tore']+$match->guestTeam->getGoals();$guestColumns['st_tore']=$guest['st_tore']+
							$match->guestTeam->getGoals();$guestColumns['sa_gegentore']=$guest['sa_gegentore']+$match->homeTeam->getGoals();$guestColumns['st_gegentore']=$guest['st_gegentore']+$match->homeTeam->getGoals();if($match->homeTeam->getGoals()>
							$match->guestTeam->getGoals()){$homeColumns['sa_siege']=$home['sa_siege']+1;$homeColumns['st_siege']=$home['st_siege']+1;$homeColumns['sa_punkte']=$home['sa_punkte']+config('POINTS_WIN');$homeColumns['st_punkte']=
							$home['st_punkte']+config('POINTS_WIN');$guestColumns['sa_niederlagen']=$guest['sa_niederlagen']+1;$guestColumns['st_niederlagen']=$guest['st_niederlagen']+1;$guestColumns['sa_punkte']=$guest['sa_punkte']+config('POINTS_LOSS');
							$guestColumns['st_punkte']=$guest['st_punkte']+config('POINTS_LOSS');}elseif($match->homeTeam->getGoals()==$match->guestTeam->getGoals()){$homeColumns['sa_unentschieden']=$home['sa_unentschieden']+1;$homeColumns['st_unentschieden']=
							$home['st_unentschieden']+1;$homeColumns['sa_punkte']=$home['sa_punkte']+config('POINTS_DRAW');$homeColumns['st_punkte']=$home['st_punkte']+config('POINTS_DRAW');$guestColumns['sa_unentschieden']=$guest['sa_unentschieden']+1;
							$guestColumns['st_unentschieden']=$guest['st_unentschieden']+1;$guestColumns['sa_punkte']=$guest['sa_punkte']+config('POINTS_DRAW');$guestColumns['st_punkte']=$guest['st_punkte']+config('POINTS_DRAW');}else{ 															  $homeColumns['sa_niederlagen']=$home['sa_niederlagen']+1;$homeColumns['st_niederlagen']=$home['st_niederlagen']+1;$homeColumns['sa_punkte']=$home['sa_punkte']+config('POINTS_LOSS');$homeColumns['st_punkte']=$home['st_punkte']+
							config('POINTS_LOSS');$guestColumns['sa_siege']=$guest['sa_siege']+1;$guestColumns['st_siege']=$guest['st_siege']+1;$guestColumns['sa_punkte']=$guest['sa_punkte']+config('POINTS_WIN');$guestColumns['st_punkte']=$guest['st_punkte']+
							config('POINTS_WIN');}$this->_db->queryUpdate($homeColumns,$fromTable,$whereCondition,$match->homeTeam->id);$this->_db->queryUpdate($guestColumns,$fromTable,$whereCondition,$match->guestTeam->id);}
			function updateTeamsOfCupGroupMatch(SimulationMatch $match){$fromTable=Config('db_prefix').'_cup_round_group AS G INNER JOIN '.Config('db_prefix').'_cup_round AS R ON R.id=G.cup_round_id INNER JOIN '.Config('db_prefix').'_cup AS C ON C.id=R.cup_id';
							$whereCondition='C.name=\'%s\'AND R.name=\'%s\'AND G.name=\'%s\'AND G.team_id=%d';$tcolumns=['G.tab_points'=>'tab_points','G.tab_goals'=>'tab_goals','G.tab_goalsreceived'=>'tab_goalsreceived','G.tab_wins'=>'tab_wins','G.tab_draws'=>
							'tab_draws','G.tab_losses'=>'tab_losses'];$homeParameters=array($match->cupName,$match->cupRoundName,$match->cupRoundGroup,$match->homeTeam->id);$result=$this->_db->querySelect($tcolumns,$fromTable,$whereCondition,$homeParameters,1);
							$home=$result->fetch_array();$guestParameters=array($match->cupName,$match->cupRoundName,$match->cupRoundGroup,$match->guestTeam->id);$result=$this->_db->querySelect($tcolumns,$fromTable,$whereCondition,$guestParameters,1);
							$guest=$result->fetch_array();$homeColumns['tab_goals']=$home['tab_goals']+$match->homeTeam->getGoals();$homeColumns['tab_goalsreceived']=$home['tab_goalsreceived']+$match->guestTeam->getGoals();$guestColumns['tab_goals']=$guest['tab_goals']+
							$match->guestTeam->getGoals();$guestColumns['tab_goalsreceived']=$guest['tab_goalsreceived']+$match->homeTeam->getGoals();if($match->homeTeam->getGoals()>$match->guestTeam->getGoals()){$homeColumns['tab_wins']=$home['tab_wins']+1;
			                $homeColumns['tab_points']=$home['tab_points']+config('POINTS_WIN');$guestColumns['tab_losses']=$guest['tab_losses']+1;$guestColumns['tab_points']=$guest['tab_points']+config('POINTS_LOSS');}
			                elseif($match->homeTeam->getGoals()==$match->guestTeam->getGoals()){$homeColumns['tab_draws']=$home['tab_draws']+1;$homeColumns['tab_points']=$home['tab_points']+config('POINTS_DRAW');$guestColumns['tab_draws']=$guest['tab_draws']+1;
							$guestColumns['tab_points']=$guest['tab_points']+config('POINTS_DRAW');}else{$homeColumns['tab_losses']=$home['tab_losses']+1;$homeColumns['tab_points']=$home['tab_points']+config('POINTS_LOSS');$guestColumns['tab_wins']=$guest['tab_wins']+1;
							$guestColumns['tab_points']=$guest['tab_points']+config('POINTS_WIN');}$this->_db->queryUpdate($homeColumns,$fromTable,$whereCondition,$homeParameters);$this->_db->queryUpdate($guestColumns,$fromTable,$whereCondition,$guestParameters);}
			function creditSponsorPayments(SimulationTeam$team,$isHomeTeam,$teamIsWinner){$columns='S.name AS sponsor_name,b_spiel,b_heimzuschlag,b_sieg,T.sponsor_spiele AS sponsor_matches';$fromTable=Config('db_prefix').'_verein AS T INNER JOIN '.Config('db_prefix').
							'_sponsor AS S ON S.id=T.sponsor_id';$whereCondition='T.id=%d AND T.sponsor_spiele>0';$result=$this->_db->querySelect($columns,$fromTable,$whereCondition,$team->id);$sponsor=$result->fetch_array();if(isset($sponsor['sponsor_matches'])){
							$amount=$sponsor['b_spiel'];if($isHomeTeam)$amount+=$sponsor['b_heimzuschlag'];if($teamIsWinner)$amount+=$sponsor['b_sieg'];creditAmount($this->_websoccer,$this->_db,$team->id,$amount,'match_sponsorpayment_subject',
							$sponsor['sponsor_name']);$updatecolums['sponsor_spiele']=max(0,$sponsor['sponsor_matches']-1);if($updatecolums['sponsor_spiele']==0)$updatecolums['sponsor_id']='';$whereCondition='id=%d';$fromTable=Config('db_prefix').'_verein';
							$this->_db->queryUpdate($updatecolums,$fromTable,$whereCondition,$team->id);}}
			function updateUsers(SimulationMatch$match){$highscoreWin=Config('highscore_win');$highscoreLoss=Config('highscore_loss');$highscoreDraw=Config('highscore_draw');$columns='U.id AS u_id,U.highscore AS highscore,U.fanbeliebtheit AS popularity';
							$fromTable=Config('db_prefix').'_verein AS T INNER JOIN '.Config('db_prefix').'_user AS U ON U.id=T.user_id';$whereCondition='T.id=%d';$result=$this->_db->querySelect($columns,$fromTable,$whereCondition,$match->homeTeam->id);
							$homeUser=$result->fetch_array();$updateTable=Config('db_prefix').'_user';$updateCondition='id=%d';$homeStrength=$match->homeTeam->computeTotalStrength($this->_websoccer,$match);
							$guestStrength=$match->guestTeam->computeTotalStrength($this->_websoccer,$match);if($homeStrength)$homeGuestStrengthDiff=round(($homeStrength-$guestStrength)/homeStrength*100);else$homeGuestStrengthDiff=0;
							if(!empty($homeUser['u_id'])&&!$match->homeTeam->noFormationSet){if($match->homeTeam->getGoals()>$match->guestTeam->getGoals()){$homeColumns['highscore']=max(0,$homeUser['highscore']+$highscoreWin);$popFactor=1.1;
							if($homeGuestStrengthDiff>=20)$popFactor=1.05;$homeColumns['fanbeliebtheit']=min(100,round($homeUser['popularity']*$popFactor));$goalsDiff=$match->homeTeam->getGoals()-$match->guestTeam->getGoals();
							awardBadgeIfApplicable($this->_websoccer,$this->_db,$homeUser['u_id'],'win_with_x_goals_difference',$goalsDiff);}elseif($match->homeTeam->getGoals()==$match->guestTeam->getGoals()){$homeColumns['highscore']=max(0,
							$homeUser['highscore']+$highscoreDraw);$popFactor=1.0;if($homeGuestStrengthDiff>=20)$popFactor=0.95;elseif($homeGuestStrengthDiff<=-20)$popFactor=1.05;$homeColumns['fanbeliebtheit']=min(100,round($homeUser['popularity']*$popFactor));}else{
							$homeColumns['highscore']=max(0,$homeUser['highscore']+$highscoreLoss);$popFactor=0.95;if($homeGuestStrengthDiff>=20)$popFactor=0.90;elseif($homeGuestStrengthDiff<=-20)$popFactor=1.00;$homeColumns['fanbeliebtheit']=
							max(1,round($homeUser['popularity']*$popFactor));}if(!$match->homeTeam->isManagedByInterimManager)$this->_db->queryUpdate($homeColumns,$updateTable,$updateCondition,$homeUser['u_id']);
							if(isset($this->_teamsWithSoonEndingContracts[$match->homeTeam->id]))$this->notifyAboutSoonEndingContracts($homeUser['u_id'],$match->homeTeam->id);}$result=$this->_db->querySelect($columns,$fromTable,$whereCondition,$match->guestTeam->id);
							$guestUser=$result->fetch_array();if(!empty($guestUser['u_id'])&&!$match->guestTeam->noFormationSet){if($match->guestTeam->getGoals()>$match->homeTeam->getGoals()){$popFactor=1.1;if($homeGuestStrengthDiff<=-20)$popFactor=1.05;
							$guestColumns['highscore']=max(0,$guestUser['highscore']+$highscoreWin);$guestColumns['fanbeliebtheit']=min(100,round($guestUser['popularity']*$popFactor));$goalsDiff=$match->guestTeam->getGoals()-$match->homeTeam->getGoals();
							awardBadgeIfApplicable($this->_websoccer,$this->_db,$guestUser['u_id'],'win_with_x_goals_difference',$goalsDiff);}elseif($match->guestTeam->getGoals()==$match->homeTeam->getGoals()){$popFactor=1.0;
							if($homeGuestStrengthDiff<=-20)$popFactor=0.95;elseif($homeGuestStrengthDiff>=20)$popFactor=1.05;$guestColumns['highscore']=max(0,$guestUser['highscore']+$highscoreDraw);$guestColumns['fanbeliebtheit']=
							min(100,round($guestUser['popularity']*$popFactor));}else{$guestColumns['highscore']=max(0,$guestUser['highscore']+$highscoreLoss);$popFactor=0.95;if($homeGuestStrengthDiff<=-20)$popFactor=0.90;elseif($homeGuestStrengthDiff>=20)
							$popFactor=1.00;$guestColumns['fanbeliebtheit']=max(1,round($guestUser['popularity']*$popFactor));}if(!$match->guestTeam->isManagedByInterimManager)$this->_db->queryUpdate($guestColumns,$updateTable,$updateCondition,$guestUser['u_id']);
							if(isset($this->_teamsWithSoonEndingContracts[$match->guestTeam->id]))$this->notifyAboutSoonEndingContracts($guestUser['u_id'],$match->guestTeam->id);}}
			function handleBorrowedPlayer(&$columns,$playerinfo){$columns['lending_matches']=max(0,$playerinfo['lending_matches']-1);if($columns['lending_matches']==0){$columns['lending_fee']=0;$columns['lending_owner_id']=0;$columns['verein_id']=
							$playerinfo['lending_owner_id'];$borrower=TeamsDataService::getTeamSummaryById($this->_websoccer,$this->_db,$playerinfo['verein_id']);$lender=TeamsDataService::getTeamSummaryById($this->_websoccer,$this->_db,$playerinfo['lending_owner_id']);
							$messageKey='lending_notification_return';$messageType='lending_return';$playerName=($playerinfo['kunstname'])?$playerinfo['kunstname']:$playerinfo['vorname'].' '.$playerinfo['nachname'];$messageData=array('player'=>$playerName,
							'borrower'=>$borrower['team_name'],'lender'=>$lender['team_name']);if($borrower['user_id'])NotificationsDataService::createNotification($this->_websoccer,$this->_db,$borrower['user_id'],$messageKey,$messageData,$messageType,'player','id='.
							$playerinfo['id']);if($lender['user_id'])NotificationsDataService::createNotification($this->_websoccer,$this->_db,$lender['user_id'],$messageKey,$messageData,$messageType,'player','id='.$playerinfo['id']);}}
			function notifyAboutSoonEndingContracts($userId,$teamId){NotificationsDataService::createNotification($this->_websoccer,$this->_db,$userId,'notification_soon_ending_playercontracts','','soon_ending_playercontracts','myteam',null,$teamId);
							unset($this->_teamsWithSoonEndingContracts[$teamId]);}
			function onSubstitution(SimulationMatch$match,SimulationSubstitution$substitution){} }
class DbConnection{public$connection;private static$_instance;private$_queryCache;
	 static function getInstance(){if(self::$_instance==NULL)self::$_instance=new DbConnection();return self::$_instance;}
			function __construct(){}
			function connect($host,$user,$password,$dbname){@$this->connection=new mysqli($host,$user,$password,$dbname);@$this->connection->set_charset('utf8');if(mysqli_connect_error())throw new Exception('Database Connection Error ('.mysqli_connect_errno().')'.
							mysqli_connect_error());}
			function close(){$this->connection->close();}
			function querySelect($columns,$fromTable,$whereCondition,$parameters=null,$limit=null){$queryStr=$this->buildQueryString($columns,$fromTable,$whereCondition,$parameters,$limit);return$this->executeQuery($queryStr);}
			function queryCachedSelect($columns,$fromTable,$whereCondition,$parameters=null,$limit=null){$queryStr=$this->buildQueryString($columns,$fromTable,$whereCondition,$parameters,$limit);if(isset($this->_queryCache[$queryStr]))
							return$this->_queryCache[$queryStr];$result=$this->executeQuery($queryStr);$rows=[];while($row=$result->fetch_array())$rows[]=$row;$this->_queryCache[$queryStr]=$rows;return$rows;}
			function queryUpdate($columns,$fromTable,$whereCondition,$parameters){$queryStr='UPDATE '.$fromTable.' SET ';$queryStr=$queryStr.self::buildColumnsValueList($columns);$queryStr=$queryStr.' WHERE ';$wherePart=self::buildWherePart($whereCondition,$parameters);
							$queryStr=$queryStr.$wherePart;$this->executeQuery($queryStr);$this->_queryCache=[];}
			function queryDelete($fromTable,$whereCondition,$parameters){$queryStr='DELETE FROM '.$fromTable;$queryStr=$queryStr.' WHERE ';$wherePart=self::buildWherePart($whereCondition,$parameters);$queryStr=$queryStr.$wherePart;$this->executeQuery($queryStr);
							$this->_queryCache=[];}
			function queryInsert($columns,$fromTable){$queryStr='INSERT '.$fromTable.' SET ';$queryStr=$queryStr .$this->buildColumnsValueList($columns);$this->executeQuery($queryStr);}
			function getLastInsertedId(){return$this->connection->insert_id;}
			function buildQueryString($columns,$fromTable,$whereCondition,$parameters=null,$limit=null){$queryStr='SELECT ';if(is_array($columns)){$firstColumn=TRUE;foreach($columns as$dbName=>$aliasName){if(!$firstColumn)$queryStr=$queryStr.', ';else$firstColumn=FALSE;
							if(is_numeric($dbName))$dbName=$aliasName;$queryStr=$queryStr.$dbName.' AS '.$aliasName;}}else$queryStr=$queryStr.$columns;$queryStr=$queryStr.' FROM '.$fromTable.' WHERE ';$wherePart=self::buildWherePart($whereCondition,$parameters);
							if(!empty($limit))$wherePart=$wherePart.' LIMIT '.$limit;$queryStr=$queryStr.$wherePart;return$queryStr;}
			function buildColumnsValueList($columns){$queryStr='';$firstColumn=TRUE;foreach($columns as$dbName=>$value){if(!$firstColumn)$queryStr=$queryStr.', ';else$firstColumn=FALSE;if(strlen($value))$columnValue='\''.$this->connection->real_escape_string($value).
							'\'';else$columnValue='DEFAULT';$queryStr=$queryStr.$dbName.'='.$columnValue;}return$queryStr;}
			function buildWherePart($whereCondition,$parameters){$maskedParameters=self::prepareParameters($parameters);return vsprintf($whereCondition,$maskedParameters);}
			function prepareParameters($parameters){if(!is_array($parameters))$parameters=array($parameters);$arrayLength=count($parameters);for($i=0;$i<$arrayLength;++$i)$parameters[$i]=$this->connection->real_escape_string(trim($parameters[$i]));return$parameters;}
			function executeQuery($queryStr){$queryResult=$this->connection->query($queryStr);if(!$queryResult)throw new Exception('Database Query Error: '.$this->connection->error);return$queryResult;}}
class DbSessionManager{
			function __construct($db,$websoccer){$this->_db=$db;$this->_websoccer=$websoccer;}
			function open($save_path,$session_name){return TRUE;}
			function close(){return TRUE;}
			function destroy($sessionId){$fromTable=Config('db_prefix').'_session';$whereCondition='session_id=\'%s\'';$this->_db->queryDelete($fromTable,$whereCondition,$sessionId);return TRUE;}
			function read($sessionId){$columns='expires,session_data';$fromTable=Config('db_prefix').'_session';$whereCondition='session_id=\'%s\'';$data='';$result=$this->_db->querySelect($columns,$fromTable,$whereCondition,$sessionId);if($result->num_rows){
							$row=$result->fetch_array();if($row['expires']<Timestamp())$this->destroy($sessionId);else{$data=$row['session_data'];if($data==null)$data='';}}return$data;}
			function validate_sid($key){$columns='expires';$fromTable=Config('db_prefix').'_session';$whereCondition='session_id=\'%s\'';$result=$this->_db->querySelect($columns,$fromTable,$whereCondition,$key);if($result->num_rows){$row=$result->fetch_array();
							if($row['expires']<Timestamp())$this->destroy($key);else{return true;}}return FALSE;}
			function write($sessionId,$data){$lifetime=(int)Config('session_lifetime');$expiry=Timestamp()+$lifetime;$fromTable=Config('db_prefix').'_session';$columns['session_data']=$data;$columns['expires']=$expiry;if($this->validate_sid($sessionId)){
							$whereCondition='session_id=\'%s\'';$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$sessionId);}elseif(!empty($data)){$columns['session_id']=$sessionId;$this->_db->queryInsert($columns,$fromTable);}return FALSE;}
			function gc($maxlifetime){$this->_deleteExpiredSessions();return true;}
			function _deleteExpiredSessions(){$fromTable=Config('db_prefix').'_session';$whereCondition='expires<%d';$this->_db->queryDelete($fromTable,$whereCondition,Timestamp());}}
class DefaultSimulationObserver {
			function onGoal(SimulationMatch$match,SimulationPlayer$scorer,SimulationPlayer$goaly){$assistPlayer=($match->getPreviousPlayerWithBall()!==NULL&&$match->getPreviousPlayerWithBall()->team->id==$scorer->team->id)? $match->getPreviousPlayerWithBall():'';
							$scorer->improveMark(config('MARK_IMPROVE_GOAL_SCORER'));$goaly->downgradeMark(config('MARK_DOWNGRADE_GOAL_GOALY'));if(strlen($assistPlayer)){$assistPlayer->improveMark(config('MARK_IMPROVE_GOAL_PASSPLAYER'));
							$assistPlayer->setAssists($assistPlayer->getAssists()+1);}$scorer->team->setGoals($scorer->team->getGoals()+1);$scorer->setGoals($scorer->getGoals()+1);$scorer->setShoots($scorer->getShoots()+1);}
			function onShootFailure(SimulationMatch$match,SimulationPlayer$scorer,SimulationPlayer$goaly){if($scorer->getGoals()<3)$scorer->downgradeMark(config('MARK_DOWNGRADE_SHOOTFAILURE'));$goaly->improveMark(config('MARK_IMPROVE_SHOOTFAILURE_GOALY'));
							if($goaly->team->getGoals()>3)$goaly->setMark(max(2.0,$goaly->getMark()));$scorer->setShoots($scorer->getShoots()+ 1);}
			function onAfterTackle(SimulationMatch$match,SimulationPlayer$winner,SimulationPlayer$looser){if($looser->getGoals()>0&&$looser->getGoals()<3&&$looser->getAssists()>0&&$looser->getAssists()<3)$looser->downgradeMark(
							config('MARK_DOWNGRADE_TACKLE_LOOSER')*0.5);elseif($looser->getGoals()<3&&$looser->getAssists()<3)$looser->downgradeMark(config('MARK_DOWNGRADE_TACKLE_LOOSER'));$winner->improveMark(config('MARK_IMPROVE_TACKLE_WINNER'));
							$winner->setWonTackles($winner->getWonTackles()+ 1);$looser->setLostTackles($winner->getLostTackles()+ 1);}
			function onBallPassSuccess(SimulationMatch$match,SimulationPlayer$player){$player->improveMark(config('MARK_IMPROVE_BALLPASS_SUCCESS'));$player->setPassesSuccessed($player->getPassesSuccessed()+1);}
			function onBallPassFailure(SimulationMatch$match,SimulationPlayer$player){if($player->getGoals()<2&&$player->getAssists()<2&&($player->getGoals()==0||$player->getAssists()==0))$player->downgradeMark(config('MARK_DOWNGRADE_BALLPASS_FAILURE'));
							$player->setPassesFailed($player->getPassesFailed()+1);}
			function onInjury(SimulationMatch$match,SimulationPlayer$player,$numberOfMatches){$player->injured=$numberOfMatches;$substituted=createUnplannedSubstitutionForPlayer($match->minute+1,$player);if(!$substituted)
							$player->team->removePlayer($player);}
			function onYellowCard(SimulationMatch$match,SimulationPlayer$player){$player->yellowCards=$player->yellowCards+1;if($player->yellowCards==2){$player->downgradeMark(config('MARK_DOWNGRADE_TACKLE_LOOSER'));$player->team->removePlayer($player);}}
			function onRedCard(SimulationMatch$match,SimulationPlayer$player,$matchesBlocked){$player->redCard=1;$player->blocked=$matchesBlocked;$player->team->removePlayer($player);}
			function onPenaltyShoot(SimulationMatch$match,SimulationPlayer$player,SimulationPlayer$goaly,$successful){if($successful){$player->improveMark(config('MARK_IMPROVE_GOAL_SCORER'));$player->team->setGoals($player->team->getGoals()+1);}else{
							$player->downgradeMark(config('MARK_DOWNGRADE_SHOOTFAILURE'));$goaly->improveMark(config('MARK_IMPROVE_SHOOTFAILURE_GOALY'));}}
			function onCorner(SimulationMatch$match,SimulationPlayer$concededByPlayer,SimulationPlayer$targetPlayer){$match->setPlayerWithBall($concededByPlayer);$concededByPlayer->improveMark(config('MARK_IMPROVE_BALLPASS_SUCCESS'));
							$concededByPlayer->setPassesSuccessed($concededByPlayer->getPassesSuccessed()+1);}
			function onFreeKick(SimulationMatch$match,SimulationPlayer$player,SimulationPlayer$goaly,$successful){$player->setShoots($player->getShoots()+1);if($successful){$player->improveMark(config('MARK_IMPROVE_GOAL_SCORER'));$player->team->setGoals(
							$player->team->getGoals()+1);$player->setGoals($player->getGoals()+1);}else{$player->downgradeMark(config('MARK_DOWNGRADE_SHOOTFAILURE'));$goaly->improveMark(config('MARK_IMPROVE_SHOOTFAILURE_GOALY'));}}}
class DefaultSimulationStrategy{private$_websoccer,$_passTargetProbPerPosition,$_opponentPositions,$_shootStrengthPerPosition,$_shootProbPerPosition,$_observers;
			function __construct($websoccer){$this->_websoccer=$websoccer;$this->_setPassTargetProbabilities();$this->_setOpponentPositions();$this->_setShootStrengthPerPosition();$this->_setShootProbPerPosition();$this->_observers=[];}
			function attachObserver($observer){$this->_observers[]=$observer;}
			function kickoff(SimulationMatch$match){$pHomeTeam[TRUE]=50;$pHomeTeam[FALSE]=50;$team=selectItemFromProbabilities($pHomeTeam)?$match->homeTeam:$match->guestTeam;$match->setPlayerWithBall(selectPlayer($team,
							config('PLAYER_POSITION_DEFENCE'),null));}
			function nextAction(SimulationMatch $match){$player=$match->getPlayerWithBall();if($player->position==config('PLAYER_POSITION_GOALY'))return'passBall';$opponentTeam=getOpponentTeam($player,$match);$opponentPosition=$this->_opponentPositions[
							$player->position];$noOfOwnPlayersInPosition=count($player->team->positionsAndPlayers[$player->position]);if(isset($opponentTeam->positionsAndPlayers[$opponentPosition]))$noOfOpponentPlayersInPosition=count($opponentTeam->positionsAndPlayers[
							$opponentPosition]);else$noOfOpponentPlayersInPosition=0;$pTackle=10;if($noOfOpponentPlayersInPosition==$noOfOwnPlayersInPosition)$pTackle+=10;elseif($noOfOpponentPlayersInPosition>$noOfOwnPlayersInPosition)$pTackle+=10+20*(
							$noOfOpponentPlayersInPosition-$noOfOwnPlayersInPosition);$pAction['tackle']=min($pTackle,40);$pShoot=$this->_shootProbPerPosition[$player->position];$tacticInfluence=($this->_getOffensiveStrength($player->team,$match)-
							$this->_getDefensiveStrength($opponentTeam,$match))/10;if($player->team->counterattacks)$pShoot=round($pShoot*0.5);$resultInfluence=($player->team->getGoals()-$opponentTeam->getGoals())*(0-5);if($player->team->getGoals()<
							$opponentTeam->getGoals()&&$player->team->morale)$resultInfluence+=floor($player->team->morale/100*5);if($player->position==config('PLAYER_POSITION_STRIKER')||$player->position==config('PLAYER_POSITION_MIDFIELD'))$minShootProb=5;
							else$minShootProb=1;$pAction['shoot']=round(max($minShootProb,min($pShoot+$tacticInfluence+$resultInfluence,50))*Config('sim_shootprobability')/100);$pAction['passBall']=100-$pAction['tackle']-$pAction['shoot'];
							return selectItemFromProbabilities($pAction);}
			function passBall(SimulationMatch$match){$player=$match->getPlayerWithBall();$pFailed[FALSE]=round(($player->getTotalStrength($this->_websoccer,$match)+$player->strengthTech)/2);if($player->team->longPasses)$pFailed[FALSE]=round($pFailed[FALSE]*0.7);
							$pFailed[TRUE]=100-$pFailed[FALSE];if(selectItemFromProbabilities($pFailed)==TRUE){$opponentTeam=getOpponentTeam($player,$match);$targetPosition=$this->_opponentPositions[$player->position];
							$match->setPlayerWithBall(SselectPlayer($opponentTeam,$targetPosition,null));foreach($this->_observers as$observer)$observer->onBallPassFailure($match,$player);return FALSE;}$pTarget[config('PLAYER_POSITION_GOALY')]=
							$this->_passTargetProbPerPosition[$player->position][config('PLAYER_POSITION_GOALY')];$pTarget[config('PLAYER_POSITION_DEFENCE')]=$this->_passTargetProbPerPosition[$player->position][config('PLAYER_POSITION_DEFENCE')];$pTarget[
							config('PLAYER_POSITION_STRIKER')]=$this->_passTargetProbPerPosition[$player->position][config('PLAYER_POSITION_STRIKER')];if($player->position!=config('PLAYER_POSITION_GOALY'))$pTarget[config('PLAYER_POSITION_STRIKER')]+=10;
							$offensiveInfluence=round(10-$player->team->offensive*0.2);$pTarget[config('PLAYER_POSITION_DEFENCE')]=$pTarget[config('PLAYER_POSITION_DEFENCE')]+$offensiveInfluence;$pTarget[config('PLAYER_POSITION_MIDFIELD')]=100-$pTarget[
							config('PLAYER_POSITION_STRIKER')]-$pTarget[config('PLAYER_POSITION_DEFENCE')]-$pTarget[config('PLAYER_POSITION_GOALY')];$targetPosition=selectItemFromProbabilities($pTarget);$match->setPlayerWithBall(
							selectPlayer($player->team,$targetPosition,$player));foreach($this->_observers as$observer)$observer->onBallPassSuccess($match,$player);return TRUE;}
			function tackle(SimulationMatch$match){
		$player=$match->getPlayerWithBall();
		$opponentTeam=getOpponentTeam($player,$match);
		$targetPosition=$this->_opponentPositions[$player->position];
		$opponent=selectPlayer($opponentTeam,$targetPosition,null);
		$pWin[TRUE]=max(1,min(50+$player->getTotalStrength($this->_websoccer,$match)- $opponent->getTotalStrength($this->_websoccer,$match),99));
		$pWin[FALSE]=100-$pWin[TRUE];
		$result=SselectItemFromProbabilities($pWin);
		foreach($this->_observers as$observer)$observer->onAfterTackle($match,($result)? $player : $opponent,($result)? $opponent : $player);
		if($result==TRUE){
			$pTackle['yellow']=round(max(1,min(20,round((100-$opponent->strengthTech)/2)))*Config('sim_cardsprobability')/100);
			if($opponent->yellowCards)$pTackle['yellow']=round($pTackle['yellow']/2);
			$pTackle['red']=1;
			if($pTackle['yellow']>15)$pTackle['red']=3;
			$pTackle['fair']=100-$pTackle['yellow']-$pTackle['red'];
			$tackled=selectItemFromProbabilities($pTackle);
			if($tackled=='yellow'||$tackled=='red'){
				$pInjured[TRUE]=min(99,round(((100-$player->strengthFreshness)/3)*Config('sim_injuredprobability')/100));
				$pInjured[FALSE]=100-$pInjured[TRUE];
				$injured=selectItemFromProbabilities($pInjured);
				$blockedMatches=0;
				if($injured){$blockedMatches=min((int)Config('sim_maxmatches_injured'),selectItemFromProbabilities([1=>5,2=>25,3=>30,4=>20,5=>5,6>=5,7>=5,8>=1,9>=1,10>=1,11>=1,$maxMatchesInjured=>1]));}
				foreach($this->_observers as$observer){
					if($tackled=='yellow')$observer->onYellowCard($match,$opponent);
					else$observer->onRedCard($match,$opponent,getMagicNumber(min(1,(int)Config('sim_maxmatches_blocked')),(int)Config('sim_maxmatches_blocked')));
					if($injured){
						$observer->onInjury($match,$player,$blockedMatches);
						$match->setPlayerWithBall(selectPlayer($player->team,config('PLAYER_POSITION_MIDFIELD')));}}
				if($player->position==config('PLAYER_POSITION_STRIKER')){
					$pPenalty[TRUE]=10;
					$pPenalty[FALSE]=90;
					if(selectItemFromProbabilities($pPenalty))$this->foulPenalty($match,$player->team);}
				else{
					if($player->team->freeKickPlayer!=NULL)$freeKickScorer=$player->team->freeKickPlayer;
					else $freeKickScorer=selectPlayer($player->team,config('PLAYER_POSITION_MIDFIELD'));
					$goaly=selectPlayer(getOpponentTeam($freeKickScorer,$match),config('PLAYER_POSITION_GOALY'),null);
					$goalyInfluence=(int)Config('sim_goaly_influence');
					$shootReduction=round($goaly->getTotalStrength($this->_websoccer,$match)* $goalyInfluence/100);
					$shootStrength=$freeKickScorer->getTotalStrength($this->_websoccer,$match);
					$pGoal[TRUE]=max(1,min($shootStrength - $shootReduction,60));
					$pGoal[FALSE]=100 - $pGoal[TRUE];
					$freeKickResult=selectItemFromProbabilities($pGoal);
					foreach($this->_observers as$observer)$observer->onFreeKick($match,$freeKickScorer,$goaly,$freeKickResult);
					if($freeKickResult)$this->_kickoff($match,$freeKickScorer);
					else $match->setPlayerWithBall($goaly);}}}
		else{
			$match->setPlayerWithBall($opponent);
			if($player->position==config('PLAYER_POSITION_STRIKER')&&$opponent->team->counterattacks){
				$counterAttempt[TRUE]=$player->team->offensive;
				$counterAttempt[FALSE]=100 - $counterAttempt[TRUE];
				if(selectItemFromProbabilities($counterAttempt)){
					if($opponent->position==config('PLAYER_POSITION_DEFENCE'))$match->setPlayerWithBall(selectPlayer($opponent->team,config('PLAYER_POSITION_STRIKER')));
					$this->shoot($match);}}}
		return$result;}
	function shoot(SimulationMatch $match){
		$player=$match->getPlayerWithBall();
		$goaly=selectPlayer(getOpponentTeam($player,$match),config('PLAYER_POSITION_GOALY'),null);
		$goalyInfluence=(int)Config('sim_goaly_influence');
		$shootReduction=round($goaly->getTotalStrength($this->_websoccer,$match)* $goalyInfluence/100);
		$shootStrength=round($player->getTotalStrength($this->_websoccer,$match)* $this->_shootStrengthPerPosition[$player->position] / 100);
		if($player->position!=config('PLAYER_POSITION_STRIKER')||isset($player->team->positionsAndPlayers[config('PLAYER_POSITION_STRIKER')])&&count($player->team->positionsAndPlayers[config('PLAYER_POSITION_STRIKER')])>1)$shootStrength=$shootStrength +
			$player->getShoots()* 2 - $player->getGoals();
		if($player->getGoals()>1)$shootStrength=round($shootStrength / $player->getGoals());
		$pGoal[TRUE]=max(1,min($shootStrength - $shootReduction,60));
		$pGoal[FALSE]=100 - $pGoal[TRUE];
		$result=selectItemFromProbabilities($pGoal);
		if($result==FALSE){
			foreach($this->_observers as$observer)$observer->onShootFailure($match,$player,$goaly);
			$match->setPlayerWithBall($goaly);
			$pCorner[TRUE]=round($player->strength / 2);
			$pCorner[FALSE]=100 - $pCorner[TRUE];
			if(selectItemFromProbabilities($pCorner)){
				if($player->team->freeKickPlayer)$passingPlayer=$player->team->freeKickPlayer;
				else $passingPlayer=selectPlayer($player->team,config('PLAYER_POSITION_MIDFIELD'));
				$targetPlayer=selectPlayer($player->team,config('PLAYER_POSITION_MIDFIELD'),$passingPlayer);
				foreach($this->_observers as$observer)$observer->onCorner($match,$passingPlayer,$targetPlayer);
				$match->setPlayerWithBall($targetPlayer);}}
		else{
			foreach($this->_observers as$observer)$observer->onGoal($match,$player,$goaly);
			$this->_kickoff($match,$player);}
		return$result;}
	function penaltyShooting(SimulationMatch $match){
		$shots=0;
		$goalsHome=0;
		$goalsGuest=0;
		$playersHome=getPlayersForPenaltyShooting($match->homeTeam);
		$playersGuest=getPlayersForPenaltyShooting($match->guestTeam);
		$playerIndexHome=0;
		$playerIndexGuest=0;
		while($shots <= 50){
			++$shots;
			if($this->_shootPenalty($match,$playersHome[$playerIndexHome]))++$goalsHome;
			if($this->_shootPenalty($match,$playersGuest[$playerIndexGuest]))++$goalsGuest;
			if($shots >= 5 &&$goalsHome !==$goalsGuest)return TRUE;
			++$playerIndexHome;
			++$playerIndexGuest;
			if($playerIndexHome >= count($playersHome))$playerIndexHome=0;
			if($playerIndexGuest >= count($playersGuest))$playerIndexGuest=0;}}
	function foulPenalty(SimulationMatch $match,SimulationTeam $team){
		$players=getPlayersForPenaltyShooting($team);
		$player=$players[0];
		$match->setPlayerWithBall($player);
		if($this->_shootPenalty($match,$player))$player->setGoals($player->getGoals()+ 1);
		else{
			$goaly=selectPlayer(getOpponentTeam($player,$match),config('PLAYER_POSITION_GOALY'),null);
			$match->setPlayerWithBall($goaly);}}
	function _setPassTargetProbabilities(){
		$this->_passTargetProbPerPosition[config('PLAYER_POSITION_GOALY')][config('PLAYER_POSITION_GOALY')]=0;
		$this->_passTargetProbPerPosition[config('PLAYER_POSITION_GOALY')][config('PLAYER_POSITION_DEFENCE')]=69;
		$this->_passTargetProbPerPosition[config('PLAYER_POSITION_GOALY')][config('PLAYER_POSITION_MIDFIELD')]=30;
		$this->_passTargetProbPerPosition[config('PLAYER_POSITION_GOALY')][config('PLAYER_POSITION_STRIKER')]=1;
		$this->_passTargetProbPerPosition[config('PLAYER_POSITION_DEFENCE')][config('PLAYER_POSITION_GOALY')]=10;
		$this->_passTargetProbPerPosition[config('PLAYER_POSITION_DEFENCE')][config('PLAYER_POSITION_DEFENCE')]=20;
		$this->_passTargetProbPerPosition[config('PLAYER_POSITION_DEFENCE')][config('PLAYER_POSITION_MIDFIELD')]=65;
		$this->_passTargetProbPerPosition[config('PLAYER_POSITION_DEFENCE')][config('PLAYER_POSITION_STRIKER')]=5;
		$this->_passTargetProbPerPosition[config('PLAYER_POSITION_MIDFIELD')][config('PLAYER_POSITION_GOALY')]=1;
		$this->_passTargetProbPerPosition[config('PLAYER_POSITION_MIDFIELD')][config('PLAYER_POSITION_DEFENCE')]=24;
		$this->_passTargetProbPerPosition[config('PLAYER_POSITION_MIDFIELD')][config('PLAYER_POSITION_MIDFIELD')]=55;
		$this->_passTargetProbPerPosition[config('PLAYER_POSITION_MIDFIELD')][config('PLAYER_POSITION_STRIKER')]=20;
		$this->_passTargetProbPerPosition[config('PLAYER_POSITION_STRIKER')][config('PLAYER_POSITION_GOALY')]=0;
		$this->_passTargetProbPerPosition[config('PLAYER_POSITION_STRIKER')][config('PLAYER_POSITION_DEFENCE')]=10;
		$this->_passTargetProbPerPosition[config('PLAYER_POSITION_STRIKER')][config('PLAYER_POSITION_MIDFIELD')]=60;
		$this->_passTargetProbPerPosition[config('PLAYER_POSITION_STRIKER')][config('PLAYER_POSITION_STRIKER')]=30;}
	function _setOpponentPositions(){
		$this->_opponentPositions[config('PLAYER_POSITION_GOALY')]=config('PLAYER_POSITION_STRIKER');
		$this->_opponentPositions[config('PLAYER_POSITION_DEFENCE')]=config('PLAYER_POSITION_STRIKER');
		$this->_opponentPositions[config('PLAYER_POSITION_MIDFIELD')]=config('PLAYER_POSITION_MIDFIELD');
		$this->_opponentPositions[config('PLAYER_POSITION_STRIKER')]=config('PLAYER_POSITION_DEFENCE');}
	function _setShootProbPerPosition(){
		$this->_shootProbPerPosition[config('PLAYER_POSITION_GOALY')]=0;
		$this->_shootProbPerPosition[config('PLAYER_POSITION_DEFENCE')]=5;
		$this->_shootProbPerPosition[config('PLAYER_POSITION_MIDFIELD')]=20;
		$this->_shootProbPerPosition[config('PLAYER_POSITION_STRIKER')]=35;}
	function _setShootStrengthPerPosition(){
		$this->_shootStrengthPerPosition[config('PLAYER_POSITION_GOALY')]=10;
		$this->_shootStrengthPerPosition[config('PLAYER_POSITION_DEFENCE')]=Config('sim_shootstrength_defense');
		$this->_shootStrengthPerPosition[config('PLAYER_POSITION_MIDFIELD')]=Config('sim_shootstrength_midfield');
		$this->_shootStrengthPerPosition[config('PLAYER_POSITION_STRIKER')]=Config('sim_shootstrength_striker');}
	function _getOffensiveStrength($team,$match){
		$strength=0;
		if(isset($team->positionsAndPlayers[config('PLAYER_POSITION_MIDFIELD')])){
			$omPlayers=0;
			foreach($team->positionsAndPlayers[config('PLAYER_POSITION_MIDFIELD')] as$player){
				$mfStrength=$player->getTotalStrength($this->_websoccer,$match);
				if($player->mainPosition=='OM'){
					++$omPlayers;
					if($omPlayers <= 3)$mfStrength=$mfStrength*1.3;
					else $mfStrength=$mfStrength*0.5;}
				elseif($player->mainPosition=='DM')$mfStrength=$mfStrength*0.7;
				$strength += $mfStrength;}}
		$noOfStrikers=0;
		if(isset($team->positionsAndPlayers[config('PLAYER_POSITION_STRIKER')])){
			foreach($team->positionsAndPlayers[config('PLAYER_POSITION_STRIKER')] as$player){
				++$noOfStrikers;
				if($noOfStrikers<3)$strength += $player->getTotalStrength($this->_websoccer,$match)* 1.5;
				else $strength += $player->getTotalStrength($this->_websoccer,$match)* 0.5;}}
		$offensiveFactor=(80 + $team->offensive*0.4)/ 100;
		$strength=$strength*$offensiveFactor;
		return$strength;}
	function _getDefensiveStrength(SimulationTeam $team,$match){
		$strength=0;
		foreach($team->positionsAndPlayers[config('PLAYER_POSITION_MIDFIELD')] as$player){
			$mfStrength=$player->getTotalStrength($this->_websoccer,$match);
			if($player->mainPosition=='OM')$mfStrength=$mfStrength*0.7;
			elseif($player->mainPosition=='DM')$mfStrength=$mfStrength*1.3;
			if($team->counterattacks)$mfStrength=$mfStrength*1.1;
			$strength += $mfStrength;}
		$noOfDefence=0;
		foreach($team->positionsAndPlayers[config('PLAYER_POSITION_DEFENCE')] as$player){
			++$noOfDefence;
			$strength += $player->getTotalStrength($this->_websoccer,$match);}
		if($noOfDefence<3)$strength=$strength*0.5;
		elseif($noOfDefence>4)$strength=$strength*1.5;
		$offensiveFactor=(130 - $team->offensive*0.5)/ 100;
		$strength=$strength*$offensiveFactor;
		return$strength;}
			function _shootPenalty(SimulationMatch$match,SimulationPlayer$player){$goaly=selectPlayer(getOpponentTeam($player,$match),config('PLAYER_POSITION_GOALY'),null);$goalyInfluence=(int)Config('sim_goaly_influence');
					$shootReduction=round($goaly->getTotalStrength($this->_websoccer,$match)*$goalyInfluence/100);$pGoal[TRUE]=max(30,min($player->strength-$shootReduction,80));$pGoal[FALSE]=100-$pGoal[TRUE];$result=selectItemFromProbabilities($pGoal);
					foreach($this->_observers as$observer)$observer->onPenaltyShoot($match,$player,$goaly,$result);return$result;}
			function _kickoff(SimulationMatch$match,SimulationPlayer$scorer){$match->setPlayerWithBall(selectPlayer(getOpponentTeam($scorer,$match),config('PLAYER_POSITION_DEFENCE'),null));}}
class FacebookSdk {
	private static $_instance;
	private $_facebook;
	private $_websoccer;
	private $_userEmail;
	static function getInstance($websoccer){
		if(self::$_instance==NULL)self::$_instance=new FacebookSdk($websoccer);
		return self::$_instance;}
	function __construct($websoccer){
		$this->_websoccer=$websoccer;
		$this->_facebook=new Facebook(array('appId'=>Config('facebook_appid'),'secret'=>Config('facebook_appsecret')));}
	function getLoginUrl(){ return$this->_facebook->getLoginUrl(array('scope'=>'email','redirect_uri'=>aUrl('facebook-login',null,'home',TRUE)));}
	function getLogoutUrl(){ return$this->_facebook->getLogoutUrl(array('next'=>aUrl('facebook-logout',null,'home',TRUE)));}
	function getUserEmail(){
		if($this->_userEmail==NULL){
			$userId=$this->_facebook->getUser();
			if($userId){
				if(isset($_SESSION['fbemail'])){
					$this->_userEmail=$_SESSION['fbemail'];
					return$this->_userEmail;}
				try{$fql='SELECT email from user where uid='.$userId;
					$ret_obj=$this->_facebook->api(array('method'=>'fql.query', 'query'=>$fql,));
					$this->_userEmail=$ret_obj[0]['email'];
					$_SESSION['fbemail']=$this->_userEmail;}
				catch(FacebookApiException $e){ $this->_userEmail='';}}
			else $this->_userEmail='';}
		return$this->_userEmail;}
	function destroySession(){ $this->_facebook->destroySession();}}
class FileWriter {private$_filePointer;
	  		function __construct($file,$truncateExistingFile=TRUE){$this->_filePointer=@fopen($file,($truncateExistingFile)?'w':'a');if($this->_filePointer===FALSE)throw new Exception('Could not create or open file '.
	  						$file.'! Verify that the file or its folder is writable.');}
			function writeLine($line){if(@fwrite($this->_filePointer,$line.PHP_EOL)===FALSE)throw new Exception('Could not write line \''.$line.'\' into file '.$file.'!');}
			function close(){if($this->_filePointer===FALSE)@fclose($this->_filePointer);}
	function __destruct(){ $this->close();}}
class FrontMessage{
			function __construct($type,$title,$message){if($type!=='info'&&$type!=='success'&&$type!=='error'&&$type!=='warning')throw new Exception('unknown FrontMessage type: '.$type);$this->type=$type;$this->title=$title;$this->message=$message;}}
							require_once($_SERVER['DOCUMENT_ROOT'].'/classes/googleapi/Google_Client.php');
class GoogleplusSdk{private static$_instance;private$_client,$_websoccer,$_oauth2Service;
	 static function getInstance($websoccer){if(self::$_instance==NULL)self::$_instance=new GoogleplusSdk($websoccer);return self::$_instance;}
			function __construct($websoccer){$this->_websoccer=$websoccer;
		$client=new Google_Client();
		$client->setApplicationName(Config('googleplus_appname'));
		$client->setClientId(Config('googleplus_clientid'));
		$client->setClientSecret(Config('googleplus_clientsecret'));
		$client->setRedirectUri($this->_websoccer->getInternalActionUrl('googleplus-login', null, 'home', TRUE));
		$client->setDeveloperKey(Config('googleplus_developerkey'));
		$client->setScopes(array('https://www.googleapis.com/auth/plus.login', 'https://www.googleapis.com/auth/userinfo.email'));
		$this->_oauth2Service=new Google_Oauth2Service($client);
		$this->_client=$client;}
	function getLoginUrl(){ return$this->_client->createAuthUrl();}
	function authenticateUser(){
		if(isset($_GET['code'])){
			$this->_client->authenticate();
			$_SESSION['gptoken']=$this->_client->getAccessToken();}
		if(isset($_SESSION['gptoken']))$this->_client->setAccessToken($_SESSION['gptoken']);
		if($this->_client->getAccessToken()){
			$userinfo=$this->_oauth2Service->userinfo->get();
			$email=$userinfo['email'];
			$_SESSION['gptoken']=$this->_client->getAccessToken();
			if(strlen($email))return$email;}
		return FALSE;}}
class I18n{private static$_instance;private$_currentLanguage,$_supportedLanguages;
	 static function getInstance($supportedLanguages){if(self::$_instance==NULL)self::$_instance=new I18n($supportedLanguages);return self::$_instance;}
			function __construct($supportedLanguages){$this->_supportedLanguages=array_map('trim',explode(',',$supportedLanguages));}
			function getCurrentLanguage(){if($this->_currentLanguage==null){if(isset($_SESSION['lang']))$lang=$_SESSION['lang'];elseif(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))$lang=strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2));
							else$lang=$this->_supportedLanguages[0];if(!in_array($lang,$this->_supportedLanguages))$lang=$this->_supportedLanguages[0];$this->_currentLanguage=$lang;}return$this->_currentLanguage;}
			function setCurrentLanguage($language){if($language==$this->_currentLanguage)return;$lang=strtolower($language);if(!in_array($lang,$this->_supportedLanguages))$lang=$this->getCurrentLanguage();$_SESSION['lang']=$lang;$this->_currentLanguage=$lang;}
			function getMessage($messageKey,$paramaters=null){global$msg;if(!isset($msg[$messageKey]))return'MessageKey: '.$messageKey.' is not defined!';$message=stripslashes($msg[$messageKey]);if($paramaters!=null)$message=sprintf($message,$paramaters);return$message;}
			function hasMessage($messageKey){global$msg;return isset($msg[$messageKey]);}
			function getNavigationLabel($pageId){return$this->getMessage($pageId.'_navlabel');}
			function getSupportedLanguages(){return$this->_supportedLanguages;}}
class MatchReportSimulatorObserver{private$_availableTexts,$_websoccer,$_db;
			function __construct($websoccer,$db){$this->_availableTexts=[];$this->_websoccer=$websoccer;$this->_db=$db;$fromTable=Config('db_prefix').'_spiel_text';$columns='id,aktion AS actiontype';$whereCondition='aktion=\'Auswechslung\'';
							$result=$db->querySelect($columns,$fromTable,$whereCondition);while($text=$result->fetch_array())$this->_availableTexts[$text['actiontype']][]=$text['id'];$result->free();}
			function onSubstitution(SimulationMatch$match,SimulationSubstitution$substitution){$this->_createMessage($match,'Auswechslung',[$substitution->playerIn->name,$substitution->playerOut->name],($substitution->playerIn->team->id==$match->homeTeam->id));}
			function _createMessage($match,$messageType,$playerNames=null,$isHomeActive=TRUE){if(!isset($this->_availableTexts[$messageType]))return;$texts=count($this->_availableTexts[$messageType]);$index=getMagicNumber(0,$texts-1);
							$messageId=$this->_availableTexts[$messageType][$index];$players='';if($playerNames!=null)$players=implode(';',$playerNames);$fromTable=Config('db_prefix').'_matchreport';$columns['match_id']=$match->id;$columns['minute']=$match->minute;
							$columns['message_id']=$messageId;$columns['playernames']=$players;$columns['active_home']=$isHomeActive;$this->_db->queryInsert($columns,$fromTable);}
			function onMatchCompleted(SimulationMatch$match){}
			function onBeforeMatchStarts(SimulationMatch$match){}}
class MatchReportSimulationObserver{private$_availableTexts,$_websoccer,$_db;
			function __construct($websoccer,$db){$this->_availableTexts=[];$this->_websoccer=$websoccer;$this->_db=$db;$fromTable=Config('db_prefix').'_spiel_text';$columns='id,aktion AS actiontype';
							$result=$db->querySelect($columns,$fromTable,'1=1');while($text=$result->fetch_array())$this->_availableTexts[$text['actiontype']][]=$text['id'];$result->free();}
	function onGoal(SimulationMatch$match,SimulationPlayer$scorer,SimulationPlayer$goaly){$assistPlayerName=($match->getPreviousPlayerWithBall()!==NULL&&$match->getPreviousPlayerWithBall()->team->id==$scorer->team->id)?$match->getPreviousPlayerWithBall()->name:'';
							if(strlen($assistPlayerName))$this->_createMessage($match,'Tor_mit_vorlage',[$scorer->name,$assistPlayerName],($scorer->team->id==$match->homeTeam->id));else$this->_createMessage($match,'Tor',[$scorer->name,$goaly->name],($scorer->team->id==
							$match->homeTeam->id));}
	function onShootFailure(SimulationMatch$match,SimulationPlayer$scorer,SimulationPlayer$goaly){if(getMagicNumber(0,1))$this->_createMessage($match,'Torschuss_daneben',[$scorer->name,$goaly->name],($scorer->team->id==$match->homeTeam->id));
							else$this->_createMessage($match,'Torschuss_auf_Tor',[$scorer->name,$goaly->name],($scorer->team->id==$match->homeTeam->id));}
	function onAfterTackle(SimulationMatch$match,SimulationPlayer$winner,SimulationPlayer$looser){if(getMagicNumber(0,1))$this->_createMessage($match,'Zweikampf_gewonnen',[$winner->name,$looser->name],($winner->team->id==$match->homeTeam->id));
							else$this->_createMessage($match,'Zweikampf_verloren',[$looser->name,$winner->name],($looser->team->id==$match->homeTeam->id));}
	function onBallPassSuccess(SimulationMatch$match,SimulationPlayer$player){}
	function onBallPassFailure(SimulationMatch$match,SimulationPlayer$player){if($player->position!='Torwart'){$targetPlayer=selectPlayer($player->team,$player->position,$player);$this->_createMessage($match,'Pass_daneben',
							[$player->name,$targetPlayer->name],($player->team->id==$match->homeTeam->id));}}
	function onInjury(SimulationMatch$match,SimulationPlayer$player,$numberOfMatches){$this->_createMessage($match,'Verletzung',[$player->name],($player->team->id==$match->homeTeam->id));}
	function onYellowCard(SimulationMatch$match,SimulationPlayer$player){if($player->yellowCards>1)$this->_createMessage($match,'Karte_gelb_rot',[$player->name],($player->team->id==$match->homeTeam->id));else$this->_createMessage($match,'Karte_gelb',
							[$player->name],($player->team->id==$match->homeTeam->id));}
	function onRedCard(SimulationMatch$match,SimulationPlayer$player,$matchesBlocked){$this->_createMessage($match,'Karte_rot',[$player->name],($player->team->id==$match->homeTeam->id));}
	function onPenaltyShoot(SimulationMatch$match,SimulationPlayer$player,SimulationPlayer$goaly,$successful){if($successful)$this->_createMessage($match,'Elfmeter_erfolg',[$player->name,$goaly->name],($player->team->id==$match->homeTeam->id));
							else$this->_createMessage($match,'Elfmeter_verschossen',[$player->name,$goaly->name],($player->team->id==$match->homeTeam->id));}
	function onCorner(SimulationMatch $match,SimulationPlayer$concededByPlayer,SimulationPlayer$targetPlayer){$this->_createMessage($match,'Ecke',[$concededByPlayer->name,$targetPlayer->name],($concededByPlayer->team->id==$match->homeTeam->id));}
	function onFreeKick(SimulationMatch$match,SimulationPlayer$player,SimulationPlayer$goaly,$successful){if($successful)$this->_createMessage($match,'Freistoss_treffer',[$player->name,$goaly->name],($player->team->id==$match->homeTeam->id));
							else$this->_createMessage($match,'Freistoss_daneben',[$player->name,$goaly->name],($player->team->id==$match->homeTeam->id));}
	function _createMessage($match,$messageType,$playerNames=null,$isHomeActive=TRUE){if(!isset($this->_availableTexts[$messageType]))return;$texts=count($this->_availableTexts[$messageType]);$index=getMagicNumber(0,$texts-1);
							$messageId=$this->_availableTexts[$messageType][$index];$players='';if ($playerNames!=null)$players=implode(';',$playerNames);$fromTable=Config('db_prefix').'_matchreport';$columns['match_id']=$match->id;$columns['minute']=$match->minute;
							$columns['message_id']=$messageId;$columns['playernames']=$players;$columns['goals']=$match->homeTeam->getGoals().':'.$match->guestTeam->getGoals();$columns['active_home']=$isHomeActive;$this->_db->queryInsert($columns,$fromTable);}}

class NavigationItem{public$pageId,$label,$children,$isActive,$weight;
			function __construct($pageId,$label,$children,$isActive,$weight){$this->pageId=$pageId;$this->label=$label;$this->children=$children;$this->isActive=$isActive;$this->weight=$weight;}}
class Paginator {public$pages,$pageNo,$eps;private$_parameters;
			function getQueryString(){if($this->_parameters!=null)return http_build_query($this->_parameters);return'';}
			function addParameter($name,$value){$this->_parameters[$name]=$value;}
			function __construct($hits,$eps,$websoccer){$this->eps=$eps;$this->pageNo=max(1,(int)Request('pageno'));if($hits%$eps)$this->pages=floor($hits/$eps)+1;else$this->pages=$hits/$eps;}
			function getFirstIndex(){return($this->pageNo-1)*$this->eps;}}
class SessionBasedUserAuthentication{
	private $_website;
	function __construct($website){ $this->_website=$website;}
	function verifyAndUpdateCurrentUser(User $currentUser){
		$db=DbConnection::getInstance();
		$fromTable=Config('db_prefix').'_user';
		if(!isset($_SESSION['frontuserid'])|| !$_SESSION['frontuserid']){
			$rememberMe=getCookieValue('user');
			if($rememberMe!=null){
				$columns='id, passwort_salt, nick, email, lang';
				$whereCondition='status=1 AND tokenid=\'%s\'';
				$result=$db->querySelect($columns,$fromTable,$whereCondition,$rememberMe);
				$rememberedUser=$result->fetch_array();
				if(isset($rememberedUser['id'])){
					$currentToken=generateSessionToken($rememberedUser['id'],$rememberedUser['passwort_salt']);
					if($currentToken===$rememberMe){
						$this->_login($rememberedUser,$db,$fromTable,$currentUser);
						return;}
					else{
						destroyCookie('user');
						$columns=array('tokenid'=>'');
						$whereCondition='id=%d';
						$parameter=$rememberedUser['id'];
						$db->queryUpdate($columns,$fromTable,$whereCondition,$parameter);}}
				else destroyCookie('user');}
			else return;}
		$userid=(isset($_SESSION['frontuserid']))? $_SESSION['frontuserid']:0;
		if(!$userid)return;
		$columns='id, nick, email, lang, premium_balance, picture';
		$whereCondition='status=1 AND id=%d';
		$result=$db->querySelect($columns,$fromTable,$whereCondition,$userid);
		if($result->num_rows){
			$userdata=$result->fetch_array();
			$this->_login($userdata,$db,$fromTable,$currentUser);}
		else $this->logoutUser($currentUser);}
	function logoutUser(User $currentUser){
		if($currentUser->getRole()=='user'){
			$currentUser->id=null;
			$currentUser->username=null;
			$currentUser->email=null;
			$_SESSION=[];
			session_destroy();
			destroyCookie('user');}}
	function _login($userdata,$db,$fromTable,$currentUser){
		$_SESSION['frontuserid']=$userdata['id'];
		$currentUser->id=$userdata['id'];
		$currentUser->username=$userdata['nick'];
		$currentUser->email=$userdata['email'];
		$currentUser->lang=$userdata['lang'];
		$currentUser->premiumBalance=$userdata['premium_balance'];
		$currentUser->setProfilePicture($this->_website,$userdata['picture']);
		$i18n=I18n::getInstance(Config('supported_languages'));
		$i18n->setCurrentLanguage($userdata['lang']);
		$columns=array('lastonline'=>Timestamp(), 'lastaction'=>Request('page'));
		$whereCondition='id=%d';
		$parameter=$userdata['id'];
		$db->queryUpdate($columns,$fromTable,$whereCondition,$parameter);}}
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
    function __construct($id,$homeTeam,$guestTeam,$minute,$playerWithBall=null,$previousPlayerWithBall=null){
    	$this->id=$id;
    	$this->homeTeam=$homeTeam;
    	$this->guestTeam=$guestTeam;
    	$this->minute=$minute;
    	$this->playerWithBall=$playerWithBall;
    	$this->previousPlayerWithBall=$previousPlayerWithBall;
    	$this->isCompleted=FALSE;
    	$this->penaltyShootingEnabled=FALSE;
    	$this->isSoldOut=FALSE;}
    function getPlayerWithBall(){ return$this->playerWithBall;}
    function getPreviousPlayerWithBall(){ return$this->previousPlayerWithBall;}
    function setPreviousPlayerWithBall($player){ $this->previousPlayerWithBall=$player;}
    function setPlayerWithBall($player){
    	if($this->playerWithBall !==NULL &&$this->playerWithBall->id !==$player->id){
    		$player->setBallContacts($player->getBallContacts()+ 1);
    		$this->previousPlayerWithBall=$this->playerWithBall;}
    	$this->playerWithBall=$player;}
    function cleanReferences(){
    	$this->homeTeam->cleanReferences();
    	$this->guestTeam->cleanReferences();
    	unset($this->homeTeam);
    	unset($this->guestTeam);
    	unset($this->playerWithBall);
    	unset($this->previousPlayerWithBall);}}
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
    function __construct($id,$team,$position,$mainPosition,$mark,$age,$strength,$strengthTech,$strengthStamina,$strengthFreshness,$strengthSatisfaction){
    	$this->id=$id;
    	$this->team=$team;
    	$this->position=$position;
    	$this->mainPosition=$mainPosition;
    	$this->mark=$mark;
    	$this->age=$age;
    	$this->strength=$strength;
    	$this->strengthTech=$strengthTech;
    	$this->strengthStamina=$strengthStamina;
    	$this->strengthFreshness=$strengthFreshness;
    	$this->strengthSatisfaction=$strengthSatisfaction;
    	$this->injured=0;
    	$this->blocked=0;
    	$this->goals=0;
    	$this->minutesPlayed=0;
    	$this->ballContacts=0;
    	$this->wonTackles=0;
    	$this->lostTackles=0;
    	$this->shoots=0;
    	$this->passesSuccessed=0;
    	$this->passesFailed=0;
    	$this->assists=0;}
    function getTotalStrength($websoccer, SimulationMatch $match){
    	if($this->totalStrength==null||$this->needsStrengthRecomputation==TRUE)$this->recomputeTotalStrength($websoccer,$match);
    	return$this->totalStrength;}
    function getMark(){ return$this->mark;}
    function setMark($mark){
    	if($this->mark !==$mark){
    		$this->mark=$mark;
    		$this->needsStrengthRecomputation=TRUE;}}
    function improveMark($improvement){
    	$newMark=max((float)$this->mark - $improvement, 1);
    	$this->setMark($newMark);}
    function downgradeMark($downgrade){
    	$newMark=min((float)$this->mark + $downgrade, 6);
    	$this->setMark($newMark);}
    function recomputeTotalStrength($websoccer, SimulationMatch $match){
    	$mainStrength=$this->strength;
    	if($match->isSoldOut &&$this->team->id==$match->homeTeam->id)$mainStrength +=Config("sim_home_field_advantage");
    	if($this->team->noFormationSet)$mainStrength=round($mainStrength *Config("sim_createformation_strength")/ 100);
    	$weightsSum=Config("sim_weight_strength")+Config("sim_weight_strengthTech")+Config("sim_weight_strengthStamina")+Config("sim_weight_strengthFreshness")+Config("sim_weight_strengthSatisfaction");
    	$totalStrength=$mainStrength *Config("sim_weight_strength");
    	$totalStrength += $this->strengthTech *Config("sim_weight_strengthTech");
    	$totalStrength += $this->strengthStamina *Config("sim_weight_strengthStamina");
    	$totalStrength += $this->strengthFreshness *Config("sim_weight_strengthFreshness");
    	$totalStrength += $this->strengthSatisfaction *Config("sim_weight_strengthSatisfaction");
    	$totalStrength=$totalStrength / $weightsSum;
    	$totalStrength=$totalStrength*(114 - 4*$this->mark)/ 100;
    	$this->totalStrength=min(100, round($totalStrength));
    	$this->needsStrengthRecomputation=FALSE;}
    function getWonTackles(){ return$this->wonTackles;}
    function setWonTackles($wonTackles){ if($this->wonTackles !==$wonTackles)$this->wonTackles=$wonTackles;}
    function getLostTackles(){ return$this->lostTackles;}
    function setLostTackles($lostTackles){ if($this->lostTackles !==$lostTackles)$this->lostTackles=$lostTackles;}
    function getPassesSuccessed(){ return$this->passesSuccessed;}
    function setPassesSuccessed($passesSuccessed){ if($this->passesSuccessed !==$passesSuccessed)$this->passesSuccessed=$passesSuccessed;}
    function getPassesFailed(){ return$this->passesFailed;}
    function setPassesFailed($passesFailed){ if($this->passesFailed !==$passesFailed)$this->passesFailed=$passesFailed;}
    function getShoots(){ return$this->shoots;}
    function setShoots($shoots){ if($this->shoots !==$shoots)$this->shoots=$shoots;}
    function getBallContacts(){ return$this->ballContacts;}
    function setBallContacts($ballContacts){ if($this->ballContacts !==$ballContacts)$this->ballContacts=$ballContacts;}
    function getGoals(){ return$this->goals;}
    function setGoals($goals){ if($this->goals !==$goals)$this->goals=$goals;}
    function getAssists(){ return$this->assists;}
    function setAssists($assists){ if($this->assists !==$assists)$this->assists=$assists;}
    function getMinutesPlayed(){ return$this->minutesPlayed;}
    function setMinutesPlayed($minutesPlayed,$recomputeFreshness=TRUE){
    	if($this->minutesPlayed<$minutesPlayed){
    		$this->minutesPlayed=$minutesPlayed;
    		if($recomputeFreshness &&$minutesPlayed % 20==0){
     			if($minutesPlayed==20 &&$this->position==config('PLAYER_POSITION_GOALY')){
    				$this->strengthFreshness=max(1,$this->strengthFreshness - 1);
    				$this->needsStrengthRecomputation=TRUE;}
    			elseif($this->position!=config('PLAYER_POSITION_GOALY'))$this->looseFreshness();}}}
    function cleanReferences(){ unset($this->team);}
    function looseFreshness(){
    	$freshness=$this->strengthFreshness - 1;
    	if($this->age>32 &&$this->position!=config('PLAYER_POSITION_GOALY'))$freshness -= 1;
    	if($this->team->offensive >= 80 &&($this->position==config('PLAYER_POSITION_MIDFIELD')||$this->position==config('PLAYER_POSITION_STRIKER')))$freshness -= 1;
    	if($this->strengthStamina<40)$freshness -= 1;
    	$freshness=max(1,$freshness);
    	$this->strengthFreshness=$freshness;
    	$this->needsStrengthRecomputation=TRUE;}
    function __toString(){
    	return "{id: ".$this->id .",team: ".$this->team->id.",position: ".$this->position .",mark: ".$this->mark .",strength: ".$this->strength.",strengthTech: ".$this->strengthTech.",strengthStamina: ".$this->strengthStamina.",
    		strengthFreshness: ".$this->strengthFreshness.",strengthSatisfaction: ".$this->strengthSatisfaction."}";}}
class SimulationSubstitution {
	public $minute;
	public $playerIn;
	public $playerOut;
	public $condition;
	public $position;
    function __construct($minute,$playerIn,$playerOut,$condition=null,$position=null){
    	$this->minute=$minute;
    	$this->playerIn=$playerIn;
    	$this->playerOut=$playerOut;
    	$this->position=$position;
    	if($condition!=null &&in_array($condition,array('Tie', 'Leading', 'Deficit')))$this->condition=$condition;
    	else $this->condition=null;}
    function __toString(){ return '{minute: '.$this->minute.', in: '.$this->playerIn.', out: '.$this->playerOut.'}';}}
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
    function __construct($id,$offensive=null){
    	$this->id=$id;
    	$this->offensive=$offensive;
    	$this->positionsAndPlayers[config('PLAYER_POSITION_GOALY')]=[];
    	$this->positionsAndPlayers[config('PLAYER_POSITION_DEFENCE')]=[];
    	$this->positionsAndPlayers[config('PLAYER_POSITION_MIDFIELD')]=[];
    	$this->positionsAndPlayers[config('PLAYER_POSITION_STRIKER')]=[];
    	$this->goals=0;
    	$this->morale=0;
    	$this->noFormationSet=FALSE;
    	$this->longPasses=FALSE;
    	$this->counterattacks=FALSE;}
    function getGoals(){ return$this->goals;}
    function setGoals($goals){ if($this->goals !==$goals)$this->goals=$goals;}
    function removePlayer($playerToRemove){
    	$newPositionsAndAplayers=[];
    	$newPositionsAndAplayers[config('PLAYER_POSITION_GOALY')]=[];
    	$newPositionsAndAplayers[config('PLAYER_POSITION_DEFENCE')]=[];
    	$newPositionsAndAplayers[config('PLAYER_POSITION_MIDFIELD')]=[];
    	$newPositionsAndAplayers[config('PLAYER_POSITION_STRIKER')]=[];
    	foreach($this->positionsAndPlayers as$position=>$players){
    		foreach($players as$player){
    			if($player->id !==$playerToRemove->id)$newPositionsAndAplayers[$player->position][]=$player;}}
    	$this->positionsAndPlayers=$newPositionsAndAplayers;
    	$this->removedPlayers[$playerToRemove->id]=$playerToRemove;
    	if($this->freeKickPlayer!=NULL &&$this->freeKickPlayer->id==$playerToRemove->id)$this->freeKickPlayer=NULL;}
    function computeTotalStrength($websoccer, SimulationMatch $match){
    	$sum=0;
    	foreach($this->positionsAndPlayers as$position=>$players){
    		foreach($players as$player)$sum += $player->getTotalStrength($websoccer,$match);}
    	return$sum;}
    function cleanReferences(){
    	unset($this->substitutions);
    	unset($this->positionsAndPlayers);
    	unset($this->playersOnBench);
    	unset($this->removedPlayers);}}
class Simulator {
	private $_simStrategy;
	private $_observers;
	function getSimulationStrategy(){ return$this->_simStrategy;}
    function __construct($db,$websoccer){
    	$strategyClass=Config('sim_strategy');
    	if(!class_exists($strategyClass))throw new Exception('simulation strategy class not found: '.$strategyClass);
    	$this->_websoccer=$websoccer;
    	$this->_db=$db;
    	$this->_simStrategy=new $strategyClass($websoccer);
    	$this->_simStrategy->attachObserver(new DefaultSimulationObserver());
    	$this->_observers=[];}
    function attachObserver($observer){ $this->_observers[]=$observer;}
    function simulateMatch(SimulationMatch $match,$minutes){
    	if($match->minute==null)$match->minute=0;
    	if($match->minute==0){
	    	foreach($this->_observers as$observer)$observer->onBeforeMatchStarts($match);}
    	if($match->isCompleted){
    		$this->completeMatch($match);
    		return;}
    	if(!$this->_hasPlayers($match->homeTeam)|| !$this->_hasPlayers($match->guestTeam)){
    		$this->completeMatch($match);
    		return;}
    	for($simMinute=1; $simMinute <= $minutes;++$simMinute){
    		$match->minute=$match->minute + 1;
    		if($match->minute==1)$this->_simStrategy->kickoff($match);
    		else{
    			checkAndExecuteSubstitutions($match,$match->homeTeam,$this->_observers);
    			checkAndExecuteSubstitutions($match,$match->guestTeam,$this->_observers);}
    		$actionName=$this->_simStrategy->nextAction($match);
    		$this->_simStrategy->$actionName($match);
    		$this->_increaseMinutesPlayed($match->homeTeam);
    		$this->_increaseMinutesPlayed($match->guestTeam);
    		$lastMinute=90+getMagicNumber(1, 5);
    		if($match->penaltyShootingEnabled||$match->type=='Pokalspiel'){
    			if(($match->minute==91||$match->minute==121)&&($match->type!='Pokalspiel'&&$match->homeTeam->getGoals()!= $match->guestTeam->getGoals()|| $match->type=='Pokalspiel'&&
    				!checkIfExtensionIsRequired($this->_websoccer,$this->_db,$match))){
    				$this->completeMatch($match);
    				break;}
    			elseif($match->minute==121 &&($match->type!='Pokalspiel'&&$match->homeTeam->getGoals()==$match->guestTeam->getGoals()|| $match->type=='Pokalspiel'&&checkIfExtensionIsRequired($this->_websoccer,$this->_db,$match))){
    				$this->_simStrategy->penaltyShooting($match);
    				if($match->type=='Pokalspiel'){
    					if($match->homeTeam->getGoals()>$match->guestTeam->getGoals())createNextRoundMatchAndPayAwards($this->_websoccer,$this->_db,$match->homeTeam->id,$match->guestTeam->id,$match->cupName,$match->cupRoundName);
    					else createNextRoundMatchAndPayAwards($this->_websoccer,$this->_db,$match->guestTeam->id,$match->homeTeam->id,$match->cupName,$match->cupRoundName);}
    				$this->completeMatch($match);
    				break;}}
    		elseif($match->minute >= $lastMinute){
    			$this->completeMatch($match);
    			break;}}}
    function completeMatch($match){
    	$match->isCompleted=TRUE;
    	foreach($this->_observers as$observer)$observer->onMatchCompleted($match);
    	$event=new MatchCompletedEvent($this->_websoccer,$this->_db, I18n::getInstance(Config('supported_languages')),$match);
    	dispatchEvent($event);}
    function _increaseMinutesPlayed(SimulationTeam $team){
    	foreach($team->positionsAndPlayers as$position=>$players){
    		foreach($players as$player)$player->setMinutesPlayed($player->getMinutesPlayed()+ 1,Config('sim_decrease_freshness'));}}
    function _hasPlayers(SimulationTeam $team){
    	if(!is_array($team->positionsAndPlayers)|| count($team->positionsAndPlayers)==0)return FALSE;
    	$noOfPlayers=0;
    	foreach($team->positionsAndPlayers as$position=>$players){
    		foreach($players as$player)++$noOfPlayers;}
    	return($noOfPlayers>5);}}
function delDir($dir){
	if(is_dir($dir)){
		$files=scandir($dir);
		foreach($files as$file){
			if($file!="."&&$file!=".."){
				if(filetype($dir."/".$file)=="dir")delDir($dir."/".$file);
				else unlink($dir."/".$file);}}
		rmdir($dir);}}
class TemplateEngine{
	private$_environment;
	function __construct(WebSoccer$env,I18n$i18n,ViewHandler$viewHandler=null){
		$this->_skin=$env->getSkin();
		$this->_initTwig();
		$this->_environment->addGlobal('i18n',$i18n);
		$this->_environment->addGlobal('env',$env);
		$this->_environment->addGlobal('skin',$this->_skin);
		$this->_environment->addGlobal('viewHandler',$viewHandler);}
	function loadTemplate($templateName){return$this->_environment->load($this->_skin->getTemplate($templateName));}
	function clearCache(){delDir($_SERVER['DOCUMENT_ROOT'].'/cache/templates');}
	function getEnvironment(){return$this->_environment;}
	function _addSettingsSupport(){$function=new Twig_SimpleFunction(CONFIG_FUNCTION_NAME,function($key){global $i18n;return Message($key);});$this->_environment->addFunction($function);}
	function _initTwig(){
		$twigConfig=array('cache'=>$_SERVER['DOCUMENT_ROOT'].'/cache/templates',);
		if(version_compare(PHP_VERSION,'7.1.0')>=0){
			TwigAutoloader::register();
			$loader=new Twig\Loader\FilesystemLoader($_SERVER['DOCUMENT_ROOT'].'/templates/default');
			$this->_environment=new Twig\Environment($loader,$twigConfig);
			$this->_environment->registerUndefinedFunctionCallback(function($name){if(function_exists($name)){return new Twig\TwigFunction($name,function()use($name){return call_user_func_array($name,func_get_args());});}return false;});}
		elseif(version_compare(PHP_VERSION,'7.1.0')<0){
			require_once($_SERVER['DOCUMENT_ROOT'].'/lib/Twig/Autoloader.php');
			Twig_Autoloader::register();
			$loader=new Twig_Loader_Filesystem($_SERVER['DOCUMENT_ROOT'].'/templates/default');
			$this->_environment=new Twig_Environment($loader,$twigConfig);
			$this->_environment->registerUndefinedFunctionCallback(function($name){if(function_exists($name)){return new Twig_SimpleFunction($name,function()use($name){return call_user_func_array($name,func_get_args());});}return false;});}}}
class User {
	public $id;
	public $username;
	public $email;
	public $language;
	public $premiumBalance;
	private $_clubId;
	private $_profilePicture;
	private $_isAdmin;
	function __construct(){
		$this->premiumBalance=0;
		$this->_isAdmin=NULL;}
	function getRole(){
		if($this->id==null)return 'guest';
		else return 'user';}
	function getClubId($websoccer=null,$db=null){
		if($this->id!=null &&$this->_clubId==null){
			if(isset($_SESSION['clubid']))$this->_clubId=$_SESSION['clubid'];
			elseif($websoccer!=null &&$db!=null){
				$fromTable=Config('db_prefix').'_verein';
				$whereCondition='status=1 AND user_id=%d AND nationalteam!=\'1\' ORDER BY interimmanager DESC';
				$columns='id';
				$result=$db->querySelect($columns,$fromTable,$whereCondition,$this->id, 1);
				$club=$result->fetch_array();
				if($club)$this->setClubId($club['id']);}}
		return$this->_clubId;}
	function setClubId($clubId){
		$_SESSION['clubid']=$clubId;
		$this->_clubId=$clubId;}
	function getProfilePicture(){
		if($this->_profilePicture==null){
			if(strlen($this->email))$this->_profilePicture=UsersDataService::getUserProfilePicture(WebSoccer::getInstance(), null,$this->email);
			else $this->_profilePicture='';}
		return$this->_profilePicture;}
	function setProfilePicture($websoccer,$fileName){ if(strlen($fileName))$this->_profilePicture=UsersDataService::getUserProfilePicture($websoccer,$fileName, null);}
	function isAdmin(){
		if($this->_isAdmin===NULL){
			$websoccer=WebSoccer::getInstance();
			$db=DbConnection::getInstance();
			$result=$db->querySelect('id',Config('db_prefix').'_admin', 'email=\'%s\' AND r_admin=\'1\'',$this->email);
			if($result->num_rows)$this->_isAdmin=TRUE;
			else $this->_isAdmin=FALSE;}
		return$this->_isAdmin;}}
class ValidationException extends Exception {
	private $_messages;
    function __construct($messages){
    	$this->_messages=$messages;
    	parent::__construct('Validation failed');}
    function getMessages(){ return$this->_messages;}}
class ViewHandler {
	private $_pages;
	private $_blocks;
	private $_validationMessages;
	function __construct($website,$db,$i18n, &$pages, &$blocks,$validationMessages=null){
		$this->_website=$website;
		$this->_db=$db;
		$this->_i18n=$i18n;
		$this->_pages=$pages;
		$this->_blocks=$blocks;
		$this->_validationMessages=$validationMessages;}
	function handlePage($pageId,$parameters){
		if($pageId==NULL)return;
		if(!isset($this->_pages[$pageId]))throw new Exception(Message('error_page_not_found'));
		$pageConfig=json_decode($this->_pages[$pageId],TRUE);
		$requiredRoles=explode(',',$pageConfig['role']);
		if(!in_array($this->_website->getUser()->getRole(),$requiredRoles))throw new AccessDeniedException(Message('error_access_denied'));
		if(isset($pageConfig['premiumBalanceMin'])){
			$minPremiumBalanceRequired=(int)$pageConfig['premiumBalanceMin'];
			if($minPremiumBalanceRequired>$this->_website->getUser()->premiumBalance){
				$targetPage=Config('premium_infopage');
				if(filter_var($targetPage, FILTER_VALIDATE_URL)){
					header('location: '.$targetPage);
					exit;}
				else{
					$this->_website->addContextParameter('premium_balance_required',$minPremiumBalanceRequired);
					return$this->handlePage($targetPage,$parameters);}}}
		$template=$this->_website->getTemplateEngine($this->_i18n,$this)->loadTemplate('views/'.$pageConfig['template']);
		if(isset($pageConfig['model'])){
			$class=$pageConfig['model'];
			if(!class_exists($class))throw new Exception('The model class \''.$class.'\' does not exist.');
			$model=new $class($this->_db,$this->_i18n,$this->_website);
			if(!$model->View()||!$model->renderView())return'';
			if(!function_exists((string)$model->Template())){$parameters=array_merge($parameters,$model->Template());}
			else $parameters=array_merge($parameters,$model->getTemplateParameters());}
		$parameters['validationMsg']=$this->_validationMessages;
		$parameters['frontMessages']=$this->_website->getFrontMessages();
		$parameters['blocks']=$this->_getBlocksForPage($pageId);
		$scriptReferences=[];
		if(isset($pageConfig['scripts'])){
			foreach($pageConfig['scripts'] as$reference){
				if(((!isset($reference['productiononly'])||!$reference['productiononly']))||((!isset($reference['debugonly'])||!$reference['debugonly'])))$scriptReferences[]=$reference['file'];}}
		$parameters['scriptReferences']=$scriptReferences;
		$cssReferences=[];
		if(isset($pageConfig['csss'])){
			foreach($pageConfig['csss'] as$reference){
				if(((!isset($reference['productiononly'])||!$reference['productiononly']))||((!isset($reference['debugonly'])||!$reference['debugonly'])))$cssReferences[]=$reference['file'];}}
		$parameters['cssReferences']=$cssReferences;
		return$template->render($parameters);}
	function renderBlock($blockId,$viewConfig=null,$parameters=null){
		if($viewConfig==null){
			if(!isset($this->_blocks[$blockId]))return '';
			$viewConfig=json_decode($this->_blocks[$blockId],true);}
		if($parameters==null)$parameters=[];
		if(isset($viewConfig['model'])){
			$class=$viewConfig['model'];
			if(!class_exists($class))throw new Exception('The model class \''.$class.'\' does not exist.');
			$model=new $class($this->_db,$this->_i18n,$this->_website);
			if(!$model->View()||!$model->renderView())return'';
			if(!function_exists((string)$model->Template())){$parameters=array_merge($parameters,$model->Template());}
			else $parameters=array_merge($parameters,$model->getTemplateParameters());}
		$userRole=$this->_website->getUser()->getRole();
		$roles=explode(',',$viewConfig['role']);
		if(!in_array($userRole,$roles))return '';
		$minPremiumBalanceRequired=(isset($viewConfig['premiumBalanceMin']))? $viewConfig['premiumBalanceMin']:0;
		if($minPremiumBalanceRequired>$this->_website->getUser()->premiumBalance)return '';
		$parameters['validationMsg']=$this->_validationMessages;
		$parameters['frontMessages']=$this->_website->getFrontMessages();
		$template=$this->_website->getTemplateEngine($this->_i18n,$this)->loadTemplate('blocks/'.$viewConfig['template']);
		$parameters['blockId']=$blockId;
		$output=$template->render($parameters);
		return$output;}
	function _getBlocksForPage($pageId){
		$blocks=[];
		$userRole=$this->_website->getUser()->getRole();
		foreach($this->_blocks as$blockId=>$blockData){
			$blockConfig=json_decode($blockData, TRUE);
			$includepages=explode(',',$blockConfig['includepages']);
			$excludepages=(isset($blockConfig['excludepages']))? explode(',',$blockConfig['excludepages']): array();
			$roles=explode(',',$blockConfig['role']);
			$minPremiumBalanceRequired=(isset($blockConfig['premiumBalanceMin']))? $blockConfig['premiumBalanceMin']:0;
			if(in_array($userRole,$roles)&&($includepages[0]=='all'&&!in_array($pageId,$excludepages)|| in_array($pageId,$includepages))&&$minPremiumBalanceRequired <= $this->_website->getUser()->premiumBalance)$blocks[$blockConfig['area']][]=$blockConfig;}
		foreach($blocks as$uiblock=>$blockdata){
			if($uiblock!='custom')usort($blocks[$uiblock],['ViewHandler','sortByWeight']);}
		return$blocks;}
	static function sortByWeight(&$a, &$b){
		if(!isset($a['weight'])|| !isset($b['weight']))return 0;
		return$a['weight'] - $b['weight'];}}
class YouthMatchDataUpdateSimulatorObserver {
	function __construct($websoccer,$db){
		$this->_websoccer=$websoccer;
		$this->_db=$db;}
	function onBeforeMatchStarts(SimulationMatch $match){}
	function onSubstitution(SimulationMatch $match, SimulationSubstitution $substitution){
		YouthMatchesDataService::createMatchReportItem($this->_websoccer,$this->_db,$match->id,$match->minute, 'ymreport_substitution',array('in'=>$substitution->playerIn->name, 'out'=>$substitution->playerOut->name),
			$substitution->playerIn->team->id==$match->homeTeam->id);}
	function onMatchCompleted(SimulationMatch $match){
		$this->_updateTeam($match,$match->homeTeam);
		$this->_updateTeam($match,$match->guestTeam);
		$columns=array('home_noformation'=>($match->homeTeam->noFormationSet)? '1':'0','home_goals'=>$match->homeTeam->getGoals(),'guest_noformation'=>($match->guestTeam->noFormationSet)? '1':'0','guest_goals'=>$match->guestTeam->getGoals(),'simulated'=>'1');
		$this->_db->queryUpdate($columns,Config('db_prefix').'_youthmatch', 'id=%d',$match->id);}
	function _updateTeam(SimulationMatch $match, SimulationTeam $team){
		$salary=YouthPlayersDataService::computeSalarySumOfYouthPlayersOfTeam($this->_websoccer,$this->_db,$team->id);
		if($salary)debitAmount($this->_websoccer,$this->_db,$team->id,$salary, 'youthteam_salarypayment_subject', 'match_salarypayment_sender');
		if(is_array($team->positionsAndPlayers)){
			foreach($team->positionsAndPlayers as$position=>$players){
				foreach($players as$player)$this->_updatePlayer($match,$player, TRUE);}}
		if(is_array($team->removedPlayers)){
			foreach($team->removedPlayers as$player)$this->_updatePlayer($match,$player, FALSE);}}
	function _updatePlayer(SimulationMatch $match, SimulationPlayer $player,$isOnPitch){
		$columns=array('name'=>$player->name, 'position_main'=>$player->mainPosition, 'grade'=>$player->getMark(), 'minutes_played'=>$player->getMinutesPlayed(), 'card_yellow'=>$player->yellowCards, 'card_red'=>$player->redCard,
			'goals'=>$player->getGoals(), 'strength'=>$player->strength, 'ballcontacts'=>$player->getBallContacts(), 'wontackles'=>$player->getWonTackles(), 'shoots'=>$player->getShoots(), 'passes_successed'=>$player->getPassesSuccessed(),
			'passes_failed'=>$player->getPassesFailed(), 'assists'=>$player->getAssists(), 'state'=>($isOnPitch)? '1' : 'Ausgewechselt');
		$this->_db->queryUpdate($columns,Config('db_prefix').'_youthmatch_player', 'match_id=%d AND player_id=%d',array($match->id,$player->id));
		if(Config('sim_played_min_minutes')<= $player->getMinutesPlayed()){
			$result=$this->_db->querySelect('*',Config('db_prefix').'_youthplayer', 'id=%d',$player->id);
			$playerinfo=$result->fetch_array();
			$strengthChange=$this->_computeStrengthChange($player);
			$event=new YouthPlayerPlayedEvent($this->_websoccer,$this->_db, I18n::getInstance(Config('supported_languages')),$player,$strengthChange);
			dispatchEvent($event);
			$yellowRedCards=0;
			if($player->yellowCards==2){
				$yellowCards=1;
				$yellowRedCards=1;}
			else $yellowCards=$player->yellowCards;
			$strength=$playerinfo['strength'] + $strengthChange;
			$maxStrength=Config('youth_scouting_max_strength');
			$minStrength=Config('youth_scouting_min_strength');
			if($strength>$maxStrength){
				$strengthChange=0;
				$strength=$maxStrength;}
			elseif($strength<$minStrength){
				$strengthChange=0;
				$strength=$minStrength;}
			$columns=array('strength'=>$strength, 'strength_last_change'=>$strengthChange, 'st_goals'=>$playerinfo['st_goals'] + $player->getGoals(), 'st_matches'=>$playerinfo['st_matches'] + 1, 'st_assists'=>$playerinfo['st_assists'] + $player->getAssists(),
				'st_cards_yellow'=>$playerinfo['st_cards_yellow'] + $yellowCards, 'st_cards_yellow_red'=>$playerinfo['st_cards_yellow_red'] + $yellowRedCards, 'st_cards_red'=>$playerinfo['st_cards_red'] + $player->redCard);
			$this->_db->queryUpdate($columns,Config('db_prefix').'_youthplayer', 'id=%d',$player->id);}}
	function _computeStrengthChange(SimulationPlayer $player){
		$mark=$player->getMark();
		if($mark <= 1.3)return Config('youth_strengthchange_verygood');
		elseif($mark <= 2.3)return Config('youth_strengthchange_good');
		elseif($mark>4.25 &&$mark <= 5)return Config('youth_strengthchange_bad');
		elseif($mark>5)return Config('youth_strengthchange_verybad');
		return 0;}}
class YouthMatchReportSimulationObserver {
	function __construct($websoccer,$db){
		$this->_websoccer=$websoccer;
		$this->_db=$db;}
	function onGoal(SimulationMatch $match, SimulationPlayer $scorer, SimulationPlayer $goaly){
		YouthMatchesDataService::createMatchReportItem($this->_websoccer,$this->_db,$match->id,$match->minute, 'ymreport_goal',array('scorer'=>$scorer->name),$scorer->team->id==$match->homeTeam->id);}
	function onShootFailure(SimulationMatch $match, SimulationPlayer $scorer, SimulationPlayer $goaly){
		YouthMatchesDataService::createMatchReportItem($this->_websoccer,$this->_db,$match->id,$match->minute, 'ymreport_attempt',array('scorer'=>$scorer->name),$scorer->team->id==$match->homeTeam->id);}
	function onAfterTackle(SimulationMatch $match, SimulationPlayer $winner, SimulationPlayer $looser){
		YouthMatchesDataService::createMatchReportItem($this->_websoccer,$this->_db,$match->id,$match->minute, 'ymreport_tackle',array('winner'=>$winner->name, 'loser'=>$looser->name),$winner->team->id==$match->homeTeam->id);}
	function onBallPassSuccess(SimulationMatch $match, SimulationPlayer $player){}
	function onBallPassFailure(SimulationMatch $match, SimulationPlayer $player){}
	function onInjury(SimulationMatch $match, SimulationPlayer $player,$numberOfMatches){
		YouthMatchesDataService::createMatchReportItem($this->_websoccer,$this->_db,$match->id,$match->minute, 'ymreport_injury',array('player'=>$player->name),$player->team->id==$match->homeTeam->id);}
	function onYellowCard(SimulationMatch $match, SimulationPlayer $player){
		if($player->yellowCards>1)YouthMatchesDataService::createMatchReportItem($this->_websoccer,$this->_db,$match->id,$match->minute, 'ymreport_card_yellowred',array('player'=>$player->name),$player->team->id==$match->homeTeam->id);
		else YouthMatchesDataService::createMatchReportItem($this->_websoccer,$this->_db,$match->id,$match->minute, 'ymreport_card_yellow',array('player'=>$player->name),$player->team->id==$match->homeTeam->id);}
	function onRedCard(SimulationMatch $match, SimulationPlayer $player,$matchesBlocked){
		YouthMatchesDataService::createMatchReportItem($this->_websoccer,$this->_db,$match->id,$match->minute, 'ymreport_card_red',array('player'=>$player->name),$player->team->id==$match->homeTeam->id);}
	function onPenaltyShoot(SimulationMatch $match, SimulationPlayer $player, SimulationPlayer $goaly,$successful){
		if($successful)YouthMatchesDataService::createMatchReportItem($this->_websoccer,$this->_db,$match->id,$match->minute, 'ymreport_penalty_success',array('player'=>$player->name),$player->team->id==$match->homeTeam->id);
		else YouthMatchesDataService::createMatchReportItem($this->_websoccer,$this->_db,$match->id,$match->minute, 'ymreport_penalty_failure',array('player'=>$player->name),$player->team->id==$match->homeTeam->id);}
	function onCorner(SimulationMatch $match, SimulationPlayer $concededByPlayer, SimulationPlayer $targetPlayer){}
	function onFreeKick(SimulationMatch $match, SimulationPlayer $player, SimulationPlayer $goaly,$successful){ if($successful)$this->onGoal($match,$player,$goaly);}}
class Controller {
	function __construct($i18n,$websoccer,$db){
		$this->_i18n=$i18n;
		$this->_websoccer=$websoccer;
		$this->_db=$db;}}
class AcceptStadiumConstructionWorkController extends Controller {
	function executeAction($parameters){
		$user=$this->_websoccer->getUser();
		$clubId=$user->getClubId($this->_websoccer,$this->_db);
		$construction=StadiumsDataService::getCurrentConstructionOrderOfTeam($this->_websoccer,$this->_db,$clubId);
		if($construction==NULL||$construction["deadline"]>Timestamp())throw new Exception(Message("stadium_acceptconstruction_err_nonedue"));
		$pStatus["completed"]=$construction["builder_reliability"];
		$pStatus["notcompleted"]=100 - $pStatus["completed"];
		$constructionResult=selectItemFromProbabilities($pStatus);
		if($constructionResult=="notcompleted"){
			$newDeadline=Timestamp()+Config("stadium_construction_delay")* 24*3600;
			$this->_db->queryUpdate(array("deadline"=>$newDeadline),Config("db_prefix")."_stadium_construction","id=%d",$construction["id"]);
			$this->_websoccer->addFrontMessage(new FrontMessage('warning',Message("stadium_acceptconstruction_notcompleted_title"),Message("stadium_acceptconstruction_notcompleted_details")));}
		else{
			$stadium=StadiumsDataService::getStadiumByTeamId($this->_websoccer,$this->_db,$clubId);
			$columns=[];
			$columns["p_steh"]=$stadium["places_stands"] + $construction["p_steh"];
			$columns["p_sitz"]=$stadium["places_seats"] + $construction["p_sitz"];
			$columns["p_haupt_steh"]=$stadium["places_stands_grand"] + $construction["p_haupt_steh"];
			$columns["p_haupt_sitz"]=$stadium["places_seats_grand"] + $construction["p_haupt_sitz"];
			$columns["p_vip"]=$stadium["places_vip"] + $construction["p_vip"];
			$this->_db->queryUpdate($columns,Config("db_prefix")."_stadion","id=%d",$stadium["stadium_id"]);
			$this->_db->queryDelete(Config("db_prefix")."_stadium_construction","id=%d",$construction["id"]);
			$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("stadium_acceptconstruction_completed_title"),Message("stadium_acceptconstruction_completed_details")));}
		return null;}}
class AcceptYouthMatchRequestController extends Controller {
	function executeAction($parameters){
		if(!Config("youth_enabled")|| !Config("youth_matchrequests_enabled"))return NULL;
		$user=$this->_websoccer->getUser();
		$clubId=$user->getClubId($this->_websoccer,$this->_db);
		$fromTable=Config("db_prefix")."_youthmatch_request";
		$result=$this->_db->querySelect("*",$fromTable,"id=%d",$parameters["id"]);
		$request=$result->fetch_array();
		if(!$request)throw new Exception(Message("youthteam_matchrequest_cancel_err_notfound"));
		if($clubId==$request["team_id"])throw new Exception(Message("youthteam_matchrequest_accept_err_ownrequest"));
		if(YouthPlayersDataService::countYouthPlayersOfTeam($this->_websoccer,$this->_db,$clubId)< 11)throw new Exception(Message("youthteam_matchrequest_create_err_notenoughplayers"));
		$maxMatchesPerDay=Config("youth_match_maxperday");
		if(YouthMatchesDataService::countMatchesOfTeamOnSameDay($this->_websoccer,$this->_db,$clubId,$request["matchdate"])>= $maxMatchesPerDay)throw new Exception(Message("youthteam_matchrequest_err_maxperday_violated",$maxMatchesPerDay));
		$homeTeam=TeamsDataService::getTeamSummaryById($this->_websoccer,$this->_db,$request["team_id"]);
		$guestTeam=TeamsDataService::getTeamSummaryById($this->_websoccer,$this->_db,$clubId);
		if($request["reward"]){
			debitAmount($this->_websoccer,$this->_db,$request["team_id"],$request["reward"],"youthteam_matchrequest_reward_subject",$guestTeam["team_name"]);
			creditAmount($this->_websoccer,$this->_db,$clubId,$request["reward"],"youthteam_matchrequest_reward_subject",$homeTeam["team_name"]);}
		$this->_db->queryInsert(array("matchdate"=>$request["matchdate"],"home_team_id"=>$request["team_id"],"guest_team_id"=>$clubId),Config("db_prefix")."_youthmatch");
		$this->_db->queryDelete($fromTable,"id=%d",$parameters["id"]);
		NotificationsDataService::createNotification($this->_websoccer,$this->_db,$homeTeam["user_id"],"youthteam_matchrequest_accept_notification",array("team"=>$guestTeam["team_name"],"date"=>Datetime($request["matchdate"])),
			"youthmatch_accept","youth-matches",null,$request["team_id"]);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("youthteam_matchrequest_accept_success"),Message("youthteam_matchrequest_accept_success_details")));
		return "youth-matches";}}
class AddNationalPlayerController extends Controller {
	function executeAction($parameters){
		if(!Config("nationalteams_enabled"))return NULL;
		$teamId=NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer,$this->_db);
		if(!$teamId)throw new Exception(Message("nationalteams_user_requires_team"));
		$result=$this->_db->querySelect("name",Config("db_prefix")."_verein","id=%d",$teamId);
		$team=$result->fetch_array();
		$fromTable=Config("db_prefix")."_spieler";
		$result=$this->_db->querySelect("nation",$fromTable,"id=%d",$parameters["id"]);
		$player=$result->fetch_array();
		if(!$player)throw new Exception(Message('error_page_not_found'));
		if($player["nation"]!=$team["name"])throw new Exception("Player is from different nation.");
		$fromTable=Config("db_prefix")."_nationalplayer";
		$result=$this->_db->querySelect("COUNT(*)AS hits",$fromTable,"player_id=%d",$parameters["id"]);
		$players=$result->fetch_array();
		if($players &&$players["hits"])throw new Exception(Message("nationalteams_addplayer_err_alreadyinteam"));
		$result=$this->_db->querySelect("COUNT(*)AS hits",$fromTable,"team_id=%d",$teamId);
		$existingplayers=$result->fetch_array();
		if($existingplayers["hits"] >= 30)throw new Exception(Message("nationalteams_addplayer_err_toomanyplayer",30));
		$this->_db->queryInsert(array("team_id"=>$teamId,"player_id"=>$parameters["id"]),$fromTable);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("nationalteams_addplayer_success"),""));
		return "nationalteam";}}
class BookCampController extends Controller {
	function executeAction($parameters){
		$now=Timestamp();
		$user=$this->_websoccer->getUser();
		$teamId=$user->getClubId($this->_websoccer,$this->_db);
		if($teamId<1)throw new Exception($Message("feature_requires_team"));
		$team=TeamsDataService::getTeamSummaryById($this->_websoccer,$this->_db,$teamId);
		$min=Config("trainingcamp_min_days");
		$max=Config("trainingcamp_max_days");
		if($parameters["days"]<$min||$parameters["days"]>$max)throw new Exception(sprintf(Message("trainingcamp_booking_err_invaliddays"),$min,$max));
		$startDateObj=DateTime::createFromFormat(Config("date_format")." H:i",$parameters["start_date"]." 00:00");
		$startDateTimestamp=$startDateObj->getTimestamp();
		$endDateTimestamp=$startDateTimestamp + 3600*24*$parameters["days"];
		if($startDateTimestamp <= $now)throw new Exception(Message("trainingcamp_booking_err_dateinpast"));
		$maxDate=$now +Config("trainingcamp_booking_max_days_in_future")* 3600*24;
		if($startDateTimestamp>$maxDate)throw new Exception(Message("trainingcamp_booking_err_datetoofar",Config("trainingcamp_booking_max_days_in_future")));
		$camp=TrainingcampsDataService::getCampById($this->_websoccer,$this->_db,$parameters["id"]);
		if(!$camp)throw new Exception("Illegal ID");
		$existingBookings=TrainingcampsDataService::getCampBookingsByTeam($this->_websoccer,$this->_db,$teamId);
		if(count($existingBookings))throw new Exception(Message("trainingcamp_booking_err_existingbookings"));
		$playersOfTeam=PlayersDataService::getPlayersOfTeamById($this->_websoccer,$this->_db,$teamId);
		$totalCosts=$camp["costs"]*$parameters["days"]*count($playersOfTeam);
		if($totalCosts >= $team["team_budget"])throw new Exception(Message("trainingcamp_booking_err_tooexpensive"));
		$matches=MatchesDataService::getMatchesByTeamAndTimeframe($this->_websoccer,$this->_db,$teamId,$startDateTimestamp,$endDateTimestamp);
		if(count($matches))throw new Exception(Message("trainingcamp_booking_err_matcheswithintimeframe"));
		debitAmount($this->_websoccer,$this->_db,$teamId,$totalCosts,"trainingcamp_booking_costs_subject",$camp["name"]);
		$columns["verein_id"]=$teamId;
		$columns["lager_id"]=$camp["id"];
		$columns["datum_start"]=$startDateTimestamp;
		$columns["datum_ende"]=$endDateTimestamp;
		$this->_db->queryInsert($columns,Config("db_prefix")."_trainingslager_belegung");
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("trainingcamp_booking_success"),""));
		return "trainingcamp";}}
class BorrowPlayerController extends Controller {
	function executeAction($parameters){
		if(!Config("lending_enabled"))return NULL;
		$user=$this->_websoccer->getUser();
		$clubId=$user->getClubId($this->_websoccer,$this->_db);
		if($clubId==null)throw new Exception(Message("feature_requires_team"));
		$player=PlayersDataService::getPlayerById($this->_websoccer,$this->_db,$parameters["id"]);
		if($clubId==$player["team_id"])throw new Exception(Message("lending_hire_err_ownplayer"));
		if($player["lending_owner_id"])throw new Exception(Message("lending_hire_err_borrowed_player"));
		if($player["lending_fee"]==0)throw new Exception(Message("lending_hire_err_notoffered"));
		if($player["player_transfermarket"])throw new Exception(Message("lending_err_on_transfermarket"));
		if($parameters["matches"] <Config("lending_matches_min")|| $parameters["matches"] >Config("lending_matches_max")){
			throw new Exception(sprintf(Message("lending_hire_err_illegalduration"),Config("lending_matches_min"),Config("lending_matches_max")));}
		if($parameters["matches"] >= $player["player_contract_matches"])throw new Exception(Message("lending_hire_err_contractendingtoosoon",$player["player_contract_matches"]));
		$fee=$parameters["matches"]*$player["lending_fee"];
		$minBudget=$fee + 5*$player["player_contract_salary"];
		$team=TeamsDataService::getTeamSummaryById($this->_websoccer,$this->_db,$clubId);
		if($team["team_budget"]<$minBudget)throw new Exception(Message("lending_hire_err_budget_too_low"));
		debitAmount($this->_websoccer,$this->_db,$clubId,$fee,"lending_fee_subject",$player["team_name"]);
		creditAmount($this->_websoccer,$this->_db,$player["team_id"],$fee,"lending_fee_subject",$team["team_name"]);
		$this->updatePlayer($player["player_id"],$player["team_id"],$clubId,$parameters["matches"]);
		$playerName=(strlen($player["player_pseudonym"]))? $player["player_pseudonym"]:$player["player_firstname"]." ".$player["player_lastname"];
		if($player["team_user_id"]){ NotificationsDataService::createNotification($this->_websoccer,$this->_db,$player["team_user_id"],"lending_notification_lent",array("player"=>$playerName,"matches"=>$parameters["matches"],"newteam"=>$team["team_name"]),
			"lending_lent","player","id=".$player["player_id"]);}
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("lending_hire_success"),""));
		return "myteam";}
	function updatePlayer($playerId,$ownerId,$clubId,$matches){
		$columns=array("lending_matches"=>$matches,"lending_owner_id"=>$ownerId,"verein_id"=>$clubId);
		$fromTable=Config("db_prefix")."_spieler";
		$whereCondition="id=%d";
		$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$playerId);}}
class BuyYouthPlayerController extends Controller {
	function executeAction($parameters){
		if(!Config("youth_enabled"))return NULL;
		$user=$this->_websoccer->getUser();
		$clubId=$user->getClubId($this->_websoccer,$this->_db);
		if($clubId<1)throw new Exception(Message("feature_requires_team"));
		$player=YouthPlayersDataService::getYouthPlayerById($this->_websoccer,$this->_db,$this->_i18n,$parameters["id"]);
		if($clubId==$player["team_id"])throw new Exception(Message("youthteam_buy_err_ownplayer"));
		$result=$this->_db->querySelect("user_id",Config("db_prefix")."_verein","id=%d",$player["team_id"]);
		$playerteam=$result->fetch_array();
		$result->free_result();
		if($playerteam["user_id"]==$user->id)throw new Exception(Message("youthteam_buy_err_ownplayer_otherteam"));
		$team=TeamsDataService::getTeamSummaryById($this->_websoccer,$this->_db,$clubId);
		if($team["team_budget"] <= $player["transfer_fee"])throw new Exception(Message("youthteam_buy_err_notenoughbudget"));
		$prevTeam=TeamsDataService::getTeamSummaryById($this->_websoccer,$this->_db,$player["team_id"]);
		debitAmount($this->_websoccer,$this->_db,$clubId,$player["transfer_fee"],"youthteam_transferfee_subject",$prevTeam["team_name"]);
		creditAmount($this->_websoccer,$this->_db,$player["team_id"],$player["transfer_fee"],"youthteam_transferfee_subject",$team["team_name"]);
		$this->_db->queryUpdate(array("team_id"=>$clubId,"transfer_fee"=> 0),Config("db_prefix")."_youthplayer","id=%d",$parameters["id"]);
		NotificationsDataService::createNotification($this->_websoccer,$this->_db,$prevTeam["user_id"],"youthteam_transfer_notification",array("player"=>$player["firstname"]." ".$player["lastname"],"newteam"=>$team["team_name"]),"youth_transfer","team",
			"id=".$clubId);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("youthteam_buy_success"),""));
		return "youth-team";}}
class CancelCampController extends Controller {
	function executeAction($parameters){
		$user=$this->_websoccer->getUser();
		$teamId=$user->getClubId($this->_websoccer,$this->_db);
		if($teamId<1)throw new Exception(Message("feature_requires_team"));
		$existingBookings=TrainingcampsDataService::getCampBookingsByTeam($this->_websoccer,$this->_db,$teamId);
		if(!count($existingBookings))throw new Exception(Message("trainingcamp_cancel_illegalid"));
		$deleted=FALSE;
		foreach($existingBookings as$booking){
			if($booking["id"]==$parameters["bookingid"]){
				$this->_db->queryDelete(Config("db_prefix")."_trainingslager_belegung","id=%d",$booking["id"]);
				$deleted=TRUE;}}
		if(!$deleted)throw new Exception(Message("trainingcamp_cancel_illegalid"));
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("trainingcamp_cancel_success"),""));
		return "trainingcamp";}}
class CancelYouthMatchRequestController extends Controller {
	function executeAction($parameters){
		if(!Config("youth_enabled"))return NULL;
		$user=$this->_websoccer->getUser();
		$clubId=$user->getClubId($this->_websoccer,$this->_db);
		$fromTable=Config("db_prefix")."_youthmatch_request";
		$result=$this->_db->querySelect("*",$fromTable,"id=%d",$parameters["id"]);
		$request=$result->fetch_array();
		if(!$request)throw new Exception(Message("youthteam_matchrequest_cancel_err_notfound"));
		if($clubId!=$request["team_id"])throw new Exception("nice try");
		$this->_db->queryDelete($fromTable,"id=%d",$parameters["id"]);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("youthteam_matchrequest_cancel_success"),""));
		return "youth-matchrequests";}}
class ChooseAdditionalTeamController extends Controller {
	function executeAction($parameters){
		$user=$this->_websoccer->getUser();
		$maxTeams=(int)Config('max_number_teams_per_user');
		if(!Config('assign_team_automatically')&&$maxTeams>1)throw new Exception(Message('freeclubs_msg_error'));
		$minHighscore=(int)Config('additional_team_min_highscore');
		if($minHighscore){
			$result=$this->_db->querySelect('highscore',Config('db_prefix').'_user', 'id=%d',$user->id);
			$userinfo=$result->fetch_array();
			if($minHighscore>$userinfo['highscore'])throw new Exception(Message('freeclubs_msg_error_minhighscore',$minHighscore));}
		$fromTable=Config('db_prefix').'_verein';
		$result=$this->_db->querySelect('id,liga_id',$fromTable, 'user_id=%d',$user->id);
		$teamsOfUser=[];
		while($teamOfUser=$result->fetch_array())$teamsOfUser[$teamOfUser['liga_id']][]=$teamOfUser['id'];
		$result->free_result();
		if(count($teamsOfUser)>=Config('max_number_teams_per_user'))throw new Exception(Message('freeclubs_msg_error_max_number_of_teams',$maxTeams));
		$teamId=$parameters['teamId'];
		$result=$this->_db->querySelect('id,user_id,liga_id,interimmanager',$fromTable, 'id=%d AND status=1',$teamId);
		$club=$result->fetch_array();
		if($club['user_id'] &&!$club['interimmanager'])throw new Exception(Message('freeclubs_msg_error'));
		if(isset($teamsOfUser[$club['liga_id']]))throw new Exception(Message('freeclubs_msg_error_no_club_from_same_league'));
		$this->_db->queryUpdate(array('user_id'=>$user->id),$fromTable,"id=%d",$teamId);
		$user->setClubId($teamId);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message('freeclubs_msg_success'), ''));
		return 'office';}}
class ChooseSponsorController extends Controller {
	function executeAction($parameters){
		$user=$this->_websoccer->getUser();
		$teamId=$user->getClubId($this->_websoccer,$this->_db);
		if($teamId<1)return null;
		$sponsor=SponsorsDataService::getSponsorinfoByTeamId($this->_websoccer,$this->_db,$teamId);
		if($sponsor)throw new Exception(Message("sponsor_choose_stillcontract"));
		$teamMatchday=MatchesDataService::getMatchdayNumberOfTeam($this->_websoccer,$this->_db,$teamId);
		if($teamMatchday <Config("sponsor_earliest_matchday"))throw new Exception(Message("sponsor_choose_tooearly",Config("sponsor_earliest_matchday")));
		$sponsors=SponsorsDataService::getSponsorOffers($this->_websoccer,$this->_db,$teamId);
		$found=FALSE;
		foreach($sponsors as$availableSponsor){
			if($availableSponsor["sponsor_id"]==$parameters["id"]){
				$found=TRUE;
				break;}}
		if(!$found)throw new Exception(Message("sponsor_choose_novalidsponsor"));
		$columns["sponsor_id"]=$parameters["id"];
		$columns["sponsor_spiele"]=Config("sponsor_matches");
		$fromTable=Config("db_prefix")."_verein";
		$whereCondition="id=%d";
		$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$teamId);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("sponsor_choose_success"),""));
		return null;}}
class ChooseTeamController extends Controller {
	function executeAction($parameters){
		$user=$this->_websoccer->getUser();
		if(!Config("assign_team_automatically"))throw new Exception(Message("freeclubs_msg_error"));
		if($user->getClubId($this->_websoccer,$this->_db))throw new Exception(Message("freeclubs_msg_error_user_is_already_manager"));
		$teamId=$parameters["teamId"];
		$fromTable=Config("db_prefix")."_verein";
		$whereCondition="id=%d AND status=1 AND(user_id=0 OR user_id IS NULL OR interimmanager='1')";
		$result=$this->_db->querySelect("id",$fromTable,$whereCondition,$teamId);
		$club=$result->fetch_array();
		if(!isset($club["id"]))throw new Exception(Message("freeclubs_msg_error"));
		$columns=[];
		$columns["user_id"]=$user->id;
		$columns["interimmanager"]="0";
		if(count($columns))$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$teamId);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("freeclubs_msg_success"),""));
		return "office";}}
class ChooseTrainerController extends Controller {
	function executeAction($parameters){
		$user=$this->_websoccer->getUser();
		$teamId=$user->getClubId($this->_websoccer,$this->_db);
		if($teamId<1)throw new Exception(Message("feature_requires_team"));
		if(TrainingDataService::countRemainingTrainingUnits($this->_websoccer,$this->_db,$teamId))throw new Exception(Message("training_choose_trainer_err_existing_units"));
		$trainer=TrainingDataService::getTrainerById($this->_websoccer,$this->_db,$parameters["id"]);
		if(!isset($trainer["id"]))throw new Exception("invalid ID");
		$numberOfUnits=(int)$parameters["units"];
		$totalCosts=$numberOfUnits*$trainer["salary"];
		$teamInfo=TeamsDataService::getTeamSummaryById($this->_websoccer,$this->_db,$teamId);
		if($teamInfo["team_budget"] <= $totalCosts)throw new Exception(Message("training_choose_trainer_err_too_expensive"));
		if($trainer['premiumfee'])PremiumDataService::debitAmount($this->_websoccer,$this->_db,$user->id,$trainer['premiumfee'],"choose-trainer");
		debitAmount($this->_websoccer,$this->_db,$teamId,$totalCosts,"training_trainer_salary_subject",$trainer["name"]);
		$columns["team_id"]=$teamId;
		$columns["trainer_id"]=$trainer["id"];
		$fromTable=Config("db_prefix")."_training_unit";
		for($unitNo=1; $unitNo <= $numberOfUnits;++$unitNo)$this->_db->queryInsert($columns,$fromTable);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("saved_message_title"),""));
		return "training";}}
class CreateYouthMatchRequestController extends Controller {
	function executeAction($parameters){
		if(!Config("youth_enabled")|| !Config("youth_matchrequests_enabled"))return NULL;
		$user=$this->_websoccer->getUser();
		$clubId=$user->getClubId($this->_websoccer,$this->_db);
		if($clubId<1)throw new Exception(Message("error_action_required_team"));
		$tooLateBoundary=Timestamp()+ 3600*24*(1 +Config("youth_matchrequest_max_futuredays"));
		$validTimes=explode(",",$Config("youth_matchrequest_allowedtimes"));
		$timeIsValid=FALSE;
		$matchTime=date("H:i",$parameters["matchdate"]);
		foreach($validTimes as$validTime){
			if($matchTime==trim($validTime)){
				$timeIsValid=TRUE;
				break;}}
		if(!$timeIsValid||$parameters["matchdate"]>$tooLateBoundary)throw new Exception(Message("youthteam_matchrequest_create_err_invaliddate"));
		$fromTable=Config("db_prefix")."_youthmatch_request";
		$result=$this->_db->querySelect("COUNT(*)AS hits",$fromTable,"team_id=%d",$clubId);
		$requests=$result->fetch_array();
		$maxNoOfRequests=(int)Config("youth_matchrequest_max_open_requests");
		if($requests &&$requests["hits"] >= $maxNoOfRequests)throw new Exception(Message("youthteam_matchrequest_create_err_too_many_open_requests",$maxNoOfRequests));
		if($parameters["reward"]){
			$team=TeamsDataService::getTeamSummaryById($this->_websoccer,$this->_db,$clubId);
			if($team["team_budget"] <= $parameters["reward"])throw new Exception(Message("youthteam_matchrequest_create_err_budgetnotenough"));}
		if(YouthPlayersDataService::countYouthPlayersOfTeam($this->_websoccer,$this->_db,$clubId)< 11)throw new Exception(Message("youthteam_matchrequest_create_err_notenoughplayers"));
		$maxMatchesPerDay=Config("youth_match_maxperday");
		if(YouthMatchesDataService::countMatchesOfTeamOnSameDay($this->_websoccer,$this->_db,$clubId,$parameters["matchdate"])>= $maxMatchesPerDay)throw new Exception(Message("youthteam_matchrequest_err_maxperday_violated",$maxMatchesPerDay));
		$columns=array( "team_id"=>$clubId,"matchdate"=>$parameters["matchdate"],"reward"=>$parameters["reward"]);
		$this->_db->queryInsert($columns,$fromTable);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("youthteam_matchrequest_create_success"),""));
		return "youth-matchrequests";}}
class DeleteMessageController extends Controller {
	function executeAction($parameters){
		$id=$parameters["id"];
		$message=MessagesDataService::getMessageById($this->_websoccer,$this->_db,$id);
		if($message==null)throw new Exception(Message("messages_delete_invalidid"));
		$fromTable=Config("db_prefix")."_briefe";
		$whereCondition="id=%d";
		$this->_db->queryDelete($fromTable,$whereCondition,$id);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("messages_delete_success"),""));
		return null;}}
class DeleteProfilePictureController extends Controller {
	function executeAction($parameters){
		if(!Config("user_picture_upload_enabled"))throw new Exception("feature is not enabled.");
		$userId=$this->_websoccer->getUser()->id;
		$fromTable=Config("db_prefix")."_user";
		$whereCondition="id=%d";
		$result=$this->_db->querySelect("picture",$fromTable,$whereCondition,$userId);
		$userinfo=$result->fetch_array();
		if(strlen($userinfo["picture"])&&file_exists($_SERVER['DOCUMENT_ROOT'].'/uploads/'.'users'."/".$userinfo["picture"]))unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/'.'users'."/".$userinfo["picture"]);
		$this->_db->queryUpdate(array("picture"=>""),$fromTable,$whereCondition,$userId);
		$this->_websoccer->getUser()->setProfilePicture($this->_websoccer, null);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("delete-profile-picture_success"),""));
		return "user";}}
class DeleteShoutBoxMessageController extends Controller {
	function executeAction($parameters){
		$this->_db->queryDelete(Config('db_prefix').'_shoutmessage', 'id=%d',$parameters['mid']);
		return null;}}
class DirectTransferAcceptController extends Controller {
	function executeAction($parameters){
		if(!Config("transferoffers_enabled"))return;
		$clubId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);
		$result=$this->_db->querySelect("*",Config("db_prefix")."_transfer_offer","id=%d AND rejected_date=0 AND admin_approval_pending='0'",$parameters["id"]);
		$offer=$result->fetch_array();
		$player=PlayersDataService::getPlayerById($this->_websoccer,$this->_db,$offer["player_id"]);
		if($player["player_transfermarket"])throw new Exception(Message("transferoffer_err_unsellable"));
		if($offer["offer_player1"]||$offer["offer_player2"]){
			$team=TeamsDataService::getTeamSummaryById($this->_websoccer,$this->_db,$clubId);
			if($offer["offer_player1"])$this->checkExchangePlayer($offer["offer_player1"],$offer["sender_user_id"],$team["team_budget"]);
			if($offer["offer_player2"])$this->checkExchangePlayer($offer["offer_player2"],$offer["sender_user_id"],$team["team_budget"]);}
		$teamSize=TeamsDataService::getTeamSize($this->_websoccer,$this->_db,$clubId);
		if($teamSize <Config("transfermarket_min_teamsize"))throw new Exception(Message("sell_player_teamsize_too_small",$teamSize));
		if(Config("transferoffers_adminapproval_required")){
			$this->_db->queryUpdate(array("admin_approval_pending"=>"1"),Config("db_prefix")."_transfer_offer","id=%d",$parameters["id"]);
			$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("transferoffer_accepted_title"),Message("transferoffer_accepted_message_approvalpending")));}
		else{executeTransferFromOffer($this->_websoccer,$this->_db,$parameters["id"]);
			$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("transferoffer_accepted_title"),Message("transferoffer_accepted_message")));}
		return null;}
	function checkExchangePlayer($playerId,$userId,$teamBudget){
		$player=PlayersDataService::getPlayerById($this->_websoccer,$this->_db,$playerId);
		$playerName=(strlen($player["player_pseudonym"]))? $player["player_pseudonym"]:$player["player_firstname"]." ".$player["player_lastname"];
		if($player["player_transfermarket"])throw new Exception(Message("transferoffer_accept_err_exchangeplayer_on_transfermarket",$playerName));
		if($player["team_user_id"]!=$userId)throw new Exception(Message("transferoffer_accept_err_exchangeplayer_notinteam",$playerName));
		$minBudget=40*$player["player_contract_salary"];
		if($teamBudget<$minBudget)throw new Exception(Message("transferoffer_accept_err_exchangeplayer_salarytoohigh",$playerName));}}
class DirectTransferCancelController extends Controller {
	function executeAction($parameters){
		if(Config("transferoffers_enabled"))return;
		$userId=$this->_websoccer->getUser()->id;
		$result=$this->_db->querySelect("*",Config("db_prefix")."_transfer_offer","id=%d AND sender_user_id=%d",array($parameters["id"],$userId));
		$offer=$result->fetch_array();
		if(!$offer)throw new Exception(Message("transferoffers_offer_cancellation_notfound"));
		$this->_db->queryDelete(Config("db_prefix")."_transfer_offer","id=%d",$offer["id"]);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("transferoffers_offer_cancellation_success"),""));
		return null;}}
class DirectTransferOfferController extends Controller {
	function executeAction($parameters){
		if(!Config("transferoffers_enabled"))return;
		$clubId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);
		if($clubId==null)throw new Exception(Message("feature_requires_team"));
		$player=PlayersDataService::getPlayerById($this->_websoccer,$this->_db,Request("id"));
		if(!$player["team_user_id"])throw new Exception(Message("transferoffer_err_nomanager"));
		if($player["team_user_id"]==$this->_websoccer->getUser()->id)throw new Exception(Message("transferoffer_err_ownplayer"));
		if($player["player_unsellable"]||$player["player_transfermarket"])throw new Exception(Message("transferoffer_err_unsellable"));
		$this->checkIfThereAreAlreadyOpenOffersFromUser($player["team_id"]);
		$this->checkIfUserIsAllowedToSendAlternativeOffers($player["player_id"]);
		$this->checkPlayersTransferStop($player["player_id"]);
		if($parameters["exchangeplayer1"])$this->checkExchangePlayer($parameters["exchangeplayer1"]);
		if($parameters["exchangeplayer2"])$this->checkExchangePlayer($parameters["exchangeplayer2"]);
		if($parameters["exchangeplayer1"]||$parameters["exchangeplayer2"]){
			$teamSize=TeamsDataService::getTeamSize($this->_websoccer,$this->_db,$clubId);
			$numberOfSizeReduction=($parameters["exchangeplayer2"])? 1:0;
			if($teamSize<(Config("transfermarket_min_teamsize")- $numberOfSizeReduction))throw new Exception(Message("sell_player_teamsize_too_small",$teamSize));}
		$noOfTransactions=TransfermarketDataService::getTransactionsBetweenUsers($this->_websoccer,$this->_db,$player["team_user_id"],$this->_websoccer->getUser()->id);
		$maxTransactions=Config("transfermarket_max_transactions_between_users");
		if($noOfTransactions >= $maxTransactions)throw new Exception(Message("transfer_bid_too_many_transactions_with_user",$noOfTransactions));
		$team=TeamsDataService::getTeamSummaryById($this->_websoccer,$this->_db,$clubId);
		$totalOffers=$this->getSumOfAllOpenOffers()+ $parameters["amount"];
		if($team["team_budget"]<$totalOffers)throw new Exception(Message("transferoffer_err_totaloffers_too_high"));
		TeamsDataService::validateWhetherTeamHasEnoughBudgetForSalaryBid($this->_websoccer,$this->_db,$this->_i18n,$clubId,$player["player_contract_salary"]);
		createTransferOffer($this->_websoccer,$this->_db,$player["player_id"],$this->_websoccer->getUser()->id,$clubId,$player["team_user_id"],$player["team_id"],$parameters["amount"],$parameters["comment"],
			$parameters["exchangeplayer1"],$parameters["exchangeplayer2"]);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("transferoffer_submitted_title"),Message("transferoffer_submitted_message")));
		return null;}
	function checkIfThereAreAlreadyOpenOffersFromUser($teamId){
		if(Config("transferoffers_adminapproval_required"))return;
		$result=$this->_db->querySelect("COUNT(*)AS hits",Config("db_prefix")."_transfer_offer","rejected_date=0 AND sender_user_id=%d AND receiver_club_id=%d",array($this->_websoccer->getUser()->id,$teamId));
		$count=$result->fetch_array();
		if($count["hits"])throw new Exception(Message("transferoffer_err_open_offers_exist"));}
	function checkIfUserIsAllowedToSendAlternativeOffers($playerId){
		$result=$this->_db->querySelect("COUNT(*)AS hits",Config("db_prefix")."_transfer_offer","rejected_date>0 AND rejected_allow_alternative='0' AND player_id=%d AND sender_user_id=%d",array($playerId,$this->_websoccer->getUser()->id));
		$count=$result->fetch_array();
		if($count["hits"])throw new Exception(Message("transferoffer_err_noalternative_allowed"));}
	function checkPlayersTransferStop($playerId){
		if(Config("transferoffers_transfer_stop_days")< 1)return;
		$transferBoundaryTimestamp()- 24*3600 *Config("transferoffers_transfer_stop_days");
		$result=$this->_db->querySelect("COUNT(*)AS hits",Config("db_prefix")."_transfer","spieler_id=%d AND datum>%d",array($playerId,$transferBoundary));
		$count=$result->fetch_array();
		if($count["hits"])throw new Exception(Message("transferoffer_err_transferstop",Config("transferoffers_transfer_stop_days")));}
	function checkExchangePlayer($playerId){
		$player=PlayersDataService::getPlayerById($this->_websoccer,$this->_db,$playerId);
		$playerName=(strlen($player["player_pseudonym"]))? $player["player_pseudonym"]:$player["player_firstname"]." ".$player["player_lastname"];
		if($player["player_transfermarket"]||$player["team_user_id"]!=$this->_websoccer->getUser()->id)throw new Exception(Message("transferoffer_err_exchangeplayer_on_transfermarket",$playerName));
		$result=$this->_db->querySelect("COUNT(*)AS hits",Config("db_prefix")."_transfer_offer","rejected_date=0 AND(offer_player1=%d OR offer_player2=%d)",array($playerId,$playerId,$playerId));
		$count=$result->fetch_array();
		if($count["hits"])throw new Exception(Message("transferoffer_err_exchangeplayer_involved_in_other_offers",$playerName));
		try { $this->checkPlayersTransferStop($playerId);}
		catch(Exception $e){ throw new Exception(Message("transferoffer_err_exchangeplayer_transferstop",$playerName));}}
	function getSumOfAllOpenOffers(){
		$result=$this->_db->querySelect("SUM(offer_amount)AS amount",Config("db_prefix")."_transfer_offer","rejected_date=0 AND sender_user_id=%d",$this->_websoccer->getUser()->id);
		$sum=$result->fetch_array();
		if($sum["amount"])return$sum["amount"];
		return 0;}}
class DirectTransferRejectController extends Controller {
	function executeAction($parameters){
		if(!Config("transferoffers_enabled"))return;
		$clubId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);
		$result=$this->_db->querySelect("*",Config("db_prefix")."_transfer_offer","id=%d AND receiver_club_id=%d",array($parameters["id"],$clubId));
		$offer=$result->fetch_array();
		if(!$offer)throw new Exception(Message("transferoffers_offer_cancellation_notfound"));
		$this->_db->queryUpdate(array( "rejected_date"=>Timestamp(),"rejected_message"=>$parameters["comment"],"rejected_allow_alternative"=>($parameters["allow_alternative"])? 1 : 0),Config("db_prefix")."_transfer_offer","id=%d",$offer["id"]);
		$player=PlayersDataService::getPlayerById($this->_websoccer,$this->_db,$offer["player_id"]);
		if($player["player_pseudonym"])$playerName=$player["player_pseudonym"];
		else $playerName=$player["player_firstname"]." ".$player["player_lastname"];
		NotificationsDataService::createNotification($this->_websoccer,$this->_db,$offer["sender_user_id"],"transferoffer_notification_rejected",array("playername"=>$playerName,"receivername"=>$this->_websoccer->getUser()->username),"transferoffer",
			"transferoffers#sent");
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("transferoffers_offer_reject_success"),""));
		return null;}}
class DisableAccountController extends Controller {
	function executeAction($parameters){
		$clubId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);
		if($clubId)$this->_db->queryUpdate(array("user_id"=> '',"captain_id"=> ''),Config("db_prefix")."_verein","user_id=%d",$this->_websoccer->getUser()->id);
		$this->_db->queryUpdate(array("status"=>"0"),Config("db_prefix")."_user","id=%d",$this->_websoccer->getUser()->id);
		$authenticatorClasses=explode(",",Config("authentication_mechanism"));
		foreach($authenticatorClasses as$authenticatorClass){
			$authenticatorClass=trim($authenticatorClass);
			if(!class_exists($authenticatorClass))throw new Exception("Class not found: ".$authenticatorClass);
			$authenticator=new $authenticatorClass($this->_websoccer);
			$authenticator->logoutUser($this->_websoccer->getUser());}
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("cancellation_success"),""));
		return "home";}}
class ExchangePremiumController extends Controller {
	function executeAction($parameters){
		$user=$this->_websoccer->getUser();
		$exchangeRate=(int)Config("premium_exchangerate_gamecurrency");
		if($exchangeRate <= 0)throw new Exception("featue is disabled!");
		$clubId=$user->getClubId($this->_websoccer,$this->_db);
		if(!$clubId)throw new Exception(Message("feature_requires_team"));
		$amount=$parameters["amount"];
		$balance=$user->premiumBalance;
		if($balance<$amount)throw new Exception(Message("premium-exchange_err_balancenotenough"));
		if($parameters["validateonly"])return "premium-exchange-confirm";
		creditAmount($this->_websoccer,$this->_db,$clubId,$amount*$exchangeRate,"premium-exchange_team_subject",$user->username);
		PremiumDataService::debitAmount($this->_websoccer,$this->_db,$user->id,$amount,"exchange-premium");
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("premium-exchange_success"),""));
		return "premiumaccount";}}
class ExecuteTrainingController extends Controller {
	function executeAction($parameters){
		$user=$this->_websoccer->getUser();
		$teamId=$user->getClubId($this->_websoccer,$this->_db);
		if($teamId<1)return null;
		$unit=TrainingDataService::getTrainingUnitById($this->_websoccer,$this->_db,$teamId,$parameters["id"]);
		if(!isset($unit["id"]))throw new Exception("invalid ID");
		if($unit["date_executed"])throw new Exception(Message("training_execute_err_already_executed"));
		$previousExecution=TrainingDataService::getLatestTrainingExecutionTime($this->_websoccer,$this->_db,$teamId);
		$earliestValidExecution=$previousExecution + 3600 *Config("training_min_hours_between_execution");
		$now=Timestamp();
		if($now<$earliestValidExecution)throw new Exception(Message("training_execute_err_too_early",Datetime($earliestValidExecution)));
		$campBookings=TrainingcampsDataService::getCampBookingsByTeam($this->_websoccer,$this->_db,$teamId);
		foreach($campBookings as$booking){
			if($booking["date_start"] <= $now &&$booking["date_end"] >= $now)throw new Exception(Message("training_execute_err_team_in_training_camp"));}
		$liveMatch=MatchesDataService::getLiveMatchByTeam($this->_websoccer,$this->_db,$teamId);
		if(isset($liveMatch["match_id"]))throw new Exception(Message("training_execute_err_match_simulating"));
		$trainer=TrainingDataService::getTrainerById($this->_websoccer,$this->_db,$unit["trainer_id"]);
		$columns["focus"]=$parameters["focus"];
		$unit["focus"]=$parameters["focus"];
		$columns["intensity"]=$parameters["intensity"];
		$unit["intensity"]=$parameters["intensity"];
		$this->trainPlayers($teamId,$trainer,$unit);
		$columns["date_executed"]=$now;
		$fromTable=Config("db_prefix")."_training_unit";
		$whereCondition="id=%d";
		$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$unit["id"]);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("training_execute_success"),""));
		return null;}
	function trainPlayers($teamId,$trainer,$unit){
		$players=PlayersDataService::getPlayersOfTeamById($this->_websoccer,$this->_db,$teamId);
		$freshnessDecrease=round(1 + $unit["intensity"] / 100*5);
		$fromTable=Config("db_prefix")."_spieler";
		$whereCondition="id=%d";
		$trainingEffects=[];
		foreach($players as$player){
			$effectFreshness=0;
			$effectStamina=0;
			$effectTechnique=0;
			$effectSatisfaction=0;
			if($player["matches_injured"]){
				$effectFreshness=1;
				$effectStamina=-1;}
			else{
				if($unit["focus"]=="FR"){
					$effectFreshness=5;
					$effectStamina=-2;
					$effectSatisfaction=1;}
				elseif($unit["focus"]=="MOT"){
					$effectFreshness=1;
					$effectStamina=-1;
					$effectSatisfaction=5;}
				elseif($unit["focus"]=="STA"){
					$effectSatisfaction=-1;
					$effectFreshness=-$freshnessDecrease;
					$staminaIncrease=1;
					if($unit["intensity"]>50){
						$successFactor=$unit["intensity"]*$trainer["p_stamina"] / 100;
						$pStamina[5]=$successFactor;
						$pStamina[1]=100 - $successFactor;
						$staminaIncrease +=selectItemFromProbabilities($pStamina);}
					$effectStamina=$staminaIncrease;}
				else{
					$effectFreshness=-$freshnessDecrease;
					if($unit["intensity"]>20)$effectStamina=1;
					$techIncrease=0;
					if($unit["intensity"]>75){
						$successFactor=$unit["intensity"]*$trainer["p_technique"] / 100;
						$pTech[2]=$successFactor;
						$pTech[0]=100 - $successFactor;
						$techIncrease += selectItemFromProbabilities($pTech);}
					$effectTechnique=$techIncrease;}}
			$event=new PlayerTrainedEvent($this->_websoccer,$this->_db,$this->_i18n,$player["id"],$teamId,$trainer["id"],$effectFreshness,$effectTechnique,$effectStamina,$effectSatisfaction);
			dispatchEvent($event);
			$columns=array("w_frische"=> min(100, max(1,$player["strength_freshness"] + $effectFreshness)),"w_technik"=> min(100, max(1,$player["strength_technic"] + $effectTechnique)),"w_kondition"=> min(100, max(1,
				$player["strength_stamina"] + $effectStamina)),"w_zufriedenheit"=> min(100, max(1,$player["strength_satisfaction"] + $effectSatisfaction)));
			$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$player["id"]);
			$trainingEffects[$player["id"]]=array("name"=>($player["pseudonym"])? $player["pseudonym"]:$player["firstname"]." ".$player["lastname"],"freshness"=>$effectFreshness,"technique"=>$effectTechnique,"stamina"=>$effectStamina,
				"satisfaction"=>$effectSatisfaction);}
		$this->_websoccer->addContextParameter("trainingEffects",$trainingEffects);}}
class ExtendContractController extends Controller {
	function executeAction($parameters){
		$user=$this->_websoccer->getUser();
		$clubId=$user->getClubId($this->_websoccer,$this->_db);
		$player=PlayersDataService::getPlayerById($this->_websoccer,$this->_db,$parameters["id"]);
		if($clubId!=$player["team_id"])throw new Exception("nice try");
		$satisfaction=$player["player_strength_satisfaction"];
		if($satisfaction<config('MINIMUM_SATISFACTION_FOR_EXTENSION'))throw new Exception(Message("extend-contract_player_is_unhappy"));
		if($player["player_transfermarket"])throw new Exception(Message("sell_player_already_on_list"));
		if($parameters["salary"]<$player["player_contract_salary"])throw new Exception(Message("extend-contract_lower_than_current_salary"));
		$averageSalary=$this->getAverageSalary($player["player_strength"]);
		if($player["player_contract_salary"]>$averageSalary)$salaryFactor=1.10;
		else $salaryFactor=(200 - $satisfaction)/ 100;
		$salaryFactor=max(1.1,$salaryFactor);
		$minSalary=round($player["player_contract_salary"]*$salaryFactor);
		if($averageSalary<($parameters["salary"]*2)){
			$minSalaryOfAverage=round(0.9*$averageSalary);
			$minSalary=max($minSalary,$minSalaryOfAverage);}
		if($parameters["salary"]<$minSalary){
			$this->decreaseSatisfaction($player["player_id"],$player["player_strength_satisfaction"]);
			throw new Exception(Message("extend-contract_salary_too_low"));}
		TeamsDataService::validateWhetherTeamHasEnoughBudgetForSalaryBid($this->_websoccer,$this->_db,$this->_i18n,$clubId,$parameters["salary"]);
		$minGoalBonus=$player["player_contract_goalbonus"]*1.3;
		if($parameters["goal_bonus"]<$minGoalBonus)throw new Exception(Message("extend-contract_goalbonus_too_low"));
		$this->updatePlayer($player["player_id"],$player["player_strength_satisfaction"],$parameters["salary"],$parameters["goal_bonus"],$parameters["matches"]);
		UserInactivityDataService::resetContractExtensionField($this->_websoccer,$this->_db,$user->id);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("extend-contract_success"),""));
		return null;}
	function getAverageSalary($playerStrength){
		$columns="AVG(vertrag_gehalt)AS average_salary";
		$fromTable=Config("db_prefix")."_spieler";
		$whereCondition="w_staerke >= %d AND w_staerke <= %d AND status=1";
		$parameters=array($playerStrength - 10,$playerStrength + 10);
		$result=$this->_db->querySelect($columns,$fromTable,$whereCondition,$parameters);
		$avg=$result->fetch_array();
		if(isset($avg["average_salary"]))return$avg["average_salary"];
		return$playerStrength;}
	function decreaseSatisfaction($playerId,$oldValue){
		if($oldValue <= config('SATISFACTION_DECREASE'))return;
		$newValue=$oldValue -config('SATISFACTION_DECREASE');
		$columns["w_zufriedenheit"]=$newValue;
		$fromTable=Config("db_prefix")."_spieler";
		$whereCondition="id=%d";
		$parameters=$playerId;
		$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$parameters);}
	function updatePlayer($playerId,$oldSatisfaction,$newSalary,$newGoalBonus,$newMatches){
		$satisfaction=min(100,$oldSatisfaction + config('SATISFACTION_INCREASE'));
		$columns["w_zufriedenheit"]=$satisfaction;
		$columns["vertrag_gehalt"]=$newSalary;
		$columns["vertrag_torpraemie"]=$newGoalBonus;
		$columns["vertrag_spiele"]=$newMatches;
		$fromTable=Config("db_prefix")."_spieler";
		$whereCondition="id=%d";
		$parameters=$playerId;
		$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$parameters);}}
class ExtendStadiumController extends Controller {
	function executeAction($parameters){
		$user=$this->_websoccer->getUser();
		$teamId=$user->getClubId($this->_websoccer,$this->_db);
		if($teamId<1)return null;
		if(!$parameters["side_standing"] &&!$parameters["side_seats"] &&!$parameters["grand_standing"] &&!$parameters["grand_seats"] &&!$parameters["vip"])return null;
		$stadium=StadiumsDataService::getStadiumByTeamId($this->_websoccer,$this->_db,$teamId);
		if(!$stadium)return null;
		$seatsSide=$stadium["places_stands"] + $stadium["places_seats"] + $parameters["side_standing"] + $parameters["side_seats"];
		if($seatsSide >Config("stadium_max_side"))throw new Exception(Message("stadium_extend_err_exceed_max_side",Config("stadium_max_side")));
		$seatsGrand=$stadium["places_stands_grand"] + $stadium["places_seats_grand"] + $parameters["grand_standing"] + $parameters["grand_seats"];
		if($seatsGrand >Config("stadium_max_grand"))throw new Exception(Message("stadium_extend_err_exceed_max_grand",Config("stadium_max_grand")));
		$seatsVip=$stadium["places_vip"] + $parameters["vip"];
		if($seatsVip >Config("stadium_max_vip"))throw new Exception(Message("stadium_extend_err_exceed_max_vip",Config("stadium_max_vip")));
		if(StadiumsDataService::getCurrentConstructionOrderOfTeam($this->_websoccer,$this->_db,$teamId)!= NULL)throw new Exception(Message("stadium_extend_err_constructionongoing"));
		if(isset($parameters["validate-only"])&&$parameters["validate-only"])return "stadium-extend-confirm";
		$builderId=Request("offerid");
		$offers=StadiumsDataService::getBuilderOffersForExtension($this->_websoccer,$this->_db,$teamId,(int)Request("side_standing"),(int)Request("side_seats"),
			(int)Request("grand_standing"),(int)Request("grand_seats"),(int)Request("vip"));
		if($builderId==NULL||!isset($offers[$builderId]))throw new Exception("Illegal offer ID.");
		$offer=$offers[$builderId];
		$team=TeamsDataService::getTeamSummaryById($this->_websoccer,$this->_db,$teamId);
		$totalCosts=$offer["totalCosts"];
		if($team["team_budget"] <= $totalCosts)throw new Exception(Message("stadium_extend_err_too_expensive"));
		if($offer["builder_premiumfee"])PremiumDataService::debitAmount($this->_websoccer,$this->_db,$user->id,$offer["builder_premiumfee"],"extend-stadium");
		debitAmount($this->_websoccer,$this->_db,$teamId,$totalCosts,"stadium_extend_transaction_subject",$offer["builder_name"]);
		$this->_db->queryInsert(array("team_id"=>$teamId,"builder_id"=>$builderId,"started"=>Timestamp(),"deadline"=>$offer["deadline"],"p_steh"=>($parameters["side_standing"])? $parameters["side_standing"] : 0,
			"p_sitz"=>($parameters["side_seats"])? $parameters["side_seats"] : 0,"p_haupt_steh"=>($parameters["grand_standing"])? $parameters["grand_standing"] : 0,"p_haupt_sitz"=>($parameters["grand_seats"])? $parameters["grand_seats"] : 0,
			"p_vip"=>($parameters["vip"])? $parameters["vip"] : 0),Config("db_prefix")."_stadium_construction");
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("stadium_extend_success"),""));
		createOrUpdateActionLog($this->_websoccer,$this->_db,$user->id,"extend-stadium");
		$seats=$parameters["side_standing"] + $parameters["side_seats"] + $parameters["grand_standing"] + $parameters["grand_seats"] + $parameters["vip"];
		awardBadgeIfApplicable($this->_websoccer,$this->_db,$user->id, 'stadium_construction_by_x',$seats);
		return "stadium";}}
class FacebookLoginController extends Controller {
	function executeAction($parameters){
		$userEmail=FacebookSdk::getInstance($this->_websoccer)->getUserEmail();
		if(!strlen($userEmail)){
			$this->_websoccer->addFrontMessage(new FrontMessage('warning',Message("facebooklogin_failure"),""));
			return "home";}
		$userEmail=strtolower($userEmail);
		$userId=UsersDataService::getUserIdByEmail($this->_websoccer,$this->_db,$userEmail);
		if($userId<1)$userId=UsersDataService::createLocalUser($this->_websoccer,$this->_db, null,$userEmail);
		loginFrontUserUsingApplicationSession($this->_websoccer,$userId);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("facebooklogin_success"),""));
		return (strlen($this->_websoccer->getUser()->username))? "office" : "enter-username";}}
class FacebookLogoutController extends Controller {
	function executeAction($parameters){
		if(strlen(FacebookSdk::getInstance($this->_websoccer)->getUserEmail())){
			$this->_websoccer->addFrontMessage(new FrontMessage('error',Message("facebooklogout_failure"),""));
			return null;}
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("facebooklogout_success"),Message("facebooklogout_success_details")));
		return "home";}}
class FirePlayerController extends Controller {
	function executeAction($parameters){
		if(!Config("enable_player_resignation"))return;
		$user=$this->_websoccer->getUser();
		$clubId=$user->getClubId($this->_websoccer,$this->_db);
		$player=PlayersDataService::getPlayerById($this->_websoccer,$this->_db,$parameters["id"]);
		if($clubId!=$player["team_id"])throw new Exception("nice try");
		$teamSize=$this->getTeamSize($clubId);
		if($teamSize <=Config("transfermarket_min_teamsize"))throw new Exception(Message("sell_player_teamsize_too_small",$teamSize));
		if(Config("player_resignation_compensation_matches")){
			$compensation=Config("player_resignation_compensation_matches")* $player["player_contract_salary"];
			$team=TeamsDataService::getTeamSummaryById($this->_websoccer,$this->_db,$clubId);
			if($team["team_budget"] <= $compensation)throw new Exception(Message("fireplayer_tooexpensive"));
			debitAmount($this->_websoccer,$this->_db,$clubId,$compensation,"fireplayer_compensation_subject",$player["player_firstname"]." ".$player["player_lastname"]);}
		$this->updatePlayer($player["player_id"]);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("fireplayer_success"),""));
		return null;}
	function updatePlayer($playerId){
		$columns["verein_id"]="";
		$columns["vertrag_spiele"]=0;
		$fromTable=Config("db_prefix")."_spieler";
		$whereCondition="id=%d";
		$parameters=$playerId;
		$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$parameters);}
	function getTeamSize($clubId){
		$columns="COUNT(*)AS number";
		$fromTable=Config("db_prefix")."_spieler";
		$whereCondition="verein_id=%d AND status=1 AND transfermarkt!=1";
		$parameters=$clubId;
		$result=$this->_db->querySelect($columns,$fromTable,$whereCondition,$parameters);
		$players=$result->fetch_array();
		return$players["number"];}}
class FireYouthPlayerController extends Controller {
	function executeAction($parameters){
		if(!Config("youth_enabled"))return NULL;
		$user=$this->_websoccer->getUser();
		$clubId=$user->getClubId($this->_websoccer,$this->_db);
		$player=YouthPlayersDataService::getYouthPlayerById($this->_websoccer,$this->_db,$this->_i18n,$parameters["id"]);
		if($clubId!=$player["team_id"])throw new Exception(Message("youthteam_err_notownplayer"));
		$this->_db->queryDelete(Config("db_prefix")."_youthplayer","id=%d",$parameters["id"]);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("youthteam_fire_success"),""));
		return "youth-team";}}
class FormLoginController extends Controller {
	function executeAction($parameters){
		$loginMethodClass=Config("login_method");
		if(!class_exists($loginMethodClass))throw new Exception("Login method class does not exist: ".$loginMethodClass);
		$loginMethod=new $loginMethodClass($this->_websoccer,$this->_db);
		if(Config("login_type")=="email")$userId=$loginMethod->authenticateWithEmail($parameters["loginstr"],$parameters["loginpassword"]);
		else $userId=$loginMethod->authenticateWithUsername($parameters["loginstr"],$parameters["loginpassword"]);
		if(!$userId){
			sleep(config('SLEEP_SECONDS_ON_FAILURE'));
			throw new Exception(Message("formlogin_invalid_data"));}
		loginFrontUserUsingApplicationSession($this->_websoccer,$userId);
		if(isset($parameters["rememberme"])&&$parameters["rememberme"]==1){
			$fromTable=Config("db_prefix")."_user";
			$whereCondition="id=%d";
			$parameter=$userId;
			$result=$this->_db->querySelect("passwort_salt",$fromTable,$whereCondition,$parameter);
			$saltinfo=$result->fetch_array();
			$salt=$saltinfo["passwort_salt"];
			if(!strlen($salt))$salt=generatePasswordSalt();
			$sessionToken=generateSessionToken($userId,$salt);
			$columns=array("tokenid"=>$sessionToken,"passwort_salt"=>$salt);
			$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$parameter);
			createCookie("user",$sessionToken,config('REMEMBERME_COOKIE_LIFETIME_DAYS'));}
		return (strlen($this->_websoccer->getUser()->username))? "office" : "enter-username";}}
class GoogleplusLoginController extends Controller {
	function executeAction($parameters){
		$userEmail=GoogleplusSdk::getInstance($this->_websoccer)->authenticateUser();
		if(!$userEmail){
			$this->_websoccer->addFrontMessage(new FrontMessage('warning',Message("googlepluslogin_failure"),""));
			return "home";}
		$userEmail=strtolower($userEmail);
		$userId=UsersDataService::getUserIdByEmail($this->_websoccer,$this->_db,$userEmail);
		if($userId<1)$userId=UsersDataService::createLocalUser($this->_websoccer,$this->_db, null,$userEmail);
		loginFrontUserUsingApplicationSession($this->_websoccer,$userId);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("googlepluslogin_success"),""));
		return (strlen($this->_websoccer->getUser()->username))? "office" : "enter-username";}}
class LanguageSwitcherController extends Controller {
	function executeAction($parameters){
		$lang=strtolower($parameters["lang"]);
		$this->_i18n->setCurrentLanguage($lang);
		$user=$this->_websoccer->getUser();
		if($user->id!=null){
			$fromTable=Config("db_prefix")."_user";
			$columns=array("lang"=>$lang);
			$whereCondition="id=%d";
			$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$user->id);}
		global $msg;
		$msg=[];
		include(sprintf($_SERVER['DOCUMENT_ROOT'].'/cache/messages_%s.inc.php',$lang));
		include(sprintf($_SERVER['DOCUMENT_ROOT'].'/cache/entitymessages_%s.inc.php',$lang));
		include(sprintf($_SERVER['DOCUMENT_ROOT'].'/languages/messages_%s.php',$lang));
		return null;}}
class LendPlayerController extends Controller {
	function executeAction($parameters){
		if(Config("lending_enabled"))return NULL;
		$user=$this->_websoccer->getUser();
		$clubId=$user->getClubId($this->_websoccer,$this->_db);
		$player=PlayersDataService::getPlayerById($this->_websoccer,$this->_db,$parameters["id"]);
		if($clubId!=$player["team_id"])throw new Exception(Message("lending_err_notownplayer"));
		if($player["lending_owner_id"])throw new Exception(Message("lending_err_borrowed_player"));
		if($player["lending_fee"])throw new Exception(Message("lending_err_alreadyoffered"));
		if($player["player_transfermarket"])throw new Exception(Message("lending_err_on_transfermarket"));
		$teamSize=TeamsDataService::getTeamSize($this->_websoccer,$this->_db,$clubId);
		if($teamSize <=Config("transfermarket_min_teamsize"))throw new Exception(Message("lending_err_teamsize_too_small",$teamSize));
		$minBidBoundary=round($player["player_marketvalue"] / 2);
		if($player["player_contract_matches"] <=Config("lending_matches_min"))throw new Exception(Message("lending_err_contract_too_short"));
		$this->updatePlayer($player["player_id"],$parameters["fee"]);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("lend_player_success"),""));
		return "myteam";}
	function updatePlayer($playerId,$fee){
		$columns=array("lending_fee"=>$fee);
		$fromTable=Config("db_prefix")."_spieler";
		$whereCondition="id=%d";
		$parameters=$playerId;
		$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$parameters);}}
class LogoutController extends Controller {
	function executeAction($parameters){
		$authenticatorClasses=explode(",",Config("authentication_mechanism"));
		foreach($authenticatorClasses as$authenticatorClass){
			$authenticatorClass=trim($authenticatorClass);
			if(!class_exists($authenticatorClass))throw new Exception("Class not found: ".$authenticatorClass);
			$authenticator=new $authenticatorClass($this->_websoccer);
			$authenticator->logoutUser($this->_websoccer->getUser());}
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("logout_message_title"),""));
		return "home";}}
class MarkAsUnsellableController extends Controller {
	function executeAction($parameters){
		$user=$this->_websoccer->getUser();
		$clubId=$user->getClubId($this->_websoccer,$this->_db);
		$player=PlayersDataService::getPlayerById($this->_websoccer,$this->_db,$parameters["id"]);
		if($clubId!=$player["team_id"])throw new Exception("nice try");
		$columns["unsellable"]=1;
		$fromTable=Config("db_prefix")."_spieler";
		$whereCondition="id=%d";
		$parameters=$parameters["id"];
		$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$parameters);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("myteam_unsellable_player_success"),""));
		return null;}}
class MicropaymentRedirectController extends Controller {
	function executeAction($parameters){
		$projectId=trim(Config("micropayment_project"));
		if(!strlen($projectId))throw new Exception("Configuration error: micropayment.de project ID is not specified.");
		$accessKey=trim(Config("micropayment_accesskey"));
		if(!strlen($accessKey))throw new Exception("Configuration error: micropayment.de AccessKey is not specified.");
		$validModules=[];
		if(Config("micropayment_call2pay_enabled"))$validModules[]='call2pay';
		if(Config("micropayment_handypay_enabled"))$validModules[]='handypay';
		if(Config("micropayment_ebank2pay_enabled"))$validModules[]='ebank2pay';
		if(Config("micropayment_creditcard_enabled"))$validModules[]='creditcard';
		$module=FALSE;
		if(isset($_POST['module'])){
			foreach($_POST['module'] as$moduleId=>$value)$module=$moduleId;}
		if(!$module||!in_array($moduleId,$validModules))throw new Exception('Illegal payment module.');
		$amount=$parameters['amount'];
		$priceOptions=explode(',',Config('premium_price_options'));
		$validAmount=FALSE;
		if(count($priceOptions)){
			foreach($priceOptions as$priceOption){
				$optionParts=explode(':',$priceOption);
				$realMoney=trim($optionParts[0]);
				if($amount==$realMoney)$validAmount=TRUE;}}
		if(!$validAmount)throw new Exception("Invalid amount");
		if(Config('premium_currency')!= 'EUR')throw new Exception('Configuration Error: Only payments in EUR are supported.');
		$amount=$amount*100;
		$paymentUrl='https://billing.micropayment.de/'.$module.'/event/?';
		$parameters=array('project'=>$projectId, 'amount'=>$amount, 'freeparam'=>$this->_websoccer->getUser()->id);
		$queryStr=http_build_query($parameters);
		$seal=hash('sha256',$parameters .$accessKey);
		$queryStr.='&seal='.$seal;
		$paymentUrl .= $queryStr;
		header('Location: '.escapeOutput($paymentUrl));
		exit;
		return null;}}
class MoveYouthPlayerToProfessionalController extends Controller {
	function executeAction($parameters){
		if(!Config("youth_enabled"))return NULL;
		$user=$this->_websoccer->getUser();
		$clubId=$user->getClubId($this->_websoccer,$this->_db);
		$player=YouthPlayersDataService::getYouthPlayerById($this->_websoccer,$this->_db,$this->_i18n,$parameters["id"]);
		if($clubId!=$player["team_id"])throw new Exception(Message("youthteam_err_notownplayer"));
		if($player["age"] <Config("youth_min_age_professional"))throw new Exception(Message("youthteam_makeprofessional_err_tooyoung",Config("youth_min_age_professional")));
		if($player["position"]=="Torwart")$validPositions=array("T");
		elseif($player["position"]=="Abwehr")$validPositions=array("LV","IV","RV");
		elseif($player["position"]=="Mittelfeld")$validPositions=array("LM","RM","DM","OM","ZM");
		else $validPositions=array("LS","RS","MS");
		if(!in_array($parameters["mainposition"],$validPositions))throw new Exception(Message("youthteam_makeprofessional_err_invalidmainposition"));
		$team=TeamsDataService::getTeamSummaryById($this->_websoccer,$this->_db,$clubId);
		if($team["team_budget"] <= TeamsDataService::getTotalPlayersSalariesOfTeam($this->_websoccer,$this->_db,$clubId))throw new Exception(Message("youthteam_makeprofessional_err_budgettooless"));
		$this->createPlayer($player,$parameters["mainposition"]);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("youthteam_makeprofessional_success"),""));
		return "myteam";}
	function createPlayer($player,$mainPosition){
		$time=strtotime("-".$player["age"]." years",Timestamp());
		$birthday=date("Y-m-d",$time);
		$columns=array("verein_id"=>$player["team_id"],"vorname"=>$player["firstname"],"nachname"=>$player["lastname"],"geburtstag"=>$birthday,"age"=>$player["age"],"position"=>$player["position"],"position_main"=>$mainPosition,
			"nation"=>$player["nation"],"w_staerke"=>$player["strength"],"w_technik"=>Config("youth_professionalmove_technique"),"w_kondition"=>Config("youth_professionalmove_stamina"),
			"w_frische"=>Config("youth_professionalmove_freshness"),"w_zufriedenheit"=>Config("youth_professionalmove_satisfaction"),
			"vertrag_gehalt"=>Config("youth_salary_per_strength")* $player["strength"],"vertrag_spiele"=>Config("youth_professionalmove_matches"),"vertrag_torpraemie"=> 0,"status"=>"1");
		$this->_db->queryInsert($columns,Config("db_prefix")."_spieler");
		$this->_db->queryDelete(Config("db_prefix")."_youthplayer","id=%d",$player["id"]);}}
class OrderBuildingController extends Controller {
	function executeAction($parameters){
		$buildingId=$parameters['id'];
		$user=$this->_websoccer->getUser();
		$teamId=$user->getClubId($this->_websoccer,$this->_db);
		if(!$teamId)throw new Exception(Message("feature_requires_team"));
		$dbPrefix=Config('db_prefix');
		$result=$this->_db->querySelect('*',$dbPrefix.'_stadiumbuilding', 'id=%d',$buildingId);
		$building=$result->fetch_array();
		if(!$building)throw new Exception('illegal building.');
		$team=TeamsDataService::getTeamSummaryById($this->_websoccer,$this->_db,$teamId);
		if($team['team_budget'] <= $building['costs'])throw new Exception(Message('stadiumenvironment_build_err_too_expensive'));
		$result=$this->_db->querySelect('*',$dbPrefix.'_buildings_of_team', 'team_id=%d AND building_id=%d',array($teamId,$buildingId));
		$buildingExists=$result->fetch_array();
		if($buildingExists)throw new Exception(Message('stadiumenvironment_build_err_already_exists'));
		if($building['required_building_id']){
			$result=$this->_db->querySelect('*',$dbPrefix.'_buildings_of_team', 'team_id=%d AND building_id=%d',array($teamId,$building['required_building_id']));
			$requiredBuildingExists=$result->fetch_array();
			if(!$requiredBuildingExists)throw new Exception(Message('stadiumenvironment_build_err_requires_building'));}
		if($building['premiumfee']>$user->premiumBalance)throw new Exception(Message('stadiumenvironment_build_err_premium_balance'));
		debitAmount($this->_websoccer,$this->_db,$teamId,$building['costs'],'building_construction_fee_subject',$building['name']);
		$constructionDeadline=Timestamp()+ $building['construction_time_days']*24*3600;
		$this->_db->queryInsert(array('building_id'=>$buildingId, 'team_id'=>$teamId, 'construction_deadline'=>$constructionDeadline),$dbPrefix.'_buildings_of_team');
		if($building['premiumfee'])PremiumDataService::debitAmount($this->_websoccer,$this->_db,$user->id,$building['premiumfee'],"order-building");
		if($building['effect_fanpopularity']!=0){
			$result=$this->_db->querySelect('fanbeliebtheit',$dbPrefix.'_user', 'id=%d',$user->id, 1);
			$userinfo=$result->fetch_array();
			$popularity=min(100, max(1,$building['effect_fanpopularity'] + $userinfo['fanbeliebtheit']));
			$this->_db->queryUpdate(array('fanbeliebtheit'=>$popularity),$dbPrefix.'_user', 'id=%d',$user->id);}
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("stadiumenvironment_build_success"),""));
		return null;}}
class PaypalPaymentNotificationController extends Controller {
	function executeAction($parameters){
		$req='cmd=_notify-validate';
		foreach($_POST as$key=>$value){
			$value=urlencode(stripslashes($value));
			$req.="&$key=$value";}
		$header="POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header.="Host: ".Config("paypal_host")."\r\n";
		$header.="Content-Type: application/x-www-form-urlencoded\r\n";
		$header.="Content-Length: ". strlen($req)."\r\n\r\n";
		$fp=fsockopen(Config("paypal_url"), 443,$errno,$errstr, 30);
		if(!$fp)throw new Exception("Error on HTTP(S)request. Error: ".$errno." ".$errstr);
		else{
			fputs($fp,$header .$req);
			$response="";
			while(!feof($fp)){
				$res=fgets($fp, 1024);
				$response .= $res;
				if(strcmp($res,"VERIFIED")==0){
					if(strtolower($parameters["receiver_email"])!= strtolower(Config("paypal_receiver_email"))){
						sendSystemEmail($this->_websoccer,Config("systememail"),"Failed PayPal confirmation: Invalid Receiver","Invalid receiver: ".$parameters["receiver_email"]);
						throw new Exception("Receiver E-Mail not correct.");}
					if($parameters["payment_status"]!="Completed"){
						sendSystemEmail($this->_websoccer,Config("systememail"),"Failed PayPal confirmation: Invalid Status","A paypment notification has been sent, but has an invalid status: ".$parameters["payment_status"]);
						throw new Exception("Payment status not correct.");}
					$amount=$parameters["mc_gross"];
					$userId=$parameters["custom"];
					PremiumDataService::createPaymentAndCreditPremium($this->_websoccer,$this->_db,$userId,$amount,"paypal-notify");
					die(200);}
				elseif(strcmp($res,"INVALID")==0)throw new Exception("Payment is invalid");}
			fclose($fp);
			header('X-Error-Message: invalid paypal response: '.$response, true, 500);
			die('X-Error-Message: invalid paypal response: '.$response);}
		return null;}}
class PremiumActionDummyController extends Controller {
	function executeAction($parameters){
		$this->_websoccer->addFrontMessage(new FrontMessage('success',"Premium action completed","testparam1: ".$parameters["testparam1"]." - testparam2: ".$parameters["testparam2"]));
		return null;}}
class RegisterFormController extends Controller {
	function executeAction($parameters){
		if(!Config("allow_userregistration"))throw new Exception(Message("registration_disabled"));
		$illegalUsernames=explode(",",strtolower(str_replace(",",",",Config("illegal_usernames"))));
		if(array_search(strtolower($parameters["nick"]),$illegalUsernames))throw new Exception(Message("registration_illegal_username"));
		if($parameters["email"] !==$parameters["email_repeat"])throw new Exception(Message("registration_repeated_email_notmatching"));
		if($parameters["pswd"] !==$parameters["pswd_repeat"])throw new Exception(Message("registration_repeated_password_notmatching"));
		if(Config("register_use_captcha")&&strlen(Config("register_captcha_publickey"))&&strlen(Config("register_captcha_privatekey"))){
			include_once($_SERVER['DOCUMENT_ROOT']."/lib/recaptcha/recaptchalib.php");
			$captchaResponse=recaptcha_check_answer(Config("register_captcha_privatekey"),$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
			if(!$captchaResponse->is_valid)throw new Exception(Message("registration_invalidcaptcha"));}
		$columns="COUNT(*)AS hits";
		$fromTable=Config("db_prefix")."_user";
		$maxNumUsers=(int)Config("max_number_of_users");
		if($maxNumUsers){
			$wherePart="status=1";
			$result=$this->_db->querySelect($columns,$fromTable,$wherePart);
			$rows=$result->fetch_array();
			if($rows["hits"] >= $maxNumUsers)throw new Exception(Message("registration_max_number_users_exceeded"));}
		$wherePart="UPPER(nick)='%s' OR UPPER(email)='%s'";
		$result=$this->_db->querySelect($columns,$fromTable,$wherePart,array(strtoupper($parameters["nick"]), strtoupper($parameters["email"])));
		$rows=$result->fetch_array();
		if($rows["hits"])throw new Exception(Message("registration_user_exists"));
		$this->_createUser($parameters,$fromTable);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("register-success_message_title"),Message("register-success_message_content")));
		return "register-success";}
	function _createUser($parameters,$fromTable){
		$dbcolumns=[];
		$dbcolumns["nick"]=$parameters["nick"];
		$dbcolumns["email"]=strtolower($parameters["email"]);
		$dbcolumns["passwort_salt"]=generatePasswordSalt();
		$dbcolumns["passwort"]=hashPassword($parameters["pswd"],$dbcolumns["passwort_salt"]);
		$dbcolumns["datum_anmeldung"]=Timestamp();
		$dbcolumns["schluessel"]=str_replace("&","_",generatePassword());
		$dbcolumns["status"]=2;
		$dbcolumns["lang"]=$this->_i18n->getCurrentLanguage();
		if(Config("premium_initial_credit"))$dbcolumns["premium_balance"]=Config("premium_initial_credit");
		$this->_db->queryInsert($dbcolumns,$fromTable);
		$columns="id";
		$wherePart="email='%s'";
		$result=$this->_db->querySelect($columns,$fromTable,$wherePart,$dbcolumns["email"]);
		$newuser=$result->fetch_array();
		$querystr="key=".$dbcolumns["schluessel"] ."&userid=".$newuser["id"];
		$tplparameters["activationlink"]=aUrl("activate",$querystr,"activate-user",TRUE);
		sendSystemEmailFromTemplate($this->_websoccer,$this->_i18n,$dbcolumns["email"],Message("activation_email_subject"),"useractivation",$tplparameters);
		$event=new UserRegisteredEvent($this->_websoccer,$this->_db,$this->_i18n,$newuser["id"],$dbcolumns["nick"],$dbcolumns["email"]);
		dispatchEvent($event);}}
class RemoveFormationTemplateController extends Controller {
	function executeAction($parameters){
		$teamId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);
		$dbTable=Config('db_prefix').'_aufstellung';
		$result=$this->_db->querySelect('verein_id',$dbTable, 'id=%d',$parameters['templateid']);
		$template=$result->fetch_array();
		if(!$template||$template['verein_id']!=$teamId)throw new Exception('illegal template ID');
		$this->_db->queryDelete($dbTable, 'id=%d',$parameters['templateid']);
		return null;}}
class RemoveNationalPlayerController extends Controller{
	function executeAction($parameters){
		if(!Config("nationalteams_enabled"))return NULL;
		$teamId=NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer,$this->_db);
		if(!$teamId)throw new Exception(Message("nationalteams_user_requires_team"));
		$result=$this->_db->querySelect("name",Config("db_prefix")."_verein","id=%d",$teamId);
		$team=$result->fetch_array();
		$fromTable=Config("db_prefix")."_spieler";
		$result=$this->_db->querySelect("nation",$fromTable,"id=%d",$parameters["id"]);
		$player=$result->fetch_array();
		if(!$player)throw new Exception(Message('error_page_not_found'));
		if($player["nation"]!=$team["name"])throw new Exception("Player is from different nation.");
		$this->_db->queryDelete(Config("db_prefix")."_nationalplayer","player_id=%d AND team_id=%d",array($parameters["id"],$teamId));
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("nationalteams_removeplayer_success"),""));
		return "nationalteam";}}
class RemovePlayerFromTransfermarketController extends Controller {
	function executeAction($parameters){
		if(!Config("transfermarket_enabled"))return NULL;
		$user=$this->_websoccer->getUser();
		$clubId=$user->getClubId($this->_websoccer,$this->_db);
		$player=PlayersDataService::getPlayerById($this->_websoccer,$this->_db,$parameters["id"]);
		if($clubId!=$player["team_id"])throw new Exception("nice try");
		$highestBid=TransfermarketDataService::getHighestBidForPlayer($this->_websoccer,$this->_db,$parameters["id"],$player["transfer_start"],$player["transfer_end"]);
		if($highestBid)throw new Exception(Message("transfermarket_remove_err_bidexists"));
		$this->_db->queryUpdate(array('transfermarkt'=>'0', 'transfer_start'=>0, 'transfer_ende'=>0),Config('db_prefix').'_spieler', 'id=%d',$parameters['id']);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("transfermarket_remove_success"),""));
		return "myteam";}}
class RemoveYouthPlayerFromMarketController extends Controller {
	function executeAction($parameters){
		if(!Config("youth_enabled"))return NULL;
		$user=$this->_websoccer->getUser();
		$clubId=$user->getClubId($this->_websoccer,$this->_db);
		$player=YouthPlayersDataService::getYouthPlayerById($this->_websoccer,$this->_db,$this->_i18n,$parameters["id"]);
		if($clubId!=$player["team_id"])throw new Exception(Message("youthteam_err_notownplayer"));
		$this->updatePlayer($parameters["id"]);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("youthteam_removefrommarket_success"),""));
		return "youth-team";}
	function updatePlayer($playerId){
		$columns=array("transfer_fee"=> 0);
		$fromTable=Config("db_prefix")."_youthplayer";
		$whereCondition="id=%d";
		$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$playerId);}}
class RenameClubController extends Controller {
	function executeAction($parameters){
		if(!Config('rename_club_enabled'))throw new Exceltion("feature is disabled");
		$user=$this->_websoccer->getUser();
		$clubId=$user->getClubId($this->_websoccer,$this->_db);
		$team=TeamsDataService::getTeamSummaryById($this->_websoccer,$this->_db,$clubId);
		if(!$team)return null;
		$short=strtoupper($parameters['kurz']);
		$this->_db->queryUpdate(array('name'=>$parameters['name'],'kurz'=>$short),Config('db_prefix').'_verein', 'id=%d',$clubId);
		$this->_db->queryUpdate(array('S.name'=>$parameters['stadium']),Config('db_prefix').'_verein AS C INNER JOIN '.Config('db_prefix').'_stadion AS S ON S.id=C.stadion_id', 'C.id=%d',$clubId);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("rename-club_success"),""));
		return 'league';}}
class ReportAbsenceController extends Controller {
	function executeAction($parameters){
		$userId=$this->_websoccer->getUser()->id;
		$deputyId=UsersDataService::getUserIdByNick($this->_websoccer,$this->_db,$parameters["deputynick"]);
		if($deputyId<1)throw new Exception(Message("absence_err_invaliddeputy"));
		if($userId==$deputyId)throw new Exception(Message("absence_err_deputyisself"));
		makeUserAbsent($this->_websoccer,$this->_db,$userId,$deputyId,$parameters['days']);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("absence_report_success"),""));
		return null;}}
class ReturnFromAbsenceController extends Controller {
	function executeAction($parameters){
		$userId=$this->_websoccer->getUser()->id;
		confirmComeback($this->_websoccer,$this->_db,$userId);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("absence_return_success"),""));
		return "office";}}
class SaveFormationController extends Controller {
	function executeAction($parameters){
		$this->_isNationalTeam=(Request('nationalteam'))?TRUE:FALSE;
		$user=$this->_websoccer->getUser();
		if($this->_isNationalTeam)$teamId=NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer,$this->_db);
		else $teamId=$user->getClubId($this->_websoccer,$this->_db);
		$nextMatches=MatchesDataService::getNextMatches($this->_websoccer,$this->_db,$teamId,Config('formation_max_next_matches'));
		if(!count($nextMatches))throw new Exception(Message('formation_err_nonextmatch'));
		$matchId=$parameters['id'];
		foreach($nextMatches as$nextMatch){
			if($nextMatch['match_id']==$matchId){
				$matchinfo=$nextMatch;
				break;}}
		if(!isset($matchinfo))throw new Exception('illegal match id');
		$players=PlayersDataService::getPlayersOfTeamById($this->_websoccer,$this->_db,$teamId,$this->_isNationalTeam,$matchinfo['match_type']=='cup',$matchinfo['match_type']!='friendly');
		$this->validatePlayer($parameters['player1'],$players);
		$this->validatePlayer($parameters['player2'],$players);
		$this->validatePlayer($parameters['player3'],$players);
		$this->validatePlayer($parameters['player4'],$players);
		$this->validatePlayer($parameters['player5'],$players);
		$this->validatePlayer($parameters['player6'],$players);
		$this->validatePlayer($parameters['player7'],$players);
		$this->validatePlayer($parameters['player8'],$players);
		$this->validatePlayer($parameters['player9'],$players);
		$this->validatePlayer($parameters['player10'],$players);
		$this->validatePlayer($parameters['player11'],$players);
		$this->validatePlayer($parameters['bench1'],$players, TRUE);
		$this->validatePlayer($parameters['bench2'],$players, TRUE);
		$this->validatePlayer($parameters['bench3'],$players, TRUE);
		$this->validatePlayer($parameters['bench4'],$players, TRUE);
		$this->validatePlayer($parameters['bench5'],$players, TRUE);
		$validSubstitutions=[];
		for($subNo=1; $subNo <= 3;++$subNo){
			$playerIn=$parameters['sub'.$subNo.'_in'];
			$playerOut=$parameters['sub'.$subNo.'_out'];
			$playerMinute=$parameters['sub'.$subNo.'_minute'];
			if($playerIn!=null &&$playerIn>0 &&$playerOut!=null &&$playerOut>0 &&$playerMinute!=null &&$playerMinute){
				$this->validateSubstitution($playerIn,$playerOut,$playerMinute,$players);
				$validSubstitutions[]=$subNo;}}
		$this->saveFormation($teamId,$matchinfo['match_id'],$parameters,$validSubstitutions);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message('saved_message_title'), ''));
		return null;}
	function validatePlayer($playerId,$players,$bench=FALSE){
		if($playerId==null||$playerId==0)return;
		if(!isset($players[$playerId]))throw new Exception(Message('formation_err_invalidplayer'));
		$position=$players[$playerId]['position'];
		if(isset($this->_addedPlayers[$position][$playerId]))throw new Exception(Message('formation_err_duplicateplayer'));
		if($players[$playerId]['matches_injured']>0||$players[$playerId]['matches_blocked'])throw new Exception(Message('formation_err_blockedplayer'));
		$this->_addedPlayers[$position][$playerId]=TRUE;}
	function validateSubstitution($playerIn,$playerOut,$minute,$players){
		if(!isset($players[$playerIn])|| !isset($players[$playerOut])|| !isset($this->_addedPlayers[$players[$playerIn]['position']][$playerIn])|| !isset($this->_addedPlayers[$players[$playerOut]['position']][$playerOut])){
			throw new Exception(Message('formation_err_invalidplayer'));}
		if($minute<2||$minute>90)throw new Exception(Message('formation_err_invalidsubstitutionminute'));}
	function saveFormation($teamId,$matchId,$parameters,$validSubstitutions){
		$fromTable=Config('db_prefix').'_aufstellung';
		$columns['verein_id']=$teamId;
		$columns['datum']=Timestamp();
		$columns['offensive']=$parameters['offensive'];
		$columns['setup']=$parameters['setup'];
		$columns['longpasses']=$parameters['longpasses'];
		$columns['counterattacks']=$parameters['counterattacks'];
		$columns['freekickplayer']=$parameters['freekickplayer'];
		for($playerNo=1; $playerNo <= 11;++$playerNo){
			$columns['spieler'.$playerNo]=$parameters['player'.$playerNo];
			$columns['spieler'.$playerNo.'_position']=$parameters['player'.$playerNo.'_pos'];}
		for($playerNo=1; $playerNo <= 5; $playerNo++)$columns['ersatz'.$playerNo]=$parameters['bench'.$playerNo];
		for($subNo=1; $subNo <= 3;++$subNo){
			if(in_array($subNo,$validSubstitutions)){
				$columns['w'.$subNo.'_raus']=$parameters['sub'.$subNo.'_out'];
				$columns['w'.$subNo.'_rein']=$parameters['sub'.$subNo.'_in'];
				$columns['w'.$subNo.'_minute']=$parameters['sub'.$subNo.'_minute'];
				$columns['w'.$subNo.'_condition']=$parameters['sub'.$subNo.'_condition'];
				$columns['w'.$subNo.'_position']=$parameters['sub'.$subNo.'_position'];}
			else{
				$columns['w'.$subNo.'_raus']='';
				$columns['w'.$subNo.'_rein']='';
				$columns['w'.$subNo.'_minute']='';
				$columns['w'.$subNo.'_condition']='';
				$columns['w'.$subNo.'_position']='';}}
		$result=$this->_db->querySelect('id',$fromTable, 'verein_id=%d AND match_id=%d',array($teamId,$matchId));
		$existingFormation=$result->fetch_array();
		if(isset($existingFormation['id']))$this->_db->queryUpdate($columns,$fromTable, 'id=%d',$existingFormation['id']);
		else{
			$columns['match_id']=$matchId;
			$this->_db->queryInsert($columns,$fromTable);}
		if(strlen($parameters['templatename'])){
			$result=$this->_db->querySelect('COUNT(*)AS templates',$fromTable, 'verein_id=%d AND templatename IS NOT NULL',$teamId);
			$existingTemplates=$result->fetch_array();
			if($existingTemplates &&$existingTemplates['templates'] >=Config('formation_max_templates')){
				$this->_websoccer->addFrontMessage(new FrontMessage('warning',
					Message('formation_template_saving_failed_because_boundary_title',Config('formation_max_templates')),
					Message('formation_template_saving_failed_because_boundary_details')));}
			else{
				$columns['match_id']=NULL;
				$columns['templatename']=$parameters['templatename'];
				$this->_db->queryInsert($columns,$fromTable);}}}}
class SaveMatchChangesController extends Controller {
	function executeAction($parameters){
		$this->_addedPlayers=[];
		$user=$this->_websoccer->getUser();
		$teamId=$user->getClubId($this->_websoccer,$this->_db);
		$nationalTeamId=NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer,$this->_db);
		$matchId=$parameters['id'];
		$matchinfo=MatchesDataService::getMatchSubstitutionsById($this->_websoccer,$this->_db,$matchId);
		if(!isset($matchinfo['match_id']))throw new Exception(Message('formation_err_nonextmatch'));
		if($matchinfo['match_home_id']!=$teamId &&$matchinfo['match_guest_id']!=$teamId &&$matchinfo['match_home_id']!=$nationalTeamId &&$matchinfo['match_guest_id']!=$nationalTeamId)throw new Exception('nice try');
		if($matchinfo['match_simulated'])throw new Exception(Message('match_details_match_completed'));
		$columns=[];
		$teamPrefix=($matchinfo['match_home_id']==$teamId||$matchinfo['match_home_id']==$nationalTeamId)? 'home' : 'guest';
		$teamPrefixDb=($matchinfo['match_home_id']==$teamId||$matchinfo['match_home_id']==$nationalTeamId)? 'home' : 'gast';
		$occupiedSubPos=[];
		$existingFutureSubs=[];
		for($subNo=1; $subNo <= 3;++$subNo){
			$existingMinute=(int)$matchinfo[$teamPrefix.'_sub'.$subNo.'_minute'];
			if($existingMinute>0 &&$existingMinute <= $matchinfo['match_minutes'])$occupiedSubPos[$subNo]=TRUE;
			elseif($existingMinute){
				$existingFutureSubs[$matchinfo[$teamPrefix.'_sub'.$subNo.'_out']]=array('minute'=>$matchinfo[$teamPrefix.'_sub'.$subNo.'_minute'],'in'=>$matchinfo[$teamPrefix.'_sub'.$subNo.'_in'],
					'condition'=>$matchinfo[$teamPrefix.'_sub'.$subNo.'_condition'],'position'=>$matchinfo[$teamPrefix.'_sub'.$subNo.'_position'],'slot'=>$subNo);}}
		if(count($occupiedSubPos)< 3){
			$nextPossibleMinute=$matchinfo['match_minutes'] +Config('sim_interval')+ 1;
			for($subNo=1; $subNo <= 3;++$subNo){
				$newOut=(int)$parameters['sub'.$subNo.'_out'];
				$newIn=(int)$parameters['sub'.$subNo.'_in'];
				$newMinute=(int)$parameters['sub'.$subNo.'_minute'];
				$newCondition=$parameters['sub'.$subNo.'_condition'];
				$newPosition=$parameters['sub'.$subNo.'_position'];
				$slot=FALSE;
				$saveSub=TRUE;
				if(isset($existingFutureSubs[$newOut])&&$newIn==$existingFutureSubs[$newOut]['in'] &&$newCondition==$existingFutureSubs[$newOut]['condition'] &&$newMinute==$existingFutureSubs[$newOut]['minute']
					&&$newPosition==$existingFutureSubs[$newOut]['position']){
					$saveSub=FALSE;}
				for($slotNo=1; $slotNo <= 3;++$slotNo){
					if(!isset($occupiedSubPos[$slotNo])){
						$slot=$slotNo;
						break;}}
				if($slot &&$newOut &&$newIn &&$newMinute){
					if($saveSub &&$newMinute<$nextPossibleMinute){
						$newMinute=$nextPossibleMinute;
						$this->_websoccer->addFrontMessage(new FrontMessage('warning', '',Message('match_details_changes_too_late_altered',$subNo)));}
					$columns[$teamPrefixDb.'_w'.$slot.'_raus']=$newOut;
					$columns[$teamPrefixDb.'_w'.$slot.'_rein']=$newIn;
					$columns[$teamPrefixDb.'_w'.$slot.'_minute']=$newMinute;
					$columns[$teamPrefixDb.'_w'.$slot.'_condition']=$newCondition;
					$columns[$teamPrefixDb.'_w'.$slot.'_position']=$newPosition;
					$occupiedSubPos[$slot]=TRUE;}}}
		$prevOffensive=$matchinfo['match_'.$teamPrefix.'_offensive'];
		$prevLongpasses=$matchinfo['match_'.$teamPrefix.'_longpasses'];
		$prevCounterattacks=$matchinfo['match_'.$teamPrefix.'_counterattacks'];
		if(!$prevLongpasses)$prevLongpasses='0';
		if(!$prevCounterattacks)$prevCounterattacks='0';
		if($prevOffensive !==$parameters['offensive']||$prevLongpasses !==$parameters['longpasses']||$prevCounterattacks !==$parameters['counterattacks']){
			$alreadyChanged=$matchinfo['match_'.$teamPrefix.'_offensive_changed'];
			if($alreadyChanged >=Config('sim_allow_offensivechanges'))throw new Exception(Message('match_details_changes_too_often',Config('sim_allow_offensivechanges')));
			$columns[$teamPrefixDb.'_offensive']=$parameters['offensive'];
			$columns[$teamPrefixDb.'_longpasses']=$parameters['longpasses'];
			$columns[$teamPrefixDb.'_counterattacks']=$parameters['counterattacks'];
			$columns[$teamPrefixDb.'_offensive_changed']=$alreadyChanged + 1;
			$this->_createMatchReportMessage($user,$matchId,$matchinfo['match_minutes'],($teamPrefix=='home'));}
		$prevFreekickPlayer=$matchinfo['match_'.$teamPrefix.'_freekickplayer'];
		if($parameters['freekickplayer'] &&$parameters['freekickplayer']!=$prevFreekickPlayer)$columns[$teamPrefixDb.'_freekickplayer']=$parameters['freekickplayer'];
		if(count($columns)){
			$fromTable=Config('db_prefix').'_spiel';
			$whereCondition='id=%d';
			$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$matchId);}
		$this->_updatePlayerPosition($parameters,$matchId,$teamId);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message('saved_message_title'), ''));
		return "match";}
	function _updatePlayerPosition($parameters,$matchId,$teamId){
		$players=MatchesDataService::getMatchPlayerRecordsByField($this->_websoccer,$this->_db,$matchId,$teamId);
		$playersOnField=$players['field'];
		$submittedPositions=[];
		for($playerNo=1; $playerNo <= 11;++$playerNo){
			$playerId=$parameters['player'.$playerNo];
			$playerPos=$parameters['player'.$playerNo.'_pos'];
			if($playerId &&$playerPos)$submittedPositions[$playerId]=$playerPos;}
		$updateTable=Config('db_prefix').'_spiel_berechnung';
		$whereCondition='id=%d';
		$setupMainMapping=array('T'=>'Torwart', 'LV'=>'Abwehr', 'RV'=>'Abwehr', 'IV'=>'Abwehr', 'DM'=>'Mittelfeld', 'LM'=>'Mittelfeld', 'ZM'=>'Mittelfeld', 'RM'=>'Mittelfeld', 'OM'=>'Mittelfeld', 'LS'=>'Sturm', 'MS'=>'Sturm', 'RS'=>'Sturm');
		foreach($playersOnField as$player){
			if(isset($submittedPositions[$player['id']])){
				$newPos=$submittedPositions[$player['id']];
				$oldPos=$player['match_position_main'];
				if($newPos!=$oldPos){
					$position=$setupMainMapping[$newPos];
					$strength=$player['strength'];
					if($player['position']!=$position &&$player['position_main']!=$newPos &&$player['position_second']!=$newPos)$strength=round($strength*(1 -Config('sim_strength_reduction_wrongposition')/ 100));
					elseif(strlen($player['position_main'])&&$player['position_main']!=$newPos &&($player['position']==$position||$player['position_second']==$newPos)){
						$strength=round($strength*(1 -Config('sim_strength_reduction_secondary')/ 100));}
					$this->_db->queryUpdate(array('position_main'=>$newPos, 'position'=>$position, 'w_staerke'=>$strength),$updateTable,$whereCondition,$player['match_record_id']);}}}}
	function _createMatchReportMessage(User $user,$matchId,$minute,$isHomeTeam){
		$result=$this->_db->querySelect('id',Config('db_prefix').'_spiel_text', 'aktion=\'Taktikaenderung\'');
		$messages=[];
		while($message=$result->fetch_array())$messages[]=$message['id'];
		if(!count($messages))return;
		$messageId=$messages[array_rand($messages)];
		$this->_db->queryInsert(array('match_id'=>$matchId, 'message_id'=>$messageId, 'minute'=>$minute, 'active_home'=>$isHomeTeam, 'playernames'=>$user->username),Config('db_prefix').'_matchreport');}}
class SaveProfileController extends Controller {
	function executeAction($parameters){
		$user=$this->_websoccer->getUser();
		$columns=[];
		if($parameters["newpassword"]!=null){
			$salt=generatePasswordSalt();
			$hashedPassword=hashPassword($parameters["newpassword"],$salt);
			$columns["passwort_salt"]=$salt;
			$columns["passwort"]=$hashedPassword;}
		if($parameters["newemail"]!=null){
			$activationKey=generatePassword();
			$columns["schluessel"]=$activationKey;
			$columns["status"]=2;
			$columns["email"]=$parameters["newemail"];
			$user->email=$parameters["newemail"];
			$querystr="key=".$columns["schluessel"] ."&userid=".$user->id;
			$tplparameters["activationlink"]=aUrl("activate",$querystr,"activate-user",TRUE);
			sendSystemEmailFromTemplate($this->_websoccer,$this->_i18n,
			$user->email,
			Message("activation_changedemail_subject"),
			"changed_email_activation",
			$tplparameters);
			$this->_websoccer->addFrontMessage(new FrontMessage('warning',Message("profile_changedemail_message_title"),Message("profile_changedemail_message_content")));}
		$columns["name"]=$parameters["realname"];
		$columns["wohnort"]=$parameters["place"];
		$columns["land"]=$parameters["country"];
		$columns["beruf"]=$parameters["occupation"];
		$columns["interessen"]=$parameters["interests"];
		$columns["lieblingsverein"]=$parameters["favorite_club"];
		$columns["homepage"]=$parameters["homepage"];
		$columns["c_hideinonlinelist"]=$parameters["c_hideinonlinelist"];
		if($parameters["birthday"]){
			$dateObj=DateTime::createFromFormat(Config("date_format"),$parameters["birthday"]);
			$columns["geburtstag"]=$dateObj->format("Y-m-d");}
		if(count($columns)){
			$fromTable=Config("db_prefix")."_user";
			$whereCondition="id=%d";
			$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$user->id);}
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("saved_message_title"),""));
		return "profile";}}
class SaveTicketsController extends Controller {
	function executeAction($parameters){
		$user=$this->_websoccer->getUser();
		$clubId=$user->getClubId($this->_websoccer,$this->_db);
		if(!$clubId)return null;
		$columns["preis_stehen"]=$parameters["p_stands"];
		$columns["preis_sitz"]=$parameters["p_seats"];
		$columns["preis_haupt_stehen"]=$parameters["p_stands_grand"];
		$columns["preis_haupt_sitze"]=$parameters["p_seats_grand"];
		$columns["preis_vip"]=$parameters["p_vip"];
		$fromTable=Config("db_prefix")."_verein";
		$whereCondition="id=%d";
		$parameters=$clubId;
		$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$parameters);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("saved_message_title"),""));
		return null;}}
class SaveUsernameController extends Controller {
	function executeAction($parameters){
		if(strlen($this->_websoccer->getUser()->username))throw new Exception("user name is already set.");
		$illegalUsernames=explode(",",strtolower(str_replace(",",",",Config("illegal_usernames"))));
		if(array_search(strtolower($parameters["nick"]),$illegalUsernames))throw new Exception(Message("registration_illegal_username"));
		$fromTable=Config("db_prefix")."_user";
		$wherePart="UPPER(nick)='%s'";
		$result=$this->_db->querySelect("COUNT(*)AS hits",$fromTable,$wherePart, strtoupper($parameters["nick"]));
		$rows=$result->fetch_array();
		if($rows["hits"])throw new Exception(Message("registration_user_exists"));
		$this->_db->queryUpdate(array("nick"=>$parameters["nick"]),$fromTable,"id=%d",$this->_websoccer->getUser()->id);
		$this->_websoccer->getUser()->username=$parameters["nick"];
		return "office";}}
class SaveYouthFormationController extends Controller{
	function executeAction($parameters){
		$user=$this->_websoccer->getUser();
		$teamId=$user->getClubId($this->_websoccer,$this->_db);
		$matchinfo=YouthMatchesDataService::getYouthMatchinfoById($this->_websoccer,$this->_db,$this->_i18n,$parameters["matchid"]);
		if($matchinfo["home_team_id"]==$teamId)$teamPrefix="home";
		elseif($matchinfo["guest_team_id"]==$teamId)$teamPrefix="guest";
		else throw new Exception(Message('error_page_not_found'));
		if($matchinfo["matchdate"]<Timestamp()|| $matchinfo["simulated"])throw new Exception(Message("youthformation_err_matchexpired"));
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
		$validSubstitutions=[];
		for($subNo=1; $subNo <= 3;++$subNo){
			$playerIn=$parameters["sub".$subNo ."_in"];
			$playerOut=$parameters["sub".$subNo ."_out"];
			$playerMinute=$parameters["sub".$subNo ."_minute"];
			if($playerIn!=null &&$playerIn>0 &&$playerOut!=null &&$playerOut>0 &&$playerMinute!=null &&$playerMinute){
				$this->validateSubstitution($playerIn,$playerOut,$playerMinute);
				$validSubstitutions[]=$subNo;}}
		$this->saveFormation($teamId,$parameters,$validSubstitutions,$matchinfo,$teamPrefix);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("saved_message_title"),""));
		return null;}
	function validatePlayer($playerId){
		$this->_addedPlayers=[];
		if($playerId==null||$playerId==0)return;
		if(isset($this->_addedPlayers[$playerId]))throw new Exception(Message("formation_err_duplicateplayer"));
		$this->_addedPlayers[$playerId]=TRUE;}
	function validateSubstitution($playerIn,$playerOut,$minute){
		if(!isset($this->_addedPlayers[$playerIn])|| !isset($this->_addedPlayers[$playerOut]))throw new Exception(Message("formation_err_invalidplayer"));
		if($minute<1||$minute>90)throw new Exception(Message("formation_err_invalidsubstitutionminute"));}
	function saveFormation($teamId,$parameters,$validSubstitutions,$matchinfo,$teamPrefix){$this->_db->queryDelete(Config("db_prefix")."_youthmatch_player","match_id=%d AND team_id=%d",array($parameters["matchid"],$teamId));
		$setupParts=explode("-", $parameters["setup"]);
		if(count($setupParts)!= 5)throw new Exception("illegal formation setup");
 		$mainPositionMapping=array(1=> "T");
 		if($setupParts[0]==1)$mainPositionMapping[2]="IV";
 		elseif($setupParts[0]==2){
 			$mainPositionMapping[2]="IV";
 			$mainPositionMapping[3]="IV";}
 		elseif($setupParts[0]==3){
 			$mainPositionMapping[2]="LV";
 			$mainPositionMapping[3]="IV";
 			$mainPositionMapping[4]="RV";}
 		else{
 			$mainPositionMapping[2]="LV";
 			$mainPositionMapping[3]="IV";
 			$mainPositionMapping[4]="IV";
 			$mainPositionMapping[5]="RV";
 			$setupParts[0]=4;}
 		if($setupParts[1]==1)$mainPositionMapping[$setupParts[0] + 2]="DM";
 		elseif($setupParts[1]==2){
 			$mainPositionMapping[$setupParts[0] + 2]="DM";
 			$mainPositionMapping[$setupParts[0] + 3]="DM";}
		else $setupParts[1]=0;
 		if($setupParts[2]==1)$mainPositionMapping[$setupParts[0] + $setupParts[1] + 2]="ZM";
 		elseif($setupParts[2]==2){
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + 2]="LM";
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + 3]="RM";}
 		elseif($setupParts[2]==3){
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + 2]="LM";
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + 3]="ZM";
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + 4]="RM";}
 		elseif($setupParts[2]==4){
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + 2]="LM";
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + 3]="ZM";
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + 4]="ZM";
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + 5]="RM";}
 		else $setupParts[2]=0;
 		if($setupParts[3]==1)$mainPositionMapping[$setupParts[0] + $setupParts[1] + $setupParts[2] + 2]="OM";
 		elseif($setupParts[3]==2){
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + $setupParts[2] + 2]="OM";
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + $setupParts[2] + 3]="OM";}
 		else $setupParts[3]=0;
 		if($setupParts[4]==1)$mainPositionMapping[$setupParts[0] + $setupParts[1] + $setupParts[2] + $setupParts[3] + 2]="MS";
 		elseif($setupParts[4]==2){
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + $setupParts[2] + $setupParts[3] + 2]="MS";
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + $setupParts[2] + $setupParts[3] + 3]="MS";}
 		elseif($setupParts[4]==3){
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + $setupParts[2] + $setupParts[3] + 2]="LS";
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + $setupParts[2] + $setupParts[3] + 3]="MS";
 			$mainPositionMapping[$setupParts[0] + $setupParts[1] + $setupParts[2] + $setupParts[3] + 4]="RS";}
		$positionMapping=array( "T"=>"Torwart","LV"=>"Abwehr","IV"=>"Abwehr","RV"=>"Abwehr","DM"=>"Mittelfeld","OM"=>"Mittelfeld","ZM"=>"Mittelfeld","LM"=>"Mittelfeld","RM"=>"Mittelfeld","LS"=>"Sturm","MS"=>"Sturm","RS"=>"Sturm");
		for($playerNo=1; $playerNo <= 11;++$playerNo){
			$mainPosition=Request("player".$playerNo."_pos");
			$position=$positionMapping[$mainPosition];
			$this->savePlayer($parameters["matchid"],$teamId,$parameters["player".$playerNo],$playerNo,$position,$mainPosition, FALSE);}
		for($playerNo=1; $playerNo <= 5;++$playerNo){
			if($parameters["bench".$playerNo])$this->savePlayer($parameters["matchid"],$teamId,$parameters["bench".$playerNo],$playerNo,"-","-",TRUE);}
		$fromTable=Config("db_prefix")."_youthmatch";
		$columns=[];
		for($subNo=1; $subNo <= 3;++$subNo){
			if(in_array($subNo,$validSubstitutions)){
				$columns[$teamPrefix."_s".$subNo."_out"]=$parameters["sub".$subNo ."_out"];
				$columns[$teamPrefix."_s".$subNo."_in"]=$parameters["sub".$subNo ."_in"];
				$columns[$teamPrefix."_s".$subNo."_minute"]=$parameters["sub".$subNo ."_minute"];
				$columns[$teamPrefix."_s".$subNo."_condition"]=$parameters["sub".$subNo ."_condition"];
				$columns[$teamPrefix."_s".$subNo."_position"]=Request("sub".$subNo."_position");}
			else{
				$columns[$teamPrefix."_s".$subNo."_out"]="";
				$columns[$teamPrefix."_s".$subNo."_in"]="";
				$columns[$teamPrefix."_s".$subNo."_minute"]="";
				$columns[$teamPrefix."_s".$subNo."_condition"]="";
				$columns[$teamPrefix."_s".$subNo."_position"]="";}}
		$this->_db->queryUpdate($columns,$fromTable,"id=%d",$parameters["matchid"]);}
	function savePlayer($matchId,$teamId,$playerId,$playerNumber,$position,$mainPosition,$onBench){
		$columns=array("match_id"=>$matchId,"team_id"=>$teamId,"player_id"=>$playerId,"playernumber"=>$playerNumber,"position"=>$position,"position_main"=>$mainPosition,"state"=>($onBench)? "Ersatzbank" : "1","strength"=> 0,"name"=>$playerId);
		$this->_db->queryInsert($columns,Config("db_prefix")."_youthmatch_player");}}
class ScoutYouthPlayerController extends Controller {
	function executeAction($parameters){
		if(!Config("youth_enabled")&&Config("youth_scouting_enabled"))return NULL;
		$user=$this->_websoccer->getUser();
		$clubId=$user->getClubId($this->_websoccer,$this->_db);
		if($clubId<1)throw new Exception(Message("error_action_required_team"));
		$lastExecutionTimestamp=YouthPlayersDataService::getLastScoutingExecutionTime($this->_websoccer,$this->_db,$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db));
		$nextPossibleExecutionTimestamp=$lastExecutionTimestamp +Config("youth_scouting_break_hours")* 3600;
		$now=Timestamp();
		if($now<$nextPossibleExecutionTimestamp)throw new Exception(Message("youthteam_scouting_err_breakviolation",Datetime($nextPossibleExecutionTimestamp)));
		$namesFolder=$_SERVER['DOCUMENT_ROOT'].'/module/admin/config/names'."/".$parameters["country"];
		if(!file_exists($namesFolder."/firstnames.txt")|| !file_exists($namesFolder."/lastnames.txt"))throw new Exception(Message("youthteam_scouting_err_invalidcountry"));
		$scout=YouthPlayersDataService::getScoutById($this->_websoccer,$this->_db,$this->_i18n,$parameters["scoutid"]);
		$team=TeamsDataService::getTeamSummaryById($this->_websoccer,$this->_db,$clubId);
		if($team["team_budget"] <= $scout["fee"])throw new Exception(Message("youthteam_scouting_err_notenoughbudget"));
		debitAmount($this->_websoccer,$this->_db,$clubId,$scout["fee"],"youthteam_scouting_fee_subject",$scout["name"]);
		$found=TRUE;
		$succesProbability=(int)Config("youth_scouting_success_probability");
		if(Config("youth_scouting_success_probability")< 100)$found=selectItemFromProbabilities(array(TRUE=>$succesProbability, FALSE=> 100 - $succesProbability));
		if($found)$this->createYouthPlayer($clubId,$scout,$parameters["country"]);
		else $this->_websoccer->addFrontMessage(new FrontMessage('warning',Message("youthteam_scouting_failure"),""));
		$this->_db->queryUpdate(array("scouting_last_execution"=>$now),Config("db_prefix")."_verein","id=%d",$clubId);
		return($found)? "youth-team" : "youth-scouting";}
	function createYouthPlayer($clubId,$scout,$country){
		$firstName=$this->getItemFromFile($_SERVER['DOCUMENT_ROOT'].'/module/admin/config/names'."/".$country."/firstnames.txt");
		$lastName=$this->getItemFromFile($_SERVER['DOCUMENT_ROOT'].'/module/admin/config/names'."/".$country."/lastnames.txt");
		$minStrength=(int)Config("youth_scouting_min_strength");
		$maxStrength=(int)Config("youth_scouting_max_strength");
		$scoutFactor=$scout["expertise"] / 100;
		$strength=$minStrength + round(($maxStrength - $minStrength)* $scoutFactor);
		$deviation=(int)Config("youth_scouting_standard_deviation");
		$strength=$strength+getMagicNumber(0 - $deviation,$deviation);
		$strength=max($minStrength, min($maxStrength,$strength));
		if($scout["speciality"]=="Torwart")$positionProbabilities=array("Torwart"=> 40,"Abwehr"=> 30,"Mittelfeld"=> 25,"Sturm"=> 5);
		elseif($scout["speciality"]=="Abwehr")$positionProbabilities=array("Torwart"=> 10,"Abwehr"=> 50,"Mittelfeld"=> 30,"Sturm"=> 10);
		elseif($scout["speciality"]=="Mittelfeld")$positionProbabilities=array("Torwart"=> 10,"Abwehr"=> 15,"Mittelfeld"=> 60,"Sturm"=> 15);
		elseif($scout["speciality"]=="Sturm")$positionProbabilities=array("Torwart"=> 5,"Abwehr"=> 15,"Mittelfeld"=> 40,"Sturm"=> 40);
		else $positionProbabilities=array("Torwart"=> 15,"Abwehr"=> 30,"Mittelfeld"=> 35,"Sturm"=> 20);
		$position=selectItemFromProbabilities($positionProbabilities);
		$minAge=Config("youth_scouting_min_age");
		$maxAge=Config("youth_min_age_professional");
		$age=$minAge+getMagicNumber(0, abs($maxAge - $minAge));
		$this->_db->queryInsert(array("team_id"=>$clubId,"firstname"=>$firstName,"lastname"=>$lastName,"age"=>$age,"position"=>$position,"nation"=>$country,"strength"=>$strength),Config("db_prefix")."_youthplayer");
		$event=new YouthPlayerScoutedEvent($this->_websoccer,$this->_db,$this->_i18n,$clubId,$scout["id"],$this->_db->getLastInsertedId());
		dispatchEvent($event);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("youthteam_scouting_success"),Message("youthteam_scouting_success_details",$firstName." ".$lastName)));}
	function getItemFromFile($fileName){
		$items=file($fileName, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$itemsCount=count($items);
		if(!$itemsCount)throw new Exception(Message("youthteam_scouting_err_invalidcountry"));
		return$items[mt_rand(0,$itemsCount - 1)];}}
class SelectCaptainController extends Controller {
	function executeAction($parameters){
		$user=$this->_websoccer->getUser();
		$clubId=$user->getClubId($this->_websoccer,$this->_db);
		$team=TeamsDataService::getTeamById($this->_websoccer,$this->_db,$clubId);
		$player=PlayersDataService::getPlayerById($this->_websoccer,$this->_db,$parameters["id"]);
		if($clubId!=$player["team_id"])throw new Exception("nice try");
		$this->_db->queryUpdate(array("captain_id"=>$parameters["id"]),Config("db_prefix")."_verein","id=%d",$clubId);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("myteam_player_select_as_captain_success"),""));
		if($team["captain_id"] &&$team["captain_id"]!=$parameters["id"]){
			$oldPlayer=PlayersDataService::getPlayerById($this->_websoccer,$this->_db,$team["captain_id"]);
			if($oldPlayer["team_id"]==$clubId){
				$newSatisfaction=round($oldPlayer["player_strength_satisfaction"]*0.6);
				$this->_db->queryUpdate(array("w_zufriedenheit"=>$newSatisfaction),Config("db_prefix")."_spieler","id=%d",$oldPlayer["player_id"]);
				$playername=(strlen($oldPlayer["player_pseudonym"]))? $oldPlayer["player_pseudonym"]:$oldPlayer["player_firstname"]." ".$oldPlayer["player_lastname"];
				$this->_websoccer->addFrontMessage(new FrontMessage('warning',Message("myteam_player_select_as_captain_warning_old_captain",$playername),""));}}
		return null;}}
class SelectTeamController extends Controller {
	function executeAction($parameters){
		$user=$this->_websoccer->getUser();
		$teamId=$parameters['id'];
		if($user->getClubId($this->_websoccer,$this->_db)==$teamId)return null;
		$fromTable=Config("db_prefix")."_verein";
		$whereCondition="id=%d AND user_id=%d AND status='1' AND nationalteam!='1'";
		$result=$this->_db->querySelect("id",$fromTable,$whereCondition,array($teamId,$user->id));
		$club=$result->fetch_array();
		if(!isset($club["id"]))throw new Exception("illegal club ID");
		$user->setClubId($teamId);
		return null;}}
class SellPlayerController extends Controller{
	function executeAction($parameters){
		if(!Config("transfermarket_enabled"))return NULL;
		$user=$this->_websoccer->getUser();
		$clubId=$user->getClubId($this->_websoccer,$this->_db);
		$player=PlayersDataService::getPlayerById($this->_websoccer,$this->_db,$parameters["id"]);
		if($clubId!=$player["team_id"])throw new Exception("nice try");
		if($player["player_transfermarket"])throw new Exception(Message("sell_player_already_on_list"));
		if($player["lending_fee"])throw new Exception(Message("lending_err_alreadyoffered"));
		$teamSize=TeamsDataService::getTeamSize($this->_websoccer,$this->_db,$clubId);
		if($teamSize <=Config("transfermarket_min_teamsize"))throw new Exception(Message("sell_player_teamsize_too_small",$teamSize));
		$minBidBoundary=round($player["player_marketvalue"] / 2);
		if($parameters["min_bid"]<$minBidBoundary)throw new Exception(Message("sell_player_min_bid_too_low"));
		$this->updatePlayer($player["player_id"],$parameters["min_bid"]);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("sell_player_success"),""));
		return "transfermarket";}
	function updatePlayer($playerId,$minBid){
		$now=Timestamp();
		$columns["transfermarkt"]=1;
		$columns["transfer_start"]=$now;
		$columns["transfer_ende"]=$now + 24*3600 *Config("transfermarket_duration_days");
		$columns["transfer_mindestgebot"]=$minBid;
		$fromTable=Config("db_prefix")."_spieler";
		$whereCondition="id=%d";
		$parameters=$playerId;
		$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$parameters);}}
class SellYouthPlayerController extends Controller {
	function executeAction($parameters){
		if(!Config("youth_enabled"))return NULL;
		$user=$this->_websoccer->getUser();
		$clubId=$user->getClubId($this->_websoccer,$this->_db);
		$player=YouthPlayersDataService::getYouthPlayerById($this->_websoccer,$this->_db,$this->_i18n,$parameters["id"]);
		if($clubId!=$player["team_id"])throw new Exception(Message("youthteam_err_notownplayer"));
		if($player["transfer_fee"])throw new Exception(Message("youthteam_sell_err_alreadyonmarket"));
		$this->updatePlayer($parameters["id"],$parameters["transfer_fee"]);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("youthteam_sell_success"),""));
		return "youth-team";}
	function updatePlayer($playerId,$transferFee){
		$columns=array("transfer_fee"=>$transferFee);
		$fromTable=Config("db_prefix")."_youthplayer";
		$whereCondition="id=%d";
		$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$playerId);}}
class SendMessageController extends Controller {
	function executeAction($parameters){
		$senderId=$this->_websoccer->getUser()->id;
		if(!Config("messages_enabled"))throw new Exception(Message("messages_err_messagesdisabled"));
		$recipientId=UsersDataService::getUserIdByNick($this->_websoccer,$this->_db,$parameters["nick"]);
		if($recipientId<1)throw new Exception(Message("messages_send_err_invalidrecipient"));
		if($senderId==$recipientId)throw new Exception(Message("messages_send_err_sendtoyourself"));
		$now=Timestamp();
		$lastMessage=MessagesDataService::getLastMessageOfUserId($this->_websoccer,$this->_db,$senderId);
		$timebreakBoundary=$now -Config("messages_break_minutes")* 60;
		if($lastMessage!=null &&$lastMessage["date"] >= $timebreakBoundary)throw new Exception(Message("messages_send_err_timebreak",Config("messages_break_minutes")));
		$columns["empfaenger_id"]=$recipientId;
		$columns["absender_id"]=$senderId;
		$columns["datum"]=$now;
		$columns["betreff"]=$parameters["subject"];
		$columns["nachricht"]=$parameters["msgcontent"];
		$fromTable=Config("db_prefix")."_briefe";
		$columns["typ"]="eingang";
		$this->_db->queryInsert($columns,$fromTable);
		$columns["typ"]="ausgang";
		$this->_db->queryInsert($columns,$fromTable);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("messages_send_success"),""));
		$_REQUEST["subject"]="";
		$_REQUEST["msgcontent"]="";
		$_REQUEST["nick"]="";
		return null;}}
class SendPasswordController extends Controller {
	function executeAction($parameters){
		if(!Config("login_allow_sendingpassword"))throw new Exception("Action is disabled.");
		if(Config("register_use_captcha")&&strlen(Config("register_captcha_publickey"))&&strlen(Config("register_captcha_privatekey"))){
			include_once($_SERVER['DOCUMENT_ROOT']."/lib/recaptcha/recaptchalib.php");
			$captchaResponse=recaptcha_check_answer(Config("register_captcha_privatekey"),$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
			if(!$captchaResponse->is_valid)throw new Exception(Message("registration_invalidcaptcha"));}
		$email=$parameters["useremail"];
		$fromTable=Config("db_prefix")."_user";
		$columns="id, passwort_salt, passwort_neu_angefordert";
		$wherePart="UPPER(email)='%s' AND status=1";
		$result=$this->_db->querySelect($columns,$fromTable,$wherePart, strtoupper($email));
		$userdata=$result->fetch_array();
		if(!isset($userdata["id"])){
			sleep(5);
			throw new Exception(Message("forgot-password_email-not-found"));}
		$now=Timestamp();
		$timeBoundary=$now - 24*3600;
		if($userdata["passwort_neu_angefordert"]>$timeBoundary)throw new Exception(Message("forgot-password_already-sent"));
		$salt=$userdata["passwort_salt"];
		if(!strlen($salt))$salt=generatePasswordSalt();
		$password=generatePassword();
		$hashedPassword=hashPassword($password,$salt);
		$columns=array("passwort_salt"=>$salt,"passwort_neu_angefordert"=>$now,"passwort_neu"=>$hashedPassword);
		$whereCondition="id=%d";
		$parameter=$userdata["id"];
		$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$parameter);
		$this->_sendEmail($email,$password);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("forgot-password_message_title"),Message("forgot-password_message_content")));
		return "login";}
	function _sendEmail($email,$password){
		$tplparameters["newpassword"]=$password;
		sendSystemEmailFromTemplate($this->_websoccer,$this->_i18n,$email,Message("sendpassword_email_subject"),"sendpassword",$tplparameters);}}
class SendShoutBoxMessageController extends Controller {
	function executeAction($parameters){
		$userId=$this->_websoccer->getUser()->id;
		$message=$parameters['msgtext'];
		$matchId=$parameters['id'];
		$date=Timestamp();
		$fromTable=Config('db_prefix').'_shoutmessage';
		$this->_db->queryInsert(array('user_id'=>$userId, 'message'=>$message, 'created_date'=>$date, 'match_id'=>$matchId),$fromTable);
		if(!isset($_SESSION['msgdeleted'])){
			$threshold=$date - 24*3600*14;
			$this->_db->queryDelete($fromTable,"created_date<%d",$threshold);
			$_SESSION['msgdeleted']=1;}
		return null;}}
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/SofortLib-PHP-Payment-2.0.1/core/sofortLibNotification.inc.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/SofortLib-PHP-Payment-2.0.1/core/sofortLibTransactionData.inc.php');
class SofortComPaymentNotificationController extends Controller {
	function executeAction($parameters){
		$configKey=trim(Config("sofortcom_configkey"));
		if(!strlen($configKey))throw new Exception("Sofort.com configuration key is not configured.");
		$userId=$parameters['u'];
		$result=$this->_db->querySelect("id",Config("db_prefix")."_user","id=%d",$userId);
		$user=$result->fetch_array();
		if(!$user)throw new Exception("illegal user id");
		$SofortLib_Notification=new SofortLibNotification();
		$TestNotification=$SofortLib_Notification->getNotification(file_get_contents('php://input'));
		$SofortLibTransactionData=new SofortLibTransactionData($configKey);
		$SofortLibTransactionData->addTransaction($TestNotification);
		$SofortLibTransactionData->sendRequest();
		if($SofortLibTransactionData->isError()){
			sendSystemEmail($this->_websoccer,Config("systememail"),"Failed Sofort.com payment notification","Error: ".$SofortLibTransactionData->getError());
			throw new Exception($SofortLibTransactionData->getError());}
		else{
			if($SofortLibTransactionData->getStatus()!= 'received'){
				sendSystemEmail($this->_websoccer,Config("systememail"),"Failed Sofort.com payment notification: invalid status","Status: ".$SofortLibTransactionData->getStatus());
				throw new Exception("illegal status");}
			$amount=$SofortLibTransactionData->getAmount();
			PremiumDataService::createPaymentAndCreditPremium($this->_websoccer,$this->_db,$userId,$amount,"sofortcom-notify");}
		return null;}}
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/SofortLib-PHP-Payment-2.0.1/payment/sofortLibSofortueberweisung.inc.php');
class SofortComRedirectController extends Controller {
	function executeAction($parameters){
		$configKey=trim(Config("sofortcom_configkey"));
		if(!strlen($configKey))throw new Exception("Sofort.com configuration key is not configured.");
		$amount=$parameters['amount'];
		$priceOptions=explode(',',Config('premium_price_options'));
		$validAmount=FALSE;
		if(count($priceOptions)){
			foreach($priceOptions as$priceOption){
				$optionParts=explode(':',$priceOption);
				$realMoney=trim($optionParts[0]);
				if($amount==$realMoney)$validAmount=TRUE;}}
		if(!$validAmount)throw new Exception("Invalid amount");
		$Sofortueberweisung=new Sofortueberweisung($configKey);
		$abortOrSuccessUrl=iUrl('premiumaccount', null, TRUE);
		$notifyUrl=aUrl('sofortcom-notify', 'u='.$this->_websoccer->getUser()->id, 'home', TRUE);
		$Sofortueberweisung->setAmount($amount);
		$Sofortueberweisung->setCurrencyCode(Config("premium_currency"));
		$Sofortueberweisung->setReason(Config("projectname"));
		$Sofortueberweisung->setSuccessUrl($abortOrSuccessUrl, true);
		$Sofortueberweisung->setAbortUrl($abortOrSuccessUrl);
		$Sofortueberweisung->setNotificationUrl($notifyUrl, 'received');
		$Sofortueberweisung->sendRequest();
		if($Sofortueberweisung->isError())throw new Exception($Sofortueberweisung->getError());
		else{
			$paymentUrl=$Sofortueberweisung->getPaymentUrl();
			header('Location: '.$paymentUrl);
			exit;}
		return null;}}
class TransferBidController extends Controller {
	function executeAction($parameters){
		if(!Config('transfermarket_enabled'))return;
		$user=$this->_websoccer->getUser();
		$clubId=$user->getClubId($this->_websoccer,$this->_db);
		$playerId=$parameters['id'];
		if($clubId<1)throw new Exception(Message('error_action_required_team'));
		$player=PlayersDataService::getPlayerById($this->_websoccer,$this->_db,$playerId);
		if($user->id==$player['team_user_id'])throw new Exception(Message('transfer_bid_on_own_player'));
		if(!$player['player_transfermarket'])throw new Exception(Message('transfer_bid_player_not_on_list'));
		$now=Timestamp();
		if($now>$player['transfer_end'])throw new Exception(Message('transfer_bid_auction_ended'));
		$minSalary=$player['player_contract_salary']*1.1;
		if($parameters['contract_salary']<$minSalary)throw new Exception(Message('transfer_bid_salary_too_less'));
		$minGoalBonus=$player['player_contract_goalbonus']*1.1;
		if($parameters['contract_goal_bonus']<$minGoalBonus)throw new Exception(Message('transfer_bid_goalbonus_too_less'));
		if($player['team_id']){
			$noOfTransactions=TransfermarketDataService::getTransactionsBetweenUsers($this->_websoccer,$this->_db,$player['team_user_id'],$user->id);
			$maxTransactions=Config('transfermarket_max_transactions_between_users');
			if($noOfTransactions >= $maxTransactions)throw new Exception(Message('transfer_bid_too_many_transactions_with_user',$noOfTransactions));}
		$highestBid=TransfermarketDataService::getHighestBidForPlayer($this->_websoccer,$this->_db,$parameters['id'],$player['transfer_start'],$player['transfer_end']);
		if($player['team_id']){
			$minBid=$player['transfer_min_bid'] - 1;
			if(isset($highestBid['amount']))$minBid=$highestBid['amount'];
			if($parameters['amount'] <= $minBid)throw new Exception(Message('transfer_bid_amount_must_be_higher',$minBid));}
		elseif(isset($highestBid['contract_matches'])){
			$ownBidValue=$parameters['handmoney'] + $parameters['contract_matches']*$parameters['contract_salary'];
			$opponentSalary=$highestBid['hand_money'] + $highestBid['contract_matches']*$highestBid['contract_salary'];
			if($player['player_position']=='midfield'||$player['player_position']=='striker'){
				$ownBidValue += 10*$parameters['contract_goal_bonus'];
				$opponentSalary += 10*$highestBid['contract_goalbonus'];}
			if($ownBidValue <= $opponentSalary)throw new Exception(Message('transfer_bid_contract_conditions_too_low'));}
		TeamsDataService::validateWhetherTeamHasEnoughBudgetForSalaryBid($this->_websoccer,$this->_db,$this->_i18n,$clubId,$parameters['contract_salary']);
		$team=TeamsDataService::getTeamSummaryById($this->_websoccer,$this->_db,$clubId);
		$result=$this->_db->querySelect('SUM(abloese)+ SUM(handgeld)AS bidsamount',Config('db_prefix').'_transfer_angebot', 'user_id=%d AND ishighest=\'1\'',$user->id);
		$bids=$result->fetch_array();
		if(isset($bids['bidsamount'])&&($parameters['handmoney'] + $parameters['amount'] + $bids['bidsamount'])>= $team['team_budget'])throw new Exception(Message('transfer_bid_budget_for_all_bids_too_less'));
		$this->saveBid($playerId,$user->id,$clubId,$parameters);
		if(isset($highestBid['bid_id']))$this->_db->queryUpdate(array('ishighest'=>'0'),Config('db_prefix').'_transfer_angebot', 'id=%d',$highestBid['bid_id']);
		if(isset($highestBid['user_id'])&&$highestBid['user_id']){
			$playerName=(strlen($player['player_pseudonym']))? $player['player_pseudonym']:$player['player_firstname'].' '.$player['player_lastname'];
			NotificationsDataService::createNotification($this->_websoccer,$this->_db,$highestBid['user_id'],'transfer_bid_notification_outbidden',array('player'=>$playerName), 'transfermarket', 'transfer-bid', 'id='.$playerId);}
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message('transfer_bid_success'), ''));
		return null;}
	function saveBid($playerId,$userId,$clubId,$parameters){
		$columns['spieler_id']=$playerId;
		$columns['user_id']=$userId;
		$columns['datum']=Timestamp();
		$columns['abloese']=$parameters['amount'];
		$columns['handgeld']=$parameters['handmoney'];
		$columns['vertrag_spiele']=$parameters['contract_matches'];
		$columns['vertrag_gehalt']=$parameters['contract_salary'];
		$columns['vertrag_torpraemie']=$parameters['contract_goal_bonus'];
		$columns['verein_id']=$clubId;
		$columns['ishighest']='1';
		$fromTable=Config('db_prefix').'_transfer_angebot';
		$this->_db->queryInsert($columns,$fromTable);}}
class UnmarkLendableController extends Controller {
	function executeAction($parameters){
		$user=$this->_websoccer->getUser();
		$clubId=$user->getClubId($this->_websoccer,$this->_db);
		$player=PlayersDataService::getPlayerById($this->_websoccer,$this->_db,$parameters["id"]);
		if($clubId!=$player["team_id"])throw new Exception(Message("lending_err_notownplayer"));
		if($player["lending_owner_id"])throw new Exception(Message("lending_err_borrowed_player"));
		$columns=array("lending_fee"=> 0);
		$fromTable=Config("db_prefix")."_spieler";
		$whereCondition="id=%d";
		$parameters=$parameters["id"];
		$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$parameters);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("lending_lendable_unmark_success"),""));
		return null;}}
class UnmarkUnsellableController extends Controller {
	function executeAction($parameters){
		$user=$this->_websoccer->getUser();
		$clubId=$user->getClubId($this->_websoccer,$this->_db);
		$player=PlayersDataService::getPlayerById($this->_websoccer,$this->_db,$parameters["id"]);
		if($clubId!=$player["team_id"])throw new Exception("nice try");
		$columns["unsellable"]=0;
		$fromTable=Config("db_prefix")."_spieler";
		$whereCondition="id=%d";
		$parameters=$parameters["id"];
		$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$parameters);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("myteam_remove_unsellable_player_success"),""));
		return null;}}
class UpgradeStadiumController extends Controller {
	function executeAction($parameters){
		$user=$this->_websoccer->getUser();
		$teamId=$user->getClubId($this->_websoccer,$this->_db);
		if($teamId<1)return null;
		$type=$parameters["type"];
		if(!in_array($type,array("pitch","videowall","seatsquality","vipquality")))throw new Exception("illegal parameter: type");
		$stadium=StadiumsDataService::getStadiumByTeamId($this->_websoccer,$this->_db,$teamId);
		if(!$stadium)return null;
		$existingLevel=$stadium["level_".$type];
		if($existingLevel >= 5)throw new Exception(Message("stadium_upgrade_err_not_upgradable"));
		$costs=StadiumsDataService::computeUpgradeCosts($this->_websoccer,$type,$stadium);
		$team=TeamsDataService::getTeamSummaryById($this->_websoccer,$this->_db,$teamId);
		if($team["team_budget"] <= $costs)throw new Exception(Message("stadium_extend_err_too_expensive"));
		debitAmount($this->_websoccer,$this->_db,$teamId,$costs,"stadium_upgrade_transaction_subject",$stadium["name"]);
		$maintenanceDue=(int)Config("stadium_maintenanceinterval_".$type);
		$this->_db->queryUpdate(array("level_".$type=>$existingLevel + 1,"maintenance_".$type=>$maintenanceDue),Config("db_prefix")."_stadion","id=%d",$stadium["stadium_id"]);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("stadium_upgrade_success"),Message("stadium_upgrade_success_details")));
		return "stadium";}}
class UploadClubPictureController extends Controller {
	function executeAction($parameters){
		if(!Config("upload_clublogo_max_size"))throw new Exception("feature is not enabled.");
		$clubId=$this->_websoccer->getUser()->getClubId();
		if(!$clubId)throw new Exception("requires team");
		if(!isset($_FILES["picture"]))throw new Exception(Message("change-profile-picture_err_notprovied"));
		$errorcode=$_FILES["picture"]["error"];
		if($errorcode==UPLOAD_ERR_FORM_SIZE)throw new Exception(Message("change-profile-picture_err_illegalfilesize"));
		$filename=$_FILES["picture"]["name"];
		$ext=strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		$allowedExtensions=explode(",",ALLOWED_PROFPIC_EXTENSIONS);
		if(!in_array($ext,$allowedExtensions))throw new Exception(Message("change-profile-picture_err_illegalfiletype"));
		$imagesize=getimagesize($_FILES["picture"]["tmp_name"]);
		if($imagesize===FALSE)throw new Exception(Message("change-profile-picture_err_illegalfiletype"));
		$type=substr($imagesize["mime"],strrpos($imagesize["mime"],"/")+ 1);
		if(!in_array($type,$allowedExtensions))throw new Exception(Message("change-profile-picture_err_illegalfiletype"));
		$maxFilesize=Config("upload_clublogo_max_size")* 1024;
		if($_POST["MAX_FILE_SIZE"]!=$maxFilesize||$_FILES["picture"]["size"]>$maxFilesize)throw new Exception(Message("change-profile-picture_err_illegalfilesize"));
		if($errorcode==UPLOAD_ERR_OK){
			$tmp_name=$_FILES["picture"]["tmp_name"];
			$name=hash('sha256',$clubId . time()).".".$ext;
			$uploaded=@move_uploaded_file($tmp_name, $_SERVER['DOCUMENT_ROOT'].'/uploads/club'."/".$name);
			if(!$uploaded)throw new Exception(Message("change-profile-picture_err_failed"));}
		else throw new Exception(Message("change-profile-picture_err_failed"));
		if($imagesize[0]!=120||$imagesize[1]!=120)$this->resizeImage($_SERVER['DOCUMENT_ROOT'].'/uploads/club'."/".$name, 120,$imagesize[0],$imagesize[1],$ext=="png");
		$fromTable=Config("db_prefix")."_verein";
		$whereCondition="id=%d";
		$result=$this->_db->querySelect("bild",$fromTable,$whereCondition,$clubId);
		$clubinfo=$result->fetch_array();
		if(strlen($clubinfo["bild"])&&file_exists($_SERVER['DOCUMENT_ROOT'].'/uploads/club'."/".$clubinfo["bild"]))unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/club'."/".$clubinfo["bild"]);
		$this->_db->queryUpdate(array("bild"=>$name),$fromTable,$whereCondition,$clubId);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("upload-clublogo_success"),""));
		return null;}
	function resizeImage($file,$width,$oldWidth,$oldHeight,$isPng){
		if(!$isPng)$src=imagecreatefromjpeg($file);
		else $src=imagecreatefrompng($file);
		$target=imagecreatetruecolor($width,$width);
		imagecopyresampled($target,$src, 0, 0, 0, 0,$width,$width,$oldWidth,$oldHeight);
		if(!$isPng)imagejpeg($target,$file);
		else imagepng($target,$file);}}
class UploadProfilePictureController extends Controller {
	function executeAction($parameters){
		if(!Config("user_picture_upload_enabled"))throw new Exception("feature is not enabled.");
		if(!isset($_FILES["picture"]))throw new Exception(Message("change-profile-picture_err_notprovied"));
		$errorcode=$_FILES["picture"]["error"];
		if($errorcode==UPLOAD_ERR_FORM_SIZE)throw new Exception(Message("change-profile-picture_err_illegalfilesize"));
		$filename=$_FILES["picture"]["name"];
		$ext=strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		$allowedExtensions=explode(",",ALLOWED_PROFPIC_EXTENSIONS);
		if(!in_array($ext,$allowedExtensions))throw new Exception(Message("change-profile-picture_err_illegalfiletype"));
		$imagesize=getimagesize($_FILES["picture"]["tmp_name"]);
		if($imagesize===FALSE)throw new Exception(Message("change-profile-picture_err_illegalfiletype"));
		$type=substr($imagesize["mime"],strrpos($imagesize["mime"],"/")+ 1);
		if(!in_array($type,$allowedExtensions))throw new Exception(Message("change-profile-picture_err_illegalfiletype"));
		$maxFilesize=Config("user_picture_upload_maxsize_kb")* 1024;
		if($_POST["MAX_FILE_SIZE"]!=$maxFilesize||$_FILES["picture"]["size"]>$maxFilesize)throw new Exception(Message("change-profile-picture_err_illegalfilesize"));
		$userId=$this->_websoccer->getUser()->id;
		if($errorcode==UPLOAD_ERR_OK){
			$tmp_name=$_FILES["picture"]["tmp_name"];
			$name=hash('sha256',$userId . time()).".".$ext;
			$uploaded=@move_uploaded_file($tmp_name, $_SERVER['DOCUMENT_ROOT'].'/uploads/'.'users'."/".$name);
			if(!$uploaded)throw new Exception(Message("change-profile-picture_err_failed"));}
		else throw new Exception(Message("change-profile-picture_err_failed"));
		if($imagesize[0]!=120||$imagesize[1]!=120)$this->resizeImage($_SERVER['DOCUMENT_ROOT'].'/uploads/'.'users'."/".$name, 120,$imagesize[0],$imagesize[1],$ext=="png");
		$fromTable=Config("db_prefix")."_user";
		$whereCondition="id=%d";
		$result=$this->_db->querySelect("picture",$fromTable,$whereCondition,$userId);
		$userinfo=$result->fetch_array();
		if(strlen($userinfo["picture"])&&file_exists($_SERVER['DOCUMENT_ROOT'].'/uploads/'.'users'."/".$userinfo["picture"]))unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/'.'users'."/".$userinfo["picture"]);
		$this->_db->queryUpdate(array("picture"=>$name),$fromTable,$whereCondition,$userId);
		$this->_websoccer->getUser()->setProfilePicture($this->_websoccer,$name);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("change-profile-picture_success"),""));
		return "user";}
	function resizeImage($file,$width,$oldWidth,$oldHeight,$isPng){
		if(!$isPng)$src=imagecreatefromjpeg($file);
		else $src=imagecreatefrompng($file);
		$target=imagecreatetruecolor($width,$width);
		imagecopyresampled($target,$src, 0, 0, 0, 0,$width,$width,$oldWidth,$oldHeight);
		if(!$isPng)imagejpeg($target,$file);
		else imagepng($target,$file);}}
class UserActivationController extends Controller {
	function executeAction($parameters){
		$key=$parameters["key"];
		$userid=$parameters["userid"];
		$fromTable=Config("db_prefix")."_user";
		$columns="id";
		$wherePart="schluessel='%s' AND id=%d AND status=2";
		$parameters=array($key,$userid);
		$result=$this->_db->querySelect($columns,$fromTable,$wherePart,$parameters);
		$userdata=$result->fetch_array();
		if(!isset($userdata["id"])){
			sleep(5);
			throw new Exception(Message("activate-user_user-not-found"));}
		$columns=array("status"=> 1);
		$whereCondition="id=%d";
		$parameter=$userdata["id"];
		$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$parameter);
		$this->_websoccer->addFrontMessage(new FrontMessage('success',Message("activate-user_message_title"),Message("activate-user_message_content")));
		return null;}}
class Converter {
	function __construct($i18n,$websoccer){
		$this->_i18n=$i18n;
		$this->_websoccer=$websoccer;}
	function toHtml($value){ return$this->toText($value);}
	function toText($value){ return$value;}
	function toDbValue($value){ return$this->toText($value);}}
class CupRoundsLinkConverter extends Converter {
	function toHtml($row){
		$output=' <a href=\'?site=managecuprounds&cup='.$row['id'].'\' title=\''.Message('manage_show_details').'\' >'.$row['entity_cup_rounds'].' <i class=\'icon-tasks\'></i></a>';
		return$output;}}
class AdminPasswordConverter extends Converter {
	function toDbValue($value){
		if(isset($_POST['id'])&&$_POST['id']){
			$db=DbConnection::getInstance();
			$columns='passwort, passwort_salt';
			$fromTable=Config('db_prefix').'_admin';
			$whereCondition='id=%d';
			$result=$db->querySelect($columns,$fromTable,$whereCondition,$_POST['id'],1);
			$admin=$result->fetch_array();
			if(strlen($value))$passwort=hashPassword($value,$admin['passwort_salt']);
			else $passwort=$admin['passwort'];}
		else $passwort=hashPassword($value, '');
		return$passwort;}}
class InactivityConverter extends Converter{
	function toHtml($row){
		if(!is_array($row))return (int)$value.'%';
		$rate=(int)$this->_format($row['entity_user_inactivity']);
		$color=$this->_color($rate);
		$output='<a href=\'#actPopup'.$row['id'].'\' role=\'button\'data-toggle=\'modal\' title=\''.Message('manage_show_details').'\' style=\'color: '.$color.'\'>'.$rate.' %</a>';
		$output .= $this->_renderInActivityPopup($row);
		return$output;}
	function _color($rate){
		if($rate <= 10)return 'green';
		elseif($rate <= 40)return 'black';
		elseif($rate <= 70)return 'orange';
		else return 'red';}
	function _renderInActivityPopup($row){
		$popup='';
		$popup.='<div id=\'actPopup'.$row['id'].'\' class=\'modal hide fade\' tabindex=\'-1\' role=\'dialog\' aria-labelledby=\'actPopupLabel\' aria-hidden=\'true\'>';
		$popup.='<div class=\'modal-header\'><button type=\'button\' class=\'close\'data-dismiss=\'modal\' aria-hidden=\'true\' title=\''.Message('button_close').'\'>&times;</button>';
		$popup.='<h3 id=\'actPopupLabel'.$row['id'].'\'>'.Message('entity_user_inactivity').': '. escapeOutput($row['entity_users_nick']).'</h3></div>';
		$popup.='<div class=\'modal-body\'>';
		$gesamt=$row['entity_user_inactivity_login'] + $row['entity_user_inactivity_aufstellung'] + $row['entity_user_inactivity_transfer']+ $row['entity_user_inactivity_vertragsauslauf'];
		$popup.='<table class=\'table table-bordered\'>
          <thead><tr>
            <th>'.Message('popup_user_inactivity_title_action').'</th>
            <th>'.Message('entity_user_inactivity').'</th>
          </tr></thead>
          <tbody><tr>
            <td><b>'.Message('entity_user_inactivity_login').'</b><br>
            <small>'.Message('entity_users_lastonline').': '. date('d.m.y, H:i',$row['entity_users_lastonline']).'</small></td>
            <td style=\'text-align: center; font-weight: bold; color: '.$this->_color($this->_format($row['entity_user_inactivity_login'])).'\'>'.$this->_format($row['entity_user_inactivity_login']).' %</td>
          </tr>
          <tr>
            <td><b>'.Message('entity_user_inactivity_aufstellung').'</b></td>
            <td style=\'text-align: center; font-weight: bold; color: '.$this->_color($this->_format($row['entity_user_inactivity_aufstellung'])).'\'>'.$this->_format($row['entity_user_inactivity_aufstellung']).' %</td>
          </tr>
          <tr>
            <td><b>'.Message('entity_user_inactivity_transfer').'</b><br>
            <small>'. sprintf(Message('entity_user_inactivity_transfer_check'), date('d.m.y, H:i',$row['entity_user_inactivity_transfer_check'])).'</small></td>
            <td style=\'text-align: center; font-weight: bold; color: '.$this->_color($this->_format($row['entity_user_inactivity_transfer'])).'\'>'.$this->_format($row['entity_user_inactivity_transfer']).' %</td>
          </tr>
          <tr>
            <td><b>'.Message('entity_user_inactivity_vertragsauslauf').'</b></td>
            <td style=\'text-align: center; font-weight: bold; color: '.$this->_color($this->_format($row['entity_user_inactivity_vertragsauslauf'])).'\'>'.$this->_format($row['entity_user_inactivity_vertragsauslauf']).' %</td>
          </tr></tbody>
          <tfoot>
          <tr>
            <td><b>'.Message('popup_user_inactivity_total').'</b></td>
            <td style=\'text-align: center; font-weight: bold; color: '.$this->_color($this->_format($gesamt)).'\'>'.$this->_format($gesamt).' %';
		if($gesamt>100)$popup.='<br/>('.$gesamt.'%)';
		$popup.='</td>
          </tr>
		</tfoot>
        </table>';
		$popup.='</div>';
		$popup.='<div class=\'modal-footer\'><button class=\'btn btn-primary\'data-dismiss=\'modal\' aria-hidden=\'true\'>'.Message('button_close').'</button></div>';
		$popup.='</div>';
		return$popup;}
	function _format($rate){
		$rate=($rate)? $rate:0;
		if($rate<0)$rate=0;
		elseif($rate>100)$rate=100;
		return$rate;}}
class MatchReportLinkConverter extends Converter {
	function toHtml($row){
		$output='<div class=\'btn-group\'>';
		$output.='<a class=\'btn btn-small dropdown-toggle\'data-toggle=\'dropdown\' href=\'#\'>';
		$output .= Message('entity_match_matchreportitems').'<span class=\'caret\'></span>';
		$output.='</a>';
		$output.='<ul class=\'dropdown-menu\'>';
		$output.='<li><a href=\'?site=manage-match-playerstatistics&match='.$row['id'].'\'><i class=\'icon-cog\'></i>'.Message('match_manage_playerstatistics').'</a></li>';
		$output.='<li><a href=\'?site=manage-match-reportitems&match='.$row['id'].'\'><i class=\'icon-th-list\'></i>'.Message('match_manage_reportitems').'</a></li>';
		if(!$row['entity_match_berechnet'])$output.='<li><a href=\'?site=manage-match-complete&match='.$row['id'].'\'><i class=\'icon-ok-sign\'></i>'.Message('match_manage_complete').'</a></li>';
		$output.='</ul>';
		$output.='</div>';
		return$output;}}
class MoneyTransactionConverter extends Converter {
	function toDbValue($value){
		$amount=(int)$value;
		if(isset($_POST['verein_id'])&&$_POST['verein_id']){
			$db=DbConnection::getInstance();
			$columns='finanz_budget';
			$fromTable=Config('db_prefix').'_verein';
			$whereCondition='id=%d';
			$result=$db->querySelect($columns,$fromTable,$whereCondition,$_POST['verein_id'],1);
			$team=$result->fetch_array();
			$budget=$team['finanz_budget'] + $amount;
			$updatecolumns=array('finanz_budget'=>$budget);
			$db->queryUpdate($updatecolumns,$fromTable,$whereCondition,$_POST['verein_id']);}
		return$amount;}}
class PaymentSenderMessageConverter extends Converter {
	function toHtml($row){
		include(sprintf($_SERVER['DOCUMENT_ROOT'].'/cache/messages_%s.inc.php',$this->_i18n->getCurrentLanguage()));
		if(isset($msg[$row['entity_transaction_absender']]))return$msg[$row['entity_transaction_absender']];
		return$row['entity_transaction_absender'];}}
class PaymentSubjectMessageConverter extends Converter {
	function toHtml($row){
		include(sprintf($_SERVER['DOCUMENT_ROOT'].'/cache/messages_%s.inc.php',$this->_i18n->getCurrentLanguage()));
		if(isset($msg[$row['entity_transaction_verwendung']]))return$msg[$row['entity_transaction_verwendung']];
		return$row['entity_transaction_verwendung'];}}
class PremiumTransactionConverter extends Converter {
	function toDbValue($value){
		$amount=(int)$value;
		if(isset($_POST['user_id'])&&$_POST['user_id']){
			$db=DbConnection::getInstance();
			$columns='premium_balance';
			$fromTable=Config('db_prefix').'_user';
			$whereCondition='id=%d';
			$result=$db->querySelect($columns,$fromTable,$whereCondition,$_POST['user_id'],1);
			$user=$result->fetch_array();
			$budget=$user['premium_balance'] + $amount;
			$updatecolumns=array('premium_balance'=>$budget);
			$db->queryUpdate($updatecolumns,$fromTable,$whereCondition,$_POST['user_id']);}
		return$amount;}}
class TransferOfferApprovalLinkConverter extends Converter {
	function toHtml($row){
		if($row['entity_transfer_offer_admin_approval_pending']){
			$output=' <a href=\'?site=manage&entity=transfer_offer&action=transferofferapprove&id='.$row['id'].'\' class=\'btn btn-small btn-success\'><i class=\'icon-ok icon-white\'></i>'.Message('button_approve').'</a>';}
		else $output='<i class=\'icon-ban-circle\'></i>';
		return$output;}}
class UserPasswordConverter extends Converter {
	function toDbValue($value){
		if(isset($_POST['id'])&&$_POST['id']){
			$db=DbConnection::getInstance();
			$columns='passwort, passwort_salt';
			$fromTable=Config('db_prefix').'_user';
			$whereCondition='id=%d';
			$result=$db->querySelect($columns,$fromTable,$whereCondition,$_POST['id'],1);
			$user=$result->fetch_array();
			if(strlen($value))$passwort=hashPassword($value,$user['passwort_salt']);
			else $passwort=$user['passwort'];}
		else $passwort=hashPassword($value, '');
		return$passwort;}}

abstract class AbstractEvent{
				function __construct($websoccer,$db,$i18n){$this->websoccer=$websoccer;$this->db=$db;$this->i18n=$i18n;}}
class MatchCompletedEvent extends AbstractEvent{
	  			function __construct($websoccer,$db,$i18n,SimulationMatch$match){parent::__construct($websoccer,$db,$i18n);$this->match=$match;}}
class PlayerTrainedEvent extends AbstractEvent{
				function __construct($websoccer,$db,$i18n,$playerId,$teamId,$trainerId,&$effectFreshness,&$effectTechnique,&$effectStamina,&$effectSatisfaction){parent::__construct($websoccer,$db,$i18n);$this->playerId=$playerId;$this->teamId=$teamId;
					     	$this->trainerId=$trainerId;$this->effectFreshness=&$effectFreshness;$this->effectTechnique=&$effectTechnique;$this->effectStamina=&$effectStamina;$this->effectSatisfaction=&$effectSatisfaction;}}
class SeasonOfTeamCompletedEvent extends AbstractEvent {
				function __construct($websoccer,$db,$i18n,$teamId,$seasonId,$rank){parent::__construct($websoccer,$db,$i18n);$this->teamId=$teamId;$this->seasonId=$seasonId;$this->rank=$rank;}}
class TicketsComputedEvent extends AbstractEvent{
				function __construct($websoccer,$db,$i18n,SimulationMatch$match,$stadiumId,&$rateStands,&$rateSeats,&$rateStandsGrand,&$rateSeatsGrand,&$rateVip){parent::__construct($websoccer,$db,$i18n);$this->match=$match;$this->stadiumId=$stadiumId;
							$this->rateStands=&$rateStands;$this->rateSeats=&$rateSeats;$this->rateStandsGrand=&$rateStandsGrand;$this->rateSeatsGrand=&$rateSeatsGrand;$this->rateVip=&$rateVip;}}
class UserRegisteredEvent extends AbstractEvent {
				function __construct($websoccer,$db,$i18n,$userid,$username,$email){parent::__construct($websoccer,$db,$i18n);$this->userId=$userid;$this->username=$username;$this->email=$email;}}
class YouthPlayerPlayedEvent extends AbstractEvent{
	  			function __construct($websoccer,$db,$i18n,SimulationPlayer$player,&$strengthChange){parent::__construct($websoccer,$db,$i18n);$this->player=$player;$this->strengthChange=&$strengthChange;}}
class YouthPlayerScoutedEvent extends AbstractEvent{
				function __construct($websoccer,$db,$i18n,$teamId,$scoutId,$playerId){parent::__construct($websoccer,$db,$i18n);$this->teamId=$teamId;$this->scoutId=$scoutId;$this->playerId=$playerId;}}

abstract class AbstractJob{protected$_websoccer,$_db,$_i18n;private$_id,$_interval;
				function __construct($websoccer,$db,I18n$i18n,$jobId,$errorOnAlreadyRunning=TRUE){$this->_websoccer=$websoccer;$this->_db=$db;$this->_i18n=$i18n;$this->_id=$jobId;$xmlConfig=$this->getXmlConfig();if($errorOnAlreadyRunning){
							$initTime=(int)$xmlConfig->attributes()->inittime;$now=Timestamp();$timeoutTime=$now-5*60;if($initTime>$timeoutTime)throw new Exception('Another instance of this job is already running.');
							$this->replaceAttribute('inittime',$now);}$interval=(int)$xmlConfig->attributes()->interval;$this->_interval=$interval*60;ignore_user_abort(TRUE);set_time_limit(0);gc_enable();}
				function __destruct(){$this->_ping(Timestamp());$this->replaceAttribute('inittime',0);}
				function start(){$this->replaceAttribute('stop','0');$this->replaceAttribute('error','');$this->_ping(0);do{$xmlConfig=$this->getXmlConfig();$stop=(int)$xmlConfig->attributes()->stop;if($stop===1)$this->stop();$now=Timestamp();
							$lastPing=(int)$xmlConfig->attributes()->last_ping;if($lastPing>0){$myOwnLastPing=$now-$this->_interval+5;if($lastPing>$myOwnLastPing)exit;}$this->_ping($now);try{$this->_db->close();$this->_db->connect(Config('db_host'),Config('db_user'),
							Config('db_passwort'),Config('db_name'));$this->execute();gc_collect_cycles();}catch(Exception$e){$this->replaceAttribute('error',$e->getMessage());$this->stop();}$this->_db->close();sleep($this->_interval);}while(true);}
				function stop(){$this->replaceAttribute('stop','1');exit;}
				function _ping($time){$this->replaceAttribute('last_ping',$time);}
				function getXmlConfig(){$xml=simplexml_load_file($_SERVER['DOCUMENT_ROOT'].'/admin/config/jobs.xml');$xmlConfig=$xml->xpath('//job[@id=\''.$this->_id.'\']');if(!$xmlConfig)throw new Exception('Job config not found.');return $xmlConfig[0];}
				function replaceAttribute($name,$value){$fp=fopen($_SERVER['DOCUMENT_ROOT'].'/admin/config/lockfile.txt','r');flock($fp,LOCK_EX);$xml=simplexml_load_file($_SERVER['DOCUMENT_ROOT'].'/admin/config/jobs.xml');
							if($xml===FALSE){$errorMessages='';$errors=libxml_get_errors();
							foreach($errors as$error)$errorMessages=$errorMessages.'\n'.$error;throw new Exception('Job with ID\''.$this->_id.'\': Could not update attribute \''.$name.'\'with value\''.$value.'\'.Errors: '.$errorMessages);}
							$xmlConfig=$xml->xpath('//job[@id=\''.$this->_id.'\']');$xmlConfig[0][$name]=$value;$successfulWritten=$xml->asXML($_SERVER['DOCUMENT_ROOT'].'/admin/config/jobs.xml');
							if(!$successfulWritten)throw new Exception('Job with ID\''.$this->_id.'\': Could not save updated attribute \''.$name.'\'with value\''.$value.'\'.');flock($fp,LOCK_UN);}
	  abstract function execute();}

class AcceptStadiumConstructionWorkJob extends AbstractJob {
	function execute(){
		$this->checkStadiumConstructions();
		$this->checkTrainingCamps();}
	function checkStadiumConstructions(){
		$constructions=StadiumsDataService::getDueConstructionOrders($this->_websoccer,$this->_db);
		$newDeadline=Timestamp()+Config('stadium_construction_delay')* 24*3600;
		foreach($constructions as$construction){
			$pStatus=[];
			$pStatus['completed']=$construction['builder_reliability'];
			$pStatus['notcompleted']=100 - $pStatus['completed'];
			$constructionResult=selectItemFromProbabilities($pStatus);
			if($constructionResult=='notcompleted'){
				$this->_db->queryUpdate(array('deadline'=>$newDeadline),Config('db_prefix').'_stadium_construction', 'id=%d',$construction['id']);
				if($construction['user_id'])NotificationsDataService::createNotification($this->_websoccer,$this->_db,$construction['user_id'],'stadium_construction_notification_delay', null, 'stadium_construction', 'stadium');}
			else{
				$stadium=StadiumsDataService::getStadiumByTeamId($this->_websoccer,$this->_db,$construction['team_id']);
				$columns=[];
				$columns['p_steh']=$stadium['places_stands'] + $construction['p_steh'];
				$columns['p_sitz']=$stadium['places_seats'] + $construction['p_sitz'];
				$columns['p_haupt_steh']=$stadium['places_stands_grand'] + $construction['p_haupt_steh'];
				$columns['p_haupt_sitz']=$stadium['places_seats_grand'] + $construction['p_haupt_sitz'];
				$columns['p_vip']=$stadium['places_vip'] + $construction['p_vip'];
				$this->_db->queryUpdate($columns,Config('db_prefix').'_stadion', 'id=%d',$stadium['stadium_id']);
				$this->_db->queryDelete(Config('db_prefix').'_stadium_construction', 'id=%d',$construction['id']);
				if($construction['user_id'])NotificationsDataService::createNotification($this->_websoccer,$this->_db,$construction['user_id'],'stadium_construction_notification_completed', null, 'stadium_construction', 'stadium');}}}
	function checkTrainingCamps(){
		$fromTable=Config('db_prefix').'_trainingslager_belegung AS B INNER JOIN '.Config('db_prefix').'_trainingslager AS C ON C.id=B.lager_id';
		$columns['B.id']='id';
		$columns['B.datum_start']='date_start';
		$columns['B.datum_ende']='date_end';
		$columns['B.verein_id']='team_id';
		$columns['C.name']='name';
		$columns['C.land']='country';
		$columns['C.preis_spieler_tag']='costs';
		$columns['C.p_staerke']='effect_strength';
		$columns['C.p_technik']='effect_strength_technique';
		$columns['C.p_kondition']='effect_strength_stamina';
		$columns['C.p_frische']='effect_strength_freshness';
		$columns['C.p_zufriedenheit']='effect_strength_satisfaction';
		$whereCondition='B.datum_ende<%d';
		$result=$this->_db->querySelect($columns,$fromTable,$whereCondition,Timestamp());
		while($booking=$result->fetch_array())TrainingcampsDataService::executeCamp($this->_websoccer,$this->_db,$booking['team_id'],$booking);}}
class AddPlayerWithoutTeamToTransfermarketJob extends AbstractJob {
	function execute(){ TransfermarketDataService::movePlayersWithoutTeamToTransfermarket($this->_websoccer,$this->_db);}}
class ExecuteTransfersJob extends AbstractJob {
	function execute(){ TransfermarketDataService::executeOpenTransfers($this->_websoccer,$this->_db);}}
class SimulateMatchesJob extends AbstractJob {
	function execute(){simulateOpenMatches($this->_websoccer,$this->_db);}}
class UpdateStatisticsJob extends AbstractJob {
	function execute(){
		$pointsWin=3;
		$statisticTable=Config('db_prefix').'_team_league_statistics';
		$clubTable=Config('db_prefix').'_verein';
		$matchTable=Config('db_prefix').'_spiel';
		$query="REPLACE INTO $statisticTable
SELECT team_id,
	season_id,
	(home_wins*$pointsWin + home_draws + guest_wins*$pointsWin + guest_draws)AS total_points,
	(home_goals + guest_goals)AS total_goals,
	(home_goalsreceived + guest_goalsreceived)AS total_goalsreceived,
	(home_goals + guest_goals - home_goalsreceived - guest_goalsreceived)AS total_goalsdiff,
	(home_wins + guest_wins)AS total_wins,
	(home_draws + guest_draws)AS total_draws,
	(home_losses + guest_losses)AS total_losses,
	(home_wins*$pointsWin + home_draws)AS home_points,
	home_goals,
	home_goalsreceived,
	(home_goals - home_goalsreceived)AS home_goalsdiff,
	home_wins,
	home_draws,
	home_losses,
	(guest_wins*$pointsWin + guest_draws)AS guest_points,
	guest_goals,
	guest_goalsreceived,
	(guest_goals - guest_goalsreceived)AS guest_goalsdiff,
	guest_wins,
	guest_draws,
	guest_losses
FROM (SELECT C.id AS team_id, M.saison_id AS season_id,
		SUM(CASE WHEN M.home_verein=C.id AND M.home_tore>M.gast_tore THEN 1 ELSE 0 END)AS home_wins,
		SUM(CASE WHEN M.home_verein=C.id AND M.home_tore<M.gast_tore THEN 1 ELSE 0 END)AS home_losses,
		SUM(CASE WHEN M.home_verein=C.id AND M.home_tore=M.gast_tore THEN 1 ELSE 0 END)AS home_draws,
		SUM(CASE WHEN M.home_verein=C.id THEN M.home_tore ELSE 0 END)AS home_goals,
		SUM(CASE WHEN M.home_verein=C.id THEN M.gast_tore ELSE 0 END)AS home_goalsreceived,
		SUM(CASE WHEN M.gast_verein=C.id AND M.gast_tore>M.home_tore THEN 1 ELSE 0 END)AS guest_wins,
		SUM(CASE WHEN M.gast_verein=C.id AND M.gast_tore<M.home_tore THEN 1 ELSE 0 END)AS guest_losses,
		SUM(CASE WHEN M.gast_verein=C.id AND M.home_tore=M.gast_tore THEN 1 ELSE 0 END)AS guest_draws,
		SUM(CASE WHEN M.gast_verein=C.id THEN M.gast_tore ELSE 0 END)AS guest_goals,
		SUM(CASE WHEN M.gast_verein=C.id THEN M.home_tore ELSE 0 END)AS guest_goalsreceived
	FROM $clubTable AS C
	INNER JOIN $matchTable AS M ON M.home_verein=C.id OR M.gast_verein=C.id
	WHERE M.saison_id>0 AND M.berechnet='1'
	GROUP BY C.id, M.saison_id)AS matches";
		$this->_db->executeQuery($query);
		$strengthQuery=' UPDATE '.Config('db_prefix').'_verein c INNER JOIN (';
		$strengthQuery.=' SELECT verein_id, AVG( w_staerke)AS strength_avg';
		$strengthQuery.=' FROM '.Config('db_prefix').'_spieler';
		$strengthQuery.=' GROUP BY verein_id';
		$strengthQuery.=')AS r ON r.verein_id=c.id';
		$strengthQuery.=' SET c.strength=r.strength_avg';
		$this->_db->executeQuery($strengthQuery);}}
class UserInactivityCheckJob extends AbstractJob {
	function execute(){
		$users=UsersDataService::getActiveUsersWithHighscore($this->_websoccer,$this->_db, 0, 1000);
		foreach($users as$user)UserInactivityDataService::computeUserInactivity($this->_websoccer,$this->_db,$user['id']);}}
class Method { function __construct($websoccer,$db){ $this->_websoccer=$websoccer; $this->_db=$db;}}
class DefaultUserLoginMethod extends Method{
	function authenticateWithEmail($email,$password){ return$this->authenticate('UPPER(email)', strtoupper($email),$password);}
	function authenticateWithUsername($nick,$password){ return$this->authenticate('nick',$nick,$password);}
	function authenticate($column,$loginstr,$password){
		$fromTable=Config('db_prefix').'_user';
		$columns='id, passwort, passwort_neu, passwort_salt';
		$wherePart=$column.'=\'%s\' AND status=1';
		$parameter=$loginstr;
		$result=$this->_db->querySelect($columns,$fromTable,$wherePart,$parameter);
		$userdata=$result->fetch_array();
		if(!$userdata['id'])return FALSE;
		$inputPassword=hashPassword($password,$userdata['passwort_salt']);
		if($inputPassword!=$userdata['passwort'] &&$inputPassword!=$userdata['passwort_neu'])return FALSE;
		if($userdata['passwort_neu']==$inputPassword){
			$columns=array('passwort'=>$inputPassword, 'passwort_neu_angefordert'=>0, 'passwort_neu'=>'');
			$whereCondition='id=%d';
			$parameter=$userdata['id'];
			$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$parameter);}
		return$userdata['id'];}}
class DemoUserLoginMethod extends Method{
	function authenticateWithEmail($email,$password){
		$mysqli=new mysqli(Config('db_host'),Config('db_user'),Config('db_passwort'),Config('db_name'));
		$escapedEMail=$mysqli->real_escape_string($email);
		$dbresult=$mysqli->query('SELECT password FROM mydummy_table WHERE email=\''.$escapedEMail.'\'');
		if(!$dbresult)throw new Exception('Database Query Error: '.$mysqli->error);
		$myUser=$dbresult->fetch_array();
		$mysqli->close();
		if(!$myUser)return FALSE;
		if($myUser['password']!=hash('sha256',$password))return FALSE;
		$existingUserId=UsersDataService::getUserIdByEmail($this->_websoccer,$this->_db, strtolower($email));
		if($existingUserId)return$existingUserId;
		return UsersDataService::createLocalUser($this->_websoccer,$this->_db, null,$email);}
	function authenticateWithUsername($nick,$password){ throw new Exception('Unsupported action.');}}
class EmailValidator{private$_i18n,$_websoccer,$_value;
	  		function __construct($i18n,$websoccer,$value){$this->_i18n=$i18n;$this->_websoccer=$websoccer;$this->_value=$value;}
			function isValid(){return filter_var($this->_value,FILTER_VALIDATE_EMAIL);}
			function getMessage(){return Message('validation_error_email');}}
class PasswordValidator{private$_i18n,$_websoccer,$_value;
			function __construct($i18n,$websoccer,$value){$this->_i18n=$i18n;$this->_websoccer=$websoccer;$this->_value=$value;}
			function isValid(){if(!preg_match('/[A-Za-z]/',$this->_value)||!preg_match('/[0-9]/',$this->_value)){return FALSE;}$blacklist=['test123','abc123','passw0rd','passw0rt'];if(in_array(strtolower($this->_value),$blacklist))return FALSE;return TRUE;}
			function getMessage(){return tMessage('validation_error_password');}}
class UniqueCupNameValidator{
			function isValid(){$this->_value=$value;$db=DbConnection::getInstance();$result=$db->querySelect('id',Config('db_prefix').'_cup', 'name=\'%s\'',$this->_value, 1);$cups=$result->fetch_array();if(isset($cups['id'])&&(!isset($_POST['id'])||$_POST['id']!==
							$cups['id']))return FALSE;$result=$db->querySelect('COUNT(*)AS hits',Config('db_prefix').'_spiel','pokalname=\'%s\'',$this->_value);$matches=$result->fetch_array();if($matches['hits']&&!isset($_POST['id']))return FALSE;return TRUE;}
			function getMessage(){returnMessage('validation_error_uniquecupname');}}
class Model{
			function __construct($db,$i18n,$websoccer){$this->_websoccer=$websoccer;$this->_db=$db;$this->_i18n=$i18n;}
			function Template(){return[];}
			function View(){return TRUE;}
			function getTemplateParameters(){return[];}
			function renderView(){return TRUE;}}
class AbsenceAlertModel extends Model{
			function Template(){return['absence'=>$this->_absence];}
			function View(){$this->_absence=getCurrentAbsenceOfUser($this->_websoccer,$this->_db,$this->_websoccer->getUser()->id);return($this->_absence!=NULL);}}
class AbsenceModel extends Model{
			function  Template(){$absence=getCurrentAbsenceOfUser($this->_websoccer,$this->_db,$this->_websoccer->getUser()->id);$deputyName='';if($absence&&$absence['deputy_id']){$result=$this->_db->querySelect('nick',Config('db_prefix').
							'_user','id=%d',$absence['deputy_id']);$deputy=$result->fetch_array();$deputyName=$deputy['nick'];}return['currentAbsence'=>$absence,'deputyName'=>$deputyName];}}
class AdsModel extends Model{
			function View(){return($this->_websoccer->getUser()->premiumBalance==0||!Config('frontend_ads_hide_for_premiumusers'));}}
class AlltimeTableModel extends Model{
			function __construct($db,$i18n,$websoccer){$this->_db=$db;$this->_i18n=$i18n;$this->_websoccer=$websoccer;$this->_leagueId=(int)Request('id');$this->_type=Request('type');$clubId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);
							if($this->_leagueId==0&&$clubId>0){$result=$db->querySelect('liga_id',Config('db_prefix').'_verein','id=%d',$clubId,1);$club=$result->fetch_array();$result->free();$this->_leagueId=$club['liga_id'];}}
			function Template(){$teams=TeamsDataService::getTeamsOfLeagueOrderedByAlltimeTableCriteria($this->_websoccer,$this->_db,$this->_leagueId,$this->_type);return['leagueId'=>$this->_leagueId,'teams'=>$teams];}
			function View(){return($this->_leagueId>0);}}
class AllUserActivitiesModel extends Model{
			function Template(){return['activities'=>getLatestActionLogs($this->_websoccer,$this->_db,5)];}}
class BadgesModel extends Model{
			function Template(){$result=$this->_db->querySelect('*',Config('db_prefix').'_badge','1 ORDER BY event ASC,level ASC');$badges=[];while($badge=$result->fetch_array())$badges[]=$badge;return['badges'=>$badges];}}
class ClubSearchModel extends Model{
			function Template(){$query=Request('query');$teams=TeamsDataService::findTeamNames($this->_websoccer,$this->_db,$query);return['items'=>$teams];}}
class CupGroupDetailsModel extends Model{
			function Template(){$cupRoundId=Request('roundid');$cupGroup=Request('group');$result=$this->_db->querySelect('C.name AS cup_name,R.name AS round_name',Config('db_prefix').'_cup_round AS R INNER JOIN '.Config('db_prefix').'_cup AS C ON C.id=R.cup_id',
							'R.id=%d',$cupRoundId);$round=$result->fetch_array();$matches=MatchesDataService::getMatchesByCupRoundAndGroup($this->_websoccer,$this->_db,$round['cup_name'],$round['round_name'],$cupGroup);return['matches'=>$matches,
							'groupteams'=>getTeamsOfCupGroupInRankingOrder($this->_websoccer,$this->_db,$cupRoundId,$cupGroup)];}}
class CupResultsModel extends Model{
			function Template(){$cupName=Request('cup');$cupRound=Request('round');$result=$this->_db->querySelect(['C.logo'=>'cup_logo','R.id'=>'round_id','R.firstround_date'=>'firstround_date','R.secondround_date'=>'secondround_date','R.finalround'=>'is_finalround',
							'R.groupmatches'=>'is_groupround','PREVWINNERS.name'=>'prev_round_winners','PREVLOOSERS.name'=>'prev_round_loosers'],Config('db_prefix').'_cup_round AS R INNER JOIN '.Config('db_prefix').'_cup AS C ON C.id=R.cup_id LEFT JOIN '.
							Config('db_prefix').'_cup_round AS PREVWINNERS ON PREVWINNERS.id=R.from_winners_round_id LEFT JOIN '.Config('db_prefix').'_cup_round AS PREVLOOSERS ON PREVLOOSERS.id=R.from_loosers_round_id','C.name=\'%s\' AND R.name=\'%s\'',
							[$cupName,$cupRound],1);$round=$result->fetch_array();$groups=[];$preSelectedGroup='';if($round['is_groupround']){$userTeamId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);$result=$this->_db->querySelect('name,team_id',
							Config('db_prefix').'_cup_round_group','cup_round_id=%d ORDER BY name ASC',[$round['round_id']]);while($group=$result->fetch_array()){if(!isset($groups[$group['name']]))$groups[$group['name']]=$group['name'];if($group['team_id']==$userTeamId)
	  						$preSelectedGroup=$group['name'];}$matches=[];}else$matches=MatchesDataService::getMatchesByCupRound($this->_websoccer,$this->_db,$cupName,$cupRound);return['matches'=>$matches,'round'=>$round,'groups'=>$groups,
	  						'preSelectedGroup'=>$preSelectedGroup];}}
class CupsListModel extends Model{
			function Template(){return['cups'=>MatchesDataService::getCupRoundsByCupname($this->_websoccer,$this->_db)];}}
class DirectTransferOfferModel extends Model{
			function Template(){$players=[];if(Request('loadformdata'))$players=PlayersDataService::getPlayersOfTeamByPosition($this->_websoccer,$this->_db,$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db));return['players'=>$players,
							'player'=>$this->_player];}
			function View(){$playerId=(int)Request('id');if($playerId<1)throw new Exception(Message('error_page_not_found'));$this->_player=PlayersDataService::getPlayerById($this->_websoccer,$this->_db,$playerId);if(!Config('transferoffers_enabled'))return FALSE;
							return(!$this->_player['player_unsellable']&&$this->_player['team_user_id']>0&&$this->_player['team_user_id']!==$this->_websoccer->getUser()->id&&!$this->_player['player_transfermarket']&&$this->_player['lending_owner_id']==0);}}
class FacebookLoginModel extends Model{
			function Template(){return['loginurl'=>FacebookSdk::getInstance($this->_websoccer)->getLoginUrl()];}
			function View(){return Config('facebook_enable_login');}}
class FinancesModel extends Model{
			function Template(){$teamId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);if($teamId<1)throw new Exception(Message('feature_requires_team'));$team=TeamsDataService::getTeamSummaryById($this->_websoccer,$this->_db,$teamId);
							$count=countAccountStatementsOfTeam($this->_websoccer,$this->_db,$teamId);$paginator=new Paginator($count,Config('entries_per_page'),$this->_websoccer);if($count)$statements=
							getAccountStatementsOfTeam($this->_websoccer,$this->_db,$teamId,$paginator->getFirstIndex(),Config('entries_per_page'));else$statements=[];return['budget'=>$team['team_budget'],'statements'=>$statements,'paginator'=>$paginator];}}
class FinancesSummaryModel extends Model{
			function Template(){$minDate=Timestamp()-365*3600*24;$columns=['verwendung'=>'subject','SUM(betrag)'=>'balance','AVG(betrag)'=>'avgAmount'];$result=$this->_db->querySelect($columns,Config('db_prefix').'_konto',
							'verein_id=%d AND datum>%d GROUP BY verwendung HAVING COUNT(*)>5',[$this->_teamId,$minDate]);$majorPositions=[];while($position=$result->fetch_array())$majorPositions[]=$position;return['majorPositions'=>$majorPositions];}
			function View(){$this->_teamId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);return($this->_teamId);}}
class FindNationalPlayersModel extends Model{
			function Template(){$teamId=NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer,$this->_db);if(!$teamId)throw new Exception(Message('nationalteams_user_requires_team'));$result=$this->_db->querySelect('name',Config('db_prefix').
							'_verein','id=%d',$teamId);$team=$result->fetch_array();$firstName=Request('fname');$lastName=Request('lname');$position=Request('position');$mainPosition=Request('position_main');$playersCount=NationalteamsDataService::findPlayersCount(
							$this->_websoccer,$this->_db,$team['name'],$teamId,$firstName,$lastName,$position,$mainPosition);$paginator=new Paginator($playersCount,Config('entries_per_page'),$this->_websoccer);$params=['fname'=>$firstName,'lname'=>$lastName,
							'position'=>$position,'position_main'=>$mainPosition,'search'=>'true'];foreach($params as$key=>$value){$paginator->addParameter($key,$value);}if($playersCount)$players=NationalteamsDataService::findPlayers($this->_websoccer,$this->_db,
							$team['name'],$teamId,$firstName,$lastName,$position,$mainPosition,$paginator->getFirstIndex(),Config('entries_per_page'));else$players=[];return['team_name'=>$team['name'],'playersCount'=>$playersCount,'players'=>$players,
							'paginator'=>$paginator];}
			function View(){return Config('nationalteams_enabled');}}
class ForgotPasswordModel extends Model{
			function Template(){$parameters=[];if(Config('register_use_captcha')&&strlen(Config('register_captcha_publickey'))&&strlen(Config('register_captcha_privatekey'))){include_once($_SERVER['DOCUMENT_ROOT'].'/lib/recaptcha/recaptchalib.php');
							$useSsl=(!empty($_SERVER['HTTPS']));$captchaCode=recaptcha_get_html(Config('register_captcha_publickey'),null,$useSsl);$parameters['captchaCode']=$captchaCode;}return$parameters;}
			function View(){return Config('login_allow_sendingpassword');}}
class FormationModel extends Model{
			function Template(){if($this->_nationalteam)$clubId=NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer,$this->_db);else$clubId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);
							$nextMatches=MatchesDataService::getNextMatches($this->_websoccer,$this->_db, $clubId,Config('formation_max_next_matches'));
		if(!count($nextMatches))throw new Exception(Message('next_match_block_no_nextmatch'));
		$matchId=Request('id');
		if (!$matchId) $matchinfo = $nextMatches[0];
		else {
			foreach ($nextMatches as $nextMatch) {
				if ($nextMatch['match_id'] == $matchId) {
					$matchinfo = $nextMatch;
					break;}}}
		if (!isset($matchinfo))throw new Exception('illegal match id');
		$players = null;
		if ($clubId > 0) {
			if ($this->_nationalteam) $players = NationalteamsDataService::getNationalPlayersOfTeamByPosition($this->_websoccer, $this->_db, $clubId);
			else $players = PlayersDataService::getPlayersOfTeamByPosition($this->_websoccer, $this->_db, $clubId, 'DESC', count($matchinfo) &&$matchinfo['match_type'] == 'cup',(isset($matchinfo['match_type']) &&$matchinfo['match_type'] != 'friendly'));}
		if (Request('templateid'))$formation = FormationDataService::getFormationByTemplateId($this->_websoccer, $this->_db, $clubId,Request('templateid'));
		else $formation = FormationDataService::getFormationByTeamId($this->_websoccer, $this->_db, $clubId, $matchinfo['match_id']);
		for ($benchNo = 1; $benchNo <= 5;++$benchNo) {
			if(Request('bench'.$benchNo))$formation['bench'.$benchNo]=Request('bench'.$benchNo);
			elseif (!isset($formation['bench'.$benchNo]))$formation['bench'.$benchNo]='';}
		for ($subNo = 1; $subNo <= 3;++$subNo) {
			if(Request('sub'.$subNo.'_out')) {
				$formation['sub'.$subNo.'_out']=Request('sub'.$subNo.'_out');
				$formation['sub'.$subNo.'_in']=Request('sub'.$subNo.'_in');
				$formation['sub'.$subNo.'_minute']=Request('sub'.$subNo.'_minute');
				$formation['sub'.$subNo.'_condition']=Request('sub'.$subNo.'_condition');
				$formation['sub'.$subNo.'_position']=Request('sub'.$subNo.'_position');}
			elseif (!isset($formation['sub'.$subNo.'_out'])) {
				$formation['sub'.$subNo.'_out']='';
				$formation['sub'.$subNo.'_in']='';
				$formation['sub'.$subNo.'_minute']='';
				$formation['sub'.$subNo.'_condition']='';
				$formation['sub'.$subNo.'_position']='';}}
		$setup = $this->getFormationSetup($formation);
		$criteria=Request('preselect');
		if ($criteria !== NULL) {
			if ($criteria == 'strongest')$sortColumn = 'w_staerke';
			elseif ($criteria == 'freshest')$sortColumn = 'w_frische';
			else $sortColumn = 'w_zufriedenheit';

			$proposedPlayers = FormationDataService::getFormationProposalForTeamId($this->_websoccer, $this->_db, $clubId,$setup['defense'], $setup['dm'], $setup['midfield'], $setup['om'], $setup['striker'], $setup['outsideforward'], $sortColumn, 'DESC',
					$this->_nationalteam, (isset($matchinfo['match_type']) &&$matchinfo['match_type'] == 'cup'));
			for ($playerNo = 1; $playerNo <= 11;++$playerNo) {
				$playerIndex = $playerNo - 1;
				if (isset($proposedPlayers[$playerIndex])) {
					$formation['player'.$playerNo] = $proposedPlayers[$playerIndex]['id'];
					$formation['player'.$playerNo . '_pos']=$proposedPlayers[$playerIndex]['position'];}}
			for ($benchNo = 1; $benchNo <= 5;++$benchNo)$formation['bench'.$benchNo]='';
			for ($subNo = 1; $subNo <= 3;++$subNo) {
				$formation['sub'.$subNo.'_out']='';
				$formation['sub'.$subNo.'_in']='';
				$formation['sub'.$subNo.'_minute']='';
				$formation['sub'.$subNo.'_condition']='';
				$formation['sub'.$subNo.'_position']='';}}
		if(Request('freekickplayer')) $formation['freekickplayer']=Request('freekickplayer');
		elseif (!isset($formation['freekickplayer']))$formation['freekickplayer']='';
		if(Request('offensive'))$formation['offensive']=Request('offensive');
		elseif (!isset($formation['offensive']))$formation['offensive']=40;
		if(Request('longpasses'))$formation['longpasses']=Request('longpasses');
		if(Request('counterattacks'))$formation['counterattacks']=Request('counterattacks');
		for ($playerNo = 1; $playerNo <= 11;++$playerNo) {
			if(Request('player'.$playerNo)) {
				$formation['player'.$playerNo]=Request('player'.$playerNo);
				$formation['player'.$playerNo . '_pos']=Request('player'.$playerNo . '_pos');}
			elseif (!isset($formation['player'.$playerNo])) {
				$formation['player'.$playerNo]='';
				$formation['player'.$playerNo . '_pos']='';}}
		return array('nextMatches' => $nextMatches,
				'next_match' => $matchinfo,
				'previous_matches' => MatchesDataService::getPreviousMatches($matchinfo, $this->_websoccer, $this->_db),
				'players' => $players,
				'formation' => $formation,
				'setup' => $setup,
				'captain_id' => TeamsDataService::getTeamCaptainIdOfTeam($this->_websoccer, $this->_db, $clubId));}
	function getFormationSetup($formation){
		$setup=array('defense'=>4, 'dm'=>1, 'midfield'=>3, 'om'=>1, 'striker'=>1, 'outsideforward'=>0);
		if(Request('formation_defense')!==NULL){
			$setup['defense']=(int)Request('formation_defense');
			$setup['dm']=(int)Request('formation_defensemidfield');
			$setup['midfield']=(int)Request('formation_midfield');
			$setup['om']=(int)Request('formation_offensivemidfield');
			$setup['striker']=(int)Request('formation_forward');
			$setup['outsideforward']=(int)Request('formation_outsideforward');}
		elseif(Request('setup')!==NULL){
			$setupParts=explode('-',Request('setup'));
			$setup['defense']=(int)$setupParts[0];
			$setup['dm']=(int)$setupParts[1];
			$setup['midfield']=(int)$setupParts[2];
			$setup['om']=(int)$setupParts[3];
			$setup['striker']=(int)$setupParts[4];
			$setup['outsideforward']=(int)$setupParts[5];}
		elseif(isset($formation['setup'])&&strlen($formation['setup'])){
			$setupParts=explode('-',$formation['setup']);
			$setup['defense']=(int)$setupParts[0];
			$setup['dm']=(int)$setupParts[1];
			$setup['midfield']=(int)$setupParts[2];
			$setup['om']=(int)$setupParts[3];
			$setup['striker']=(int)$setupParts[4];
			if(count($setupParts)>5)$setup['outsideforward']=(int)$setupParts[5];
			else $setup['outsideforward']=0;}
		$altered=FALSE;
		while(($noOfPlayers=$setup['defense'] + $setup['dm'] + $setup['midfield'] + $setup['om'] + $setup['striker'] + $setup['outsideforward'])!= 10){
			if($noOfPlayers>10){
				if($setup['striker']>1)$setup['striker']=$setup['striker'] - 1;
				elseif($setup['outsideforward']>1)$setup['outsideforward']=0;
				elseif($setup['om']>1)$setup['om']=$setup['om'] - 1;
				elseif($setup['dm']>1)$setup['dm']=$setup['dm'] - 1;
				elseif($setup['midfield']>2)$setup['midfield']=$setup['midfield'] - 1;
				else $setup['defense']=$setup['defense'] - 1;}
			else{
				if($setup['defense']<4)$setup['defense']=$setup['defense'] + 1;
				elseif($setup['midfield']<4)$setup['midfield']=$setup['midfield'] + 1;
				elseif($setup['dm']<2)$setup['dm']=$setup['dm'] + 1;
				elseif($setup['om']<2)$setup['om']=$setup['om'] + 1;
				else $setup['striker']=$setup['striker'] + 1;}
			$altered=TRUE;}
		if($altered)$this->_websoccer->addFrontMessage(new FrontMessage('warning',Message('formation_setup_altered_warn_title'),Message('formation_setup_altered_warn_details')));
		return$setup;}
	function View(){
		$this->_nationalteam=(Request('nationalteam'))? TRUE : FALSE;
		return TRUE;}}
class FormationTemplatesModel extends Model {
	function Template(){
		$templates=[];
		$result=$this->_db->querySelect('id, datum AS date, templatename',Config('db_prefix').'_aufstellung', 'verein_id=%d AND templatename IS NOT NULL ORDER BY datum DESC',$this->_websoccer->getUser()->getClubId($this->_websoccer,
			$this->_db));
		while($template=$result->fetch_array())$templates[]=$template;
		return array('templates'=>$templates);}}
class FreeClubsModel extends Model {
	function Template(){ return array("countries"=> TeamsDataService::getTeamsWithoutUser($this->_websoccer,$this->_db));}}
class GoogleplusLoginModel extends Model {
	function Template(){ return array("loginurl"=> GoogleplusSdk::getInstance($this->_websoccer)->getLoginUrl());}
	function View(){ return Config("googleplus_enable_login");}}
class HallOfFameModel extends Model {
	function Template(){
		$leagues=[];
		$cups=[];
		$columns=array('L.name'=>'league_name', 'L.land'=>'league_country', 'S.name'=>'season_name', 'C.id'=>'team_id', 'C.name'=>'team_name', 'C.bild'=>'team_picture');
		$fromTable=Config('db_prefix').'_saison AS S INNER JOIN '.Config('db_prefix').'_liga AS L ON L.id=S.liga_id INNER JOIN '.Config('db_prefix').'_verein AS C ON C.id=S.platz_1_id';
		$whereCondition='S.beendet=\'1\' ORDER BY L.land ASC, L.name ASC, S.id DESC';
		$result=$this->_db->querySelect($columns,$fromTable,$whereCondition);
		while($season=$result->fetch_array())$leagues[$season['league_name']][]=$season;
		$columns=array('CUP.name'=>'cup_name', 'C.id'=>'team_id', 'C.name'=>'team_name', 'C.bild'=>'team_picture');
		$fromTable=Config('db_prefix').'_cup AS CUP INNER JOIN '.Config('db_prefix').'_verein AS C ON C.id=CUP.winner_id';
		$whereCondition='CUP.winner_id IS NOT NULL ORDER BY CUP.id DESC';
		$result=$this->_db->querySelect($columns,$fromTable,$whereCondition);
		while($cup=$result->fetch_array())$cups[]=$cup;
		return array('leagues'=>$leagues, 'cups'=>$cups);}}
class HighscoreModel extends Model {
	function Template(){
		$count=UsersDataService::countActiveUsersWithHighscore($this->_websoccer,$this->_db);
		$paginator=new Paginator($count,Config('entries_per_page'),$this->_websoccer);
		if($count)$users=UsersDataService::getActiveUsersWithHighscore($this->_websoccer,$this->_db,$paginator->getFirstIndex(),Config('entries_per_page'));
		else $users=[];
		return array("users"=>$users,"paginator"=>$paginator);}}
class ImprintModel extends Model {
	function Template(){
		$filecontent="";
		if(file_exists($_SERVER['DOCUMENT_ROOT'].'/generated/imprint.php'))$filecontent=file_get_contents($_SERVER['DOCUMENT_ROOT'].'/generated/imprint.php');
		return array("imprint_content"=>$filecontent);}}
class LanguageSwitcherModel extends Model {
	function View(){ return (count($this->_i18n->getSupportedLanguages())>1);}}
class LastMatchModel extends Model {
	function Template(){
		$matchinfo=MatchesDataService::getLastMatch($this->_websoccer,$this->_db);
		return array("last_match"=>$matchinfo);}}
class LastTransfersModel extends Model {
	function Template(){
		$teamId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);
		$transfers=TransfermarketDataService::getLastCompletedTransfers($this->_websoccer,$this->_db,$teamId);
		return array("completedtransfers"=>$transfers);}}
class LatestResultsBlockModel extends Model {
	function Template(){
		$matches=MatchesDataService::getLatestMatches($this->_websoccer,$this->_db, 5, TRUE);
		return array('matches'=>$matches);}}
class LatestResultsModel extends Model {
	function Template(){
		$matches=MatchesDataService::getLatestMatches($this->_websoccer,$this->_db);
		return array("matches"=>$matches);}}
class LeagueDetailsModel extends Model{
	function Template(){
		$league=null;
		$leagueId=(int)Request("id");
		if($leagueId==0){
			$clubId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);
			if($clubId){
				$result=$this->_db->querySelect("liga_id",Config("db_prefix")."_verein","id=%d",$clubId, 1);
				$club=$result->fetch_array();
				$leagueId=$club["liga_id"];}}
		if($leagueId){
			$league=LeagueDataService::getLeagueById($this->_websoccer,$this->_db,$leagueId);
			if(!isset($league["league_id"]))throw new Exception(Message('error_page_not_found'));}
		return array("league"=>$league,"leagues"=> LeagueDataService::getLeaguesSortedByCountry($this->_websoccer,$this->_db));}}
class LeagueSelectionModel extends Model {
	function Template(){
		$fromTable=Config("db_prefix")."_liga";
		$whereCondition="land='%s' ORDER BY name ASC";
		$leagues=[];
		$result=$this->_db->querySelect("id, name",$fromTable,$whereCondition,$this->_country);
		while($league=$result->fetch_array())$leagues[]=$league;
		return array("leagues"=>$leagues);}
	function View(){
		$this->_country=Request("country");
		return (strlen($this->_country));}}
class LeaguesListModel extends Model {
	function Template(){
		$fromTable=Config("db_prefix")."_liga";
		$whereCondition="1=1 ORDER BY land ASC, name ASC";
		$leagues=[];
		$result=$this->_db->querySelect("id, land AS country, name",$fromTable,$whereCondition,array());
		while($league=$result->fetch_array())$leagues[$league["country"]][]=$league;
		return array("countries"=>$leagues);}}
class LeaguesOverviewModel extends Model {
	function Template(){
		$fromTable=Config("db_prefix")."_liga";
		$whereCondition="1=1 GROUP BY land ORDER BY land ASC";
		$columns["land"]="name";
		$columns["COUNT(*)"]="noOfLeagues";
		$countries=[];
		$result=$this->_db->querySelect($columns,$fromTable,$whereCondition);
		while($country=$result->fetch_array())$countries[]=$country;
		return array("countries"=>$countries);}}
class LeagueTableModel extends Model{
	  			function __construct($db,$i18n,$websoccer){$this->_db=$db;$this->_i18n=$i18n;$this->_websoccer=$websoccer;$this->_leagueId=(int)Request('id');$this->_seasonId=Request('seasonid');$this->_type=Request('type');$clubId=$this->_websoccer->getUser()->
							getClubId($this->_websoccer,$this->_db);if($this->_leagueId==0&&$clubId>0){$result=$db->querySelect('liga_id',Config('db_prefix').'_verein','id=%d',$clubId,1);$club=$result->fetch_array();$result->free();$this->_leagueId=$club['liga_id'];}}
				function Template(){$markers=[];$teams=[];if($this->_seasonId==null&&$this->_type==null){$teams=TeamsDataService::getTeamsOfLeagueOrderedByTableCriteria($this->_websoccer,$this->_db,$this->_leagueId);$result=$this->_db->querySelect(
							['bezeichnung'=>'name','farbe'=>'color','platz_von'=>'place_from','platz_bis'=>'place_to'],Config('db_prefix').'_tabelle_markierung','liga_id=%d ORDER BY place_from ASC',$this->_leagueId);while($marker=$result->fetch_array())$markers[]=
							$marker;}else{$seasonId=0;if($this->_seasonId==null){$result=$this->_db->querySelect('id',Config('db_prefix').'_saison',"liga_id=%d AND beendet='0'ORDER BY name DESC",$this->_leagueId,1);$season=$result->fetch_array();if($season['id'])
							$seasonId=$season['id'];}else$seasonId=$this->_seasonId;if($seasonId)$teams=TeamsDataService::getTeamsOfSeasonOrderedByTableCriteria($this->_websoccer,$this->_db,$seasonId,$this->_type);}$seasons=[];$result=$this->_db->querySelect('id,name',
							Config('db_prefix').'_saison',"liga_id=%d AND beendet='1'ORDER BY name DESC",$this->_leagueId);while($season=$result->fetch_array())$seasons[]=$season;return['leagueId'=>$this->_leagueId,'teams'=>$teams,'markers'=>$markers,
							'seasons'=>$seasons];}
				function View(){return($this->_leagueId>0);}}
class LentPlayersModel extends Model {
	function Template(){
		$teamId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);
		$columns=array("P.id"=>"id","vorname"=>"firstname","nachname"=>"lastname","kunstname"=>"pseudonym","position"=>"position","position_main"=>"position_main","position_second"=>"position_second","nation"=>"player_nationality",
			"lending_matches"=>"lending_matches","lending_fee"=>"lending_fee","C.id"=>"team_id","C.name"=>"team_name");
		if(Config('players_aging')=='birthday')$ageColumn='TIMESTAMPDIFF(YEAR,geburtstag,CURDATE())';
		else $ageColumn='age';
		$columns[$ageColumn]='age';
		$dbPrefix=Config("db_prefix");
		$fromTable=$dbPrefix."_spieler P INNER JOIN ".$dbPrefix."_verein C ON C.id=P.verein_id";
		$whereCondition="P.status=1 AND lending_owner_id=%d";
		$whereCondition.=" ORDER BY lending_matches ASC, position ASC, position_main ASC, nachname ASC, vorname ASC";
		$result=$this->_db->querySelect($columns,$fromTable,$whereCondition,$teamId, 50);
		$players=[];
		while($player=$result->fetch_array()){
			$player["position"]=PlayersDataService::_convertPosition($player["position"]);
			$players[]=$player;}
		return array('lentplayers'=>$players);}}
class LiveMatchBlockModel extends Model {
	function Template(){ return array("match"=>$this->_match);}
	function View(){
		$this->_match=MatchesDataService::getLiveMatch($this->_websoccer,$this->_db);;
		return (count($this->_match));}}
class MatchChangesModel extends FormationModel{private$_db,$_i18n,$_websoccer;
	function __construct($db,$i18n,$websoccer){$this->_db=$db;$this->_i18n=$i18n;$this->_websoccer=$websoccer;}
	function Template(){$matchId=(int)Request('id');if($matchId<1)throw new Exception(Message(config(MSG_KEY_ERROR_PAGENOTFOUND)));$match=MatchesDataService::getMatchSubstitutionsById($this->_websoccer,$this->_db,$matchId);if($match['match_simulated'])
						throw new Exception(Message('match_details_match_completed'));$teamId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);if($match['match_home_id']!==$teamId&&$match['match_guest_id']!==$teamId)
						$teamId=NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer,$this->_db);if($teamId!==$match['match_home_id']&&$match['match_guest_id']!==$teamId)throw new Exception('illegal match');
						$teamPrefix=($teamId==$match['match_home_id'])?'home':'guest';$players=MatchesDataService::getMatchPlayerRecordsByField($this->_websoccer,$this->_db,$matchId,$teamId);$playersOnField=$players['field'];$playersOnBench=(isset($players['bench']))?
						$players['bench']:[];$formation=[];if(Request('freekickplayer'))$formation['freekickplayer']=Request('freekickplayer');else$formation['freekickplayer']=$match['match_'.$teamPrefix.'_freekickplayer'];
						if(Request('offensive'))$formation['offensive']=Request('offensive');else$formation['offensive']=$match['match_'.$teamPrefix.'_offensive'];if(Request('longpasses'))$formation['longpasses']=Request('longpasses');
						else$formation['longpasses']=$match['match_'.$teamPrefix.'_longpasses'];if(Request('counterattacks'))$formation['counterattacks']=Request('counterattacks');else$formation['counterattacks']=$match['match_'.$teamPrefix.'_counterattacks'];
						$playerNo=0;
		foreach($playersOnField as$player){++$playerN;
			$formation['player'.$playerNo]=$player['id'];
			$formation['player'.$playerNo.'_pos']=$player['match_position_main'];}
		$setup = array('defense'=>6,'dm' => 3,'midfield' => 4,'om' => 3,'striker' => 2,'outsideforward' => 2);
		$setupMainMapping = array('LV' => 'defense','RV' => 'defense','IV' => 'defense','DM' => 'dm','LM' => 'midfield','ZM' => 'midfield','RM' => 'midfield','OM' => 'om','LS' => 'outsideforward','MS' => 'striker','RS' => 'outsideforward');
		$setupPosMapping = array('Abwehr' => 'defense','Mittelfeld' => 'midfield','Sturm' => 'striker');
		for ($playerNo = 1; $playerNo <= 11;++$playerNo) {
			if(Request('player'.$playerNo) > 0) {
				$formation['player'.$playerNo]=Request('player'.$playerNo);
				$formation['player'.$playerNo . '_pos']=Request('player'.$playerNo . '_pos');}}
		$benchNo = 0;
		foreach ($playersOnBench as $player) {
			++$benchNo;
			$formation['bench'.$benchNo] = $player['id'];}
		for ($benchNo = 1; $benchNo <= 5;++$benchNo) {
			if(Request('bench'.$benchNo))$formation['bench'.$benchNo]=Request('bench'.$benchNo);
			elseif (!isset($formation['bench'.$benchNo]))$formation['bench'.$benchNo]='';
		}
	for ($subNo = 1; $subNo <= 3;++$subNo) {
			if(Request('sub'.$subNo.'_out')) {
				$formation['sub'.$subNo.'_out']=Request('sub'.$subNo.'_out');
				$formation['sub'.$subNo.'_in']=Request('sub'.$subNo.'_in');
				$formation['sub'.$subNo.'_minute']=Request('sub'.$subNo.'_minute');
				$formation['sub'.$subNo.'_condition']=Request('sub'.$subNo.'_condition');
				$formation['sub'.$subNo.'_position']=Request('sub'.$subNo.'_position');}
			elseif (isset($match[$teamPrefix.'_sub'.$subNo.'_out'])) {
				$formation['sub'.$subNo.'_out']=$match[$teamPrefix.'_sub'.$subNo.'_out'];
				$formation['sub'.$subNo.'_in']=$match[$teamPrefix.'_sub'.$subNo.'_in'];
				$formation['sub'.$subNo.'_minute']=$match[$teamPrefix.'_sub'.$subNo.'_minute'];
				$formation['sub'.$subNo.'_condition']=$match[$teamPrefix.'_sub'.$subNo.'_condition'];
				$formation['sub'.$subNo.'_position']=$match[$teamPrefix.'_sub'.$subNo.'_position'];}
			else {
				$formation['sub'.$subNo.'_out']='';
				$formation['sub'.$subNo.'_in']='';
				$formation['sub'.$subNo.'_minute']='';
				$formation['sub'.$subNo.'_condition']='';
				$formation['sub'.$subNo.'_position']='';}}
		return array('setup' => $setup, 'players' => $players, 'formation' => $formation, 'minute' => $match['match_minutes']);}}
class MatchDayResultsModel extends Model {
	function Template(){
		$matches=MatchesDataService::getMatchesByMatchday($this->_websoccer,$this->_db,$this->_seasonId,$this->_matchday);
		return array("matches"=>$matches);}
	function View(){
		$this->_seasonId=(int)Request("seasonid");
		$this->_matchday=(int)Request("matchday");
		return($this->_seasonId>0 &&$this->_matchday);}}
class MatchDetailsModel extends Model {
	function Template(){
		$matchId=(int)Request('id');
		if($matchId<1)throw new Exception(Message('error_page_not_found'));
		$match=MatchesDataService::getMatchById($this->_websoccer,$this->_db,$matchId);
		if(!isset($match['match_id']))throw new Exception(Message('error_page_not_found'));
		$allowTacticChanges=FALSE;
		$reportmessages=[];
		if($match['match_minutes']){
			$reportmessages=MatchesDataService::getMatchReportMessages($this->_websoccer,$this->_db,$this->_i18n,$matchId);
			$userTeamId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);
			$userNationalTeamId=NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer,$this->_db);
			if(!$match['match_simulated'] &&Config('sim_allow_livechanges')&&($match['match_home_id']==$userTeamId||$match['match_guest_id']==$userTeamId||$match['match_home_id']==$userNationalTeamId||$match['match_guest_id']
				= $userNationalTeamId))$allowTacticChanges=TRUE;}
		$homeStrikerMessages=[];
		$guestStrikerMessages=[];
		foreach($reportmessages as$reportMessage){
			$type=$reportMessage['type'];
			if($type=='Tor'||$type=='Tor_mit_vorlage'||$type=='Elfmeter_erfolg'||$type=='Freistoss_treffer'){
				if($reportMessage['active_home'])array_unshift($homeStrikerMessages,$reportMessage);
				else array_unshift($guestStrikerMessages,$reportMessage);}}
		return array('match'=>$match, 'reportmessages'=>$reportmessages, 'allowTacticChanges'=>$allowTacticChanges, 'homeStrikerMessages'=>$homeStrikerMessages, 'guestStrikerMessages'=>$guestStrikerMessages);}}
class MatchPlayersModel extends Model {
	function Template(){
		$matchId=(int)Request("id");
		if($matchId<1)throw new Exception(Message('error_page_not_found'));
		$match=MatchesDataService::getMatchById($this->_websoccer,$this->_db,$matchId, FALSE, TRUE);
		$home_players=MatchesDataService::getMatchReportPlayerRecords($this->_websoccer,$this->_db,$matchId,$match["match_home_id"]);
		$guest_players=MatchesDataService::getMatchReportPlayerRecords($this->_websoccer,$this->_db,$matchId,$match["match_guest_id"]);
		if($match["match_simulated"]){
			$result=$this->_db->querySelect("player_id",Config("db_prefix")."_teamoftheday","season_id=%d AND matchday=%d",array($match["match_season_id"],$match["match_matchday"]));
			$topPlayerIds=[];
			while($topmember=$result->fetch_array())$topPlayerIds[]=$topmember["player_id"];
			if(count($topPlayerIds)){
				for($playerIndex=0; $playerIndex<count($home_players);++$playerIndex){
					if(in_array($home_players[$playerIndex]["id"],$topPlayerIds))$home_players[$playerIndex]["is_best_player_of_day"]=TRUE;}
				for($playerIndex=0; $playerIndex<count($guest_players);++$playerIndex){
					if(in_array($guest_players[$playerIndex]["id"],$topPlayerIds))$guest_players[$playerIndex]["is_best_player_of_day"]=TRUE;}}}
		return array("match"=>$match,"home_players"=>$home_players,"guest_players"=>$guest_players);}}
class MatchPreviewModel extends Model {
	function Template(){
		$latestMatchesHome=$this->_getLatestMatchesByTeam($this->_match['match_home_id']);
		$latestMatchesGuest=$this->_getLatestMatchesByTeam($this->_match['match_guest_id']);
		return array('match'=>$this->_match, 'latestMatchesHome'=>$latestMatchesHome, 'latestMatchesGuest'=>$latestMatchesGuest, 'homeUser'=>$this->_getUserInfoByTeam($this->_match['match_home_id']),
			'guestUser'=>$this->_getUserInfoByTeam($this->_match['match_guest_id']));}
	function View(){
		$matchId=(int)Request('id');
		$this->_match=MatchesDataService::getMatchById($this->_websoccer,$this->_db,$matchId);
		return($this->_match['match_simulated']!='1'&&!$this->_match['match_minutes']);}
	function _getLatestMatchesByTeam($teamId){
		$whereCondition="M.berechnet=1 AND(HOME.id=%d OR GUEST.id=%d)";
		$parameters=array($teamId,$teamId);
		if($this->_match['match_season_id']){
			$whereCondition.=' AND M.saison_id=%d';
			$parameters[]=$this->_match['match_season_id'];}
		elseif(strlen($this->_match['match_cup_name'])){
			$whereCondition.=' AND M.pokalname=\'%s\'';
			$parameters[]=$this->_match['match_cup_name'];}
		else $whereCondition.=' AND M.spieltyp=\'Freundschaft\'';
		$whereCondition.=" ORDER BY M.datum DESC";
		return MatchesDataService::getMatchesByCondition($this->_websoccer,$this->_db,$whereCondition,$parameters, 5);}
	function _getUserInfoByTeam($teamId){
		$columns='U.id AS user_id, nick, email, picture';
		$fromTable=Config('db_prefix').'_user AS U INNER JOIN '.Config('db_prefix').'_verein AS C ON C.user_id=U.id';
		$result=$this->_db->querySelect($columns,$fromTable, 'C.id=%d',$teamId);
		$user=$result->fetch_array();
		if($user)$user['picture']=UsersDataService::getUserProfilePicture($this->_websoccer,$user['picture'],$user['email'],120);
		return$user;}}
class MatchStatisticsModel extends Model {
	function Template(){
		$matchId=(int)Request("id");
		if($matchId<1)throw new Exception(Message('error_page_not_found'));
		$match=MatchesDataService::getMatchById($this->_websoccer,$this->_db,$matchId);
		$columns["SUM(shoots)"]="shoots";
		$columns["SUM(ballcontacts)"]="ballcontacts";
		$columns["SUM(wontackles)"]="wontackles";
		$columns["SUM(passes_successed)"]="passes_successed";
		$columns["SUM(passes_failed)"]="passes_failed";
		$fromTable=Config("db_prefix")."_spiel_berechnung";
		$whereCondition="spiel_id=%d AND team_id=%d";
		$parameters=array($matchId,$match["match_home_id"]);
		$result=$this->_db->querySelect($columns,$fromTable,$whereCondition,$parameters);
		$homeStatistics=$result->fetch_array();
		$parameters=array($matchId,$match["match_guest_id"]);
		$result=$this->_db->querySelect($columns,$fromTable,$whereCondition,$parameters);
		$guestStatistics=$result->fetch_array();
		return array("match"=>$match,"homeStatistics"=>$homeStatistics,"guestStatistics"=>$guestStatistics);}}
class MessageDetailsModel extends Model {
			function Template(){
		$id=Request("id");
		$message=MessagesDataService::getMessageById($this->_websoccer,$this->_db,$id);
		if($message &&!$message["seen"]){
			$columns["gelesen"]="1";
			$fromTable=Config("db_prefix")."_briefe";
			$whereCondition="id=%d";
			$this->_db->queryUpdate($columns,$fromTable,$whereCondition,$id);}
		return array("message"=>$message);}}
class MessagesInboxModel extends Model {
			function Template(){
		$count=MessagesDataService::countInboxMessages($this->_websoccer,$this->_db);
		$paginator=new Paginator($count,Config('entries_per_page'),$this->_websoccer);
		if($count)$messages=MessagesDataService::getInboxMessages($this->_websoccer,$this->_db,$paginator->getFirstIndex(),Config('entries_per_page'));
		else $messages=[];
		return array("messages"=>$messages,"paginator"=>$paginator);}}
class MessagesOutboxModel extends Model {
			function Template(){
		$count=MessagesDataService::countOutboxMessages($this->_websoccer,$this->_db);
		$paginator=new Paginator($count,Config('entries_per_page'),$this->_websoccer);
		$paginator->addParameter("block","messages-outbox");
		if($count)$messages=MessagesDataService::getOutboxMessages($this->_websoccer,$this->_db,$paginator->getFirstIndex(),Config('entries_per_page'));
		else $messages=[];
		return array("messages"=>$messages,"paginator"=>$paginator);}}
class MyScheduleModel extends Model {
			function Template(){
		$matches=[];
		$paginator=null;
		$clubId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);
		$whereCondition='(home_verein=%d OR gast_verein=%d)AND berechnet!=\'1\'';
		$parameters=array($clubId,$clubId);
		$result=$this->_db->querySelect('COUNT(*)AS hits',Config('db_prefix').'_spiel',$whereCondition,$parameters);
		$matchesCnt=$result->fetch_array();
		if($matchesCnt)$count=$matchesCnt['hits'];
		else $count=0;
		if($count){
			$whereCondition.=' ORDER BY M.datum ASC';
			$paginator=new Paginator($count,Config('entries_per_page'),$this->_websoccer);
			$matches=MatchesDataService::getMatchesByCondition($this->_websoccer,$this->_db,$whereCondition,$parameters,$paginator->getFirstIndex().','.Config('entries_per_page'));}
		return array("matches"=>$matches,"paginator"=>$paginator);}}
class MyTeamModel extends Model{
			function Template(){$teamId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);$captain_id=TeamsDataService::getTeamCaptainIdOfTeam($this->_websoccer,$this->_db,$teamId);$players=[];
							if($teamId)$players=PlayersDataService::getPlayersOfTeamById($this->_websoccer,$this->_db,$teamId);return['players'=>$players,'captain_id'=>$captain_id];}}
class MyTransferBidsModel extends Model{
			function Template(){$teamId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);$bids=TransfermarketDataService::getCurrentBidsOfTeam($this->_websoccer,$this->_db,$teamId);return['bids'=>$bids];}}
class MyTransfersModel extends Model{
			function Template(){$teamId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);$transfers=TransfermarketDataService::getCompletedTransfersOfTeam($this->_websoccer,$this->_db,$teamId);return['completedtransfers'=>$transfers];}}
class MyYouthTeamModel extends Model{
			function Template(){$teamId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);$players=[];if($teamId){$players=YouthPlayersDataService::getYouthPlayersOfTeam($this->_websoccer,$this->_db,$teamId);$noOfPlayers=count($players);
				     		for($playerIndex=0;$playerIndex<$noOfPlayers;++$playerIndex)$players[$playerIndex]['nation_flagfile']=PlayersDataService::getFlagFilename($players[$playerIndex]['nation']);}return['players'=>$players];}
			function View(){return Config('youth_enabled');}}
class NationalMatchResultsModel extends Model{
			function Template(){$teamId=NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer,$this->_db);if(!$teamId)throw new Exception(Message('nationalteams_user_requires_team'));
							$matchesCount=NationalteamsDataService::countSimulatedMatches($this->_websoccer,$this->_db,$teamId);$paginator=new Paginator($matchesCount,5,$this->_websoccer);$paginator->addParameter('block','national-match-results');$matches=[];
							if($matchesCount)$matches=NationalteamsDataService::getSimulatedMatches($this->_websoccer,$this->_db,$teamId,$paginator->getFirstIndex(),5);return['paginator'=>$paginator,'matches'=>$matches];}
			function View(){return Config('nationalteams_enabled');}}
class NationalNextMatchesModel extends Model{
			function Template(){$teamId=NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer,$this->_db);if(!$teamId)throw new Exception(Message('nationalteams_user_requires_team'));
							$matchesCount=NationalteamsDataService::countNextMatches($this->_websoccer,$this->_db,$teamId);$paginator=new Paginator($matchesCount,5,$this->_websoccer);$paginator->addParameter('block','national-next-matches');$matches=[];
							if($matchesCount)$matches=NationalteamsDataService::getNextMatches($this->_websoccer,$this->_db,$teamId,$paginator->getFirstIndex(),5);return['paginator'=>$paginator,'matches'=>$matches];}
			function View(){return Config('nationalteams_enabled');}}
class NationalNextMatchModel extends Model{
			function Template(){$matches=NationalteamsDataService::getNextMatches($this->_websoccer,$this->_db,$this->_teamId,0,1);return['match'=>$matches[0]];}
			function View(){if(!Config('nationalteams_enabled'))return FALSE;$this->_teamId=NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer,$this->_db);if(!$this->_teamId)return FALSE;
							$matchesCount=NationalteamsDataService::countNextMatches($this->_websoccer,$this->_db,$this->_teamId);if(!$matchesCount)return FALSE;return TRUE;}}
class NationalPlayersModel extends Model{
			function Template(){$teamId=NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer,$this->_db);if(!$teamId)throw new Exception(Message('nationalteams_user_requires_team'));$result=$this->_db->querySelect('name',
							Config('db_prefix').'_verein','id=%d',$teamId);$team=$result->fetch_array();return['team_name'=>$team['name'],'players'=>NationalteamsDataService::getNationalPlayersOfTeamByPosition($this->_websoccer,$this->_db,$teamId)];}
			function View(){return Config('nationalteams_enabled');}}
class NationalTeamMatchesModel extends Model{
			function Template(){$teamId=NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer,$this->_db);if(!$teamId)throw new Exception(Message('nationalteams_user_requires_team'));return[];}
			function View(){return Config('nationalteams_enabled');}}
class NewsDetailsModel extends Model {
	function Template(){
		$tablePrefix=Config("db_prefix")."_";
		$fromTable=$tablePrefix."news AS NewsTab LEFT JOIN ".$tablePrefix."admin AS AdminTab ON NewsTab.autor_id=AdminTab.id";
		$whereCondition="NewsTab.id=%d AND status=1";
		$parameters=(int)Request("id");
		$result=$this->_db->querySelect("NewsTab.*, AdminTab.name AS author_name",$fromTable,$whereCondition,$parameters);
		$item=$result->fetch_array();
		if(!$item)throw new Exception(Message('error_page_not_found'));
		$message=$item["nachricht"];
		if($item["c_br"])$message=nl2br($message);
		if($item["c_links"])$message=$this->_strToLink($message);
		$relatedLinks=[];
		if($item["linktext1"] &&$item["linkurl1"])$relatedLinks[$item["linkurl1"]]=$item["linktext1"];
		if($item["linktext2"] &&$item["linkurl2"])$relatedLinks[$item["linkurl2"]]=$item["linktext2"];
		if($item["linktext3"] &&$item["linkurl3"])$relatedLinks[$item["linkurl3"]]=$item["linktext3"];
		$article=array("id"=>$item["id"],"title"=>$item["titel"],"date"=>FormattedDate($item["datum"]),"message"=>$message,"author_name"=>$item["author_name"]);
		return array("article"=>$article,"relatedLinks"=>$relatedLinks);}
	function _strToLink($str){
	  $str=preg_replace("#([\t\r\n ])([a-z0-9]+?){1}://([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)#i",'\1<a href="\2://\3" target="_blank">\2://\3</a>',$str);
	  $str=preg_replace("#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i","\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>",$str);
	  return$str;}}
class NewsListModel extends Model {
	function Template(){
		$fromTable=Config("db_prefix")."_news";
		$whereCondition="status=%d";
		$parameters="1";
		$result=$this->_db->querySelect("COUNT(*)AS hits",$fromTable,$whereCondition,$parameters);
		$rows=$result->fetch_array();
		$paginator=new Paginator($rows["hits"],Config('NEWS_ENTRIES_PER_PAGE'),$this->_websoccer);
		$columns="id, titel, datum, nachricht";
		$whereCondition.=" ORDER BY datum DESC";
		$limit=$paginator->getFirstIndex().",".Config('NEWS_ENTRIES_PER_PAGE');
		$result=$this->_db->querySelect($columns,$fromTable,$whereCondition,$parameters,$limit);
		$articles=[];
		while($article=$result->fetch_array())$articles[]=array("id"=>$article["id"],"title"=>$article["titel"],"date"=>FormattedDate($article["datum"]),"teaser"=>$this->_shortenMessage($article["nachricht"]));
		return array("articles"=>$articles,"paginator"=>$paginator);}
	function _shortenMessage($message){
		if(strlen($message)>config('NEWS_TEASER_MAXLENGTH')){
			$message=wordwrap($message, config('NEWS_TEASER_MAXLENGTH'));
			$message=substr($message, 0, strpos($message,"\n"))."...";}
		return$message;}}
class NextMatchModel extends Model{
			function Template(){if(Request('nationalteam'))$clubId=NationalteamsDataService::getNationalTeamManagedByCurrentUser($this->_websoccer,$this->_db);else$clubId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);
							$matchinfo=MatchesDataService::getNextMatch($this->_websoccer,$this->_db,$clubId);if(count($matchinfo))$matchinfo['previous_matches']=MatchesDataService::getPreviousMatches($matchinfo,$this->_websoccer,$this->_db);return$matchinfo;}}
class NotificationsModel extends Model{
			function Template(){$user=$this->_websoccer->getUser();$notifications=NotificationsDataService::getLatestNotifications($this->_websoccer,$this->_db,$this->_i18n,$user->id,$user->getClubId($this->_websoccer,$this->_db),
							Config('notifications_max'));$this->_db->queryUpdate(['seen'=>'1'],Config('db_prefix').'_notification','user_id=%d',$this->_websoccer->getUser()->id);return['notifications'=>$notifications];}}
class OfficeModel extends Model{
			function Template(){$user=$this->_websoccer->getUser();$clubId=$user->getClubId($this->_websoccer,$this->_db);if($clubId)RandomEventsDataService::createEventIfRequired($this->_websoccer,$this->_db,$user->id);return[];}}
class PaypalLinkModel extends Model {
			function Template(){
		$userId=$this->_websoccer->getUser()->id;
		$linkCode=Config("paypal_buttonhtml");
		$customField="<input type=\"hidden\" name=\"custom\" value=\"".$userId."\">";
		$notifyUrlField="<input type=\"hidden\" name=\"notify_url\" value=\"".aUrl("paypal-notify",null, null, TRUE)."\">";
		$linkCode=str_replace("</form>",$notifyUrlField .$customField."</form>",$linkCode);
		return array("linkCode"=>$linkCode);}
			function View(){ return Config("paypal_enabled");}}
class PlayerDetailsModel extends Model {
			function Template(){
		$playerId=(int)Request("id");
		if($playerId<1)throw new Exception(Message('error_page_not_found'));
		$player=PlayersDataService::getPlayerById($this->_websoccer,$this->_db,$playerId);
		if(!isset($player["player_id"]))throw new Exception(Message('error_page_not_found'));
		return array("player"=>$player);}}
class PlayerDetailsWithDependenciesModel extends Model {
			function Template(){
		$playerId=(int)Request("id");
		if($playerId<1)throw new Exception(Message('error_page_not_found'));
		$player=PlayersDataService::getPlayerById($this->_websoccer,$this->_db,$playerId);
		if(!isset($player["player_id"]))throw new Exception(Message('error_page_not_found'));
		$grades=$this->_getGrades($playerId);
		$transfers=TransfermarketDataService::getCompletedTransfersOfPlayer($this->_websoccer,$this->_db,$playerId);
		return array("player"=>$player,"grades"=>$grades,"completedtransfers"=>$transfers);}
			function _getGrades($playerId){
		$grades=[];
		$fromTable=Config("db_prefix")."_spiel_berechnung";
		$columns="note AS grade";
		$whereCondition="spieler_id=%d AND minuten_gespielt>0 ORDER BY id DESC";
		$parameters=$playerId;
		$result=$this->_db->querySelect($columns,$fromTable,$whereCondition,$parameters, 10);
		while($grade=$result->fetch_array())$grades[]=$grade["grade"];
		$grades=array_reverse($grades);
		return$grades;}}
class PlayersSearchModel extends Model {
			function Template(){
		$playersCount=PlayersDataService::findPlayersCount($this->_websoccer,$this->_db,$this->_firstName,$this->_lastName,$this->_club,$this->_position,$this->_strength,$this->_lendableOnly);
		$paginator=new Paginator($playersCount,Config('entries_per_page'),$this->_websoccer);
		$paginator->addParameter("block","playerssearch-results");
		$paginator->addParameter("fname",$this->_firstName);
		$paginator->addParameter("lname",$this->_lastName);
		$paginator->addParameter("club",$this->_club);
		$paginator->addParameter("position",$this->_position);
		$paginator->addParameter("strength",$this->_strength);
		$paginator->addParameter("lendable",$this->_lendableOnly);
		if($playersCount)$players=PlayersDataService::findPlayers($this->_websoccer,$this->_db,$this->_firstName,$this->_lastName,$this->_club,$this->_position,$this->_strength,$this->_lendableOnly,$paginator->getFirstIndex(),Config('entries_per_page'));
		else $players=[];
		return array("playersCount"=>$playersCount,"players"=>$players,"paginator"=>$paginator);}
			function View(){
		$this->_firstName=Request("fname");
		$this->_lastName=Request("lname");
		$this->_club=Request("club");
		$this->_position=Request("position");
		$this->_strength=Request("strength");
		$this->_lendableOnly=(Request("lendable")=="1")? TRUE : FALSE;
		return($this->_firstName !==null||$this->_lastName !==null||$this->_club !==null||$this->_position !==null||$this->_strength !==null||$this->_lendableOnly);}}
class PlayerStatisticsModel extends Model {
			function Template(){
		$playerId=(int)Request('id');
		if($playerId<1)throw new Exception(Message('error_page_not_found'));
		$leagueStatistics=[];
		$cupStatistics=[];
		$columns=array('L.name'=>'league_name', 'SEAS.name'=>'season_name', 'M.pokalname'=>'cup_name', 'COUNT(S.id)'=>'matches', 'SUM(S.assists)'=>'assists', 'AVG(S.note)'=>'grade', 'SUM(S.tore)'=>'goals', 'SUM(S.karte_gelb)'=>'yellowcards',
			'SUM(S.karte_rot)'=>'redcards', 'SUM(S.shoots)'=>'shoots', 'SUM(S.passes_successed)'=>'passes_successed', 'SUM(S.passes_failed)'=>'passes_failed');
		$fromTable=Config('db_prefix').'_spiel_berechnung AS S INNER JOIN '.Config('db_prefix').'_spiel AS M ON M.id=S.spiel_id LEFT JOIN ' .Config('db_prefix').
			'_saison AS SEAS ON SEAS.id=M.saison_id LEFT JOIN '.Config('db_prefix').'_liga AS L ON SEAS.liga_id=L.id';
		$whereCondition='S.spieler_id=%d AND S.minuten_gespielt>0 AND((M.spieltyp=\'Pokalspiel\' AND M.pokalname IS NOT NULL AND M.pokalname!=\'\')OR (M.spieltyp=\'Ligaspiel\' AND SEAS.id IS NOT NULL))GROUP BY IFNULL(M.pokalname,\'\'),
			SEAS.id ORDER BY L.name ASC, SEAS.id ASC, M.pokalname ASC';
		$result=$this->_db->querySelect($columns,$fromTable,$whereCondition,$playerId);
		while($statistic=$result->fetch_array()){
			if(strlen($statistic['league_name']))$leagueStatistics[]=$statistic;
			else $cupStatistics[]=$statistic;}
		return array('leagueStatistics'=>$leagueStatistics, 'cupStatistics'=>$cupStatistics);}}
class PremiumAccountModel extends Model{
			function Template(){$userId=$this->_websoccer->getUser()->id;$count=PremiumDataService::countAccountStatementsOfUser($this->_websoccer,$this->_db,$userId);$paginator=new Paginator($count,Config('entries_per_page'),$this->_websoccer);
							if($count)$statements=PremiumDataService::getAccountStatementsOfUser($this->_websoccer,$this->_db,$userId,$paginator->getFirstIndex(),Config('entries_per_page'));else$statements=[];return['statements'=>$statements,'paginator'=>$paginator,
							'payments'=>PremiumDataService::getPaymentsOfUser($this->_websoccer,$this->_db,$userId,5)];}}
class ProfileBlockModel extends Model{
	  		function Template(){$fromTable=Config('db_prefix').'_user';$user=$this->_websoccer->getUser();$columns='fanbeliebtheit AS user_popularity,highscore AS user_highscore';$whereCondition='id=%d';$result=$this->_db->querySelect($columns,$fromTable,
	  						$whereCondition,$user->id,1);$userinfo=$result->fetch_array();$clubId=$user->getClubId($this->_websoccer,$this->_db);$team=null;if($clubId)$team=TeamsDataService::getTeamSummaryById($this->_websoccer,$this->_db,$clubId);
							$unseenMessages=MessagesDataService::countUnseenInboxMessages($this->_websoccer,$this->_db);$unseenNotifications=NotificationsDataService::countUnseenNotifications($this->_websoccer,$this->_db,$user->id,$clubId);
							return['profile'=>$userinfo,'userteam'=>$team,'unseenMessages'=>$unseenMessages,'unseenNotifications'=>$unseenNotifications];}
			function View(){return(strlen($this->_websoccer->getUser()->username))?TRUE:FALSE;}}
class ProfileModel extends Model{
			function Template(){$user=$this->_websoccer->getUser();$result=$this->_db->querySelect(['name'=>'realname','wohnort'=>'place','land'=>'country','geburtstag'=>'birthday','beruf'=>'occupation','interessen'=>'interests',
							'lieblingsverein'=>'favorite_club','homepage'=>'homepage','c_hideinonlinelist'=>'c_hideinonlinelist'],Config('db_prefix').'_user','id=%d',$user->id,1);$userinfo=$result->fetch_array();if(!strlen($userinfo['birthday'])||
							substr($userinfo['birthday'],0,4)=='0000')$userinfo['birthday']='';else$userinfo['birthday']=DateTime::createFromFormat('Y-m-d',$userinfo['birthday'])->format(Config('date_format'));foreach($columns as$dbColumn)if(Request($dbColumn))
							$userinfo[$dbColumn]=Request($dbColumn);return['user'=>$userinfo];}}
class ProjectStatisticsModel extends Model{
			function Template(){return['usersOnline'=>UsersDataService::countOnlineUsers($this->_websoccer,$this->_db),'usersTotal'=>UsersDataService::countTotalUsers($this->_websoccer,$this->_db),'numberOfLeagues'=>
							LeagueDataService::countTotalLeagues($this->_websoccer,$this->_db),'numberOfFreeTeams'=>TeamsDataService::countTeamsWithoutManager($this->_websoccer,$this->_db)];}}
class RegisterFormModel extends Model{
			function Template(){if(!Config('allow_userregistration'))throw new Exception(Message('registration_disabled'));$parameters=[];if(Config('register_use_captcha')&&strlen(Config('register_captcha_publickey'))&&
							strlen(Config('register_captcha_privatekey'))){include_once($_SERVER['DOCUMENT_ROOT'].'/lib/recaptcha/recaptchalib.php');$useSsl=(!empty($_SERVER['HTTPS']));$captchaCode=recaptcha_get_html(Config('register_captcha_publickey'),null,$useSsl);
							$parameters['captchaCode']=$captchaCode;}return$parameters;}}
class RssResultsOfUserModel extends Model {
			function Template(){$userId=(int)Request('id');$matches=MatchesDataService::getLatestMatchesByUser($this->_websoccer,$this->_db,$userId);$items=[];foreach($matches as$match)$items[]=array('url'=>iUrl('match','id='.$match['id'],TRUE),
							'title'=>$match['home_team'].' - '.$match['guest_team'].' ('.$match['home_goals'].':'.$match['guest_goals'].')','date'=>gmdate(DATE_RSS,$match['date']));return['items'=>$items];}}
class SeasonsOfLeagueModel extends Model {
	function Template(){
		$fromTable=Config("db_prefix")."_saison AS S INNER JOIN ".Config("db_prefix")."_liga AS L ON L.id=S.liga_id";
		$whereCondition="S.liga_id=%d ORDER BY beendet DESC, name ASC";
		$seasons=[];
		$result=$this->_db->querySelect("S.id AS id, S.name AS name, L.name AS league_name",$fromTable,$whereCondition,$this->_leagueId);
		while($season=$result->fetch_array())$seasons[]=$season;
		$league_name="";
		if(isset($seasons[0]["league_name"]))$league_name=$seasons[0]["league_name"];
		$currentMatchDay=0;
		$maxMatchDay=0;
		if(Request("seasonid")!= null){
			$seasonId=Request("seasonid");
			$fromTable=Config("db_prefix")."_spiel";
			$condition="saison_id=%d";
			$result=$this->_db->querySelect("MAX(spieltag)AS maxMatchday",$fromTable,$condition,$seasonId);
			$match=$result->fetch_array();
			$maxMatchDay=$match["maxMatchday"];
			$result=$this->_db->querySelect("MAX(spieltag)AS currentMatchday",$fromTable,$condition." AND berechnet='1'",$seasonId);
			$match=$result->fetch_array();
			$currentMatchDay=$match["currentMatchday"];}
		return array("seasons"=>$seasons,"league_name"=>$league_name,"currentMatchDay"=>$currentMatchDay,"maxMatchDay"=>$maxMatchDay);}
	function View(){
		$this->_leagueId=(int)Request("leagueid");
		return($this->_leagueId);}}
class ShoutboxLeagueModel extends Model {
	function Template(){
		$messages=[];
		$tablePrefix=Config('db_prefix');
		$fromTable=$tablePrefix.'_shoutmessage AS MESSAGE INNER JOIN '.$tablePrefix.'_user AS U ON U.id=MESSAGE.user_id INNER JOIN '.$tablePrefix.'_spiel AS M ON M.id=MESSAGE.match_id INNER JOIN '.$tablePrefix .
			'_verein AS HOME ON HOME.id=M.home_verein INNER JOIN '.$tablePrefix.'_verein AS GUEST ON GUEST.id=M.gast_verein INNER JOIN '.$tablePrefix.'_saison AS SEASON ON (M.saison_id=SEASON.id)INNER JOIN '.$tablePrefix .
			'_liga AS L ON (L.id=SEASON.liga_id)';
		$whereCondition='L.id=%d ORDER BY MESSAGE.created_date DESC';
		$columns=array('MESSAGE.id'=>'message_id', 'MESSAGE.message'=>'message', 'MESSAGE.created_date'=>'date', 'U.id'=>'user_id', 'U.nick'=>'user_name', 'HOME.name'=>'home_name', 'GUEST.name'=>'guest_name', 'M.id'=>'match_id');
		$result=$this->_db->querySelect($columns,$fromTable,$whereCondition,$this->_leagueId, 20);
		while($message=$result->fetch_array())$messages[]=$message;
		return array("messages"=>$messages,"hidesubmit"=> TRUE);}
	function View(){
		$this->_leagueId=Request('id');
		return($this->_leagueId!=NULL);}}
class ShoutboxModel extends Model {
	function Template(){
		$messages=[];
		$tablePrefix=Config('db_prefix');
		$fromTable=$tablePrefix.'_shoutmessage AS MESSAGE INNER JOIN '.$tablePrefix.'_user AS U ON U.id=MESSAGE.user_id INNER JOIN '.$tablePrefix.'_spiel AS M ON M.id=MESSAGE.match_id INNER JOIN '.$tablePrefix .
			'_verein AS HOME ON HOME.id=M.home_verein INNER JOIN '.$tablePrefix.'_verein AS GUEST ON GUEST.id=M.gast_verein INNER JOIN '.$tablePrefix .
			'_spiel AS REFERENCE ON (M.saison_id IS NOT NULL AND M.saison_id=REFERENCE.saison_id OR M.pokalname IS NOT NULL AND M.pokalname!=\'\' AND  M.pokalname=REFERENCE.pokalname OR REFERENCE.spieltyp=\'Freundschaft\' AND M.spieltyp=REFERENCE.spieltyp)';
		$whereCondition='REFERENCE.id=%d ORDER BY MESSAGE.created_date DESC';
		$columns=array('MESSAGE.id'=>'message_id', 'MESSAGE.message'=>'message', 'MESSAGE.created_date'=>'date', 'U.id'=>'user_id', 'U.nick'=>'user_name', 'HOME.name'=>'home_name', 'GUEST.name'=>'guest_name', 				'M.id'=>'match_id');
		$result=$this->_db->querySelect($columns,$fromTable,$whereCondition,$this->_matchId, 20);
		while($message=$result->fetch_array())$messages[]=$message;
		return array("messages"=>$messages);}
	function View(){
		$this->_matchId=Request('id');
		return($this->_matchId!=NULL);}}
class SponsorModel extends Model {
	function Template(){
		$teamId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);
		if($teamId<1)throw new Exception(Message("feature_requires_team"));
		$sponsor=SponsorsDataService::getSponsorinfoByTeamId($this->_websoccer,$this->_db,$teamId);
		$sponsors=[];
		$teamMatchday=0;
		if(!$sponsor){
			$teamMatchday=MatchesDataService::getMatchdayNumberOfTeam($this->_websoccer,$this->_db,$teamId);
			if($teamMatchday >=Config("sponsor_earliest_matchday"))$sponsors=SponsorsDataService::getSponsorOffers($this->_websoccer,$this->_db,$teamId);}
		return array("sponsor"=>$sponsor,"sponsors"=>$sponsors,"teamMatchday"=>$teamMatchday);}}
class StadiumEnvironmentModel extends Model {
	function Template(){
		$teamId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);
		if($teamId<1)throw new Exception(Message("feature_requires_team"));
		$dbPrefix=Config('db_prefix');
		$existingBuildings=[];
		$result=$this->_db->querySelect('*',$dbPrefix.'_buildings_of_team INNER JOIN '.$dbPrefix.'_stadiumbuilding ON id=building_id', 'team_id=%d ORDER BY construction_deadline DESC',$teamId);
		$now=Timestamp();
		while($building=$result->fetch_array()){
			$building['under_construction']=$now<$building['construction_deadline'];
			$existingBuildings[]=$building;}
		$availableBuildings=[];
		$result=$this->_db->querySelect('*',$dbPrefix.'_stadiumbuilding', 'id NOT IN (SELECT building_id FROM '.$dbPrefix.'_buildings_of_team WHERE team_id=%d)'.' AND(required_building_id IS NULL OR required_building_id IN (SELECT building_id FROM ' .
			$dbPrefix.'_buildings_of_team WHERE team_id=%d AND construction_deadline<%d))'.' ORDER BY name ASC',array($teamId,$teamId,$now));
		while($building=$result->fetch_array()){
			if(hasMessage($building['name']))$building['name']=Message($building['name']);
			if(hasMessage($building['description']))$building['description']=Message($building['description']);
			$availableBuildings[]=$building;}
		return array('existingBuildings'=>$existingBuildings, 'availableBuildings'=>$availableBuildings);}}
class StadiumExtensionModel extends Model {
			function Template(){
		$teamId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);
		$offers=StadiumsDataService::getBuilderOffersForExtension($this->_websoccer,$this->_db,$teamId, (int)Request("side_standing"), (int)Request("side_seats"),
			(int)Request("grand_standing"),(int)Request("grand_seats"),(int)Request("vip"));
		return array("offers"=>$offers);}}
class StadiumModel extends Model {
			function Template(){
		$teamId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);
		$stadium=StadiumsDataService::getStadiumByTeamId($this->_websoccer,$this->_db,$teamId);
		$construction=StadiumsDataService::getCurrentConstructionOrderOfTeam($this->_websoccer,$this->_db,$teamId);
		$upgradeCosts=[];
		if($stadium){
			$upgradeCosts["pitch"]=StadiumsDataService::computeUpgradeCosts($this->_websoccer,"pitch",$stadium);
			$upgradeCosts["videowall"]=StadiumsDataService::computeUpgradeCosts($this->_websoccer,"videowall",$stadium);
			$upgradeCosts["seatsquality"]=StadiumsDataService::computeUpgradeCosts($this->_websoccer,"seatsquality",$stadium);
			$upgradeCosts["vipquality"]=StadiumsDataService::computeUpgradeCosts($this->_websoccer,"vipquality",$stadium);}
		return array("stadium"=>$stadium,"construction"=>$construction,"upgradeCosts"=>$upgradeCosts);}}
class TableHistoryModel extends Model {
			function Template(){
		$teamId=(int)Request('id');
		if($teamId<1)throw new Exception(Message('error_page_not_found'));
		$team=TeamsDataService::getTeamById($this->_websoccer,$this->_db,$teamId);
		if(!isset($team['team_id']))throw new Exception(Message('error_page_not_found'));
		$result=$this->_db->querySelect('id',Config('db_prefix').'_saison', 'liga_id=%d AND beendet=\'0\' ORDER BY name DESC',$team['team_league_id'],1);
		$season=$result->fetch_array();
		$history=[];
		if($season){
			$columns=array('H.matchday'=>'matchday', 'H.rank'=>'rank');
			$fromTable=Config('db_prefix').'_leaguehistory AS H';
			$result=$this->_db->querySelect('matchday, rank',$fromTable, 'season_id=%d AND team_id=%s ORDER BY matchday ASC',array($season['id'],$team['team_id']));
			while($historyRecord=$result->fetch_array())$history[]=$historyRecord;}
		$result=$this->_db->querySelect('COUNT(*)AS cnt',Config('db_prefix').'_verein', 'liga_id=%d AND status=\'1\'',$team['team_league_id'],1);
		$teams=$result->fetch_array();
		return array('teamName'=>$team['team_name'],'history'=>$history, 'noOfTeamsInLeague'=>$teams['cnt'],'leagueid'=>$team['team_league_id']);}}
class TeamDetailsModel extends Model {
			function Template(){
		$teamId=(int)Request('id');
		if($teamId<1)throw new Exception(Message('error_page_not_found'));
		$team=TeamsDataService::getTeamById($this->_websoccer,$this->_db,$teamId);
		if(!isset($team['team_id']))throw new Exception(Message('error_page_not_found'));
		$stadium=StadiumsDataService::getStadiumByTeamId($this->_websoccer,$this->_db,$teamId);
		if($team['is_nationalteam']){
			$dbPrefix=Config('db_prefix');
			$result=$this->_db->querySelect('AVG(P.w_staerke)AS avgstrength',$dbPrefix.'_spieler AS P INNER JOIN '.$dbPrefix.'_nationalplayer AS NP ON P.id=NP.player_id', 'NP.team_id=%d',$team['team_id']);
			$players=$result->fetch_array();
			if($players)$team['team_strength']=$players['avgstrength'];}
		if(!$team['is_nationalteam'])$playerfacts=$this->getPlayerFacts($teamId);
		else $playerfacts=[];
		$team['victories']=$this->getVictories($team['team_id'],$team['team_league_id']);
		$team['cupvictories']=$this->getCupVictories($team['team_id']);
		return array('team'=>$team, 'stadium'=>$stadium, 'playerfacts'=>$playerfacts);}
			function getVictories($teamId,$leagueId){
		$fromTable=Config('db_prefix').'_saison AS S INNER JOIN '.Config('db_prefix').'_liga AS L ON L.id=S.liga_id';
		$columns['S.name']='season_name';
		$columns['L.name']='league_name';
		$columns['platz_1_id']='season_first';
		$columns['platz_2_id']='season_second';
		$columns['platz_3_id']='season_third';
		$columns['platz_4_id']='season_fourth';
		$columns['platz_5_id']='season_fivth';
		$whereCondition='beendet=1 AND(platz_1_id=%d OR platz_2_id=%d OR platz_3_id=%d OR platz_4_id=%d OR platz_5_id=%d)';
		$parameters=array($teamId,$teamId,$teamId,$teamId,$teamId);
		$victories=[];
		$result=$this->_db->querySelect($columns,$fromTable,$whereCondition,$parameters);
		while($season=$result->fetch_array()){
			$place=1;
			if($season['season_second']==$teamId)$place=2;
			elseif($season['season_third']==$teamId)$place=3;
			elseif($season['season_fourth']==$teamId)$place=4;
			elseif($season['season_fivth']==$teamId)$place=5;
			$victories[]=array('season_name'=>$season['season_name'],'season_place'=>$place, 'league_name'=>$season['league_name']);}
		return$victories;}
			function getCupVictories($teamId){
		$fromTable=Config('db_prefix').'_cup';
		$whereCondition='winner_id=%d ORDER BY name ASC';
		$result=$this->_db->querySelect('id AS cup_id,name AS cup_name,logo AS cup_logo',$fromTable,$whereCondition,$teamId);
		$victories=[];
		while($cup=$result->fetch_array())$victories[]=$cup;;
		return$victories;}
			function getPlayerFacts($teamId){
		$columns=array('COUNT(*)'=>'numberOfPlayers');
		if(Config('players_aging')=='birthday')$ageColumn='TIMESTAMPDIFF(YEAR,geburtstag,CURDATE())';
		else $ageColumn='age';
		$columns['AVG('.$ageColumn.')']='avgAge';
		if(Config('transfermarket_computed_marketvalue')){
			$columns['SUM(w_staerke)']='sumStrength';
			$columns['SUM(w_technik)']='sumTechnique';
			$columns['SUM(w_frische)']='sumFreshness';
			$columns['SUM(w_zufriedenheit)']='sumSatisfaction';
			$columns['SUM(w_kondition)']='sumStamina';}
		else $columns['SUM(marktwert)']='sumMarketValue';
		$result=$this->_db->querySelect($columns,Config('db_prefix').'_spieler', 'verein_id=%d AND status=\'1\'',$teamId);
		$playerfacts=$result->fetch_array();
		if(Config('transfermarket_computed_marketvalue'))$playerfacts['sumMarketValue']=$this->computeMarketValue($playerfacts['sumStrength'],$playerfacts['sumTechnique'],$playerfacts['sumFreshness'],$playerfacts['sumSatisfaction'],
			$playerfacts['sumStamina']);
		if($playerfacts['numberOfPlayers'])$playerfacts['avgMarketValue']=$playerfacts['sumMarketValue'] / $playerfacts['numberOfPlayers'];
		else $playerfacts['avgMarketValue']=0;
		return$playerfacts;}
			function computeMarketValue($strength,$technique,$freshness,$satisfaction,$stamina){
		$weightStrength=Config('sim_weight_strength');
		$weightTech=Config('sim_weight_strengthTech');
		$weightStamina=Config('sim_weight_strengthStamina');
		$weightFreshness=Config('sim_weight_strengthFreshness');
		$weightSatisfaction=Config('sim_weight_strengthSatisfaction');
		$totalStrength=$weightStrength*$strength;
		$totalStrength += $weightTech*$technique;
		$totalStrength += $weightStamina*$freshness;
		$totalStrength += $weightFreshness*$satisfaction;
		$totalStrength += $weightSatisfaction*$stamina;
		$totalStrength /= $weightStrength + $weightTech + $weightStamina + $weightFreshness + $weightSatisfaction;
		return$totalStrength *Config('transfermarket_value_per_strength');}}
class TeamHistoryModel extends Model {
			function Template(){
		$columns=array('U.id'=>'user_id', 'U.nick'=>'user_name', 'L.name'=>'league_name', 'SEASON.name'=>'season_name', 'A.rank'=>'season_rank', 'A.id'=>'achievement_id', 'A.date_recorded'=>'achievement_date', 'CUP.name'=>'cup_name',
			'CUPROUND.name'=>'cup_round_name');
		$tablePrefix=Config('db_prefix');
		$fromTable=$tablePrefix.'_achievement AS A LEFT JOIN '.$tablePrefix.'_saison AS SEASON ON SEASON.id=A.season_id LEFT JOIN '.$tablePrefix.'_liga AS L ON SEASON.liga_id=L.id LEFT JOIN '.$tablePrefix .
			'_cup_round AS CUPROUND ON CUPROUND.id=A.cup_round_id LEFT JOIN '.$tablePrefix.'_cup AS CUP ON CUP.id=CUPROUND.cup_id LEFT JOIN '.$tablePrefix.'_user AS U ON U.id=A.user_id';
		$whereCondition='A.team_id=%d ORDER BY A.date_recorded DESC';
		$result=$this->_db->querySelect($columns,$fromTable,$whereCondition,$this->_teamId);
		$leagues=[];
		$cups=[];
		while($achievement=$result->fetch_array()){
			if(strlen($achievement['league_name']))$leagues[$achievement['league_name']][]=$achievement;
			elseif(!isset($cups[$achievement['cup_name']]))$cups[$achievement['cup_name']]=$achievement;
			else $this->_db->queryDelete($tablePrefix.'_achievement', 'id=%d',$achievement['achievement_id']);}
		return array("leagues"=>$leagues,"cups"=>$cups);}
			function View(){
		$this->_teamId=(int)Request("teamid");
		return$this->_teamId>0;}}
class TeamOfTheDayModel extends Model {
			function Template(){
		$players=[];
		$positions;
		$leagues=LeagueDataService::getLeaguesSortedByCountry($this->_websoccer,$this->_db);
		$leagueId=Request("leagueid");
		if(!$leagueId){
			$clubId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);
			if($clubId){
				$result=$this->_db->querySelect("liga_id",Config("db_prefix")."_verein","id=%d",$clubId, 1);
				$club=$result->fetch_array();
				$leagueId=$club["liga_id"];}}
		$seasons=[];
		$seasonId=Request("seasonid");
		if($leagueId){
			$fromTable=Config("db_prefix")."_saison";
			$whereCondition="liga_id=%d ORDER BY name ASC";
			$result=$this->_db->querySelect("id, name, beendet",$fromTable,$whereCondition,$leagueId);
			while($season=$result->fetch_array()){
				$seasons[]=$season;
				if(!$seasonId &&!$season["beendet"])$seasonId=$season["id"];}}
		$matchday=Request("matchday");
		$maxMatchDay=0;
		$openMatchesExist=FALSE;
		if($seasonId){
			$result=$this->_db->querySelect("MAX(spieltag)AS max_matchday",Config("db_prefix")."_spiel","saison_id=%d AND berechnet='1'",$seasonId);
			$matches=$result->fetch_array();
			if($matches){
				$maxMatchDay=$matches["max_matchday"];
				if(!$matchday)$matchday=$maxMatchDay;
				$result=$this->_db->querySelect("COUNT(*)AS hits",Config("db_prefix")."_spiel","saison_id=%d AND spieltag=%d AND berechnet!='1'",array($seasonId,$matchday));
				$openmatches=$result->fetch_array();
				if($openmatches &&$openmatches["hits"])$openMatchesExist=TRUE;
				else $this->getTeamOfTheDay($seasonId,$matchday,$players);}}
		return array("leagues"=>$leagues,"leagueId"=>$leagueId,"seasons"=>$seasons,"seasonId"=>$seasonId,"maxMatchDay"=>$maxMatchDay,"matchday"=>$matchday,"openMatchesExist"=>$openMatchesExist,"players"=>$players);}
			function getTeamOfTheDay($seasonId,$matchday, &$players){
		$seasonId=(int)$seasonId;
		if($matchday==-1){
			$this->findPlayersForTeamOfSeason($seasonId,array("T"), 1,$players);
			$this->findPlayersForTeamOfSeason($seasonId,array("LV"), 1,$players);
			$this->findPlayersForTeamOfSeason($seasonId,array("IV"), 2,$players);
			$this->findPlayersForTeamOfSeason($seasonId,array("RV"), 1,$players);
			$this->findPlayersForTeamOfSeason($seasonId,array("LM"), 1,$players);
			$this->findPlayersForTeamOfSeason($seasonId,array("DM","ZM","OM"), 2,$players);
			$this->findPlayersForTeamOfSeason($seasonId,array("RM"), 1,$players);
			$this->findPlayersForTeamOfSeason($seasonId,array("LS","MS","RS"), 2,$players);
			return;}
		$columns=array( "S.id"=>"statistic_id","S.spieler_id"=>"player_id","S.name"=>"player_name","P.picture"=>"picture","S.position"=>"position","S.position_main"=>"position_main","S.note"=>"grade","S.tore"=>"goals","S.assists"=>"assists",
			"T.name"=>"team_name","T.bild"=>"team_picture","(SELECT COUNT(*)FROM ".Config("db_prefix")."_teamoftheday AS STAT WHERE STAT.season_id=$seasonId AND STAT.player_id=S.spieler_id)"=>"memberoftopteam");
		$fromTable=Config("db_prefix")."_teamoftheday AS C INNER JOIN ".Config("db_prefix")."_spiel_berechnung AS S ON S.id=C.statistic_id INNER JOIN ".Config("db_prefix").
			"_spiel AS M ON M.id=S.spiel_id INNER JOIN ".Config("db_prefix")."_verein AS T ON T.id=S.team_id LEFT JOIN ".Config("db_prefix")."_spieler AS P ON P.id=S.spieler_id";
		$result=$this->_db->querySelect($columns,$fromTable,"C.season_id=%d AND C.matchday=%d",array($seasonId,$matchday));
		while($player=$result->fetch_array())$players[]=$player;
		if(!count($players)){
			$this->findPlayers($columns,$seasonId,$matchday,array("T"), 1,$players);
			$this->findPlayers($columns,$seasonId,$matchday,array("LV"), 1,$players);
			$this->findPlayers($columns,$seasonId,$matchday,array("IV"), 2,$players);
			$this->findPlayers($columns,$seasonId,$matchday,array("RV"), 1,$players);
			$this->findPlayers($columns,$seasonId,$matchday,array("LM"), 1,$players);
			$this->findPlayers($columns,$seasonId,$matchday,array("DM","ZM","OM"), 2,$players);
			$this->findPlayers($columns,$seasonId,$matchday,array("RM"), 1,$players);
			$this->findPlayers($columns,$seasonId,$matchday,array("LS","MS","RS"), 2,$players);}}
			function findPlayers($columns,$seasonId,$matchday,$mainPositions,$limit, &$players){
		$fromTable=Config("db_prefix")."_spiel_berechnung AS S INNER JOIN ".Config("db_prefix")."_spiel AS M ON M.id=S.spiel_id INNER JOIN ".Config("db_prefix").
			"_verein AS T ON T.id=S.team_id LEFT JOIN ".Config("db_prefix")."_spieler AS P ON P.id=S.spieler_id";
		$whereCondition="M.saison_id=%d AND M.spieltag=%d AND(S.position_main='";
		$whereCondition .= implode("' OR S.position_main='",$mainPositions);
		$whereCondition.="')ORDER BY S.note ASC, S.tore DESC, S.assists DESC, S.wontackles DESC";
		$result=$this->_db->querySelect($columns,$fromTable,$whereCondition,array($seasonId,$matchday),$limit);
		while($player=$result->fetch_array()){
			$players[]=$player;
			$this->_db->queryInsert(array("season_id"=>$seasonId,"matchday"=>$matchday,"position_main"=>$player["position_main"],"statistic_id"=>$player["statistic_id"],"player_id"=>$player["player_id"]),Config("db_prefix")."_teamoftheday");}}
			function findPlayersForTeamOfSeason($seasonId,$mainPositions,$limit, &$players){
		$columns=array( "P.id"=>"player_id","P.vorname"=>"firstname","P.nachname"=>"lastname","P.kunstname"=>"pseudonym","P.picture"=>"picture","P.position"=>"position","C.position_main"=>"position_main","T.name"=>"team_name",
			"T.bild"=>"team_picture","(SELECT COUNT(*)FROM ".Config("db_prefix")."_teamoftheday AS STAT WHERE STAT.season_id=$seasonId AND STAT.player_id=P.id)"=>"memberoftopteam");
		$fromTable=Config("db_prefix")."_teamoftheday AS C INNER JOIN ".Config("db_prefix")."_spieler AS P ON P.id=C.player_id LEFT JOIN ".Config("db_prefix").
			"_verein AS T ON T.id=P.verein_id";
		$whereCondition="C.season_id=%d AND(C.position_main='";
		$whereCondition .= implode("' OR C.position_main='",$mainPositions);
		$whereCondition.="')";
		foreach($players as $foundPlayer)$whereCondition.=" AND  P.id!=".$foundPlayer['player_id'];
		$whereCondition.=" GROUP BY P.id ORDER BY memberoftopteam DESC";
		$result=$this->_db->querySelect($columns,$fromTable,$whereCondition,$seasonId,$limit);
		while($player=$result->fetch_array()){
			$player["player_name"]=(strlen($player["pseudonym"]))? $player["pseudonym"]:$player["firstname"]." ".$player["lastname"];
			$players[]=$player;}}}
class TeamPlayersModel extends Model{
			function Template(){$isNationalTeam=(Request('nationalteam'))?TRUE:FALSE;$players=PlayersDataService::getPlayersOfTeamById($this->_websoccer,$this->_db,$this->_teamid,$isNationalTeam);return['players'=>$players];}
			function View(){$this->_teamid=(int)Request('teamid');return($this->_teamid);}}
class TeamResultsModel extends Model{
			function Template(){$matches=MatchesDataService::getLatestMatchesByTeam($this->_websoccer,$this->_db,$this->_teamId);return['matches'=>$matches];}
			function View(){$this->_teamId=(int)Request('teamid');return$this->_teamId>0;}}
class TeamTransfersModel extends Model{
			function Template(){$teamId=Request('teamid');if($teamId)$transfers=TransfermarketDataService::getCompletedTransfersOfTeam($this->_websoccer,$this->_db,$teamId);return['completedtransfers'=>$transfers];}}
class TermsAndConditionsModel extends Model {
			function Template(){
		$termsFile=$_SERVER['DOCUMENT_ROOT']."/admin/config/termsandconditions.xml";
		if(!file_exists($termsFile))throw new Exception("File does not exist: ".$termsFile);
		$xml=simplexml_load_file($termsFile);
		$termsConfig=$xml->xpath("//pagecontent[@lang='".$this->_i18n->getCurrentLanguage()."'][1]");
		if(!$termsConfig)throw new Exception(Message("termsandconditions_err_notavilable"));
		$terms=(string)$termsConfig[0];
		return array("terms"=> nl2br($terms));}}
class TicketsModel extends Model {
			function Template(){
		$teamId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);
		if($teamId<1)throw new Exception(Message("feature_requires_team"));
		$columns["T.preis_stehen"]="p_stands";
		$columns["T.preis_sitz"]="p_seats";
		$columns["T.preis_haupt_stehen"]="p_stands_grand";
		$columns["T.preis_haupt_sitze"]="p_seats_grand";
		$columns["T.preis_vip"]="p_vip";
		$columns["T.last_steh"]="l_stands";
		$columns["T.last_sitz"]="l_seats";
		$columns["T.last_haupt_steh"]="l_stands_grand";
		$columns["T.last_haupt_sitz"]="l_seats_grand";
		$columns["T.last_vip"]="l_vip";
		$columns["S.p_steh"]="s_stands";
		$columns["S.p_sitz"]="s_seats";
		$columns["S.p_haupt_steh"]="s_stands_grand";
		$columns["S.p_haupt_sitz"]="s_seats_grand";
		$columns["S.p_vip"]="s_vip";
		$fromTable=Config("db_prefix")."_verein AS T LEFT JOIN ".Config("db_prefix")."_stadion AS S ON S.id=T.stadion_id";
		$whereCondition="T.id=%d";
		$parameters=$teamId;
		$result=$this->_db->querySelect($columns,$fromTable,$whereCondition,$parameters);
		$tickets=$result->fetch_array();
		if(Request("p_stands"))$tickets["p_stands"]=Request("p_stands");
		if(Request("p_seats"))$tickets["p_seats"]=Request("p_seats");
		if(Request("p_stands_grand"))$tickets["p_stands_grand"]=Request("p_stands_grand");
		if(Request("p_seats_grand"))$tickets["p_seats_grand"]=Request("p_seats_grand");
		if(Request("p_vip"))$tickets["p_vip"]=Request("p_vip");
		return array("tickets"=>$tickets);}}
class TodaysMatchesModel extends Model{
			function Template(){$matches=[];$paginator=null;$count=MatchesDataService::countTodaysMatches($this->_websoccer,$this->_db);if($count){$paginator=new Paginator($count,Config('entries_per_page'),$this->_websoccer);
				     		$matches=MatchesDataService::getTodaysMatches($this->_websoccer,$this->_db,$paginator->getFirstIndex(),Config('entries_per_page'));}return['matches'=>$matches,'paginator'=>$paginator];}}
class TopNewsListModel extends Model{
			function Template(){$result=$this->_db->querySelect('id,titel,datum',Config('db_prefix').'_news','status=1 ORDER BY datum DESC',[],config('NUMBER_OF_TOP_NEWS'));$articles=[];while($article=$result->fetch_array())$articles[]=['id'=>$article['id'],
							'title'=>$article['titel'],'date'=>FormattedDate($article['datum'])];return['topnews'=>$articles];}}
class TopScorersModel extends Model{
			function Template(){return['players'=>PlayersDataService::getTopScorers($this->_websoccer,$this->_db,config('NUMBER_OF_PLAYERS'),Request('leagueid')),'leagues'=>LeagueDataService::getLeaguesSortedByCountry($this->_websoccer,$this->_db)];}}
class TopStrikersModel extends Model{
			function Template(){return['players'=>PlayersDataService::getTopStrikers($this->_websoccer,$this->_db,config('NUMBER_OF_PLAYERS'),Request("leagueid")),'leagues'=>LeagueDataService::getLeaguesSortedByCountry($this->_websoccer,$this->_db)];}}
class TrainerDetailsModel extends Model{
			function Template(){$trainerId=Request('id');$trainer=TrainingDataService::getTrainerById($this->_websoccer,$this->_db,$trainerId);if(!isset($trainer['id']))throw new Exception('error_page_not_found');return['trainer'=>$trainer];}}
class TrainingCampsDetailsModel extends Model{
			function Template(){$camp=TrainingcampsDataService::getCampById($this->_websoccer,$this->_db,Request('id'));if(!$camp)throw new Exception(Message('error_page_not_found'));$defaultDate=Timestamp()+24*3600;return['camp'=>$camp,
							'defaultDate'=>$defaultDate];}}
class TrainingCampsModel extends Model {
			function Template(){
		$user=$this->_websoccer->getUser();
		$teamId=$user->getClubId($this->_websoccer,$this->_db);
		if($teamId<1)throw new Exception(Message("feature_requires_team"));
		$camps=[];
		$bookedCamp=[];
		$bookedCamps=TrainingcampsDataService::getCampBookingsByTeam($this->_websoccer,$this->_db,$teamId);
		$listCamps=TRUE;
		if(count($bookedCamps)){
			$bookedCamp=$bookedCamps[0];
			if($bookedCamp["date_end"]<Timestamp()){
				TrainingcampsDataService::executeCamp($this->_websoccer,$this->_db,$teamId,$bookedCamp);
				$bookedCamp=[];}
			else $listCamps=FALSE;}
		if($listCamps)$camps=TrainingcampsDataService::getCamps($this->_websoccer,$this->_db);
		return array("bookedCamp"=>$bookedCamp,"camps"=>$camps);}}
class TrainingModel extends Model {
			function Template(){
		$teamId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);
		$lastExecution=TrainingDataService::getLatestTrainingExecutionTime($this->_websoccer,$this->_db,$teamId);
		$unitsCount=TrainingDataService::countRemainingTrainingUnits($this->_websoccer,$this->_db,$teamId);
		$paginator=null;
		$trainers=null;
		$training_unit=TrainingDataService::getValidTrainingUnit($this->_websoccer,$this->_db,$teamId);
		if(!isset($training_unit["id"])){
			$count=TrainingDataService::countTrainers($this->_websoccer,$this->_db);
			$paginator=new Paginator($count,Config('entries_per_page'),$this->_websoccer);
			if($count)$trainers=TrainingDataService::getTrainers($this->_websoccer,$this->_db,$paginator->getFirstIndex(),Config('entries_per_page'));}
		else $training_unit["trainer"]=TrainingDataService::getTrainerById($this->_websoccer,$this->_db,$training_unit["trainer_id"]);
		$trainingEffects=[];
		$contextParameters=$this->_websoccer->getContextParameters();
		if(isset($contextParameters["trainingEffects"]))$trainingEffects=$contextParameters["trainingEffects"];
		return array("unitsCount"=>$unitsCount,"lastExecution"=>$lastExecution,"training_unit"=>$training_unit,"trainers"=>$trainers,"paginator"=>$paginator,"trainingEffects"=>$trainingEffects);}}
class TransferBidModel extends Model {
			function Template(){
		$highestBid=TransfermarketDataService::getHighestBidForPlayer($this->_websoccer,$this->_db,$this->_player["player_id"],$this->_player["transfer_start"],$this->_player["transfer_end"]);
		return array("player"=>$this->_player,"highestbid"=>$highestBid);}
			function View(){
		$playerId=(int)Request("id");
		if($playerId<1)throw new Exception(Message('error_page_not_found'));
		$this->_player=PlayersDataService::getPlayerById($this->_websoccer,$this->_db,$playerId);
		return($this->_player["transfer_end"]>Timestamp());}}
class TransfermarketOverviewModel extends Model {
			function Template(){
		$teamId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);
		if($teamId<1)throw new Exception(Message("feature_requires_team"));
		$positionInput=Request("position");
		$positionFilter=null;
		if($positionInput=="goaly")$positionFilter="Torwart";
		elseif($positionInput=="defense")$positionFilter="Abwehr";
		elseif($positionInput=="midfield")$positionFilter="Mittelfeld";
		elseif($positionInput=="striker")$positionFilter="Sturm";
		$count=PlayersDataService::countPlayersOnTransferList($this->_websoccer,$this->_db,$positionFilter);
		$paginator=new Paginator($count,Config('entries_per_page'),$this->_websoccer);
		if($positionFilter!=null)$paginator->addParameter("position",$positionInput);
		if($count)$players=PlayersDataService::getPlayersOnTransferList($this->_websoccer,$this->_db,$paginator->getFirstIndex(),Config('entries_per_page'),$positionFilter);
		else $players=[];
		return array("transferplayers"=>$players,"playerscount"=>$count,"paginator"=>$paginator);}
			function View(){return(Config('transfermarket_enabled')==1);}}
class TransferOffersModel extends Model {
			function Template(){
		$clubId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);
		$offers=[];
		$countReceivedOffers($this->_websoccer,$this->_db,$clubId);
		$paginator=new Paginator($count,Config('entries_per_page'),$this->_websoccer);
		if($count)$offers=getReceivedOffers($this->_websoccer,$this->_db,$paginator->getFirstIndex(),Config('entries_per_page'),$clubId);
		else $offers=[];
		return array("offers"=>$offers,"paginator"=>$paginator);}
			function View(){return(Config('transferoffers_enabled')==1);}}
class TransferOffersSentModel extends Model {
	  		function Template(){
		$clubId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);
		$userId=$this->_websoccer->getUser()->id;
		$offers=[];
		$count=countSentOffers($this->_websoccer,$this->_db,$clubId,$userId);
		$paginator=new Paginator($count,Config('entries_per_page'),$this->_websoccer);
		$paginator->addParameter("block","directtransfer-sentoffers");
		if($count)$offers=getSentOffers($this->_websoccer,$this->_db,$paginator->getFirstIndex(),Config('entries_per_page'),$clubId,$userId);
		else $offers=[];
		return array("offers"=>$offers,"paginator"=>$paginator);}
			function View(){return(Config('transferoffers_enabled')==1);}}
class UserActivitiesModel extends Model{
			function Template(){return['activities'=>getActionLogsOfUser($this->_websoccer,$this->_db,Request('userid'))];}}
class UserClubsSelectionModel extends Model{
			function Template(){$whereCondition='id=%d';$result=$this->_db->querySelect('id,name',Config('db_prefix').'_verein',"user_id=%d AND status='1'AND nationalteam!='1'ORDER BY name ASC",$this->_websoccer->getUser()->id);$teams=[];
							while($team=$result->fetch_array())$teams[]=$team;return['userteams'=>$teams];}
			function View(){return(strlen($this->_websoccer->getUser()->username))?TRUE:FALSE;}}
class UserDetailsModel extends Model {
			function Template(){
		$userId=(int)Request('id');
		if($userId<1)$userId=$this->_websoccer->getUser()->id;
		$user=UsersDataService::getUserById($this->_websoccer,$this->_db,$userId);
		if(!isset($user['id']))throw new Exception(Message('error_page_not_found'));
		$fromTable=Config('db_prefix').'_verein';
		$whereCondition='user_id=%d AND status=\'1\' AND nationalteam!=\'1\' ORDER BY name ASC';
		$result=$this->_db->querySelect('id,name',$fromTable,$whereCondition,$userId);
		$teams=[];
		while($team=$result->fetch_array())$teams[]=$team;
		if(Config('nationalteams_enabled')){
			$columns='id,name';
			$fromTable=Config('db_prefix').'_verein';
			$whereCondition='user_id=%d AND nationalteam=\'1\'';
			$result=$this->_db->querySelect($columns,$fromTable,$whereCondition,$userId, 1);
			$nationalteam=$result->fetch_array();
			if(isset($nationalteam['id']))$user['nationalteam']=$nationalteam;}
		$result=$this->_db->querySelect('name, description, level, date_rewarded, event',Config('db_prefix').'_badge INNER JOIN '.Config('db_prefix').'_badge_user ON id=badge_id', 'user_id=%d ORDER BY level DESC,
			date_rewarded ASC',$userId);
		$badges=[];
		while($badge=$result->fetch_array()){
			if(!isset($badges[$badge['event']]))$badges[$badge['event']]=$badge;}
		return array('user'=>$user, 'userteams'=>$teams, 'absence'=>getCurrentAbsenceOfUser($this->_websoccer,$this->_db,$userId), 'badges'=>$badges);}}
class UserHistoryModel extends Model {
			function Template(){
		$columns=array('TEAM.id'=>'team_id', 'TEAM.name'=>'team_name', 'L.name'=>'league_name', 'SEASON.name'=>'season_name', 'A.rank'=>'season_rank', 'A.id'=>'achievement_id', 'A.date_recorded'=>'achievement_date', 'CUP.name'=>'cup_name',
			'CUPROUND.name'=>'cup_round_name');
		$tablePrefix=Config('db_prefix');
		$fromTable=$tablePrefix.'_achievement AS A INNER JOIN '.$tablePrefix.'_verein AS TEAM ON TEAM.id=A.team_id LEFT JOIN '.$tablePrefix.'_saison AS SEASON ON SEASON.id=A.season_id LEFT JOIN '.$tablePrefix .
			'_liga AS L ON SEASON.liga_id=L.id LEFT JOIN '.$tablePrefix.'_cup_round AS CUPROUND ON CUPROUND.id=A.cup_round_id LEFT JOIN '.$tablePrefix.'_cup AS CUP ON CUP.id=CUPROUND.cup_id';
		$whereCondition='A.user_id=%d ORDER BY A.date_recorded DESC';
		$result=$this->_db->querySelect($columns,$fromTable,$whereCondition,$this->_userId);
		$leagues=[];
		$cups=[];
		while($achievement=$result->fetch_array()){
			if(strlen($achievement['league_name']))$leagues[$achievement['league_name']][]=$achievement;
			elseif(!isset($cups[$achievement['cup_name']]))$cups[$achievement['cup_name']]=$achievement;
			else $this->_db->queryDelete($tablePrefix.'_achievement', 'id=%d',$achievement['achievement_id']);}
		return array("leagues"=>$leagues,"cups"=>$cups);}
			function View(){$this->_userId=(int)Request('userid');return$this->_userId>0;}}
class UserNickSearchModel extends Model{
			function Template(){$query=Request('query');$users=UsersDataService::findUsernames($this->_websoccer,$this->_db,$query);return['items'=>$users];}}
class UserResultsModel extends Model{
	  		function Template(){$matches=MatchesDataService::getLatestMatchesByUser($this->_websoccer,$this->_db,$this->_userId);return['matches'=>$matches];}
			function View(){$this->_userId=(int)Request('userid');return$this->_userId>0;}}
class UserTransfersModel extends Model{
			function Template(){$userId=Request('userid');if($userId)$transfers=TransfermarketDataService::getCompletedTransfersOfUser($this->_websoccer,$this->_db,$userId);return['completedtransfers'=>$transfers];}}
class WhoIsOnlineModel extends Model{
			function Template(){$count=UsersDataService::countOnlineUsers($this->_websoccer,$this->_db);$paginator=new Paginator($count,Config('entries_per_page'),$this->_websoccer);if($count)$users=UsersDataService::getOnlineUsers($this->_websoccer,
							$this->_db,$paginator->getFirstIndex(),Config('entries_per_page'));else$users=[];return['users'=>$users,'paginator'=>$paginator];}}
class YouthMarketplaceModel extends Model{
			function Template(){$positionFilter=Request('position');$count=YouthPlayersDataService::countTransferableYouthPlayers($this->_websoccer,$this->_db,$positionFilter);$paginator=new Paginator($count,Config('entries_per_page'),$this->_websoccer);
							if($positionFilter!=null)$paginator->addParameter('position',$positionFilter);$players=YouthPlayersDataService::getTransferableYouthPlayers($this->_websoccer,$this->_db,$paginator->getFirstIndex(),Config('entries_per_page'),$positionFilter);
							return['players'=>$players,'paginator'=>$paginator];}
			function View(){return Config('youth_enabled');}}
class YouthMatchesModel extends Model{
			function Template(){$clubId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);$count=YouthMatchesDataService::countMatchesOfTeam($this->_websoccer,$this->_db,$clubId);$paginator=new Paginator($count,Config('entries_per_page'),
							$this->_websoccer);$matches=YouthMatchesDataService::getMatchesOfTeam($this->_websoccer,$this->_db,$clubId,$paginator->getFirstIndex(),Config('entries_per_page'));return['matches'=>$matches,'paginator'=>$paginator];}
			function View(){return Config('youth_enabled');}}
class YouthMatchFormationModel extends Model{
	function Template(){
		$clubId=$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db);
		$matchinfo=YouthMatchesDataService::getYouthMatchinfoById($this->_websoccer,$this->_db,$this->_i18n,Request("matchid"));
		if($matchinfo["home_team_id"]==$clubId)$teamPrefix="home";
		elseif($matchinfo["guest_team_id"]==$clubId)$teamPrefix="guest";
		else throw new Exception(Message('error_page_not_found'));
		if($matchinfo["matchdate"] <=Timestamp()|| $matchinfo["simulated"])throw new Exception(Message("youthformation_err_matchexpired"));
		$players=null;
		if($clubId)$players=YouthPlayersDataService::getYouthPlayersOfTeamByPosition($this->_websoccer,$this->_db,$clubId,"DESC");
		$formation=$this->_getFormation($teamPrefix,$matchinfo);
		for($benchNo=1; $benchNo <= 5;++$benchNo){
			if(Request("bench".$benchNo))$formation["bench".$benchNo]=Request("bench".$benchNo);
			elseif(!isset($formation["bench".$benchNo])){
				$formation["bench".$benchNo]="";}}
		$setup=$this->getFormationSetup($formation);
		for($playerNo=1; $playerNo <= 11;++$playerNo){
			if(Request("player".$playerNo)){
				$formation["player".$playerNo]=Request("player".$playerNo);
				$formation["player".$playerNo."_pos"]=Request("player".$playerNo."_pos");}
			elseif(!isset($formation["player".$playerNo])){
				$formation["player".$playerNo]="";
				$formation["player".$playerNo."_pos"]="";}}
		return array("matchinfo"=>$matchinfo,"players"=>$players,"formation"=>$formation,"setup"=>$setup,"youthFormation"=> TRUE);}
	function getFormationSetup($formation){
		$setup=array("defense"=> 4,"dm"=> 1,"midfield"=> 3,"om"=> 1,"striker"=> 1);
		if(Request("formation_defense")!==NULL){
			$setup["defense"]=(int)Request("formation_defense");
			$setup["dm"]=(int)Request("formation_defensemidfield");
			$setup["midfield"]=(int)Request("formation_midfield");
			$setup["om"]=(int)Request("formation_offensivemidfield");
			$setup["striker"]=(int)Request("formation_forward");}
		elseif(Request("setup")!==NULL){
			$setupParts=explode("-",Request("setup"));
			$setup["defense"]=(int)$setupParts[0];
			$setup["dm"]=(int)$setupParts[1];
			$setup["midfield"]=(int)$setupParts[2];
			$setup["om"]=(int)$setupParts[3];
			$setup["striker"]=(int)$setupParts[4];}
		elseif(isset($formation["setup"])&&strlen($formation["setup"])){
			$setupParts=explode("-",$formation["setup"]);
			$setup["defense"]=(int)$setupParts[0];
			$setup["dm"]=(int)$setupParts[1];
			$setup["midfield"]=(int)$setupParts[2];
			$setup["om"]=(int)$setupParts[3];
			$setup["striker"]=(int)$setupParts[4];}
		$altered=FALSE;
		while(($noOfPlayers=$setup["defense"] + $setup["dm"] + $setup["midfield"] + $setup["om"] + $setup["striker"])!= 10){
			if($noOfPlayers>10){
				if($setup["striker"]>1)$setup["striker"]=$setup["striker"] - 1;
				elseif($setup["om"]>1)$setup["om"]=$setup["om"] - 1;
				elseif($setup["dm"]>1)$setup["dm"]=$setup["dm"] - 1;
				elseif($setup["midfield"]>2)$setup["midfield"]=$setup["midfield"] - 1;
				else $setup["defense"]=$setup["defense"] - 1;}
			else{
				if($setup["defense"]<4)$setup["defense"]=$setup["defense"] + 1;
				elseif($setup["midfield"]<4)$setup["midfield"]=$setup["midfield"] + 1;
				elseif($setup["dm"]<2)$setup["dm"]=$setup["dm"] + 1;
				elseif($setup["om"]<2)$setup["om"]=$setup["om"] + 1;
				else $setup["striker"]=$setup["striker"] + 1;}
			$altered=TRUE;}
		if($altered)$this->_websoccer->addFrontMessage(new FrontMessage('warning',Message("formation_setup_altered_warn_title"),Message("formation_setup_altered_warn_details")));
		return$setup;}
	function _getFormation($teamPrefix,$matchinfo){
		$formation=[];
		for($subNo=1; $subNo <= 3;++$subNo){
			if(Request("sub".$subNo ."_out")){
				$formation["sub".$subNo ."_out"]=Request("sub".$subNo ."_out");
				$formation["sub".$subNo ."_in"]=Request("sub".$subNo ."_in");
				$formation["sub".$subNo ."_minute"]=Request("sub".$subNo ."_minute");
				$formation["sub".$subNo ."_condition"]=Request("sub".$subNo ."_condition");
				$formation["sub".$subNo ."_position"]=Request("sub".$subNo ."_position");}
			else{
				$formation["sub".$subNo ."_out"]=$matchinfo[$teamPrefix."_s".$subNo ."_out"];
				$formation["sub".$subNo ."_in"]=$matchinfo[$teamPrefix."_s".$subNo ."_in"];
				$formation["sub".$subNo ."_minute"]=$matchinfo[$teamPrefix."_s".$subNo ."_minute"];
				$formation["sub".$subNo ."_condition"]=$matchinfo[$teamPrefix."_s".$subNo ."_condition"];
				$formation["sub".$subNo ."_position"]=$matchinfo[$teamPrefix."_s".$subNo ."_position"];}}
		$setup=array("defense"=> 0,"dm"=> 0,"midfield"=> 0,"om"=> 0,"striker"=> 0);
		$result=$this->_db->querySelect("*",Config("db_prefix")."_youthmatch_player","match_id=%d AND team_id=%d",array($matchinfo["id"],$matchinfo[$teamPrefix."_team_id"]));
		while($player=$result->fetch_array()){
			if($player["state"]=="Ersatzbank")$formation["bench".$player["playernumber"]]=$player["player_id"];
			else{
				$formation["player".$player["playernumber"]]=$player["player_id"];
				$formation["player".$player["playernumber"]."_pos"]=$player["position_main"];
				$mainPosition=$player["position_main"];
				$position=$player["position"];
				if($position=="Abwehr")$setup["defense"]=$setup["defense"] + 1;
				elseif($position=="Sturm")$setup["striker"]=$setup["striker"] + 1;
				elseif($position=="Mittelfeld"){
					if($mainPosition=="DM")$setup["dm"]=$setup["dm"] + 1;
					elseif($mainPosition=="OM")$setup["om"]=$setup["om"] + 1;
					else $setup["midfield"]=$setup["midfield"] + 1;}}}
		$setPlayers=$setup["defense"] + $setup["striker"] + $setup["dm"] + $setup["om"] + $setup["midfield"];
		if($setPlayers)$formation["setup"]=$setup["defense"]."-".$setup["dm"]."-".$setup["midfield"]."-".$setup["om"]."-".$setup["striker"];
		return$formation;}}
class YouthMatchReportModel extends Model {
	function Template(){
		$match=YouthMatchesDataService::getYouthMatchinfoById($this->_websoccer,$this->_db,$this->_i18n,Request("id"));
		$players=[];
		$statistics=[];
		$result=$this->_db->querySelect("*",Config("db_prefix")."_youthmatch_player","match_id=%d AND minutes_played>0 ORDER BY playernumber ASC",$match["id"]);
		while($playerinfo=$result->fetch_array()){
			if($playerinfo["team_id"]==$match["home_team_id"])$teamPrefix="home";
			else $teamPrefix="guest";
			if(!isset($statistics[$teamPrefix])){
				$statistics[$teamPrefix]["avg_strength"]=0;
				$statistics[$teamPrefix]["ballcontacts"]=0;
				$statistics[$teamPrefix]["wontackles"]=0;
				$statistics[$teamPrefix]["shoots"]=0;
				$statistics[$teamPrefix]["passes_successed"]=0;
				$statistics[$teamPrefix]["passes_failed"]=0;
				$statistics[$teamPrefix]["assists"]=0;}
			$players[$teamPrefix][]=$playerinfo;
			$statistics[$teamPrefix]["avg_strength"]=$statistics[$teamPrefix]["avg_strength"] + $playerinfo["strength"];
			$statistics[$teamPrefix]["ballcontacts"]=$statistics[$teamPrefix]["ballcontacts"] + $playerinfo["ballcontacts"];
			$statistics[$teamPrefix]["wontackles"]=$statistics[$teamPrefix]["wontackles"] + $playerinfo["wontackles"];
			$statistics[$teamPrefix]["shoots"]=$statistics[$teamPrefix]["shoots"] + $playerinfo["shoots"];
			$statistics[$teamPrefix]["passes_successed"]=$statistics[$teamPrefix]["passes_successed"] + $playerinfo["passes_successed"];
			$statistics[$teamPrefix]["passes_failed"]=$statistics[$teamPrefix]["passes_failed"] + $playerinfo["passes_failed"];
			$statistics[$teamPrefix]["assists"]=$statistics[$teamPrefix]["assists"] + $playerinfo["assists"];}
		if(isset($statistics["guest"]["wontackles"])&&isset($statistics["home"]["wontackles"])){
			$statistics["home"]["losttackles"]=$statistics["guest"]["wontackles"];
			$statistics["guest"]["losttackles"]=$statistics["home"]["wontackles"];}
		if(isset($statistics["guest"]["avg_strength"])&&isset($statistics["home"]["avg_strength"])){
			$statistics["home"]["avg_strength"]=round($statistics["home"]["avg_strength"] / count($players["home"]));
			$statistics["guest"]["avg_strength"]=round($statistics["guest"]["avg_strength"] / count($players["guest"]));}
		if(isset($statistics["guest"]["ballcontacts"])&&isset($statistics["home"]["ballcontacts"])){
			$statistics["home"]["ballpossession"]=round($statistics["home"]["ballcontacts"]*100 /($statistics["home"]["ballcontacts"] + $statistics["guest"]["ballcontacts"]));
			$statistics["guest"]["ballpossession"]=round($statistics["guest"]["ballcontacts"]*100 /($statistics["home"]["ballcontacts"] + $statistics["guest"]["ballcontacts"]));}
		$reportMessages=YouthMatchesDataService::getMatchReportItems($this->_websoccer,$this->_db,$this->_i18n,$match["id"]);
		return array("match"=>$match,"players"=>$players,"statistics"=>$statistics,"reportMessages"=>$reportMessages);}
	function View(){ return Config("youth_enabled");}}
class YouthMatchRequestsCreateModel extends Model{
			function Template(){$timeOptions=[];$maxDays=Config('youth_matchrequest_max_futuredays');$times=explode(',',Config('youth_matchrequest_allowedtimes'));$validTimes=[];foreach($times as$time)$validTimes[]=explode(':',$time);$dateOptions=[];
							$dateObj=new DateTime();$dateFormat=Config('datetime_format');for($day=1;$day<=$maxDays;++$day){$dateObj->add(new DateInterval('P1D'));foreach($validTimes as$validTime){$hour=$validTime[0];$minute=$validTime[1];
							$dateObj->setTime($hour,$minute);$dateOptions[$dateObj->Timestamp()]=$dateObj->format($dateFormat);}}return['dateOptions'=>$dateOptions];}
			function View(){return Config('youth_enabled');}}
class YouthMatchRequestsModel extends Model {
			function Template(){YouthPlayersDataService::deleteInvalidOpenMatchRequests($this->_websoccer,$this->_db);$count=YouthPlayersDataService::countMatchRequests($this->_websoccer,$this->_db);$paginator=new Paginator($count,Config('entries_per_page'),
							$this->_websoccer);$requests=YouthPlayersDataService::getMatchRequests($this->_websoccer,$this->_db,$paginator->getFirstIndex(),Config('entries_per_page'));return['requests'=>$requests,'paginator'=>$paginator];}
			function View(){return Config('youth_enabled')&&Config('youth_matchrequests_enabled');}}
class YouthPlayerDetailsModel extends Model{
			function Template(){$playerId=(int)Request('id');if($playerId<1)throw new Exception(Message('error_page_not_found'));$player=YouthPlayersDataService::getYouthPlayerById($this->_websoccer,$this->_db,$this->_i18n,$playerId);
							return['player'=>$player];}
			function View(){return Config('youth_enabled');}}
class YouthPlayersOfTeamModel extends Model{
			function Template(){$teamId=Request('teamid');$players=[];if($teamId)$players=YouthPlayersDataService::getYouthPlayersOfTeam($this->_websoccer,$this->_db,$teamId);return['players'=>$players];}
			function View(){return Config('youth_enabled');}}
class YouthScoutingModel extends Model{
			function Template(){$lastExecutionTimestamp=YouthPlayersDataService::getLastScoutingExecutionTime($this->_websoccer,$this->_db,$this->_websoccer->getUser()->getClubId($this->_websoccer,$this->_db));$nextPossibleExecutionTimestamp=
							$lastExecutionTimestamp+Config('youth_scouting_break_hours')*3600;$now=Timestamp();$scouts=[];$countries=[];$scoutingPossible=($nextPossibleExecutionTimestamp<=$now);if($scoutingPossible){$scoutId=(int)Request('scoutid');
							if($scoutId)$countries=YouthPlayersDataService::getPossibleScoutingCountries();else$scouts=YouthPlayersDataService::getScouts($this->_websoccer,$this->_db);}return['lastExecutionTimestamp'=>$lastExecutionTimestamp,
							'nextPossibleExecutionTimestamp'=>$nextPossibleExecutionTimestamp,'scoutingPossible'=>$scoutingPossible,'scouts'=>$scouts,'countries'=>$countries];}
			function View(){return Config('youth_enabled')&&Config('youth_scouting_enabled');}}
function Bootstrap_css(){?><style>
/*!
 * Bootstrap v2.3.2
 *
 * Copyright 2013 Twitter, Inc
 * Licensed under the Apache License v2.0
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Designed and built with all the love in the world by @mdo and @fat.
 */.clearfix{*zoom:1}.clearfix:before,.clearfix:after{display:table;line-height:0;content:""}.clearfix:after{clear:both}.hide-text{font:0/0 a;color:transparent;text-shadow:none;background-color:transparent;border:0}.input-block-level{display:block;width:100%;min-height:30px;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}article,aside,details,figcaption,figure,footer,header,hgroup,nav,section{display:block}audio,canvas,video{display:inline-block;*display:inline;*zoom:1}audio:not([controls]){display:none}html{font-size:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}a:focus{outline:thin dotted #333;outline:5px auto -webkit-focus-ring-color;outline-offset:-2px}a:hover,a:active{outline:0}sub,sup{position:relative;font-size:75%;line-height:0;vertical-align:baseline}sup{top:-0.5em}sub{bottom:-0.25em}img{width:auto\9;height:auto;max-width:100%;vertical-align:middle;border:0;-ms-interpolation-mode:bicubic}#map_canvas img,.google-maps img{max-width:none}button,input,select,textarea{margin:0;font-size:100%;vertical-align:middle}button,input{*overflow:visible;line-height:normal}button::-moz-focus-inner,input::-moz-focus-inner{padding:0;border:0}button,html input[type="button"],input[type="reset"],input[type="submit"]{cursor:pointer;-webkit-appearance:button}label,select,button,input[type="button"],input[type="reset"],input[type="submit"],input[type="radio"],input[type="checkbox"]{cursor:pointer}input[type="search"]{-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;-webkit-appearance:textfield}input[type="search"]::-webkit-search-decoration,input[type="search"]::-webkit-search-cancel-button{-webkit-appearance:none}textarea{overflow:auto;vertical-align:top}@media print{*{color:#000!important;text-shadow:none!important;background:transparent!important;box-shadow:none!important}a,a:visited{text-decoration:underline}a[href]:after{content:" (" attr(href) ")"}abbr[title]:after{content:" (" attr(title) ")"}.ir a:after,a[href^="javascript:"]:after,a[href^="#"]:after{content:""}pre,blockquote{border:1px solid #999;page-break-inside:avoid}thead{display:table-header-group}tr,img{page-break-inside:avoid}img{max-width:100%!important}@page{margin:.5cm}p,h2,h3{orphans:3;widows:3}h2,h3{page-break-after:avoid}}body{margin:0;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;font-size:14px;line-height:20px;color:#333;background-color:#fff}a{color:#08c;text-decoration:none}a:hover,a:focus{color:#005580;text-decoration:underline}.img-rounded{-webkit-border-radius:6px;-moz-border-radius:6px;border-radius:6px}.img-polaroid{padding:4px;background-color:#fff;border:1px solid #ccc;border:1px solid rgba(0,0,0,0.2);-webkit-box-shadow:0 1px 3px rgba(0,0,0,0.1);-moz-box-shadow:0 1px 3px rgba(0,0,0,0.1);box-shadow:0 1px 3px rgba(0,0,0,0.1)}.img-circle{-webkit-border-radius:500px;-moz-border-radius:500px;border-radius:500px}.row{margin-left:-20px;*zoom:1}.row:before,.row:after{display:table;line-height:0;content:""}.row:after{clear:both}[class*="span"]{float:left;min-height:1px;margin-left:20px}.container,.navbar-static-top .container,.navbar-fixed-top .container,.navbar-fixed-bottom .container{width:940px}.span12{width:940px}.span11{width:860px}.span10{width:780px}.span9{width:700px}.span8{width:620px}.span7{width:540px}.span6{width:460px}.span5{width:380px}.span4{width:300px}.span3{width:220px}.span2{width:140px}.span1{width:60px}.offset12{margin-left:980px}.offset11{margin-left:900px}.offset10{margin-left:820px}.offset9{margin-left:740px}.offset8{margin-left:660px}.offset7{margin-left:580px}.offset6{margin-left:500px}.offset5{margin-left:420px}.offset4{margin-left:340px}.offset3{margin-left:260px}.offset2{margin-left:180px}.offset1{margin-left:100px}.row-fluid{width:100%;*zoom:1}.row-fluid:before,.row-fluid:after{display:table;line-height:0;content:""}.row-fluid:after{clear:both}.row-fluid [class*="span"]{display:block;float:left;width:100%;min-height:30px;margin-left:2.127659574468085%;*margin-left:2.074468085106383%;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}.row-fluid [class*="span"]:first-child{margin-left:0}.row-fluid .controls-row [class*="span"]+[class*="span"]{margin-left:2.127659574468085%}.row-fluid .span12{width:100%;*width:99.94680851063829%}.row-fluid .span11{width:91.48936170212765%;*width:91.43617021276594%}.row-fluid .span10{width:82.97872340425532%;*width:82.92553191489361%}.row-fluid .span9{width:74.46808510638297%;*width:74.41489361702126%}.row-fluid .span8{width:65.95744680851064%;*width:65.90425531914893%}.row-fluid .span7{width:57.44680851063829%;*width:57.39361702127659%}.row-fluid .span6{width:48.93617021276595%;*width:48.88297872340425%}.row-fluid .span5{width:40.42553191489362%;*width:40.37234042553192%}.row-fluid .span4{width:31.914893617021278%;*width:31.861702127659576%}.row-fluid .span3{width:23.404255319148934%;*width:23.351063829787233%}.row-fluid .span2{width:14.893617021276595%;*width:14.840425531914894%}.row-fluid .span1{width:6.382978723404255%;*width:6.329787234042553%}.row-fluid .offset12{margin-left:104.25531914893617%;*margin-left:104.14893617021275%}.row-fluid .offset12:first-child{margin-left:102.12765957446808%;*margin-left:102.02127659574467%}.row-fluid .offset11{margin-left:95.74468085106382%;*margin-left:95.6382978723404%}.row-fluid .offset11:first-child{margin-left:93.61702127659574%;*margin-left:93.51063829787232%}.row-fluid .offset10{margin-left:87.23404255319149%;*margin-left:87.12765957446807%}.row-fluid .offset10:first-child{margin-left:85.1063829787234%;*margin-left:84.99999999999999%}.row-fluid .offset9{margin-left:78.72340425531914%;*margin-left:78.61702127659572%}.row-fluid .offset9:first-child{margin-left:76.59574468085106%;*margin-left:76.48936170212764%}.row-fluid .offset8{margin-left:70.2127659574468%;*margin-left:70.10638297872339%}.row-fluid .offset8:first-child{margin-left:68.08510638297872%;*margin-left:67.9787234042553%}.row-fluid .offset7{margin-left:61.70212765957446%;*margin-left:61.59574468085106%}.row-fluid .offset7:first-child{margin-left:59.574468085106375%;*margin-left:59.46808510638297%}.row-fluid .offset6{margin-left:53.191489361702125%;*margin-left:53.085106382978715%}.row-fluid .offset6:first-child{margin-left:51.063829787234035%;*margin-left:50.95744680851063%}.row-fluid .offset5{margin-left:44.68085106382979%;*margin-left:44.57446808510638%}.row-fluid .offset5:first-child{margin-left:42.5531914893617%;*margin-left:42.4468085106383%}.row-fluid .offset4{margin-left:36.170212765957444%;*margin-left:36.06382978723405%}.row-fluid .offset4:first-child{margin-left:34.04255319148936%;*margin-left:33.93617021276596%}.row-fluid .offset3{margin-left:27.659574468085104%;*margin-left:27.5531914893617%}.row-fluid .offset3:first-child{margin-left:25.53191489361702%;*margin-left:25.425531914893618%}.row-fluid .offset2{margin-left:19.148936170212764%;*margin-left:19.04255319148936%}.row-fluid .offset2:first-child{margin-left:17.02127659574468%;*margin-left:16.914893617021278%}.row-fluid .offset1{margin-left:10.638297872340425%;*margin-left:10.53191489361702%}.row-fluid .offset1:first-child{margin-left:8.51063829787234%;*margin-left:8.404255319148938%}[class*="span"].hide,.row-fluid [class*="span"].hide{display:none}[class*="span"].pull-right,.row-fluid [class*="span"].pull-right{float:right}.container{margin-right:auto;margin-left:auto;*zoom:1}.container:before,.container:after{display:table;line-height:0;content:""}.container:after{clear:both}.container-fluid{padding-right:20px;padding-left:20px;*zoom:1}.container-fluid:before,.container-fluid:after{display:table;line-height:0;content:""}.container-fluid:after{clear:both}p{margin:0 0 10px}.lead{margin-bottom:20px;font-size:21px;font-weight:200;line-height:30px}small{font-size:85%}strong{font-weight:bold}em{font-style:italic}cite{font-style:normal}.muted{color:#999}a.muted:hover,a.muted:focus{color:#808080}.text-warning{color:#c09853}a.text-warning:hover,a.text-warning:focus{color:#a47e3c}.text-error{color:#b94a48}a.text-error:hover,a.text-error:focus{color:#953b39}.text-info{color:#3a87ad}a.text-info:hover,a.text-info:focus{color:#2d6987}.text-success{color:#468847}a.text-success:hover,a.text-success:focus{color:#356635}.text-left{text-align:left}.text-right{text-align:right}.text-center{text-align:center}h1,h2,h3,h4,h5,h6{margin:10px 0;font-family:inherit;font-weight:bold;line-height:20px;color:inherit;text-rendering:optimizelegibility}h1 small,h2 small,h3 small,h4 small,h5 small,h6 small{font-weight:normal;line-height:1;color:#999}h1,h2,h3{line-height:40px}h1{font-size:38.5px}h2{font-size:31.5px}h3{font-size:24.5px}h4{font-size:17.5px}h5{font-size:14px}h6{font-size:11.9px}h1 small{font-size:24.5px}h2 small{font-size:17.5px}h3 small{font-size:14px}h4 small{font-size:14px}.page-header{padding-bottom:9px;margin:20px 0 30px;border-bottom:1px solid #eee}ul,ol{padding:0;margin:0 0 10px 25px}ul ul,ul ol,ol ol,ol ul{margin-bottom:0}li{line-height:20px}ul.unstyled,ol.unstyled{margin-left:0;list-style:none}ul.inline,ol.inline{margin-left:0;list-style:none}ul.inline>li,ol.inline>li{display:inline-block;*display:inline;padding-right:5px;padding-left:5px;*zoom:1}dl{margin-bottom:20px}dt,dd{line-height:20px}dt{font-weight:bold}dd{margin-left:10px}.dl-horizontal{*zoom:1}.dl-horizontal:before,.dl-horizontal:after{display:table;line-height:0;content:""}.dl-horizontal:after{clear:both}.dl-horizontal dt{float:left;width:160px;overflow:hidden;clear:left;text-align:right;text-overflow:ellipsis;white-space:nowrap}.dl-horizontal dd{margin-left:180px}hr{margin:20px 0;border:0;border-top:1px solid #eee;border-bottom:1px solid #fff}abbr[title],abbr[data-original-title]{cursor:help;border-bottom:1px dotted #999}abbr.initialism{font-size:90%;text-transform:uppercase}blockquote{padding:0 0 0 15px;margin:0 0 20px;border-left:5px solid #eee}blockquote p{margin-bottom:0;font-size:17.5px;font-weight:300;line-height:1.25}blockquote small{display:block;line-height:20px;color:#999}blockquote small:before{content:'\2014 \00A0'}blockquote.pull-right{float:right;padding-right:15px;padding-left:0;border-right:5px solid #eee;border-left:0}blockquote.pull-right p,blockquote.pull-right small{text-align:right}blockquote.pull-right small:before{content:''}blockquote.pull-right small:after{content:'\00A0 \2014'}q:before,q:after,blockquote:before,blockquote:after{content:""}address{display:block;margin-bottom:20px;font-style:normal;line-height:20px}code,pre{padding:0 3px 2px;font-family:Monaco,Menlo,Consolas,"Courier New",monospace;font-size:12px;color:#333;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px}code{padding:2px 4px;color:#d14;white-space:nowrap;background-color:#f7f7f9;border:1px solid #e1e1e8}pre{display:block;padding:9.5px;margin:0 0 10px;font-size:13px;line-height:20px;word-break:break-all;word-wrap:break-word;white-space:pre;white-space:pre-wrap;background-color:#f5f5f5;border:1px solid #ccc;border:1px solid rgba(0,0,0,0.15);-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px}pre.prettyprint{margin-bottom:20px}pre code{padding:0;color:inherit;white-space:pre;white-space:pre-wrap;background-color:transparent;border:0}.pre-scrollable{max-height:340px;overflow-y:scroll}form{margin:0 0 20px}fieldset{padding:0;margin:0;border:0}legend{display:block;width:100%;padding:0;margin-bottom:20px;font-size:21px;line-height:40px;color:#333;border:0;border-bottom:1px solid #e5e5e5}legend small{font-size:15px;color:#999}label,input,button,select,textarea{font-size:14px;font-weight:normal;line-height:20px}input,button,select,textarea{font-family:"Helvetica Neue",Helvetica,Arial,sans-serif}label{display:block;margin-bottom:5px}select,textarea,input[type="text"],input[type="password"],input[type="datetime"],input[type="datetime-local"],input[type="date"],input[type="month"],input[type="time"],input[type="week"],input[type="number"],input[type="email"],input[type="url"],input[type="search"],input[type="tel"],input[type="color"],.uneditable-input{display:inline-block;height:20px;padding:4px 6px;margin-bottom:10px;font-size:14px;line-height:20px;color:#555;vertical-align:middle;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px}input,textarea,.uneditable-input{width:206px}textarea{height:auto}textarea,input[type="text"],input[type="password"],input[type="datetime"],input[type="datetime-local"],input[type="date"],input[type="month"],input[type="time"],input[type="week"],input[type="number"],input[type="email"],input[type="url"],input[type="search"],input[type="tel"],input[type="color"],.uneditable-input{background-color:#fff;border:1px solid #ccc;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,0.075);-moz-box-shadow:inset 0 1px 1px rgba(0,0,0,0.075);box-shadow:inset 0 1px 1px rgba(0,0,0,0.075);-webkit-transition:border linear .2s,box-shadow linear .2s;-moz-transition:border linear .2s,box-shadow linear .2s;-o-transition:border linear .2s,box-shadow linear .2s;transition:border linear .2s,box-shadow linear .2s}textarea:focus,input[type="text"]:focus,input[type="password"]:focus,input[type="datetime"]:focus,input[type="datetime-local"]:focus,input[type="date"]:focus,input[type="month"]:focus,input[type="time"]:focus,input[type="week"]:focus,input[type="number"]:focus,input[type="email"]:focus,input[type="url"]:focus,input[type="search"]:focus,input[type="tel"]:focus,input[type="color"]:focus,.uneditable-input:focus{border-color:rgba(82,168,236,0.8);outline:0;outline:thin dotted \9;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,0.075),0 0 8px rgba(82,168,236,0.6);-moz-box-shadow:inset 0 1px 1px rgba(0,0,0,0.075),0 0 8px rgba(82,168,236,0.6);box-shadow:inset 0 1px 1px rgba(0,0,0,0.075),0 0 8px rgba(82,168,236,0.6)}input[type="radio"],input[type="checkbox"]{margin:4px 0 0;margin-top:1px \9;*margin-top:0;line-height:normal}input[type="file"],input[type="image"],input[type="submit"],input[type="reset"],input[type="button"],input[type="radio"],input[type="checkbox"]{width:auto}select,input[type="file"]{height:30px;*margin-top:4px;line-height:30px}select{width:220px;background-color:#fff;border:1px solid #ccc}select[multiple],select[size]{height:auto}select:focus,input[type="file"]:focus,input[type="radio"]:focus,input[type="checkbox"]:focus{outline:thin dotted #333;outline:5px auto -webkit-focus-ring-color;outline-offset:-2px}.uneditable-input,.uneditable-textarea{color:#999;cursor:not-allowed;background-color:#fcfcfc;border-color:#ccc;-webkit-box-shadow:inset 0 1px 2px rgba(0,0,0,0.025);-moz-box-shadow:inset 0 1px 2px rgba(0,0,0,0.025);box-shadow:inset 0 1px 2px rgba(0,0,0,0.025)}.uneditable-input{overflow:hidden;white-space:nowrap}.uneditable-textarea{width:auto;height:auto}input:-moz-placeholder,textarea:-moz-placeholder{color:#999}input:-ms-input-placeholder,textarea:-ms-input-placeholder{color:#999}input::-webkit-input-placeholder,textarea::-webkit-input-placeholder{color:#999}.radio,.checkbox{min-height:20px;padding-left:20px}.radio input[type="radio"],.checkbox input[type="checkbox"]{float:left;margin-left:-20px}.controls>.radio:first-child,.controls>.checkbox:first-child{padding-top:5px}.radio.inline,.checkbox.inline{display:inline-block;padding-top:5px;margin-bottom:0;vertical-align:middle}.radio.inline+.radio.inline,.checkbox.inline+.checkbox.inline{margin-left:10px}.input-mini{width:60px}.input-small{width:90px}.input-medium{width:150px}.input-large{width:210px}.input-xlarge{width:270px}.input-xxlarge{width:530px}input[class*="span"],select[class*="span"],textarea[class*="span"],.uneditable-input[class*="span"],.row-fluid input[class*="span"],.row-fluid select[class*="span"],.row-fluid textarea[class*="span"],.row-fluid .uneditable-input[class*="span"]{float:none;margin-left:0}.input-append input[class*="span"],.input-append .uneditable-input[class*="span"],.input-prepend input[class*="span"],.input-prepend .uneditable-input[class*="span"],.row-fluid input[class*="span"],.row-fluid select[class*="span"],.row-fluid textarea[class*="span"],.row-fluid .uneditable-input[class*="span"],.row-fluid .input-prepend [class*="span"],.row-fluid .input-append [class*="span"]{display:inline-block}input,textarea,.uneditable-input{margin-left:0}.controls-row [class*="span"]+[class*="span"]{margin-left:20px}input.span12,textarea.span12,.uneditable-input.span12{width:926px}input.span11,textarea.span11,.uneditable-input.span11{width:846px}input.span10,textarea.span10,.uneditable-input.span10{width:766px}input.span9,textarea.span9,.uneditable-input.span9{width:686px}input.span8,textarea.span8,.uneditable-input.span8{width:606px}input.span7,textarea.span7,.uneditable-input.span7{width:526px}input.span6,textarea.span6,.uneditable-input.span6{width:446px}input.span5,textarea.span5,.uneditable-input.span5{width:366px}input.span4,textarea.span4,.uneditable-input.span4{width:286px}input.span3,textarea.span3,.uneditable-input.span3{width:206px}input.span2,textarea.span2,.uneditable-input.span2{width:126px}input.span1,textarea.span1,.uneditable-input.span1{width:46px}.controls-row{*zoom:1}.controls-row:before,.controls-row:after{display:table;line-height:0;content:""}.controls-row:after{clear:both}.controls-row [class*="span"],.row-fluid .controls-row [class*="span"]{float:left}.controls-row .checkbox[class*="span"],.controls-row .radio[class*="span"]{padding-top:5px}input[disabled],select[disabled],textarea[disabled],input[readonly],select[readonly],textarea[readonly]{cursor:not-allowed;background-color:#eee}input[type="radio"][disabled],input[type="checkbox"][disabled],input[type="radio"][readonly],input[type="checkbox"][readonly]{background-color:transparent}.control-group.warning .control-label,.control-group.warning .help-block,.control-group.warning .help-inline{color:#c09853}.control-group.warning .checkbox,.control-group.warning .radio,.control-group.warning input,.control-group.warning select,.control-group.warning textarea{color:#c09853}.control-group.warning input,.control-group.warning select,.control-group.warning textarea{border-color:#c09853;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,0.075);-moz-box-shadow:inset 0 1px 1px rgba(0,0,0,0.075);box-shadow:inset 0 1px 1px rgba(0,0,0,0.075)}.control-group.warning input:focus,.control-group.warning select:focus,.control-group.warning textarea:focus{border-color:#a47e3c;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,0.075),0 0 6px #dbc59e;-moz-box-shadow:inset 0 1px 1px rgba(0,0,0,0.075),0 0 6px #dbc59e;box-shadow:inset 0 1px 1px rgba(0,0,0,0.075),0 0 6px #dbc59e}.control-group.warning .input-prepend .add-on,.control-group.warning .input-append .add-on{color:#c09853;background-color:#fcf8e3;border-color:#c09853}.control-group.error .control-label,.control-group.error .help-block,.control-group.error .help-inline{color:#b94a48}.control-group.error .checkbox,.control-group.error .radio,.control-group.error input,.control-group.error select,.control-group.error textarea{color:#b94a48}.control-group.error input,.control-group.error select,.control-group.error textarea{border-color:#b94a48;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,0.075);-moz-box-shadow:inset 0 1px 1px rgba(0,0,0,0.075);box-shadow:inset 0 1px 1px rgba(0,0,0,0.075)}.control-group.error input:focus,.control-group.error select:focus,.control-group.error textarea:focus{border-color:#953b39;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,0.075),0 0 6px #d59392;-moz-box-shadow:inset 0 1px 1px rgba(0,0,0,0.075),0 0 6px #d59392;box-shadow:inset 0 1px 1px rgba(0,0,0,0.075),0 0 6px #d59392}.control-group.error .input-prepend .add-on,.control-group.error .input-append .add-on{color:#b94a48;background-color:#f2dede;border-color:#b94a48}.control-group.success .control-label,.control-group.success .help-block,.control-group.success .help-inline{color:#468847}.control-group.success .checkbox,.control-group.success .radio,.control-group.success input,.control-group.success select,.control-group.success textarea{color:#468847}.control-group.success input,.control-group.success select,.control-group.success textarea{border-color:#468847;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,0.075);-moz-box-shadow:inset 0 1px 1px rgba(0,0,0,0.075);box-shadow:inset 0 1px 1px rgba(0,0,0,0.075)}.control-group.success input:focus,.control-group.success select:focus,.control-group.success textarea:focus{border-color:#356635;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,0.075),0 0 6px #7aba7b;-moz-box-shadow:inset 0 1px 1px rgba(0,0,0,0.075),0 0 6px #7aba7b;box-shadow:inset 0 1px 1px rgba(0,0,0,0.075),0 0 6px #7aba7b}.control-group.success .input-prepend .add-on,.control-group.success .input-append .add-on{color:#468847;background-color:#dff0d8;border-color:#468847}.control-group.info .control-label,.control-group.info .help-block,.control-group.info .help-inline{color:#3a87ad}.control-group.info .checkbox,.control-group.info .radio,.control-group.info input,.control-group.info select,.control-group.info textarea{color:#3a87ad}.control-group.info input,.control-group.info select,.control-group.info textarea{border-color:#3a87ad;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,0.075);-moz-box-shadow:inset 0 1px 1px rgba(0,0,0,0.075);box-shadow:inset 0 1px 1px rgba(0,0,0,0.075)}.control-group.info input:focus,.control-group.info select:focus,.control-group.info textarea:focus{border-color:#2d6987;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,0.075),0 0 6px #7ab5d3;-moz-box-shadow:inset 0 1px 1px rgba(0,0,0,0.075),0 0 6px #7ab5d3;box-shadow:inset 0 1px 1px rgba(0,0,0,0.075),0 0 6px #7ab5d3}.control-group.info .input-prepend .add-on,.control-group.info .input-append .add-on{color:#3a87ad;background-color:#d9edf7;border-color:#3a87ad}input:focus:invalid,textarea:focus:invalid,select:focus:invalid{color:#b94a48;border-color:#ee5f5b}input:focus:invalid:focus,textarea:focus:invalid:focus,select:focus:invalid:focus{border-color:#e9322d;-webkit-box-shadow:0 0 6px #f8b9b7;-moz-box-shadow:0 0 6px #f8b9b7;box-shadow:0 0 6px #f8b9b7}.form-actions{padding:19px 20px 20px;margin-top:20px;margin-bottom:20px;background-color:#f5f5f5;border-top:1px solid #e5e5e5;*zoom:1}.form-actions:before,.form-actions:after{display:table;line-height:0;content:""}.form-actions:after{clear:both}.help-block,.help-inline{color:#595959}.help-block{display:block;margin-bottom:10px}.help-inline{display:inline-block;*display:inline;padding-left:5px;vertical-align:middle;*zoom:1}.input-append,.input-prepend{display:inline-block;margin-bottom:10px;font-size:0;white-space:nowrap;vertical-align:middle}.input-append input,.input-prepend input,.input-append select,.input-prepend select,.input-append .uneditable-input,.input-prepend .uneditable-input,.input-append .dropdown-menu,.input-prepend .dropdown-menu,.input-append .popover,.input-prepend .popover{font-size:14px}.input-append input,.input-prepend input,.input-append select,.input-prepend select,.input-append .uneditable-input,.input-prepend .uneditable-input{position:relative;margin-bottom:0;*margin-left:0;vertical-align:top;-webkit-border-radius:0 4px 4px 0;-moz-border-radius:0 4px 4px 0;border-radius:0 4px 4px 0}.input-append input:focus,.input-prepend input:focus,.input-append select:focus,.input-prepend select:focus,.input-append .uneditable-input:focus,.input-prepend .uneditable-input:focus{z-index:2}.input-append .add-on,.input-prepend .add-on{display:inline-block;width:auto;height:20px;min-width:16px;padding:4px 5px;font-size:14px;font-weight:normal;line-height:20px;text-align:center;text-shadow:0 1px 0 #fff;background-color:#eee;border:1px solid #ccc}.input-append .add-on,.input-prepend .add-on,.input-append .btn,.input-prepend .btn,.input-append .btn-group>.dropdown-toggle,.input-prepend .btn-group>.dropdown-toggle{vertical-align:top;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0}.input-append .active,.input-prepend .active{background-color:#a9dba9;border-color:#46a546}.input-prepend .add-on,.input-prepend .btn{margin-right:-1px}.input-prepend .add-on:first-child,.input-prepend .btn:first-child{-webkit-border-radius:4px 0 0 4px;-moz-border-radius:4px 0 0 4px;border-radius:4px 0 0 4px}.input-append input,.input-append select,.input-append .uneditable-input{-webkit-border-radius:4px 0 0 4px;-moz-border-radius:4px 0 0 4px;border-radius:4px 0 0 4px}.input-append input+.btn-group .btn:last-child,.input-append select+.btn-group .btn:last-child,.input-append .uneditable-input+.btn-group .btn:last-child{-webkit-border-radius:0 4px 4px 0;-moz-border-radius:0 4px 4px 0;border-radius:0 4px 4px 0}.input-append .add-on,.input-append .btn,.input-append .btn-group{margin-left:-1px}.input-append .add-on:last-child,.input-append .btn:last-child,.input-append .btn-group:last-child>.dropdown-toggle{-webkit-border-radius:0 4px 4px 0;-moz-border-radius:0 4px 4px 0;border-radius:0 4px 4px 0}.input-prepend.input-append input,.input-prepend.input-append select,.input-prepend.input-append .uneditable-input{-webkit-border-radius:0;-moz-border-radius:0;border-radius:0}.input-prepend.input-append input+.btn-group .btn,.input-prepend.input-append select+.btn-group .btn,.input-prepend.input-append .uneditable-input+.btn-group .btn{-webkit-border-radius:0 4px 4px 0;-moz-border-radius:0 4px 4px 0;border-radius:0 4px 4px 0}.input-prepend.input-append .add-on:first-child,.input-prepend.input-append .btn:first-child{margin-right:-1px;-webkit-border-radius:4px 0 0 4px;-moz-border-radius:4px 0 0 4px;border-radius:4px 0 0 4px}.input-prepend.input-append .add-on:last-child,.input-prepend.input-append .btn:last-child{margin-left:-1px;-webkit-border-radius:0 4px 4px 0;-moz-border-radius:0 4px 4px 0;border-radius:0 4px 4px 0}.input-prepend.input-append .btn-group:first-child{margin-left:0}input.search-query{padding-right:14px;padding-right:4px \9;padding-left:14px;padding-left:4px \9;margin-bottom:0;-webkit-border-radius:15px;-moz-border-radius:15px;border-radius:15px}.form-search .input-append .search-query,.form-search .input-prepend .search-query{-webkit-border-radius:0;-moz-border-radius:0;border-radius:0}.form-search .input-append .search-query{-webkit-border-radius:14px 0 0 14px;-moz-border-radius:14px 0 0 14px;border-radius:14px 0 0 14px}.form-search .input-append .btn{-webkit-border-radius:0 14px 14px 0;-moz-border-radius:0 14px 14px 0;border-radius:0 14px 14px 0}.form-search .input-prepend .search-query{-webkit-border-radius:0 14px 14px 0;-moz-border-radius:0 14px 14px 0;border-radius:0 14px 14px 0}.form-search .input-prepend .btn{-webkit-border-radius:14px 0 0 14px;-moz-border-radius:14px 0 0 14px;border-radius:14px 0 0 14px}.form-search input,.form-inline input,.form-horizontal input,.form-search textarea,.form-inline textarea,.form-horizontal textarea,.form-search select,.form-inline select,.form-horizontal select,.form-search .help-inline,.form-inline .help-inline,.form-horizontal .help-inline,.form-search .uneditable-input,.form-inline .uneditable-input,.form-horizontal .uneditable-input,.form-search .input-prepend,.form-inline .input-prepend,.form-horizontal .input-prepend,.form-search .input-append,.form-inline .input-append,.form-horizontal .input-append{display:inline-block;*display:inline;margin-bottom:0;vertical-align:middle;*zoom:1}.form-search .hide,.form-inline .hide,.form-horizontal .hide{display:none}.form-search label,.form-inline label,.form-search .btn-group,.form-inline .btn-group{display:inline-block}.form-search .input-append,.form-inline .input-append,.form-search .input-prepend,.form-inline .input-prepend{margin-bottom:0}.form-search .radio,.form-search .checkbox,.form-inline .radio,.form-inline .checkbox{padding-left:0;margin-bottom:0;vertical-align:middle}.form-search .radio input[type="radio"],.form-search .checkbox input[type="checkbox"],.form-inline .radio input[type="radio"],.form-inline .checkbox input[type="checkbox"]{float:left;margin-right:3px;margin-left:0}.control-group{margin-bottom:10px}legend+.control-group{margin-top:20px;-webkit-margin-top-collapse:separate}.form-horizontal .control-group{margin-bottom:20px;*zoom:1}.form-horizontal .control-group:before,.form-horizontal .control-group:after{display:table;line-height:0;content:""}.form-horizontal .control-group:after{clear:both}.form-horizontal .control-label{float:left;width:160px;padding-top:5px;text-align:right}.form-horizontal .controls{*display:inline-block;*padding-left:20px;margin-left:180px;*margin-left:0}.form-horizontal .controls:first-child{*padding-left:180px}.form-horizontal .help-block{margin-bottom:0}.form-horizontal input+.help-block,.form-horizontal select+.help-block,.form-horizontal textarea+.help-block,.form-horizontal .uneditable-input+.help-block,.form-horizontal .input-prepend+.help-block,.form-horizontal .input-append+.help-block{margin-top:10px}.form-horizontal .form-actions{padding-left:180px}table{max-width:100%;background-color:transparent;border-collapse:collapse;border-spacing:0}.table{width:100%;margin-bottom:20px}.table th,.table td{padding:8px;line-height:20px;text-align:left;vertical-align:top;border-top:1px solid #ddd}.table th{font-weight:bold}.table thead th{vertical-align:bottom}.table caption+thead tr:first-child th,.table caption+thead tr:first-child td,.table colgroup+thead tr:first-child th,.table colgroup+thead tr:first-child td,.table thead:first-child tr:first-child th,.table thead:first-child tr:first-child td{border-top:0}.table tbody+tbody{border-top:2px solid #ddd}.table .table{background-color:#fff}.table-condensed th,.table-condensed td{padding:4px 5px}.table-bordered{border:1px solid #ddd;border-collapse:separate;*border-collapse:collapse;border-left:0;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px}.table-bordered th,.table-bordered td{border-left:1px solid #ddd}.table-bordered caption+thead tr:first-child th,.table-bordered caption+tbody tr:first-child th,.table-bordered caption+tbody tr:first-child td,.table-bordered colgroup+thead tr:first-child th,.table-bordered colgroup+tbody tr:first-child th,.table-bordered colgroup+tbody tr:first-child td,.table-bordered thead:first-child tr:first-child th,.table-bordered tbody:first-child tr:first-child th,.table-bordered tbody:first-child tr:first-child td{border-top:0}.table-bordered thead:first-child tr:first-child>th:first-child,.table-bordered tbody:first-child tr:first-child>td:first-child,.table-bordered tbody:first-child tr:first-child>th:first-child{-webkit-border-top-left-radius:4px;border-top-left-radius:4px;-moz-border-radius-topleft:4px}.table-bordered thead:first-child tr:first-child>th:last-child,.table-bordered tbody:first-child tr:first-child>td:last-child,.table-bordered tbody:first-child tr:first-child>th:last-child{-webkit-border-top-right-radius:4px;border-top-right-radius:4px;-moz-border-radius-topright:4px}.table-bordered thead:last-child tr:last-child>th:first-child,.table-bordered tbody:last-child tr:last-child>td:first-child,.table-bordered tbody:last-child tr:last-child>th:first-child,.table-bordered tfoot:last-child tr:last-child>td:first-child,.table-bordered tfoot:last-child tr:last-child>th:first-child{-webkit-border-bottom-left-radius:4px;border-bottom-left-radius:4px;-moz-border-radius-bottomleft:4px}.table-bordered thead:last-child tr:last-child>th:last-child,.table-bordered tbody:last-child tr:last-child>td:last-child,.table-bordered tbody:last-child tr:last-child>th:last-child,.table-bordered tfoot:last-child tr:last-child>td:last-child,.table-bordered tfoot:last-child tr:last-child>th:last-child{-webkit-border-bottom-right-radius:4px;border-bottom-right-radius:4px;-moz-border-radius-bottomright:4px}.table-bordered tfoot+tbody:last-child tr:last-child td:first-child{-webkit-border-bottom-left-radius:0;border-bottom-left-radius:0;-moz-border-radius-bottomleft:0}.table-bordered tfoot+tbody:last-child tr:last-child td:last-child{-webkit-border-bottom-right-radius:0;border-bottom-right-radius:0;-moz-border-radius-bottomright:0}.table-bordered caption+thead tr:first-child th:first-child,.table-bordered caption+tbody tr:first-child td:first-child,.table-bordered colgroup+thead tr:first-child th:first-child,.table-bordered colgroup+tbody tr:first-child td:first-child{-webkit-border-top-left-radius:4px;border-top-left-radius:4px;-moz-border-radius-topleft:4px}.table-bordered caption+thead tr:first-child th:last-child,.table-bordered caption+tbody tr:first-child td:last-child,.table-bordered colgroup+thead tr:first-child th:last-child,.table-bordered colgroup+tbody tr:first-child td:last-child{-webkit-border-top-right-radius:4px;border-top-right-radius:4px;-moz-border-radius-topright:4px}.table-striped tbody>tr:nth-child(odd)>td,.table-striped tbody>tr:nth-child(odd)>th{background-color:#f9f9f9}.table-hover tbody tr:hover>td,.table-hover tbody tr:hover>th{background-color:#f5f5f5}table td[class*="span"],table th[class*="span"],.row-fluid table td[class*="span"],.row-fluid table th[class*="span"]{display:table-cell;float:none;margin-left:0}.table td.span1,.table th.span1{float:none;width:44px;margin-left:0}.table td.span2,.table th.span2{float:none;width:124px;margin-left:0}.table td.span3,.table th.span3{float:none;width:204px;margin-left:0}.table td.span4,.table th.span4{float:none;width:284px;margin-left:0}.table td.span5,.table th.span5{float:none;width:364px;margin-left:0}.table td.span6,.table th.span6{float:none;width:444px;margin-left:0}.table td.span7,.table th.span7{float:none;width:524px;margin-left:0}.table td.span8,.table th.span8{float:none;width:604px;margin-left:0}.table td.span9,.table th.span9{float:none;width:684px;margin-left:0}.table td.span10,.table th.span10{float:none;width:764px;margin-left:0}.table td.span11,.table th.span11{float:none;width:844px;margin-left:0}.table td.span12,.table th.span12{float:none;width:924px;margin-left:0}.table tbody tr.success>td{background-color:#dff0d8}.table tbody tr.error>td{background-color:#f2dede}.table tbody tr.warning>td{background-color:#fcf8e3}.table tbody tr.info>td{background-color:#d9edf7}.table-hover tbody tr.success:hover>td{background-color:#d0e9c6}.table-hover tbody tr.error:hover>td{background-color:#ebcccc}.table-hover tbody tr.warning:hover>td{background-color:#faf2cc}.table-hover tbody tr.info:hover>td{background-color:#c4e3f3}[class^="icon-"],[class*=" icon-"]{display:inline-block;width:14px;height:14px;margin-top:1px;*margin-right:.3em;line-height:14px;vertical-align:text-top;background-image:url("../img/glyphicons-halflings.png");background-position:14px 14px;background-repeat:no-repeat}.icon-white,.nav-pills>.active>a>[class^="icon-"],.nav-pills>.active>a>[class*=" icon-"],.nav-list>.active>a>[class^="icon-"],.nav-list>.active>a>[class*=" icon-"],.navbar-inverse .nav>.active>a>[class^="icon-"],.navbar-inverse .nav>.active>a>[class*=" icon-"],.dropdown-menu>li>a:hover>[class^="icon-"],.dropdown-menu>li>a:focus>[class^="icon-"],.dropdown-menu>li>a:hover>[class*=" icon-"],.dropdown-menu>li>a:focus>[class*=" icon-"],.dropdown-menu>.active>a>[class^="icon-"],.dropdown-menu>.active>a>[class*=" icon-"],.dropdown-submenu:hover>a>[class^="icon-"],.dropdown-submenu:focus>a>[class^="icon-"],.dropdown-submenu:hover>a>[class*=" icon-"],.dropdown-submenu:focus>a>[class*=" icon-"]{background-image:url("../img/glyphicons-halflings-white.png")}.icon-glass{background-position:0 0}.icon-music{background-position:-24px 0}.icon-search{background-position:-48px 0}.icon-envelope{background-position:-72px 0}.icon-heart{background-position:-96px 0}.icon-star{background-position:-120px 0}.icon-star-empty{background-position:-144px 0}.icon-user{background-position:-168px 0}.icon-film{background-position:-192px 0}.icon-th-large{background-position:-216px 0}.icon-th{background-position:-240px 0}.icon-th-list{background-position:-264px 0}.icon-ok{background-position:-288px 0}.icon-remove{background-position:-312px 0}.icon-zoom-in{background-position:-336px 0}.icon-zoom-out{background-position:-360px 0}.icon-off{background-position:-384px 0}.icon-signal{background-position:-408px 0}.icon-cog{background-position:-432px 0}.icon-trash{background-position:-456px 0}.icon-home{background-position:0 -24px}.icon-file{background-position:-24px -24px}.icon-time{background-position:-48px -24px}.icon-road{background-position:-72px -24px}.icon-download-alt{background-position:-96px -24px}.icon-download{background-position:-120px -24px}.icon-upload{background-position:-144px -24px}.icon-inbox{background-position:-168px -24px}.icon-play-circle{background-position:-192px -24px}.icon-repeat{background-position:-216px -24px}.icon-refresh{background-position:-240px -24px}.icon-list-alt{background-position:-264px -24px}.icon-lock{background-position:-287px -24px}.icon-flag{background-position:-312px -24px}.icon-headphones{background-position:-336px -24px}.icon-volume-off{background-position:-360px -24px}.icon-volume-down{background-position:-384px -24px}.icon-volume-up{background-position:-408px -24px}.icon-qrcode{background-position:-432px -24px}.icon-barcode{background-position:-456px -24px}.icon-tag{background-position:0 -48px}.icon-tags{background-position:-25px -48px}.icon-book{background-position:-48px -48px}.icon-bookmark{background-position:-72px -48px}.icon-print{background-position:-96px -48px}.icon-camera{background-position:-120px -48px}.icon-font{background-position:-144px -48px}.icon-bold{background-position:-167px -48px}.icon-italic{background-position:-192px -48px}.icon-text-height{background-position:-216px -48px}.icon-text-width{background-position:-240px -48px}.icon-align-left{background-position:-264px -48px}.icon-align-center{background-position:-288px -48px}.icon-align-right{background-position:-312px -48px}.icon-align-justify{background-position:-336px -48px}.icon-list{background-position:-360px -48px}.icon-indent-left{background-position:-384px -48px}.icon-indent-right{background-position:-408px -48px}.icon-facetime-video{background-position:-432px -48px}.icon-picture{background-position:-456px -48px}.icon-pencil{background-position:0 -72px}.icon-map-marker{background-position:-24px -72px}.icon-adjust{background-position:-48px -72px}.icon-tint{background-position:-72px -72px}.icon-edit{background-position:-96px -72px}.icon-share{background-position:-120px -72px}.icon-check{background-position:-144px -72px}.icon-move{background-position:-168px -72px}.icon-step-backward{background-position:-192px -72px}.icon-fast-backward{background-position:-216px -72px}.icon-backward{background-position:-240px -72px}.icon-play{background-position:-264px -72px}.icon-pause{background-position:-288px -72px}.icon-stop{background-position:-312px -72px}.icon-forward{background-position:-336px -72px}.icon-fast-forward{background-position:-360px -72px}.icon-step-forward{background-position:-384px -72px}.icon-eject{background-position:-408px -72px}.icon-chevron-left{background-position:-432px -72px}.icon-chevron-right{background-position:-456px -72px}.icon-plus-sign{background-position:0 -96px}.icon-minus-sign{background-position:-24px -96px}.icon-remove-sign{background-position:-48px -96px}.icon-ok-sign{background-position:-72px -96px}.icon-question-sign{background-position:-96px -96px}.icon-info-sign{background-position:-120px -96px}.icon-screenshot{background-position:-144px -96px}.icon-remove-circle{background-position:-168px -96px}.icon-ok-circle{background-position:-192px -96px}.icon-ban-circle{background-position:-216px -96px}.icon-arrow-left{background-position:-240px -96px}.icon-arrow-right{background-position:-264px -96px}.icon-arrow-up{background-position:-289px -96px}.icon-arrow-down{background-position:-312px -96px}.icon-share-alt{background-position:-336px -96px}.icon-resize-full{background-position:-360px -96px}.icon-resize-small{background-position:-384px -96px}.icon-plus{background-position:-408px -96px}.icon-minus{background-position:-433px -96px}.icon-asterisk{background-position:-456px -96px}.icon-exclamation-sign{background-position:0 -120px}.icon-gift{background-position:-24px -120px}.icon-leaf{background-position:-48px -120px}.icon-fire{background-position:-72px -120px}.icon-eye-open{background-position:-96px -120px}.icon-eye-close{background-position:-120px -120px}.icon-warning-sign{background-position:-144px -120px}.icon-plane{background-position:-168px -120px}.icon-calendar{background-position:-192px -120px}.icon-random{width:16px;background-position:-216px -120px}.icon-comment{background-position:-240px -120px}.icon-magnet{background-position:-264px -120px}.icon-chevron-up{background-position:-288px -120px}.icon-chevron-down{background-position:-313px -119px}.icon-retweet{background-position:-336px -120px}.icon-shopping-cart{background-position:-360px -120px}.icon-folder-close{width:16px;background-position:-384px -120px}.icon-folder-open{width:16px;background-position:-408px -120px}.icon-resize-vertical{background-position:-432px -119px}.icon-resize-horizontal{background-position:-456px -118px}.icon-hdd{background-position:0 -144px}.icon-bullhorn{background-position:-24px -144px}.icon-bell{background-position:-48px -144px}.icon-certificate{background-position:-72px -144px}.icon-thumbs-up{background-position:-96px -144px}.icon-thumbs-down{background-position:-120px -144px}.icon-hand-right{background-position:-144px -144px}.icon-hand-left{background-position:-168px -144px}.icon-hand-up{background-position:-192px -144px}.icon-hand-down{background-position:-216px -144px}.icon-circle-arrow-right{background-position:-240px -144px}.icon-circle-arrow-left{background-position:-264px -144px}.icon-circle-arrow-up{background-position:-288px -144px}.icon-circle-arrow-down{background-position:-312px -144px}.icon-globe{background-position:-336px -144px}.icon-wrench{background-position:-360px -144px}.icon-tasks{background-position:-384px -144px}.icon-filter{background-position:-408px -144px}.icon-briefcase{background-position:-432px -144px}.icon-fullscreen{background-position:-456px -144px}.dropup,.dropdown{position:relative}.dropdown-toggle{*margin-bottom:-3px}.dropdown-toggle:active,.open .dropdown-toggle{outline:0}.caret{display:inline-block;width:0;height:0;vertical-align:top;border-top:4px solid #000;border-right:4px solid transparent;border-left:4px solid transparent;content:""}.dropdown .caret{margin-top:8px;margin-left:2px}.dropdown-menu{position:absolute;top:100%;left:0;z-index:1000;display:none;float:left;min-width:160px;padding:5px 0;margin:2px 0 0;list-style:none;background-color:#fff;border:1px solid #ccc;border:1px solid rgba(0,0,0,0.2);*border-right-width:2px;*border-bottom-width:2px;-webkit-border-radius:6px;-moz-border-radius:6px;border-radius:6px;-webkit-box-shadow:0 5px 10px rgba(0,0,0,0.2);-moz-box-shadow:0 5px 10px rgba(0,0,0,0.2);box-shadow:0 5px 10px rgba(0,0,0,0.2);-webkit-background-clip:padding-box;-moz-background-clip:padding;background-clip:padding-box}.dropdown-menu.pull-right{right:0;left:auto}.dropdown-menu .divider{*width:100%;height:1px;margin:9px 1px;*margin:-5px 0 5px;overflow:hidden;background-color:#e5e5e5;border-bottom:1px solid #fff}.dropdown-menu>li>a{display:block;padding:3px 20px;clear:both;font-weight:normal;line-height:20px;color:#333;white-space:nowrap}.dropdown-menu>li>a:hover,.dropdown-menu>li>a:focus,.dropdown-submenu:hover>a,.dropdown-submenu:focus>a{color:#fff;text-decoration:none;background-color:#0081c2;background-image:-moz-linear-gradient(top,#08c,#0077b3);background-image:-webkit-gradient(linear,0 0,0 100%,from(#08c),to(#0077b3));background-image:-webkit-linear-gradient(top,#08c,#0077b3);background-image:-o-linear-gradient(top,#08c,#0077b3);background-image:linear-gradient(to bottom,#08c,#0077b3);background-repeat:repeat-x;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff0088cc',endColorstr='#ff0077b3',GradientType=0)}.dropdown-menu>.active>a,.dropdown-menu>.active>a:hover,.dropdown-menu>.active>a:focus{color:#fff;text-decoration:none;background-color:#0081c2;background-image:-moz-linear-gradient(top,#08c,#0077b3);background-image:-webkit-gradient(linear,0 0,0 100%,from(#08c),to(#0077b3));background-image:-webkit-linear-gradient(top,#08c,#0077b3);background-image:-o-linear-gradient(top,#08c,#0077b3);background-image:linear-gradient(to bottom,#08c,#0077b3);background-repeat:repeat-x;outline:0;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff0088cc',endColorstr='#ff0077b3',GradientType=0)}.dropdown-menu>.disabled>a,.dropdown-menu>.disabled>a:hover,.dropdown-menu>.disabled>a:focus{color:#999}.dropdown-menu>.disabled>a:hover,.dropdown-menu>.disabled>a:focus{text-decoration:none;cursor:default;background-color:transparent;background-image:none;filter:progid:DXImageTransform.Microsoft.gradient(enabled=false)}.open{*z-index:1000}.open>.dropdown-menu{display:block}.dropdown-backdrop{position:fixed;top:0;right:0;bottom:0;left:0;z-index:990}.pull-right>.dropdown-menu{right:0;left:auto}.dropup .caret,.navbar-fixed-bottom .dropdown .caret{border-top:0;border-bottom:4px solid #000;content:""}.dropup .dropdown-menu,.navbar-fixed-bottom .dropdown .dropdown-menu{top:auto;bottom:100%;margin-bottom:1px}.dropdown-submenu{position:relative}.dropdown-submenu>.dropdown-menu{top:0;left:100%;margin-top:-6px;margin-left:-1px;-webkit-border-radius:0 6px 6px 6px;-moz-border-radius:0 6px 6px 6px;border-radius:0 6px 6px 6px}.dropdown-submenu:hover>.dropdown-menu{display:block}.dropup .dropdown-submenu>.dropdown-menu{top:auto;bottom:0;margin-top:0;margin-bottom:-2px;-webkit-border-radius:5px 5px 5px 0;-moz-border-radius:5px 5px 5px 0;border-radius:5px 5px 5px 0}.dropdown-submenu>a:after{display:block;float:right;width:0;height:0;margin-top:5px;margin-right:-10px;border-color:transparent;border-left-color:#ccc;border-style:solid;border-width:5px 0 5px 5px;content:" "}.dropdown-submenu:hover>a:after{border-left-color:#fff}.dropdown-submenu.pull-left{float:none}.dropdown-submenu.pull-left>.dropdown-menu{left:-100%;margin-left:10px;-webkit-border-radius:6px 0 6px 6px;-moz-border-radius:6px 0 6px 6px;border-radius:6px 0 6px 6px}.dropdown .dropdown-menu .nav-header{padding-right:20px;padding-left:20px}.typeahead{z-index:1051;margin-top:2px;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px}.well{min-height:20px;padding:19px;margin-bottom:20px;background-color:#f5f5f5;border:1px solid #e3e3e3;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,0.05);-moz-box-shadow:inset 0 1px 1px rgba(0,0,0,0.05);box-shadow:inset 0 1px 1px rgba(0,0,0,0.05)}.well blockquote{border-color:#ddd;border-color:rgba(0,0,0,0.15)}.well-large{padding:24px;-webkit-border-radius:6px;-moz-border-radius:6px;border-radius:6px}.well-small{padding:9px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px}.fade{opacity:0;-webkit-transition:opacity .15s linear;-moz-transition:opacity .15s linear;-o-transition:opacity .15s linear;transition:opacity .15s linear}.fade.in{opacity:1}.collapse{position:relative;height:0;overflow:hidden;-webkit-transition:height .35s ease;-moz-transition:height .35s ease;-o-transition:height .35s ease;transition:height .35s ease}.collapse.in{height:auto}.close{float:right;font-size:20px;font-weight:bold;line-height:20px;color:#000;text-shadow:0 1px 0 #fff;opacity:.2;filter:alpha(opacity=20)}.close:hover,.close:focus{color:#000;text-decoration:none;cursor:pointer;opacity:.4;filter:alpha(opacity=40)}button.close{padding:0;cursor:pointer;background:transparent;border:0;-webkit-appearance:none}.btn{display:inline-block;*display:inline;padding:4px 12px;margin-bottom:0;*margin-left:.3em;font-size:14px;line-height:20px;color:#333;text-align:center;text-shadow:0 1px 1px rgba(255,255,255,0.75);vertical-align:middle;cursor:pointer;background-color:#f5f5f5;*background-color:#e6e6e6;background-image:-moz-linear-gradient(top,#fff,#e6e6e6);background-image:-webkit-gradient(linear,0 0,0 100%,from(#fff),to(#e6e6e6));background-image:-webkit-linear-gradient(top,#fff,#e6e6e6);background-image:-o-linear-gradient(top,#fff,#e6e6e6);background-image:linear-gradient(to bottom,#fff,#e6e6e6);background-repeat:repeat-x;border:1px solid #ccc;*border:0;border-color:#e6e6e6 #e6e6e6 #bfbfbf;border-color:rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);border-bottom-color:#b3b3b3;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff',endColorstr='#ffe6e6e6',GradientType=0);filter:progid:DXImageTransform.Microsoft.gradient(enabled=false);*zoom:1;-webkit-box-shadow:inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);-moz-box-shadow:inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);box-shadow:inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05)}.btn:hover,.btn:focus,.btn:active,.btn.active,.btn.disabled,.btn[disabled]{color:#333;background-color:#e6e6e6;*background-color:#d9d9d9}.btn:active,.btn.active{background-color:#ccc \9}.btn:first-child{*margin-left:0}.btn:hover,.btn:focus{color:#333;text-decoration:none;background-position:0 -15px;-webkit-transition:background-position .1s linear;-moz-transition:background-position .1s linear;-o-transition:background-position .1s linear;transition:background-position .1s linear}.btn:focus{outline:thin dotted #333;outline:5px auto -webkit-focus-ring-color;outline-offset:-2px}.btn.active,.btn:active{background-image:none;outline:0;-webkit-box-shadow:inset 0 2px 4px rgba(0,0,0,0.15),0 1px 2px rgba(0,0,0,0.05);-moz-box-shadow:inset 0 2px 4px rgba(0,0,0,0.15),0 1px 2px rgba(0,0,0,0.05);box-shadow:inset 0 2px 4px rgba(0,0,0,0.15),0 1px 2px rgba(0,0,0,0.05)}.btn.disabled,.btn[disabled]{cursor:default;background-image:none;opacity:.65;filter:alpha(opacity=65);-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none}.btn-large{padding:11px 19px;font-size:17.5px;-webkit-border-radius:6px;-moz-border-radius:6px;border-radius:6px}.btn-large [class^="icon-"],.btn-large [class*=" icon-"]{margin-top:4px}.btn-small{padding:2px 10px;font-size:11.9px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px}.btn-small [class^="icon-"],.btn-small [class*=" icon-"]{margin-top:0}.btn-mini [class^="icon-"],.btn-mini [class*=" icon-"]{margin-top:-1px}.btn-mini{padding:0 6px;font-size:10.5px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px}.btn-block{display:block;width:100%;padding-right:0;padding-left:0;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}.btn-block+.btn-block{margin-top:5px}input[type="submit"].btn-block,input[type="reset"].btn-block,input[type="button"].btn-block{width:100%}.btn-primary.active,.btn-warning.active,.btn-danger.active,.btn-success.active,.btn-info.active,.btn-inverse.active{color:rgba(255,255,255,0.75)}.btn-primary{color:#fff;text-shadow:0 -1px 0 rgba(0,0,0,0.25);background-color:#006dcc;*background-color:#04c;background-image:-moz-linear-gradient(top,#08c,#04c);background-image:-webkit-gradient(linear,0 0,0 100%,from(#08c),to(#04c));background-image:-webkit-linear-gradient(top,#08c,#04c);background-image:-o-linear-gradient(top,#08c,#04c);background-image:linear-gradient(to bottom,#08c,#04c);background-repeat:repeat-x;border-color:#04c #04c #002a80;border-color:rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff0088cc',endColorstr='#ff0044cc',GradientType=0);filter:progid:DXImageTransform.Microsoft.gradient(enabled=false)}.btn-primary:hover,.btn-primary:focus,.btn-primary:active,.btn-primary.active,.btn-primary.disabled,.btn-primary[disabled]{color:#fff;background-color:#04c;*background-color:#003bb3}.btn-primary:active,.btn-primary.active{background-color:#039 \9}.btn-warning{color:#fff;text-shadow:0 -1px 0 rgba(0,0,0,0.25);background-color:#faa732;*background-color:#f89406;background-image:-moz-linear-gradient(top,#fbb450,#f89406);background-image:-webkit-gradient(linear,0 0,0 100%,from(#fbb450),to(#f89406));background-image:-webkit-linear-gradient(top,#fbb450,#f89406);background-image:-o-linear-gradient(top,#fbb450,#f89406);background-image:linear-gradient(to bottom,#fbb450,#f89406);background-repeat:repeat-x;border-color:#f89406 #f89406 #ad6704;border-color:rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#fffbb450',endColorstr='#fff89406',GradientType=0);filter:progid:DXImageTransform.Microsoft.gradient(enabled=false)}.btn-warning:hover,.btn-warning:focus,.btn-warning:active,.btn-warning.active,.btn-warning.disabled,.btn-warning[disabled]{color:#fff;background-color:#f89406;*background-color:#df8505}.btn-warning:active,.btn-warning.active{background-color:#c67605 \9}.btn-danger{color:#fff;text-shadow:0 -1px 0 rgba(0,0,0,0.25);background-color:#da4f49;*background-color:#bd362f;background-image:-moz-linear-gradient(top,#ee5f5b,#bd362f);background-image:-webkit-gradient(linear,0 0,0 100%,from(#ee5f5b),to(#bd362f));background-image:-webkit-linear-gradient(top,#ee5f5b,#bd362f);background-image:-o-linear-gradient(top,#ee5f5b,#bd362f);background-image:linear-gradient(to bottom,#ee5f5b,#bd362f);background-repeat:repeat-x;border-color:#bd362f #bd362f #802420;border-color:rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffee5f5b',endColorstr='#ffbd362f',GradientType=0);filter:progid:DXImageTransform.Microsoft.gradient(enabled=false)}.btn-danger:hover,.btn-danger:focus,.btn-danger:active,.btn-danger.active,.btn-danger.disabled,.btn-danger[disabled]{color:#fff;background-color:#bd362f;*background-color:#a9302a}.btn-danger:active,.btn-danger.active{background-color:#942a25 \9}.btn-success{color:#fff;text-shadow:0 -1px 0 rgba(0,0,0,0.25);background-color:#5bb75b;*background-color:#51a351;background-image:-moz-linear-gradient(top,#62c462,#51a351);background-image:-webkit-gradient(linear,0 0,0 100%,from(#62c462),to(#51a351));background-image:-webkit-linear-gradient(top,#62c462,#51a351);background-image:-o-linear-gradient(top,#62c462,#51a351);background-image:linear-gradient(to bottom,#62c462,#51a351);background-repeat:repeat-x;border-color:#51a351 #51a351 #387038;border-color:rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff62c462',endColorstr='#ff51a351',GradientType=0);filter:progid:DXImageTransform.Microsoft.gradient(enabled=false)}.btn-success:hover,.btn-success:focus,.btn-success:active,.btn-success.active,.btn-success.disabled,.btn-success[disabled]{color:#fff;background-color:#51a351;*background-color:#499249}.btn-success:active,.btn-success.active{background-color:#408140 \9}.btn-info{color:#fff;text-shadow:0 -1px 0 rgba(0,0,0,0.25);background-color:#49afcd;*background-color:#2f96b4;background-image:-moz-linear-gradient(top,#5bc0de,#2f96b4);background-image:-webkit-gradient(linear,0 0,0 100%,from(#5bc0de),to(#2f96b4));background-image:-webkit-linear-gradient(top,#5bc0de,#2f96b4);background-image:-o-linear-gradient(top,#5bc0de,#2f96b4);background-image:linear-gradient(to bottom,#5bc0de,#2f96b4);background-repeat:repeat-x;border-color:#2f96b4 #2f96b4 #1f6377;border-color:rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff5bc0de',endColorstr='#ff2f96b4',GradientType=0);filter:progid:DXImageTransform.Microsoft.gradient(enabled=false)}.btn-info:hover,.btn-info:focus,.btn-info:active,.btn-info.active,.btn-info.disabled,.btn-info[disabled]{color:#fff;background-color:#2f96b4;*background-color:#2a85a0}.btn-info:active,.btn-info.active{background-color:#24748c \9}.btn-inverse{color:#fff;text-shadow:0 -1px 0 rgba(0,0,0,0.25);background-color:#363636;*background-color:#222;background-image:-moz-linear-gradient(top,#444,#222);background-image:-webkit-gradient(linear,0 0,0 100%,from(#444),to(#222));background-image:-webkit-linear-gradient(top,#444,#222);background-image:-o-linear-gradient(top,#444,#222);background-image:linear-gradient(to bottom,#444,#222);background-repeat:repeat-x;border-color:#222 #222 #000;border-color:rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff444444',endColorstr='#ff222222',GradientType=0);filter:progid:DXImageTransform.Microsoft.gradient(enabled=false)}.btn-inverse:hover,.btn-inverse:focus,.btn-inverse:active,.btn-inverse.active,.btn-inverse.disabled,.btn-inverse[disabled]{color:#fff;background-color:#222;*background-color:#151515}.btn-inverse:active,.btn-inverse.active{background-color:#080808 \9}button.btn,input[type="submit"].btn{*padding-top:3px;*padding-bottom:3px}button.btn::-moz-focus-inner,input[type="submit"].btn::-moz-focus-inner{padding:0;border:0}button.btn.btn-large,input[type="submit"].btn.btn-large{*padding-top:7px;*padding-bottom:7px}button.btn.btn-small,input[type="submit"].btn.btn-small{*padding-top:3px;*padding-bottom:3px}button.btn.btn-mini,input[type="submit"].btn.btn-mini{*padding-top:1px;*padding-bottom:1px}.btn-link,.btn-link:active,.btn-link[disabled]{background-color:transparent;background-image:none;-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none}.btn-link{color:#08c;cursor:pointer;border-color:transparent;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0}.btn-link:hover,.btn-link:focus{color:#005580;text-decoration:underline;background-color:transparent}.btn-link[disabled]:hover,.btn-link[disabled]:focus{color:#333;text-decoration:none}.btn-group{position:relative;display:inline-block;*display:inline;*margin-left:.3em;font-size:0;white-space:nowrap;vertical-align:middle;*zoom:1}.btn-group:first-child{*margin-left:0}.btn-group+.btn-group{margin-left:5px}.btn-toolbar{margin-top:10px;margin-bottom:10px;font-size:0}.btn-toolbar>.btn+.btn,.btn-toolbar>.btn-group+.btn,.btn-toolbar>.btn+.btn-group{margin-left:5px}.btn-group>.btn{position:relative;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0}.btn-group>.btn+.btn{margin-left:-1px}.btn-group>.btn,.btn-group>.dropdown-menu,.btn-group>.popover{font-size:14px}.btn-group>.btn-mini{font-size:10.5px}.btn-group>.btn-small{font-size:11.9px}.btn-group>.btn-large{font-size:17.5px}.btn-group>.btn:first-child{margin-left:0;-webkit-border-bottom-left-radius:4px;border-bottom-left-radius:4px;-webkit-border-top-left-radius:4px;border-top-left-radius:4px;-moz-border-radius-bottomleft:4px;-moz-border-radius-topleft:4px}.btn-group>.btn:last-child,.btn-group>.dropdown-toggle{-webkit-border-top-right-radius:4px;border-top-right-radius:4px;-webkit-border-bottom-right-radius:4px;border-bottom-right-radius:4px;-moz-border-radius-topright:4px;-moz-border-radius-bottomright:4px}.btn-group>.btn.large:first-child{margin-left:0;-webkit-border-bottom-left-radius:6px;border-bottom-left-radius:6px;-webkit-border-top-left-radius:6px;border-top-left-radius:6px;-moz-border-radius-bottomleft:6px;-moz-border-radius-topleft:6px}.btn-group>.btn.large:last-child,.btn-group>.large.dropdown-toggle{-webkit-border-top-right-radius:6px;border-top-right-radius:6px;-webkit-border-bottom-right-radius:6px;border-bottom-right-radius:6px;-moz-border-radius-topright:6px;-moz-border-radius-bottomright:6px}.btn-group>.btn:hover,.btn-group>.btn:focus,.btn-group>.btn:active,.btn-group>.btn.active{z-index:2}.btn-group .dropdown-toggle:active,.btn-group.open .dropdown-toggle{outline:0}.btn-group>.btn+.dropdown-toggle{*padding-top:5px;padding-right:8px;*padding-bottom:5px;padding-left:8px;-webkit-box-shadow:inset 1px 0 0 rgba(255,255,255,0.125),inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);-moz-box-shadow:inset 1px 0 0 rgba(255,255,255,0.125),inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);box-shadow:inset 1px 0 0 rgba(255,255,255,0.125),inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05)}.btn-group>.btn-mini+.dropdown-toggle{*padding-top:2px;padding-right:5px;*padding-bottom:2px;padding-left:5px}.btn-group>.btn-small+.dropdown-toggle{*padding-top:5px;*padding-bottom:4px}.btn-group>.btn-large+.dropdown-toggle{*padding-top:7px;padding-right:12px;*padding-bottom:7px;padding-left:12px}.btn-group.open .dropdown-toggle{background-image:none;-webkit-box-shadow:inset 0 2px 4px rgba(0,0,0,0.15),0 1px 2px rgba(0,0,0,0.05);-moz-box-shadow:inset 0 2px 4px rgba(0,0,0,0.15),0 1px 2px rgba(0,0,0,0.05);box-shadow:inset 0 2px 4px rgba(0,0,0,0.15),0 1px 2px rgba(0,0,0,0.05)}.btn-group.open .btn.dropdown-toggle{background-color:#e6e6e6}.btn-group.open .btn-primary.dropdown-toggle{background-color:#04c}.btn-group.open .btn-warning.dropdown-toggle{background-color:#f89406}.btn-group.open .btn-danger.dropdown-toggle{background-color:#bd362f}.btn-group.open .btn-success.dropdown-toggle{background-color:#51a351}.btn-group.open .btn-info.dropdown-toggle{background-color:#2f96b4}.btn-group.open .btn-inverse.dropdown-toggle{background-color:#222}.btn .caret{margin-top:8px;margin-left:0}.btn-large .caret{margin-top:6px}.btn-large .caret{border-top-width:5px;border-right-width:5px;border-left-width:5px}.btn-mini .caret,.btn-small .caret{margin-top:8px}.dropup .btn-large .caret{border-bottom-width:5px}.btn-primary .caret,.btn-warning .caret,.btn-danger .caret,.btn-info .caret,.btn-success .caret,.btn-inverse .caret{border-top-color:#fff;border-bottom-color:#fff}.btn-group-vertical{display:inline-block;*display:inline;*zoom:1}.btn-group-vertical>.btn{display:block;float:none;max-width:100%;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0}.btn-group-vertical>.btn+.btn{margin-top:-1px;margin-left:0}.btn-group-vertical>.btn:first-child{-webkit-border-radius:4px 4px 0 0;-moz-border-radius:4px 4px 0 0;border-radius:4px 4px 0 0}.btn-group-vertical>.btn:last-child{-webkit-border-radius:0 0 4px 4px;-moz-border-radius:0 0 4px 4px;border-radius:0 0 4px 4px}.btn-group-vertical>.btn-large:first-child{-webkit-border-radius:6px 6px 0 0;-moz-border-radius:6px 6px 0 0;border-radius:6px 6px 0 0}.btn-group-vertical>.btn-large:last-child{-webkit-border-radius:0 0 6px 6px;-moz-border-radius:0 0 6px 6px;border-radius:0 0 6px 6px}.alert{padding:8px 35px 8px 14px;margin-bottom:20px;text-shadow:0 1px 0 rgba(255,255,255,0.5);background-color:#fcf8e3;border:1px solid #fbeed5;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px}.alert,.alert h4{color:#c09853}.alert h4{margin:0}.alert .close{position:relative;top:-2px;right:-21px;line-height:20px}.alert-success{color:#468847;background-color:#dff0d8;border-color:#d6e9c6}.alert-success h4{color:#468847}.alert-danger,.alert-error{color:#b94a48;background-color:#f2dede;border-color:#eed3d7}.alert-danger h4,.alert-error h4{color:#b94a48}.alert-info{color:#3a87ad;background-color:#d9edf7;border-color:#bce8f1}.alert-info h4{color:#3a87ad}.alert-block{padding-top:14px;padding-bottom:14px}.alert-block>p,.alert-block>ul{margin-bottom:0}.alert-block p+p{margin-top:5px}.nav{margin-bottom:20px;margin-left:0;list-style:none}.nav>li>a{display:block}.nav>li>a:hover,.nav>li>a:focus{text-decoration:none;background-color:#eee}.nav>li>a>img{max-width:none}.nav>.pull-right{float:right}.nav-header{display:block;padding:3px 15px;font-size:11px;font-weight:bold;line-height:20px;color:#999;text-shadow:0 1px 0 rgba(255,255,255,0.5);text-transform:uppercase}.nav li+.nav-header{margin-top:9px}.nav-list{padding-right:15px;padding-left:15px;margin-bottom:0}.nav-list>li>a,.nav-list .nav-header{margin-right:-15px;margin-left:-15px;text-shadow:0 1px 0 rgba(255,255,255,0.5)}.nav-list>li>a{padding:3px 15px}.nav-list>.active>a,.nav-list>.active>a:hover,.nav-list>.active>a:focus{color:#fff;text-shadow:0 -1px 0 rgba(0,0,0,0.2);background-color:#08c}.nav-list [class^="icon-"],.nav-list [class*=" icon-"]{margin-right:2px}.nav-list .divider{*width:100%;height:1px;margin:9px 1px;*margin:-5px 0 5px;overflow:hidden;background-color:#e5e5e5;border-bottom:1px solid #fff}.nav-tabs,.nav-pills{*zoom:1}.nav-tabs:before,.nav-pills:before,.nav-tabs:after,.nav-pills:after{display:table;line-height:0;content:""}.nav-tabs:after,.nav-pills:after{clear:both}.nav-tabs>li,.nav-pills>li{float:left}.nav-tabs>li>a,.nav-pills>li>a{padding-right:12px;padding-left:12px;margin-right:2px;line-height:14px}.nav-tabs{border-bottom:1px solid #ddd}.nav-tabs>li{margin-bottom:-1px}.nav-tabs>li>a{padding-top:8px;padding-bottom:8px;line-height:20px;border:1px solid transparent;-webkit-border-radius:4px 4px 0 0;-moz-border-radius:4px 4px 0 0;border-radius:4px 4px 0 0}.nav-tabs>li>a:hover,.nav-tabs>li>a:focus{border-color:#eee #eee #ddd}.nav-tabs>.active>a,.nav-tabs>.active>a:hover,.nav-tabs>.active>a:focus{color:#555;cursor:default;background-color:#fff;border:1px solid #ddd;border-bottom-color:transparent}.nav-pills>li>a{padding-top:8px;padding-bottom:8px;margin-top:2px;margin-bottom:2px;-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px}.nav-pills>.active>a,.nav-pills>.active>a:hover,.nav-pills>.active>a:focus{color:#fff;background-color:#08c}.nav-stacked>li{float:none}.nav-stacked>li>a{margin-right:0}.nav-tabs.nav-stacked{border-bottom:0}.nav-tabs.nav-stacked>li>a{border:1px solid #ddd;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0}.nav-tabs.nav-stacked>li:first-child>a{-webkit-border-top-right-radius:4px;border-top-right-radius:4px;-webkit-border-top-left-radius:4px;border-top-left-radius:4px;-moz-border-radius-topright:4px;-moz-border-radius-topleft:4px}.nav-tabs.nav-stacked>li:last-child>a{-webkit-border-bottom-right-radius:4px;border-bottom-right-radius:4px;-webkit-border-bottom-left-radius:4px;border-bottom-left-radius:4px;-moz-border-radius-bottomright:4px;-moz-border-radius-bottomleft:4px}.nav-tabs.nav-stacked>li>a:hover,.nav-tabs.nav-stacked>li>a:focus{z-index:2;border-color:#ddd}.nav-pills.nav-stacked>li>a{margin-bottom:3px}.nav-pills.nav-stacked>li:last-child>a{margin-bottom:1px}.nav-tabs .dropdown-menu{-webkit-border-radius:0 0 6px 6px;-moz-border-radius:0 0 6px 6px;border-radius:0 0 6px 6px}.nav-pills .dropdown-menu{-webkit-border-radius:6px;-moz-border-radius:6px;border-radius:6px}.nav .dropdown-toggle .caret{margin-top:6px;border-top-color:#08c;border-bottom-color:#08c}.nav .dropdown-toggle:hover .caret,.nav .dropdown-toggle:focus .caret{border-top-color:#005580;border-bottom-color:#005580}.nav-tabs .dropdown-toggle .caret{margin-top:8px}.nav .active .dropdown-toggle .caret{border-top-color:#fff;border-bottom-color:#fff}.nav-tabs .active .dropdown-toggle .caret{border-top-color:#555;border-bottom-color:#555}.nav>.dropdown.active>a:hover,.nav>.dropdown.active>a:focus{cursor:pointer}.nav-tabs .open .dropdown-toggle,.nav-pills .open .dropdown-toggle,.nav>li.dropdown.open.active>a:hover,.nav>li.dropdown.open.active>a:focus{color:#fff;background-color:#999;border-color:#999}.nav li.dropdown.open .caret,.nav li.dropdown.open.active .caret,.nav li.dropdown.open a:hover .caret,.nav li.dropdown.open a:focus .caret{border-top-color:#fff;border-bottom-color:#fff;opacity:1;filter:alpha(opacity=100)}.tabs-stacked .open>a:hover,.tabs-stacked .open>a:focus{border-color:#999}.tabbable{*zoom:1}.tabbable:before,.tabbable:after{display:table;line-height:0;content:""}.tabbable:after{clear:both}.tab-content{overflow:auto}.tabs-below>.nav-tabs,.tabs-right>.nav-tabs,.tabs-left>.nav-tabs{border-bottom:0}.tab-content>.tab-pane,.pill-content>.pill-pane{display:none}.tab-content>.active,.pill-content>.active{display:block}.tabs-below>.nav-tabs{border-top:1px solid #ddd}.tabs-below>.nav-tabs>li{margin-top:-1px;margin-bottom:0}.tabs-below>.nav-tabs>li>a{-webkit-border-radius:0 0 4px 4px;-moz-border-radius:0 0 4px 4px;border-radius:0 0 4px 4px}.tabs-below>.nav-tabs>li>a:hover,.tabs-below>.nav-tabs>li>a:focus{border-top-color:#ddd;border-bottom-color:transparent}.tabs-below>.nav-tabs>.active>a,.tabs-below>.nav-tabs>.active>a:hover,.tabs-below>.nav-tabs>.active>a:focus{border-color:transparent #ddd #ddd #ddd}.tabs-left>.nav-tabs>li,.tabs-right>.nav-tabs>li{float:none}.tabs-left>.nav-tabs>li>a,.tabs-right>.nav-tabs>li>a{min-width:74px;margin-right:0;margin-bottom:3px}.tabs-left>.nav-tabs{float:left;margin-right:19px;border-right:1px solid #ddd}.tabs-left>.nav-tabs>li>a{margin-right:-1px;-webkit-border-radius:4px 0 0 4px;-moz-border-radius:4px 0 0 4px;border-radius:4px 0 0 4px}.tabs-left>.nav-tabs>li>a:hover,.tabs-left>.nav-tabs>li>a:focus{border-color:#eee #ddd #eee #eee}.tabs-left>.nav-tabs .active>a,.tabs-left>.nav-tabs .active>a:hover,.tabs-left>.nav-tabs .active>a:focus{border-color:#ddd transparent #ddd #ddd;*border-right-color:#fff}.tabs-right>.nav-tabs{float:right;margin-left:19px;border-left:1px solid #ddd}.tabs-right>.nav-tabs>li>a{margin-left:-1px;-webkit-border-radius:0 4px 4px 0;-moz-border-radius:0 4px 4px 0;border-radius:0 4px 4px 0}.tabs-right>.nav-tabs>li>a:hover,.tabs-right>.nav-tabs>li>a:focus{border-color:#eee #eee #eee #ddd}.tabs-right>.nav-tabs .active>a,.tabs-right>.nav-tabs .active>a:hover,.tabs-right>.nav-tabs .active>a:focus{border-color:#ddd #ddd #ddd transparent;*border-left-color:#fff}.nav>.disabled>a{color:#999}.nav>.disabled>a:hover,.nav>.disabled>a:focus{text-decoration:none;cursor:default;background-color:transparent}.navbar{*position:relative;*z-index:2;margin-bottom:20px;overflow:visible}.navbar-inner{min-height:40px;padding-right:20px;padding-left:20px;background-color:#fafafa;background-image:-moz-linear-gradient(top,#fff,#f2f2f2);background-image:-webkit-gradient(linear,0 0,0 100%,from(#fff),to(#f2f2f2));background-image:-webkit-linear-gradient(top,#fff,#f2f2f2);background-image:-o-linear-gradient(top,#fff,#f2f2f2);background-image:linear-gradient(to bottom,#fff,#f2f2f2);background-repeat:repeat-x;border:1px solid #d4d4d4;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff',endColorstr='#fff2f2f2',GradientType=0);*zoom:1;-webkit-box-shadow:0 1px 4px rgba(0,0,0,0.065);-moz-box-shadow:0 1px 4px rgba(0,0,0,0.065);box-shadow:0 1px 4px rgba(0,0,0,0.065)}.navbar-inner:before,.navbar-inner:after{display:table;line-height:0;content:""}.navbar-inner:after{clear:both}.navbar .container{width:auto}.nav-collapse.collapse{height:auto;overflow:visible}.navbar .brand{display:block;float:left;padding:10px 20px 10px;margin-left:-20px;font-size:20px;font-weight:200;color:#777;text-shadow:0 1px 0 #fff}.navbar .brand:hover,.navbar .brand:focus{text-decoration:none}.navbar-text{margin-bottom:0;line-height:40px;color:#777}.navbar-link{color:#777}.navbar-link:hover,.navbar-link:focus{color:#333}.navbar .divider-vertical{height:40px;margin:0 9px;border-right:1px solid #fff;border-left:1px solid #f2f2f2}.navbar .btn,.navbar .btn-group{margin-top:5px}.navbar .btn-group .btn,.navbar .input-prepend .btn,.navbar .input-append .btn,.navbar .input-prepend .btn-group,.navbar .input-append .btn-group{margin-top:0}.navbar-form{margin-bottom:0;*zoom:1}.navbar-form:before,.navbar-form:after{display:table;line-height:0;content:""}.navbar-form:after{clear:both}.navbar-form input,.navbar-form select,.navbar-form .radio,.navbar-form .checkbox{margin-top:5px}.navbar-form input,.navbar-form select,.navbar-form .btn{display:inline-block;margin-bottom:0}.navbar-form input[type="image"],.navbar-form input[type="checkbox"],.navbar-form input[type="radio"]{margin-top:3px}.navbar-form .input-append,.navbar-form .input-prepend{margin-top:5px;white-space:nowrap}.navbar-form .input-append input,.navbar-form .input-prepend input{margin-top:0}.navbar-search{position:relative;float:left;margin-top:5px;margin-bottom:0}.navbar-search .search-query{padding:4px 14px;margin-bottom:0;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;font-size:13px;font-weight:normal;line-height:1;-webkit-border-radius:15px;-moz-border-radius:15px;border-radius:15px}.navbar-static-top{position:static;margin-bottom:0}.navbar-static-top .navbar-inner{-webkit-border-radius:0;-moz-border-radius:0;border-radius:0}.navbar-fixed-top,.navbar-fixed-bottom{position:fixed;right:0;left:0;z-index:1030;margin-bottom:0}.navbar-fixed-top .navbar-inner,.navbar-static-top .navbar-inner{border-width:0 0 1px}.navbar-fixed-bottom .navbar-inner{border-width:1px 0 0}.navbar-fixed-top .navbar-inner,.navbar-fixed-bottom .navbar-inner{padding-right:0;padding-left:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0}.navbar-static-top .container,.navbar-fixed-top .container,.navbar-fixed-bottom .container{width:940px}.navbar-fixed-top{top:0}.navbar-fixed-top .navbar-inner,.navbar-static-top .navbar-inner{-webkit-box-shadow:0 1px 10px rgba(0,0,0,0.1);-moz-box-shadow:0 1px 10px rgba(0,0,0,0.1);box-shadow:0 1px 10px rgba(0,0,0,0.1)}.navbar-fixed-bottom{bottom:0}.navbar-fixed-bottom .navbar-inner{-webkit-box-shadow:0 -1px 10px rgba(0,0,0,0.1);-moz-box-shadow:0 -1px 10px rgba(0,0,0,0.1);box-shadow:0 -1px 10px rgba(0,0,0,0.1)}.navbar .nav{position:relative;left:0;display:block;float:left;margin:0 10px 0 0}.navbar .nav.pull-right{float:right;margin-right:0}.navbar .nav>li{float:left}.navbar .nav>li>a{float:none;padding:10px 15px 10px;color:#777;text-decoration:none;text-shadow:0 1px 0 #fff}.navbar .nav .dropdown-toggle .caret{margin-top:8px}.navbar .nav>li>a:focus,.navbar .nav>li>a:hover{color:#333;text-decoration:none;background-color:transparent}.navbar .nav>.active>a,.navbar .nav>.active>a:hover,.navbar .nav>.active>a:focus{color:#555;text-decoration:none;background-color:#e5e5e5;-webkit-box-shadow:inset 0 3px 8px rgba(0,0,0,0.125);-moz-box-shadow:inset 0 3px 8px rgba(0,0,0,0.125);box-shadow:inset 0 3px 8px rgba(0,0,0,0.125)}.navbar .btn-navbar{display:none;float:right;padding:7px 10px;margin-right:5px;margin-left:5px;color:#fff;text-shadow:0 -1px 0 rgba(0,0,0,0.25);background-color:#ededed;*background-color:#e5e5e5;background-image:-moz-linear-gradient(top,#f2f2f2,#e5e5e5);background-image:-webkit-gradient(linear,0 0,0 100%,from(#f2f2f2),to(#e5e5e5));background-image:-webkit-linear-gradient(top,#f2f2f2,#e5e5e5);background-image:-o-linear-gradient(top,#f2f2f2,#e5e5e5);background-image:linear-gradient(to bottom,#f2f2f2,#e5e5e5);background-repeat:repeat-x;border-color:#e5e5e5 #e5e5e5 #bfbfbf;border-color:rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#fff2f2f2',endColorstr='#ffe5e5e5',GradientType=0);filter:progid:DXImageTransform.Microsoft.gradient(enabled=false);-webkit-box-shadow:inset 0 1px 0 rgba(255,255,255,0.1),0 1px 0 rgba(255,255,255,0.075);-moz-box-shadow:inset 0 1px 0 rgba(255,255,255,0.1),0 1px 0 rgba(255,255,255,0.075);box-shadow:inset 0 1px 0 rgba(255,255,255,0.1),0 1px 0 rgba(255,255,255,0.075)}.navbar .btn-navbar:hover,.navbar .btn-navbar:focus,.navbar .btn-navbar:active,.navbar .btn-navbar.active,.navbar .btn-navbar.disabled,.navbar .btn-navbar[disabled]{color:#fff;background-color:#e5e5e5;*background-color:#d9d9d9}.navbar .btn-navbar:active,.navbar .btn-navbar.active{background-color:#ccc \9}.navbar .btn-navbar .icon-bar{display:block;width:18px;height:2px;background-color:#f5f5f5;-webkit-border-radius:1px;-moz-border-radius:1px;border-radius:1px;-webkit-box-shadow:0 1px 0 rgba(0,0,0,0.25);-moz-box-shadow:0 1px 0 rgba(0,0,0,0.25);box-shadow:0 1px 0 rgba(0,0,0,0.25)}.btn-navbar .icon-bar+.icon-bar{margin-top:3px}.navbar .nav>li>.dropdown-menu:before{position:absolute;top:-7px;left:9px;display:inline-block;border-right:7px solid transparent;border-bottom:7px solid #ccc;border-left:7px solid transparent;border-bottom-color:rgba(0,0,0,0.2);content:''}.navbar .nav>li>.dropdown-menu:after{position:absolute;top:-6px;left:10px;display:inline-block;border-right:6px solid transparent;border-bottom:6px solid #fff;border-left:6px solid transparent;content:''}.navbar-fixed-bottom .nav>li>.dropdown-menu:before{top:auto;bottom:-7px;border-top:7px solid #ccc;border-bottom:0;border-top-color:rgba(0,0,0,0.2)}.navbar-fixed-bottom .nav>li>.dropdown-menu:after{top:auto;bottom:-6px;border-top:6px solid #fff;border-bottom:0}.navbar .nav li.dropdown>a:hover .caret,.navbar .nav li.dropdown>a:focus .caret{border-top-color:#333;border-bottom-color:#333}.navbar .nav li.dropdown.open>.dropdown-toggle,.navbar .nav li.dropdown.active>.dropdown-toggle,.navbar .nav li.dropdown.open.active>.dropdown-toggle{color:#555;background-color:#e5e5e5}.navbar .nav li.dropdown>.dropdown-toggle .caret{border-top-color:#777;border-bottom-color:#777}.navbar .nav li.dropdown.open>.dropdown-toggle .caret,.navbar .nav li.dropdown.active>.dropdown-toggle .caret,.navbar .nav li.dropdown.open.active>.dropdown-toggle .caret{border-top-color:#555;border-bottom-color:#555}.navbar .pull-right>li>.dropdown-menu,.navbar .nav>li>.dropdown-menu.pull-right{right:0;left:auto}.navbar .pull-right>li>.dropdown-menu:before,.navbar .nav>li>.dropdown-menu.pull-right:before{right:12px;left:auto}.navbar .pull-right>li>.dropdown-menu:after,.navbar .nav>li>.dropdown-menu.pull-right:after{right:13px;left:auto}.navbar .pull-right>li>.dropdown-menu .dropdown-menu,.navbar .nav>li>.dropdown-menu.pull-right .dropdown-menu{right:100%;left:auto;margin-right:-1px;margin-left:0;-webkit-border-radius:6px 0 6px 6px;-moz-border-radius:6px 0 6px 6px;border-radius:6px 0 6px 6px}.navbar-inverse .navbar-inner{background-color:#1b1b1b;background-image:-moz-linear-gradient(top,#222,#111);background-image:-webkit-gradient(linear,0 0,0 100%,from(#222),to(#111));background-image:-webkit-linear-gradient(top,#222,#111);background-image:-o-linear-gradient(top,#222,#111);background-image:linear-gradient(to bottom,#222,#111);background-repeat:repeat-x;border-color:#252525;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff222222',endColorstr='#ff111111',GradientType=0)}.navbar-inverse .brand,.navbar-inverse .nav>li>a{color:#999;text-shadow:0 -1px 0 rgba(0,0,0,0.25)}.navbar-inverse .brand:hover,.navbar-inverse .nav>li>a:hover,.navbar-inverse .brand:focus,.navbar-inverse .nav>li>a:focus{color:#fff}.navbar-inverse .brand{color:#999}.navbar-inverse .navbar-text{color:#999}.navbar-inverse .nav>li>a:focus,.navbar-inverse .nav>li>a:hover{color:#fff;background-color:transparent}.navbar-inverse .nav .active>a,.navbar-inverse .nav .active>a:hover,.navbar-inverse .nav .active>a:focus{color:#fff;background-color:#111}.navbar-inverse .navbar-link{color:#999}.navbar-inverse .navbar-link:hover,.navbar-inverse .navbar-link:focus{color:#fff}.navbar-inverse .divider-vertical{border-right-color:#222;border-left-color:#111}.navbar-inverse .nav li.dropdown.open>.dropdown-toggle,.navbar-inverse .nav li.dropdown.active>.dropdown-toggle,.navbar-inverse .nav li.dropdown.open.active>.dropdown-toggle{color:#fff;background-color:#111}.navbar-inverse .nav li.dropdown>a:hover .caret,.navbar-inverse .nav li.dropdown>a:focus .caret{border-top-color:#fff;border-bottom-color:#fff}.navbar-inverse .nav li.dropdown>.dropdown-toggle .caret{border-top-color:#999;border-bottom-color:#999}.navbar-inverse .nav li.dropdown.open>.dropdown-toggle .caret,.navbar-inverse .nav li.dropdown.active>.dropdown-toggle .caret,.navbar-inverse .nav li.dropdown.open.active>.dropdown-toggle .caret{border-top-color:#fff;border-bottom-color:#fff}.navbar-inverse .navbar-search .search-query{color:#fff;background-color:#515151;border-color:#111;-webkit-box-shadow:inset 0 1px 2px rgba(0,0,0,0.1),0 1px 0 rgba(255,255,255,0.15);-moz-box-shadow:inset 0 1px 2px rgba(0,0,0,0.1),0 1px 0 rgba(255,255,255,0.15);box-shadow:inset 0 1px 2px rgba(0,0,0,0.1),0 1px 0 rgba(255,255,255,0.15);-webkit-transition:none;-moz-transition:none;-o-transition:none;transition:none}.navbar-inverse .navbar-search .search-query:-moz-placeholder{color:#ccc}.navbar-inverse .navbar-search .search-query:-ms-input-placeholder{color:#ccc}.navbar-inverse .navbar-search .search-query::-webkit-input-placeholder{color:#ccc}.navbar-inverse .navbar-search .search-query:focus,.navbar-inverse .navbar-search .search-query.focused{padding:5px 15px;color:#333;text-shadow:0 1px 0 #fff;background-color:#fff;border:0;outline:0;-webkit-box-shadow:0 0 3px rgba(0,0,0,0.15);-moz-box-shadow:0 0 3px rgba(0,0,0,0.15);box-shadow:0 0 3px rgba(0,0,0,0.15)}.navbar-inverse .btn-navbar{color:#fff;text-shadow:0 -1px 0 rgba(0,0,0,0.25);background-color:#0e0e0e;*background-color:#040404;background-image:-moz-linear-gradient(top,#151515,#040404);background-image:-webkit-gradient(linear,0 0,0 100%,from(#151515),to(#040404));background-image:-webkit-linear-gradient(top,#151515,#040404);background-image:-o-linear-gradient(top,#151515,#040404);background-image:linear-gradient(to bottom,#151515,#040404);background-repeat:repeat-x;border-color:#040404 #040404 #000;border-color:rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff151515',endColorstr='#ff040404',GradientType=0);filter:progid:DXImageTransform.Microsoft.gradient(enabled=false)}.navbar-inverse .btn-navbar:hover,.navbar-inverse .btn-navbar:focus,.navbar-inverse .btn-navbar:active,.navbar-inverse .btn-navbar.active,.navbar-inverse .btn-navbar.disabled,.navbar-inverse .btn-navbar[disabled]{color:#fff;background-color:#040404;*background-color:#000}.navbar-inverse .btn-navbar:active,.navbar-inverse .btn-navbar.active{background-color:#000 \9}.breadcrumb{padding:8px 15px;margin:0 0 20px;list-style:none;background-color:#f5f5f5;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px}.breadcrumb>li{display:inline-block;*display:inline;text-shadow:0 1px 0 #fff;*zoom:1}.breadcrumb>li>.divider{padding:0 5px;color:#ccc}.breadcrumb>.active{color:#999}.pagination{margin:20px 0}.pagination ul{display:inline-block;*display:inline;margin-bottom:0;margin-left:0;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;*zoom:1;-webkit-box-shadow:0 1px 2px rgba(0,0,0,0.05);-moz-box-shadow:0 1px 2px rgba(0,0,0,0.05);box-shadow:0 1px 2px rgba(0,0,0,0.05)}.pagination ul>li{display:inline}.pagination ul>li>a,.pagination ul>li>span{float:left;padding:4px 12px;line-height:20px;text-decoration:none;background-color:#fff;border:1px solid #ddd;border-left-width:0}.pagination ul>li>a:hover,.pagination ul>li>a:focus,.pagination ul>.active>a,.pagination ul>.active>span{background-color:#f5f5f5}.pagination ul>.active>a,.pagination ul>.active>span{color:#999;cursor:default}.pagination ul>.disabled>span,.pagination ul>.disabled>a,.pagination ul>.disabled>a:hover,.pagination ul>.disabled>a:focus{color:#999;cursor:default;background-color:transparent}.pagination ul>li:first-child>a,.pagination ul>li:first-child>span{border-left-width:1px;-webkit-border-bottom-left-radius:4px;border-bottom-left-radius:4px;-webkit-border-top-left-radius:4px;border-top-left-radius:4px;-moz-border-radius-bottomleft:4px;-moz-border-radius-topleft:4px}.pagination ul>li:last-child>a,.pagination ul>li:last-child>span{-webkit-border-top-right-radius:4px;border-top-right-radius:4px;-webkit-border-bottom-right-radius:4px;border-bottom-right-radius:4px;-moz-border-radius-topright:4px;-moz-border-radius-bottomright:4px}.pagination-centered{text-align:center}.pagination-right{text-align:right}.pagination-large ul>li>a,.pagination-large ul>li>span{padding:11px 19px;font-size:17.5px}.pagination-large ul>li:first-child>a,.pagination-large ul>li:first-child>span{-webkit-border-bottom-left-radius:6px;border-bottom-left-radius:6px;-webkit-border-top-left-radius:6px;border-top-left-radius:6px;-moz-border-radius-bottomleft:6px;-moz-border-radius-topleft:6px}.pagination-large ul>li:last-child>a,.pagination-large ul>li:last-child>span{-webkit-border-top-right-radius:6px;border-top-right-radius:6px;-webkit-border-bottom-right-radius:6px;border-bottom-right-radius:6px;-moz-border-radius-topright:6px;-moz-border-radius-bottomright:6px}.pagination-mini ul>li:first-child>a,.pagination-small ul>li:first-child>a,.pagination-mini ul>li:first-child>span,.pagination-small ul>li:first-child>span{-webkit-border-bottom-left-radius:3px;border-bottom-left-radius:3px;-webkit-border-top-left-radius:3px;border-top-left-radius:3px;-moz-border-radius-bottomleft:3px;-moz-border-radius-topleft:3px}.pagination-mini ul>li:last-child>a,.pagination-small ul>li:last-child>a,.pagination-mini ul>li:last-child>span,.pagination-small ul>li:last-child>span{-webkit-border-top-right-radius:3px;border-top-right-radius:3px;-webkit-border-bottom-right-radius:3px;border-bottom-right-radius:3px;-moz-border-radius-topright:3px;-moz-border-radius-bottomright:3px}.pagination-small ul>li>a,.pagination-small ul>li>span{padding:2px 10px;font-size:11.9px}.pagination-mini ul>li>a,.pagination-mini ul>li>span{padding:0 6px;font-size:10.5px}.pager{margin:20px 0;text-align:center;list-style:none;*zoom:1}.pager:before,.pager:after{display:table;line-height:0;content:""}.pager:after{clear:both}.pager li{display:inline}.pager li>a,.pager li>span{display:inline-block;padding:5px 14px;background-color:#fff;border:1px solid #ddd;-webkit-border-radius:15px;-moz-border-radius:15px;border-radius:15px}.pager li>a:hover,.pager li>a:focus{text-decoration:none;background-color:#f5f5f5}.pager .next>a,.pager .next>span{float:right}.pager .previous>a,.pager .previous>span{float:left}.pager .disabled>a,.pager .disabled>a:hover,.pager .disabled>a:focus,.pager .disabled>span{color:#999;cursor:default;background-color:#fff}.modal-backdrop{position:fixed;top:0;right:0;bottom:0;left:0;z-index:1040;background-color:#000}.modal-backdrop.fade{opacity:0}.modal-backdrop,.modal-backdrop.fade.in{opacity:.8;filter:alpha(opacity=80)}.modal{position:fixed;top:10%;left:50%;z-index:1050;width:560px;margin-left:-280px;background-color:#fff;border:1px solid #999;border:1px solid rgba(0,0,0,0.3);*border:1px solid #999;-webkit-border-radius:6px;-moz-border-radius:6px;border-radius:6px;outline:0;-webkit-box-shadow:0 3px 7px rgba(0,0,0,0.3);-moz-box-shadow:0 3px 7px rgba(0,0,0,0.3);box-shadow:0 3px 7px rgba(0,0,0,0.3);-webkit-background-clip:padding-box;-moz-background-clip:padding-box;background-clip:padding-box}.modal.fade{top:-25%;-webkit-transition:opacity .3s linear,top .3s ease-out;-moz-transition:opacity .3s linear,top .3s ease-out;-o-transition:opacity .3s linear,top .3s ease-out;transition:opacity .3s linear,top .3s ease-out}.modal.fade.in{top:10%}.modal-header{padding:9px 15px;border-bottom:1px solid #eee}.modal-header .close{margin-top:2px}.modal-header h3{margin:0;line-height:30px}.modal-body{position:relative;max-height:400px;padding:15px;overflow-y:auto}.modal-form{margin-bottom:0}.modal-footer{padding:14px 15px 15px;margin-bottom:0;text-align:right;background-color:#f5f5f5;border-top:1px solid #ddd;-webkit-border-radius:0 0 6px 6px;-moz-border-radius:0 0 6px 6px;border-radius:0 0 6px 6px;*zoom:1;-webkit-box-shadow:inset 0 1px 0 #fff;-moz-box-shadow:inset 0 1px 0 #fff;box-shadow:inset 0 1px 0 #fff}.modal-footer:before,.modal-footer:after{display:table;line-height:0;content:""}.modal-footer:after{clear:both}.modal-footer .btn+.btn{margin-bottom:0;margin-left:5px}.modal-footer .btn-group .btn+.btn{margin-left:-1px}.modal-footer .btn-block+.btn-block{margin-left:0}.tooltip{position:absolute;z-index:1030;display:block;font-size:11px;line-height:1.4;opacity:0;filter:alpha(opacity=0);visibility:visible}.tooltip.in{opacity:.8;filter:alpha(opacity=80)}.tooltip.top{padding:5px 0;margin-top:-3px}.tooltip.right{padding:0 5px;margin-left:3px}.tooltip.bottom{padding:5px 0;margin-top:3px}.tooltip.left{padding:0 5px;margin-left:-3px}.tooltip-inner{max-width:200px;padding:8px;color:#fff;text-align:center;text-decoration:none;background-color:#000;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px}.tooltip-arrow{position:absolute;width:0;height:0;border-color:transparent;border-style:solid}.tooltip.top .tooltip-arrow{bottom:0;left:50%;margin-left:-5px;border-top-color:#000;border-width:5px 5px 0}.tooltip.right .tooltip-arrow{top:50%;left:0;margin-top:-5px;border-right-color:#000;border-width:5px 5px 5px 0}.tooltip.left .tooltip-arrow{top:50%;right:0;margin-top:-5px;border-left-color:#000;border-width:5px 0 5px 5px}.tooltip.bottom .tooltip-arrow{top:0;left:50%;margin-left:-5px;border-bottom-color:#000;border-width:0 5px 5px}.popover{position:absolute;top:0;left:0;z-index:1010;display:none;max-width:276px;padding:1px;text-align:left;white-space:normal;background-color:#fff;border:1px solid #ccc;border:1px solid rgba(0,0,0,0.2);-webkit-border-radius:6px;-moz-border-radius:6px;border-radius:6px;-webkit-box-shadow:0 5px 10px rgba(0,0,0,0.2);-moz-box-shadow:0 5px 10px rgba(0,0,0,0.2);box-shadow:0 5px 10px rgba(0,0,0,0.2);-webkit-background-clip:padding-box;-moz-background-clip:padding;background-clip:padding-box}.popover.top{margin-top:-10px}.popover.right{margin-left:10px}.popover.bottom{margin-top:10px}.popover.left{margin-left:-10px}.popover-title{padding:8px 14px;margin:0;font-size:14px;font-weight:normal;line-height:18px;background-color:#f7f7f7;border-bottom:1px solid #ebebeb;-webkit-border-radius:5px 5px 0 0;-moz-border-radius:5px 5px 0 0;border-radius:5px 5px 0 0}.popover-title:empty{display:none}.popover-content{padding:9px 14px}.popover .arrow,.popover .arrow:after{position:absolute;display:block;width:0;height:0;border-color:transparent;border-style:solid}.popover .arrow{border-width:11px}.popover .arrow:after{border-width:10px;content:""}.popover.top .arrow{bottom:-11px;left:50%;margin-left:-11px;border-top-color:#999;border-top-color:rgba(0,0,0,0.25);border-bottom-width:0}.popover.top .arrow:after{bottom:1px;margin-left:-10px;border-top-color:#fff;border-bottom-width:0}.popover.right .arrow{top:50%;left:-11px;margin-top:-11px;border-right-color:#999;border-right-color:rgba(0,0,0,0.25);border-left-width:0}.popover.right .arrow:after{bottom:-10px;left:1px;border-right-color:#fff;border-left-width:0}.popover.bottom .arrow{top:-11px;left:50%;margin-left:-11px;border-bottom-color:#999;border-bottom-color:rgba(0,0,0,0.25);border-top-width:0}.popover.bottom .arrow:after{top:1px;margin-left:-10px;border-bottom-color:#fff;border-top-width:0}.popover.left .arrow{top:50%;right:-11px;margin-top:-11px;border-left-color:#999;border-left-color:rgba(0,0,0,0.25);border-right-width:0}.popover.left .arrow:after{right:1px;bottom:-10px;border-left-color:#fff;border-right-width:0}.thumbnails{margin-left:-20px;list-style:none;*zoom:1}.thumbnails:before,.thumbnails:after{display:table;line-height:0;content:""}.thumbnails:after{clear:both}.row-fluid .thumbnails{margin-left:0}.thumbnails>li{float:left;margin-bottom:20px;margin-left:20px}.thumbnail{display:block;padding:4px;line-height:20px;border:1px solid #ddd;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,0.055);-moz-box-shadow:0 1px 3px rgba(0,0,0,0.055);box-shadow:0 1px 3px rgba(0,0,0,0.055);-webkit-transition:all .2s ease-in-out;-moz-transition:all .2s ease-in-out;-o-transition:all .2s ease-in-out;transition:all .2s ease-in-out}a.thumbnail:hover,a.thumbnail:focus{border-color:#08c;-webkit-box-shadow:0 1px 4px rgba(0,105,214,0.25);-moz-box-shadow:0 1px 4px rgba(0,105,214,0.25);box-shadow:0 1px 4px rgba(0,105,214,0.25)}.thumbnail>img{display:block;max-width:100%;margin-right:auto;margin-left:auto}.thumbnail .caption{padding:9px;color:#555}.media,.media-body{overflow:hidden;*overflow:visible;zoom:1}.media,.media .media{margin-top:15px}.media:first-child{margin-top:0}.media-object{display:block}.media-heading{margin:0 0 5px}.media>.pull-left{margin-right:10px}.media>.pull-right{margin-left:10px}.media-list{margin-left:0;list-style:none}.label,.badge{display:inline-block;padding:2px 4px;font-size:11.844px;font-weight:bold;line-height:14px;color:#fff;text-shadow:0 -1px 0 rgba(0,0,0,0.25);white-space:nowrap;vertical-align:baseline;background-color:#999}.label{-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px}.badge{padding-right:9px;padding-left:9px;-webkit-border-radius:9px;-moz-border-radius:9px;border-radius:9px}.label:empty,.badge:empty{display:none}a.label:hover,a.label:focus,a.badge:hover,a.badge:focus{color:#fff;text-decoration:none;cursor:pointer}.label-important,.badge-important{background-color:#b94a48}.label-important[href],.badge-important[href]{background-color:#953b39}.label-warning,.badge-warning{background-color:#f89406}.label-warning[href],.badge-warning[href]{background-color:#c67605}.label-success,.badge-success{background-color:#468847}.label-success[href],.badge-success[href]{background-color:#356635}.label-info,.badge-info{background-color:#3a87ad}.label-info[href],.badge-info[href]{background-color:#2d6987}.label-inverse,.badge-inverse{background-color:#333}.label-inverse[href],.badge-inverse[href]{background-color:#1a1a1a}.btn .label,.btn .badge{position:relative;top:-1px}.btn-mini .label,.btn-mini .badge{top:0}@-webkit-keyframes progress-bar-stripes{from{background-position:40px 0}to{background-position:0 0}}@-moz-keyframes progress-bar-stripes{from{background-position:40px 0}to{background-position:0 0}}@-ms-keyframes progress-bar-stripes{from{background-position:40px 0}to{background-position:0 0}}@-o-keyframes progress-bar-stripes{from{background-position:0 0}to{background-position:40px 0}}@keyframes progress-bar-stripes{from{background-position:40px 0}to{background-position:0 0}}.progress{height:20px;margin-bottom:20px;overflow:hidden;background-color:#f7f7f7;background-image:-moz-linear-gradient(top,#f5f5f5,#f9f9f9);background-image:-webkit-gradient(linear,0 0,0 100%,from(#f5f5f5),to(#f9f9f9));background-image:-webkit-linear-gradient(top,#f5f5f5,#f9f9f9);background-image:-o-linear-gradient(top,#f5f5f5,#f9f9f9);background-image:linear-gradient(to bottom,#f5f5f5,#f9f9f9);background-repeat:repeat-x;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#fff5f5f5',endColorstr='#fff9f9f9',GradientType=0);-webkit-box-shadow:inset 0 1px 2px rgba(0,0,0,0.1);-moz-box-shadow:inset 0 1px 2px rgba(0,0,0,0.1);box-shadow:inset 0 1px 2px rgba(0,0,0,0.1)}.progress .bar{float:left;width:0;height:100%;font-size:12px;color:#fff;text-align:center;text-shadow:0 -1px 0 rgba(0,0,0,0.25);background-color:#0e90d2;background-image:-moz-linear-gradient(top,#149bdf,#0480be);background-image:-webkit-gradient(linear,0 0,0 100%,from(#149bdf),to(#0480be));background-image:-webkit-linear-gradient(top,#149bdf,#0480be);background-image:-o-linear-gradient(top,#149bdf,#0480be);background-image:linear-gradient(to bottom,#149bdf,#0480be);background-repeat:repeat-x;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff149bdf',endColorstr='#ff0480be',GradientType=0);-webkit-box-shadow:inset 0 -1px 0 rgba(0,0,0,0.15);-moz-box-shadow:inset 0 -1px 0 rgba(0,0,0,0.15);box-shadow:inset 0 -1px 0 rgba(0,0,0,0.15);-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;-webkit-transition:width .6s ease;-moz-transition:width .6s ease;-o-transition:width .6s ease;transition:width .6s ease}.progress .bar+.bar{-webkit-box-shadow:inset 1px 0 0 rgba(0,0,0,0.15),inset 0 -1px 0 rgba(0,0,0,0.15);-moz-box-shadow:inset 1px 0 0 rgba(0,0,0,0.15),inset 0 -1px 0 rgba(0,0,0,0.15);box-shadow:inset 1px 0 0 rgba(0,0,0,0.15),inset 0 -1px 0 rgba(0,0,0,0.15)}.progress-striped .bar{background-color:#149bdf;background-image:-webkit-gradient(linear,0 100%,100% 0,color-stop(0.25,rgba(255,255,255,0.15)),color-stop(0.25,transparent),color-stop(0.5,transparent),color-stop(0.5,rgba(255,255,255,0.15)),color-stop(0.75,rgba(255,255,255,0.15)),color-stop(0.75,transparent),to(transparent));background-image:-webkit-linear-gradient(45deg,rgba(255,255,255,0.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,0.15) 50%,rgba(255,255,255,0.15) 75%,transparent 75%,transparent);background-image:-moz-linear-gradient(45deg,rgba(255,255,255,0.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,0.15) 50%,rgba(255,255,255,0.15) 75%,transparent 75%,transparent);background-image:-o-linear-gradient(45deg,rgba(255,255,255,0.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,0.15) 50%,rgba(255,255,255,0.15) 75%,transparent 75%,transparent);background-image:linear-gradient(45deg,rgba(255,255,255,0.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,0.15) 50%,rgba(255,255,255,0.15) 75%,transparent 75%,transparent);-webkit-background-size:40px 40px;-moz-background-size:40px 40px;-o-background-size:40px 40px;background-size:40px 40px}.progress.active .bar{-webkit-animation:progress-bar-stripes 2s linear infinite;-moz-animation:progress-bar-stripes 2s linear infinite;-ms-animation:progress-bar-stripes 2s linear infinite;-o-animation:progress-bar-stripes 2s linear infinite;animation:progress-bar-stripes 2s linear infinite}.progress-danger .bar,.progress .bar-danger{background-color:#dd514c;background-image:-moz-linear-gradient(top,#ee5f5b,#c43c35);background-image:-webkit-gradient(linear,0 0,0 100%,from(#ee5f5b),to(#c43c35));background-image:-webkit-linear-gradient(top,#ee5f5b,#c43c35);background-image:-o-linear-gradient(top,#ee5f5b,#c43c35);background-image:linear-gradient(to bottom,#ee5f5b,#c43c35);background-repeat:repeat-x;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffee5f5b',endColorstr='#ffc43c35',GradientType=0)}.progress-danger.progress-striped .bar,.progress-striped .bar-danger{background-color:#ee5f5b;background-image:-webkit-gradient(linear,0 100%,100% 0,color-stop(0.25,rgba(255,255,255,0.15)),color-stop(0.25,transparent),color-stop(0.5,transparent),color-stop(0.5,rgba(255,255,255,0.15)),color-stop(0.75,rgba(255,255,255,0.15)),color-stop(0.75,transparent),to(transparent));background-image:-webkit-linear-gradient(45deg,rgba(255,255,255,0.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,0.15) 50%,rgba(255,255,255,0.15) 75%,transparent 75%,transparent);background-image:-moz-linear-gradient(45deg,rgba(255,255,255,0.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,0.15) 50%,rgba(255,255,255,0.15) 75%,transparent 75%,transparent);background-image:-o-linear-gradient(45deg,rgba(255,255,255,0.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,0.15) 50%,rgba(255,255,255,0.15) 75%,transparent 75%,transparent);background-image:linear-gradient(45deg,rgba(255,255,255,0.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,0.15) 50%,rgba(255,255,255,0.15) 75%,transparent 75%,transparent)}.progress-success .bar,.progress .bar-success{background-color:#5eb95e;background-image:-moz-linear-gradient(top,#62c462,#57a957);background-image:-webkit-gradient(linear,0 0,0 100%,from(#62c462),to(#57a957));background-image:-webkit-linear-gradient(top,#62c462,#57a957);background-image:-o-linear-gradient(top,#62c462,#57a957);background-image:linear-gradient(to bottom,#62c462,#57a957);background-repeat:repeat-x;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff62c462',endColorstr='#ff57a957',GradientType=0)}.progress-success.progress-striped .bar,.progress-striped .bar-success{background-color:#62c462;background-image:-webkit-gradient(linear,0 100%,100% 0,color-stop(0.25,rgba(255,255,255,0.15)),color-stop(0.25,transparent),color-stop(0.5,transparent),color-stop(0.5,rgba(255,255,255,0.15)),color-stop(0.75,rgba(255,255,255,0.15)),color-stop(0.75,transparent),to(transparent));background-image:-webkit-linear-gradient(45deg,rgba(255,255,255,0.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,0.15) 50%,rgba(255,255,255,0.15) 75%,transparent 75%,transparent);background-image:-moz-linear-gradient(45deg,rgba(255,255,255,0.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,0.15) 50%,rgba(255,255,255,0.15) 75%,transparent 75%,transparent);background-image:-o-linear-gradient(45deg,rgba(255,255,255,0.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,0.15) 50%,rgba(255,255,255,0.15) 75%,transparent 75%,transparent);background-image:linear-gradient(45deg,rgba(255,255,255,0.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,0.15) 50%,rgba(255,255,255,0.15) 75%,transparent 75%,transparent)}.progress-info .bar,.progress .bar-info{background-color:#4bb1cf;background-image:-moz-linear-gradient(top,#5bc0de,#339bb9);background-image:-webkit-gradient(linear,0 0,0 100%,from(#5bc0de),to(#339bb9));background-image:-webkit-linear-gradient(top,#5bc0de,#339bb9);background-image:-o-linear-gradient(top,#5bc0de,#339bb9);background-image:linear-gradient(to bottom,#5bc0de,#339bb9);background-repeat:repeat-x;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff5bc0de',endColorstr='#ff339bb9',GradientType=0)}.progress-info.progress-striped .bar,.progress-striped .bar-info{background-color:#5bc0de;background-image:-webkit-gradient(linear,0 100%,100% 0,color-stop(0.25,rgba(255,255,255,0.15)),color-stop(0.25,transparent),color-stop(0.5,transparent),color-stop(0.5,rgba(255,255,255,0.15)),color-stop(0.75,rgba(255,255,255,0.15)),color-stop(0.75,transparent),to(transparent));background-image:-webkit-linear-gradient(45deg,rgba(255,255,255,0.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,0.15) 50%,rgba(255,255,255,0.15) 75%,transparent 75%,transparent);background-image:-moz-linear-gradient(45deg,rgba(255,255,255,0.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,0.15) 50%,rgba(255,255,255,0.15) 75%,transparent 75%,transparent);background-image:-o-linear-gradient(45deg,rgba(255,255,255,0.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,0.15) 50%,rgba(255,255,255,0.15) 75%,transparent 75%,transparent);background-image:linear-gradient(45deg,rgba(255,255,255,0.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,0.15) 50%,rgba(255,255,255,0.15) 75%,transparent 75%,transparent)}.progress-warning .bar,.progress .bar-warning{background-color:#faa732;background-image:-moz-linear-gradient(top,#fbb450,#f89406);background-image:-webkit-gradient(linear,0 0,0 100%,from(#fbb450),to(#f89406));background-image:-webkit-linear-gradient(top,#fbb450,#f89406);background-image:-o-linear-gradient(top,#fbb450,#f89406);background-image:linear-gradient(to bottom,#fbb450,#f89406);background-repeat:repeat-x;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#fffbb450',endColorstr='#fff89406',GradientType=0)}.progress-warning.progress-striped .bar,.progress-striped .bar-warning{background-color:#fbb450;background-image:-webkit-gradient(linear,0 100%,100% 0,color-stop(0.25,rgba(255,255,255,0.15)),color-stop(0.25,transparent),color-stop(0.5,transparent),color-stop(0.5,rgba(255,255,255,0.15)),color-stop(0.75,rgba(255,255,255,0.15)),color-stop(0.75,transparent),to(transparent));background-image:-webkit-linear-gradient(45deg,rgba(255,255,255,0.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,0.15) 50%,rgba(255,255,255,0.15) 75%,transparent 75%,transparent);background-image:-moz-linear-gradient(45deg,rgba(255,255,255,0.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,0.15) 50%,rgba(255,255,255,0.15) 75%,transparent 75%,transparent);background-image:-o-linear-gradient(45deg,rgba(255,255,255,0.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,0.15) 50%,rgba(255,255,255,0.15) 75%,transparent 75%,transparent);background-image:linear-gradient(45deg,rgba(255,255,255,0.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,0.15) 50%,rgba(255,255,255,0.15) 75%,transparent 75%,transparent)}.accordion{margin-bottom:20px}.accordion-group{margin-bottom:2px;border:1px solid #e5e5e5;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px}.accordion-heading{border-bottom:0}.accordion-heading .accordion-toggle{display:block;padding:8px 15px}.accordion-toggle{cursor:pointer}.accordion-inner{padding:9px 15px;border-top:1px solid #e5e5e5}.carousel{position:relative;margin-bottom:20px;line-height:1}.carousel-inner{position:relative;width:100%;overflow:hidden}.carousel-inner>.item{position:relative;display:none;-webkit-transition:.6s ease-in-out left;-moz-transition:.6s ease-in-out left;-o-transition:.6s ease-in-out left;transition:.6s ease-in-out left}.carousel-inner>.item>img,.carousel-inner>.item>a>img{display:block;line-height:1}.carousel-inner>.active,.carousel-inner>.next,.carousel-inner>.prev{display:block}.carousel-inner>.active{left:0}.carousel-inner>.next,.carousel-inner>.prev{position:absolute;top:0;width:100%}.carousel-inner>.next{left:100%}.carousel-inner>.prev{left:-100%}.carousel-inner>.next.left,.carousel-inner>.prev.right{left:0}.carousel-inner>.active.left{left:-100%}.carousel-inner>.active.right{left:100%}.carousel-control{position:absolute;top:40%;left:15px;width:40px;height:40px;margin-top:-20px;font-size:60px;font-weight:100;line-height:30px;color:#fff;text-align:center;background:#222;border:3px solid #fff;-webkit-border-radius:23px;-moz-border-radius:23px;border-radius:23px;opacity:.5;filter:alpha(opacity=50)}.carousel-control.right{right:15px;left:auto}.carousel-control:hover,.carousel-control:focus{color:#fff;text-decoration:none;opacity:.9;filter:alpha(opacity=90)}.carousel-indicators{position:absolute;top:15px;right:15px;z-index:5;margin:0;list-style:none}.carousel-indicators li{display:block;float:left;width:10px;height:10px;margin-left:5px;text-indent:-999px;background-color:#ccc;background-color:rgba(255,255,255,0.25);border-radius:5px}.carousel-indicators .active{background-color:#fff}.carousel-caption{position:absolute;right:0;bottom:0;left:0;padding:15px;background:#333;background:rgba(0,0,0,0.75)}.carousel-caption h4,.carousel-caption p{line-height:20px;color:#fff}.carousel-caption h4{margin:0 0 5px}.carousel-caption p{margin-bottom:0}.hero-unit{padding:60px;margin-bottom:30px;font-size:18px;font-weight:200;line-height:30px;color:inherit;background-color:#eee;-webkit-border-radius:6px;-moz-border-radius:6px;border-radius:6px}.hero-unit h1{margin-bottom:0;font-size:60px;line-height:1;letter-spacing:-1px;color:inherit}.hero-unit li{line-height:30px}.pull-right{float:right}.pull-left{float:left}.hide{display:none}.show{display:block}.invisible{visibility:hidden}.affix{position:fixed}
</style><?php }
			function DBSave(){$db=new SQLDump(new mysqli(config('db_host'),config('db_user'),config('db_passwort'),config('db_name')));$db->save(date('Y-m-d_H-i-s').'_owsforallphp.sql.gz');}
class SQLDump{const NONE=0,DROP=1,CREATE=2,DATA=4,TRIGGERS=8,ALL=15,MAX_SQL_SIZE=1e6;public$tables=['*'=>self::ALL,],$charset='utf8';private$connection;
			function __construct(mysqli$connection){$this->connection=$connection;if($connection->connect_errno)throw new Exception($connection->connect_error);}
			function save($file){$handle=strcasecmp(substr($file,-3),'.gz')?fopen($file,'wb'):gzopen($file,'wb');if(!$handle)throw new Exception("ERROR: Cannot write file '$file'.");$this->write($handle);}
	  		function write($handle=null){if($handle===null)$handle=fopen('php://output','wb');elseif(!is_resource($handle)||get_resource_type($handle)!=='stream')throw new Exception('Argument must be stream resource.');$tables=$views=[];
							$res=$this->connection->query('SHOW FULL TABLES');while($row=$res->fetch_row()){if($row[1]==='VIEW')$views[]=$row[0];else$tables[]=$row[0];}$res->close();$tables=array_merge($tables,$views);
							$this->connection->query('LOCK TABLES `'.implode('` READ,`',$tables).'`READ');$db=$this->connection->query('SELECT DATABASE()')->fetch_row();foreach($tables as$table)$this->dumpTable($handle,$table);$this->connection->query('UNLOCK TABLES');}
			function dumpTable($handle,$table){$mode=isset($this->tables[$table])?$this->tables[$table]:$this->tables['*'];if($mode===self::NONE)return;$delTable=$this->delimite($table);$res=$this->connection->query("SHOW CREATE TABLE $delTable");
							$row=$res->fetch_assoc();$res->close();$view=isset($row['Create View']);if($mode&self::DROP)fwrite($handle,'DROP '.($view?'VIEW':'TABLE')." IF EXISTS $delTable;\n");if($mode&self::CREATE)fwrite($handle,$row[$view?'Create View':'Create Table'].
							";\n");if(!$view&&($mode&self::DATA)){fwrite($handle,'ALTER '.($view?'VIEW':'TABLE').$delTable."DISABLE KEYS;\n");$numeric =[];$res=$this->connection->query("SHOW COLUMNS FROM$delTable");$cols=[];while($row=$res->fetch_assoc()){
							$col=$row['Field'];$cols[]=$this->delimite($col);$numeric[$col]=(bool)preg_match('#^[^(]*(BYTE|COUNTER|SERIAL|INT|LONG$|CURRENCY|REAL|MONEY|FLOAT|DOUBLE|DECIMAL|NUMERIC|NUMBER)#i',$row['Type']);}$cols='('.implode(',',$cols).')';
							$res->close();$size=0;$res=$this->connection->query("SELECT*FROM $delTable",MYSQLI_USE_RESULT);while($row=$res->fetch_assoc()){$s='(';foreach($row as$key=>$value){if($value===null)$s.="NULL,\t";elseif($numeric[$key])$s.=$value.",\t";
							else$s.="'".$this->connection->real_escape_string($value)."',\t";}if($size==0)$s="INSERT INTO $delTable$cols VALUES\n$s";else$s=",\n$s";$len=strlen($s)-1;$s[$len-1]=')';fwrite($handle,$s,$len);$size+=$len;if($size>self::MAX_SQL_SIZE){
							fwrite($handle,";\n");$size=0;}}$res->close();if($size)fwrite($handle,";\n");fwrite($handle,'ALTER '.($view?'VIEW':'TABLE').$delTable."ENABLE KEYS;\n");}}
			function delimite($s){return'`'.str_replace('`','``',$s).'`';}}
function Diagnosis(){
class benchmarkTimer{var $startTime,$totalTime=0;
			function start(){list($usc,$string_ec)=explode(' ',microtime());$this->startTime=((float)$usc+(float)$string_ec);}
			function stop($time_itle){list($usec,$string_ec)=explode(' ',microtime());$time=((float)$usec+(float)$string_ec)-$this->startTime;$this->totalTime+=$time;}}
			function _getallheaders(){foreach($_SERVER as$name=>$value){if(substr($name,0,5)=='HTTP_'){$name=str_replace(' ','-',ucwords(strtolower(str_replace('_',' ',substr($name,5)))));$headers[$name]=$value;}elseif($name=='CONTENT_TYPE')$headers['Content-Type']=
							$value;elseif($name=='CONTENT_LENGTH')$headers['Content-Length']=$value;}return@$headers;}
			function RequestTime(){$starttime=microtime(true);foreach(_getallheaders()as$name=>$value);$stoptime=microtime(true);$status=0;$status=($stoptime-$starttime)*1000000;$status=floor($status);return$status;}
							if(function_exists('date_default_timezone_set'))date_default_timezone_set('UTC');$_SERVER['HTTPS']='on';$timer=new benchmarkTimer();
			function simple(){$a=0;for($i=0;$i<900000;++$i)++$a;$thisisanotherlongname=0;for($thisisalongname=0;$thisisalongname<900000;++$thisisalongname)++$thisisanotherlongname;}
			function simplecall(){for($i=0;$i<900000;++$i)strlen('hallo');}
			function hallo($a){}
			function simpleucall(){for($i=0;$i<900000;++$i)hallo('hallo');}
			function simpleudcall(){for($i=0;$i<900000;++$i)hallo2('hallo');}
			function hallo2($a){}
			function mandel(){$w1=92;$h1=843;$recen=-.45;$imcen=0.0;$r=0.7;$s=0;$rec=0;$imc=0;$re=0;$im=0;$re2=0;$im2=0;$x=0;$y=0;$w2=0;$h2=0;$color=0;$s=2*$r/$w1;$w2=40;$h2=12;for($y=0;$y<=$w1;$y=$y+1){$imc=$s*($y-$h2)+$imcen;for($x=0;$x<=$h1;$x=$x+1){$rec=$s*($x-$w2)+
							$recen;$re=$rec;$im=$imc;$color=1000;$re2=$re*$re;$im2=$im*$im;while(((($re2+$im2)<900000)&&$color)){$im=$re*$im*2+$imc;$re=$re2-$im2+$rec;$re2=$re*$re;$im2=$im*$im;$color=$color-1;}if($color==0)print'_';else print'#';}print'<br>';flush();}}
			function mandel2(){$b=' .:,;!/>)|&IH%*#';for($y=30;printf('\n'),$C=$y*0.1-1.5,--$y;){for($x=0;$c=$x*0.04-2,$z=0,$Z=0,++$x<75;){for($r=$c,$i=$C,$k=0;$t=$z*$z-$Z*$Z+$r,$Z=2*$z*$Z+$i,$z=$t,$k<5000;++$k)if($z*$z+$Z*$Z>2000000)break;echo$b[$k%16];}}}
			function Ack($m,$n){if($m==0)return$n+1;if($n==0)return Ack($m-1,1);return Ack($m-1,Ack($m,($n-1)));}
			function ackermann($n){$r=Ack(3,$n);print'Ack(3,$n): $r\n';}
			function ary($n){for($i=0;$i<$n;++$i)$X[$i]=$i;for($i=$n-1;$i>=0;--$i)$Y[$i]=$X[$i];$last=$n-1;print'$Y[$last]\n';}
			function ary2($n){for($i=0;$i<$n;){$X[$i]=$i;++$i;$X[$i]=$i;++$i;$X[$i]=$i;++$i;$X[$i]=$i;++$i;$X[$i]=$i;++$i;$X[$i]=$i;++$i;$X[$i]=$i;++$i;$X[$i]=$i;++$i;$X[$i]=$i;++$i;$X[$i]=$i;++$i;}for($i=$n-1;$i>=0;){$Y[$i]=$X[$i];--$i;$Y[$i]=$X[$i];--$i;$Y[$i]=$X[$i];
							--$i;$Y[$i]=$X[$i];--$i;$Y[$i]=$X[$i];--$i;$Y[$i]=$X[$i];--$i;$Y[$i]=$X[$i];--$i;$Y[$i]=$X[$i];--$i;$Y[$i]=$X[$i];--$i;$Y[$i]=$X[$i];--$i;}$last=$n-1;print'$Y[$last]\n';}
			function ary3($n){for($i=0;$i<$n;++$i){$X[$i]=$i+1;$Y[$i]=0;}for($k=0;$k<1000;++$k)for($i=$n-1;$i>=0;--$i)$Y[$i]+=$X[$i];$last=$n-1;print'$Y[0] $Y[$last]\n';}
			function fibo_r($n){return(($n<2)?1:fibo_r($n-2)+fibo_r($n-1));}
			function fibo($n){$r=fibo_r($n);print'$r\n';}
			function hash1($n){for($i=1;$i<=$n;++$i)$X[dechex($i)]=$i;$c=0;for($i=$n;$i>0;--$i)if($X[dechex($i)]){++$c;}print'$c\n';}
			function hash2($n){for($i=0;$i<$n;++$i){$hash1['foo_$i']=$i;$hash2['foo_$i']=0;}for($i=$n;$i>0;--$i)foreach($hash1 as$key=>$value)$hash2[$key]+=$value;$first='foo_0';$last='foo_'.($n-1);print'$hash1[$first]$hash1[$last]$hash2[$first]$hash2[$last]\n';}
			function gen_random($n){global$LAST;return(($n*($LAST=($LAST*3877+29573)%139968))/139968);}
			function heapsort_r($n,&$ra){$l=($n>>1)+1;$ir=$n;while(1){if($l>1)$rra=$ra[--$l];else{$rra=$ra[$ir];$ra[$ir]=$ra[1];if(--$ir==1){$ra[1]=$rra;return;}}$i=$l;$j=$l<<1;while($j<=$ir){if(($j<$ir)&&($ra[$j]<$ra[$j+1]))++$j;if($rra<$ra[$j]){$ra[$i]=$ra[$j];
							$j+=($i=$j);}else$j=$ir+1;}$ra[$i]=$rra;}}
			function heapsort($N){global$LAST;$LAST=42;for($i=1;$i<=$N;++$i)$ary[$i]=gen_random(1);heapsort_r($N,$ary);printf('%.10f\n',$ary[$N]);}
			function mkmatrix($rows,$cols){$count=1;$mx=[];for($i=0;$i<$rows;++$i)for($j=0;$j<$cols;++$j)$mx[$i][$j]=++$count;return($mx);}
			function mmult($rows,$cols,$m1,$m2){$m3=[];for($i=0;$i<$rows;++$i){for($j=0;$j<$cols;++$j){$x=0;for($k=0;$k<$cols;++$k)$x+=$m1[$i][$k]*$m2[$k][$j];$m3[$i][$j]=$x;}}return($m3);}
			function matrix($n){$SIZE=30;$m1=mkmatrix($SIZE,$SIZE);$m2=mkmatrix($SIZE,$SIZE);while(--$n)$mm=mmult($SIZE,$SIZE,$m1,$m2);print'{$mm[0][0]} {$mm[2][3]} {$mm[3][2]} {$mm[4][4]}\n';}
			function nestedloop($n){$x=0;for($a=0;$a<$n;++$a)for($b=0;$b<$n;++$b)for($c=0;$c<$n;++$c)for($d=0;$d<$n;++$d)for($e=0;$e<$n;++$e)for($f=0;$f<$n;++$f)++$x;print'$x\n';}
			function sieve($n){$count=0;while(--$n){$count=0;$flags=range(0,8192);for($i=2;$i<8193;++$i){if($flags[$i]){for($k=$i+$i;$k<=8192;$k+=$i)$flags[$k]=0;++$count;}}}print'Count: $count\n';}
			function strcat($n){$str='';while(--$n)$str.='hello\n';$len=strlen($str);print'$len\n';}
			function getmicrotime(){$t=gettimeofday();return($t['sec']+$t['usec']/1000000);}
			function start_test(){ob_start();return getmicrotime();}
			function end_test($start,$name){global$total;$end=getmicrotime();ob_end_clean();$total+=$end-$start;$num=number_format($end-$start,1);ob_start();return getmicrotime();}
			function total(){global$total;$num=number_format($total,1);echo'<br>Rating Note = '.(round($num*1.668));}$t0=$t=start_test();simple();$t=end_test($t,'simple');simplecall();$t=end_test($t,'simplecall');simpleucall();$t=end_test($t,'simpleucall');
							simpleudcall();$t=end_test($t,'simpleudcall');mandel();$t=end_test($t,'mandel');mandel2();$t=end_test($t,'mandel2');ackermann(7);$t=end_test($t,'ackermann(7)');ary(50000);$t=end_test($t,'ary(50000)');ary2(50000);$t=end_test($t,'ary2(50000)');
							ary3(2000);$t=end_test($t,'ary3(2000)');	fibo(30);$t=end_test($t,'fibo(30)');hash1(50000);$t=end_test($t,'hash1(50000)');hash2(500);$t=end_test($t,'hash2(500)');heapsort(20000);$t=end_test($t,'heapsort(20000)');matrix(20);
							$t=end_test($t,'matrix(20)');nestedloop(12);$t=end_test($t,'nestedloop(12)');sieve(30);$t=end_test($t,'sieve(30)');strcat(200000);$t=end_test($t,'strcat(200000)');total();
echo"<pre>
Diagnosis from  : ".date('m/d/Y H:i:s')."
Eigene-Adresse  : $_SERVER[REMOTE_ADDR]
Request-Time    : ".RequestTime()." Milli-Sec.
Protokoll       : $_SERVER[SERVER_PROTOCOL]
Server          : $_SERVER[SERVER_NAME]
Server-Adresse  : $_SERVER[SERVER_ADDR]
Platform        : ".PHP_OS."
Webserver       : $_SERVER[SERVER_SOFTWARE]
Serversoftware  : ".php_uname()."
PHP version     : ".phpversion().' with '.strtoupper(php_sapi_name()).'-Prozessmanager';
echo'<br>Memory Limit	: '.ini_get('memory_limit');
if(extension_loaded('ionCube Loader'))echo'<br>IonCube         : Loader is present.';if(extension_loaded('apcu'))echo'<br>APCu-Cache      : is present.';if(extension_loaded('Zend OPcache'))echo'<br>OpCache         : is present';
else echo'<br>OpCache		: is not present!';echo'<br>Script Laufzeit : maximum '.ini_get('max_execution_time').' Sec.';echo'<br>BaseDir         : '.$_SERVER["DOCUMENT_ROOT"];$runs=500000;$runs_slow=5000;$string_1='Peter & Jens & Thomas & Karl & ich & du sind &&&&=%';
$string_2='     wie      ';$string_3=strtoupper($string_1);$string_4='1234a';$string_5='64x32';$string_6='Dies ist ein Link nach http://openwebsoccer.de';$string_7='Die Nummer %d ist wie der String %s der wie eine hex %x ausgegeben wird';
$string_8=$string_7.' and then some';$string_9='quotes\'are "fun" to use\'. Most of the time. \\ ya';$array_1=['a','b','c','d','e','f','g','h'=>1,'i'=>2,'j'=>NULL];$array_2=['Kaffee','Tee','Coffein'];$time_1='29/11/2011 Datum 10:15:37 Zeit';$now=time();$timer->start();
$i=0;while($i>NULL)--$i;for($i=NULL;$i<$runs;++$i);for($i=NULL;$i<$runs;++$i){$z=$i%4;if($z==NULL){}elseif($z==1){}elseif($z==2){}else{}}for($i=NULL;$i<$runs;++$i){$z=$i%4;switch($z){case 0: break;case 1:break;case 2:break;default:break;}}
for($i=NULL;$i<$runs;++$i){$z=($i%2==NULL?1:0);}for($i=NULL;$i<$runs;++$i)str_replace('&','&amp;',$string_1);for($i=NULL;$i<$runs_slow;++$i)preg_replace('#(^|\s)(http[s]?://\w+[^\s\[\]\<]+)#i',"\1<a href='\2'>\2</a>",$string_6);
for($i=NULL;$i<$runs;++$i)preg_match('#http[s]?://\w+[^\s\[\]\<]+#',$string_6);for($i=NULL;$i<$runs;++$i)count($array_1);for($i=NULL;$i<$runs;++$i){isset($array_1['i']);isset($array_1['zzNozz']);}for($i=NULL;$i<$runs;++$i)time();
for($i=NULL;$i<$runs;++$i)strlen($string_1);for($i=NULL;$i<$runs;++$i)sprintf($string_7,$i,$string_5,$i);for($i=NULL;$i<$runs;++$i)strcmp($string_7,$string_8);for($i=NULL;$i<$runs;++$i)trim($string_2);for($i=NULL;$i<$runs_slow;++$i)explode('&',$string_1);
for($i=NULL;$i<$runs;++$i)implode('&',$array_1);$f1=$timer->totalTime;for($i=NULL;$i<$runs;++$i)number_format($f1,3);for($i=NULL;$i<$runs;++$i)floor($f1);for($i=NULL;$i<$runs;++$i)strpos($string_2,'t');for($i=NULL;$i<$runs;++$i)substr($string_1,10);
for($i=NULL;$i<$runs;++$i)intval($string_4);for($i=NULL;$i<$runs;++$i)(int)$string_4;for($i=NULL;$i<$runs;++$i){is_array($array_1);is_array($string_1);}for($i=NULL;$i<$runs;++$i){is_numeric($f1);is_numeric($string_4);}
for($i=NULL;$i<$runs;++$i){is_int($f1);is_int($string_4);}for($i=NULL;$i<$runs;++$i){is_string($f1);is_string($string_4);}for($i=NULL;$i<$runs;++$i)ip2long('1.2.3.4');for($i=NULL;$i<$runs;++$i)long2ip(89851921);for($i=NULL;$i<$runs_slow;++$i)date('F j,Y,g:i a',$now);
for($i=NULL;$i<$runs_slow;++$i)date('%B %e,%Y,%l:%M %P',$now);for($i=NULL;$i<$runs_slow;++$i)strtotime($time_1);for($i=NULL;$i<$runs;++$i)strtolower($string_3);for($i=NULL;$i<$runs;++$i)strtoupper($string_1);for($i=NULL;$i<$runs;++$i)hash('sha256',$string_1);
for($i=NULL;$i<$runs;++$i){unset($array_1['j']);$array_1['j']=NULL;}for($i=NULL;$i<$runs;++$i)list($drink,$runsolor,$power)=$array_2;for($i=NULL;$i<$runs;++$i)urlencode($string_1);$string_1e=urlencode($string_1);for($i=NULL;$i<$runs;++$i)urldecode($string_1e);
for($i=NULL;$i<$runs;++$i)addslashes($string_9);$string_9e=addslashes($string_9);for($i=NULL;$i<$runs;++$i)stripslashes($string_9e);$timer->stop('');echo'<br>PHP Benchmark   : Referenztime PHP 8.2.8 : 1.0 Sec.';
echo@$head.'<br>'.str_pad('PHP Benchmark   : Server       PHP '.PHP_VERSION,23).' : '.number_format($timer->totalTime,1).' Sec.</pre>';
							if(function_exists(phpinfo())){phpinfo();$phpinfo=ob_get_contents();$phpinfo=preg_replace('/<\/div><\/body><\/html>/','',$phpinfo);$hr='<div style="width:100%;background:#000;height:10px;margin-bottom:1em;"></div>'.PHP_EOL;ob_start();
							echo'<table border="0" cellpadding="3" width="600">'.PHP_EOL;echo'<tr class="h"><td><a href="http://www.php.net/">';echo'<img border="0"src="http://static.php.net/www.php.net/images/php.gif"alt="PHP Logo"/>';echo'</a>
							<h1 class="p">PHP Extensions</h1>'.PHP_EOL;echo'</td></tr>'.PHP_EOL;echo'</table>'.PHP_EOL;echo'<h2>geladene Erweiterungen</h2>'.PHP_EOL;echo'<table border="0"cellpadding="3"width="600">'.PHP_EOL;echo'<tr><td class="e">Extensions</td>
							<td class="v">'.PHP_EOL;foreach(get_loaded_extensions()as$ext)$exts[]=$ext;echo implode(',',$exts).PHP_EOL;echo'</td></tr></table><br />'.PHP_EOL;echo'<h2>enthaltene Funktionen</h2>'.PHP_EOL;echo'<table border="0"cellpadding="3"width="600">'.
							PHP_EOL;foreach($exts as$ext)$extensions=get_loaded_extensions();foreach($extensions as$extension){echo'<tr><td class="e">'.$extension.'</td><td class="v">';echo implode(',',(array)get_extension_funcs($extension)),'<br/>';}echo'</td></tr>'.
							PHP_EOL;echo'</table>'.PHP_EOL;echo'</div></body></html>'.PHP_EOL;$extinfo=ob_get_contents();ob_end_clean();echo $phpinfo.$hr.$extinfo;}}
			function printWelcomeScreen(){global$supportedLanguages;$first=TRUE;echo'<br><br><form method=\'post\'>';foreach($supportedLanguages as$langId=>$langLabel){echo"<label class=\"radio\"><img src='/img/flags/$langId.png'width='24'height='24'/>
							<input type=\"radio\"name=\"lang\"id=\"$langId\"value=\"$langId\"";if($first){echo'checked';$first=FALSE;}echo"> $langLabel</label>";}echo"<br><br><button type=\"submit\"class=\"btn\">Let´s go!</button>
							<input type=\"hidden\"name=\"action\"value=\"actionSetLanguage\"></form>";}
			function actionSetLanguage(){if(!isset($_POST['lang'])){global$errors;$errors[]='Please select a language.';return'printWelcomeScreen';}global$supportedLanguages;$lang=$_POST['lang'];if(key_exists($lang,$supportedLanguages)){$_SESSION['lang']=$lang;
							if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/generated/config.inc.php'))return'printSystemCheck';else{echo'<b>There is already a configuration. Please delete or rename the config.inc.php file in the "generated" directory.';exit();
							$db=DbConnection::getInstance();connectDB();if($db->executeQuery("SHOW TABLES LIKE '`_session`'"))return'setAdminForm';}return'printWelcomeScreen';}}
			function printSystemCheck($messages){echo'<h2>'.$messages['check_title'].'</h2>';$requirments[]=['requirement'=>$messages['check_req_php'],'min'=>'5.5.0','actual'=>PHP_VERSION,'status'=>(version_compare(PHP_VERSION,'5.5.0')>-1)?'success':'error'];
							$requirments[]=['requirement'=>$messages['check_req_json'],'min'=>$messages['check_req_yes'],'actual'=>(function_exists('json_encode'))?$messages['check_req_yes']:$messages['check_req_no'],'status'=>(function_exists('json_encode'))?
							'success':'error'];$requirments[]=['requirement'=>$messages['check_req_gd'],'min'=>$messages['check_req_yes'],'actual'=>(function_exists('getimagesize'))?$messages['check_req_yes']:$messages['check_req_no'],'status'=>
							(function_exists('getimagesize'))?'success':'error'];$requirments[]=['requirement'=>$messages['check_req_safemode'],'min'=>$messages['check_req_off'],'actual'=>(!ini_get('safe_mode'))?$messages['check_req_off']:$messages['check_req_on'],
							'status'=>(!ini_get('safe_mode'))?'success':'error'];
							$writableFiles=explode(',',"generated/,uploads/club/,uploads/cup/,uploads/player/,uploads/sponsor/,uploads/stadium/,uploads/stadiumbuilder/,uploads/stadiumbuilding/,uploads/users/,admin/config/jobs.xml,admin/config/termsandconditions.xml");
							foreach($writableFiles as$writableFile){$file=$_SERVER['DOCUMENT_ROOT'].'/'.$writableFile;$requirments[]=['requirement'=>$messages['check_req_writable'].'<i>'.$writableFile.'</i>','min'=>$messages['check_req_yes'],
							'actual'=>(is__writable($file))?$messages['check_req_yes']:$messages['check_req_no'],'status'=>(is__writable($file))?'success':'error'];}?><table class='table'><thead><tr><th><?php echo$messages['check_head_requirement']?></th><th><?php
							echo$messages['check_head_required_value']?></th><th><?php echo$messages['check_head_actual_value']?></th></tr></thead><tbody><?php $valid=TRUE;foreach($requirments as$requirement){echo"<tr class=\"".$requirement["status"]."\"><td>".
							$requirement["requirement"]."</td><td>".$requirement["min"]."</td><td>".$requirement["actual"]."</td></tr>";if($requirement['status']=='error')$valid=FALSE;}?></tbody></table><?php if($valid)echo"<form method=\"post\">
							<button type=\"submit\"class=\"btn\">".$messages["button_next"]."</button><input type=\"hidden\"name=\"action\"value=\"actionGotoConfig\"></form>";else echo'<p>'.$messages['check_req_error'].'</p>';}
			function actionGotoConfig(){return'printConfigForm';}
			function printConfigForm($messages){?><form method='post'class='form-horizontal'><fieldset><legend><?php echo$messages['config_formtitle']?></legend><div class='control-group'><label class='control-label'for='db_host'><?php echo$messages['label_db_host']?>
							</label><div class='controls'><input type='text'id='db_host'name='db_host'required value='<?php echo htmlentities((isset($_POST['db_host']))?$_POST['db_host']:'localhost');?>'><span class='help-inline'><?php echo$messages['label_db_host_help']
							?></span></div></div><div class='control-group'><label class='control-label'for='db_name'><?php echo$messages['label_db_name']?></label><div class='controls'><input type='text'id='db_name'name='db_name'required value='<?php echo
							htmlentities((isset($_POST['db_name']))?$_POST['db_name']:'');?>'></div></div><div class='control-group'><label class='control-label'for='db_user'><?php echo$messages['label_db_user']?></label><div class='controls'><input type='text'
							id='db_user'name='db_user'required value='<?php echo htmlentities((isset($_POST['db_user']))?$_POST['db_user']:'');?>'></div></div><div class='control-group'><label class='control-label'for='db_password'><?php
							echo$messages['label_db_password']?></label><div class='controls'><input type='text'id='db_password'name='db_password'required value='<?php echo htmlentities((isset($_POST['db_password']))?$_POST['db_password']:'');?>'></div></div><hr>
							<div class='control-group'><label class='control-label'for='projectname'><?php echo$messages['label_projectname']?></label><div class='controls'><input type='text'id='projectname'name='projectname'required value='<?php echo
							htmlentities((isset($_POST['projectname']))?$_POST['projectname']:'');?>'><span class='help-inline'><?php echo$messages['label_projectname_help']?></span></div></div><div class='control-group'><label class='control-label'for='projectname'>
							<?php echo$messages['label_systememail']?></label><div class='controls'><input type='email'id='systememail'name='systememail'required value='<?php echo htmlentities((isset($_POST['systememail']))?$_POST['systememail']:'');?>'><span
							class='help-inline'><?php echo$messages['label_systememail_help']?></span></div></div><?php $defaultUrl='http://'.$_SERVER['HTTP_HOST'];?><div class='control-group'><label class='control-label'for='url'><?php echo$messages['label_url']?>
							</label><div class='controls'><input type='url'id='url'name='url'required value='<?php echo htmlentities((isset($_POST['url']))?$_POST['url']:$defaultUrl);?>'><span class='help-inline'><?php echo $messages['label_url_help']?></span></div>
							</div><?php $defaultRoot=substr($_SERVER['REQUEST_URI'],0,strrpos($_SERVER['REQUEST_URI'],'/install'));?></fieldset><div class='form-actions'><button type='submit'class='btn btn-primary'><?php echo$messages['button_next'];?></button></div>
							<input type='hidden'name='action'value='actionSaveConfig'></form><?php }
			function actionSaveConfig(){global$errors;global$messages;$requiredFields=['db_host','db_name','db_user','db_password','projectname','systememail','url'];
							foreach($requiredFields as$requiredField){if(!isset($_POST[$requiredField])||!strlen($_POST[$requiredField]))$errors[]=$messages['requires_value'].': '.$messages['label_'.$requiredField];}if(count($errors))return'printConfigForm';
							if(isset($conf)&&count($conf))$errors[]=$messages['err_already_installed'];else{try{$db=DbConnection::getInstance();$db->connect($_POST['db_host'],$_POST['db_user'],$_POST['db_password'],$_POST['db_name']);$db->close();}catch(Exception$e)
							{$errors[]=$messages['invalid_db_credentials'];}}if(count($errors))return'printConfigForm';$filecontent='<?php'.PHP_EOL."\$conf['db_host']=\"".$_POST['db_host']."\";".PHP_EOL."\$conf['db_user']=\"".$_POST['db_user']."\";".PHP_EOL.
							"\$conf['db_passwort']=\"".$_POST['db_password']."\";".PHP_EOL."\$conf['db_name']=\"".$_POST['db_name']."\";".PHP_EOL
	."\$conf['db_prefix']=\"".''."\";".PHP_EOL."\$conf['context_root']=\"".''."\";".PHP_EOL."\$conf['supported_languages']='de,en,es,dk,ee,fi,fr,id,it,lv,lt,nl,pl,pt,br,ro,se,sk,si,cz,tr,hu,jp';".PHP_EOL."\$conf['homepage']=\"".$_POST['url']."\";".PHP_EOL
	."\$conf['projectname']=\"".$_POST['projectname']."\";".PHP_EOL."\$conf['systememail']=\"".$_POST['systememail']."\";".PHP_EOL."\$conf['NUMBER_OF_PLAYERS']=\"".'20'."\";".PHP_EOL."\$conf['upload_clublogo_max_size']=\"".'0'."\";".PHP_EOL
	."\$conf['rename_club_enabled']=\"".''."\";".PHP_EOL."\$conf['session_lifetime']=\"".'7200'."\";".PHP_EOL."\$conf['time_zone']=\"".'Europe/Berlin'."\";".PHP_EOL."\$conf['time_offset']=\"".'0'."\";".PHP_EOL."\$conf['date_format']=\"".'d.m.Y'."\";".PHP_EOL
	."\$conf['datetime_format']=\"".'d.m.Y, H:i'."\";".PHP_EOL."\$conf['time_format']=\"".'H:i'."\";".PHP_EOL."\$conf['password_protected']=\"".''."\";".PHP_EOL."\$conf['password_protected_startpage']=\"".''."\";".PHP_EOL."\$conf['privacypolicy_url']=\"".''."\";".PHP_EOL
	."\$conf['game_currency']=\"".'€'."\";".PHP_EOL."\$conf['offline']=\"".'online'."\";".PHP_EOL."\$conf['offline_text']=\"".'The website is temporarily offline.'."\";".PHP_EOL."\$conf['offline_times']=\"".''."\";".PHP_EOL
	."\$conf['enable_player_resignation']=\"".'1'."\";".PHP_EOL."\$conf['player_resignation_compensation_matches']=\"".'10'."\";".PHP_EOL."\$conf['formation_max_next_matches']=\"".'3'."\";".PHP_EOL."\$conf['formation_max_templates']=\"".'10'."\";".PHP_EOL
	."\$conf['login_type']=\"".'username'."\";".PHP_EOL."\$conf['login_method']=\"".'DefaultUserLoginMethod'."\";".PHP_EOL."\$conf['login_allow_sendingpassword']=\"".'1'."\";".PHP_EOL."\$conf['assign_team_automatically']=\"".'1'."\";".PHP_EOL
	."\$conf['max_number_teams_per_user']=\"".'1'."\";".PHP_EOL."\$conf['additional_team_min_highscore']=\"".'100'."\";".PHP_EOL."\$conf['skin']=\"".'DefaultBootstrapSkin'."\";".PHP_EOL."\$conf['entries_per_page']=\"".'20'."\";".PHP_EOL
	."\$conf['head_code']=\"".''."\";".PHP_EOL."\$conf['frontend_ads_hide_for_premiumusers']=\"".''."\";".PHP_EOL."\$conf['frontend_ads_code_sidebar']=\"".''."\";".PHP_EOL."\$conf['frontend_ads_code_top']=\"".''."\";".PHP_EOL
	."\$conf['frontend_ads_code_bottom']=\"".''."\";".PHP_EOL."\$conf['joomlalogin_tableprefix']=\"".'_'."\";".PHP_EOL."\$conf['lending_enabled']=\"".'1'."\";".PHP_EOL."\$conf['lending_matches_min']=\"".'5'."\";".PHP_EOL
	."\$conf['lending_matches_max']=\"".'20'."\";".PHP_EOL."\$conf['messages_enabled']=\"".'1'."\";".PHP_EOL."\$conf['messages_break_minutes']=\"".'2'."\";".PHP_EOL."\$conf['no_transactions_for_teams_without_user']=\"".''."\";".PHP_EOL
	."\$conf['nationalteams_enabled']=\"".'1'."\";".PHP_EOL."\$conf['notifications_max']=\"".'5'."\";".PHP_EOL."\$conf['contract_max_number_of_remaining_matches']=\"".'15'."\";".PHP_EOL."\$conf['hide_strength_attributes']=\"".''."\";".PHP_EOL
	."\$conf['players_aging']=\"".'birthday'."\";".PHP_EOL."\$conf['premium_enabled']=\"".''."\";".PHP_EOL."\$conf['premium_credit_unit']=\"".'Pinunzen'."\";".PHP_EOL."\$conf['premium_infopage']=\"".'premium-feature-requested'."\";".PHP_EOL
	."\$conf['premium_price_options']=\"".'5:10,20:50,50:150'."\";".PHP_EOL."\$conf['premium_currency']=\"".'EUR'."\";".PHP_EOL."\$conf['premium_initial_credit']=\"".'0'."\";".PHP_EOL."\$conf['premium_exchangerate_gamecurrency']=\"".'1000000'."\";".PHP_EOL
	."\$conf['randomevents_interval_days']=\"".'10'."\";".PHP_EOL."\$conf['sim_noformation_oneteam']=\"".'computer'."\";".PHP_EOL."\$conf['sim_noformation_bothteams']=\"".'computer'."\";".PHP_EOL."\$conf['sim_createformation_without_manager']=\"".'1'."\";".PHP_EOL
	."\$conf['sim_createformation_without_manager_offensive']=\"".'50'."\";".PHP_EOL."\$conf['sim_createformation_on_invalidsubmission']=\"".''."\";".PHP_EOL."\$conf['sim_createformation_strength']=\"".'100'."\";".PHP_EOL
	."\$conf['sim_income_trough_friendly']=\"".'1'."\";".PHP_EOL."\$conf['sim_injured_after_friendly']=\"".'1'."\";".PHP_EOL."\$conf['sim_tiredness_through_friendly']=\"".'1'."\";".PHP_EOL."\$conf['sim_playerupdate_through_nationalteam']=\"".'1'."\";".PHP_EOL
	."\$conf['sim_goaly_influence']=\"".'20'."\";".PHP_EOL."\$conf['sim_shootprobability']=\"".'100'."\";".PHP_EOL."\$conf['sim_cardsprobability']=\"".'100'."\";".PHP_EOL."\$conf['sim_injuredprobability']=\"".'100'."\";".PHP_EOL
	."\$conf['sim_weight_strength']=\"".'40'."\";".PHP_EOL."\$conf['sim_weight_strengthTech']=\"".'20'."\";".PHP_EOL."\$conf['sim_weight_strengthStamina']=\"".'20'."\";".PHP_EOL."\$conf['sim_weight_strengthFreshness']=\"".'10'."\";".PHP_EOL
	."\$conf['sim_weight_strengthSatisfaction']=\"".'10'."\";".PHP_EOL."\$conf['sim_strength_reduction_secondary']=\"".'10'."\";".PHP_EOL."\$conf['sim_strength_reduction_wrongposition']=\"".'50'."\";".PHP_EOL."\$conf['sim_home_field_advantage']=\"".'2'."\";".PHP_EOL
	."\$conf['sim_shootstrength_defense']=\"".'70'."\";".PHP_EOL."\$conf['sim_shootstrength_midfield']=\"".'90'."\";".PHP_EOL."\$conf['sim_shootstrength_striker']=\"".'100'."\";".PHP_EOL."\$conf['sim_block_player_after_yellowcards']=\"".'5'."\";".PHP_EOL
	."\$conf['sim_played_min_minutes']=\"".'10'."\";".PHP_EOL."\$conf['sim_decrease_freshness']=\"".'1'."\";".PHP_EOL."\$conf['sim_strengthchange_stamina']=\"".'1'."\";".PHP_EOL."\$conf['sim_strengthchange_satisfaction']=\"".'1'."\";".PHP_EOL
	."\$conf['sim_strengthchange_freshness_notplayed']=\"".'4'."\";".PHP_EOL."\$conf['sim_maxmatches_injured']=\"".'12'."\";".PHP_EOL."\$conf['sim_maxmatches_blocked']=\"".'5'."\";".PHP_EOL."\$conf['sim_allow_livechanges']=\"".'1'."\";".PHP_EOL
	."\$conf['sim_allow_offensivechanges']=\"".'2'."\";".PHP_EOL."\$conf['sim_interval']=\"".'2'."\";".PHP_EOL."\$conf['sim_max_matches_per_run']=\"".'15'."\";".PHP_EOL."\$conf['sim_strategy']=\"".'DefaultSimulationStrategy'."\";".PHP_EOL
	."\$conf['sim_simulation_observers']=\"".'MatchReportSimulationObserver'."\";".PHP_EOL."\$conf['sim_simulator_observers']=\"".'MatchReportSimulatorObserver,DataUpdateSimulatorObserver'."\";".PHP_EOL."\$conf['sponsor_matches']=\"".'15'."\";".PHP_EOL
	."\$conf['sponsor_earliest_matchday']=\"".'4'."\";".PHP_EOL."\$conf['stadium_max_grand']=\"".'60000'."\";".PHP_EOL."\$conf['stadium_max_side']=\"".'30000'."\";".PHP_EOL."\$conf['stadium_max_vip']=\"".'1000'."\";".PHP_EOL
	."\$conf['stadium_cost_standing']=\"".'100'."\";".PHP_EOL."\$conf['stadium_cost_seats']=\"".'200'."\";".PHP_EOL."\$conf['stadium_cost_standing_grand']=\"".'150'."\";".PHP_EOL."\$conf['stadium_cost_seats_grand']=\"".'300'."\";".PHP_EOL
	."\$conf['stadium_cost_vip']=\"".'2000'."\";".PHP_EOL."\$conf['stadium_construction_delay']=\"".'7'."\";".PHP_EOL."\$conf['stadium_hide_builders_reliability']=\"".'1'."\";".PHP_EOL."\$conf['stadium_maintenanceinterval_pitch']=\"".'2'."\";".PHP_EOL
	."\$conf['stadium_maintenanceinterval_videowall']=\"".'10'."\";".PHP_EOL."\$conf['stadium_maintenanceinterval_seatsquality']=\"".'5'."\";".PHP_EOL."\$conf['stadium_maintenanceinterval_vipquality']=\"".'5'."\";".PHP_EOL
	."\$conf['stadium_pitch_price']=\"".'10000'."\";".PHP_EOL."\$conf['stadium_videowall_price']=\"".'100000'."\";".PHP_EOL."\$conf['stadium_seatsquality_price']=\"".'10'."\";".PHP_EOL."\$conf['stadium_vipquality_price']=\"".'50'."\";".PHP_EOL
	."\$conf['stadium_maintenance_priceincrease_per_level']=\"".'10'."\";".PHP_EOL."\$conf['stadium_pitch_effect']=\"".'1'."\";".PHP_EOL."\$conf['stadium_videowall_effect']=\"".'1'."\";".PHP_EOL."\$conf['stadium_seatsquality_effect']=\"".'2'."\";".PHP_EOL
	."\$conf['stadium_vipquality_effect']=\"".'5'."\";".PHP_EOL."\$conf['training_min_hours_between_execution']=\"".'24'."\";".PHP_EOL."\$conf['trainingcamp_min_days']=\"".'3'."\";".PHP_EOL."\$conf['trainingcamp_max_days']=\"".'5'."\";".PHP_EOL
	."\$conf['trainingcamp_booking_max_days_in_future']=\"".'30'."\";".PHP_EOL."\$conf['transfermarket_enabled']=\"".'1'."\";".PHP_EOL."\$conf['transfermarket_duration_days']=\"".'7'."\";".PHP_EOL."\$conf['transfermarket_computed_marketvalue']=\"".'1'."\";".PHP_EOL	."\$conf['transfermarket_value_per_strength']=\"".'10000'."\";".PHP_EOL."\$conf['transfermarket_min_teamsize']=\"".'18'."\";".PHP_EOL."\$conf['transfermarket_max_transactions_between_users']=\"".'2'."\";".PHP_EOL."\$conf['transferoffers_enabled']=\"".'1'."\";".PHP_EOL
	."\$conf['transferoffers_transfer_stop_days']=\"".'30'."\";".PHP_EOL."\$conf['transferoffers_adminapproval_required']=\"".''."\";".PHP_EOL."\$conf['transferoffers_contract_matches']=\"".'20'."\";".PHP_EOL
	."\$conf['authentication_mechanism']=\"".'SessionBasedUserAuthentication'."\";".PHP_EOL."\$conf['allow_userregistration']=\"".'1'."\";".PHP_EOL."\$conf['registration_url']=\"".''."\";".PHP_EOL."\$conf['max_number_of_users']=\"".'0'."\";".PHP_EOL
	."\$conf['illegal_usernames']=\"".'root,admin,administrator,test'."\";".PHP_EOL."\$conf['user_picture_upload_enabled']=\"".'1'."\";".PHP_EOL."\$conf['user_picture_upload_maxsize_kb']=\"".'512'."\";".PHP_EOL."\$conf['highscore_win']=\"".'5'."\";".PHP_EOL	."\$conf['highscore_draw']=\"".'3'."\";".PHP_EOL."\$conf['highscore_loss']=\"".'1'."\";".PHP_EOL."\$conf['webjobexecution_enabled']=\"".'1'."\";".PHP_EOL."\$conf['webjobexecution_key']=\"".'-'."\";".PHP_EOL."\$conf['wordpresslogin_tableprefix']=\"".'wp_'."\";".PHP_EOL
	."\$conf['youth_enabled']=\"".'1'."\";".PHP_EOL."\$conf['youth_scouting_enabled']=\"".'1'."\";".PHP_EOL."\$conf['youth_matchrequests_enabled']=\"".'1'."\";".PHP_EOL."\$conf['youth_min_age_professional']=\"".'16'."\";".PHP_EOL
	."\$conf['youth_salary_per_strength']=\"".'50'."\";".PHP_EOL."\$conf['youth_scouting_break_hours']=\"".'24'."\";".PHP_EOL."\$conf['youth_scouting_success_probability']=\"".'75'."\";".PHP_EOL."\$conf['youth_scouting_min_strength']=\"".'5'."\";".PHP_EOL
	."\$conf['youth_scouting_max_strength']=\"".'70'."\";".PHP_EOL."\$conf['youth_scouting_standard_deviation']=\"".'5'."\";".PHP_EOL."\$conf['youth_scouting_min_age']=\"".'14'."\";".PHP_EOL."\$conf['youth_professionalmove_matches']=\"".'30'."\";".PHP_EOL
	."\$conf['youth_professionalmove_technique']=\"".'50'."\";".PHP_EOL."\$conf['youth_professionalmove_stamina']=\"".'60'."\";".PHP_EOL."\$conf['youth_professionalmove_freshness']=\"".'100'."\";".PHP_EOL
	."\$conf['youth_professionalmove_satisfaction']=\"".'100'."\";".PHP_EOL."\$conf['youth_matchrequest_max_open_requests']=\"".'2'."\";".PHP_EOL."\$conf['youth_matchrequest_max_futuredays']=\"".'14'."\";".PHP_EOL	."\$conf['youth_matchrequest_allowedtimes']=\"".'14:00,15:00'."\";".PHP_EOL."\$conf['youth_matchrequest_accept_hours_in_advance']=\"".'2'."\";".PHP_EOL."\$conf['youth_match_maxperday']=\"".'1'."\";".PHP_EOL."\$conf['youth_strengthchange_verygood']=\"".'2'."\";".PHP_EOL
	."\$conf['youth_strengthchange_good']=\"".'1'."\";".PHP_EOL."\$conf['youth_strengthchange_bad']=\"".'-1'."\";".PHP_EOL."\$conf['youth_strengthchange_verybad']=\"".'-2'."\";".PHP_EOL."\$conf['facebook_enable_login']=\"".''."\";".PHP_EOL
	."\$conf['facebook_appid']=\"".''."\";".PHP_EOL."\$conf['facebook_appsecret']=\"".''."\";".PHP_EOL."\$conf['facebook_enable_comments']=\"".''."\";".PHP_EOL."\$conf['facebook_enable_likebutton']=\"".''."\";".PHP_EOL
	."\$conf['googleplus_enable_login']=\"".''."\";".PHP_EOL."\$conf['googleplus_appname']=\"".''."\";".PHP_EOL."\$conf['googleplus_clientid']=\"".''."\";".PHP_EOL."\$conf['googleplus_clientsecret']=\"".''."\";".PHP_EOL
	."\$conf['googleplus_developerkey']=\"".''."\";".PHP_EOL."\$conf['googleplus_enable_likebutton']=\"".''."\";".PHP_EOL."\$conf['gravatar_enable']=\"".'1'."\";".PHP_EOL."\$conf['micropayment_enabled']=\"".''."\";".PHP_EOL
	."\$conf['micropayment_project']=\"".''."\";".PHP_EOL."\$conf['micropayment_accesskey']=\"".''."\";".PHP_EOL."\$conf['micropayment_call2pay_enabled']=\"".''."\";".PHP_EOL."\$conf['micropayment_handypay_enabled']=\"".''."\";".PHP_EOL
	."\$conf['micropayment_ebank2pay_enabled']=\"".''."\";".PHP_EOL."\$conf['micropayment_creditcard_enabled']=\"".''."\";".PHP_EOL."\$conf['paypal_enabled']=\"".''."\";".PHP_EOL."\$conf['paypal_receiver_email']=\"".''."\";".PHP_EOL
	."\$conf['paypal_host']=\"".'www.paypal.com'."\";".PHP_EOL."\$conf['paypal_url']=\"".'ssl://www.paypal.com'."\";".PHP_EOL."\$conf['paypal_buttonhtml']=\"".''."\";".PHP_EOL."\$conf['sofortcom_enabled']=\"".''."\";".PHP_EOL
	."\$conf['sofortcom_configkey']=\"".''."\";".PHP_EOL."\$conf['social_likebutton_twitter']=\"".''."\";".PHP_EOL."\$conf['social_likebutton_werkenntwen']=\"".''."\";".PHP_EOL."\$conf['social_likebutton_xing']=\"".''."\";".PHP_EOL
	."\$conf['register_use_captcha']=\"".''."\";".PHP_EOL."\$conf['register_captcha_publickey']=\"".'-'."\";".PHP_EOL."\$conf['register_captcha_privatekey']=\"".'-'."\";".PHP_EOL."\$conf['sortcolumn']=\"".'sortcolumn'."\";".PHP_EOL
	."\$conf['sortdir']=\"".'sortdir'."\";".PHP_EOL."\$conf['resetsort']=\"".'resetsort'."\";".PHP_EOL."\$conf['DEFAULT_PAGE_ID']=\"".'home'."\";".PHP_EOL."\$conf['DEFAULT_PLAYER_AGE']=\"".'20'."\";".PHP_EOL."\$conf['DEFAULT_YOUTH_OFFENSIVE']=\"".'60'."\";".PHP_EOL
	."\$conf['DOUBLE_SUBMIT_CHECK_SECONDS']=\"".'3'."\";".PHP_EOL."\$conf['INACTIVITY_PER_DAY_LOGIN']=\"".'0.45'."\";".PHP_EOL."\$conf['INACTIVITY_PER_DAY_TRANSFERS']=\"".'0.1'."\";".PHP_EOL."\$conf['INACTIVITY_PER_DAY_TACTICS']=\"".'0.2'."\";".PHP_EOL
	."\$conf['INACTIVITY_PER_CONTRACTEXTENSION']=\"".'5'."\";".PHP_EOL."\$conf['MARK_IMPROVE_GOAL_SCORER']=\"".'1'."\";".PHP_EOL."\$conf['MARK_IMPROVE_GOAL_PASSPLAYER']=\"".'0.75'."\";".PHP_EOL."\$conf['MARK_DOWNGRADE_GOAL_GOALY']=\"".'0.5'."\";".PHP_EOL
	."\$conf['MARK_DOWNGRADE_SHOOTFAILURE']=\"".'0.5'."\";".PHP_EOL."\$conf['MARK_IMPROVE_SHOOTFAILURE_GOALY']=\"".'0.5'."\";".PHP_EOL."\$conf['MARK_IMPROVE_TACKLE_WINNER']=\"".'0.25'."\";".PHP_EOL."\$conf['MARK_DOWNGRADE_TACKLE_LOOSER']=\"".'0.5'."\";".PHP_EOL
	."\$conf['MARK_DOWNGRADE_BALLPASS_FAILURE']=\"".'0.25'."\";".PHP_EOL."\$conf['MARK_IMPROVE_BALLPASS_SUCCESS']=\"".'0.1'."\";".PHP_EOL."\$conf['MAX_ITEMS']=\"".'20'."\";".PHP_EOL."\$conf['MAX_STRENGTH']=\"".'100'."\";".PHP_EOL
	."\$conf['MIN_NUMBER_OF_PLAYERS']=\"".'9'."\";".PHP_EOL."\$conf['MINIMUM_SATISFACTION_FOR_EXTENSION']=\"".'30'."\";".PHP_EOL."\$conf['NEWS_ENTRIES_PER_PAGE']=\"".'5'."\";".PHP_EOL."\$conf['NEWS_TEASER_MAXLENGTH']=\"".'256'."\";".PHP_EOL
	."\$conf['NOTIFICATION_TYPE']=\"".'transferoffer'."\";".PHP_EOL."\$conf['NOTIFICATION_TARGETPAGE']=\"".'transferoffers'."\";".PHP_EOL."\$conf['NUMBER_OF_PLAYERS']=\"".'20'."\";".PHP_EOL."\$conf['NUMBER_OF_TOP_NEWS']=\"".'5'."\";".PHP_EOL
	."\$conf['PLAYER_POSITION_GOALY']=\"".'Torwart'."\";".PHP_EOL."\$conf['PLAYER_POSITION_DEFENCE']=\"".'Abwehr'."\";".PHP_EOL."\$conf['PLAYER_POSITION_MIDFIELD']=\"".'Mittelfeld'."\";".PHP_EOL."\$conf['PLAYER_POSITION_STRIKER']=\"".'Sturm'."\";".PHP_EOL
	."\$conf['POINTS_WIN']=\"".'3'."\";".PHP_EOL."\$conf['POINTS_DRAW']=\"".'1'."\";".PHP_EOL."\$conf['POINTS_LOSS']=\"".'0'."\";".PHP_EOL."\$conf['REMEMBERME_COOKIE_LIFETIME_DAYS']=\"".'30'."\";".PHP_EOL."\$conf['SATISFACTION_DECREASE']=\"".'10'."\";".PHP_EOL
	."\$conf['SATISFACTION_INCREASE']=\"".'10'."\";".PHP_EOL."\$conf['SLEEP_SECONDS_ON_FAILURE']=\"".'5'."\";".PHP_EOL."\$conf['USER_STATUS_ENABLED']=\"".'1'."\";".PHP_EOL."\$conf['USER_STATUS_UNCONFIRMED']=\"".'2'."\";".PHP_EOL
	."\$conf['YOUTH_STRENGTH_FRESHNESS']=\"".'100'."\";".PHP_EOL."\$conf['YOUTH_STRENGTH_SATISFACTION']=\"".'100'."\";".PHP_EOL
	."\$conf['WRITABLE_FOLDERS']=\"".'generated/,uploads/club/,uploads/cup/,uploads/player/,uploads/sponsor/,uploads/stadium/,uploads/stadiumbuilder/,uploads/stadiumbuilding/,uploads/users/,admin/config/jobs.xml,admin/config/termsandconditions.xml'."\";".PHP_EOL;
	$fp=fopen($_SERVER['DOCUMENT_ROOT'].'/generated/config.inc.php','w+');fwrite($fp,$filecontent);fclose($fp);if(file_exists($_SERVER['DOCUMENT_ROOT'].'/generated/config.inc.php'))return"printPreDbCreate";}
			function printPreDbCreate($messages){?><h2><?php echo$messages['predb_title'];?></h2><form method='post'><label class='radio'><input type='radio'name='install'value='new'checked><?php echo$messages['predb_label_new'];?></label><button type='submit'
							class='btn btn-primary'><?php echo$messages['button_next'];?></button><input type='hidden'name='action'value='actionCreateDb'></form><p><i class='icon-warning-sign'></i><?php echo$messages['predb_label_warning'];?></p><?php }
			function actionCreateDb(){include($_SERVER['DOCUMENT_ROOT'].'/generated/config.inc.php');$db=DbConnection::getInstance();$db->connect($conf['db_host'],$conf['db_user'],$conf['db_passwort'],$conf['db_name']);
							try{if($_POST['install']=='new')loadAndExecuteDdl('owsPro.sql',$db);}catch(Exception$e){global$errors;$errors[]=$e->getMessage();return'printPreDbCreate';}$db->close();return'printCreateUserForm';}
			function loadAndExecuteDdl($file,$db){$script=file_get_contents($file);$queryResult=$db->connection->multi_query($script);while($db->connection->more_results()&&$db->connection->next_result());
							if(!$queryResult)throw new Exception('Database Query Error: '.$db->connection->error);}
			function printCreateUserForm($messages){?><form method='post'class='form-horizontal'><fieldset><legend><?php echo$messages['user_formtitle']?></legend><div class='control-group'><label class='control-label'for='name'><?php echo$messages['label_name']?>
							</label><div class='controls'><input type='text'id='name'name='name'required value='<?php echo htmlentities((isset($_POST['name']))?$_POST['name']:'');?>'></div></div><div class='control-group'><label class='control-label'for='password'>
							<?php echo$messages['label_password']?></label><div class='controls'><input type='password'id='password'name='password'required value='<?php echo htmlspecialchars(isset($_POST['password'])?$_POST['password']:'');?>'></div></div><br>
							<div class='control-group'><label class='control-label'for='email'><?php echo$messages['label_email']?></label><div class='controls'><input type='email'id='email'name='email'required value='<?php echo htmlentities((isset($_POST['email']))?
							$_POST['email']:'');?>'</div></div></fieldset><div class='form-actions'><button type='submit'class='btn btn-primary'><?php echo$messages['button_next'];?></button></div><input type='hidden'name='action'value='actionSaveUser'></form><?php }
			function actionSaveUser(){global$errors;global$messages;$requiredFields=['name','password','email'];
							foreach($requiredFields as$requiredField){if(!isset($_POST[$requiredField])||!strlen($_POST[$requiredField]))$errors[]=$messages['requires_value'].': '.$messages['label_'.$requiredField];}if(count($errors))return'printCreateUserForm';$salt=
							generatePasswordSalt();$password=hashPassword($_POST['password'],$salt);$columns=['name'=>$_POST['name'],'passwort'=>$password,'passwort_salt'=>$salt,'email'=>$_POST['email'],'r_admin'=>'1'];
							include($_SERVER['DOCUMENT_ROOT'].'/generated/config.inc.php');$db=DbConnection::getInstance();$db->connect($conf['db_host'],$conf['db_user'],$conf['db_passwort'],$conf['db_name']);$db->queryInsert($columns,'admin');
							$db->queryInsert($columns,'_admin');$db->queryInsert($columns,'ws3_admin');return'printFinalPage';}
			function printFinalPage($messages){include($_SERVER['DOCUMENT_ROOT'].'/generated/config.inc.php');?><div class='alert alert-success'><strong><?php echo$messages['final_success_alert'];?></strong></div><div class='alert'><strong><?php
							echo$messages['final_success_note'];?></strong></div><p><i class='icon-arrow-right'></i><a href='<?php echo$conf['context_root'];?>/admin'><?php echo$messages['final_link'];?></a></p><?php }
			function is__writable($path){if($path[strlen($path)-1]=='/')return is__writable($path.uniqid(mt_rand()).'.tmp');elseif(is_dir($path))return is__writable($path.'/'.uniqid(mt_rand()).'.tmp');$rm=file_exists($path);$f=@fopen($path,'a');if($f===false)return
							false;fclose($f);if(!$rm)unlink($path);return true;}
			function setAdminScreen(){global$supportedLanguages;$first=TRUE;echo'<br><br><form method=\'post\'>';foreach($supportedLanguages as$langId=>$langLabel){echo"<label class=\"radio\"><img src='/img/flags/$langId.png'width='24'height='24'/>
							<input type=\"radio\"name=\"lang\"id=\"$langId\"value=\"$langId\"";if($first){echo'checked';$first=FALSE;}echo"> $langLabel</label>";}echo"<br><br><button type=\"submit\"class=\"btn\">Let´s go!</button><input type=\"hidden\"name=\"action\"
							value=\"actionSetLanguage\"></form>";}
			function setAdminForm($messages){?><form method='post'class='form-horizontal'><fieldset><legend><?php echo$messages['user_formtitle']?></legend><div class='control-group'><label class='control-label'for='db_host'><?php echo$messages['label_db_host']?></label>
							<div class='controls'><input type='text'id='db_host'name='db_host'required value="<?php echo escapeOutput(isset($_POST['db_host'])?$_POST['db_host']:'localhost');?>"><span class='help-inline'><?php echo$messages['label_db_host_help']?></span>
							</div></div><div class='control-group'><label class='control-label'for='db_name'><?php echo$messages['label_db_name']?></label><div class='controls'><input type='text'id='db_name'name='db_name'required value="<?php echo escapeOutput(isset(
							$_POST['db_name'])?$_POST['db_name']:'');?>"></div></div><div class='control-group'><label class='control-label'for='db_user'><?php echo$messages['label_db_user']?></label><div class='controls'><input type='text'id='db_user'name='db_user'
							required value="<?php echo escapeOutput(isset($_POST['db_user'])?$_POST['db_user']:'');?>"></div></div><div class='control-group'><label class='control-label'for='db_password'><?php echo$messages['label_db_password']?></label>
							<div class='controls'><input type=text'id='db_password'name='db_password'required value="<?php echo escapeOutput(isset($_POST['db_password'])?$_POST['db_password']:'');?>"></div></div><div class='control-group'><label
							class='control-label'for='name'><?php echo$messages['label_name']?></label><div class='controls'><input type='text'id='name'name='name'required value='<?php echo htmlentities((isset($_POST['name']))?$_POST['name']:'');?>'></div></div>
							<div class='control-group'><label class='control-label'for='password'><?php echo$messages['label_password']?></label><div class='controls'><input type='password'id='password'name='password'required value='<?php echo
							htmlspecialchars(isset($_POST['password'])?$_POST['password']:'');?>'></div></div><div class='control-group'><label class='control-label'for='email'><?php echo$messages['label_email']?></label><div class='controls'><input type='email'
							id='email'name='email'required value='<?php echo htmlentities(isset($_POST['email'])?$_POST['email']:'');?>'></div></div></fieldset><div class='form-actions'><button type='submit'class='btn btn-primary'><?php echo$messages['button_next'];?>
							</button></div><input type='hidden'name='action'value='actionSaveUser'></form><?php }
			function flags($site){?><a href=<?php echo$site?>de><span class="ad"></span></a><a href=<?php echo$site?>de><img src='/img/flags/de.png'width='24'height='24'alt='deutsch'title='deutsch'/></a><a href=<?php echo$site?>en><img src='/img/flags/en.png'width='24'
							height='24'alt='english'title='english'/></a><a href=<?php echo$site?>es><img src='/img/flags/es.png'width='24'height='24'alt='español'title='español'/></a><a href=<?php echo$site?>pt><img src='/img/flags/pt.png'width='24'height='24'
							alt='português'title='português'/></a><a href=<?php echo$site?>dk><img src='/img/flags/dk.png'width='24'height='24'alt='dansk'title='dansk'/></a><a href=<?php echo$site?>ee><img src='/img/flags/ee.png'width='24'height='24'alt='eesti'
							title='eesti'/></a><a href=<?php echo$site?>fi><img src='/img/flags/fi.png'width='24'height='24'alt='suomalainen'title='suomalainen'/></a><a href=<?php echo$site?>fr><img src='/img/flags/fr.png'width='24'height='24'alt='français'
							title='français'/></a><a href=<?php echo$site?>id><img src='/img/flags/id.png'width='24'height='24'alt='indonesia'title='indonesia'/></a><a href=<?php echo$site?>it><img src='/img/flags/it.png'width='24'height='24'alt='italiano'
							title='italiano'/></a><a href=<?php echo$site?>lv><img src='/img/flags/lv.png'width='24'height='24'alt='latvieši'title='latvieši'/></a><a href=<?php echo$site?>lt><img src='/img/flags/lt.png'width='24'height='24'alt='lietuviškas'
							title='lietuviškas'/></a><a href=<?php echo$site?>nl><img src='/img/flags/nl.png'width='24'height='24'alt='nederlands'title='nederlands'/></a><a href=<?php echo$site?>pl><img src='/img/flags/pl.png'width='24'height='24'alt='polska'
							title='polska'/></a><a href=<?php echo$site?>br><img src='/img/flags/br.png'width='24'height='24'alt='???lang_label_br???'title='???lang_label_br???'/></a><a href=<?php echo$site?>ro><img src='/img/flags/ro.png'width='24'height='24'
							alt='???lang_label_ro???'title='???lang_label_ro???'/></a><a href=<?php echo$site?>se><img src='/img/flags/se.png'width='24'height='24'alt='???lang_label_se???'title='???lang_label_se???'/></a><a href=<?php echo$site?>sk>
							<img src='/img/flags/sk.png'width='24'height='24'alt='???lang_label_sk???'title='???lang_label_sk???'/></a><a href=<?php echo$site?>si><img src='/img/flags/si.png'width='24'height='24'alt='???lang_label_si???'title='???lang_label_si???'/></a>
							<a href=<?php echo$site?>cz><img src='/img/flags/cz.png'width='24'height='24'alt='???lang_label_cz???'title='???lang_label_cz???'/></a><a href=<?php echo$site?>tr><img src='/img/flags/tr.png'width='24'height='24'alt='???lang_label_tr???'
							title='???lang_label_tr???'/></a><a href=<?php echo$site?>hu><img src='/img/flags/hu.png'width='24'height='24'alt='???lang_label_hu???'title='???lang_label_hu???'/></a><a href=<?php echo$site?>jp><img src='/img/flags/jp.png'width='24'
							height='24'alt='???lang_label_jp???'title='???lang_label_jp???'/></a><br><br><?php }
			function classes_autoloader($class){$subforder='';if(substr($class,-9)==='Converter')$subforder='converters/';elseif(substr($class,-4)==='Skin')$subforder='skins/';elseif(substr($class,-5)==='Model')$subforder='models/';elseif(substr($class,-9)==='Validator')
							$subforder='validators/';elseif(substr($class,-10)==='Controller')$subforder='actions/';elseif(substr($class,-7)==='Service')$subforder='services/';elseif(substr($class,-3)==='Job')$subforder='jobs/';elseif(substr($class,-11)==='LoginMethod')
							$subforder='loginmethods/';elseif(substr($class,-5)==='Event')$subforder='events/';elseif(substr($class,-6)==='Plugin')$subforder='plugins/';@include($_SERVER['DOCUMENT_ROOT'].'/classes/'.$subforder.$class.'.class.php');}
			function sendEmail($email,$password,$website,$i18n){$tplparameters['newpassword']=$password;sendSystemEmailFromTemplate($website,$i18n,$email,Message('sendpassword_admin_email_subject'),'sendpassword_admin',$tplparameters);}
			function escapeOutput($message){return htmlspecialchars((string)$message,ENT_COMPAT,'UTF-8');}
			function createWarningMessage($title,$message){return createMessage('warning',$title,$message);}
			function createInfoMessage($title,$message){return createMessage('info',$title,$message);}
			function createErrorMessage($title,$message){return createMessage('error',$title,$message);}
			function createSuccessMessage($title,$message){return createMessage('success',$title,$message);}
			function createMessage($severity,$title,$message){$html='<div class=\'alert alert-'.$severity.'\'>'.'<button type=\'button\'class=\'close\'data-dismiss=\'alert\'>&times;</button>'.'<h4>'.$title.'</h4>'.$message.'</div>';return $html;}
			function logAdminAction(WebSoccer $websoccer,$type,$username,$entity,$entityValue){$userIp=getenv('REMOTE_ADDR');$message=Datetime(Timestamp()).';'.$username.';'.$userIp.';'.$type.';'.$entity.';'.$entityValue;
							$file=$_SERVER['DOCUMENT_ROOT'].'/generated/entitylog.php';$fw=new FileWriter($file,FALSE);$fw->writeLine($message);$fw->close();}
			function renderErrorPage($website,$i18n,$viewHandler,$message,$parameters){$parameters['title']=$message;$parameters['message']='';print_r($website->getTemplateEngine($i18n,$viewHandler)->loadTemplate('error')->render($parameters));}
			function printNavItem($currentSite,$pageId,$navLabel,$entity=''){$url='?site='.$pageId;$active=($currentSite==$pageId);if(strlen($entity)){$url.='&entity='.escapeOutput($entity);$active=(isset($_REQUEST['entity'])&&$_REQUEST['entity']==$entity);}echo'<li';
							if($active)echo'class=\'active\'';echo'><a href=\''.$url.'\'>'.$navLabel.'</a></li>';}
			function prepareFielfValueForSaving($fieldValue){$preparedValue=trim($fieldValue);$preparedValue=stripslashes($fieldValue);return$preparedValue;}
			function renderRound($roundNode){global$i18n,$website,$hierarchy,$site,$cupid,$cup,$action,$db;echo'<div class=\'cupround\'>';$showEditForm=FALSE;if($action=='edit'&&$_REQUEST['id']==$roundNode['round']['id']){$showEditForm=TRUE;}elseif($action=='edit-save'&&
							$_REQUEST['id']==$roundNode['round']['id']){if(isset($admin['r_demo'])&&$admin['r_demo'])throw new Exception(Message('validationerror_no_changes_as_demo'));$showEditForm=TRUE;$columns=[];$columns['name']=$_POST['name'];$columns['finalround']=
							(isset($_POST['finalround'])&&$_POST['finalround']=='1')?1:0;$columns['groupmatches']=(isset($_POST['groupmatches'])&&$_POST['groupmatches']=='1')?1:0;$firstDateObj=DateTime::createFromFormat(Config('date_format').', H:i',
							$_POST['firstround_date_date'].', '.$_POST['firstround_date_time']);$columns['firstround_date']=$firstDateObj->getTimestamp();if(isset($_POST['secondround_date_date'])){$secondDateObj=DateTime::createFromFormat(Config('date_format').', H:i',
							$_POST['secondround_date_date'].', '.$_POST['secondround_date_time']);$columns['secondround_date']=$secondDateObj->getTimestamp();}$db->queryUpdate($columns,Config('db_prefix').'_cup_round','id=%d',$roundNode['round']['id']);
							if($roundNode['round']['name']!==$_POST['name'])$db->queryUpdate(['pokalrunde'=>$_POST['name']],Config('db_prefix').'_spiel',"pokalname='%s'AND pokalrunde='%s'",[$cup['name'],$roundNode['round']['name']]);$result=$db->querySelect('*',
							Config('db_prefix').'_cup_round','id=%d',$roundNode['round']['id']);$roundNode['round']=$result->fetch_array();$result->free();$showEditForm=FALSE;}if($showEditForm){?><form action='<?php echo escapeOutput($_SERVER['PHP_SELF']);?>'
							method='post'class='form-horizontal'><input type='hidden'name='action'value='edit-save'><input type='hidden'name='site'value='<?php echo$site; ?>'><input type='hidden'name='cup'value='<?php echoescapeOutput($cupid);?>'><input type='hidden'
							name='id'value='<?php echo$roundNode['round']['id'];?>'><?php $formFields=[];$formFields['name']=['type'=>'text','value'=>$roundNode['round']['name'],'required'=>'true'];$formFields['firstround_date']=['type'=>'timestamp','value'=>
							$roundNode['round']['firstround_date'],'required'=>'true'];if($roundNode['round']['secondround_date'])$formFields['secondround_date']=['type'=>'timestamp','value'=>$roundNode['round']['secondround_date'],'required'=>'false'];
							$formFields['finalround']=['type'=>'boolean','value'=>$roundNode['round']['finalround']];$formFields['groupmatches']=['type'=>'boolean','value'=>$roundNode['round']['groupmatches']];foreach($formFields as$fieldId=>$fieldInfo)echo
							createFormGroup($i18n,$fieldId,$fieldInfo,$fieldInfo['value'],'managecuprounds_label_');?><div class='control-group'><div class='controls'><input type='submit'class='btn btn-primary'accesskey='s'title='Alt + s'value='<?php echo
							Message('button_save');?>'><a href="<?php echo'?site='.escapeOutput($site).'&cup='.escapeOutput($cupid);?>'class='btn'><?php echo Message('button_cancel');?></a></div></div></form><?php }else{echo'<p><strong>';
							if($roundNode['round']['finalround']=='1')echo'<em>';echo escapeOutput($roundNode['round']['name']);if($roundNode['round']['finalround']=='1')echo'</em></strong><a href=\'?site='.escapeOutput($site).'&cup='.
							escapeOutput($cupid).'&action=edit&id='.$roundNode['round']['id'].'\'title=\''.Message('manage_edit').'\'><i class=\'icon-pencil\'></i></a><a class=\'deleteLink\'href=\'?site='.escapeOutput($site).'&cup='.escapeOutput($cupid).
							'&action=delete&id='.$roundNode['round']['id'].'\'title=\''.Message('manage_delete').'\'><i class=\'icon-trash\'></i></a></p><ul><li><em>'.Message('managecuprounds_label_firstround_date').':</em>'.
							date(FormattedDatetime($roundNode['round']['firstround_date'])).'</li>';if($roundNode['round']['secondround_date'])echo'<li><em>'.Message('managecuprounds_label_secondround_date').':</em>'.date(FormattedDatetime(
							$roundNode['round']['secondround_date'])).'</li>';$matchesUrl='?site=manage&entity=match&'.http_build_query(['entity_match_pokalname'=>escapeOutput($cup['name']),'entity_match_pokalrunde'=>escapeOutput($roundNode['round']['name'])]);
							echo'<li><a href=\'$matchesUrl\'>'.Message('managecuprounds_show_matches').'</a></li></ul>';$addMatchUrl='?site=manage&entity=match&show=add&'.http_build_query(['pokalname'=>escapeOutput($cup['name']),'pokalrunde'=>
							escapeOutput($roundNode['round']['name']),'spieltyp'=>'Pokalspiel']);if(!$roundNode['round']['groupmatches']){echo'<p><a href=\'$addMatchUrl\'class=\'btn btn-mini\'><i class=\'icon-plus-sign\'></i>'.Message('managecuprounds_add_match').'</a>
							<a href=\'?site=managecuprounds-generate&round='.$roundNode['round']['id'].'\'class=\'btn btn-mini\'><i class=\'icon-random\'></i>'.Message('managecuprounds_generate_matches').'</a></p>';}else{echo'<p>
							<a href=\'?site=managecuprounds-groups&round='.$roundNode['round']['id'].'\'class=\'btn btn-mini\'><i class=\'icon-list\'></i>'.Message('managecuprounds_manage_groups').'</a></p>';}if(isset($roundNode['winnerround'])){echo'<p><em>'.
							Message('managecuprounds_next_round_winners').':</em></p>\n';renderRound($hierarchy[$roundNode['winnerround']]);}if(isset($roundNode['looserround'])){echo'<p><em>'.Message('managecuprounds_next_round_loosers').':</em></p>\n';
							renderRound($hierarchy[$roundNode['looserround']]);}}echo'</div>';}
function flags_css(){?><style>
span.af,.Afghanistan::before{content:url('data:image/gif;base64,R0lGODdhIAAUAPEAAAAAAACAAP8AAP///ywAAAAAIAAUAAACVYSPqZvhD6OcztCLn81c7g4GHyUEwlmW2EiaKWcM8kwPpy3g9R7vc45C2XyzHhGoQxIHRh/ypiQ2ncNhbskEYGXA6JKREACCY7D5jE6r1+y2+w2PgwsAOw==');}
span.al,.Albania,.Albanien::before{content:url('data:image/gif;base64,R0lGODdhIAAUAPAAAAAAAP8AACwAAAAAIAAUAAACRISPqZvhD6OcztCLn81c7g4GX8iNngigYmZG1qFiLZTWayrjFKPv/dn4eYQuRYlIQ9lYyBNoRppAo0Xq0crE5njcLqAAADs=');}span.dz,.Algeria,.Algerien::before{height:17px;content:url('data:image/gif;base64,R0lGODdhIAAUAPIAAAAAAACAAGBgAKBgAMBAAP8AAP+ggP///ywAAAAAIAAUAAADhwi63P4tyEnpuTjro2oVoGBs5NV5AUgUbFtq57eyxGDcxYvFkzATAkkmp+OlBrSgUAcDyFgDy+hAfBkFrcqlYKiWrlnLocsqOilYlnbbmm7AajHZ+z73ZtHJdkxvfpAFQHpufRlGKT9KQ0yHiC01N11MHHYfPi0uk409IW6alSh6kzsQpaYNCQA7'width='16'height='10'alt='andorra'title='andorra');}span.ad,.Andorra::before{content:url('data:image/gif;base64,R0lGODdhIAAUAPIAAAAAAAAAgICAAMDAAP8AAP+AAP/AAP//ACwAAAAAIAAUAAADigi63P4tyDmPvfgUwntXVJiNm+eBYTVipcmhabCy7gfE0jXs+9W6sNRBsCMSBwJN7XXDHZCCqHSgXAZFSIJBUNhSf6Yr5ckddonVmlglbVvAp2aMbCgICMPvkrDOIQd2Bn9pQHJCRW09cDY4Mk9FPG97fY46GYtMjTM+k4Yim4RhnmOgmHwQqKkNCQA7');}span.ao,.Angola::before{content:url('data:image/gif;base64,R0lGODdhIAAUAPIAAAAAAIAAAICAAP8AAP+AAP/AAP//AAAAACwAAAAAIAAUAAADXQi63P5tyEmrvVLhzafuE0GAw0cShjFyJlgM6Yq1nQrbM0DeNn7RlxTsddsoAsikEilIKZ3LJOTRNCwMgqlW0eSmAN/tNpWtYsVic+qM3greb3J7Xp3b7/i8fi9OAAA7');}span.ag,.Antigua_und_Barbuda::before{content:url('data:image/gif;base64,R0lGODdhIAAUAPIAAAAAAAAA/6CgAP8AAP//AP///wAAAAAAACwAAAAAIAAUAAADZQi63P4wSjmmAPfOCgZ3GUBgJOQp3tcIRNtqT4rKjMC6ru2kHK+KONyONyNibsEXzNdjBp7QqDTAbDqn2Kr1ioVqt9zst6gtmM/m76mjHqDTbfIX3V7XPfC6/K5n8/l7f2MThBEJADs=');}span.ar,.Argentina,.Argentinien::before{content:url('data:image/gif;base64,R0lGODdhIAAUAPIAAAAAAAAA/6CgAP//AP///wAAAAAAAAAAACwAAAAAIAAUAAADTwi63P4tyEmrvVLhzafuoPWFZDCW4IlyCuG+8CsMc2y77Q0PNC/osByQMPMNhjgAktDzLYXAYvGpXPZ+1FVIpb1wu5Uv2AMYb8RmiHrtSAAAOw==');}span.am,.armenia,.Armenien::before{content:url('data:image/gif;base64,R0lGODdhIAAUAPEAAAAAAAAA//8AAP+AQCwAAAAAIAAUAAACSISPqZviD6OcztCLn81c7g4KX8iNJGYE6sq27qum8EzHQI2/cs4He4/7AWmGgfGITCqXxiLzCW0CotSls4odXLPULRfKCIsVBQA7');}span.au,.australia,.Australien::before{content:url('data:image/gif;base64,R0lGODdhIAAUAPIAAAAAAAAA/2Bg/8DA//8AAP9gQP///wAAACwAAAAAIAAUAAADegi63P6NmEANkTTYzDtXwTVZGOmdHzCsYkusAxp7ymXf+DkYc1r8wAsQKDspYC4SDJXpBUAiTdTUce56tUkluuVcvSmm2EkDiM8ZbQbUEaC9ZHZGYHC/UfIA3VC/G80cdHZoZBR5FINnXx2HfhSFT4COaI2TfxCYmQwJADs=');}span.at,.austria,.�sterreich::before{content:url('data:image/gif;base64,R0lGODdhIAAUAPEAAAAAAP8AAP///wAAACwAAAAAIAAUAAACQISPqZvhD6OcztCLn81c7g4GX8iNJGYI6sq27qum8EzHQI2/cs4Le4/7AWmmE6Vo9ACSGSRTs3weo1Il44pFFAAAOw==');}span.az,.azerbaijan,.Aserbaidschan::before{content:url('data:image/gif;base64,R0lGODlhIAAUAPIAAAAAAAAA/wCAAP8AAP+AgP///wAAAAAAACH5BAAAAPcALAAAAAAgABQAAgNbCLrc/i3ISau9UuHNp+6g9YVkMJbgiXLK4L7wW8xx/bY2XBBukce432D3IviEwR/RZeT9kjmiUXgDUHu060Ah6Hq/4LC4yx2bz2QAei0us98CN3wtn58h+LwjAQA7');}
span.bs,.Bahamas::before{content:url('data:image/gif;base64,R0lGODdhIAAUAPEAAAAAAAAA////AAAAACwAAAAAIAAUAAACPYSPqavhD6OcAdGLq838tc4tYMaMFwMI6sq2roqm7+zGMo3b+B7vPOP7fUwSEXEyPEYSSsqmWTRAnbYqqgAAOw==');}
span.bh,.Bahrain::before{content:url('data:image/gif;base64,R0lGODdhIAAUAPEAAAAAAP8AAP+ggP///ywAAAAAIAAUAAACSISPqZvj70SYtNprYLy8z6xt3hiAoUR65oOmKhCKLgbH7VytMm7pLN+rnYA54Y9YjA1uQN+uaXwSnUvkJ3pE+phTrBTHCIsVBQA7');}
span.bd,.bangladesh,.Bangladesch::before{content:url('data:image/gif;base64,R0lGODdhIAAUAPEAAAAAAACAAEBgAP8AACwAAAAAIAAUAAACT4SPqZvhD6OcztCLn81i+CFkwXZ95pCR03liasSy1/t0sRlOtHPLOkDptX5B4YeyCxiPxIlNmJMklcYZsNRzXbE+q+gJEk1FXrK5wkirEwUAOw==');}
span.bb,.Barbados::before{content:url('data:image/gif;base64,R0lGODdhIAAUAPEAAAAAAAAAgIBAAP+AQCwAAAAAIAAUAAACYYSPqZvhD4ecdMBr7q1cv+w5XBUGYDhS5eml0wqUwQBMtVTDMl0fvBViaQaCm1EA9Ag3NGStKNGVbLRqNBib4qq3pGYZwSFe2B2le1VmUWdSWeuSsuPveUrecnkxjL4/UQAAOw==');}
span.by,.Belarus::before{content:url('data:image/gif;base64,R0lGODdhIAAUAPIAAAAAAACAAP8AAP+AgP///wAAAAAAAAAAACwAAAAAIAAUAAADYwi63P5NDCLIqCLrzbtQWEZRXtmBU3WRZvsB2Bi6JmhJMl3D6c3qJ5gsBwziVLPixiaJKY2UVfL5OvqoS6HzR0WNVoGweEwuB2yXnnltZg7ZcLEXSYjHmaKKHT737dkQgYIOCQA7');}
span.be,.belgium,.Belgien::before{content:url('data:image/gif;base64,R0lGODdhIAAUAPEAAAAAAP8AAP//AAAAACwAAAAAIAAUAAACQISPqcsrD2OYtLYTs6g8XKNJHfUBITSS3/mkU8luLsy63lrP+GnTvH6J9XahYTCX8hWBDSGT4UwSNcYmclTKMgoAOw==');}
span.bz,.Belize::before{content:url('data:image/gif;base64,R0lGODdhIAAUAPIAAAAAAAAA/wCAAIAAAICA//8AAP//AP///ywAAAAAIAAUAAADbwi63P6tyEmrvVKFzbv/4KaFZCkCZkAc7EGkY7gKB027ZQzWvOCzOZSsBvDdaK+QzsMzGAcsGmnZaR4U0NtUuONdDVDelsQDuFbRw3h4U6STIGq1dqOr18PoD4cnoVlwfSmDAXKEWxiJihMQjY4OCQA7');}
span.bj,.Benin::before{content:url('data:image/gif;base64,R0lGODdhIAAUAPEAAAAAAACAAP8AAP//ACwAAAAAIAAUAAACTISPqZvhD4ectFYDc7CcY/104vSB2yiWIJoCpsN2qhZ77ltbc5ZftykICodE4g5STCaPD6VTyHQ8n9HA1Fm9KrPaIrc7/IKDjLJZUQAAOw==');}
span.bt,.Bhutan::before{content:url('data:image/gif;base64,R0lGODdhIAAUAPIAAAAAAICAgMDA//8AAP+AQP///wAAAAAAACwAAAAAIAAUAAADhwi63P6NyEmrvWQozGUAnycNGtBhXxGoBEiWp5WudPDCcRXU343nnpWLBLhtYiaPyqYpFF/HE2PnBDhXxiRHoSo4VQuNL4qxCmkqX3br3ZF2oKf6Z1H05GKmmjxhLNNEAgVzdC1Wh096b4prHiA0A3CEk1GPcoeThEd4N26Ze5yfokQQpaYNCQA7');}
span.blank::before{content:url('data:image/gif;base64,R0lGODlhIAAUAPAAAAAAAP///yH5BAUAAAAALAAAAAAgABQAQAIXhI+py+0Po5y02ouz3rz7D4biSJbm6RQAOw==');}
span.bo,.bolivia,.Bolivien::before{content:url('data:image/gif;base64,R0lGODdhIAAUAPIAAAAAAACAAGBgAIAAAICAAP8AAP//AAAAACwAAAAAIAAUAAADWwi63P6tyEmrvVLhzafuoPWFZDGW4IlyiuG+8EvMRGy77Q0PxsDzulcu2BsEAj4iDqDsGY5O5TAoMCIFzWlza1Acv+CwePz1ks/ooznNDq/b8DecLZ+jIfi8IwEAOw=='');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}
span::before{content:url('');}









</style><?php }
function Responsive_css(){?><style>
/*!
 * Bootstrap Responsive v2.2.2
 *
 * Copyright 2012 Twitter, Inc
 * Licensed under the Apache License v2.0
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Designed and built with all the love in the world @twitter by @mdo and @fat.
 */@-ms-viewport{width:device-width}.clearfix:after,.clearfix:before{display:table;line-height:0;content:""}.clearfix:after{clear:both}.hide-text{font:0/0 a;color:transparent;text-shadow:none;background-color:transparent;border:0}.input-block-level{display:block;width:100%;min-height:30px;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}.hidden{display:none;visibility:hidden}.visible-phone{display:none!important}.visible-tablet{display:none!important}.hidden-desktop{display:none!important}.visible-desktop{display:inherit!important}@media(min-width:768px) AND(max-width:979px){.hidden-desktop{display:inherit!important}.visible-desktop{display:none!important}.visible-tablet{display:inherit!important}.hidden-tablet{display:none!important}}@media(max-width:767px){.hidden-desktop{display:inherit!important}.visible-desktop{display:none!important}.visible-phone{display:inherit!important}.hidden-phone{display:none!important}}@media(min-width:1200px){.row{margin-left:-30px}.row:after,.row:before{display:table;line-height:0;content:""}.row:after{clear:both}[class*=span]{float:left;min-height:1px;margin-left:30px}.container,.navbar-fixed-bottom .container,.navbar-fixed-top .container,.navbar-static-top .container{width:1170px}.span12{width:1170px}.span11{width:1070px}.span10{width:970px}.span9{width:870px}.span8{width:770px}.span7{width:670px}.span6{width:570px}.span5{width:470px}.span4{width:370px}.span3{width:270px}.span2{width:170px}.span1{width:70px}.offset12{margin-left:1230px}.offset11{margin-left:1130px}.offset10{margin-left:1030px}.offset9{margin-left:930px}.offset8{margin-left:830px}.offset7{margin-left:730px}.offset6{margin-left:630px}.offset5{margin-left:530px}.offset4{margin-left:430px}.offset3{margin-left:330px}.offset2{margin-left:230px}.offset1{margin-left:130px}.row-fluid{width:100%}.row-fluid:after,.row-fluid:before{display:table;line-height:0;content:""}.row-fluid:after{clear:both}.row-fluid [class*=span]{display:block;float:left;width:100%;min-height:30px;margin-left:2.564102564102564%;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}.row-fluid [class*=span]:first-child{margin-left:0}.row-fluid .controls-row [class*=span]+[class*=span]{margin-left:2.564102564102564%}.row-fluid .span12{width:100%}.row-fluid .span11{width:91.45299145299145%}.row-fluid .span10{width:82.90598290598291%}.row-fluid .span9{width:74.35897435897436%}.row-fluid .span8{width:65.81196581196582%}.row-fluid .span7{width:57.26495726495726%}.row-fluid .span6{width:48.717948717948715%}.row-fluid .span5{width:40.17094017094017%}.row-fluid .span4{width:31.623931623931625%}.row-fluid .span3{width:23.076923076923077%}.row-fluid .span2{width:14.52991452991453%}.row-fluid .span1{width:5.982905982905983%}.row-fluid .offset12{margin-left:105.12820512820512%}.row-fluid .offset12:first-child{margin-left:102.56410256410257%}.row-fluid .offset11{margin-left:96.58119658119658%}.row-fluid .offset11:first-child{margin-left:94.01709401709402%}.row-fluid .offset10{margin-left:88.03418803418803%}.row-fluid .offset10:first-child{margin-left:85.47008547008548%}.row-fluid .offset9{margin-left:79.48717948717949%}.row-fluid .offset9:first-child{margin-left:76.92307692307693%}.row-fluid .offset8{margin-left:70.94017094017094%}.row-fluid .offset8:first-child{margin-left:68.37606837606839%}.row-fluid .offset7{margin-left:62.393162393162385%}.row-fluid .offset7:first-child{margin-left:59.82905982905982%}.row-fluid .offset6{margin-left:53.84615384615384%}.row-fluid .offset6:first-child{margin-left:51.28205128205128%}.row-fluid .offset5{margin-left:45.299145299145295%}.row-fluid .offset5:first-child{margin-left:42.73504273504273%}.row-fluid .offset4{margin-left:36.75213675213675%}.row-fluid .offset4:first-child{margin-left:34.18803418803419%}.row-fluid .offset3{margin-left:28.205128205128204%}.row-fluid .offset3:first-child{margin-left:25.641025641025642%}.row-fluid .offset2{margin-left:19.65811965811966%}.row-fluid .offset2:first-child{margin-left:17.094017094017094%}.row-fluid .offset1{margin-left:11.11111111111111%}.row-fluid .offset1:first-child{margin-left:8.547008547008547%}.uneditable-input,input,textarea{margin-left:0}.controls-row [class*=span]+[class*=span]{margin-left:30px}.uneditable-input.span12,input.span12,textarea.span12{width:1156px}.uneditable-input.span11,input.span11,textarea.span11{width:1056px}.uneditable-input.span10,input.span10,textarea.span10{width:956px}.uneditable-input.span9,input.span9,textarea.span9{width:856px}.uneditable-input.span8,input.span8,textarea.span8{width:756px}.uneditable-input.span7,input.span7,textarea.span7{width:656px}.uneditable-input.span6,input.span6,textarea.span6{width:556px}.uneditable-input.span5,input.span5,textarea.span5{width:456px}.uneditable-input.span4,input.span4,textarea.span4{width:356px}.uneditable-input.span3,input.span3,textarea.span3{width:256px}.uneditable-input.span2,input.span2,textarea.span2{width:156px}.uneditable-input.span1,input.span1,textarea.span1{width:56px}.thumbnails{margin-left:-30px}.thumbnails>li{margin-left:30px}.row-fluid .thumbnails{margin-left:0}}@media(min-width:768px) AND(max-width:979px){.row{margin-left:-20px}.row:after,.row:before{display:table;line-height:0;content:""}.row:after{clear:both}[class*=span]{float:left;min-height:1px;margin-left:20px}.container,.navbar-fixed-bottom .container,.navbar-fixed-top .container,.navbar-static-top .container{width:724px}.span12{width:724px}.span11{width:662px}.span10{width:600px}.span9{width:538px}.span8{width:476px}.span7{width:414px}.span6{width:352px}.span5{width:290px}.span4{width:228px}.span3{width:166px}.span2{width:104px}.span1{width:42px}.offset12{margin-left:764px}.offset11{margin-left:702px}.offset10{margin-left:640px}.offset9{margin-left:578px}.offset8{margin-left:516px}.offset7{margin-left:454px}.offset6{margin-left:392px}.offset5{margin-left:330px}.offset4{margin-left:268px}.offset3{margin-left:206px}.offset2{margin-left:144px}.offset1{margin-left:82px}.row-fluid{width:100%}.row-fluid:after,.row-fluid:before{display:table;line-height:0;content:""}.row-fluid:after{clear:both}.row-fluid [class*=span]{display:block;float:left;width:100%;min-height:30px;margin-left:2.7624309392265194%;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}.row-fluid [class*=span]:first-child{margin-left:0}.row-fluid .controls-row [class*=span]+[class*=span]{margin-left:2.7624309392265194%}.row-fluid .span12{width:100%}.row-fluid .span11{width:91.43646408839778%}.row-fluid .span10{width:82.87292817679558%}.row-fluid .span9{width:74.30939226519337%}.row-fluid .span8{width:65.74585635359117%}.row-fluid .span7{width:57.18232044198895%}.row-fluid .span6{width:48.61878453038674%}.row-fluid .span5{width:40.05524861878453%}.row-fluid .span4{width:31.491712707182323%}.row-fluid .span3{width:22.92817679558011%}.row-fluid .span2{width:14.3646408839779%}.row-fluid .span1{width:5.801104972375691%}.row-fluid .offset12{margin-left:105.52486187845304%}.row-fluid .offset12:first-child{margin-left:102.76243093922652%}.row-fluid .offset11{margin-left:96.96132596685082%}.row-fluid .offset11:first-child{margin-left:94.1988950276243%}.row-fluid .offset10{margin-left:88.39779005524862%}.row-fluid .offset10:first-child{margin-left:85.6353591160221%}.row-fluid .offset9{margin-left:79.8342541436464%}.row-fluid .offset9:first-child{margin-left:77.07182320441989%}.row-fluid .offset8{margin-left:71.2707182320442%}.row-fluid .offset8:first-child{margin-left:68.50828729281768%}.row-fluid .offset7{margin-left:62.70718232044199%}.row-fluid .offset7:first-child{margin-left:59.94475138121547%}.row-fluid .offset6{margin-left:54.14364640883978%}.row-fluid .offset6:first-child{margin-left:51.38121546961326%}.row-fluid .offset5{margin-left:45.58011049723757%}.row-fluid .offset5:first-child{margin-left:42.81767955801105%}.row-fluid .offset4{margin-left:37.01657458563536%}.row-fluid .offset4:first-child{margin-left:34.25414364640884%}.row-fluid .offset3{margin-left:28.45303867403315%}.row-fluid .offset3:first-child{margin-left:25.69060773480663%}.row-fluid .offset2{margin-left:19.88950276243094%}.row-fluid .offset2:first-child{margin-left:17.12707182320442%}.row-fluid .offset1{margin-left:11.32596685082873%}.row-fluid .offset1:first-child{margin-left:8.56353591160221%}.uneditable-input,input,textarea{margin-left:0}.controls-row [class*=span]+[class*=span]{margin-left:20px}.uneditable-input.span12,input.span12,textarea.span12{width:710px}.uneditable-input.span11,input.span11,textarea.span11{width:648px}.uneditable-input.span10,input.span10,textarea.span10{width:586px}.uneditable-input.span9,input.span9,textarea.span9{width:524px}.uneditable-input.span8,input.span8,textarea.span8{width:462px}.uneditable-input.span7,input.span7,textarea.span7{width:400px}.uneditable-input.span6,input.span6,textarea.span6{width:338px}.uneditable-input.span5,input.span5,textarea.span5{width:276px}.uneditable-input.span4,input.span4,textarea.span4{width:214px}.uneditable-input.span3,input.span3,textarea.span3{width:152px}.uneditable-input.span2,input.span2,textarea.span2{width:90px}.uneditable-input.span1,input.span1,textarea.span1{width:28px}}@media(max-width:767px){body{padding-right:20px;padding-left:20px}.navbar-fixed-bottom,.navbar-fixed-top,.navbar-static-top{margin-right:-20px;margin-left:-20px}.container-fluid{padding:0}.dl-horizontal dt{float:none;width:auto;clear:none;text-align:left}.dl-horizontal dd{margin-left:0}.container{width:auto}.row-fluid{width:100%}.row,.thumbnails{margin-left:0}.thumbnails>li{float:none;margin-left:0}.row-fluid [class*=span],.uneditable-input[class*=span],[class*=span]{display:block;float:none;width:100%;margin-left:0;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}.row-fluid .span12,.span12{width:100%;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}.row-fluid [class*=offset]:first-child{margin-left:0}.input-large,.input-xlarge,.input-xxlarge,.uneditable-input,input[class*=span],select[class*=span],textarea[class*=span]{display:block;width:100%;min-height:30px;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}.input-append input,.input-append input[class*=span],.input-prepend input,.input-prepend input[class*=span]{display:inline-block;width:auto}.controls-row [class*=span]+[class*=span]{margin-left:0}.modal{position:fixed;top:20px;right:20px;left:20px;width:auto;margin:0}.modal.fade{top:-100px}.modal.fade.in{top:20px}}@media(max-width:480px){.nav-collapse{-webkit-transform:translate3d(0,0,0)}.page-header h1 small{display:block;line-height:20px}input[type=checkbox],input[type=radio]{border:1px solid #ccc}.form-horizontal .control-label{float:none;width:auto;padding-top:0;text-align:left}.form-horizontal .controls{margin-left:0}.form-horizontal .control-list{padding-top:0}.form-horizontal .form-actions{padding-right:10px;padding-left:10px}.media .pull-left,.media .pull-right{display:block;float:none;margin-bottom:10px}.media-object{margin-right:0;margin-left:0}.modal{top:10px;right:10px;left:10px}.modal-header .close{padding:10px;margin:-10px}.carousel-caption{position:static}}@media(max-width:979px){body{padding-top:0}.navbar-fixed-bottom,.navbar-fixed-top{position:static}.navbar-fixed-top{margin-bottom:20px}.navbar-fixed-bottom{margin-top:20px}.navbar-fixed-bottom .navbar-inner,.navbar-fixed-top .navbar-inner{padding:5px}.navbar .container{width:auto;padding:0}.navbar .brand{padding-right:10px;padding-left:10px;margin:0 0 0 -5px}.nav-collapse{clear:both}.nav-collapse .nav{float:none;margin:0 0 10px}.nav-collapse .nav>li{float:none}.nav-collapse .nav>li>a{margin-bottom:2px}.nav-collapse .nav>.divider-vertical{display:none}.nav-collapse .nav .nav-header{color:#777;text-shadow:none}.nav-collapse .dropdown-menu a,.nav-collapse .nav>li>a{padding:9px 15px;font-weight:700;color:#777;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px}.nav-collapse .btn{padding:4px 10px 4px;font-weight:400;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px}.nav-collapse .dropdown-menu li+li a{margin-bottom:2px}.nav-collapse .dropdown-menu a:hover,.nav-collapse .nav>li>a:hover{background-color:#f2f2f2}.navbar-inverse .nav-collapse .dropdown-menu a,.navbar-inverse .nav-collapse .nav>li>a{color:#999}.navbar-inverse .nav-collapse .dropdown-menu a:hover,.navbar-inverse .nav-collapse .nav>li>a:hover{background-color:#111}.nav-collapse.in .btn-group{padding:0;margin-top:5px}.nav-collapse .dropdown-menu{position:static;top:auto;left:auto;display:none;float:none;max-width:none;padding:0;margin:0 15px;background-color:transparent;border:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none}.nav-collapse .open>.dropdown-menu{display:block}.nav-collapse .dropdown-menu:after,.nav-collapse .dropdown-menu:before{display:none}.nav-collapse .dropdown-menu .divider{display:none}.nav-collapse .nav>li>.dropdown-menu:after,.nav-collapse .nav>li>.dropdown-menu:before{display:none}.nav-collapse .navbar-form,.nav-collapse .navbar-search{float:none;padding:10px 15px;margin:10px 0;border-top:1px solid #f2f2f2;border-bottom:1px solid #f2f2f2;-webkit-box-shadow:inset 0 1px 0 rgba(255,255,255,.1),0 1px 0 rgba(255,255,255,.1);-moz-box-shadow:inset 0 1px 0 rgba(255,255,255,.1),0 1px 0 rgba(255,255,255,.1);box-shadow:inset 0 1px 0 rgba(255,255,255,.1),0 1px 0 rgba(255,255,255,.1)}.navbar-inverse .nav-collapse .navbar-form,.navbar-inverse .nav-collapse .navbar-search{border-top-color:#111;border-bottom-color:#111}.navbar .nav-collapse .nav.pull-right{float:none;margin-left:0}.nav-collapse,.nav-collapse.collapse{height:0;overflow:hidden}.navbar .btn-navbar{display:block}.navbar-static .navbar-inner{padding-right:10px;padding-left:10px}}@media(min-width:980px){.nav-collapse.collapse{height:auto!important;overflow:visible!important}}</style><?php }
function Switch_css(){?><style>.has-switch{display:inline-block;cursor:pointer;-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px;border:1px solid;border-color:rgba(0,0,0,.1) rgba(0,0,0,.1) rgba(0,0,0,.25);position:relative;text-align:left;overflow:hidden;line-height:8px;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;-o-user-select:none;user-select:none;vertical-align:middle;min-width:100px}.has-switch.switch-mini{min-width:72px}.has-switch.switch-mini i.switch-mini-icons{height:1.2em;line-height:9px;vertical-align:text-top;text-align:center;transform:scale(.6);margin-top:-1px;margin-bottom:-1px}.has-switch.switch-small{min-width:80px}.has-switch.switch-large{min-width:120px}.has-switch.deactivate{opacity:.5;cursor:default!important}.has-switch.deactivate label,.has-switch.deactivate span{cursor:default!important}.has-switch>div{display:inline-block;width:150%;position:relative;top:0}.has-switch>div.switch-animate{-webkit-transition:left .5s;-moz-transition:left .5s;-o-transition:left .5s;transition:left .5s}.has-switch>div.switch-off{left:-50%}.has-switch>div.switch-on{left:0}.has-switch input[type=checkbox],.has-switch input[type=radio]{display:none}.has-switch label,.has-switch span{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;cursor:pointer;position:relative;display:inline-block;height:100%;padding-bottom:4px;padding-top:4px;font-size:14px;line-height:20px}.has-switch label.switch-mini,.has-switch span.switch-mini{padding-bottom:4px;padding-top:4px;font-size:10px;line-height:9px}.has-switch label.switch-small,.has-switch span.switch-small{padding-bottom:3px;padding-top:3px;font-size:12px;line-height:18px}.has-switch label.switch-large,.has-switch span.switch-large{padding-bottom:9px;padding-top:9px;font-size:16px;line-height:normal}.has-switch label{text-align:center;margin-top:-1px;margin-bottom:-1px;z-index:100;width:34%;border-left:1px solid #ccc;border-right:1px solid #ccc;color:#333;text-shadow:0 -1px 0 rgba(0,0,0,.25);background-color:#f5f5f5;background-image:-moz-linear-gradient(top,#fff,#e6e6e6);background-image:-webkit-gradient(linear,0 0,0 100%,from(#fff),to(#e6e6e6));background-image:-webkit-linear-gradient(top,#fff,#e6e6e6);background-image:-o-linear-gradient(top,#fff,#e6e6e6);background-image:linear-gradient(to bottom,#fff,#e6e6e6);background-repeat:repeat-x;border-color:#e6e6e6 #e6e6e6 #bfbfbf;border-color:rgba(0,0,0,.1) rgba(0,0,0,.1) rgba(0,0,0,.25)}.has-switch label.active,.has-switch label.disabled,.has-switch label:active,.has-switch label:focus,.has-switch label:hover,.has-switch label[disabled]{color:#333;background-color:#e6e6e6}.has-switch label i{color:#000;text-shadow:0 1px 0 #fff;line-height:18px;pointer-events:none}.has-switch span{text-align:center;z-index:1;width:33%}.has-switch span.switch-left{-webkit-border-top-left-radius:4px;-moz-border-radius-topleft:4px;border-top-left-radius:4px;-webkit-border-bottom-left-radius:4px;-moz-border-radius-bottomleft:4px;border-bottom-left-radius:4px}.has-switch span.switch-right{color:#333;text-shadow:0 1px 1px rgba(255,255,255,.75);background-color:#f0f0f0;background-image:-moz-linear-gradient(top,#e6e6e6,#fff);background-image:-webkit-gradient(linear,0 0,0 100%,from(#e6e6e6),to(#fff));background-image:-webkit-linear-gradient(top,#e6e6e6,#fff);background-image:-o-linear-gradient(top,#e6e6e6,#fff);background-image:linear-gradient(to bottom,#e6e6e6,#fff);background-repeat:repeat-x;border-color:#fff #fff #d9d9d9;border-color:rgba(0,0,0,.1) rgba(0,0,0,.1) rgba(0,0,0,.25)}.has-switch span.switch-right.active,.has-switch span.switch-right.disabled,.has-switch span.switch-right:active,.has-switch span.switch-right:focus,.has-switch span.switch-right:hover,.has-switch span.switch-right[disabled]{color:#333;background-color:#fff}.has-switch span.switch-left,.has-switch span.switch-primary{color:#fff;text-shadow:0 -1px 0 rgba(0,0,0,.25);background-color:#005fcc;background-image:-moz-linear-gradient(top,#04c,#08c);background-image:-webkit-gradient(linear,0 0,0 100%,from(#04c),to(#08c));background-image:-webkit-linear-gradient(top,#04c,#08c);background-image:-o-linear-gradient(top,#04c,#08c);background-image:linear-gradient(to bottom,#04c,#08c);background-repeat:repeat-x;border-color:#08c #08c #005580;border-color:rgba(0,0,0,.1) rgba(0,0,0,.1) rgba(0,0,0,.25)}.has-switch span.switch-left.active,.has-switch span.switch-left.disabled,.has-switch span.switch-left:active,.has-switch span.switch-left:focus,.has-switch span.switch-left:hover,.has-switch span.switch-left[disabled],.has-switch span.switch-primary.active,.has-switch span.switch-primary.disabled,.has-switch span.switch-primary:active,.has-switch span.switch-primary:focus,.has-switch span.switch-primary:hover,.has-switch span.switch-primary[disabled]{color:#fff;background-color:#08c}.has-switch span.switch-info{color:#fff;text-shadow:0 -1px 0 rgba(0,0,0,.25);background-color:#41a7c5;background-image:-moz-linear-gradient(top,#2f96b4,#5bc0de);background-image:-webkit-gradient(linear,0 0,0 100%,from(#2f96b4),to(#5bc0de));background-image:-webkit-linear-gradient(top,#2f96b4,#5bc0de);background-image:-o-linear-gradient(top,#2f96b4,#5bc0de);background-image:linear-gradient(to bottom,#2f96b4,#5bc0de);background-repeat:repeat-x;border-color:#5bc0de #5bc0de #28a1c5;border-color:rgba(0,0,0,.1) rgba(0,0,0,.1) rgba(0,0,0,.25)}.has-switch span.switch-info.active,.has-switch span.switch-info.disabled,.has-switch span.switch-info:active,.has-switch span.switch-info:focus,.has-switch span.switch-info:hover,.has-switch span.switch-info[disabled]{color:#fff;background-color:#5bc0de}.has-switch span.switch-success{color:#fff;text-shadow:0 -1px 0 rgba(0,0,0,.25);background-color:#58b058;background-image:-moz-linear-gradient(top,#51a351,#62c462);background-image:-webkit-gradient(linear,0 0,0 100%,from(#51a351),to(#62c462));background-image:-webkit-linear-gradient(top,#51a351,#62c462);background-image:-o-linear-gradient(top,#51a351,#62c462);background-image:linear-gradient(to bottom,#51a351,#62c462);background-repeat:repeat-x;border-color:#62c462 #62c462 #3b9e3b;border-color:rgba(0,0,0,.1) rgba(0,0,0,.1) rgba(0,0,0,.25)}.has-switch span.switch-success.active,.has-switch span.switch-success.disabled,.has-switch span.switch-success:active,.has-switch span.switch-success:focus,.has-switch span.switch-success:hover,.has-switch span.switch-success[disabled]{color:#fff;background-color:#62c462}.has-switch span.switch-warning{color:#fff;text-shadow:0 -1px 0 rgba(0,0,0,.25);background-color:#f9a123;background-image:-moz-linear-gradient(top,#f89406,#fbb450);background-image:-webkit-gradient(linear,0 0,0 100%,from(#f89406),to(#fbb450));background-image:-webkit-linear-gradient(top,#f89406,#fbb450);background-image:-o-linear-gradient(top,#f89406,#fbb450);background-image:linear-gradient(to bottom,#f89406,#fbb450);background-repeat:repeat-x;border-color:#fbb450 #fbb450 #f89406;border-color:rgba(0,0,0,.1) rgba(0,0,0,.1) rgba(0,0,0,.25)}.has-switch span.switch-warning.active,.has-switch span.switch-warning.disabled,.has-switch span.switch-warning:active,.has-switch span.switch-warning:focus,.has-switch span.switch-warning:hover,.has-switch span.switch-warning[disabled]{color:#fff;background-color:#fbb450}.has-switch span.switch-danger{color:#fff;text-shadow:0 -1px 0 rgba(0,0,0,.25);background-color:#d14641;background-image:-moz-linear-gradient(top,#bd362f,#ee5f5b);background-image:-webkit-gradient(linear,0 0,0 100%,from(#bd362f),to(#ee5f5b));background-image:-webkit-linear-gradient(top,#bd362f,#ee5f5b);background-image:-o-linear-gradient(top,#bd362f,#ee5f5b);background-image:linear-gradient(to bottom,#bd362f,#ee5f5b);background-repeat:repeat-x;border-color:#ee5f5b #ee5f5b #e51d18;border-color:rgba(0,0,0,.1) rgba(0,0,0,.1) rgba(0,0,0,.25)}.has-switch span.switch-danger.active,.has-switch span.switch-danger.disabled,.has-switch span.switch-danger:active,.has-switch span.switch-danger:focus,.has-switch span.switch-danger:hover,.has-switch span.switch-danger[disabled]{color:#fff;background-color:#ee5f5b}.has-switch span.switch-default{color:#333;text-shadow:0 1px 1px rgba(255,255,255,.75);background-color:#f0f0f0;background-image:-moz-linear-gradient(top,#e6e6e6,#fff);background-image:-webkit-gradient(linear,0 0,0 100%,from(#e6e6e6),to(#fff));background-image:-webkit-linear-gradient(top,#e6e6e6,#fff);background-image:-o-linear-gradient(top,#e6e6e6,#fff);background-image:linear-gradient(to bottom,#e6e6e6,#fff);background-repeat:repeat-x;border-color:#fff #fff #d9d9d9;border-color:rgba(0,0,0,.1) rgba(0,0,0,.1) rgba(0,0,0,.25)}.has-switch span.switch-default.active,.has-switch span.switch-default.disabled,.has-switch span.switch-default:active,.has-switch span.switch-default:focus,.has-switch span.switch-default:hover,.has-switch span.switch-default[disabled]{color:#333;background-color:#fff}</style><?php }
function Skin_css(){?><style>
							body{padding-top:60px;padding-bottom:40px}.sidebar-nav{padding:9px 0}.box{padding:8px10px;background:#fdfdfd;border:1px solid #eee;border-bottom:3px solid #eee;border-radius:6px;margin-bottom:30px}.box table{margin-bottom:10px}
							.box>h4{border-bottom:1px solid #eee;padding-bottom:7px}.container{margin-right:auto;margin-left:auto;}.row{position:relative;top:40px;max-width:1680px;}.clearfix{*zoom:1;}.clearfix:before,.clearfix:after{display:table;
							content:'';line-height:0;}.clearfix:after{clear:both;}.hide-text{font:0/0 a;color:transparent;text-shadow:none;background-color:transparent;border:0;}.input-block-level{display:block;width:100%;min-height:30px;-webkit-box-sizing:border-box;
							-moz-box-sizing:border-box;box-sizing:border-box;}article,aside,details,figcaption,figure,footer,header,hgroup,nav,section{display:block;}audio,canvas,video{display:inline-block;*display:inline;*zoom:1;}audio:not([controls]){display:none;}
							html{font-size:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;}a:focus{outline:thin dotted#333;outline:5px auto -webkit-focus-ring-color;outline-offset:-2px;}a:hover,a:active{outline:0;}sub,sup{position:relative;font-size:75%;
							line-height:0;vertical-align:baseline;}sup{top:-0.5em;}sub{bottom:-0.25em;}img{max-width:100%;width:auto\9;height:auto;vertical-align:middle;border:0;-ms-interpolation-mode:bicubic;}#map_canvas img,.google-maps img{max-width:none;}
							button,input,select,textarea{margin:0;font-size:100%;vertical-align:middle;}button,input{*overflow:visible;line-height:normal;}button::-moz-focus-inner,input::-moz-focus-inner{padding:0;border:0;}button,html input[type='button'],
							input[type='reset'],input[type='submit']{-webkit-appearance:button;cursor:pointer;}label,select,button,input[type='button'],input[type='reset'],input[type='submit'],input[type='radio'],input[type='checkbox']{cursor:pointer;}
							input[type='search']{-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;-webkit-appearance:textfield;}input[type='search']::-webkit-search-decoration,
							input[type='search']::-webkit-search-cancel-button{-webkit-appearance:none;}textarea{overflow:auto;vertical-align:top;}@media print{*{text-shadow:none !important;color:#000 !important;background:transparent !important;box-shadow:none
							!important;}a,a:visited{text-decoration:underline;}a[href]:after{content:' (' attr(href) ')';}abbr[title]:after{content:' (' attr(title) ')';}.ir a:after,a[href^='javascript:']:after,a[href^='#']:after{content:'';}pre,
							blockquote{border:1px solid#999;page-break-inside:avoid;}thead{display:table-header-group;}tr,img{page-break-inside:avoid;}img{max-width:100% !important;}@page{margin:0.5cm;}p,h2,h3{orphans:3;widows:3;}h2,h3{page-break-after:avoid;
							}}body{margin:0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:14px;color:#333333;background-color:#ffffff;}a{color:#0088cc;text-decoration:none;}a:hover,a:focus{color:#005580;text-decoration:underline;}
							.img-rounded{-webkit-border-radius:6px;-moz-border-radius:6px;border-radius:6px;}.img-polaroid{padding:4px;background-color:#fff;border:1px solid #ccc;border:1px solid rgba(0,0,0,0.2);-webkit-box-shadow:0 1px3px rgba(0,0,0,0.1);
							-moz-box-shadow:0 1px3px rgba(0,0,0,0.1);box-shadow:0 1px3px rgba(0,0,0,0.1);}.img-circle{-webkit-border-radius:500px;-moz-border-radius:500px;border-radius:500px;}.row{margin-top:-10px;margin-left:-20px*zoom:1;}.row:before,
							.row:after{display:table;content:'';line-height:0;}.row:after{clear:both;}[class*='span']{float:left;min-height:1px;margin-left:10px;}.container,.navbar-static-top .container,.navbar-fixed-top .container,.navbar-fixed-bottom
							.container{width:940px;}[class*='span'].hide,.row-fluid [class*='span'].hide{display:none;}[class*='span'].pull-right,.row-fluid [class*='span'].pull-right{float:right;}.container{margin-right:auto;margin-left:auto;*zoom:1;}.container:before,
							.container:after{display:table;content:'';line-height:0;}.container:after{clear:both;}.container-fluid{padding-right:20px;padding-left:20px;*zoom:1;}.container-fluid:before,.container-fluid:after{display:table;content:'';line-height:0;}
							.container-fluid:after{clear:both;}p{margin:0 0 10px;}.lead{margin-bottom:20px;font-size:21px;font-weight:200;line-height:30px;}small{font-size:85%;}strong{font-weight:bold;}em{font-style:italic;}cite{font-style:normal;}.muted{color:#999999;}
							a.muted:hover,a.muted:focus{color:#808080;}.text-warning{color:#c09853;}a.text-warning:hover,a.text-warning:focus{color:#a47e3c;}.text-error{color:#b94a48;}a.text-error:hover,a.text-error:focus{color:#953b39;}.text-info{color:#3a87ad;}
							a.text-info:hover,a.text-info:focus{color:#2d6987;}.text-success{color:#468847;}a.text-success:hover,a.text-success:focus{color:#356635;}.text-left{text-align:left;}.text-right{text-align:right;}.text-center{text-align:center;}</style><?php }
function Formation_css(){?><style>
							.accordion-body.in{overflow:visible}.accordion-body.in:hover{overflow:visible}.playerIsOnPitch{background-color:#bdefbd}.playerIsOnBench{background-color:#ebffad}#pitch{width:450px;height:680px;border:1px solid #000;background-color:green;
							position:relative;margin-bottom:10px;background-image:url('../img/formation_pitch_bg.png');background-repeat:no-repeat;background-position:center}.playersColumn{margin-left:0}.pitchColumn{width:470px}@media(max-width:979px)and
							(min-width:768px){.playersColumn{margin-left:20px}}#playersSelection .accordion-inner{padding:0}.playerinfo{border-top:1px solid #e5e5e5;padding:04px;position:relative;cursor:default}.playerBlocked{opacity:.5}
							.playerinfoBar{height:24px;font-size:12px}.playerinfoIcons{position:absolute;right:5px;padding-top:2px}.playerBlockedLabel{color:red;font-weight:700;opacity:1}.playerinfoStrengthRow{width:100%}.playerinfoStrengthRow:after,
							.playerinfoStrengthRow:before{display:table;line-height:0;content:""}.playerinfoStrengthRow:after{clear:both}.playerinfoStrength>.bar{font-size:10px;line-height:14px}.playerinfoStrength{height:14px;margin:0}
							.playerinfoStrengthLabelFull{display:block;float:left;width:80px;font-size:12px}.mainposition{color:#04c}.secondposition{color:#2f96b4}.position{position:absolute;width:90px;height:60px}.positionLabel{text-align:center;margin-top:20px;
							font-size:.9em;font-weight:700;cursor:default}.positionPlayer{background-color:#fff;border:1px solid#000;font-size:9px;width:100%;text-align:center;position:absolute;top:60px;left:-1px;word-wrap:break-word;border-radius:3px;cursor:default}
							.position>.playerinfoStrength{position:absolute;top:-18px;width:100%}.positionPlayerRemove{position:absolute;top:-5px;right:0}.positionPlayerIcons{position:absolute;top:-5px;right:0}.position.T{bottom:50px;left:180px}.position.IV.leftWing,
							.position.LV{bottom:160px;left:18px}.position.LV.goalyRow,.position.RV.goalyRow{bottom:50px}.position.IV{bottom:160px;left:180px}.position.IV.leftPos{left:126px}.position.IV.rightPos{left:234px}.position.IV.rightWing,.position
							.RV{bottom:160px;left:342px}.position.DM{bottom:270px;left:180px}.position.DM.leftPos{left:126px}.position.DM.rightPos{left:234px}.position.DM.leftOuterPos{left:18px}.position.DM.rightOuterPos{left:342px}.position.LM{bottom:380px;left:18px}
							.position.ZM{bottom:380px;left:180px}.position.ZM.leftPos{left:126px}.position.ZM.rightPos{left:234px}.position.RM{bottom:380px;left:342px}.position.OM{bottom:490px;left:180px}.position.OM.leftPos{left:126px}.position.OM.rightPos{left:234px}
							.position.OM.leftOuterPos{left:18px}.position.OM.rightOuterPos{left:342px}.position.LS{bottom:600px;left:18px}.position.MS{bottom:600px;left:180px}.position.MS.leftPos{left:126px}.position.MS.rightPos{left:234px}.position.RS{bottom:600px;
							left:342px}.freePosition{background-color:#fff;border-radius:6px;opacity:.7}.playerDropHover{background-color:#ccc;opacity:1}.playerRemoveLink{display:none}.formationPlayerPicture{position:absolute;top:0;text-align:center;width:100%}
							.formationPlayerPicture>img{max-width:90px;max-height:60px}.positionStatePrimary.jersey{background-image:url('../img/shirts_sprite.png');background-repeat:no-repeat;background-position:0 -60px}.positionStateSecondary
							.jersey{background-image:url('../img/shirts_sprite.png');background-repeat:no-repeat;background-position:-90px -60px}.positionStateWrong.jersey{background-image:url('../img/shirts_sprite.png');background-repeat:no-repeat;
							background-position:0 0}.positionStateSecondary{color:#00f}.positionStateWrong{color:red}.playerinfo.ui-draggable-dragging{background-color:#fff;border:1px solid #000;border-radius:6px;opacity:.95;max-width:150px}.playerinfo.
							ui-draggable-dragging.playerinfoIcons,.playerinfo.ui-draggable-dragging.playerinfoStrengthRow{display:none}.benchPlaceholder{font-style:italic}.benchPlayerRemove,.benchPlayerSubAdd,.benchPlayerSubInfo,.benchPlayerSubInfoConditionDeficit,
							.benchPlayerSubInfoConditionLeading,.benchPlayerSubInfoConditionTie{display:none}</style><?php }
function Slider_css(){?><style>
							/*!
							 * Slider for Bootstrap
							 *
							 * Copyright 2012 Stefan Petre
							 * Licensed under the Apache License v2.0
							 * http://www.apache.org/licenses/LICENSE-2.0
							 *
							 */
 							.slider{display:inline-block;vertical-align:middle;position:relative}.slider.slider-horizontal{width:210px;height:20px}.slider.slider-horizontal .slider-track{height:10px;width:100%;margin-top:-5px;top:50%;left:0}.slider.slider-horizontal 									.slider-selection{height:100%;top:0;bottom:0}.slider.slider-horizontal .slider-handle{margin-left:-10px;margin-top:-5px}.slider.slider-horizontal .slider-handle.triangle{border-width:0 10px 10px 10px;width:0;height:0;
 							border-bottom-color:#0480be;margin-top:0}.slider.slider-vertical{height:210px;width:20px}.slider.slider-vertical .slider-track{width:10px;height:100%;margin-left:-5px;left:50%;top:0}.slider.slider-vertical .slider-selection{width:100%;
 							left:0;top:0;bottom:0}.slider.slider-vertical .slider-handle{margin-left:-5px;margin-top:-10px}.slider.slider-vertical .slider-handle.triangle{border-width:10px 0 10px 10px;width:1px;height:1px;border-left-color:#0480be;margin-left:0}
 							.slider input{display:none}.slider .tooltip-inner{white-space:nowrap}.slider-track{position:absolute;cursor:pointer;background-color:#f7f7f7;background-image:-moz-linear-gradient(top,#f5f5f5,#f9f9f9);
 							background-image:-webkit-gradient(linear,0 0,0 100%,from(#f5f5f5),to(#f9f9f9));background-image:-webkit-linear-gradient(top,#f5f5f5,#f9f9f9);background-image:-o-linear-gradient(top,#f5f5f5,#f9f9f9);background-image:linear-gradient(to 										bottom,#f5f5f5,#f9f9f9);background-repeat:repeat-x;-webkit-box-shadow:inset 0 1px 2px rgba(0,0,0,.1);-moz-box-shadow:inset 0 1px 2px rgba(0,0,0,.1);box-shadow:inset 0 1px 2px rgba(0,0,0,.1);-webkit-border-radius:4px;-moz-border-radius:4px;
 							border-radius:4px}.slider-selection{position:absolute;background-color:#f7f7f7;background-image:-moz-linear-gradient(top,#f9f9f9,#f5f5f5);background-image:-webkit-gradient(linear,0 0,0 100%,from(#f9f9f9),to(#f5f5f5));
 							background-image:-webkit-linear-gradient(top,#f9f9f9,#f5f5f5);background-image:-o-linear-gradient(top,#f9f9f9,#f5f5f5);background-image:linear-gradient(to bottom,#f9f9f9,#f5f5f5);background-repeat:repeat-x;-webkit-box-shadow:inset 0 -1px 0 								rgba(0,0,0,.15);-moz-box-shadow:inset 0 -1px 0 rgba(0,0,0,.15);box-shadow:inset 0 -1px 0 rgba(0,0,0,.15);-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;-webkit-border-radius:4px;-moz-border-radius:4px;
 							border-radius:4px}.slider-handle{position:absolute;width:20px;height:20px;background-color:#0e90d2;background-image:-moz-linear-gradient(top,#149bdf,#0480be);background-image:-webkit-gradient(linear,0 0,0 100%,from(#149bdf),to(#0480be))
 							;background-image:-webkit-linear-gradient(top,#149bdf,#0480be);background-image:-o-linear-gradient(top,#149bdf,#0480be);background-image:linear-gradient(to bottom,#149bdf,#0480be);
 							background-repeat:repeat-x;-webkit-box-shadow:inset 0 1px 0 rgba(255,255,255,.2),0 1px 2px rgba(0,0,0,.05);-moz-box-shadow:inset 0 1px 0 rgba(255,255,255,.2),0 1px 2px rgba(0,0,0,.05);box-shadow:inset 0 1px 0 rgba(255,255,255,.2),0 1px2px 								rgba(0,0,0,.05);opacity:.8;border:0 solid transparent}.slider-handle.round{-webkit-border-radius:20px;-moz-border-radius:20px;border-radius:20px}.slider-handle.triangle{background:transparent none}</style><?php }
function Websoccer_css(){?><style>
							.inforow{margin-top:10px}.infolabel{font-weight:700;background:#f5f5f5;padding:8px;border-radius:4px}.infovalue{padding:8px}.noBottomMargin{margin-bottom:0}.darkIcon{color:#000}.darkIcon:active,.darkIcon:hover{color:#444;
							text-decoration:none}.collapse{overflow:hidden}.dl-horizontal dt{white-space:normal}.tab-content{overflow:visible}.popover{max-width:500px}#ajaxLoaderPage{display:none;background:url('../img/ajaxloaderbar.gif') no-repeat center center;
							height:15px;width:100%;position:fixed;bottom:10px}.languageswitcher{list-style-type:none;margin:0;padding:0;text-align:center}.languageswitcher li{display:inline;padding-right:20px}.strength_success{color:#5eb95e}
							.strength_info{color:#4bb1cf}.strength_warning{color:#faa732}.strength_danger{color:#dd514c}.incell-strength-label{width:30px;display:inline-block}#report_result{border-top:1px solid #000;border-bottom:1px solid#000;text-align:center}
							#report_goals_guest,#report_goals_home,#report_goals_separator{padding:30px;display:inline-block;font-size:36px;font-weight:700}#report_goals_home{padding-left:0}#report_goals_guest{padding-right:0}#reportarea{border:1px solid #000;
							padding:5px;border:1px solid #dde2e4;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.05);-moz-box-shadow:inset 0 1px 1px rgba(0,0,0,.05);box-shadow:inset 0 1px 1px 											rgba(0,0,0,.05)}.report_minute{font-weight:700;color:#000}.report_comment{color:#666}.report_team_home{color:#069}.report_team_guest{color:#f60}#reporttable{table-layout:fixed}#reporttable td{padding-top:10px}.minutesPlayed{font-weight:700;
							font-size:80%;cursor:default}.youthmatchreporticon{max-height:14px}.websoccericon-goal{background:url('../img/icons-websoccer-32.png') no-repeat -95px -32px;width:34px;height:32px;display:inline-block}
							.websoccericon-yellowcard{background:url('../img/icons-websoccer-32.png') no-repeat -223px -96px;width:34px;height:32px;display:inline-block}.websoccericon-redcard{background:url('../img/icons-websoccer-32.png') no-repeat -160px -96px;
							width:34px;height:32px;display:inline-block}.websoccericon-substitution{background:url('../img/icons-websoccer-32.png') no-repeat -223px -163px;width:34px;height:32px;display:inline-block}
							.websoccericon-injury{background:url('../img/icons-websoccer-32.png') no-repeat -222px -224px;width:34px;height:32px;display:inline-block}.websoccericon-shoot{background:url('../img/icons-websoccer-32.png') no-repeat -95px
							-225px;width:34px;height:32px;display:inline-block}.websoccericon-attempt{background:url('../img/icons-websoccer-32.png') no-repeat -31px -225px;width:34px;height:32px;display:inline-block}
							.websoccericon-penalty{background:url('../img/icons-websoccer-32.png') no-repeat -160px -224px;width:34px;height:32px;display:inline-block}.websoccericon-matchcompleted{background:url('../img/icons-websoccer-32.png') no-repeat -159px 										-30px;width:34px;height:32px;display:inline-block}.websoccericon-corner{background:url('../img/icons-websoccer-32.png') no-repeat -31px -160px;width:34px;height:32px;display:inline-block}
							.websoccericon-tacticschange{background:url('../img/icons-websoccer-32.png') no-repeat -95px -160px;width:34px;height:34px;display:inline-block}.yAxis .tickLabel{left:-20px!important}
							#stadium{background:url('../img/pitch_bg.png') no-repeat 110px 90px}#notificationspopup{width:350px}.shoutboxmessages{height:250px;overflow-y:scroll;padding:10px 5px;margin-bottom:5px;background-color:#f9f9f9;border:1px solid #dde2e4;
							-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.05);-moz-box-shadow:inset 0 1px 1px rgba(0,0,0,.05);box-shadow:inset 0 1px 1px rgba(0,0,0,.05)}@media all and
							(min-height:100px){.modal-body{max-height:50px}}@media all AND(min-height:300px){.modal-body{max-height:150px}}@media all AND(min-height:450px){.modal-body{max-height:300px}}@media all AND(min-height:700px){
							.modal-body{max-height:550px}}</style><?php }
function Editor_css(){?><style>
							/* -------------------------------------------------------------------
							// markItUp! Universal MarkUp Engine, JQuery plugin
							// By Jay Salvat - http://markitup.jaysalvat.com/
							// ------------------------------------------------------------------*/
							.markItUp*{margin:0px;padding:0px;outline:none;}.markItUp a:link,.markItUp a:visited{color:#000;text-decoration:none;}.markItUp{width:700px;margin:5px 0 5px 0;border:5px solid#F5F5F5;}.markItUpContainer{border:1px solid #3C769D;
							background:#FFF url(images/bg-container.png)repeat-x top left;padding:5px5px2px5px;font:11px Verdana,Arial,Helvetica,sans-serif;}.markItUpEditor{font:12px'Courier New',Courier,monospace;padding:5px5px5px35px;border:3px solid#3C769D;
							width:643px;height:320px;background:#FFF url(images/bg-editor.png)no-repeat;clear:both;line-height:18px;overflow:auto;}.markItUpPreviewFrame{overflow:auto;background-color:#FFFFFF;border:1px solid#3C769D;width:99.9%;height:300px;margin:5px 0;}
							.markItUpFooter{width:100%;cursor:n-resize;}.markItUpResizeHandle{overflow:hidden;width:22px;height:5px;margin-left:auto;margin-right:auto;background-image:url(images/handle.png);cursor:n-resize;}.markItUpHeader ul li{list-style:none;
							float:left;position:relative;}.markItUpHeader ul li ul{display:none;}.markItUpHeader ul li:hover>ul{display:block;}.markItUpHeader ul .markItUpDropMenu{background:transparent url(images/menu.png)no-repeat 115%50%;margin-right:5px;}
							.markItUpHeader ul.markItUpDropMenu li{margin-right:0px;}.markItUpHeader ul.markItUpSeparator{margin:0 10px;width:1px;height:16px;overflow:hidden;background-color:#CCC;}.markItUpHeader ul ul.markItUpSeparator{width:auto;height:1px;margin:0px;}
							.markItUpHeader ul ul{display:none;position:absolute;top:18px;left:0px;background:#F5F5F5;border:1px solid#3C769D;height:inherit;}.markItUpHeader ul ul li{float:none;border-bottom:1px solid#3C769D;}.markItUpHeader ul ul.markItUpDropMenu{
							background:#F5F5F5 url(images/submenu.png)no-repeat 100%50%;}.markItUpHeader ul ul ul{position:absolute;top:-1px;left:150px;}.markItUpHeader ul ul ul li{float:none;}.markItUpHeader ul a{display:block;width:16px;height:16px;
							text-indent:-10000px;background-repeat:no-repeat;padding:3px;margin:0px;}.markItUpHeader ul ul a{display:block;padding-left:0px;text-indent:0;width:120px;padding:5px5px5px25px;background-position:2px50%;}.markItUpHeader ul ul a:hover{
							color:#FFF;background-color:#3C769D;}.markItUp.markItUpButton1 a {background-image:url(data:image/png;filename=bold.png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwA
							AADCSURBVCjPY/jPgB8yUEtBeUL5+ZL/Be+z61PXJ7yPnB8sgGFCcX3m/6z9IFbE/JD/XucxFOTWp/5PBivwr/f77/gfQ0F6ffz/aKACXwG3+27/LeZjKEioj/wffN+n3vW8y3+z/Vh8EVEf/N8LLGEy3+K/2nl5ATQF/vW+/x3BCrQF1P7r/hcvQFPgVg+0GWq0zH/N/wL1aAps6x3+64M9J12g8p//PZcCigKbBJP1uvvV9sv
							3S/YL7+ft51SgelzghgBKWvx6E5D1XwAAAABJRU5ErkJggg==);}.markItUp.markItUpButton2 a{background-image:url(data:image/png;filename=italic.png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1h
							Z2VSZWFkeXHJZTwAAABxSURBVCjPY/jPgB8yUFtBdkPqh4T/kR+CD+A0Ie5B5P/ABJwmxBiE//f/gMeKkAlB/90W4FHg88Dzv20ATgVeBq7/bT7g8YXjBJf/RgvwKLB4YPFfKwCnAjMH0/8a/3EGlEmD7gG1A/IHJDfQOC4wIQALYP87Y6unEgAAAABJRU5ErkJggg==);}.markItUp
							.markItUpButton3 a{background-image:url(data:image/png;filename=stroke.png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAACfSURBVCjPY/jPgB8yUFNBiWDBzOy01PKEmZG7sSrI
							e5dVDqIjygP/Y1GQm5b2P7kDwvbAZkK6S8L/6P8hM32N/zPYu2C1InJ36P/A/x7/bc+YoSooLy3/D4Px/23+SyC5G8kEf0EIbZSmfdfov9wZDCvc0uzLYWyZ/2J3MRTYppn/14eaIvKOvxxDgUma7ju1M/LlkmnC5bwdNIoL7BAAWzr8P9A5d4gAAAAASUVORK5CYII=);}.markItUp
							.markItUpButton4 a{background-image:url(data:image/png;filename=list-bullet.png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAADqSURBVDjLY/j//z8DJZiBKgbkzH9cMHXX6wcg
							miwDQJq3nv/4H0SD+OXl5dlA/L+kpOR/QUHB/+zs7P+pqan/ExIS/kdGRv4PDg7+T10XDHwgpsx8VNC56eWDkJ675Hmhbf3zB0uPvP1fuvQpOBDj4uKyIyIi/gcGBv738vL67+zs/N/Gxua/iYnJf11d3f9qamqogRjQcaugZPHjB66V14ZqINrmXyqIn3bvgXXeJfK8ANLcv+3lfxAN4hsZGWVra2v/V1FR+S8nJ/dfXFz8v5C
							Q0H8eHp7/7Ozs/5mZmVEDEWQzRS6gBAMAYBDQP57x26IAAAAASUVORK5CYII=);}.markItUp.markItUpButton5 a{background-image:url(data:image/png;filename=list-numeric.png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdH
							dhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAD3SURBVDjLY/j//z8DJRhM5Mx/rLLo8Lv/ZBsA0kyRATBDYOzy8vJsIP5fUlLyv6Cg4H92dvb/1NTU/wkJCf8jIyP/BwcH/8fqgkUHSXcFA1UCce7+t/9n7Xn9P2LiPRWyXRDae0+ld8tL8rwQ1HVHpXPTc7jmuLi47IiIiP+BgYH/vby8/js7O/+3sbH5b2Ji8l9XV/e/mpoaa
							iC2rX/+v3HN0/81q54OUCCWL3v8v3Tp4//Fix+T7wKQZuu8S+THAkgzzAVGRkbZ2tra/1VUVP7Lycn9FxcX/y8kJPSfh4fnPzs7+39mZmbUQARpBGG7oisddA9EAPd/1bRtLxctAAAAAElFTkSuQmCC);}.markItUp.markItUpButton6 a{background-image:url(data:image/png;filename=picture.png;
							base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAHwSURBVDjLpZM9a1RBFIafM/fevfcmC7uQjWEjUZKAYBHEVEb/gIWFjVVSWEj6gI0/wt8gprPQykIsTP5BQLAIhBVBzRf52Gw22bk7c8YiZslugggZppu
							Z55z3nfdICIHrrBhg+ePaa1WZPyk0s+6KWwM1khiyhDcvns4uxQAaZOHJo4nRLMtEJPpnxY6Cd10+fNl4DpwBTqymaZrJ8uoBHfZoyTqTYzvkSRMXlP2jnG8bFYbCXWJGePlsEq8iPQmFA2MijEBhtpis7ZCWftC0LZx3xGnK1ESd741hqqUaqgMeAChgjGDDLqXkgMPTJtZ3KJzDhTZpmtK2OSO5IRB6xvQDRAhOsb5Lx1lOu
							5ZCHV4B6RLUExvh4s+ZntHhDJAxSqs9TCDBqsc6j0iJdqtMuTROFBkIcllCCGcSytFNfm1tU8k2GRo2pOI43h9ie6tOvTJFbORyDsJFQHKD8fw+P9dWqJZ/I96TdEa5Nb1AOavjVfti0dfB+t4iXhWvyh27y9zEbRRobG7z6fgVeqSoKvB5oIMQEODx7FLvIJo55KS9R7b5ldrDReajpC+Z5z7GAHJFXn1exedVbG36ijwOmJgl
							0kS7lXtjD0DkLyqc70uPnSuIIwk9QCmWd+9XGnOFDzP/M5xxBInhLYBcd5z/AAZv2pOvFcS/AAAAAElFTkSuQmCC);}.markItUp.markItUpButton7 a{background-image:url(data:image/png;filename=link.png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwW
							K6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAADpSURBVCjPY/jPgB8y0EmBHXdWaeu7ef9rHuaY50jU3J33v/VdVqkdN1SBEZtP18T/L/7f/X/wf+O96kM3f9z9f+T/xP8+XUZsYAWGfsUfrr6L2Ob9J/X/pP+V/1P/e/+J2LbiYfEHQz+ICV1N3yen+3PZf977/9z/Q//X/rf/7M81Ob3pu1EXWIFuZv
							r7aSVBOx1/uf0PBEK3/46/gnZOK0l/r5sJVqCp6Xu99/2qt+v+T/9f+L8CSK77v+pt73vf65qaYAVqzPYGXvdTvmR/z/4ZHhfunP0p+3vKF6/79gZqzPQLSYoUAABKPQ+kpVV/igAAAABJRU5ErkJggg==);}.markItUp.markItUpButton8 a{background-image:url(data:image/png;filename=clean.png;
							base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABh0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzT7MfTgAAAhlJREFUOI2lk0toE1EYhc9kZmoeauyAMrZ5NFNsOo1tKm7MooIgKnTlqlkVF0LAlaWLuBG7kGJAV4qu7EYQtSvREkwV
							xKRWwVhLqKR5OK04Tu0jjQnNY5zJuBptbVqK/Zbnv+e73B8uoWkadoNhV+2tBMdufrX+t6A7NG9lGSrsf7KQ17P+Z8v53gcLb7tD85vEGwTeYYFhGTrMsw2+crGW0/NSuZbzsLSPZeiw52qWqSvoui74jx6xiHwT7ct8k4Vkotijz1KptZ6UJAt8E+3rbLeI7suzfn1GaJqGzqEvDR1u8xLXSO8XVmQlPpk/kb7Dx9ffxAfTx71
							de9/Z95FURpILU+9/Hpwb8cgGAEgMcfKnD4VARqpWGNpAtXLmUWd/olkvuwdTzZzDOMrQBiorVSsfJ1YDcyMeGQAo/dDsrbZHroszEZvN+Ly91eRT2yxRABwAXDA8THijjxvpxSwUEisB1FoA5e8T1mPzT1vtTlOYYeiOsaDrQKzPEbTYHTf4U+exh/OgPB3B5+i4mksmB868+nV7k+BfXpymsicHQpwp+xoQJwCrFcuUE/FITD
							j7UuGobdsAyBrhMh52Ab2DfzLqGguyRrRs2MFWqKQmlqbGbJanl1At/0AJQLFAQiXxHdjBXzAzh+7PTL5RpIoJBZLGao5AWiRUDbgL1FliPWJ99itrOSlAqoRTJTVRA+6dG1eGdyzYjt/h2M+sdF20TgAAAABJRU5ErkJggg==);}.markItUp.preview a{background-image:url(data:image/png;
							filename=preview.png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAGrSURBVDjLvZPZLkNhFIV75zjvYm7VGFNCqoZUJ+roKUUpjRuqp61Wq0NKDMelGGqOxBSUIBKXWtWGZxAvobr8lWjChRgSF//
							dv9be+9trCwAI/vIE/26gXmviW5bqnb8yUK028qZjPfoPWEj4Ku5HBspgAz941IXZeze8N1bottSo8BTZviVWrEh546EO03EXpuJOdG63otJbjBKHkEp/Ml6yNYYzpuezWL4s5VMtT8acCMQcb5XL3eJE8VgBlR7BeMGW9Z4yT9y1CeyucuhdTGDxfftaBO7G4L+zg91UocxVmCiy51NpiP3n2treUPujL8xhOjYOzZYsQWANyR
							YlU4Y9Br6oHd5bDh0bCpSOixJiWx71YY09J5pM/WEbzFcDmHvwwBu2wnikg+lEj4mwBe5bC5h1OUqcwpdC60dxegRmR06TyjCF9G9z+qM2uCJmuMJmaNZaUrCSIi6X+jJIBBYtW5Cge7cd7sgoHDfDaAvKQGAlRZYc6ltJlMxX03UzlaRlBdQrzSCwksLRbOpHUSb7pcsnxCCwngvM2Rm/ugUCi84fycr4l2t8Bb6iqTxSCgNIA
							AAAAElFTkSuQmCC);}.markItUp.image a{background-image:url(data:image/png;filename=image.png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAGWSURBVBgZpcE/a1NhGMbh3/OeN
							56cKq2Dp6AoCOKmk4uCn8DNycEOIojilr2TaBfRzVnESQR3Bz+FFDoWA2IjtkRqmpyc97k9qYl/IQV7XSaJw4g0VlZfP0m13dwepPbuiH85fyhyWCx4/ubxjU6kkdxWHt69VC6XpZlFBAhwJgwJJHAmRKorbj94ewvoRBrbuykvT5R2/+lLTp05Tp45STmEJYJBMAjByILxYeM9jzr3GCczGpHGYAQhRM6fO8uFy1fJQoaUwCKY
							EcwwC4QQaGUBd36KTDmQ523axTGQmEcIEBORKQfG1ZDxcA/MkBxXwj1ggCQyS9TVAMmZiUxJ8Ln/kS+9PmOvcSW+jrao0mmMH5bzHfa+9UGBmciUBJ+2Fmh1h+yTQCXSkJkdCrpd8btIwwEJQnaEkOXMk7XaiF8CUxL/JdKQOwb0Ntc5SG9zHXQNd/ZFGsaEeLa2ChjzXQcqZiKNxSL0vR4unVwwMENMCATib0ZdV+QtE41I42g
							eXt1Ze3dlMNZFdw6Ut6CIvKBhkjiM79Pyq1YUmtkKAAAAAElFTkSuQmCC);}</style><?php }
class DefaultBootstrapSkin{protected$_websoccer;
				function __construct($websoccer){$this->_websoccer=$websoccer;}
				function getTemplatesSubDirectory(){return 'default';}
				function getCssSources(){Bootstrap_css();Skin_css();Websoccer_css();Responsive_css();Formation_css();Slider_css();$dir=Config('context_root').'/css/';return$files;}
				function getJavaScriptSources(){$dir=Config('context_root').'/js/';$files[]='//code.jquery.com/jquery-1.11.1.min.js';$files[]=$dir.'websoccer.min.js';return$files;}
				function getTemplate($templateName){return$templateName.'.twig';}
				function getImage($fileName){if(file_exists($_SERVER['DOCUMENT_ROOT'].'/img/'.$fileName))return Config('context_root').'/img/'.$fileName;return FALSE;}
				function __toString(){ return 'DefaultBootstrapSkin';}}
function owsProFunctions(){$num_cols=4;$ar=get_defined_functions();$int_funct=$ar['user'];sort($int_funct);$count=count($int_funct);?><p><table align='center'border='1'></p>owsPro Funktionen: <?php print$count;for($i=0;$i<$count;++$i){$doc=$_SERVER['DOCUMENT_ROOT'].
							'manual/de/function/'.strtr($int_funct[$i],'_','-').'.php';print'<td><a href=\''.$doc.'\'target=\"phpwin\'>'.$int_funct[$i].'</a></td>';if(($i>1)&&(($i+$num_cols)%$num_cols==($num_cols-1)))print'</tr><tr>';}for($i=($num_cols-(
							$count%$num_cols));$i>0;--$i)print'<td>&nbsp;</td>';?></table><?php }
