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
 * Defines the ManyValuedNode interface.
 * @package GoTableaux
 */

namespace GoTableaux\Proof\TableauNode;

use \GoTableaux\Exception\Tableau as TableauException;

/**
 * Signifies a many-valued tableau node that has a designation marker.
 * @package GoTableaux
 */
class ManyValued extends \GoTableaux\Proof\TableauNode
{
	/**
	 * Holds the designation
	 * @var boolean
	 */
	private $designated;
	
	/**
	 * Sets the node properties.
	 *
	 * @param array $properties The properties.
	 * @throws TableauException when no designation is given.
	 */
	public function setProperties( array $properties )
	{
		$this->node->setProperties( $properties );
		if ( !isset( $properties['designated'] )) 
			throw new TableauException( 'Must set designation when creating a many-valued node.' );
		$this->setDesignation( $properties['designated'] );
	}
	
	public function filter( array $conditions )
	{
		if ( !$this->node->filter( $conditions )) return false;
		return !isset( $conditions['designated' ] ) || $this->isDesignated() === $conditions['designated'];
	}
	
	/**
	 * Returns whether the node is designated.
	 *
	 * @return boolean Whether the node is designated.
	 */
	public function isDesignated()
	{
		return $this->designated;
	}
	
	/**
	 * Sets the designation of the node.
	 *
	 * @param boolean $isDesignated The designation.
	 * @return ManyValued Current instance.
	 */
	public function setDesignation( $isDesignated )
	{
		$this->designated = (bool) $isDesignated;
	}
}