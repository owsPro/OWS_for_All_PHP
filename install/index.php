<?php
/*This file is part of 'OWS for All PHP' (Rolf Joseph)
  https://github.com/owsPro/OWS_for_All_PHP/
  A spinn-off for PHP Versions 5.5 to 8.3 from:
  OpenWebSoccer-Sim(Ingo Hofmann), https://github.com/ihofmann/open-websoccer.

  'OWS for All PHP' is is distributed in WITHOUT ANY WARRANTY;
  without even the implied warranty of MERCHANTABILITY
  or FITNESS FOR A PARTICULAR PURPOSE.

  See GNU Lesser General Public License Version 3 http://www.gnu.org/licenses/

	Refactored

- The code starts with the opening PHP tag <?php and includes the necessary file owsPro.php using the include statement.
- The ini_set functions are used to set the session cookie options for security purposes.
- The session_start function is called to start the session.
- The $supportedLanguages array is defined, which contains the supported languages and their corresponding names.
- The HTML code starts with the <!DOCTYPE html> declaration and the opening html tag.
- The head section contains the necessary meta tags, CSS styles, and the title of the page.
- The body section starts with a container div and displays the heading "owsPro Installation" followed by a horizontal line.
- The code checks if the $_SESSION['lang'] variable is set and includes the corresponding language messages file.
- The $action variable is checked to determine the appropriate view to display. If the action is not set or does not start with "action", the printWelcomeScreen view is displayed; otherwise, the action view is called.
- If the language has changed, the corresponding language messages file is included again.
- If there are any errors, they are displayed as alert messages using a foreach loop.
- The appropriate view is displayed, either using the $messages variable or by calling the $view function.
- The code ends with the closing div tag, a horizontal line, and a footer displaying the powered by and forked from information.
- The necessary JavaScript files are included at the end of the code.

*****************************************************************************/
include($_SERVER['DOCUMENT_ROOT'].'/owsPro.php');
ini_set('session.cookie_httponly',1);
ini_set('session.use_only_cookies',1);
ini_set('session.cookie_secure',1);
session_start();
$supportedLanguages=['de'=>'deutsch','en'=>'english','es'=>'español','pt'=>'português','dk'=>'dansk','ee'=>'eesti','fi'=>'suomalainen','fr'=>'français','id'=>'indonesia','it'=>'italiano','lv'=>'latvieši','lt'=>'lietuviškas','nl'=>'nederlands','pl'=>'polska','br'=>'brasil','ro'=>'rumenic',
	'se'=>'svenskt','sk'=>'slovenské','si'=>'slovenski','cz'=>'Česky','tr'=>'TÜRKÇE','hu'=>'magyar','jp'=>'ジャパニーズ'];
if(isset($_POST['lang']) && array_key_exists($_POST['lang'], $supportedLanguages))$_SESSION['lang'] = $_POST['lang'];
else$_SESSION['lang'] = 'en'; // Set a default language or display an error message // Handle the error when an unsupported language is selected
?><!DOCTYPE html>
<html lang='de'>
<head>
    <?php Bootstrap_css(); ?>
    <title>owsPro - Installation</title>
    <link rel='shortcut icon'type='image/x-icon'href='../favicon.ico'/>
    <meta charset='UTF-8'>
    <style type='text/css'>body{padding-top:100px;padding-bottom:40px;}</style>
</head>
<body>
    <div class='container'>
        <h1>owsPro Installation</h1>
        <hr>
        <?php
        $errors=[];
        $messagesIncluded=false;
        if(isset($_SESSION['lang'])){
            include('messages_'.$_SESSION['lang'].'.inc.php');
            $messagesIncluded=$_SESSION['lang'];}
        $action=isset($_REQUEST['action'])?$_REQUEST['action']:'';
        if(!strlen($action)||substr($action,0,6)!=='action')$view='printWelcomeScreen';
        else$view=$action();
        if(isset($_SESSION['lang'])&&$_SESSION['lang']!==$messagesIncluded)include('messages_'.$_SESSION['lang'].'.inc.php');
        if(count($errors))foreach($errors as$error)echo'<div class=\'alert alert-error\'>'.$error.'</div>';
        if(isset($messages))$view($messages);
        else$view();?>
        <hr>
        <footer><p>Powered by <a href='https://github.com/owsPro/owsPro' target='_blank'>owsPro</a><br>Forked from <a href='https://github.com/ihofmann/open-websoccer' target='_blank'>ihofmann</a></p></footer>
    </div>
    <script src='http://code.jquery.com/jquery-latest.js'></script>
    <script src='../admin/bootstrap/js/bootstrap.min.js'></script>
</body>
</html>