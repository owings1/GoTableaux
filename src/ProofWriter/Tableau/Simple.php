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
 * Defines the Simple Tableau Writer class.
 * @package GoTableaux
 */

namespace GoTableaux\ProofWriter\Tableau;

use \GoTableaux\Proof\TableauStructure as Structure;

/**
 * Represents a simple tableau proof writer.
 * @package GoTableaux
 */
class Simple extends \GoTableaux\ProofWriter\Tableau
{
	/**
	 * Makes a string representation of a tableau structure.
	 * 
	 * @param Structure $structure The tableau structure to represent.
	 * @return string The string representation of the structure.
	 */
	public function writeStructure( Structure $structure )
	{
		return print_r( $this->getArrayForStructure( $structure ), true );
	}	
}