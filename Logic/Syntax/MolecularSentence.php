<?php
/**
 * Contains the MolecularSentence class.
 * @package Syntax
 * @author Douglas Owings
 */

namespace GoTableaux;

/**
 * Loads {@link VocabularyException} class.
 */
require_once dirname( __FILE__) . '/../Exceptions/VocabularyException.php';

/**
 * Represents a molecular sentence.
 * @package Syntax
 * @author Douglas Owings
 **/
class MolecularSentence extends Sentence
{
	/**
	 * Holds an Operator instance.
	 * @var Operator
	 * @access private
	 */
	protected $operator; 
	
	/**
	 * Holds the operands.
	 * @var array Array of {@link Sentence} objects.
	 * @access private
	 */
	protected $operands = array();
	
	/**
	 * Sets the operator
	 * 
	 * @param Operator $operator The operator object of the sentence.
	 * @return MolecularSentence Current instance.
	 */
	public function setOperator( Operator $operator )
	{
		$this->operator = $operator;
		return $this;
	}
	
	/**
	 * Adds an operand, or many operands.
	 *
	 * @param Sentence|array $operand The operand(s) to add.
	 * @return MolecularSentence Current instance.
	 */
	public function addOperand( $operand )
	{
		if ( is_array( $operand ))
			foreach ( $operand as $sentence ) $this->_addOperand( $sentence );
		else 
			$this->_addOperand( $operand );
		return $this;
	}
	
	/**
	 * Adds an operand, with type forcing.
	 *
	 * @param Sentence $operand
	 * @return void
	 * @access private
	 */
	protected function _addOperand( Sentence $operand )
	{
		if ( count( $this->operands ) == $this->operator->getArity() )
			throw new VocabularyException( 'Cannot exceed operator arity.' );
		$this->operands[] = $operand;
	}
	
	/**
	 * Gets the Operator object.
	 *
	 * @return Operator The sentence's operator.
	 */
	public function getOperator()
	{
		return $this->operator;
	}
	
	/**
	 * Sets the operands.
	 *
	 * @param array $operands Array of {@link Sentence}s.
	 * @return MolecularSentence Current isntace.
	 */
	public function setOperands( array $operands )
	{
		$this->operands = array();
		foreach ( $operands as $operand ) $this->_addOperand( $operand );
		return $this;
	}
	
	/**
	 * Gets the operands.
	 *
	 * @return array The sentence's operands. An array of {@link Sentence} objects.
	 */
	public function getOperands()
	{
		return $this->operands;
	}
}
?>