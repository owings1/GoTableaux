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
use \GoTableaux\Proof\TableauNode\Sentence as SentenceNode;
use \GoTableaux\Proof\TableauNode\Sentence\ManyValued as ManyValuedSentenceNode;
/**
 * Represents a tableau branch.
 * @package GoTableaux
 */
class TableauBranch
{
	/**
	 * Holds the {@link TableauNode}s of the branch.
	 * @var array
	 */
	protected $nodes = array();
	
	/**
	 * Holds the ticked {@link TableauNode}s relative to the branch.
	 * @var array
	 */
	private $tickedNodes = array();
	
	/**
	 * Tracks whether the branch is closed.
	 * @var boolean
	 */
	protected $closed = false;
	
	/**
	 * Holds a reference to the tableau.
	 * @var Tableau
	 */
	protected $tableau;
	
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
	 * @param boolean $untickedOnly Whether to limit search to nodes that are unticked.
	 * @return array Array of {@link TableauNode}s.
	 */
	public function getNodes( $untickedOnly = false )
	{
		if ( $untickedOnly ) return $this->getUntickedNodes();
		return $this->nodes;
	}
	
	/**
	 * Gets all sentence nodes on the branch.
	 *
	 * @param boolean $untickedOnly Whether to limit search to nodes that are unticked.
	 * @return array Array of {@link SentenceNode}s.
	 */
	public function getSentenceNodes( $untickedOnly = false )
	{
		if ( !$untickedOnly ) return $this->getNodesByClassName( 'Sentence' );
		return Utilities::arrayDiff( $this->getNodesByClassName( 'Sentence' ), $this->getTickedNodes() );
	}
	
	/**
	 * Checks whether a sentence is on the branch.
	 *
	 * @param Sentence $sentence The sentence to search for.
	 * @return boolean Whether the branch has a node with that sentence.
	 */
	public function hasNodeWithSentence( Sentence $sentence )
	{
		foreach ( $this->getNodesByClassName( 'Sentence' ) as $node )
			if ( $node->getSentence() === $sentence ) return true;
		return false;
	}
	
