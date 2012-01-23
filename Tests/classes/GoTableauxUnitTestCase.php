<?php

class GoTableauxUnitTestCase extends UnitTestCase
{
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