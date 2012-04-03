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
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program in file LICENSE.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace GoTableaux\Test;

use \GoTableaux\Logic as Logic;
use \GoTableaux\SentenceWriter as Writer;

require_once dirname(__FILE__) . '/../simpletest/autorun.php';
require_once dirname(__FILE__) . '/../classes/UnitTestCase.php';
require_once dirname(__FILE__) . '/../../GoTableaux.php';


class DefaultParserTest extends UnitTestCase
{
	public function setUp()
	{
		$this->logic 	= Logic::getInstance( 'CPL' );
		$this->writer	= Writer::getInstance( $this->logic->getVocabulary() );
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
		$this->assertEachIsA( $sentences, 'GoTableaux\Sentence\Atomic' );
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