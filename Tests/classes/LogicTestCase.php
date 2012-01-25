<?php

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