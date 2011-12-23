<?php
/**
 * Defines the ManyValuedModalBranch class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link ModalBranch} parent class.
 */
require_once 'ModalBranch.php';

/**
 * Represents a tableau branch with designation markers for a many-valued 
 * modal logic.
 * @package Tableaux
 * @author Douglas Owings
 */
class ManyValuedModalBranch extends ModalBranch
{
	/**
	 * Gets all designated sentence nodes on the branch.
	 *
	 * @param boolean $untickedOnly Whether to limit results to unticked nodes.
	 *								Default is false.
	 * @return array Array of {@link ManyValuedModalSentenceNode}s.
	 */
	public function getDesignatedNodes( $untickedOnly = false )
	{
		$nodes = array();
		foreach ( $this->getSentenceNodes( $untickedOnly ) as $node )
			if ( $node->isDesignated() ) $nodes[] = $node;
		return $nodes;
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
		$nodes = array();
		foreach ( $this->getSentenceNodes( $untickedOnly ) as $node )
			if ( ! $node->isDesignated() ) $nodes[] = $node;
		return $nodes;
	}
	
	/**
	 * Checks for the existence on the branch of a node with particular attributes.
	 *
	 * @param Sentence $sentence The sentence of the nodes.
	 * @param integer $i The world index of the nodes.
	 * @param boolean $isDesignated Whether the node is designated.
	 * @param boolean $untickedOnly Whether to limit search to unticked nodes.
	 *								Default is false.
	 * @return boolean Whether a node with the given attributes appears on the branch.
	 */
	public function hasSentenceNodeWithAttr( Sentence $sentence, $i, $isDesignated, $untickedOnly = false )
	{
		$nodes = ( $isDesignated ) ? $this->getDesignatedNodes( $untickedOnly ) 
								   : $this->getUndesignatedNodes( $untickedOnly );
		foreach ( $nodes as $node ) 
			if ( $node->getSentence() === $sentence && $node->getI() === $i ) return true;
		return false;
	}
}