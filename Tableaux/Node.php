<?php

abstract class Tableaux_Node
{
	protected 	$ticked = array();
	
	function tick( Tableaux_Branch $branch )
	{
		if ( ! in_array( $branch, $this->ticked, true )){
			$this->ticked[] = $branch;
		}
	}
	function ticked( Tableaux_Branch $branch )
	{
		return in_array( $branch, $this->ticked, true );
	}
}

?>