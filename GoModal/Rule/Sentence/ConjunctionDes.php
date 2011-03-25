<?php

class GoModal_Rule_Sentence_ConjunctionDes extends Tableaux_Rule
{
	public function apply( Tableaux_Branch $branch )
	{
		
		if ( ! $branch instanceof GoModal_Branch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$conjNodes = GoModal_Branch::getNodesByOperatorName( $branch->getDesignatedNodes( true ), 'CONJUNCTION' );
		
		if ( empty( $conjNodes )){
			return false;
		}
		
		$node = $conjNodes[0];
		
		$n = new Doug_SimpleNotifier( 'Rule_ConjunctionDes' );
		$n->notify( 'found an unticked designated conjunction: ' . $node->getSentence()->__tostring() );
		
		$operands = $node->getSentence()->getOperands();
		
		$branch->addNode( new GoModal_Node_Sentence( $operands[0], $node->getI(), true ) );
		$branch->addNode( new GoModal_Node_Sentence( $operands[1], $node->getI(), true ) );
		
		$n->notify( 'ticking node' );
		$node->tick( $branch );
		
		$n->notify( 'extension now has ' . count( $branch->getNodes() ) . ' nodes, ' . count( $branch->getTickedNodes() ) . ' of which are ticked' );
		
		return array( 0 => $branch );
	}
}
?>