<?php

class GoModal_Rule_Sentence_ConjunctionUndes extends Tableaux_Rule
{
	public function apply( Tableaux_Branch $branch )
	{
		if ( ! $branch instanceof GoModal_Branch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$conjNodes = GoModal_Branch::getNodesByOperatorName( $branch->getUndesignatedNodes( true ), 'CONJUNCTION' );
		
		if ( empty( $conjNodes )){
			return false;
		}
		
		$node = $conjNodes[0];
		
		// get negation operator
		$vocabulary = GoModal::getVocabulary();
		$operator = $vocabulary->getOperatorByName( 'NEGATION' );
		
		// get conjunction
		$operand = $node->getSentence();
		
		// create new sentence
		$newSentence = new Sentence_Molecular();
		
		// set operator to negation
		$newSentence->setOperator( $operator );
		
		// set operand to conjunction
		$newSentence->addOperand( $operand );
		
		// get instance
		$sentence = $vocabulary->oldOrNew( $newSentence );
		
		
		$branch->addNode( new GoModal_Node_Sentence( $sentence, $node->getI(), true ) );
		
		$node->tick( $branch );
		

		return array( 0 => $branch );
	}
}
?>