<?php
/**
 * Defines the ManyValuedSentenceNode class.
 * @package Tableaux
 * @author Douglas Owings
 */

namespace GoTableaux\Proof\TableauNode\Sentence;

/**
 * Represents a sentence node on a branch of a many-valued logic tableau.
 * @package Tableaux
 * @author Douglas Owings
 */
class ManyValued extends \GoTableaux\Proof\TableauNode\Sentence implements \GoTableaux\Proof\TableauNode\ManyValued
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
	public function __construct( \GoTableaux\Sentence $sentence, $isDesignated )
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