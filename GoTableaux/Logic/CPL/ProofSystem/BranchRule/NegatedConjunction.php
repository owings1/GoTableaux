<?php

namespace GoTableaux\Logic\CPL\ProofSystem\BranchRule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

class NegatedConjunction implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		$nodes = $branch->getNodesByTwoOperatorNames( 'Negation', 'Conjunction', true );
		if ( empty( $nodes )) return false;
		$node = $nodes[0];
		
		list( $negatum ) = $node->getSentence()->getOperands();
		list( $leftConjunct, $rightConjunct ) = $negatum->getOperands();
		$branch->branch()
			   ->createNode( $logic->negate( $leftConjunct ))
			   ->tickNode( $node );
		$branch->createNode( $logic->negate( $rightConjunct ))
			   ->tickNode( $node );
		return true;
	}
}