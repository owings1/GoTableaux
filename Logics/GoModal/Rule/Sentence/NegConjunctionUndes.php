<?php

class GoModal_Rule_Sentence_NegConjunctionUndes implements Rule
{
	public function apply( Branch $branch )
	{
		if ( ! $branch instanceof GoModalBranch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$negNodes = GoModalBranch::getNodesByOperatorName( $branch->getUndesignatedNodes( true ), 'NEGATION' );
		
		$negConjNodes = array();
		
		foreach ( $negNodes as $node ){
			$operands = $node->getSentence()->getOperands();
			$sen = $operands[0];
			if ( $sen instanceof MolecularSentence && $sen->getOperator()->getName() == 'CONJUNCTION' ){
				$negConjNodes[] = $node;
			}
		}
		if ( empty( $negConjNodes )){
			return false;
		}
		
		$node = $negConjNodes[0];
		
		$operands = $node->getSentence()->getOperands();
		
		$branch->addNode( new GoModal_Node_Sentence( $operands[0], $node->getI(), true ) );
		
		$node->tick( $branch );
		

		return array( 0 => $branch );
	}
}
?>