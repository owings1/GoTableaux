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
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program in file LICENSE.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * Defines the ManyValuedModalSentenceNode class.
 * @package GoTableaux
 */

namespace GoTableaux\Proof\TableauNode\Sentence\Modal;

/**
 * Represents a sentence node on a branch of a many-valued modal logic tableau.
 * @package GoTableaux
 */
class ManyValued extends \GoTableaux\Proof\TableauNode\Sentence\Modal implements GoTableaux\Proof\TableauNode\ManyValued
{
	/**
	 * Holds the designation flag.
	 * @var boolean
	 * @access private
	 */
	protected $isDesignated;
	
	/**
	 * Constructor.
	 *
	 * Sets the sentence, index, and designation flag.
	 *
	 * @param Sentence $sentence The sentence to place on the node.
	 * @param integer $i The "world" index of the node.
	 * @param boolean $isDesignated Whether the sentence is designated at $i.
	 */
	public function __construct( \GoTableaux\Sentence $sentence, $i, $isDesignated )
	{
		parent::__construct( $sentence, $i );
		$this->setDesignation( $isDesignated );
	}
	
	/**
	 * Sets the designation flag.
	 *
	 * @param boolean $isDesignated Whether the sentence is designated at the 
	 *								world index of the node.
	 * @return DesignationModalSentenceNode Current instance.
	 */
	public function setDesignation( $isDesignated )
	{
		$this->isDesignated = (bool) $isDesignated;
		return $this;
	}
	
	/**
	 * Gets whether the sentence is designated at the world index.
	 *
	 * @return boolean Whether the sentence is designated at the world index.
	 */
	public function isDesignated()
	{
		return $this->isDesignated;
	}
}