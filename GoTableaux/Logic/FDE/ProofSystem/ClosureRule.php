<?php
/**
 * Defines the Closure rule class for FDE.
 * @package FDE
 * @author Douglas Owings
 */

namespace GoTableaux\Logic\FDE\ProofSystem;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Logic as Logic;

/**
 * Represents the tableaux closure rule for FDE.
 * @package FDE
 * @author Douglas Owings
 */
class ClosureRule implements \GoTableaux\ProofSystem\TableauxSystem\ClosureRule
{
	public function doesApply( Branch $branch, Logic $logic )
	{
		foreach ( $branch->getDesignatedNodes() as $node ) {
			$sentence = $node->getSentence();
			if ( $branch->hasSentenceWithDesignation( $sentence, false )) return true;
		}
		return false;
	}
}