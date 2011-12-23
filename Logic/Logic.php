<?php
/**
 * Defines the Logic base class.
 * @package Logic
 * @author Douglas Owings
 */

/**
 * Loads the {@link LogicException} class.
 */
require_once 'LogicException.php';

/**
 * Loads the {@link ProofSystem} interface.
 */
require_once 'ProofSystem.php';

/**
 * Loads the {@link Vocabulary} class.
 */
require_once 'Syntax/Vocabulary.php';

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
	 * Initializes the vocabulary and the proof system.
	 */
	public function __construct()
	{
		$this->vocabalary = Vocabulary::createWithLexicon( $this->lexicon );
		$this->proofSystem = new $this->proofSystemClass;
		$this->proofSystem->setVocabulary( $this->vocabulary );
	}
	
	/**
	 * Gets the vocabulary.
	 *
	 * @return Vocabulary The vocabulary.
	 */
	public function getVocabulary()
	{
		return $this->vocabulary;
	}
	

}