<?php
/**
 * Defines the ProofSystem base class.
 * @package Proof
 * @author Douglas Owings
 */

namespace GoTableaux;

/**
 * Represents a proof system.
 * @package Proof
 * @author Douglas Owings
 */
abstract class ProofSystem
{	
	/**
	 * Holds a reference to the logic instance.
	 * @var Logic
	 * @access private
	 */
	protected $logic;

	
	/**
	 * Constructor.
	 *
	 * @param Logic $logic The logic for the proof system to use.
	 */
	public function __construct( Logic $logic )
	{
		$this->logic = $logic;
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
		return $this->logic;
	}
	
	/**
	 * Constructs a proof for an argument.
	 * 
	 * @param Argument $argument The argument for which to construct the proof.
	 * @return Poof $proof The constructed proof object.
	 */
	abstract public function constructProofForArgument( Argument $argument );
	
	/**
	 * Checks whether a putative proof is valid.
	 *
	 * @param Proof $proof The proof whose validity to check.
	 * @return boolean Whether the proof is valid.
	 * @throws {@link ProofException} on type errors.
	 */
	abstract public function isValidProof( Proof $proof );
	
	/**
	 * Gets a counterexample from a proof.
	 *
	 * @param Proof $proof The (putative) proof from which to get a counterexample.
	 * @return Model The countermodel built from the proof.
	 * @throws {@link ProofException} on type errors.
	 */
	abstract public function getCountermodel( Proof $proof );
}