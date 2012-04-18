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
 * Defines the Branch class.
 * @package GoTableaux
 */

namespace GoTableaux\Proof;

use \GoTableaux\Sentence as Sentence;
use \GoTableaux\Utilities as Utilities;
use \GoTableaux\Proof\TableauNode as Node;
use \GoTableaux\Proof\TableauNode\Modal as ModalNode;
use \GoTableaux\Proof\TableauNode\Access as AccessNode;
use \GoTableaux\Proof\TableauNode\Sentence as SentenceNode;
use \GoTableaux\Proof\TableauNode\ManyValued as ManyValuedNode;
use \GoTableaux\Proof\TableauNode\Sentence\ManyValued as ManyValuedSentenceNode;

/**
 * Represents a tableau branch.
 * @package GoTableaux
 */
class TableauBranch
{
	/**
	 * Holds the nodes of the branch.
	 * @var array
	 */
	private $nodes = array();
	
	/**
	 * Tracks the ticked nodes relative to the branch.
	 * @var array
	 */
	private $tickedNodes = array();
	
	/**
	 * Tracks whether the branch is closed.
	 * @var boolean
	 */
	private $closed = false;
	
	/**
	 * Holds a reference to the tableau.
	 * @var Tableau
	 */
	private $tableau;
	
	/**
	 * Constructor.
	 *
	 * Initializes the tableau.
	 *
	 * @param Tableau $tableau The tableau of the branch.
	 */
	public function __construct( Tableau $tableau )
	{
		$this->tableau = $tableau;
		$tableau->attach( $this );
	}
	
	/**
	 * Gets the tableau.
	 *
	 * @return Tableau The tableau.
	 */
	public function getTableau()
	{
		return $this->tableau;
	}
		
	/**
	 * Gets the nodes on the branch.
	 *
	 * @return array The {@link TableauNode nodes}.
	 */
	public function getNodes()
	{
		return $this->nodes;
	}
	
	/**
	 * Gets all nodes on the branch that are ticked relative to the branch.
	 *
	 * @return array Array of {@link TableauNode}s.
	 */
	public function getTickedNodes()
	{
		return $this->tickedNodes;
	}

	/**
	 * Gets all nodes of a certain class name.
	 *
	 * @param string $className The name of the class, either relative or absolute.
	 * @param boolean strict Whether to return only nodes that are instantiated
	 *						 as that particular class. Default behavior is to 
	 *						 return any node that inherits from the given class.
	 * @return array The nodes on the branch that are of the class.
	 */
	public function getNodesByClassName( $className, $strict = false )
	{
		if ( $className{0} !== '\\' ) $className = __NAMESPACE__ . '\TableauNode\\' . $className;
		return array_filter( $this->getNodes(), function( $node ) use( $className, $strict ) {
			return $strict ? get_class( $node ) === $className : $node instanceof $className;
		});
	}
	
	/**
	 * Closes the branch.
	 *
	 * @return TableauBranch Current instance.
	 */
	public function close()
	{
		$this->closed = true;
		return $this;
	}
	
	/**
	 * Checks whether the branch is closed.
	 *
	 * @return boolean Whether the branch is closed.
	 */
	public function isClosed()
	{
		return $this->closed;
	}
	
	/**
	 * Checks whether the branch is open.
	 *
	 * @return boolean Whether the branch is open.
	 */
	public function isOpen()
	{
		return !$this->isClosed();
	}
	
	/**
	 * Checks whether a node is on the branch.
	 *
	 * @param TableauNode $node The node to check.
	 * @return boolean Whether the node is on the branch.
	 */
	public function hasNode( TableauNode $node )
	{
		return in_array( $node, $this->getNodes(), true );
	}
	
	/**
	 * Clones the branch. Maintains references to the nodes.
	 *
	 * @return TableauBranch The new copy.
	 */
	public function copy()
	{
		return clone $this;
	}
	
	/**
	 * Branches the branch.
	 *
	 * Copies the branch, attaches the copy to the tableau, and returns the new
	 * branch.
	 *
	 * @return TableauBranch The new branch
	 */
	public function branch()
	{
		$newBranch = $this->copy();
		$this->getTableau()->attach( $newBranch );
		return $newBranch;
	}
	
