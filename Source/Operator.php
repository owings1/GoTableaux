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
 * Defines the Operator class.
 * @package GoTableaux
 */

namespace GoTableaux;

use \GoTableaux\Exception\Vocabulary as VocabularyException;

/**
 * Represents an operator.
 * @package GoTableaux
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
	public function __construct( $name, $arity )
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