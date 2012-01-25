<?php
/**
 * Defines the ManyValuedModalBranch class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link ModalBranch} parent class.
 */
require_once dirname( __FILE__) . '/ModalBranch.php';

/**
 * Loads the {@link ManyValuedModalSentenceNode} node class.
 */
require_once dirname( __FILE__) . '/../Node/ManyValuedModalSentenceNode.php';

/**
 * Represents a tableau branch with designation markers for a many-valued 
 * modal logic.
 * @package Tableaux
 * @author Douglas Owings
 */
class ManyValuedModalBranch extends ModalBranch
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
		$this->_addNode( new ManyValuedModalSentenceNode( $sentence, $i, $isDesignated ));		
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