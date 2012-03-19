<?php
/**
 * Defines the ManyValuedNode interface.
 * @package Tableaux
 * @author Douglas Owings
 */

namespace GoTableaux\Proof\TableauNode;

/**
 * Signifies a many-valued tableau node that has a designation marker.
 * @package Tableaux
 * @author Douglas Owings
 */
interface ManyValued
{
	/**
	 * Returns whether the node is designated.
	 *
	 * @return boolean Whether the node is designated.
	 */
	public function isDesignated();
}