<?php

class Operator
{
	protected $name, $arity, $symbol;
	
	function __construct( $symbol, $arity, $name )
	{
		/* 		Force arity to type integer		*/
		$arity = intval( $arity );
		
		/*		Force name to type string		*/
		$name = strval( $name );
		
		/*		Check for non-empty name		*/
		if ( strlen( $name ) < 1 ){
			throw new Exception( 'Empty name given for operator' );
		}
		
		/* 		Check for arity > 0 		*/
		if ( $arity < 1 ){
			throw new Exception( 'Arity must be integer > 0' );
		}
		$this->name = $name;
		$this->arity = $arity;
		$this->symbol = $symbol;
	}
	function getName()
	{
		return $this->name;
	}
	function getArity()
	{
		return $this->arity;
	}
	function getSymbol()
	{
		return $this->symbol;
	}
}
?>