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
 * Defines the ManyValuedSentenceNode class.
 * @package Tableaux
 */

namespace GoTableaux\Proof\TableauNode\Sentence;

/**
 * Represents a sentence node on a branch of a many-valued logic tableau.
 * @package Tableaux
 */
class ManyValued extends \GoTableaux\Proof\TableauNode\Sentence implements \GoTableaux\Proof\TableauNode\ManyValued
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
	 * Sets the sentence and designation flag.
	 *
	 * @param Sentence $sentence The sentence to place on the node.
	 * @param boolean $isDesignated Whether the sentence is designated at $i.
	 */
	public function __construct( \GoTableaux\Sentence $sentence, $isDesignated )
	{
		parent::__construct( $sentence );
		$this->setDesignation( $isDesignated );
	}
	
	/**
	 * Sets the designation flag.
	 *
	 * @param boolean $isDesignated Whether the sentence is designated at the node.
	 * @return ManyValuedSentenceNode Current instance.
	 */
	public function setDesignation( $isDesignated )
	{
		$this->isDesignated = (bool) $isDesignated;
		return $this;
	}
	
	/**
	 * Gets whether the sentence is designated at the world index.
	 *
	 * @return boolean Whether the sentence is designated at the node.
	 */
	public function isDesignated()
	{
		return $this->isDesignated;
	}
}