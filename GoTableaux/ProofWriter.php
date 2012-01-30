<?php
/**
 * Defines the ProofWriter class.
 * @package Proof
 * @author Douglas Owings
 */

namespace GoTableaux;

use \GoTableaux\Exception\Writer as WriterException;
use \GoTableaux\SentenceWriter\Decorator as SentenceWriterDecorator;

/**
 * Writes proofs.
 * @package Proof
 * @author Douglas Owings
 */
abstract class ProofWriter
{
	/**
	 * 
	 * @var array
	 */
	private $translations = array();
	
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
		$operatorNames = $this->vocabulary->getOperatorNames();
		$operatorSymbols = array_flip( $operatorNames );
		$this->getSentenceWriter()->setOperatorStrings( $operatorSymbols );
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
		foreach ( $translations as $name => $value )
			$this->translations[$name] = $value;
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