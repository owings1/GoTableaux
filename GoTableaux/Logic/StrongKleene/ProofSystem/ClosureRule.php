<?php
/**
 * Defines the K3 Closure Rule class.
 * @package StrongKleene
 * @author Douglas Owings
 */

namespace GoTableaux\Logic\StrongKleene\ProofSystem;

use \GoTableaux\Logic as Logic;
use \GoTableaux\Proof\TableauBranch as Branch;

/**
 * Represents the K3 closure rule.
 * @package StrongKleene
 * @author Douglas Owings
 */
class ClosureRule extends \GoTableaux\Logic\FDE\ProofSystem\ClosureRule
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