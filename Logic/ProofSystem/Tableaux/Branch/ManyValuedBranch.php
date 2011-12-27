<?php
/**
 * Defines the ManyValuedBranch class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link Branch} parent class.
 */
require_once 'GoTableaux/Logic/ProofSystem/Tableaux/Branch.php';

/**
 * Loads the {@link ManyValuedSentenceNode} node class.
 * @see ManyValuedBranch::createNode()
 */
require_once 'GoTableaux/Logic/ProofSystem/Tableaux/Node/ManyValuedSentenceNode.php';

/**
 * Represents a many-valued logic tableau branch.
 * @package Tableaux
 * @author Douglas Owings
 */
class ManyValuedBranch extends Branch
{
	/**
	 * Creates a node on the branch.
	 *
	 * @param Sentence $sentence The sentence to place on the node.
	 * @param boolean $isDesignated The designation marker for the node.
	 * @return ManyValuedBranch Current instance.
	 */
	public function createNode( Sentence $sentence, $isDesignated )
	{
		$sentence = $this->registerSentence( $sentence );
		$this->addNode( new ManyValuedSentenceNode( $sentence, $isDesignated ));
		return $this;
	}
	
	/**
	 * Checks whether a sentence is on the branch.
	 *
	 * @param Sentence $sentence The sentence to search for.
	 * @param boolean $isDesignated Whether the sentence should be designated.
	 * @return boolean Whether the branch has a node with that sentence.
	 */
	public function hasSentenceWithDesignation( Sentence $sentence, $isDesignated )
	{
		foreach ( $this->getNodes() as $node )
			if ( $node->getSentence() === $sentence && $node->isDesignated() === $isDesignated ) return true;
		return false;
	}
}