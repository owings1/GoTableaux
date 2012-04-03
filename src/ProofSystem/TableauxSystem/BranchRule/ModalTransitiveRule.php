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
 * Defines the ModalTransitive Rule class.
 * @package Tableaux
 */

namespace GoTableaux\ProofSystem\TableauxSystem\BranchRule;

/**
 * Implements the transitivity rule for a modal tableaux system.
 * @package Tableaux
 */
class ModalTransitive implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	/**
	 * Implements the Transitive Rule.
	 *
	 * A world index is transitive exactly when it access all worlds that are
	 * accessed by all the worlds it accesses. The first non-transitive index
	 * found is remedied.
	 *
	 * @param ModalBranch $branch The modal branch to search and apply the rule to.
	 * @param Logic $logic The Tableaux system.
	 * @return boolean Whether the rule was applied.
	 */
	public function apply( \GoTableaux\Proof\TableauBranch $branch, \GoTableaux\Logic $logic )
	{
		foreach ( $branch->getAllIndexes() as $index ) 
			if ( !$branch->indexIsTransitive( $index, $newIndex )) {
				$branch->createAccessNode( $index, $newIndex );
				return true;
			}
		return false;
	}
}