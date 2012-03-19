<?php

namespace GoTableaux\Logic\CPL\ProofSystem\BranchRule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

class MaterialConditional implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByOperatorName( 'Material Conditional', true ))
			return false;
		$node = $nodes[0];
		
		list( $antecedent, $consequent ) = $node->getSentence()->getOperands();
		
		$branch->branch()
			   ->createNode( $logic->negate( $antecedent ))
			   ->tickNode( $node );
		
		$branch->createNode( $consequent )
			   ->tickNode( $node );
		
		return true;
	}
}