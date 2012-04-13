<?php

namespace GoTableaux\Utilities;

class ArraySet extends Set
{
	private $arr = array();
	
	public function size()
	{
		return count( $this->arr );
	}
	
	public function getMembers()
	{
		return $this->arr;
	}
	
	protected function addOne( $member )
	{
		if ( !in_array( $member, $this->arr, true )) $this->arr[] = $member;
	}
	
	protected function removeOne( $member )
	{
		$arr = array();
		foreach ( $this->arr as $item )
			if ( $member !== $item ) $arr[] = $item;
		$this->arr = $arr;
	}
	
	protected function containsOne( $member )
	{
		return in_array( $member, $this->arr, true );
	}
}