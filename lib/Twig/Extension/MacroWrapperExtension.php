<?php
namespace Twig\Extension;
class MacroWrapperExtension extends AbstractExtension{
        function getFunctions(){return[new \Twig\TwigFunction('Twig',[$this,'macro'],['needs_environment'=>true,]),];}
        protected $container=null;
        function macro(\Twig\Environment$twig,$template){return$this->getContainer($twig)->get($template);}
        function getContainer(\Twig\Environment$twig){if($this->container===null)$this->container=new MacroWrapperContainer($twig);return $this->container;}}
class MacroWrapper{
        protected $template=null;
        function __construct(\Twig\TemplateWrapper$template_wrapper){$this->template=$template_wrapper->unwrap();}
        function __call($method_name,$args){return$this->template->{'macro_'.$method_name};}}
class MacroWrapperContainer{
        const FOLDER='macros';
        protected $twig=null;
        protected $macros=[];
        function __construct(\Twig\Environment$twig){$this->setTwig($twig)->load();}
        function get($macro){if(isset($this->macros[$macro])){return$this->macros[$macro];}else{return null;}}
        function load(){
            foreach($this->getTwig()->getLoader()->getPaths()as$path){
                if(!is_dir($path.'/'.self::FOLDER)) continue;
                $this->loadMacros($path.'/'.self::FOLDER);}}
        function loadMacros($path){
            $files=scandir($path);
            foreach($files as$file)if($this->isTemplate($file))$this->loadMacro($file);}
        function loadMacro($file){
            $name=pathinfo($file,PATHINFO_FILENAME);
            if(!isset($this->macros[$name]))$this->macros[$name]=new MacroWrapper($this->getTwig()->load(self::FOLDER.'/'.$file));}
        function isTemplate($file){return in_array(pathinfo($file,PATHINFO_EXTENSION),['html','twig',]);}
        function setTwig(\Twig\Environment $twig){
            $this->twig=$twig;
            return $this;}
        function getTwig(){return $this->twig;}
        function __call($method_name,$args){return$this->get($method_name);}}