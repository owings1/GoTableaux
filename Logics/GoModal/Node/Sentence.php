<?php

class GoModal_Node_Sentence extends Tableaux_Node
{
	protected 	$sentence,
				$i,
				$designated;
	
	protected static	$desMarker = '+',
						$undesMarker = '-';
	
	static function setDesMarker( $symbol )
	{
		self::$desMarker = $sybmol;
	}
	static function setUndesMarker( $symbol )
	{
		self::$undesMarker = $symbol;
	}
	public function __construct( Sentence $sentence, $i, $designated )
	{
		$this->sentence = $sentence;
		$this->i = (int) $i;
		$this->designated = (bool) $designated;
	}
	
	public function getSentence()
	{
		return $this->sentence;
	}
	
	public function getI()
	{
		return $this->i;
	}
	
	public function isDesignated()
	{
		return $this->designated;
	}
	public function __tostring()
	{
		$string = $this->sentence->__tostring();
		if ( 0 === strpos( $string, '(' ) && 0 === strpos( strrev( $string ), ')' )){
			$string = substr( $string, 1, strlen( $string ) - 2 );
		}
		return  $string . ', ' . $this->i . ( ( $this->isDesignated() ) ? self::$desMarker : self::$undesMarker );
	}
}
?>