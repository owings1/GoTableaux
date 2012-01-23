<?php
require_once 'simpletest/autorun.php';
require_once 'classes/GoTableauxUnitTestCase.php';

require_once 'GoTableaux/Logic/Logic.php';
require_once 'GoTableaux/Logic/Syntax/SentenceWriter.php';


class DefaultParserTest extends GoTableauxUnitTestCase
{
	public function setUp()
	{
		$this->logic 	= Logic::getInstance( 'CPL' );
		$this->writer	= new StandardSentenceWriter;
	}
	
	public function testDebug()
	{
		$this->logic->initVocabulary();
		$debugLevel = Settings::read( 'debug' );
		Settings::write( 'debug', true );
		
		$this->logic->parseSentences(array(
			'A', 'A', 'A', 'B'
		));
		
		$sentences = $this->logic->getVocabulary()->getSentences();
		
		foreach ( $sentences as $sentence ) $this->logic->getVocabulary()->registerSentence( $sentence );
		//$this->dump( $sentences );
		//$this->logic->parseSentence( 'A & A' );
		//$input 		= 'A & (B & C)';
		$input 		= '(A & B) & (A & C)';
		//$sentence 	= $this->logic->parseSentence( $input );
		//$output 	= $this->writer->writeSentence( $sentence, $this->logic );
		
		
		//$this->dump( compact('input', 'output', 'sentence') );
		
		Settings::write( 'debug', $debugLevel );
		$this->logic->initVocabulary();
	}
	
	public function testWithAtomic()
	{
		$sentences = $this->logic->parseSentences(array(
			'A' 	=> 'A',
			'A0' 	=> 'A_0',
			'B23' 	=> 'B _2 3',
			'C' 	=> 'C', 
			'D' 	=> 'D',
		));
		$outputs = $this->writer->writeSentences( $sentences, $this->logic );
		
		/* Test parsing */
		$this->assertEachIsA( $sentences, 'AtomicSentence' );
		$this->assertIdentical( $sentences['A']->getSubscript(), 0 );
		$this->assertIdentical( $sentences['A']->getSymbol(), 'A' );
		$this->assertReference( $sentences['A'], $sentences['A0'] );
		$this->assertIdentical( $sentences['B23']->getSubscript(), 23 );
		$this->assertIdentical( $sentences['B23']->getSymbol(), 'B' );
		
		/* Test writing */
		$this->assertIdentical( $outputs['A'], 'A' );
		$this->assertIdentical( $outputs['B23'], 'B_23' );
	}
	
	public function testWithUnary()
	{
		$atomicSentences = $this->logic->parseSentences(array(
			'A' => 'A',
		));
		$molecularSentences = $this->logic->parseSentences(array( 
			'~A' 	=> '~A', 
			'~~A' 	=> '~~A', 
			'~~A*' => '~(~A)',
		));
		$sentences = array_merge( $atomicSentences, $molecularSentences );
		$outputs = $this->writer->writeSentences( $sentences, $this->logic );
		
		/* Test parsing */
		$this->assertEachIsA( $molecularSentences, 'MolecularSentence' );
		
		
		/* Test writing */
		$this->assertIdentical( $outputs['~A'], '~A' );
		$this->assertIdentical( $outputs['~~A'], '~~A');
		$this->assertReference( $sentences['~~A'], $sentences['~~A*'] );
		
	}
	
	public function testWithBinary()
	{
		$atomicSentences = $this->logic->parseSentences(array(
			'A' => 'A',
			'B' => 'B',
			'C' => 'D',
		));
		$molecularSentences = $this->logic->parseSentences(array(
			'A & B' => 'A & B',
			'(A & B)' => '(A & B)',
			'A & (B & C)' => 'A & (B & C)',
			'(A & B) & (C & D)' => '(A & B) & (C & D)',
		));
		$sentences = array_merge( $atomicSentences, $molecularSentences );
		$outputs = $this->writer->writeSentences( $sentences, $this->logic );
		
		/* Test parsing */
		$this->assertEachIsA( $molecularSentences, 'MolecularSentence' );
		
		/* Test writing */
		$this->assertIdentical( $outputs['A & B'], 'A & B' );
		$this->assertReference( $sentences['A & B'], $sentences['(A & B)']);
		//$this->assertIdentical( $outputs['A & (B & C)'], 'A & (B & C)' );
		$this->assertIdentical( $outputs['(A & B) & (C & D)'], '(A & B) & (C & D)' );
		//$this->assertIdentical( $outputs[''], '' );
		
		
	}
}