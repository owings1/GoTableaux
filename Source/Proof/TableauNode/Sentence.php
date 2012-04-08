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