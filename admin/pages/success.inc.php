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
    echo'<h1>'.$mainTitle.' &raquo; '.Message('subpage_save_title').'</h1>';echo createSuccessMessage(Message('alert_save_success'),'');echo"<p>&raquo; <a href=\"".escapeOutput($_SERVER['PHP_SELF']).'?site='.$site.'\">'.Message('back_label')."</a></p>\n";