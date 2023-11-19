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
								include($_SERVER['DOCUMENT_ROOT'].'/admin/config/global.inc.php');
Bootstrap_css();

// Initialize the I18n instance with supported languages
$i18n = I18n::getInstance(Config('supported_languages'));

// Set the current language based on the 'lang' query parameter
if(isset($_GET['lang'])){
    $i18n->setCurrentLanguage($_GET['lang']);
}

// Include the language configuration file based on the current language
include('/cache/wsconfigadmin.inc.php',CurrentLanguage());

// Include the language messages file based on the current language
include('/languages/messages_%s.php',CurrentLanguage()));

$errors = [];

// Get the input email from the POST request
$inputEmail = (isset($_POST['inputEmail'])) ? trim($_POST['inputEmail']) : FALSE;

if($inputEmail){
    $now = Timestamp();

    if(count($errors) == 0){
        $columns = array('id', 'passwort_neu_angefordert', 'name', 'passwort_salt');
        $fromTable = $conf['db_prefix'].'_admin';
        $whereCondition = 'email=\'%s\'';
        $parameters = $inputEmail;

        // Query the database to get the admin user
        $result = $db->querySelect($columns, $fromTable, $whereCondition, $parameters);
        $admin = $result->fetch_array();

        if($result->num_rows < 1){
            $errors['inputEmail'] = Message('sendpassword_admin_usernotfound');
        }elseif($admin['passwort_neu_angefordert'] > ($now - 120 * 60)){
            $errors['inputEmail'] = Message('sendpassword_admin_alreadysent');
        }else{
            $newPassword = generatePassword();
            $hashedPw = hashPassword($newPassword, $admin['passwort_salt']);
            $columns = array('passwort_neu' => $hashedPw, 'passwort_neu_angefordert' => $now);
            $fromTable = $conf['db_prefix'].'_admin';
            $whereCondition = 'id=%d';
            $parameter = $admin['id'];

            // Update the admin user's password and password request timestamp
            $db->queryUpdate($columns, $fromTable, $whereCondition, $parameter);

            try{
                // Send an email with the new password
                sendEmail($inputEmail, $newPassword, $website, $i18n);
                header('location:login.php?newpwd=1');
                die();
            }catch(Exception $e){
                $errors['inputEmail'] = $e->getMessage();
            }
        }

        $result->free();
    }
}

?><!DOCTYPE html>
<html>
<head>
    <title>AdminCenter - <?php echo Message('sendpassword_admin_title');?></title>
    <meta charset='UTF-8'>
    <link rel='shortcut icon' type='image/x-icon' href='../favicon.ico'/>
    <style type='text/css'>
        body{
            padding-top: 100px;
            padding-bottom: 40px;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1><?php echo Message('sendpassword_admin_title');?></h1>

        <?php
        // Display error messages
        if(count($errors) > 0){
            foreach($errors as $key => $message){
                echo createErrorMessage(Message('subpage_error_title'), $message);
            }
        }

        // Display language flags
        flags('/admin/forgot-password.php?lang=');
        ?>

        <p><?php echo Message('sendpassword_admin_intro');?></p>

        <form action='forgot-password.php' method='post' class='form-horizontal'>
            <div class='control-group <?php if(isset($errors['inputEmail'])) echo 'error';?>'>
                <label class='control-label' for='inputEmail'><?php echo Message('sendpassword_admin_label_email');?></label>
                <div class='controls'>
                    <input type='email' name='inputEmail' id='inputEmail' placeholder='E-Mail' value='<?php echo escapeOutput($inputEmail);?>'>
                </div>
            </div>
            <div class='control-group'>
                <div class='controls'>
                    <button type='submit' class='btn'><?php echo Message('sendpassword_admin_button');?></button>
                </div>
            </div>
        </form>

        <p><a href='login.php'><?php echo Message('sendpassword_admin_loginlink');?></a></p>
        <hr>
        <footer>
            <p>Powered by <a href='https://github.com/owsPro/OWS_for_All_PHP' target='_blank'>OWS_for_All_PHP</a></p>
        </footer>
    </div>

    <script src='https://code.jquery.com/jquery-latest.min.js'></script>
    <script src='bootstrap/js/bootstrap.min.js'></script>
</body>
</html>