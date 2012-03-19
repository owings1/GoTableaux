<?php
/**
 * Defines the LPClosure Rule class.
 * @package LP
 * @author Douglas Owings
 */

namespace GoTableaux\Logic\LP\ProofSystem;

use \GoTableaux\Logic as Logic;
use \GoTableaux\Proof\TableauBranch as Branch;

/**
 * Represents the LP closure rule.
 * @package LP
 * @author Douglas Owings
 */
class ClosureRule extends \GoTableaux\Logic\FDE\ProofSystem\ClosureRule
{
	public function doesApply( Branch $branch, Logic $logic )
	{
		foreach ( $branch->getUndesignatedNodes() as $node ) {
			$negated = $logic->negate( $node->getSentence() );
			if ( $branch->hasSentenceWithDesignation( $negated, false )) return true;
		}
		return parent::doesApply( $branch, $logic );
	}
}