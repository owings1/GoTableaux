<?php
/**
 * Contains base Vocabulary class.
 * @package Syntax
 * @author Douglas Owings
 */

/**
 * Loads {@link Operator} class.
 */
require_once 'Operator.php';

/**
 * Loads {@link VocabularyException} class.
 */
require_once 'VocabularyException.php';

/**
 * Represents a vocabulary.
 * @package Syntax
 * @author Douglas Owings
 */
class Vocabulary
{
	/**
	 * All vocabulary items, including operators. Key is item symbol; value is 
	 * type flag.
	 * @var array
	 * @access private
	 */
	protected $items = array();
	
	/**
	 * Operators. Key is operator symbol; value is Operator object.
	 * @var array
	 * @access private
	 */
	protected $operators = array();
	
	/**
	 * Sentence registry, used for tracking {@link Sentence} instances, to 
	 * ensure object identity with sentence form identity. Key is sentence 
	 * string relative to parser, value is sentence object.
	 * @var array
	 * @see Vocabulary::registerSentence()
	 * @access private
	 */
	protected $sentences = array();
	
	// Operator Symbols are Flagged by Positive n = arity
	const ATOMIC 			= 0;
	const PUNCT_OPEN 		= -1;
	const PUNCT_CLOSE 		= -2;
	const PUNCT_SEPARATOR 	= -3;
	const CTRL_SUBSCRIPT	= -4;
	
	/**
	 * Checks whether a type flag is of an operator.
	 *
	 * @param integer $flag The type flag.
	 * @return boolean True if flag is of an operator, false otherwise.
	 */
	public static function isOperatorType( $flag )
	{
		return $flag > 0;
	}
	
	/**
	 * Constructor. Does nothing.
	 *
	 */
	public function __construct()
	{

	}
	
	/**
	 * Adds an atomic symbol.
	 *
	 * @param string $symbol Symbol to be added (single character).
	 * @return Vocabulary The current instance, to allow chaining.
	 */
	public function addAtomicSymbol( $symbol )
	{
		return $this->addSymbol( $symbol, self::ATOMIC );
	}
	
	/**
	 * Gets the atomic symbols.
	 *
	 * @param integer $single When true, returns a single character. Default
	 *						  is false.
	 * @return array|string Array of characters, or single character.
	 */
	public function getAtomicSymbols( $single = false )
	{
		return $this->getItems( self::ATOMIC, $single );
	}
	
	/**
	 * Sets the symbol used for subscripting atomic symbols
	 *
	 * @param string $symbol Symbol to use.
	 * @return Vocabulary The current instance, to allow chaining.
	 */
	public function addSubscriptSymbol( $symbol )
	{
		return $this->addSymbol( $symbol, self::CTRL_SUBSCRIPT );
	}
	
	/**
	 * Gets the subscript symbol.
	 *
	 * @param integer $single When true, returns a single character. Default
	 *						  is false.
	 * @return array|string Array of characters, or single character.
	 */
	public function getSubscriptSymbols( $single = false )
	{
		return $this->getItems( self::CTRL_SUBSCRIPT, $single );
	}
	
	/**
	 * Adds an opening punctuation symbol, e.g. '('.
	 *
	 * @param string $symbol Symbol to be added (single character).
	 * @return Vocabulary The current instance, to allow chaining.
	 */
	public function addOpenMark( $symbol )
	{
		return $this->addSymbol( $symbol, self::PUNCT_OPEN );
	}
	
	/**
	 * Gets the open marks.
	 *
	 * @param integer $single When true, returns a single character. Default
	 *						  is false.
	 * @return array|string Array of characters, or single character.
	 */
	public function getOpenMarks( $single = false )
	{
		return $this->getItems( self::PUNCT_OPEN, $single );
	}
	
	/**
	 * Adds a closing punctuation symbol, e.g. ')'.
	 *
	 * @param string $symbol Symbol to be added (single character).
	 * @return Vocabulary The current instance, to allow chaining.
	 */
	public function addCloseMark( $symbol )
	{
		return $this->addSymbol( $symbol, self::PUNCT_CLOSE );
	}
	
	/**
	 * Gets the close marks.
	 *
	 * @param integer $single When true, returns a single character. Default
	 *						  is false.
	 * @return array|string Array of characters, or single character.
	 */
	public function getCloseMarks( $single = false )
	{
		return $this->getItems( self::PUNCT_CLOSE, $single );
	}
	
	/**
	 * Adds a separator symbol, e.g. a space character.
	 *
	 * @param string $symbol Symbol to be added (single character).
	 * @return Vocabulary The current instance, to allow chaining.
	 */
	public function addSeparator( $symbol )
	{
		return $this->addSymbol( $symbol, self::PUNCT_SEPARATOR );
	}
	
	/**
	 * Gets the separators.
	 *
	 * @param integer $single When true, returns a single character. Default
	 *						  is false.
	 * @return array|string Array of characters, or single character.
	 */
	public function getSeparators( $single = false )
	{
		return $this->getItems( self::PUNCT_SEPARATOR, $single );
	}
	
