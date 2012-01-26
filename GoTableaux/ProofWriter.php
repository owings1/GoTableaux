<?php
/**
 * Defines the ProofWriter class.
 * @package Proof
 * @author Douglas Owings
 */

namespace GoTableaux;

/**
 * Writes proofs.
 * @package Proof
 * @author Douglas Owings
 */
abstract class ProofWriter
{
	/**
	 * @var SentenceWriter
	 * @access private
	 */
	protected $sentenceWriter;
	
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
		$vocabulary = $proof->getProofSystem()->getLogic()->getVocabulary();
		$this->setSentenceWriter( SentenceWriter::getInstance( $vocabulary, $sentenceWriterType ));
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
	 * @return TableauWriter Current instance.
	 */
	public function setSentenceWriter( SentenceWriter $sentenceWriter )
	{
		$this->sentenceWriter = $sentenceWriter;
		return $this;
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