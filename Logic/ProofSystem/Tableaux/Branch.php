<?php
/**
 * Defines the Branch class.
 * @package Tableaux
 * @author Douglas Owings
 */

namespace GoTableaux;

/**
 * Represents a tableau branch.
 * @package Tableaux
 * @author Douglas Owings
 */
class Branch
{
	/**
	 * Holds the {@link Node nodes} of the branch.
	 * @var array
	 * @access private
	 */
	protected $nodes = array();
	
	/**
	 * Holds the ticked {@link Node}s.
	 * @var array
	 * @access private
	 */
	protected $tickedNodes = array();
	
	/**
	 * Holds the {@link SentenceNode}s of the branch.
	 * @var array
	 * @access private
	 */
	protected $sentenceNodes = array();
	
	/**
	 * Tracks whether the branch is closed.
	 * @var boolean
	 * @access private
	 */
	protected $closed = false;
	
	/**
	 * Holds a reference to the tableau.
	 * @var Tableau
	 * @access private
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
	 * @return array Array of {@link Node}s.
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
		if ( !$untickedOnly ) return $this->sentenceNodes;
		return Utilities::arrayDiff( $this->sentenceNodes, $this->getTickedNodes() );
	}
	
	/**
	 * Checks whether a sentence is on the branch.
	 *
	 * @param Sentence $sentence The sentence to search for.
	 * @return boolean Whether the branch has a node with that sentence.
	 */
	public function hasNodeWithSentence( Sentence $sentence )
	{
		foreach ( $this->getSentenceNodes() as $node )
			if ( $node->getSentence() === $sentence ) return true;
		return false;
	}
	
	/**
	 * Gets all nodes on the branch that are unticked relative to the branch.
	 *
	 * @return array Array of {@link Node}s.
	 */
	public function getUntickedNodes()
	{
		return Utilities::arrayDiff( $this->getNodes(), $this->getTickedNodes() );
	}
	
	/**
	 * Gets all nodes on the branch that are ticked relative to the branch.
	 *
	 * @return array Array of {@link Node}s.
	 */
	public function getTickedNodes()
	{
		return $this->tickedNodes;
	}
	
	/**
	 * Closes the branch.
	 *
	 * @return Branch Current instance.
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
	 * Checks whether a node is on the branch.
	 *
	 * @param Node $node The node to check.
	 * @return boolean Whether the node is on the branch.
	 */
	public function hasNode( Node $node )
	{
		return in_array( $node, $this->getNodes(), true );
	}
	
	/**
	 * Clones the branch. Maintains references to the nodes.
	 *
	 * @return Branch The new copy.
	 */
	public function copy()
	{
		$newBranch = clone $this;
		return $newBranch;
	}
	
	/**
	 * Branches the branch.
	 *
	 * Copies the branch, attaches the copy to the tableau, and returns the new
	 * branch.
	 *
	 * @return Branch The new branch
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
		$nodes = array();
		foreach ( $this->getSentenceNodes( $untickedOnly ) as $node ) 
			if ( $node->getSentence()->getOperatorName() === $operatorName ) $nodes[] = $node;
		return $nodes;
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
	
	/**
	 * Ticks a node relative to the branch.
	 *
	 * @param Node $node The node to tick.
	 * @return Branch Current instance.
	 */
	public function tickNode( Node $node )
	{
		if ( !in_array( $node, $this->tickedNodes, true ))
			$this->tickedNodes[] = $node;
		return $this;
	}

	/**
	 * Adds a node to the branch.
	 *
	 * @param Node $node The node to add.
	 * @return Branch Current instance.
	 */
	protected function _addNode( Node $node )
	{
		if ( $node instanceof SentenceNode ) {
			$sentence = $this->getTableau()
							 ->getProofSystem()
							 ->getLogic()
							 ->getVocabulary()
							 ->registerSentence( $node->getSentence() );
			$node->setSentence( $sentence );
			$this->sentenceNodes[] = $node;
		}
		$this->nodes[] = $node;
		return $this;
	}
	
	/**
	 * Removes all references to a node from the branch.
	 *
	 * @param Node $node The node to remove. If the node is on the branch in
	 *					 multiple places, each reference is removed.
	 * @return Branch Current instance.
	 */
	public function _removeNode( Node $node )
	{
		$key = array_search( $node, $this->sentenceNodes, true );
		if ( $key !== false ) array_splice( $this->sentenceNodes, $key, 1 );
		$key = array_search( $node, $this->nodes, true );
		if ( $key !== false ) array_splice( $this->nodes, $key, 1 );
		$key = array_search( $node, $this->tickedNodes, true );
		if ( $key !== false ) array_splice( $this->tickedNodes, $key, 1 );
		return $this;
	}
	
}