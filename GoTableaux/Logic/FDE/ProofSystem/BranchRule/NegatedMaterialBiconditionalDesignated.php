<?php

namespace GoTableaux\Logic\FDE\ProofSystem\BranchRule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

class NegatedMaterialBiconditionalDesignated implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByTwoOperatorNamesAndDesignation( 'Negation', 'Material Biconditional', true, true ))
			return false;
		$node = $nodes[0];

		list( $negatum ) = $node->getSentence()->getOperands();
		list( $lhs, $rhs ) = $negatum->getOperands();

		$branch->branch()
			   ->createNodeWithDesigation( $lhs, true )
			   ->createNodeWithDesigation( $logic->negate( $rhs ), true )
			   ->tickNode( $node );
			
		$branch->createNodeWithDesignation( $logic->negate( $lhs ), true )
			   ->createNodeWithDesignation( $rhs, true )
			   ->tickNode( $node );
			
		return true;
	}
}