<?php
/**
 * Defines the Argument class.
 * @author Douglas Owings
 */

/**
 * Represents an argument as a set of premises and one conclusion.
 * @author Douglas Owings
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
	 * Adds a premise to the argument.
	 *
	 * @param Sentence $sentence The premise to add.
	 * @return Argument Current instance, for chaining.
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
	 * @return Argument Current instance, for chaining.
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
	 * @return array Array of {@link Sentence} objects.
	 */
	public function getPremises()
	{
		return $this->premises;
	}
	
	/**
	 * Sets the conclusion of the argument.
	 *
	 * @param Sentence $sentence The conclusion.
	 * @return Argument Current instance, for chaining.
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