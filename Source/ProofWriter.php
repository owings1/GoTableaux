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
 * Defines the ProofWriter class.
 * @package Proof
 */

namespace GoTableaux;

use \GoTableaux\Exception\Writer as WriterException;
use \GoTableaux\SentenceWriter\Decorator as SentenceWriterDecorator;

/**
 * Writes proofs.
 * @package Proof
 */
abstract class ProofWriter
{
	/**
	 * 
	 * @var array
	 */
	protected $translations = array();
	
	/**
	 * @var SentenceWriter
	 * @access private
	 */
	private $sentenceWriter;
	
	/**
	 * @var Vocabulary
	 */
	private $vocabulary;
	
	/**
	 * Gets a child instance.
	 *
	 * @param Proof $proof The proof to write.
	 * @param string $type Proof writer type.
	 * @param string $sentenceWriterType The type of sentence writer to use.
	 * @return ProofWriter Created instance.
	 */
	public static function getInstance( Proof $proof, $type = 'Simple', $sentenceWriterType = 'Standard' )
	{
		$proofClass = get_class( $proof );
		$class = str_replace( '\\Proof\\', '\\ProofWriter\\', $proofClass ) . "\\$type";
		return new $class( $proof, $sentenceWriterType );
	}
	
	/**
	 * Constructor.
	 *
	 * @param Proof $proof The proof to write.
	 * @param string $sentenceWriterType The type of sentence writer to use.
	 */
	public function __construct( Proof $proof, $sentenceWriterType = 'Standard' )
	{
		$this->vocabulary = $proof->getProofSystem()->getLogic()->getVocabulary();
		$this->setSentenceWriter( SentenceWriter::getInstance( $this->vocabulary, $sentenceWriterType ));
	}
	
	/**
	 * Adds translations.
	 *
	 * @param array $translations The translations to add, where key is to be
	 *							  translated into value.
	 * @return ProofWriter Current instance.
	 */
	public function addTranslations( array $translations )
	{
		$this->translations = array_merge( $this->translations, $translations );
		return $this;
	}
	
	/**
	 * Removes a translation.
	 *
	 * @param string $name Name of the translation to remove.
	 * @return ProofWriter Current instance.
	 */
	public function removeTranslation( $name )
	{
		unset( $this->translations[$name] );
		return $this;
	}
	
	/**
	 * Gets a translation.
	 *
	 * @param string $name Name of the translation.
	 * @return string The translation.
	 */
	public function getTranslation( $name )
	{
		if ( empty( $this->translations[$name] ))
			throw new WriterException( "Unknown translation name: $name" );
		return $this->translations[$name];
	}
	
	/**
	 * Gets the sentence writer object.
	 *
	 * @return SentenceWriter The sentence writer.
	 */
	public function getSentenceWriter()
	{
		return $this->sentenceWriter;
	}
	
	/**
	 * Sets the sentence writer object.
	 *
	 * @param SentenceWriter $sentenceWriter The sentence writer to set.
	 * @return ProofWriter Current instance.
	 */
	public function setSentenceWriter( SentenceWriter $sentenceWriter )
	{
		$this->sentenceWriter = $sentenceWriter;
		return $this;
	}
	
	/**
	 * Decorates the sentence writer.
	 *
	 * @param string $type Type of decorator.
	 * @return ProofWriter Current instance.
	 */
	public function decorateSentenceWriter( $type )
	{
		return $this->setSentenceWriter( SentenceWriter::getDecoratorInstance( $this->getSentenceWriter(), $type ));
	}
	
	/**
	 * Writes a sentence.
	 *
	 * Delegates to sentence writer.
	 * 
	 * @param Sentence $sentence The sentence to write.
	 * @return string The string representation of the sentence.
	 */
	public function writeSentence( Sentence $sentence )
	{
		return $this->getSentenceWriter()->writeSentence( $sentence );
	}
	
	/**
	 * Writes a proof's argument.
	 *
	 * Delegates to $this->sentenceWriter.
	 * 
	 * @param Proof $proof The proof whose argument to write.
	 * @return string The string for the argument.
	 */
	public function writeArgumentOfProof( Proof $proof )
	{
		return $this->getSentenceWriter()->writeArgument( $proof->getArgument() );
	}
	
	/**
	 * Gets a formatted data array of a proof.
	 *
	 * @param Proof $proof Proof to get data from.
	 * @return array Formatted data array.
	 */
	abstract public function getArray( Proof $proof );
	
	/**
	 * Makes a string representation of a proof.
	 * 
	 * @param Proof $proof The proof to represent.
	 * @return string The string representation.
	 */
	abstract public function writeProof( Proof $proof );
}