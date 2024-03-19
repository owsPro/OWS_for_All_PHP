<?php
/*
/* This file "owsPro - Twig 4.0 / In1File" (Rolf Joseph) https://github.com/owsPro/

    This is a Twig template in a file system to be able to create Twig templates to corresponding PHP cached files using a file,
    which makes maintenance easier. The functions are set among each other in the file so that the normal functionality is given.

    This software library is distributed WITHOUT ANY WARRANTY;
    without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
    See GNU Lesser General Public License Version 3 http://www.gnu.org/licenses/
    This software library is based on Twig 4 with modifications.

    Not everyone has or uses the PHPUnit framework and therefore it is not supported!
    -Use PHPUnit\Framework\TestCase;
    -abstract class IntegrationTestCase extends TestCase
    -abstract class NodeTestCase extends TestCase
*/
/*
This software library uses Twig 4 in compliance with the copyright below:

Copyright(c)2009-present by the Twig Team.

All rights reserved.

Redistribution and use in source and binary forms,with or without modification,
are permitted provided that the following conditions are met:

   *Redistributions of source code must retain the above copyright notice,
      this list of conditions and the following disclaimer.
   *Redistributions in binary form must reproduce the above copyright notice,
      this list of conditions and the following disclaimer in the documentation
      and/or other materials provided with the distribution.
   *Neither the name of Twig nor the names of its contributors
      may be used to endorse or promote products derived from this software
      without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
"AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,INCLUDING,BUT NOT
LIMITED TO,THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
CONTRIBUTORS BE LIABLE FOR ANY DIRECT,INDIRECT,INCIDENTAL,SPECIAL,
EXEMPLARY,OR CONSEQUENTIAL DAMAGES(INCLUDING,BUT NOT LIMITED TO,
PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,DATA,OR
PROFITS; OR BUSINESS INTERRUPTION)HOWEVER CAUSED AND ON ANY THEORY OF
LIABILITY,WHETHER IN CONTRACT,STRICT LIABILITY,OR TORT(INCLUDING
NEGLIGENCE OR OTHERWISE)ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE,EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/
namespace Twig;
#[\Attribute(\Attribute::TARGET_CLASS)]
class YieldReady{}
interface LoaderInterface{
                function getSourceContext(string$name):Source;
                function getCacheKey(string$name):string;
                function isFresh(string$name,int$time):bool;
                function exists(string$name);}
class FilesystemLoader   implements LoaderInterface{const MAIN_NAMESPACE='__main__';protected$paths=[];protected$cache=[];protected$errorCache=[];private string$rootPath;
                function __construct($paths=[],?string$rootPath=null){$this->rootPath=($rootPath??getcwd()).\DIRECTORY_SEPARATOR;if(null!==$rootPath&&false!==($realPath=realpath($rootPath))){$this->rootPath=$realPath.\DIRECTORY_SEPARATOR;}if($paths){$this->setPaths($paths);}}
                function getPaths(string$namespace=self::MAIN_NAMESPACE):array{return$this->paths[$namespace]??[];}
                function getNamespaces():array{return array_keys($this->paths);}
                function setPaths($paths,string$namespace=self::MAIN_NAMESPACE):void{if(!\is_array($paths)){$paths=[$paths];}$this->paths[$namespace]=[];foreach($paths as$path){$this->addPath($path,$namespace);}}
                function addPath(string$path,string$namespace=self::MAIN_NAMESPACE):void{$this->cache=$this->errorCache=[];$checkPath=$this->isAbsolutePath($path)?$path:$this->rootPath.$path;if(!is_dir($checkPath)){throw new LoaderError(sprintf('The "%s" directory does not exist("%s").',$path,$checkPath));}
                         $this->paths[$namespace][]=rtrim($path,'/\\');}
                function prependPath(string$path,string$namespace=self::MAIN_NAMESPACE):void{$this->cache=$this->errorCache=[];$checkPath=$this->isAbsolutePath($path)?$path:$this->rootPath.$path;if(!is_dir($checkPath)){throw new LoaderError(sprintf('The "%s" directory does not exist("%s").',$path,$checkPath))
                         ;}$path=rtrim($path,'/\\');if(!isset($this->paths[$namespace])){$this->paths[$namespace][]=$path;}else{array_unshift($this->paths[$namespace],$path);}}
                function getSourceContext(string$name):Source{if(null===$path=$this->findTemplate($name)){return new Source('',$name,'');}return new Source(file_get_contents($path),$name,$path);}
                function getCacheKey(string$name):string{if(null===$path=$this->findTemplate($name)){return'';}$len=\strlen($this->rootPath);if(0===strncmp($this->rootPath,$path,$len)){return substr($path,$len);}return$path;}
                function exists(string$name){$name=$this->normalizeName($name);if(isset($this->cache[$name])){return true;}return null!==$this->findTemplate($name,false);}
                function isFresh(string$name,int$time):bool{return filemtime($path)<$time;}
      protected function findTemplate(string$name,bool$throw=true){$name=$this->normalizeName($name);if(isset($this->cache[$name])){return$this->cache[$name];}if(isset($this->errorCache[$name])){if(!$throw){return null;}throw new LoaderError($this->errorCache[$name]);}try{[$namespace,$shortname]=$this->
                         parseName($name);$this->validateName($shortname);}catch(LoaderError$e){if(!$throw){return null;}throw$e;}if(!isset($this->paths[$namespace])){$this->errorCache[$name]=sprintf('There are no registered paths for namespace "%s".',$namespace);if(!$throw){return null;}throw new LoaderError
                         ($this->errorCache[$name]);}foreach($this->paths[$namespace]as$path){if(!$this->isAbsolutePath($path)){$path=$this->rootPath.$path;}if(is_file($path.'/'.$shortname)){if(false!==$realpath=realpath($path.'/'.$shortname)){return$this->cache[$name]=$realpath;}return$this->cache[$name]=
                         $path.'/'.$shortname;}}$this->errorCache[$name]=sprintf('Unable to find template "%s"(looked into: %s).',$name,implode(',',$this->paths[$namespace]));if(!$throw){return null;}throw new LoaderError($this->errorCache[$name]);}
        private function normalizeName(string$name):string{return preg_replace('#/{2,}#','/',str_replace('\\','/',$name));}
        private function parseName(string$name,string$default=self::MAIN_NAMESPACE):array{if(isset($name[0])&&'@'==$name[0]){if(false===$pos=strpos($name,'/')){throw new LoaderError(sprintf('Malformed namespaced template name "%s"(expecting "@namespace/template_name").',$name));}$namespace=substr($name,1,$pos
                         -1);$shortname=substr($name,$pos+1);return[$namespace,$shortname];}return[$default,$name];}
        private function validateName(string$name):void{if(str_contains($name,"\0")){throw new LoaderError('A template name cannot contain NUL bytes.');}$name=ltrim($name,'/');$parts=explode('/',$name);$level=0;foreach($parts as$part){if('..'===$part){--$level;}elseif('.'!==$part){++$level;}if($level<0){throw
                         new LoaderError(sprintf('Looks like you try to load a template outside configured directories(%s).',$name));}}}
        private function isAbsolutePath(string$file):bool{return strspn($file,'/\\',0,1)||(\strlen($file)>3&&ctype_alpha($file[0])&&':'===$file[1]&&strspn($file,'/\\',2,1))||null!==parse_url($file,\PHP_URL_SCHEME);}}
interface ExtensionInterface{
                function getTokenParsers();
                function getNodeVisitors();
                function getFilters();
                function getTests();
                function getFunctions();
                function getOperators();}
class Error              extends \Exception{private int$lineno;private?string$name;private string$rawMessage;private?string$sourcePath=null;private?string$sourceCode=null;
                function __construct(string$message,int$lineno=-1,?Source$source=null,?\Throwable$previous=null){parent::__construct('',0,$previous);if(null===$source){$name=null;}else{$name=$source->getName();$this->sourceCode=$source->getCode();$this->sourcePath=$source->getPath();}$this->lineno=$lineno;
                         $this->name=$name;$this->rawMessage=$message;$this->updateRepr();}
                function getRawMessage():string{return$this->rawMessage;}
                function getTemplateLine():int{return$this->lineno;}
                function setTemplateLine(int$lineno):void{$this->lineno=$lineno;$this->updateRepr();}
                function getSourceContext():?Source{return$this->name? new Source($this->sourceCode,$this->name,$this->sourcePath):null;}
                function setSourceContext(?Source$source=null):void{if(null===$source){$this->sourceCode=$this->name=$this->sourcePath=null;}else{$this->sourceCode=$source->getCode();$this->name=$source->getName();$this->sourcePath=$source->getPath();}$this->updateRepr();}
                function guess():void{$this->guessTemplateInfo();$this->updateRepr();}
                function appendMessage($rawMessage):void{$this->rawMessage.=$rawMessage;$this->updateRepr();}
        private function updateRepr():void{$this->message=$this->rawMessage;if($this->sourcePath&&$this->lineno>0){$this->file=$this->sourcePath;$this->line=$this->lineno;return;}$dot=false;if(str_ends_with($this->message,'.')){$this->message=substr($this->message,0,-1);$dot=true;}$questionMark=false;if(
                         str_ends_with($this->message,'?')){$this->message=substr($this->message,0,-1);$questionMark=true;}if($this->name){if(\is_string($this->name)||(\is_object($this->name)&&method_exists($this->name,'__toString'))){$name=sprintf('"%s"',$this->name);}else{$name=json_encode($this->name);}
                         $this->message.=sprintf(' in %s',$name);}if($this->lineno&&$this->lineno>= 0){$this->message.=sprintf(' at line %d',$this->lineno);}if($dot){$this->message.='.';}if($questionMark){$this->message.='?';}}
        private function guessTemplateInfo():void{$template=null;$templateClass=null;$backtrace=debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS|\DEBUG_BACKTRACE_PROVIDE_OBJECT);foreach($backtrace as$trace){if(isset($trace['object'])&&$trace['object']instanceof Template){$currentClass=$trace['object']::class;
                         $isEmbedContainer=null===$templateClass? false:str_starts_with($templateClass,$currentClass);if(null===$this->name||($this->name==$trace['object']->getTemplateName()&&!$isEmbedContainer)){$template=$trace['object'];$templateClass=$trace['object']::class;}}}if(null!==$template&&null===
                         $this->name){$this->name=$template->getTemplateName();}if(null!==$template&&null===$this->sourcePath){$src=$template->getSourceContext();$this->sourceCode=$src->getCode();$this->sourcePath=$src->getPath();}if(null===$template||$this->lineno>-1){return;}$r=new \ReflectionObject(
                         $template);$file=$r->getFileName();$exceptions=[$e=$this];while($e=$e->getPrevious()){$exceptions[]=$e;}while($e=array_pop($exceptions)){$traces=$e->getTrace();array_unshift($traces,['file'=>$e->getFile(),'line'=>$e->getLine()]);while($trace=array_shift($traces)){if(!isset($trace[
                         'file'])||!isset($trace['line'])||$file!=$trace['file']){continue;}foreach($template->getDebugInfo()as$codeLine=>$templateLine){if($codeLine<=$trace['line']){$this->lineno=$templateLine;return;}}}}}}
class RuntimeError       extends Error{}
abstract
class AbstractExtension  implements ExtensionInterface{
                function getTokenParsers(){return[];}
                function getNodeVisitors(){return[];}
                function getFilters(){return[];}
                function getTests(){return[];}
                function getFunctions(){return[];}
                function getOperators(){return[];}}
class CoreExtension      extends AbstractExtension{private array$dateFormats=['F j,Y H:i','%d days'];private array$numberFormat=[0,'.',','];private?\DateTimeZone$timezone=null;
                function setDateFormat($format=null,$dateIntervalFormat=null){if(null!==$format){$this->dateFormats[0]=$format;}if(null!==$dateIntervalFormat){$this->dateFormats[1]=$dateIntervalFormat;}}
                function getDateFormat(){return$this->dateFormats;}
                function setTimezone($timezone){$this->timezone=$timezone instanceof \DateTimeZone?$timezone:new \DateTimeZone($timezone);}
                function getTimezone(){if(null===$this->timezone){$this->timezone=new \DateTimeZone(date_default_timezone_get());}return$this->timezone;}
                function setNumberFormat($decimal,$decimalPoint,$thousandSep){$this->numberFormat=[$decimal,$decimalPoint,$thousandSep];}
                function getNumberFormat(){return$this->numberFormat;}
                function getTokenParsers():array{return[new ApplyTokenParser(),new ForTokenParser(),new IfTokenParser(),new ExtendsTokenParser(),new IncludeTokenParser(),new BlockTokenParser(),new UseTokenParser(),new MacroTokenParser(),new ImportTokenParser(),new FromTokenParser(),new SetTokenParser(),new
                         FlushTokenParser(),new DoTokenParser(),new EmbedTokenParser(),new WithTokenParser(),new DeprecatedTokenParser(),];}
                function getFilters():array{return[new TwigFilter('date',[self::class,'dateFormatFilter'],['needs_environment'=>true]),new TwigFilter('date_modify',[self::class,'dateModifyFilter'],['needs_environment'=>true]),new TwigFilter('format',[self::class,'sprintf']),new TwigFilter('replace',[self::
                         class,'replaceFilter']),new TwigFilter('number_format',[self::class,'numberFormatFilter'],['needs_environment'=>true]),new TwigFilter('abs','abs'),new TwigFilter('round',[self::class,'round']),new TwigFilter('url_encode',[self::class,'urlencodeFilter']),new TwigFilter('json_encode',
                         'json_encode'),new TwigFilter('convert_encoding',[self::class,'convertEncoding']),new TwigFilter('title',[self::class,'titleStringFilter'],['needs_environment'=>true]),new TwigFilter('capitalize',[self::class,'capitalizeStringFilter'],['needs_environment'=>true]),new TwigFilter('upper'
                         ,[self::class,'upperFilter'],['needs_environment'=>true]),new TwigFilter('lower',[self::class,'lowerFilter'],['needs_environment'=>true]),new TwigFilter('striptags',[self::class,'striptags']),new TwigFilter('trim',[self::class,'trimFilter']),new TwigFilter('nl2br',[self::class,'nl2br']
                         ,['pre_escape'=>'html','is_safe'=>['html']]),new TwigFilter('spaceless',[self::class,'spaceless'],['is_safe'=>['html']]),new TwigFilter('join',[self::class,'joinFilter']),new TwigFilter('split',[self::class,'splitFilter'],['needs_environment'=>true]),new TwigFilter('sort',[self::class,
                         'sortFilter'],['needs_environment'=>true]),new TwigFilter('merge',[self::class,'arrayMerge']),new TwigFilter('batch',[self::class,'arrayBatch']),new TwigFilter('column',[self::class,'arrayColumn']),new TwigFilter('filter',[self::class,'arrayFilter'],['needs_environment'=>true]),new
                         TwigFilter('map',[self::class,'arrayMap'],['needs_environment'=>true]),new TwigFilter('reduce',[self::class,'arrayReduce'],['needs_environment'=>true]),new TwigFilter('reverse',[self::class,'reverseFilter'],['needs_environment'=>true]),new TwigFilter('length',[self::class,
                         'lengthFilter'],['needs_environment'=>true]),new TwigFilter('slice',[self::class,'slice'],['needs_environment'=>true]),new TwigFilter('first',[self::class,'first'],['needs_environment'=>true]),new TwigFilter('last',[self::class,'last'],['needs_environment'=>true]),new TwigFilter(
                         'default',[self::class,'defaultFilter'],['node_class'=>DefaultFilter::class]),new TwigFilter('keys',[self::class,'getArrayKeysFilter']),];}
                function getFunctions():array{return[new TwigFunction('max','max'),new TwigFunction('min','min'),new TwigFunction('range','range'),new TwigFunction('constant',[self::class,'constant']),new TwigFunction('cycle',[self::class,'cycle']),new TwigFunction('random',[self::class,'random'],[
                         'needs_environment'=>true]),new TwigFunction('date',[self::class,'dateConverter'],['needs_environment'=>true]),new TwigFunction('include',[self::class,'include'],['needs_environment'=>true,'needs_context'=>true,'is_safe'=>['all']]),new TwigFunction('source',[self::class,'source'],[
                         'needs_environment'=>true,'is_safe'=>['all']]),];}
                function getTests():array{return[new TwigTest('even',null,['node_class'=>EvenTest::class]),new TwigTest('odd',null,['node_class'=>OddTest::class]),new TwigTest('defined',null,['node_class'=>DefinedTest::class]),new TwigTest('same as',null,['node_class'=>SameasTest::class,
                         'one_mandatory_argument'=>true]),new TwigTest('none',null,['node_class'=>NullTest::class]),new TwigTest('null',null,['node_class'=>NullTest::class]),new TwigTest('divisible by',null,['node_class'=>DivisiblebyTest::class,'one_mandatory_argument'=>true]),new TwigTest('constant',null,[
                         'node_class'=>ConstantTest::class]),new TwigTest('empty',[self::class,'testEmpty']),new TwigTest('iterable','is_iterable'),];}
                function getNodeVisitors():array{return[new MacroAutoImportNodeVisitor()];}
                function getOperators():array{return[['not'=>['precedence'=>50,'class'=>NotUnary::class],'-'=>['precedence'=>500,'class'=>NegUnary::class],'+'=>['precedence'=>500,'class'=>PosUnary::class],],['or'=>['precedence'=>10,'class'=>OrBinary::class,'associativity'=>ExpressionParser::OPERATOR_LEFT],
                         'and'=>['precedence'=>15,'class'=>AndBinary::class,'associativity'=>ExpressionParser::OPERATOR_LEFT],'b-or'=>['precedence'=>16,'class'=>BitwiseOrBinary::class,'associativity'=>ExpressionParser::OPERATOR_LEFT],'b-xor'=>['precedence'=>17,'class'=>BitwiseXorBinary::class,'associativity'=>
                         ExpressionParser::OPERATOR_LEFT],'b-and'=>['precedence'=>18,'class'=>BitwiseAndBinary::class,'associativity'=>ExpressionParser::OPERATOR_LEFT],'=='=>['precedence'=>20,'class'=>EqualBinary::class,'associativity'=>ExpressionParser::OPERATOR_LEFT],'!='=>['precedence'=>20,'class'=>
                         NotEqualBinary::class,'associativity'=>ExpressionParser::OPERATOR_LEFT],'<=>'=>['precedence'=>20,'class'=>SpaceshipBinary::class,'associativity'=>ExpressionParser::OPERATOR_LEFT],'<'=>['precedence'=>20,'class'=>LessBinary::class,'associativity'=>ExpressionParser::OPERATOR_LEFT],
                         '>'=>['precedence'=>20,'class'=>GreaterBinary::class,'associativity'=>ExpressionParser::OPERATOR_LEFT],'>='=>['precedence'=>20,'class'=>GreaterEqualBinary::class,'associativity'=>ExpressionParser::OPERATOR_LEFT],'<='=>['precedence'=>20,'class'=>LessEqualBinary::class,'associativity'=>
                         ExpressionParser::OPERATOR_LEFT],'not in'=>['precedence'=>20,'class'=>NotInBinary::class,'associativity'=>ExpressionParser::OPERATOR_LEFT],'in'=>['precedence'=>20,'class'=>InBinary::class,'associativity'=>ExpressionParser::OPERATOR_LEFT],'matches'=>['precedence'=>20,'class'=>
                         MatchesBinary::class,'associativity'=>ExpressionParser::OPERATOR_LEFT],'starts with'=>['precedence'=>20,'class'=>StartsWithBinary::class,'associativity'=>ExpressionParser::OPERATOR_LEFT],'ends with'=>['precedence'=>20,'class'=>EndsWithBinary::class,'associativity'=>ExpressionParser::
                         OPERATOR_LEFT],'has some'=>['precedence'=>20,'class'=>HasSomeBinary::class,'associativity'=>ExpressionParser::OPERATOR_LEFT],'has every'=>['precedence'=>20,'class'=>HasEveryBinary::class,'associativity'=>ExpressionParser::OPERATOR_LEFT],'..'=>['precedence'=>25,'class'=>RangeBinary::
                         class,'associativity'=>ExpressionParser::OPERATOR_LEFT],'+'=>['precedence'=>30,'class'=>AddBinary::class,'associativity'=>ExpressionParser::OPERATOR_LEFT],'-'=>['precedence'=>30,'class'=>SubBinary::class,'associativity'=>ExpressionParser::OPERATOR_LEFT],'~'=>['precedence'=>40,'class'=>
                         ConcatBinary::class,'associativity'=>ExpressionParser::OPERATOR_LEFT],'*'=>['precedence'=>60,'class'=>MulBinary::class,'associativity'=>ExpressionParser::OPERATOR_LEFT],'/'=>['precedence'=>60,'class'=>DivBinary::class,'associativity'=>ExpressionParser::OPERATOR_LEFT],'//'=>[
                         'precedence'=>60,'class'=>FloorDivBinary::class,'associativity'=>ExpressionParser::OPERATOR_LEFT],'%'=>['precedence'=>60,'class'=>ModBinary::class,'associativity'=>ExpressionParser::OPERATOR_LEFT],'is'=>['precedence'=>100,'associativity'=>ExpressionParser::OPERATOR_LEFT],'is not'=>[
                         'precedence'=>100,'associativity'=>ExpressionParser::OPERATOR_LEFT],'**'=>['precedence'=>200,'class'=>PowerBinary::class,'associativity'=>ExpressionParser::OPERATOR_RIGHT],'??'=>['precedence'=>300,'class'=>NullCoalesceExpression::class,'associativity'=>ExpressionParser::OPERATOR_RIGHT]
                         ,],];}
         static function cycle($values,$position){if(!\is_array($values)&&!$values instanceof \ArrayAccess){return$values;}if(!\count($values)){throw new RuntimeError('The "cycle" function does not work on empty arrays');}return$values[$position % \count($values)];}
         static function random(Environment$env,$values=null,$max=null){if(null===$values){return null===$max? mt_rand():mt_rand(0,(int)$max);}if(\is_int($values)||\is_float($values)){if(null===$max){if($values<0){$max=0;$min=$values;}else{$max=$values;$min=0;}}else{$min=$values;}return mt_rand((int)$min,(int)
                         $max);}if(\is_string($values)){if(''===$values){return'';}$charset=$env->getCharset();if('UTF-8'!==$charset){$values=self::convertEncoding($values,'UTF-8',$charset);}$values=preg_split('/(?<!^)(?!$)/u',$values);if('UTF-8'!==$charset){foreach($values as$i=>$value){$values[$i]=self::
                         convertEncoding($value,$charset,'UTF-8');}}}if(!is_iterable($values)){return$values;}$values=self::toArray($values);if(0===\count($values)){throw new RuntimeError('The random function cannot pick from an empty array.');}return$values[array_rand($values,1)];}
         static function dateFormatFilter(Environment$env,$date,$format=null,$timezone=null){if(null===$format){$formats=$env->getExtension(self::class)->getDateFormat();$format=$date instanceof \DateInterval?$formats[1]:$formats[0];}if($date instanceof \DateInterval){return$date->format($format);}return self
                         ::dateConverter($env,$date,$timezone)->format($format);}
         static function dateModifyFilter(Environment$env,$date,$modifier):\DateTimeImmutable{return self::dateConverter($env,$date,false)->modify($modifier);}
         static function sprintf($format,...$values){return sprintf($format??'',...$values);}
         static function dateConverter(Environment$env,$date=null,$timezone=null):\DateTimeImmutable{if(false!==$timezone){if(null===$timezone){$timezone=$env->getExtension(self::class)->getTimezone();}elseif(!$timezone instanceof \DateTimeZone){$timezone=new \DateTimeZone($timezone);}}if($date instanceof
                         \DateTimeImmutable){return false!==$timezone?$date->setTimezone($timezone):$date;}if($date instanceof \DateTimeInterface){$date=\DateTimeImmutable::createFromInterface($date);if(false!==$timezone){return$date->setTimezone($timezone);}return$date;}if(null===$date||'now'===$date){if(null
                         ===$date){$date='now';}return new \DateTimeImmutable($date,false!==$timezone?$timezone:$env->getExtension(self::class)->getTimezone());}$asString=(string)$date;if(ctype_digit($asString)||(!empty($asString)&&'-'===$asString[0]&&ctype_digit(substr($asString,1)))){$date=new
                         \DateTimeImmutable('@'.$date);}else{$date=new \DateTimeImmutable($date,$env->getExtension(self::class)->getTimezone());}if(false!==$timezone){return$date->setTimezone($timezone);}return$date;}
         static function replaceFilter($str,$from){if(!is_iterable($from)){throw new RuntimeError(sprintf('The "replace" filter expects an array or "Traversable" as replace values,got "%s".',get_debug_type($from)));}return strtr($str??'',self::toArray($from));}
         static function round($value,$precision=0,$method='common'){$value=(float)$value;if('common'===$method){return round($value,$precision);}if('ceil'!==$method&&'floor'!==$method){throw new RuntimeError('The round filter only supports the "common","ceil",and "floor" methods.');}return$method($value*10**
                         $precision)/10**$precision;}
         static function numberFormatFilter(Environment$env,$number,$decimal=null,$decimalPoint=null,$thousandSep=null){$defaults=$env->getExtension(self::class)->getNumberFormat();if(null===$decimal){$decimal=$defaults[0];}if(null===$decimalPoint){$decimalPoint=$defaults[1];}if(null===$thousandSep){
                         $thousandSep=$defaults[2];}return number_format((float)$number,$decimal,$decimalPoint,$thousandSep);}
         static function urlencodeFilter($url){if(\is_array($url)){return http_build_query($url,'','&',\PHP_QUERY_RFC3986);}return rawurlencode($url??'');}
         static function arrayMerge(...$arrays){$result=[];foreach($arrays as$argNumber=>$array){if(!is_iterable($array)){throw new RuntimeError(sprintf('The merge filter only works with arrays or "Traversable",got "%s" for argument %d.',\gettype($array),$argNumber+1));}$result=array_merge($result,self::
                         toArray($array));}return$result;}
         static function slice(Environment$env,$item,$start,$length=null,$preserveKeys=false){if($item instanceof \Traversable){while($item instanceof \IteratorAggregate){$item=$item->getIterator();}if($start>= 0&&$length>= 0&&$item instanceof \Iterator){try{return iterator_to_array(new \LimitIterator($item,
                         $start,$length??-1),$preserveKeys);}catch(\OutOfBoundsException$e){return[];}}$item=iterator_to_array($item,$preserveKeys);}if(\is_array($item)){return \array_slice($item,$start,$length,$preserveKeys);}return mb_substr((string)$item,$start,$length,$env->getCharset());}
         static function first(Environment$env,$item){$elements=self::slice($env,$item,0,1,false);return \is_string($elements)?$elements:current($elements);}
         static function last(Environment$env,$item){$elements=self::slice($env,$item,-1,1,false);return \is_string($elements)?$elements:current($elements);}
         static function joinFilter($value,$glue='',$and=null){if(!is_iterable($value)){$value=(array)$value;}$value=self::toArray($value,false);if(0===\count($value)){return'';}if(null===$and||$and===$glue){return implode($glue,$value);}if(1===\count($value)){return$value[0];}return implode($glue,
                         \array_slice($value,0,-1)).$and.$value[\count($value)-1];}
         static function splitFilter(Environment$env,$value,$delimiter,$limit=null){$value=$value??'';if(''!==$delimiter){return null===$limit? explode($delimiter,$value):explode($delimiter,$value,$limit);}if($limit<=1){return preg_split('/(?<!^)(?!$)/u',$value);}$length=mb_strlen($value,$env->getCharset());if
                         ($length<$limit){return[$value];}$r=[];for($i=0;$i<$length;$i+=$limit){$r[]=mb_substr($value,$i,$limit,$env->getCharset());}return$r;}
         static function defaultFilter($value,$default=''){if(self::testEmpty($value)){return$default;}return$value;}
         static function getArrayKeysFilter($array){if($array instanceof \Traversable){while($array instanceof \IteratorAggregate){$array=$array->getIterator();}$keys=[];if($array instanceof \Iterator){$array->rewind();while($array->valid()){$keys[]=$array->key();$array->next();}return$keys;}foreach($array as
                         $key=>$item){$keys[]=$key;}return$keys;}if(!\is_array($array)){return[];}return array_keys($array);}
         static function reverseFilter(Environment$env,$item,$preserveKeys=false){if($item instanceof \Traversable){return array_reverse(iterator_to_array($item),$preserveKeys);}if(\is_array($item)){return array_reverse($item,$preserveKeys);}$string=(string)$item;$charset=$env->getCharset();if('UTF-8'!==
                         $charset){$string=self::convertEncoding($string,'UTF-8',$charset);}preg_match_all('/./us',$string,$matches);$string=implode('',array_reverse($matches[0]));if('UTF-8'!==$charset){$string=self::convertEncoding($string,$charset,'UTF-8');}return$string;}
         static function sortFilter(Environment$env,$array,$arrow=null){if($array instanceof \Traversable){$array=iterator_to_array($array);}elseif(!\is_array($array)){throw new RuntimeError(sprintf('The sort filter only works with arrays or "Traversable",got "%s".',\gettype($array)));}if(null!==$arrow){self::
                         checkArrowInSandbox($env,$arrow,'sort','filter');uasort($array,$arrow);}else{asort($array);}return$array;}
         static function inFilter($value,$compare){if($value instanceof Markup){$value=(string)$value;}if($compare instanceof Markup){$compare=(string)$compare;}if(\is_string($compare)){if(\is_string($value)||\is_int($value)||\is_float($value)){return''===$value||str_contains($compare,(string)$value);}return
                         false;}if(!is_iterable($compare)){return false;}if(\is_object($value)||\is_resource($value)){if(!\is_array($compare)){foreach($compare as$item){if($item===$value){return true;}}return false;}return \in_array($value,$compare,true);}foreach($compare as$item){if(0===self::compare($value,
                         $item)){return true;}}return false;}
         static function compare($a,$b){if(\is_int($a)&&\is_string($b)){$bTrim=trim($b," \t\n\r\v\f");if(!is_numeric($bTrim)){return(string)$a<=>$b;}if((int)$bTrim==$bTrim){return$a<=>(int)$bTrim;}else{return(float)$a<=>(float)$bTrim;}}if(\is_string($a)&&\is_int($b)){$aTrim=trim($a,"\t\n\r\v\f");if(!is_numeric
                         ($aTrim)){return$a<=>(string)$b;}if((int)$aTrim==$aTrim){return(int)$aTrim<=>$b;}else{return(float)$aTrim<=>(float)$b;}}if(\is_float($a)&&\is_string($b)){if(is_nan($a)){return 1;}$bTrim=trim($b," \t\n\r\v\f");if(!is_numeric($bTrim)){return(string)$a<=>$b;}return$a<=>(float)$bTrim;}if(
                         \is_string($a)&&\is_float($b)){if(is_nan($b)){return 1;}$aTrim=trim($a," \t\n\r\v\f");if(!is_numeric($aTrim)){return$a<=>(string)$b;}return(float)$aTrim<=>$b;}return$a<=>$b;}
         static function matches(string$regexp,?string$str){set_error_handler(function($t,$m)use($regexp){throw new RuntimeError(sprintf('Regexp "%s" passed to "matches" is not valid',$regexp).substr($m,12));});try{return preg_match($regexp,$str??'');}finally{restore_error_handler();}}
         static function trimFilter($string,$characterMask=null,$side='both'){if(null===$characterMask){$characterMask=" \t\n\r\0\x0B";}return match($side){'both'=>trim($string??'',$characterMask),'left'=>ltrim($string??'',$characterMask),'right'=>rtrim($string??'',$characterMask),default=>throw new
                         RuntimeError('Trimming side must be "left","right" or "both".'),};}
         static function nl2br($string){return nl2br($string??'');}
         static function spaceless($content){return trim(preg_replace('/>\s+</','><',$content??''));}
         static function convertEncoding($string,$to,$from){if(!\function_exists('iconv')){throw new RuntimeError('Unable to convert encoding: required function iconv()does not exist. You should install ext-iconv or symfony/polyfill-iconv.');}return iconv($from,$to,$string??'');}
         static function lengthFilter(Environment$env,$thing){if(null===$thing){return 0;}if(\is_scalar($thing)){return mb_strlen($thing,$env->getCharset());}if(is_countable($thing)||$thing instanceof \SimpleXMLElement){return \count($thing);}if($thing instanceof \Traversable){return iterator_count($thing);}if
                         (method_exists($thing,'__toString')){return mb_strlen((string)$thing,$env->getCharset());}return 1;}
         static function upperFilter(Environment$env,$string){return mb_strtoupper($string??'',$env->getCharset());}
         static function lowerFilter(Environment$env,$string){return mb_strtolower($string??'',$env->getCharset());}
         static function striptags($string,$allowable_tags=null){return strip_tags($string??'',$allowable_tags);}
         static function titleStringFilter(Environment$env,$string){return mb_convert_case($string??'',\MB_CASE_TITLE,$env->getCharset());}
         static function capitalizeStringFilter(Environment$env,$string){$charset=$env->getCharset();return mb_strtoupper(mb_substr($string??'',0,1,$charset),$charset).mb_strtolower(mb_substr($string??'',1,null,$charset),$charset);}
         static function callMacro(Template$template,string$method,array$args,int$lineno,array$context,Source$source){if(!method_exists($template,$method)){$parent=$template;while($parent=$parent->getParent($context)){if(method_exists($parent,$method)){return$parent->$method(...$args);}}throw new RuntimeError(
                         sprintf('Macro "%s" is not defined in template "%s".',substr($method,\strlen('macro_')),$template->getTemplateName()),$lineno,$source);}return$template->$method(...$args);}
         static function ensureTraversable($seq){if(is_iterable($seq)){return$seq;}return[];}
         static function toArray($seq,$preserveKeys=true){if($seq instanceof \Traversable){return iterator_to_array($seq,$preserveKeys);}if(!\is_array($seq)){return$seq;}return$preserveKeys?$seq:array_values($seq);}
         static function testEmpty($value){if($value instanceof \Countable){return 0===\count($value);}if($value instanceof \Traversable){return !iterator_count($value);}if(\is_object($value)&&method_exists($value,'__toString')){return''===(string)$value;}return''===$value||false===$value||null===$value||[]===
                         $value;}
         static function include(Environment$env,$context,$template,$variables=[],$withContext=true,$ignoreMissing=false,$sandboxed=false){$alreadySandboxed=false;$sandbox=null;if($withContext){$variables=array_merge($context,$variables);}if($isSandboxed=$sandboxed&&$env->hasExtension(SandboxExtension::class))
                        {$sandbox=$env->getExtension(SandboxExtension::class);if(!$alreadySandboxed=$sandbox->isSandboxed()){$sandbox->enableSandbox();}foreach((\is_array($template)?$template:[$template])as$name){if($name instanceof TemplateWrapper||$name instanceof Template){$name->unwrap()->checkSecurity()
                         ;}}}try{$loaded=null;try{$loaded=$env->resolveTemplate($template);}catch(LoaderError$e){if(!$ignoreMissing){throw$e;}}return$loaded?$loaded->render($variables):'';}finally{if($isSandboxed&&!$alreadySandboxed){$sandbox->disableSandbox();}}}
         static function source(Environment$env,$name,$ignoreMissing=false){$loader=$env->getLoader();try{return$loader->getSourceContext($name)->getCode();}catch(LoaderError$e){if(!$ignoreMissing){throw$e;}return'';}}
         static function constant($constant,$object=null){if(null!==$object){if('class'===$constant){return$object::class;}$constant=$object::class.'::'.$constant;}if(!\defined($constant)){if('::class'===strtolower(substr($constant,-7))){throw new RuntimeError(sprintf(
                         'You cannot use the Twig function "constant()" to access "%s". You could provide an object and call constant("class",$object)or use the class name directly as a string.',$constant));}throw new RuntimeError(sprintf('Constant "%s" is undefined.',$constant));}return \constant($constant);
                         }
         static function constantIsDefined($constant,$object=null){if(null!==$object){if('class'===$constant){return true;}$constant=$object::class.'::'.$constant;}return \defined($constant);}
         static function arrayBatch($items,$size,$fill=null,$preserveKeys=true){if(!is_iterable($items)){throw new RuntimeError(sprintf('The "batch" filter expects an array or "Traversable",got "%s".',get_debug_type($items)));}$size=ceil($size);$result=array_chunk(self::toArray($items,$preserveKeys),$size,
                         $preserveKeys);if(null!==$fill&&$result){$last=\count($result)-1;if($fillCount=$size-\count($result[$last])){for($i=0;$i<$fillCount; ++$i){$result[$last][]=$fill;}}}return$result;}
         static function getAttribute(Environment$env,Source$source,$object,$item,array$arguments=[],$type='any',$isDefinedTest=false,$ignoreStrictCheck=false,$sandboxed=false,int$lineno=-1){if('method'!==$type){$arrayItem=\is_bool($item)||\is_float($item)?(int)$item:$item;if(((\is_array($object)||$object
                         instanceof \ArrayObject)&&(isset($object[$arrayItem])||\array_key_exists($arrayItem,(array)$object)))||($object instanceof \ArrayAccess&&isset($object[$arrayItem]))){if($isDefinedTest){return true;}return$object[$arrayItem];}if('array'===$type||!\is_object($object)){if($isDefinedTest){
                         return false;}if($ignoreStrictCheck||!$env->isStrictVariables()){return;}if($object instanceof \ArrayAccess){$message=sprintf('Key "%s" in object with ArrayAccess of class "%s" does not exist.',$arrayItem,$object::class);}elseif(\is_object($object)){$message=sprintf(
                         'Impossible to access a key "%s" on an object of class "%s" that does not implement ArrayAccess interface.',$item,$object::class);}elseif(\is_array($object)){if(empty($object)){$message=sprintf('Key "%s" does not exist as the array is empty.',$arrayItem);}else{$message=sprintf(
                         'Key "%s" for array with keys "%s" does not exist.',$arrayItem,implode(',',array_keys($object)));}}elseif('array'===$type){if(null===$object){$message=sprintf('Impossible to access a key("%s")on a null variable.',$item);}else{$message=sprintf(
                         'Impossible to access a key("%s")on a %s variable("%s").',$item,\gettype($object),$object);}}elseif(null===$object){$message=sprintf('Impossible to access an attribute("%s")on a null variable.',$item);}else{$message=sprintf(
                         'Impossible to access an attribute("%s")on a %s variable("%s").',$item,\gettype($object),$object);}throw new RuntimeError($message,$lineno,$source);}}if(!\is_object($object)){if($isDefinedTest){return false;}if($ignoreStrictCheck||!$env->isStrictVariables()){return;}if(null===$object)
                        {$message=sprintf('Impossible to invoke a method("%s")on a null variable.',$item);}elseif(\is_array($object)){$message=sprintf('Impossible to invoke a method("%s")on an array.',$item);}else{$message=sprintf('Impossible to invoke a method("%s")on a %s variable("%s").',$item,\gettype
                         ($object),$object);}throw new RuntimeError($message,$lineno,$source);}if($object instanceof Template){throw new RuntimeError('Accessing \Twig\Template attributes is forbidden.',$lineno,$source);}if('method'!==$type){if(isset($object->$item)||\array_key_exists((string)$item,(array)
                         $object)){if($isDefinedTest){return true;}if($sandboxed){$env->getExtension(SandboxExtension::class)->checkPropertyAllowed($object,$item,$lineno,$source);}return$object->$item;}}static$cache=[];$class=$object::class;if(!isset($cache[$class])){$methods=get_class_methods($object);sort(
                         $methods);$lcMethods=array_map(fn($value)=>strtr($value,'ABCDEFGHIJKLMNOPQRSTUVWXYZ','abcdefghijklmnopqrstuvwxyz'),$methods);$classCache=[];foreach($methods as$i=>$method){$classCache[$method]=$method;$classCache[$lcName=$lcMethods[$i]]=$method;if('g'===$lcName[0]&&str_starts_with(
                         $lcName,'get')){$name=substr($method,3);$lcName=substr($lcName,3);}elseif('i'===$lcName[0]&&str_starts_with($lcName,'is')){$name=substr($method,2);$lcName=substr($lcName,2);}elseif('h'===$lcName[0]&&str_starts_with($lcName,'has')){$name=substr($method,3);$lcName=substr($lcName,3);if(
                         \in_array('is'.$lcName,$lcMethods)){continue;}}else{continue;}if($name){if(!isset($classCache[$name])){$classCache[$name]=$method;}if(!isset($classCache[$lcName])){$classCache[$lcName]=$method;}}}$cache[$class]=$classCache;}$call=false;if(isset($cache[$class][$item])){
                         $method=$cache[$class][$item];}elseif(isset($cache[$class][$lcItem=strtr($item,'ABCDEFGHIJKLMNOPQRSTUVWXYZ','abcdefghijklmnopqrstuvwxyz')])){$method=$cache[$class][$lcItem];}elseif(isset($cache[$class]['__call'])){$method=$item;$call=true;}else{if($isDefinedTest){return false;}if(
                         $ignoreStrictCheck||!$env->isStrictVariables()){return;}throw new RuntimeError(sprintf('Neither the property "%1$s" nor one of the methods "%1$s()","get%1$s()"/"is%1$s()"/"has%1$s()" or "__call()" exist and have public access in class "%2$s".',$item,$class),$lineno,$source);}if(
                         $isDefinedTest){return true;}if($sandboxed){$env->getExtension(SandboxExtension::class)->checkMethodAllowed($object,$method,$lineno,$source);}try{$ret=$object->$method(...$arguments);}catch(\BadMethodCallException$e){if($call&&($ignoreStrictCheck||!$env->isStrictVariables())){return;}
                         throw$e;}return$ret;}
         static function arrayColumn($array,$name,$index=null):array{if($array instanceof \Traversable){$array=iterator_to_array($array);}elseif(!\is_array($array)){throw new RuntimeError(sprintf('The column filter only works with arrays or "Traversable",got "%s" as first argument.',\gettype($array)));}return
                         array_column($array,$name,$index);}
         static function arrayFilter(Environment$env,$array,$arrow){if(!is_iterable($array)){throw new RuntimeError(sprintf('The "filter" filter expects an array or "Traversable",got "%s".',get_debug_type($array)));}self::checkArrowInSandbox($env,$arrow,'filter','filter');if(\is_array($array)){return
                         array_filter($array,$arrow,\ARRAY_FILTER_USE_BOTH);}return new \CallbackFilterIterator(new \IteratorIterator($array),$arrow);}
         static function arrayMap(Environment$env,$array,$arrow){self::checkArrowInSandbox($env,$arrow,'map','filter');$r=[];foreach($array as$k=>$v){$r[$k]=$arrow($v,$k);}return$r;}
         static function arrayReduce(Environment$env,$array,$arrow,$initial=null){self::checkArrowInSandbox($env,$arrow,'reduce','filter');if(!\is_array($array)&&!$array instanceof \Traversable){throw new RuntimeError(sprintf(
                         'The "reduce" filter only works with arrays or "Traversable",got "%s" as first argument.',\gettype($array)));}$accumulator=$initial;foreach($array as$key=>$value){$accumulator=$arrow($accumulator,$value,$key);}return$accumulator;}
         static function arraySome(Environment$env,$array,$arrow){self::checkArrowInSandbox($env,$arrow,'has some','operator');foreach($array as$k=>$v){if($arrow($v,$k)){return true;}}return false;}
         static function arrayEvery(Environment$env,$array,$arrow){self::checkArrowInSandbox($env,$arrow,'has every','operator');foreach($array as$k=>$v){if(!$arrow($v,$k)){return false;}}return true;}
         static function checkArrowInSandbox(Environment$env,$arrow,$thing,$type){if(!$arrow instanceof \Closure&&$env->hasExtension(\Twig\Extension\SandboxExtension::class)&&$env->getExtension(\Twig\Extension\SandboxExtension::class)->isSandboxed()){throw new RuntimeError(sprintf(
                         'The callable passed to the "%s" %s must be a Closure in sandbox mode.',$thing,$type));}}}
class Environment       {const VERSION='Twig 4.0 / In1File 4.0319';const VERSION_ID=40000;const MAJOR_VERSION=4;const MINOR_VERSION=0;const RELEASE_VERSION=0;const EXTRA_VERSION='DEV';private string$charset;private LoaderInterface$loader;private bool$debug;private bool$autoReload;private CacheInterface|string|
                         false$cache;private?Lexer$lexer=null;private?Parser$parser=null;private?Compiler$compiler=null;private array$globals=[];private?array$resolvedGlobals=null;private array$loadedTemplates;private bool$strictVariables;private string$templateClassPrefix='__TwigTemplate_';private
                         CacheInterface|string|false$originalCache;private ExtensionSet$extensionSet;private array$runtimeLoaders=[];private array$runtimes=[];private string$optionsHash;
                function __construct(LoaderInterface$loader,$options=[]){$this->setLoader($loader);$options=array_merge(['debug'=>false,'charset'=>'UTF-8','strict_variables'=>false,'autoescape'=>'html','cache'=>false,'auto_reload'=>null,'optimizations'=>-1,],$options);$this->debug=(bool)$options['debug'];$this
                         ->setCharset($options['charset']??'UTF-8');$this->autoReload=null===$options['auto_reload']?$this->debug:(bool)$options['auto_reload'];$this->strictVariables=(bool)$options['strict_variables'];$this->setCache($options['cache']);$this->extensionSet=new ExtensionSet();$this->addExtension
                         (new CoreExtension());$this->addExtension(new EscaperExtension($options['autoescape']));$this->addExtension(new OptimizerExtension($options['optimizations']));}
                function enableDebug(){$this->debug=true;$this->updateOptionsHash();}
                function disableDebug(){$this->debug=false;$this->updateOptionsHash();}
                function isDebug(){return$this->debug;}
                function enableAutoReload(){$this->autoReload=true;}
                function disableAutoReload(){$this->autoReload=false;}
                function isAutoReload(){return$this->autoReload;}
                function enableStrictVariables(){$this->strictVariables=true;$this->updateOptionsHash();}
                function disableStrictVariables(){$this->strictVariables=false;$this->updateOptionsHash();}
                function isStrictVariables(){return$this->strictVariables;}
                function getCache($original=true){return$original?$this->originalCache:$this->cache;}
                function setCache($cache){if(\is_string($cache)){$this->originalCache=$cache;$this->cache=new FilesystemCache($cache,$this->autoReload? FilesystemCache::FORCE_BYTECODE_INVALIDATION:0);}elseif(false===$cache){$this->originalCache=$cache;$this->cache=new NullCache();}elseif($cache instanceof
                         CacheInterface){$this->originalCache=$this->cache=$cache;}else{throw new \LogicException('Cache can only be a string,false,or a \Twig\Cache\CacheInterface implementation.');}}
                function getTemplateClass(string$name,?int$index=null):string{$key=$this->getLoader()->getCacheKey($name).$this->optionsHash;return$this->templateClassPrefix.hash('xxh128',$key).(null===$index?'':'___'.$index);}
                function render($name,array$context=[]):string{return$this->load($name)->render($context);}
                function display($name,array$context=[]):void{$this->load($name)->display($context);}
                function load($name):TemplateWrapper{if($name instanceof TemplateWrapper){return$name;}return new TemplateWrapper($this,$this->loadTemplate($this->getTemplateClass($name),$name));}
                function loadTemplate(string$cls,string$name,?int$index=null):Template{$mainCls=$cls;if(null!==$index){$cls.='___'.$index;}if(isset($this->loadedTemplates[$cls])){return$this->loadedTemplates[$cls];}if(!class_exists($cls,false)){$key=$this->cache->generateKey($name,$mainCls);if(!$this->
                         isAutoReload()||$this->isTemplateFresh($name,$this->cache->getTimestamp($key))){$this->cache->load($key);}if(!class_exists($cls,false)){$source=$this->getLoader()->getSourceContext($name);$content=$this->compileSource($source);$this->cache->write($key,$content);$this->cache->load($key)
                         ;if(!class_exists($mainCls,false)){eval('?>'.$content);}if(!class_exists($cls,false)){throw new RuntimeError(sprintf('Failed to load Twig template "%s",index "%s": cache might be corrupted.',$name,$index),-1,$source);}}}$this->extensionSet->initRuntime();return$this->loadedTemplates[
                         $cls]=new$cls($this);}
                function createTemplate(string$template,?string$name=null):TemplateWrapper{$hash=hash('xxh128',$template,false);if(null!==$name){$name=sprintf('%s(string template %s)',$name,$hash);}else{$name=sprintf('__string_template__%s',$hash);}$loader=new ChainLoader([new ArrayLoader([$name=>$template]),
                         $current=$this->getLoader(),]);$this->setLoader($loader);try{return new TemplateWrapper($this,$this->loadTemplate($this->getTemplateClass($name),$name));}finally{$this->setLoader($current);}}
                function isTemplateFresh(string$name,int$time):bool{return$this->extensionSet->getLastModified()<=$time&&$this->getLoader()->isFresh($name,$time);}
                function resolveTemplate($names):TemplateWrapper{if(!\is_array($names)){return$this->load($names);}$count=\count($names);foreach($names as$name){if($name instanceof Template){return new TemplateWrapper($this,$name);}if($name instanceof TemplateWrapper){return$name;}if(1!==$count&&!$this->
                         getLoader()->exists($name)){continue;}return$this->load($name);}throw new LoaderError(sprintf('Unable to find one of the following templates: "%s".',implode('","',$names)));}
                function setLexer(Lexer$lexer){$this->lexer=$lexer;}
                function tokenize(Source$source):TokenStream{if(null===$this->lexer){$this->lexer=new Lexer($this);}return$this->lexer->tokenize($source);}
                function setParser(Parser$parser){$this->parser=$parser;}
                function parse(TokenStream$stream):ModuleNode{if(null===$this->parser){$this->parser=new Parser($this);}return$this->parser->parse($stream);}
                function setCompiler(Compiler$compiler){$this->compiler=$compiler;}
                function compile(Node$node):string{if(null===$this->compiler){$this->compiler=new Compiler($this);}return$this->compiler->compile($node)->getSource();}
                function compileSource(Source$source):string{try{return$this->compile($this->parse($this->tokenize($source)));}catch(Error$e){$e->setSourceContext($source);throw$e;}catch(\Exception$e){throw new SyntaxError(sprintf('An exception has been thrown during the compilation of a template("%s").',$e
                         ->getMessage()),-1,$source,$e);}}
                function setLoader(LoaderInterface$loader){$this->loader=$loader;}
                function getLoader():LoaderInterface{return$this->loader;}
                function setCharset(string$charset){if('UTF8'===$charset=strtoupper($charset?: '')){$charset='UTF-8';}$this->charset=$charset;}
                function getCharset():string{return$this->charset;}
                function hasExtension(string$class):bool{return$this->extensionSet->hasExtension($class);}
                function addRuntimeLoader(RuntimeLoaderInterface$loader){$this->runtimeLoaders[]=$loader;}
                function getExtension(string$class):ExtensionInterface{return$this->extensionSet->getExtension($class);}
                function getRuntime(string$class){if(isset($this->runtimes[$class])){return$this->runtimes[$class];}foreach($this->runtimeLoaders as$loader){if(null!==$runtime=$loader->load($class)){return$this->runtimes[$class]=$runtime;}}throw new RuntimeError(sprintf('Unable to load the "%s" runtime.',
                         $class));}
                function addExtension(ExtensionInterface$extension){$this->extensionSet->addExtension($extension);$this->updateOptionsHash();}
                function setExtensions(array$extensions){$this->extensionSet->setExtensions($extensions);$this->updateOptionsHash();}
                function getExtensions():array{return$this->extensionSet->getExtensions();}
                function addTokenParser(TokenParserInterface$parser){$this->extensionSet->addTokenParser($parser);}
                function getTokenParsers():array{return$this->extensionSet->getTokenParsers();}
                function getTokenParser(string$name):?TokenParserInterface{return$this->extensionSet->getTokenParser($name);}
                function registerUndefinedTokenParserCallback(callable$callable):void{$this->extensionSet->registerUndefinedTokenParserCallback($callable);}
                function addNodeVisitor(NodeVisitorInterface$visitor){$this->extensionSet->addNodeVisitor($visitor);}
                function getNodeVisitors():array{return$this->extensionSet->getNodeVisitors();}
                function addFilter(TwigFilter$filter){$this->extensionSet->addFilter($filter);}
                function getFilter(string$name):?TwigFilter{return$this->extensionSet->getFilter($name);}
                function registerUndefinedFilterCallback(callable$callable):void{$this->extensionSet->registerUndefinedFilterCallback($callable);}
                function getFilters():array{return$this->extensionSet->getFilters();}
                function addTest(TwigTest$test){$this->extensionSet->addTest($test);}
                function getTests():array{return$this->extensionSet->getTests();}
                function getTest(string$name):?TwigTest{return$this->extensionSet->getTest($name);}
                function addFunction(TwigFunction$function){$this->extensionSet->addFunction($function);}
                function getFunction(string$name):?TwigFunction{return$this->extensionSet->getFunction($name);}
                function registerUndefinedFunctionCallback(callable$callable):void{$this->extensionSet->registerUndefinedFunctionCallback($callable);}
                function getFunctions():array{return$this->extensionSet->getFunctions();}
                function addGlobal(string$name,$value){if($this->extensionSet->isInitialized()&&!\array_key_exists($name,$this->getGlobals())){throw new \LogicException(sprintf('Unable to add global "%s" as the runtime or the extensions have already been initialized.',$name));}if(null!==$this->resolvedGlobals)
                        {$this->resolvedGlobals[$name]=$value;}else{$this->globals[$name]=$value;}}
                function getGlobals():array{if($this->extensionSet->isInitialized()){if(null===$this->resolvedGlobals){$this->resolvedGlobals=array_merge($this->extensionSet->getGlobals(),$this->globals);}return$this->resolvedGlobals;}return array_merge($this->extensionSet->getGlobals(),$this->globals);}
                function mergeGlobals(array$context):array{foreach($this->getGlobals()as$key=>$value){if(!\array_key_exists($key,$context)){$context[$key]=$value;}}return$context;}
                function getUnaryOperators():array{return$this->extensionSet->getUnaryOperators();}
                function getBinaryOperators():array{return$this->extensionSet->getBinaryOperators();}
        private function updateOptionsHash():void{$this->optionsHash=implode(':',[$this->extensionSet->getSignature(),\PHP_MAJOR_VERSION,\PHP_MINOR_VERSION,self::VERSION,(int)$this->debug,(int)$this->strictVariables,]);}}
class FilesystemCache    implements CacheInterface{const FORCE_BYTECODE_INVALIDATION=1;private string$directory;private int$options;
                function __construct(string$directory,int$options=0){$this->directory=rtrim($directory,'\/').'/';$this->options=$options;}
                function generateKey(string$name,string$className):string{$hash=hash('xxh128',$className);return$this->directory.$hash[0].$hash[1].'/'.$hash.'.php';}
                function load(string$key):void{if(is_file($key)){@include_once$key;}}
                function write(string$key,string$content):void{$dir=\dirname($key);if(!is_dir($dir)){if(false===@mkdir($dir,0777,true)){clearstatcache(true,$dir);if(!is_dir($dir)){throw new \RuntimeException(sprintf('Unable to create the cache directory(%s).',$dir));}}}elseif(!is_writable($dir)){throw new
                         \RuntimeException(sprintf('Unable to write in the cache directory(%s).',$dir));}$tmpFile=tempnam($dir,basename($key));if(false!==@file_put_contents($tmpFile,$content)&&@rename($tmpFile,$key)){@chmod($key,0666&~umask());if(self::FORCE_BYTECODE_INVALIDATION==($this->options&self::
                         FORCE_BYTECODE_INVALIDATION)){if(\function_exists('opcache_invalidate')&&filter_var(\ini_get('opcache.enable'),\FILTER_VALIDATE_BOOLEAN)){@opcache_invalidate($key,true);}elseif(\function_exists('apc_compile_file')){apc_compile_file($key);}}return;}throw new \RuntimeException(sprintf(
                         'Failed to write cache file "%s".',$key));}
                function getTimestamp(string$key):int{if(!is_file($key)){return 0;}return(int)@filemtime($key);}}
interface CacheInterface{
                function generateKey(string$name,string$className):string;
                function write(string$key,string$content):void;
                function load(string$key):void;
                function getTimestamp(string$key):int;}
#[YieldReady]
class Node               implements \Countable,\IteratorAggregate{protected$nodes;protected$attributes;protected$lineno;protected$tag;private?Source$sourceContext=null;
                function __construct(array$nodes=[],array$attributes=[],int$lineno=0,?string$tag=null){foreach($nodes as$name=>$node){if(!$node instanceof self){throw new\InvalidArgumentException(sprintf('Using "%s" for the value of node "%s" of "%s" is not supported. You must pass a \Twig\Node\Node instance.'
                         ,\is_object($node)?$node::class:(null===$node?'null':\gettype($node)),$name,static::class));}}$this->nodes=$nodes;$this->attributes=$attributes;$this->lineno=$lineno;$this->tag=$tag;}
                function __toString(){$attributes=[];foreach($this->attributes as$name=>$value){$attributes[]=sprintf('%s: %s',$name,str_replace("\n",'',var_export($value,true)));}$repr=[static::class.'('.implode(',',$attributes)];if(\count($this->nodes)){foreach($this->nodes as$name=>$node){$len=\strlen($name
                         )+4;$noderepr=[];foreach(explode("\n",(string)$node)as$line){$noderepr[]=str_repeat(' ',$len).$line;}$repr[]=sprintf('  %s: %s',$name,ltrim(implode("\n",$noderepr)));}$repr[]=')';}else{$repr[0].=')';}return implode("\n",$repr);}
                function compile(Compiler$compiler){foreach($this->nodes as$node){$compiler->subcompile($node);}}
                function getTemplateLine():int{return$this->lineno;}
                function getNodeTag():?string{return$this->tag;}
                function hasAttribute(string$name):bool{return \array_key_exists($name,$this->attributes);}
                function getAttribute(string$name){if(!\array_key_exists($name,$this->attributes)){throw new \LogicException(sprintf('Attribute "%s" does not exist for Node "%s".',$name,static::class));}return$this->attributes[$name];}
                function setAttribute(string$name,$value):void{$this->attributes[$name]=$value;}
                function removeAttribute(string$name):void{unset($this->attributes[$name]);}
                function hasNode(string$name):bool{return isset($this->nodes[$name]);}
                function getNode(string$name):self{if(!isset($this->nodes[$name])){throw new \LogicException(sprintf('Node "%s" does not exist for Node "%s".',$name,static::class));}return$this->nodes[$name];}
                function setNode(string$name,self$node):void{$this->nodes[$name]=$node;}
                function removeNode(string$name):void{unset($this->nodes[$name]);}
                function count():int{return \count($this->nodes);}
                function getIterator():\Traversable{return new \ArrayIterator($this->nodes);}
                function getTemplateName():?string{return$this->sourceContext?$this->sourceContext->getName():null;}
                function setSourceContext(Source$source):void{$this->sourceContext=$source;foreach($this->nodes as$node){$node->setSourceContext($source);}}
                function getSourceContext():?Source{return$this->sourceContext;}}
class StagingExtension   extends AbstractExtension{private array$functions=[];private array$filters=[];private array$visitors=[];private array$tokenParsers=[];private array$tests=[];
                function addFunction(TwigFunction$function):void{if(isset($this->functions[$function->getName()])){throw new \LogicException(sprintf('Function "%s" is already registered.',$function->getName()));}$this->functions[$function->getName()]=$function;}
                function getFunctions():array{return$this->functions;}
                function addFilter(TwigFilter$filter):void{if(isset($this->filters[$filter->getName()])){throw new \LogicException(sprintf('Filter "%s" is already registered.',$filter->getName()));}$this->filters[$filter->getName()]=$filter;}
                function getFilters():array{return$this->filters;}
                function addNodeVisitor(NodeVisitorInterface$visitor):void{$this->visitors[]=$visitor;}
                function getNodeVisitors():array{return$this->visitors;}
                function addTokenParser(TokenParserInterface$parser):void{if(isset($this->tokenParsers[$parser->getTag()])){throw new \LogicException(sprintf('Tag "%s" is already registered.',$parser->getTag()));}$this->tokenParsers[$parser->getTag()]=$parser;}
                function getTokenParsers():array{return$this->tokenParsers;}
                function addTest(TwigTest$test):void{if(isset($this->tests[$test->getName()])){throw new \LogicException(sprintf('Test "%s" is already registered.',$test->getName()));}$this->tests[$test->getName()]=$test;}
                function getTests():array{return$this->tests;}}
class ExtensionSet      {private array$extensions;private bool$initialized=false;private bool$runtimeInitialized=false;private$staging;private array$parsers;private array$visitors;private array$filters;private array$tests;private array$functions;private array$unaryOperators;private array$binaryOperators;
                         private?array$globals=null;private array$functionCallbacks=[];private array$filterCallbacks=[];private array$parserCallbacks=[];private int$lastModified=0;
                function __construct(){$this->staging=new StagingExtension();}
                function initRuntime(){$this->runtimeInitialized=true;}
                function hasExtension(string$class):bool{return isset($this->extensions[ltrim($class,'\\')]);}
                function getExtension(string$class):ExtensionInterface{$class=ltrim($class,'\\');if(!isset($this->extensions[$class])){throw new RuntimeError(sprintf('The "%s" extension is not enabled.',$class));}return$this->extensions[$class];}
                function setExtensions(array$extensions):void{foreach($extensions as$extension){$this->addExtension($extension);}}
                function getExtensions():array{return$this->extensions;}
                function getSignature():string{return json_encode(array_keys($this->extensions));}
                function isInitialized():bool{return$this->initialized||$this->runtimeInitialized;}
                function getLastModified():int{if(0!==$this->lastModified){return$this->lastModified;}foreach($this->extensions as$extension){$r=new \ReflectionObject($extension);if(is_file($r->getFileName())&&($extensionTime=filemtime($r->getFileName()))>$this->lastModified){$this->lastModified=
                         $extensionTime;}}return$this->lastModified;}
                function addExtension(ExtensionInterface$extension):void{$class=$extension::class;if($this->initialized){throw new \LogicException(sprintf('Unable to register extension "%s" as extensions have already been initialized.',$class));}if(isset($this->extensions[$class])){throw new \LogicException(
                         sprintf('Unable to register extension "%s" as it is already registered.',$class));}$this->extensions[$class]=$extension;}
                function addFunction(TwigFunction$function):void{if($this->initialized){throw new \LogicException(sprintf('Unable to add function "%s" as extensions have already been initialized.',$function->getName()));}$this->staging->addFunction($function);}
                function getFunctions():array{if(!$this->initialized){$this->initExtensions();}return$this->functions;}
                function getFunction(string$name):?TwigFunction{if(!$this->initialized){$this->initExtensions();}if(isset($this->functions[$name])){return$this->functions[$name];}foreach($this->functions as$pattern=>$function){$pattern=str_replace('\\*','(.*?)',preg_quote($pattern,'#'),$count);if($count&&
                         preg_match('#^'.$pattern.'$#',$name,$matches)){array_shift($matches);$function->setArguments($matches);return$function;}}foreach($this->functionCallbacks as$callback){if(false!==$function=$callback($name)){return$function;}}return null;}
                function registerUndefinedFunctionCallback(callable$callable):void{$this->functionCallbacks[]=$callable;}
                function addFilter(TwigFilter$filter):void{if($this->initialized){throw new \LogicException(sprintf('Unable to add filter "%s" as extensions have already been initialized.',$filter->getName()));}$this->staging->addFilter($filter);}
                function getFilters():array{if(!$this->initialized){$this->initExtensions();}return$this->filters;}
                function getFilter(string$name):?TwigFilter{if(!$this->initialized){$this->initExtensions();}if(isset($this->filters[$name])){return$this->filters[$name];}foreach($this->filters as$pattern=>$filter){$pattern=str_replace('\\*','(.*?)',preg_quote($pattern,'#'),$count);if($count&&preg_match('#^'.
                         $pattern.'$#',$name,$matches)){array_shift($matches);$filter->setArguments($matches);return$filter;}}foreach($this->filterCallbacks as$callback){if(false!==$filter=$callback($name)){return$filter;}}return null;}
                function registerUndefinedFilterCallback(callable$callable):void{$this->filterCallbacks[]=$callable;}
                function addNodeVisitor(NodeVisitorInterface$visitor):void{if($this->initialized){throw new \LogicException('Unable to add a node visitor as extensions have already been initialized.');}$this->staging->addNodeVisitor($visitor);}
                function getNodeVisitors():array{if(!$this->initialized){$this->initExtensions();}return$this->visitors;}
                function addTokenParser(TokenParserInterface$parser):void{if($this->initialized){throw new \LogicException('Unable to add a token parser as extensions have already been initialized.');}$this->staging->addTokenParser($parser);}
                function getTokenParsers():array{if(!$this->initialized){$this->initExtensions();}return$this->parsers;}
                function getTokenParser(string$name):?TokenParserInterface{if(!$this->initialized){$this->initExtensions();}if(isset($this->parsers[$name])){return$this->parsers[$name];}foreach($this->parserCallbacks as$callback){if(false!==$parser=$callback($name)){return$parser;}}return null;}
                function registerUndefinedTokenParserCallback(callable$callable):void{$this->parserCallbacks[]=$callable;}
                function getGlobals():array{if(null!==$this->globals){return$this->globals;}$globals=[];foreach($this->extensions as$extension){if(!$extension instanceof GlobalsInterface){continue;}$extGlobals=$extension->getGlobals();if(!\is_array($extGlobals)){throw new \UnexpectedValueException(sprintf(
                         '"%s::getGlobals()" must return an array of globals.',$extension::class));}$globals=array_merge($globals,$extGlobals);}if($this->initialized){$this->globals=$globals;}return$globals;}
                function addTest(TwigTest$test):void{if($this->initialized){throw new \LogicException(sprintf('Unable to add test "%s" as extensions have already been initialized.',$test->getName()));}$this->staging->addTest($test);}
                function getTests():array{if(!$this->initialized){$this->initExtensions();}return$this->tests;}
                function getTest(string$name):?TwigTest{if(!$this->initialized){$this->initExtensions();}if(isset($this->tests[$name])){return$this->tests[$name];}foreach($this->tests as$pattern=>$test){$pattern=str_replace('\\*','(.*?)',preg_quote($pattern,'#'),$count);if($count){if(preg_match('#^'.$pattern.
                         '$#',$name,$matches)){array_shift($matches);$test->setArguments($matches);return$test;}}}return null;}
                function getUnaryOperators():array{if(!$this->initialized){$this->initExtensions();}return$this->unaryOperators;}
                function getBinaryOperators():array{if(!$this->initialized){$this->initExtensions();}return$this->binaryOperators;}
        private function initExtensions():void{$this->parsers=[];$this->filters=[];$this->functions=[];$this->tests=[];$this->visitors=[];$this->unaryOperators=[];$this->binaryOperators=[];foreach($this->extensions as$extension){$this->initExtension($extension);}$this->initExtension($this->staging);$this->
                         initialized=true;}
        private function initExtension(ExtensionInterface$extension):void{foreach($extension->getFilters()as$filter){$this->filters[$filter->getName()]=$filter;}foreach($extension->getFunctions()as$function){$this->functions[$function->getName()]=$function;}foreach($extension->getTests()as$test){$this->tests[
                         $test->getName()]=$test;}foreach($extension->getTokenParsers()as$parser){if(!$parser instanceof TokenParserInterface){throw new \LogicException('getTokenParsers()must return an array of \Twig\TokenParser\TokenParserInterface.');}$this->parsers[$parser->getTag()]=$parser;}foreach(
                         $extension->getNodeVisitors()as$visitor){$this->visitors[]=$visitor;}if($operators=$extension->getOperators()){if(!\is_array($operators)){throw new \InvalidArgumentException(sprintf('"%s::getOperators()" must return an array with operators,got "%s".',$extension::class,\is_object(
                         $operators)?$operators::class:\gettype($operators).(\is_resource($operators)?'':'#'.$operators)));}if(2!==\count($operators)){throw new \InvalidArgumentException(sprintf('"%s::getOperators()" must return an array of 2 elements,got %d.',$extension::class,\count($operators)));}$this->
                         unaryOperators=array_merge($this->unaryOperators,$operators[0]);$this->binaryOperators=array_merge($this->binaryOperators,$operators[1]);}}}
abstract
class AbstractExpression extends Node{}
class ConstantExpression extends AbstractExpression{
                function __construct($value,int$lineno){parent::__construct([],['value'=>$value],$lineno);}
                function compile(Compiler$compiler):void{$compiler->repr($this->getAttribute('value'));}}
class EscaperExtension   extends AbstractExtension{private$defaultStrategy;private array$escapers=[];public$safeClasses=[];public$safeLookup=[];
                function __construct($defaultStrategy='html'){$this->setDefaultStrategy($defaultStrategy);}
                function getTokenParsers():array{return[new AutoEscapeTokenParser()];}
                function getNodeVisitors():array{return[new EscaperNodeVisitor()];}
                function getFilters():array{return[new TwigFilter('escape',[self::class,'escape'],['needs_environment'=>true,'is_safe_callback'=>[self::class,'escapeFilterIsSafe']]),new TwigFilter('e',[self::class,'escape'],['needs_environment'=>true,'is_safe_callback'=>[self::class,'escapeFilterIsSafe']]),new
                         TwigFilter('raw',[self::class,'raw'],['is_safe'=>['all']]),];}
                function setDefaultStrategy($defaultStrategy):void{if('name'===$defaultStrategy){$defaultStrategy=[FileExtensionEscapingStrategy::class,'guess'];}$this->defaultStrategy=$defaultStrategy;}
                function getDefaultStrategy(string$name){if(!\is_string($this->defaultStrategy)&&false!==$this->defaultStrategy){return \call_user_func($this->defaultStrategy,$name);}return$this->defaultStrategy;}
                function setEscaper($strategy,callable$callable){$this->escapers[$strategy]=$callable;}
                function getEscapers(){return$this->escapers;}
                function setSafeClasses(array$safeClasses=[]){$this->safeClasses=[];$this->safeLookup=[];foreach($safeClasses as$class=>$strategies){$this->addSafeClass($class,$strategies);}}
                function addSafeClass(string$class,array$strategies){$class=ltrim($class,'\\');if(!isset($this->safeClasses[$class])){$this->safeClasses[$class]=[];}$this->safeClasses[$class]=array_merge($this->safeClasses[$class],$strategies);foreach($strategies as$strategy){$this->safeLookup[$strategy][
                         $class]=true;}}
         static function raw($string){return$string;}
         static function escapeFilterIsSafe(Node$filterArgs){foreach($filterArgs as$arg){if($arg instanceof ConstantExpression){return[$arg->getAttribute('value')];}return[];}return['html'];}
         static function escape(Environment$env,$string,$strategy='html',$charset=null,$autoescape=false){if($autoescape&&$string instanceof Markup){return$string;}if(!\is_string($string)){if(\is_object($string)&&method_exists($string,'__toString')){if($autoescape){$c=$string::class;$ext=$env->getExtension(
                         self::class);if(!isset($ext->safeClasses[$c])){$ext->safeClasses[$c]=[];foreach(class_parents($string)+class_implements($string)as$class){if(isset($ext->safeClasses[$class])){$ext->safeClasses[$c]=array_unique(array_merge($ext->safeClasses[$c],$ext->safeClasses[$class]));foreach($ext->
                         safeClasses[$class]as$s){$ext->safeLookup[$s][$c]=true;}}}}if(isset($ext->safeLookup[$strategy][$c])||isset($ext->safeLookup['all'][$c])){return(string)$string;}}$string=(string)$string;}elseif(\in_array($strategy,['html','js','css','html_attr','url'])){return$string;}}if(''===$string)
                        {return'';}if(null===$charset){$charset=$env->getCharset();}switch($strategy){case'html':static$htmlspecialcharsCharsets=['ISO-8859-1'=>true,'ISO8859-1'=>true,'ISO-8859-15'=>true,'ISO8859-15'=>true,'utf-8'=>true,'UTF-8'=>true,'CP866'=>true,'IBM866'=>true,'866'=>true,'CP1251'=>true,
                         'WINDOWS-1251'=>true,'WIN-1251'=>true,'1251'=>true,'CP1252'=>true,'WINDOWS-1252'=>true,'1252'=>true,'KOI8-R'=>true,'KOI8-RU'=>true,'KOI8R'=>true,'BIG5'=>true,'950'=>true,'GB2312'=>true,'936'=>true,'BIG5-HKSCS'=>true,'SHIFT_JIS'=>true,'SJIS'=>true,'932'=>true,'EUC-JP'=>true,'EUCJP'=>
                         true,'ISO8859-5'=>true,'ISO-8859-5'=>true,'MACROMAN'=>true,];if(isset($htmlspecialcharsCharsets[$charset])){return htmlspecialchars($string,\ENT_QUOTES|\ENT_SUBSTITUTE,$charset);}if(isset($htmlspecialcharsCharsets[strtoupper($charset)])){$htmlspecialcharsCharsets[$charset]=true;return
                         htmlspecialchars($string,\ENT_QUOTES|\ENT_SUBSTITUTE,$charset);}$string=CoreExtension::convertEncoding($string,'UTF-8',$charset);$string=htmlspecialchars($string,\ENT_QUOTES|\ENT_SUBSTITUTE,'UTF-8');return iconv('UTF-8',$charset,$string);case'js':if('UTF-8'!==$charset){$string=
                         CoreExtension::convertEncoding($string,'UTF-8',$charset);}if(!preg_match('//u',$string)){throw new RuntimeError('The string to escape is not a valid UTF-8 string.');}$string=preg_replace_callback('#[^a-zA-Z0-9,\._]#Su',function($matches){$char=$matches[0];static$shortMap=['\\'=>'\\\\',
                         '/'=>'\\/',"\x08"=>'\b',"\x0C"=>'\f',"\x0A"=>'\n',"\x0D"=>'\r',"\x09"=>'\t',];if(isset($shortMap[$char])){return$shortMap[$char];}$codepoint=mb_ord($char,'UTF-8');if(0x10000>$codepoint){return sprintf('\u%04X',$codepoint);}$u=$codepoint-0x10000;$high=0xD800|($u>>10);$low=0xDC00|(
                         $u&0x3FF);return sprintf('\u%04X\u%04X',$high,$low);},$string);if('UTF-8'!==$charset){$string=iconv('UTF-8',$charset,$string);}return$string;case'css':if('UTF-8'!==$charset){$string=CoreExtension::convertEncoding($string,'UTF-8',$charset);}if(!preg_match('//u',$string)){throw new
                         RuntimeError('The string to escape is not a valid UTF-8 string.');}$string=preg_replace_callback('#[^a-zA-Z0-9]#Su',function($matches){$char=$matches[0];return sprintf('\\%X ',1===\strlen($char)? \ord($char):mb_ord($char,'UTF-8'));},$string);if('UTF-8'!==$charset){$string=iconv('UTF-8'
                         ,$charset,$string);}return$string;case'html_attr':if('UTF-8'!==$charset){$string=CoreExtension::convertEncoding($string,'UTF-8',$charset);}if(!preg_match('//u',$string)){throw new RuntimeError('The string to escape is not a valid UTF-8 string.');}$string=preg_replace_callback(
                         '#[^a-zA-Z0-9,\.\-_]#Su',function($matches){$chr=$matches[0];$ord=\ord($chr);if(($ord<=0x1F&&"\t"!=$chr&&"\n"!=$chr&&"\r"!=$chr)||($ord>= 0x7F&&$ord<= 0x9F)){return'&#xFFFD;';}if(1===\strlen($chr)){static$entityMap=[34=>'&quot;',38=>'&amp;',60=>'&lt;',62=>'&gt;',];if(isset($entityMap
                         [$ord])){return$entityMap[$ord];}return sprintf('&#x%02X;',$ord);}return sprintf('&#x%04X;',mb_ord($chr,'UTF-8'));},$string);if('UTF-8'!==$charset){$string=iconv('UTF-8',$charset,$string);}return$string;case'url':return rawurlencode($string);default:$escapers=$env->getExtension(self::
                         class)->getEscapers();if(\array_key_exists($strategy,$escapers)){return$escapers[$strategy]($env,$string,$charset);}$validStrategies=implode('","',array_merge(['html','js','url','css','html_attr'],array_keys($escapers)));throw new RuntimeError(sprintf(
                         'Invalid escaping strategy "%s"(valid ones: "%s").',$strategy,$validStrategies));}}}
class OptimizerExtension extends AbstractExtension{private int$optimizers;
                function __construct(int$optimizers=-1){$this->optimizers=$optimizers;}
                function getNodeVisitors():array{return[new OptimizerNodeVisitor($this->optimizers)];}}
abstract
class AbstractTokenParser
                         implements TokenParserInterface{protected$parser;
                function setParser(Parser$parser):void{$this->parser=$parser;}}
class NameExpression     extends AbstractExpression{private array$specialVars=['_self'=>'$this->getTemplateName()','_context'=>'$context','_charset'=>'$this->env->getCharset()',];
                function __construct(string$name,int$lineno){parent::__construct([],['name'=>$name,'is_defined_test'=>false,'ignore_strict_check'=>false,'always_defined'=>false],$lineno);}
                function compile(Compiler$compiler):void{$name=$this->getAttribute('name');$compiler->addDebugInfo($this);if($this->getAttribute('is_defined_test')){if($this->isSpecial()){$compiler->repr(true);}else{$compiler->raw('array_key_exists(')->string($name)->raw(',$context)');}}elseif($this->isSpecial
                         ()){$compiler->raw($this->specialVars[$name]);}elseif($this->getAttribute('always_defined')){$compiler->raw('$context[')->string($name)->raw(']');}else{if($this->getAttribute('ignore_strict_check')||!$compiler->getEnvironment()->isStrictVariables()){$compiler->raw('($context[')->string
                         ($name)->raw(']??null)');}else{$compiler->raw('(array_key_exists(')->string($name)->raw(',$context)?$context[')->string($name)->raw(']:throw new RuntimeError(\'Variable ')->string($name)->raw(' does not exist.\',')->repr($this->lineno)->raw(',$this->source)')->raw(')');}}}
                function isSpecial(){return isset($this->specialVars[$this->getAttribute('name')]);}
                function isSimple(){return !$this->isSpecial()&&!$this->getAttribute('is_defined_test');}}
class ApplyTokenParser   extends AbstractTokenParser{
                function parse(Token$token):Node{$lineno=$token->getLine();$name=$this->parser->getVarName();$ref=new TempNameExpression($name,$lineno);$ref->setAttribute('always_defined',true);$filter=$this->parser->getExpressionParser()->parseFilterExpressionRaw($ref,$this->getTag());$this->parser->getStream
                         ()->expect(Token::BLOCK_END_TYPE);$body=$this->parser->subparse([$this,'decideApplyEnd'],true);$this->parser->getStream()->expect(Token::BLOCK_END_TYPE);return new Node([new SetNode(true,$ref,$body,$lineno,$this->getTag()),new PrintNode($filter,$lineno,$this->getTag()),]);}
                function decideApplyEnd(Token$token):bool{return$token->test('endapply');}
                function getTag():string{return'apply';}}
interface TokenParserInterface{
                function setParser(Parser$parser):void;
                function parse(Token$token);
                function getTag();}
class ForTokenParser     extends AbstractTokenParser{
                function parse(Token$token):Node{$lineno=$token->getLine();$stream=$this->parser->getStream();$targets=$this->parser->getExpressionParser()->parseAssignmentExpression();$stream->expect(8,'in');$seq=$this->parser->getExpressionParser()->parseExpression();$stream->expect(3);$body=$this->parser->
                         subparse([$this,'decideForFork']);if('else'==$stream->next()->getValue()){$stream->expect(3);$else=$this->parser->subparse([$this,'decideForEnd'],true);}else{$else=null;}$stream->expect(3);if(\count($targets)>1){$keyTarget=$targets->getNode('0');$keyTarget=new AssignNameExpression(
                         $keyTarget->getAttribute('name'),$keyTarget->getTemplateLine());$valueTarget=$targets->getNode('1');}else{$keyTarget=new AssignNameExpression('_key',$lineno);$valueTarget=$targets->getNode('0');}$valueTarget=new
                         AssignNameExpression($valueTarget->getAttribute('name'),$valueTarget->getTemplateLine());return new ForNode($keyTarget,$valueTarget,$seq,null,$body,$else,$lineno,$this->getTag());}
                function decideForFork(Token$token):bool{return$token->test(['else','endfor']);}
                function decideForEnd(Token$token):bool{return$token->test('endfor');}
                function getTag():string{return'for';}}
class IfTokenParser      extends AbstractTokenParser{
                function parse(Token$token):Node{$lineno=$token->getLine();$expr=$this->parser->getExpressionParser()->parseExpression();$stream=$this->parser->getStream();$stream->expect(3);$body=$this->parser->subparse([$this,'decideIfFork']);$tests=[$expr,$body];$else=null;$end=false;while(!$end){switch(
                         $stream->next()->getValue()){case'else':$stream->expect(3);$else=$this->parser->subparse([$this,'decideIfEnd']);break;case'elseif':$expr=$this->parser->getExpressionParser()->parseExpression();$stream->expect(3);$body=$this->parser->subparse([$this,'decideIfFork']);$tests[]=$expr;
                         $tests[]=$body;break;case'endif':$end=true;break;default:throw new SyntaxError(sprintf('Unexpected end of template. Twig was looking for the following tags "else","elseif",or "endif" to close the "if" block started at line %d).',$lineno),$stream->getCurrent()->getLine(),$stream->
                         getSourceContext());}}$stream->expect(3);return new IfNode(new Node($tests),$else,$lineno,$this->getTag());}
                function decideIfFork(Token$token):bool{return$token->test(['elseif','else','endif']);}
                function decideIfEnd(Token$token):bool{return$token->test(['endif']);}
                function getTag():string{return'if';}}
class ExtendsTokenParser extends AbstractTokenParser{
                function parse(Token$token):Node{$stream=$this->parser->getStream();if($this->parser->peekBlockStack()){throw new SyntaxError('Cannot use "extend" in a block.',$token->getLine(),$stream->getSourceContext());}elseif(!$this->parser->isMainScope()){throw new SyntaxError(
                         'Cannot use "extend" in a macro.',$token->getLine(),$stream->getSourceContext());}if(null!==$this->parser->getParent()){throw new SyntaxError('Multiple extends tags are forbidden.',$token->getLine(),$stream->getSourceContext());}$this->parser->setParent($this->parser->
                         getExpressionParser()->parseExpression());$stream->expect(3);return new Node();}
                function getTag():string{return'extends';}}
class IncludeTokenParser extends AbstractTokenParser{
                function parse(Token$token):Node{$expr=$this->parser->getExpressionParser()->parseExpression();[$variables,$only,$ignoreMissing]=$this->parseArguments();return new IncludeNode($expr,$variables,$only,$ignoreMissing,$token->getLine(),$this->getTag());}
      protected function parseArguments(){$stream=$this->parser->getStream();$ignoreMissing=false;if($stream->nextIf(5,'ignore')){$stream->expect(5,'missing');$ignoreMissing=true;}$variables=null;if($stream->nextIf(5,'with')){$variables=$this->parser->getExpressionParser()->parseExpression();}$only=false;if(
                         $stream->nextIf(5,'only')){$only=true;}$stream->expect(3);return[$variables,$only,$ignoreMissing];}
                function getTag():string{return'include';}}
class BlockTokenParser   extends AbstractTokenParser{
                function parse(Token$token):Node{$lineno=$token->getLine();$stream=$this->parser->getStream();$name=$stream->expect(5)->getValue();if($this->parser->hasBlock($name)){throw new SyntaxError(sprintf("The block '%s' has already been defined line %d.",$name,$this->parser->getBlock($name)->
                         getTemplateLine()),$stream->getCurrent()->getLine(),$stream->getSourceContext());}$this->parser->setBlock($name,$block=new BlockNode($name,new Node([]),$lineno));$this->parser->pushLocalScope();$this->parser->pushBlockStack($name);if($stream->nextIf(3)){$body=$this->parser->subparse([
                         $this,'decideBlockEnd'],true);if($token=$stream->nextIf(5)){$value=$token->getValue();if($value!=$name){throw new SyntaxError(sprintf('Expected endblock for block "%s"(but "%s" given).',$name,$value),$stream->getCurrent()->getLine(),$stream->getSourceContext());}}}else{$body=new Node([
                         new PrintNode($this->parser->getExpressionParser()->parseExpression(),$lineno),]);}$stream->expect(3);$block->setNode('body',$body);$this->parser->popBlockStack();$this->parser->popLocalScope();return new BlockReferenceNode($name,$lineno,$this->getTag());}
                function decideBlockEnd(Token$token):bool{return$token->test('endblock');}
                function getTag():string{return'block';}}
class UseTokenParser     extends AbstractTokenParser{
                function parse(Token$token):Node{$template=$this->parser->getExpressionParser()->parseExpression();$stream=$this->parser->getStream();if(!$template instanceof ConstantExpression){throw new SyntaxError('The template references in a "use" statement must be a string.',$stream->getCurrent()->
                         getLine(),$stream->getSourceContext());}$targets=[];if($stream->nextIf('with')){while(true){$name=$stream->expect(5)->getValue();$alias=$name;if($stream->nextIf('as')){$alias=$stream->expect(5)->getValue();}$targets[$name]=new ConstantExpression($alias,-1);if(!$stream->nextIf(9,',')){
                         break;}}}$stream->expect(3);$this->parser->addTrait(new Node(['template'=>$template,'targets'=>new Node($targets)]));return new Node();}
                function getTag():string{return'use';}}
class MacroTokenParser   extends AbstractTokenParser{
                function parse(Token$token):Node{$lineno=$token->getLine();$stream=$this->parser->getStream();$name=$stream->expect(5)->getValue();$arguments=$this->parser->getExpressionParser()->parseArguments(true,true);$stream->expect(3);$this->parser->pushLocalScope();$body=$this->parser->subparse([$this,
                         'decideBlockEnd'],true);if($token=$stream->nextIf(5)){$value=$token->getValue();if($value!=$name){throw new SyntaxError(sprintf('Expected endmacro for macro "%s"(but "%s" given).',$name,$value),$stream->getCurrent()->getLine(),$stream->getSourceContext());}}$this->parser->popLocalScope
                         ();$stream->expect(3);$this->parser->setMacro($name,new MacroNode($name,new BodyNode([$body]),$arguments,$lineno,$this->getTag()));return new Node();}
                function decideBlockEnd(Token$token):bool{return$token->test('endmacro');}
                function getTag():string{return'macro';}}
class ImportTokenParser  extends AbstractTokenParser{
                function parse(Token$token):Node{$macro=$this->parser->getExpressionParser()->parseExpression();$this->parser->getStream()->expect(5,'as');$var=new AssignNameExpression($this->parser->getStream()->expect(5)->getValue(),$token->getLine());$this->parser->getStream()->expect(3);$this->parser->
                         addImportedSymbol('template',$var->getAttribute('name'));return new ImportNode($macro,$var,$token->getLine(),$this->getTag(),$this->parser->isMainScope());}
                function getTag():string{return'import';}}
class FromTokenParser    extends AbstractTokenParser{
                function parse(Token$token):Node{$macro=$this->parser->getExpressionParser()->parseExpression();$stream=$this->parser->getStream();$stream->expect(5,'import');$targets=[];while(true){$name=$stream->expect(5)->getValue();$alias=$name;if($stream->nextIf('as')){$alias=$stream->expect(5)->
                         getValue();}$targets[$name]=$alias;if(!$stream->nextIf(9,',')){break;}}$stream->expect(3);$var=new AssignNameExpression($this->parser->getVarName(),$token->getLine());$node=new ImportNode($macro,$var,$token->getLine(),$this->getTag(),$this->parser->isMainScope());foreach($targets as
                         $name=>$alias){$this->parser->addImportedSymbol('function',$alias,'macro_'.$name,$var);}return$node;}
                function getTag():string{return'from';}}
class SetTokenParser     extends AbstractTokenParser{
                function parse(Token$token):Node{$lineno=$token->getLine();$stream=$this->parser->getStream();$names=$this->parser->getExpressionParser()->parseAssignmentExpression();$capture=false;if($stream->nextIf(8,'=')){$values=$this->parser->getExpressionParser()->parseMultitargetExpression();$stream->
                         expect(3);if(\count($names)!==\count($values)){throw new SyntaxError('When using set,you must have the same number of variables and assignments.',$stream->getCurrent()->getLine(),$stream->getSourceContext());}}else{$capture=true;if(\count($names)>1){throw new SyntaxError(
                         'When using set with a block,you cannot have a multi-target.',$stream->getCurrent()->getLine(),$stream->getSourceContext());}$stream->expect(3);$values=$this->parser->subparse([$this,'decideBlockEnd'],true);$stream->expect(3);}return new SetNode($capture,$names,$values,$lineno,$this->
                         getTag());}
                function decideBlockEnd(Token$token):bool{return$token->test('endset');}
                function getTag():string{return'set';}}
class FlushTokenParser   extends AbstractTokenParser{
                function parse(Token$token):Node{$this->parser->getStream()->expect(3);return new FlushNode($token->getLine(),$this->getTag());}
                function getTag():string{return'flush';}}
class DoTokenParser      extends AbstractTokenParser{
                function parse(Token$token):Node{$expr=$this->parser->getExpressionParser()->parseExpression();$this->parser->getStream()->expect(3);return new DoNode($expr,$token->getLine(),$this->getTag());}
                function getTag():string{return'do';}}
class EmbedTokenParser   extends IncludeTokenParser{
                function parse(Token$token):Node{$stream=$this->parser->getStream();$parent=$this->parser->getExpressionParser()->parseExpression();[$variables,$only,$ignoreMissing]=$this->parseArguments();$parentToken=$fakeParentToken=new Token(7,'__parent__',$token->getLine());if($parent instanceof
                         ConstantExpression){$parentToken=new Token(7,$parent->getAttribute('value'),$token->getLine());}elseif($parent instanceof NameExpression){$parentToken=new Token(5,$parent->getAttribute('name'),$token->getLine());}$stream->injectTokens([new Token(1,'',$token->getLine()),new Token(5,
                         'extends',$token->getLine()),$parentToken,new Token(3,'',$token->getLine()),]);$module=$this->parser->parse($stream,[$this,'decideBlockEnd'],true);if($fakeParentToken===$parentToken){$module->setNode('parent',$parent);}$this->parser->embedTemplate($module);$stream->expect(3);return new
                         EmbedNode($module->getTemplateName(),$module->getAttribute('index'),$variables,$only,$ignoreMissing,$token->getLine(),$this->getTag());}
                function decideBlockEnd(Token$token):bool{return$token->test('endembed');}
                function getTag():string{return'embed';}}
class WithTokenParser
                         extends AbstractTokenParser{
                function parse(Token$token):Node{$stream=$this->parser->getStream();$variables=null;$only=false;if(!$stream->test(3)){$variables=$this->parser->getExpressionParser()->parseExpression();$only=(bool)$stream->nextIf(5,'only');}$stream->expect(3);$body=$this->parser->subparse([$this,'decideWithEnd'
                         ],true);$stream->expect(3);return new WithNode($body,$variables,$only,$token->getLine(),$this->getTag());}
                function decideWithEnd(Token$token):bool{return$token->test('endwith');}
                function getTag():string{return'with';}}
class DeprecatedTokenParser
                         extends AbstractTokenParser{
                function parse(Token$token):Node{$expr=$this->parser->getExpressionParser()->parseExpression();$this->parser->getStream()->expect(Token::BLOCK_END_TYPE);return new DeprecatedNode($expr,$token->getLine(),$this->getTag());}
                function getTag():string{return'deprecated';}}
interface NodeVisitorInterface{
                function enterNode(Node$node,Environment$env):Node;
                function leaveNode(Node$node,Environment$env):?Node;
                function getPriority();}
class MacroAutoImportNodeVisitor
                         implements NodeVisitorInterface{private bool$inAModule=false;private bool$hasMacroCalls=false;
                function enterNode(Node$node,Environment$env):Node{if($node instanceof ModuleNode){$this->inAModule=true;$this->hasMacroCalls=false;}return$node;}
                function leaveNode(Node$node,Environment$env):Node{if($node instanceof ModuleNode){$this->inAModule=false;if($this->hasMacroCalls){$node->getNode('constructor_end')->setNode('_auto_macro_import',new ImportNode(new NameExpression('_self',0),new AssignNameExpression('_self',0),0,'import',true));}
                         }elseif($this->inAModule){if($node instanceof GetAttrExpression&&$node->getNode('node')instanceof NameExpression&&'_self'===$node->getNode('node')->getAttribute('name')&&$node->getNode('attribute')instanceof ConstantExpression){$this->hasMacroCalls=true;$name=$node->getNode('attribute'
                         )->getAttribute('value');$node=new MethodCallExpression($node->getNode('node'),'macro_'.$name,$node->getNode('arguments'),$node->getTemplateLine());$node->setAttribute('safe',true);}}return$node;}
                function getPriority():int{return-10;}}
class AutoEscapeTokenParser
                         extends AbstractTokenParser{
                function parse(Token$token):Node{$lineno=$token->getLine();$stream=$this->parser->getStream();if($stream->test(3)){$value='html';}else{$expr=$this->parser->getExpressionParser()->parseExpression();if(!$expr instanceof ConstantExpression){throw new SyntaxError(
                         'An escaping strategy must be a string or false.',$stream->getCurrent()->getLine(),$stream->getSourceContext());}$value=$expr->getAttribute('value');}$stream->expect(3);$body=$this->parser->subparse([$this,'decideBlockEnd'],true);$stream->expect(3); return new AutoEscapeNode($value,
                         $body,$lineno,$this->getTag());}
                function decideBlockEnd(Token$token):bool{return$token->test('endautoescape');}
                function getTag():string{return'autoescape';}}
class EscaperNodeVisitor implements NodeVisitorInterface{private array$statusStack=[];private array$blocks=[];private SafeAnalysisNodeVisitor$safeAnalysis;private?NodeTraverser$traverser=null;private string|false$defaultStrategy=false;private array$safeVars=[];
                function __construct(){$this->safeAnalysis=new SafeAnalysisNodeVisitor();}
                function enterNode(Node$node,Environment$env):Node{if($node instanceof ModuleNode){if($env->hasExtension(EscaperExtension::class)&&$defaultStrategy=$env->getExtension(EscaperExtension::class)->getDefaultStrategy($node->getTemplateName())){$this->defaultStrategy=$defaultStrategy;}$this->safeVars
                         =[];$this->blocks=[];}elseif($node instanceof AutoEscapeNode){$this->statusStack[]=$node->getAttribute('value');}elseif($node instanceof BlockNode){$this->statusStack[]=$this->blocks[$node->getAttribute('name')]??$this->needEscaping();}elseif($node instanceof ImportNode){$this->
                         safeVars[]=$node->getNode('var')->getAttribute('name');}return$node;}
                function leaveNode(Node$node,Environment$env):?Node{if($node instanceof ModuleNode){$this->defaultStrategy=false;$this->safeVars=[];$this->blocks=[];}elseif($node instanceof FilterExpression){return$this->preEscapeFilterNode($node,$env);}elseif($node instanceof PrintNode&&false!==$type=$this->
                         needEscaping()){$expression=$node->getNode('expr');if($expression instanceof ConditionalExpression&&$this->shouldUnwrapConditional($expression,$env,$type)){return new DoNode($this->unwrapConditional($expression,$env,$type),$expression->getTemplateLine());}return$this->escapePrintNode(
                         $node,$env,$type);}if($node instanceof AutoEscapeNode||$node instanceof BlockNode){array_pop($this->statusStack);}elseif($node instanceof BlockReferenceNode){$this->blocks[$node->getAttribute('name')]=$this->needEscaping();}return$node;}
        private function shouldUnwrapConditional(ConditionalExpression$expression,Environment$env,string$type):bool{$expr2Safe=$this->isSafeFor($type,$expression->getNode('expr2'),$env);$expr3Safe=$this->isSafeFor($type,$expression->getNode('expr3'),$env);return$expr2Safe!==$expr3Safe;}
        private function unwrapConditional(ConditionalExpression$expression,Environment$env,string$type):ConditionalExpression{$expr2=$expression->getNode('expr2');if($expr2 instanceof ConditionalExpression&&$this->shouldUnwrapConditional($expr2,$env,$type)){$expr2=$this->unwrapConditional($expr2,$env,$type);}
                         else{$expr2=$this->escapeInlinePrintNode(new InlinePrint($expr2,$expr2->getTemplateLine()),$env,$type);}$expr3=$expression->getNode('expr3');if($expr3 instanceof ConditionalExpression&&$this->shouldUnwrapConditional($expr3,$env,$type)){$expr3=$this->unwrapConditional($expr3,$env,$type)
                         ;}else{$expr3=$this->escapeInlinePrintNode(new InlinePrint($expr3,$expr3->getTemplateLine()),$env,$type);}return new ConditionalExpression($expression->getNode('expr1'),$expr2,$expr3,$expression->getTemplateLine());}
        private function escapeInlinePrintNode(InlinePrint$node,Environment$env,string$type):Node{$expression=$node->getNode('node');if($this->isSafeFor($type,$expression,$env)){return$node;}return new InlinePrint($this->getEscaperFilter($type,$expression),$node->getTemplateLine());}
        private function escapePrintNode(PrintNode$node,Environment$env,string$type):Node{$expression=$node->getNode('expr');if($this->isSafeFor($type,$expression,$env)){return$node;}$class=$node::class;return new$class($this->getEscaperFilter($type,$expression),$node->getTemplateLine());}
        private function preEscapeFilterNode(FilterExpression$filter,Environment$env):FilterExpression{$name=$filter->getNode('filter')->getAttribute('value');$type=$env->getFilter($name)->getPreEscape();if(null===$type){return$filter;}$node=$filter->getNode('node');if($this->isSafeFor($type,$node,$env)){
                         return$filter;}$filter->setNode('node',$this->getEscaperFilter($type,$node));return$filter;}
        private function isSafeFor(string$type,Node$expression,Environment$env):bool{$safe=$this->safeAnalysis->getSafe($expression);if(null===$safe){if(null===$this->traverser){$this->traverser=new NodeTraverser($env,[$this->safeAnalysis]);}$this->safeAnalysis->setSafeVars($this->safeVars);$this->traverser->
                         traverse($expression);$safe=$this->safeAnalysis->getSafe($expression);}return \in_array($type,$safe)||\in_array('all',$safe);}
        private function needEscaping(){if(\count($this->statusStack)){return$this->statusStack[\count($this->statusStack)-1];}return$this->defaultStrategy?: false;}
        private function getEscaperFilter(string$type,Node$node):FilterExpression{$line=$node->getTemplateLine();$name=new ConstantExpression('escape',$line);$args=new Node([new ConstantExpression($type,$line),new ConstantExpression(null,$line),new ConstantExpression(true,$line)]);return new FilterExpression(
                         $node,$name,$args,$line);}
                function getPriority():int{return 0;}}
class SafeAnalysisNodeVisitor
                         implements NodeVisitorInterface{private$data=[];private$safeVars=[];
                function setSafeVars(array$safeVars):void{$this->safeVars=$safeVars;}
                function getSafe(Node$node){$hash=spl_object_hash($node);if(!isset($this->data[$hash])){return;}foreach($this->data[$hash]as$bucket){if($bucket['key']!==$node){continue;}if(\in_array('html_attr',$bucket['value'])){$bucket['value'][]='html';}return$bucket['value'];}}
        private function setSafe(Node$node,array$safe):void{$hash=spl_object_hash($node);if(isset($this->data[$hash])){foreach($this->data[$hash]as &$bucket){if($bucket['key']===$node){$bucket['value']=$safe;return;}}}$this->data[$hash][]=['key'=>$node,'value'=>$safe,];}
                function enterNode(Node$node,Environment$env):Node{return$node;}
                function leaveNode(Node$node,Environment$env):?Node{if($node instanceof ConstantExpression){$this->setSafe($node,['all']);}elseif($node instanceof BlockReferenceExpression){$this->setSafe($node,['all']);}elseif($node instanceof ParentExpression){$this->setSafe($node,['all']);}elseif($node
                         instanceof ConditionalExpression){$safe=$this->intersectSafe($this->getSafe($node->getNode('expr2')),$this->getSafe($node->getNode('expr3')));$this->setSafe($node,$safe);}elseif($node instanceof FilterExpression){$name=$node->getNode('filter')->getAttribute('value');$args=$node->
                         getNode('arguments');if($filter=$env->getFilter($name)){$safe=$filter->getSafe($args);if(null===$safe){$safe=$this->intersectSafe($this->getSafe($node->getNode('node')),$filter->getPreservesSafety());}$this->setSafe($node,$safe);}else{$this->setSafe($node,[]);}}elseif($node instanceof
                         FunctionExpression){$name=$node->getAttribute('name');$args=$node->getNode('arguments');if($function=$env->getFunction($name)){$this->setSafe($node,$function->getSafe($args));}else{$this->setSafe($node,[]);}}elseif($node instanceof MethodCallExpression){if($node->getAttribute('safe')){
                         $this->setSafe($node,['all']);}else{$this->setSafe($node,[]);}}elseif($node instanceof GetAttrExpression&&$node->getNode('node')instanceof NameExpression){$name=$node->getNode('node')->getAttribute('name');if(\in_array($name,$this->safeVars)){$this->setSafe($node,['all']);}else{$this
                         ->setSafe($node,[]);}}else{$this->setSafe($node,[]);}return$node;}
        private function intersectSafe(?array$a=null,?array$b=null):array{if(null===$a||null===$b){return[];}if(\in_array('all',$a)){return$b;}if(\in_array('all',$b)){return$a;}return array_intersect($a,$b);}
                function getPriority():int{return 0;}}
class OptimizerNodeVisitor
                         implements NodeVisitorInterface{const OPTIMIZE_ALL=-1;const OPTIMIZE_NONE=0;const OPTIMIZE_FOR=2;const OPTIMIZE_RAW_FILTER=4;const OPTIMIZE_TEXT_NODES=8;private array$loops=[];private array$loopsTargets=[];private int$optimizers;
                function __construct(int$optimizers=-1){if($optimizers>(self::OPTIMIZE_FOR|self::OPTIMIZE_RAW_FILTER)){throw new \InvalidArgumentException(sprintf('Optimizer mode "%s" is not valid.',$optimizers));}$this->optimizers=$optimizers;}
                function enterNode(Node$node,Environment$env):Node{if(self::OPTIMIZE_FOR===(self::OPTIMIZE_FOR &$this->optimizers)){$this->enterOptimizeFor($node);}return$node;}
                function leaveNode(Node$node,Environment$env):?Node{if(self::OPTIMIZE_FOR===(self::OPTIMIZE_FOR &$this->optimizers)){$this->leaveOptimizeFor($node);}if(self::OPTIMIZE_RAW_FILTER===(self::OPTIMIZE_RAW_FILTER &$this->optimizers)){$node=$this->optimizeRawFilter($node);}$node=$this->
                         optimizePrintNode($node);if(self::OPTIMIZE_TEXT_NODES===(self::OPTIMIZE_TEXT_NODES &$this->optimizers)){$node=$this->mergeTextNodeCalls($node);}return$node;}
        private function mergeTextNodeCalls(Node$node):Node{$text='';$names=[];foreach($node as$k=>$n){if(!$n instanceof TextNode){return$node;}$text.=$n->getAttribute('data');$names[]=$k;}if(!$text){return$node;}if(Node::class===get_class($node)){return new TextNode($text,$node->getTemplateLine());}foreach(
                         $names as$i=>$name){if(0===$i){$node->setNode($name,new TextNode($text,$node->getTemplateLine()));}else{$node->removeNode($name);}}return$node;}
        private function optimizePrintNode(Node$node):Node{if(!$node instanceof PrintNode){return$node;}$exprNode=$node->getNode('expr');if($exprNode instanceof ConstantExpression&&\is_string($exprNode->getAttribute('value'))){return new TextNode($exprNode->getAttribute('value'),$exprNode->getTemplateLine());}
                         if($exprNode instanceof BlockReferenceExpression||$exprNode instanceof ParentExpression){$exprNode->setAttribute('output',true);return$exprNode;}return$node;}
        private function optimizeRawFilter(Node$node):Node{if($node instanceof FilterExpression&&'raw'==$node->getNode('filter')->getAttribute('value')){return$node->getNode('node');}return$node;}
        private function enterOptimizeFor(Node$node):void{if($node instanceof ForNode){$node->setAttribute('with_loop',false);array_unshift($this->loops,$node);array_unshift($this->loopsTargets,$node->getNode('value_target')->getAttribute('name'));array_unshift($this->loopsTargets,$node->getNode('key_target')
                         ->getAttribute('name'));}elseif(!$this->loops){return;}elseif($node instanceof NameExpression&&'loop'===$node->getAttribute('name')){$node->setAttribute('always_defined',true);$this->addLoopToCurrent();}elseif($node instanceof NameExpression&&\in_array($node->getAttribute('name'),$this
                         ->loopsTargets)){$node->setAttribute('always_defined',true);}elseif($node instanceof BlockReferenceNode||$node instanceof BlockReferenceExpression){$this->addLoopToCurrent();}elseif($node instanceof IncludeNode&&!$node->getAttribute('only')){$this->addLoopToAll();}elseif($node
                         instanceof FunctionExpression&&'include'===$node->getAttribute('name')&&(!$node->getNode('arguments')->hasNode('with_context')||false!==$node->getNode('arguments')->getNode('with_context')->getAttribute('value'))){$this->addLoopToAll();}elseif($node instanceof GetAttrExpression&&(
                         !$node->getNode('attribute')instanceof ConstantExpression||'parent'===$node->getNode('attribute')->getAttribute('value'))&&(true===$this->loops[0]->getAttribute('with_loop')||($node->getNode('node')instanceof NameExpression&&'loop'===$node->getNode('node')->getAttribute('name')))){
                         $this->addLoopToAll();}}
        private function leaveOptimizeFor(Node$node):void{if($node instanceof ForNode){array_shift($this->loops);array_shift($this->loopsTargets);array_shift($this->loopsTargets);}}
        private function addLoopToCurrent():void{$this->loops[0]->setAttribute('with_loop',true);}
        private function addLoopToAll():void{foreach($this->loops as$loop){$loop->setAttribute('with_loop',true);}}
                function getPriority():int{return 255;}}
class AssignNameExpression
                         extends NameExpression{
                function compile(Compiler$compiler):void{$compiler->raw('$context[')->string($this->getAttribute('name'))->raw(']');}}
abstract
class CallExpression     extends AbstractExpression{private?array$reflector=null;
      protected function compileCallable(Compiler$compiler){$callable=$this->getAttribute('callable');if(\is_string($callable)&&!str_contains($callable,'::')){$compiler->raw($callable);}else{[$r,$callable]=$this->reflectCallable($callable);if(\is_string($callable)){$compiler->raw($callable);}elseif(\is_array(
                         $callable)&&\is_string($callable[0])){if(!$r instanceof \ReflectionMethod||$r->isStatic()){$compiler->raw(sprintf('%s::%s',$callable[0],$callable[1]));}else{$compiler->raw(sprintf('$this->env->getRuntime(\'%s\')->%s',$callable[0],$callable[1]));}}elseif(\is_array($callable)&&$callable
                         [0]instanceof ExtensionInterface){$class=$callable[0]::class;if(!$compiler->getEnvironment()->hasExtension($class)){$compiler->raw(sprintf('$this->env->getExtension(\'%s\')',$class));}else{$compiler->raw(sprintf('$this->extensions[\'%s\']',ltrim($class,'\\')));}$compiler->raw(sprintf(
                         '->%s',$callable[1]));}else{$compiler->raw(sprintf('$this->env->get%s(\'%s\')->getCallable()',ucfirst($this->getAttribute('type')),$this->getAttribute('name')));}}$this->compileArguments($compiler);}
      protected function compileArguments(Compiler$compiler,$isArray=false):void{$compiler->raw($isArray?'[':'(');$first=true;if($this->hasAttribute('needs_environment')&&$this->getAttribute('needs_environment')){$compiler->raw('$this->env');$first=false;}if($this->hasAttribute('needs_context')&&$this->
                         getAttribute('needs_context')){if(!$first){$compiler->raw(',');}$compiler->raw('$context');$first=false;}if($this->hasAttribute('arguments')){foreach($this->getAttribute('arguments')as$argument){if(!$first){$compiler->raw(',');}$compiler->string($argument);$first=false;}}if($this->
                         hasNode('node')){if(!$first){$compiler->raw(',');}$compiler->subcompile($this->getNode('node'));$first=false;}if($this->hasNode('arguments')){$callable=$this->getAttribute('callable');$arguments=$this->getArguments($callable,$this->getNode('arguments'));foreach($arguments as$node){if(
                         !$first){$compiler->raw(',');}$compiler->subcompile($node);$first=false;}}$compiler->raw($isArray?']':')');}
      protected function getArguments($callable,$arguments){$callType=$this->getAttribute('type');$callName=$this->getAttribute('name');$parameters=[];$named=false;foreach($arguments as$name=>$node){if(!\is_int($name)){$named=true;$name=$this->normalizeName($name);}elseif($named){throw new SyntaxError(sprintf(
                         'Positional arguments cannot be used after named arguments for %s "%s".',$callType,$callName),$this->getTemplateLine(),$this->getSourceContext());}$parameters[$name]=$node;}$isVariadic=$this->hasAttribute('is_variadic')&&$this->getAttribute('is_variadic');if(!$named&&!$isVariadic){
                         return$parameters;}if(!$callable){if($named){$message=sprintf('Named arguments are not supported for %s "%s".',$callType,$callName);}else{$message=sprintf('Arbitrary positional arguments are not supported for %s "%s".',$callType,$callName);}throw new \LogicException($message);}[
                         $callableParameters,$isPhpVariadic]=$this->getCallableParameters($callable,$isVariadic);$arguments=[];$names=[];$missingArguments=[];$optionalArguments=[];$pos=0;foreach($callableParameters as$callableParameter){$name=$this->normalizeName($callableParameter->name);if('range'===
                         $callable){if('start'===$name){$name='low';}elseif('end'===$name){$name='high';}}$names[]=$name;if(\array_key_exists($name,$parameters)){if(\array_key_exists($pos,$parameters)){throw new SyntaxError(sprintf('Argument "%s" is defined twice for %s "%s".',$name,$callType,$callName),$this
                         ->getTemplateLine(),$this->getSourceContext());}if(\count($missingArguments)){throw new SyntaxError(sprintf('Argument "%s" could not be assigned for %s "%s(%s)" because it is mapped to an internal PHP function which cannot determine default value for optional argument%s "%s".',$name,
                         $callType,$callName,implode(',',$names),\count($missingArguments)>1?'s':'',implode('","',$missingArguments)),$this->getTemplateLine(),$this->getSourceContext());}$arguments=array_merge($arguments,$optionalArguments);$arguments[]=$parameters[$name];unset($parameters[$name]);
                         $optionalArguments=[];}elseif(\array_key_exists($pos,$parameters)){$arguments=array_merge($arguments,$optionalArguments);$arguments[]=$parameters[$pos];unset($parameters[$pos]);$optionalArguments=[];++$pos;}elseif($callableParameter->isDefaultValueAvailable()){$optionalArguments[]=new
                         ConstantExpression($callableParameter->getDefaultValue(),-1);}elseif($callableParameter->isOptional()){if(empty($parameters)){break;}else{$missingArguments[]=$name;}}else{throw new SyntaxError(sprintf('Value for argument "%s" is required for %s "%s".',$name,$callType,$callName),$this->
                         getTemplateLine(),$this->getSourceContext());}}if($isVariadic){$arbitraryArguments=$isPhpVariadic? new VariadicExpression([],-1):new ArrayExpression([],-1);foreach($parameters as$key=>$value){if(\is_int($key)){$arbitraryArguments->addElement($value);}else{$arbitraryArguments->
                         addElement($value,new ConstantExpression($key,-1));}unset($parameters[$key]);}if($arbitraryArguments->count()){$arguments=array_merge($arguments,$optionalArguments);$arguments[]=$arbitraryArguments;}}if(!empty($parameters)){$unknownParameter=null;foreach($parameters as$parameter){if(
                         $parameter instanceof Node){$unknownParameter=$parameter;break;}}throw new SyntaxError(sprintf('Unknown argument%s "%s" for %s "%s(%s)".',\count($parameters)>1?'s':'',implode('","',array_keys($parameters)),$callType,$callName,implode(',',$names)),$unknownParameter?$unknownParameter->
                         getTemplateLine():$this->getTemplateLine(),$unknownParameter?$unknownParameter->getSourceContext():$this->getSourceContext());}return$arguments;}
      protected function normalizeName(string$name):string{return strtolower(preg_replace(['/([A-Z]+)([A-Z][a-z])/','/([a-z\d])([A-Z])/'],['\\1_\\2','\\1_\\2'],$name));}
        private function getCallableParameters($callable,bool$isVariadic):array{[$r,,$callableName]=$this->reflectCallable($callable);$parameters=$r->getParameters();if($this->hasNode('node')){array_shift($parameters);}if($this->hasAttribute('needs_environment')&&$this->getAttribute('needs_environment')){
                         array_shift($parameters);}if($this->hasAttribute('needs_context')&&$this->getAttribute('needs_context')){array_shift($parameters);}if($this->hasAttribute('arguments')&&null!==$this->getAttribute('arguments')){foreach($this->getAttribute('arguments')as$argument){array_shift($parameters
                         );}}$isPhpVariadic=false;if($isVariadic){$argument=end($parameters);$isArray=$argument&&$argument->hasType()&&'array'===$argument->getType()->getName();if($isArray&&$argument->isDefaultValueAvailable()&&[]===$argument->getDefaultValue()){array_pop($parameters);}elseif($argument&&
                         $argument->isVariadic()){array_pop($parameters);$isPhpVariadic=true;}else{throw new \LogicException(sprintf('The last parameter of "%s" for %s "%s" must be an array with default value,eg. "array$arg=[]".',$callableName,$this->getAttribute('type'),$this->getAttribute('name')));}}return[
                         $parameters,$isPhpVariadic];}
        private function reflectCallable($callable){if(null!==$this->reflector){return$this->reflector;}if(\is_string($callable)&&false!==$pos=strpos($callable,'::')){$callable=[substr($callable,0,$pos),substr($callable,2 +$pos)];}if(\is_array($callable)&&method_exists($callable[0],$callable[1])){$r=new
                         \ReflectionMethod($callable[0],$callable[1]);return$this->reflector=[$r,$callable,$r->class.'::'.$r->name];}$checkVisibility=$callable instanceof \Closure;try{$closure=\Closure::fromCallable($callable);}catch(\TypeError$e){throw new \LogicException(sprintf(
                         'Callback for %s "%s" is not callable in the current scope.',$this->getAttribute('type'),$this->getAttribute('name')),0,$e);}$r=new \ReflectionFunction($closure);if(str_contains($r->name,'{closure}')){return$this->reflector=[$r,$callable,'Closure'];}if($object=$r->getClosureThis()){
                         $callable=[$object,$r->name];$callableName=get_debug_type($object).'::'.$r->name;}elseif($class=$r->getClosureCalledClass()){$callableName=$class->name.'::'.$r->name;}else{$callable=$callableName=$r->name;}if($checkVisibility&&\is_array($callable)&&method_exists(...$callable)&&!(new
                         \ReflectionMethod(...$callable))->isPublic()){$callable=$r->getClosure();}return$this->reflector=[$r,$callable,$callableName];}}
class TestExpression
                         extends CallExpression{
                function __construct(Node$node,string$name,?Node$arguments,int$lineno){$nodes=['node'=>$node];if(null!==$arguments){$nodes['arguments']=$arguments;}parent::__construct($nodes,['name'=>$name],$lineno);}
                function compile(Compiler$compiler):void{$name=$this->getAttribute('name');$test=$compiler->getEnvironment()->getTest($name);$this->setAttribute('name',$name);$this->setAttribute('type','test');$this->setAttribute('arguments',$test->getArguments());$this->setAttribute('callable',$test->
                         getCallable());$this->setAttribute('is_variadic',$test->isVariadic());$this->compileCallable($compiler);}}
class ExpressionParser  {const OPERATOR_LEFT=1;const OPERATOR_RIGHT=2;private Parser$parser;private Environment$env;private array$unaryOperators;private array$binaryOperators;
                function __construct(Parser$parser,Environment$env){$this->parser=$parser;$this->env=$env;$this->unaryOperators=$env->getUnaryOperators();$this->binaryOperators=$env->getBinaryOperators();}
                function parseExpression($precedence=0,$allowArrow=false){if($allowArrow&&$arrow=$this->parseArrow()){return$arrow;}$expr=$this->getPrimary();$token=$this->parser->getCurrentToken();while($this->isBinary($token)&&$this->binaryOperators[$token->getValue()]['precedence']>=$precedence){$op=$this->
                         binaryOperators[$token->getValue()];$this->parser->getStream()->next();if('is not'===$token->getValue()){$expr=$this->parseNotTestExpression($expr);}elseif('is'===$token->getValue()){$expr=$this->parseTestExpression($expr);}elseif(isset($op['callable'])){$expr=$op['callable']($this->
                         parser,$expr);}else{$expr1=$this->parseExpression(self::OPERATOR_LEFT===$op['associativity']?$op['precedence']+1:$op['precedence'],true);$class=$op['class'];$expr=new$class($expr,$expr1,$token->getLine());}$token=$this->parser->getCurrentToken();}if(0===$precedence){return$this->
                         parseConditionalExpression($expr);}return$expr;}
        private function parseArrow(){$stream=$this->parser->getStream();if($stream->look(1)->test(12)){$line=$stream->getCurrent()->getLine();$token=$stream->expect(5);$names=[new AssignNameExpression($token->getValue(),$token->getLine())];$stream->expect(12);return new ArrowFunctionExpression($this->
                         parseExpression(0),new Node($names),$line);}$i=0;if(!$stream->look($i)->test(9,'(')){return null;}++$i;while(true){++$i;if(!$stream->look($i)->test(9,',')){break;}++$i;}if(!$stream->look($i)->test(9,')')){return null;}++$i;if(!$stream->look($i)->test(12)){return null;}$token=$stream->
                         expect(9,'(');$line=$token->getLine();$names=[];while(true){$token=$stream->expect(5);$names[]=new AssignNameExpression($token->getValue(),$token->getLine());if(!$stream->nextIf(9,',')){break;}}$stream->expect(9,')');$stream->expect(12);return new ArrowFunctionExpression($this->
                         parseExpression(0),new Node($names),$line);}
        private function getPrimary():AbstractExpression{$token=$this->parser->getCurrentToken();if($this->isUnary($token)){$operator=$this->unaryOperators[$token->getValue()];$this->parser->getStream()->next();$expr=$this->parseExpression($operator['precedence']);$class=$operator['class'];return$this->
                         parsePostfixExpression(new$class($expr,$token->getLine()));}elseif($token->test(9,'(')){$this->parser->getStream()->next();$expr=$this->parseExpression();$this->parser->getStream()->expect(9,')','An opened parenthesis is not properly closed');return$this->
                         parsePostfixExpression($expr);}return$this->parsePrimaryExpression();}
        private function parseConditionalExpression($expr):AbstractExpression{while($this->parser->getStream()->nextIf(9,'?')){if(!$this->parser->getStream()->nextIf(9,':')){$expr2=$this->parseExpression();if($this->parser->getStream()->nextIf(9,':')){$expr3=$this->parseExpression();}else{$expr3=new
                         ConstantExpression('',$this->parser->getCurrentToken()->getLine());}}else{$expr2=$expr;$expr3=$this->parseExpression();}$expr=new ConditionalExpression($expr,$expr2,$expr3,$this->parser->getCurrentToken()->getLine());}return$expr;}
        private function isUnary(Token$token):bool{return$token->test(8)&&isset($this->unaryOperators[$token->getValue()]);}
        private function isBinary(Token$token):bool{return$token->test(8)&&isset($this->binaryOperators[$token->getValue()]);}
                function parsePrimaryExpression(){$token=$this->parser->getCurrentToken();switch($token->getType()){case 5:$this->parser->getStream()->next();switch($token->getValue()){case'true':case'TRUE':$node=new ConstantExpression(true,$token->getLine());break;case'false':case'FALSE':$node=new
                         ConstantExpression(false,$token->getLine());break;case'none':case'NONE':case'null':case'NULL':$node=new ConstantExpression(null,$token->getLine());break;default:if('('===$this->parser->getCurrentToken()->getValue()){$node=$this->getFunctionNode($token->getValue(),$token->getLine());}
                         else{$node=new NameExpression($token->getValue(),$token->getLine());}}break;case 6:$this->parser->getStream()->next();$node=new ConstantExpression($token->getValue(),$token->getLine());break;case 7:case 10:$node=$this->parseStringExpression();break;case 8:if(preg_match(Lexer::
                         REGEX_NAME,$token->getValue(),$matches)&&$matches[0]==$token->getValue()){$this->parser->getStream()->next();$node=new NameExpression($token->getValue(),$token->getLine());break;}if(isset($this->unaryOperators[$token->getValue()])){$class=$this->unaryOperators[$token->getValue()][
                         'class'];if(!\in_array($class,[NegUnary::class,PosUnary::class])){throw new SyntaxError(sprintf('Unexpected unary operator "%s".',$token->getValue()),$token->getLine(),$this->parser->getStream()->getSourceContext());}$this->parser->getStream()->next();$expr=$this->
                         parsePrimaryExpression();$node=new$class($expr,$token->getLine());break;}default:if($token->test(9,'[')){$node=$this->parseArrayExpression();}elseif($token->test(9,'{')){$node=$this->parseHashExpression();}elseif($token->test(8,'=')&&('=='===$this->parser->getStream()->look(-1)->
                         getValue()||'!='===$this->parser->getStream()->look(-1)->getValue())){throw new SyntaxError(sprintf('Unexpected operator of value "%s". Did you try to use "===" or "!==" for strict comparison? Use "is same as(value)" instead.',$token->getValue()),$token->getLine(),$this->parser->
                         getStream()->getSourceContext());}else{throw new SyntaxError(sprintf('Unexpected token "%s" of value "%s".',Token::typeToEnglish($token->getType()),$token->getValue()),$token->getLine(),$this->parser->getStream()->getSourceContext());}}return$this->parsePostfixExpression($node);}
                function parseStringExpression(){$stream=$this->parser->getStream();$nodes=[];$nextCanBeString=true;while(true){if($nextCanBeString&&$token=$stream->nextIf(7)){$nodes[]=new ConstantExpression($token->getValue(),$token->getLine());$nextCanBeString=false;}elseif($stream->nextIf(10)){$nodes[]=
                         $this->parseExpression();$stream->expect(11);$nextCanBeString=true;}else{break;}}$expr=array_shift($nodes);foreach($nodes as$node){$expr=new ConcatBinary($expr,$node,$node->getTemplateLine());}return$expr;}
                function parseArrayExpression(){$stream=$this->parser->getStream();$stream->expect(9,'[','An array element was expected');$node=new ArrayExpression([],$stream->getCurrent()->getLine());$first=true;while(!$stream->test(9,']')){if(!$first){$stream->expect(9,',',
                         'An array element must be followed by a comma');if($stream->test(9,']')){break;}}$first=false;if($stream->test(13)){$stream->next();$expr=$this->parseExpression();$expr->setAttribute('spread',true);$node->addElement($expr);}else{$node->addElement($this->parseExpression());}}$stream->
                         expect(9,']','An opened array is not properly closed');return$node;}
                function parseHashExpression(){$stream=$this->parser->getStream();$stream->expect(9,'{','A hash element was expected');$node=new ArrayExpression([],$stream->getCurrent()->getLine());$first=true;while(!$stream->test(9,'}')){if(!$first){$stream->expect(9,',',
                         'A hash value must be followed by a comma');if($stream->test(9,'}')){break;}}$first=false;if($stream->test(13)){$stream->next();$value=$this->parseExpression();$value->setAttribute('spread',true);$node->addElement($value);continue;}if($token=$stream->nextIf(5)){$key=new
                         ConstantExpression($token->getValue(),$token->getLine());if($stream->test(Token::PUNCTUATION_TYPE,[',','}'])){$value=new NameExpression($key->getAttribute('value'),$key->getTemplateLine());$node->addElement($value,$key);continue;}}elseif(($token=$stream->nextIf(7))||$token=$stream->
                         nextIf(6)){$key=new ConstantExpression($token->getValue(),$token->getLine());}elseif($stream->test(9,'(')){$key=$this->parseExpression();}else{$current=$stream->getCurrent();throw new SyntaxError(sprintf(
                         'A hash key must be a quoted string,a number,a name,or an expression enclosed in parentheses(unexpected token "%s" of value "%s".',Token::typeToEnglish($current->getType()),$current->getValue()),$current->getLine(),$stream->getSourceContext());}$stream->expect(9,':',
                         'A hash key must be followed by a colon(:)');$value=$this->parseExpression();$node->addElement($value,$key);}$stream->expect(9,'}','An opened hash is not properly closed');return$node;}
               function parsePostfixExpression($node){while(true){$token=$this->parser->getCurrentToken();if(9==$token->getType()){if('.'==$token->getValue()||'['==$token->getValue()){$node=$this->parseSubscriptExpression($node);}elseif('|'==$token->getValue()){$node=$this->parseFilterExpression($node);}else{
                        break;}}else{break;}}return$node;}
               function getFunctionNode($name,$line){switch($name){case'parent':$this->parseArguments();if(!\count($this->parser->getBlockStack())){throw new SyntaxError('Calling "parent" outside a block is forbidden.',$line,$this->parser->getStream()->getSourceContext());}if(!$this->parser->getParent()&&
                        !$this->parser->hasTraits()){throw new SyntaxError('Calling "parent" on a template that does not extend nor "use" another template is forbidden.',$line,$this->parser->getStream()->getSourceContext());}return new ParentExpression($this->parser->peekBlockStack(),$line);case'block':$args=
                        $this->parseArguments();if(\count($args)<1){throw new SyntaxError('The "block" function takes one argument(the block name).',$line,$this->parser->getStream()->getSourceContext());}return new BlockReferenceExpression($args->getNode('0'),\count($args)>1?$args->getNode('1'):null,$line);
                        case'attribute':$args=$this->parseArguments();if(\count($args)<2){throw new SyntaxError('The "attribute" function takes at least two arguments(the variable and the attributes).',$line,$this->parser->getStream()->getSourceContext());}return new GetAttrExpression($args->getNode('0'),$args
                        ->getNode('1'),\count($args)>2?$args->getNode('2'):null,Template::ANY_CALL,$line);default:if(null!==$alias=$this->parser->getImportedSymbol('function',$name)){$arguments=new ArrayExpression([],$line);foreach($this->parseArguments()as$n){$arguments->addElement($n);}$node=new
                        MethodCallExpression($alias['node'],$alias['name'],$arguments,$line);$node->setAttribute('safe',true);return$node;}$args=$this->parseArguments(true);$class=$this->getFunctionNodeClass($name,$line);return new$class($name,$args,$line);}}
               function parseSubscriptExpression($node){$stream=$this->parser->getStream();$token=$stream->next();$lineno=$token->getLine();$arguments=new ArrayExpression([],$lineno);$type=Template::ANY_CALL;if('.'==$token->getValue()){$token=$stream->next();if(5==$token->getType()||6==$token->getType()||(8==
                        $token->getType()&&preg_match(Lexer::REGEX_NAME,$token->getValue()))){$arg=new ConstantExpression($token->getValue(),$lineno);if($stream->test(9,'(')){$type=Template::METHOD_CALL;foreach($this->parseArguments()as$n){$arguments->addElement($n);}}}else{throw new SyntaxError(sprintf(
                        'Expected name or number,got value "%s" of type %s.',$token->getValue(),Token::typeToEnglish($token->getType())),$lineno,$stream->getSourceContext());}if($node instanceof NameExpression&&null!==$this->parser->getImportedSymbol('template',$node->getAttribute('name'))){$name=$arg->
                        getAttribute('value');$node=new MethodCallExpression($node,'macro_'.$name,$arguments,$lineno);$node->setAttribute('safe',true);return$node;}}else{$type=Template::ARRAY_CALL;$slice=false;if($stream->test(9,':')){$slice=true;$arg=new ConstantExpression(0,$token->getLine());}else{$arg=
                        $this->parseExpression();}if($stream->nextIf(9,':')){$slice=true;}if($slice){if($stream->test(9,']')){$length=new ConstantExpression(null,$token->getLine());}else{$length=$this->parseExpression();}$class=$this->getFilterNodeClass('slice',$token->getLine());$arguments=new Node([$arg,
                        $length]);$filter=new$class($node,new ConstantExpression('slice',$token->getLine()),$arguments,$token->getLine());$stream->expect(9,']');return$filter;}$stream->expect(9,']');}return new GetAttrExpression($node,$arg,$arguments,$type,$lineno);}
               function parseFilterExpression($node){$this->parser->getStream()->next();return$this->parseFilterExpressionRaw($node);}
               function parseFilterExpressionRaw($node,$tag=null){while(true){$token=$this->parser->getStream()->expect(5);$name=new ConstantExpression($token->getValue(),$token->getLine());if(!$this->parser->getStream()->test(9,'(')){$arguments=new Node();}else{$arguments=$this->parseArguments(true,false,
                        true);}$class=$this->getFilterNodeClass($name->getAttribute('value'),$token->getLine());$node=new$class($node,$name,$arguments,$token->getLine(),$tag);if(!$this->parser->getStream()->test(9,'|')){break;}$this->parser->getStream()->next();}return$node;}
               function parseArguments($namedArguments=false,$definition=false,$allowArrow=false){$args=[];$stream=$this->parser->getStream();$stream->expect(9,'(','A list of arguments must begin with an opening parenthesis');while(!$stream->test(9,')')){if(!empty($args)){$stream->expect(9,',',
                        'Arguments must be separated by a comma');if($stream->test(9,')')){break;}}if($definition){$token=$stream->expect(5,null,'An argument must be a name');$value=new NameExpression($token->getValue(),$this->parser->getCurrentToken()->getLine());}else{$value=$this->parseExpression(0,
                        $allowArrow);}$name=null;if($namedArguments&&$token=$stream->nextIf(8,'=')){if(!$value instanceof NameExpression){throw new SyntaxError(sprintf('A parameter name must be a string,"%s" given.',$value::class),$token->getLine(),$stream->getSourceContext());}$name=$value->getAttribute(
                        'name');if($definition){$value=$this->parsePrimaryExpression();if(!$this->checkConstantExpression($value)){throw new SyntaxError('A default value for an argument must be a constant(a boolean,a string,a number,or an array).',$token->getLine(),$stream->getSourceContext());}}else{$value=
                        $this->parseExpression(0,$allowArrow);}}if($definition){if(null===$name){$name=$value->getAttribute('name');$value=new ConstantExpression(null,$this->parser->getCurrentToken()->getLine());}$args[$name]=$value;}else{if(null===$name){$args[]=$value;}else{$args[$name]=$value;}}}$stream->
                        expect(9,')','A list of arguments must be closed by a parenthesis');return new Node($args);}
               function parseAssignmentExpression(){$stream=$this->parser->getStream();$targets=[];while(true){$token=$this->parser->getCurrentToken();if($stream->test(8)&&preg_match(Lexer::REGEX_NAME,$token->getValue())){$this->parser->getStream()->next();}else{$stream->expect(5,null,
                        'Only variables can be assigned to');}$value=$token->getValue();if(\in_array(strtr($value,'ABCDEFGHIJKLMNOPQRSTUVWXYZ','abcdefghijklmnopqrstuvwxyz'),['true','false','none','null'])){throw new SyntaxError(sprintf('You cannot assign a value to "%s".',$value),$token->getLine(),$stream->
                        getSourceContext());}$targets[]=new AssignNameExpression($value,$token->getLine());if(!$stream->nextIf(9,',')){break;}}return new Node($targets);}
               function parseMultitargetExpression(){$targets=[];while(true){$targets[]=$this->parseExpression();if(!$this->parser->getStream()->nextIf(9,',')){break;}}return new Node($targets);}
       private function parseNotTestExpression(Node$node):NotUnary{return new NotUnary($this->parseTestExpression($node),$this->parser->getCurrentToken()->getLine());}
       private function parseTestExpression(Node$node):TestExpression{$stream=$this->parser->getStream();[$name,$test]=$this->getTest($node->getTemplateLine());$class=$this->getTestNodeClass($test);$arguments=null;if($stream->test(9,'(')){$arguments=$this->parseArguments(true);}elseif($test->
                        hasOneMandatoryArgument()){$arguments=new Node([0=>$this->parsePrimaryExpression()]);}if('defined'===$name&&$node instanceof NameExpression&&null!==$alias=$this->parser->getImportedSymbol('function',$node->getAttribute('name'))){$node=new MethodCallExpression($alias['node'],$alias[
                        'name'],new ArrayExpression([],$node->getTemplateLine()),$node->getTemplateLine());$node->setAttribute('safe',true);}return new$class($node,$name,$arguments,$this->parser->getCurrentToken()->getLine());}
       private function getTest(int$line):array{$stream=$this->parser->getStream();$name=$stream->expect(5)->getValue();if($test=$this->env->getTest($name)){return[$name,$test];}if($stream->test(5)){$name=$name.' '.$this->parser->getCurrentToken()->getValue();if($test=$this->env->getTest($name)){$stream->
                        next();return[$name,$test];}}$e=new SyntaxError(sprintf('Unknown "%s" test.',$name),$line,$stream->getSourceContext());$e->addSuggestions($name,array_keys($this->env->getTests()));throw$e;}
       private function getTestNodeClass(TwigTest$test):string{if($test->isDeprecated()){$stream=$this->parser->getStream();$message=sprintf('Twig Test "%s" is deprecated',$test->getName());if($test->getDeprecatedVersion()){$message.=sprintf(' since version %s',$test->getDeprecatedVersion());}if($test->
                        getAlternative()){$message.=sprintf('. Use "%s" instead',$test->getAlternative());}$src=$stream->getSourceContext();$message.=sprintf(' in %s at line %d.',$src->getPath()?:$src->getName(),$stream->getCurrent()->getLine());@trigger_error($message,\E_USER_DEPRECATED);}return$test->
                        getNodeClass();}
       private function getFunctionNodeClass(string$name,int$line):string{if(!$function=$this->env->getFunction($name)){$e=new SyntaxError(sprintf('Unknown "%s" function.',$name),$line,$this->parser->getStream()->getSourceContext());$e->addSuggestions($name,array_keys($this->env->getFunctions()));throw$e;}if(
                        $function->isDeprecated()){$message=sprintf('Twig Function "%s" is deprecated',$function->getName());if($function->getDeprecatedVersion()){$message.=sprintf(' since version %s',$function->getDeprecatedVersion());}if($function->getAlternative()){$message.=sprintf('. Use "%s" instead',
                        $function->getAlternative());}$src=$this->parser->getStream()->getSourceContext();$message.=sprintf(' in %s at line %d.',$src->getPath()?:$src->getName(),$line);@trigger_error($message,\E_USER_DEPRECATED);}return$function->getNodeClass();}
       private function getFilterNodeClass(string$name,int$line):string{if(!$filter=$this->env->getFilter($name)){$e=new SyntaxError(sprintf('Unknown "%s" filter.',$name),$line,$this->parser->getStream()->getSourceContext());$e->addSuggestions($name,array_keys($this->env->getFilters()));throw$e;}
                        if($filter->isDeprecated()){$message=sprintf('Twig Filter "%s" is deprecated',$filter->getName());if($filter->getDeprecatedVersion()){$message.=sprintf(' since version %s',$filter->getDeprecatedVersion());}if($filter->getAlternative()){$message.=sprintf('. Use "%s" instead',$filter->
                        getAlternative());}$src=$this->parser->getStream()->getSourceContext();$message.=sprintf(' in %s at line %d.',$src->getPath()?:$src->getName(),$line);@trigger_error($message,\E_USER_DEPRECATED);}return$filter->getNodeClass();}
       private function checkConstantExpression(Node$node):bool{if(!($node instanceof ConstantExpression||$node instanceof ArrayExpression||$node instanceof NegUnary||$node instanceof PosUnary)){return false;}foreach($node as$n){if(!$this->checkConstantExpression($n)){return false;}}return true;}}
class Parser           {private array$stack=[];private TokenStream$stream;private?Node$parent;private?array$visitors=null;private?ExpressionParser$expressionParser=null;private array$blocks;private array$blockStack;private array$macros;private Environment$env;private array$importedSymbols;private array$traits
                        ;private array$embeddedTemplates=[];private int$varNameSalt=0;
               function __construct(Environment$env){$this->env=$env;}
               function getVarName():string{return sprintf('__internal_parse_%d',$this->varNameSalt++);}
               function parse(TokenStream$stream,$test=null,bool$dropNeedle=false):ModuleNode{$vars=get_object_vars($this); unset($vars['stack'],$vars['env'],$vars['handlers'],$vars['visitors'],$vars['expressionParser'],$vars['reservedMacroNames'],$vars['varNameSalt']);$this->stack[]=$vars;if(null===$this->
                        visitors){$this->visitors=$this->env->getNodeVisitors();}if(null===$this->expressionParser){$this->expressionParser=new ExpressionParser($this,$this->env);}$this->stream=$stream;$this->parent=null;$this->blocks=[];$this->macros=[];$this->traits=[];$this->blockStack=[];$this->
                        importedSymbols=[[]];$this->embeddedTemplates=[];try{$body=$this->subparse($test,$dropNeedle);if(null!==$this->parent&&null===$body=$this->filterBodyNodes($body)){$body=new Node();}}catch(SyntaxError$e){if(!$e->getSourceContext()){$e->setSourceContext($this->stream->
                        getSourceContext());}if(!$e->getTemplateLine()){$e->setTemplateLine($this->stream->getCurrent()->getLine());}throw$e;}$node=new ModuleNode(new BodyNode([$body]),$this->parent,new Node($this->blocks),new Node($this->macros),new Node($this->traits),$this->embeddedTemplates,$stream->
                        getSourceContext());$traverser=new NodeTraverser($this->env,$this->visitors);$node=$traverser->traverse($node);foreach(array_pop($this->stack)as$key=>$val){$this->$key=$val;}return$node;}
               function subparse($test,bool$dropNeedle=false):Node{$lineno=$this->getCurrentToken()->getLine();$rv=[];while(!$this->stream->isEOF()){switch($this->getCurrentToken()->getType()){case 0:$token=$this->stream->next();$rv[]=new TextNode($token->getValue(),$token->getLine());break;case 2:$token=$this
                        ->stream->next();$expr=$this->expressionParser->parseExpression();$this->stream->expect(4);$rv[]=new PrintNode($expr,$token->getLine());break;case 1:$this->stream->next();$token=$this->getCurrentToken();if(5!==$token->getType()){throw new SyntaxError(
                        'A block must start with a tag name.',$token->getLine(),$this->stream->getSourceContext());}if(null!==$test&&$test($token)){if($dropNeedle){$this->stream->next();}if(1===\count($rv)){return$rv[0];}return new Node($rv,[],$lineno);}if(!$subparser=$this->env->getTokenParser($token->
                        getValue())){if(null!==$test){$e=new SyntaxError(sprintf('Unexpected "%s" tag',$token->getValue()),$token->getLine(),$this->stream->getSourceContext());if(\is_array($test)&&isset($test[0])&&$test[0]instanceof TokenParserInterface){$e->appendMessage(sprintf(
                        '(expecting closing tag for the "%s" tag defined near line %s).',$test[0]->getTag(),$lineno));}}else{$e=new SyntaxError(sprintf('Unknown "%s" tag.',$token->getValue()),$token->getLine(),$this->stream->getSourceContext());$e->addSuggestions($token->getValue(),array_keys($this->env->
                        getTokenParsers()));}throw$e;}$this->stream->next();$subparser->setParser($this);$node=$subparser->parse($token);if(null!==$node){$rv[]=$node;}break;default:throw new SyntaxError('Lexer or parser ended up in unsupported state.',$this->getCurrentToken()->getLine(),$this->stream->
                        getSourceContext());}}if(1===\count($rv)){return$rv[0];}return new Node($rv,[],$lineno);}
               function getBlockStack():array{return$this->blockStack;}
               function peekBlockStack(){return$this->blockStack[\count($this->blockStack)-1]??null;}
               function popBlockStack():void{array_pop($this->blockStack);}
               function pushBlockStack($name):void{$this->blockStack[]=$name;}
               function hasBlock(string$name):bool{return isset($this->blocks[$name]);}
               function getBlock(string$name):Node{return$this->blocks[$name];}
               function setBlock(string$name,BlockNode$value):void{$this->blocks[$name]=new BodyNode([$value],[],$value->getTemplateLine());}
               function hasMacro(string$name):bool{return isset($this->macros[$name]);}
               function setMacro(string$name,MacroNode$node):void{$this->macros[$name]=$node;}
               function addTrait($trait):void{$this->traits[]=$trait;}
               function hasTraits():bool{return \count($this->traits)>0;}
               function embedTemplate(ModuleNode$template){$template->setIndex(mt_rand());$this->embeddedTemplates[]=$template;}
               function addImportedSymbol(string$type,string$alias,?string$name=null,?AbstractExpression$node=null):void{$this->importedSymbols[0][$type][$alias]=['name'=>$name,'node'=>$node];}
               function getImportedSymbol(string$type,string$alias){return$this->importedSymbols[0][$type][$alias]??($this->importedSymbols[\count($this->importedSymbols)-1][$type][$alias]??null);}
               function isMainScope():bool{return 1===\count($this->importedSymbols);}
               function pushLocalScope():void{array_unshift($this->importedSymbols,[]);}
               function popLocalScope():void{array_shift($this->importedSymbols);}
               function getExpressionParser():ExpressionParser{return$this->expressionParser;}
               function getParent():?Node{return$this->parent;}
               function setParent(?Node$parent):void{$this->parent=$parent;}
               function getStream():TokenStream{return$this->stream;}
               function getCurrentToken():Token{return$this->stream->getCurrent();}
       private function filterBodyNodes(Node$node,bool$nested=false):?Node{if(($node instanceof TextNode&&!ctype_space($node->getAttribute('data')))||(!$node instanceof TextNode&&!$node instanceof BlockReferenceNode&&$node instanceof NodeOutputInterface)){if(str_contains((string)$node,\chr(0xEF).\chr(0xBB).
                        \chr(0xBF))){$t=substr($node->getAttribute('data'),3);if(''===$t||ctype_space($t)){return null;}}throw new SyntaxError('A template that extends another one cannot include content outside Twig blocks. Did you forget to put the content inside a{% block %}tag?',$node->getTemplateLine(),
                        $this->stream->getSourceContext());}if($node instanceof NodeCaptureInterface){return$node;}if($nested&&$node instanceof BlockReferenceNode){throw new SyntaxError('A block definition cannot be nested under non-capturing nodes.',$node->getTemplateLine(),$this->stream->getSourceContext());
                        }if($node instanceof NodeOutputInterface){return null;}$nested=$nested||Node::class!==$node::class;foreach($node as$k=>$n){if(null!==$n&&null===$this->filterBodyNodes($n,$nested)){$node->removeNode($k);}}return$node;}}
#[YieldReady]
class TextNode          extends Node implements NodeOutputInterface{
               function __construct(string$data,int$lineno){parent::__construct([],['data'=>$data],$lineno);}
               function compile(Compiler$compiler):void{$compiler->addDebugInfo($this);$compiler->write('yield ')->string($this->getAttribute('data'))->raw(";\n");}}
interface NodeOutputInterface{}
#[YieldReady]
class ImportNode        extends Node{
               function __construct(AbstractExpression$expr,AbstractExpression$var,int$lineno,?string$tag=null,bool$global=true){parent::__construct(['expr'=>$expr,'var'=>$var],['global'=>$global],$lineno,$tag);}
               function compile(Compiler$compiler):void{$compiler->addDebugInfo($this)->write('$macros[')->repr($this->getNode('var')->getAttribute('name'))->raw(']=');if($this->getAttribute('global')){$compiler->raw('$this->macros[')->repr($this->getNode('var')->getAttribute('name'))->raw(']=');}if($this->
                        getNode('expr')instanceof NameExpression&&'_self'===$this->getNode('expr')->getAttribute('name')){$compiler->raw('$this');}else{$compiler->raw('$this->loadTemplate(')->subcompile($this->getNode('expr'))->raw(',')->repr($this->getTemplateName())->raw(',')->repr($this->getTemplateLine())
                        ->raw(')->unwrap()');}$compiler->raw(";\n");}}
#[YieldReady]
class BlockNode         extends Node{
               function __construct(string$name,Node$body,int$lineno,?string$tag=null){parent::__construct(['body'=>$body],['name'=>$name],$lineno,$tag);}
               function compile(Compiler$compiler):void{$compiler->addDebugInfo($this)->write(sprintf("function block_%s(\$context,array \$blocks=[])\n",$this->getAttribute('name')),"{\n")->indent()->write("\$macros=\$this->macros;\n");$compiler->subcompile($this->getNode('body'))->write(
                        "return; yield '';\n")->outdent()->write("}\n\n");}}
#[YieldReady]
class BodyNode          extends Node{}
class FunctionExpression
                        extends CallExpression{
               function __construct(string$name,Node$arguments,int$lineno){parent::__construct(['arguments'=>$arguments],['name'=>$name,'is_defined_test'=>false],$lineno);}
               function compile(Compiler$compiler){$name=$this->getAttribute('name');$function=$compiler->getEnvironment()->getFunction($name);$this->setAttribute('name',$name);$this->setAttribute('type','function');$this->setAttribute('needs_environment',$function->needsEnvironment());$this->setAttribute(
                         'needs_context',$function->needsContext());$this->setAttribute('arguments',$function->getArguments());$callable=$function->getCallable();if('constant'===$name&&$this->getAttribute('is_defined_test')){$callable=[CoreExtension::class,'constantIsDefined'];}$this->setAttribute('callable',
                         $callable);$this->setAttribute('is_variadic',$function->isVariadic());$this->compileCallable($compiler);}}
class TwigFunction      {private string$name;private$callable;private array$options;private array$arguments=[];
                function __construct(string$name,$callable=null,array$options=[]){$this->name=$name;$this->callable=$callable;$this->options=array_merge(['needs_environment'=>false,'needs_context'=>false,'is_variadic'=>false,'is_safe'=>null,'is_safe_callback'=>null,'node_class'=>FunctionExpression::class,
                         'deprecated'=>false,'alternative'=>null,],$options);}
                function getName():string{return$this->name;}
                function getCallable(){return$this->callable;}
                function getNodeClass():string{return$this->options['node_class'];}
                function setArguments(array$arguments):void{$this->arguments=$arguments;}
                function getArguments():array{return$this->arguments;}
                function needsEnvironment():bool{return$this->options['needs_environment'];}
                function needsContext():bool{return$this->options['needs_context'];}
                function getSafe(Node$functionArgs):?array{if(null!==$this->options['is_safe']){return$this->options['is_safe'];}if(null!==$this->options['is_safe_callback']){return$this->options['is_safe_callback']($functionArgs);}return[];}
                function isVariadic():bool{return(bool)$this->options['is_variadic'];}
                function isDeprecated():bool{return(bool)$this->options['deprecated'];}
                function getDeprecatedVersion():string{return \is_bool($this->options['deprecated'])?'':$this->options['deprecated'];}
                function getAlternative():?string{return$this->options['alternative'];}}
#[YieldReady]
class PrintNode          extends Node implements NodeOutputInterface{
                function __construct(AbstractExpression$expr,int$lineno,?string$tag=null){parent::__construct(['expr'=>$expr],[],$lineno,$tag);}
                function compile(Compiler$compiler):void{$compiler->addDebugInfo($this);$compiler->write('yield ')->subcompile($this->getNode('expr'))->raw(";\n");}}
#[YieldReady]
class BlockReferenceNode extends Node implements NodeOutputInterface{
                function __construct(string$name,int$lineno,?string$tag=null){parent::__construct([],['name'=>$name],$lineno,$tag);}
                function compile(Compiler$compiler):void{$compiler->addDebugInfo($this)->write(sprintf("yield from \$this->unwrap()->yieldBlock('%s',\$context,\$blocks);\n",$this->getAttribute('name')));}}
class ArrayExpression    extends AbstractExpression{private int$index;
                function __construct(array$elements,int$lineno){parent::__construct($elements,[],$lineno);$this->index=-1;foreach($this->getKeyValuePairs()as$pair){if($pair['key']instanceof ConstantExpression&&ctype_digit((string)$pair['key']->getAttribute('value'))&&$pair['key']->getAttribute('value')>$this
                         ->index){$this->index=$pair['key']->getAttribute('value');}}}
                function getKeyValuePairs():array{$pairs=[];foreach(array_chunk($this->nodes,2)as$pair){$pairs[]=['key'=>$pair[0],'value'=>$pair[1],];}return$pairs;}
                function hasElement(AbstractExpression$key):bool{foreach($this->getKeyValuePairs()as$pair){if((string)$key===(string)$pair['key']){return true;}}return false;}
                function addElement(AbstractExpression$value,?AbstractExpression$key=null):void{if(null===$key){$key=new ConstantExpression(++$this->index,$value->getTemplateLine());}array_push($this->nodes,$key,$value);}
                function compile(Compiler$compiler):void{$keyValuePairs=$this->getKeyValuePairs();$compiler->raw('[');$first=true;$reopenAfterMergeSpread=false;$nextIndex=0;foreach($keyValuePairs as$pair){if($reopenAfterMergeSpread){$compiler->raw(',[');$reopenAfterMergeSpread=false;}if(!$first){$compiler->
                         raw(',');}$first=false;if($pair['value']->hasAttribute('spread')){$compiler->raw('...')->subcompile($pair['value']);++$nextIndex;}else{$key=$pair['key']instanceof ConstantExpression?$pair['key']->getAttribute('value'):null;if($nextIndex!==$key){if(\is_int($key)){$nextIndex=$key+1;}
                         $compiler->subcompile($pair['key'])->raw('=>');}else{++$nextIndex;}$compiler->subcompile($pair['value']);}}if(!$reopenAfterMergeSpread){$compiler->raw(']');}}}
class GetAttrExpression  extends AbstractExpression{
                function __construct(AbstractExpression$node,AbstractExpression$attribute,?AbstractExpression$arguments,string$type,int$lineno){$nodes=['node'=>$node,'attribute'=>$attribute];if(null!==$arguments){$nodes['arguments']=$arguments;}parent::__construct($nodes,['type'=>$type,'is_defined_test'=>false
                         ,'ignore_strict_check'=>false,'optimizable'=>true],$lineno);}
                function compile(Compiler$compiler):void{$env=$compiler->getEnvironment();if($this->getAttribute('optimizable')&&(!$env->isStrictVariables()||$this->getAttribute('ignore_strict_check'))&&!$this->getAttribute('is_defined_test')&&Template::ARRAY_CALL===$this->getAttribute('type')){$var='$'.
                         $compiler->getVarName();$compiler->raw('(('.$var.'=')->subcompile($this->getNode('node'))->raw(')&&is_array(')->raw($var)->raw(')||')->raw($var)->raw(' instanceof ArrayAccess?(')->raw($var)->raw('[')->subcompile($this->getNode('attribute'))->raw(']??null):null)');return;}$compiler->
                         raw('CoreExtension::getAttribute($this->env,$this->source,');if($this->getAttribute('ignore_strict_check')){$this->getNode('node')->setAttribute('ignore_strict_check',true);}$compiler->subcompile($this->getNode('node'))->raw(',')->subcompile($this->getNode('attribute'));if($this->
                         hasNode('arguments')){$compiler->raw(',arguments: ')->subcompile($this->getNode('arguments'));}if(Template::ANY_CALL!==$type=$this->getAttribute('type')){$compiler->raw(',type: ')->repr($type);}if($this->getAttribute('is_defined_test')){$compiler->raw(',isDefinedTest: true');}if($this
                         ->getAttribute('ignore_strict_check')){$compiler->raw(',ignoreStrictCheck: true');}if($env->hasExtension(SandboxExtension::class)){$compiler->raw(',sandboxed: true');}$compiler->raw(',lineno: ')->repr($this->getNode('node')->getTemplateLine())->raw(')');}}
abstract
class AbstractBinary     extends AbstractExpression{
                function __construct(Node$left,Node$right,int$lineno){parent::__construct(['left'=>$left,'right'=>$right],[],$lineno);}
                function compile(Compiler$compiler):void{$compiler->raw('(')->subcompile($this->getNode('left'))->raw(' ');$this->operator($compiler);$compiler->raw(' ')->subcompile($this->getNode('right'))->raw(')');}
abstract        function operator(Compiler$compiler):Compiler;}
class EqualBinary        extends AbstractBinary{
                function operator(Compiler$compiler):Compiler{return$compiler->raw('==');}}
class BlockReferenceExpression
                         extends AbstractExpression{
                function __construct(Node$name,?Node$template,int$lineno,?string$tag=null){$nodes=['name'=>$name];if(null!==$template){$nodes['template']=$template;}parent::__construct($nodes,['is_defined_test'=>false,'output'=>false],$lineno,$tag);}
                function compile(Compiler$compiler):void{if($this->getAttribute('is_defined_test')){$this->compileTemplateCall($compiler,'hasBlock');}else{if($this->getAttribute('output')){$compiler->addDebugInfo($this);$compiler->write('yield from ');$this->compileTemplateCall($compiler,'yieldBlock')->raw(
                         ";\n");}else{$this->compileTemplateCall($compiler,'renderBlock');}}}
        private function compileTemplateCall(Compiler$compiler,string$method):Compiler{if(!$this->hasNode('template')){$compiler->write('$this');}else{$compiler->write('$this->loadTemplate(')->subcompile($this->getNode('template'))->raw(',')->repr($this->getTemplateName())->raw(',')->repr($this->
                         getTemplateLine())->raw(')');}$compiler->raw(sprintf('->unwrap()->%s',$method));return$this->compileBlockArguments($compiler);}
        private function compileBlockArguments(Compiler$compiler):Compiler{$compiler->raw('(')->subcompile($this->getNode('name'))->raw(',$context');if(!$this->hasNode('template')){$compiler->raw(',$blocks');}return$compiler->raw(')');}}
class FilterExpression   extends CallExpression{
                function __construct(Node$node,ConstantExpression$filterName,Node$arguments,int$lineno,?string$tag=null){parent::__construct(['node'=>$node,'filter'=>$filterName,'arguments'=>$arguments],[],$lineno,$tag);}
                function compile(Compiler$compiler):void{$name=$this->getNode('filter')->getAttribute('value');$filter=$compiler->getEnvironment()->getFilter($name);$this->setAttribute('name',$name);$this->setAttribute('type','filter');$this->setAttribute('needs_environment',$filter->needsEnvironment());$this
                         ->setAttribute('needs_context',$filter->needsContext());$this->setAttribute('arguments',$filter->getArguments());$this->setAttribute('callable',$filter->getCallable());$this->setAttribute('is_variadic',$filter->isVariadic());$this->compileCallable($compiler);}}
class TwigFilter        {private string$name;private$callable;private array$options;private array$arguments=[];
                function __construct(string$name,$callable=null,array$options=[]){$this->name=$name;$this->callable=$callable;$this->options=array_merge(['needs_environment'=>false,'needs_context'=>false,'is_variadic'=>false,'is_safe'=>null,'is_safe_callback'=>null,'pre_escape'=>null,'preserves_safety'=>null,
                         'node_class'=>FilterExpression::class,'deprecated'=>false,'alternative'=>null,],$options);}
                function getName():string{return$this->name;}
                function getCallable(){return$this->callable;}
                function getNodeClass():string{return$this->options['node_class'];}
                function setArguments(array$arguments):void{$this->arguments=$arguments;}
                function getArguments():array{return$this->arguments;}
                function needsEnvironment():bool{return$this->options['needs_environment'];}
                function needsContext():bool{return$this->options['needs_context'];}
                function getSafe(Node$filterArgs):?array{if(null!==$this->options['is_safe']){return$this->options['is_safe'];}if(null!==$this->options['is_safe_callback']){return$this->options['is_safe_callback']($filterArgs);}return null;}
                function getPreservesSafety():?array{return$this->options['preserves_safety'];}
                function getPreEscape():?string{return$this->options['pre_escape'];}
                function isVariadic():bool{return$this->options['is_variadic'];}
                function isDeprecated():bool{return(bool)$this->options['deprecated'];}
                function getDeprecatedVersion():string{return \is_bool($this->options['deprecated'])?'':$this->options['deprecated'];}
                function getAlternative():?string{return$this->options['alternative'];}}
class GreaterBinary      extends AbstractBinary{
                function operator(Compiler$compiler):Compiler{return$compiler->raw('>');}}
class OrBinary           extends AbstractBinary{
                function operator(Compiler$compiler):Compiler{return$compiler->raw('||');}}
#[YieldReady]
class SetNode            extends Node implements NodeCaptureInterface{
                function __construct(bool$capture,Node$names,Node$values,int$lineno,?string$tag=null){$safe=false;if($capture){$safe=true;if($values instanceof TextNode){$values=new ConstantExpression($values->getAttribute('data'),$values->getTemplateLine());$capture=false;}else{$values=new CaptureNode($values
                         ,$values->getTemplateLine());$values->setAttribute('with_blocks',true);}}parent::__construct(['names'=>$names,'values'=>$values],['capture'=>$capture,'safe'=>$safe],$lineno,$tag);}
                function compile(Compiler$compiler):void{$compiler->addDebugInfo($this);if(\count($this->getNode('names'))>1){$compiler->write('[');foreach($this->getNode('names')as$idx=>$node){if($idx){$compiler->raw(',');}$compiler->subcompile($node);}$compiler->raw(']');}else{$compiler->subcompile($this->
                         getNode('names'),false);}$compiler->raw('=');if($this->getAttribute('capture')){$compiler->subcompile($this->getNode('values'));}else{if(\count($this->getNode('names'))>1){$compiler->write('[');foreach($this->getNode('values')as$idx=>$value){if($idx){$compiler->raw(',');}$compiler->
                         subcompile($value);}$compiler->raw(']');}else{if($this->getAttribute('safe')){$compiler->raw("(''===\$tmp=")->subcompile($this->getNode('values'))->raw(")?'':new Markup(\$tmp,\$this->env->getCharset())");}else{$compiler->subcompile($this->getNode('values'));}}$compiler->raw(';');}
                         $compiler->raw("\n");}}
interface NodeCaptureInterface{}
#[YieldReady]
class IfNode             extends Node{
                function __construct(Node$tests,?Node$else,int$lineno,?string$tag=null){$nodes=['tests'=>$tests];if(null!==$else){$nodes['else']=$else;}parent::__construct($nodes,[],$lineno,$tag);}
                function compile(Compiler$compiler):void{$compiler->addDebugInfo($this);for($i=0,$count=\count($this->getNode('tests'));$i<$count;$i+=2){if($i>0){$compiler->outdent()->write('}elseif(');}else{$compiler->write('if(');}$compiler->subcompile($this->getNode('tests')->getNode((string)$i))->raw(
                         "){\n")->indent();if($this->getNode('tests')->hasNode((string)($i+1))){$compiler->subcompile($this->getNode('tests')->getNode((string)($i+1)));}}if($this->hasNode('else')){$compiler->outdent()->write("}else{\n")->indent()->subcompile($this->getNode('else'));}$compiler->outdent()->
                         write("}\n");}}
class DefinedTest        extends TestExpression{
                function __construct(Node$node,string$name,?Node$arguments,int$lineno){if($node instanceof NameExpression){$node->setAttribute('is_defined_test',true);}elseif($node instanceof GetAttrExpression){$node->setAttribute('is_defined_test',true);$this->changeIgnoreStrictCheck($node);}elseif($node
                         instanceof BlockReferenceExpression){$node->setAttribute('is_defined_test',true);}elseif($node instanceof FunctionExpression&&'constant'===$node->getAttribute('name')){$node->setAttribute('is_defined_test',true);}elseif($node instanceof ConstantExpression||$node instanceof
                         ArrayExpression){$node=new ConstantExpression(true,$node->getTemplateLine());}elseif($node instanceof MethodCallExpression){$node->setAttribute('is_defined_test',true);}else{throw new SyntaxError('The "defined" test only works with simple variables.',$lineno);}parent::__construct($node
                         ,$name,$arguments,$lineno);}
        private function changeIgnoreStrictCheck(GetAttrExpression$node){$node->setAttribute('optimizable',false);$node->setAttribute('ignore_strict_check',true);if($node->getNode('node')instanceof GetAttrExpression){$this->changeIgnoreStrictCheck($node->getNode('node'));}}
                function compile(Compiler$compiler):void{$compiler->subcompile($this->getNode('node'));}}
class TwigTest          {private string$name;private$callable;private array$options;private array$arguments=[];
                function __construct(string$name,$callable=null,array$options=[]){$this->name=$name;$this->callable=$callable;$this->options=array_merge(['is_variadic'=>false,'node_class'=>TestExpression::class,'deprecated'=>false,'alternative'=>null,'one_mandatory_argument'=>false,],$options);}
                function getName():string{return$this->name;}
                function getCallable(){return$this->callable;}
                function getNodeClass():string{return$this->options['node_class'];}
                function setArguments(array$arguments):void{$this->arguments=$arguments;}
                function getArguments():array{return$this->arguments;}
                function isVariadic():bool{return(bool)$this->options['is_variadic'];}
                function isDeprecated():bool{return(bool)$this->options['deprecated'];}
                function getDeprecatedVersion():string{return \is_bool($this->options['deprecated'])?'':$this->options['deprecated'];}
                function getAlternative():?string{return$this->options['alternative'];}
                function hasOneMandatoryArgument():bool{return(bool)$this->options['one_mandatory_argument'];}}
#[YieldReady]
class ForNode            extends Node{private ForLoopNode$loop;
                function __construct(AssignNameExpression$keyTarget,AssignNameExpression$valueTarget,AbstractExpression$seq,?Node$ifexpr,Node$body,?Node$else,int$lineno,?string$tag=null){$body=new Node([$body,$this->loop=new ForLoopNode($lineno,$tag)]);$nodes=['key_target'=>$keyTarget,'value_target'=>
                         $valueTarget,'seq'=>$seq,'body'=>$body];if(null!==$else){$nodes['else']=$else;}parent::__construct($nodes,['with_loop'=>true],$lineno,$tag);}
                function compile(Compiler$compiler):void{$compiler->addDebugInfo($this)->write("\$context['_parent']=\$context;\n")->write("\$context['_seq']=CoreExtension::ensureTraversable(")->subcompile($this->getNode('seq'))->raw(");\n");if($this->hasNode('else')){$compiler->write(
                         "\$context['_iterated']=false;\n");}if($this->getAttribute('with_loop')){$compiler->write("\$context['loop']=[\n")->write("  'parent'=>\$context['_parent'],\n")->write("  'index0'=>0,\n")->write("  'index' =>1,\n")->write("  'first' =>true,\n")->write("];\n")->write(
                         "if(is_array(\$context['_seq'])||(is_object(\$context['_seq'])&&\$context['_seq']instanceof \Countable)){\n")->indent()->write("\$length=count(\$context['_seq']);\n")->write("\$context['loop']['revindex0']=\$length-1;\n")->write("\$context['loop']['revindex']=\$length;\n")->
                         write("\$context['loop']['length']=\$length;\n")->write("\$context['loop']['last']=1===\$length;\n")->outdent()->write("}\n");}$this->loop->setAttribute('else',$this->hasNode('else'));$this->loop->setAttribute('with_loop',$this->getAttribute('with_loop'));$compiler->write(
                         "foreach(\$context['_seq']as ")->subcompile($this->getNode('key_target'))->raw('=>')->subcompile($this->getNode('value_target'))->raw("){\n")->indent()->subcompile($this->getNode('body'))->outdent()->write("}\n");if($this->hasNode('else')){$compiler->write(
                         "if(!\$context['_iterated']){\n")->indent()->subcompile($this->getNode('else'))->outdent()->write("}\n");}$compiler->write("\$_parent=\$context['_parent'];\n");$compiler->write('unset($context[\'_seq\'],$context[\'_iterated\'],$context[\''.$this->getNode('key_target')->getAttribute
                         ('name').'\'],$context[\''.$this->getNode('value_target')->getAttribute('name').'\'],$context[\'_parent\'],$context[\'loop\']);'."\n");$compiler->write("\$context=array_intersect_key(\$context,\$_parent)+\$_parent;\n");}}
#[YieldReady]
class ForLoopNode        extends Node{
                function __construct(int$lineno,?string$tag=null){parent::__construct([],['with_loop'=>false,'ifexpr'=>false,'else'=>false],$lineno,$tag);}
                function compile(Compiler$compiler):void{if($this->getAttribute('else')){$compiler->write("\$context['_iterated']=true;\n");}if($this->getAttribute('with_loop')){$compiler->write("++\$context['loop']['index0'];\n")->write("++\$context['loop']['index'];\n")->write(
                         "\$context['loop']['first']=false;\n")->write("if(isset(\$context['loop']['length'])){\n")->indent()->write("--\$context['loop']['revindex0'];\n")->write("--\$context['loop']['revindex'];\n")->write("\$context['loop']['last']=0===\$context['loop']['revindex0'];\n")->outdent()->write(
                         "}\n");}}}
class MethodCallExpression
                         extends AbstractExpression{
                function __construct(AbstractExpression$node,string$method,ArrayExpression$arguments,int$lineno){parent::__construct(['node'=>$node,'arguments'=>$arguments],['method'=>$method,'safe'=>false,'is_defined_test'=>false],$lineno);if($node instanceof NameExpression){$node->setAttribute(
                         'always_defined',true);}}
                function compile(Compiler$compiler):void{if($this->getAttribute('is_defined_test')){$compiler->raw('method_exists($macros[')->repr($this->getNode('node')->getAttribute('name'))->raw('],')->repr($this->getAttribute('method'))->raw(')');return;}$compiler->raw('CoreExtension::callMacro($macros[')
                         ->repr($this->getNode('node')->getAttribute('name'))->raw('],')->repr($this->getAttribute('method'))->raw(',[');$first=true;foreach($this->getNode('arguments')->getKeyValuePairs()as$pair){if(!$first){$compiler->raw(',');}$first=false;$compiler->subcompile($pair['value']);}$compiler
                         ->raw('],')->repr($this->getTemplateLine())->raw(',$context,$this->getSourceContext())');}}
#[YieldReady]
class ModuleNode         extends Node{
                function __construct(Node$body,?AbstractExpression$parent,Node$blocks,Node$macros,Node$traits,$embeddedTemplates,Source$source){$nodes=['body'=>$body,'blocks'=>$blocks,'macros'=>$macros,'traits'=>$traits,'display_start'=>new Node(),'display_end'=>new Node(),'constructor_start'=>new Node(),
                         'constructor_end'=>new Node(),'class_end'=>new Node(),];if(null!==$parent){$nodes['parent']=$parent;}parent::__construct($nodes,['index'=>null,'embedded_templates'=>$embeddedTemplates,],1);$this->setSourceContext($source);}
                function setIndex($index){$this->setAttribute('index',$index);}
                function compile(Compiler$compiler):void{$this->compileTemplate($compiler);foreach($this->getAttribute('embedded_templates')as$template){$compiler->subcompile($template);}}
      protected function compileTemplate(Compiler$compiler){if(!$this->getAttribute('index')){$compiler->write('<?php');}$this->compileClassHeader($compiler);$this->compileConstructor($compiler);$this->compileGetParent($compiler);$this->compileDisplay($compiler);$compiler->subcompile($this->getNode('blocks'));
                         $this->compileMacros($compiler);$this->compileGetTemplateName($compiler);$this->compileIsTraitable($compiler);$this->compileDebugInfo($compiler);$this->compileGetSourceContext($compiler);$this->compileClassFooter($compiler);}
      protected function compileGetParent(Compiler$compiler){if(!$this->hasNode('parent')){return;}$parent=$this->getNode('parent');$compiler->write("protected function doGetParent(array \$context)\n","{\n")->indent()->addDebugInfo($parent)->write('return');if($parent instanceof ConstantExpression){$compiler->
                         subcompile($parent);}else{$compiler->raw('$this->loadTemplate(')->subcompile($parent)->raw(',')->repr($this->getSourceContext()->getName())->raw(',')->repr($parent->getTemplateLine())->raw(')');}$compiler->raw(";\n")->outdent()->write("}\n\n");}
      protected function compileClassHeader(Compiler$compiler){$compiler->write("\n\n");if(!$this->getAttribute('index')){$compiler->write("use Twig\Environment;\n")->write("use Twig\LoaderError;\n")->write("use Twig\RuntimeError;\n")->write("use Twig\CoreExtension;\n")->write("use Twig\SandboxExtension;\n")->
                         write("use Twig\Markup;\n")->write("use Twig\SecurityError;\n")->write("use Twig\SecurityNotAllowedTagError;\n")->write("use Twig\SecurityNotAllowedFilterError;\n")->write("use Twig\SecurityNotAllowedFunctionError;\n")->write("use Twig\Source;\n")->write("use Twig\Template;\n\n");}
                         $compiler->write('/* '.str_replace('*/','* /',$this->getSourceContext()->getName())." */\n")->write('class '.$compiler->getEnvironment()->getTemplateClass($this->getSourceContext()->getName(),$this->getAttribute('index')))->raw(" extends Template\n")->write("{\n")->indent()->write(
                         "private Source \$source;\n")->write("private array \$macros=[];\n\n");}
      protected function compileConstructor(Compiler$compiler){$compiler->write("function __construct(Environment \$env)\n","{\n")->indent()->subcompile($this->getNode('constructor_start'))->write("parent::__construct(\$env);\n\n")->write("\$this->source=\$this->getSourceContext();\n\n");if(!$this->
                         hasNode('parent')){$compiler->write("\$this->parent=false;\n\n");}$countTraits=\count($this->getNode('traits'));if($countTraits){foreach($this->getNode('traits')as$i=>$trait){$node=$trait->getNode('template');$compiler->addDebugInfo($node)->write(sprintf('$_trait_%s=$this->
                         loadTemplate(',$i))->subcompile($node)->raw(',')->repr($node->getTemplateName())->raw(',')->repr($node->getTemplateLine())->raw(");\n")->write(sprintf("if(!\$_trait_%s->isTraitable()){\n",$i))->indent()->write("throw new RuntimeError('Template \"'.")->subcompile($trait->getNode(
                         'template'))->raw(".'\" cannot be used as a trait.',")->repr($node->getTemplateLine())->raw(",\$this->source);\n")->outdent()->write("}\n")->write(sprintf("\$_trait_%s_blocks=\$_trait_%s->getBlocks();\n\n",$i,$i));foreach($trait->getNode('targets')as$key=>$value){$compiler->write(
                         sprintf('if(!isset($_trait_%s_blocks[',$i))->string($key)->raw("])){\n")->indent()->write("throw new RuntimeError('Block ")->string($key)->raw(' is not defined in trait ')->subcompile($trait->getNode('template'))->raw(".',")->repr($node->getTemplateLine())->raw(",\$this->source);\n")->
                         outdent()->write("}\n\n")->write(sprintf('$_trait_%s_blocks[',$i))->subcompile($value)->raw(sprintf(']=$_trait_%s_blocks[',$i))->string($key)->raw(sprintf(']; unset($_trait_%s_blocks[',$i))->string($key)->raw("]);\n\n");}}if($countTraits>1){$compiler->write(
                         "\$this->traits=array_merge(\n")->indent();for($i=0;$i<$countTraits;++$i){$compiler->write(sprintf('$_trait_%s_blocks'.($i==$countTraits-1?'':',')."\n",$i));}$compiler->outdent()->write(");\n\n");}else{$compiler->write("\$this->traits=\$_trait_0_blocks;\n\n");}$compiler->write(
                         "\$this->blocks=array_merge(\n")->indent()->write("\$this->traits,\n")->write("[\n");}else{$compiler->write("\$this->blocks=[\n");}$compiler->indent();foreach($this->getNode('blocks')as$name=>$node){$compiler->write(sprintf("'%s'=>[\$this,'block_%s'],\n",$name,$name));}if($countTraits){
                         $compiler->outdent()->write("]\n")->outdent()->write(");\n");}else{$compiler->outdent()->write("];\n");}$compiler->subcompile($this->getNode('constructor_end'))->outdent()->write("}\n\n");}
      protected function compileDisplay(Compiler$compiler){$compiler->write("protected function doDisplay(array \$context,array \$blocks=[])\n","{\n")->indent()->write("\$macros=\$this->macros;\n")->subcompile($this->getNode('display_start'))->subcompile($this->getNode('body'));if($this->hasNode('parent')){
                         $parent=$this->getNode('parent');$compiler->addDebugInfo($parent);if($parent instanceof ConstantExpression){$compiler->write('$this->parent=$this->loadTemplate(')->subcompile($parent)->raw(',')->repr($this->getSourceContext()->getName())->raw(',')->repr($parent->getTemplateLine())->
                         raw(");\n");}$compiler->write('yield from ');if($parent instanceof ConstantExpression){$compiler->raw('$this->parent');}else{$compiler->raw('$this->getParent($context)');}$compiler->raw("->unwrap()->yield(\$context,array_merge(\$this->blocks,\$blocks));\n");}else{$compiler->write(
                         "return; yield '';\n");}$compiler->subcompile($this->getNode('display_end'))->outdent()->write("}\n\n");}
      protected function compileClassFooter(Compiler$compiler){$compiler->subcompile($this->getNode('class_end'))->outdent()->write("}\n");}
      protected function compileMacros(Compiler$compiler){$compiler->subcompile($this->getNode('macros'));}
      protected function compileGetTemplateName(Compiler$compiler){$compiler->write("/**\n")->write("*@codeCoverageIgnore\n")->write(" */\n")->write("function getTemplateName()\n","{\n")->indent()->write('return')->repr($this->getSourceContext()->getName())->raw(";\n")->outdent()->write("}\n\n");}
      protected function compileIsTraitable(Compiler$compiler){$traitable=!$this->hasNode('parent')&&0===\count($this->getNode('macros'));if($traitable){if($this->getNode('body')instanceof BodyNode){$nodes=$this->getNode('body')->getNode('0');}else{$nodes=$this->getNode('body');}if(!\count($nodes)){$nodes=new
                         Node([$nodes]);}foreach($nodes as$node){if(!\count($node)){continue;}if($node instanceof TextNode&&ctype_space($node->getAttribute('data'))){continue;}if($node instanceof BlockReferenceNode){continue;}$traitable=false;break;}}if($traitable){return;}$compiler->write("/**\n")->write(
                         "*@codeCoverageIgnore\n")->write(" */\n")->write("function isTraitable()\n","{\n")->indent()->write("return false;\n")->outdent()->write("}\n\n");}
      protected function compileDebugInfo(Compiler$compiler){$compiler->write("/**\n")->write("*@codeCoverageIgnore\n")->write(" */\n")->write("function getDebugInfo()\n","{\n")->indent()->write(sprintf("return %s;\n",str_replace("\n",'',var_export(array_reverse($compiler->getDebugInfo(),true),true))))
                         ->outdent()->write("}\n\n");}
      protected function compileGetSourceContext(Compiler$compiler){$compiler->write("function getSourceContext()\n","{\n")->indent()->write('return new Source(')->string($compiler->getEnvironment()->isDebug()?$this->getSourceContext()->getCode():'')->raw(',')->string($this->getSourceContext()->getName(
                         ))->raw(',')->string($this->getSourceContext()->getPath())->raw(");\n")->outdent()->write("}\n");}
      protected function compileLoadTemplate(Compiler$compiler,$node,$var){if($node instanceof ConstantExpression){$compiler->write(sprintf('%s=$this->loadTemplate(',$var))->subcompile($node)->raw(',')->repr($node->getTemplateName())->raw(',')->repr($node->getTemplateLine())->raw(");\n");}else{throw new
                         \LogicException('Trait templates can only be constant nodes.');}}
        private function hasNodeOutputNodes(Node$node):bool{if($node instanceof NodeOutputInterface){return true;}foreach($node as$child){if($this->hasNodeOutputNodes($child)){return true;}}return false;}}
class NodeTraverser     {private Environment$env;private array$visitors=[];
                function __construct(Environment$env,array$visitors=[]){$this->env=$env;foreach($visitors as$visitor){$this->addVisitor($visitor);}}
                function addVisitor(NodeVisitorInterface$visitor):void{$this->visitors[$visitor->getPriority()][]=$visitor;}
                function traverse(Node$node):Node{ksort($this->visitors);foreach($this->visitors as$visitors){foreach($visitors as$visitor){$node=$this->traverseForVisitor($visitor,$node);}}return$node;}
        private function traverseForVisitor(NodeVisitorInterface$visitor,Node$node):?Node{$node=$visitor->enterNode($node,$this->env);foreach($node as$k=>$n){if(null!==$m=$this->traverseForVisitor($visitor,$n)){if($m!==$n){$node->setNode($k,$m);}}else{$node->removeNode($k);}}return$visitor->leaveNode($node,
                         $this->env);}}
class Compiler{private?int$lastLine;private string$source;private int$indentation;private Environment$env;private array$debugInfo;private int$sourceOffset;private int$sourceLine;private int$varNameSalt;private string|false$didUseEcho=false;private array$didUseEchoStack=[];
                function __construct(Environment$env){$this->env=$env;$this->reset();}
                function getEnvironment():Environment{return$this->env;}
                function getSource():string{return$this->source;}
                function reset(int$indentation=0){$this->lastLine=null;$this->source='';$this->debugInfo=[];$this->sourceOffset=0;$this->sourceLine=1;$this->indentation=$indentation;$this->varNameSalt=0;return$this;}
                function compile(Node$node,int$indentation=0){$this->reset($indentation);$this->didUseEchoStack[]=$this->didUseEcho;try{$this->didUseEcho=false;$node->compile($this);if($this->didUseEcho){throw new \LogicException('Using "%s" is not supported; use "yield" instead in "%s".',$this->didUseEcho,
                         \get_class($node));}return$this;}finally{$this->didUseEcho=array_pop($this->didUseEchoStack);}}
                function subcompile(Node$node,bool$raw=true){if(!$raw){$this->source.=str_repeat(' ',$this->indentation*4);}$this->didUseEchoStack[]=$this->didUseEcho;try{$this->didUseEcho=false;$node->compile($this);if($this->didUseEcho){throw new \LogicException(sprintf(
                         'Using "%s" is not supported; use "yield" instead in "%s".',$this->didUseEcho,\get_class($node)));}return$this;}finally{$this->didUseEcho=array_pop($this->didUseEchoStack);}}
                function raw(string$string){$this->checkForEcho($string);$this->source.=$string;return$this;}
                function write(...$strings){foreach($strings as$string){$this->checkForEcho($string);$this->source.=str_repeat(' ',$this->indentation*4).$string;}return$this;}
                function string(string$value){$this->source.=sprintf('"%s"',addcslashes($value,"\0\t\"\$\\"));return$this;}
                function repr($value){if(\is_int($value)||\is_float($value)){if(false!==$locale=setlocale(\LC_NUMERIC,'0')){setlocale(\LC_NUMERIC,'C');}$this->raw(var_export($value,true));if(false!==$locale){setlocale(\LC_NUMERIC,$locale);}}elseif(null===$value){$this->raw('null');}elseif(\is_bool($value)){
                         $this->raw($value?'true':'false');}elseif(\is_array($value)){$this->raw('array(');$first=true;foreach($value as$key=>$v){if(!$first){$this->raw(',');}$first=false;$this->repr($key);$this->raw('=>');$this->repr($v);}$this->raw(')');}else{$this->string($value);}return$this;}
                function addDebugInfo(Node$node){if($node->getTemplateLine()!=$this->lastLine){$this->write(sprintf("// line %d\n",$node->getTemplateLine()));$this->sourceLine+=substr_count($this->source,"\n",$this->sourceOffset);$this->sourceOffset=\strlen($this->source);$this->debugInfo[$this->sourceLine]=
                         $node->getTemplateLine();$this->lastLine=$node->getTemplateLine();}return$this;}
                function getDebugInfo():array{ksort($this->debugInfo);return$this->debugInfo;}
                function indent(int$step=1){$this->indentation+=$step;return$this;}
                function outdent(int$step=1){if($this->indentation<$step){throw new \LogicException('Unable to call outdent()as the indentation would become negative.');}$this->indentation -=$step;return$this;}
                function getVarName():string{return sprintf('__internal_compile_%d',$this->varNameSalt++);}
        private function checkForEcho(string$string):void{if($this->didUseEcho){return;}$this->didUseEcho=preg_match('/^\s*+(echo|print)\b/',$string,$m)?$m[1]:false;}}
class DefaultFilter      extends FilterExpression{
                function __construct(Node$node,ConstantExpression$filterName,Node$arguments,int$lineno,?string$tag=null){$default=new FilterExpression($node,new ConstantExpression('default',$node->getTemplateLine()),$arguments,$node->getTemplateLine());if('default'===$filterName->getAttribute('value')&&($node
                         instanceof NameExpression||$node instanceof GetAttrExpression)){$test=new DefinedTest(clone$node,'defined',new Node(),$node->getTemplateLine());$false=\count($arguments)?$arguments->getNode('0'):new ConstantExpression('',$node->getTemplateLine());$node=new ConditionalExpression($test,
                         $default,$false,$node->getTemplateLine());}else{$node=$default;}parent::__construct($node,$filterName,$arguments,$lineno,$tag);}
                function compile(Compiler$compiler):void{$compiler->subcompile($this->getNode('node'));}}
class ConditionalExpression
                         extends AbstractExpression{
                function __construct(AbstractExpression$expr1,AbstractExpression$expr2,AbstractExpression$expr3,int$lineno){parent::__construct(['expr1'=>$expr1,'expr2'=>$expr2,'expr3'=>$expr3],[],$lineno);}
                function compile(Compiler$compiler):void{if($this->getNode('expr1')===$this->getNode('expr2')){$compiler->raw('((')->subcompile($this->getNode('expr1'))->raw(')?:(')->subcompile($this->getNode('expr3'))->raw('))');}else{$compiler->raw('((')->subcompile($this->getNode('expr1'))->raw(')?(')->
                         subcompile($this->getNode('expr2'))->raw('):(')->subcompile($this->getNode('expr3'))->raw('))');}}}
#[YieldReady]
class MacroNode          extends Node{const VARARGS_NAME='varargs';
                function __construct(string$name,Node$body,Node$arguments,int$lineno,?string$tag=null){foreach($arguments as$argumentName=>$argument){if(self::VARARGS_NAME===$argumentName){throw new SyntaxError(sprintf(
                         'The argument "%s" in macro "%s" cannot be defined because the variable "%s" is reserved for arbitrary arguments.',self::VARARGS_NAME,$name,self::VARARGS_NAME),$argument->getTemplateLine(),$argument->getSourceContext());}}parent::__construct(['body'=>$body,'arguments'=>$arguments],
                         ['name'=>$name],$lineno,$tag);}
                function compile(Compiler$compiler):void{$compiler->addDebugInfo($this)->write(sprintf('function macro_%s(',$this->getAttribute('name')));$count=\count($this->getNode('arguments'));$pos=0;foreach($this->getNode('arguments')as$name=>$default){$compiler->raw('$__'.$name.'__=')->subcompile(
                         $default);if(++$pos<$count){$compiler->raw(',');}}if($count){$compiler->raw(',');}$compiler->raw('...$__varargs__')->raw(")\n")->write("{\n")->indent()->write("\$macros=\$this->macros;\n")->write("\$context=\$this->env->mergeGlobals([\n")->indent();foreach($this->getNode('arguments')as
                         $name=>$default){$compiler->write('')->string($name)->raw('=>$__'.$name.'__')->raw(",\n");}$compiler->write('')->string(self::VARARGS_NAME)->raw('=>')->raw("\$__varargs__,\n")->outdent()->write("]);\n\n")->write("\$blocks=[];\n\n")->write('return')->subcompile(new CaptureNode($this->
                         getNode('body'),$this->getNode('body')->lineno,$this->getNode('body')->tag))->raw("\n")->outdent()->write("}\n\n");}}
class CaptureNode        extends Node{
                function __construct(Node$body,int$lineno,?string$tag=null){parent::__construct(['body'=>$body],['raw'=>false,'with_blocks'=>false],$lineno,$tag);}
                function compile(Compiler$compiler):void{if($this->getAttribute('raw')){$compiler->raw("implode('',iterator_to_array(");}else{$compiler->raw("(''===\$tmp=implode('',iterator_to_array(");}if($this->getAttribute('with_blocks')){$compiler->raw("(function()use(&\$context,\$macros,\$blocks){\n");}
                         else{$compiler->raw("(function()use(&\$context,\$macros){\n");}$compiler->indent()->subcompile($this->getNode('body'))->outdent()->write("})()??new \EmptyIterator()))");if(!$this->getAttribute('raw')){$compiler->raw(")?'':new Markup(\$tmp,\$this->env->getCharset())");}$compiler->raw(
                         ';');}}
class NotEqualBinary     extends AbstractBinary{
                function operator(Compiler$compiler):Compiler{return$compiler->raw('!=');}}
class ConcatBinary       extends AbstractBinary{
                function operator(Compiler$compiler):Compiler{return$compiler->raw('.');}}
#[YieldReady]
class IncludeNode extends Node implements NodeOutputInterface{
                 function __construct(AbstractExpression$expr,?AbstractExpression$variables,bool$only,bool$ignoreMissing,int$lineno,?string$tag=null){$nodes=['expr'=>$expr];if(null!==$variables){$nodes['variables']=$variables;}parent::__construct($nodes,['only'=>$only,'ignore_missing'=>$ignoreMissing],$lineno,
                          $tag);}
                 function compile(Compiler$compiler):void{$compiler->addDebugInfo($this);if($this->getAttribute('ignore_missing')){$template=$compiler->getVarName();$compiler->write(sprintf("$%s=null;\n",$template))->write("try{\n")->indent()->write(sprintf('$%s=',$template));$this->addGetTemplate($compiler);
                          $compiler->raw(";\n")->outdent()->write("}catch(LoaderError \$e){\n")->indent()->write("// ignore missing template\n")->outdent()->write("}\n")->write(sprintf("if($%s){\n",$template))->indent()->write(sprintf('yield from$%s->unwrap()->yield(',$template));$this->addTemplateArguments(
                          $compiler);$compiler->raw(");\n")->outdent()->write("}\n");}else{$compiler->write('yield from ');$this->addGetTemplate($compiler);$compiler->raw('->unwrap()->yield(');$this->addTemplateArguments($compiler);$compiler->raw(");\n");}}
       protected function addGetTemplate(Compiler$compiler){$compiler->write('$this->loadTemplate(')->subcompile($this->getNode('expr'))->raw(',')->repr($this->getTemplateName())->raw(',')->repr($this->getTemplateLine())->raw(')');}
       protected function addTemplateArguments(Compiler$compiler){if(!$this->hasNode('variables')){$compiler->raw(false===$this->getAttribute('only')?'$context':'[]');}elseif(false===$this->getAttribute('only')){$compiler->raw('CoreExtension::arrayMerge($context,')->subcompile($this->getNode('variables'))->
                          raw(')');}else{$compiler->raw('CoreExtension::toArray(');$compiler->subcompile($this->getNode('variables'));$compiler->raw(')');}}}
abstract
class Template            {const ANY_CALL='any';const ARRAY_CALL='array';const METHOD_CALL='method';protected$parent;protected$parents=[];protected$env;protected$blocks=[];protected$traits=[];protected$extensions=[];protected$sandbox;
                 function __construct(Environment$env){$this->env=$env;$this->extensions=$env->getExtensions();}
abstract         function getTemplateName();
abstract         function getDebugInfo();
abstract         function getSourceContext();
abstract
       protected function doDisplay(array$context,array$blocks=[]);
                 function getParent(array$context){if(null!==$this->parent){return$this->parent;}try{if(!$parent=$this->doGetParent($context)){return false;}if($parent instanceof self||$parent instanceof TemplateWrapper){return$this->parents[$parent->getSourceContext()->getName()]=$parent;}if(!isset($this->
                          parents[$parent])){$this->parents[$parent]=$this->loadTemplate($parent);}}catch(LoaderError$e){$e->setSourceContext(null);$e->guess();throw$e;}return$this->parents[$parent];}
       protected function doGetParent(array$context){return false;}
                 function isTraitable(){return true;}
                 function displayParentBlock($name,array$context,array$blocks=[]){foreach($this->yieldParentBlock($name,$context,$blocks)as$data){echo$data;}}
                 function displayBlock($name,array$context,array$blocks=[],$useBlocks=true,?self$templateContext=null){foreach($this->yieldBlock($name,$context,$blocks,$useBlocks,$templateContext)as$data){echo$data;}}
                 function renderParentBlock($name,array$context,array$blocks=[]){$content='';foreach($this->yieldParentBlock($name,$context,$blocks)as$data){$content.=$data;}return$content;}
                 function renderBlock($name,array$context,array$blocks=[],$useBlocks=true){$content='';foreach($this->yieldBlock($name,$context,$blocks,$useBlocks)as$data){$content.=$data;}return$content;}
                 function hasBlock($name,array$context,array$blocks=[]){if(isset($blocks[$name])){return$blocks[$name][0]instanceof self;}if(isset($this->blocks[$name])){return true;}if($parent=$this->getParent($context)){return$parent->hasBlock($name,$context);}return false;}
                 function getBlockNames(array$context,array$blocks=[]){$names=array_merge(array_keys($blocks),array_keys($this->blocks));if($parent=$this->getParent($context)){$names=array_merge($names,$parent->getBlockNames($context));}return array_unique($names);}
       protected function loadTemplate($template,$templateName=null,$line=null,$index=null){try{if(\is_array($template)){return$this->env->resolveTemplate($template);}if($template instanceof self||$template instanceof TemplateWrapper){return$template;}if($template===$this->getTemplateName()){$class=static::
                          class;if(false!==$pos=strrpos($class,'___',-1)){$class=substr($class,0,$pos);}}else{$class=$this->env->getTemplateClass($template);}return$this->env->loadTemplate($class,$template,$index);}catch(Error$e){if(!$e->getSourceContext()){$e->setSourceContext($templateName?new Source('',
                          $templateName):$this->getSourceContext());}if($e->getTemplateLine()>0){throw$e;}if(!$line){$e->guess();}else{$e->setTemplateLine($line);}throw$e;}}
                 function unwrap(){return$this;}
                 function getBlocks(){return$this->blocks;}
                 function display(array$context,array$blocks=[]):void{foreach($this->yield($context,$blocks)as$data){echo$data;}}
                 function render(array$context):string{$content='';foreach($this->yield($context)as$data){$content.=$data;}return$content;}
                 function yield(array$context,array$blocks=[]):iterable{$context=$this->env->mergeGlobals($context);$blocks=array_merge($this->blocks,$blocks);try{yield from$this->doDisplay($context,$blocks);}catch(Error$e){if(!$e->getSourceContext()){$e->setSourceContext($this->getSourceContext());}if(-1===$e
                          ->getTemplateLine()){$e->guess();}throw$e;}catch(\Throwable$e){$e=new RuntimeError(sprintf('An exception has been thrown during the rendering of a template("%s").',$e->getMessage()),-1,$this->getSourceContext(),$e);$e->guess();throw$e;}}
                 function yieldBlock($name,array$context,array$blocks=[],$useBlocks=true,?self$templateContext=null){if($useBlocks&&isset($blocks[$name])){$template=$blocks[$name][0];$block=$blocks[$name][1];}elseif(isset($this->blocks[$name])){$template=$this->blocks[$name][0];$block=$this->blocks[$name][1];}
                          else{$template=null;$block=null;}if(null!==$template&&!$template instanceof self){throw new \LogicException('A block must be a method on a \Twig\Template instance.');}if(null!==$template){try{yield from$template->$block($context,$blocks);}catch(Error$e){if(!$e->getSourceContext()){$e
                          ->setSourceContext($template->getSourceContext());}if(-1===$e->getTemplateLine()){$e->guess();}throw$e;}catch(\Throwable$e){$e=new RuntimeError(sprintf('An exception has been thrown during the rendering of a template("%s").',$e->getMessage()),-1,$template->getSourceContext(),$e);$e->
                          guess();throw$e;}}elseif($parent=$this->getParent($context)){yield from$parent->yieldBlock($name,$context,array_merge($this->blocks,$blocks),false,$templateContext??$this);}elseif(isset($blocks[$name])){throw new RuntimeError(sprintf(
                          'Block "%s" should not call parent()in "%s" as the block does not exist in the parent template "%s".',$name,$blocks[$name][0]->getTemplateName(),$this->getTemplateName()),-1,$blocks[$name][0]->getSourceContext());}else{throw new RuntimeError(sprintf(
                          'Block "%s" on template "%s" does not exist.',$name,$this->getTemplateName()),-1,($templateContext??$this)->getSourceContext());}}
                 function yieldParentBlock($name,array$context,array$blocks=[]){if(isset($this->traits[$name])){yield from$this->traits[$name][0]->yieldBlock($name,$context,$blocks,false);}elseif($parent=$this->getParent($context)){yield from$parent->unwrap()->yieldBlock($name,$context,$blocks,false);}else{
                          throw new RuntimeError(sprintf('The template has no parent and no traits defining the "%s" block.',$name),-1,$this->getSourceContext());}}}
class TemplateWrapper     {private?Environment$env;private?Template$template;
                 function __construct(Environment$env,Template$template){$this->env=$env;$this->template=$template;}
                 function render(array$context=[]):string{return$this->template->render($context);}
                 function display(array$context=[]){$this->template->display($context,\func_get_args()[1]??[]);}
                 function hasBlock(string$name,array$context=[]):bool{return$this->template->hasBlock($name,$context);}
                 function getBlockNames(array$context=[]):array{return$this->template->getBlockNames($context);}
                 function renderBlock(string$name,array$context=[]):string{return$this->template->renderBlock($name,$this->env->mergeGlobals($context));}
                 function displayBlock(string$name,array$context=[]){$context=$this->env->mergeGlobals($context);foreach($this->template->yieldBlock($name,$context)as$data){echo$data;}}
                 function getSourceContext():Source{return$this->template->getSourceContext();}
                 function getTemplateName():string{return$this->template->getTemplateName();}
                 function unwrap(){return$this->template;}}
class Lexer               {private bool$isInitialized=false;private array$tokens;private string$code;private int$cursor;private int$lineno;private int$end;private int$state;private array$states;private array$brackets;private Environment$env;private Source$source;private array$options;private array$regexes;
                          private int$position;private array$positions;private int$currentVarBlockLine;const STATE_DATA=0;const STATE_BLOCK=1;const STATE_VAR=2;const STATE_STRING=3;const STATE_INTERPOLATION=4;const REGEX_NAME='/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/A';const REGEX_NUMBER=
                          '/[0-9]+(?:\.[0-9]+)?([Ee][\+\-][0-9]+)?/A';const REGEX_STRING='/"([^#"\\\\]*(?:\\\\.[^#"\\\\]*)*)"|\'([^\'\\\\]*(?:\\\\.[^\'\\\\]*)*)\'/As';const REGEX_DQ_STRING_DELIM='/"/A';const REGEX_DQ_STRING_PART='/[^#"\\\\]*(?:(?:\\\\.|#(?!\{))[^#"\\\\]*)*/As';const PUNCTUATION='()[]{}?:.,|';
                 function __construct(Environment$env,array$options=[]){$this->env=$env;$this->options=array_merge(['tag_comment'=>['{#','#}'],'tag_block'=>['{%','%}'],'tag_variable'=>['{{','}}'],'whitespace_trim'=>'-','whitespace_line_trim'=>'~','whitespace_line_chars'=>' \t\0\x0B','interpolation'=>['#{','}']
                          ,],$options);}
         private function initialize(){if($this->isInitialized){return;}$this->isInitialized=true;$this->regexes=['lex_var'=>'{\s*(?:'.preg_quote($this->options['whitespace_trim'].$this->options['tag_variable'][1]).'\s*'.'|'.preg_quote($this->options['whitespace_line_trim'].$this->options['tag_variable'][1]).
                          '['.$this->options['whitespace_line_chars'].']*'.'|'.preg_quote($this->options['tag_variable'][1]).')}Ax','lex_block'=>'{\s*(?:'.preg_quote($this->options['whitespace_trim'].$this->options['tag_block'][1]).'\s*\n?'.'|'.preg_quote($this->options['whitespace_line_trim'].$this->options[
                          'tag_block'][1]).'['.$this->options['whitespace_line_chars'].']*'.'|'.preg_quote($this->options['tag_block'][1]).'\n?'.')}Ax','lex_raw_data'=>'{'.preg_quote($this->options['tag_block'][0]).'('.$this->options['whitespace_trim'].'|'.$this->options['whitespace_line_trim'].
                          ')?\s*endverbatim\s*'.'(?:'.preg_quote($this->options['whitespace_trim'].$this->options['tag_block'][1]).'\s*'.'|'.preg_quote($this->options['whitespace_line_trim'].$this->options['tag_block'][1]).'['.$this->options['whitespace_line_chars'].']*'.'|'.preg_quote($this->options[
                          'tag_block'][1]).')}sx','operator'=>$this->getOperatorRegex(),'lex_comment'=>'{(?:'.preg_quote($this->options['whitespace_trim'].$this->options['tag_comment'][1]).'\s*\n?'.'|'.preg_quote($this->options['whitespace_line_trim'].$this->options['tag_comment'][1]).'['.$this->options[
                          'whitespace_line_chars'].']*'.'|'.preg_quote($this->options['tag_comment'][1]).'\n?'.')}sx','lex_block_raw'=>'{\s*verbatim\s*(?:'.preg_quote($this->options['whitespace_trim'].$this->options['tag_block'][1]).'\s*'.'|'.preg_quote($this->options['whitespace_line_trim'].$this->options[
                          'tag_block'][1]).'['.$this->options['whitespace_line_chars'].']*'.'|'.preg_quote($this->options['tag_block'][1]).')}Asx','lex_block_line'=>'{\s*line\s+(\d+)\s*'.preg_quote($this->options['tag_block'][1]).'}As','lex_tokens_start'=>'{('.preg_quote($this->options['tag_variable'][0]).'|'.
                          preg_quote($this->options['tag_block'][0]).'|'.preg_quote($this->options['tag_comment'][0]).')('.preg_quote($this->options['whitespace_trim']).'|'.preg_quote($this->options['whitespace_line_trim']).')?}sx','interpolation_start'=>'{'.preg_quote($this->options['interpolation'][0]).
                          '\s*}A','interpolation_end'=>'{\s*'.preg_quote($this->options['interpolation'][1]).'}A',];}
                 function tokenize(Source$source):TokenStream{$this->initialize();$this->source=$source;$this->code=str_replace(["\r\n","\r"],"\n",$source->getCode());$this->cursor=0;$this->lineno=1;$this->end=\strlen($this->code);$this->tokens=[];$this->state=self::STATE_DATA;$this->states=[];$this->
                          brackets=[];$this->position=-1;preg_match_all($this->regexes['lex_tokens_start'],$this->code,$matches,\PREG_OFFSET_CAPTURE);$this->positions=$matches;while($this->cursor<$this->end){switch($this->state){case self::STATE_DATA:$this->lexData();break;case self::STATE_BLOCK:$this->
                          lexBlock();break;case self::STATE_VAR:$this->lexVar();break;case self::STATE_STRING:$this->lexString();break;case self::STATE_INTERPOLATION:$this->lexInterpolation();break;}}$this->pushToken(-1);if(!empty($this->brackets)){[$expect,$lineno]=array_pop($this->brackets);throw new
                          SyntaxError(sprintf('Unclosed "%s".',$expect),$lineno,$this->source);}return new TokenStream($this->tokens,$this->source);}
         private function lexData():void{if($this->position== \count($this->positions[0])-1){$this->pushToken(0,substr($this->code,$this->cursor));$this->cursor=$this->end;return;}$position=$this->positions[0][++$this->position];while($position[1]<$this->cursor){if($this->position== \count($this->positions[0])
                          -1){return;}$position=$this->positions[0][++$this->position];}$text=$textContent=substr($this->code,$this->cursor,$position[1]-$this->cursor);if(isset($this->positions[2][$this->position][0])){if($this->options['whitespace_trim']===$this->positions[2][$this->position][0]){$text=rtrim(
                          $text);}elseif($this->options['whitespace_line_trim']===$this->positions[2][$this->position][0]){$text=rtrim($text," \t\0\x0B");}}$this->pushToken(0,$text);$this->moveCursor($textContent.$position[0]);switch($this->positions[1][$this->position][0]){case$this->options['tag_comment'][0]
                          :$this->lexComment();break;case$this->options['tag_block'][0]:if(preg_match($this->regexes['lex_block_raw'],$this->code,$match,0,$this->cursor)){$this->moveCursor($match[0]);$this->lexRawData();}elseif(preg_match($this->regexes['lex_block_line'],$this->code,$match,0,$this->cursor)){
                          $this->moveCursor($match[0]);$this->lineno=(int)$match[1];}else{$this->pushToken(1);$this->pushState(self::STATE_BLOCK);$this->currentVarBlockLine=$this->lineno;}break;case$this->options['tag_variable'][0]:$this->pushToken(2);$this->pushState(self::STATE_VAR);$this->
                          currentVarBlockLine=$this->lineno;break;}}
         private function lexBlock():void{if(empty($this->brackets)&&preg_match($this->regexes['lex_block'],$this->code,$match,0,$this->cursor)){$this->pushToken(3);$this->moveCursor($match[0]);$this->popState();}else{$this->lexExpression();}}
         private function lexVar():void{if(empty($this->brackets)&&preg_match($this->regexes['lex_var'],$this->code,$match,0,$this->cursor)){$this->pushToken(4);$this->moveCursor($match[0]);$this->popState();}else{$this->lexExpression();}}
         private function lexExpression():void{if(preg_match('/\s+/A',$this->code,$match,0,$this->cursor)){$this->moveCursor($match[0]);if($this->cursor>=$this->end){throw new SyntaxError(sprintf('Unclosed "%s".',self::STATE_BLOCK===$this->state?'block':'variable'),$this->currentVarBlockLine,$this->source);}}
                          if('.'===$this->code[$this->cursor]&&($this->cursor+2<$this->end)&&'.'===$this->code[$this->cursor+1]&&'.'===$this->code[$this->cursor+2]){$this->pushToken(Token::SPREAD_TYPE,'...');$this->moveCursor('...');}elseif('='===$this->code[$this->cursor]&&'>'===$this->code[$this->cursor+1]){
                          $this->pushToken(Token::ARROW_TYPE,'=>');$this->moveCursor('=>');}elseif(preg_match($this->regexes['operator'],$this->code,$match,0,$this->cursor)){$this->pushToken(8,preg_replace('/\s+/',' ',$match[0]));$this->moveCursor($match[0]);}elseif(preg_match(self::REGEX_NAME,$this->code,
                          $match,0,$this->cursor)){$this->pushToken(5,$match[0]);$this->moveCursor($match[0]);}elseif(preg_match(self::REGEX_NUMBER,$this->code,$match,0,$this->cursor)){$number=(float)$match[0];if(ctype_digit($match[0])&&$number<= \PHP_INT_MAX){$number=(int)$match[0];}$this->pushToken(6,$number
                          );$this->moveCursor($match[0]);}elseif(str_contains(self::PUNCTUATION,$this->code[$this->cursor])){if(str_contains('([{',$this->code[$this->cursor])){$this->brackets[]=[$this->code[$this->cursor],$this->lineno];}elseif(str_contains(')]}',$this->code[$this->cursor])){if(empty($this->
                          brackets)){throw new SyntaxError(sprintf('Unexpected "%s".',$this->code[$this->cursor]),$this->lineno,$this->source);}[$expect,$lineno]=array_pop($this->brackets);if($this->code[$this->cursor]!= strtr($expect,'([{',')]}')){throw new SyntaxError(sprintf('Unclosed "%s".',$expect),
                          $lineno,$this->source);}}$this->pushToken(9,$this->code[$this->cursor]);++$this->cursor;}elseif(preg_match(self::REGEX_STRING,$this->code,$match,0,$this->cursor)){$this->pushToken(7,stripcslashes(substr($match[0],1,-1)));$this->moveCursor($match[0]);}elseif(preg_match(self::
                          REGEX_DQ_STRING_DELIM,$this->code,$match,0,$this->cursor)){$this->brackets[]=['"',$this->lineno];$this->pushState(self::STATE_STRING);$this->moveCursor($match[0]);}else{throw new SyntaxError(sprintf('Unexpected character "%s".',$this->code[$this->cursor]),$this->lineno,$this->source);
                          }}
         private function lexRawData():void{if(!preg_match($this->regexes['lex_raw_data'],$this->code,$match,\PREG_OFFSET_CAPTURE,$this->cursor)){throw new SyntaxError('Unexpected end of file: Unclosed "verbatim" block.',$this->lineno,$this->source);}$text=substr($this->code,$this->cursor,$match[0][1]-$this->
                          cursor);$this->moveCursor($text.$match[0][0]);if(isset($match[1][0])){if($this->options['whitespace_trim']===$match[1][0]){$text=rtrim($text);}else{$text=rtrim($text," \t\0\x0B");}}$this->pushToken(0,$text);}
         private function lexComment():void{if(!preg_match($this->regexes['lex_comment'],$this->code,$match,\PREG_OFFSET_CAPTURE,$this->cursor)){throw new SyntaxError('Unclosed comment.',$this->lineno,$this->source);}$this->moveCursor(substr($this->code,$this->cursor,$match[0][1]-$this->cursor).$match[0][0]);}
         private function lexString():void{if(preg_match($this->regexes['interpolation_start'],$this->code,$match,0,$this->cursor)){$this->brackets[]=[$this->options['interpolation'][0],$this->lineno];$this->pushToken(10);$this->moveCursor($match[0]);$this->pushState(self::STATE_INTERPOLATION);}elseif(
                          preg_match(self::REGEX_DQ_STRING_PART,$this->code,$match,0,$this->cursor)&&''!==$match[0]){$this->pushToken(7,stripcslashes($match[0]));$this->moveCursor($match[0]);}elseif(preg_match(self::REGEX_DQ_STRING_DELIM,$this->code,$match,0,$this->cursor)){[$expect,$lineno]=array_pop($this->
                          brackets);if('"'!=$this->code[$this->cursor]){throw new SyntaxError(sprintf('Unclosed "%s".',$expect),$lineno,$this->source);}$this->popState();++$this->cursor;}else{throw new SyntaxError(sprintf('Unexpected character "%s".',$this->code[$this->cursor]),$this->lineno,$this->source);}}
         private function lexInterpolation():void{$bracket=end($this->brackets);if($this->options['interpolation'][0]===$bracket[0]&&preg_match($this->regexes['interpolation_end'],$this->code,$match,0,$this->cursor)){array_pop($this->brackets);$this->pushToken(11);$this->moveCursor($match[0]);$this->popState(
                          );}else{$this->lexExpression();}}
         private function pushToken($type,$value=''):void{if(0===$type&&''===$value){return;}$this->tokens[]=new Token($type,$value,$this->lineno);}
         private function moveCursor($text):void{$this->cursor+=\strlen($text);$this->lineno+=substr_count($text,"\n");}
         private function getOperatorRegex():string{$operators=array_merge(['='],array_keys($this->env->getUnaryOperators()),array_keys($this->env->getBinaryOperators()));$operators=array_combine($operators,array_map('strlen',$operators));arsort($operators);$regex=[];foreach($operators as$operator=>$length){$r
                          =preg_quote($operator,'/');if(ctype_alpha($operator[$length-1])){$r.='(?=[\s()\[{])';}if(ctype_alpha($operator[0])){$r='(?<![\.\|])'.$r;}$r=preg_replace('/\s+/','\s+',$r);$regex[]=$r;}return'/'.implode('|',$regex).'/A';}
         private function pushState($state):void{$this->states[]=$this->state;$this->state=$state;}
         private function popState():void{if(0===\count($this->states)){throw new \LogicException('Cannot pop state without a previous state.');}$this->state=array_pop($this->states);}}
class Token               {private$value;private$type;private$lineno;const EOF_TYPE=-1;const TEXT_TYPE=0;const BLOCK_START_TYPE=1;const VAR_START_TYPE=2;const BLOCK_END_TYPE=3;const VAR_END_TYPE=4;const NAME_TYPE=5;const NUMBER_TYPE=6;const STRING_TYPE=7;const OPERATOR_TYPE=8;const PUNCTUATION_TYPE=9;const
                          INTERPOLATION_START_TYPE=10;const INTERPOLATION_END_TYPE=11;const ARROW_TYPE=12;const SPREAD_TYPE=13;
                 function __construct(int$type,$value,int$lineno){$this->type=$type;$this->value=$value;$this->lineno=$lineno;}
                 function __toString(){return sprintf('%s(%s)',self::typeToString($this->type,true),$this->value);}
                 function test($type,$values=null):bool{if(null===$values&&!\is_int($type)){$values=$type;$type=self::NAME_TYPE;}return($this->type===$type)&&(null===$values||(\is_array($values)&&\in_array($this->value,$values))||$this->value==$values);}
                 function getLine():int{return$this->lineno;}
                 function getType():int{return$this->type;}
                 function getValue(){return$this->value;}
          static function typeToString(int$type,bool$short=false):string{$name=match($type){self::EOF_TYPE=>'EOF_TYPE',self::TEXT_TYPE=>'TEXT_TYPE',self::BLOCK_START_TYPE=>'BLOCK_START_TYPE',self::VAR_START_TYPE=>'VAR_START_TYPE',self::BLOCK_END_TYPE=>'BLOCK_END_TYPE',self::VAR_END_TYPE=>'VAR_END_TYPE',self::
                          NAME_TYPE=> 'NAME_TYPE',self::NUMBER_TYPE=>'NUMBER_TYPE',self::STRING_TYPE=>'STRING_TYPE',self::OPERATOR_TYPE=>'OPERATOR_TYPE',self::PUNCTUATION_TYPE=>'PUNCTUATION_TYPE',self::INTERPOLATION_START_TYPE=>'INTERPOLATION_START_TYPE',self::INTERPOLATION_END_TYPE=>'INTERPOLATION_END_TYPE',
                          self::ARROW_TYPE=>'ARROW_TYPE',self::SPREAD_TYPE=>'SPREAD_TYPE',default=>throw new \LogicException(sprintf('Token of type "%s" does not exist.',$type)),};return$short?$name:'Twig\Token::'.$name;}
          static function typeToEnglish(int$type):string{return match($type){self::EOF_TYPE=>'end of template',self::TEXT_TYPE=>'text',self::BLOCK_START_TYPE=>'begin of statement block',self::VAR_START_TYPE=>'begin of print statement',self::BLOCK_END_TYPE=>'end of statement block',self::VAR_END_TYPE=>
                          'end of print statement',self::NAME_TYPE=>'name',self::NUMBER_TYPE=>'number',self::STRING_TYPE=>'string',self::OPERATOR_TYPE=>'operator',self::PUNCTUATION_TYPE=>'punctuation',self::INTERPOLATION_START_TYPE=>'begin of string interpolation',self::INTERPOLATION_END_TYPE=>
                          'end of string interpolation',self::ARROW_TYPE=>'arrow function',self::SPREAD_TYPE=>'spread operator',default=>throw new \LogicException(sprintf('Token of type "%s" does not exist.',$type)),};}}
class TokenStream         {private array$tokens;private int$current=0;private Source$source;
                 function __construct(array$tokens,?Source$source=null){$this->tokens=$tokens;$this->source=$source?: new Source('','');}
                 function __toString(){return implode("\n",$this->tokens);}
                 function injectTokens(array$tokens){$this->tokens=array_merge(\array_slice($this->tokens,0,$this->current),$tokens,\array_slice($this->tokens,$this->current));}
                 function next():Token{if(!isset($this->tokens[++$this->current])){throw new SyntaxError('Unexpected end of template.',$this->tokens[$this->current-1]->getLine(),$this->source);}return$this->tokens[$this->current-1];}
                 function nextIf($primary,$secondary=null){return$this->tokens[$this->current]->test($primary,$secondary)?$this->next():null;}
                 function expect($type,$value=null,?string$message=null):Token{$token=$this->tokens[$this->current];if(!$token->test($type,$value)){$line=$token->getLine();throw new SyntaxError(sprintf('%sUnexpected token "%s"%s("%s" expected%s).',$message?$message.'. ':'',Token::typeToEnglish($token->getType
                          ()),$token->getValue()? sprintf(' of value "%s"',$token->getValue()):'',Token::typeToEnglish($type),$value? sprintf(' with value "%s"',$value):''),$line,$this->source);}$this->next();return$token;}
                 function look(int$number=1):Token{if(!isset($this->tokens[$this->current +$number])){throw new SyntaxError('Unexpected end of template.',$this->tokens[$this->current +$number-1]->getLine(),$this->source);}return$this->tokens[$this->current +$number];}
                 function test($primary,$secondary=null):bool{return$this->tokens[$this->current]->test($primary,$secondary);}
                 function isEOF():bool{return-1===$this->tokens[$this->current]->getType();}
                 function getCurrent():Token{return$this->tokens[$this->current];}
                 function getSourceContext():Source{return$this->source;}}
class AndBinary           extends AbstractBinary{
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('&&');}}
class SubBinary           extends AbstractBinary{
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('-');}}
class RangeBinary         extends AbstractBinary{
                 function compile(Compiler$compiler):void{$compiler->raw('range(')->subcompile($this->getNode('left'))->raw(',')->subcompile($this->getNode('right'))->raw(')');}
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('..');}}
class AddBinary           extends AbstractBinary{
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('+');}}
class LessBinary          extends AbstractBinary{
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('<');}}
class LessEqualBinary     extends AbstractBinary{
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('<=');}}
class GreaterEqualBinary  extends AbstractBinary{
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('>=');}}
abstract
class AbstractUnary       extends AbstractExpression{
                 function __construct(Node$node,int$lineno){parent::__construct(['node'=>$node],[],$lineno);}
                 function compile(Compiler$compiler):void{$compiler->raw(' ');$this->operator($compiler);$compiler->subcompile($this->getNode('node'));}
abstract         function operator(Compiler$compiler):Compiler;}
class NotUnary            extends AbstractUnary{
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('!');}}
class NullTest            extends TestExpression{
                 function compile(Compiler$compiler):void{$compiler->raw('(null===')->subcompile($this->getNode('node'))->raw(')');}}
