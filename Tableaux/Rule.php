<?php
/**
 * Defines the Rule interface.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the RuleException class.
 */
require_once 'RuleException.php';

/**
 * Represents a tableau rule.
 * @package Tableaux
 * @author Douglas Owings
 */
interface Rule
{
	/**
	 * Applies the rule to a branch.
	 *
	 * @param Branch $branch The branch to which to apply the rule.
	 * @return array|boolean Array of resulting branches to attach to the tree,
	 *						 or false if the rule did not apply to the branch.
	 *						 The array of branches may optionally contain the
	 *						 original branch that was passed. Since a tableau's
	 *						 branches are treated like a set, any branch that
	 *						 is already on the tree will be ignored. However,
	 *						 an empty array _cannot_ be returned, or an 
	 *						 exception will be thrown while
	 *					{@link Tableau::applyOnceAndExtend() building}
	 *						 the tree.
	 * @throws {@link RuleException}
	 */
	public function apply( Branch $branch );
}