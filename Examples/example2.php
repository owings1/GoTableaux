<?php
/**
 * Basic examples for evaluating arguments in several logics, using Polish notation,
 * and printing the results.
 *
 * @package GoTableaux
 * @author Douglas Owings
 */

// Load the example functions.
require_once __DIR__ . '/example_functions.php';

// Select Polish sentence notation.
$notation = 'Polish';

// Create an example argument.
$premises = array( '>A_2B', 'B' );
$conclusion = 'A_2';

// Choose which logics to use.
$logicNames = array( 
	'CPL', 
	'FDE', 
	'LP', 
	'StrongKleene',
);

// Select proof writer output.
$output = 'Simple';
//$output = 'JSON';
//$output = 'LaTeX_Qtree';

// Evaluate the argument in several logics and print the result.
foreach ( $logicNames as $logicName )
	echo GoTableaux\evaluate_argument( $premises, $conclusion, $logicName, $output, $notation );

// Load example arguments in standard notation.
$exampleArguments = array(
	//'Disjunctive Syllogism' 	=> array( array( 'V A B', '~B' ), 'A' ),
	//'Affirming a Disjunct'		=> array( array( 'V A B', 'A' ), 'B' ),
	//'Law of Excluded Middle' 	=> array( 'B', 'V A ~A' ),
	//'Denying the Antecedent' 	=> array( array( '> A B', '~A' ), 'B' ),
	//'Law of Non-contradiction' 	=> array( '& A ~A', 'B' ),
	//'Identity'					=> array( null, '> A A' ),
	//'Modus Ponens' 				=> array( array( '> A B', 'A' ), 'B' ),
	//'Modus Tollens' 			=> array( array( '> A B', '~B' ), '~A' ),
	//'DeMorgan 1' 				=> array( '~ V A B', '& ~A ~B' ),
	//'DeMorgan 2' 				=> array( '~ & A B', 'V ~A ~B' ),
	//'DeMorgan 3' 				=> array( '& ~A ~B', '~ V A B' ),
	//'DeMorgan 4' 				=> array( 'V ~A ~B', '~ & A B' ),
	//'Contraction'				=> array( '> A > A B', '> A B' ),
	//'Pseudo Contraction'		=> array( null, '> > A > A B > A B' ),
);

// Evaluate the example arugments in several logics and print the results.
foreach ( $logicNames as $logicName )
	echo GoTableaux\evaluate_many_arguments( $exampleArguments, $logicName, $output, $notation );