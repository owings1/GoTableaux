<?php
require_once 'simpletest/autorun.php';
require_once 'classes/GoTableauxUnitTestCase.php';

require_once 'GoTableaux/Logic/Logic.php';

class VocabularyTest extends GoTableauxUnitTestCase
{
	function setUp()
	{
		$this->logic = Logic::getInstance( 'CPL' );
		$this->vocabulary = $this->logic->getVocabulary();
		$this->parser = $this->logic->getDefaultParser();
		//Settings::write( 'debug', true );
	}
	
	function testRegisterSentence()
	{
		
		$sentences['a'] 		= $this->parser->stringToSentence( 'A', $this->vocabulary );
		$sentences['a1'] 		= $this->parser->stringToSentence( 'A', $this->vocabulary );
		
		$a 	= $this->vocabulary->registerSentence( $sentences['a'] );
		$a1 = $this->vocabulary->registerSentence( $sentences['a1'] );
		
		$b 			= $this->logic->parseSentence( 'B' );
		$a_and_b 	= $this->logic->parseSentence( 'A & B' );
		$a_and_b1 	= $this->logic->parseSentence( 'A & B' );
		$b_and_c 	= $this->logic->parseSentence( 'B & C' );
		
		$sentences['a_and_bc'] 	= $this->parser->stringToSentence( 'A & (B & C)', $this->vocabulary );
		$a_and_bc = $this->vocabulary->registerSentence( $sentences['a_and_bc'] );
		
		$this->assertReference( $a, $a1 );
		$this->assertFalse( $sentences['a'] === $sentences['a1'] );
		
		
		$operands = $a_and_b->getOperands();
		
		$this->assertReference( $operands[0], $a );
		$this->assertReference( $operands[1], $b );
		$this->assertReference( $a_and_b, $a_and_b1 );
		
		$operands = $sentences['a_and_bc']->getOperands();
		
		$this->assertIdentical( count( $operands ), 2 );
		$this->assertIsA( $operands[1], 'MolecularSentence' );
		
		$operands1 = $operands[1]->getOperands();
		
		$this->assertEachIsA( $operands1, 'AtomicSentence' );
		$this->assertIdentical( $operands1[0]->getSymbol(), 'B' );
		$this->assertIdentical( $operands1[1]->getSymbol(), 'C' );
		$this->assertReference( $a_and_bc, $sentences['a_and_bc'] );
		
		$operands = $a_and_bc->getOperands();
		
		$this->assertReference( $operands[0], $a );
		$this->assertReference( $operands[1], $b_and_c );
		
		$this->assertFalse( $a_and_b === $a_and_bc );
		$this->assertReference( $sentences['a_and_bc'], $a_and_bc );
		
	}
}