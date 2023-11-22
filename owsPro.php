<?php
/*This file is part of "OWS for All PHP" (Rolf Joseph) https://github.com/owsPro/OWS_for_All_PHP/ A spinn-off for PHP Versions 7.1 to 8.3 from: OpenWebSoccer-Sim(Ingo Hofmann), https://github.com/ihofmann/open-websoccer.
  "OWS for All PHP" is is distributed in WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See GNU Lesser General Public License Version 3 http://www.gnu.org/licenses/
 - Features like the OpenWebSoccer from Ingo Hofmann
 - Automatic Twig version switcher that sets the correct Twig version depending on the PHP version used, actual PHP 8.2 with Twig 3.6.0.
 - All files from the classes folder in one file
 - All comments removed from the original
 - Source code adapted for more clarity.
 - Already much less source code.
 - Easier bug search and adaptations for future PHP versions.
 - The installation is a high level of compatibility.
 - Additional configuration and settings, e.g. through add-ons and basic configuration and settings, which are supplemented or overwritten by '/cache/wsconfigadmin.inc.php'.
 - Terms and conditions translated into many languages.
=====================================================================================*/
function C($name){global$conf;if(!isset($conf[$name]))M('Missing configuration: '.$name);return$conf[$name];}
function M($messageKey,$paramaters=NULL){global$msg;if(!hasMessage($messageKey)){return$messageKey;}$message=stripslashes($msg[$messageKey]);if($paramaters!=NULL){$message=sprintf($message,$paramaters);}return$message;}
function hasMessage($messageKey){global$msg;return isset($msg[$messageKey]);}
function SuccessMessage($title,$message){return createMessage('success',$title,$message);}
function WarningMessage($title,$message){return createMessage('warning',$title,$message);}
function ESC($message){return htmlspecialchars((string)$message,ENT_COMPAT,'UTF-8');}
function prepareFielfValueForSaving($fieldValue){$preparedValue=stripslashes($fieldValue);return$preparedValue;}
function AllLogging(){
	$mainTitle=M('all_logging_title');
	if(!$show){?>
  		<h1><?php echo$mainTitle;?></h1><p><?php echo M('all_logging_intro');?></p><?php
  		$datei='../generated/adminlog.php';
  		if(!file_exists($datei))echo ErrorMessage(M('alert_error_title'),M('all_logging_filenotfound'));
  		elseif($admin['r_demo'])echo ErrorMessage(M('error_access_denied'),'');
  		else{
    		if($action=='leeren'){$fp=fopen($datei,'w+');$ip=getenv('REMOTE_ADDR');$content='Truncated by '.$admin['name'].' (id: '.$admin['id'].'), '.$ip.','.date('d.m.y - H:i:s');fwrite($fp,$content);fclose($fp);
      			if($fp)echo SuccessMessage(M('all_logging_alert_logfile_truncated'),'');
      			else echo ErrorMessage(M('alert_error_title'),M('all_logging_error_not_truncated'));}
    		$datei_gr=filesize($datei);$gr_kb=round($datei_gr/1024);
    		if($datei_gr&&!$gr_kb)$gr_kb=1;
    		echo'<div class=\'well\'>'.sprintf(M('all_logging_filesize'),number_format($gr_kb,0,' ',',')).'</div>';
    		if(!$datei_gr)echo'<p>'.M('empty_list').'</p>';
    		else{?>	<form action='<?php echo ESC($_SERVER['PHP_SELF']);?>'method='post'><input type='hidden'name='action'value='leeren'><input type='hidden'name='site'value='<?php echo $site;?>'><p><input type='submit'class='btn'value='<?php echo M('all_logging_button_empty_file');?>'></p></form>
      			<p>(<?php echo M('all_logging_only_last_entries_shown');?>)</p>
        		<table class='table table-bordered table-striped'>
           			<tr><th><?php echo M('all_logging_label_no');?></th><th><?php echo M('all_logging_label_user');?></th><th><?php echo M('all_logging_label_ip');?></th><th><?php echo M('all_logging_label_time');?></th></tr><?php 
            		$file=file($datei);$lines=count($file);$min=$lines-50;
            		if($min<0)$min=0;for($i=$lines-1;$i>=$min;--$i){$line=$file[$i];$row=explode(',',$line);$n=$i+1;echo'<tr><td><b>'.$n.'</b></td><td>'.ESC($row[0]).'</td><td>'.ESC($row[1]).'</td><td>'.ESC($row[2]).'</td></tr>';}?></table><?php }}}}
