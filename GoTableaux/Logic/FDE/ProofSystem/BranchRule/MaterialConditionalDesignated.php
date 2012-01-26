<?php

namespace GoTableaux\Logic\FDE\ProofSystem\BranchRule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

class MaterialConditionalDesignated implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByOperatorNameAndDesignation( 'Material Conditional', true, true ))
			return false;
		$node = $nodes[0];

		list( $antecedent, $consequent ) = $node->getSentence()->getOperands();
		
		$branch->branch()
			   ->createNodeWithDesignation( $logic->negate( $antecedent ), true )
			   ->tickNode( $node );
			
		$branch->createNodeWithDesignation( $consequent, true )
			   ->tickNode( $node );
		
		return true;
	}
}