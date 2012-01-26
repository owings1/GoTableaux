<?php

namespace GoTableaux\Logic\FDE\ProofSystem\BranchRule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

class MaterialConditionalUndesignated implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByOperatorNameAndDesignation( 'Material Conditional', false, true ))
			return false;
		$node = $nodes[0];

		list( $antecedent, $consequent ) = $node->getSentence()->getOperands();
		
		$branch->createNodeWithDesignation( $logic->negate( $antecedent ), false )
  			   ->createNodeWithDesignation( $consequent, false )
			   ->tickNode( $node );
			
		return true;
	}
}