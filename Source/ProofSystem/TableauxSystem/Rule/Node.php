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
 * Defines the ModalTransitive Rule class.
 * @package GoTableaux
 */

namespace GoTableaux\ProofSystem\TableauxSystem\Rule;

use \GoTableaux\Logic as Logic;
use \GoTableaux\Proof\TableauBranch as TableauBranch;
use \GoTableaux\Proof\TableauNode as TableauNode;
use \GoTableaux\Utilities as Utilities;

/**
 * Implements the transitivity rule for a modal tableaux system.
 * @package GoTableaux
 */
abstract class Node extends Branch
{
	/**
	 * Gives conditions for matching a single node to which the rule applies.
	 * @param array
	 */
	protected $conditions = array();
	
	/**
	 * Looks for a node on the branch that meets $this->conditions, and passes
	 * it to applyToNode().
	 *
	 * @param Branch $branch The branch.
	 * @param Logic $logic The logic.
	 * @return boolean Whether the rule was applied.
	 */
	final public function applyToBranch( TableauBranch $branch, Logic $logic )
	{
		if ( !$node = $branch->find( 'one', $this->getConditions() )) return false;
		$this->applyToNode( $node, $branch, $logic );
		return true;
	}
	
    /**
     * Gets the base name of the rule.
     * 
     * @return string The base name of the rule, e.g. NegatedConjunctionDesignated. 
     */
    public function getName()
    {
        return Utilities::getBaseClassName( $this );
    }
    
    /**
     * Gets the conditions.
     * 
     * @return array The conditions. 
     */
    public function getConditions()
    {
        return $this->conditions;
    }
    
    public function getExampleNode()
    {
        $conditions = $this->getConditions();
        // TODO Finish function
        //if ( !empty)
    }
	/**
	 * Applies the changes to a branch for a node that meets $this->conditions.
	 *
	 * @param TableauNode $node The node to apply the changes.
	 * @param Branch $branch The branch for which the rule is applying.
	 * @param Logic $logic The logic of the proof system.
	 * @return void
	 */
	abstract public function applyToNode( TableauNode $node, TableauBranch $branch, Logic $logic );
}