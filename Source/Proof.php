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
 * Defines the base proof class.
 * @package Proof
 */

namespace GoTableaux;

/**
 * Represents a proof.
 *
 * @package Proof
 **/
abstract class Proof
{
	/**
	 * Holds the argument for the proof.
	 * @var Argument
	 * @access private
	 */
	protected $argument;
	
	/**
	 * Holds a reference to the proof system.
	 * @var ProofSystem
	 * @access private
	 */
	protected $proofSystem;
	
	/**
	 * Constructor. Initializes argument.
	 *
	 * @param Argument $argument Argument for the proof.
	 * @param ProofSystem $proofSystem The proof system of the proof.
	 */
	public function __construct( Argument $argument, ProofSystem $proofSystem )
	{
		$this->argument = $argument;
		$this->proofSystem = $proofSystem;
	}
	
	/**
	 * Gets the Argument object.
	 *
	 * @return Argument The argument.
	 */
	public function getArgument()
	{
		return $this->argument;
	}
	
	/**
	 * Gets the ProofSystem object.
	 *
	 * @return ProofSystem The proof's proof system.
	 */
	public function getProofSystem()
	{
		return $this->proofSystem;
	}
	
	/**
	 * Checks whether the proof is valid
	 *
	 * @return boolean Whether the proof is valid.
	 */
	public function isValid()
	{
		return $this->getProofSystem()->isValidProof( $this );
	}
}