<?php
 /**
  * 	Structure
  *
  *		Simple composition structure class which takes a Tableau object and creates a tree structure from its branches.
  *
  *		Thinking of a branch as a tuple of nodes, a single node can appear on more than one branch. This is helpful in applying rules.
  *		This class collapses common nodes into one structure. A structure comprises an array of Node objects, and an array of structures.
  *		
  */
class Tableaux_Structure
{
	protected	$tableau;
	
	protected 	$nodes = array(),
				$tickedNodes = array(),
				$structures = array();
				
	protected	$closed = false;
				
	
	public static function getInstance( Tableaux_Tableau $tableau )
	{
		$instance = new self();
		$instance->setTableau( $tableau );
		return $instance;
	}
	public function setTableau( Tableaux_Tableau $tableau )
	{
		$this->tableau = $tableau;
	}
	public function build()
	{
		$this->structurize( $this->tableau->getBranches() );
	}
	public function getNodes()
	{
		return $this->nodes;
	}
	public function getStructures()
	{
		return $this->structures;
	}
	public function ticked( Tableaux_Node $node )
	{
		return in_array( $node, $this->tickedNodes, true );
	}
	private function structurize( array $b )
	{
		if ( empty( $b )){
			return;
		}
		
		$branches = $b;
		
		// get nodes that are common to branches
		$nodes = Tableaux_Branch::getCommonNodes( $branches );
		
		foreach ( $nodes as $node ){
			$ticked = false;
			foreach ( $branches as $branch ){
				if ( $node->ticked( $branch )){
					$ticked = true;
				}
			}
			if ( $ticked ){
				$this->tickedNodes[] = $node;
			}
		}
		// remove nodes from branches
		foreach ( $nodes as $node ){
			foreach ( $branches as $branch ){
				$branch->removeNode( $node );
			}
		}
		// assign nodes to structure
		$this->nodes = $nodes;
		
		if ( count( $branches ) > 1 ){
			
			while ( ! empty( $branches )){
				// grab first node from a branch
				$branch = $branches[0];
				$n = $branch->getNodes();
				$node = $n[0];
				
				// group branches that have that node
				$group = Tableaux_Branch::getBranchesWithNode( $branches, $node );
				
				// remove them from $branches
				$branches = self::subtract( $branches, $group );
				
				// add group to sub structures
				$structure = new self();
				$this->structures[] = $structure;
				
				// structurize branches
				$structure->structurize( $group );
				
				
			}
		}
		else{
			$b = $branches[0];
			if ( $b->isClosed() ){
				$this->closed = true;
			}
		}
		
	}
	public function isClosed()
	{
		return $this->closed;
	}
	protected static function subtract( array $a, array $b )
	{
		// subtracts b from a
		$retArr = array();
		foreach ( $a as $v ){
			if ( ! in_array( $v, $b, true )){
				$retArr[] = $v;
			}
		}
		return $retArr;
	}
}
?>