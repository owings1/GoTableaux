<?php
require_once dirname(__FILE__) . '/../simpletest/autorun.php';
require_once dirname(__FILE__) . '/../classes/GoTableauxUnitTestCase.php';
require_once dirname(__FILE__) . '/../../Logic/Logic.php';

class VocabularyTest extends GoTableauxUnitTestCase
{
	public function setUp()
	{
		$this->logic = Logic::getInstance( 'CPL' );
		$this->vocabulary = $this->logic->getVocabulary();
		$this->parser = $this->logic->getDefaultParser();
	}
	
	private function parse( $str )
	{
		return $this->parser->stringToSentence( $str, $this->vocabulary );
	}
	
	private function register( Sentence $sentence )
	{
		return $this->vocabulary->registerSentence( $sentence );
	}
	
	public function testRegisteringCreatesMissingMoleculars()
	{
		$this->logic->initVocabulary();
		$this->setUp();
		$this->register( $this->parse( 'A & (B & C)' ));
		$b_and_c = $this->parse( 'B & C' );
		$this->assertTrue( Sentence::sameFormInArray( $b_and_c, $this->vocabulary->getSentences() ));
	}
	
	public function testRegisterSentence()
	{
		$sentences['a']  = $this->parse( 'A' );
		$sentences['a1'] = $this->parse( 'A' );
		$this->assertNoReference( $sentences['a'], $sentences['a1'] );
		
		$a  = $this->register( $sentences['a'] );
		$a1 = $this->register( $sentences['a1'] );
		$this->assertReference( $a, $a1 );
		
		$sentences['a_and_bc'] = $this->parse( 'A & (B & C)' );
		$a_and_bc = $this->register( $sentences['a_and_bc'] );
		$this->assertSameForm( $a_and_bc, $sentences['a_and_bc'] );
		
		$sentences['a_and_b'] = $this->parse( 'A & B' );
		$a_and_b = $this->register( $sentences['a_and_b'] );	
		$this->assertNoReference( $a_and_b, $a_and_bc );	
		
		$sentences['a_and_b1'] = $this->parse( 'A & B' );
		$a_and_b1 = $this->register( $sentences['a_and_b1'] );
		$this->assertReference( $a_and_b, $a_and_b1 );
		
		$a_and_b_ops = $a_and_b->getOperands();
		$this->assertReference( $a_and_b_ops[0], $a );
		
		$sentences['b'] = $this->parse( 'B' );
		$b = $this->register( $sentences['b'] );
		$this->assertReference( $a_and_b_ops[1], $b );
		
		$a_and_bc_ops = $a_and_bc->getOperands();
		$this->assertIdentical( count( $a_and_bc_ops ), 2 );
		$this->assertIsA( $a_and_bc_ops[1], 'MolecularSentence' );
		
		$sentences['b_and_c'] = $this->parse( 'B & C' );
		$b_and_c = $this->register( $sentences['b_and_c'] );
		$b_and_c_ops = $b_and_c->getOperands();
		$this->assertEachIsA( $b_and_c_ops, 'AtomicSentence' );
		$this->assertIdentical( $b_and_c_ops[0]->getSymbol(), 'B' );
		$this->assertIdentical( $b_and_c_ops[1]->getSymbol(), 'C' );
	}
}