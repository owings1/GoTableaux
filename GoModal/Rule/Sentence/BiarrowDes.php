<?php

class GoModal_Rule_Sentence_BiarrowDes extends Tableaux_Rule
{
	public function apply( Tableaux_Branch $branch )
	{
		
		
		if ( ! $branch instanceof GoModal_Branch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$arrowNodes = GoModal_Branch::getNodesByOperatorName( $branch->getDesignatedNodes( true ), 'BIARROW' );
		
		if ( empty( $arrowNodes )){
			return false;
		}
		
		$node = $arrowNodes[0];
		
		// get negation operator
		$vocabulary = GoModal::getVocabulary();
		$arrow = $vocabulary->getOperatorByName( 'ARROW' );
		
		// get operands of arrow
		$operands = $node->getSentence()->getOperands();
		
		// create new sentence
		$newSentence = new Sentence_Molecular();
		
		// set operator to arrow
		$newSentence->setOperator( $arrow );
		
		// set operands
		$newSentence->addOperand( $operands[0] );
		$newSentence->addOperand( $operands[1] );
		
		// get instance
		$sentence_a = $vocabulary->oldOrNew( $newSentence );
		
		// create new sentence
		$newSentence = new Sentence_Molecular();
		
		// set operator to arrow
		$newSentence->setOperator( $arrow );
		
		// set operands 
		$newSentence->addOperand( $operands[1] );
		$newSentence->addOperand( $operands[0] );
		
		// getInstance
		$sentence_b = $vocabulary->oldOrNew( $newSentence );
		
		// attach sentences to original branch
		$branch->addNode( new GoModal_Node_Sentence( $sentence_a, $node->getI(), true ) );
		$branch->addNode( new GoModal_Node_Sentence( $sentence_b, $node->getI(), true ) );
		
		
		$node->tick( $branch );
		
		return array( 0 => $branch );
	}
}
?>