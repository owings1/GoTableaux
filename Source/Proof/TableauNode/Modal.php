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
 * Defines the Modal Node interface.
 * @package GoTableaux
 */

namespace GoTableaux\Proof\TableauNode;

/**
 * Signifies a modal tableau node that has at least one index.
 * @package GoTableaux
 */
class Modal extends \GoTableaux\Proof\TableauNode
{
	/**
	 * Meta symbol names required by the node.
	 * @var array
	 */
    public static $metaSymbolNames = array( 'worldSymbol' );

    /**
     * Holds the (first) index.
 	 * @var integer
     */
    private $i;
    
    /**
	 * Sets the node properties.
	 * @param array $properties The properties.
	 * @throws TableauException when no sentence is given.
	 */
	public function setProperties( array $properties )
	{
		$this->node->setProperties( $properties );
		if ( empty( $properties['i'] )) 
			throw new TableauException( 'Must set index when creating a modal node.' );
		$this->setI( $properties['i'] );
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
	 * @return boolean Wether the node passes (i.e. is not ruled out by) the conditions.
	 * @see TableauBranch::find()
	 */
	public function filter( array $conditions )
	{
		if ( !$this->node->filter( $conditions )) return false;
		return !isset( $conditions['i' ] ) || $this->getI() === $conditions['i'];
	}
	
	/**
	 * Returns the index, or the first index, of a modal node.
	 *
	 * @return integer The index, or first index of the node.
	 */
	public function getI()
    {
        return $this->i;
    }
	
	/**
	 * Sets the first index
	 *
	 * @param integer $i The index.
	 * @return Modal Current instance.
	 */
	public function setI( $i )
    {
        $this->i = (int) $i;
    }
}