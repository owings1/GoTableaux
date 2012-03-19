<?php

namespace GoTableaux\Logic\FDE\ProofSystem\BranchRule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

class MaterialBiconditionalDesignated implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByOperatorNameAndDesignation( 'Material Biconditional', true, true ))
			return false;
		$node = $nodes[0];

		list( $lhs, $rhs ) = $node->getSentence()->getOperands();

		$branch->branch()
			   ->createNodeWithDesigation( $logic->negate( $lhs ), true )
			   ->createNodeWithDesigation( $logic->negate( $rhs ), true )
			   ->tickNode( $node );
			
		$branch->createNodeWithDesignation( $lhs, true )
			   ->createNodeWithDesignation( $rhs, true )
			   ->tickNode( $node );
			
		return true;
	}
}