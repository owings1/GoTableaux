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
 * Loads the {@link Argument} class.
 */
require_once 'Argument.php';

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
	 * Holds a reference to the parser.
	 * @var SentenceParser
	 * @access private
	 */
	protected $parser;
	
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
	 * Holds evaluated logical consequences.
	 * @var array
	 * @access private
	 */
	protected $consequences = array();
	
	/**
	 * Constructor.
	 *
	 * Sets the parser.
	 *
	 * @param SentenceParser $parser The parser for the logic instance.
	 */	
	public function __construct( SentenceParser $parser )
	{
		$parser->setVocabulary( $this->getVocabulary() );
		$this->parser = $parser;
	}
	
	/**
	 * Gets the vocabulary.
	 *
	 * Lazily instantiates vocabulary.
	 *
	 * @return Vocabulary The logic's vocabulary.
	 */
	public function getVocabulary()
	{
		if ( empty( $this->vocabulary ))
			$this->vocabulary = Vocabulary::createWithLexicon( $this->lexicon );
		return $this->vocabulary;
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
			$this->proofSystem->setVocabulary( $this->getVocabulary() );
		}
		return $this->proofSystem;
	}
}