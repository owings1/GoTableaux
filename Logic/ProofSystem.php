<?php
/**
 * Defines the ProofSystem base class.
 * @package Proof
 * @author Douglas Owings
 */

/**
 * Loads the {@link Proof} base class.
 */
require_once 'ProofSystem/Proof.php';

/**
 * Loads the {@link ProofException} class.
 */
require_once 'ProofSystem/ProofException.php';

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
	 * @see ProofSystem::constructProofForArgument()
	 */
	protected $proofClass = 'Proof';
	
	/**
	 * Holds a reference to the Logic's vocabulary.
	 * @var Vocabulary
	 * @access private
	 */
	protected $vocabulary;
	
	/**
	 * Constructs a proof an argument.
	 *
	 * @param Argument $argument The argument to be evaluated.
	 * @return Proof|Counterexample A proof, if the argument is valid, or a
	 *								counterexample, if is is invalid.
	 * @throws {@link ProofException} on errors.
	 */
	public function evaluateArgument( Argument $argument )
	{
		$proof = $this->constructProofForArgument( $argument );
		if ( $proof->isValid() ) return $proof;
		else return $this->getCounterexample( $proof );
	}
	
	/**
	 * Constructs a proof for an argument.
	 * 
	 * @param Argument $argument The argument for which to construct the proof.
	 * @return Poof $proof The constructed proof object.
	 */
	public function constructProofForArgument( Argument $argument )
	{
		$proofClass = $this->proofClass;
		$proof = new $proofClass( $argument, $this );
		$this->buildProof( $proof );
		return $proof;
	}
	
	/**
	 * Gets the vocabulary.
	 *
	 * @return Vocabulary $vocabulary The logic's vocabulary.
	 * @throws {@link ProofException} on empty vocabulary.
	 * @see Logic::getProofSystem()
	 */
	public function getVocabulary()
	{
		if ( empty( $this->vocabulary )) throw new ProofException( 'No vocabulary is set for the proof system.' );
		return $this->vocabulary;
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
	 * @see Vocabulary::getOperatorByName()
	 */
	public function getOperator( $name )
	{
		return $this->getVocabulary()->getOperatorByName( $name );
	}
	
	/**
	 * Registers a sentence in the logic's vocabulary.
	 *
	 * @param Sentence $sentence The sentence to register
	 * @return Sentence The sentence or the one from the registry, if found.
	 * @see Vocabulary::registerSentence()
	 */
	public function registerSentence( Sentence $sentence )
	{
		return $this->getVocabulary()->registerSentence( $sentence );
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
	abstract public function isValidProof( Proof $proof );
	
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