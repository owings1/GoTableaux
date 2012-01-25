<?php
/**
 * Defines the ModalNode interface.
 * @package Tableaux
 * @author Douglas Owings
 */

namespace GoTableaux;

/**
 * Signifies a modal tableau node that has at least one index.
 * @package Tableaux
 * @author Douglas Owings
 */
interface ModalNode
{
	/**
	 * Returns the index, or the first index, of a modal node.
	 *
	 * @return integer The index, or first index of the node.
	 */
	public function getI();
}