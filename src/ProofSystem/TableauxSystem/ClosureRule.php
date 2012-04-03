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
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program in file LICENSE.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * Defines the ClosureRule interface.
 * @package Tableaux
 */

namespace GoTableaux\ProofSystem\TableauxSystem;

/**
 * Represents a tableau closure rule.
 * @package Tableaux
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