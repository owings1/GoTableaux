<?php
/**
 * GoTableaux. A multi-logic tableaux generator.
 * Copyright (C) 2012  Douglas Owings
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed WITHOUT ANY WARRANTY. 
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/agpl-3.0.html>.
 */
/**
 * Defines the ClosureRule interface.
 * @package GoTableaux
 */

namespace GoTableaux\ProofSystem\TableauxSystem;

/**
 * Represents a tableau closure rule.
 * @package GoTableaux
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