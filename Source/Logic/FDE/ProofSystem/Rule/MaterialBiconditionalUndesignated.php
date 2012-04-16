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
namespace GoTableaux\Logic\FDE\ProofSystem\Rule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Proof\TableauNode as Node;
use \GoTableaux\Logic as Logic;

/**
 * @package FDE
 */
class MaterialBiconditionalUndesignated extends \GoTableaux\ProofSystem\TableauxSystem\Rule\Node
{
	protected $conditions = array(
		'operator'	 => 'Material Biconditional',
		'designated' => false,
		'ticked'	 => false
	);
	
	public function applyToNode( Node $node, Branch $branch, Logic $logic )
	{
		list( $lhs, $rhs ) = $node->getSentence()->getOperands();

		$branch->branch()
			   ->createNode( 'Sentence\ManyValued', array( 'sentence' => $logic->negate( $lhs ), 'designated' => false ))
			   ->createNode( 'Sentence\ManyValued', array( 'sentence' => $rhs, 'designated' => false ))
			   ->tickNode( $node );
			
		$branch->createNode( 'Sentence\ManyValued', array( 'sentence' => $lhs, 'designated' => false ))
			   ->createNode( 'Sentence\ManyValued', array( 'sentence' => $logic->negate( $rhs ), 'designated' => false ))
			   ->tickNode( $node );
	}
}