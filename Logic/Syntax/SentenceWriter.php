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
	 * Holds the options.
	 * @var array
	 * @access private
	 */
	protected $options = array( 'printZeroSubscripts' => false );
	
	/**
	 * Gets the value of a single option.
	 *
	 * @param string $option The name of the option to get.
	 * @return mixed The value of the option.
	 */
	public function getOption( $option )
	{
		return isset( $this->options[$option] ) ? $this->options[$option] : null;
	}
	
	/**
	 * Gets all the options.
	 *
	 * @return array The options. Key is option name, value is option value.
	 */
	public function getOptions()
	{
		return $this->options;
	}
	
	/**
	 * Sets an option.
	 *
	 * @param string $option The option to set.
	 * @param mixed $value The value of the option to set.
	 * @return SentenceWriter Current instance.
	 */
	public function setOption( $option, $value )
	{
		$this->options[$option] = $value;
	}
	
	/**
	 * Sets many options.
	 *
	 * @param array $options Array of options to set. Key is option name, value
	 *						 is option value.
	 * @return SentenceWriter Current instance.
	 */
	public function setOptions( array $options )
	{
		foreach ( $options as $option => $value ) $this->setOption( $option, $value );
		return $this;
	}
	
	/**
	 * Makes a string representation of a {@link Sentence}.
	 * 
	 * @param Sentence $sentence The sentence to write.
	 * @param Logic $logic The logic (language) relative to which to write the sentence.
	 * @return string The string representation of the sentence.
	 */
	abstract public function writeSentence( Sentence $sentence, Logic $logic );
}