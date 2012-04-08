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
 * Defines the ReflexiveModalRule class.
 * @package GoTableaux
 */

namespace GoTableaux\ProofSystem\TableauxSystem\BranchRule;

/**
 * Implements the reflexivity rule for a standard modal tableaux system.
 * @package GoTableaux
 */
class ModalReflexive implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	/**
	 * Implements the Reflexive Rule.
	 *
	 * A node is reflexive exactly when both indexes are equal. This rule 
	 * searches for any indexes on the branch for which there is not currently 
	 * a reflexive node. If one is found, it adds a reflexive node with the
	 * class {@link AccessNode}. If more than one are found, it adds
	 * a node for least index.
	 *
	 * @param ModalBranch $branch The modal branch to search and apply the rule to.
	 * @param Logic $logic The Tableaux system.
	 * @return boolean Whether the rule was applied.
	 */
	public function apply( \GoTableaux\Proof\TableauBranch $branch, \GoTableaux\Logic $logic )
	{
		if ( !$indexes = array_diff( $branch->getAllIndexes(), $branch->getReflexiveNodes() ))
			return false;
		$index = min( $indexes );
		$branch->createAccessNode( $index, $index );
		return true;
	}
}