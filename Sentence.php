<?php
/**
 * Defines the Sentence class.
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
}