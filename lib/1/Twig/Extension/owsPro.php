<?php
/******************************************************************
*
* Eigen-Modul:	OpenWebsoccer Twig-Funktions-Library
*
* Author:		Rolf Joseph
*
* Core für eingene Funktionen,die in Twig zur Verfügung stehen.
* Diese Datei muss im Ordner Twig/Extension vorhanden sein.
* Die Registrierung erfolgt in der Datei Twig/Environment.php
* mittels $this->addExtension(new Twig_Extension_Lib());
*
* This file is part of OpenWebSoccer-Sim.
*
* OpenWebSoccer-Sim is free software: you can redistribute it
* and/or modify it under the terms of the
* GNU Lesser General Public License
* as published by the Free Software Foundation,either version 3 of
* the License,or any later version.
*
* OpenWebSoccer-Sim is distributed in the hope that it will be
* useful,but WITHOUT ANY WARRANTY; without even the implied
* warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU Lesser General Public License for more details.
*
* You should have received a copy of the GNU Lesser General Public
* License along with OpenWebSoccer-Sim.
* If not,see <http://www.gnu.org/licenses/>.
*
******************************************************************/
class Twig_Extension_owsPro extends Twig_Extension
{
    public function getOperators()
    {
        return [[
			'!'	 => ['precedence' => 50,'class' => 'Twig_Node_Expression_Unary_Not'],],
		  [
			'||' => ['precedence' => 10,'class' => 'Twig_Node_Expression_Binary_Or',	'associativity' => Twig_ExpressionParser::OPERATOR_LEFT],
			'&&' => ['precedence' => 15,'class' => 'Twig_Node_Expression_Binary_And',	'associativity' => Twig_ExpressionParser::OPERATOR_LEFT],
			'::' => ['precedence' => 25,'class' => 'Twig_Node_Expression_Constant',	'associativity' => Twig_ExpressionParser::OPERATOR_LEFT],
		],];
    }
}
class Twig_Extension_phpTag extends  Twig_TokenParser
{	public function parse(Twig_Token $token)
    {	$parser = $this->parser;
        $stream = $parser->getStream();
        $name = $stream->expect(Twig_Token::NAME_TYPE)->getValue();
        $stream->expect(Twig_Token::OPERATOR_TYPE,'=');
        $value = $parser->getExpressionParser()->parseExpression();
        $stream->expect(Twig_Token::BLOCK_END_TYPE);
        return new Project_Set_Node($name,$value,$token->getLine(),$this->getTag());
    }
    public function decideMyTagEnd(Twig_Token $token)
    {
        return $token->test('?>');
    }
    public function getTag()
    {	return '<?php';
    }
}
