<?php
/**
 * GoTableaux. A multi-logic tableaux generator.
 * Copyright (C) 2012  Douglas Owings
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed WITHOUT ANY WARRANTY. 
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/agpl-3.0.html>.
 */
/**
 * Contains the MolecularSentence class.
 * @package Syntax
 */

namespace GoTableaux\Sentence;
use \GoTableaux\Exception\Vocabulary as Exception;

/**
 * Represents a molecular sentence.
 * @package Syntax
 **/
class Molecular extends \GoTableaux\Sentence
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
	public function setOperator( \GoTableaux\Operator $operator )
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
	protected function _addOperand( \GoTableaux\Sentence $operand )
	{
		if ( count( $this->operands ) == $this->operator->getArity() )
			throw new Exception( 'Cannot exceed operator arity.' );
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