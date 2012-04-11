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
 * @package Syntax
 */

namespace GoTableaux;

use \GoTableaux\Exception\Parser as ParserException;

/**
 * Represents a sentence parser.
 * @package Syntax
 **/
abstract class SentenceParser
{
	/**
	 * Holds the vocabulary.
	 * @var Vocabulary
	 * @access private
	 */
	protected $vocabulary;
	
	/**
	 * Creates a child instance.
	 *
	 * @param Vocabulary $vocabulary The vocabulary for the parser to use.
	 * @param string $type The type of parser to create.
	 */
	public static function getInstance( Vocabulary $vocabulary, $type = 'Standard' )
	{
		$class = __CLASS__ . '\\' . $type;
		return new $class( $vocabulary );
	}
	
	/**
	 * Constructor. Sets the vocabulary.
	 *
	 * @param Vocabulary $vocabulary The vocabulary for the parser to use.
	 */
	public function __construct( Vocabulary $vocabulary )
	{
		$this->vocabulary = $vocabulary;
	}
	
	/**
	 * Gets the vocabulary.
	 *
	 * @return Vocabulary The vocabulary that the parser is using.
	 */
	public function getVocabulary()
	{
		return $this->vocabulary;
	}
	
	/**
	 * Parses an atomic sentence from a string that starts with an atomic symbol.
	 *
	 * This is the default implementation for parsing an atomic sentence from a
	 * string that starts with an atomic symbol. This implementation expects 
	 * the next symbol to be either a separator (space) character, or a 
	 * subscript character followed by an integer. If a subscript is not given,
	 * it will be assigned 0.
	 *
	 * @param string $sentenceStr The string to parse.
	 * @return Sentence The resulting sentence instance.
	 * @throws {@link ParserException}.
	 */
	protected function parseAtomic( $sentenceStr )
	{
		$vocabulary		= $this->getVocabulary();
		$atomicSymbols 	= $vocabulary->getAtomicSymbols();
		$subscripts 	= $vocabulary->getSubscriptSymbols();
		$hasSubscript 	= false !== Utilities::strPosArr( $sentenceStr, $subscripts, 1, $match );
		
		list( $symbol, $subscript ) = $hasSubscript ? explode( $match, $sentenceStr ) 
													: array( $sentenceStr, 0 );
		
		if ( !in_array( $symbol, $atomicSymbols ))
			throw new ParserException( "$symbol is not an atomic symbol." );
		
		if ( !is_numeric( $subscript ))
			throw new ParserException( "Subscript must be numeric." );
		
		return Sentence::createAtomic( $symbol, $subscript );
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