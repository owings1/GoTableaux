<?php

namespace GoTableaux\Test;

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