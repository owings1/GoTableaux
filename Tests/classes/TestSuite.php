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

use \GoTableaux\Utilities as Utilities;

if ( !defined( 'DS' )) define( 'DS', DIRECTORY_SEPARATOR );
require_once __DIR__ . DS . '..' . DS . '..' . DS . 'Source' . DS . 'Loader.php';
abstract class TestSuite extends \TestSuite 
{
	public $subDirectory;
	public $t1;
	
	public function __construct()
	{
		parent::__construct();
		$this->t1 = microtime( true );
		$this->collect( __DIR__ . DS . '..' . DS . $this->subDirectory, new \SimplePatternCollector( '/Test.php/' ));

	}
	
	public function tearDown()
	{
		Utilities::debug ( "{$this->subDirectory} time: " . round(microtime( true ) - $this->t1, 2 ) . 's');
		Utilities::debug ( "\n" );
	}
}