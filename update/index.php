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
					include($_SERVER['DOCUMENT_ROOT'].'/classes/WebSoccer.class.php');ini_set('session.cookie_httponly',1);ini_set('session.use_only_cookies',1);ini_set('session.cookie_secure',1);session_start();$supportedLanguages=['de'=>'deutsch','en'=>'english',
					'es'=>'español','pt'=>'português','dk'=>'dansk','ee'=>'eesti','fi'=>'suomalainen','fr'=>'français','id'=>'indonesia','it'=>'italiano','lv'=>'latvieši','lt'=>'lietuviškas','nl'=>'nederlands','pl'=>'polska','br'=>'brasil','ro'=>'rumenic',
					'se'=>'svenskt','sk'=>'slovenské','si'=>'slovenski','cz'=>'Česky','tr'=>'TÜRKÇE','hu'=>'magyar','jp'=>'ジャパニーズ'];?><!DOCTYPE html><html lang='de'><head><?php Bootstrap_css();?><title>Open WebSoccer-Sim Update Installation</title>
    				<link href="../admin/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen"><link rel="shortcut icon" type="image/x-icon" href="../favicon.ico"/><meta charset="UTF-8"><style type="text/css">body{padding-top:100px;padding-bottom:40px;}
    				</style></head><body><div class="container"><h1>Open WebSoccer-Sim Update Installation</h1><hr><?php$errors=[];$messagesIncluded=false;if(isset($_SESSION["lang"])){include'messages_'.$_SESSION['lang'].'.inc.php';$messagesIncluded=$_SESSION['lang'];}
      				$action=isset($_REQUEST['action'])?$_REQUEST['action']:'';if(!strlen($action)||substr($action,0,6)!== 'action')$view='UpdateScreen';
      else $view = $action();
      if (isset($_SESSION["lang"]) && $_SESSION["lang"] !== $messagesIncluded)include "messages_" . $_SESSION["lang"] . ".inc.php";
      if (count($errors))foreach ($errors as $error)echo "<div class=\'alert alert-error\'>$error</div>";
      if (isset($messages))$view($messages);
      else$view();
      ?><hr><footer>
						<p>Powered by <a href='https://github.com/owsPro/owsPro' target='_blank'>owsPro</a><br>Forked from <a href='https://github.com/ihofmann/open-websoccer' target='_blank'>ihofmann</a></p></footer></div>
						<script src='http://code.jquery.com/jquery-latest.js'></script><script src='../admin/bootstrap/js/bootstrap.min.js'></script></body></html>






						
					


