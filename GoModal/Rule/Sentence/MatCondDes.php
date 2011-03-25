<?php

class GoModal_Rule_Sentence_MatCondDes extends Tableaux_Rule
{
	public function apply( Tableaux_Branch $branch )
	{
		
		
		if ( ! $branch instanceof GoModal_Branch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$condNodes = GoModal_Branch::getNodesByOperatorName( $branch->getDesignatedNodes( true ), 'MATERIALCONDITIONAL' );
		
		if ( empty( $condNodes )){
			return false;
		}
		
		$node = $condNodes[0];
		
		// get negation operator
		$vocabulary = GoModal::getVocabulary();
		$operator = $vocabulary->getOperatorByName( 'NEGATION' );
		
		// get operands of material conditional
		$operands = $node->getSentence()->getOperands();
		
		// create new sentence
		$newSentence = new Sentence_Molecular();
		
		// set operator to negation
		$newSentence->setOperator( $operator );
		
		// set operand to antecedent
		$newSentence->addOperand( $operands[0] );
		
		// get instance
		$sentence = $vocabulary->oldOrNew( $newSentence );
		
		
		// make extension
		$branch_b = $branch->copy();
		
		// attach negated antecedent to original branch
		$branch->addNode( new GoModal_Node_Sentence( $sentence, $node->getI(), true ) );
		
		// attach consequent to extension
		$branch_b->addNode( new GoModal_Node_Sentence( $operands[1], $node->getI(), true ) );
		
		$node->tick( $branch );
		$node->tick( $branch_b );

		return array( 0 => $branch, $branch_b );
	}
}
?>