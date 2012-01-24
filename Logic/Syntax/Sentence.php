<?php
/**
 * Defines the Sentence class.
 * @package Syntax
 * @author Douglas Owings
 */

/**
 * Loads the {@link AtomicSentence} class.
 */
require_once 'AtomicSentence.php';

/**
 * Loads the {@link MolecularSentence} class.
 */
require_once 'MolecularSentence.php';

/**
 * Represents a sentence.
 * @package Syntax
 * @author Douglas Owings
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