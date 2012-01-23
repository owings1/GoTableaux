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
require_once 'GoTableaux/Logic/Exceptions/VocabularyException.php';

/**
 * Represents a vocabulary.
 * @package Syntax
 * @author Douglas Owings
 */
class Vocabulary
{
	/**
	 * Holds a hash of all vocabulary items, including operators. 
	 *
	 * Key is item symbol; value is type flag.
	 * @var array
	 * @access private
	 */
	protected $items = array();
	
	/**
	 * Holds the operator symbols.
	 *
	 * Key is symbol, value is operator name.
	 * @var array 
	 * @access private
	 */
	protected $operatorSymbols = array();
	
	/**
	 * Holds the operators. 
	 *
	 * Key is operator name; value is {@link Operator operator} object.
	 * @var array 
	 * @access private
	 */
	protected $operators = array();
	
	/**
	 * Holds the set of sentences.
	 *
	 * This is used for tracking {@link Sentence} instances, to ensure object
	 * identity with sentence form identity. Key is sentence string relative to
	 * a {@link SentenceParser parser}, value is {@link Sentence sentence} object.
	 * @var array
	 * @see Vocabulary::registerSentence()
	 * @access private
	 */
	protected $sentences = array();
	
	/**
	 * Holds the set of {@link AtomicSentence atomic sentences}.
	 * @var array
	 */
	protected $atomicSentences = array();
	
	// Operator Symbols are Flagged by Positive n = arity
	const OPER_TERNARY		= 3;
	const OPER_BINARY		= 2;
	const OPER_UNARY		= 1;
	const ATOMIC 			= 0;
	const PUNCT_OPEN 		= -1;
	const PUNCT_CLOSE 		= -2;
	const PUNCT_SEPARATOR 	= -3;
	const CTRL_SUBSCRIPT	= -4;
	
	/**
	 * Creates an instance with a lexicon.
	 *
	 * @param array $lexicon Array of lexical items. For format, see the
	 *						 {@link Vocabulary::__consruct() constructor}.
	 * @return Vocabulary The created instance.
	 */
	public static function createWithLexicon( array $lexicon )
	{
		return new self( $lexicon );
	}
	
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
	 * Constructor. Initializes lexicon.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $vocabulary = new Vocabulary( array(
	 *		'openMarks' => array('(', '['),
	 *		'closeMarks' => array(')', ']'),
	 *		'atomicSymbols' => array('A', 'B', 'C'),
	 *		'subscripts' => array('_'),
	 *		'separators' => array(' '),
	 *		'operatorSymbols' => array(
	 *			'&' => array('Conjunction' => 2),
	 *			'~' => array('Negation'  => 1)
	 *		)
	 * ));
	 * ?>
	 * </code>
	 * @param array $lexicon Structured array of lexical items.
	 */
	public function __construct( array $lexicon )
	{
		$this->insertLexicon( $lexicon );
	}
	
	/**
	 * Adds an atomic symbol.
	 *
	 * @param string $symbol Symbol to be added (single character).
	 * @return Vocabulary The current instance.
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
	 * @return Vocabulary The current instance.
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
	 * @return Vocabulary The current instance.
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
	 * @return Vocabulary The current instance.
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
	 * @return Vocabulary The current instance.
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
		
		if ( isset( $this->operators[$name] ))
			throw new VocabularyException ( "Operator $name is already in vocabulary." );
		
		$newOperator = new Operator( $name, $arity );
		$this->addSymbol( $symbol, $arity );
		$this->operators[$name] = $newOperator;
		$this->operatorSymbols[$symbol] = $name;
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
		foreach ( array_keys( $this->operatorSymbols ) as $symbol )
			$items[$symbol] = $this->items[$symbol];
		return $items;
	}
	
	/**
	 * Gets Operator object by its name.
	 *
	 * @param string $name Operator name.
	 * @return Operator Operator object.
	 * @throws {@link VocabularyException} when no operator is found.
	 */
	public function getOperatorByName( $name )
	{
		if ( !isset( $this->operators[$name] ))
			throw new VocabularyException( "No operator with name $name in vocabulary." );
		return $this->operators[$name];
	}
	
	/**
	 * Gets Operator object by its symbol.
	 *
	 * @param string $symbol Operator symbol.
	 * @return Operator Operator instance.
	 * @throws {@link VocabularyException} when $symbol is not an operator 
	 * 		   symbol in the vocabulary.
	 */
	public function getOperatorBySymbol( $symbol )
	{
		if ( !isset( $this->operatorSymbols[$symbol] ))
			throw new VocabularyException( "$symbol is not an operator symbol in the vocabulary." );
		$operatorName = $this->operatorSymbols[$symbol];
		return $this->getOperatorByName( $operatorName );
	}
	
	/**
	 * Gets the symbol used for a particular operator.
	 *
	 * @param Operator|string Operator object or name of operator.
	 * @return string Operator symbol.
	 * @throws {@link VocabularyException} when operator is not in the vocabulary.
	 */
	public function getSymbolForOperator( $operator )
	{
		if ( !$operator instanceof Operator ) $operator = $this->getOperatorByName( $operator ); 
		$symbols = $this->getOperatorSymbols( $operator->getArity() );
		foreach ( $symbols as $symbol )
			if ( $this->getOperatorBySymbol( $symbol ) === $operator ) return $symbol;
		throw new VocabularyException( "Operator not found in vocabulary." );		
	}
	
