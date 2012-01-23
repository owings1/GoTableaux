<?php
/**
 * Defines the StandardSentenceWriter class.
 * @package Syntax
 * @author Douglas Owings
 */

/**
 * Represents the standard sentence writer.
 * @package Syntax
 * @author Douglas Owings
 */
class StandardSentenceWriter extends SentenceWriter
{
	public function writeSentence( Sentence $sentence, Logic $logic )
	{
		$vocabulary = $logic->getVocabulary();
		$sentenceStr = $this->_sentenceToString( $sentence, $vocabulary );
		return ParserUtilities::dropOuterParens( $sentenceStr, $vocabulary );
	}
	
	/**
	 * Makes a string representation of a sentence with outer parentheses.
	 *
	 * @param Sentence $sentence The sentence to represent.
	 * @param Vocabulary $vocabulary The vocabulary relative to which to produce
	 *								 the representation.
	 * @return string The string representation with outer parentheses.
	 * @access private
	 */
	private function _sentenceToString( Sentence $sentence, Vocabulary $vocabulary )
	{
		if ( $sentence instanceof AtomicSentence ) 
			return $this->_atomicToString( $sentence, $vocabulary );
		
		$sentenceStr = $this->_molecularToString( $sentence, $vocabulary );
		return $sentenceStr;
	}
	
	/**
	 * Makes a string representation of an atomic sentence.
	 *
	 * @param AtomicSentence $sentence The atomic sentence to represent.
	 * @param Vocabulary $vocabulary The vocabulary relative to which to produce
	 *								 the representation.
	 * @return string The string representation of the sentence.
	 * @access private
	 */
	private function _atomicToString( AtomicSentence $sentence, Vocabulary $vocabulary )
	{
		$string 		 = $sentence->getSymbol();
		$subscriptSymbol = $vocabulary->getSubscriptSymbols( true );
		$subscript 		 = $sentence->getSubscript();
		
		if ( $subscript > 0 || $this->getOption( 'printZeroSubscripts' ))
			$string .= $subscriptSymbol . $subscript;
			
		return  $string;
	}
	
	/**
	 * Makes a string representation of a molecular sentence.
	 *
	 * @param MolecularSentence $sentence
	 * @param Vocabulary $vocabulary The vocabulary relative to which to parse.
	 * @return string
	 * @throws {@link ParserException} when trying to represent operators of arity > 2.
	 * @access private
	 */
	private function _molecularToString( MolecularSentence $sentence, Vocabulary $vocabulary )
	{
		$operands	 	= $sentence->getOperands();
		$operatorSymbol = $vocabulary->getSymbolForOperator( $sentence->getOperator() );
		
		switch ( $vocabulary->getSymbolType( $operatorSymbol )) {
			case Vocabulary::OPER_UNARY :
				$sentenceStr = $operatorSymbol . $this->_sentenceToString( $operands[0], $vocabulary );
				break;
			case Vocabulary::OPER_BINARY :
				$separator	 = $vocabulary->getSeparators( true );
				$sentenceStr = $vocabulary->getOpenMarks( true ) . 
									$this->_sentenceToString( $operands[0], $vocabulary ) .
							   		$separator . $operatorSymbol . $separator .
							   		$this->_sentenceToString( $operands[1], $vocabulary ) . 
							   $vocabulary->getCloseMarks( true );
				break;
			default:
				throw new ParserException( 'Cannot represent sentences with operators of arity > 2.' );
				break;
		}
		return $sentenceStr;
	}
}