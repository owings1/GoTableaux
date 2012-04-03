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
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program in file LICENSE.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace GoTableaux\Test;

require_once dirname( __FILE__ ) . '/UnitTestCase.php';

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

	public function assertValid( \GoTableaux\Proof $proof, $message = '' )
	{
		$this->assertTrue( $proof->isValid(), $message );
	}
	
	public function assertInvalid( \GoTableaux\Proof $proof, $message = '' )
	{
		$this->assertFalse( $proof->isValid(), $message );
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