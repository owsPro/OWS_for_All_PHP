<?php
/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
/*

(c) & @author Rolf Joseph of this modified code and includes additional comments and documentation.

It improves the readability and understanding of the code. It also mentions that the code has been modified for the Websoccer owsPro project to take advantage of PHP 8 features.
"owsPro" is distributed WITHOUT ANY WARRANTY, including the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public Licence version 3 http://www.gnu.org/licenses/
The code provided is a modified version of the Twig environment class. Twig is a popular PHP templating engine. The modified code takes advantage of new features in PHP 8 and includes additional documentation.

Here are the main features and functions of the code:

The Environment class represents the Twig environment and is responsible for managing templates, loaders, extensions and other configuration options.
The class has several constants such as VERSION, VERSION_ID, MAJOR_VERSION, MINOR_VERSION, RELEASE_VERSION and EXTRA_VERSION which represent the version information of the Twig library.
The class has properties such as charset, loader, debug, autoReload, cache, lexer, parser, compiler, globals, resolvedGlobals, loadedTemplates, strictVariables, templateClassPrefix, originalCache, extensionSet, runtimeLoaders, runtimes, and optionsHash that store various configuration options and runtime data.
The class has methods like enableDebug(), disableDebug(), isDebug(), enableAutoReload(), disableAutoReload(), isAutoReload(), enableStrictVariables(), disableStrictVariables(), isStrictVariables(), getCache(), setCache(), getTemplateClass(), render(), display(), load(), loadTemplate(), createTemplate(), isTemplateFresh(), resolveTemplate(), setLexer(), tokenize(), setParser(), parse(), setCompiler(), compile(), compileSource(), setLoader(), getLoader(), setCharset(), getCharset(), hasExtension(), addRuntimeLoader(), getExtension(), getRuntime(), addExtension(), setExtensions(), getExtensions(), addTokenParser(), getTokenParsers(), getTokenParser(), registerUndefinedTokenParserCallback(), addNodeVisitor(), getNodeVisitors(), addFilter(), getFilter(), registerUndefinedFilterCallback(), getFilters(), addTest(), getTests(), getTest(), addFunction() getFunction(), registerUndefinedFunctionCallback(), getFunctions(), addGlobal(), getGlobals(), mergeGlobals(), getUnaryOperators(), getBinaryOperators(), and updateOptionsHash() which provide various functions related to template rendering, caching, extension management and more. */

