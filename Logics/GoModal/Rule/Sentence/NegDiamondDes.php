<?php

class GoModal_Rule_Sentence_NegDiamondDes implements Rule
{
	public function apply( Branch $branch )
	{
		$n = new Doug_SimpleNotifier( 'NegDiamDes' );
		if ( ! $branch instanceof GoModalBranch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$negNodes = GoModalBranch::getNodesByOperatorName( $branch->getDesignatedNodes( true ), 'NEGATION' );
		
		$negDiamNodes = array();
		foreach ( $negNodes as $node ){
			$operands = $node->getSentence()->getOperands();
			$sen = $operands[0];
			if ( $sen instanceof MolecularSentence && $sen->getOperator()->getName() == 'POSSIBILITY' ){
				$negDiamNodes[] = $node;
			}
		}
		
		if ( empty( $negDiamNodes )){
			return false;
		}
		
		
		
		$count = 0;
		do{
			$node = $negDiamNodes[$count];
			
			$is = $branch->getJsByI( $node->getI() );

			if ( empty( $is )){
				return false;
			}
			// get diamond sentence
			$operands = $node->getSentence()->getOperands();
			$diamondSentence = $operands[0];
		
			// get operand of diamond sentence
			$operands = $diamondSentence->getOperands();

			$newI = false;
			foreach ( $is as $i ){
				$n->notify( 'checking for ' . $operands[0]->__tostring() . ', ' . $i . '-' );
				if ( ! $branch->hasSentenceNodeWithAttr( $operands[0], $i, false )){
					$n->notify( 'not found, adding ' );
					$newI = $i;
				}
				$n->notify( 'found, skipping' );
			}
			$count++;
		} while ( ! $newI and $count < count( $negDiamNodes ) );
		
		if ( $newI === false ){
			return false;
		}
		
		$branch->addNode( new GoModal_Node_Sentence( $operands[0], $newI, false ) );
		
		return array( 0 => $branch );
	}
}
?>