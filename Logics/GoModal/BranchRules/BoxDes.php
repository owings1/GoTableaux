<?php

class GoModal_Rule_Sentence_BoxDes implements Rule
{
	public function apply( Branch $branch )
	{
		$n = new Doug_SimpleNotifier( 'BoxDes' );
		if ( ! $branch instanceof GoModalBranch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$necNodes = SentenceNode::findNodesByOperatorName( $branch->getDesignatedNodes( true ), 'NECESSITY' );
		
		if ( empty( $necNodes )){
			return false;
		}
		
		
		
		
		$count = 0;
		do{
			$node = $necNodes[$count];
			$is = $branch->getJsByI( $node->getI() );

			if ( empty( $is )){
				return false;
			}
			
			
			
			$operands = $node->getSentence()->getOperands();
		
			$newI = false;
			foreach ( $is as $i ){
				$n->notify( 'looking for ' . $operands[0]->__tostring() . ', ' . $i . '+' );
				if ( ! $branch->hasSentenceNodeWithAttr( $operands[0], $i, true, false )){
					$n->notify( 'not found, adding it' );
					$newI = $i;
				}
				else{
					$n->notify( 'found, skipping it' );
				}
			}
			$count++;
		}
		while ( ! $newI and $count < count( $necNodes ) );
		if ( $newI === false ){
			return false;
		}
		
		$branch->addNode( new GoModal_Node_Sentence( $operands[0], $newI, true ) );
		
		return array( 0 => $branch );
	}
}
?>