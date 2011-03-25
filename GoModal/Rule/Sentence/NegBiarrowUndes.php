<?php

class GoModal_Rule_Sentence_NegBiarrowUndes extends Tableaux_Rule
{
	public function apply( Tableaux_Branch $branch )
	{
		
		
		if ( ! $branch instanceof GoModal_Branch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$negNodes = GoModal_Branch::getNodesByOperatorName( $branch->getUndesignatedNodes( true ), 'NEGATION' );
		
		$negCondNodes = array();
		
		foreach ( $negNodes as $node ){
			$operands = $node->getSentence()->getOperands();
			$sen = $operands[0];
			if ( $sen instanceof Sentence_Molecular && $sen->getOperator()->getName() == 'BIARROW' ){
				$negCondNodes[] = $node;
			}
		}
		if ( empty( $negCondNodes )){
			return false;
		}
		
		$node = $negCondNodes[0];
		
		// get singleton operands of negated sentence
		$operands = $node->getSentence()->getOperands();
		
		// get biarrow sentence
		$operand = $operands[0];
			
		// attach biarrow sentence to original branch
		$branch->addNode( new GoModal_Node_Sentence( $sentence, $node->getI(), true ) );
		
		$node->tick( $branch );

		return array( 0 => $branch );
	}
}
?>