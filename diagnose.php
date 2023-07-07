<?php
error_reporting(E_ALL^E_WARNING);
class benchmarkTimer{
    var $startTime;
	var $totalTime=0;
	function start(){
	    list($usec,$string_ec)=explode(' ',microtime());
		$this->startTime=((float)$usec+(float)$string_ec);}
	function stop($time_itle){
	    list($usec,$string_ec)=explode(' ',microtime());
		$time=((float)$usec+(float)$string_ec)-$this->startTime;
		$this->totalTime+=$time;}}
function _getallheaders(){
    foreach($_SERVER as $name=>$value){
        if(substr($name,0,5)=='HTTP_'){
            $name=str_replace(' ','-',ucwords(strtolower(str_replace('_',' ',substr($name,5)))));
            $headers[$name]=$value;}
        elseif($name=="CONTENT_TYPE")$headers["Content-Type"]=$value;
        elseif($name=="CONTENT_LENGTH")$headers["Content-Length"]=$value;}
       return @$headers;}
function RequestTime(){
	$starttime=microtime(true);
	foreach(_getallheaders()as$name=>$value);
	$stoptime=microtime(true);
	$status=0;
	$status=($stoptime-$starttime)*1000000;
	$status=floor($status);
	return $status;}
// php benchmarker by Paul Taulborg (njaguar at http://forums.d2jsp.org) - Modified by Jeroen Post - Modified by Rolf Joseph / ErdemCan
if(function_exists("date_default_timezone_set"))date_default_timezone_set("UTC");
$_SERVER['HTTPS']='on';
$timer=new benchmarkTimer();
function simple(){
    $a = 0;
	for($i=0;$i<900000;++$i)++$a;
	$thisisanotherlongname=0;
	for($thisisalongname=0;$thisisalongname<900000;++$thisisalongname)$thisisanotherlongname++;}
function simplecall(){for($i=0;$i<900000;$i++)strlen("hallo");}
function hallo($a){}
function simpleucall(){for($i=0;$i<900000;++$i)hallo("hallo");}
function simpleudcall(){for($i=0;$i<900000;++$i)hallo2("hallo");}
function hallo2($a){}
function mandel(){
	$w1=92;	$h1=843;$recen=-.45;$imcen=0.0;$r=0.7;$s=0;$rec=0;$imc=0;$re=0;$im=0;$re2=0;$im2=0;$x=0;$y=0;$w2=0;$h2=0;$color=0;
	$s=2*$r/$w1;
	$w2=40;
	$h2=12;
	for ($y=0;$y<=$w1;$y=$y+1){
	    $imc=$s*($y-$h2)+$imcen;
		for($x=0;$x<=$h1;$x=$x+1){
		    $rec=$s*($x-$w2)+$recen;
			$re=$rec;
	 		$im=$imc;
	 		$color=1000;
			$re2=$re*$re;
			$im2=$im*$im;
			while(((($re2+$im2)<900000)&&$color>0)){
				$im=$re*$im*2+$imc;
				$re=$re2-$im2+$rec;
				$re2=$re*$re;
				$im2=$im*$im;
				$color=$color-1;}
				if($color==0)print"_";
 			else print"#";}
 			print"<br>";
			flush();}}
function mandel2(){
	$b=" .:,;!/>)|&IH%*#";
	for($y=30;printf("\n"),$C=$y*0.1-1.5,--$y;){
		for($x=0;$c=$x*0.04-2,$z=0,$Z=0,++$x<75;){
			for($r=$c,$i=$C,$k=0;$t=$z*$z-$Z*$Z+$r,$Z=2*$z*$Z+$i,$z=$t,$k<5000;$k++)if($z*$z+$Z*$Z>2000000)break;
				echo$b[$k%16];}}}
function Ack($m,$n){
	if($m==0)return $n+1;
	if($n==0)return Ack($m-1,1);
	return Ack($m-1,Ack($m,($n-1)));}
function ackermann($n){
	$r=Ack(3,$n);
	print"Ack(3,$n): $r\n";}
function ary($n){
	for($i=0;$i<$n;++$i)$X[$i]=$i;
	for($i=$n-1;$i>=0;--$i)$Y[$i] = $X[$i];
  	$last=$n-1;
  	print"$Y[$last]\n";}
function ary2($n){
	for($i=0;$i<$n;){
	    $X[$i]=$i;++$i;$X[$i]=$i;++$i;$X[$i]=$i;++$i;$X[$i]=$i;++$i;$X[$i]=$i;++$i;$X[$i]=$i;++$i;$X[$i]=$i;++$i;$X[$i]=$i;++$i;$X[$i]=$i;++$i;$X[$i]=$i;++$i;}
	for ($i=$n-1;$i>=0;){$Y[$i]=$X[$i];--$i;$Y[$i]=$X[$i];--$i;$Y[$i]=$X[$i];--$i;$Y[$i]=$X[$i];--$i;$Y[$i]=$X[$i];--$i;$Y[$i]=$X[$i];--$i;$Y[$i]=$X[$i];--$i;$Y[$i]=$X[$i];--$i;$Y[$i]=$X[$i];--$i;$Y[$i]=$X[$i];--$i;}
	$last=$n-1;
	print"$Y[$last]\n";}
