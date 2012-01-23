<?php
/**
 * Defines the Logic base class.
 * @package Logic
 * @author Douglas Owings
 */

/**
 * Loads the {@link ProofSystem} interface.
 */
require_once 'ProofSystem.php';

/**
 * Loads the {@link Vocabulary} class.
 */
require_once 'Syntax/Vocabulary.php';

/**
 * Loads the {@link Sentence} classes.
 */
require_once 'Syntax/Sentence.php';

/**
 * Loads the {@link SentenceParser} classes.
 */
require_once 'Syntax/SentenceParser.php';

/**
 * Loads the {@link Argument} class.
 */
require_once 'Argument.php';

/**
 * Loads the {@link Utilities} class.
 */
require_once 'Utilities.php';

/**
 * Represents a Logic.
 * @package Logic
 * @author Douglas Owings
 */
abstract class Logic {
	
	/**
	 * Defines the default lexicon for initializing the vocabulary.
	 * @var array Associate array of lexical items.
	 * @see Vocabulary::__construct()
	 */
	public $lexicon = array();
	
	/**
	 * Defines the proof system class.
	 * @var string Class name.
	 */
	public $proofSystemClass = 'ProofSystem';
	
	/**
	 * Defines the default sentence parser.
	 * @var string Class name prefix.
	 */
	public $defaultParser = 'Standard';
	
	/**
	 * Holds a reference to the vocabulary.
	 * @var Vocabulary
	 * @access private
	 */
	protected $vocabulary;
	
	/**
	 * Holds a reference to the proof system.
	 * @var ProofSystem
	 * @access private
	 */
	protected $proofSystem;
	
	/**
	 * Holds the singleton instances of the logics.
	 * @var array
	 * @access private
	 */
	protected static $instances = array();
	
	/**
	 * Gets the singleton instance of a particular logic.
	 *
	 * If the logic class is not loaded, it will attempt to load automatically.
	 *
	 * @param string $name The name of the logic.
	 * @return Logic The instance of the logic.
	 */
	public static function getInstance( $name )
	{
		if ( !array_key_exists( $name, self::$instances )) {
			if ( !class_exists( $name )) {
				try {
					require_once "GoTableaux/Logics/$name/$name.php";
				} catch( Exception $e ) {
					throw new Exception( "Unable to auto-load class $name." );
				}
			}
			$instance = new $name;
			if ( !$instance instanceof Logic ) 
				throw new Exception( "$name does not inherit from the Logic class." );
			self::$instances[$name] = $instance;
		}
		return self::$instances[$name];
	}
	
	/**
	 * Constructor. Final & private, for forcing single instances for each logic.
	 */
	final private function __construct()
	{
		
	}
	
	/**
	 * Initializes the vocabulary.
	 *
	 * This should be run to reload any changes to the lexicon. This creates a
	 * new {@link Vocabulary} object, and so also clears the set of sentences.
	 *
	 * @return Logic Current instance.
	 */
	public function initVocabulary()
	{
		$this->vocabulary = Vocabulary::createWithLexicon( $this->lexicon );
		return $this;
	}
	
	/**
	 * Gets the vocabulary.
	 *
	 * Lazily initializes the vocabulary.
	 *
	 * @return Vocabulary The logic's vocabulary.
	 */
	public function getVocabulary()
	{
		if ( empty( $this->vocabulary )) $this->initVocabulary();
		return $this->vocabulary;
	}
	
	/**
	 * Gets the default sentence parser.
	 *
	 * Lazily instantiates {@link StandardSentenceParser}.
	 *
	 * @return SentenceParser The default sentence parser.
	 */
	public function getDefaultParser()
	{
		if ( !$this->defaultParser instanceof SentenceParser ) {
			$className = $this->defaultParser . "SentenceParser";
			if ( !class_exists( $className )) {
				try {
					require_once "Syntax/SentenceParser/$className.php";
				} catch( Exception $e ) {
					throw new Exception( "Unable to auto-load class $className." );
				}
			}
			$parser = new $className;
			if ( !$parser instanceof SentenceParser )
				throw new Exception( "$className does not inherit from SentenceParser." );
			$this->defaultParser = $parser;
		}
		return $this->defaultParser;
	}
	/**
	 * Gets the proof system.
	 *
	 * Lazily instantiates proof system.
	 * 
	 * @return ProofSystem The logic's proof system.
	 */
	public function getProofSystem()
	{
		if ( empty( $this->proofSystem )) {
			$this->proofSystem = new $this->proofSystemClass;
			$this->proofSystem->setLogic( $this );
		}
		return $this->proofSystem;
	}
	
