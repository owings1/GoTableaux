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
 * Defines the AccessNode class.
 * @package GoTableaux
 */

namespace GoTableaux\Proof\TableauNode;

/**
 * Represents a modal logic access relation node.
 * @package GoTableaux
 */
class Access extends \GoTableaux\Proof\TableauNode implements Modal
{
	/**
	 * Holds a reference to the seeing world index.
	 * @var integer
	 * @access private
	 */
	protected $i;
	
	/**
	 * Holds a reference to the seen world index.
	 * @var integer
	 * @access private
	 */
	protected $j;
	
	/**
	 * Sets the first index.
	 *
	 * @param integer $i The index.
	 * @return AccessNode Current instance
	 */
	public function setI( $i )
	{
		$this->i = (int) $i;
		return $this;
	}
	
	/**
	 * Gets the first index
	 * 
	 * @return integer The first index.
	 */
	public function getI()
	{
		return $this->i;
	}
	
	/**
	 * Sets the second index.
	 *
	 * @param integer $j The second index.
	 * @return AccessNode Current instance.
	 */
	public function setJ( $j )
	{
		$this->j = (int) $j;
		return $this;
	}
	
	/**
	 * Gets the second index.
	 *
	 * @return integer The second index.
	 */
	public function getJ()
	{
		return $this->j;
	}
	
	/**
	 * Sets the node properties.
	 * @param array $properties The properties.
	 * @return void
	 * @throws TableauException when no sentence is given.
	 */
	public function setProperties( array $properties )
	{
		parent::setProperties( $properties );
		if ( empty( $properties['i'] )) throw new TableauException( 'Must set first index when creating a sentence node.' );
		if ( empty( $properties['j'] )) throw new TableauException( 'Must set second index when creating a sentence node.' );
		$this->setI( $properties['i'] );
		$this->setJ( $properties['j'] );
	}
}