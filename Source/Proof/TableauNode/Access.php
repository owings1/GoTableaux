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

use \GoTableaux\Logic as Logic;
use \GoTableaux\Exception\Tableau as TableauException;

/**
 * Represents a modal logic access relation node.
 * @package GoTableaux
 */
class Access extends Modal
{
	/**
	 * Meta symbol names required by the node.
	 * @var array
	 */
    public static $metaSymbolNames = array( 'accessRelationSymbol' );

	/**
	 * States which filter conditions should enforce a node to be this class.
	 * @var array
	 */
	public static $forceClassOnConditions = array( 'j' );
	
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
		if ( !parent::filter( $conditions, $logic )) return false;
		return !isset( $conditions['j' ] ) || $conditions['j'] === '*' || $this->getJ() === $conditions['j'];
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