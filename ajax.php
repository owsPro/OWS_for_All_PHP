<?php
/*
This file is part of "owsPro" (Rolf Joseph) https://github.com/owsPro/owsPro/
A further development for PHP versions 8.1 to 8.3 from: OpenWebSoccer-Sim (Ingo Hofmann), https://github.com/ihofmann/open-websoccer.
"owsPro" is distributed WITHOUT ANY WARRANTY, including the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
See GNU Lesser General Public Licence Version 3 http://www.gnu.org/licenses/ */

/// The provided code is written in PHP 8.1 and performs various tasks related to handling actions, rendering blocks and pages, and generating JSON responses.

// This sets the error reporting level to show all errors and includes the required frontbase.inc.php file.
// It also initialises the $output array with empty values for the message and content keys.
// It also sets the content type of the response to JSON.
\error_reporting(E_ALL);
\ini_set('display_errors', 1);

require($_SERVER['DOCUMENT_ROOT'].'/frontbase.inc.php');

$output['messages'] = '';
$output['content'] = '';
\header('Content-type:application/json;charset=utf-8');

// The code checks whether the website is offline by comparing the value of the offline configuration setting with the string 'offline'.
// If the website is not offline, it proceeds to handle the requested action. The action ID is retrieved from the request using the Request() function.
// Inside the action handling block is a try-catch statement. It attempts to handle the action using the ActionHandler::handleAction() method, passing the necessary parameters.
// If a ValidationException is thrown, the validation messages are retrieved and stored in the $validationMessages variable.
// In addition, an error message is added to the front end messages using the addFrontMessage() method of the $website object.
// If any other exception is caught, an error message is added to the front-end messages and the error message is assigned to the $output['messages'] key.

if (\C('offline') !== 'offline') {
    $validationMessages = \null;
    $actionId = \Request('action');

    if ($actionId !== \null) {
        try {
            \ActionHandler::handleAction($website, $db, $i18n, $actionId);
        } catch (\ValidationException $ve) {
            $validationMessages = $ve->getMessages();
            $website->addFrontMessage(new FrontMessage(
                'error',
                \Message('validation_error_box_title'),
                \Message('validation_error_box_message')
            ));
        } catch (\Exception $e) {
            $website->addFrontMessage(new FrontMessage('error', Message('errorpage_title'), $e->getMessage()));
            $output['messages'] = $e->getMessage();
        }
    }

    // This section creates a new instance of the ViewHandler class and passes it the necessary parameters.
    // A try-catch block is then used to handle any exceptions that may occur during the rendering process.
    // The code retrieves the values of the block and page parameters from the request using the Request() function.
    // It then iterates over an array containing these values to determine whether to render a block or a page.
    // If a non-empty block ID is found and the corresponding block exists in the $block array, the renderBlock() method of the $viewHandler object is called to render the block.
    // The block data is retrieved from the $block array using the ID as a key. The rendered content is assigned to the $output['content'] key, and the loop is exited using the break statement.
    // If a non-null page ID is found, the setPageId() function is called to set the page ID.
    // Then the handlePage() method of the $viewHandler object is called to handle the page.
    // The rendered content is assigned to the $output['content'] key, and the loop is exited using the break statement.
    // If an exception is thrown during the rendering process, an error message is added to the frontend messages, and the error message is assigned to the $output['messages'] key.

    $viewHandler = new \ViewHandler($website, $db, $i18n, $page, $block, $validationMessages);

    try {
        $blockId = \Request('block');
        $pageId = \Request('page');

        foreach ([$blockId, $pageId] as $id) {
            if (\strlen($id) && isset($block[$id])) {
                $output['content'] = $viewHandler->renderBlock($id, \json_decode($block[$id], true), $parameters);
                break;
            } elseif ($id != null) {
                \setPageId($id);
                $output['content'] = $viewHandler->handlePage($id, $parameters);
                break;
            }
        }
    } catch (\Exception $e) {
        $website->addFrontMessage(new \FrontMessage('error', Message('errorpage_title'), $e->getMessage()));
        $output['messages'] = $e->getMessage();
    }
}

// Finally, the code checks if the contentonly parameter is present in the request.
// If so, only the content is echoed to the output.
// Otherwise, the front-end messages are rendered using the renderBlock() method of the $viewHandler object, and the entire $output array is encoded as JSON and echoed to the output.

if (\Request('contentonly')) {
    echo $output['content'];
} else {
    $output['messages'] = $viewHandler->renderBlock('messagesblock', \json_decode($block['messagesblock'], true));
    echo \json_encode($output);
}