<?php
/**
 * GoTableaux. A multi-logic tableaux generator.
 * Copyright (C) 2012  Douglas Owings
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed WITHOUT ANY WARRANTY. 
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/agpl-3.0.html>.
 */
/**
 * Defines the StandardSentenceParser class.
 * @package GoTableaux
 */

namespace GoTableaux\SentenceParser;

use \GoTableaux\Utilities as Utilities;
use \GoTableaux\Utilities\Parser as ParserUtilities;
use \GoTableaux\Exception\Parser as ParserException;
use \GoTableaux\Vocabulary as Vocabulary;
use \GoTableaux\Sentence as Sentence;

/**
 * Represents the standard sentence parser.
 * @package GoTableaux
 **/
class Standard extends \GoTableaux\SentenceParser
{
	
	public $atomicSymbols = array( 'A', 'B', 'C', 'D', 'E' );
	
	public $operatorNameSymbols = array(
		'Negation' => '~',
		'Conjunction' => '&',
		'Disjunction' => 'V',
		'Material Conditional' => '>',
		'Material Biconditional' => '<',
		'Conditional' => '$',
		'Possibility' => 'P',
		'Necessity' => 'N'
	);
	
	/**
	 * Creates a {@link Sentence sentence} from a string.
	 * 
	 * @param string $sentenceStr The string to interpret.
	 * @return Sentence The resulting instance.
	 */
	public function stringToSentence( $sentenceStr )
	{
		$sentenceStr = $this->removeSeparators( $sentenceStr );
		$sentenceStr = $this->dropOuterParens( $sentenceStr );
		
		if ( empty( $sentenceStr )) 
			throw new ParserException( 'Sentence string cannot be empty.' );
		
		$firstSentenceStr = $this->_readSentence( $sentenceStr );
		
		if ( $firstSentenceStr === $sentenceStr ) {
			$firstSymbolType = $this->symbolTable[ $sentenceStr{0} ];
			switch ( $firstSymbolType ) {
				case self::ATOMIC :
					return $this->parseAtomic( $sentenceStr );
				case self::OPER_UNARY :
					$operator 	= $this->getOperatorBySymbol( $sentenceStr{0} );
					$operandStr = substr( $sentenceStr, 1 );
					$operand 	= $this->stringToSentence( $operandStr );
					return Sentence::createMolecular( $operator, array( $operand ));
				default :
					throw ParserException::createWithMsgInputPos( 'Unexpected symbol type.', $sentenceStr, 0 );
			}
		}
		
		$pos 			= strlen( $firstSentenceStr );
		$nextSymbol 	= $sentenceStr{$pos};
		$nextSymbolType = $this->symbolTable[ $nextSymbol ];
		
		if ( $nextSymbolType !== self::OPER_BINARY )
			throw ParserException::createWithMsgInputPos( 'Unexpected symbol. Expecting binary operator.', $sentenceStr, $pos );
		
		$rightStr			= substr( $sentenceStr, ++$pos );
		$secondSentenceStr 	= $this->_readSentence( $rightStr );
		
		if ( $rightStr !== $secondSentenceStr )
			throw ParserException::createWithMsgInputPos( 'Invalid right operand string.', $sentenceStr, $pos );
		
		$operator = $this->getOperatorBySymbol( $nextSymbol );
		$operands = array(
			$this->stringToSentence( $firstSentenceStr ),
			$this->stringToSentence( $secondSentenceStr )
		);
		return Sentence::createMolecular( $operator, $operands );
	}
	
	/**
	 * Reads a string for the first occurrence of a sentence expression.
	 *
	 * @param string $string The string to read.
	 * @return string The first sentence string.
	 * @throws {@link ParserException} on parse error.
	 * @access private
	 */
	private function _readSentence( $string )
	{	
		switch ( $this->symbolTable[ $string{0} ] ) {
			case self::ATOMIC :
				$hasSubscript = strlen( $string ) > 1 && $string{1} === $this->subscriptSymbol;
				$firstSentenceStr = $hasSubscript ? substr( $string, 0, 2 ) . intval( substr( $string, 2 ))
												  : $string{0};
				break;
			case self::PUNCT_OPEN;
				$closePos = $this->closePosFromOpenPos( $string, 0 );
				$firstSentenceStr = substr( $string, 0, $closePos + 1 );
				break;
			case self::OPER_UNARY :
				$firstSentenceStr = $string{0} . $this->_readSentence( substr( $string, 1 ));
				break;
			default :
				throw ParserException::createWithMsgInputPos( 'Unexpected symbol type.', $string, 0 );
		}
		return $firstSentenceStr;
	}
	

}