class NegUnary            extends AbstractUnary{
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('-');}}
class DivBinary           extends AbstractBinary{
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('/');}}
class MulBinary           extends AbstractBinary{
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('*');}}
class BitwiseAndBinary    extends AbstractBinary{
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('&');}}
class BitwiseOrBinary     extends AbstractBinary{
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('|');}}
class BitwiseXorBinary    extends AbstractBinary{
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('^');}}
class EndsWithBinary      extends AbstractBinary{
                 function compile(Compiler$compiler):void{$left=$compiler->getVarName();$right=$compiler->getVarName();$compiler->raw(sprintf('(is_string($%s=',$left))->subcompile($this->getNode('left'))->raw(sprintf(')&&is_string($%s=',$right))->subcompile($this->getNode('right'))->raw(sprintf(
                          ')&&str_ends_with($%1$s,$%2$s))',$left,$right));}
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('');}}
class FloorDivBinary      extends AbstractBinary{
                 function compile(Compiler$compiler):void{$compiler->raw('(int)floor(');parent::compile($compiler);$compiler->raw(')');}
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('/');}}
class HasEveryBinary      extends AbstractBinary{
                 function compile(Compiler$compiler):void{$compiler->raw('CoreExtension::arrayEvery($this->env,')->subcompile($this->getNode('left'))->raw(',')->subcompile($this->getNode('right'))->raw(')');}
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('');}}
class HasSomeBinary extends AbstractBinary{
                 function compile(Compiler$compiler):void{$compiler->raw('CoreExtension::arraySome($this->env,')->subcompile($this->getNode('left'))->raw(',')->subcompile($this->getNode('right'))->raw(')');}
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('');}}
class InBinary            extends AbstractBinary{
                 function compile(Compiler$compiler):void{$compiler->raw('CoreExtension::inFilter(')->subcompile($this->getNode('left'))->raw(',')->subcompile($this->getNode('right'))->raw(')');}
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('in');}}
class MatchesBinary       extends AbstractBinary{
                 function compile(Compiler$compiler):void{$compiler->raw('CoreExtension::matches(')->subcompile($this->getNode('right'))->raw(',')->subcompile($this->getNode('left'))->raw(')');}
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('');}}
class ModBinary           extends AbstractBinary{
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('%');}}
class NotInBinary         extends AbstractBinary{
                 function compile(Compiler$compiler):void{$compiler->raw('!CoreExtension::inFilter(')->subcompile($this->getNode('left'))->raw(',')->subcompile($this->getNode('right'))->raw(')');}
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('not in');}}
class PowerBinary         extends AbstractBinary{
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('**');}}
class SpaceshipBinary     extends AbstractBinary{
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('<=>');}}
class StartsWithBinary    extends AbstractBinary{
                 function compile(Compiler$compiler):void{$left=$compiler->getVarName();$right=$compiler->getVarName();$compiler->raw(sprintf('(is_string($%s=',$left))->subcompile($this->getNode('left'))->raw(sprintf(')&&is_string($%s=',$right))->subcompile($this->getNode('right'))->raw(sprintf(')&&
                          str_starts_with($%1$s,$%2$s))',$left,$right));}
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('');}}
class PosUnary            extends AbstractUnary{
                 function operator(Compiler$compiler):Compiler{return$compiler->raw('+');}}
class ConstantTest        extends TestExpression{
                 function compile(Compiler$compiler):void{$compiler->raw('(')->subcompile($this->getNode('node'))->raw('===constant(');if($this->getNode('arguments')->hasNode('1')){$compiler->raw('get_class(')->subcompile($this->getNode('arguments')->getNode('1'))->raw(')."::".');}$compiler->subcompile($this->
                          getNode('arguments')->getNode('0'))->raw('))');}}
class DivisiblebyTest     extends TestExpression{
                 function compile(Compiler$compiler):void{$compiler->raw('(0== ')->subcompile($this->getNode('node'))->raw(' % ')->subcompile($this->getNode('arguments')->getNode('0'))->raw(')');}}
class EvenTest            extends TestExpression{
                 function compile(Compiler$compiler):void{$compiler->raw('(')->subcompile($this->getNode('node'))->raw(' % 2== 0')->raw(')');}}
class OddTest             extends TestExpression{
                 function compile(Compiler$compiler):void{$compiler->raw('(')->subcompile($this->getNode('node'))->raw(' % 2!= 0')->raw(')');}}
class SameasTest          extends TestExpression{
                 function compile(Compiler$compiler):void{$compiler->raw('(')->subcompile($this->getNode('node'))->raw('===')->subcompile($this->getNode('arguments')->getNode('0'))->raw(')');}}
class ArrowFunctionExpression
                          extends AbstractExpression{
                 function __construct(AbstractExpression$expr,Node$names,$lineno,$tag=null){parent::__construct(['expr'=>$expr,'names'=>$names],[],$lineno,$tag);}
                 function compile(Compiler$compiler):void{$compiler->addDebugInfo($this)->raw('function(');foreach($this->getNode('names')as$i=>$name){if($i){$compiler->raw(',');}$compiler->raw('$__')->raw($name->getAttribute('name'))->raw('__');}$compiler->raw(')use($context,$macros){');foreach($this->getNode
                          ('names')as$name){$compiler->raw('$context["')->raw($name->getAttribute('name'))->raw('"]=$__')->raw($name->getAttribute('name'))->raw('__; ');}$compiler->raw('return')->subcompile($this->getNode('expr'))->raw('; }');}}
class InlinePrint         extends AbstractExpression{
                 function __construct(Node$node,int$lineno){parent::__construct(['node'=>$node],[],$lineno);}
                 function compile(Compiler$compiler):void{$compiler->raw('yield ')->subcompile($this->getNode('node'));}}
class NullCoalesceExpression
                          extends ConditionalExpression{
                 function __construct(Node$left,Node$right,int$lineno){$test=new DefinedTest(clone$left,'defined',new Node(),$left->getTemplateLine());if(!$left instanceof BlockReferenceExpression){$test=new AndBinary($test,new NotUnary(new NullTest($left,'null',new Node(),$left->getTemplateLine()),$left->
                          getTemplateLine()),$left->getTemplateLine());}parent::__construct($test,$left,$right,$lineno);}
                 function compile(Compiler$compiler):void{if($this->getNode('expr2')instanceof NameExpression){$this->getNode('expr2')->setAttribute('always_defined',true);$compiler->raw('((')->subcompile($this->getNode('expr2'))->raw(')??(')->subcompile($this->getNode('expr3'))->raw('))');}else{parent::
                          compile($compiler);}}}
class ParentExpression    extends AbstractExpression{
                 function __construct(string$name,int$lineno,?string$tag=null){parent::__construct([],['output'=>false,'name'=>$name],$lineno,$tag);}
                 function compile(Compiler$compiler):void{if($this->getAttribute('output')){$compiler->addDebugInfo($this)->write('yield from$this->yieldParentBlock(')->string($this->getAttribute('name'))->raw(",\$context,\$blocks);\n");}else{$compiler->raw('$this->renderParentBlock(')->string($this->
                          getAttribute('name'))->raw(',$context,$blocks)');}}}
class TempNameExpression  extends AbstractExpression{
                 function __construct(string$name,int$lineno){parent::__construct([],['name'=>$name],$lineno);}
                 function compile(Compiler$compiler):void{$compiler->raw('$_')->raw($this->getAttribute('name'))->raw('_');}}
class VariadicExpression  extends ArrayExpression{
                 function compile(Compiler$compiler):void{$compiler->raw('...');parent::compile($compiler);}}
#[YieldReady]
class AutoEscapeNode      extends Node{
                 function __construct($value,Node$body,int$lineno,string$tag='autoescape'){parent::__construct(['body'=>$body],['value'=>$value],$lineno,$tag);}
                 function compile(Compiler$compiler):void{$compiler->subcompile($this->getNode('body'));}}
#[YieldReady]
class CheckSecurityCallNode
                          extends Node{
                 function compile(Compiler$compiler){$compiler->write("\$this->sandbox=\$this->env->getExtension('\Twig\Extension\SandboxExtension');\n")->write("\$this->checkSecurity();\n");}}
#[YieldReady]
class CheckSecurityNode   extends Node{private array$usedFilters;private array$usedTags;private array$usedFunctions;
                 function __construct(array$usedFilters,array$usedTags,array$usedFunctions){$this->usedFilters=$usedFilters;$this->usedTags=$usedTags;$this->usedFunctions=$usedFunctions;parent::__construct();}
                 function compile(Compiler$compiler):void{$tags=$filters=$functions=[];foreach(['tags','filters','functions']as$type){foreach($this->{'used'.ucfirst($type)}as$name=>$node){if($node instanceof Node){${$type}[$name]=$node->getTemplateLine();}else{${$type}[$node]=null;}}}$compiler->write("\n")->
                          write("function checkSecurity()\n")->write("{\n")->indent()->write('static$tags=')->repr(array_filter($tags))->raw(";\n")->write('static$filters=')->repr(array_filter($filters))->raw(";\n")->write('static$functions=')->repr(array_filter($functions))->raw(";\n\n")->write("try{\n")->
                          indent()->write("\$this->sandbox->checkSecurity(\n")->indent()->write(!$tags? "[],\n":"['".implode("','",array_keys($tags))."'],\n")->write(!$filters? "[],\n":"['".implode("','",array_keys($filters))."'],\n")->write(!$functions? "[],\n":"['".implode("','",array_keys($functions))."'],
                          \n")->write("\$this->source\n")->outdent()->write(");\n")->outdent()->write("}catch(SecurityError \$e){\n")->indent()->write("\$e->setSourceContext(\$this->source);\n\n")->write("if(\$e instanceof SecurityNotAllowedTagError&&isset(\$tags[\$e->getTagName()])){\n")->indent()->write(
                          "\$e->setTemplateLine(\$tags[\$e->getTagName()]);\n")->outdent()->write("}elseif(\$e instanceof SecurityNotAllowedFilterError&&isset(\$filters[\$e->getFilterName()])){\n")->indent()->write("\$e->setTemplateLine(\$filters[\$e->getFilterName()]);\n")->outdent()->write("}elseif(\$e
                          instanceof SecurityNotAllowedFunctionError&&isset(\$functions[\$e->getFunctionName()])){\n")->indent()->write("\$e->setTemplateLine(\$functions[\$e->getFunctionName()]);\n")->outdent()->write("}\n\n")->write("throw \$e;\n")->outdent()->write("}\n\n")->outdent()->write("}\n");}}
#[YieldReady]
class CheckToStringNode   extends AbstractExpression{
                 function __construct(AbstractExpression$expr){parent::__construct(['expr'=>$expr],[],$expr->getTemplateLine(),$expr->getNodeTag());}
                 function compile(Compiler$compiler):void{$expr=$this->getNode('expr');$compiler->raw('$this->sandbox->ensureToStringAllowed(')->subcompile($expr)->raw(',')->repr($expr->getTemplateLine())->raw(',$this->source)');}}
#[YieldReady]
class DeprecatedNode      extends Node{
                 function __construct(AbstractExpression$expr,int$lineno,?string$tag=null){parent::__construct(['expr'=>$expr],[],$lineno,$tag);}
                 function compile(Compiler$compiler):void{$compiler->addDebugInfo($this);$expr=$this->getNode('expr');if($expr instanceof ConstantExpression){$compiler->write('@trigger_error(')->subcompile($expr);}else{$varName=$compiler->getVarName();$compiler->write(sprintf('$%s=',$varName))->subcompile(
                          $expr)->raw(";\n")->write(sprintf('@trigger_error($%s',$varName));}$compiler->raw('.')->string(sprintf('("%s" at line %d).',$this->getTemplateName(),$this->getTemplateLine()))->raw(",E_USER_DEPRECATED);\n");}}
#[YieldReady]
class DoNode              extends Node{
                 function __construct(AbstractExpression$expr,int$lineno,?string$tag=null){parent::__construct(['expr'=>$expr],[],$lineno,$tag);}
                 function compile(Compiler$compiler):void{$compiler->addDebugInfo($this)->write('')->subcompile($this->getNode('expr'))->raw(";\n");}}
#[YieldReady]
class EmbedNode           extends IncludeNode{
                 function __construct(string$name,int$index,?AbstractExpression$variables,bool$only,bool$ignoreMissing,int$lineno,?string$tag=null){parent::__construct(new ConstantExpression('not_used',$lineno),$variables,$only,$ignoreMissing,$lineno,$tag);$this->setAttribute('name',$name);$this->setAttribute(
                          'index',$index);}
       protected function addGetTemplate(Compiler$compiler):void{$compiler->write('$this->loadTemplate(')->string($this->getAttribute('name'))->raw(',')->repr($this->getTemplateName())->raw(',')->repr($this->getTemplateLine())->raw(',')->string($this->getAttribute('index'))->raw(')');}}
#[YieldReady]
class FlushNode           extends Node{
                 function __construct(int$lineno,string$tag){parent::__construct([],[],$lineno,$tag);}
                 function compile(Compiler$compiler):void{$compiler->addDebugInfo($this)->write("flush();\n");}}
#[YieldReady]
class SandboxNode         extends Node{
                 function __construct(Node$body,int$lineno,?string$tag=null){parent::__construct(['body'=>$body],[],$lineno,$tag);}
                 function compile(Compiler$compiler):void{$compiler         ->addDebugInfo($this)->write("if(!\$alreadySandboxed=\$this->sandbox->isSandboxed()){\n")->indent()->write("\$this->sandbox->enableSandbox();\n")->outdent()->write("}\n")->write("try{\n")->indent()->subcompile($this->getNode('body'))->
                         outdent()->write("}finally{\n")->indent()->write("if(!\$alreadySandboxed){\n")->indent()->write("\$this->sandbox->disableSandbox();\n")->outdent()->write("}\n")->outdent()->write("}\n");}}
#[YieldReady]
class WithNode            extends Node{
                 function __construct(Node$body,?Node$variables,bool$only,int$lineno,?string$tag=null){$nodes=['body'=>$body];if(null!==$variables){$nodes['variables']=$variables;}parent::__construct($nodes,['only'=>$only],$lineno,$tag);}
                 function compile(Compiler$compiler):void{$compiler->addDebugInfo($this);$parentContextName=$compiler->getVarName();$compiler->write(sprintf("\$%s=\$context;\n",$parentContextName));if($this->hasNode('variables')){$node=$this->getNode('variables');$varsName=$compiler->getVarName();$compiler->
                         write(sprintf('$%s=',$varsName))->subcompile($node)->raw(";\n")->write(sprintf("if(!is_iterable(\$%s)){\n",$varsName))->indent()->write("throw new RuntimeError('Variables passed to the \"with\" tag must be a hash.',")->repr($node->getTemplateLine())->raw(
                         ",\$this->getSourceContext());\n")->outdent()->write("}\n")->write(sprintf("\$%s=CoreExtension::toArray(\$%s);\n",$varsName,$varsName));if($this->getAttribute('only')){$compiler->write("\$context=[];\n");}$compiler->write(sprintf(
                         "\$context=\$this->env->mergeGlobals(array_merge(\$context,\$%s));\n",$varsName));}$compiler->subcompile($this->getNode('body'))->write(sprintf("\$context=\$%s;\n",$parentContextName));}}
abstract
class AbstractNodeVisitor
                          implements NodeVisitorInterface{
                 function enterNode(Node$node,Environment$env):Node{return$this->doEnterNode($node,$env);}
                 function leaveNode(Node$node,Environment$env):?Node{return$this->doLeaveNode($node,$env);}
abstract
       protected function doEnterNode(Node$node,Environment$env);
abstract
       protected function doLeaveNode(Node$node,Environment$env);}
class SandboxNodeVisitor  implements NodeVisitorInterface{private bool$inAModule=false;private array$tags;private array$filters;private array$functions;private bool$needsToStringWrap=false;
                 function enterNode(Node$node,Environment$env):Node{if($node instanceof ModuleNode){$this->inAModule=true;$this->tags=[];$this->filters=[];$this->functions=[];return$node;}elseif($this->inAModule){if($node->getNodeTag()&&!isset($this->tags[$node->getNodeTag()])){$this->tags[$node->getNodeTag()]
                          =$node;}if($node instanceof FilterExpression&&!isset($this->filters[$node->getNode('filter')->getAttribute('value')])){$this->filters[$node->getNode('filter')->getAttribute('value')]=$node;}if($node instanceof FunctionExpression&&!isset($this->functions[$node->getAttribute('name')])){
                          $this->functions[$node->getAttribute('name')]=$node;}if($node instanceof RangeBinary&&!isset($this->functions['range'])){$this->functions['range']=$node;}if($node instanceof PrintNode){$this->needsToStringWrap=true;$this->wrapNode($node,'expr');}if($node instanceof SetNode&&!$node->
                          getAttribute('capture')){$this->needsToStringWrap=true;}if($this->needsToStringWrap){if($node instanceof ConcatBinary){$this->wrapNode($node,'left');$this->wrapNode($node,'right');}if($node instanceof FilterExpression){$this->wrapNode($node,'node');$this->wrapArrayNode($node,
                          'arguments');}if($node instanceof FunctionExpression){$this->wrapArrayNode($node,'arguments');}}}return$node;}
                 function leaveNode(Node$node,Environment$env):?Node{if($node instanceof ModuleNode){$this->inAModule=false;$node->setNode('constructor_end',new Node([new CheckSecurityCallNode(),$node->getNode('constructor_end')]));$node->setNode('class_end',new Node([new CheckSecurityNode($this->filters,$this
                          ->tags,$this->functions),$node->getNode('class_end')]));}elseif($this->inAModule){if($node instanceof PrintNode||$node instanceof SetNode){$this->needsToStringWrap=false;}}return$node;}
         private function wrapNode(Node$node,string$name):void{$expr=$node->getNode($name);if($expr instanceof NameExpression||$expr instanceof GetAttrExpression){$node->setNode($name,new CheckToStringNode($expr));}}
         private function wrapArrayNode(Node$node,string$name):void{$args=$node->getNode($name);foreach($args as$name=>$_){$this->wrapNode($args,$name);}}
                 function getPriority():int{return 0;}}
class DebugExtension      extends AbstractExtension{
                 function getFunctions():array{$isDumpOutputHtmlSafe=\extension_loaded('xdebug')&&(false===\ini_get('xdebug.overload_var_dump')||\ini_get('xdebug.overload_var_dump'))&&(false===\ini_get('html_errors')||\ini_get('html_errors'))||'cli'===\PHP_SAPI;return[new TwigFunction('dump',[self::class,
                          'dump'],['is_safe'=>$isDumpOutputHtmlSafe?['html']:[],'needs_context'=>true,'needs_environment'=>true,'is_variadic'=>true]),];}
          static function dump(Environment$env,$context,...$vars){if(!$env->isDebug()){return;}ob_start();if(!$vars){$vars=[];foreach($context as$key=>$value){if(!$value instanceof Template&&!$value instanceof TemplateWrapper){$vars[$key]=$value;}}var_dump($vars);}else{var_dump(...$vars);}return ob_get_clean()
                          ;}}
interface GlobalsInterface{
                 function getGlobals():array;}
class ProfilerExtension   extends AbstractExtension{private array$actives=[];
                 function __construct(Profile$profile){$this->actives[]=$profile;}
                 function enter(Profile$profile){$this->actives[0]->addProfile($profile);array_unshift($this->actives,$profile);}
                 function leave(Profile$profile){$profile->leave();array_shift($this->actives);if(1===\count($this->actives)){$this->actives[0]->leave();}}
                 function getNodeVisitors():array{return[new ProfilerNodeVisitor(static::class)];}}
interface RuntimeExtensionInterface{}
class SandboxExtension    extends AbstractExtension{private bool$sandboxedGlobally;private bool$sandboxed=false;private SecurityPolicyInterface$policy;private?SourcePolicyInterface$sourcePolicy;
                 function __construct(SecurityPolicyInterface$policy,$sandboxed=false,?SourcePolicyInterface$sourcePolicy=null){$this->policy=$policy;$this->sandboxedGlobally=$sandboxed;$this->sourcePolicy=$sourcePolicy;}
                 function getTokenParsers():array{return[new SandboxTokenParser()];}
                 function getNodeVisitors():array{return[new SandboxNodeVisitor()];}
                 function enableSandbox():void{$this->sandboxed=true;}
                 function disableSandbox():void{$this->sandboxed=false;}
                 function isSandboxed(?Source$source=null):bool{return$this->sandboxedGlobally||$this->sandboxed||$this->isSourceSandboxed($source);}
                 function isSandboxedGlobally():bool{return$this->sandboxedGlobally;}
         private function isSourceSandboxed(?Source$source):bool{if(null===$source||null===$this->sourcePolicy){return false;}return$this->sourcePolicy->enableSandbox($source);}
                 function setSecurityPolicy(SecurityPolicyInterface$policy){$this->policy=$policy;}
                 function getSecurityPolicy():SecurityPolicyInterface{return$this->policy;}
                 function checkSecurity($tags,$filters,$functions,?Source$source=null):void{if($this->isSandboxed($source)){$this->policy->checkSecurity($tags,$filters,$functions);}}
                 function checkMethodAllowed($obj,$method,int$lineno=-1,?Source$source=null):void{if($this->isSandboxed($source)){try{$this->policy->checkMethodAllowed($obj,$method);}catch(SecurityNotAllowedMethodError$e){$e->setSourceContext($source);$e->setTemplateLine($lineno);throw$e;}}}
                 function checkPropertyAllowed($obj,$property,int$lineno=-1,?Source$source=null):void{if($this->isSandboxed($source)){try{$this->policy->checkPropertyAllowed($obj,$property);}catch(SecurityNotAllowedPropertyError$e){$e->setSourceContext($source);$e->setTemplateLine($lineno);throw$e;}}}
                 function ensureToStringAllowed($obj,int$lineno=-1,?Source$source=null){if($this->isSandboxed($source)&&\is_object($obj)&&method_exists($obj,'__toString')){try{$this->policy->checkMethodAllowed($obj,'__toString');}catch(SecurityNotAllowedMethodError$e){$e->setSourceContext($source);$e->
                          setTemplateLine($lineno);throw$e;}}return$obj;}}
class StringLoaderExtension
                          extends AbstractExtension{
                 function getFunctions():array{return[new TwigFunction('template_from_string',[self::class,'templateFromString'],['needs_environment'=>true]),];}
          static function templateFromString(Environment$env,$template,?string$name=null):TemplateWrapper{return$env->createTemplate((string)$template,$name);}}
class SandboxTokenParser  extends AbstractTokenParser{
                 function parse(Token$token):Node{$stream=$this->parser->getStream();$stream->expect(3);$body=$this->parser->subparse([$this,'decideBlockEnd'],true);$stream->expect(3);if(!$body instanceof IncludeNode){foreach($body as$node){if($node instanceof TextNode&&ctype_space($node->getAttribute('data')))
                          {continue;}if(!$node instanceof IncludeNode){throw new SyntaxError('Only "include" tags are allowed within a "sandbox" section.',$node->getTemplateLine(),$stream->getSourceContext());}}}return new SandboxNode($body,$token->getLine(),$this->getTag());}
                 function decideBlockEnd(Token$token):bool{return$token->test('endsandbox');}
                 function getTag():string{return'sandbox';}}
class NullCache           implements CacheInterface{
                 function generateKey(string$name,string$className):string{return'';}
                 function write(string$key,string$content):void{}
                 function load(string$key):void{}
                 function getTimestamp(string$key):int{return 0;}}
class LoaderError         extends Error{}
class SyntaxError         extends Error{
                 function addSuggestions(string$name,array$items):void{$alternatives=[];foreach($items as$item){$lev=levenshtein($name,$item);if($lev<= \strlen($name)/3||str_contains($item,$name)){$alternatives[$item]=$lev;}}if(!$alternatives){return;}asort($alternatives);$this->appendMessage(sprintf(
                          ' Did you mean "%s"?',implode('","',array_keys($alternatives))));}}
class ArrayLoader         implements LoaderInterface{private array$templates=[];
                 function __construct(array$templates=[]){$this->templates=$templates;}
                 function setTemplate(string$name,string$template):void{$this->templates[$name]=$template;}
                 function getSourceContext(string$name):Source{if(!isset($this->templates[$name])){throw new LoaderError(sprintf('Template "%s" is not defined.',$name));}return new Source($this->templates[$name],$name);}
                 function exists(string$name):bool{return isset($this->templates[$name]);}
                 function getCacheKey(string$name):string{if(!isset($this->templates[$name])){throw new LoaderError(sprintf('Template "%s" is not defined.',$name));}return$name.':'.$this->templates[$name];}
                 function isFresh(string$name,int$time):bool{if(!isset($this->templates[$name])){throw new LoaderError(sprintf('Template "%s" is not defined.',$name));}return true;}}
class ChainLoader         implements LoaderInterface{private array$hasSourceCache=[];private array$loaders=[];
                 function __construct(array$loaders=[]){foreach($loaders as$loader){$this->addLoader($loader);}}
                 function addLoader(LoaderInterface$loader):void{$this->loaders[]=$loader;$this->hasSourceCache=[];}
                 function getLoaders():array{return$this->loaders;}
                 function getSourceContext(string$name):Source{$exceptions=[];foreach($this->loaders as$loader){if(!$loader->exists($name)){continue;}try{return$loader->getSourceContext($name);}catch(LoaderError$e){$exceptions[]=$e->getMessage();}}throw new LoaderError(sprintf(
                          'Template "%s" is not defined%s.',$name,$exceptions?'('.implode(',',$exceptions).')':''));}
                 function exists(string$name):bool{if(isset($this->hasSourceCache[$name])){return$this->hasSourceCache[$name];}foreach($this->loaders as$loader){if($loader->exists($name)){return$this->hasSourceCache[$name]=true;}}return$this->hasSourceCache[$name]=false;}
                 function getCacheKey(string$name):string{$exceptions=[];foreach($this->loaders as$loader){if(!$loader->exists($name)){continue;}try{return$loader->getCacheKey($name);}catch(LoaderError$e){$exceptions[]=$loader::class.': '.$e->getMessage();}}throw new LoaderError(sprintf(
                          'Template "%s" is not defined%s.',$name,$exceptions?'('.implode(',',$exceptions).')':''));}
                 function isFresh(string$name,int$time):bool{$exceptions=[];foreach($this->loaders as$loader){if(!$loader->exists($name)){continue;}try{return$loader->isFresh($name,$time);}catch(LoaderError$e){$exceptions[]=$loader::class.': '.$e->getMessage();}}throw new LoaderError(sprintf(
                          'Template "%s" is not defined%s.',$name,$exceptions?'('.implode(',',$exceptions).')':''));}}
interface RuntimeLoaderInterface{
                 function load(string$class);}
class ContainerRuntimeLoader
                          implements RuntimeLoaderInterface{private ContainerInterface$container;
                 function __construct(ContainerInterface$container){$this->container=$container;}
                 function load(string$class){return$this->container->has($class)?$this->container->get($class):null;}}
class FactoryRuntimeLoader
                          implements RuntimeLoaderInterface{private array$map;
                 function __construct(array$map=[]){$this->map=$map;}
                 function load(string$class){if(!isset($this->map[$class])){return null;}$runtimeFactory=$this->map[$class];return$runtimeFactory();}}
class DeprecationCollector
                         {private Environment$twig;
                 function __construct(Environment$twig){$this->twig=$twig;}
                 function collectDir(string$dir,string$ext='.twig'):array{$iterator=new \RegexIterator(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir),\RecursiveIteratorIterator::LEAVES_ONLY),'{'.preg_quote($ext).'$}');return$this->collect(new TemplateDirIterator($iterator));}
                 function collect(\Traversable$iterator):array{$deprecations=[];set_error_handler(function($type,$msg)use(&$deprecations){if(\E_USER_DEPRECATED===$type){$deprecations[]=$msg;}});foreach($iterator as$name=>$contents){try{$this->twig->parse($this->twig->tokenize(new Source($contents,$name)));}
                          catch(SyntaxError$e){}}restore_error_handler();return$deprecations;}}
