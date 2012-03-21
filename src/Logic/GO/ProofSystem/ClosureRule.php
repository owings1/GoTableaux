<?php
/**
 * Defines the Closure rule class for GO.
 * @package GO
 * @author Douglas Owings
 */

namespace GoTableaux\Logic\GO\ProofSystem;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

/**
 * Represents the tableaux closure rule for GO.
 * @package GO
 * @author Douglas Owings
 */
class ClosureRule implements \GoTableaux\ProofSystem\TableauxSystem\ClosureRule
{
	public function doesApply( Branch $branch, Logic $logic )
	{
		foreach ( $branch->getDesignatedNodes() as $node ) {
			$sentence = $node->getSentence();
			if ( $branch->hasSentenceWithDesignation( $sentence, false )) return true;
			if ( $branch->hasSentenceWithDesignation( $logic->negate( $sentence ), true )) return true;
		}
		return false;
	}
}