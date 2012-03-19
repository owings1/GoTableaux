<?php

namespace GoTableaux\Logic\FDE\ProofSystem\BranchRule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

class DisjunctionDesignated implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByOperatorNameAndDesignation( 'Disjunction', true, true ))
			return false;
		$node = $nodes[0];
		
		list( $leftDisjunct, $rightDisjunct ) = $node->getSentence()->getOperands();
		
		$branch->branch()
			   ->createNodeWithDesignation( $leftDisjunct, true )
			   ->tickNode( $node );
			
		$branch->createNodeWithDesignation( $rightDisjunct, true )
			   ->tickNode( $node );
		
		return true;
	}
}