<?php
/**
 * Defines the PropositionalBranch class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link Branch} parent class.
 */
require_once dirname( __FILE__) . '/../Branch.php';

/**
 * Loads the {@link SentenceNode} node class.
 * @see PropositionalBranch::createNode()
 */
require_once dirname( __FILE__) . '/../Node/SentenceNode.php';

/**
 * Represents a propositional logic tableau branch.
 * @package Tableaux
 * @author Douglas Owings
 */
class PropositionalBranch extends Branch
{
	/**
	 * Creates a node on the branch.
	 *
	 * @param Sentence $sentence The sentence to place on the node.
	 * @return PropositionalBranch Current instance.
	 */
	public function createNode( Sentence $sentence )
	{
		return $this->_addNode( new SentenceNode( $sentence ));
	}
	
	/**
	 * Checks whether a sentence is on the branch.
	 *
	 * @param Sentence $sentence The sentence to search for.
	 * @return boolean Whether the branch has a node with that sentence.
	 */
	public function hasSentence( Sentence $sentence )
	{
		return $this->hasNodeWithSentence( $sentence );
	}
}