class TemplateDirIterator
                          extends \IteratorIterator{
                 function current():mixed{return file_get_contents(parent::current());}
                 function key():mixed{return(string)parent::key();}}
class FileExtensionEscapingStrategy{
          static function guess(string$name){if(\in_array(substr($name,-1),['/','\\'])){return'html';}if(str_ends_with($name,'.twig')){$name=substr($name,0,-5);}$extension=pathinfo($name,\PATHINFO_EXTENSION);return match($extension){'js'=>'js','css'=>'css','txt'=>false,default=>'html',};}}
class Markup              implements \Countable,\JsonSerializable{private string$content;private string$charset;
                 function __construct($content,$charset){$this->content=(string)$content;$this->charset=$charset;}
                 function __toString(){return$this->content;}
                 function count():int{return mb_strlen($this->content,$this->charset);}
                 function jsonSerialize():mixed{return$this->content;}}
class Source             {private string$code;private string$name;private string$path;
                 function __construct(string$code,string$name,string$path=''){$this->code=$code;$this->name=$name;$this->path=$path;}
                 function getCode():string{return$this->code;}
                 function getName():string{return$this->name;}
                 function getPath():string{return$this->path;}}
class ProfilerNodeVisitor
                          implements NodeVisitorInterface{private string$extensionName;private string$varName;
                 function __construct(string$extensionName){$this->extensionName=$extensionName;$this->varName=sprintf('__internal_%s',hash('xxh128',$extensionName));}
                 function enterNode(Node$node,Environment$env):Node{return$node;}
                 function leaveNode(Node$node,Environment$env):?Node{if($node instanceof ModuleNode){$node->setNode('display_start',new Node([new EnterProfileNode($this->extensionName,Profile::TEMPLATE,$node->getTemplateName(),$this->varName),$node->getNode('display_start')]));$node->setNode('display_end',new
                          Node([new LeaveProfileNode($this->varName),$node->getNode('display_end')]));}elseif($node instanceof BlockNode){$node->setNode('body',new BodyNode([new EnterProfileNode($this->extensionName,Profile::BLOCK,$node->getAttribute('name'),$this->varName),$node->getNode('body'),new
                          LeaveProfileNode($this->varName),]));}elseif($node instanceof MacroNode){$node->setNode('body',new BodyNode([new EnterProfileNode($this->extensionName,Profile::MACRO,$node->getAttribute('name'),$this->varName),$node->getNode('body'),new LeaveProfileNode($this->varName),]));}return$node
                          ;}
                 function getPriority():int{return 0;}}
