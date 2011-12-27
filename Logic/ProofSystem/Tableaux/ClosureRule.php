<?php
/**
 * Defines the ClosureRule interface.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link TableauException} class.
 */
require_once 'TableauException.php';

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
	 * @param TableauxSystem $tableauxSystem The Tableaux system.
	 * @return boolean Whether the closure rule applies, and thus whether the
	 *				   branch should be closed. In the default implementation
	 *				   of Tableau::build(), the closing of the branch
	 *				   occurs when true is returned.
	 * @throws {@link TableauException}
	 */
	public function doesApply( Branch $branch, TableauxSystem $tableauxSystem );
}