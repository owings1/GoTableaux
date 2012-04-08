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
namespace GoTableaux\Test;

if ( !defined( 'DS' )) define( 'DS', DIRECTORY_SEPARATOR );
require_once __DIR__ . DS . '..' . DS . 'simpletest' . DS . 'autorun.php';
require_once __DIR__ . DS . '..' . DS . 'classes' . DS . 'LogicTestCase.php';

class FDETest extends LogicTestCase
{
	public $logicName = 'FDE';
	
	public $validities = array(
		'Simplification'			=> array( 'A & B', 'A' ),
		'DeMorgan 1' 				=> array( '~(A V B)', '~A & ~B' ),
		'DeMorgan 2' 				=> array( '~(A & B)', '~A V ~B' ),
		'DeMorgan 3' 				=> array( '~A & ~B', '~(A V B)' ),
		'DeMorgan 4' 				=> array( '~A V ~B', '~(A & B)' ),
		'Contraction'				=> array( 'A > (A > B)', 'A > B' ),
	);
	
	public $invalidities = array(
		'Affirming the Consequent'	=> array( array( 'A > B', 'B' ), 'A' ),
		'Affirming a Disjunct'		=> array( array( 'A V B', 'A' ), 'B' ),
		'Denying the Antecedent' 	=> array( array( 'A > B', '~A' ), 'B' ),
		'Law of Excluded Middle' 	=> array( null, 'A V ~A' ),
		'Law of Non-contradiction' 	=> array( 'A & ~A', 'B' ),
		'Pseudo Contraction'		=> array( null, '(A > (A > B)) > (A > B)' ),
		'Identity'					=> array( null, 'A > A' ),
		'Disjunctive Syllogism' 	=> array( array( 'A V B', '~B' ), 'A' ),
		'Modus Ponens' 				=> array( array( 'A > B', 'A' ), 'B' ),
		'Modus Tollens' 			=> array( array( 'A > B', '~B' ), '~A' ),
	);
}