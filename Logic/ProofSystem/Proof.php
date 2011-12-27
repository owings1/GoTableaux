<?php
/**
 * Defines the base proof class.
 * @package Proof
 * @author Douglas Owings
 */

/**
 * Represents a proof.
 *
 * @package Proof
 * @author Douglas Owings
 **/
abstract class Proof
{
	/**
	 * Holds the argument for the proof.
	 * @var Argument
	 * @access private
	 */
	protected $argument;
	
	/**
	 * Holds a reference to the proof system.
	 * @var ProofSystem
	 * @access private
	 */
	protected $proofSystem;
	
	/**
	 * Constructor. Initializes argument.
	 *
	 * @param Argument $argument Argument for the proof.
	 * @param ProofSystem $proofSystem The proof system of the proof.
	 */
	public function __construct( Argument $argument, ProofSystem $proofSystem )
	{
		$this->argument = $argument;
		$this->proofSystem = $proofSystem;
	}
	
	/**
	 * Gets the Argument object.
	 *
	 * @return Argument The argument.
	 */
	public function getArgument()
	{
		return $this->argument;
	}
	
	/**
	 * Gets the ProofSystem object.
	 *
	 * @return ProofSystem The proof's proof system.
	 */
	public function getProofSystem()
	{
		return $this->proofSystem;
	}
	
	/**
	 * Checks whether the proof is valid
	 *
	 * @return boolean Whether the proof is valid.
	 */
	public function isValid()
	{
		return $this->getProofSystem()->isValidProof( $this );
	}
}