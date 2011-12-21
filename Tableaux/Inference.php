<?php
/**
 * Defines the Inference class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Represents a simple wrapper of an argument, convenient for grouping.
 * @package Tableaux
 * @author Douglas Owings
 */
class Inference
{
	/**
	 * @var Argument
	 */
	protected $argment;
	
	/**
	 * @var string
	 */
	protected $label;
	
	/**
	 * @var boolean
	 */
	protected $bi;
	
	/**
	 * Constructor. 
	 *
	 * @param Argument $argument The argument for the inference.
	 * @param string $label The label for the argument.
	 * @param boolean $bi Whether the inference should be evaluated as a
	 *					  bi-entailment.
	 */
	function __construct( Argument $argument, $label = null, $bi = false )
	{
		$this->argument = $argument;
		$this->label = (string) $label;
		$this->bi = (bool) $bi;
	}
}