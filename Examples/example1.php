<?php
/**
 * Basic examples for evaluating arguments in several logics, and printing the results.
 *
 * @package Examples
 * @author Douglas Owings
 */

// Load the example functions.
require_once __DIR__ . '/example_functions.php';

// Create an example argument.
$premises = array( 'A_2 > B', 'B' );
$conclusion = 'A_2';

// Choose which logics to use.
$logicNames = array( 
	'CPL', 
	'FDE', 
	'LP', 
	'StrongKleene',
);

// Select sentence notation.
$notation = 'Standard';
//$notation = 'Polish';

// Select proof writer output.
$output = 'Simple';
//$output = 'JSON';
//$output = 'LaTeX_Qtree';

// Evaluate the argument in several logics and print the result.
foreach ( $logicNames as $logicName )
	echo GoTableaux\evaluate_argument( $premises, $conclusion, $logicName, $output, $notation );

// Load pre-fab example arguments.
$exampleArguments = include( __DIR__ . '/example_arguments.php' );

// Evaluate the example arugments in several logics and print the results.
foreach ( $logicNames as $logicName )
	echo GoTableaux\evaluate_many_arguments( $exampleArguments, $logicName, $output, $notation );