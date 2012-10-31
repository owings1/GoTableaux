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

namespace GoTableaux\Logic\CPL\ProofSystem\Rule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Utilities as Utilities;
use \GoTableaux\Logic as Logic;

/**
 * Represents the tableaux closure rule for CPL.
 * @package CPL
 */
class Closure extends \GoTableaux\ProofSystem\TableauxSystem\Rule\Closure
{
	public function appliesToBranch( Branch $branch, Logic $logic )
	{
		$t = microtime( true );
		foreach ( $branch->find( 'all' ) as $node ) {
			$sentence = $logic->negate( $node->getSentence() );
			if ( $branch->find( 'exists', compact( 'sentence' ))) {
				Utilities::debug( 'Felicitous applies search lasted ' . round( microtime( true ) - $t, 2 ) . ' seconds.' );
				return true;
			}
		}
		Utilities::debug( 'Infelicitous applies search lasted ' . round( microtime( true ) - $t, 2 ) . ' seconds for ' . $this->getName() . '.' );
		return false;
	}
	
	/**
	 * Builds an example branch for the rule.
	 *
	 * @param TableauBrach $branch The branch to build.
	 * @param Logic $logic The logic.
	 * @return void
	 */
	public function buildExample( Branch $branch, Logic $logic )
	{
		$sentence = $logic->parseSentence( 'A' );
		$branch->createNode( 'Sentence', compact( 'sentence' ))
			   ->createNode( 'Sentence', array( 'sentence' => $logic->negate( $sentence )));
	}
}