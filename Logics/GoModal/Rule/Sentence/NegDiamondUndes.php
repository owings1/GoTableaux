<?php

class GoModal_Rule_Sentence_NegDiamondUndes implements Rule
{
	public function apply( Branch $branch )
	{
		
		if ( ! $branch instanceof GoModalBranch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$negNodes = GoModalBranch::getNodesByOperatorName( $branch->getUndesignatedNodes( true ), 'NEGATION' );
		
		$negDiamNodes = array();
		
		foreach ( $negNodes as $node ){
			$operands = $node->getSentence()->getOperands();
			$sen = $operands[0];
			if ( $sen instanceof MolecularSentence && $sen->getOperator()->getName() == 'POSSIBILITY' ){
				$negDiamNodes[] = $node;
			}
		}
		if ( empty( $negDiamNodes )){
			return false;
		}
		
		$node = $negDiamNodes[0];
		
		// get diamond sentence
		$operands = $node->getSentence()->getOperands();
		$operand = $operands[0];
		
		
		$branch->addNode( new GoModal_Node_Sentence( $operand, $node->getI(), true ) );
		
		$node->tick( $branch );
		
		return array( 0 => $branch );
	}
}
?>