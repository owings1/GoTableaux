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

require_once __DIR__ . '/UnitTestCase.php';

abstract class LogicTestCase extends UnitTestCase
{
	
	public $validities = array();
	
	public $invalidities = array();
	
	public $logicName;
	
	public $logic;
	
	public function setUp()
	{
		$this->logic = \GoTableaux\Logic::getInstance( $this->logicName );
	}

	public function assertValid( \GoTableaux\Proof $proof, $msg = '')
	{
		$this->assertTrue( $proof->isValid(), $msg );
	}
	
	public function assertInvalid( \GoTableaux\Proof $proof, $msg = '' )
	{
		$this->assertFalse( $proof->isValid(), $msg );
	}
	
	public function parseArguments( array $argumentsArray )
	{
		$arguments = array();
		foreach ( $argumentsArray as $name => $argumentStrings )
			$arguments[$name] = $this->logic->parseArgument( $argumentStrings[0], $argumentStrings[1] );
		return $arguments;	
	}
	
	public function testValidities()
	{
		$arguments = $this->parseArguments( $this->validities );
		foreach ( $arguments as $name => $argument ) {
			$proof = $this->logic->getProofSystem()
							   	 ->constructProofForArgument( $argument );
			$this->assertValid( $proof, $name );
		}
	}
	
	public function testInvalidities()
	{
		$arguments = $this->parseArguments( $this->invalidities );
		foreach ( $arguments as $name => $argument ) {
			$proof = $this->logic->getProofSystem()
							   	 ->constructProofForArgument( $argument );
			$this->assertInvalid( $proof, $name );
		}
	}
}