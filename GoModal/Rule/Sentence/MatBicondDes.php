<?php

class GoModal_Rule_Sentence_MatBicondDes extends Tableaux_Rule
{
	public function apply( Tableaux_Branch $branch )
	{
		
		
		if ( ! $branch instanceof GoModal_Branch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$biCondNodes = GoModal_Branch::getNodesByOperatorName( $branch->getDesignatedNodes( true ), 'MATERIALBICONDITIONAL' );
		
		if ( empty( $biCondNodes )){
			return false;
		}
		
		$node = $biCondNodes[0];
		
		// get negation operator
		$vocabulary = GoModal::getVocabulary();
		$operator = $vocabulary->getOperatorByName( 'NEGATION' );
		
		// get operands of material biconditional
		$operands = $node->getSentence()->getOperands();
		
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
		
		// attach negated Lhs and Rhs to original branch
		$branch->addNode( new GoModal_Node_Sentence( $negLhs, $node->getI(), true ) );
		$branch->addNode( new GoModal_Node_Sentence( $negRhs, $node->getI(), true ) );
		
		// attach Lhs and Rhs to extension
		$branch_b->addNode( new GoModal_Node_Sentence( $operands[0], $node->getI(), true ) );
		$branch_b->addNode( new GoModal_Node_Sentence( $operands[1], $node->getI(), true ) );
		
		$node->tick( $branch );
		$node->tick( $branch_b );
		
		return array( 0 => $branch, $branch_b );
	}
}
?>