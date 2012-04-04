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

if ( !defined( 'DS' )) define( 'DS', DIRECTORY_SEPARATOR );
require_once __DIR__ . DS . '..' . DS . 'simpletest' . DS . 'autorun.php';
require_once __DIR__ . DS . '..' . DS . 'classes' . DS . 'LogicTestCase.php';

class LukasiewiczTest extends LogicTestCase
{
	public $logicName = 'Lukasiewicz';
	
	public $validities = array(
		'Law of Non-contradiction' 	=> array( 'A & ~A', 'B' ),
		'Material Modus Ponens'		=> array( array( 'A > B', 'A' ), 'B' ),
		'Material Modus Tollens' 	=> array( array( 'A > B', '~B' ), '~A' ),
		'Disjunctive Syllogism' 	=> array( array( 'A V B', '~B' ), 'A' ),
		'Simplification'			=> array( 'A & B', 'A' ),
		'DeMorgan 1' 				=> array( '~(A V B)', '~A & ~B' ),
		'DeMorgan 2' 				=> array( '~(A & B)', '~A V ~B' ),
		'DeMorgan 3' 				=> array( '~A & ~B', '~(A V B)' ),
		'DeMorgan 4' 				=> array( '~A V ~B', '~(A & B)' ),
		'Material Contraction'		=> array( 'A > (A > B)', 'A > B' ),
		'Identity'					=> array( null, 'A $ A' ),
		'Material to Conditional'	=> array( 'A > B', 'A $ B' ),
	);
	
	public $invalidities = array(
		'Affirming the Consequent'	=> array( array( 'A > B', 'B' ), 'A' ),
		'Affirming a Disjunct'		=> array( array( 'A V B', 'A' ), 'B' ),
		'Denying the Antecedent' 	=> array( array( 'A > B', '~A' ), 'B' ),
		'Law of Excluded Middle' 	=> array( null, 'A V ~A' ),
		'Pseudo Contraction'		=> array( null, '(A > (A > B)) > (A > B)' ),
		'Material Identity'			=> array( null, 'A > A' ),
		'Conditional to Material'	=> array( 'A $ B', 'A > B' ),
	);
}