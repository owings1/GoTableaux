<?php

namespace GoTableaux\Logic\FDE\ProofSystem\BranchRule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

class NegatedConjunctionDesignated implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByTwoOperatorNamesAndDesignation( 'Negation', 'Conjunction', true, true ))
			return false;
		$node = $nodes[0];
		
		list( $negatum ) = $node->getSentence()->getOperands();
		list( $leftConjunct, $rightConjunct ) = $negatum->getOperands();
		
		$branch->branch()
			   ->createNodeWithDesignation( $logic->negate( $leftConjunct ), true )
			   ->tickNode( $node );
			
		$branch->createNodeWithDesignation( $logic->negate( $rightConjunct ), true )
			   ->tickNode( $node );
		
		return true;
	}
}