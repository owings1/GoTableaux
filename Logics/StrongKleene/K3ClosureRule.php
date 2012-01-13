<?php
/**
 * Defines the K3ClosureRule class.
 * @package StrongKleene
 * @author Douglas Owings
 */

/**
 * Represents the K3 closure rule.
 * @package StrongKleene
 * @author Douglas Owings
 */
class K3ClosureRule extends FDEClosureRule
{
	public function doesApply( Branch $branch, Logic $logic )
	{
		foreach ( $branch->getDesignatedNodes() as $node ) {
			$negated = $logic->negate( $sentence );
			if ( $branch->hasSentenceWithDesignation( $negated, true )) return true;
		}
		return parent::doesApply( $branch, $logic );
	}
}