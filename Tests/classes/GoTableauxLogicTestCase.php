<?php
require_once 'GoTableauxUnitTestCase.php';

abstract class GoTableauxLogicTestCase extends GoTableauxUnitTestCase
{
	
	public $validities = array();
	
	public $invalidities = array();
	
	public $logicName;
	
	public $logic;
	
	public function setUp()
	{
		$this->logic = Logic::getInstance( $this->logicName );
	}

	public function assertValid( Proof $proof, $message = '' )
	{
		$this->assertTrue( $proof->isValid(), $message );
	}
	
	public function assertInvalid( Proof $proof, $message = '' )
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