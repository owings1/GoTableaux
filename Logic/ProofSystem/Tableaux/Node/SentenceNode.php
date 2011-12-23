<?php
/**
 * Defines the SentenceNode class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link Node} base class.
 */
require_once '../Node.php';

/**
 * Represents a sentence tableau node.
 * @package Tableaux
 * @author Douglas Owings
 */
class SentenceNode extends Node
{
	/**
	 * Holds a reference to the sentence on the node
	 * @var Sentence
	 */
	protected $sentence;
	
	/**
	 * Gets all modal sentence nodes in an array of nodes, whose sentence's
	 * operator has a particular name.
	 *
	 * @param array $searchNodes An array of {@link SentenceNode}s to search.
	 * @param string $operatorName The name of the {@link Operator} to search for.
	 * @return array Array of {@link SentenceNode}s.
	 */
	public static function findNodesByOperatorName( array $searchNodes, $operatorName )
	{
		$nodes = array();
		foreach ( $searchNodes as $node )
			if ($node instanceof SentenceNode &&
				$node->getSentence() instanceof MolecularSentence &&
				$node->getSentence()->getOperator()->getName() === $operatorName	
			)
				$nodes[] = $node;
		return $nodes;
	}
	
	/**
	 * Constructor.
	 *
	 * Sets the sentence.
	 *
	 * @param Sentence $sentence The sentence to place on the node.
	 */
	public function __construct( Sentence $sentence )
	{
		$this->setSentence( $sentence );
	}
	
	/**
	 * Sets the sentence.
	 *
	 * @param Sentence $sentence The sentence to place on the node.
	 * @return SentenceNode Current instance.
	 */
	public function setSentence( Sentence $sentence )
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