<?php

class GoModal_Rule_Sentence_NegMatCondUndes implements Rule
{
	public function apply( Branch $branch )
	{
		
		
		if ( ! $branch instanceof GoModalBranch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$negNodes = SentenceNode::findNodesByOperatorName( $branch->getUndesignatedNodes( true ), 'NEGATION' );
		
		$negCondNodes = array();
		
		foreach ( $negNodes as $node ){
			$operands = $node->getSentence()->getOperands();
			$sen = $operands[0];
			if ( $sen instanceof MolecularSentence && $sen->getOperator()->getName() == 'MATERIALCONDITIONAL' ){
				$negCondNodes[] = $node;
			}
		}
		if ( empty( $negCondNodes )){
			return false;
		}
		
		$node = $negCondNodes[0];
		
		// get singleton operands of negated sentence
		$operands = $node->getSentence()->getOperands();
		
		// get material conditional
		$operand = $operands[0];
			
		// attach material conditional to original branch
		$branch->addNode( new GoModal_Node_Sentence( $operand, $node->getI(), true ) );
		
		$node->tick( $branch );

		return array( 0 => $branch );
	}
}
?>