<?php
/**
 * Defines the Operator class.
 * @package Syntax
 * @author Douglas Owings
 */

/**
 * Loads the {@link VocabularyException} class.
 */
require_once 'GoTableaux/Logic/Exceptions/VocabularyException.php';

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
	 * Constructor.
	 *
	 * @param string $name The human name of the operator, e.g. 'Conjunction'.
	 * @param integer $arity The arity of the operator.
	 * @throws {@link VobabularyException} on parameter errors.
	 * @see Vocabulary::createOperator()
	 */
	function __construct( $name, $arity )
	{
	if ( empty( $name ))
			throw new VobabularyException( 'Operator name cannot be empty' );
		if ( $arity < 1 )
			throw new VobabularyException( 'Arity must be greater than zero.' );
		$this->name 	= $name;
		$this->arity 	= (int) $arity;
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
}