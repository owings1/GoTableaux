<?php
/**
 * Defines the Tableau class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link TableauException} class.
 */
require_once 'TableauException.php';

/**
 * Loads the {@link Structure} class.
 * @see Tableau::buildStructure()
 */
require_once 'Structure.php';

/**
 * Represents a tableau for an argument.
 *
 * @package Tableaux
 * @author Douglas Owings
 */
class Tableau
{
	/**
	 * Holds the argument for the tree.
	 * @var Argument
	 * @access private
	 */
	protected $argument;
	
	/**
	 * Holds the branches on the tree.
	 * @var array Array of {@link Branch} objects.
	 * @access private
	 */
	protected $branches = array();
	
	/**
	 * Holds the initial rule that sets up the tree with the argument.
	 * @var InitialRule
	 * @access private
	 */
	protected $initialRule;
	
	/**
	 * Holds the closure rule to apply after applying each rule.
	 * @var ClosureRule
	 * @access private
	 */
	protected $closureRule;
	
	/**
	 * Tableaux rules.
	 * @var array Array of {@link Rule} objects.
	 * @access private
	 */
	protected $rules = array();
	
	/**
	 * Holds the tree structure.
	 * @var Structure
	 * @access private
	 */	
	protected $structure;
	
	/**
	 * Constructor. Initializes argument.
	 *
	 * @param Argument $argument Argument for the tree.
	 */
	public function __construct( Argument $argument )
	{
		$this->argument = $argument;
	}
	
	/**
	 * Gets the Argument object.
	 *
	 * @return Argument The argument.
	 */
	function getArgument()
	{
		return $this->argument;
	}
	
	/**
	 * Sets the initial rule. This rule sets up the tree from an argument.
	 *
	 * @param InitialRule $rule The initial rule.
	 * @return Tableau Current instance, for chaining.
	 */
	function setInitialRule( InitialRule $rule )
	{
		$this->initialRule = $rule;
		return $this;
	}
	
	/**
	 * Sets the closure rule. This rule is applied after each application of
	 * a distinct rule, including the initial rule.
	 *
	 * @param ClosureRule $rule The closure rule.
	 * @return Tableau Current instance, for chaining.
	 */
	function setClosureRule( ClosureRule $rule )
	{
		$this->closureRule = $rule;
		return $this;
	}
	
	/**
	 * Adds a rule to the rule set. If the rule is already in the rule set, it
	 * is ignored.
	 *
	 * @param Rule $rule The rule to add.
	 * @return Tableau Current instance, for chaining.
	 */
	function addRule( Rule $rule )
	{
		if ( !in_array( $rule, $this->rules, true ))
			$this->rules[] = $rule;
		return $this;
	}
	
	/**
	 * Attaches one or more branches to the tree.
	 *
	 * @param Branch|array The branch instance to add, or an array of
	 *								branches.
	 * @return Tableau Current instance, for chaining.
	 */
	public function attach( $branch )
	{
		if ( is_array( $branch ))
			foreach ( $branch as $b ) $this->_attach( $b );
		else
			$this->_attach( $branch );
		return $this;
	}
	
	/**
	 * Removes one or more branches from the tree.
	 *
	 * @param Branch|array The branch instance to remove, or an array
	 *								of branches.
	 * @return Tableau Current instance, for chaining.
	 */
	public function detach( $branch )
	{
		if ( is_array( $branch ))
			foreach ( $branch as $b ) $this->_detach( $b );
		else
			$this->_detach( $branch );
		return $this;
	}
	
	/**
	 * Gets all open branches on the tree.
	 *
	 * @return array Array of {@link Branch} objects.
	 */
	public function getOpenBranches()
	{
		$branches = array();
		foreach ( $this->branches as $branch )
			if ( !$branch->isClosed() ) $branches[] = $branch;
		return $branches;
	}
	
	/**
	 * Gets all branches on the tree.
	 *
	 * @return array Array of {@link Branch} objects.
	 */
	public function getBranches()
	{
		return $this->branches;
	}
	
	/**
	 * Gets the tableau's tree structure representation.
	 *
	 * @return Structure The tree structure.
	 */
	public function getStructure()
	{
		return $this->structure;
	}
	
	/**
	 * Builds the tree. Applies the rules until either all branches on the tree
	 * are closed, or no rule any longer applies to any branch.
	 *
	 * @return Tableau Current instance, for chaining.
	 * @throws {@link TableauException} on empty rule set.
	 */
	public function build()
	{
		$this->constructInitialList();
		
		if ( empty( $this->rules ))
			throw new TableauException( 'Rule set cannot be empty.' );
		
		$i = 0;
		do {
			$this->applyClosureRule( $this->getOpenBranches() );
			$rule 			= $this->rules[$i];
			$ruleDidApply 	= false;
			foreach ( $this->getOpenBranches() as $branch ){
				if ( $this->applyOnceAndExtend( $rule, $branch )) {
					$i 				= 0;
					$ruleDidApply 	= true;
				}
			}
		} while ( $ruleDidApply || isset( $this->rules[++$i] ));
		
		$this->applyClosureRule( $this->getOpenBranches() );
		
		$this->buildStructure();
		
		return $this;
	}
	
