<?php
/**
 * Defines the GoModal_ClosureRule class.
 * @package GoModal
 * @author Douglas Owings
 */

/**
 * GoModal implementation of {@link Tableaux_ClosureRule}.
 * @package GoModal
 * @author Douglas Owings
 */
class GoModal_ClosureRule implements Tableaux_ClosureRule
{
	/**
	 * Implements Tableaux_ClosureRule::doesApply()
	 *
	 * @param Tableaux_Branch $branch
	 * @return boolean
	 * @throws {@link Tableaux_TableauException}
	 */
	public function doesApply( Tableaux_Branch $branch )
	{
		if ( !$branch instanceof GoModal_Branch )
			throw new Tableaux_TableauException( 'branch must be a GoModal instance' );
		
		$desNodes   = $branch->getDesignatedNodes();
		
		/*		Check for a Sentence That Is Both Designated and Undesignated		*/
		foreach ( $desNodes as $desNode )
			if ( $branch->hasSentenceNodeWithAttr( $desNode->getSentence(), $desNode->getI(), false ))
				return true;
		
		/*		Check for a Designated Sentence Whose Negation Is Designated			*/
		
		/*		Get Designated Negated Nodes			*/
		$desNegNodes = GoModal_Branch::getNodesByOperatorName( $desNodes, 'NEGATION' );
		foreach ( $desNegNodes as $node ){
			$operands = $node->getSentence()->getOperands();
			$operand = $operands[0];
			if ( $branch->hasSentenceNodeWithAttr( $operand, $node->getI(), true ))
				return true;
		}
		
		return false;
	}
}