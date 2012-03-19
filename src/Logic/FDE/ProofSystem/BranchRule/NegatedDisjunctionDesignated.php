<?php

namespace GoTableaux\Logic\FDE\ProofSystem\BranchRule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

class NegatedDisjunctionDesignated implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByTwoOperatorNamesAndDesignation( 'Negation', 'Disjunction', true, true ))
			return false;
		$node = $nodes[0];
		
		list( $negatum ) = $node->getSentence()->getOperands();
		list( $leftDisjunct, $rightDisjunct ) = $negatum->getOperands();
		
		$branch->createNodeWithDesignation( $logic->negate( $leftDisjunct ), true )
  			   ->createNodeWithDesignation( $logic->negate( $rightDisjunct ), true )
			   ->tickNode( $node );

		return true;
	}
}