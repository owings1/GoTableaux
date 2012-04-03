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
 * Defines the Atomic Sentence class.
 * @package Syntax
 * @author Douglas Owings
 */

namespace GoTableaux\Sentence;

/**
 * Represents an atomic sentence.
 * @package Syntax
 * @author Douglas Owings
 */
class Atomic extends \GoTableaux\Sentence
{
	/**
	 * Atomic symbol, e.g. 'A' or 'B'.
	 * @var string
	 * @access private
	 */
	protected $symbol;
	
	/**
	 * Subscript of the atomic symbol.
	 * @var integer
	 * @access private
	 */
	protected $subscript;
	
	/**
	 * Sets the atomic symbol.
	 * 
	 * @param string $symbol The symbol, e.g. 'A' or 'B'.
	 * @return Atomic Current instance.
	 */
	public function setSymbol( $symbol )
	{
		$this->symbol = $symbol;
		return $this;
	}
	
	/**
	 * Gets the atomic symbol.
	 *
	 * @return string The atomic symbol, e.g. 'A' or 'B'.
	 */
	public function getSymbol()
	{
		return $this->symbol;
	}
	
	/**
	 * Sets the subscript of the atomic symbol.
	 *
	 * @param integer $subscript The subscript.
	 * @return Atomic Current instance.
	 */
	public function setSubscript( $subscript )
	{
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