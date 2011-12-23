<?php
/**
 * Contains the base Parser class.
 * @package Syntax
 * @author Douglas Owings
 */

/**
 * Loads {@link ParserException} class.
 */
require_once 'ParserException.php';

/**
 * Loads {@link StandardSentenceParser} class, which is the default parser class.
 */
require_once 'SentenceParser/StandardSentenceParser.php';

/**
 * Represents a sentence parser.
 * @package Syntax
 * @author Douglas Owings
 **/
abstract class SentenceParser
{
	/**
	 * Stores a reference to the {@link Vocabulary vocabulary} relative to 
	 * which to parse.
	 *
	 * @access private
	 * @var Vocabulary
	 */
	protected $vocabulary;
	
	/**
	 * Creates an instance.
	 *
	 * @param Vocabulary $vocabulary The vocabulary instance relative to which to parse.
	 * @param string $type Parser type. Default is 'Standard'.
	 * @return SentenceParser
	 */
	public static function createWithVocabulary( Vocabulary $vocabulary, $type = 'Standard' )
	{
		switch ( $type ) {
			case 'Standard':
			default:
				$instance = new StandardSentenceParser;
				break;
		}
		return $instance->setVocabulary( $vocabulary );
	}
	
	/**
	 * Searches a string for the first occurrence of any string in a given array.
	 *
	 * @param string $haystack The string to search.
	 * @param array $needles An array of strings to seek.
	 * @param integer $offset The offset of $haystack at which to begin.
	 * @param string &$match Holds the first match.
	 * @return integer|boolean Position of $haystack at which the first match
	 *						   was found, OR false if no match is found.
	 */
	public static function strPosArr( $haystack, array $needles, $offset = 0, &$match = null )
	{
		$position = strlen( $haystack ) + 1;
		foreach ( $needles as $needle ) {
			$pos = strpos( $haystack, $needle, $offset );
			if ( false !== $pos && $pos < $position ) {
				$position = $pos;
				$match = $needle;
			}
		}
		return ( $position < ( strlen( $haystack ) + 1 )) ? $position : false;
	}
	
	/**
	 * Sets the {@link Vocabulary vocabulary} of the parser.
	 *
	 * @param Vocabulary $vocabulary The vocabulary instance.
	 * @return SentenceParser Current instance.
	 */
	public function setVocabulary( Vocabulary $vocabulary )
	{
		$this->vocabulary = $vocabulary;
		return $this;
	}
	
	/**
	 * Gets the string representation of a {@link Sentence sentence}.
	 *
	 * @param Sentence $sentence The sentence to represent.
	 * @return string The string representation.
	 **/
	abstract public function sentenceToString( Sentence $sentence );
	
	/**
	 * Creates a {@link Sentence sentence} instance from a string.
	 * 
	 * IMPORTANT: The implementation should return the value by calling 
	 * {@link Vocabulary::registerSentence()} to preserve token identity
	 * of sentences with identical forms. A typical implementation of this
	 * function will use recursion.
	 *
	 * Example stub:
	 * <code>
	 * public function stringToSentence( $sentenceStr )
	 * {
	 *		if ($stringIsAtomicSentence) {
	 *			// base case
	 *			$newSentence = Sentence::createAtomic( $sentenceStr );
	 *			return $this->vocabulary->registerSentence( $newSentence, $this );
	 *		}
	 *		list( $operandStr, $operatorSymbol ) = someParsingFunction( $sentenceStr );
	 *		$operator = $this->vocabulary->getOperatorBySymbol( $operatorSymbol );
	 *		// recur
	 *		$operand = $this->stringToSentence( $operandStr );
	 *		$newSentence = Sentence::createMolecular( $operator, array( $operand ));
	 *		return $this->vocabulary->registerSentence( $newSentence, $this );
	 * }
	 * </code>
	 * @param string $sentenceStr The string to interpret.
	 * @return Sentence The resulting instance.
	 * @throws {@link ParserException} on any errors in parsing the input string.
	 */
	abstract public function stringToSentence( $sentenceStr );
	
	/**
	 * Trims separator (whitespace) characters from beginning and end of a string.
	 *
	 * @param string $string The string to trim.
	 * @return string The trimmed string.
	 */
	protected function trimSeparators( $string )
	{
		$trimStr = implode( '', $this->vocabulary->getSeparators() );
		return trim( $string, $trimStr );
	}
	
