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
use \GoTableaux\Sentence as Sentence;

/**
 * Represents the standard sentence parser.
 * @package GoTableaux
 **/
class Polish extends \GoTableaux\SentenceParser
{
	public $atomicSymbols = array( 'a', 'b', 'c', 'd', 'e' );
	
	public $operatorNameSymbols = array(
		'Conjunction'            => 'K',
		'Disjunction'            => 'A',
		'Negation'	             => 'N',
		'Material Conditional' 	 => 'C',
		'Material Biconditional' => 'E',
		'Possibility'            => 'M',
		'Necessity'              => 'L',
		'Conditional'            => 'U'
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
		
		if ( empty( $sentenceStr ))
			throw new ParserException( 'Input cannot be empty.' );
		
		$stack = array();
		$this->_readSentences( $sentenceStr, $stack );
		return $this->_processStack( $stack );
	}
	
	/**
	 * Reads a string (whitespace stripped) for the first occurrence 
	 * of a sentence.
	 *
	 * @param string $string The string to read.
	 * @return string The string length of the sentence read.
	 * @throws {@link ParserException} on parse error.
	 */
	private function _readSentences( $string, &$stack = array() )
	{
		$pos = 0;
		$char = $string{$pos};
		if ( !isset( $this->symbolTable[ $char ]))
			throw new ParserException( "$char is not in the symbol table." );
		if ( in_array( $char, $this->atomicSymbols )) {
			$sentenceStr = $this->readAtomic( $string );
			$stack[] = $sentenceStr;
			return strlen( $sentenceStr );
		}
		if ( $this->isOperatorSymbol( $char )) {
			$operator = $this->getOperatorBySymbol( $char );
			$subStack = array();
			for ( $i = 0, $pos++; $i < $operator->getArity(); $i++ ) 
				$pos += $this->_readSentences( substr( $string, $pos ), $subStack );
			$stack[] = array( $char => $subStack );
			return $pos;
		}
		throw new ParserException( "Unexpected symbol '$char'" );
	}
	
	private function _processStack( array &$stack )
	{
		$element = array_shift( $stack );
		if ( !is_array( $element )) return $this->parseAtomic( $element );
		$operator = $this->getOperatorBySymbol( key( $element ));
		$operands = array();
		$element = array_shift( $element );
		for ( $i = 0; $i < $operator->getArity(); $i++ )
			$operands[] = $this->_processStack( $element );
		return Sentence::createMolecular( $operator, $operands );
	}
}