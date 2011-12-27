<?php
/**
 * Defines the PropositionalBranch class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link Branch} parent class.
 */
require_once 'GoTableaux/Logic/ProofSystem/Tableaux/Branch.php';

/**
 * Loads the {@link SentenceNode} node class.
 * @see PropositionalBranch::createNode()
 */
require_once 'GoTableaux/Logic/ProofSystem/Tableaux/Node/SentenceNode.php';

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
		$sentence = $this->registerSentence( $sentence );
		$this->addNode( new SentenceNode( $sentence ));
		return $this;
	}
	
	/**
	 * Checks whether a sentence is on the branch.
	 *
	 * @param Sentence $sentence The sentence to search for.
	 * @return boolean Whether the branch has a node with that sentence.
	 */
	public function hasSentence( Sentence $sentence )
	{
		foreach ( $this->getNodes() as $node )
			if ( $node->getSentence() === $sentence ) return true;
		return false;
	}
}