<?php
/**
 * Defines the Standard Sentence Writer class.
 * @package GoTableaux
 * @author Douglas Owings
 */

namespace GoTableaux\SentenceWriter;

use \GoTableaux\Sentence\Molecular as MolecularSentence;

/**
 * Writes sentences in Polish notation.
 * @package GoTableaux
 * @author Douglas Owings
 */
class Polish extends \GoTableaux\SentenceWriter
{
	protected $standardOperatorTranslations = array(
		'Conjunction' => 'K',
		'Disjunction' => 'A',
		'Negation'	  => 'N',
		'Material Conditional' 		=> 'M',
		'Material Biconditional' 	=> 'Q',
	);
	
	public function writeAtomicSymbol( $symbol )
	{
		return strtolower( $symbol );
	}
	
	/**
	 * Makes a string representation of a molecular sentence.
	 *
	 * @param MolecularSentence $sentence The molecular sentence to represent.
	 * @return string The string representation of the sentence.
	 */
	public function writeMolecular( MolecularSentence $sentence )
	{
		$str = $this->writeOperator( $sentence->getOperator() );
		foreach ( $sentence->getOperands() as $operand )
			$str .= $this->_writeSentence( $operand );
		return $str;
	}
}