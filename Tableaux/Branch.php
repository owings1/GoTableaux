<?php
require_once 'Doug/SimpleNotifier.php';
class Tableaux_Branch
{
	protected 	$nodes = array(),
				$closed = false;
	
	function addNode( Tableaux_Node $node )
	{
		$this->nodes[] = $node;
	}
	function removeNode( Tableaux_Node $node )
	{
		$nodes = array();
		foreach ( $this->nodes as $oldNode ){
			if ( $node !== $oldNode ){
				$nodes[] = $oldNode;
			}
		}
		$this->nodes = $nodes;
	}
	function getNodes()
	{
		return $this->nodes;
	}
	function getUntickedNodes()
	{
		$n = new Doug_SimpleNotifier( 'Branch' );
		//$n->notify( 'getting unticked nodes' );
		$nodes = array();
		foreach ( $this->nodes as $node ){
			if ( ! $node->ticked( $this ) ){
				$nodes[] = $node;
			}
		}
		//$n->notify( count( $nodes ) . ' unticked nodes found' );
		return $nodes;
	}
	function getTickedNodes()
	{
		$nodes = array();
		foreach ( $this->nodes as $node ){
			if ( $node->ticked( $this ) ){
				$nodes[] = $node;
			}
		}
		return $nodes;
	}	
	function close()
	{
		$this->closed = true;
	}
	function isClosed()
	{
		return $this->closed;
	}
	function hasNode( Tableaux_Node $node )
	{
		return in_array( $node, $this->nodes, true );
	}
	function copy()
	{
		$newBranch = clone $this;
		foreach ( $this->getTickedNodes() as $node ){
			$node->tick( $newBranch );
		}
		return $newBranch;
	}
	public static function getCommonNodes( array $branches )
	{
		$nodes = array();
		foreach ( $branches as $branch ){
			if ( ! $branch instanceof Tableaux_Branch ){
				throw new Exception();
			}
			$nodes = array_merge( $nodes, $branch->getNodes() );
		}
		$commonNodes = array();
		foreach ( $nodes as $node ){
			// Assume commonality
			$common = true;
			
			foreach ( $branches as $branch ){
				if ( ! $branch->hasNode( $node )){
					$common = false;
				}
			}
			if ( $common ){
				$commonNodes[] = $node;
			}
		}
		return array_unique( $commonNodes );
	}
	static function getBranchesWithNode( array $branches, Tableaux_Node $node )
	{
		$b = array();
		foreach ( $branches as $branch ){
			if ( $branch->hasNode( $node )){
				$b[] = $branch;
			}
		}
		return $b;
	}
}
?>