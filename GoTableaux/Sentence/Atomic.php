<?php
/**
 * Defines the Atomic Sentence class.
 * @package Syntax
 * @author Douglas Owings
 */

namespace GoTableaux\Sentence;

/**
 * Represents an atomic sentence.
 * @package Syntax
 * @author Douglas Owings
 */
class Atomic extends \GoTableaux\Sentence
{
	/**
	 * Atomic symbol, e.g. 'A' or 'B'.
	 * @var string
	 * @access private
	 */
	protected $symbol;
	
	/**
	 * Subscript of the atomic symbol.
	 * @var integer
	 * @access private
	 */
	protected $subscript;
	
	/**
	 * Sets the atomic symbol.
	 * 
	 * @param string $symbol The symbol, e.g. 'A' or 'B'.
	 * @return Atomic Current instance.
	 */
	public function setSymbol( $symbol )
	{
		$this->symbol = $symbol;
		return $this;
	}
	
	/**
	 * Gets the atomic symbol.
	 *
	 * @return string The atomic symbol, e.g. 'A' or 'B'.
	 */
	public function getSymbol()
	{
		return $this->symbol;
	}
	
	/**
	 * Sets the subscript of the atomic symbol.
	 *
	 * @param integer $subscript The subscript.
	 * @return Atomic Current instance.
	 */
	public function setSubscript( $subscript )
	{
		$this->subscript = (int) $subscript;
		return $this;
	}
	
	/**
	 * Gets the subscript of the atomic symbol.
	 *
	 * @return integer The subscript.
	 */
	public function getSubscript()
	{
		return $this->subscript;
	}

}