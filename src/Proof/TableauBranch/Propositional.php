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
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program in file LICENSE.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * Defines the PropositionalBranch class.
 * @package Tableaux
 */

namespace GoTableaux\Proof\TableauBranch;

use \GoTableaux\Sentence as Sentence;
use \GoTableaux\Proof\TableauNode\Sentence as SentenceNode;

/**
 * Represents a propositional logic tableau branch.
 * @package Tableaux
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