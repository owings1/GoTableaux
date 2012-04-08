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
 * Defines the BranchRule interface.
 * @package GoTableaux
 */

namespace GoTableaux\ProofSystem\TableauxSystem;

/**
 * Represents a tableau rule that applies to a branch.
 * @package GoTableaux
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
	 * @return boolean Whether the rule did apply.
	 * @throws {@link RuleException} on any errors.
	 */
	public function apply( \GoTableaux\Proof\TableauBranch $branch, \GoTableaux\Logic $logic );
}