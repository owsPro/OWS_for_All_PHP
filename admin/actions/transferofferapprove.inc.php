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
DirectTransfersDataService::executeTransferFromOffer($website, $db, $website->getRequestParameter('id'));
$db->queryUpdate(['admin_approval_pending' => '0'], $website->getConfig('db_prefix') . '_transfer_offer', 'id = %d', $website->getRequestParameter('id'));
echo createSuccessMessage($i18n->getMessage('transferoffer_approval_success'), '');
