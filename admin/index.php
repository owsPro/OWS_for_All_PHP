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
								// Check if the PHP version is at least 5.5.0
if(version_compare(PHP_VERSION,'5.5.0')<0){
    echo 'Minimum PHP 5.5.0 !';
    exit;
}

// Include the admin global configuration file
include($_SERVER['DOCUMENT_ROOT'].'/admin/adminglobal.inc.php');

// Set the current language based on the 'lang' query parameter
if(isset($_GET['lang'])){
    $i18n->setCurrentLanguage($_GET['lang']);
}

// Initialize the navigation items arrays
$navItems['settings'] = [];
$navItems['website'] = [];

// Loop through the admin pages
foreach($adminpage as $pageId => $pageData){
    $pageInfo = json_decode($pageData, true);
    
    // Check if the user has the required permissions to access the page
    if((!isset($admin['r_admin']) || !$admin['r_admin']) && 
       (!isset($admin['r_demo']) || !$admin['r_demo']) && 
       (!isset($admin[$pageInfo['permissionrole']]) || !$admin[$pageInfo['permissionrole']])){
        continue;
    }
    
    // Create the site info based on the page info
    if(isset($pageInfo['entity']) && $pageInfo['entity']){
        $siteInfo['label'] = Message('entity_'.$pageInfo['entity']);
        $siteInfo['pageid'] = 'manage';
        $siteInfo['entity'] = $pageInfo['entity'];
    }else{
        $siteInfo['label'] = $i18n->getNavigationLabel($pageId);
        $siteInfo['pageid'] = $pageInfo['filename'];
        $siteInfo['entity'] = null;
    }
    
    // Add the site info to the navigation items array
    $navItems[$pageInfo['navcategory']][] = $siteInfo;
}

// Call the Bootstrap_css function
Bootstrap_css();
?>

<!DOCTYPE html>
<html lang='<?php echo ESC($i18n->getCurrentLanguage());?>'>
<head>
    <title><?php echo Message('main_title')?></title>
    <link href='bootstrap-datepicker/css/datepicker.css' rel='stylesheet'>
    <link href='bootstrap-timepicker/css/bootstrap-timepicker.min.css' rel='stylesheet'>
    <link href='select2/select2.css' rel='stylesheet'/>
    <link href='markitup/skins/simple/style.css' rel='stylesheet'/>
    <link href='markitup/sets/ws/style.css' rel='stylesheet'/>
    <link href='bootstrap/bootstrap-tag.css' rel='stylesheet'>
    <meta charset='UTF-8'>
    <link rel='shortcut icon' type='image/x-icon' href='../favicon.ico'/>
