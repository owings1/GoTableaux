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
 * @package GoTableaux
 */

namespace GoTableaux;

use \GoTableaux\Exception\Writer as WriterException;
use \GoTableaux\SentenceWriter\Decorator as SentenceWriterDecorator;

/**
 * Writes proofs.
 * @package GoTableaux
 */
abstract class ProofWriter
{
	public $metaSymbolStrings = array();
	
	/**
	 * @var SentenceWriter
	 */
	protected $sentenceWriter;
	
	/**
	 * @var Logic
	 */
	protected $logic;
	
	/**
	 * Gets a child instance.
	 *
	 * @param string $proofType The type of proof.
	 * @param Logic $logic The logic to use.
	 * @param string $type Proof writer output type.
	 * @param string $notation The type of sentence writer to use.
	 * @param string $format The sentence writer format.
	 * @return ProofWriter Created instance.
	 */
	public static function getInstance( $proofType, Logic $logic, $output = 'Simple', $notation = 'Standard', $format = null )
	{
		if ( empty( $notation )) $notation = 'Standard';
		if ( empty( $output )) $output = 'Simple';
		$class = __CLASS__ . '\\' . $proofType . '\\' . $output;
		return new $class( $logic, $notation, $format );
	}
	
	/**
	 * Constructor.
	 *
	 * @param Logic $logic The logic.
	 * @param string $notation The type of sentence writer to use.
	 */
	public function __construct( Logic $logic, $notation = null, $format = null )
	{
		$this->logic = $logic;
		$this->sentenceWriter = SentenceWriter::getInstance( $logic, $notation, $format );
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
		return $this->sentenceWriter->writeSentence( $sentence );
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
		return $this->sentenceWriter->writeArgument( $proof->getArgument() );
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