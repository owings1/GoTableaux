<?php

class Sentence_Atomic extends Sentence
{
	protected $label;
	
	function setLabel( $label )
	{
		$this->label = $label;
	}
	function getLabel()
	{
		return $this->label;
	}
	public function __tostring()
	{
		return $this->getLabel();
	}
}
?>