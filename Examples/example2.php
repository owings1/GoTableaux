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
 * Basic examples for evaluating arguments in several logics, and printing the results.
 *
 * This example parses in Polish notation
 *
 * @package GoTableaux
 */

// Load the example functions.
require_once __DIR__ . '/example_functions.php';

// Select write notation.
$writeNotation = 'Standard';
$writeNotation = 'Polish';

// Create an example argument.
$premises = array( 'Ca2b', 'b' );
$conclusion = 'a2';

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
	echo GoTableaux\evaluate_argument( $premises, $conclusion, $logicName, $output, 'Polish', $writeNotation );

// Load example arguments in standard notation.
$exampleArguments = array(
	'Disjunctive Syllogism' 	=> array( array( 'Aab', 'Nb' ), 'a' ),
	'Affirming a Disjunct'		=> array( array( 'Aab', 'a' ), 'b' ),
	'Law of Excluded Middle' 	=> array( 'b', 'AaNa' ),
	'Denying the Antecedent' 	=> array( array( 'Cab', 'Na' ), 'b' ),
	'Law of Non-contradiction' 	=> array( 'KaNa', 'b' ),
	'Identity'					=> array( null, 'Caa' ),
	'Modus Ponens' 				=> array( array( 'Cab', 'a' ), 'b' ),
	'Modus Tollens' 			=> array( array( 'Cab', 'Nb' ), 'Na' ),
	'DeMorgan 1' 				=> array( 'NAab', 'KNaNb' ),
	'DeMorgan 2' 				=> array( 'NKab', 'ANaNb' ),
	'DeMorgan 3' 				=> array( 'KNaNb', 'NAab' ),
	'DeMorgan 4' 				=> array( 'ANaNb', 'NKab' ),
	'Contraction'				=> array( 'CaCab', 'Cab' ),
	'Pseudo Contraction'		=> array( null, 'CCaCabCab' ),
);

// Evaluate the example arugments in several logics and print the results.
foreach ( $logicNames as $logicName )
	echo GoTableaux\evaluate_many_arguments( $exampleArguments, $logicName, $output, 'Polish', $writeNotation );