<?php
/*
This file is part of "owsPro" (Rolf Joseph) https://github.com/owsPro/owsPro/
A further development for PHP versions 8.1 to 8.3 from: OpenWebSoccer-Sim (Ingo Hofmann), https://github.com/ihofmann/open-websoccer.
"owsPro" is distributed WITHOUT ANY WARRANTY, including the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
See GNU Lesser General Public Licence Version 3 http://www.gnu.org/licenses/ */

// If checks the PHP version is at least 8.1.0. If the version is lower, it displays the message "Minimum PHP 8.1.0!" and exits the script.
if (\version_compare(\PHP_VERSION, '7.1.0') <= 0) {
    echo 'Minimum PHP 8.1.0!';
    \exit;
}

// It includes the "frontbase.inc.php" file located in the document root directory. This file is required for the script to run correctly.
include($_SERVER['DOCUMENT_ROOT'] . '/frontbase.inc.php');

// It retrieve the values of the "offline" and "offline_times" configurations using the \C() function.
// The value of the "offline" configuration is compared to the string "offline" and stored in the $isOffline variable.
// The value of the "offline_times" configuration is stored in the $offlineTimeSpansConfig variable.
$isOffline = (\C('offline') == 'offline');
$offlineTimeSpansConfig = \C('offline_times');

// It handles the offline mode functionality. If the $offlineTimeSpansConfig variable is not empty, it means that there are offline time spans configured.
// The code splits the $offlineTimeSpansConfig string into an array of time spans using the comma as the delimiter.
// It then iterates over each time span and checks if the current time falls within the specified range.
// If it does, the $isOffline variable is set to true, indicating that the website is in offline mode.
// If any configuration errors occur or an exception is thrown, an error message is displayed, and the script exits.
if (!empty($offlineTimeSpansConfig)) {
    $timeSpans = \explode(',', $offlineTimeSpansConfig);
    $now = new \DateTime();

    try {
        foreach ($timeSpans as $timeSpan) {
            [$fromTime, $toTime] = \explode('-', \trim($timeSpan));

            if (\count(\explode('-', \trim($timeSpan))) != 2) {
                throw new \Exception('Configuration error: Recurring offline mode time span must have a format like 15:00-17:00.');
            }

            $fromDateTime = \DateTime::createFromFormat('H:i', \trim($fromTime));
            $toDateTime = \DateTime::createFromFormat('H:i', \trim($toTime));

            if ($fromDateTime === \false || $toDateTime === \false) {
                throw new Exception('Configuration error: Invalid time format in offline mode time span.');
            }

            if ($fromDateTime <= $now && $now <= $toDateTime) {
                $isOffline = \true;
                \break;
            }
        }
    } catch (\Exception $e) {
        echo 'Error: ' . $e->getMessage();
        exit;
    }
}

// If the website is in offline mode ($isOffline is true), it sets the offline_message parameter to the formatted offline text and renders the "views/offline" template using the template engine.
// Otherwise, it proceeds with the normal website flow.
// It checks if the user is a registered user and if the badge check has not been performed yet.
// If so, it retrieves the user's registration date and calculates the number of registered days.
// If applicable, it awards a badge to the user using the BadgesDataService::awardBadgeIfApplicable() method.
// Then it handles the page navigation and actions.
// It retrieves the current page ID from the query parameters and sets it using the setPageId() function.
// It also initializes the $validationMessages variable and retrieves the action ID from the request parameters.
// If an action ID is present, the code attempts to handle the action using the ActionHandler::handleAction() method.
// If a target ID is returned, it updates the page ID accordingly.
// The code continues by retrieving the navigation items and breadcrumb items using the NavigationBuilder::getNavigationItems()
// and BreadcrumbBuilder::getBreadcrumbItems() methods, respectively. These items are stored in the $parameters array.
// The script then sets the content type header to "text/html;charset=utf-8" and creates a new instance of the ViewHandler class.
// It attempts to handle the page using the handlePage() method of the ViewHandler class, passing the page ID and parameters.
// The resulting output is printed using print_r().
// If an ExceptionAccessDenied exception is caught, it checks if the user is a guest.
// If so, it adds an error message to the front messages and renders the "login" page.
// Otherwise, it calls the renderErrorPage() function to display an error page.
// If any other exception is caught, it also calls the renderErrorPage() function to display an error page.

if ($isOffline) {
    $parameters['offline_message'] = \nl2br(\C('offline_text'));
    echo $website->getTemplateEngine($i18n)->loadTemplate('views/offline')->render($parameters);
} else {
    $user = $user ?? $website->getUser();
    if (!isset($_SESSION['badgechecked']) && $user?->getRole() == 'user' && $user?->getClubId($website, $db)) {
        $userId = $website->getUser()->id;
        $result = $db->querySelect(
            'datum_anmeldung',
            \C('db_prefix') . '_user',
            'id=%d',
            $userId
        );
        $userinfo = $result->fetch_array();

        if ($userinfo['datum_anmeldung']) {
            $numberOfRegisteredDays = \round((time() - $userinfo['datum_anmeldung']) / 86400);
            \BadgesDataService::awardBadgeIfApplicable(
                $website,
                $db,
                $userId,
                'membership_since_x_days',
                $numberOfRegisteredDays
            );
        }

        $_SESSION['badgechecked'] = 1;
    }

    $pageId = $_GET['page'] ?? \null;
    \setPageId($pageId);

    $validationMessages = \null;
    $actionId = \Request('action');

    if ($actionId !== \null) {
        try {
            $targetId = \ActionHandler::handleAction($website, $db, $i18n, $actionId);

            if ($targetId != \null) {
                $pageId = $targetId;
            }
        } catch (\ValidationException $ve) {
            $validationMessages = $ve->getMessages();
            $website->addFrontMessage(new FrontMessage('error', \Message('validation_error_box_title'), \Message('validation_error_box_message')));
        } catch (\Exception $e) {
            $website->addFrontMessage(new FrontMessage(
                'error',
                \Message('errorpage_title'),
                $e->getMessage()
            ));
        }
    }

    \setPageId($pageId);
    $navItems = \NavigationBuilder::getNavigationItems($website, $i18n, $page, $pageId);
    $parameters['navItems'] = $navItems;
    $parameters['breadcrumbItems'] = \BreadcrumbBuilder::getBreadcrumbItems(
        $website,
        $i18n,
        $page,
        $pageId
    );

    \header('Content-type:text/html;charset=utf-8');
    $viewHandler = new \ViewHandler($website, $db, $i18n, $page, $block, $validationMessages);

    try {
        \print_r($viewHandler->handlePage($pageId, $parameters));
    } catch (\ExceptionAccessDenied $e) {
        if ($website->getUser()->getRole() == 'guest') {
            $website->addFrontMessage(new FrontMessage('error', $e->getMessage(), ''));
            \print_r($viewHandler->handlePage('login', $parameters));
        } else {
            renderErrorPage(
                $website,
                $i18n,
                $viewHandler,
                $e->getMessage(),
                $parameters
            );
        }
    } catch (\Exception $e) {
        \renderErrorPage($website, $i18n, $viewHandler, $e->getMessage(), $parameters);
    }
}