<?php

class GoModal_Rule_Sentence_DiamondUndes implements Rule
{
	public function apply( Branch $branch )
	{
		
		if ( ! $branch instanceof GoModalBranch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$diamNodes = SentenceNode::findNodesByOperatorName( $branch->getUndesignatedNodes( true ), 'POSSIBILITY' );
		
		if ( empty( $diamNodes )){
			return false;
		}
		
		$node = $diamNodes[0];
		
		// get negation operator
		$vocabulary = GoModal::getVocabulary();
		$negation = $vocabulary->getOperatorByName( 'NEGATION' );

		$diamSentence = $node->getSentence();
		
		$newSentence = new MolecularSentence();
		$newSentence->setOperator( $negation );
		$newSentence->addOperand( $diamSentence );
		
		$sentence = $vocabulary->oldOrNew( $newSentence );
		
		
		$branch->addNode( new GoModal_Node_Sentence( $sentence, $node->getI(), true ) );
		
		$node->tick( $branch );
		
		return array( 0 => $branch );
	}
}
?>