class Profile             implements \IteratorAggregate,\Serializable{const ROOT='ROOT';const BLOCK='block';const TEMPLATE='template';const MACRO='macro';private string$template;private string$name;private string$type;private array$starts=[];private array$ends=[];private array$profiles=[];
                 function __construct(string$template='main',string$type=self::ROOT,string$name='main'){$this->template=$template;$this->type=$type;$this->name=str_starts_with($name,'__internal_')?'INTERNAL':$name;$this->enter();}
                 function getTemplate():string{return$this->template;}
                 function getType():string{return$this->type;}
                 function getName():string{return$this->name;}
                 function isRoot():bool{return self::ROOT===$this->type;}
                 function isTemplate():bool{return self::TEMPLATE===$this->type;}
                 function isBlock():bool{return self::BLOCK===$this->type;}
                 function isMacro():bool{return self::MACRO===$this->type;}
                 function getProfiles():array{return$this->profiles;}
                 function addProfile(self$profile):void{$this->profiles[]=$profile;}
                 function getDuration():float{if($this->isRoot()&&$this->profiles){$duration=0;foreach($this->profiles as$profile){$duration+=$profile->getDuration();}return$duration;}return isset($this->ends['wt'])&&isset($this->starts['wt'])?$this->ends['wt']-$this->starts['wt']:0;}
                 function getMemoryUsage():int{return isset($this->ends['mu'])&&isset($this->starts['mu'])?$this->ends['mu']-$this->starts['mu']:0;}
                 function getPeakMemoryUsage():int{return isset($this->ends['pmu'])&&isset($this->starts['pmu'])?$this->ends['pmu']-$this->starts['pmu']:0;}
                 function enter():void{$this->starts=['wt'=>microtime(true),'mu'=>memory_get_usage(),'pmu'=>memory_get_peak_usage(),];}
                 function leave():void{$this->ends=['wt'=>microtime(true),'mu'=>memory_get_usage(),'pmu'=>memory_get_peak_usage(),];}
                 function reset():void{$this->starts=$this->ends=$this->profiles=[];$this->enter();}
                 function getIterator():\Traversable{return new \ArrayIterator($this->profiles);}
                 function serialize():string{return serialize($this->__serialize());}
                 function unserialize($data):void{$this->__unserialize(unserialize($data));}
                 function __serialize():array{return[$this->template,$this->name,$this->type,$this->starts,$this->ends,$this->profiles];}
                 function __unserialize(array$data):void{[$this->template,$this->name,$this->type,$this->starts,$this->ends,$this->profiles]=$data;}}
