<?php
/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 * (c) Armin Ronacher
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/*

(c) & @author Rolf Joseph of this modified code and includes additional comments and documentation.

It improves the readability and understanding of the code. It also mentions that the code has been modified for the Websoccer owsPro project to take advantage of PHP 8 features.
"owsPro" is distributed WITHOUT ANY WARRANTY, including the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public Licence version 3 http://www.gnu.org/licenses/
The code provided is a modified version of the Twig environment class. Twig is a popular PHP templating engine. The modified code takes advantage of new features in PHP 8 and includes additional documentation. */

namespace Twig;
use Twig\Node\Node;

/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Compiler
{   private ?int $lastLine;																												// The last line number of the source code
    private string $source;																												// The source code string
    private int $indentation;																											// The current indentation level
    private Environment $env;																											// The environment object
    private array $debugInfo;																											// Debug information array
    private int $sourceOffset;																											// The offset of the source code
    private int $sourceLine;																											// The current line number of the source code
    private int $varNameSalt;																											// Salt value for generating unique variable names
    public function __construct(Environment$env)
    {   $this->env=$env;																												// Assign the environment object
        $this->reset();}																												// Reset the state of the object
    public function getEnvironment()																									:Environment
    {return $this->env;}																												// Return the current environment.
    public function getSource()																											:string
    {return $this->source;}																												// Return the source of the data as string.
    public function reset(int$indentation=0)																							:self
    {   $this->lastLine=null;																											// Reset the properties of the object stored in $lastLine
        $this->source='';																												// Reset the properties of the object stored in $source
        $this->debugInfo=[];																											// Reset the properties of the object stored in $debugInfo
        $this->sourceOffset=0;																											// Reset the properties of the object stored in $lastLine
        $this->sourceLine=1;																											// Reset the properties of the object stored in $sourceLine and the source code starts at 1 (as we then increment it when we encounter new lines)
        $this->indentation=$indentation;																								// Reset the properties of the object stored in $indentation
        $this->varNameSalt=0;																											// Reset the properties of the object stored in $varNameSalt
        return$this;}																													// Return the current object for method chaining
    public function compile(Node$node,int$indentation=0)																				:self
    {   $this->reset($indentation);																										// Reset the indentation level
        $node->compile($this);																											// Compile the given node
        return$this;}																													// Return the current instance for method chaining
    public function subcompile(Node$node,bool$raw=true)																					:self
    {   if(false===$raw)$this->source.=\str_repeat(' ',$this->indentation*4);															// Add indentation if $raw is false
        $node->compile($this);																											// Compile the node
        return$this;}																													// Return the current object for method chaining
    public function raw(string $string)																									:self
    {   $this->source.=$string;																											// Append the given string to the source
        return$this;}																													// Return the current object for method chaining
    public function write(...$strings)																									:self
    {   $indentation=\str_repeat(' ',$this->indentation*4);																				// Concatenate the string with indentation
        $this->source.=\implode('',\array_map(fn($string)=>$indentation.$string,$strings));												// The array_map applys the indentation to each string. The resulting strings are then concatenated using the implode function.
        return$this;}																													// Returns the current object instance
    public function string(string$value='')																								:self
    																																	// By setting a default value of an empty string for the $value parameter, we can use it to be called without passing any arguments and reduce the need for additional checks.
    {   																																// We appends the formatted string $value to the $source. The sprintf function is used to format the string with double quotes around the value
        $this->source.=\sprintf('"%s"',\addcslashes($value,"\0\t\"\$\\"));																// and escape any special characters within the value using addcslashes. This ensures that the value is properly formatted and escaped before appending it to the $source.
        return$this;}																													// By returning $this at the end of the method, we enable method chaining, allowing multiple method calls to be chained together in a fluent manner.
    public function repr($value)																										:self
    {   if(\is_int($value)||\is_float($value)){																							// Check if the value is an integer or float
            if(false!==$locale=\setlocale(\LC_NUMERIC,'0')){																			// Store the current locale
                \setlocale(\LC_NUMERIC,'C');}																							// Set the locale to 'C' for consistent numeric representation
            $this->raw(\var_export($value,true));																						// Export the value as a string and display it
            if(false!==$locale)\setlocale(\LC_NUMERIC,$locale);}																		// Restore the original locale
        elseif($value===\null){																											// Check if the value is null
            $this->raw('null');}																										// If the value is null. If it is, it displays the string 'null'.
        elseif(\is_bool($value)){																										// Check if the value is a boolean
            $this->raw($value?'true':'false');}																							// Display 'true' if the value is true, 'false' otherwise
        elseif(\is_array($value)){																										// Check if the value is an array
            $this->raw('array(');																										// Display 'array(' to indicate the start of the array
            $first=true;																												// Iterate over each key-value pair in the array
            foreach($value as$key=>$v){																									// Check if it's the first iteration of the loop
                if(!$first)$this->raw(',');																								// Add a comma and space before each subsequent key-value pair
                $first=\false;																											// Set the variable $first to false
                $this->repr($key);																										// Recursively call the repr() function for the key and value
                $this->raw('=>');																										// It outputs the arrow symbol "=>" to indicate a key-value pair in the output.
                $this->repr($v);}																										// It calls the `repr` function to represent the value `$v` in the output.
            $this->raw(')');}																											// Display ')' to indicate the end of the array
        else$this->string($value);																										// If none of the above conditions are met, treat the value as a string
        return$this;}																													// It returns an instance of the current class
    public function addDebugInfo(Node $node)																							:self
    {   if($node->getTemplateLine()!=$this->lastLine){																					// Check if the node's template line is different from the last line
            $this->write(\sprintf("// line %d\n",$node->getTemplateLine()));															// Add a comment indicating the line number
            $this->sourceLine+=\substr_count($this->source,"\n",$this->sourceOffset);													// Update the source line
            $this->sourceOffset=\strlen($this->source);																					// Update the source offset
            $this->debugInfo[$this->sourceLine]=$node->getTemplateLine();																// Store the debug information
            $this->lastLine=$node->getTemplateLine();}																					// Update the last line
        return$this;}																													// It returns an instance of the current class
    public function getDebugInfo()																										:array
    {	\ksort($this->debugInfo,SORT_NATURAL|SORT_FLAG_CASE);																			// Sort the debugInfo array in ascending order based on the keys, and using the SORT_NATURAL | SORT_FLAG_CASE parameters,
    																																	// we can achieve a natural sorting order that is case-insensitive. This ensures that the keys are sorted in a more intuitive way.
        return$this->debugInfo;}																										// Return the sorted debugInfo array
    public function indent(int$step=1):self																								// Increases the indentation level by the specified step.
    {   $this->indentation+=$step;																										// The number of steps to increase the indentation level by
        return$this;}																													// It returns the current instance of the class
    public function outdent(int $step = 1)																								:self
    {   if($this->indentation<$step){																									// Check if the requested outdent step is greater than the current indentation level
            throw new \LogicException('Unable to call outdent() as the indentation would become negative.');}							// can't outdent by more steps than the current indentation level
        $this->indentation-=$step;																										// Decrease the indentation level by the specified number of steps
        return$this;}																													// Return the current object
    public function getVarName()																										:string
    {return \sprintf('__internal_compile_%d',$this->varNameSalt++);}}																	// Generate a unique variable name for internal compilation
