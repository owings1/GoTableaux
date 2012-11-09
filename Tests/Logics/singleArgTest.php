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

class SingleArgTest extends LogicTestCase
{
	public $logicName = 'CPL';
	
	public $validities = array(
		//'Arg1' => array(
		//	array('A0 V B0', 'A1 V B1', 'A1 V B1', 'A1 V B1', 'B0 V ~A3', 'A3' ),
		//)
	);
	
	public $invalidities = array(
		'Long Argument 1'			=> array( 
			array('A0 V B0', 'A1 V B1', 'A1 V B1', 'A1 V B1', 'A2 V A3' ), 
			'(A0 & B0) & (A1 & B1)'
		),
		'Long Argument 2'			=> array( 
			array('A0 V B0', 'A1 V B1', 'A1 V B1', 'A1 V B1', 'A2 V A3', '~A3' ), 
			'(A0 & B0) & (A1 & B1)'
		),
	);

}