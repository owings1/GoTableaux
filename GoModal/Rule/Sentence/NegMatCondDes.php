<?php

class GoModal_Rule_Sentence_NegMatCondDes extends Tableaux_Rule
{
	public function apply( Tableaux_Branch $branch )
	{
		
		
		if ( ! $branch instanceof GoModal_Branch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$negNodes = GoModal_Branch::getNodesByOperatorName( $branch->getDesignatedNodes( true ), 'NEGATION' );
		
		$negCondNodes = array();
		
		foreach ( $negNodes as $node ){
			$operands = $node->getSentence()->getOperands();
			$sen = $operands[0];
			if ( $sen instanceof Sentence_Molecular && $sen->getOperator()->getName() == 'MATERIALCONDITIONAL' ){
				$negCondNodes[] = $node;
			}
		}
		if ( empty( $negCondNodes )){
			return false;
		}
		
		$node = $negCondNodes[0];
		
		// get negation operator
		$vocabulary = GoModal::getVocabulary();
		$operator = $vocabulary->getOperatorByName( 'NEGATION' );
		
		// get material conditional sentence
		$operands = $node->getSentence()->getOperands();
		$condSentence = $operands[0];
		
		// get operands of material conditional
		$operands = $condSentence->getOperands();
		
		// create new sentence
		$newSentence = new Sentence_Molecular();
		
		// set operator to negation
		$newSentence->setOperator( $operator );
		
		// set operand to antecedent
		$newSentence->addOperand( $operands[0] );
		
		// get instance
		$sentence = $vocabulary->oldOrNew( $newSentence );
		
		// attach negated antecedent to original branch
		$branch->addNode( new GoModal_Node_Sentence( $sentence, $node->getI(), false ) );
		
		// attach consequent to extension
		$branch->addNode( new GoModal_Node_Sentence( $operands[1], $node->getI(), false ) );
		
		$node->tick( $branch );

		return array( 0 => $branch );
	}
}
?>