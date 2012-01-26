<?php
/**
 * Defines the Standard Sentence Writer class.
 * @package Syntax
 * @author Douglas Owings
 */

namespace GoTableaux\SentenceWriter;

use \GoTableaux\Exception\Writer as Exception;
use \GoTableaux\Vocabulary as Vocabulary;
use \GoTableaux\Sentence as Sentence;
use \GoTableaux\Argument as Argument;
use \GoTableaux\Sentence\Atomic as AtomicSentence;
use \GoTableaux\Sentence\Molecular as MolecularSentence;
use \GoTableaux\ParserUtilities as ParserUtilities;

/**
 * Represents the standard sentence writer.
 * @package Syntax
 * @author Douglas Owings
 */
class Standard extends \GoTableaux\SentenceWriter
{
	public function writeSentence( Sentence $sentence )
	{
		$sentenceStr = $this->_sentenceToString( $sentence );
		return ParserUtilities::dropOuterParens( $sentenceStr, $this->vocabulary );
	}
	
	public function writeArgument( Argument $argument )
	{
		$premisesStr = implode( ', ', $this->writeSentences( $argument->getPremises() ));
		$conclusionStr = $this->writeSentence( $argument->getConclusion() );
		return "$premisesStr Therefore $conclusionStr";
	}
	
	/**
	 * Makes a string representation of a sentence with outer parentheses.
	 *
	 * @param Sentence $sentence The sentence to represent.
	 * @return string The string representation with outer parentheses.
	 * @access private
	 */
	private function _sentenceToString( Sentence $sentence )
	{
		return $sentence instanceof AtomicSentence ? $this->_atomicToString( $sentence )
												   : $this->_molecularToString( $sentence );
	}
	
	/**
	 * Makes a string representation of an atomic sentence.
	 *
	 * @param Sentence\Atomic $sentence The atomic sentence to represent.
	 * @return string The string representation of the sentence.
	 * @access private
	 */
	private function _atomicToString( AtomicSentence $sentence )
	{
		$string 		 = $sentence->getSymbol();
		$subscriptSymbol = $this->vocabulary->getSubscriptSymbols( true );
		$subscript 		 = $sentence->getSubscript();
		
		if ( $subscript > 0 || $this->getOption( 'printZeroSubscripts' ))
			$string .= $subscriptSymbol . $subscript;
			
		return  $string;
	}
	
	/**
	 * Makes a string representation of a molecular sentence.
	 *
	 * @param Sentence\Molecular $sentence
	 * @return string
	 * @throws {@link ParserException} when trying to represent operators of arity > 2.
	 * @access private
	 */
	private function _molecularToString( MolecularSentence $sentence )
	{
		$operands	 	= $sentence->getOperands();
		$vocabulary		= $this->vocabulary;
		$operatorSymbol = $vocabulary->getSymbolForOperator( $sentence->getOperator() );
		
		switch ( $vocabulary->getSymbolType( $operatorSymbol )) {
			case Vocabulary::OPER_UNARY :
				$sentenceStr = $operatorSymbol . $this->_sentenceToString( $operands[0] );
				break;
			case Vocabulary::OPER_BINARY :
				$separator	 = $vocabulary->getSeparators( true );
				$sentenceStr = $vocabulary->getOpenMarks( true ) . 
									$this->_sentenceToString( $operands[0] ) .
							   		$separator . $operatorSymbol . $separator .
							   		$this->_sentenceToString( $operands[1] ) . 
							   $vocabulary->getCloseMarks( true );
				break;
			default:
				throw new Exception( 'Cannot represent sentences with operators of arity > 2.' );
				break;
		}
		return $sentenceStr;
	}
}