<?php

class GoModal_Rule_Sentence_NegDisjunctionDes implements Rule
{
	public function apply( Branch $branch )
	{
		if ( ! $branch instanceof GoModalBranch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$negNodes = GoModalBranch::getNodesByOperatorName( $branch->getDesignatedNodes( true ), 'NEGATION' );
		
		$negDisjNodes = array();
		
		foreach ( $negNodes as $node ){
			$operands = $node->getSentence()->getOperands();
			$sen = $operands[0];
			if ( $sen instanceof MolecularSentence && $sen->getOperator()->getName() == 'DISJUNCTION' ){
				$negDisjNodes[] = $node;
			}
		}
		if ( empty( $negDisjNodes )){
			return false;
		}
		
		$node = $negDisjNodes[0];
		
		$operands = $node->getSentence()->getOperands();
		$operand = $operands[0];
		$operands = $operand->getOperands();
		
		$branch->addNode( new GoModal_Node_Sentence( $operands[0], $node->getI(), false ) );
		$branch->addNode( new GoModal_Node_Sentence( $operands[1], $node->getI(), false ) );
		
		$node->tick( $branch );
		

		return array( 0 => $branch );
	}
}
?>