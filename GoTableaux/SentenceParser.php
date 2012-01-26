<?php
/**
 * Contains the base Parser class.
 * @package Syntax
 * @author Douglas Owings
 */

namespace GoTableaux;

/**
 * Represents a sentence parser.
 * @package Syntax
 * @author Douglas Owings
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
	 * Constructor. 
	 *
	 * Sets the vocabulary.
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
	 * @return Vocabulary The parser's vocabulary.
	 */
	public function getVocabulary()
	{
		return $this->vocabulary;
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