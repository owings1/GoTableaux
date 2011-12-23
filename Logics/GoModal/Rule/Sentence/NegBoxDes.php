<?php

class GoModal_Rule_Sentence_NegBoxDes implements Rule
{
	public function apply( Branch $branch )
	{
		
		if ( ! $branch instanceof GoModalBranch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$negNodes = GoModalBranch::getNodesByOperatorName( $branch->getDesignatedNodes( true ), 'NEGATION' );
		
		$negBoxNodes = array();
		foreach ( $negNodes as $node ){
			$operands = $node->getSentence()->getOperands();
			$sen = $operands[0];
			if ( $sen instanceof MolecularSentence && $sen->getOperator()->getName() == 'NECESSITY' ){
				$negBoxNodes[] = $node;
			}
		}
		
		if ( empty( $negBoxNodes )){
			return false;
		}
		
		$node = $negBoxNodes[0];
		
		$i = max( $branch->getIsAndJsOnBranch() ) + 1;
		
		// get box sentence
		$operands = $node->getSentence()->getOperands();
		$boxSentence = $operands[0];
		
		// get operand of box sentence
		$operands = $boxSentence->getOperands();
		$sentence = $operands[0];
		
		$branch->addNode( new GoModal_Node_Sentence( $sentence, $i, false ) );
		$branch->addNode( new GoModal_Node_Access( $node->getI(), $i ) );
		
		$node->tick( $branch );
		
		return array( 0 => $branch );
	}
}
?>