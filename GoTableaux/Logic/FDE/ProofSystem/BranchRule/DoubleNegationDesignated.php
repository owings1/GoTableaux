<?php

namespace GoTableaux\Logic\FDE\ProofSystem\BranchRule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

class DoubleNegationDesignated implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByTwoOperatorNamesAndDesignation( 'Negation', 'Negation', true, true ))
			return false;
		$node = $nodes[0];

		list( $negatum ) = $node->getSentence()->getOperands();
		list( $doubleNegatum ) = $negatum->getOperands();

		$branch->createNodeWithDesignation( $doubleNegatum, true )
			   ->tickNode( $node );
			
		return true;
	}
}