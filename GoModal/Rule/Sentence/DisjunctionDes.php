<?php

class GoModal_Rule_Sentence_DisjunctionDes extends Tableaux_Rule
{
	public function apply( Tableaux_Branch $branch )
	{
		
		
		if ( ! $branch instanceof GoModal_Branch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$disjNodes = GoModal_Branch::getNodesByOperatorName( $branch->getDesignatedNodes( true ), 'DISJUNCTION' );
		
		if ( empty( $disjNodes )){
			return false;
		}
		
		$node = $disjNodes[0];
		
		$n = new Doug_SimpleNotifier( 'Rule_DisjunctionDes' );
		$n->notify( 'found an unticked designated disjunction: ' . $node->getSentence()->__tostring() );
		
		$operands = $node->getSentence()->getOperands();
		
		$branch_b = $branch->copy();
		
		$branch->addNode( new GoModal_Node_Sentence( $operands[0], $node->getI(), true ) );
		$branch_b->addNode( new GoModal_Node_Sentence( $operands[1], $node->getI(), true ) );
		
		$n->notify( 'ticking node' );
		$node->tick( $branch );
		$n->notify( 'first extension now has ' . count( $branch->getNodes() ) . ' nodes, ' . count( $branch->getTickedNodes() ) . ' of which are ticked' );
		$node->tick( $branch_b );
		$n->notify( 'second extension now has ' . count( $branch_b->getNodes() ) . ' nodes, ' . count( $branch_b->getTickedNodes() ) . ' of which are ticked' );
		
		

		return array( 0 => $branch, $branch_b );
	}
}
?>