	/**
	 * Creates an operator and adds it to the vocabulary items.
	 *
	 * @param string $symbol Operator symbol.
	 * @param integer $arity Arity of operator, either 1 or 2.
	 * @param string $name Human name of the operator, e.g. 'Conjunction'.
	 * @return Operator Operator instance.
	 * @throws {@link VocabularyException} on errors.
	 */
	public function createOperator( $symbol, $arity, $name )
	{	
		if ( $arity < 1 )
			throw new VocabularyException( 'Arity of operator cannot be less than 1.' );
		
		if ( $arity > 2 )
			throw new VocabularyException( 'Arity of operator cannot be greater than 2.' );
			
		if ( empty( $name ))
			throw new VocabularyException( 'Operator name cannot be empty.' );
		
		foreach ( $this->operators as $operator )
			if ( $operator->getName() == $name )
				throw new VocabularyException ( "Operator $name is already in vocabulary." );
		
		$this->addSymbol( $symbol, $arity );
		
		$newOperator = new Operator( $symbol, $arity, $name );
		$this->operators[$symbol] = $newOperator;
		return $newOperator;
	}
	
	/**
	 * Gets all operator symbols.
	 *
	 * @param integer $arity Arity of operators to get. Default is 0, which 
	 *						 returns all operator symbols.
	 * @return array Array of operator symbols. Key is operator symbol, value 
	 * 				 is operator's arity.
	 */
	public function getOperatorSymbols( $arity = 0 )
	{
		if ( $arity > 0 ) return $this->getItems( $arity );
		$items = array();
		foreach ( $this->operators as $symbol => $op )
			$items[$symbol] = $this->items[$symbol];
		return $items;
	}
	
	/**
	 * Gets Operator object by its name.
	 *
	 * @param string $name Operator name.
	 * @return Operator Operator instance.
	 * @throws {@link VocabularyException} when no operator is found.
	 */
	public function getOperatorByName( $name )
	{
		foreach ( $this->operators as $operator )
			if ( $operator->getName() == $name ) return $operator;
		throw new VocabularyException( "No operator with name $name in vocabulary." );
	}
	
	/**
	 * Gets Operator object by its symbol.
	 *
	 * @param string $symbol Operator symbol.
	 * @return Operator Operator instance.
	 * @throws {@link VocabularyException} when $symbol is not an operator symbol.
	 */
	public function getOperatorBySymbol( $symbol )
	{
		if ( !isset( $this->operators[$symbol] ))
			throw new VocabularyException( "$symbol is not an operator symbol." );
		return $this->operators[$symbol];
	}
	
	/**
	 * Gets item type by symbol.
	 *
	 * @param string $symbol The symbol in the vocabulary.
	 * @return integer Type flag of the symbol.
	 * @throws {@link VocabularyException} when $symbol is not a vocabulary item.
	 */
	public function getSymbolType( $symbol )
	{
		if ( !isset( $this->items[$symbol] ))
			throw new VocabularyException( "$symbol is not a symbol in the vocabulary." );
		return $this->items[$symbol];
	}
	
	/**
	 * Adds a sentence to the registry. If a sentence of the same form is 
	 * already in the registry, the passed sentence is ignored, and the stored
	 * sentence is returned. Sentence form is taken from the parser.
	 *
	 * @param Sentence $sentence The sentence to add.
	 * @param SentenceParser $parser The parser from which to get the form.
	 * @return Sentence The sentence instance. If the $sentence is new to the
	 *					registry, it is returned. Otherwise, the existing 
	 *					sentence is returned.
	 */
	public function registerSentence( Sentence $sentence, SentenceParser $parser )
	{
		$sentenceForm = $parser->sentenceToString( $sentence );
		if ( !isset( $this->sentences[$sentenceForm] ))
			$this->sentences[$sentenceForm] = $sentence;
		return $this->sentences[$sentenceForm];
	}
	
	/**
	 * Adds a symbol to the vocabulary.
	 *
	 * @param string $symbol Single character symbol, novel to vocabulary instance.
	 * @param integer $typeFlag The symbol's type flag.
	 * @return Vocabulary Current instance, to allow chaining.
	 * @throws {@link VocabularyException}.
	 */
	protected function addSymbol( $symbol, $typeFlag )
	{
		if ( strlen( $symbol ) !== 1 )
			throw new VocabularyException( 'Symbol must be exactly one character long.' );
			
		if ( isset( $this->items[$symbol] ))
			throw new VocabularyException( "Symbol $symbol already exists in vocabulary." );
		
		if ( is_numeric( $symbol ))
			throw new VocabularyException( 'Symbol cannot be numeric.' );
		
		$this->items[$symbol] = (int) $typeFlag;
		
		return $this;
	}
	
	/**
	 * Gets all items in vocabulary of a type flag.
	 *
	 * @param integer|array $typeFlag Type flag or array of type flags.
	 * @param boolean $single Whether to return a single character
	 * @return array|string Array of vocabulary items, or, if $single is true,
	 *						a single character string.
	 */
	protected function getItems( $typeFlag, $single = false )
	{
		$items = array();
		if ( is_array( $typeFlag )) 
			foreach ( $typeFlag as $flag )
				$items = array_merge( $items, $this->getItems( $flag ));
		else 
			foreach ( $this->items as $item => $flag )
				if ( $flag == $typeFlag )
					$items[] = $item;
		
		return $single ? $items[0] : $items;
	}
}