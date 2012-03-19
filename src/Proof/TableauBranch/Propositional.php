<?php
/**
 * Defines the PropositionalBranch class.
 * @package Tableaux
 * @author Douglas Owings
 */

namespace GoTableaux\Proof\TableauBranch;

use \GoTableaux\Sentence as Sentence;
use \GoTableaux\Proof\TableauNode\Sentence as SentenceNode;

/**
 * Represents a propositional logic tableau branch.
 * @package Tableaux
 * @author Douglas Owings
 */
class Propositional extends \GoTableaux\Proof\TableauBranch
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