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
 * Defines the Structure class.
 * @package GoTableaux
 */

namespace GoTableaux\Proof;

use \GoTableaux\Utilities as Utilities;

/**
 * Represents the proper 'tree' structure of a tableau. 
 *
 * This is a simple composition structure class which takes a {@link Tableau} 
 * object and creates a tree structure from its branches. Thinking of a branch 
 * as a tuple of nodes, a single node can appear on more than one branch. This 
 * is helpful in applying rules. This class collapses common nodes into one 
 * structure. A structure comprises an array of {@link Node} objects, and an 
 * array of self-similar Structure objects.
 *
 * @package GoTableaux
 */
class TableauStructure
{
	/**
	 * @var Tableau
	 * @access private
	 */
	protected $tableau;
	
	/**
	 * @var array Array of {@link Node} objects.
	 * @access private
	 */
	protected $nodes = array();
	
	/**
	 * @var array Array of {@link Node} objects.
	 * @access private
	 */
	protected $tickedNodes = array();
	
	/**
	 * @var array Array of self-similar objects.
	 * @access private
	 */
	protected $structures = array();
	
	/**
	 * @var boolean
	 * @access private
	 */
	protected $closed = false;
				
	/**
	 * Creates an instance from a tableau.
	 *
	 * @param Tableau $tableau The tableau whose structure to represent.
	 * @return Structure New instance.
	 */
	public static function getInstance( Tableau $tableau )
	{
		$instance = new self();
		$instance->setTableau( $tableau );
		return $instance;
	}

	/**
	 * Gets all branches that have a particular node on them.
	 *
	 * @param array $searchBranches Array of {@link Branch}es to search.
	 * @param Node $node The node to search for.
	 * @return array Array of branches.
	 */
	protected static function findBranchesWithNode( array $searchBranches, TableauNode $node )
	{
		$branches = array();
		foreach ( $searchBranches as $branch )
			if ( $branch->hasNode( $node )) $branches[] = $branch;
		return $branches;
	}
	
	/**
	 * Gets all nodes that are on each of an array of branches.
	 *
	 * @param array $branches Array of {@link Branch}es.
	 * @return array Array of common {@link Node}s. 
	 */
	protected static function findNodesCommonToBranches( array $branches )
	{
		$nodes = $commonNodes = array();
		foreach ( $branches as $branch ) $nodes = array_merge( $nodes, $branch->getNodes() );
		foreach ( $nodes as $node ) {
			$isCommon = true;			
			foreach ( $branches as $branch )
				if ( !$branch->hasNode( $node )) {
					$isCommon = false;
					break;
				}
			if ( $isCommon ) $commonNodes[] = $node;
		}
		return Utilities::arrayUnique( $commonNodes );
	}
	
	/**
	 * Sets the tableau to represent.
	 *
	 * @param Tableau $tableau The tableau whose structure to represent.
	 * @return Structure Current instance.
	 */
	public function setTableau( Tableau $tableau )
	{
		$this->tableau = $tableau->copy();
		return $this->build();
	}
	
	/**
	 * Builds the tree structure.
	 *
	 * @return Structure Current instance.
	 */
	public function build()
	{
		$branches = $this->tableau->getBranches();
		$this->structurize( $branches );
		return $this;
	}
	
	/**
	 * Gets the nodes of the current structure.
	 *
	 * @return array Array of {@link Node}s.
	 */
	public function getNodes()
	{
		return $this->nodes;
	}
	
	/**
	 * Gets the child structures of the current structure.
	 *
	 * @return array Array of {@link Structure}s.
	 */
	public function getStructures()
	{
		return $this->structures;
	}
	
	/**
	 * Checks whether a node is ticked relative to the current structure.
	 *
	 * @param Node $node The node to check.
	 * @return boolean Whether the node is ticked.
	 */
	public function nodeIsTicked( TableauNode $node )
	{
		return in_array( $node, $this->tickedNodes, true );
	}
	
	/**
	 * Checks whether the structure is closed.
	 *
	 * @return boolean Whether the structure is closed.
	 */
	public function isClosed()
	{
		return $this->closed;
	}
	
	/**
	 * Recursive structurizing function.
	 *
	 * @param array $branches Array of {@link Branch}es to structurize.
	 * @return void
	 * @access private
	 */
	protected function structurize( array $branches )
	{
		if ( empty( $branches )) return;
		
		// get nodes that are common to branches
		$nodes = self::findNodesCommonToBranches( $branches );
		
		foreach ( $nodes as $node ) {
			$ticked = false;
			foreach ( $branches as $branch ) $ticked |= $node->isTickedAtBranch( $branch );
			if ( $ticked ) {
				$this->tickedNodes[] = $node;
				$node->writeAsTicked = true;
			} else $node->writeAsTicked = false;
		}
		// remove nodes from branches
		foreach ( $nodes as $node )
			foreach ( $branches as $branch ) $branch->removeNode( $node );

		// assign nodes to structure
		$this->nodes = $nodes;
		
		if ( count( $branches ) > 1 ) 
			while ( !empty( $branches )) {
				// grab first node from a branch
				$branch = $branches[0];
				$n 		= $branch->getNodes();
				$node 	= array_shift( $n );
				
				// group branches that have that node
				$group = self::findBranchesWithNode( $branches, $node );
				
				// remove them from $branches
				$branches = Utilities::arrayDiff( $branches, $group );
				
				// add group to sub structures
				$structure = new self();
				$this->structures[] = $structure;
				
				// structurize branches
				$structure->structurize( $group );
			}
		else {
			$branch = $branches[0];
			if ( $branch->isClosed() ) $this->closed = true;
		}	
	}
}