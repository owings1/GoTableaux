<?php
/**
 * Defines the ManyValuedNode interface.
 * @package Tableaux
 * @author Douglas Owings
 */

namespace GoTableaux;

/**
 * Signifies a many-valued tableau node that has a designation marker.
 * @package Tableaux
 * @author Douglas Owings
 */
interface ManyValuedNode
{
	/**
	 * Returns whether the node is designated.
	 *
	 * @return boolean Whether the node is designated.
	 */
	public function isDesignated();
}