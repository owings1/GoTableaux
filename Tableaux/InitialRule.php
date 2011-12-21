<?php
/**
 * Defines the InitialRule interface.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link TableauException} class.
 */
require_once 'TableauException.php';

/**
 * Represents an initial rule for a tableau. The initial rule constructs the
 * initial branch(es) (the trunk) for an argument.
 * @package Tableaux
 * @author Douglas Owings
 */
interface InitialRule
{
	/**
	 * Produces a non-empty set of branches that result from applying the
	 * initial rule to an argument.
	 *
	 * @param Argument $argument The argument for which the tableau is being
	 *							 constructed.
	 * @return array A non-empty array of {@link Branch} objects.
	 * @throws {@link TableaException}
	 */
	public function apply( Argument $argument );
}