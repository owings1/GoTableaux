<?php
/**
 * Defines the ClosureRule interface.
 * @package Tableaux
 * @author Douglas Owings
 */

namespace GoTableaux\ProofSystem\TableauxSystem;

/**
 * Represents a tableau closure rule.
 * @package Tableaux
 * @author Douglas Owings
 */
interface ClosureRule
{
	/**
	 * Determines whether a branch should be closed, according to the 
	 * implementation of the rule.
	 *
	 * @param Branch $branch The branch to check for applicability.
	 * @param Logic $logic The logic.
	 * @return boolean Whether the closure rule applies, and thus whether the
	 *				   branch should be closed. In the default implementation
	 *				   of Tableau::build(), the closing of the branch
	 *				   occurs when true is returned.
	 * @throws {@link TableauException}
	 */
	public function doesApply( \GoTableaux\Proof\TableauBranch $branch, \GoTableaux\Logic $logic );
}