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

use \GoTableaux\Sentence\Atomic as AtomicSentence;
use \GoTableaux\Sentence\Molecular as MolecularSentence;

/**
 * Represents a Logic.
 * @package GoTableaux
 */
abstract class Logic {
	
	public $inheritOperatorsFrom = null;
	
	public $operatorArities = array();
	
	protected $operators = array();
	
	protected $sentences = array();
	
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
		$class = get_class( $this ) . '\\ProofSystem';
		$this->proofSystem = new $class( $this );
		
		if ( !empty( $this->inheritOperatorsFrom ))
			foreach ( (array) $this->inheritOperatorsFrom as $logicName ) {
				$otherLogic = self::getInstance( $logicName );
				$this->operatorArities = array_merge( $otherLogic->operatorArities, $this->operatorArities );
			}
			
		foreach ( $this->operatorArities as $name => $arity ) 
			$this->operators[ $name ] = new Operator( $name, $arity );
	}
	
	/**
	 * Gets the name of the Logic.
	 *
	 * @return string The name of the logic.
	 */
	public function getName()
	{
		return Utilities::getBaseClassName( $this );
	}
	
	/**
	 * Gets a new sentence parser of the specified type.
	 *
	 * @param string $type Type of parser to instantiate. Default is 'Standard'.
	 * @return SentenceParser The initialized sentence parser.
	 */
	public function getParser( $type = 'Standard' )
	{
		return SentenceParser::getInstance( $this, $type );
	}
	
	/**
	 * Gets the proof system.
	 * 
	 * @return ProofSystem The logic's proof system.
	 */
	public function getProofSystem()
	{
		return $this->proofSystem;
	}
	
	/**
	 * Gets an operator.
	 *
	 * @param string $name The name of the operator.
	 * @return Operator The operator object.
	 */
	public function getOperator( $name )
	{
		if ( !array_key_exists( $name, $this->operators ))
			throw new Exception( "Operator $name does not exist." );
		return $this->operators[ $name ];
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
		return $this->registerSentence( $sentence );
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
	 * @param string|array $premiseStrings The premise string(s).
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
		return $this->registerSentence( $sentence );
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
		foreach ( $this->sentences as $existingSentence )
			if ( Sentence::sameForm( $existingSentence, $sentence )) return $existingSentence;
		
		if ( $sentence instanceof AtomicSentence ) {	
			$newSentence = clone $sentence;
		} elseif ( $sentence instanceof MolecularSentence ) {
			$operands = array_map( array( $this, 'registerSentence' ), $sentence->getOperands() );
			$operator = $this->getOperator( $sentence->getOperatorName() );
			$newSentence = Sentence::createMolecular( $operator, $operands );
		} else throw new Exception( 'Unknown Sentence type: ' . get_class( $sentence ));
		$this->sentences[] = $newSentence;		
		return $newSentence;
	}
	
	/**
	 * Gets the set of sentences.
	 *
	 * @return array The sentences.
	 */
	public function getSentences()
	{
		return $this->sentences;
	}
}