<?php

class GoModal_Rule_Sentence_NegMatBicondDes extends Tableaux_Rule
{
	public function apply( Tableaux_Branch $branch )
	{
		
		
		if ( ! $branch instanceof GoModal_Branch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$negNodes = GoModal_Branch::getNodesByOperatorName( $branch->getDesignatedNodes( true ), 'NEGATION' );
		
		$negBicondNodes = array();
		
		foreach ( $negNodes as $node ){
			$operands = $node->getSentence()->getOperands();
			$sen = $operands[0];
			if ( $sen instanceof Sentence_Molecular && $sen->getOperator()->getName() == 'MATERIALBICONDITIONAL' ){
				$negBicondNodes[] = $node;
			}
		}
		if ( empty( $negBicondNodes )){
			return false;
		}
		
		$node = $negBicondNodes[0];
		
		// get negation operator
		$vocabulary = GoModal::getVocabulary();
		$operator = $vocabulary->getOperatorByName( 'NEGATION' );
		
		// get operands of negated biconditional
		$operands = $node->getSentence()->getOperands();
		
		// get operands of biconditional
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
		
		// attach negated Lhs and original Rhs to original branch
		$branch->addNode( new GoModal_Node_Sentence( $negLhs, $node->getI(), false ) );
		$branch->addNode( new GoModal_Node_Sentence( $operands[1], $node->getI(), false ) );
		
		// attach original Lhs and negated Rhs to extension
		$branch_b->addNode( new GoModal_Node_Sentence( $operands[0], $node->getI(), false ) );
		$branch_b->addNode( new GoModal_Node_Sentence( $negRhs, $node->getI(), false ) );
		
		$node->tick( $branch );
		$node->tick( $branch_b );
		
		return array( 0 => $branch, $branch_b );
	}
}
?>