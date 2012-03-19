<?php

namespace GoTableaux\Logic\FDE\ProofSystem\BranchRule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

class NegatedDisjunctionUndesignated implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByTwoOperatorNamesAndDesignation( 'Negation', 'Disjunction', false, true ))
			return false;
		$node = $nodes[0];
		
		list( $negatum ) = $node->getSentence()->getOperands();
		list( $leftDisjunct, $rightDisjunct ) = $negatum->getOperands();
		
		$branch->branch()
			   ->createNodeWithDesignation( $logic->negate( $leftDisjunct ), false )
			   ->tickNode( $node );
			
  		$branch->createNodeWithDesignation( $logic->negate( $rightDisjunct ), false )
			   ->tickNode( $node );

		return true;
	}
}