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
		/* 		Operator Symbols are Flagged by Positive n = arity 	*/
		ATOMIC = 0,
		PUNCT_OPEN = -1,
		PUNCT_CLOSE = -2,
		PUNCT_SEPARATOR = -3;
	
	function __construct()
	{
		$this->n = new Doug_SimpleNotifier( 'Vocabulary' );
		$this->n->notify();
	}
	function addAtomic( $symbol, $subscripts = 0, $alwaysSubscript = false )
	{
		if ( ! $alwaysSubscript ){
			$this->addSymbol( $symbol, self::ATOMIC );
		}
		for ( $i = 0; $i < min( $subscripts, 10000 ); $i++ ){
			$this->addSymbol( $symbol . '_' . $i, self::ATOMIC );
		}
	}
	function addOpenMark( $symbol )
	{
		/*		Force single character		*/
		$this->addSymbol( substr( $symbol, 0, 1 ), self::PUNCT_OPEN );
	}
	function addCloseMark( $symbol )
	{
		/*		Force single character		*/
		$this->addSymbol( substr( $symbol, 0, 1 ), self::PUNCT_CLOSE );
	}
	function addSeparator( $symbol )
	{
		/*		Force single character		*/
		$this->addSymbol( substr( $symbol, 0, 1 ), self::PUNCT_SEPARATOR );
	}
	function createOperator( $symbol, $arity, $name )
	{
		/* 		Force arity to type integer 	*/
		$arity = intval( $arity );
		
		/* 		Check for arity > 0 		*/
		if ( $arity < 1 ){
			throw new Exception( 'arity must not be < 1' );
		}
		
		/*		Force name to type string		*/
		$name = strval( $name );
		
		/*		Check for non-empty name		*/
		if ( strlen( $name ) < 1 ){
			throw new Exception( 'operator name cannot be empty' );
		}
		
		/*		Check for name uniqueness		*/
		foreach ( $this->operators as $operator ){
			if ( $operator->getName() == $name ){
				throw new Exception ( 'operator name ' . $name . ' already exists in vocabulary' );
			}
		}
		
		/*		Add Operator Symbol to Vocabulary		*/
		$this->addSymbol( $symbol, $arity );
		
		/*		Create new Operator						*/
		$this->operators[$symbol] = new Operator( $symbol, $arity, $name );
	}
	function getOperatorByName( $name )
	{
		foreach ( $this->operators as $operator ){
			if ( $operator->getName() == $name ){
				return $operator;
			}
		}
	}
	function getSentence( $string )
	{
		/*		Trim Separators						*/
		$this->trimSeps( $string );
		
		/*		Check for non-empty string			*/
		if ( strlen( $string ) < 1 ){
			throw new Exception( 'string must be non-empty' );
		}
		
		/*		Check for atomic sentence			*/
		if ( array_key_exists( $string, $this->items ) && $this->items[$string] == self::ATOMIC ){
			
			/*		Create new atomic sentence			*/
			$sentence = new Sentence_Atomic();
			$sentence->setLabel( $string );
			
			/*		Return Sentence or Existing Instance of its Form	*/
			return $this->oldOrNew( $sentence );
		}
		
		/*										*/
		/*		Working with molecular			*/	
		/*										*/
		
		/*		Syntax check: 
					string Must Start with Open Mark and End with Close Mark		*/
		if ( 
			0 !== self::strPosArr( $string, $this->getItems( self::PUNCT_OPEN )) ||
			0 !== self::strPosArr( strrev( $string ), $this->getItems( self::PUNCT_CLOSE ))
			){
			throw new Exception( 'syntax error: moleculars must start with open mark and end with close mark' );
		}
		
		/*		Strip off first and last characters -- open/close marks; 	*/
		$string = substr( $string, 1, strlen( $string ) - 2 );
		
		//$this->n->notify( 'treated: /' . $string . '/' );

		/*		Find First Occurrance of Separator 	*/
		if ( ! $pos = self::strPosArr( $string, $this->getItems( self::PUNCT_SEPARATOR ) )){	// not allowed at position 0
			throw new Exception( 'no separator found or found at position 0' );
		}
		/*		Get Operator Symbol					*/
		$operatorSymbol = substr( $string, 0, $pos );
		
		/*		Get Operator						*/
		if ( ! $operator = $this->operators[$operatorSymbol] ){
			throw new Exception( 'unknown operator symbol ' . $operatorSymbol );
		}
		
		/*		Get string of All Operands			*/
		$fullOperandStr = substr( $string, $pos );
		
		/*		Trim Separators						*/
		$this->trimSeps( $string );
		
		/*		Split Operands into strings			*/
		$operandStrArr = $this->splitOperands( $operator, $fullOperandStr );
		
		//$this->n->notify( 'operandStrArr ' . print_r( $operandStrArr, true ));
		
		/*		Create new Molecular Sentence		*/
		$sentence = new Sentence_Molecular();
		$sentence->setOperator( $operator );
		
		/*		Add Operands						*/
		foreach ( $operandStrArr as $operandStr ){
			$operand = $this->getSentence( $operandStr );
			$sentence->addOperand( $operand );
		}
		
		/*		Return Sentence or Existing Instance of its Form	*/
		return $this->oldOrNew( $sentence );
	
	}
	
	function oldOrNew( Sentence $sentence )
	{
		$form = $this->sentenceToString( $sentence );
		if ( array_key_exists( $form, $this->sentenceForms )){
			unset( $sentence );
			return $this->sentenceForms[$form];
		}
		$this->sentenceForms[$form] = $sentence;
		return $sentence;
	}
	public function sentenceToString( Sentence $sentence )
	{
		if ( $sentence instanceof Sentence_Atomic ){
			return $sentence->getLabel();
		}
		
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
		foreach ( $operands as $operand ){
			$string .= $separator . $this->sentenceToString( $operand );
		}
		$string .= $closeMark;
		
		return $string;
		
	}
	protected function addSymbol( $symbol, $typeFlag )
	{
		/*		Force symbol to type string		*/
		$symbol = strval( $symbol );
		
		/* 		Check symbol uniqueness 		*/
		if ( array_key_exists( $symbol, $this->items )){
			throw new Exception( 'symbol ' . $symbol . ' already exists' );
		}
		
		/*		Ensure symbol does not contain characters in punctuation marks		*/
		if ( false !== self::strPosArr( $symbol, $this->getItems( array( 0 => -1, -2, -3 ) ) )){
			throw new Exception( 'symbol contains punctuation marks' );
		}
		
		$this->items[$symbol] = intval( $typeFlag );
	}
	
	protected function getItems( $typeFlag )
	{
		$items = array();
		if ( is_array( $typeFlag )){
			foreach ( $typeFlag as $flag ){
				$items = array_merge( $items, $this->getItems( $flag ));
			}
		}
		else{
			foreach ( $this->items as $item => $flag ){
				if ( $flag == $typeFlag ){
					$items[] = $item;
				}
			}
		}
		return $items;
	}
	protected function splitOperands( Operator $operator, $string )
	{
		/*		Get Arity of Operator		*/
		$arity = $operator->getArity();
		
		$operands = array();
		
		for ( $i = 0; $i < $arity; $i++ ){
			
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
			if ( 0 !== $pos ){
				
				/* 		It's Atomic. Set Position (>0) at Next Separator		*/
				if ( ! $pos = self::strPosArr( $string, $this->getItems( self::PUNCT_SEPARATOR ) )){
					throw new Exception( 'no separator found or found at position 0' );
				}
						
			} 
			else{
				/*		It's Molecular. Find Corresponding Close Mark		*/
				
				/*		Set Mark Counter to 1		*/
				$markCount = 1;
				do{
					/*		Move to Next Character		*/
					$pos++;
					/*		Set Position (>0) to Next Mark		*/
					if ( ! $pos = self::strPosArr( $string, $this->getItems( array( 0 => self::PUNCT_OPEN, self::PUNCT_CLOSE )), $pos, $char )){	// not allowed at position 0
						throw new Exception( 'either no open or close mark found, or it was found at position 0' );
					}
					if ( $this->items[$char] == self::PUNCT_OPEN ){
						/*		Another Open Mark Found. Increment Mark Counter		*/
						$markCount++;
					}
					elseif ( $this->items[$char] == self::PUNCT_CLOSE ){
						/*		Close Mark Found. Decrement	Mark Counter			*/
						$markCount--;
					}
				}
				while ( $markCount > 0 );
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
	protected function trimSeps( &$string )
	{
		$seps = $this->getItems( self::PUNCT_SEPARATOR );
		$string = trim( $string, implode( $seps ));
		return (string) $string;
	}
	protected static function strPosArr( $string, array $array, $offset = 0, &$match = null )
	{
		$string = strval( $string );
		$position = strlen( $string ) + 1;
		foreach ( $array as $value ){
			$pos = strpos( $string, $value, $offset );
			if ( false !== $pos && $pos < $position ){
				$position = $pos;
				$match = $value;
			}
			
		}
		return ( $position < ( strlen( $string ) + 1 )) ? $position : false;
	}
	
}
?>