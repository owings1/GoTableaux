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
 * Defines the LP Closure Rule class.
 * @package LP
 */

namespace GoTableaux\Logic\LP\ProofSystem;

use \GoTableaux\Logic as Logic;
use \GoTableaux\Proof\TableauBranch as Branch;

/**
 * Represents the LP closure rule.
 * @package LP
 */
class ClosureRule extends \GoTableaux\Logic\FDE\ProofSystem\ClosureRule
{
	public function doesApply( Branch $branch, Logic $logic )
	{
		foreach ( $branch->getUndesignatedNodes() as $node ) {
			$negated = $logic->negate( $node->getSentence() );
			if ( $branch->hasSentenceWithDesignation( $negated, false )) return true;
		}
		return parent::doesApply( $branch, $logic );
	}
}