<?php
/**
 * Basic examples for evaluating arguments in several logics, and printing the results.
 *
 * @package Examples
 * @author Douglas Owings
 */

// Load the example functions.
require 'example_functions.php';

// Create an example argument.
$premises = array( 'A > B', 'B' );
$conclusion = 'A';

// Evaluate the argument in several logics.
echo GoTableaux\evaluate_argument( $premises, $conclusion, 'CPL' );
echo GoTableaux\evaluate_argument( $premises, $conclusion, 'FDE' );
echo GoTableaux\evaluate_argument( $premises, $conclusion, 'LP' );
echo GoTableaux\evaluate_argument( $premises, $conclusion, 'StrongKleene' );

// Load sample arguments
$arguments = include( 'example_arguments.php' );

// Evaluate the arugments in several logics
echo GoTableaux\evaluate_many_arguments( $arguments, 'CPL' );
echo GoTableaux\evaluate_many_arguments( $arguments, 'FDE' );
echo GoTableaux\evaluate_many_arguments( $arguments, 'LP' );
echo GoTableaux\evaluate_many_arguments( $arguments, 'StrongKleene' );