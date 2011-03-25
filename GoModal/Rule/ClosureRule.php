<?php

class GoModal_ClosureRule extends Tableaux_ClosureRule
{
	function apply( Tableaux_Branch $branch )
	{
		if ( ! $branch instanceof GoModal_Branch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		$desNodes   = $branch->getDesignatedNodes();
		
		/*		Check for a Sentence That Is Both Designated and Undesignated		*/
		foreach ( $desNodes as $desNode ){
			if ( $branch->hasSentenceNodeWithAttr( $desNode->getSentence(), $desNode->getI(), false )){
				$branch->close();
				return true;
			}
			
		}
		
		/*		Check for a Designated Sentence Whose Negation Is Designated			*/
		
		/*		Get Designated Negated Nodes			*/
		$desNegNodes = GoModal_Branch::getNodesByOperatorName( $desNodes, 'NEGATION' );
		foreach ( $desNegNodes as $node ){
			$operands = $node->getSentence()->getOperands();
			$operand = $operands[0];
			if ( $branch->hasSentenceNodeWithAttr( $operand, $node->getI(), true )){
				$branch->close();
				return true;
			}
			
		}
		return false;
	}
}
?>