	/**
	 * Gets item type by symbol.
	 *
	 * @param string $symbol The symbol in the vocabulary.
	 * @return integer Type flag of the symbol.
	 * @throws {@link VocabularyException} when symbol is not a vocabulary item.
	 */
	public function getSymbolType( $symbol )
	{
		if ( !isset( $this->items[$symbol] ))
			throw new VocabularyException( "$symbol is not a symbol in the vocabulary." );
		return $this->items[$symbol];
	}
		
	/**
	 * Adds a sentence to the vocabulary, maintaining uniqueness.
	 *
	 * If the sentence, or one of the same form is already in the vocabulary,
	 * then that sentence is returned. Otherwise the passed sentence is
	 * returned. 
	 *
	 * @param Sentence $sentence The sentence to add.
	 * @return Sentence Old or new sentence.
	 */
	public function registerSentence( Sentence $sentence )
	{
		//if ( in_array( $sentence, $this->sentences, true )) return $sentence;
		foreach ( $this->sentences as $existingSentence ) {
			debug( $existingSentence === $sentence );
			debug( $existingSentence, $sentence );
			if ( $sentence === $existingSentence ) {
				debug( "Existing Sentence found" );
				return $existingSentence;
			}
		}
			
		
		
		if ( $sentence instanceof AtomicSentence ) {
			
			foreach ( $this->atomicSentences as $atomicSentence )
				if ( $atomicSentence->getSymbol() === $sentence->getSymbol() &&
					 $atomicSentence->getSubscript() === $sentence->getSubscript()
				) return $atomicSentence;
			
			$atomicSymbol = $sentence->getSymbol();
			if ( $this->getSymbolType( $atomicSymbol ) !== self::ATOMIC )
				throw new VocabularyException( "$atomicSymbol is not in the atomic symbols." );
			$this->atomicSentences[] = $sentence;
			$this->sentences[] = $sentence;
			return $sentence;
		}
		$operator = $sentence->getOperator();
		
		
			
		$oldOperands = $sentence->getOperands();
		$newOperands = array();
		foreach ( $oldOperands as $operand ) $newOperands[] = $this->registerSentence( $operand );
		$sentence->setOperands( $newOperands );
		foreach ( $this->sentences as $s )
			if ( $s->getOperatorName() === $operator->getName() ) {
				$operands = $s->getOperands();
				$isSame = false;
				foreach ($operands as $key => $operand) 
					if ( $operand === $newOperands[$key] ) $isSame = true;
				if ( $isSame ) return $s;
			}
		$this->sentences[] = $sentence;
		debug( $this->sentences );
		
		return $sentence;
	}
	
	/**
	 * Gets the set of sentences.
	 *
	 * @return array Array of {@link Sentence}s.
	 */
	public function getSentences()
	{
		return $this->sentences;
	}
	
	/**
	 * Adds an array of lexical items to the vocabulary.
	 *
	 * @param array $lexicon Array of lexical items. For format, see the
	 *						 {@link Vocabulary::__construct() constructor}.
	 * @return Vocabulary Current instance.
	 */
	protected function insertLexicon( array $lexicon )
	{
		if ( !empty( $lexicon['openMarks'] ))
			foreach ( $lexicon['openMarks'] as $mark ) $this->addOpenMark( $mark );
		
		if ( !empty( $lexicon['closeMarks'] ))
			foreach ( $lexicon['closeMarks'] as $mark ) $this->addCloseMark( $mark );
		
		if ( !empty( $lexicon['atomicSymbols'] ))
			foreach ( $lexicon['atomicSymbols'] as $symbol ) $this->addAtomicSymbol( $symbol );
		
		if ( !empty( $lexicon['subscripts'] ))
			foreach ( $lexicon['subscripts'] as $subscript ) $this->addSubscriptSymbol( $subscript );
		
		if ( !empty( $lexicon['separators'] ))
			foreach ( $lexicon['separators'] as $separator ) $this->addSeparator( $separator );
		
		if ( !empty( $lexicon['operatorSymbols'] ))
			foreach ( $lexicon['operatorSymbols'] as $symbol => $operator ) {
				list( $operatorName ) = array_keys( $operator );
				$arity = $operator[$operatorName];
				$this->createOperator( $symbol, $arity, $operatorName );
			}
		return $this;
	}
	
	/**
	 * Adds a symbol to the vocabulary.
	 *
	 * @param string $symbol Single character symbol, novel to vocabulary instance.
	 * @param integer $typeFlag The symbol's type flag.
	 * @return Vocabulary Current instance.
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
		if ( is_array( $typeFlag )) foreach ( $typeFlag as $flag )
			$items = array_merge( $items, $this->getItems( $flag ));
		else foreach ( $this->items as $item => $flag )
			if ( $flag == $typeFlag ) $items[] = $item;
		return $single ? $items[0] : $items;
	}
}