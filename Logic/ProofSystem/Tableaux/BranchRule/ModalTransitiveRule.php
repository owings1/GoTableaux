<?php
/**
 * Defines the ModalTransitiveRule class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link BranchRule} interface.
 */
require_once 'GoTableaux/Logic/ProofSystem/Tableaux/BranchRule.php';

/**
 * Implements the transitivity rule for a modal tableaux system.
 * @package Tableaux
 * @author Douglas Owings
 */
class ModalTransitiveRule implements BranchRule
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
	public function apply( Branch $branch, Logic $logic )
	{
		foreach ( $branch->getAllIndexes() as $index ) 
			if ( !$branch->indexIsTransitive( $index, $newIndex )) {
				$branch->createAccessNode( $index, $newIndex );
				return true;
			}
		return false;
	}
}
?>