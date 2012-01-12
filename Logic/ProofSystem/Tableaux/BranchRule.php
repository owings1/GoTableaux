<?php
/**
 * Defines the BranchRule interface.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link RuleException} class.
 */
require_once 'GoTableaux/Logic/Exceptions/RuleException.php';

/**
 * Represents a tableau rule that applies to a branch.
 * @package Tableaux
 * @author Douglas Owings
 */
interface BranchRule
{
	/**
	 * Applies the rule to a branch. 
	 * 
	 * The implementation should first check to determine whether the rule 
	 * applies to the branch. If so, nodes should be added and ticked 
	 * accordingly. If the rule is a branching rule, a new branch (or branches)
	 * should be created as a copy of the original branch and returned. Since 
	 * $branch is a reference, any alterations on it will be reflected on the 
	 * original tableau.
	 *
	 * @param Branch $branch The branch to which to apply the rule.
	 * @param Logic $logic The logic.
	 * @return Branch|array|boolean Resulting branch, or array of branches to 
	 * 		attach to the tree, or whether the rule applied to the branch. The 
	 *		original branch need not (but can) be returned, as it will by 
	 *		default remain on the tree.
	 * @throws {@link RuleException} on any errors.
	 */
	public function apply( Branch $branch, Logic $logic );
}