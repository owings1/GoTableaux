<?php
/**
 * Defines the Operator class.
 * @package Syntax
 * @author Douglas Owings
 */

/**
 * Loads the {@link VocabularyException} class.
 */
require_once 'VocabularyException.php';

/**
 * Represents an operator.
 * @package Syntax
 * @author Douglas Owings
 * @see Vocabulary::createOperator()
 */
class Operator
{
	/**
	 * Holds the name of the operator.
	 * @var string
	 * @access private
	 */
	protected $name;
	
	/**
	 * Holds the arity of the operator.
	 * @var integer
	 * @access private
	 */ 
	protected $arity;
	
	/**
	 * Holds the operator's symbol.
	 * @var string Single character.
	 * @access private
	 */ 
	protected $symbol;
	
	/**
	 * Constructor.
	 *
	 * @param string $symbol The operator symbol.
	 * @param integer $arity The arity of the operator.
	 * @param string $name The human name of the operator, e.g. 'Conjunction'.
	 * @throws {@link VobabularyException} on parameter errors.
	 * @see Vocabulary::createOperator()
	 */
	function __construct( $symbol, $arity, $name )
	{
		if ( strlen( $symbol ) !== 1 )
			throw new VobabularyException( 'Operator symbol must be exactly one character long.' );
		if ( empty( $name ))
			throw new VobabularyException( 'Operator name cannot be empty' );
		if ( $arity < 1 )
			throw new VobabularyException( 'Arity must be greater than zero.' );

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