<?php
/**
 * Defines the TableauxSystem base class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link ProofSystem} parent class.
 */
require_once 'GoTableaux/Logic/ProofSystem.php';

/**
 * Loads the {@link ClosureRule} interface.
 */
require_once 'Tableaux/ClosureRule.php';

/**
 * Loads the {@link BranchRule} interface.
 */
require_once 'Tableaux/BranchRule.php';

/**
 * Loads the {@link Tableau} base class.
 */
require_once 'Tableaux/Tableau.php';

/**
 * Loads the {@link Model} class for counterexamples.
 */
require_once 'GoTableaux/Logic/ModelTheory/Model.php';

/**
 * Represents a tableaux proof system.
 * @package Tableaux
 * @author Douglas Owings
 */
abstract class TableauxSystem extends ProofSystem
{
	/**
	 * Defines the closure rule class for the logic.
	 * @var string Class name of closure rule.
	 */
	public $closureRuleClass = 'ClosureRule';
	
	/**
	 * Defines the branch rule classes for the logic.
	 * @var array Array of class names of branch rules.
	 * @see TableauxSystem::__construct()
	 * @see TableauxSystem::addBranchRules()
	 */
	public $branchRuleClasses = array();
	
	/**
	 * Defines the tableau proof class.
	 * @var string Class name of tableau.
	 */
	protected $proofClass = 'Tableau';
	
	/**
	 * @var ClosureRule
	 */
	protected $closureRule;
	
	/**
	 * @var array Array of {@link BranchRule}s.
	 */
	protected $branchRules = array();
	
	/**
	 * Constructor.
	 *
	 * Loads the rules of the tableaux system.
	 *
	 * @see $closureRuleClass
	 * @see $branchRuleClasses
	 */
	public function __construct()
	{
		if ( !class_exists( $this->closureRuleClass ))
			throw new TableauException( "Class {$this->closureRuleClass} not found." );
		$this->closureRule = new $this->closureRuleClass;
		if ( empty( $this->branchRuleClasses ))
			throw new TableauException( 'Branch rules cannot be empty. Set TableauxSystem::$branchRuleClasses' );
		foreach ( $this->branchRuleClasses as $class ) {
			if ( !class_exists( $class )) throw new TableauException( "Class $class not found." );
			$this->addBranchRules( new $class );
		}
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
		foreach ( $tableau->getOpenBranches() as $branch )
			if ( $closureRule->doesApply( $branch, $this )) $branch->close();
	}
	
	/**
	 * Adds tableau rules. Duplicate entries are ignored.
	 *
	 * @param BranchRule|array $branchRule The branch rule(s) to add.
	 * @return TableauxSystem Current Instance.
	 */
	public function addBranchRules( $branchRules )
	{
		if ( is_array( $branchRules )) {
			foreach ( $branchRules as $rule ) $this->addBranchRules( $rule );
			return $this;
		}
		if ( !$branchRules instanceof BranchRule )
			throw new TableauException( 'Branch rule must be instance of BranchRule.' );
		if ( !in_array( $branchRules, $this->branchRules, true ))
			$this->branchRules[] = $branchRules;
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
	 * Builds a tableau.
	 *
	 * @param Tableau $tableau The tableau to build.
	 * @return void
	 */
	public function buildProof( Proof $tableau )
	{
		$this->buildTrunk( $tableau, $tableau->getArgument() );
		$branchRules = $this->getBranchRules();
		$i = 0;
		do {
			$this->applyClosureRule( $tableau );
			$rule 			= $branchRules[$i];
			echo "Applying rule " . get_class($rule) . "\n";
			$ruleDidApply 	= false;
			foreach ( $tableau->getOpenBranches() as $branch ) 
				if ( $rule->apply( $branch, $this ) !== false ) {
					echo "Rule applied\n";
					$ruleDidApply = true;
					$i = 0;
				} else echo "Rule did not apply\n";
		} while ( $ruleDidApply || isset( $branchRules[++$i] ));
		$this->applyClosureRule( $tableau );
	}
	
	/**
	 * Checks whether a Tableau is a valid proof.
	 *
	 * @param Tableau $tableau The tableau whose validity to check.
	 * @return boolean Whether the tableau is a valid proof.
	 * @throws {@link ProofException} when $proof is of wrong type.
	 */
	public function isValidProof( Proof $tableau )
	{
		return !$tableau->hasOpenBranches();
	}
	
	/**
	 * Gets a counterexample from a Tableau proof.
	 *
	 * A counterexample for a tableaux system is a model induced from an open
	 * branch. 
	 *
	 * @param Tableau $tableau The tableau from which to build the counterexample
	 * @return Counterexample The counterexample extracted from the proof.
	 * @throws {@link ProofException} on type error.
	 * @throws {@link TableauException} on no open branches.
	 */
	public function getCounterexample( Proof $tableau )
	{
		return $this->induceModel( array_pop( $tableau->getOpenBranches() ));
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
}