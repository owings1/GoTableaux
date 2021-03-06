<?php

class GoModal_Rule_Sentence_NegBoxUndes implements Rule
{
	public function apply( Branch $branch )
	{
		
		if ( ! $branch instanceof GoModalBranch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$negNodes = SentenceNode::findNodesByOperatorName( $branch->getUndesignatedNodes( true ), 'NEGATION' );
		
		$negBoxNodes = array();
		
		foreach ( $negNodes as $node ){
			$operands = $node->getSentence()->getOperands();
			$sen = $operands[0];
			if ( $sen instanceof MolecularSentence && $sen->getOperator()->getName() == 'NECESSITY' ){
				$negBoxNodes[] = $node;
			}
		}
		if ( empty( $negBoxNodes )){
			return false;
		}
		
		$node = $negBoxNodes[0];
		
		// get box sentence
		$operands = $node->getSentence()->getOperands();
		$operand = $operands[0];
		
		
		$branch->addNode( new GoModal_Node_Sentence( $operand, $node->getI(), true ) );
		
		$node->tick( $branch );
		
		return array( 0 => $branch );
	}
}
?>