<?php
/**
 * Defines the AtomicSentence class.
 * @package Syntax
 * @author Douglas Owings
 */

/**
 * Represents an atomic sentence.
 * @package Syntax
 * @author Douglas Owings
 */
class AtomicSentence extends Sentence
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
	 * @return AtomicSentence Current instance, to allow chaining.
	 */
	public function setSymbol( $symbol )
	{
		$this->symbol = $symbol;
		return $this;
	}
	
	/**
	 * Alias for {@link AtomicSentence::setSymbol()}.
	 * @param string $symbol The symbol, e.g. 'A' or 'B'.
	 * @return AtomicSentence Current instance, to allow chaining.
	 */
	public function setLabel( $symbol )
	{
		return $this->setSymbol( $symbol );
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
	 * Alias for {@link AtomicSentence::getSymbol()}.
	 * @return string The atomic symbol, e.g. 'A' or 'B'.
	 */
	public function getLabel()
	{
		return $this->getSymbol();
	}
	
	/**
	 * Sets the subscript of the atomic symbol.
	 *
	 * @param integer $subscript The subscript.
	 * @return AtomicSentence Current instance, for chaining.
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