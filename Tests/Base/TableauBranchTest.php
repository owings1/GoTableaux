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
use \GoTableaux\Argument as Argument;

if ( !defined( 'DS' )) define( 'DS', DIRECTORY_SEPARATOR );
require_once __DIR__ . DS . '..' . DS . 'simpletest' . DS . 'autorun.php';
require_once __DIR__ . DS . '..' . DS . 'classes' . DS .'UnitTestCase.php';

class TableauBranchTest extends UnitTestCase
{
	public $tableau;
	
	public function setUp()
	{
		$logic = Logic::getInstance( 'CPL' );
		$argument = $logic->parseArgument( array( 'A', 'A > B', 'C V ~B' ), 'D' );
		$this->tableau = $logic->constructProofForArgument( $argument );
	}
	
	function testGetNodesByClassName()
	{
		$className = 'Sentence';
		foreach ( $this->tableau->getBranches() as $branch ) {
			$nodes = $branch->getNodesByClassName( $className );
			$this->assertFalse( empty( $nodes ));
			foreach ( $nodes as $node )
				$this->assertTrue( $node instanceof \GoTableaux\Proof\TableauNode\Sentence );
		}
		
	}
}