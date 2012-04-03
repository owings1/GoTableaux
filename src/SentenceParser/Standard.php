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
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program in file LICENSE.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * Defines the StandardSentenceParser class.
 * @package Syntax
 */

namespace GoTableaux\SentenceParser;
use \GoTableaux\Utilities as Utilities;
use \GoTableaux\ParserUtilities as ParserUtilities;
use \GoTableaux\Exception\Parser as ParserException;
use \GoTableaux\Vocabulary as Vocabulary;
use \GoTableaux\Sentence as Sentence;

/**
 * Represents the standard sentence parser.
 * @package Syntax
 **/
class Standard extends \GoTableaux\SentenceParser
{
	/**
	 * Creates a {@link Sentence sentence} from a string.
	 * 
	 * @param string $sentenceStr The string to interpret.
	 * @return Sentence The resulting instance.
	 */
	public function stringToSentence( $sentenceStr )
	{
		$vocabulary  = $this->getVocabulary();
		$sentenceStr = ParserUtilities::removeSeparators( $sentenceStr, $vocabulary );
		$sentenceStr = ParserUtilities::dropOuterParens( $sentenceStr, $vocabulary );
		
		if ( empty( $sentenceStr )) 
			throw new ParserException( 'Sentence string cannot be empty.' );
		
		$firstSentenceStr = $this->_readSentence( $sentenceStr, $vocabulary );
		
		if ( $firstSentenceStr === $sentenceStr ) {
			$firstSymbolType = $vocabulary->getSymbolType( $sentenceStr{0} );
			switch ( $firstSymbolType ) {
				case Vocabulary::ATOMIC :
					return $this->parseAtomic( $sentenceStr, $vocabulary );
				case Vocabulary::OPER_UNARY :
					$operator 	= $vocabulary->getOperatorBySymbol( $sentenceStr{0} );
					$operandStr = substr( $sentenceStr, 1 );
					$operand 	= $this->stringToSentence( $operandStr, $vocabulary );
					return Sentence::createMolecular( $operator, array( $operand ));
				default :
					throw ParserException::createWithMsgInputPos( 'Unexpected symbol type.', $sentenceStr, 0 );
			}
		}
		
		$pos 			= strlen( $firstSentenceStr );
		$nextSymbol 	= $sentenceStr{$pos};
		$nextSymbolType = $vocabulary->getSymbolType( $nextSymbol );
		
		if ( $nextSymbolType !== Vocabulary::OPER_BINARY )
			throw ParserException::createWithMsgInputPos( 'Unexpected symbol. Expecting binary operator.', $sentenceStr, $pos );
		
		$rightStr			= substr( $sentenceStr, ++$pos );
		$secondSentenceStr 	= $this->_readSentence( $rightStr, $vocabulary );
		
		if ( $rightStr !== $secondSentenceStr )
			throw ParserException::createWithMsgInputPos( 'Invalid right operand string.', $sentenceStr, $pos );
		
		$operator = $vocabulary->getOperatorBySymbol( $nextSymbol );
		$operands = array(
			$this->stringToSentence( $firstSentenceStr, $vocabulary ),
			$this->stringToSentence( $secondSentenceStr, $vocabulary )
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
		$vocabulary		 = $this->getVocabulary(); 
		$firstSymbolType = $vocabulary->getSymbolType( $string{0} );
		switch ( $firstSymbolType ) {
			case Vocabulary::ATOMIC :
				$hasSubscript = strlen( $string ) > 1 && 
								$vocabulary->getSymbolType( $string{1} ) === Vocabulary::CTRL_SUBSCRIPT;
				$firstSentenceStr = $hasSubscript ? substr( $string, 0, 2 ) . intval( substr( $string, 2 ))
												  : $string{0};
				break;
			case Vocabulary::PUNCT_OPEN;
				$closePos = ParserUtilities::closePosFromOpenPos( $string, 0, $vocabulary );
				$firstSentenceStr = substr( $string, 0, $closePos + 1 );
				break;
			case Vocabulary::OPER_UNARY :
				$firstSentenceStr = $string{0} . $this->_readSentence( substr( $string, 1 ), $vocabulary );
				break;
			default :
				throw ParserException::createWithMsgInputPos( 'Unexpected symbol type.', $string, 0 );
		}
		return $firstSentenceStr;
	}
	

}