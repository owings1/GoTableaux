<?php
/**
 * Defines the K3ClosureRule class.
 * @package StrongKleene
 * @author Douglas Owings
 */

namespace GoTableaux;

/**
 * Represents the K3 closure rule.
 * @package StrongKleene
 * @author Douglas Owings
 */
class StrongKleeneClosureRule extends FDEClosureRule
{
	public function doesApply( Branch $branch, Logic $logic )
	{
		foreach ( $branch->getDesignatedNodes() as $node ) {
			$negated = $logic->negate( $node->getSentence() );
			if ( $branch->hasSentenceWithDesignation( $negated, true )) return true;
		}
		return parent::doesApply( $branch, $logic );
	}
}