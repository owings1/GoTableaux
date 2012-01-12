<?php
/**
 * Defines the LPClosure Rule class.
 * @package LP
 * @author Douglas Owings
 */

/**
 * Represents the LP closure rule.
 * @package LP
 * @author Douglas Owings
 */
class LPClosureRule extends FDEClosureRule
{
	public function doesApply( Branch $branch, Logic $logic )
	{
		foreach ( $branch->getUndesignatedNodes() as $node ) {
			$negated = $logic->negate( $sentence );
			if ( $branch->hasSentenceWithDesignation( $negated, false )) return true;
		}
		return parent::doesApply( $branch, $logic );
	}
}