<?php
require_once 'simpletest/autorun.php';

require_once 'GoTableaux/Logic/Logic.php';
require_once 'GoTableaux/Logic/Syntax/SentenceParser.php';
require_once 'GoTableaux/Logic/Syntax/SentenceWriter.php';



class ParserWriterTest extends UnitTestCase
{
	function setUp()
	{
		global $logic, $writer, $parser;
		$logic 	= Logic::getInstance( 'CPL' );
		$parser = new StandardSentenceParser;
		$writer	= new StandardSentenceWriter;
	}
	
	function testAtomic()
	{
		global $logic, $writer, $parser;
		
		/* Create sentences */
		$a 	 = $logic->parseSentence( 'A', $parser );
		$a0  = $logic->parseSentence( 'A_0', $parser );
		$b23 = $logic->parseSentence( 'B_23', $parser );
		
		/* Write outputs */
		$a_str = $writer->writeSentence( $a, $logic );
		$writer->setOption( 'printZeroSubscripts', true ); 	// Change writer option
		$a0_str = $writer->writeSentence( $a0, $logic );
		$b23_str = $writer->writeSentence( $b23, $logic );
		
		/* Test parsing */
		$this->assertIsA( $a, 'AtomicSentence' );
		$this->assertIdentical( $a->getSubscript(), 0 );
		$this->assertIdentical( $a->getSymbol(), 'A' );
		$this->assertReference( $a, $a0 );
		$this->assertIdentical( $b23->getSubscript(), 23 );
		$this->assertIdentical( $b23->getSymbol(), 'B' );
		
		/* Test writing */
		$this->assertIdentical( $a_str, 'A' );
		$this->assertIdentical( $a0_str, 'A_0' );
		$this->assertIdentical( $b23_str, 'B_23' );
	}
}