class EnterProfileNode    extends Node{
                 function __construct(string$extensionName,string$type,string$name,string$varName){parent::__construct([],['extension_name'=>$extensionName,'name'=>$name,'type'=>$type,'var_name'=>$varName]);}
                 function compile(Compiler$compiler):void{$compiler->write(sprintf('$%s=$this->extensions[',$this->getAttribute('var_name')))->repr($this->getAttribute('extension_name'))->raw("];\n")->write(sprintf('$%s->enter($%s=new \Twig\Profiler\Profile($this->getTemplateName(),',$this->getAttribute(
                          'var_name'),$this->getAttribute('var_name').'_prof'))->repr($this->getAttribute('type'))->raw(',')->repr($this->getAttribute('name'))->raw("));\n\n");}}
class LeaveProfileNode    extends Node{
                 function __construct(string$varName){parent::__construct([],['var_name'=>$varName]);}
                 function compile(Compiler$compiler):void{$compiler->write("\n")->write(sprintf("\$%s->leave(\$%s);\n\n",$this->getAttribute('var_name'),$this->getAttribute('var_name').'_prof'));}}
abstract
class BaseDumper          {private float$root;
                 function dump(Profile$profile):string{return$this->dumpProfile($profile);}
abstract
       protected function formatTemplate(Profile$profile,$prefix):string;
abstract
       protected function formatNonTemplate(Profile$profile,$prefix):string;
abstract
       protected function formatTime(Profile$profile,$percent):string;
         private function dumpProfile(Profile$profile,$prefix='',$sibling=false):string{if($profile->isRoot()){$this->root=$profile->getDuration();$start=$profile->getName();}else{if($profile->isTemplate()){$start=$this->formatTemplate($profile,$prefix);}else{$start=$this->formatNonTemplate($profile,$prefix);}
                          $prefix.=$sibling?'│ ':'  ';}$percent=$this->root?$profile->getDuration()/$this->root*100:0;if($profile->getDuration()*1000<1){$str=$start."\n";}else{$str=sprintf("%s %s\n",$start,$this->formatTime($profile,$percent));}$nCount=\count($profile->getProfiles());foreach($profile as$i=>
                          $p){$str.=$this->dumpProfile($p,$prefix,$i+1!==$nCount);}return$str;}}
