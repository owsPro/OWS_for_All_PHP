<?php
/*This file is part of "OWS for All PHP"(Rolf Joseph)
  https://github.com/owsPro/OWS_for_All_PHP/
  A spinn-off for PHP Versions 5.4 to 8.2 from:
  OpenWebSoccer-Sim(Ingo Hofmann), https://github.com/ihofmann/open-websoccer.

  "OWS for All PHP"is is distributed in WITHOUT ANY WARRANTY;
  without even the implied warranty of MERCHANTABILITY
  or FITNESS FOR A PARTICULAR PURPOSE.

  See GNU Lesser General Public License Version 3 http://www.gnu.org/licenses/

*****************************************************************************/
	if(!$show){flags('/admin/index.php?lang=');?><h1><?php echo sprintf(Message('home_title'),escapeOutput($admin['name']));?></h1><p><?php echo Message('home_intro');?></p><h3><?php echo Message('home_softwareinfo_title');?></h3>
	<table class='table table-bordered'style='width:500px;'><tr><td><b><?php echo Message('home_softwareinfo_name');?></b></td><td>owsPro for All PHP</td></tr><tr><td><b><?php echo Message('home_softwareinfo_version');?></b></td>
	<td><?php readfile('config/version.txt');?></td></tr></table><h3><?php echo Message('home_projectinfo_title');?></h3><table class='table table-bordered'style='width:500px;'><tr><td><b><?php echo Message('home_projectinfo_name');?></b></td>
	<td><?php echo escapeOutput(Config('projectname'))?></td></tr><tr><td><b><?php Message('home_projectinfo_adminemail');?></b></td><td><a href='mailto:<?php echo Config('systememail');?>'><?php echo Config('systememail');?></a></td></tr></table><?php }