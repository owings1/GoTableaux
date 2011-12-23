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
	public $defaultLexicon = array();
	
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
	 * Initializes the vocabulary, with optional overrides and additions to the
	 * default lexicon.
	 *
	 * @param array $lexicon Lexicon overrides.
	 * @see Logic::initVocabulary()
	 */
	public function __construct()
	{
		$this->initVocabulary();
		$this->initProofSystem();
	}
	
	/**
	 * Initializes the vocabulary. 
	 *
	 * Called when changes are made to vocabulary properties after instance 
	 * creation.
	 *
	 * @param array $lexicon Associative array of vocabulary properties to
	 *						 override. Any property not overridden will default
	 *						 to the object's defined properties.
	 * @return Logic Current instance.
	 */
	public function initVocabulary( array $lexicon )
	{
		$lexicon 	= array_merge( $this->defaultLexicon, $lexicon );
		$vocabulary = Vocabulary::createWithLexicon( $lexicon );
		return $this->setVocabulary( $vocabulary );
	}
	
	/**
	 * Sets the vocabulary to use. 
	 * 
	 * This is done automatically by the construct, and when 
	 * {@link Logic::initVocabulary() initVocabulary()} is called.
	 *
	 * @param Vocabulary $vocabulary The vocabulary instance to use.
	 * @return Logic Current instance.
	 */
	public function setVocabulary( Vocabulary $vocabulary )
	{
		$this->vocabulary = $vocabulary;
		return $this;
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
	
	/**
	 * Initialize the proof system. 
	 *
	 * Called by constructor. Uses class name in {@link $proofSystemClass}.
	 *
	 * @param string $proofSystemClass The class name of the proof system to
	 *								   use. Default is $this->proofSystemClass.
	 * @return Logic Current instance.
	 * @throws {@link LogicException} on empty class name or class not found.
	 */
	public function initProofSystem( $proofSystemClass = null )
	{
		if ( empty( $proofSystemClass )) {
			$proofSystemClass = $this->proofSystemClass;
			if ( empty( $proofSystemClass ))
				throw new LogicException( 'No class name specified for proof system. Set Logic::proofSystemClass.' );
		}
		
		if ( !class_exists( $proofSystemClass ))
			throw new LogicException( "Class $proofSystemClass not found." );
		
		$proofSystem = new $proofSystemClass;
	}
	
}