<?php

namespace GoTableaux\Logic\FDE\ProofSystem\BranchRule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

class MaterialBiconditionalUndesignated implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByOperatorNameAndDesignation( 'Material Biconditional', false, true ))
			return false;
		$node = $nodes[0];

		list( $lhs, $rhs ) = $node->getSentence()->getOperands();

		$branch->branch()
			   ->createNodeWithDesigation( $logic->negate( $lhs ), false )
			   ->createNodeWithDesigation( $rhs, false )
			   ->tickNode( $node );
			
		$branch->createNodeWithDesignation( $lhs, false )
			   ->createNodeWithDesignation( $logic->negate( $rhs ), false )
			   ->tickNode( $node );
			
		return true;
	}
}