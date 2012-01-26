<?php

namespace GoTableaux\Logic\CPL\ProofSystem\BranchRule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

class MaterialBiconditional implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByOperatorName( 'Material Biconditional', true ))
			return false;
		$node = $nodes[0];
		
		list( $lhs, $rhs ) = $node->getSentence()->getOperands();
		
		$branch->branch()
			   ->createNode( $logic->negate( $lhs ))
			   ->createNode( $logic->negate( $rhs ))
			   ->tickNode( $node );
		
		$branch->createNode( $lhs )
			   ->createNode( $rhs )
			   ->tickNode( $node );
			
		return true;
	}
}