function ary3($n){
	for($i=0;$i<$n;++$i){
		$X[$i]=$i+1;
		$Y[$i]=0;}
	for ($k=0;$k<1000;++$k)for($i=$n-1;$i>=0;--$i)$Y[$i]+=$X[$i];
	$last=$n-1;
	print"$Y[0] $Y[$last]\n";}
function fibo_r($n){return(($n<2)?1:fibo_r($n-2)+fibo_r($n-1));}
function fibo($n){
	$r=fibo_r($n);
	print"$r\n";}
function hash1($n){
	for($i=1;$i<=$n;++$i)$X[dechex($i)]=$i;
	$c=0;
	for($i=$n;$i>0;--$i)if($X[dechex($i)]){++$c;}
	print"$c\n";}
function hash2($n){
	for($i=0;$i<$n;++$i){
		$hash1["foo_$i"]=$i;
		$hash2["foo_$i"]=0;}
	for($i=$n;$i>0;--$i)foreach($hash1 as$key=>$value)$hash2[$key]+=$value;
	$first="foo_0";
	$last="foo_".($n-1);
	print"$hash1[$first] $hash1[$last] $hash2[$first] $hash2[$last]\n";}
function gen_random($n){
	global $LAST;
	return(($n*($LAST=($LAST*IA+IC)%IM))/IM);}
function heapsort_r($n,&$ra){
	$l=($n>>1)+1;
	$ir=$n;
	while(1){
		if($l>1)$rra=$ra[--$l];
		else{
			$rra=$ra[$ir];
			$ra[$ir]=$ra[1];
			if(--$ir==1){
				$ra[1]=$rra;
				return;}}
		$i=$l;
		$j=$l<<1;
		while($j<=$ir)
		{	if(($j<$ir)&&($ra[$j]<$ra[$j+1]))++$j;
			if($rra<$ra[$j]){
				$ra[$i]=$ra[$j];
				$j+=($i=$j);}
			else$j=$ir+1;}
		$ra[$i]=$rra;}}
function heapsort($N){
	global$LAST;
	define("IM",139968);
	define("IA",3877);
	define("IC",29573);
	$LAST=42;
	for($i=1;$i<=$N;++$i)$ary[$i]=gen_random(1);
	heapsort_r($N,$ary);
	printf("%.10f\n",$ary[$N]);}
function mkmatrix($rows,$cols){
	$count=1;
	$mx=[];
	for($i=0;$i<$rows;++$i)for($j=0;$j<$cols;++$j)$mx[$i][$j]=++$count;
	return($mx);}
function mmult($rows,$cols,$m1,$m2){
	$m3=[];
	for($i=0;$i<$rows;$i++){
	    for($j=0;$j<$cols;$j++){
	        $x=0;
			for($k=0;$k<$cols;++$k)$x+=$m1[$i][$k]*$m2[$k][$j];
			$m3[$i][$j]=$x;}}
	return($m3);}
function matrix($n){
	$SIZE=30;
	$m1=mkmatrix($SIZE,$SIZE);
	$m2=mkmatrix($SIZE,$SIZE);
	while(--$n)$mm=mmult($SIZE,$SIZE,$m1,$m2);
	print"{$mm[0][0]} {$mm[2][3]} {$mm[3][2]} {$mm[4][4]}\n";}
function nestedloop($n){
	$x=0;
	for($a=0;$a<$n;++$a)
		for($b=0;$b<$n;++$b)
			for($c=0;$c<$n;++$c)
				for($d=0;$d<$n;++$d)
					for($e=0;$e<$n;++$e)
						for($f=0;$f<$n;++$f)
							$x++;
	print"$x\n";}
function sieve($n){
	$count=0;
	while(--$n>0){
		$count=0;
		$flags=range(0,8192);
		for($i=2;$i<8193;++$i){
			if($flags[$i]>0){
				for($k=$i+$i;$k<=8192;$k+=$i)$flags[$k]=0;
				++$count;}}}
	print"Count: $count\n";}
function strcat($n){
	$str="";
	while($n-->0)$str.="hello\n";
	$len=strlen($str);
	print"$len\n";}
function getmicrotime(){
	$t=gettimeofday();
	return($t['sec']+$t['usec']/1000000);}
function start_test(){
	ob_start();
	return getmicrotime();}