class BlackfireDumper{
                 function dump(Profile$profile):string{$data=[];$this->dumpProfile('main()',$profile,$data);$this->dumpChildren('main()',$profile,$data);$start=sprintf('%f',microtime(true));$str=<<<EOF
                          file-format: BlackfireProbe
                          cost-dimensions: wt mu pmu
                          request-start:$start
                          EOF;foreach($data as$name=>$values){$str.="$name//{$values['ct']}{$values['wt']}{$values['mu']}{$values['pmu']}\n";}return$str;}
         private function dumpChildren(string$parent,Profile$profile,&$data){foreach($profile as$p){if($p->isTemplate()){$name=$p->getTemplate();}else{$name=sprintf('%s::%s(%s)',$p->getTemplate(),$p->getType(),$p->getName());}$this->dumpProfile(sprintf('%s==>%s',$parent,$name),$p,$data);$this->dumpChildren(
                          $name,$p,$data);}}
         private function dumpProfile(string$edge,Profile$profile,&$data){if(isset($data[$edge])){++$data[$edge]['ct'];$data[$edge]['wt']+=floor($profile->getDuration()*1000000);$data[$edge]['mu']+=$profile->getMemoryUsage();$data[$edge]['pmu']+=$profile->getPeakMemoryUsage();}else{$data[$edge]=['ct'=>1,'wt'=>
                          floor($profile->getDuration()*1000000),'mu'=>$profile->getMemoryUsage(),'pmu'=>$profile->getPeakMemoryUsage(),];}}}
