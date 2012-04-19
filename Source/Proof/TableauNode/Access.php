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

use \GoTableaux\Exception\Tableau as Tableau;

/**
 * Represents a modal logic access relation node.
 * @package GoTableaux
 */
class Access extends Modal
{
	/**
	 * Holds a reference to the seen world index.
	 * @var integer
	 * @access private
	 */
	private $j;

	/**
	 * Sets the node properties.
	 *
	 * @param array $properties The properties.
	 * @return void
	 * @throws TableauException when no second index is given.
	 */
	public function setProperties( array $properties )
	{
		parent::setProperties( $properties );
		if ( empty( $properties['j'] )) 
			throw new TableauException( 'Must set second index when creating a sentence node.' );
		$this->setJ( $properties['j'] );
	}
	
	public function filter( array $conditions )
	{
		if ( !parent::filter( $conditions )) return false;
		return !isset( $conditions['j' ] ) || $this->getJ() === $conditions['j'];
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