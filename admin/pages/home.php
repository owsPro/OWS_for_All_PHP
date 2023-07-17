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
if(!$show){?>
	<a href="/admin/index.php?lang=de"><img src="/img/flags/de.png" width="24" height="24" alt="deutsch" title="deutsch" /></a>
	<a href="/admin/index.php?lang=en"><img src="/img/flags/en.png" width="24" height="24" alt="english" title="english" /></a>
	<a href="/admin/index.php?lang=es"><img src="/img/flags/es.png" width="24" height="24" alt="español" title="español" /></a>
	<a href="/admin/index.php?lang=pt"><img src="/img/flags/pt.png" width="24" height="24" alt="português" title="português" /></a>
	<a href="/admin/index.php?lang=dk"><img src="/img/flags/dk.png" width="24" height="24" alt="dansk" title="dansk" /></a>
	<a href="/admin/index.php?lang=ee"><img src="/img/flags/ee.png" width="24" height="24" alt="eesti" title="eesti" /></a>
	<a href="/admin/index.php?lang=fi"><img src="/img/flags/fi.png" width="24" height="24" alt="suomalainen" title="suomalainen" /></a>
	<a href="/admin/index.php?lang=fr"><img src="/img/flags/fr.png" width="24" height="24" alt="français" title="français" /></a>
	<a href="/admin/index.php?lang=id"><img src="/img/flags/id.png" width="24" height="24" alt="indonesia" title="indonesia" /></a>
	<a href="/admin/index.php?lang=it"><img src="/img/flags/it.png" width="24" height="24" alt="italiano" title="italiano" /></a>
	<a href="/admin/index.php?lang=lv"><img src="/img/flags/lv.png" width="24" height="24" alt="latvieši" title="latvieši" /></a>
	<a href="/admin/index.php?lang=lt"><img src="/img/flags/lt.png" width="24" height="24" alt="lietuviškas" title="lietuviškas" /></a>
	<a href="/admin/index.php?lang=nl"><img src="/img/flags/nl.png" width="24" height="24" alt="nederlands" title="nederlands" /></a>
	<a href="/admin/index.php?lang=pl"><img src="/img/flags/pl.png" width="24" height="24" alt="polska" title="polska" /></a>

	<a href="/admin/index.php?lang=br"><img src="/img/flags/br.png" width="24" height="24" alt="???lang_label_br???" title="???lang_label_br???" /></a>
	<a href="/admin/index.php?lang=ro"><img src="/img/flags/ro.png" width="24" height="24" alt="???lang_label_ro???" title="???lang_label_ro???" /></a>
	<a href="/admin/index.php?lang=se"><img src="/img/flags/se.png" width="24" height="24" alt="???lang_label_se???" title="???lang_label_se???" /></a>

	<a href="/admin/index.php?lang=sk"><img src="/img/flags/sk.png" width="24" height="24" alt="???lang_label_sk???" title="???lang_label_sk???" /></a>
	<a href="/admin/index.php?lang=si"><img src="/img/flags/si.png" width="24" height="24" alt="???lang_label_si???" title="???lang_label_si???" /></a>
	<a href="/admin/index.php?lang=cz"><img src="/img/flags/cz.png" width="24" height="24" alt="???lang_label_cz???" title="???lang_label_cz???" /></a>

	<a href="/admin/index.php?lang=tr"><img src="/img/flags/tr.png" width="24" height="24" alt="???lang_label_tr???" title="???lang_label_tr???" /></a>
	<a href="/admin/index.php?lang=hu"><img src="/img/flags/hu.png" width="24" height="24" alt="???lang_label_hu???" title="???lang_label_hu???" /></a>
	<a href="/admin/index.php?lang=jp"><img src="/img/flags/jp.png" width="24" height="24" alt="???lang_label_jp???" title="???lang_label_jp???" /></a><br><br>
<h1><?php echo sprintf($i18n->getMessage('home_title'),escapeOutput($admin['name']));?></h1><p><?php echo$i18n->getMessage('home_intro');?></p><h3><?php echo$i18n->getMessage('home_softwareinfo_title');?></h3>
<table class='table table-bordered'style='width:500px;'><tr><td><b><?php echo$i18n->getMessage('home_softwareinfo_name');?></b></td><td>owsPro for All PHP</td></tr><tr><td><b><?php echo$i18n->getMessage('home_softwareinfo_version');?></b></td>
<td><?php readfile('config/version.txt');?></td></tr></table><h3><?php echo$i18n->getMessage('home_projectinfo_title');?></h3><table class='table table-bordered'style='width:500px;'><tr><td><b><?php echo$i18n->getMessage('home_projectinfo_name');?></b></td>
<td><?php echo escapeOutput(Config('projectname'))?></td></tr><tr><td><b><?php echo$i18n->getMessage('home_projectinfo_adminemail');?></b></td><td><a href='mailto:<?php echo Config('systememail');?>'><?php echo Config('systememail');?></a></td></tr></table><?php }