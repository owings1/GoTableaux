<?php
/**
 * Examples using JSON output.
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
echo GoTableaux\evaluate_argument( $premises, $conclusion, 'CPL', 'JSON' );
echo GoTableaux\evaluate_argument( $premises, $conclusion, 'FDE', 'JSON' );
echo GoTableaux\evaluate_argument( $premises, $conclusion, 'LP', 'JSON' );
echo GoTableaux\evaluate_argument( $premises, $conclusion, 'StrongKleene', 'JSON' );

// Load sample arguments
$arguments = include( 'example_arguments.php' );

// Evaluate the arugments in several logics
echo GoTableaux\evaluate_many_arguments( $arguments, 'CPL', 'JSON' );
echo GoTableaux\evaluate_many_arguments( $arguments, 'FDE', 'JSON' );
echo GoTableaux\evaluate_many_arguments( $arguments, 'LP', 'JSON' );
echo GoTableaux\evaluate_many_arguments( $arguments, 'StrongKleene', 'JSON' );