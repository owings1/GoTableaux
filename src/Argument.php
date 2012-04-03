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
 * Defines the Argument class.
 * @package GoTableaux
 */

namespace GoTableaux;

/**
 * Represents an argument as a set of premises and one conclusion.
 * @package GoTableaux
 */
class Argument
{
	/**
	 * The set of premises. An array of {@link Sentence} objects.
	 * @var array
	 * @access private
	 */
	protected $premises = array(); 
	
	/**
	 * The conclusion.
	 * @var Sentence
	 * @access private
	 */
	protected $conclusion;
	
	/**
	 * Creates an argument with given premises and conclusion.
	 *
	 * @param array $premises The premises of the argument.
	 * @param Sentence $conclusion The conclusion of the argument.
	 * @return Argument The created instance.
	 */
	public static function createWithPremisesAndConclusion( $premises, Sentence $conclusion )
	{
		$argument = new self;
		if ( !is_array( $premises )) $premises = array( $premises );
		return $argument->addPremises( $premises )->setConclusion( $conclusion );
	}
	
	/**
	 * Adds a premise to the argument.
	 *
	 * @param Sentence $sentence The premise to add.
	 * @return Argument Current instance.
	 */
	public function addPremise( Sentence $sentence )
	{
		$this->premises[] = $sentence;
		return $this;
	}
	
	/**
	 * Adds multiple premises to the argument.
	 *
	 * @param array The premises as an array of {@link Sentence} objects.
	 * @return Argument Current instance.
	 */
	public function addPremises( array $premises )
	{
		foreach ( $premises as $premise )
			$this->addPremise( $premise );
		return $this;
	}
	
	/**
	 * Gets all the premises of the argument.
	 *
	 * @return array Array of {@link Sentence}s.
	 */
	public function getPremises()
	{
		return $this->premises;
	}
	
	/**
	 * Sets the conclusion of the argument.
	 *
	 * @param Sentence $sentence The conclusion.
	 * @return Argument Current instance.
	 */
	public function setConclusion( Sentence $sentence )
	{
		$this->conclusion = $sentence;
		return $this;
	}
	
	/**
	 * Gets the conclusion of the argument.
	 *
	 * @return Sentence The conclusion.
	 */
	public function getConclusion()
	{
		return $this->conclusion;
	}
}