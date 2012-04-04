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
 * Basic examples for evaluating arguments in several logics, and printing the results.
 *
 * @package GoTableaux
 */

// Load the example functions.
require_once __DIR__ . '/example_functions.php';

// Select standard sentence notation.
$notation = 'Standard';

// Create an example argument.
$premises = array( 'A_2 > B', 'B' );
$conclusion = 'A_2';

// Choose which logics to use.
$logicNames = array( 
	'CPL', 
	'FDE', 
	'LP', 
	'StrongKleene',
	'Lukasiewicz',
	'GO',
);

// Select proof writer output.
$output = 'Simple';
//$output = 'JSON';
$output = 'LaTeX_Qtree';

// Evaluate the argument in several logics and print the result.
foreach ( $logicNames as $logicName )
	echo GoTableaux\evaluate_argument( $premises, $conclusion, $logicName, $output, $notation );

// Load example arguments in standard notation.
$exampleArguments = array(
	'Disjunctive Syllogism' 	=> array( array( 'A V B', '~B' ), 'A' ),
	'Affirming a Disjunct'		=> array( array( 'A V B', 'A' ), 'B' ),
	'Law of Excluded Middle' 	=> array( 'B', 'A V ~A' ),
	'Denying the Antecedent' 	=> array( array( 'A > B', '~A' ), 'B' ),
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

// Evaluate the example arugments in several logics and print the results.
foreach ( $logicNames as $logicName )
	echo GoTableaux\evaluate_many_arguments( $exampleArguments, $logicName, $output, $notation );