<?php
/**
 * Defines the Operator class.
 * @package Syntax
 * @author Douglas Owings
 */

/**
 * Represents an operator.
 * @package Syntax
 * @author Douglas Owings
 */
class Operator
{
	/**
	 * The human name of the operator, e.g. 'Conjunction'
	 * @var string
	 * @access private
	 */
	protected $name;
	
	/**
	 * The arity of the operator.
	 * @var integer
	 * @access private
	 */ 
	protected $arity;
	
	/**
	 * The symbol with which the operator is created.
	 * @var string Single character.
	 * @access private
	 */ 
	protected $symbol;
	
	/**
	 * Constructor.
	 *
	 * @param string $symbol
	 * @param integer $arity
	 * @param string $name
	 */
	function __construct( $symbol, $arity, $name )
	{
		if ( strlen( $symbol ) !== 1 )
			throw new Exception( 'Operator symbol must be exactly one character long.' );
		if ( empty( $name ))
			throw new Exception( 'Operator name cannot be empty' );
		if ( $arity < 1 )
			throw new Exception( 'Arity must be greater than zero.' );

		$this->name 	= $name;
		$this->arity 	= (int) $arity;
		$this->symbol 	= $symbol;
	}
	
	/**
	 * Gets the name of the operator.
	 *
	 * @return string The human name of the operator, e.g. 'Conjunction'.
	 */
	function getName()
	{
		return $this->name;
	}
	
	/**
	 * Gets the arity of the operator.
	 *
	 * @return integer The arity of the operator.
	 */
	function getArity()
	{
		return $this->arity;
	}
	
	/**
	 * Gets the operator symbol.
	 *
	 * @return string The symbol of the operator.
	 */
	function getSymbol()
	{
		return $this->symbol;
	}
}