	/**
	 * Gets all nodes on the branch that are unticked relative to the branch.
	 *
	 * @return array Array of {@link TableauNode}s.
	 */
	public function getUntickedNodes()
	{
		return Utilities::arrayDiff( $this->getNodes(), $this->getTickedNodes() );
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
	 * @param Node $node The node to check.
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
	 * Gets any {@link SentenceNode}s on the branch that have a given operator
	 * as its sentence's main connective.
	 *
	 * @param string $operatorName The name of the operator.
	 * @param boolean $untickedOnly Whether to include unticked nodes only. 
	 *								Default is false.
	 * @return array Array of {@link SentenceNode}s.
	 */
	public function getNodesByOperatorName( $operatorName, $untickedOnly = false )
	{
		return array_filter( $this->getSentenceNodes( $untickedOnly ), function( $node ) use( $operatorName ) {
			return $node->getSentence()->getOperatorName() === $operatorName;
		});
	}
	
	/**
	 * Gets any {@link SentenceNode}s by two operator names.
	 *
	 * Returns sentence nodes whose first operator is a given operator, and 
	 * whose first operand is a molecular sentence with the given second
	 * operator.
	 *
	 * @param string $firstOperatorName The name of the first operator.
	 * @param string $secondOperatorName The name of the second operator.
	 * @param boolean $untickedOnly Whether to include unticked nodes only.
	 *								Default is false.
	 * @return array The resulting array of {@link SentenceNode}s.
	 */
	public function getNodesByTwoOperatorNames( $firstOperatorName, $secondOperatorName, $untickedOnly = false )
	{
		$nodes = array();
		$searchNodes = $this->getNodesByOperatorName( $firstOperatorName, $untickedOnly );
		foreach ( $searchNodes as $node ) {
			list( $firstOperand ) = $node->getSentence()->getOperands();
			if ( $firstOperand->getOperatorName() === $secondOperatorName ) $nodes[] = $node;
		}
		return $nodes;
	}
	
	public function find( $ret, array $conditions = array() )
	{
		
	}
	/**
	 * Ticks a node relative to the branch.
	 *
	 * @param Node $node The node to tick.
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
	 * @param Node $node The node to untick.
	 * @return TableauBranch
	 */
	public function untickNode( TalbeauNode $node )
	{
		Utilities::arrayRm( $node, $this->tickedNodes );
		return $this;
	}
	
	/**
	 * Adds a node to the branch.
	 *
	 * @param Node $node The node to add.
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
	 * @param Node $node The node to remove. If the node is on the branch in
	 *					 multiple places, each reference is removed.
	 * @return TableauBranch Current instance.
	 */
	public function removeNode( TableauNode $node )
	{
		$this->nodes = array_filter( $this->nodes, function( $n ) use( $node ) { return $n !== $node; });
		return $this;
	}
	
	/**
	 * Checks whether a sentence is on the branch.
	 *
	 * @param Sentence $sentence The sentence to search for.
	 * @return boolean Whether the branch has a node with that sentence.
	 */
	public function hasSentence( Sentence $sentence )
	{
		return $this->hasNodeWithSentence( $sentence );
	}
	
	/**
	 * Creates a node on the branch.
	 *
	 * @param Sentence $sentence The sentence to place on the node.
	 * @return PropositionalBranch Current instance.
	 */
	public function createNode( Sentence $sentence )
	{
		return $this->addNode( new SentenceNode( $sentence ));
	}
	/**
	 * Creates a node on the branch.
	 *
	 * @param Sentence $sentence The sentence to place on the node.
	 * @param boolean $isDesignated The designation marker for the node.
	 * @return ManyValuedBranch Current instance.
	 */
	public function createNodeWithDesignation( Sentence $sentence, $isDesignated )
	{
		return $this->addNode( new ManyValuedSentenceNode( $sentence, $isDesignated ));
	}
	
	/**
	 * Checks whether a sentence is on the branch.
	 *
	 * @param Sentence $sentence The sentence to search for.
	 * @param boolean $isDesignated Whether the sentence should be designated.
	 * @return boolean Whether the branch has a node with that sentence.
	 */
	public function hasSentenceWithDesignation( Sentence $sentence, $isDesignated )
	{
		foreach ( $this->getNodesByClassName( 'Sentence\ManyValued' ) as $node )
			if ( $node->getSentence() === $sentence && $node->isDesignated() === $isDesignated ) return true;
		return false;
	}
	
	/**
	 * Gets all designated sentence nodes on the branch.
	 *
	 * @param boolean $untickedOnly Whether to limit results to unticked nodes.
	 *								Default is false.
	 * @return array Array of {@link ManyValuedSentenceNode}s.
	 */
	public function getDesignatedNodes( $untickedOnly = false )
	{
		$designatedNodes = array();
		foreach ( $this->getNodesByClassName( 'ManyValued' ) as $node ) 
			if ( $node->isDesignated() ) $designatedNodes[] = $node;
		if ( !$untickedOnly ) return $designatedNodes;
		return Utilities::arrayDiff( $designatedNodes, $this->getTickedNodes() );
	}
	
	/**
	 * Gets all undesignated sentence nodes on the branch.
	 * 
	 * @param boolean $untickedOnly Whether to limit results to unticked nodes.
	 *								Default is false.
	 * @return array Array of {@link ManyValuedSentenceNode}s.
	 */
	public function getUndesignatedNodes( $untickedOnly = false )
	{
		return Utilities::arrayDiff( $this->getSentenceNodes( $untickedOnly ), $this->getDesignatedNodes() );
	}
	
	/**
	 * Gets all nodes that have a certain operator name and designation.
	 *
	 * @param string $operatorName The name of the operator.
	 * @param boolean $isDesignated Whether the nodes should be designated.
	 * @param boolean $untickedOnly Whether to restrict to unticked nodes.
	 * @return array Array of {@link ManyValuedSentenceNode}s.
	 */
	public function getNodesByOperatorNameAndDesignation( $operatorName, $isDesignated, $untickedOnly = false )
	{
		$nodes = array();
		$searchNodes = $this->getNodesByOperatorName( $operatorName, $untickedOnly );
		foreach ( $searchNodes as $node )
			if ( $node->isDesignated() === $isDesignated ) $nodes[] = $node;
		return $nodes;
	}
	
	/**
	 * Gets all nodes that have a two operator names and a certain designation.
	 *
	 * @param string $operatorName The name of the operator.
	 * @param boolean $isDesignated Whether the nodes should be designated.
	 * @param boolean $untickedOnly Whether to restrict to unticked nodes.
	 * @return array Array of {@link ManyValuedSentenceNode}s.
	 * @see Branch::getNodesByTwoOperatorNames()
	 */
	public function getNodesByTwoOperatorNamesAndDesignation( $firstOperatorName, $secondOperatorName, $isDesignated, $untickedOnly = false )
	{
		$nodes = array();
		$searchNodes = $this->getNodesByTwoOperatorNames( $firstOperatorName, $secondOperatorName, $untickedOnly );
		foreach ( $searchNodes as $node )
			if ( $node->isDesignated() === $isDesignated ) $nodes[] = $node;
		return $nodes;
	}
}