<?php
/**
 * GoTableaux. A multi-logic tableaux generator.
 * Copyright (C) 2012  Douglas Owings
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed WITHOUT ANY WARRANTY. 
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/agpl-3.0.html>.
 */
/**
 * Defines the Logic base class.
 * @package GoTableaux
 */

namespace GoTableaux;

/**
 * Represents a Logic.
 * @package GoTableaux
 */
abstract class Logic {
	
	/**
	 * Defines the default operator symbols for the lexicon.
	 * @var array Key is operator name, value is operator symbol.
	 * @see Logic::initVocabulary()
	 */
	public static $defaultOperatorSymbols = array(
		'Negation' => '~',
		'Conjunction' => '&',
		'Disjunction' => 'V',
		'Material Conditional' => '>',
		'Material Biconditional' => '<',
		'Conditional' => '$',
	);
	
	/**
	 * Defines the default lexicon for initializing the vocabulary.
	 * @var array Associate array of lexical items.
	 * @see Vocabulary::__construct()
	 */
	public $lexicon = array();

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
			$class = __NAMESPACE__ . '\\Logic\\' . $name;
			self::$instances[$name] = new $class;
		}
		return self::$instances[$name];
	}
	
	/**
	 * Constructor. Final & private, for forcing single instances for each logic.
	 */
	final private function __construct()
	{
		$this->getProofSystem();
	}
	
	/**
	 * Gets the name of the Logic.
	 *
	 * @return string The name of the logic.
	 */
	public function getName()
	{
		$nameParts = explode( '\\', get_class( $this ));
		return array_pop( $nameParts );
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
		$lexicon = $this->lexicon;
		if ( !empty( $this->inheritLexiconFrom ))
			foreach ( (array) $this->inheritLexiconFrom as $logicName ) {
				$otherLogic = self::getInstance( $logicName );
				$lexicon = array_merge_recursive( $otherLogic->lexicon, $lexicon );
			}
		$lexicon['operatorSymbols'] = array();
		if ( !empty( $lexicon['operators'] ))
			foreach ( $lexicon['operators'] as $name => $arity )
				$lexicon['operatorSymbols'][self::$defaultOperatorSymbols[$name]] = array( $name => $arity );
		unset( $lexicon['operators'] );
		$this->vocabulary = Vocabulary::createWithLexicon( $lexicon );
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
	 * Gets a new sentence parser of the specified type.
	 *
	 * @param string $type Type of parser to instantiate. Default is 'Standard'.
	 * @return SentenceParser The initialized sentence parser.
	 */
	public function getParser( $type = 'Standard' )
	{
		return SentenceParser::getInstance( $this->getVocabulary(), $type );
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
			$class 	=  get_class( $this ) . '\\ProofSystem';
			$this->proofSystem = new $class( $this );
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
	 * @param string $parserType The parser type to do the parsing. Default is 'Standard'.
	 * @return Sentence The sentence instance, registered in the logic's vocabulary.
	 */
	public function parseSentence( $string, $parserType = 'Standard' )
	{
		$sentence = $this->getParser( $parserType )->stringToSentence( $string );
		return $this->getVocabulary()->registerSentence( $sentence );
	}
	
	/**
	 * Parses an array of sentence strings.
	 *
	 * @param array $strings Array of sentence strings to parse.
	 * @param string $parserType The parser type to do the parsing. Default is 'Standard'.
	 * @return array Array of {@link Sentence}s.
	 */
	public function parseSentences( array $strings, $parserType = 'Standard' )
	{
		$sentences = array();
		foreach ( $strings as $key => $string )
			$sentences[$key] = $this->parseSentence( $string, $parserType );
		return $sentences;
	}
	
	/**
	 * Parses an argument.
	 *
	 * @param string|array $premiseStrings The premise strings.
	 * @param string $conclusionString Non-empty conclusion string.
	 * @param string $parserType The parser type to do the parsing. Default is 'Standard'.
	 * @return Argument The argument instance.
	 */
	public function parseArgument( $premiseStrings, $conclusionString, $parserType = 'Standard' )
	{
		$premises 	= $this->parseSentences( (array) $premiseStrings, $parserType );
		$conclusion = $this->parseSentence( $conclusionString, $parserType );
		return Argument::createWithPremisesAndConclusion( $premises, $conclusion );
	}
	
	/**
	 * Builds a proof for an argument with the proof system.
	 *
	 * @param Argument $argument The argument for which to build the proof.
	 * @return Proof $proof The resulting (putative) proof.
	 */
	public function constructProofForArgument( Argument $argument )
	{
		return $this->getProofSystem()->constructProofForArgument( $argument );
	}
	
	/**
	 * Applies an operator to some operands to generate a new sentence.
	 *
	 * @param string|Operator $operatorOrName The name of the operator, or the
	 *										  operator object.
	 * @param array|Sentence $operands The sentence(s) to which to apply the operator.
	 * @return Sentence\Molecular The resulting sentence.
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