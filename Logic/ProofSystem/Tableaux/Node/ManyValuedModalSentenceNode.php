<?php
/**
 * Defines the ManyValuedModalSentenceNode class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the ModalSentenceNode parent class.
 */
require_once dirname( __FILE__) . '/ModalSentenceNode.php';

/**
 * Loads the ManyValuedNode interface.
 */
require_once dirname( __FILE__) . '/ManyValuedNode.php';

/**
 * Represents a sentence node on a branch of a many-valued modal logic tableau.
 * @package Tableaux
 * @author Douglas Owings
 */
class ManyValuedModalSentenceNode extends ModalSentenceNode implements ManyValuedNode
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
	 * Sets the sentence, index, and designation flag.
	 *
	 * @param Sentence $sentence The sentence to place on the node.
	 * @param integer $i The "world" index of the node.
	 * @param boolean $isDesignated Whether the sentence is designated at $i.
	 */
	public function __construct( Sentence $sentence, $i, $isDesignated )
	{
		parent::__construct( $sentence, $i );
		$this->setDesignation( $isDesignated );
	}
	
	/**
	 * Sets the designation flag.
	 *
	 * @param boolean $isDesignated Whether the sentence is designated at the 
	 *								world index of the node.
	 * @return DesignationModalSentenceNode Current instance.
	 */
	public function setDesignation( $isDesignated )
	{
		$this->isDesignated = (bool) $isDesignated;
		return $this;
	}
	
	/**
	 * Gets whether the sentence is designated at the world index.
	 *
	 * @return boolean Whether the sentence is designated at the world index.
	 */
	public function isDesignated()
	{
		return $this->isDesignated;
	}
}