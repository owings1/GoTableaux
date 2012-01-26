<?php

namespace GoTableaux\Logic\CPL\ProofSystem\BranchRule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

class DoubleNegation implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByTwoOperatorNames( 'Negation', 'Negation', true ))
			return false;
		$node = $nodes[0];
		
		list( $singleNegatum ) = $node->getSentence()->getOperands();
		list( $doubleNegatum ) = $singleNegatum->getOperands();
		
		$branch->createNode( $doubleNegatum )
			   ->tickNode( $node );
		
		return true;
	}
}