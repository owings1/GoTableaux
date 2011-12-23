<?php
/**
 * Defines the base ProofSystem interface.
 * @package Proof
 * @author Douglas Owings
 */

/**
 * Loads the {@link Proof} base class.
 */
require_once 'Proof.php';

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
	 * Defines the proof class name for the system.
	 * @var string Class name.
	 * @see ProofSystem::evaluateArgument()
	 */
	protected $proofClass = 'Proof';
	
	/**
	 * Holds the Logic's vocabulary.
	 * @var Vocabulary
	 * @access private
	 */
	protected $vocabulary;
	
	/**
	 * Evaluates an argument.
	 *
	 * @param Argument $argument The argument to be evaluated.
	 * @return Proof|Counterexample A proof, if the argument is valid, or a
	 *								counterexample, if is is invalid.
	 * @throws {@link ProofException} on errors.
	 */
	public function evaluateArgument( Argument $argument )
	{
		$proofClass = $this->proofClass;
		$proof = new $proofClass( $argument );
		$this->buildProof( $proof );
		if ( $this->isValid( $proof )) return $proof;
		else return $this->getCounterexample( $proof );
	}
	
	/**
	 * Sets the vocabulary.
	 *
	 * @param Vocabulary $vocabulary The vocabulary of the logic.
	 * @return void
	 */
	public function setVocabulary( Vocabulary $vocabulary )
	{
		$this->vocabulary = $vocabulary;
	}
	
	/**
	 * Gets an operator from the logic's vocabulary.
	 *
	 * @param string $name The name of the operator.
	 * @return Operator The operator object.
	 */
	public function getOperator( $name )
	{
		return $this->vocabulary->getOperatorByName( $name );
	}
	
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
	 * Builds a proof.
	 *
	 * @param Proof $proof The proof object to operate on.
	 * @return void
	 */
	abstract public function buildProof( Proof $proof );
	
	/**
	 * Gets a counterexample from a proof.
	 *
	 * @param Proof $proof The (putative) proof from which to get a counterexample.
	 * @return Counterexample The counterexample built from the proof.
	 * @throws {@link ProofException} on type errors.
	 */
	abstract public function getCounterexample( Proof $proof );
}