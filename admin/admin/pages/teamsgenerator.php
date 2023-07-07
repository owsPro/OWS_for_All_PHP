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
$mainTitle = $i18n->getMessage("teamsgenerator_navlabel");
echo "<h1>$mainTitle</h1>";
if (!$admin["r_admin"] && !$admin["r_demo"] && !$admin[$page["permissionrole"]]) throw new Exception($i18n->getMessage("error_access_denied"));
if (!$show) { ?>
  <p><?php echo $i18n->getMessage("teamsgenerator_intro"); ?></p>
  <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" class="form-horizontal">
    <input type="hidden" name="show" value="generate">
	<input type="hidden" name="site" value="<?php echo $site; ?>">
	<fieldset>
    	<legend><?php echo $i18n->getMessage("generator_label"); ?></legend><?php
			$formFields = ["league" => ["type" => "foreign_key", "labelcolumns" => "land,name", "jointable" => "liga", "entity" => "league", "value" => "", "required" => "true"],
			"numberofteams" => ["type" => "number", "value" => 20, "required" => "true"],
			"budget" => ["type" => "number", "value" => 5000000, "required" => "true"],
			"generatestadium" => ["type" => "boolean", "value" => 1],
			"stadiumpattern" => ["type" => "text", "value" => "Stadion %s"],
			"stadium_p_stands" => ["type" => "number", "value" => 1000],
			"stadium_p_seats" => ["type" => "number", "value" => 5000],
			"stadium_p_stands_grand" => ["type" => "number", "value" => 0],
			"stadium_p_seats_grand" => ["type" => "number", "value" => 10000],
			"stadium_p_vip" => ["type" => "number", "value" => 100]];
	foreach ($formFields as $fieldId => $fieldInfo) echo FormBuilder::createFormGroup($i18n, $fieldId, $fieldInfo, $fieldInfo["value"], "generator_label_"); ?></fieldset>
	<div class="form-actions">
		<input type="submit" class="btn btn-primary" accesskey="s" title="Alt + s" value="<?php echo $i18n->getMessage("generator_button"); ?>">
		<input type="reset" class="btn" value="<?php echo $i18n->getMessage("button_reset"); ?>"></div></form><?php }
elseif ($show == "generate") {
  if (!isset($_POST['league']) || $_POST['league'] <= 0) $err[] = $i18n->getMessage("generator_validationerror_noleague");
  if ($_POST['numberofteams'] <= 0) $err[] = $i18n->getMessage("generator_validationerror_numberofitems");
  if ($_POST['numberofteams'] > 100) $err[] = $i18n->getMessage("generator_validationerror_numberofitems_max");
  if ($admin['r_demo']) $err[] = $i18n->getMessage("validationerror_no_changes_as_demo");
  if (isset($err)) include("validationerror.inc.php");
  else {
	DataGeneratorService::generateTeams($website, $db, $_POST['numberofteams'], $_POST['league'], $_POST['budget'], (isset($_POST['generatestadium']) && $_POST['generatestadium']), $_POST['stadiumpattern'], $_POST['stadium_p_stands'], $_POST['stadium_p_seats'],
		$_POST['stadium_p_stands_grand'], $_POST['stadium_p_seats_grand'], $_POST['stadium_p_vip'] );
	echo createSuccessMessage($i18n->getMessage("generator_success"), "");
    echo "<p>&raquo; <a href=\"?site=". $site ."\">". $i18n->getMessage("back_label") . "</a></p>\n";}}
