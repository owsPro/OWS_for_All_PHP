<?php
/******************************************************************
*
* This file is part of OpenWebSoccer-Sim.
*
* OpenWebSoccer-Sim is free software: you can redistribute it
* and/or modify it under the terms of the
* GNU Lesser General Public License
* as published by the Free Software Foundation,either version 3 of
* the License,or any later version.
*
* OpenWebSoccer-Sim is distributed in the hope that it will be
* useful,but WITHOUT ANY WARRANTY; without even the implied
* warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU Lesser General Public License for more details.
*
* You should have received a copy of the GNU Lesser General Public
* License along with OpenWebSoccer-Sim.
* If not,see <http://www.gnu.org/licenses/>.
*
* Author: Ingo Hofmann
* Base Version: OpenWebSoccer-Sim 5.2.4-Snapshot vom 21. Juni 2015
*
* This Version called "OpenWebsoccer Professional"
* This is a general rewritten Version with many features too.
* by Rolf Joseph / ErdemCan 2015 - 2018
*
* For comparison of the code look at the original at
* https://github.com/ihofmann/open-websoccer
******************************************************************/
include($_SERVER['DOCUMENT_ROOT'].'/classes/WebSoccer.class.php');define('BASE_FOLDER',__DIR__ .'/..');define('PHP_MIN_VERSION','5.3.0');define('CONFIGFILE',BASE_FOLDER.'/generated/config.inc.php');session_start();
$supportedLanguages=["de"=>"deutsch","en"=>"english","es"=>"español","pt"=>"português","dk"=>"dansk","ee"=>"eesti","fi"=>"suomalainen","fr"=>"français","id"=>"indonesia","it"=>"italiano","lv"=>"latvieši","lt"=>"lietuviškas","nl"=>"nederlands","pl"=>"polska",
	"br"=>"brasil","ro"=>"rumenic","se"=>"svenskt","sk"=>"slovenské","si"=>"slovenski","cz"=>"Česky","tr"=>"TÜRKÇE","hu"=>"magyar","jp"=>"ジャパニーズ"];$setAdmin=1;?>
<!DOCTYPE html>
<html lang="de">
	<head>
		<?php Bootstrap_css();?>
		<title>owsPro setAdmin</title>
		<link href="../admin/bootstrap/css/bootstrap.min.css"rel="stylesheet"media="screen">
		<link rel="shortcut icon"type="image/x-icon"href="../favicon.ico" />
		<meta charset="UTF-8">
		<style type="text/css">
			body{
				padding-top:100px;
				padding-bottom:40px;}</style></head>
	<body>
		<div class="container">
			<h1>owsPro - setAdmin</h1>
			<hr>
			<?php
				$errors=[];
				$messagesIncluded=FALSE;
				if(isset($_SESSION["lang"])){
					include("messages_".$_SESSION["lang"].".inc.php");
					$messagesIncluded=$_SESSION["lang"];}
				$action=(isset($_REQUEST["action"]))?$_REQUEST["action"]:"";
				if(!strlen($action)||substr($action,0,6)!=="action")$view="setAdminScreen";
				else $view=$action();
				if(isset($_SESSION["lang"])&&$_SESSION["lang"]!==$messagesIncluded)	include("messages_".$_SESSION["lang"].".inc.php");
				if(count($errors))foreach($errors as $error)echo"<div class=\"alert alert-error\">$error</div>";
				if(isset($messages))$view($messages);
				else $view();?>
			<hr>
			<footer>
				<p>Powered by <a href="https://github.com/owsPro/owsPro" target="_blank">owsPro</a><br>
				Forked from <a href="https://github.com/ihofmann/open-websoccer" target="_blank">ihofmann</a></p></footer></div>
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script src="../admin/bootstrap/js/bootstrap.min.js"></script></body></html>
