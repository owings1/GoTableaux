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
 * Defines the ManyValuedSentenceNode class.
 * @package GoTableaux
 */

namespace GoTableaux\Proof\TableauNode\Sentence;

use \GoTableaux\Exception\Tableau as TableauException;

/**
 * Represents a sentence node on a branch of a many-valued logic tableau.
 * @package GoTableaux
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
	
	/**
	 * Sets the node properties.
	 * @param array $properties The properties.
	 * @return void
	 * @throws TableauException when no designation is given.
	 */
	public function setProperties( array $properties )
	{
		parent::setProperties( $properties );
		if ( !isset( $properties['designated'] )) 
			throw new TableauException( 'Must set designation when creating a many valued sentence node.' );
		$this->setDesignation( $properties['designated'] );
	}
}