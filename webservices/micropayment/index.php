<?php
/*This file is part of "OWS for All PHP" (Rolf Joseph)
  https://github.com/owsPro/OWS_for_All_PHP/
  A spinn-off for PHP Versions 5.5 to 8.2 from:
  OpenWebSoccer-Sim(Ingo Hofmann), https://github.com/ihofmann/open-websoccer.

  "OWS for All PHP" is is distributed in WITHOUT ANY WARRANTY;
  without even the implied warranty of MERCHANTABILITY
  or FITNESS FOR A PARTICULAR PURPOSE.

  See GNU Lesser General Public License Version 3 http://www.gnu.org/licenses/

*****************************************************************************/
						include($_SERVER['DOCUMENT_ROOT'].'/admin/config/global.inc.php');if(!Config('micropayment_enabled'))die('micropayments.de is not enabled');$amount=$_GET['amount']/100;$userId=(int)$_GET['free'];if(!$userId)die('status=error');
						if($_GET['function']!='billing')die('invalid function');PremiumDataService::createPaymentAndCreditPremium($website,$db,$userId,$amount,'micropayment-notify');$trenner='\n';$status='ok';$url=iUrl('premiumaccount',null,TRUE);
						$target='_top';$forward=1;$response='status='.$status.$trenner.'url='.$url.$trenner.'target='.$target.$trenner.'forward='.$forward;echo$response;