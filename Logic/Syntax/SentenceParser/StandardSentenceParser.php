<?php
/**
 * Defines the StandardSentenceParser class.
 * @package Syntax
 * @author Douglas Owings
 */

/**
 * Represents the standard sentence parser.
 * @package Syntax
 * @author Douglas Owings
 **/
class StandardSentenceParser extends SentenceParser
{
	/**
	 * Creates a {@link Sentence sentence} from a string.
	 * 
	 * @param string $sentenceStr The string to interpret.
	 * @param Vocabulary $vocabulary The vocabulary relative to which to parse.
	 * @return Sentence The resulting instance.
	 * @throws {@link ParserException} on any errors in parsing the input string.
	 */
	public function stringToSentence( $sentenceStr, Vocabulary $vocabulary )
	{
		$sentenceStr = ParserUtilities::removeSeparators( $sentenceStr, $vocabulary );
		$sentenceStr = ParserUtilities::dropOuterParens( $sentenceStr, $vocabulary );
		
		if ( empty( $sentenceStr )) 
			throw new ParserException( 'Sentence string cannot be empty.' );
		
		$firstSentenceStr = $this->_readSentence( $sentenceStr, $vocabulary );
		
		if ( $firstSentenceStr === $sentenceStr ) {
			$firstSymbolType = $vocabulary->getSymbolType( $sentenceStr{0} );
			switch ( $firstSymbolType ) {
				case Vocabulary::ATOMIC :
					return $this->_parseAtomic( $sentenceStr, $vocabulary );
				case Vocabulary::OPER_UNARY :
					$operator 	= $vocabulary->getOperatorBySymbol( $sentenceStr{0} );
					$operandStr = substr( $sentenceStr, 1 );
					$operand 	= $this->stringToSentence( $operandStr, $vocabulary );
					return Sentence::createMolecular( $operator, array( $operand ));
				default :
					throw ParserException::createWithMsgInputPos( 'Unexpected symbol type.', $sentenceStr, 0 );
			}
		}
		
		$pos 			= strlen( $firstSentenceStr );
		$nextSymbol 	= $sentenceStr{$pos};
		$nextSymbolType = $vocabulary->getSymbolType( $nextSymbol );
		
		if ( $nextSymbolType !== Vocabulary::OPER_BINARY )
			throw ParserException::createWithMsgInputPos( 'Unexpected symbol. Expecting binary operator.', $sentenceStr, $pos );
		
		$rightStr			= substr( $sentenceStr, ++$pos );
		$secondSentenceStr 	= $this->_readSentence( $rightStr, $vocabulary );
		
		if ( $rightStr !== $secondSentenceStr )
			throw ParserException::createWithMsgInputPos( 'Invalid right operand string.', $sentenceStr, $pos );
		
		$operator = $vocabulary->getOperatorBySymbol( $nextSymbol );
		$operands = array(
			$this->stringToSentence( $firstSentenceStr, $vocabulary ),
			$this->stringToSentence( $secondSentenceStr, $vocabulary )
		);
		return Sentence::createMolecular( $operator, $operands );
	}
	
	/**
	 * Parses an atomic sentence string.
	 *
	 * @param string $sentenceStr The string to parse.
	 * @param Vocabulary $vocabulary The vocabulary to use.
	 * @return Sentence The resulting sentence instance.
	 * @throws {@link ParserException}.
	 * @access private
	 */
	private function _parseAtomic( $sentenceStr, Vocabulary $vocabulary )
	{
		$subscripts 	= $vocabulary->getSubscriptSymbols();
		$atomicSymbols 	= $vocabulary->getAtomicSymbols();
		$hasSubscript 	= false !== Utilities::strPosArr( $sentenceStr, $subscripts, 1, $match );
		
		list( $symbol, $subscript ) = $hasSubscript ? explode( $match, $sentenceStr ) 
													: array( $sentenceStr, 0 );
		
		if ( !in_array( $symbol, $atomicSymbols ))
			throw new ParserException( "$symbol is not an atomic symbol." );
		
		if ( !is_numeric( $subscript ))
			throw new ParserException( "Subscript must be numeric." );
		
		return Sentence::createAtomic( $symbol, $subscript );
	}
	
	/**
	 * Reads a string for the first occurrence of a sentence expression.
	 *
	 * @param string $string The string to read.
	 * @param Vocabulary $vocabulary The vocabulary to use.
	 * @return string The first sentence string.
	 * @throws {@link ParserException} on parse error.
	 * @access private
	 */
	private function _readSentence( $string, Vocabulary $vocabulary )
	{	
		$firstSymbolType = $vocabulary->getSymbolType( $string{0} );
		switch ( $firstSymbolType ) {
			case Vocabulary::ATOMIC :
				$hasSubscript = strlen( $string ) > 1 && 
								$vocabulary->getSymbolType( $string{1} ) === Vocabulary::CTRL_SUBSCRIPT;
				$firstSentenceStr = $hasSubscript ? substr( $string, 0, 2 ) . intval( substr( $string, 2 ))
												  : $string{0};
				break;
			case Vocabulary::PUNCT_OPEN;
				$closePos = ParserUtilities::closePosFromOpenPos( $string, 0, $vocabulary );
				$firstSentenceStr = substr( $string, 0, $closePos + 1 );
				break;
			case Vocabulary::OPER_UNARY :
				$firstSentenceStr = $string{0} . $this->_readSentence( substr( $string, 1 ), $vocabulary );
				break;
			default :
				throw ParserException::createWithMsgInputPos( 'Unexpected symbol type.', $string, 0 );
		}
		return $firstSentenceStr;
	}
	

}