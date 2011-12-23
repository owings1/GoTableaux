<?php
/**
 * Defines the ModalBranch class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link TableauException} class.
 */
require_once '../TableauException.php';

/**
 * Loads the {@link Branch} parent class.
 */
require_once '../Branch.php';

/**
 * Loads the {@link ModalSentenceNode} node class.
 * @see ModalBranch::createSentenceNode()
 */
require_once 'Node/ModalSentenceNode.php';

/**
 * Loads the {@link AccessNode} node class.
 * @see ModalBranch::createAccessNode()
 */
require_once 'Node/AccessNode.php';

/**
 * Represents a modal logic tableau branch.
 * @package Tableaux
 * @author Douglas Owings
 */
class ModalBranch extends Branch
{
	/**
	 * Holds the access nodes.
	 * @var array Array of {@link AccessNode}s.
	 */
	protected $accessNodes = array();
	
	/**
	 * Holds the sentence nodes.
	 * @var array Array of {@link SentenceNode}s.
	 */
	protected $sentenceNodes = array();
	
	/**
	 * Gets all world indexes that appear in an array of modal access nodes.
	 *
	 * @param array $nodes An array of {@link Node}s to search.
	 * @return array Array of unique integer indexes that occur on the nodes.
	 */
	public static function getIndexesFromNodes( array $nodes )
	{
		$indexes = array();
		foreach ( $nodes as $node ) 
			if ( $node instanceof ModalNode ) $indexes[] = $node->getI();
			if ( $node instanceof AccessNode ) $indexes[] = $node->getJ();
		}
		return array_unique( $indexes );
	}
	
	/**
	 * Adds a new access node for the given two integer indexes.
	 *
	 * @param integer $i The first index.
	 * @param integer $j The second index.
	 * @param boolean $allowDuplicate Whether to add a node even if an exactly
	 *								  similar node is already on the branch.
	 *								  Default is false.
	 * @return ModalBranch Current instance.
	 */
	public function addAccessNode( $i, $j, $allowDuplicate = false )
	{
		if ( $allowDuplicate || !$this->hasAccessNode( $i, $j ))
			$this->addNode( new AccessNode( $i, $j ) );
		return $this;
	}
	
	/**
	 * Gets all modal access nodes on the branch.
	 *
	 * @return array Array of {@link AccessNode}s.
	 */
	public function getAccessNodes()
	{
		return $this->accessNodes;
	}
	
	/**
	 * Gets all modal access nodes that have a given first index.
	 *
	 * @param integer $i The value of the first index of the access nodes to get.
	 * @return array Array of {@link AccessNode}s.
	 */
	protected function getAccessNodesByI( $i )
	{
		$nodes = array();
		foreach ( $this->getAccessNodes() as $node )
			if ( $node->getI() === $i ) $nodes[] = $node;
		return $nodes;
	}
	
	/**
	 * Gets all modal access nodes that have a given second index.
	 *
	 * @param integer $j The value of the second index of the access nodes to get.
	 * @return array Array of {@link AccessNode}s.
	 */
	protected function getAccessNodesByJ( $j )
	{
		$nodes = array();
		foreach ( $this->getAccessNodes() as $node )
			if ( $node->getJ() === $j ) $nodes[] = $node;
		return $nodes;
	}
	
	/**
	 * Get all modal access nodes whose first and second indexes are equal.
	 *
	 * @return array Array of {@link AccessNodes}s.
	 */
	public function getReflexiveNodes()
	{
		$nodes = array();
		foreach ( $this->getAccessNodes() as $node )
			if ( $node->getI() === $node->getJ() ) $nodes[] = $node;
		return $nodes;
	}
	
	/**
	 * Checks for existence of access node with given indexes.
	 *
	 * @param integer $i The first index.
	 * @param integer $j The second index.
	 * @return boolean Whether such an access node is on the branch.
	 */
	public function hasAccessNode( $i, $j )
	{
		foreach ( $this->getAccessNodes() as $node )
			if ( $node->getI() === $i && $node->getJ() === $j ) return true;
		return false;
	}
	
	/**
	 * Gets all world indexes that appear on the branch.
	 *
	 * @return array Array of unique integer indexes that appear on the branch.
	 */
	public function getAllIndexes()
	{
		return self::getIndexesFromNodes( $this->getNodes() );
	}
	
	/**
	 * Gets all world indexes that "see" themselves.
	 *
	 * @return array Array of unique integer indexes that have reflexive nodes.
	 */
	public function getReflexiveIndexes()
	{
		$indexes = array();
		foreach ( $this->getAllIndexes() as $index )
			if ( $this->hasAccessNode( $index, $index )) $indexes[] = $index;
		return array_unique( $indexes );
	}
	
	/**
	 * Gets all the second indexes of access nodes by a particular first index.
	 *
	 * @param integer $i The first index to search for.
	 * @return array Array of unique second index integers.
	 */
	public function getAccessedIndexes( $i )
	{
		$indexes = array();
		foreach ( $this->getAccessNodesByI( $i ) as $node )
			$js[] = $node->getJ();
		return array_unique( $js );
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
		foreach ( $this->getAccessNodes() as $node )
			if ( $node->getI() === $i && $node->getJ() === $j ) return true;
		return false;
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
		foreach ( $this->getAccessedIndexes( $i ) as $indexA )
			foreach ( $this->getAccessedIndexes( $indexA ) as $indexB )
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
	 * @param boolean $allowDuplicate Whether to allow a duplicate node being
	 *								  added. Default is true.
	 * @return ModalBranch Current instance.
	 */
	public function addSentenceNode( Sentence $sentence, $i, $allowDuplicate = true )
	{
		if ( $allowDuplicate || !$this->hasSentenceNode( $sentence, $i ))
			$this->addNode( new ModalSentenceNode( $sentence, $i ));
		return $this;
	}
	
	/**
	 * Gets all modal sentence nodes on the branch.
	 *
	 * @param boolean $untickedOnly Whether to limit search to nodes that are unticked.
	 * @return array Array of {@link ModalSentenceNode}s.
	 */
	public function getSentenceNodes( $untickedOnly = false )
	{
		return $this->sentenceNodes;
	}
	
	/**
	 * Checks for existence of sentence node with given sentence and index.
	 *
	 * @param Sentence $sentence The sentence to search for.
	 * @param integer $i The index to search for.
	 * @return boolean Whether such a sentence node is on the branch.
	 */
	public function hasSentenceNode( Sentence $sentence, $i )
	{
		foreach ( $this->getSentenceNodes() as $node )
			if ( $node->getSentence === $sentence && $node->getI() === $i ) return true;
		return false;
	}
	
	/**
	 * Adds a node to the branch.
	 *
	 * @param Node $node The node to add.
	 * @return void
	 */
	protected function addNode( Node $node )
	{
		if ( $node instanceof SentenceNode ) $this->sentenceNodes[] = $node;
		elseif ( $node instanceof AccessNode ) $this->accessNodes[] = $node;
		parent::addNode( $node );
	}
	
	/**
	 * Removes a node from the branch.
	 * 
	 * @param Node $node The node to remove.
	 * @return void
	 */
	protected function removeNode( Node $node )
	{
		if ( $node instanceof SentenceNode ) {
			$sentenceNodes = array();
			foreach ( $this->sentenceNodes as $sentenceNode )
				if ( $node !== $sentenceNode ) $sentenceNodes[] = $node;
			$this->sentenceNodes = $sentenceNodes;
		} elseif ( $node instanceof AccessNode ) {
			$accessNodes = array();
			foreach ( $this->accessNodes as $accessNode )
				if ( $node !== $accessNode ) $accessNodes[] = $node;
			$this->accessNodes = $accessNodes;
		}
		parent::removeNode( $node );
	}
}