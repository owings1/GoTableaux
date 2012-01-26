<?php

namespace GoTableaux\Test;

use \GoTableaux\Logic as Logic;
use \GoTableaux\Sentence as Sentence;
use \GoTableaux\SentenceParser as Parser;

require_once dirname(__FILE__) . '/../simpletest/autorun.php';
require_once dirname(__FILE__) . '/../classes/UnitTestCase.php';
require_once dirname(__FILE__) . '/../../GoTableaux.php';

class SentenceTest extends UnitTestCase
{
	public $logic, $parser;
	
	public function setUp()
	{
		$this->logic = Logic::getInstance( 'CPL' );
		$vocabulary = $this->logic->getVocabulary();
		$this->parser = Parser::getInstance( $vocabulary );
	}
	
	private function parse( $str )
	{
		return $this->parser->stringToSentence( $str );
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