	/**
	 * Finds nodes on the branch matching the given conditions.
	 *
	 * conditions: 
	 * 			 class - the class name of the node
	 *		designated - whether the node is designated
	 *		  sentence - the sentence that is on the node
	 *				 i - the first world index
	 *			     j - the second world index
	 * 			ticked - whether the node is ticked
	 *		  operator - the operator name, or array of operator names of the
	 *				     sentence on the node.
	 *
	 * @param string $ret Wether to return one or all results ('all' or 'one').
	 * @param array $conditions The conditions to apply.
	 * @return mixed The results depending on $ret.
	 * @throws \ErrorException on an invalid $ret value.
	 */
	public function find( $ret = 'all', array $conditions = array() )
	{
		$that = $this;
		if ( !empty( $conditions['class'] ))
			$nodes = $this->getNodesByClassName( $conditions['class'] );
		else $nodes = $this->getNodes();
		if ( isset( $conditions['ticked'] ))
			$nodes = array_filter( $nodes, function( $node ) use( $conditions, $that ) {
				return $that->nodeIsTicked( $node ) === $conditions['ticked'];
			});
		if ( isset( $conditions['i'] ))
			$nodes = array_filter( $nodes, function( $node ) use( $conditions ) {
				return $node instanceof ModalNode && $node->getI() === $conditions['i'];
			});
		if ( isset( $conditions['j'] ))
			$nodes = array_filter( $nodes, function( $node ) use( $conditions ) {
				return $node instanceof AccessNode && $node->getJ() === $conditions['j'];
			});
		if ( isset( $conditions['sentence'] ))
			$nodes = array_filter( $nodes, function( $node ) use( $conditions ) {
				return $node instanceof SentenceNode && $node->getSentence() === $conditions['sentence'];
			});
		if ( isset( $conditions['designated'] ))
			$nodes = array_filter( $nodes, function( $node ) use( $conditions ) { 
				return $node instanceof ManyValuedNode && $node->isDesignated() === $conditions['designated'];
			});
		if ( !empty( $conditions['operator'] )) {
			$operators = (array) $conditions['operator'];
			$nodes = array_filter( $nodes, function( $node ) use( $operators ) {
				if ( !$node instanceof SentenceNode ) return false;
				if ( $node->getSentence()->getOperatorName() !== $operators[0] ) return false;
				if ( empty( $operators[1] )) return true;
				list( $firstOperand ) = $node->getSentence()->getOperands();
				return $firstOperand->getOperatorName() === $operators[1];
			});
		}
		if ( $ret === 'all' ) return $nodes;
		elseif ( $ret === 'one' || $ret === 'first' ) return array_shift( $nodes );
		elseif ( $ret === 'exists' ) return !empty( $nodes );
		else throw new \ErrorException( "$ret is not a valid parameter." );
	}
	
	/**
	 * Ticks a node relative to the branch.
	 *
	 * @param TableauNode $node The node to tick.
	 * @return TableauBranch Current instance.
	 */
	public function tickNode( TableauNode $node )
	{
		Utilities::uniqueAdd( $node, $this->tickedNodes );
		return $this;
	}
	
	/**
	 * Unticks a node relative to the branch.
	 *
	 * @param TableauNode $node The node to untick.
	 * @return TableauBranch
	 */
	public function untickNode( TalbeauNode $node )
	{
		Utilities::arrayRm( $node, $this->tickedNodes );
		return $this;
	}
	
	/**
	 * Determines whether a node is ticked relative to the branch.
	 *
	 * @param TableauNode $node The node to check.
	 * @param boolean Whether the node is ticked relative to the branch.
	 */
	public function nodeIsTicked( TableauNode $node )
	{
		return in_array( $node, $this->getTickedNodes(), true );
	}
	
	/**
	 * Adds a node to the branch.
	 *
	 * @param TableauNode $node The node to add.
	 * @return TableauBranch Current instance.
	 */
	public function addNode( TableauNode $node )
	{
		$node->beforeAttach( $this );
		$this->nodes[] = $node;
		return $this;
	}
	
	/**
	 * Removes all references to a node from the branch.
	 *
	 * @param TableauNode $node The node to remove. If the node is on the branch in
	 *					 multiple places, each reference is removed.
	 * @return TableauBranch Current instance.
	 */
	public function removeNode( TableauNode $node )
	{
		$this->nodes = array_filter( $this->nodes, function( $n ) use( $node ) { return $n !== $node; });
		return $this;
	}
	
	/**
	 * Creates a node on the branch.
	 *
	 * @param string|array $class The node classes to instantiate.
	 * @param array $properties The properties of the node.
	 * @return TableauBranch Current instance.
	 */
	public function createNode( $classes, array $properties = array() )
	{
		$class = str_replace( '\\', '/', $class );
                $node = new TableauNode;
                if ( !is_array( $classes )) $classes = explode( '/', $class );
		foreach ( $classes as $class ) {
                    if ( $class{0} !== '\\' ) $class = __NAMESPACE__ . '\TableauNode\\' . $class;
                    $node = new $class( $node, $properties );
                }
		$this->addNode( $node );
		return $this;
	}
}