function end_test($start,$name){
	global$total;
	$end=getmicrotime();
	ob_end_clean();
	$total+=$end-$start;
	$num=number_format($end-$start,1);
	ob_start();
	return getmicrotime();}
function total(){
	global$total;
	$num=number_format($total,1);
	echo"Rating Note = ".(round($num*1.668))."\n";}
$t0=$t=start_test();
simple();
$t=end_test($t,"simple");
simplecall();
$t=end_test($t,"simplecall");
simpleucall();
$t=end_test($t,"simpleucall");
simpleudcall();
$t=end_test($t,"simpleudcall");
mandel();
$t=end_test($t,"mandel");
mandel2();
$t=end_test($t,"mandel2");
ackermann(7);
$t=end_test($t,"ackermann(7)");
ary(50000);
$t=end_test($t,"ary(50000)");
ary2(50000);
$t=end_test($t,"ary2(50000)");
ary3(2000);
$t=end_test($t,"ary3(2000)");
fibo(30);
$t=end_test($t,"fibo(30)");
hash1(50000);
$t=end_test($t,"hash1(50000)");
hash2(500);
$t=end_test($t,"hash2(500)");
heapsort(20000);
$t=end_test($t,"heapsort(20000)");
matrix(20);
$t=end_test($t,"matrix(20)");
nestedloop(12);
$t=end_test($t,"nestedloop(12)");
sieve(30);
$t=end_test($t,"sieve(30)");
strcat(200000);
$t=end_test($t,"strcat(200000)");
total();
echo"<pre><br>
Diagnose vom    : ".date('m/d/Y H:i:s')."
Eigene-Adresse  : $_SERVER[REMOTE_ADDR]
Request-Time    : ".RequestTime()." Milli-Sec.
Protokoll       : $_SERVER[SERVER_PROTOCOL]
Server          : $_SERVER[SERVER_NAME]
Server-Adresse  : $_SERVER[SERVER_ADDR]
Platform        : ".PHP_OS."
Webserver       : $_SERVER[SERVER_SOFTWARE]
Serversoftware  : ".php_uname()."
PHP version     : ".phpversion().' with '.strtoupper(php_sapi_name()).'-Prozessmanager';
echo'<br>Memory Limit	: '.ini_get('memory_limit');
if(extension_loaded('ionCube Loader'))echo'<br>IonCube         : Loader is present.';
if(extension_loaded('apcu'))echo'<br>APCu-Cache      : is present.';
if(extension_loaded('Zend OPcache'))echo'<br>OpCache         : is present';
else echo'<br>OpCache		: is not present!';
echo'<br>Script Laufzeit : maximum '.ini_get('max_execution_time').' Sec.';
echo'<br>BaseDir         : '.$_SERVER["DOCUMENT_ROOT"];
$runs=500000;
$runs_slow=5000;
$string_1='Peter & Jens & Thomas & Karl & ich & du sind &&&& =%';
$string_2='     wie      ';
$string_3=strtoupper($string_1);
$string_4='1234a';
$string_5='64x32';
$string_6='Dies ist ein Link nach http://openwebsoccer.de';
$string_7='Die Nummer %d ist wie der String %s der wie eine hex %x ausgegeben wird';
$string_8=$string_7.' and then some';
$string_9='quotes\'are "fun" to use\'. Most of the time. \\ ya';
$array_1=['a','b','c','d','e','f','g','h'=>1,'i'=>2,'j'=>NULL];
$array_2=['Kaffee','Tee','Coffein'];
$time_1='29/11/2011 Datum 10:15:37 Zeit';
$now=time();
$timer->start();
$i=0;
while($i>NULL)--$i;
for($i=NULL;$i<$runs;++$i);
for($i=NULL;$i<$runs;++$i){$z=$i%4;if($z==NULL){}elseif($z==1){}elseif($z==2){}else{}}
for($i=NULL;$i<$runs;++$i){$z=$i%4;switch($z){case 0: break;case 1:break;case 2:break;default:break;}}
for($i=NULL;$i<$runs;++$i){$z=($i%2==NULL?1:0);}
for($i=NULL;$i<$runs;++$i)str_replace('&','&amp;',$string_1);
for($i=NULL;$i<$runs_slow;++$i)preg_replace('#(^|\s)(http[s]?://\w+[^\s\[\]\<]+)#i',"\1<a href='\2'>\2</a>",$string_6);
for($i=NULL;$i<$runs;++$i)preg_match('#http[s]?://\w+[^\s\[\]\<]+#',$string_6);
for($i=NULL;$i<$runs;++$i)count($array_1);
for($i=NULL;$i<$runs;++$i){isset($array_1['i']);isset($array_1['zzNozz']);}
for($i=NULL;$i<$runs;++$i)time();
for($i=NULL;$i<$runs;++$i)strlen($string_1);
for($i=NULL;$i<$runs;++$i)sprintf($string_7,$i,$string_5,$i);
for($i=NULL;$i<$runs;++$i)strcmp($string_7,$string_8);
for($i=NULL;$i<$runs;++$i)trim($string_2);
for($i=NULL;$i<$runs_slow;++$i)explode('&',$string_1);
for($i=NULL;$i<$runs;++$i)implode('&',$array_1);
$f1=$timer->totalTime;
for($i=NULL;$i<$runs;++$i)number_format($f1,3);
for($i=NULL;$i<$runs;++$i)floor($f1);
for($i=NULL;$i<$runs;++$i)strpos($string_2,'t');
for($i=NULL;$i<$runs;++$i)substr($string_1,10);
for($i=NULL;$i<$runs;++$i)intval($string_4);
for($i=NULL;$i<$runs;++$i)(int)$string_4;
for($i=NULL;$i<$runs;++$i){is_array($array_1);is_array($string_1);}
for($i=NULL;$i<$runs;++$i){is_numeric($f1);is_numeric($string_4);}
for($i=NULL;$i<$runs;++$i){is_int($f1);is_int($string_4);}
for($i=NULL;$i<$runs;++$i){is_string($f1);is_string($string_4);}
for($i=NULL;$i<$runs;++$i)ip2long('1.2.3.4');
for($i=NULL;$i<$runs;++$i)long2ip(89851921);
for($i=NULL;$i<$runs_slow;++$i)date('F j,Y,g:i a',$now);
for($i=NULL;$i<$runs_slow;++$i)date('%B %e,%Y,%l:%M %P',$now);
for($i=NULL;$i<$runs_slow;++$i)strtotime($time_1);
for($i=NULL;$i<$runs;++$i)strtolower($string_3);
for($i=NULL;$i<$runs;++$i)strtoupper($string_1);
for($i=NULL;$i<$runs;++$i)md5($string_1);
for($i=NULL;$i<$runs;++$i){unset($array_1['j']); $array_1['j']=NULL;}
for($i=NULL;$i<$runs;++$i)list($drink,$runsolor,$power)=$array_2;
for($i=NULL;$i<$runs;++$i)urlencode($string_1);
$string_1e=urlencode($string_1);
for($i=NULL;$i<$runs;++$i)urldecode($string_1e);
for($i=NULL;$i<$runs;++$i)addslashes($string_9);
$string_9e=addslashes($string_9);
for($i=NULL;$i<$runs;++$i)stripslashes($string_9e);
$timer->stop('');
echo'<br>PHP Benchmark   : Referenztime PHP 8.2.7 : 1.0 Sec.';
echo@$head.'<br>'.str_pad('PHP Benchmark   : Server       PHP '.PHP_VERSION,23).' : '.number_format($timer->totalTime,1).' Sec.<br></pre>';
ob_start();
if(function_exists(phpinfo())){
    phpinfo();
    $phpinfo=ob_get_contents();
    ob_end_clean();
    $phpinfo=preg_replace('/<\/div><\/body><\/html>/','',$phpinfo);
    $hr='<div style="width:100%;background:#000;height:10px;margin-bottom:1em;"></div>'.PHP_EOL;
    ob_start();
    echo'<table border="0" cellpadding="3" width="600">'.PHP_EOL;
    echo'<tr class="h"><td><a href="http://www.php.net/">';
    echo'<img border="0"src="http://static.php.net/www.php.net/images/php.gif"alt="PHP Logo"/>';
    echo'</a><h1 class="p">PHP Extensions</h1>'.PHP_EOL;
    echo'</td></tr>'.PHP_EOL;
    echo'</table><br />'.PHP_EOL;
    echo'<h2>geladene Erweiterungen</h2>'.PHP_EOL;
    echo'<table border="0"cellpadding="3"width="600">'.PHP_EOL;
    echo'<tr><td class="e">Extensions</td><td class="v">'.PHP_EOL;
    foreach(get_loaded_extensions()as$ext)$exts[]=$ext;
    echo implode(',',$exts).PHP_EOL;
    echo'</td></tr></table><br />'.PHP_EOL;
    echo'<h2>enthaltene Funktionen</h2>'.PHP_EOL;
    echo'<table border="0"cellpadding="3"width="600">'.PHP_EOL;
    foreach($exts as$ext)
	    $extensions=get_loaded_extensions();
        foreach($extensions as$extension){
    	    echo'<tr><td class="e">'.$extension.'</td><td class="v">';
    	    echo implode(',',(array)get_extension_funcs($extension)),'<br/>';}
        echo'</td></tr>'.PHP_EOL;
    echo'</table><br />'.PHP_EOL;
    echo'</div></body></html>'.PHP_EOL;
    $extinfo=ob_get_contents();
    ob_end_clean();
    echo $phpinfo.$hr.$extinfo;}