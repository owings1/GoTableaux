<?php

class GoModal_Rule_Sentence_DiamondUndes extends Tableaux_Rule
{
	public function apply( Tableaux_Branch $branch )
	{
		
		if ( ! $branch instanceof GoModal_Branch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$diamNodes = GoModal_Branch::getNodesByOperatorName( $branch->getUndesignatedNodes( true ), 'POSSIBILITY' );
		
		if ( empty( $diamNodes )){
			return false;
		}
		
		$node = $diamNodes[0];
		
		// get negation operator
		$vocabulary = GoModal::getVocabulary();
		$negation = $vocabulary->getOperatorByName( 'NEGATION' );

		$diamSentence = $node->getSentence();
		
		$newSentence = new Sentence_Molecular();
		$newSentence->setOperator( $negation );
		$newSentence->addOperand( $diamSentence );
		
		$sentence = $vocabulary->oldOrNew( $newSentence );
		
		
		$branch->addNode( new GoModal_Node_Sentence( $sentence, $node->getI(), true ) );
		
		$node->tick( $branch );
		
		return array( 0 => $branch );
	}
}
?>