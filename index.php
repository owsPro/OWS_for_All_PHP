<?php
/* This file is part of "owsPro" (Rolf Joseph) https://github.com/owsPro/owsPro/ A further development for PHP versions 8.1 to 8.3 from: OpenWebSoccer-Sim (Ingo Hofmann), https://github.com/ihofmann/open-websoccer.
   "owsPro" is distributed WITHOUT ANY WARRANTY, including the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public Licence version 3 http://www.gnu.org/licenses/ */
if(\PHP_VERSION_ID<80100){																												// Check if the PHP version is less than 8.1.0
    echo'Minimum PHP 8.1.0!';																											// If the version is lower, it displays the message "Minimum PHP 8.1.0!"
    \exit;}																																// and exits the script.
include($_SERVER['DOCUMENT_ROOT'].'/frontbase.inc.php');																				// It includes the "frontbase.inc.php" file located in the document root directory. This file is required for the script to run correctly.
$isOffline=(\C('offline')=='offline');																									// The configuration entries "offline" and "offline_times" are retrieved with the function \C() and compared with the string "offline" and stored in the variable $isOffline.
$offlineTimeSpansConfig=\C('offline_times');																							// The value of the "offline_times" configuration is stored in the $offlineTimeSpansConfig variable.
if(!empty($offlineTimeSpansConfig)){																									// Check if there are offline time spans configured
    $timeSpans=\explode(',',$offlineTimeSpansConfig);																					// Split the offline time spans into an array
    $now=new \DateTime();																												// Get the current date and time
    try{
        foreach($timeSpans as$timeSpan) {																								// Iterate through each offline time span
            [$fromTime,$toTime]=\explode('-',\trim($timeSpan));																			// Split the time span into start and end times
            if(\count(\explode('-',\trim($timeSpan)))!=2){																				// Check if the time span has the correct format
                throw new \Exception('Configuration error: The time period must have the following format 15:00-17:00.');}				// Throw an exception if the format is incorrect
            $fromDateTime=\DateTime::createFromFormat('H:i',\trim($fromTime));															// Create a DateTime object for the start time
            $toDateTime=\DateTime::createFromFormat('H:i',\trim($toTime));																// Create a DateTime object for the end time
            if($fromDateTime===\false||$toDateTime===\false){																			// Check if the time format is invalid
                throw new \Exception('Configuration error: Invalid time format in offline mode time span.');}							// Throw an exception if the format is invalid
            if($fromDateTime<=$now&&$now<=$toDateTime){																					// Check if the current time is within the offline time span
                $isOffline=\true;																										// Set the offline flag to true
                \break;}}}																												// Exit the loop
    catch(\Exception$e){																												// Catch any exceptions that occur during the process
        echo'Error: '.$e->getMessage();																									// Display the error messag
        \exit;}}																														// Exit the script
if($isOffline){
    $parameters['offline_message']=\nl2br(\C('offline_text'));																			// If the website is offline, 
    echo $website->getTemplateEngine($i18n)->loadTemplate('views/offline')->render($parameters);}										// display the offline messag
else{
    $user=$user??$website->getUser();																									// Assigns the value of $user. If $user is null, it retrieves the user object from the $website object using the getUser() method
    if(!isset($_SESSION['badgechecked'])&&$user?->getRole()=='user'&&$user?->getClubId($website,$db)){ 									// Check if the user's badge has not been checked yet, the user is a regular user, and the user has a club ID
        $userId=$website->getUser()->id; 																								// This line retrieves the user ID from the user object obtained from the $website object
        $result=$db->querySelect('datum_anmeldung',\C('db_prefix').'_user','id=%d',$userId );											// Retrieve the registration date of the user from the database
        $userinfo=$result->fetch_array();																								// This line fetches the row from the $result object and stores it in the $userinfo variable as an associative array.
        if($userinfo['datum_anmeldung']) {																								// If the user has a registration date
            $numberOfRegisteredDays=\round((time()-$userinfo['datum_anmeldung'])/86400);												// Calculate the number of days since registration
            \BadgesDataService::awardBadgeIfApplicable($website,$db,$userId,'membership_since_x_days',$numberOfRegisteredDays);} 		// Award the "membership_since_x_days" badge if applicable
        $_SESSION['badgechecked']=1;}																									// Set the badgechecked session variable to indicate that the badge has been checked
    $pageId=$_GET['page']??'home';																										// The value of the "page" parameter is assigned to the $pageId variable. If the "page" parameter is not set, "home" is assigned to the "$pageId" variable as the default page.
    \setPageId($pageId);																												// Call the setPageId function and pass the $pageId variable as an argument.
    $validationMessages=\null;																											// Initialize the $validationMessages variable with null.
    $actionId=\Request('action');																										// Get the value of the 'action' parameter from the request and assign it to the $actionId variable.
    if($actionId!==\null){																												// Check if the $actionId variable is not null.
        try{$targetId=\ActionHandler::handleAction($website,$db,$i18n,$actionId);														// Call the handleAction and assign the return value to the $targetId variable.
            if($targetId!=\null){																										// Check if the $targetId variable is not null.
                $pageId=$targetId;}}																									// Assign the value of $targetId to the $pageId variable.
        catch(\ValidationException$ve){																									// Catch a ValidationException if it is thrown.
            $validationMessages=$ve->getMessages();																						// Assign the return value to the $validationMessages variable.
            $website->addFrontMessage(new FrontMessage('error',\M('validation_error_box_title'),\M('validation_error_box_message')));}	// Add the FrontMessage with the 'error' type to the $website.
        catch(\Exception $e){$website->addFrontMessage(new \FrontMessage('error',\M('errorpage_title'),$e->getMessage()));}}			// Add the FrontMessage with the 'error' type to the $website.
    \setPageId($pageId);																												// Set the page ID
    $navItems = \NavigationBuilder::getNavigationItems($website,$i18n,$page,$pageId);													// Get the navigation items
    $parameters['navItems']=$navItems;																									// Assign the navigation items to the parameters array
    $parameters['breadcrumbItems']=\BreadcrumbBuilder::getBreadcrumbItems($website,$i18n,$page,$pageId);								// Get the breadcrumb items
    \header('Content-type:text/html;charset=utf-8');																					// Set the content type header
    $viewHandler=new \ViewHandler($website,$db,$i18n,$page,$block,$validationMessages);													// Create a new ViewHandler instance
    try{\print_r($viewHandler->handlePage($pageId,$parameters));}																		// Handle the page using the view handler and print the result
    catch(\ExceptionAccessDenied$e){
        if($website->getUser()->getRole()=='guest'){
            $website->addFrontMessage(new \FrontMessage('error',$e->getMessage(),''));													// Add an error message to the front of the website
            \print_r($viewHandler->handlePage('login',$parameters));}																	// and handle the login page
        else{\renderErrorPage($website,$i18n,$viewHandler,$e->getMessage(),$parameters);}}												// Render an error page with the website, i18n, view handler, error message, and parameters
    catch(\Exception$e){\renderErrorPage($website,$i18n,$viewHandler,$e->getMessage(),$parameters);}}									// Render an error page with the website, i18n, view handler, error message, and parameters