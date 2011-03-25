<?php

class GoModal_Node_Access extends Tableaux_Node
{
	protected	$i, $j;
	
	public function __construct( $i, $j )
	{
		$this->i = (int) $i;
		$this->j = (int) $j;
	}
	
	public function getI()
	{
		return $this->i;
	}
	public function getJ()
	{
		return $this->j;
	}
	public function __tostring()
	{
		return $this->i . 'R' . $this->j;
	}
}
?>