<?php
/**
 * Defines the base ProofSystem class.
 * @package Proof
 * @author Douglas Owings
 */

/**
 * Loads the {@link ProofException} class.
 */
require_once 'ProofException.php';

/**
 * Represents a proof system.
 * @package Proof
 * @author Douglas Owings
 */
abstract class ProofSystem
{	
	/**
	 * Evaluates an argument.
	 *
	 * @param Argument $argument The argument to be evaluated.
	 * @return Proof|Counterexample A proof, if the argument is valid, or a
	 *								counterexample, if is is invalid.
	 * @throws {@link ProofException} on errors.
	 */
	abstract public function evaluateArgument( Argument $argument );
	
	/**
	 * Constructor.
	 *
	 * The implementation must declare a constructor, which in most cases will
	 * create instances of the rules and load them into the proof system.
	 *
	 * @see Logic::initProofSystem()
	 */
	abstract public function __construct();
	
	/**
	 * Checks whether a putative proof is valid.
	 *
	 * @param Proof $proof The proof whose validity to check.
	 * @return boolean Whether the proof is valid.
	 * @throws {@link ProofException} on type errors.
	 */
	abstract public function isValid( Proof $proof );
	
	/**
	 * Gets a counterexample from a proof.
	 *
	 * @param Proof $proof The (putative) proof from which to get a counterexample.
	 * @return Counterexample The counterexample built from the proof.
	 * @throws {@link ProofException} on type errors.
	 */
	abstract public function getCounterexample( Proof $proof );
}