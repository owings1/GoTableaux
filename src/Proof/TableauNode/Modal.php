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
 * Defines the Modal Node interface.
 * @package Tableaux
 * @author Douglas Owings
 */

namespace GoTableaux\Proof\TableauNode;

/**
 * Signifies a modal tableau node that has at least one index.
 * @package Tableaux
 * @author Douglas Owings
 */
interface Modal
{
	/**
	 * Returns the index, or the first index, of a modal node.
	 *
	 * @return integer The index, or first index of the node.
	 */
	public function getI();
}