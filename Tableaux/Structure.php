<?php
/**
 * Defines the Structure class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * 	Represents the proper 'tree' structure of a tableau. This is a simple
 *  composition structure class which takes a Tableau object and creates a tree
 *  structure from its branches. Thinking of a branch as a tuple of nodes, a 
 *  single node can appear on more than one branch. This is helpful in applying 
 *  rules. This class collapses common nodes into one structure. A structure 
 *  comprises an array of Node objects, and an array of structures.
 *
 * @package Tableaux
 * @author Douglas Owings
 */
class Structure
{
	/**
	 * @var Tableau
	 * @access private
	 */
	protected $tableau;
	
	/**
	 * @var array Array of {@link Node} objects.
	 * @access private
	 */
	protected $nodes = array();
	
	/**
	 * @var array Array of {@link Node} objects.
	 * @access private
	 */
	protected $tickedNodes = array();
	
	/**
	 * @var array Array of self-similar objects.
	 * @access private
	 */
	protected $structures = array();
	
	/**
	 * @var boolean
	 * @access private
	 */
	protected $closed = false;
				
	/**
	 * Creates an instance from a tableau.
	 *
	 * @param Tableau $tableau The tableau whose structure to represent.
	 * @return Structure New instance.
	 */
	public static function getInstance( Tableau $tableau )
	{
		$instance = new self();
		$instance->setTableau( $tableau );
		return $instance;
	}
	
	/**
	 * Subtracts one array from another, reference strict.
	 *
	 * @param array $a The first array.
	 * @param array $b The second array.
	 * @return array An array of everything in $a that is not also in $b.
	 */
	protected static function subtract( array $a, array $b )
	{
		$retArr = array();
		foreach ( $a as $v )
			if ( ! in_array( $v, $b, true )) $retArr[] = $v;
		return $retArr;
	}
	
	/**
	 * Sets the tableau to represent.
	 *
	 * @param Tableau $tableau The tableau whose structure to represent.
	 * @return Structure Current instance, for chaining.
	 */
	public function setTableau( Tableau $tableau )
	{
		$this->tableau = $tableau;
		return $this;
	}
	
	/**
	 * Builds the tree structure.
	 *
	 * @return Structure Current instance, for chaining.
	 */
	public function build()
	{
		$this->structurize( $this->tableau->getBranches() );
		return $this;
	}
	
	/**
	 * Gets the nodes of the current structure.
	 *
	 * @return array Array of {@link Node} objects.
	 */
	public function getNodes()
	{
		return $this->nodes;
	}
	
	/**
	 * Gets the child structures of the current structure.
	 *
	 * @return array Array of Structure objects.
	 */
	public function getStructures()
	{
		return $this->structures;
	}
	
	/**
	 * Checks whether a node is ticked relative to the current structure.
	 *
	 * @param Node $node The node to check.
	 * @return boolean Whether the node is ticked.
	 */
	public function nodeIsTicked( Node $node )
	{
		return in_array( $node, $this->tickedNodes, true );
	}
	
	/**
	 * Checks whether the structure is closed.
	 *
	 * @return boolean Whether the structure is closed.
	 */
	public function isClosed()
	{
		return $this->closed;
	}
	
	/**
	 * Recursive structurizing function.
	 *
	 * @param array $b
	 * @return void
	 */
	protected function structurize( array $b )
	{
		if ( empty( $b )) return;
		
		$branches = $b;
		
		// get nodes that are common to branches
		$nodes = Branch::getCommonNodes( $branches );
		
		foreach ( $nodes as $node ){
			$ticked = false;
			foreach ( $branches as $branch )
				$ticked |= $node->isTickedAtBranch( $branch );
				
			if ( $ticked ) $this->tickedNodes[] = $node;
		}
		// remove nodes from branches
		foreach ( $nodes as $node )
			foreach ( $branches as $branch ) $branch->removeNode( $node );

		// assign nodes to structure
		$this->nodes = $nodes;
		
		if ( count( $branches ) > 1 ) {
			while ( ! empty( $branches )) {
				// grab first node from a branch
				$branch = $branches[0];
				$n 		= $branch->getNodes();
				$node 	= $n[0];
				
				// group branches that have that node
				$group = Branch::getBranchesWithNode( $branches, $node );
				
				// remove them from $branches
				$branches = self::subtract( $branches, $group );
				
				// add group to sub structures
				$structure = new self();
				$this->structures[] = $structure;
				
				// structurize branches
				$structure->structurize( $group );
			}
		} else {
			$b = $branches[0];
			if ( $b->isClosed() ) $this->closed = true;
		}	
	}
}