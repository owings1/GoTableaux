<?php

abstract class Sentence
{
	protected static $counter = 0;
	
	protected $id;
	
	function __construct()
	{
		$this->id = self::$counter++;
	}
}

// children
require_once 'Sentence/Atomic.php';
require_once 'Sentence/Molecular.php';
?>