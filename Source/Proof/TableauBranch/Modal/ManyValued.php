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
 * Defines the ManyValuedModalBranch class.
 * @package GoTableaux
 */

namespace GoTableaux\Proof\TableauBranch\Modal;

use \GoTableaux\Sentence as Sentence;
use \GoTableaux\Proof\TableauNode as Node;
use \GoTableaux\Proof\TableauNode\Sentence as SentenceNode;
use \GoTableaux\Proof\TableauNode\Sentence\Modal\ManyValued as MVMSentenceNode;

/**
 * Represents a tableau branch with designation markers for a many-valued 
 * modal logic.
 * @package GoTableaux
 */
class ManyValued extends \GoTableaux\Proof\TableauBranch\Modal
{
	/**
	 * Holds the designated nodes.
	 * @var array
	 * @access private
	 */
	protected $designatedNodes = array();
	
	/**
	 * Adds a sentence node to the branch.
	 *
	 * @param Sentence $sentence The sentence to place on the node.
	 * @param integer $i The world index to place on the node.
	 * @param boolean $isDesignated The designation flag of the node.
	 * @return ManyValuedModalBranch Current instance.
	 */
	public function createSentenceNodeAtIndexWithDesignation( Sentence $sentence, $i, $isDesignated )
	{
		$this->_addNode( new MVMSentenceNode( $sentence, $i, $isDesignated ));		
		return $this;
	}
	
	/**
	 * Checks whether a sentence node with the given attributes is on the branch.
	 *
	 * @param Sentence $sentence The sentence to search for.
	 * @param integer $i The world index of the node.
	 * @param boolean $isDesignated The designation flag of the node.
	 * @return boolean Whether such a node is on the branch.
	 */
	public function hasSentenceAtIndexWithDesignation( Sentence $sentence, $i, $isDesignated )
	{
		foreach ( $this->getSentenceNodes() as $node )
			if ( 
				$node->getSentence() 	=== $sentence 	&& 
				$node->getI() 			=== $i 			&& 
				$node->isDesignated() 	=== $isDesignated
			) return true;
		return false;
	}
	
	/**
	 * Gets all designated sentence nodes on the branch.
	 *
	 * @param boolean $untickedOnly Whether to limit results to unticked nodes.
	 *								Default is false.
	 * @return array Array of {@link ManyValuedModalSentenceNode}s.
	 */
	public function getDesignatedNodes( $untickedOnly = false )
	{
		if ( !$untickedOnly ) return $this->designatedNodes;
		return Utilities::arrayDiff( $this->designatedNodes, $this->getTickedNodes() );
	}
	
	/**
	 * Gets all undesignated sentence nodes on the branch.
	 * 
	 * @param boolean $untickedOnly Whether to limit results to unticked nodes.
	 *								Default is false.
	 * @return array Array of {@link ManyValuedModalSentenceNode}s.
	 */
	public function getUndesignatedNodes( $untickedOnly = false )
	{
		return Utilities::arrayDiff( $this->getSentenceNodes( $untickedOnly ), $this->designatedNodes );
	}
	
	/**
	 * @access private
	 */
	protected function _addNode( Node $node )
	{
		if ( $node instanceof SentenceNode && $node->isDesignated() )
			$this->designatedNodes[] = $node;
		return parent::_addNode( $node );
	}
	
	/**
	 * @access private
	 */
	public function _removeNode( Node $node )
	{
		$key = array_search( $node, $this->designatedNodes, true );
		if ( $key !== false ) array_splice( $this->designatedNodes, $key, 1 );
		return parent::_removeNode( $node );
	}
}