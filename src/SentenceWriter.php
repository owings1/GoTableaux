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
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program in file LICENSE.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * Defines the SentenceWriter base class.
 * @package GoTableaux
 * @author Douglas Owings
 */

namespace GoTableaux;

use \GoTableaux\Sentence\Atomic as AtomicSentence;
use \GoTableaux\Sentence\Molecular as MolecularSentence;

/**
 * Writes sentences.
 * @package GoTableaux
 * @author Douglas Owings
 */
abstract class SentenceWriter
{
	/**
	 * Defines the standard operator translations, if any.
	 * @var array
	 */
	protected $standardOperatorTranslations = array();
	
	/**
	 * Holds the operator strings.
	 * @var array
	 */
	private $operatorStrings = array();
	
	/**
	 * Holds the options.
	 * @var array
	 * @access private
	 */
	protected $options = array( 
		'printZeroSubscripts' => false,
		'dropOuterParentheses' => true,
	);
	
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
	 * Gets a decorator instance of a given type for a sentence writer.
	 *
	 * @param SentenceWriter $sentenceWriter The sentence writer for the instance to decorate.
	 * @param string $decoratorType The decorator type to instantiate.
	 */
	public static function getDecoratorInstance( SentenceWriter $sentenceWriter, $decoratorType )
	{
		$class = get_class( $sentenceWriter ) . '\\' . $decoratorType . 'Decorator';
		$instance = new $class( $sentenceWriter->getVocabulary() );
		$sentenceWriter->standardOperatorTranslations = array_merge( 
			$sentenceWriter->standardOperatorTranslations, 
			$instance->standardOperatorTranslations
		);
		$instance->sentenceWriter = $sentenceWriter;
		return $instance;
	}
	
	/**
	 * Constructor.
	 *
	 * Sets the vocabulary.
	 *
	 * @param Vocabulary $vocabulary The vocabulary for the writer to use.
	 */
	protected function __construct( Vocabulary $vocabulary )
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
	 * Gets the string for an operator by its name.
	 * 
	 * @param string $operatorName The name of the operator.
	 * @return string The string for the operator.
	 */
	public function getOperatorString( $operatorName )
	{
		if ( empty( $this->operatorStrings[$operatorName] )) {
			if ( isset( $this->standardOperatorTranslations[$operatorName] ))
				$this->operatorStrings[$operatorName] = $this->standardOperatorTranslations[$operatorName];
			else $this->operatorStrings[$operatorName] = $this->getVocabulary()->getSymbolForOperator( $operatorName );
		}
		return $this->operatorStrings[$operatorName];
	}
	
	/**
	 * Sets the strings to use for some operators.
	 * 
	 * @param array $strings Key is operator name, value is string.
	 * @return SentenceWriter Current instance.
	 */
	public function setOperatorStrings( array $strings )
	{
		$this->operatorStrings = array_merge( $this->operatorStrings, $strings );
		return $this;
	}
	
	/**
	 * Writes an operator.
	 *
	 * @param Operator|string $operatorOrName Operator object or name of operator.
	 * @return string String representation of the operator.
	 */
	public function writeOperator( $operatorOrName )
	{
		$name = $operatorOrName instanceof Operator ? $operatorOrName->getName() : $operatorOrName;
		return $this->getOperatorString( $name );
	}
	
	/**
	 * Writes a subscript index.
	 *
	 * @param integer $subscript The subscript index to write.
	 * @return string The string representation of the subscript index.
	 */
	public function writeSubscript( $subscript )
	{
		$subscriptSymbol = $this->getVocabulary()->getSubscriptSymbols( true );
		return $subscriptSymbol . $subscript;
	}
	
	/**
	 * Writes an atomic symbol.
	 *
	 * @param string $symbol The atomic symbol to write.
	 * @return string The representation of the atomic symbol.
	 */
	public function writeAtomicSymbol( $symbol )
	{
		return $symbol;
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
		$str = $this->writeAtomicSymbol( $sentence->getSymbol() );
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
		$str = $this->_writeSentence( $sentence );
									
		if ( $this->getOption( 'dropOuterParentheses' ))
			$str = ParserUtilities::dropOuterParens( $str, $this->getVocabulary() );
		
		return $str;
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
	
	/**
	 * Recursive function for writing sentences.
	 */
	protected function _writeSentence( Sentence $sentence )
	{
		return $sentence instanceof AtomicSentence ? $this->writeAtomic( $sentence )
												   : $this->writeMolecular( $sentence );
	}
}