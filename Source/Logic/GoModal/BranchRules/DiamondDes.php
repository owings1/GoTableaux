<?php

class GoModal_Rule_Sentence_DiamondDes implements Rule
{
	public function apply( Branch $branch )
	{
		
		if ( ! $branch instanceof GoModalBranch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$posNodes = SentenceNode::findNodesByOperatorName( $branch->getDesignatedNodes( true ), 'POSSIBILITY' );
		
		
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