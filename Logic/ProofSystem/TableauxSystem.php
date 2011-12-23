<?php
/**
 * Defines the TableauxSystem base class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link ProofSystem} parent class.
 */
require_once 'ProofSystem.php';

/**
 * Loads the {@link Tableau} class.
 */
require_once 'Tableaux/Tableau.php';

/**
 * Loads the {@link Model} class for counterexamples.
 */
require_once '../ModelTheory/Model.php';

/**
 * Represents a tableaux proof system.
 * @package Tableaux
 * @author Douglas Owings
 */
abstract class TableauxSystem extends ProofSystem
{
	/**
	 * Defines the tableau proof class.
	 * @var string Class name of tableau.
	 */
	protected $tableauClass = 'Tableau';
	
	/**
	 * @var ClosureRule
	 */
	protected $closureRule;
	
	/**
	 * @var array Array of {@link BranchRule}s.
	 */
	protected $branchRules = array();
	
	/**
	 * Sets the closure rule.
	 *
	 * @param ClosureRule $closureRule The closure rule.
	 * @return TableauxSystem Current instance.
	 */
	public function setClosureRule( ClosureRule $closureRule )
	{
		$this->closureRule = $closureRule;
		return $this;
	}
	
	/**
	 * Gets the closure rule.
	 *
	 * @return ClosureRule The closure rule.
	 */
	public function getClosureRule()
	{
		return $this->closureRule;
	}
	
	/**
	 * Applies the closure rule to a tableau.
	 *
	 * @param Tableau $tableau The tableau to which to apply closure rule.
	 * @return void
	 * @throws {@link TableauException} on empty closure rule.
	 */
	public function applyClosureRule( Tableau $tableau )
	{
		$closureRule = $this->getClosureRule();
		
		if ( empty( $closureRule )) 
			throw new TableauException( 'No closure rule set for Tableaux System.' );
		
		foreach ( $tableau->getOpenBranches() as $branch )
			if ( $closureRule->doesApply( $branch )) $branch->close();
	}
	
	/**
	 * Adds tableau rules. Duplicate entries are ignored.
	 *
	 * @param BranchRule|array $branchRule The branch rule(s) to add.
	 * @return TableauxSystem Current Instance.
	 */
	public function addBranchRules( $branchRules )
	{
		if ( is_array( $branchRules ))
			foreach ( $branchRules as $rule ) $this->_addBranchRule( $rule );
		else $this->_addBranchRule( $branchRules );
		
		return $this;
	}
	
	/**
	 * Gets tableau rules.
	 *
	 * @return array Array of {@link BranchRule}s.
	 */
	public function getBranchRules()
	{
		return $this->branchRules;
	}
	
	/**
	 * Evaluates an argument.
	 *
	 * @param Argument $argument The argument to be evaluated.
	 * @return Tableau|Model A tableau proof, if the argument is valid, or a
	 *						 countermodel, if is is invalid.
	 * @throws {@link ProofException} on errors.
	 */
	public function evaluateArgument( Argument $argument )
	{
		$tableauClass = $this->tableauClass;
		$tableau = new $tableauClass;
		$this->buildTrunk( $tableau, $argument );
		
		$branchRules = $this->getBranchRules();
		if ( empty( $branchRules )) throw new TableauException( 'Rule set cannot be empty.' );
		
		$i = 0;
		do {
			$this->applyClosureRule( $tableau );
			$rule 			= $branchRules[$i];
			$ruleDidApply 	= false;
			foreach ( $tableau->getOpenBranches() as $branch ) {
				$result = $rule->apply( $branch );
				if ( $result !== false ) {
					$i = 0;
					$ruleDidApply = true;
					if ( !empty( $result )) $tableau->attach( $result );
				}
			}
		} while ( $ruleDidApply || isset( $branchRules[++$i] ));
		
		$this->applyClosureRule( $tableau );
		
		if ( $this->isValid( $tableau )) {
			$tableau->buildStructure();
			return $tableau;
		}
		
		return $this->getCounterExample( $tableau );
	}
	/**
	 * Checks whether a Tableau is a valid proof.
	 *
	 * @param Tableau $proof The tableau whose validity to check.
	 * @return boolean Whether the tableau is a valid proof.
	 * @throws {@link ProofException} when $proof is of wrong type.
	 */
	public function isValid( Proof $proof )
	{
		if ( !$proof instanceof $this->proofClass )
			throw new ProofException( "Proof is not an instance of {$this->proofClass}." );
		$openBranches = $proof->getOpenBranches();
		return empty( $openBranches );
	}
	
	/**
	 * Gets a counterexample from a Tableau proof.
	 *
	 * A counterexample for a tableaux system is a model induced from an open
	 * branch. 
	 *
	 * @param Tableau $proof The tableau from which to build the counterexample
	 * @return Counterexample The counterexample extracted from the proof.
	 * @throws {@link ProofException} on type error.
	 * @throws {@link TableauException} on no open branches.
	 */
	public function getCounterexample( Proof $proof )
	{
		if ( !$proof instanceof Tableau )
			throw new ProofException( "Proof is not an instance of class Tableau." );
		$openBranches = $proof->getOpenBranches();
		if ( empty( $openBranches ))
			throw new TableauException( 'No open branches found on tableau.' );
		return $this->induceModel( array_pop( $openBranches ));
	}
	
	/**
	 * Induces a model from an open branch.
	 *
	 * @param Branch $branch The open branch from which to induce a model
	 * @return Model The induced model.
	 */
	abstract public function induceModel( Branch $branch );
	
	/**
	 * Constructs the initial list (trunk) for an argument.
	 *
	 * @param Tableau $tableau The tableau to attach the 
	 * @param Argument $argument The argument for which to build the trunk.
	 * @return void
	 */
	abstract public function buildTrunk( Tableau $tableau, Argument $argument );
	
	/**
	 * Adds a tableau rule.
	 *
	 * @param BranchRule The rule to add.
	 * @return void
	 */
	protected function _addBranchRule( BranchRule $branchRule )
	{
		if ( !in_array( $branchRule, $this->branchRules, true ))
			$this->branchRules[] = $branchRule;
	}
}