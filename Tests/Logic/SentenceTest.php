<?php
require_once dirname(__FILE__) . '/../simpletest/autorun.php';
require_once dirname(__FILE__) . '/../classes/GoTableauxUnitTestCase.php';
require_once dirname(__FILE__) . '/../../Logic/Logic.php';

class SentenceTest extends GoTableauxUnitTestCase
{
	public $logic, $parser;
	
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
	public function testSameForm()
	{
		$a = $this->parse( 'A' );
		$b = $this->parse( 'A' );
		$c = $this->parse( 'C' );
		$this->assertTrue( $a !== $b );
		$this->assertTrue( Sentence::sameForm( $a, $a ));
		$this->assertTrue( Sentence::sameForm( $a, $b ));
		$this->assertFalse( Sentence::sameForm( $a, $c ));
		
		$a = $this->parse( 'A & B' );
		$b = $this->parse( 'A & B' );
		$c = $this->parse( 'A V B' );
		$d = $this->parse( 'B V A' );
		$e = $this->parse( 'A & (B & C)' );
		$f = $this->parse( '(A & (B & C))' );
		$g = $this->parse( 'F' );
		list( $a_op1, $a_op2 ) = $a->getOperands();
		list( $e_op1, $e_op2 ) = $e->getOperands();
		$this->assertTrue( $a !== $b );
		$this->assertTrue( Sentence::sameForm( $a, $b ));
		$this->assertFalse( Sentence::sameForm( $a, $c ));
		$this->assertFalse( Sentence::sameForm( $b, $c ));
		$this->assertFalse( Sentence::sameForm( $c, $d ));
		$this->assertTrue( Sentence::sameForm( $a_op1, $e_op1 ));
		$this->assertTrue( Sentence::sameForm( $e, $f ));
		$this->assertFalse( Sentence::sameForm( $e_op2, $g ));
		
	}
}