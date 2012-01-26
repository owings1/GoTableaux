<?php

namespace GoTableaux\Logic\CPL\ProofSystem\BranchRule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

class Conjunction implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		$nodes = $branch->getNodesByOperatorName( 'Conjunction', true );
		if ( empty( $nodes )) return false;
		$node = $nodes[0];
		
		list( $leftConjunct, $rightConjunct ) = $node->getSentence()->getOperands();
		$branch->createNode( $leftConjunct )
			   ->createNode( $rightConjunct )
			   ->tickNode( $node );
		return true;
	}
}