namespace Twig;
use Twig\Compiler;
use Twig\Cache\CacheInterface;
use Twig\Cache\FilesystemCache;
use Twig\Cache\NullCache;
use Twig\Error\Error;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\CoreExtension;
use Twig\Extension\EscaperExtension;
use Twig\Extension\ExtensionInterface;
use Twig\Extension\OptimizerExtension;
use Twig\Loader\ArrayLoader;
use Twig\Loader\ChainLoader;
use Twig\Loader\LoaderInterface;
use Twig\Node\Expression\Binary\AbstractBinary;
use Twig\Node\Expression\Unary\AbstractUnary;
use Twig\Node\ModuleNode;
use Twig\Node\Node;
use Twig\NodeVisitor\NodeVisitorInterface;
use Twig\RuntimeLoader\RuntimeLoaderInterface;
use Twig\TokenParser\TokenParserInterface;
class Environment
{   public const VERSION = '4.0.0-DEV';																									// It represents the version of the code or application.
    public const VERSION_ID = 40000;																									// It represents the version of the code or application in a numeric format.
    public const MAJOR_VERSION = 4;																										// It represents the major version of the code or application.
    public const MINOR_VERSION = 0;																										// It represents the minor version of the code or application.
    public const RELEASE_VERSION = 0;																									// It represents the release version of the code or application.
    public const EXTRA_VERSION = 'DEV';																									// It represents any additional version information or development status.
    private string $charset;																											// It used to store the character encoding used by the code or application.
    private LoaderInterface $loader;																									// It used to load templates or resources required by the code or application.
    private bool $debug;																												// It used to determine whether the code or application is in debug mode.
    private bool $autoReload;																											// It used to determine whether the code or application should automatically reload templates or resources.
    private CacheInterface|string|false $cache;																							// It used to store the cache implementation or disable caching.
    private ?Lexer $lexer = null;																										// It used for lexical analysis or parsing of the code or templates.
    private ?Parser $parser = null;																										// It used for parsing the code or templates.
    private ?Compiler $compiler = null;																									// It used for compiling the parsed code or templates.
    private array $globals = [];																										// It used to store global variables accessible within the code or templates.
    private ?array $resolvedGlobals = null;																								// It used to store the resolved global variables.
    private array $loadedTemplates;																										// It used to store the loaded templates.
    private bool $strictVariables;																										// It used to determine whether strict variable handling is enabled.
    private string $templateClassPrefix = '__TwigTemplate_';																			// It is used as a prefix for the generated template class names.
    private CacheInterface|string|false $originalCache;																					// It store the original cache implementation.
    private ExtensionSet $extensionSet;																									// It store the extensions or plugins used by the code or application.
    private array $runtimeLoaders = [];																									// It store the runtime loaders.
    private array $runtimes = [];																										// It store the runtime environments.
    private string $optionsHash;																										// It store a hash value representing the options used by the code or application.
    public function __construct(LoaderInterface$loader,$options=[])
    {   $this->setLoader($loader);																										// Set the loader
        $options=array_merge(['debug'=>false,'charset'=>'UTF-8','strict_variables'=>false,'autoescape'=>'html','cache'=>false,'auto_reload'=>null,'optimizations'=>-1,],$options);	// Merge the options with default values
        $this->debug=(bool)$options['debug'];																							// Set the debug flag
        $this->setCharset($options['charset']??'UTF-8');																				// Set the charset
        $this->autoReload=null===$options['auto_reload']?$this->debug:(bool)$options['auto_reload'];									// Set the auto reload flag
        $this->strictVariables=(bool)$options['strict_variables'];																		// Set the strict variables flag
        $this->setCache($options['cache']);																								// Set the cache
        $this->extensionSet=new ExtensionSet();																							// Create a new ExtensionSet
        $this->addExtension(new CoreExtension());																						// Add the CoreExtension
        $this->addExtension(new EscaperExtension($options['autoescape']));																// Add the EscaperExtension with the autoescape option
        $this->addExtension(new OptimizerExtension($options['optimizations']));}														// Add the OptimizerExtension with the optimizations option
    public function enableDebug()																										:void
    {   $this->debug=true;																												// Enable debug mode
        $this->updateOptionsHash();}																									// Update options hash
    public function disableDebug()																										:void
    {   $this->debug=false;																												// Disable debug mode
        $this->updateOptionsHash();}																									// Update options hash
    public function isDebug()																											:bool
    {   return $this->debug;}																											// Check if debug mode is enabled
    public function enableAutoReload()																									:void
    {   $this->autoReload=\true;}																										// Enable auto reload
    public function disableAutoReload()																									:void
    {   $this->autoReload=\false;}																										// Disable auto reload feature
    public function isAutoReload()																										:bool
    {   return $this->autoReload;}																										// Check if auto reload is enabled
    public function enableStrictVariables()																								:void
    {   $this->strictVariables=\true;																									// Enable strict variables
        $this->updateOptionsHash();}																									// Update options hash
    public function disableStrictVariables()																							:void
    {   $this->strictVariables=\false;																									// Disable strict variables
        $this->updateOptionsHash();}																									// Update options hash
    public function isStrictVariables()																									:bool
    {   return $this->strictVariables;}																									// Check if strict variables mode is enabled.
    public function getCache($original=\true)																							:bool
    {   return $original?$this->originalCache:$this->cache;}																			// Return the original cache if $original is true, otherwise return the cache
    public function setCache($cache)																									:void
    {   if(\is_string($cache)){																											// Check if the cache is a string
            $this->originalCache=$cache;																								// Set the original cache value
            $this->cache=new FilesystemCache($cache,$this->autoReload?FilesystemCache::FORCE_BYTECODE_INVALIDATION:0);}					// Create a new FilesystemCache instance with the provided cache directory and set the appropriate bytecode invalidation flag based on the autoReload property
        elseif(\false===$cache){																										// Check if the cache is set to false
            $this->originalCache=$cache;																								// Check if the cache is false
            $this->cache=new \NullCache();}																								// Create a new NullCache instance
        elseif($cache instanceof \CacheInterface)$this->originalCache=$this->cache=$cache;												// Check if the cache implements the CacheInterface
        else throw new \LogicException('Cache can only be a string, false, or a \Twig\Cache\CacheInterface implementation.');}			// Throw an exception if the cache is not a string, false, or a CacheInterface implementation
    public function getTemplateClass(string $name, ?int $index = null): string															// Retrieves the template class based on the provided name and optional index.
	{	$key=$this->getLoader()->getCacheKey($name).$this->optionsHash;																	// Generate a cache key by combining the name and options hash
    	$templateClass=$this->templateClassPrefix.hash('xxh128',$key).($index!==null?'___'.$index:'');									// Generate a template class name by prefixing it with the template class prefix and hashing the cache key
    	return$templateClass;}																											// Return the generated template class name
    public function render($name,array$context=[])																						:string
    {   return $this->load($name)->render($context);}																					// Load the template file based on the given name and render the template with the given context, returning the rendered template as a string.
    public function display($name,array$context=[])																						:void
    {   $this->load($name)->display($context);}																							// Display the specified template with the given context.
    public function load($name)																											:TemplateWrapper
    {   if($name instanceof TemplateWrapper)return$name;																				// Check if the $name parameter is already an instance of TemplateWrapper
        return new TemplateWrapper($this,$this->loadTemplate($this->getTemplateClass($name),$name));}									// Load the template and create a new instance of TemplateWrapper
    public function loadTemplate(string$cls,string$name,int$index=null):Template														// The function will load a template based on the provided class, name, and index (optional).
    {   $mainCls=$cls;																													// Assign the value of $cls to $mainCls
        if(\null!==$index)$cls.='___'.$index;																							// Check if an index is provided
        if(isset($this->loadedTemplates[$cls]))return$this->loadedTemplates[$cls];														// Check if the template is already loaded
        if(!\class_exists($cls,\false)){																								// Check if the class exists
            $key=$this->cache->generateKey($name,$mainCls);																				// Generate a cache key using the provided name and main class
            if(!$this->isAutoReload()||$this->isTemplateFresh($name,$this->cache->getTimestamp($key)))$this->cache->load($key);			// Check if auto-reload is disabled or the template is fresh
            if(!\class_exists($cls,\false)){																							// Check if the class still doesn't exist after loading from cache
                $source=$this->getLoader()->getSourceContext($name);																	// Get the source context for the given name
                $content=$this->compileSource($source);																					// Compile the source to get the content
                $this->cache->write($key,$content);																						// Write the compiled content to cache
                $this->cache->load($key);																								// Load data from cache
                if(!\class_exists($mainCls,\false))\eval('?>'.$content);																// Check if the main class still doesn't exist after evaluating the content
                if(!\class_exists($cls,false))throw new RuntimeE1rror(\sprintf('Failed to load Twig template "%s",index "%s": cache might be corrupted.',$name,$index),-1,$source);}}  // Throw an exception if the class still doesn't exist after evaluation
        $this->extensionSet->initRuntime();																								// Initialize the runtime for extensions
        return$this->loadedTemplates[$cls]=new $cls($this);}																			// Create a new instance of the template class and return it
    public function createTemplate(string$template,string$name=\null)																	:TemplateWrapper
    {   $hash=\hash('xxh128',$template,\false);																							// Generate a hash of the template using xxh128 algorithm
        if(\null!==$name)$name=\sprintf('%s (string template %s)',$name,$hash);															// If a name is provided, append the hash to the name
        else$name=\sprintf('__string_template__%s',$hash);																				// If no name is provided, use a default name with the hash
        $loader=new ChainLoader([																										// Create a new ChainLoader with an ArrayLoader and the current loader
            new ArrayLoader([$name=>$template]),$current=$this->getLoader(),]);															// Create a new instance of the ArrayLoader class and pass an array with the template name as the key and the template itself as the value, also get the current loader instance
        $this->setLoader($loader);																										// Set the new loader as the current loader
        try{return new TemplateWrapper($this,$this->loadTemplate($this->getTemplateClass($name),$name));}								// Load the template using the generated name and create a TemplateWrapper
        finally{$this->setLoader($current);}}																							// Restore the original loader
    public function isTemplateFresh(string$name,int$time)																				:bool
    {   return$this->extensionSet->getLastModified()<=$time&&$this->getLoader()->isFresh($name,$time);}									// Store the last modified time of the extension set in a variable and Check if the extension last modified time is less than or equal to the given time and returnd the status.
    public function resolveTemplate($names)																								:TemplateWrapper
    {   if(!\is_array($names))return$this->load($names);																				// Check if $names is not an array, return the loaded template
        $count=\count($names);																											// Count the number of elements in the $names array
        foreach($names as$name){																										// Iterate over each name in the $names array
        	if($name instanceof Template||$name instanceof TemplateWrapper)return$name;													// Return the $name if it is an instance of Template or TemplateWrapper
        	if($this->getLoader()->exists($name))return$this->load($name);}																// Return the loaded template if it exists in the loader
        throw new LoaderError(\sprintf('Unable to find one of the following templates: "%s".',\implode('","',$names)));}				// Throw an exception if none of the templates are found
    public function setLexer(Lexer$lexer)																								:void
    {  $this->lexer=$lexer;}																											// Set the lexer property with the provided lexer object
    public function tokenize(Source$source)																								:TokenStream
    {   if(\null===$this->lexer)$this->lexer=new Lexer($this);																			// Check if the lexer is null and Create a new Lexer instance
        return$this->lexer->tokenize($source);}																							// Tokenize the source using the lexer
    public function setParser(Parser$parser)																							:void
	{	$this->parser=$parser;}																											// Set the parser for the object
    public function parse(TokenStream$stream)																							:ModuleNode
    {   if(\null===$this->parser)$this->parser=new Parser($this);																		// Check if the parser instance is null and create a new Parser instanc
        return$this->parser->parse($stream);}																							// Parse the token stream using the parser instance
    public function setCompiler(Compiler$compiler)																						:void
    {   $this->compiler=$compiler;}																										// Set the compiler object
    public function compile(Node$node)																									:string
    {   if(\null===$this->compiler)$this->compiler=new Compiler($this);																	// Check if the compiler instance is null and create a new Compiler instance
        return $this->compiler->compile($node)->getSource();}																			// Compile the node using the compiler instance and get the source
    public function compileSource(Source $source)																						:string
    {   try{return$this->compile($this->parse($this->tokenize($source)));}																// Tokenize the source code, parse the tokens und compile the parsed tokens
        catch(Error$e){																													// Catch any errors that occur within the try block.
            $e->setSourceContext($source);																								// Set the source context for the error
            throw$e;}																													// Throws the error
        catch(\Exception$e){throw new SyntaxError(\sprintf('An exception has been thrown during the compilation of a template ("%s").',$e->getMessage()),-1,$source,$e);}} // Catch any exception that occurs during the compilation of a template
    public function setLoader(LoaderInterface$loader)																					:void
    {  $this->loader=$loader;}																											// Set the loader property with the provided loader objec
    public function getLoader()																											:LoaderInterface
    {  return $this->loader;}																											// Retrieve the loader instance.
    public function setCharset(?string$charset)																							:void
    {	$this->charset=strtoupper($charset)==='UTF8'?'UTF-8':$charset;}																	// Convert the charset to uppercase, check if the charset is 'UTF8', if it is, set the charset to 'UTF-8' for compatibility, otherwise set the charset to the given value.
    public function getCharset()																										:string
    {   return $this->charset;}																											// Return the value of the charset property
    public function hasExtension(string$class)																							:bool
    {   return$this->extensionSet->hasExtension($class);}																				// The $extensionSet object is assumed to be an instance of a class from the hasExtension method.
    public function addRuntimeLoader(RuntimeLoaderInterface$loader)																		:void
    {   $this->runtimeLoaders[]=$loader;}																								// We appends the $loader object to the $runtimeLoaders array property of the class.
    public function getExtension(string$class)																							:ExtensionInterface
    {   return$this->extensionSet->getExtension($class);}																				// Retrieves the extension for a given class.
    public function getRuntime(string $class)																							: bool
    {	if (isset($this->runtimes[$class]))return$this->runtimes[$class];																// Check if the runtime for the given class is already loaded and return the already loaded runtime
    	foreach ($this->runtimeLoaders as$loader)if($runtime=$loader->load($class))return$this->runtimes[$class]=$runtime;				// Iterate through the runtime loaders, attempt to load the runtime for the given class, cache the loaded runtime for future use and return the loaded runtime
    	throw new RuntimeError(sprintf('Unable to load the "%s" runtime.', $class));}													// Throw a RuntimeError exception if the specified runtime cannot be loaded
    public function addExtension(ExtensionInterface$extension)																			:void
    {   $this->extensionSet->addExtension($extension);																					// Add the extension to the extension set
        $this->updateOptionsHash();}																									// Update the options hash
    public function setExtensions(array$extensions)																						:void
    {   $this->extensionSet->setExtensions($extensions);																				// Set the extensions for the extension set
        $this->updateOptionsHash();}																									// Update the options hash
    public function getExtensions()																										:array
    {   return $this->extensionSet->getExtensions();}																					// We returning the result of getExtensions() on the $extensionSet object. It assumes that $extensionSet is an instance of a class that has a getExtensions() method.
    public function addTokenParser(TokenParserInterface$parser)																			:void
    {  $this->extensionSet->addTokenParser($parser);}																					//  Add a token parser to the extension set.
    public function getTokenParsers()																									:array
    {  return $this->extensionSet->getTokenParsers();}																					// Returns the token parsers.
    public function getTokenParser(string $name)																						:?TokenParserInterface
    {  return $this->extensionSet->getTokenParser($name);}																				// Retrieves a token parser by name
    public function registerUndefinedTokenParserCallback(callable $callable)															:void
    {  $this->extensionSet->registerUndefinedTokenParserCallback($callable);}															// Register the callback function for handling undefined token parsers
    public function addNodeVisitor(NodeVisitorInterface$visitor)																		:void
    {  $this->extensionSet->addNodeVisitor($visitor);}																					// Add the visitor to the extension set
    public function getNodeVisitors()																									:array
    {  return $this->extensionSet->getNodeVisitors();}																					// Retrieve the node visitors from the extension set
    public function addFilter(TwigFilter $filter)																						:void
    {  $this->extensionSet->addFilter($filter);}																						// Adds a filter to the Twig extension set
    public function getFilter(string $name)																								:?TwigFilter
    {  return $this->extensionSet->getFilter($name);}																					// Retrieve the filter from the extension set
	public function registerUndefinedFilterCallback($key)																				:bool
	{  if(isset($this->cache[$key]))return$this->cache[$key];																			// Registers a callback function for undefined filters
       $data=fetchDataFromDataSource($key);																								// Perform data access operation
       $this->cache[$key]=$data;																										// Store the data in the cache
       return$data;}																													// Return the fetched data
    public function getFilters()																										:array
    {  return $this->extensionSet->getFilters();}																						// Retrieve the filters from the extension set
    public function addTest(TwigTest$test)																								:void
    {  $this->extensionSet->addTest($test);}																							// Adds a test to the Twig extension set
    public function getTests()																											:array
    {  return $this->extensionSet->getTests();}																							// Retrieve the tests from the extension set
    public function getTest(string $name)																								:?TwigTest
    {  return $this->extensionSet->getTest($name);}																						// Retrieve a TwigTest object from the extension set based on the provided name
    public function addFunction(TwigFunction$function)																					:void
    {  $this->extensionSet->addFunction($function);}																					// Adds a Twig function to the extension set.
    public function getFunction(string$name):																							?TwigFunction
	{  if(isset($this->functionCache[$name]))return$this->functionCache[$name];															// Check if the function is already cached
       $function=$this->extensionSet->getFunction($name);																				// Retrieve the function from the extension set
       $this->functionCache[$name]=$function;																							// Cache the function for future use
       return$function;}																												// Return the retrieved function
    public function registerUndefinedFunctionCallback(callable $callable)																:void
    {  $this->extensionSet->registerUndefinedFunctionCallback($callable);}																// Registers a callback function for handling undefined functions
    public function getFunctions()																										:array
    {  return $this->extensionSet->getFunctions();}																						// Retrieve the functions from the extension set
    public function addGlobal(string$name,$value)																						:void
    {  if($this->extensionSet->isInitialized()&&!\array_key_exists($name,$this->getGlobals()))throw new \LogicException(\sprintf('Unable to add global "%s" as the runtime or the extensions is already been set.',$name)); // Check if the extension set is initialized and the global variable doesn't already exist
       if(\null!==$this->resolvedGlobals)$this->resolvedGlobals[$name]=$value;															// If the resolvedGlobals array is not null, add the global variable to it
       else$this->globals[$name]=$value;}																								// Otherwise, add the global variable to the globals array
    public function getGlobals()																										:array
	{  if($this->extensionSet->isInitialized()){																						// Check if the extension set is initialized
        $this->resolvedGlobals??=\array_merge($this->extensionSet->getGlobals(),$this->globals);										// Merge the extension set globals with the class globals
        return$this->resolvedGlobals;}																									// Return the resolved globals
       return \array_merge($this->extensionSet->getGlobals(), $this->globals);}															// If the extension set is not initialized, merge the extension set globals with the class globals and return
    public function mergeGlobals(array$context)																							:array
    {  foreach($this->getGlobals()as$key=>$value)if(!\array_key_exists($key,$context))$context[$key]=$value;							// Merge global variable into context if it doesn't already exist
       return$context;}																													// Return the global variables array to ensure all variables are accessible within the template
    public function getUnaryOperators()																									:array
	{  if($this->cachedUnaryOperators===\null)$this->cachedUnaryOperators=$this->extensionSet->getUnaryOperators();						// Check if the cached unary operators are null
    return $this->cachedUnaryOperators;}																								// Return the cached unary operators
    public function getBinaryOperators()																								:array
	{  if($this->binaryOperatorsCache===\null)																							// Check if the binary operators cache is null
        $this->binaryOperatorsCache=$this->extensionSet->getBinaryOperators();															// Retrieve the binary operators from the extension set
    	return$this->binaryOperatorsCache;}																								// Return the binary operators cache
    private function updateOptionsHash()																								:void
	{	$this->optionsHash=implode(':',[																								// to create a unique options hash
        $this->extensionSet->getSignature(),																							// Get the signature from the extension set
        PHP_MAJOR_VERSION,																												// Get the major version of PHP
        PHP_MINOR_VERSION,																												// Get the minor version of PHP
        self::VERSION,																													// Get the version of the class
        (int)$this->debug,																												// Convert the debug mode to an integer
        (int)$this->strictVariables,]);}}																								// Convert the strict variables mode to an integer
