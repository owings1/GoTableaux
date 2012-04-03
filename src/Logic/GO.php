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
 * Defines the GO class.
 * @package GO
 * @author Douglas Owings
 */

namespace GoTableaux\Logic;

/**
 * Represents GO 3-valued Propositional Logic.
 * @package GO
 * @author Douglas Owings
 */
class GO extends \GoTableaux\Logic
{	
	public $lexicon = array(
		'openMarks' 		=> array('('),
		'closeMarks' 		=> array(')'),
		'separators' 		=> array(' '),
		'subscripts' 		=> array('_'),
		'atomicSymbols' 	=> array('A', 'B', 'C', 'D', 'E', 'F'),
		'operatorSymbols' 	=> array(
			'~' => array( 'Negation' => 1 ),
			'&' => array( 'Conjunction' => 2 ),
			'V' => array( 'Disjunction' => 2 ),
			'>' => array( 'Material Conditional' => 2 ),
			'<' => array( 'Material Biconditional' => 2 ),
		)
	);
}