<?php
/**
 * Basic examples.
 * @package Examples
 * @author Douglas Owings
 */

// Load the example functions.
require 'example_functions.php';

$premises = array( 'A > B', 'B' );
$conclusion = 'A';

echo evaluate_argument( $premises, $conclusion, 'CPL' );

echo evaluate_argument( $premises, $conclusion, 'FDE' );

$arguments = array(
	'Disjunctive Syllogism' 	=> array( array( 'A V B', '~B' ), 'A' ),
	'Affirming a Disjunct'		=> array( array( 'A V B', 'A' ), 'B' ),
	'Law of Excluded Middle' 	=> array( 'B', 'A V ~A' ),
	'Denying the Antecedent' 	=> array( array( 'A > B', '~A' ), 'B' ),
	'Law of Non-contradiction' 	=> array( 'A & ~A', 'B' ),
	'Modus Ponens' 				=> array( array( 'A > B', 'A' ), 'B' ),
	'Modus Tollens' 			=> array( array( 'A > B', '~B' ), '~A' ),
	'DeMorgan 1' 				=> array( '~(A V B)', '~A & ~B' ),
	'DeMorgan 2' 				=> array( '~(A & B)', '~A V ~B' ),
	'DeMorgan 3' 				=> array( '~A & ~B', '~(A V B)' ),
	'DeMorgan 4' 				=> array( '~A V ~B', '~(A & B)' ),
);

echo evaluate_many_arguments( $arguments, 'CPL' );

echo evaluate_many_arguments( $arguments, 'FDE' );