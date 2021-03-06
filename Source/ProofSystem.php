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
 * Defines the ProofSystem base class.
 * @package GoTableaux
 */

namespace GoTableaux;

/**
 * Represents a proof system.
 * @package GoTableaux
 */
abstract class ProofSystem
{	
	/**
	 * Holds a reference to the logic instance.
	 * @var Logic
	 * @access private
	 */
	protected $logic;

	/**
	 * Constructor.
	 *
	 * @param Logic $logic The logic for the proof system to use.
	 */
	public function __construct( Logic $logic )
	{
		$this->logic = $logic;
	}
	
	public function getType()
	{
		$class = trim( str_replace( __CLASS__ , '', get_class( $this )), '\\' );
		list( $type ) = explode( '\\', $class );
		return $type;
	}
	
	/**
	 * Gets the logic instance.
	 *
	 * @return Logic The logic instance.
	 * @throws {@link ProofException} on empty logic.
	 * @see Logic::__construct()
	 */
	public function getLogic()
	{
		return $this->logic;
	}
	
	abstract public function getProofWriter( $output = null, $notation = null, $format = null );
	
	/**
	 * Constructs a proof for an argument.
	 * 
	 * @param Argument $argument The argument for which to construct the proof.
	 * @return Proof $proof The constructed proof object.
	 */
	abstract public function constructProofForArgument( Argument $argument );
	
	/**
	 * Checks whether a putative proof is valid.
	 *
	 * @param Proof $proof The proof whose validity to check.
	 * @return boolean Whether the proof is valid.
	 * @throws {@link ProofException} on type errors.
	 */
	abstract public function isValidProof( Proof $proof );
}