<?php

class GoModal_Rule_Sentence_NegDisjunctionUndes implements Rule
{
	public function apply( Branch $branch )
	{
		if ( ! $branch instanceof GoModalBranch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$negNodes = SentenceNode::findNodesByOperatorName( $branch->getUndesignatedNodes( true ), 'NEGATION' );
		
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
		
		$branch->addNode( new GoModal_Node_Sentence( $operands[0], $node->getI(), true ) );
		
		$node->tick( $branch );
		

		return array( 0 => $branch );
	}
}
?>