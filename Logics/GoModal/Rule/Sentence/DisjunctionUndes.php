<?php

class GoModal_Rule_Sentence_DisjunctionUndes implements Rule
{
	public function apply( Branch $branch )
	{
		if ( ! $branch instanceof GoModalBranch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$disjNodes = GoModalBranch::getNodesByOperatorName( $branch->getUndesignatedNodes( true ), 'DISJUNCTION' );
		
		if ( empty( $disjNodes )){
			return false;
		}
		
		$node = $disjNodes[0];
		
		$vocabulary = GoModal::getVocabulary();
		$operator = $vocabulary->getOperatorByName( 'NEGATION' );
		
		$operand = $node->getSentence();
		$newSentence = new MolecularSentence();
		$newSentence->setOperator( $operator );
		$newSentence->addOperand( $operand );
		$sentence = $vocabulary->oldOrNew( $newSentence );
		
		
		$branch->addNode( new GoModal_Node_Sentence( $sentence, $node->getI(), true ) );
		
		$node->tick( $branch );
		

		return array( 0 => $branch );
	}
}
?>