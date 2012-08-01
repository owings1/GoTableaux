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
	 * @param integer $index The atomic symbol index in the parser.
	 * @param integer $subscript The subscript. Default is 0.
	 * @return AtomicSentence The created instance.
	 */
	public static function createAtomic( $index, $subscript = 0 )
	{
		$sentence = new AtomicSentence;
		return $sentence->setSymbolIndex( $index )->setSubscript( $subscript );
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
	 * @return string|null The name of the operator, or null if atomic.
	 */
	public function getOperatorName()
	{
		if ( $this instanceof AtomicSentence ) return null;
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
			return $sentence_a->getSymbolIndex() === $sentence_b->getSymbolIndex() &&
				   $sentence_a->getSubscript() === $sentence_b->getSubscript();
		if ( count( $sentence_a->getOperands() ) !== count( $sentence_b->getOperands() ) ) return false;
		$operands_a = $sentence_a->getOperands();
		$operands_b = $sentence_b->getOperands();
		foreach ( $operands_a as $key => $operand )
			if ( !self::sameForm( $operand, $operands_b[$key] )) return false;
		return true;
	}
	
    /**
     * Checks whether the form of the first sentence is consitent, if less
     * complex, than the first.
     * 
     * @param Sentence $super A sentence with the basic form.
     * @param Sentence $sentence The sentence to examine whether it has a 
     *                           form consitent with $super.
     * @return boolean Whether the sentences have a similar form.
     */
    public static function similarForm( Sentence $super, Sentence $sentence )
    {
        if ( $super instanceof AtomicSentence ) return true;
        if ( $sentence->getOperatorName() !== $super->getOperatorName() ) return false;
        $sentenceOperands = $sentence->getOperands();
        foreach ( $super->getOperands() as $key => $operand )
            if ( empty( $sentenceOperands[$key] ) || !self::similarForm( $operand, $sentenceOperands[$key] )) 
                return false;
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