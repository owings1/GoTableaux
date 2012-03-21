<?php

namespace GoTableaux\Logic\GO\ProofSystem\BranchRule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

class ConjunctionUndesignated implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByOperatorNameAndDesignation( 'Conjunction', false, true ))
			return false;
		$node = $nodes[0];
		
		$negated = $logic->negate( $node->getSentence() );
		
		$branch->createNodeWithDesignation( $negated, true )
			   ->tickNode( $node );
		
		return true;
	}
}