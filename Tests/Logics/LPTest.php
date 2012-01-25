<?php

namespace GoTableaux\Test;

require_once dirname(__FILE__) . '/../simpletest/autorun.php';
require_once dirname(__FILE__) . '/../classes/LogicTestCase.php';
require_once dirname(__FILE__) . '/../../Logic/Logic.php';

class LPTest extends LogicTestCase
{
	public $logicName = 'LP';
	
	public $validities = array(
		'Law of Excluded Middle' 	=> array( null, 'A V ~A' ),
		'Identity'					=> array( null, 'A > A' ),
		'Simplification'			=> array( 'A & B', 'A' ),
		'DeMorgan 1' 				=> array( '~(A V B)', '~A & ~B' ),
		'DeMorgan 2' 				=> array( '~(A & B)', '~A V ~B' ),
		'DeMorgan 3' 				=> array( '~A & ~B', '~(A V B)' ),
		'DeMorgan 4' 				=> array( '~A V ~B', '~(A & B)' ),
		'Contraction'				=> array( 'A > (A > B)', 'A > B' ),
		'Pseudo Contraction'		=> array( null, '(A > (A > B)) > (A > B)' ),
	);
	
	public $invalidities = array(
		'Affirming the Consequent'	=> array( array( 'A > B', 'B' ), 'A' ),
		'Affirming a Disjunct'		=> array( array( 'A V B', 'A' ), 'B' ),
		'Denying the Antecedent' 	=> array( array( 'A > B', '~A' ), 'B' ),
		'Law of Non-contradiction' 	=> array( 'A & ~A', 'B' ),
		'Disjunctive Syllogism' 	=> array( array( 'A V B', '~B' ), 'A' ),
		'Modus Ponens' 				=> array( array( 'A > B', 'A' ), 'B' ),
		'Modus Tollens' 			=> array( array( 'A > B', '~B' ), '~A' ),
	);

}