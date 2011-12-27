<?php
/**
 * Defines the StandardSentenceParser class.
 * @package Syntax
 * @author Douglas Owings
 */

/**
 * Loads {@link Sentence} class.
 */
require_once 'GoTableaux/Logic/Syntax/Sentence.php';

/**
 * Represents the standard sentence parser.
 * @package Syntax
 * @author Douglas Owings
 **/
class StandardSentenceParser extends SentenceParser
{
	/**
	 * Gets a string representation of a {@link Sentence sentence}.
	 *
	 * @param Sentence $sentence The sentence to represent.
	 * @return string The string representation of the sentence.
	 **/
	public function sentenceToString( Sentence $sentence )
	{
		$sentenceStr = $this->_sentenceToString( $sentence );
		return $this->dropOuterParens( $sentenceStr );
	}
	
	/**
	 * Creates a {@link Sentence sentence} from a string.
	 * 
	 * @param string $sentenceStr The string to interpret.
	 * @return Sentence The resulting instance.
	 * @throws {@link ParserException} on any errors in parsing the input string.
	 */
	public function stringToSentence( $sentenceStr )
	{
		$vocabulary  = $this->getVocabulary();
		$sentenceStr = $this->removeSeparators( $sentenceStr );
		$sentenceStr = $this->dropOuterParens( $sentenceStr );
		
		if ( empty( $sentenceStr )) 
			throw new ParserException( 'Sentence string cannot be empty.' );
		
		$firstSentenceStr = $this->_readSentence( $sentenceStr );
		
		if ( $firstSentenceStr === $sentenceStr ) {
			$firstSymbolType = $vocabulary->getSymbolType( $sentenceStr{0} );
			if ( $firstSymbolType === Vocabulary::ATOMIC ) {
				$newSentence = $this->_parseAtomic( $sentenceStr );
				return $this->registerSentence( $newSentence );
			}
			if ( $firstSymbolType === 1 ) {
				$operator 		= $vocabulary->getOperatorBySymbol( $sentenceStr{0} );
				$operandStr 	= substr( $sentenceStr, 1 );
				$operand 		= $this->stringToSentence( $operandStr );
				$newSentence 	= Sentence::createMolecular( $operator, array( $operand ));
				return $this->registerSentence( $newSentence );
			}
			throw ParserException::createWithMsgInputPos( 'Unexpected symbol type.', $sentenceStr, 0 );
		}
		
		$pos 			= strlen( $firstSentenceStr );
		$nextSymbol 	= $sentenceStr{$pos};
		$nextSymbolType = $vocabulary->getSymbolType( $nextSymbol );
		
		if ( $nextSymbolType !== 2 )
			throw ParserException::createWithMsgInputPos( 'Unexpected symbol. Expecting binary operator.', $sentenceStr, $pos );
		
		$rightStr			= substr( $sentenceStr, $pos + 1 );
		$secondSentenceStr 	= $this->_readSentence( $rightStr );
		
		if ( $rightStr !== $secondSentenceStr )
			throw ParserException::createWithMsgInputPos( 'Invalid right operand string.', $sentenceStr, $pos + 1 );
		
		$operator = $vocabulary->getOperatorBySymbol( $nextSymbol );
		$operands = array(
			$this->stringToSentence( $firstSentenceStr ),
			$this->stringToSentence( $secondSentenceStr )
		);
		$newSentence = Sentence::createMolecular( $operator, $operands );
		return $this->registerSentence( $newSentence );
	}
	
	/**
	 * Parses an atomic sentence string.
	 *
	 * @param string $sentenceStr
	 * @return Sentence
	 * @throws {@link ParserException}.
	 * @access private
	 */
	protected function _parseAtomic( $sentenceStr )
	{
		$subscripts 	= $this->vocabulary->getSubscriptSymbols();
		$atomicSymbols 	= $this->vocabulary->getAtomicSymbols();
		$hasSubscript 	= false !== self::strPosArr( $sentenceStr, $subscripts, 1, $match );
		
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
	 * @return string The first sentence string.
	 * @throws {@link ParserException} on parse error.
	 * @access private
	 */
	protected function _readSentence( $string )
	{	
		$vocabulary = $this->getVocabulary();
		$firstSymbolType = $vocabulary->getSymbolType( $string{0} );
		switch ( $firstSymbolType ) {
			case Vocabulary::ATOMIC :
				$hasSubscript = strlen( $string ) > 1 && 
								$vocabulary->getSymbolType( $string{1} ) === Vocabulary::CTRL_SUBSCRIPT;
				$firstSentenceStr = $hasSubscript ? substr( $string, 0, 2 ) . intval( substr( $string, 2 ))
												  : $string{0};
				break;
			case Vocabulary::PUNCT_OPEN;
				$closePos = $this->closePosFromOpenPos( $string, 0 );
				$firstSentenceStr = substr( $string, 0, $closePos + 1 );
				break;
			case 1 :
				$firstSentenceStr = $string{0} . $this->_readSentence( substr( $string, 1 ));
				break;
			default :
				throw ParserException::createWithMsgInputPos( 'Unexpected symbol type.', $string, 0 );
				break;
		}
		return $firstSentenceStr;
	}
	
	/**
	 * Makes a string representation of a sentence with outer parentheses.
	 *
	 * @param Sentence $sentence The sentence to represent.
	 * @return string The string representation with outer parentheses.
	 * @access private
	 */
	protected function _sentenceToString( Sentence $sentence )
	{
		if ( $sentence instanceof AtomicSentence ) 
			return $this->_atomicToString( $sentence );
		
		$sentenceStr = $this->_molecularToString( $sentence );
		return $sentenceStr;
	}
	
	/**
	 * Makes a string representation of an atomic sentence.
	 *
	 * @param AtomicSentence $sentence The atomic sentence to represent.
	 * @return string The string representation of the sentence.
	 * @access private
	 */
	protected function _atomicToString( AtomicSentence $sentence )
	{
		$subscript = $this->vocabulary->getSubscriptSymbols( true );
		return $sentence->getSymbol() . $subscript . $sentence->getSubscript();
	}
	
	/**
	 * Makes a string representation of a molecular sentence.
	 *
	 * @param MolecularSentence $sentence
	 * @return string
	 * @throws {@link ParserException} when trying to represent operators of arity > 2.
	 * @access private
	 */
	protected function _molecularToString( MolecularSentence $sentence )
	{
		$vocabulary		= $this->getVocabulary();
		$operator		= $sentence->getOperator();
		$operands		= $sentence->getOperands();
		$operatorSymbol = $operator->getSymbol();
		$arity			= $operator->getArity();
		$openMark 		= $vocabulary->getOpenMarks( true );
		$closeMark 		= $vocabulary->getCloseMarks( true );
		$separator		= $vocabulary->getSeparators( true );

		switch ( $arity ) {
			case 1:
				$sentenceStr = $operatorSymbol . $this->_sentenceToString( $operands[0] );
				break;
			case 2:
				$sentenceStr = $openMark . $this->_sentenceToString( $operands[0] ) .
							   $separator . $operatorSymbol . $separator .
							   $this->_sentenceToString( $operands[1] ) . $closeMark;
				break;
			default:
				throw new ParserException( 'Cannot represent sentences with operators of arity > 2.' );
				break;
		}
		return $sentenceStr;
	}
}