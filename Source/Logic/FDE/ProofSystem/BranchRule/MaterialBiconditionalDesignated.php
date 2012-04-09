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
namespace GoTableaux\Logic\FDE\ProofSystem\BranchRule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

/**
 * @package FDE
 */
class MaterialBiconditionalDesignated implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByOperatorNameAndDesignation( 'Material Biconditional', true, true ))
			return false;
		$node = $nodes[0];

		list( $lhs, $rhs ) = $node->getSentence()->getOperands();

		$branch->branch()
			   ->createNodeWithDesignation( $logic->negate( $lhs ), true )
			   ->createNodeWithDesignation( $logic->negate( $rhs ), true )
			   ->tickNode( $node );
			
		$branch->createNodeWithDesignation( $lhs, true )
			   ->createNodeWithDesignation( $rhs, true )
			   ->tickNode( $node );
			
		return true;
	}
}