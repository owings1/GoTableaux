<?php
/**
 * Defines the Closure rule class for CPL.
 * @package CPL
 * @author Douglas Owings
 */

namespace GoTableaux\Logic\CPL\ProofSystem;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

/**
 * Represents the tableaux closure rule for CPL.
 * @package CPL
 * @author Douglas Owings
 */
class ClosureRule implements \GoTableaux\ProofSystem\TableauxSystem\ClosureRule
{
	public function doesApply( Branch $branch, Logic $logic )
	{
		foreach ( $branch->getNodes() as $node ) {
			$negated = $logic->negate( $node->getSentence() );
			if ( $branch->hasSentence( $negated )) return true;
		}
		return false;
	}
}