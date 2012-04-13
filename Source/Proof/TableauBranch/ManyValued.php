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
 * Defines the ManyValuedBranch class.
 * @package GoTableaux
 */

namespace GoTableaux\Proof\TableauBranch;

use \GoTableaux\Utilities as Utilities;
use \GoTableaux\Sentence as Sentence;
use \GoTableaux\Proof\TableauNode as Node;
use \GoTableaux\Proof\TableauNode\Sentence\ManyValued as ManyValuedSentenceNode;

/**
 * Represents a many-valued logic tableau branch.
 * @package GoTableaux
 */
class ManyValued extends \GoTableaux\Proof\TableauBranch
{
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