</head>
<body>
    <div class='container-fluid'>
        <div class='row-fluid'>
            <div class='span2'>
                <br>
                <div class='well sidebar-nav'>
                    <ul class='nav'>
                        <h3>
                            <a class='brand' href='index.php' title='<?php echo Message('admincenter_homelink_tooltip');?>'>
                                <?php echo Message('admincenter_brand')?>
                            </a>
                        </h3>
                        <div class='nav-collapse collapse'>
                            <p class='navbar-text pull-right'></a></p>
                            <ul class='nav'>
                                <?php echo Message('admincenter_loggedin_as');echo' ';echo ESC($admin['name']);?><br><br>
                                <li>
                                    <a href="<?php $contextRoot=Config('context_root');echo(strlen($contextRoot))?$contextRoot:'/';?>">
                                        <i class='icon-globe icon-white'></i>
                                        <?php echo' '.Message('admincenter_link_website');?>
                                    </a>
                                </li>
                                <li>
                                    <a href='?site=profile'>
                                        <i class='icon-user icon-white'></i>
                                        <?php echo' '.Message('admincenter_link_profile');?>
                                    </a>
                                </li>
                                <li>
                                    <a href='?site=clearcache'>
                                        <i class='icon-refresh icon-white'></i>
                                        <?php echo' '.Message('admincenter_link_clear_cache');?>
                                    </a>
                                </li>
                                <li>
                                    <a href='logout.php'>
                                        <i class='icon-off icon-white'></i>
                                        <?php echo' '.Message('admincenter_logout');?>
                                    </a>
                                </li>
                                <br>
                                <li>
                                    <?php if(isset($_GET['DBSave'])) DBSave();?>
                                    <a href='index.php?DBSave=true'>DBSave</a>
                                <li>
                                    <?php if(isset($_GET['Diagnosis'])) Diagnosis();?>
                                    <a href='index.php?Diagnosis=true'>Diagnosis</a>
                                </li>
                                <?php if(isset($_GET['owsProFunctions'])) owsProFunctions();?>
                                <li>
                                    <a href='index.php?owsProFunctions=true'>owsProFunctions</a>
                                </li>
                                <?php foreach($navItems as $navCategory => $categoryItems){
                                    echo '<li class=\'nav-header\'>'.$i18n->getNavigationLabel('category_'.$navCategory).'</li>';
                                    foreach($categoryItems as $navInfo){
                                        printNavItem($site, $navInfo['pageid'], $navInfo['label'], $navInfo['entity']);
                                    }
                                }?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="span10">
                <?php 
                if(empty($site)){
                    $site = 'home';
                }
                $includeFile = 'pages/'.$site.'.php';
                if(preg_match('#^[a-z0-9_-]+$#i', $site) && file_exists($includeFile)){
                    try{
                        include($includeFile);
                    }catch(Exception $e){
                        echo createErrorMessage(Message('alert_error_title'), $e->getMessage());
                    }
                }else{
                    echo createErrorMessage(Message('alert_error_title'), Message('error_page_not_found'));
                }
                ?>
            </div>
        </div>
    </div>
    <div class='span2'>
        <footer>
            <p>
                Powered by <a href='https://github.com/owsPro/OWS_for_All_PHP' target='_blank'>owsPro</a><br>
                Forked from <a href='https://github.com/ihofmann/open-websoccer' target='_blank'>ihofmann</a>
            </p>
        </footer>
    </div>
    <script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script src="bootstrap-datepicker/js/locales/bootstrap-datepicker.<?php echo ESC($i18n->getCurrentLanguage());?>.js"></script>
    <script src="bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
    <script src="select2/select2.min.js"></script>
    <?php if($i18n->getCurrentLanguage()!="en") echo "<script src=\"select2/select2_locale_".ESC($i18n->getCurrentLanguage()).".js\"></script>";?>
    <script src="markitup/jquery.markitup.js"></script>
    <?php if ($i18n->getCurrentLanguage() == "de") { ?>
        <script src="markitup/sets/ws/set_de.js"></script>
    <?php } else { ?>
        <script src="markitup/sets/ws/set.js"></script>
    <?php } ?>
    <script src="js/admincenter.js"></script>
    <script src="js/bootbox.min.js"></script>
    <script src="js/bootstrap-tag.js"></script>
    <script>
        $(function(){
            $(document).on("click",".deleteBtn",function(e){
                bootbox.confirm("<?php echo Message("manage_delete_multiselect_confirm");?>","<?php echo Message("option_no");?>","<?php echo Message("option_yes");?>",function(result){
                    if(result){
                        document.frmMain.submit();
                    }
                });
            });

            $(document).on("click", ".deleteLink", function(e){
                e.preventDefault();
                var link = $(this);
                bootbox.confirm("<?php echo Message("manage_delete_link_confirm");?>","<?php echo Message("option_no");?>","<?php echo Message("option_yes");?>",function(result){
                    if(result){
                        window.location = link.attr("href");
                    }
                });
            });

            $(".datepicker").datepicker({
                format: "<?php echo str_replace("Y","yyyy",Config("date_format"));?>",
                language: "<?php echo ESC($i18n->getCurrentLanguage());?>",
                autoclose: true
            });
        });
    </script>
</body>
</html>
