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
use \GoTableaux\Exception\Parser as ParserException;
use \GoTableaux\Sentence as Sentence;

/**
 * Represents the standard sentence parser.
 * @package GoTableaux
 **/
class Standard extends \GoTableaux\SentenceParser
{
	
	public $atomicSymbols = array( 'A', 'B', 'C', 'D', 'E' );
	
	public $operatorNameSymbols = array(
		'Negation'               => '~',
		'Conjunction'            => '&',
		'Disjunction'            => 'V',
		'Material Conditional'   => '>',
		'Material Biconditional' => '<',
		'Conditional'            => '$',
		'Possibility'            => 'P',
		'Necessity'              => 'N'
	);
	
	public $openMark = '(';

	public $closeMark = ')';

	public function buildSymbolTable()
	{
		parent::buildSymbolTable();
		$this->symbolTable[ $this->openMark ] = self::PUNCT_OPEN;
		$this->symbolTable[ $this->closeMark ] = self::PUNCT_CLOSE;
	}
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
		if ( !isset( $this->symbolTable[ $string{0} ]))
			throw new ParserException( $string{0}. ' is not in the symbol table.' );
		switch ( $this->symbolTable[ $string{0} ] ) {
			case self::ATOMIC :
				$firstSentenceStr = $string{0};
				$hasSubscript = strlen( $string ) > 1 && is_numeric( $string{1} );
				if ( $hasSubscript ) for ( $i = 1; isset( $string{$i} ) && is_numeric( $string{$i} ); $i++ )
					$firstSentenceStr .= $string{$i};
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
	
	/**
	 * Drops outer parentheses from a string, if they exist.
	 *
	 * @param string $string The string to be parsed.
	 * @return string The resulting string.
	 */
	private function dropOuterParens( $string )
	{
		try {
			$parenGroup = $this->grabParenGroup( $string, true );
			if ( $parenGroup === $string ) {
				$string = substr( $string, 1, strlen( $string ) - 2 );
				return $this->dropOuterParens( $string );
			}
		} catch ( ParserException $e ) { }
		return $string;
	}

	/**
	 * Finds a string's position for the corresponding close mark of an open 
	 * mark at the given position.
	 *
	 * @param string $string The string to scan.
	 * @param integer $openPos String position of open mark.
	 * @return integer The position of the corresponding close mark.
	 * @throws {@link Exception\Parser} on parsing error.
	 */
	private function closePosFromOpenPos( $string, $openPos )
	{
		if ( $this->symbolTable[ $string{$openPos} ] !== self::PUNCT_OPEN )
			throw ParserException::createWithMsgInputPos( "Open mark expected.", $string, $openPos );
		$length 	= strlen( $string );
		$depth  	= 1;
		$pos 		= $openPos;
		do {
			if ( ++$pos === $length )
				throw ParserException::createWithMsgInputPos( 'Unterminated open mark.', $string, --$pos );
			$char = $string{$pos};
			if ( $char === $this->openMark ) $depth++;
			elseif ( $char === $this->closeMark ) $depth--;
		} while ( $depth );
		return $pos;
	}
	
	/**
	 * Parses first complete parenthesized group in a string.
	 *
	 * @param string $str The string to be parsed. Must contain at least one
	 *					  parenthesized group.
	 * @param boolean $includeOuter Whether to include the outer parentheses
	 *								in the returned string. Default is false.
	 * @param integer $offset String offset at which to start searching.
	 * @return string Everything inside the first parenthesized group. Includes
	 *				  outer parentheses if $includeOuter is set to true.
	 * @throws {@link Exception\Parser} on no parentheses in string, or parsing error.
	 */
	private function grabParenGroup( $str, $includeOuter = false, $offset = 0 )
	{
		$length		= strlen( $str );
		$startPos 	= strpos( $str, $this->openMark, $offset );
		
		if ( $startPos === false )
			throw new ParserException( "No open punctuation found. Check parentheses." );
		
		$depth  = 1;
		$endPos = $startPos;
		$startPos += 1;
		do {
			if ( ++$endPos === $length )
				throw new ParserException( "Unable to parse punctuation. Check parentheses." );
			$char = $str{$endPos};
			if ( $char === $this->openMark ) $depth++;
			elseif ( $char === $this->closeMark ) $depth--;
		} while ( $depth );
		
		if ( $includeOuter ) {
			$startPos -= 1;
			$endPos += 2;
		}
		
		return substr( $str, $startPos, $endPos - 1 );
	}
}