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
 * Contains the base Parser class.
 * @package GoTableaux
 */

namespace GoTableaux;

use \GoTableaux\Exception\Parser as ParserException;

/**
 * Represents a sentence parser.
 * @package GoTableaux
 **/
abstract class SentenceParser
{
	// Operator Symbols are Flagged by Positive n = arity
	const OPER_TERNARY		= 3;
	const OPER_BINARY		= 2;
	const OPER_UNARY		= 1;
	const ATOMIC 			= 0;
	const PUNCT_OPEN 		= -1;
	const PUNCT_CLOSE 		= -2;
	const PUNCT_SEPARATOR 	= -3;
	const CTRL_SUBSCRIPT	= -4;
	const NUMERIC_CHAR		= -5;
	
	//  Define in child classes
	public $atomicSymbols = array();
	public $operatorNameSymbols = array();
	
	//  Constructed from logic
	protected $operatorSymbolArities = array();
	
	//  Hashed for convenience
	protected $operatorSymbolNames = array();
	
	//  Reasonable defaults
	protected $openMark = '(';
	protected $closeMark = ')';
	protected $subscriptSymbol = '_';
	protected $spaceSymbols = array( ' ', "\n", "\t" );
	
	/**
	 * Maps symbols to types.
	 */
	protected $symbolTable = array();
	
	protected $logic;
	
	/**
	 * Creates a child instance.
	 *
	 * @param Logic $logic The logic whose operators/arities to use.
	 * @param string $type The type of parser to create.
	 */
	public static function getInstance( Logic $logic, $type = 'Standard' )
	{
		$class = __CLASS__ . '\\' . $type;
		return new $class( $logic );
	}
	
	/**
	 * Constructor. Initializes the operators
	 *
	 * @param Logic $logic The logic (language) for which to parse.
	 * @throws Exception
	 */
	protected function __construct( Logic $logic )
	{
		$this->logic = $logic;
		$operatorArities = $logic->operatorArities;
		foreach ( $operatorArities as $name => $arity ) {
			if ( empty( $this->operatorNameSymbols[ $name ]))
				throw new Exception( "No symbol defined for operator $name" );
			$symbol = $this->operatorNameSymbols[ $name ];
			$arity = intval( $arity );
			if ( $arity < self::OPER_UNARY )
				throw new Exception( "Invalid arity." );
			
			$this->operatorSymbolArities[ $symbol ] = $arity;
		}
		$this->operatorSymbolNames = array_flip( $this->operatorNameSymbols );
		$this->buildSymbolTable();
		
	}
	
	public function buildSymbolTable()
	{
		foreach ( $this->atomicSymbols as $symbol ) 
			$this->symbolTable[ $symbol ] = self::ATOMIC;
		foreach ( $this->spaceSymbols as $symbol )
			$this->symbolTable[ $symbol ] = self::PUNCT_SEPARATOR;
		$this->symbolTable[ $this->openMark ] = self::PUNCT_OPEN;
		$this->symbolTable[ $this->closeMark ] = self::PUNCT_CLOSE;
		$this->symbolTable = array_merge( $this->symbolTable, $this->operatorSymbolArities );
	}
	
	/**
	 * Gets Operator object by its symbol.
	 *
	 * @param string $symbol Operator symbol.
	 * @return Operator Operator instance.
	 * @throws {@link ParserException} when $symbol is not an operator 
	 * 		   symbol in the vocabulary.
	 */
	public function getOperatorBySymbol( $symbol )
	{
		if ( !array_key_exists( $symbol, $this->operatorSymbolNames ))
			throw new ParserException( "$symbol is not an operator symbol in the vocabulary." );
		return $this->logic->getOperator( $this->operatorSymbolNames[ $symbol ]);
	}
	
	/**
	 * Parses an atomic sentence from a string that starts with an atomic symbol.
	 *
	 * This provides default functionality for parsing an atomic sentence from a
	 * string that starts with an atomic symbol. If the next character is a 
	 * subscript symbol, it will read the following series of integer characters.
	 * If a subscript is not given, it will be assigned 0.
	 *
	 * @param string $sentenceStr The string to parse.
	 * @return Sentence The resulting sentence instance.
	 * @throws {@link ParserException}.
	 */
	protected function parseAtomic( $sentenceStr )
	{
		$symbolIndex = array_search( $sentenceStr{0}, $this->atomicSymbols );
		if ( $symbolIndex === false )
			throw new ParserException( "$symbol is not an atomic symbol." );
		
		$symbol = $sentenceStr{0};
		$hasSubscript = strlen( $sentenceStr ) > 1 && $sentenceStr{1} === $this->subscriptSymbol;
		if ( $hasSubscript ) {
			$subscriptStr = '';
			for ( $i = 2; $i < strlen( $sentenceStr ) && is_int( $sentenceStr{$i} ); $i++ )
				$subscriptStr += $sentenceStr{$i};
			$subscript = ( int ) $subscriptStr;
		} else $subscript = 0;
		
		return Sentence::createAtomic( $symbolIndex, $subscript );
	}
	
	/**
	 * Trims separator (whitespace) characters from beginning and end of a string.
	 *
	 * @param string $string The string to trim.
	 * @return string The trimmed string.
	 */
	public function trimSeparators( $string )
	{
		return trim( $string, implode( '', $this->spaceSymbols ));
	}
	
	/**
	 * Removes separator (space) characters from a string.
	 *
	 * @param string $string The string to replace.
	 * @return string The string with all separators removed.
	 */
	public function removeSeparators( $string )
	{
		return str_replace( $this->spaceSymbols, '', $string );
	}
		
	/**
	 * Drops outer parentheses from a string, if they exist.
	 *
	 * @param string $string The string to be parsed.
	 * @return string The resulting string.
	 */
	public function dropOuterParens( $string )
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
	 * Removes all parentheses from a string.
	 * 
	 * @param string $string The string from which to remove parentheses.
	 * @return string The string with parentheses removed.
	 */
	public function removeAllParens( $string )
	{
		$parens = array( $this->openMark, $this->closeMark );
		return str_replace( $parens, '', $string );
	}
	
	/**
	 * Adds outer parentheses to a string.
	 *
	 * @param string $string The string to be added to.
	 * @return string The resulting string.
	 */
	public function addOuterParens( $string )
	{
		return $this->openMark . $string . $this->closeMark;
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
	public function closePosFromOpenPos( $string, $openPos )
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
	
	/**
	 * Creates a {@link Sentence sentence} instance from a string.
	 * 
	 * @param string $sentenceStr The string to interpret.
	 * @return Sentence The resulting instance.
	 * @throws {@link ParserException} on any errors in parsing the input string.
	 */
	abstract public function stringToSentence( $sentenceStr );
}