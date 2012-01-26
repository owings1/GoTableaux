<?php

namespace GoTableaux\Logic\FDE\ProofSystem\BranchRule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

class NegatedMaterialConditionalDesignated implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByTwoOperatorNamesAndDesignation( 'Negation', 'Material Conditional', true, true ))
			return false;
		$node = $nodes[0];

		list( $negatum ) = $node->getSentence()->getOperands();
		list( $antecedent, $consequent ) = $negatum->getOperands();
		
		$branch->createNodeWithDesignation( $antecedent, true )
  			   ->createNodeWithDesignation( $logic->negate( $consequent ), true )
			   ->tickNode( $node );

		return true;
	}
}