<?php
/**
 * GoTableaux. A multi-logic tableaux generator.
 * Copyright (C) 2012  Douglas Owings
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed WITHOUT ANY WARRANTY. 
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/agpl-3.0.html>.
 */
/**
 * Defines the ModalSentenceNode class.
 * @package GoTableaux
 */

namespace GoTableaux\Proof\TableauNode\Sentence;

/**
 * Represents a modal sentence tableau node.
 *
 * A modal sentence node has a sentence and a "world" integer index.
 * 
 * @package GoTableaux
 */
class Modal extends \GoTableaux\Proof\TableauNode\Sentence implements \GoTableaux\Proof\TableauNode\Modal
{
	/**
	 * Holds a reference to the "world" index.
	 * @var integer
	 */
	protected $i;
	
	/**
	 * Constructor.
	 *
	 * Sets the sentence by calling the parent constructor, and sets the index.
	 *
	 * @param Sentence $sentence The sentence to place on the node.
	 * @param integer $i The index of the node.
	 */
	public function __construct( \GoTableaux\Sentence $sentence, $i )
	{
		parent::__construct( $sentence );
		$this->setI( $i );
	}
	
	/**
	 * Sets the index.
	 *
	 * @param integer $i The index.
	 * @return ModalSentenceNode Current instance.
	 */
	public function setI( $i )
	{
		$this->i = (int) $i;
	}
	
	/**
	 * Gets the index.
	 *
	 * @return integer The index.
	 */
	public function getI()
	{
		return $this->i;
	}
}
?>