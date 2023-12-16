<?php
/* This file is part of "owsPro" (Rolf Joseph) https://github.com/owsPro/owsPro/ A further development for PHP versions 8.1 to 8.3 from: OpenWebSoccer-Sim (Ingo Hofmann), https://github.com/ihofmann/open-websoccer.
   "owsPro" is distributed WITHOUT ANY WARRANTY, including the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public Licence version 3 http://www.gnu.org/licenses/ */
\error_reporting(E_ALL);																												// Set error reporting to all errors
\ini_set('display_errors',1);																											// Set explicit error display
require($_SERVER['DOCUMENT_ROOT'].'/frontbase.inc.php');																				// Include the frontbase.inc.php file
$output['messages']='';																													// Initialize the output variable
$output['content']='';																													// Initialize the output variable
\header('Content-type:application/json;charset=utf-8');																					// Set the content type to JSON
if(\C('offline')!=='offline'){																											// Check if the application is not in offline mode
    $validationMessages=\null;																											// Initializing the variable to store validation messages
    $actionId=$_GET['action']??null;																									// Getting the value of the 'action' parameter from the request
    if($actionId!==\null){																												// Check if the website is not offline
        try{
            \ActionHandler::handleAction($website,$db,$i18n,$actionId);}																// Handle the action using ActionHandler
        catch(\ValidationException$ve){
            $validationMessages=$ve->getMessages();																						// If a ValidationException is thrown, get the validation messages
            $website->addFrontMessage(new \FrontMessage('error',\M('validation_error_box_title'),\M('validation_error_box_message')));} // Add a front message for the validation error
        catch(\Exception$e){
            $website->addFrontMessage(new FrontMessage('error',\M('errorpage_title'),$e->getMessage()));								// If any other exception is thrown, add a front message with the error details
            $output['messages']=$e->getMessage();}}																						// Set the error message in the output array
    $viewHandler=new \ViewHandler($website,$db,$i18n,$page,$block,$validationMessages);													// Create a new ViewHandler instance
    try{
        $blockId=\R('block');																											// Get the value of the 'block' parameter from the request
        $pageId=\R('page');																												// Get the value of the 'page' parameter from the request
        foreach([$blockId,$pageId]as$id){
            if(\strlen($id)&&isset($block[$id])){																						// Check if the ID is not empty and if the block with that ID exists
                $output['content']=$viewHandler->renderBlock($id,\json_decode($block[$id],true),$parameters);							// Render the block using the ViewHandler and pass the decoded JSON data and parameters
                break;}																													// Exit the loop
            elseif($id!=null){																											// If the ID is not null, set the page ID and handle the page using the ViewHandler
                \setPageId($id);																										// Set the page ID to the provided value
                $output['content']=$viewHandler->handlePage($id,$parameters);															// Retrieve and handle the page content
                break;}}}																												// Exit the loop
    catch(\Exception$e){
        $website->addFrontMessage(new \FrontMessage('error',\M('errorpage_title'),$e->getMessage()));									// Add error message to the front of the website
        $output['messages']=$e->getMessage();}}																							// Set the error message in the output array
if(\R('contentonly'))echo$output['content'];																							// Check if the 'contentonly' parameter is present in the request
else{
    $output['messages']=$viewHandler->renderBlock('messagesblock',\json_decode($block['messagesblock'],true));							// If 'contentonly' parameter is not present, render the 'messagesblock' and encode the output
    echo \json_encode($output, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);}															// JSON_THROW_ON_ERROR throws an exception if an error occurs during JSON encoding, and JSON_UNESCAPED_UNICODE ensures that Unicode characters are not escaped.