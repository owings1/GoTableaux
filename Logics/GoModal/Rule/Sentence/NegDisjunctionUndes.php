<?php

class GoModal_Rule_Sentence_NegDisjunctionUndes implements Tableaux_Rule
{
	public function apply( Tableaux_Branch $branch )
	{
		if ( ! $branch instanceof GoModal_Branch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$negNodes = GoModal_Branch::getNodesByOperatorName( $branch->getUndesignatedNodes( true ), 'NEGATION' );
		
		$negDisjNodes = array();
		
		foreach ( $negNodes as $node ){
			$operands = $node->getSentence()->getOperands();
			$sen = $operands[0];
			if ( $sen instanceof Sentence_Molecular && $sen->getOperator()->getName() == 'DISJUNCTION' ){
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