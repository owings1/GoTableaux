<?php

namespace GoTableaux\Test;

use \GoTableaux\Logic as Logic;
use \GoTableaux\Sentence as Sentence;
use \GoTableaux\SentenceParser as Parser;

require_once dirname(__FILE__) . '/../simpletest/autorun.php';
require_once dirname(__FILE__) . '/../classes/UnitTestCase.php';
require_once dirname(__FILE__) . '/../../GoTableaux.php';

class VocabularyTest extends UnitTestCase
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
	
	private function register( Sentence $sentence )
	{
		return $this->logic->getVocabulary()->registerSentence( $sentence );
	}
	
	public function testRegisteringCreatesMissingMoleculars()
	{
		$this->logic->initVocabulary();
		$this->setUp();
		$this->register( $this->parse( 'A & (B & C)' ));
		$b_and_c = $this->parse( 'B & C' );
		$this->assertTrue( Sentence::sameFormInArray( $b_and_c, $this->logic->getVocabulary()->getSentences() ));
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
		$this->assertIsA( $a_and_bc_ops[1], '\GoTableaux\Sentence\Molecular' );
		
		$sentences['b_and_c'] = $this->parse( 'B & C' );
		$b_and_c = $this->register( $sentences['b_and_c'] );
		$b_and_c_ops = $b_and_c->getOperands();
		$this->assertEachIsA( $b_and_c_ops, '\GoTableaux\Sentence\Atomic' );
		$this->assertIdentical( $b_and_c_ops[0]->getSymbol(), 'B' );
		$this->assertIdentical( $b_and_c_ops[1]->getSymbol(), 'C' );
	}
}