<?php
/**
 * Defines the Node base class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Represents a node on a branch.
 * @package Tableaux
 * @author Douglas Owings
 */
abstract class Node
{
	/**
	 * Holds the branches relative to which the node is ticked.
	 * @var array Array of Branch objects.
	 * @access private
	 */
	protected $tickedBranches = array();
	
	/**
	 * Ticks the node relative to a branch.
	 *
	 * @param Branch $branch The branch relative to which to tick the
	 *								  node.
	 * @return Node Current instance.
	 */
	public function tickAtBranch( Branch $branch )
	{
		if ( !in_array( $branch, $this->tickedBranches, true ))
			$this->tickedBranches[] = $branch;
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
		return in_array( $branch, $this->tickedBranches, true );
	}
	
	/**
	 * Alias of Node::tickAtBranch()
	 *
	 * @param Branch $branch
	 * @return Node
	 */
	public function tick( Branch $branch )
	{
		return $this->tickAtBranch( $branch );
	}
	
	/**
	 * Alias of Node::isTickedAtBranch()
	 * @param Branch $branch
	 * @return boolean 
	 */
	public function ticked( Branch $branch )
	{
		return $this->isTickedAtBranch( $branch );
	}
}