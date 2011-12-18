<?php
require_once 'Doug/SimpleNotifier.php';
require_once 'Operator.php'; // creates in createOperator()
require_once 'Sentence.php'; // creates in getSentence()
/**
 * Represents a vocabulary
 */
class Vocabulary
{
	protected 	$items = array(),				// (string) Symbol => (int) typeFlag
				$operators = array();			// (string) Symbol => Operator
			
	/*	Used for tracking instances		*/
	protected 	$sentenceForms = array();		// (string) Form => Sentence
	
	/*	Notifier						*/
	public $n;
	
	/*  Type Flags  */	
	const
		// Operator Symbols are Flagged by Positive n = arity
		ATOMIC 				= 0,
		PUNCT_OPEN 			= -1,
		PUNCT_CLOSE 		= -2,
		PUNCT_SEPARATOR 	= -3;
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->n = new Doug_SimpleNotifier( 'Vocabulary' );
		$this->n->notify();
	}
	/**
	 * Adds an atomic symbol
	 *
	 * @param $symbol
	 *			string, the symbol to be added
	 * @param [optional] $subscripts
	 *			int, number of subscripts allowed, default 0 (none allowed)
	 * @param [optional] $alwaysSubscript
	 *			bool, whether to force subscripting, default false
	 * @return Vocabulary
	 *			current instance
	 */
	public function addAtomic( $symbol, $subscripts = 0, $alwaysSubscript = false )
	{
		if ( ! $alwaysSubscript )
			$this->addSymbol( $symbol, self::ATOMIC );
		
		for ( $i = 0; $i < min( $subscripts, 10000 ); $i++ )
			$this->addSymbol( $symbol . '_' . $i, self::ATOMIC );
		
		return $this;
	}
	/**
	 * Adds an opening punctuation symbol, e.g. '('
	 *
	 * @param $symbol
	 *			string, the symbol to be added
	 * @return Vocabulary
	 *			current instance
	 */
	public function addOpenMark( $symbol )
	{
		/*		Force single character		*/
		$this->addSymbol( substr( $symbol, 0, 1 ), self::PUNCT_OPEN );
		return $this;
	}
	/**
	 * Adds a closing punctuation symbol, e.g. ')'
	 *
	 * @param $symbol
	 *			string, the symbol to be added
	 * @return Vocabulary
	 *			current instance
	 */
	public function addCloseMark( $symbol )
	{
		/*		Force single character		*/
		$this->addSymbol( substr( $symbol, 0, 1 ), self::PUNCT_CLOSE );
		return $this;
	}
	/**
	 * Adds a separator symbol, e.g. ' '
	 *
	 * @param $symbol
	 *			string, the symbol to be added
	 * @return Vocabulary
	 *			current instance
	 */
	public function addSeparator( $symbol )
	{
		/*		Force single character		*/
		$this->addSymbol( substr( $symbol, 0, 1 ), self::PUNCT_SEPARATOR );
		return $this;
	}
	/**
	 * Creates an operator 
	 *
	 * @param $symbol
	 *
	 * @param $arity
	 *
	 * @param $name
	 *			non-empty string, unique within vocabulary instance
	 * @return Operator
	 *			newly created Operator instance	
	 */
	public function createOperator( $symbol, $arity, $name )
	{
		/* 		Force arity to type integer 	*/
		$arity = intval( $arity );
		
		/* 		Check for arity > 0 		*/
		if ( $arity < 1 )
			throw new Exception( 'arity must not be < 1' );
		
		/*		Force name to type string		*/
		$name = strval( $name );
		
		/*		Check for non-empty name		*/
		if ( strlen( $name ) < 1 )
			throw new Exception( 'operator name cannot be empty' );
		
		/*		Check for name uniqueness		*/
		foreach ( $this->operators as $operator ) {
			if ( $operator->getName() == $name )
				throw new Exception ( 'operator name ' . $name . ' already exists in vocabulary' );
		}
		
		/*		Add Operator Symbol to Vocabulary		*/
		$this->addSymbol( $symbol, $arity );
		
		/*		Create new Operator						*/
		$newOperator = new Operator( $symbol, $arity, $name );
		$this->operators[$symbol] = $newOperator;
		return $newOperator;
	}
	/**
	 * Gets Operator object by its name
	 *
	 * @param $name
	 * 			the name of the operator
	 * @return Operator or false
	 *			false on empty search
	 */
	public function getOperatorByName( $name )
	{
		foreach ( $this->operators as $operator ){
			if ( $operator->getName() == $name )
				return $operator;
		}
		return false;
	}
	/**
	 * Gets instance of sentence. If an instance of the given representation has
	 * already been created, a reference to the existing sentence is returned.
	 * Otherwise, a new instance is created, stored and returned.
	 *
	 *	@param $string
	 *			string representation of the sentence
	 *  @return Sentence
	 *			new or existing instance
	 */
	public function getSentence( $string )
	{
		/*		Trim Separators						*/
		$this->trimSeps( $string );
		
		/*		Check for non-empty string			*/
		if ( strlen( $string ) < 1 )
			throw new Exception( 'string must be non-empty' );
		
		/*		Check for atomic sentence			*/
		if ( array_key_exists( $string, $this->items ) && $this->items[$string] == self::ATOMIC ) {
			
			/*		Create new atomic sentence			*/
			$sentence = new Sentence_Atomic();
			$sentence->setLabel( $string );
			
			/*		Return Sentence or Existing Instance of its Form	*/
			return $this->oldOrNew( $sentence );
		}
		
		/*		Working with molecular			*/
		
		/*		Syntax check: 
					string Must Start with Open Mark and End with Close Mark		*/
		if (0 !== self::strPosArr( $string, $this->getItems( self::PUNCT_OPEN )) ||
			0 !== self::strPosArr( strrev( $string ), $this->getItems( self::PUNCT_CLOSE )))
			throw new Exception( 'syntax error: moleculars must start with open mark and end with close mark' );
		
		
		/*		Strip off first and last characters -- open/close marks; 	*/
		$string = substr( $string, 1, strlen( $string ) - 2 );
		
		//$this->n->notify( 'treated: /' . $string . '/' );

		/*		Find First Occurrance of Separator 	*/
		if ( ! $pos = self::strPosArr( $string, $this->getItems( self::PUNCT_SEPARATOR ) ))
			// not allowed at position 0
			throw new Exception( 'no separator found or found at position 0' );

		/*		Get Operator Symbol					*/
		$operatorSymbol = substr( $string, 0, $pos );
		
		/*		Get Operator						*/
		if ( ! $operator = $this->operators[$operatorSymbol] )
			throw new Exception( 'unknown operator symbol ' . $operatorSymbol );
		
		/*		Get string of All Operands			*/
		$fullOperandStr = substr( $string, $pos );
		
		/*		Trim Separators						*/
		$this->trimSeps( $string );
		
		/*		Split Operands into strings			*/
		$operandStrArr = $this->splitOperands( $operator, $fullOperandStr );
		
		/*		Create new Molecular Sentence		*/
		$sentence = new Sentence_Molecular();
		$sentence->setOperator( $operator );
		
		/*		Add Operands						*/
		foreach ( $operandStrArr as $operandStr )
			$sentence->addOperand( $this->getSentence( $operandStr ));
		
		/*		Return Sentence or Existing Instance of its Form	*/
		return $this->oldOrNew( $sentence );
	
	}
	/**
	 * Returns passed sentence or stored sentence
	 *
	 * @param $sentence
	 *			Sentence
	 * @return Sentence
	 */
	public function oldOrNew( Sentence $sentence )
	{
		$form = $this->sentenceToString( $sentence );
		if ( array_key_exists( $form, $this->sentenceForms )) {
			unset( $sentence );
			return $this->sentenceForms[$form];
		}
		$this->sentenceForms[$form] = $sentence;
		return $sentence;
	}
	/**
	 * Gets a string representation of a Sentence
	 *
	 * @param $sentence
	 *			Sentence
	 * @return string
	 *			string representation of the sentence
	 */
	public function sentenceToString( Sentence $sentence )
	{
		/*		Return $sentence if it's atomic	*/
		if ( $sentence instanceof Sentence_Atomic )
			return $sentence->getLabel();
		
		/*		Get First Open Mark				*/
		$openMarks = $this->getItems( self::PUNCT_OPEN );
		$openMark = $openMarks[0];
		
		/*		Get First Close Mark			*/
		$closeMarks = $this->getItems( self::PUNCT_CLOSE );
		$closeMark = $closeMarks[0];
		
		/*		Get First Separator				*/
		$separators = $this->getItems( self::PUNCT_SEPARATOR );
		$separator = $separators[0];
		
		/*		Get Operator Symbol				*/
		$operatorSymbol = $sentence->getOperator()->getSymbol();
		
		/*		Get Operands					*/
		$operands = $sentence->getOperands();
		
		/*		Build string					*/
		$string = $openMark . $operatorSymbol;
		foreach ( $operands as $operand )
			$string .= $separator . $this->sentenceToString( $operand );
	
		$string .= $closeMark;
		
		return $string;
		
	}
	/**
	 * Adds a symbol to the vocabulary
	 *
	 * @param $symbol
	 *			string symbol to be added
	 * @param $typeFlag
	 *			int type flag of symbol
	 * @return Vocabulary
	 *			current instance
	 */
	protected function addSymbol( $symbol, $typeFlag )
	{
		/* 		Check symbol uniqueness 		*/
		if ( array_key_exists( $symbol, $this->items ))
			throw new Exception( 'symbol ' . $symbol . ' already exists' );
		
		/*		Ensure symbol does not contain characters in punctuation marks		*/
		if ( false !== self::strPosArr( $symbol, $this->getItems( array( 0 => -1, -2, -3 ) ) ))
			throw new Exception( 'symbol contains punctuation marks' );
		
		$this->items[$symbol] = intval( $typeFlag );
		
		return $this;
	}
	/**
	 * Gets all items in vocabulary of a type flag
	 *
	 * @param $typeFlag
	 * @return array
	 *			Vocabulary items
	 */
	protected function getItems( $typeFlag )
	{
		$items = array();
		if ( is_array( $typeFlag )) {
			foreach ( $typeFlag as $flag )
				$items = array_merge( $items, $this->getItems( $flag ));
		} else {
			foreach ( $this->items as $item => $flag ) {
				if ( $flag == $typeFlag )
					$items[] = $item;
			}
		}
		return $items;
	}
	/**
	 * Splits operands by string
	 *
	 * @param $operator
	 *			Operator
	 * @param $string
	 * @return array
	 */
	protected function splitOperands( Operator $operator, $string )
	{
		/*		Get Arity of Operator		*/
		$arity = $operator->getArity();
		
		$operands = array();
		
		for ( $i = 0; $i < $arity; $i++ ) {
			
			/*		Trim Separators						*/
			$this->trimSeps( $string );
			
			/*	 	If it's the last operand ...		*/ 
			if ( $i == ( $arity - 1 )){
				/*		Store the string and return 	*/
				$operands[$i] = $string;
				return $operands;
			}
			
			/*		Find the Position of the End of the Operand		*/
			
			/*		If string doesn't Start with Open Mark ...		*/
			$pos = self::strPosArr( $string, $this->getItems( self::PUNCT_OPEN ));
			if ( 0 !== $pos ) {
				
				/* 		It's Atomic. Set Position ( > 0 ) at Next Separator		*/
				if ( ! $pos = self::strPosArr( $string, $this->getItems( self::PUNCT_SEPARATOR ) ))
					throw new Exception( 'no separator found or found at position 0' );
						
			} else {
				/*		It's Molecular. Find Corresponding Close Mark		*/
				
				/*		Set Mark Counter to 1		*/
				$markCount = 1;
				do {
					
					/*		Move to Next Character		*/
					$pos++;
					
					/*		Set Position ( > 0 ) to Next Mark		*/
					if ( ! $pos = self::strPosArr( $string, $this->getItems( array( 0 => self::PUNCT_OPEN, self::PUNCT_CLOSE )), $pos, $char ))	
						// not allowed at position 0
						throw new Exception( 'either no open or close mark found, or it was found at position 0' );
					
					if ( $this->items[$char] == self::PUNCT_OPEN )
						/*		Another Open Mark Found. Increment Mark Counter		*/
						$markCount++;
					
					elseif ( $this->items[$char] == self::PUNCT_CLOSE )
						/*		Close Mark Found. Decrement	Mark Counter			*/
						$markCount--;
					
				} while ( $markCount > 0 );
				
				/*		Set Position 1 Character After Close Mark		*/
				$pos++;
			}
			
			/*		Extract Operand string			*/
			$operands[$i] = substr( $string, 0, $pos );
			
			/* 		Remove Operand string			*/
			$string = substr( $string, $pos );		
		}
		return $operands;
		
	}
	/**
	 * Trims separators from string by reference
	 *
	 * @param &$string
	 *			reference to string
	 * @return string
	 */
	protected function trimSeps( &$string )
	{
		$seps = $this->getItems( self::PUNCT_SEPARATOR );
		$string = trim( $string, implode( $seps ));
		return (string) $string;
	}
	/**
	 * Searches a string for the first occurrence of any string in a given array
	 *
	 * @param $haystack
	 *			string to be searched (haystack)
	 * @param $needles
	 *			array of strings to look for (needles)
	 * @param [optional] $offset
	 *			int position of needle to start search, default 0
	 * @param [optional] &$match
	 *			reference to variable in which to store the string that was matched
	 * @return int or false
	 *			position in haystack of $match, or false when no match
	 */
	protected static function strPosArr( $haystack, array $needles, $offset = 0, &$match = null )
	{
		$position = strlen( $haystack ) + 1;
		foreach ( $needles as $needle ){
			$pos = strpos( $haystack, $needle, $offset );
			if ( false !== $pos && $pos < $position ) {
				$position = $pos;
				$match = $needle;
			}
			
		}
		return ( $position < ( strlen( $haystack ) + 1 )) ? $position : false;
	}
	
}
?>