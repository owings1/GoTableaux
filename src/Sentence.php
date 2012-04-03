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
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program in file LICENSE.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * Defines the Sentence class.
 * @package GoTableaux
 */

namespace GoTableaux;

use \GoTableaux\Sentence\Atomic as AtomicSentence;
use \GoTableaux\Sentence\Molecular as MolecularSentence;

/**
 * Represents a sentence.
 * @package GoTableaux
 */
class Sentence
{
	/**
	 * Creates an atomic sentence.
	 *
	 * @param string $symbol The atomic symbol, e.g. 'A' or 'B'.
	 * @param integer $subscript The subscript. Default is 0.
	 * @return AtomicSentence The created instance.
	 */
	public static function createAtomic( $symbol, $subscript = 0 )
	{
		$sentence = new AtomicSentence;
		return $sentence->setSymbol( $symbol )->setSubscript( $subscript );
	}
	
	/**
	 * Creates a molecular sentence.
	 *
	 * @param Operator $operator Operator instance.
	 * @param array $operands Array of Sentence objects.
	 * @return MolecularSentence The created instance.
	 */
	public static function createMolecular( Operator $operator, array $operands )
	{
		$sentence = new MolecularSentence;
		return $sentence->setOperator( $operator )->addOperand( $operands );
	}
	
	/**
	 * Gets the operator name.
	 *
	 * @return string|false The name of the operator, or false if atomic.
	 */
	public function getOperatorName()
	{
		if ( $this instanceof AtomicSentence ) return false;
		return $this->getOperator()->getName();
	}
	
	/**
	 * Compares two sentences for form and atomic symbol identity.
	 *
	 * @param Sentence $sentence_a The first sentence.
	 * @param Sentence $sentence_b The second sentence.
	 * @return boolean Whether the sentences have the same form and atomic symbols.
	 */
	public static function sameForm( Sentence $sentence_a, Sentence $sentence_b )
	{
		if ( $sentence_a === $sentence_b ) return true;
		if ( $sentence_a->getOperatorName() !== $sentence_b->getOperatorName() ) return false;
		if ( $sentence_a instanceof AtomicSentence )
			return $sentence_a->getSymbol() === $sentence_b->getSymbol() &&
				   $sentence_a->getSubscript() === $sentence_b->getSubscript();
		if ( count( $sentence_a->getOperands() ) !== count( $sentence_b->getOperands() ) ) return false;
		$operands_a = $sentence_a->getOperands();
		$operands_b = $sentence_b->getOperands();
		foreach ( $operands_a as $key => $operand )
			if ( !self::sameForm( $operand, $operands_b[$key] )) return false;
		return true;
	}
	
	/**
	 * Checks whether $haystack has a sentence with the same form as $needle.
	 *
	 * @param Sentence $needle The sentence to check.
	 * @param array $haystack Array of {@link Sentence}s to search.
	 * @return boolean Whether a sentence with the same form is found.
	 */
	public static function sameFormInArray( Sentence $needle, array $haystack )
	{
		foreach ( $haystack as $sentence )
			if ( self::sameForm( $needle, $sentence )) return true;
		return false;
	}
}