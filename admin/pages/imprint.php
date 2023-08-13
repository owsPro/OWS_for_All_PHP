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

$mainTitle = Message('imprint_navlabel');

if (!$admin['r_admin'] && !$admin['r_demo'] && !$admin[$page['permissionrole']]) {
	throw new Exception(Message('error_access_denied'));
}

if (!$show) {

  ?>

  <h1><?php echo $mainTitle; ?></h1>

  <p><?php echo escapeOutput(Message('imprint_introduction')); ?></p>

  <form action='<?php echo escapeOutput($_SERVER['PHP_SELF']); ?>' method='post' class='form-horizontal'>
    <input type='hidden' name='show' value='save'>
	<input type='hidden' name='site' value='<?php echo $site; ?>'>

	<fieldset>
	<?php
	$formFields = array();

	$filecontent = '';
	if (file_exists($_SERVER['DOCUMENT_ROOT'].'/generated/imprint.php')) {
		$filecontent = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/generated/imprint.php');
	}

	$formFields['content'] = array('type' => 'html', 'value' => $filecontent, 'required' => 'true');
	foreach ($formFields as $fieldId => $fieldInfo) {
		echo createFormGroup($i18n, $fieldId, $fieldInfo, $fieldInfo['value'], 'imprint_label_');
	}
	?>
	</fieldset>
	<div class='form-actions'>
		<input type='submit' class='btn btn-primary' accesskey='s' title='Alt + s' value='<?php echo Message('button_save'); ?>'>
		<input type='reset' class='btn' value='<?php echo Message('button_reset'); ?>'>
	</div>
  </form>


  <?php

}

elseif ($show == 'save') {

  if (!isset($_POST['content']) || !$_POST['content']) $err[] = Message('imprint_validationerror_content');
  if ($admin['r_demo']) $err[] = Message('validationerror_no_changes_as_demo');

  if (isset($err)) {

    include('validationerror.inc.php');

  }
  else {

    echo '<h1>'. $mainTitle .' &raquo; '. Message('subpage_save_title') . '</h1>';

    $fw = new FileWriter($_SERVER['DOCUMENT_ROOT'].'/generated/imprint.php');
    $fw->writeLine(stripslashes($_POST['content']));
    $fw->close();

	echo createSuccessMessage(Message('alert_save_success'), '');

      echo '<p>&raquo; <a href=\'?site='. $site .'\'>'. Message('back_label') . '</a></p>';

  }

}

?>