	/**
	 * Checks whether the argument is valid. Here, an argument is considered 
	 * valid exactly when all branches on the tree are closed.
	 *
	 * @param Branch|null &$openBranch Stores the first open branch
	 *											or null if the argument is 
	 *											valid. This is helpful for 
	 *											constructing a counterexample.
	 * @return boolean True if the argument is valid, false if it is invalid.
	 */
	public function isValid( &$openBranch = null )
	{
		$openBranches = $this->getOpenBranches();
		if ( empty( $openBranches )){
			$openBranch = null;
			return true;
		}
		$openBranch = $openBranches[0];
		return false;
	}
	
	/**
	 * Copies the tree and all its branches.
	 *
	 * @return Tableau The cloned tree.
	 */
	public function copy()
	{
		$copy = clone $this;
		$copy->clearAllBranches();
		foreach ( $this->branches as $branch )
			$copy->attach( $branch->copy() );
		return $copy;
	}
	
	/**
	 * Attaches a single branch to the tree. If the branch is already on the
	 * tree, it is ignored.
	 *
	 * @param Branch $branch The branch instance to attach.
	 * @return void
	 */
	protected function _attach( Branch $branch )
	{
		if ( !in_array( $branch, $this->branches, true ))
			$this->branches[] = $branch;
	}
	
	/**
	 * Removes a single branch from the tree. If the branch is not on the tree,
	 * it is ignored.
	 *
	 * @param Branch $branch The branch instance to remove.
	 * @return void
	 */
	protected function _detach( Branch $branch )
	{
		$key = array_search( $branch, $this->branches, true );
		if ( $key !== false ) unset( $this->branches[$key] );
	}
	
	/**
	 * Applies the closure rule to a branch or array of branches, and closes 
	 * the branch if the rule does apply.
	 * 
	 * @param Branch|array $branch The branch or array of branches to 
	 *										which to apply the closure rule.
	 * @return boolean Wether the closure rule applied, and thus whether the 
	 *				   branch, or at least one branch in the array was closed.
	 */
	protected function applyClosureRule( $branch )
	{
		if ( is_array( $branch )) {
			$didApply = false;
			foreach ( $branch as $b )
				$didApply |= $this->_applyClosureRule( $b );
			return $didApply;
		}
		return $this->_applyClosureRule( $b );
	}

	/**
	 * Applies the closure rule to a single branch, and closes if the rule applies.
	 *
	 * @param Branch $branch The branch to which to apply the rule.
	 * @return boolean Wether the closure rule applied, and thus closed the branch.
	 * @throws {@link TableauException} on empty closure rule.
	 */
	protected function _applyClosureRule( Branch $branch )
	{
		if ( empty( $this->closureRule ))
			throw new TableauException( 'No closure rule set for tableau.' );
		
		if ( $didApply = $this->closureRule->doesApply( $branch ))
			$branch->close();
		
		return $didApply;
	}
	
	/**
	 * Applies a rule to a branch, and attaches the returned branches.
	 *
	 * @param Rule $rule The rule to apply.
	 * @param Branch $branch The branch to which to apply the rule.
	 * @return boolean Array Whether the rule applied.
	 * @throws {@link TableauException} when a rule returns empty.
	 */
	protected function applyOnceAndExtend( Rule $rule, Branch $branch )
	{
		if ( $branch->isClosed() ) return false;
	
		$result = $rule->apply( $branch );
		
		if ( $result === false ) return false;
		
		if ( empty( $result ))
			throw new TableauException( 'Rule ' . get_class( $rule ) . ' returned an empty set.' );
		
		$this->attach( $result );
		
		return true;
	}
	
	/**
	 * Constructs the initial list of the tree.
	 *
	 * @return void
	 * @throws {@link TableauException} when no initial rule is set,
	 *		   or the initial rule returns an empty set.
	 */
	protected function constructInitialList()
	{
		if ( empty( $this->initialRule ))
			throw new TableauException( 'No initial rule set.' );
	
		$branches = $this->initialRule->apply( $this->argument );
		
		if ( empty( $branches ))
			throw new TableauException( 'Initial rule returned empty set.' );

		$this->attach( $branches );
	}
	
	/**
	 * Builds the tree structure.
	 *
	 * @return void
	 * @uses Structure
	 */	
	protected function buildStructure()
	{
		$copy = $this->copy();
		$this->structure = Structure::getInstance( $copy );
		$this->structure->build();
	}
	
	/**
	 * Clears all branches from the tree.
	 *
	 * @return void
	 */
	protected function clearAllBranches()
	{
		$this->branches = array();
	}
}