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
 * Defines the AccessNode class.
 * @package Tableaux
 */

namespace GoTableaux\Proof\TableauNode;

/**
 * Represents a modal logic access relation node.
 * @package Tableaux
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
	 * Constructor.
	 * 
	 * Sets the indexes of the node.
	 *
	 * @param integer $i The first index.
	 * @param integer $j The second index.
	 */
	public function __construct( $i, $j )
	{
		$this->setI( $i )->setJ( $j ); 
	}
	
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
}