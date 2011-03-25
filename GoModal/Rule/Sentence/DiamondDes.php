<?php

class GoModal_Rule_Sentence_DiamondDes extends Tableaux_Rule
{
	public function apply( Tableaux_Branch $branch )
	{
		
		if ( ! $branch instanceof GoModal_Branch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$posNodes = GoModal_Branch::getNodesByOperatorName( $branch->getDesignatedNodes( true ), 'POSSIBILITY' );
		
		
		if ( empty( $posNodes )){
			return false;
		}
		
		$node = $posNodes[0];
		
		$i = max( $branch->getIsAndJsOnBranch() ) + 1;
		
		$operands = $node->getSentence()->getOperands();
		
		$branch->addNode( new GoModal_Node_Sentence( $operands[0], $i, true ) );
		$branch->addNode( new GoModal_Node_Access( $node->getI(), $i ) );

		$node->tick( $branch );
		
		return array( 0 => $branch );
	}
}
?>