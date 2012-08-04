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
	
	//  Define in child classes
	public $atomicSymbols = array();
	public $operatorNameSymbols = array();
	
	//  Reasonable defaults
	public $spaceSymbols = array( ' ', "\n", "\t" );
	
	//  Constructed from logic
	protected $operatorSymbolArities = array();
	
	//  Hashed for convenience
	protected $operatorSymbolNames = array();
	
	/**
	 * Maps symbols to types.
	 */
	protected $symbolTable = array();
	
	/**
	 * @var Logic
	 */
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
	 * Tests for a symbol being an operator symbol.
	 *
	 * @param string $symbol  The symbol to check.
	 * @return boolean  Whether the symbol is an operator symbol.
	 */
	public function isOperatorSymbol( $symbol )
	{
		return array_key_exists( $symbol, $this->operatorSymbolNames );
	}
	
	/**
	 * Parses an atomic sentence from a string that starts with an atomic symbol.
	 *
	 * This provides default functionality for parsing an atomic sentence from a
	 * string that starts with an atomic symbol. If the atomic symbol is followed
	 * by an integer, the sentence's subscript will be assigned the intval of the
	 * remaining string. 
	 *
	 * @param string $sentenceStr The string to parse.
	 * @return Sentence The resulting sentence instance.
	 * @throws {@link ParserException}.
	 */
	protected function parseAtomic( $sentenceStr )
	{
		$atomicStr = $this->readAtomic( $sentenceStr );
		$symbol = $atomicStr{0};
		$symbolIndex = array_search( $symbol, $this->atomicSymbols );
		if ( $symbolIndex === false ) throw new ParserException( "$char is not an atomic symbol." );
		return Sentence::createAtomic( $symbolIndex, ( int ) substr( $atomicStr, 1 ));
	}
	
	/**
	 * Reads an atomic sentence from a string that starts with an atomic symbol.
	 *
	 * @param string $sentenceStr  The string to read.
	 * @return string  The atomic sentence string.
	 * @throws {@link ParserException}.
	 */
	protected function readAtomic( $sentenceStr )
	{
		$char = $sentenceStr{0};
		if ( !in_array( $char, $this->atomicSymbols )) throw new ParserException( "$char is not an atomic symbol." );
		for ( $i = 1; $i < strlen( $sentenceStr ) && is_numeric( $sentenceStr{$i} ); $i++ );
		return substr( $sentenceStr, 0, $i );
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
		
	public function getOperatorSymbolNames()
	{
		return $this->operatorSymbolNames;
	}
	
	public function getLogicOperatorSymbolNames()
	{
		$logic = $this->logic;
		return array_filter( $this->operatorSymbolNames, function( $name ) use ( $logic ){
			return array_key_exists( $name, $logic->operatorArities );
		});
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