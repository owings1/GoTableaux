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
 * @package GoTableaux
 */

namespace GoTableaux;

/**
 * Represents a proof.
 *
 * @package GoTableaux
 **/
abstract class Proof
{
	/**
	 * Reference to the argument for the proof.
	 * @var Argument
	 * @access private
	 */
	protected $argument;
	
	/**
	 * Reference to the proof system.
	 * @var ProofSystem
	 * @access private
	 */
	protected $proofSystem;
	
	/**
	 * Meta proof symbol names.
	 * @var array
	 */
	protected $metaSymbolNames = array();
	
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
	 * Gets the meta symbols names.
	 * 
	 * @return array The meta symbol names.
	 */
	public function getMetaSymbolNames()
	{
		return $this->metaSymbolNames;
	}
	
	/**
	 * Adds meta symbol names.
	 *
	 * @param string|array $names The meta symbol name(s).
	 * @return void
	 */
	public function addMetaSymbolNames( $names )
	{
		foreach ( (array) $names as $name )	Utilities::uniqueAdd( $name, $this->metaSymbolNames );
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