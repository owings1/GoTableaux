<?php
/**
 * Defines the base proof class.
 * @package Proof
 * @author Douglas Owings
 */

/**
 * Loads the {@link ProofException} class.
 */
require_once 'ProofException.php';

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
	 * Constructor. Initializes argument and proof system.
	 *
	 * @param Argument $argument Argument for the proof.
	 * @param ProofSystem $proofSystem Proof system to use.
	 */
	public function __construct( Argument $argument, ProofSystem $proofSystem )
	{
		$this->argument 	= $argument;
		$this->proofSystem 	= $proofSystem;
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
	 * Gets the proof system.
	 *
	 * @return ProofSystem The proof system for the proof.
	 */
	public function getProofSystem()
	{
		return $this->proofSystem;
	}
	
	/**
	 * Builds the proof.
	 *
	 * @return void
	 * @throws {@link ProofException} on errors.
	 */
	abstract public function build();
	
	/**
	 * Determines whether the built proof is valid.
	 *
	 * @param Counterexample|null &$counterexample Holds a counterexample, if any.
	 * @return boolean Whether the argument is valid.
	 */
	abstract public function isValid( &$counterexample );
}