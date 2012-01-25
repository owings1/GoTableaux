<?php
/**
 * Defines the Node base class.
 * @package Tableaux
 * @author Douglas Owings
 */

namespace GoTableaux;

/**
 * Represents a node on a branch.
 * @package Tableaux
 * @author Douglas Owings
 */
abstract class Node
{
	/**
	 * Ticks the node relative to a branch.
	 *
	 * @param Branch $branch The branch relative to which to tick the
	 *								  node.
	 * @return Node Current instance.
	 */
	public function tickAtBranch( Branch $branch )
	{
		$branch->tickNode( $this );
		return $this;
	}
	
	/**
	 * Checks whether the node is ticked relative to a particular branch.
	 *
	 * @param Branch $branch The branch relative to which to check.
	 * @return boolean Whether the node is ticked relative to $branch.
	 */
	public function isTickedAtBranch( Branch $branch )
	{
		return in_array( $this, $branch->getTickedNodes(), true );
	}
}