class HtmlDumper          extends BaseDumper{private static$colors=['block'=>'#dfd','macro'=>'#ddf','template'=>'#ffd','big'=>'#d44',];
                 function dump(Profile$profile):string{return'<pre>'.parent::dump($profile).'</pre>';}
       protected function formatTemplate(Profile$profile,$prefix):string{return sprintf('%s└<span style="background-color: %s">%s</span>',$prefix,self::$colors['template'],$profile->getTemplate());}
       protected function formatNonTemplate(Profile$profile,$prefix):string{return sprintf('%s└ %s::%s(<span style="background-color: %s">%s</span>)',$prefix,$profile->getTemplate(),$profile->getType(),self::$colors[$profile->getType()]??'auto',$profile->getName());}
       protected function formatTime(Profile$profile,$percent):string{return sprintf('<span style="color: %s">%.2fms/%.0f%%</span>',$percent>20? self::$colors['big']:'auto',$profile->getDuration()*1000,$percent);}}
class TextDumper          extends BaseDumper{
       protected function formatTemplate(Profile$profile,$prefix):string{return sprintf('%s└ %s',$prefix,$profile->getTemplate());}
       protected function formatNonTemplate(Profile$profile,$prefix):string{return sprintf('%s└ %s::%s(%s)',$prefix,$profile->getTemplate(),$profile->getType(),$profile->getName());}
       protected function formatTime(Profile$profile,$percent):string{return sprintf('%.2fms/%.0f%%',$profile->getDuration()*1000,$percent);}}
