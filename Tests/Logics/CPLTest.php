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
namespace GoTableaux\Test;

require_once dirname(__FILE__) . '/../simpletest/autorun.php';
require_once dirname(__FILE__) . '/../classes/LogicTestCase.php';
require_once dirname(__FILE__) . '/../../GoTableaux.php';

class CPLTest extends LogicTestCase
{
	public $logicName = 'CPL';
	
	public $validities = array(
		'Disjunctive Syllogism' 	=> array( array( 'A V B', '~B' ), 'A' ),
		'Law of Excluded Middle' 	=> array( null, 'A V ~A' ),
		'Law of Non-contradiction' 	=> array( 'A & ~A', 'B' ),
		'Identity'					=> array( null, 'A > A' ),
		'Modus Ponens' 				=> array( array( 'A > B', 'A' ), 'B' ),
		'Modus Tollens' 			=> array( array( 'A > B', '~B' ), '~A' ),
		'DeMorgan 1' 				=> array( '~(A V B)', '~A & ~B' ),
		'DeMorgan 2' 				=> array( '~(A & B)', '~A V ~B' ),
		'DeMorgan 3' 				=> array( '~A & ~B', '~(A V B)' ),
		'DeMorgan 4' 				=> array( '~A V ~B', '~(A & B)' ),
		'Contraction'				=> array( 'A > (A > B)', 'A > B' ),
		'Pseudo Contraction'		=> array( null, '(A > (A > B)) > (A > B)' ),
	);
	
	public $invalidities = array(
		'Affirming the Consequent'	=> array( array( 'A > B', 'B' ), 'A' ),
		'Affirming a Disjunct'		=> array( array( 'A V B', 'A' ), 'B' ),
		'Denying the Antecedent' 	=> array( array( 'A > B', '~A' ), 'B' ),
	);

}