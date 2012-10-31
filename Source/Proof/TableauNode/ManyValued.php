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

use \GoTableaux\Logic as Logic;
use \GoTableaux\Exception\Tableau as TableauException;

/**
 * Signifies a many-valued tableau node that has a designation marker.
 * @package GoTableaux
 */
class ManyValued extends \GoTableaux\Proof\TableauNode
{
	/**
	 * Meta symbol names required by the node.
	 * @var array
	 */
    public static $metaSymbolNames = array( 'designatedMarker', 'undesignatedMarker' );

	/**
	 * States which filter conditions should enforce a node to be this class.
	 * @var array
	 */
	public static $forceClassOnConditions = array( 'designated' );

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
	
	/**
	 * Determines whether the node passes the given conditions.
	 *
	 * This is called, for example, when querying a branch for particular nodes.
	 * Direct children should first check $this->node->filter(), and return 
	 * false if it does, otherwise continue with filtering. Further descendants
	 * should likewise check parent::filter().
	 *
	 * @param array $conditions A hash of the conditions to pass.
	 * @param Logic $logic The logic.
	 * @return boolean Wether the node passes (i.e. is not ruled out by) the conditions.
	 * @see TableauBranch::find()
	 */
	public function filter( array $conditions, Logic $logic )
	{
		if ( !$this->node->filter( $conditions, $logic )) return false;
		if ( isset( $conditions['designated'] )) return $this->isDesignated() === $conditions['designated'];
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