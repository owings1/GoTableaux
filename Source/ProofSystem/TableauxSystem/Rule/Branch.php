<?php
/**
 * GoTableaux. A multi-logic tableaux generator.
 * Copyright (C) 2012  Douglas Owings
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed WITHOUT ANY WARRANTY. 
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/agpl-3.0.html>.
 */
/**
 * Defines the BranchRule interface.
 * @package GoTableaux
 */

namespace GoTableaux\ProofSystem\TableauxSystem\Rule;

use \GoTableaux\Logic as Logic;
use \GoTableaux\Utilities as Utilities;
use \GoTableaux\Proof\Tableau as Tableau;
use \GoTableaux\Proof\TableauBranch as TableauBranch;

/**
 * Represents a tableau rule that applies to a branch.
 * @package GoTableaux
 */
abstract class Branch extends \GoTableaux\ProofSystem\TableauxSystem\Rule
{
	/**
	 * Determines whether the rule can apply to the tableau.
	 *
	 * A branch rule can apply to a tableau when it can apply to an open branch.
	 *
	 * @param Tableau $tableau The tableau to check.
	 * @return boolean Whether the rule can apply.
	 */
	public function applies( Tableau $tableau )
	{
		$t = microtime( true );
		foreach( $tableau->getOpenBranches() as $branch )
		 	if ( $this->appliesToBranch( $branch, $tableau->getProofSystem()->getLogic() )) {
				//Utilities::debug( 'Felicitous applies search lasted ' . round( microtime( true ) - $t, 2 ) . ' seconds.' );
				return true;
			} 
		//Utilities::debug( 'Infelicitous applies search lasted ' . round( microtime( true ) - $t, 2 ) . ' seconds for ' . $this->getName() . '.' );
		return false;
	}

	/**
	 * Applies the rule to a tableau. 
	 * 
	 * A branch rule applies to the first open branch.
	 *
	 * @param Tableau $tableau The tableau to which to apply the rule.
	 * @param Logic $logic The logic.
	 * @return void
	 */
	public function apply( Tableau $tableau )
	{
		parent::apply( $tableau );
		$logic = $tableau->getProofSystem()->getLogic();
		foreach ( $tableau->getOpenBranches() as $branch )
			if ( $this->appliesToBranch( $branch, $logic )) 
				return $this->applyToBranch( $branch, $logic );
		Utilities::debug( get_class( $this ));
		throw new TableauException( 'Trying to apply a branch rule to a tableau with no applicable branches.' );
	}
	
	/**
	 * Creates an example tableau for the rule.
	 * 
	 * @param Logic $logic The logic.
	 * @return Tableau The example tableau.
	 */
	public function getExample( Logic $logic )
	{
		$tableau = new Tableau( $logic->getProofSystem() );
		$branch = $tableau->createBranch();
		$this->buildExample( $branch, $logic );
		$this->applyToBranch( $branch, $logic );
		return $tableau;
	}
	
	/**
	 * Builds an example branch for the rule.
	 *
	 * @param TableauBrach $branch The branch to build.
	 * @param Logic $logic The logic.
	 * @return void
	 */
	abstract public function buildExample( TableauBranch $branch, Logic $logic );
	
	/**
	 * Determines whether a rule can apply to a branch.
	 *
	 * @param TableauBranch The branch to check.
	 * @param Logic $logic The logic of the proof system.
	 * @return boolean Whether the rule can apply.
	 */
	abstract public function appliesToBranch( TableauBranch $branch, Logic $logic );
	
	/**
	 * Applies the rule to an open branch.
	 *
	 * @param TableauBranch $branch The open branch.
	 * @param Logic $logic The logic of the proof system.
	 * @return void
	 */
	abstract public function applyToBranch( TableauBranch $branch, Logic $logic );
}