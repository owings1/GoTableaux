<?php
/**
 * Defines the ReflexiveModalRule class.
 * @package Tableaux
 * @author Douglas Owings
 */

namespace GoTableaux\ProofSystem\TableauxSystem\BranchRule;

/**
 * Implements the reflexivity rule for a standard modal tableaux system.
 * @package Tableaux
 * @author Douglas Owings
 */
class ModalReflexive implements \GoTableaux\ProofSystem\TableauxSystem\BranchRule
{
	/**
	 * Implements the Reflexive Rule.
	 *
	 * A node is reflexive exactly when both indexes are equal. This rule 
	 * searches for any indexes on the branch for which there is not currently 
	 * a reflexive node. If one is found, it adds a reflexive node with the
	 * class {@link AccessNode}. If more than one are found, it adds
	 * a node for least index.
	 *
	 * @param ModalBranch $branch The modal branch to search and apply the rule to.
	 * @param Logic $logic The Tableaux system.
	 * @return boolean Whether the rule was applied.
	 */
	public function apply( \GoTableaux\Proof\TableauBranch $branch, \GoTableaux\Logic $logic )
	{
		if ( !$indexes = array_diff( $branch->getAllIndexes(), $branch->getReflexiveNodes() ))
			return false;
		$index = min( $indexes );
		$branch->createAccessNode( $index, $index );
		return true;
	}
}