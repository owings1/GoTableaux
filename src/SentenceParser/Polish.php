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
 * @package GoTableaux
 */

namespace GoTableaux\SentenceParser;
use \GoTableaux\Utilities as Utilities;
use \GoTableaux\ParserUtilities as ParserUtilities;
use \GoTableaux\Exception\Parser as ParserException;
use \GoTableaux\Vocabulary as Vocabulary;
use \GoTableaux\Sentence as Sentence;

/**
 * Represents the standard sentence parser.
 * @package GoTableaux
 **/
class Polish extends \GoTableaux\SentenceParser
{
	/**
	 * Creates a {@link Sentence sentence} from a string.
	 * 
	 * @param string $sentenceStr The string to interpret.
	 * @return Sentence The resulting instance.
	 */
	public function stringToSentence( $sentenceStr )
	{
		$sentenceStr = ParserUtilities::removeSeparators( $sentenceStr, $this->getVocabulary() );
		$sentencestr = ParserUtilities::removeAllParens( $sentenceStr, $this->getVocabulary() );
		
		if ( empty( $sentenceStr ))
			throw new ParserException( 'Input cannot be empty.' );
			
		if ( $error = $this->readRPN( strrev( $sentenceStr )))
			throw ParserException::createWithMsgInputPos( $error );
		
		if ( count( $this->operandStack ) !== 1 )
			throw new ParserException( 'Expected operand stack to contain exactly one sentence.' );
		
		return array_pop( $this->operandStack );
	}
	
	private $operandStack = array();
	
	private function readRPN( $input )
	{
		if ( empty( $input )) return false;
		
		$vocabulary = $this->getVocabulary();
		$firstCharType = $vocabulary->getSymbolType( $input{0} );
		
		if ( $firstCharType === Vocabulary::NUMERIC_CHAR ) {
			$i = 1;
			$subscriptStr = '';
			do {
				$subscriptStr .= $input{$i};
				$nextType = $vocabulary->getSymbolType( $input{++$i} );
			} while ( $nextType === Vocabulary::NUMERIC_CHAR );
			if ( $nextType !== Vocabulary::CTRL_SUBSCRIPT )
				return array( 'message' => 'Expecting subscript character', 'position' => $i, 'input' => $input );
			$atomicSymbol = $input{++$i};
			$this->operandStack[] = Sentence::createAtomic( $atomicSymbol, (int) $subscriptStr );
			return $this->readRPN( substr( $input , $i ));
		}
		
		if ( $firstCharType === Vocabulary::ATOMIC ) {
			$this->operandStack[] = Sentence::createAtomic( $atomicSymbol, 0 );
			return $this->readRPN( substr( $input , 1 ));
		}
		
		if ( $firstCharType < Vocabulary::OPER_UNARY )
			return array( 'message' => 'Expecting operator symbol', 'position' => 0, 'input' => $input );
		
		$operator = $vocabulary->getOperatorBySymbol( $input{0} );
		$operands = array();
		for ( $i = 0; $i < $operator->getArity(); $i++ )
			$operands[] = array_pop( $this->operandStack );
		$this->operandStack[] = Sentence::createMolecular( $operator, $operands );
		return $this->readRPN( substr( $input, 1 ));
	}
}