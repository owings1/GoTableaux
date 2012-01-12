<?php
/**
 * Defines the ManyValuedBranch class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link Branch} parent class.
 */
require_once 'GoTableaux/Logic/ProofSystem/Tableaux/Branch.php';

/**
 * Loads the {@link ManyValuedSentenceNode} node class.
 * @see ManyValuedBranch::createNode()
 */
require_once 'GoTableaux/Logic/ProofSystem/Tableaux/Node/ManyValuedSentenceNode.php';

/**
 * Represents a many-valued logic tableau branch.
 * @package Tableaux
 * @author Douglas Owings
 */
class ManyValuedBranch extends Branch
{
	/**
	 * Holds the designated nodes
	 * @var array
	 * @access private
	 */
	protected $designatedNodes = array();
	
	/**
	 * Creates a node on the branch.
	 *
	 * @param Sentence $sentence The sentence to place on the node.
	 * @param boolean $isDesignated The designation marker for the node.
	 * @return ManyValuedBranch Current instance.
	 */
	public function createNodeWithDesignation( Sentence $sentence, $isDesignated )
	{
		return $this->_addNode( new ManyValuedSentenceNode( $sentence, $isDesignated ));
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
		foreach ( $this->getNodes() as $node )
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
		if ( !$untickedOnly ) return $this->designatedNodes;
		return Utilities::arrayDiff( $this->designatedNodes, $this->getTickedNodes() );
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
		return Utilities::arrayDiff( $this->getSentenceNodes( $untickedOnly ), $this->designatedNodes );
	}
	
	/**
	 * Gets all nodes that have a certain operator name and designation.
	 *
	 * @param string $operatorName The name of the operator.
	 * @param boolean $isDesignated Whether the nodes should be designated.
	 * @return array Array of {@link ManyValuedSentenceNode}s.
	 */
	public function getNodesByOperatorNameAndDesignation( $operatorName, $isDesignated )
	{
		$nodes = array();
		$searchNodes = $this->getNodesByOperatorName( $operatorName );
		foreach ( $searchNodes as $node )
			if ( $node->isDesignated() === $isDesignated ) $nodes[] = $node;
		return $nodes;
	}
	
	/**
	 * Gets all nodes that have a two operator names and a certain designation.
	 *
	 * @param string $operatorName The name of the operator.
	 * @param boolean $isDesignated Whether the nodes should be designated.
	 * @return array Array of {@link ManyValuedSentenceNode}s.
	 * @see Branch::getNodesByTwoOperatorNames()
	 */
	public function getNodesByTwoOperatorNamesAndDesignation( $firstOperatorName, $secondOperatorName, $isDesignated )
	{
		$nodes = array();
		$searchNodes = $this->getNodesByTwoOperatorNames( $firstOperatorName, $secondOperatorName );
		foreach ( $searchNodes as $node )
			if ( $node->isDesignated() === $isDesignated ) $nodes[] = $node;
		return $nodes;
	}
	
	/**
	 * @access private
	 */
	protected function _addNode( Node $node )
	{
		if ( $node->isDesignated() ) $this->designatedNodes[] = $node;
		return parent::_addNode( $node );
	}
	
	/**
	 * @access private
	 */
	protected function _removeNode( Node $node )
	{
		$key = array_search( $node, $this->designatedNodes, true );
		if ( $key !== false ) array_splice( $this->designatedNodes, $key, 1 );
		return parent::_removeNode( $node );
	}
	
}