function AllSettings(){
	$mainTitle=M('all_settings_title');
	include(CONFIGCACHE_SETTINGS);
	if(!$show){
		$tabs=[];
		foreach($setting as$settingId=>$settingData){
			$settingInfo=json_decode($settingData,true);
			$tabs[$settingInfo['category']][$settingId]=$settingInfo;}?>
  	<h1><?php echo$mainTitle;?></h1>
  	<form action='<?php echo ESC($_SERVER['PHP_SELF']);?>'method='post'class='form-horizontal'><input type='hidden'name='show'value='speichern'><input type='hidden'name='site'value='<?php echo$site;?>'>
		<ul class='nav nav-tabs'><?php
			$firstTab=TRUE;
			foreach($tabs as$tabId=>$settings){
				echo'<li';
				if($firstTab)echo' class=\'active\'';
				echo'><a href=\'#'.$tabId.'\'data-toggle=\'tab\'>'.M('settings_tab_'.$tabId).'</a></li>';
				$firstTab=FALSE;}?></ul>
		<div class='tab-content'><?php
			$firstTab=TRUE;
			foreach($tabs as$tabId=>$settings){
				echo'<div class=\'tab-pane';
				if($firstTab)echo' active';
				echo'\'id=\''.$tabId.'\'>';
				foreach($settings as$settingId=>$settingInfo)echo FormBuilder::createFormGroup($i18n,$settingId,$settingInfo,C($settingId),'settings_label_');
				echo'</div>';
				$firstTab=FALSE;}?></div>
		<div class='form-actions'><input type='submit'class='btn btn-primary'accesskey='s'title='Alt + s'value='<?php echo M('button_save');?>'><input type='reset'class='btn'value='<?php echo M('button_reset');?>'></div></form><?php }
	elseif($show=='speichern'){
		if($admin['r_demo'])$err[]=M('validationerror_no_changes_as_demo');
		if(isset($err))include('validationerror.inc.php');
		else{
  			$newSettings=[];
  			foreach($setting as$settingId=>$settingData)$newSettings[$settingId]=(isset($_POST[$settingId]))?prepareFielfValueForSaving($_POST[$settingId]):'';
  			$cf=ConfigFileWriter::getInstance($conf);
  			$cf->saveSettings($newSettings);
			include('success.inc.php');
			echo WarningMessage(M('settings_saved_note_restartjobs'),M('settings_saved_note_restartjobs_details'));}}}

@include($_SERVER['DOCUMENT_ROOT'].'/owsPro_old.php');

function Config($name){global$conf;if(!isset($conf[$name]))throw new Exception('Missing configuration: '.$name);return$conf[$name];}
function DBSave(){save(date('Y-m-d_H-i-s').'_'.C('db_name').'.sql.gz');}
function Query($queryStr){
        $con=new mysqli(C('db_host'),C('db_user'),C('db_passwort'),C('db_name'));
        $Result=$con->query($queryStr);
        if(!$Result)throw new Exception('Database Query Error: '.$con->error);
        return$Result;}
function save($file){
    	$extension='.gz';
    	$fileExtension=substr($file,-strlen($extension));
    	if($fileExtension===$extension)$handle=gzopen($file,'wb');
    	else$handle=fopen($file,'wb');
    	if(!$handle){error_log("ERROR: Cannot write file '$file'.");return;}
    	write($handle);
    	fclose($handle);}
function write($handle=null) {
        if($handle===null)$handle??fopen('php://output','wb');
        elseif(!is_resource($handle)||get_resource_type($handle)!=='stream')throw new Exception('Argument must be a stream resource.');
        $tables=$views=[];
        $res=Query('SHOW FULL TABLES');
		$tables=[];
		$views=[];
		while($row=$res->fetch_row())$row[1]==='VIEW'?$views[]=$row[0]:$tables[]=$row[0];
		$res->close();
        $tables=array_merge($tables,$views);
        Query('LOCK TABLES `'.implode('` READ,`',$tables).'`READ');
        Query('SELECT DATABASE()')->fetch_row();
        foreach($tables as$table)dumpTable($handle,$table);
        Query('UNLOCK TABLES');}
function dumpTable($handle,$table){
        $delTable=delimit($table);
        $res=Query("SHOW CREATE TABLE $delTable");
        $row=$res->fetch_assoc();
        $res->close();
        $view=isset($row['Create View']);
        if($view){fwrite($handle,'DROP VIEW IF EXISTS '.$delTable.';\n');fwrite($handle,$row['Create View'].';\n');}
        else{fwrite($handle,'DROP TABLE IF EXISTS '.$delTable.';\n');fwrite($handle,$row['Create Table'].';\n');fwrite($handle,'ALTER TABLE '.$delTable.' DISABLE KEYS;\n');
        $cols=[];
    	$res=Query("SHOW COLUMNS FROM $delTable");
    	while($row=$res->fetch_assoc()){$col=delimit($row['Field']);$cols[]=$col;}
    	$res->close();
        $rows=[];
        $res=Query("SELECT*FROM $delTable",MYSQLI_USE_RESULT);
        while($row=$res->fetch_assoc()){
        	$values=[];
            foreach($row as$value)$values[]=$value===null?'NULL':"'".escapeOutput($value)."'";$rows[]='('.implode(',',$values).')';}
            $res->close();
            $inserts=array_chunk($rows,100);
    		foreach($inserts as$insert)fwrite($handle,'INSERT INTO '.$delTable.' ('.implode(',',$cols).') VALUES '.(implode(',',$insert)??'').';\n');
            fwrite($handle,'ALTER TABLE '.$delTable.' ENABLE KEYS;\n');}}
function delimit($s){return '`'.str_replace('`','``',$s).'`';}
