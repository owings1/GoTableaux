<?php
/**
 * Defines the SentenceNode class.
 * @package Tableaux
 * @author Douglas Owings
 */

namespace GoTableaux\Proof\TableauNode;

/**
 * Represents a sentence tableau node.
 * @package Tableaux
 * @author Douglas Owings
 */
class Sentence extends \GoTableaux\Proof\TableauNode
{
	/**
	 * Holds a reference to the sentence on the node
	 * @var Sentence
	 */
	protected $sentence;
	
	/**
	 * Constructor.
	 *
	 * Sets the sentence.
	 *
	 * @param Sentence $sentence The sentence to place on the node.
	 */
	public function __construct( \GoTableaux\Sentence $sentence )
	{
		$this->setSentence( $sentence );
	}
	
	/**
	 * Sets the sentence.
	 *
	 * @param Sentence $sentence The sentence to place on the node.
	 * @return SentenceNode Current instance.
	 */
	public function setSentence( \GoTableaux\Sentence $sentence )
	{
		$this->sentence = $sentence;
	}
	
	/**
	 * Gets the sentence.
	 *
	 * @return Sentence The sentence on the node.
	 */
	public function getSentence()
	{
		return $this->sentence;
	}
}