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
 * Defines the ModalBranch class.
 * @package GoTableaux
 */

namespace GoTableaux\Proof\TableauBranch;

use \GoTableaux\Proof\TableauNode as Node;
use \GoTableaux\Proof\TableauNode\Access as AccessNode;
use \GoTableaux\Sentence as Sentence;
use \GoTableaux\Proof\TableauNode\Sentence\Modal as ModalSentenceNode;

/**
 * Represents a modal logic tableau branch.
 * @package GoTableaux
 */
class Modal extends \GoTableaux\Proof\TableauBranch
{
	/**
	 * Hashes the access relation.
	 * @var array
	 * @access private
	 */
	protected $accessRelation = array();
	
	/**
	 * Hashes the indexes on the branch.
	 * @var array
	 * @access private
	 */
	protected $indexes = array();
	
	/**
	 * Adds a new access node for the given two integer indexes.
	 *
	 * @param integer $i The first index.
	 * @param integer $j The second index.
	 * @return ModalBranch Current instance.
	 */
	public function createAccessNode( $i, $j )
	{
		return $this->addNode( new AccessNode( $i, $j ) );
	}
	
	/**
	 * Gets all modal access nodes on the branch.
	 *
	 * @return array Array of {@link AccessNode}s.
	 */
	public function getAccessNodes()
	{
		return $this->getNodesByClassName( 'Access' );
	}
	
	/**
	 * Gets all world indexes that appear on the branch.
	 *
	 * @return array Array of unique integer indexes that appear on the branch.
	 */
	public function getIndexes()
	{
		return $this->indexes;
	}
	
	/**
	 * Gets all world indexes that "see" themselves.
	 *
	 * @return array Array of unique integer indexes that have reflexive nodes.
	 */
	public function getReflexiveIndexes()
	{
		$that = $this;
		return array_filter( $this->getIndexes(), function( $i ) use( $that ) { return $that->access( $i, $i ); });
	}
	
	/**
	 * Checks whether a given world index accesses another.
	 *
	 * @param integer $i The world that wants to access.
	 * @param integer $j The world that wants to be accessed.
	 * @return boolean Whether the one world accesses the other.
	 */
	public function accesses( $i, $j )
	{
		return in_array( $j, $this->getAccessRelation( $i ));
	}
	
	/**
	 * Gets the access relation.
	 *
	 * @param integer $i The index whose access relation to get.
	 * @return array If $i is set, then the array of indexes that $i access is
	 *				 returned. Otherwise, a two-dimensional array of the access
	 *				 relation is returned.
	 */
	public function getAccessRelation( $i = null )
	{
		if ( is_null( $i )) return $this->accessRelation;
		if ( empty( $this->accessRelation[$i] )) return array();
		return $this->accessRelation[$i];
	}
	
	/**
	 * Checks whether a given world index represents a transitive world.
	 *
	 * @param integer $i The world index to check for transitivity.
	 * @param integer|null &$firstMissing The first counterexample, if found.
	 * @return boolean Whether the world index is transitive.
	 */
	public function indexIsTransitive( $i, &$firstMissing = null )
	{
		foreach ( $this->getAccessRelation( $i ) as $indexA )
			foreach ( $this->getAccessRelation( $indexA ) as $indexB )
				if ( !$this->accesses( $i, $indexB )) {
					$firstMissing = $indexB;
					return false;
				}
		return true;
	}
	
	/**
	 * Adds a new sentence node to the branch.
	 *
	 * @param Sentence $sentence The sentence to add.
	 * @param integer $index The world index of the node.
	 * @return ModalBranch Current instance.
	 */
	public function createSentenceNodeAtIndex( Sentence $sentence, $i )
	{
		return $this->addNode( new ModalSentenceNode( $sentence, $i ));
	}
	
	/**
	 * Checks for existence of sentence node with given sentence and index.
	 *
	 * @param Sentence $sentence The sentence to search for.
	 * @param integer $i The index to search for.
	 * @return boolean Whether such a sentence node is on the branch.
	 */
	public function hasSentenceAtIndex( Sentence $sentence, $i )
	{
		foreach ( $this->getSentenceNodes() as $node )
			if ( $node->getSentence() === $sentence && $node->getI() === $i ) return true;
		return false;
	}
	
	/**
	 * Stores an access relationship.
	 *
	 * @param integer $i The first index.
	 * @param integer $j The second index.
	 * @return ModalBranch Current instance.
	 * @access private
	 */
	protected function _addAccessRelationship( $i, $j )
	{
		if ( !in_array( $j, $this->accessRelation[$i] )) $this->accessRelation[$i][] = $j;
		return $this;
	}
	
	/**
	 * Stores an index on the branch.
	 * 
	 * @param integer $i The index to store.
	 * @return ModalBranch Current instance.
	 * @access private
	 */
	protected function _addIndex( $i )
	{
		if ( !in_array( $i, $this->indexes )) {
			$this->indexes[] = $i;
			$this->accessRelation[$i] = array();
		}
		return $this;
	}
	
	/**
	 * Adds a node to the branch.
	 *
	 * @param Node $node The node to add.
	 * @return ModalBranch Current instance.
	 * @access private
	 */
	protected function addNode( Node $node )
	{
		$this->_addIndex( $node->getI() );
		if ( $node instanceof AccessNode ) {
			$this->_addAccessRelationship( $node->getI(), $node->getJ() )
				 ->_addIndex( $node->getJ() );
		}
		return parent::addNode( $node );
	}
	
	/**
	 * Removes a node from the branch.
	 * 
	 * @param Node $node The node to remove.
	 * @return ModalBranch Current instance.
	 * @access private
	 */
	public function _removeNode( Node $node )
	{
		parent::_removeNode( $node );
		$this->indexes = $this->accessRelation = array();
		foreach ( $this->getNodes() as $node ) {
			$this->_addIndex( $node->getI() );
			if ( $node instanceof AccessNode ) 
				$this->_addAccessRelationship( $node->getI(), $node->getJ() )
					 ->_addIndex( $node->getJ() );
		}
	}
}