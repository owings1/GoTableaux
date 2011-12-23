<?php
/**
 * Defines the base proof class.
 * @package Proof
 * @author Douglas Owings
 */

/**
 * Represents a proof.
 *
 * @package Proof
 * @author Douglas Owings
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
	 * Constructor. Initializes argument.
	 *
	 * @param Argument $argument Argument for the proof.
	 */
	public function __construct( Argument $argument )
	{
		$this->argument 	= $argument;
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
}