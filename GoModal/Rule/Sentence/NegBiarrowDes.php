<?php

class GoModal_Rule_Sentence_NegBiarrowDes extends Tableaux_Rule
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
			if ( $sen instanceof Sentence_Molecular && $sen->getOperator()->getName() == 'BIARROW' ){
				$negCondNodes[] = $node;
			}
		}
		if ( empty( $negCondNodes )){
			return false;
		}
		
		$node = $negCondNodes[0];
		
		// get singleton operands of negated sentence
		$operands = $node->getSentence()->getOperands();
		
		// get biarrow sentence
		$biarrowSentence = $operands[0];
		
		// get negation operator
		$vocabulary = GoModal::getVocabulary();
		$negation = $vocabulary->getOperatorByName( 'NEGATION' );
		
		// get arrow operator
		$arrow = $vocabulary->getOperatorByName( 'ARROW' );
		
		// get operands of biarrow
		$operands = $biarrowSentence->getOperands();
		
		
		
		// create new sentence
		$newSentence = new Sentence_Molecular();
		
		// set operator to arrow
		$newSentence->setOperator( $arrow );
		
		// set operands
		$newSentence->addOperand( $operands[0] );
		$newSentence->addOperand( $operands[1] );
		
		// get instance
		$lrd = $vocabulary->oldOrNew( $newSentence );
		
		// create new sentence
		$newSentence = new Sentence_Molecular();
		
		// set operator to negation
		$newSentence->setOperator( $negation );
		
		// set operand to lrd
		$newSentence->addOperand( $lrd );
		
		// get instance
		$negLrd = $vocabulary->oldOrNew( $newSentence );
		
		
		
		// create new sentence
		$newSentence = new Sentence_Molecular();
		
		// set operator to arrow
		$newSentence->setOperator( $arrow );
		
		// set operands
		$newSentence->addOperand( $operands[1] );
		$newSentence->addOperand( $operands[0] );
		
		// get instance
		$rld = $vocabulary->oldOrNew( $newSentence );
		
		// create new sentence
		$newSentence = new Sentence_Molecular();
		
		// set operator to negation
		$newSentence->setOperator( $negation );
		
		// set operand to lrd
		$newSentence->addOperand( $rld );
		
		// get instance
		$negRld = $vocabulary->oldOrNew( $newSentence );
		
		// make extension
		$branch_b = $branch->copy();
		
		// attach negated Lrd to original branch
		$branch->addNode( new GoModal_Node_Sentence( $negLrd, $node->getI(), true ) );
		
		// attach negated Rld to extension
		$branch_b->addNode( new GoModal_Node_Sentence( $negRld, $node->getI(), true ) );
		
		$node->tick( $branch );
		$node->tick( $branch_b );
		
		return array( 0 => $branch, $branch_b );
	}
}
?>