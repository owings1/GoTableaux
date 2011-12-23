<?php

class GoModal_Rule_Sentence_BoxUndes implements Rule
{
	public function apply( Branch $branch )
	{
		
		if ( ! $branch instanceof GoModalBranch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$necNodes = SentenceNode::findNodesByOperatorName( $branch->getUndesignatedNodes( true ), 'NECESSITY' );
		
		if ( empty( $necNodes )){
			return false;
		}
		
		$node = $necNodes[0];
		
		// get negation operator
		$vocabulary = GoModal::getVocabulary();
		$negation = $vocabulary->getOperatorByName( 'NEGATION' );

		// get box sentence
		$boxSentence = $node->getSentence();
		
		// create negated box sentence
		$newSentence = new MolecularSentence();
		$newSentence->setOperator( $negation );
		$newSentence->addOperand( $boxSentence );
		
		// get instance
		$sentence = $vocabulary->oldOrNew( $newSentence );
		
		
		$branch->addNode( new GoModal_Node_Sentence( $sentence, $node->getI(), true ) );
		
		$node->tick( $branch );
		
		return array( 0 => $branch );
	}
}
?>