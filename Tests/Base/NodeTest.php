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
use \GoTableaux\Proof\TableauNode as Node;
use \GoTableaux\Proof\TableauNode\Sentence as SentenceNode;
use \GoTableaux\Proof\TableauNode\Modal as ModalNode;
use \GoTableaux\Proof\TableauNode\ManyValued as ManyValuedNode;
use \GoTableaux\Proof\TableauNode\Access as AccessNode;

if ( !defined( 'DS' )) define( 'DS', DIRECTORY_SEPARATOR );
require_once __DIR__ . DS . '..' . DS . 'simpletest' . DS . 'autorun.php';
require_once __DIR__ . DS . '..' . DS . 'classes' . DS .'UnitTestCase.php';

class NodeTest extends UnitTestCase
{
	public $logic;
	
	public function setUp()
	{
		$this->logic = Logic::getInstance( 'CPL' );
	}
	
	function testNode()
	{
		$properties = array(
			'sentence' => $this->logic->parseSentence( 'A' ),
			'i' => 0,
			'j' => 1
		);
		$node = new Node;
		$sNode = new SentenceNode( $node, $properties );
		$mNode = new ModalNode( $sNode, $properties );
		
		$this->assertTrue( $node->hasClass( 'Sentence' ));
		$this->assertTrue( $mNode->hasClass( 'Sentence' ));
		$this->assertTrue( $sNode->hasClass( 'Sentence' ));
		$this->assertTrue( $node->hasClass( 'Modal' ));
		$this->assertTrue( $mNode->hasClass( 'Modal' ));
		$this->assertTrue( $sNode->hasClass( 'Modal' ));
		
		
		$this->assertTrue( $node->filter( array(), $this->logic ));
		$this->assertTrue( $sNode->filter( array(), $this->logic ));
		$this->assertTrue( $mNode->filter( array(), $this->logic ));
		
		$this->assertTrue( $node->filter( array( 'i' => '*' ), $this->logic ));
		
		$this->assertTrue( $mNode->filter( array( 'i' => '*' ), $this->logic ));
		
		$this->assertTrue( $mNode->filter( array( 'i' => 0 ), $this->logic ));
		$this->assertFalse( $mNode->filter( array( 'i' => '1' ), $this->logic ));
		$this->assertTrue( $sNode->filter( array( 'i' => '*' ), $this->logic ));
		$this->assertTrue( $sNode->filter( array( 'i' => 0 ), $this->logic ));
		
		$aNode = new AccessNode( $node, $properties );
		
		$this->assertTrue( $node->hasClass( 'Access' ));
		$this->assertTrue( $node->hasClass( 'Modal' ));
		
	}
}