<?php

class GoModal_Rule_Sentence_NegConjunctionDes extends Tableaux_Rule
{
	public function apply( Tableaux_Branch $branch )
	{
		if ( ! $branch instanceof GoModal_Branch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$negNodes = GoModal_Branch::getNodesByOperatorName( $branch->getDesignatedNodes( true ), 'NEGATION' );
		
		$negConjNodes = array();
		
		foreach ( $negNodes as $node ){
			$operands = $node->getSentence()->getOperands();
			$sen = $operands[0];
			if ( $sen instanceof Sentence_Molecular && $sen->getOperator()->getName() == 'CONJUNCTION' ){
				$negConjNodes[] = $node;
			}
		}
		if ( empty( $negConjNodes )){
			return false;
		}
		
		$node = $negConjNodes[0];
		
		$operands = $node->getSentence()->getOperands();
		$operand = $operands[0];
		$operands = $operand->getOperands();
		
		$branch_a = $branch;
		$branch_b = $branch->copy();
		
		$branch_a->addNode( new GoModal_Node_Sentence( $operands[0], $node->getI(), false ) );
		$branch_b->addNode( new GoModal_Node_Sentence( $operands[1], $node->getI(), false ) );
		
		$node->tick( $branch_a );
		$node->tick( $branch_b );
		

		return array( 0 => $branch_a, $branch_b );
	}
}
?>