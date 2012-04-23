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
use \GoTableaux\Exception\Tableau as TableauException;
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
	 * Determines whether a rule can apply to a branch.
	 *
	 * A node rule can apply to a branch when it can apply to a node.
	 *
	 * @param TableauBranch The branch to check.
	 * @param Logic $logic The logic of the proof system.
	 * @return boolean Whether the rule can apply.
	 */
	final public function appliesToBranch( TableauBranch $branch, Logic $logic )
	{
		foreach ( $branch->getUntickedNodes() as $node )
			if ( $this->appliesToNode( $node, $branch, $logic )) return true;
		return false;
	}
	
	/**
	 * Looks for a node on the branch that meets $this->conditions, and passes
	 * it to applyToNode().
	 *
	 * @param Branch $branch The branch.
	 * @param Logic $logic The logic.
	 * @return void
	 * @throws TableauException if there is no node on the branch to which the 
	 *		   rule applies.
	 */
	final public function applyToBranch( TableauBranch $branch, Logic $logic )
	{
		$node = $branch->find( 'first', $this->getConditions() );
		if ( empty( $node )) {
			Utilities::debug( get_class( $this ));
			throw new TableauException( 'Trying to apply a node rule to a branch that has no applicable nodes.' );
		}
		$this->applyToNode( $node, $branch, $logic );
	}
	
	/**
	 * Builds an example branch for the rule.
	 *
	 * @param TableauBrach $branch The branch to build.
	 * @param Logic $logic The logic.
	 * @return void
	 */
	final public function buildExample( TableauBranch $branch, Logic $logic )
	{
		$branch->addNode( $this->getExampleNode( $logic ));
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
     * Gets the conditions. Forces unticked.
     * 
     * @return array The conditions. 
     */
    public function getConditions()
    {
        return array_merge( array( 'ticked' => false ), $this->conditions );
    }
    
	/**
	 * Gets an example node based on the rule's conditions.
	 *
	 * @param Logic $logic The logic.
	 * @return TableauNode|boolean The example node, or false if no example 
	 *							   could be induced.
	 */
    private function getExampleNode( Logic $logic )
    {
        $conditions = $this->getConditions();
		$classes = $properties = array();
		if ( !empty( $conditions['sentenceForm'] )) {
			$classes[] = 'Sentence';
			$properties['sentence'] = $logic->parseSentence( $conditions['sentenceForm'] );
		}
		if ( isset( $conditions['designated'] )) {
			$classes[] = 'ManyValued';
			$properties['designated'] = $conditions['designated'];
		}
		if ( isset( $conditions['i'] )) {
			$classes[] = 'Modal';
			$properties['i'] = $conditions['i'] === '*' ? 0 : $conditions['i'];
		}
		if ( isset( $conditions['j'] )) {
			$classes[] = 'Access';
			$properties['i'] = $conditions['j'] === '*' ? 0 : $conditions['j'];
		}
		if ( empty( $classes )) return false;
		$node = new TableauNode;
		$baseClass = get_class( $node );
		foreach ( $classes as $class ) {
			$class = $baseClass . '\\' . $class;
			$node = new $class( $node, $properties );
		}
		return $node;
    }
	
	/**
	 * Determines whether the rule applies to a node.
	 *
	 * The default implementation is to run a simple find query on the branch 
	 * with the rule's conditions.
	 */
	public function appliesToNode( TableauNode $node, TableauBranch $branch, Logic $logic )
	{
		return $node->filter( $this->getConditions(), $logic );
	}
	
	/**
	 * Applies the changes to a branch for an unticked node that meets $this->conditions.
	 *
	 * @param TableauNode $node The node to apply the changes.
	 * @param Branch $branch The branch for which the rule is applying.
	 * @param Logic $logic The logic of the proof system.
	 * @return void
	 */
	abstract public function applyToNode( TableauNode $node, TableauBranch $branch, Logic $logic );
}