<?php

class GoModal_Rule_Sentence_ArrowUndes implements Tableaux_Rule
{
	public function apply( Tableaux_Branch $branch )
	{
		if ( ! $branch instanceof GoModal_Branch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$condNodes = GoModal_Branch::getNodesByOperatorName( $branch->getUndesignatedNodes( true ), 'ARROW' );
		
		
		if ( empty( $condNodes )){
			return false;
		}
		
		$node = $condNodes[0];
		
		// get arrow sentence
		$operand = $node->getSentence();
		
		// get negation operator
		$vocabulary = GoModal::getVocabulary();
		$operator = $vocabulary->getOperatorByName( 'NEGATION' );
		
		// create new sentence
		$newSentence = new Sentence_Molecular();
		
		// set operator to negation
		$newSentence->setOperator( $operator );
		
		// set operand to arrow sentence
		$newSentence->addOperand( $operand );
		
		// get instance
		$sentence = $vocabulary->oldOrNew( $newSentence );
		
		$branch->addNode( new GoModal_Node_Sentence( $sentence, $node->getI(), true ) );
		
		$node->tick( $branch );
		

		return array( 0 => $branch );
	}
}
?>