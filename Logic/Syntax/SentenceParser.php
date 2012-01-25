<?php
/**
 * Contains the base Parser class.
 * @package Syntax
 * @author Douglas Owings
 */

/**
 * Loads the {@link ParserException} class.
 */
require_once dirname( __FILE__ ) . "/../Exceptions/ParserException.php";

/**
 * Loads the {@link StandardSentenceParser} class, which is the default parser class.
 */
require_once dirname( __FILE__ ) . "/SentenceParser/StandardSentenceParser.php";

/**
 * Loads {@link Sentence} class.
 */
require_once dirname( __FILE__ ) . "/Sentence.php";

/**
 * Loads the {@link Argument} class.
 */
require_once dirname( __FILE__ ) . "/../Argument.php";

/**
 * Loads the {@link ParserUtilites} class.
 */
require_once dirname( __FILE__ ) . "/ParserUtilities.php";

/**
 * Represents a sentence parser.
 * @package Syntax
 * @author Douglas Owings
 **/
abstract class SentenceParser
{
	/**
	 * Creates a {@link Sentence sentence} instance from a string.
	 * 
	 * @param string $sentenceStr The string to interpret.
	 * @param Vocabulary $vocabulary The vocabulary relative to which to parse.
	 * @return Sentence The resulting instance.
	 * @throws {@link ParserException} on any errors in parsing the input string.
	 */
	abstract public function stringToSentence( $sentenceStr, Vocabulary $vocabulary );
}