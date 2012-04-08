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
namespace GoTableaux\Logic\CPL\ProofSystem\BranchRule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

class Disjunction implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		$nodes = $branch->getNodesByOperatorName( 'Disjunction', true );
		if ( empty( $nodes )) return false;
		$node = $nodes[0];
		
		list( $leftDisjunct, $rightDisjunct ) = $node->getSentence()->getOperands();
		$branch->branch()
			   ->createNode( $leftDisjunct )
			   ->tickNode( $node );
		$branch->createNode( $rightDisjunct )
		       ->tickNode( $node );
		return true;
	}
}