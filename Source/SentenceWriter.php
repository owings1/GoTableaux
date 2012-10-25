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
 * Defines the SentenceWriter base class.
 * @package GoTableaux
 */

namespace GoTableaux;

use \GoTableaux\Utilities\Parser as ParserUtilities;
use \GoTableaux\Sentence\Atomic as AtomicSentence;
use \GoTableaux\Sentence\Molecular as MolecularSentence;

/**
 * Writes sentences.
 * @package GoTableaux
 */
abstract class SentenceWriter
{
	
	//  Define in child classes
	public $atomicStrings = array();
	public $operatorStrings = array();
	
	//  Optional
	public $options = array();
	
	/**
	 * Holds the options.
	 * @var array
	 * @access private
	 */
	private $_options = array( 
		'printZeroSubscripts' => false,
	);
	
	/**
	 * Creates a child instance.
	 *
	 * @param Logic $logic The logic for the writer to use.
	 * @param string $notation Type sentence notation.
	 * @param string $format The sentence format.
	 * @return SentenceWriter New instance.
	 */
	public static function getInstance( Logic $logic, $notation = 'Standard', $format = null )
	{
		if ( empty( $notation )) $notation = 'Standard';
		$class = __CLASS__ . '\\' . $notation;
		if ( !empty( $format )) $class .= '\\' . $format;
		return new $class( $logic );
	}
	
	/**
	 * Constructor.
	 *
	 * Sets the logic.
	 *
	 * @param Logic $logic The Logic for the writer to use.
	 */
	protected function __construct( Logic $logic )
	{
		$this->logic = $logic;
		$this->options = array_merge( $this->_options, $this->options );
	}

	final public function getNotation()
	{
		$class = str_replace( __CLASS__, get_class( $this ));
		list( $notation ) = explode( '\\', trim( $class, '\\' ));
		return $notation;
	}
	
	final public function getFormat()
	{
		$class = str_replace( __CLASS__, get_class( $this ));
		list( $notation, $format ) = explode( '\\', trim( $class, '\\' ));
		return $format;
	}
	
	/**
	 * Gets the logic
	 *
	 * @return Logic The logic.
	 */
	public function getLogic()
	{
		return $this->logic;
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
	 * Writes a subscript index.
	 *
	 * @param integer $subscript The subscript index to write.
	 * @return string The string representation of the subscript index.
	 */
	public function writeSubscript( $subscript )
	{
		return "$subscript";
	}
	
	/**
	 * Writes an atomic symbol.
	 *
	 * @param integer $index The atomic symbol index to write.
	 * @return string The representation of the atomic symbol.
	 */
	public function writeAtomicSymbolIndex( $index )
	{
		return $this->atomicStrings[ $index ];
	}
	
	/**
	 * Writes an atomic sentence.
	 * 
	 * @param AtomicSentence $sentence The atomic sentence to represent.
	 * @return string The string representation of the atomic sentence.
	 */
	public function writeAtomic( AtomicSentence $sentence )
	{
		$subscript = $sentence->getSubscript();
		$str = $this->writeAtomicSymbolIndex( $sentence->getSymbolIndex() );
		if ( $subscript > 0 || $this->getOption( 'printZeroSubscripts' ))
			$str .= $this->writeSubscript( $subscript );
		return $str;
	}
	
	/**
	 * Makes a string representation of a sentence.
	 * 
	 * @param Sentence $sentence The sentence to write.
	 * @return string The string representation of the sentence.
	 */
	public function writeSentence( Sentence $sentence )
	{	
		return $sentence instanceof AtomicSentence ? $this->writeAtomic( $sentence )
												   : $this->writeMolecular( $sentence );
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
	 * Makes a string representation of an argument.
	 *
	 * @param Argument $argument The argument to write.
	 * @return string The string representation of the argument.
	 */
	public function writeArgument( Argument $argument )
	{
		Utilities::debug( 'writing argument as ' . get_class( $this ));
		$premisesStr = implode( ', ', $this->writeSentences( $argument->getPremises() ));
		$conclusionStr = $this->writeSentence( $argument->getConclusion() );
		return "$premisesStr Therefore $conclusionStr";
	}
	
	/**
	 * Makes a string representation of a molecular sentence.
	 * 
	 * @param MolecularSentence $sentence The sentence to write.
	 * @return string The string representation of the sentence.
	 */
	abstract public function writeMolecular( MolecularSentence $sentence );

}