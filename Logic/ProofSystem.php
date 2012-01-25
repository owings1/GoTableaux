<?php
/**
 * Defines the ProofSystem base class.
 * @package Proof
 * @author Douglas Owings
 */

/**
 * Loads the {@link Proof} base class.
 */
require_once dirname( __FILE__ ) . "/ProofSystem/Proof.php";

/**
 * Loads the {@link ProofException} class.
 */
require_once dirname( __FILE__ ) . "/Exceptions/ProofException.php";

/**
 * Loads the {@link TableauxSystem} child class.
 */
require_once dirname( __FILE__ ) . "/ProofSystem/TableauxSystem.php";

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
	 * Holds a reference to the logic instance.
	 * @var Logic
	 * @access private
	 */
	protected $logic;
	
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
	 * Sets the logic instance.
	 *
	 * @param Logic $logic The logic.
	 * @return ProofSystem Current instance.
	 * @see Logic::__construct()
	 */
	public function setLogic( Logic $logic )
	{
		$this->logic = $logic;
		return $this;
	}
	
	/**
	 * Gets the logic instance.
	 *
	 * @return Logic The logic instance.
	 * @throws {@link ProofException} on empty logic.
	 * @see Logic::__construct()
	 */
	public function getLogic()
	{
		if ( empty( $this->logic )) throw new ProofException( 'Logic is empty.' );
		return $this->logic;
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