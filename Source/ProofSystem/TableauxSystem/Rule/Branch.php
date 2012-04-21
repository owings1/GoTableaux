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

namespace GoTableaux\ProofSystem\TableauxSystem\Rule;

use \GoTableaux\Logic as Logic;
use \GoTableaux\Proof\Tableau as Tableau;
use \GoTableaux\Proof\TableauBranch as TableauBranch;

/**
 * Represents a tableau rule that applies to a branch.
 * @package GoTableaux
 */
abstract class Branch implements \GoTableaux\ProofSystem\TableauxSystem\Rule
{
	/**
	 * Determines whether the rule can apply to the tableau.
	 *
	 * A branch rule can apply to a tableau when it can apply to an open branch.
	 *
	 * @param Tableau $tableau The tableau to check.
	 * @return boolean Whether the rule can apply.
	 */
	public function applies( Tableau $tableau )
	{
		foreach( $tableau->getOpenBranches() as $branch )
		 	if ( $this->appliesToBranch( $branch )) return true;	
		return false;
	}

	/**
	 * Applies the rule to a tableau. 
	 * 
	 * A branch rule applies to the first open branch, if any.
	 *
	 * @param Tableau $tableau The tableau to which to apply the rule.
	 * @param Logic $logic The logic.
	 * @return boolean Whether the rule did apply.
	 */
	public function apply( Tableau $tableau )
	{
		foreach ( $tableau->getOpenBranches() as $branch )
			if ( $this->applyToBranch( $branch, $tableau->getProofSystem()->getLogic() )) return true;
		return false;
	}
	
	/**
	 * Determines whether a rule can apply to a branch.
	 *
	 * @param TableauBranch The branch to check.
	 * @param Logic $logic The logic of the proof system.
	 * @return boolean Whether the rule can apply.
	 */
	abstract public function appliesToBranch( TableauBranch $branch, Logic $logic );
	
	/**
	 * Applies the rule to an open branch.
	 *
	 * @param TableauBranch $branch The open branch.
	 * @param Logic $logic The logic of the proof system.
	 * @return void
	 */
	abstract public function applyToBranch( TableauBranch $branch, Logic $logic );
}