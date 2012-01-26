<?php

namespace GoTableaux\Logic\FDE\ProofSystem\BranchRule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

class ConjunctionUndesignated implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByOperatorNameAndDesignation( 'Conjunction', false, true ))
			return false;
		$node = $nodes[0];
		
		list( $leftConjunct, $rightConjunct ) = $node->getSentence()->getOperands();
		
		$branch->branch()
			   ->createNodeWithDesignation( $leftConjunct, false )
			   ->tickNode( $node );
			
		$branch->createNodeWithDesignation( $rightConjunct, false )
			   ->tickNode( $node );
		
		return true;
	}
}