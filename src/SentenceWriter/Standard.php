<?php
/**
 * Defines the Standard notation sentence writer class.
 * @package GoTableaux
 * @author Douglas Owings
 */

namespace GoTableaux\SentenceWriter;

use \GoTableaux\Exception\Writer as WriterException;
use \GoTableaux\Sentence\Molecular as MolecularSentence;

/**
 * Writes sentences in standard notation.
 * @package GoTableaux
 * @author Douglas Owings
 */
class Standard extends \GoTableaux\SentenceWriter
{
	/**
	 * Makes a string representation of a molecular sentence.
	 *
	 * @param MolecularSentence $sentence The molecular sentence to represent.
	 * @return string The string representation of the sentence.
	 */
	public function writeMolecular( MolecularSentence $sentence )
	{
		$operator		= $sentence->getOperator();
		$operands	 	= $sentence->getOperands();
		$vocabulary		= $this->getVocabulary();
		
		$operatorStr 	= $this->writeOperator( $operator );
		
		switch ( $operator->getArity() ) {
			case 1 :
				$sentenceStr = $operatorStr . $this->_writeSentence( $operands[0] );
				break;
			case 2 :
				$separator	 = $vocabulary->getSeparators( true );
				$sentenceStr = $vocabulary->getOpenMarks( true ) . 
									$this->_writeSentence( $operands[0] ) .
							   		$separator . $operatorStr . $separator .
							   		$this->_writeSentence( $operands[1] ) . 
							   $vocabulary->getCloseMarks( true );
				break;
			default:
				throw new WriterException( 'Cannot represent sentences with operators of arity > 2.' );
				break;
		}
		return $sentenceStr;
	}
}