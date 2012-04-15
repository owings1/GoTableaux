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
 * Defines the Closure rule class for CPL.
 * @package CPL
 */

namespace GoTableaux\Logic\CPL\ProofSystem;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

/**
 * Represents the tableaux closure rule for CPL.
 * @package CPL
 */
class ClosureRule implements \GoTableaux\ProofSystem\TableauxSystem\ClosureRule
{
	public function doesApply( Branch $branch, Logic $logic )
	{
		foreach ( $branch->find( 'all' ) as $node ) {
			$negated = $logic->negate( $node->getSentence() );
			if ( $branch->find( 'exists', array( 'sentence' => $negated ))) return true;
		}
		return false;
	}
}