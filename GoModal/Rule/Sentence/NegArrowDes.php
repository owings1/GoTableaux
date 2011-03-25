<?php

class GoModal_Rule_Sentence_NegArrowDes extends Tableaux_Rule
{
	public function apply( Tableaux_Branch $branch )
	{
		
		
		if ( ! $branch instanceof GoModal_Branch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$negNodes = GoModal_Branch::getNodesByOperatorName( $branch->getDesignatedNodes( true ), 'NEGATION' );
		
		$negArrowNodes = array();
		
		foreach ( $negNodes as $node ){
			$operands = $node->getSentence()->getOperands();
			$sen = $operands[0];
			if ( $sen instanceof Sentence_Molecular && $sen->getOperator()->getName() == 'ARROW' ){
				$negArrowNodes[] = $node;
			}
		}
		if ( empty( $negArrowNodes )){
			return false;
		}
		
		$node = $negArrowNodes[0];
		
		// get negation operator
		$vocabulary = GoModal::getVocabulary();
		$operator = $vocabulary->getOperatorByName( 'NEGATION' );
		
		// get operands of negated arrow
		$operands = $node->getSentence()->getOperands();
		
		// get operands of arrow
		$operand = $operands[0];
		$operands = $operand->getOperands();
		
		// create new sentence
		$newSentence = new Sentence_Molecular();
		
		// set operator to negation
		$newSentence->setOperator( $operator );
		
		// set operand to lhs
		$newSentence->addOperand( $operands[0] );
		
		// get instance
		$negLhs = $vocabulary->oldOrNew( $newSentence );
		
		// create new sentence
		$newSentence = new Sentence_Molecular();
		
		// set operator to negation
		$newSentence->setOperator( $operator );
		
		// set operand to rhs
		$newSentence->addOperand( $operands[1] );
		
		// get instance
		$negRhs = $vocabulary->oldOrNew( $newSentence );
		
		// make extension
		$branch_b = $branch->copy();
		
		// attach Lhs +, and Rhs - to original branch
		$branch->addNode( new GoModal_Node_Sentence( $operands[0], $node->getI(), true ) );
		$branch->addNode( new GoModal_Node_Sentence( $operands[1], $node->getI(), false ) );
		
		// attach negRhs +, and negLhs - to extension
		$branch_b->addNode( new GoModal_Node_Sentence( $negRhs, $node->getI(), true ) );
		$branch_b->addNode( new GoModal_Node_Sentence( $negLhs, $node->getI(), false ) );
		
		$node->tick( $branch );
		$node->tick( $branch_b );
		
		return array( 0 => $branch, $branch_b );
	}
}
?>