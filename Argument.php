<?php

class Argument

{
	protected $premises = array(), $conclusion;
	
	
	function addPremise( Sentence $sentence )
	{
		$this->premises[] = $sentence;
	}
	
	function setConclusion( Sentence $sentence )
	{
		$this->conclusion = $sentence;
	}
	
	public function getPremises()
	{
		return $this->premises;
	}
	
	public function getConclusion()
	{
		return $this->conclusion;
	}
}
?>