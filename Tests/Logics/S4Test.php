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

class S4Test extends LogicTestCase
{
	public $logicName = 'S4';
	
	public $validities = array(/*
		'Disjunctive Syllogism' 	=> array( array( 'A V B', '~B' ), 'A' ),
		'Law of Excluded Middle' 	=> array( null, 'A V ~A' ),
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
		'Biconditional Elimination' => array( array( 'A < B', 'A'), 'B' ),
		'Biconditional Elimination 2' => array( array( 'A < B', '~A' ), '~B' ),
	*/	'Modal Platitude 1'			=> array( 'PA', 'PA'),
		'Modal Platitude 2'			=> array( 'NA', 'NA'),
		'Modal Platitude 3'			=> array( 'NPA', 'NPA'),
		'Modal Transformation 1'	=> array( 'NA', '~P~A' ),
		'Modal Transformation 2'	=> array( '~P~A', 'NA' ),
		'Modal Transformation 3'	=> array( '~NA', 'P~A' ),
		'Modal Transformation 4'	=> array( 'P~A', '~NA' ),
		'Necessity Distribution'	=> array( null, 'N(A > B) > (NA > NB)' ),
		'Reflexive Inference 1'		=> array( null, 'NA > A' ),
		'NP Collapse'				=> array( 'NPA', 'PA' ),
		'Possibility Addition'		=> array( 'A', 'PA' ),
		'Necessity Elimination'		=> array( 'NA', 'A'), 
		'S4 Inference 1'			=> array( null, 'NA > NNA' ),
		'S4 Complex 1'				=> array( 'NPA', 'PPPPPPPPPPPPPPPPPPPPPPPPA' ),
		'S4 Complex 2'				=> array( 'NPA', 'NPA' ),
		'S4 Complex 3'				=> array( 'NPA', 'PNPA' ),
	);
	
	public $invalidities = array(
		'Triviality 1'				=> array( 'A', 'B' ),
		'Triviality 2'				=> array( null, 'A' ),
		'Affirming the Consequent'	=> array( array( 'A > B', 'B' ), 'A' ),
		'Affirming a Disjunct 1'	=> array( array( 'A V B', 'A' ), 'B' ),
		'Affirming a Disjunct 2'	=> array( array( 'A V B', 'A' ), '~B' ),
		'Conditional Equivalence'	=> array( 'A > B', 'B > A' ),
		'Extracting the Consequent' => array( 'A > B', 'B' ),
		'Extracting the Antecedent' => array( 'A > B', 'A' ),
		'Extracting as Disjunct 1'	=> array( 'A V B', 'B' ),
		'Extracting as Disjunct 2'	=> array( 'A V ~B', '~A' ),
		'Denying the Antecedent' 	=> array( array( 'A > B', '~A' ), 'B' ),
		'Possibility distribution'	=> array( 'PA & PB', 'P(A & B)'),
		//'S4 Infinite 1'				=> array( null, 'NPA > PNA' ),
		//'S4 Infinite 2'				=> array( null, 'PA > PNA' ),
		//'S4 Infinite 3'			=> array( 'PA', 'PNA' ),
		//'S4 Infinite 4'			=> array( 'NPA', 'NPB' ),
		//'S4 Infinite 5'				=> array( 'NPA', 'B' ),
		'S5 Inference 1'			=> array( null, 'A > NPA' ),
	);

}