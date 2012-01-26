<?php

namespace GoTableaux\Logic\FDE\ProofSystem\BranchRule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

class DisjunctionUndesignated implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByOperatorNameAndDesignation( 'Disjunction', false, true ))
			return false;
		$node = $nodes[0];
		
		list( $leftDisjunct, $rightDisjunct ) = $node->getSentence()->getOperands();
		
		$branch->createNodeWithDesignation( $leftDisjunct, false )
  			   ->createNodeWithDesignation( $rightDisjunct, false )
			   ->tickNode( $node );

		return true;
	}
}