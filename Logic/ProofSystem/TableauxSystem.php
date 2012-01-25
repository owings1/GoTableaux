<?php
/**
 * Defines the TableauxSystem base class.
 * @package Tableaux
 * @author Douglas Owings
 */

namespace GoTableaux;

/**
 * Loads the {@link ProofSystem} parent class.
 */
require_once dirname( __FILE__ ) . "/../ProofSystem.php";

/**
 * Loads the {@link ClosureRule} interface.
 */
require_once dirname( __FILE__ ) . "/Tableaux/ClosureRule.php";

/**
 * Loads the {@link BranchRule} interface.
 */
require_once dirname( __FILE__ ) . "/Tableaux/BranchRule.php";

/**
 * Loads the {@link Tableau} base class.
 */
require_once dirname( __FILE__ ) . "/Tableaux/Tableau.php";

/**
 * Loads the {@link Branch} base class.
 */
require_once dirname( __FILE__ ) . "/Tableaux/Branch.php";

/**
 * Loads the {@link Model} class for counterexamples.
 */
require_once dirname( __FILE__ ) . "/../ModelTheory/Model.php";

/**
 * Loads the {@link PropositionalTableauxSystem} child class.
 */
require_once dirname( __FILE__ ) . "/Tableaux/PropositionalTableauxSystem.php";

/**
 * Loads the {@link ModalTableauxSystem} child class.
 */
require_once dirname( __FILE__ ) . "/Tableaux/ModalTableauxSystem.php";

/**
 * Loads the {@link ManyValuedTableauxSystem} child class.
 */
require_once dirname( __FILE__ ) . "/Tableaux/ManyValuedTableauxSystem.php";

/**
 * Loads the {@link ManyValuedModalTableauxSystem} child class.
 */
require_once dirname( __FILE__ ) . "/Tableaux/ManyValuedModalTableauxSystem.php";

/**
 * Represents a tableaux proof system.
 * @package Tableaux
 * @author Douglas Owings
 */
abstract class TableauxSystem extends ProofSystem
{	
	/**
	 * Defines the branch rule classes names for the logic.
	 * @var array
	 * @see TableauxSystem::__construct()
	 * @see TableauxSystem::addBranchRules()
	 */
	public $branchRuleClasses = array();
	
	/**
	 * Defines the branch class name for the tableaux.
	 * @var string
	 */
	public $branchClass = 'Branch';
	
	/**
	 * Defines the closure rule class name for the logic.
	 * @var string Class name of closure rule.
	 */
	protected $closureRuleClass;
	
	/**
	 * Defines the tableau proof class.
	 * @var string Class name of tableau.
	 */
	protected $proofClass = 'Tableau';
	
	/**
	 * @var ClosureRule
	 * @access private
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
		$nameArr 			= explode( '\\', get_class( $this ));
		$tableauxSystemName = end( $nameArr );
		$logicName 			= str_replace( array( 'TableauxSystem', 'ProofSystem' ), '', $tableauxSystemName );
		$logicsPath 		= Settings::read( 'logicsPath' );
		$systemClass 		= new \ReflectionClass( $this );
		
		// Autoload Tableaux Rule classes
		$logicNames = array( $logicName );
		$class = new \ReflectionClass( $this );
		while ( $class = $class->getParentClass() ) 
			if ( !$class->isAbstract() ) {
				$nameArr = explode( '\\', $class->getName() );
				$logicNames[] = str_replace( array( 'TableauxSystem', 'ProofSystem' ), '',  end( $nameArr ));
			}
		
		foreach ( $logicNames as $name ) {
			$tableauxRulesFileName = $logicsPath . $name . DIRECTORY_SEPARATOR . 'tableaux_rules.php';
			if ( file_exists( $tableauxRulesFileName )) 
				require_once $tableauxRulesFileName;
		}
		
		// Set ClosureRule class
		if ( empty( $this->closureRuleClass )) 
			$this->closureRuleClass = $logicName . 'ClosureRule';
		
		$namespacedClassName = __NAMESPACE__ . '\\' . $this->closureRuleClass;
		// Autoload ClosureRule classes
		if ( !class_exists( $namespacedClassName )) {
			$closureRuleFileName =  $logicsPath . $logicName . DIRECTORY_SEPARATOR . $this->closureRuleClass . '.php';
			if ( file_exists( $closureRuleFileName )) require_once $closureRuleFileName;
			else throw new TableauException( "Closure rule class {$this->closureRuleClass} not found, looking for $closureRuleFileName." );
		}
		$this->closureRule = new $namespacedClassName;
		
		if ( empty( $this->branchRuleClasses ))
			throw new TableauException( 'Branch rules cannot be empty. Set TableauxSystem::$branchRuleClasses' );
		
		// Instantiate BranchRules
		foreach ( $this->branchRuleClasses as $class ) {
			$namespacedClassName = __NAMESPACE__ . '\\' . $class;
			if ( !class_exists( $namespacedClassName )) throw new TableauException( "Branch rule class $namespacedClassName not found." );
			$this->addBranchRules( new $namespacedClassName );
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
			if ( $closureRule->doesApply( $branch, $this->getLogic() )) $branch->close();
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
	 * Gets the tableau branch rules.
	 *
	 * @return array Array of {@link BranchRule}s.
	 */
	public function getBranchRules()
	{
		return $this->branchRules;
	}
	
	/**
	 * Gets the branch class name.
	 *
	 * @return string Branch class name.
	 */
	public function getBranchClass()
	{
		return $this->branchClass;
	}
	
	/**
	 * Builds a tableau.
	 *
	 * @param Tableau $tableau The tableau to build.
	 * @return void
	 */
	public function buildProof( Proof $tableau )
	{
		$this->buildTrunk( $tableau, $tableau->getArgument(), $this->getLogic() );
		$branchRules = $this->getBranchRules();
		$i = 0;
		do {
			$this->applyClosureRule( $tableau );
			$rule 			= $branchRules[$i];
			$ruleDidApply 	= false;
			foreach ( $tableau->getOpenBranches() as $branch ) 
				if ( $rule->apply( $branch, $this->getLogic() ) !== false ) {
					$ruleDidApply = true;
					$i = 0;
				}
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
	 * @param Logic $logic The logic of the proof system.
	 * @return void
	 */
	abstract public function buildTrunk( Tableau $tableau, Argument $argument, Logic $logic );
}