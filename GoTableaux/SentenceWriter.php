<?php
/**
 * Defines the SentenceWriter base class.
 * @package Syntax
 * @author Douglas Owings
 */

namespace GoTableaux;

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
	 * Holds the vocabulary
	 * @var Vocabulary
	 * @access private
	 */
	protected $vocabulary;
	
	/**
	 * Creates a child instance.
	 *
	 * @param Vocabulary $vocabulary The vocabulary for the writer to use.
	 * @param string $type Type of writer to instantiate, default is 'Standard'.
	 * @return SentenceWriter New instance.
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
	 * @param Vocabulary $vocabulary The vocabulary for the writer to use.
	 */
	public function __construct( Vocabulary $vocabulary )
	{
		$this->setVocabulary( $vocabulary );
	}
	
	/**
	 * Gets the vocabulary.
	 *
	 * @return Vocabulary The writer's vocabulary.
	 */
	public function getVocabulary()
	{
		return $this->vocabulary;
	}
	
	/**
	 * Sets the vocabulary.
	 *
	 * @param Vocabulary $vocabulary 
	 * @return SentenceWriter Current instance.
	 */
	public function setVocabulary( Vocabulary $vocabulary )
	{
		$this->vocabulary = $vocabulary;
	}
	
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
	 * Makes an array of string representations of {@link Sentence}s.
	 *
	 * @param array $sentences The sentences to write.
	 * @return array An array of string representations of the sentences.
	 */
	public function writeSentences( array $sentences )
	{
		$strings = array();
		foreach ( $sentences as $key => $sentence )
			$strings[$key] = $this->writeSentence( $sentence);
		return $strings;
	}
	
	/**
	 * Makes a formatted Argument array.
	 *
	 * @param Argument $argument The argument to format.
	 * @return array Formatted argument array.
	 */
	public function getArgumentArray( Argument $argument )
	{
		return array(
			'premises' 		=> $this->writeSentences( $argument->getPremises() ),
			'conclusion' 	=> $this->writeSentence( $argument->getConclusion() )
		);
	}
	
	/**
	 * Makes a string representation of a {@link Sentence}.
	 * 
	 * @param Sentence $sentence The sentence to write.
	 * @return string The string representation of the sentence.
	 */
	abstract public function writeSentence( Sentence $sentence );
	
	/**
	 * Makes a string representation of an argument.
	 *
	 * @param Argument $argument The argument to write.
	 * @return string The string representation of the argument.
	 */
	abstract public function writeArgument( Argument $argument );
}