interface SourcePolicyInterface{
                 function enableSandbox(Source$source):bool;}
class SecurityError       extends Error{}
class SecurityNotAllowedFilterError
                          extends SecurityError{private string$filterName;
                 function __construct(string$message,string$functionName){parent::__construct($message);$this->filterName=$functionName;}
                 function getFilterName():string{return$this->filterName;}}
class SecurityNotAllowedFunctionError
                          extends SecurityError{private string$functionName;
                 function __construct(string$message,string$functionName){parent::__construct($message);$this->functionName=$functionName;}
                 function getFunctionName():string{return$this->functionName;}}
class SecurityNotAllowedMethodError
                          extends SecurityError{private string$className;private string$methodName;
                 function __construct(string$message,string$className,string$methodName){parent::__construct($message);$this->className=$className;$this->methodName=$methodName;}
                 function getClassName():string{return$this->className;}
                 function getMethodName(){return$this->methodName;}}
class SecurityNotAllowedPropertyError
                          extends SecurityError{private string$className;private string$propertyName;
                 function __construct(string$message,string$className,string$propertyName){parent::__construct($message);$this->className=$className;$this->propertyName=$propertyName;}
                 function getClassName():string{return$this->className;}
                 function getPropertyName(){return$this->propertyName;}}
class SecurityNotAllowedTagError
                          extends SecurityError{private string$tagName;
                 function __construct(string$message,string$tagName){parent::__construct($message);$this->tagName=$tagName;}
                 function getTagName():string{return$this->tagName;}}
interface SecurityPolicyInterface{
                 function checkSecurity($tags,$filters,$functions):void;
                 function checkMethodAllowed($obj,$method):void;
                 function checkPropertyAllowed($obj,$property):void;}
class SecurityPolicy      implements SecurityPolicyInterface{private array$allowedTags;private array$allowedFilters;private array$allowedMethods;private array$allowedProperties;private array$allowedFunctions;
                 function __construct(array$allowedTags=[],array$allowedFilters=[],array$allowedMethods=[],array$allowedProperties=[],array$allowedFunctions=[]){$this->allowedTags=$allowedTags;$this->allowedFilters=$allowedFilters;$this->setAllowedMethods($allowedMethods);$this->allowedProperties=
                          $allowedProperties;$this->allowedFunctions=$allowedFunctions;}
                 function setAllowedTags(array$tags):void{$this->allowedTags=$tags;}
                 function setAllowedFilters(array$filters):void{$this->allowedFilters=$filters;}
                 function setAllowedMethods(array$methods):void{$this->allowedMethods=[];foreach($methods as$class=>$m){$this->allowedMethods[$class]=array_map(fn($value)=>strtr($value,'ABCDEFGHIJKLMNOPQRSTUVWXYZ','abcdefghijklmnopqrstuvwxyz'),\is_array($m)?$m:[$m]);}}
                 function setAllowedProperties(array$properties):void{$this->allowedProperties=$properties;}
                 function setAllowedFunctions(array$functions):void{$this->allowedFunctions=$functions;}
                 function checkSecurity($tags,$filters,$functions):void{foreach($tags as$tag){if(!\in_array($tag,$this->allowedTags)){throw new SecurityNotAllowedTagError(sprintf('Tag "%s" is not allowed.',$tag),$tag);}}foreach($filters as$filter){if(!\in_array($filter,$this->allowedFilters)){throw new
                          SecurityNotAllowedFilterError(sprintf('Filter "%s" is not allowed.',$filter),$filter);}}foreach($functions as$function){if(!\in_array($function,$this->allowedFunctions)){throw new SecurityNotAllowedFunctionError(sprintf('Function "%s" is not allowed.',$function),$function);}}}
                 function checkMethodAllowed($obj,$method):void{if($obj instanceof Template||$obj instanceof Markup){return;}$allowed=false;$method=strtr($method,'ABCDEFGHIJKLMNOPQRSTUVWXYZ','abcdefghijklmnopqrstuvwxyz');foreach($this->allowedMethods as$class=>$methods){if($obj instanceof$class&&\in_array(
                          $method,$methods)){$allowed=true;break;}}if(!$allowed){$class=$obj::class;throw new SecurityNotAllowedMethodError(sprintf('Calling "%s" method on a "%s" object is not allowed.',$method,$class),$class,$method);}}
                 function checkPropertyAllowed($obj,$property):void{$allowed=false;foreach($this->allowedProperties as$class=>$properties){if($obj instanceof$class&&\in_array($property,\is_array($properties)?$properties:[$properties])){$allowed=true;break;}}if(!$allowed){$class=$obj::class;throw new
                          SecurityNotAllowedPropertyError(sprintf('Calling "%s" property on a "%s" object is not allowed.',$property,$class),$class,$property);}}}
