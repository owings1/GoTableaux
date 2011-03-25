<?php

class Tableaux_Inference
{
	protected $argment, $label, $bi;
	
	function __construct( Argument $argument, $label = null, $bi = false )
	{
		$this->argument = $argument;
		$this->label = (string) $label;
		$this->bi = (bool) $bi;
	}
}
?>