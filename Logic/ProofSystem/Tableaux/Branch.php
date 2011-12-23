<?php
/**
 * Defines the Branch class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Represents a tableau branch.
 * @package Tableaux
 * @author Douglas Owings
 */
class Branch
{
	/**
	 * Holds the nodes of the branch.
	 * @var array Array of {@link Node} objects.
	 * @access private
	 */
	protected $nodes = array();
	
	/**
	 * Tracks whether the branch is closed.
	 * @var boolean
	 * @access private
	 */
	protected $closed = false;
	
	/**
	 * Gets all nodes that are on each of an array of branches.
	 *
	 * @param array $branches Array of {@link Branch branches}.
	 * @return array Array of common {@link Node nodes}. 
	 */
	public static function getCommonNodes( array $branches )
	{
		$nodes = $commonNodes = array();
		foreach ( $branches as $branch ) {
			if ( !$branch instanceof Branch ) 
				throw new TableauxException();
			$nodes = array_merge( $nodes, $branch->getNodes() );
		}
		foreach ( $nodes as $node ) {
			$isCommon = true;			
			foreach ( $branches as $branch )
				if ( ! $branch->hasNode( $node )) {
					$isCommon = false;
					break;
				}
			if ( $isCommon ) $commonNodes[] = $node;
		}
		return array_unique( $commonNodes );
	}
	
	/**
	 * Gets all branches that have a particular node on them.
	 *
	 * @param array $branches Array of {@link Branch branches}.
	 * @param Node $node The node to search for.
	 * @return array Array of branches.
	 */
	public static function getBranchesWithNode( array $branches, Node $node )
	{
		$b = array();
		foreach ( $branches as $branch )
			if ( $branch->hasNode( $node )) $b[] = $branch;
		return $b;
	}
	
	/**
	 * Adds a node to the branch.
	 *
	 * @param Node|array $node The node or array of nodes to add.
	 * @return Branch Current instance.
	 */
	public function addNode( $nodes )
	{
		if ( is_array( $nodes )) foreach ( $nodes as $node ) $this->_addNode( $node );
		else $this->_addNode( $nodes );
		return $this;
	}
	
	/**
	 * Removes all reference of a node from the branch.
	 *
	 * @param Node $node The node to remove. If the node is on the 
	 *							  branch in multiple places, each reference is
	 *							  removed.
	 * @return Branch Current instance.
	 */
	public function removeNode( Node $node )
	{
		$nodes = array();
		foreach ( $this->nodes as $oldNode )
			if ( $node !== $oldNode ) $nodes[] = $oldNode;
		$this->nodes = $nodes;
		return $this;
	}
	
	/**
	 * Gets the nodes on the branch.
	 *
	 * @return array Array of {@link Node nodes}.
	 */
	public function getNodes()
	{
		return $this->nodes;
	}
	
	/**
	 * Gets all nodes on the branch that are unticked relative to the branch.
	 *
	 * @return array Array of {@link Node nodes}.
	 */
	public function getUntickedNodes()
	{
		$nodes = array();
		foreach ( $this->nodes as $node )
			if ( ! $node->ticked( $this )) $nodes[] = $node;
		return $nodes;
	}
	
	/**
	 * Gets all nodes on the branch that are ticked relative to the branch.
	 *
	 * @return array Array of Node objects.
	 */
	public function getTickedNodes()
	{
		$nodes = array();
		foreach ( $this->nodes as $node )
			if ( $node->ticked( $this )) $nodes[] = $node;
		return $nodes;
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
		return in_array( $node, $this->nodes, true );
	}
	
	/**
	 * Clones the branch. Maintains references to the nodes.
	 *
	 * @return Branch The new copy.
	 */
	public function copy()
	{
		$newBranch = clone $this;
		foreach ( $this->getTickedNodes() as $node )
			$node->tick( $newBranch );
		return $newBranch;
	}
	
	/**
	 * Adds a node to the branch.
	 *
	 * @param Node $node The node to add.
	 * @return void
	 */
	protected function _addNode( Node $node )
	{
		$this->nodes[] = $node;
	}
}