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
 * Loads the {@link ManyValuedModalSentenceNode} node class.
 */
require_once 'GoTableaux/Logic/ProofSystem/Tableaux/Node/ManyValuedModalSentenceNode.php';

/**
 * Represents a tableau branch with designation markers for a many-valued 
 * modal logic.
 * @package Tableaux
 * @author Douglas Owings
 */
class ManyValuedModalBranch extends ModalBranch
{
	/**
	 * Adds a sentence node to the branch.
	 *
	 * @param Sentence $sentence The sentence to place on the node.
	 * @param integer $i The world index to place on the node.
	 * @param boolean $isDesignated The designation flag of the node.
	 * @return ManyValuedModalBranch Current instance.
	 */
	public function addSentenceNode( Sentence $sentence, $i, $isDesignated )
	{
		if ( !$this->hasSentenceNode( $sentence, $i, $isDesignated )) {
			$sentence = $this->registerSentence( $sentence );
			$this->addNode( new ManyValuedModalSentenceNode( $sentence, $i, $isDesignated ));
		}		
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
	public function hasSentenceNode( Sentence $sentence, $i, $isDesignated )
	{
		foreach ( $this->getSentenceNodes() as $node )
			if ( $node->getSentence === $sentence && 
				 $node->getI() === $i && 
				 $node->isDesignated() === $isDesignated
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
		$nodes = array();
		foreach ( $this->getSentenceNodes() as $node )
			if ( $node->isDesignated() && 
				 !( $untickedOnly && $node->isTickedAtBranch( $this ))
				) $nodes[] = $node;
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
		foreach ( $this->getSentenceNodes() as $node )
			if ( ! $node->isDesignated() && 
				 !( $untickedOnly && $node->isTickedAtBranch( $this )) 
				) $nodes[] = $node;
		return $nodes;
	}
}