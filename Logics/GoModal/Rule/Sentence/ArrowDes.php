<?php

class GoModal_Rule_Sentence_ArrowDes implements Rule
{
	public function apply( Branch $branch )
	{
		
		
		if ( ! $branch instanceof GoModalBranch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$arrowNodes = GoModalBranch::getNodesByOperatorName( $branch->getDesignatedNodes( true ), 'ARROW' );
		
		if ( empty( $arrowNodes ))
			return false;
		
		
		$node = $arrowNodes[0];
		
		// get negation operator
		$vocabulary = GoModal::getVocabulary();
		$negation = $vocabulary->getOperatorByName( 'NEGATION' );
		
		// get disjunction operator
		$disjunction = $vocabulary->getOperatorByName( 'DISJUNCTION' );
		
		// get operands of arrow
		$operands = $node->getSentence()->getOperands();
		
		// create new sentence
		$newSentence = new MolecularSentence();
		
		// set operator to negation
		$newSentence->setOperator( $negation );
		
		// set operand to lhs
		$newSentence->addOperand( $operands[0] );
		
		// get instance
		$negLhs = $vocabulary->oldOrNew( $newSentence );
		
		// create new sentence
		$newSentence = new MolecularSentence();
		
		// set operator to disjunction
		$newSentence->setOperator( $disjunction );
		
		// set operands to negLhs and Rhs
		$newSentence->addOperand( $negLhs );
		$newSentence->addOperand( $operands[1] );
		
		// getInstance
		$disj = $vocabulary->oldOrNew( $newSentence );
		
		// create new sentence
		$newSentence = new MolecularSentence();
		
		// set operator to negation
		$newSentence->setOperator( $negation );
		
		// set operand to rhs
		$newSentence->addOperand( $operands[1] );
		
		// get instance
		$negRhs = $vocabulary->oldOrNew( $newSentence );
		
		// make extension
		$branch_b = $branch->copy();
		
		// attach disj to original branch
		$branch->addNode( new GoModal_Node_Sentence( $disj, $node->getI(), true ) );
		
		// attach Lhs, Rhs, negLhs and negRhs to extension
		$branch_b->addNode( new GoModal_Node_Sentence( $operands[0], $node->getI(), false ) );
		$branch_b->addNode( new GoModal_Node_Sentence( $operands[1], $node->getI(), false ) );
		$branch_b->addNode( new GoModal_Node_Sentence( $negLhs, $node->getI(), false ) );
		$branch_b->addNode( new GoModal_Node_Sentence( $negRhs, $node->getI(), false ) );
		
		$node->tick( $branch );
		$node->tick( $branch_b );
		
		return array( $branch_b );
	}
}
?>