<?php

namespace GoTableaux\Logic\FDE\ProofSystem\BranchRule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

class NegatedMaterialBiconditionalUndesignated implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByTwoOperatorNamesAndDesignation( 'Negation', 'Material Biconditional', false, true ))
			return false;
		$node = $nodes[0];

		list( $negatum ) = $node->getSentence()->getOperands();
		list( $lhs, $rhs ) = $negatum->getOperands();

		$branch->branch()
			   ->createNodeWithDesigation( $logic->negate( $lhs ), false )
			   ->createNodeWithDesigation( $logic->negate( $rhs ), false )
			   ->tickNode( $node );
			
		$branch->createNodeWithDesignation( $lhs, false )
			   ->createNodeWithDesignation( $rhs, false )
			   ->tickNode( $node );
			
		return true;
	}
}