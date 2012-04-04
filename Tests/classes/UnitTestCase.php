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

if ( !defined( 'DS' )) define( 'DS', DIRECTORY_SEPARATOR );
require_once __DIR__ . DS . '..' . DS . '..' . DS . 'src' . DS . 'Loader.php';

class UnitTestCase extends \UnitTestCase
{
	public function assertSameForm( \GoTableaux\Sentence $a, \GoTableaux\Sentence $b, $message = '' )
	{
		$this->assertTrue( \GoTableaux\Sentence::sameForm( $a, $b ), $message );
	}
	
	public function assertNoReference( $a, $b, $message = '' )
	{
		$this->assertFalse( $a === $b, $message );
	}
	
	public function assertReferenceOfArray( array $arr, $message = null )
	{
		$one = array_rand( $arr );
		foreach ( $arr as $item ) $this->assertReference( $one, $item, $message );
	}
	
	public function assertEachIsA( array $arr, $type, $message = null )
	{
		foreach ( $arr as $item ) $this->assertIsA( $item, $type, $message );
	}
}