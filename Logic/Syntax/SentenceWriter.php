<?php
/**
 * Defines the SentenceWriter base class.
 * @package Syntax
 * @author Douglas Owings
 */

/**
 * Loads the {@link ParserUtilites} class.
 */
require_once 'ParserUtilities.php';

/**
 * Loads the {@link StandardSentenceWriter} child class.
 */
require_once 'SentenceWriter/StandardSentenceWriter.php';

/**
 * Represents a {@link Sentence} writer.
 * @package Syntax
 * @author Douglas Owings
 */
abstract class SentenceWriter
{
	/**
	 * Makes a string representation of a {@link Sentence}.
	 * 
	 * @param Sentence $sentence The sentence to write.
	 * @param Logic $logic The logic (language) relative to which to write the sentence.
	 * @return string The string representation of the sentence.
	 */
	abstract public function writeSentence( Sentence $sentence, Logic $logic );
}