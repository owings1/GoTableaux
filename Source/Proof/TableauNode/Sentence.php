<?php
/**
 * GoTableaux. A multi-logic tableaux generator.
 * Copyright (C) 2012  Douglas Owings
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed WITHOUT ANY WARRANTY. 
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/agpl-3.0.html>.
 */
/**
 * Defines the SentenceNode class.
 * @package GoTableaux
 */

namespace GoTableaux\Proof\TableauNode;

use \GoTableaux\Sentence as Sent;
use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Exception\Tableau as TableauException;

/**
 * Represents a sentence tableau node.
 * @package GoTableaux
 */
class Sentence extends \GoTableaux\Proof\TableauNode
{
	/**
	 * Holds a reference to the sentence on the node
	 * @var Sentence
	 */
	private $sentence;
	
	/**
	 * Sets the node properties.
	 * @param array $properties The properties.
	 * @throws TableauException when no sentence is given.
	 */
	public function setProperties( array $properties )
	{
		$this->node->setProperties( $properties );
		if ( empty( $properties['sentence'] )) 
			throw new TableauException( 'Must set sentence when creating a sentence node.' );
		$this->setSentence( $properties['sentence'] );
	}
	
	/**
	 * Registers the node's sentence with the logic's vocabulary before the 
	 * node is attached to the branch.
	 *
	 * @param Branch $branch The branch to which the node is to be attached.
	 * @return void
	 */
	public function beforeAttach( Branch $branch )
	{
		$this->node->beforeAttach( $branch );
		$sentence = $branch->getTableau()
						   ->getProofSystem()
						   ->getLogic()
						   ->getVocabulary()
						   ->registerSentence( $this->getSentence() );
		$this->setSentence( $sentence );
	}

	public function filter( array $conditions )
	{
		if ( !$this->node->filter( $conditions )) return false;
		if ( !empty( $conditions['sentence' ] )) return $this->getSentence() === $conditions['sentence'];
		if ( empty( $conditions['operator'] )) return true;
		$operators = (array) $conditions['operator'];
		if ( $this->getSentence()->getOperatorName() !== $operators[0] ) return false;
		if ( empty( $operators[1] )) return true;
		list( $firstOperand ) = $this->getSentence()->getOperands();
		return $firstOperand->getOperatorName() === $operators[1];
	}
	
	/**
	 * Sets the sentence.
	 *
	 * @param Sentence $sentence The sentence to place on the node.
	 * @return SentenceNode Current instance.
	 */
	public function setSentence( Sent $sentence )
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