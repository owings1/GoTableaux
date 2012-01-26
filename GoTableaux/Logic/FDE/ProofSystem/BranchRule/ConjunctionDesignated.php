<?php

namespace GoTableaux\Logic\FDE\ProofSystem\BranchRule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

class ConjunctionDesignated implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByOperatorNameAndDesignation( 'Conjunction', true, true ))
			return false;
		$node = $nodes[0];
		
		list( $leftConjunct, $rightConjunct ) = $node->getSentence()->getOperands();
		
		$branch->createNodeWithDesignation( $leftConjunct, true )
			   ->createNodeWithDesignation( $rightConjunct, true )
			   ->tickNode( $node );
		
		return true;
	}
}