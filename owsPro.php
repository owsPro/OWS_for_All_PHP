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
@include($_SERVER['DOCUMENT_ROOT'].'/owsPro_old.php');

function Config($name){global$conf;if(!isset($conf[$name]))throw new Exception('Missing configuration: '.$name);return$conf[$name];}
function C(...$args){return Config(...$args);}
function xpathESC($value){
    	$quote="'";
    	if(FALSE===strpos($value,$quote))return$quote.$value.$quote;
    	else return sprintf("concat('%s')",implode("',\"'\",'",explode($quote,$value)));}
function DBSave(){save(date('Y-m-d_H-i-s').'_'.C('db_name').'.sql.gz');}
function Query($queryStr){
    	$con=new mysqli(C('db_host'),C('db_user'),C('db_password'),$dbName=C('db_name'));
    	if($con->connect_error)throw new Exception('Database Connection Error: '.$con->connect_error);
    	$sanitizedQuery=$con->real_escape_string($queryStr);
		$result=$con->query($sanitizedQuery);
    	if(!$result)throw new Exception('Database Query Error: '.$con->error);
    	return $result;}
function save($file){
    	$extension='.gz';
    	$fileExtension=substr($file,-strlen($extension));
    	if($fileExtension===$extension)$handle=gzopen($file,'wb');
    	else$handle=fopen($file,'wb');
    	if(!$handle){error_log("ERROR: Cannot write file '$file'.");return;}
    	write($handle);
    	fclose($handle);}
function write($handle=null) {
        if($handle===null)$handle=fopen('php://output','wb');
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
            foreach($row as$value)$values[]=$value===null?'NULL':"'".ESC($value)."'";$rows[]='('.implode(',',$values).')';}
            $res->close();
            $inserts=array_chunk($rows,100);
    		foreach($inserts as$insert)fwrite($handle,'INSERT INTO '.$delTable.' ('.implode(',',$cols).') VALUES '.(implode(',',$insert)??'').';\n');
            fwrite($handle,'ALTER TABLE '.$delTable.' ENABLE KEYS;\n');}}
function delimit($s){return '`'.str_replace('`','``',$s).'`';}
