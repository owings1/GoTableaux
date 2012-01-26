<?php

namespace GoTableaux\Logic\CPL\ProofSystem\BranchRule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

class NegatedMaterialBiconditional implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByTwoOperatorNames( 'Negation', 'Material Biconditional', true ))
			return false;
		$node = $nodes[0];
		
		list( $negatum ) = $node->getSentence()->getOperands();
		list( $lhs, $rhs ) = $negatum->getOperands();
		
		$branch->branch()
			   ->createNode( $logic->negate( $lhs ))
			   ->createNode( $rhs )
			   ->tickNode( $node );
		
		$branch->createNode( $lhs )
			   ->createNode( $logic->negate( $rhs ))
			   ->tickNode( $node );
			
		return true;
	}
}