	/**
	 * Removes separator (whitespace) characters from a string.
	 *
	 * @param string $string The string to replace.
	 * @return string The string with all separators removed.
	 */
	protected function removeSeparators( $string )
	{
		return str_replace( $this->vocabulary->getSeparators(), '', $string );
	}
	
	/**
	 * Parses first complete parenthesized group in a string.
	 *
	 * @param string $str The string to be parsed. Must contain at least one
	 *					  parenthesized group.
	 * @param boolean $includeOuter Whether to include the outer parentheses
	 *								in the returned string. Default is false.
	 * @param integer $offset String offset at which to start searching.
	 * @return string Everything inside the first parenthesized group. Includes
	 *				  outer parentheses if $includeOuter is set to true.
	 * @throws {@link ParserException} on no parentheses in string, or parsing error.
	 */
	protected function grabParenGroup( $str, $includeOuter = false, $offset = 0 )
	{
		$openMarks 	= $this->vocabulary->getOpenMarks();
		$closeMarks = $this->vocabulary->getCloseMarks();
		$length		= strlen( $str );
		$startPos 	= self::strPosArr( $str, $openMarks, $offset );
		
		if ( $startPos === false )
			throw new ParserException( "No open punctuation found. Check parentheses." );
		
		$depth  = 1;
		$endPos = $startPos++;
		do {
			if ( ++$endPos === $length )
				throw new ParserException( "Unable to parse punctuation. Check parentheses." );
			$char = $str{$endPos};
			if ( in_array( $char, $openMarks )) $depth++;
			elseif ( in_array( $char, $closeMarks )) $depth--;
		} while ( $depth );
		
		if ( $includeOuter ) {
			$startPos -= 1;
			$endPos += 2;
		}
		
		return substr( $str, $startPos, $endPos - 1 );
	}
	
	/**
	 * Drops outer parentheses from a string, if they exist.
	 *
	 * @param string $string The string to be parsed.
	 * @return string The resulting string.
	 */
	protected function dropOuterParens( $string )
	{
		try {
			$parenGroup = $this->grabParenGroup( $string, true );
			if ( $parenGroup === $string ) {
				$string = substr( $string, 1, strlen( $string ) - 2 );
				return $this->dropOuterParens( $string );
			}
		} catch ( ParserException $e ) { }
		return $string;
	}
	
	/**
	 * Adds outer parentheses to a string.
	 *
	 * @param string $string The string to be added to.
	 * @return string The resulting string.
	 */
	protected function addOuterParens( $string )
	{
		$openMark 	= $this->vocabulary->getOpenMarks( true );
		$closeMark 	= $this->vocabulary->getCloseMarks( true );
		return $openMark . $string . $closeMark;
	}
	
	/**
	 * Finds a string's for the corresponding close mark of an open mark at the
	 * given position.
	 *
	 * @param string $string The string to scan.
	 * @param integer $openPos String position of open mark.
	 * @return integer The position of the corresponding close mark.
	 * @throws {@link ParserException} on parsing error.
	 */
	protected function closePosFromOpenPos( $string, $openPos )
	{
		$positionSymbolType = $this->vocabulary->getSymbolType( $string{$openPos} );
		if ( $positionSymbolType !== Vocabulary::PUNCT_OPEN )
			throw ParserException::createWithOptions(array(
				'message' 	=> "Open mark expected.",
				'input'		=> $string,
				'position'	=> $openPos
			));
		$openMarks 	= $this->vocabulary->getOpenMarks();
		$closeMarks = $this->vocabulary->getCloseMarks();
		$length 	= strlen( $string );
		$depth  	= 1;
		$pos 		= $openPos;
		do {
			if ( ++$pos === $length )
				throw ParserException::createWithOptions(array(
					'message' 	=> 'Unterminated open mark.',
					'input'		=> $string,
					'position'	=> $pos - 1
				));
			$char = $string{$pos};
			if ( in_array( $char, $openMarks )) $depth++;
			elseif ( in_array( $char, $closeMarks )) $depth--;
		} while ( $depth );
		return $pos;
	}
}