<?php
/**
 * Defines the ModalSentenceNode class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link SentenceNode} parent class.
 */
require_once 'SentenceNode.php';

/**
 * Represents a modal sentence tableau node.
 *
 * A modal sentence node has a sentence and a "world" integer index.
 * 
 * @package Tableaux
 * @author Douglas Owings
 */
class ModalSentenceNode extends SentenceNode implements ModalNode
{
	/**
	 * Holds a reference to the "world" index.
	 * @var integer
	 */
	protected $i;
	
	/**
	 * Constructor.
	 *
	 * Sets the sentence by calling the parent constructor, and sets the index.
	 *
	 * @param Sentence $sentence The sentence to place on the node.
	 * @param integer $i The index of the node.
	 */
	public function __construct( Sentence $sentence, $i )
	{
		parent::__construct( $sentence );
		$this->setI( $i );
	}
	
	/**
	 * Sets the index.
	 *
	 * @param integer $i The index.
	 * @return ModalSentenceNode Current instance.
	 */
	public function setI( $i )
	{
		$this->i = (int) $i;
	}
	
	/**
	 * Gets the index.
	 *
	 * @return integer The index.
	 */
	public function getI()
	{
		return $this->i;
	}
}
?>