	/**
	 * Gets an operator from the logic's vocabulary.
	 *
	 * @param string $name The name of the operator.
	 * @return Operator The operator object.
	 * @see Vocabulary::getOperatorByName()
	 */
	public function getOperator( $name )
	{
		return $this->getVocabulary()->getOperatorByName( $name );
	}
	
	/**
	 * Parses a sentence string.
	 *
	 * @param string $string The sentence string to parse.
	 * @param SentenceParser $parser The parser to do the parsing.
	 * @return Sentence The sentence instance, registered in the logic's vocabulary.
	 */
	public function parseSentence( $string, $parser = null )
	{
		if ( $parser === null ) $parser = $this->getDefaultParser();
		if ( !$parser instanceof SentenceParser )
			throw new Exception( "Parser must be instance of SentenceParser" );
		$vocabulary = $this->getVocabulary();
		$sentence = $parser->stringToSentence( $string, $vocabulary );
		return $vocabulary->registerSentence( $sentence );
	}
	
	/**
	 * Parses an array of sentence strings.
	 *
	 * @param array $array Array of sentence strings to parse.
	 * @param SentenceParser $parser The parser to do the parsing.
	 * @return array Array of {@link Sentence}s.
	 */
	public function parseSentences( array $array, $parser = null )
	{
		if ( $parser === null ) $parser = $this->getDefaultParser();
		if ( !$parser instanceof SentenceParser )
			throw new Exception( "Parser must be instance of SentenceParser" );
		return array_map( array( $this, 'parseSentence' ), $array, array( $parser ));
	}
	
	/**
	 * Parses an argument.
	 *
	 * @param string|array $premiseStrings The premise strings.
	 * @param string $conclusionString Non-empty conclusion string.
	 * @param SentenceParser $parser The parser to do the parsing.
	 * @return Argument The argument instance.
	 */
	public function parseArgument( $premiseStrings, $conclusionString, $parser = null )
	{
		if ( $parser === null ) $parser = $this->getDefaultParser();
		if ( !$parser instanceof SentenceParser )
			throw new Exception( "Parser must be instance of SentenceParser" );
		$premises 	= $this->parseSentences( (array) $premiseStrings, $parser );
		$conclusion = $this->parseSentence( $conclusionString, $parser );
		return Argument::createWithPremisesAndConclusion( $premises, $conclusion );
	}
	
	/**
	 * Applies an operator to some operands to generate a new sentence.
	 *
	 * @param string|Operator $operatorOrName The name of the operator, or the
	 *										  operator object.
	 * @param array|Sentence $operands The sentence(s) to which to apply the operator.
	 * @return MolecularSentence The resulting sentence.
	 * @throws Exception on type error.
	 */
	public function applyOperatorToOperands( $operatorOrName, $operands )
	{
		if ( is_string( $operatorOrName )) $operator = $this->getOperator( $operatorOrName );
		else {
			if ( !$operatorOrName instanceof Operator ) throw new Exception( 'Operator must be instance of Operator.' );
			$operator = $operatorOrName;
		}
		if ( !is_array( $operands )) $operands = array( $operands );
		$sentence = Sentence::createMolecular( $operator, $operands );
		return $this->getVocabulary()->registerSentence( $sentence );
	}
	
	/**
	 * Negates a sentence.
	 *
	 * Requires an operator named 'Negation' in the language.
	 *
	 * @param Sentence $sentence The sentence to negate.
	 * @return Sentence The negated sentence.
	 */
	public function negate( Sentence $sentence )
	{
		return $this->applyOperatorToOperands( 'Negation', $sentence );
	}
	
}