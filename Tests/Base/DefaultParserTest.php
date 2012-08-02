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
 * This program is distributed WITHOUT ANY WARRANTY. 
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/agpl-3.0.html>.
 */
namespace GoTableaux\Test;

use \GoTableaux\Logic as Logic;
use \GoTableaux\Sentence as Sentence;
use \GoTableaux\SentenceParser as Parser;
use \GoTableaux\SentenceWriter as Writer;

if ( !defined( 'DS' )) define( 'DS', DIRECTORY_SEPARATOR );
require_once __DIR__ . DS . '..' . DS . 'simpletest' . DS . 'autorun.php';
require_once __DIR__ . DS . '..' . DS . 'classes' . DS .'UnitTestCase.php';


class DefaultParserTest extends UnitTestCase
{
	public function setUp()
	{
		$this->logic 	= Logic::getInstance( 'CPL' );
		$this->parser   = Parser::getInstance( $this->logic );
		$this->writer	= Writer::getInstance( $this->logic );
	}
	
	
	private function parse( $str )
	{
		return $this->parser->stringToSentence( $str );
	}
	
	private function register( Sentence $sentence )
	{
		return $this->logic->registerSentence( $sentence );
	}
	
	private function assertSimForm( $a, $b )
	{
		if ( is_string( $a )) $a = $this->parse( $a );
		if ( is_string( $b )) $b = $this->parse( $b );
		return $this->assertTrue( Sentence::similarForm( $a, $b ));
	}
	private function assertNotSimForm( $a, $b )
	{
		if ( is_string( $a )) $a = $this->parse( $a );
		if ( is_string( $b )) $b = $this->parse( $b );
		return $this->assertFalse( Sentence::similarForm( $a, $b ));
	}
	public function testRegisteringCreatesMissingMoleculars()
	{
		$this->setUp();
		$this->register( $this->parse( 'A & (B & C)' ));
		$b_and_c = $this->parse( 'B & C' );
		$this->assertTrue( Sentence::sameFormInArray( $b_and_c, $this->logic->getSentences() ));
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
		$this->assertIdentical( $b_and_c_ops[0]->getSymbolIndex(), 1 );
		$this->assertIdentical( $b_and_c_ops[1]->getSymbolIndex(), 2 );
	}
	
	function testSimilarForm()
	{
		$this->assertSimForm( 'A', 'A' );
		$this->assertSimForm( 'A', 'B' );
		$this->assertSimForm( 'A', 'B & C' );
		$this->assertSimForm( 'A & B', 'A & B' );
		$this->assertSimForm( 'A & B', 'B & C' );
		$this->assertSimForm( 'A & B', 'C & D');
		$this->assertSimForm( 'A & B', 'C & ~A' );
		$this->assertSimForm( 'A & B', 'A & (B V C)' );
		
		$this->assertNotSimForm( 'A & B', 'A' );
		$this->assertNotSimForm( '~B', 'A > B' );
		$this->assertNotSimForm( 'B V C', 'A & (B V C)' );
		$this->assertNotSimForm( 'C & D', 'C V D' );

	}
	
	public function testWithAtomic()
	{
		$sentences = $this->logic->parseSentences(array(
			'A' 	=> 'A',
			'A0' 	=> 'A_0',
			'B23' 	=> 'B_23',
			'B1'    => 'B_1',
			'C' 	=> 'C', 
			'D' 	=> 'D',
		));
		$outputs = $this->writer->writeSentences( $sentences, $this->logic );
		
		/* Test parsing */
		$this->assertEachIsA( $sentences, 'GoTableaux\Sentence\Atomic' );
		$this->assertIdentical( $sentences['A']->getSubscript(), 0 );
		$this->assertIdentical( $sentences['A']->getSymbolIndex(), 0 );
		$this->assertIdentical( $sentences['B1']->getSymbolIndex(), 1 );
		$this->assertIdentical( $sentences['B1']->getSubScript(), 1 );
		$this->assertReference( $sentences['A'], $sentences['A0'] );
		$this->assertIdentical( $sentences['B23']->getSubscript(), 23 );
		$this->assertIdentical( $sentences['B23']->getSymbolIndex(), 1 );
		$this->assertIdentical( $sentences['D']->getSymbolIndex(), 3 );
	
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
		$this->assertEachIsA( $molecularSentences, '\GoTableaux\Sentence\Molecular' );
		$this->assertReference( $sentences['~~A'], $sentences['~~A*'] );
		
		
		/* Test writing */
		$this->assertIdentical( $outputs['~A'], '~A' );
		$this->assertIdentical( $outputs['~~A'], '~~A');
		
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
		$this->assertEachIsA( $molecularSentences, '\GoTableaux\Sentence\Molecular' );
		$this->assertReference( $sentences['A & B'], $sentences['(A & B)']);
		
		/* Test writing */
		$this->assertIdentical( $outputs['A & B'], 'A & B' );
		$this->assertIdentical( $outputs['A & (B & C)'], 'A & (B & C)' );
		$this->assertIdentical( $outputs['(A & B) & (C & D)'], '(A & B) & (C & D)' );
		
		//$this->assertIdentical( $outputs[''], '' );
		
		
	}
}