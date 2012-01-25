<?php
/**
 * Defines the ManyValuedSentenceNode class.
 * @package Tableaux
 * @author Douglas Owings
 */

namespace GoTableaux;

/**
 * Loads the SentenceNode parent class.
 */
require_once dirname( __FILE__) . '/SentenceNode.php';

/**
 * Loads the ManyValuedNode interface.
 */
require_once dirname( __FILE__) . '/ManyValuedNode.php';

/**
 * Represents a sentence node on a branch of a many-valued logic tableau.
 * @package Tableaux
 * @author Douglas Owings
 */
class ManyValuedSentenceNode extends SentenceNode implements ManyValuedNode
{
	/**
	 * Holds the designation flag.
	 * @var boolean
	 * @access private
	 */
	protected $isDesignated;
	
	/**
	 * Constructor.
	 *
	 * Sets the sentence and designation flag.
	 *
	 * @param Sentence $sentence The sentence to place on the node.
	 * @param boolean $isDesignated Whether the sentence is designated at $i.
	 */
	public function __construct( Sentence $sentence, $isDesignated )
	{
		parent::__construct( $sentence );
		$this->setDesignation( $isDesignated );
	}
	
	/**
	 * Sets the designation flag.
	 *
	 * @param boolean $isDesignated Whether the sentence is designated at the node.
	 * @return ManyValuedSentenceNode Current instance.
	 */
	public function setDesignation( $isDesignated )
	{
		$this->isDesignated = (bool) $isDesignated;
		return $this;
	}
	
	/**
	 * Gets whether the sentence is designated at the world index.
	 *
	 * @return boolean Whether the sentence is designated at the node.
	 */
	public function isDesignated()
	{
		return $this->isDesignated;
	}
}