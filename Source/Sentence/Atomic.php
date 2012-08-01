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
 * Defines the Atomic Sentence class.
 * @package GoTableaux
 */

namespace GoTableaux\Sentence;

use \GoTableaux\Exception as Exception;

/**
 * Represents an atomic sentence.
 * @package GoTableaux
 */
class Atomic extends \GoTableaux\Sentence
{
	/**
	 * Atomic symbol index.
	 * @var integer
	 */
	protected $symbolIndex;
	
	/**
	 * Subscript of the atomic symbol.
	 * @var integer
	 */
	protected $subscript;
	
	/**
	 * Sets the atomic symbol index.
	 * 
	 * @param integer $index The index of the atomic symbol
	 * @return Atomic Current instance.
	 */
	public function setSymbolIndex( $index )
	{
		$this->symbolIndex = $index;
		return $this;
	}
	
	/**
	 * Gets the atomic symbol index.
	 *
	 * @return integer The index of the atomic symbol
	 */
	public function getSymbolIndex()
	{
		return $this->symbolIndex;
	}
	
	/**
	 * Sets the subscript of the atomic symbol.
	 *
	 * @param integer $subscript The subscript.
	 * @return Atomic Current instance.
	 */
	public function setSubscript( $subscript )
	{
		if ( !is_int( $subscript )) throw new Exception( "subscript must be numeric" );
		$this->subscript = (int) $subscript;
		return $this;
	}
	
	/**
	 * Gets the subscript of the atomic symbol.
	 *
	 * @return integer The subscript.
	 */
	public function getSubscript()
	{
		return $this->subscript;
	}

}