<?php
/**
 * Returns array of example arguments.
 * @package Examples
 * @author Douglas Owings
 */
return array(
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