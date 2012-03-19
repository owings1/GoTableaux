<?php

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