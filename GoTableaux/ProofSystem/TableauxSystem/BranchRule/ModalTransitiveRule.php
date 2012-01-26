<?php
/**
 * Defines the ModalTransitive Rule class.
 * @package Tableaux
 * @author Douglas Owings
 */

namespace GoTableaux\ProofSystem\TableauxSystem\BranchRule;

/**
 * Implements the transitivity rule for a modal tableaux system.
 * @package Tableaux
 * @author Douglas Owings
 */
class ModalTransitive implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	/**
	 * Implements the Transitive Rule.
	 *
	 * A world index is transitive exactly when it access all worlds that are
	 * accessed by all the worlds it accesses. The first non-transitive index
	 * found is remedied.
	 *
	 * @param ModalBranch $branch The modal branch to search and apply the rule to.
	 * @param Logic $logic The Tableaux system.
	 * @return boolean Whether the rule was applied.
	 */
	public function apply( \GoTableaux\Proof\TableauBranch $branch, \GoTableaux\Logic $logic )
	{
		foreach ( $branch->getAllIndexes() as $index ) 
			if ( !$branch->indexIsTransitive( $index, $newIndex )) {
				$branch->createAccessNode( $index, $newIndex );
				return true;
			}
		return false;
	}
}