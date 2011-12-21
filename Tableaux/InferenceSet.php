<?php
/**
 * Defines the InferenceSet class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Represents a set of inferences to evaluate.
 * @package Tableaux
 * @author Douglas Owings
 */
class InferenceSet
{
	/**
	 * @var string
	 */
	protected $title;
	
	/**
	 * @var array Array of {@link Inference} objects.
	 */
	protected $inferences = array();
	
	/**
	 * Constructor.
	 *
	 * @param string $title The title of the inference set.
	 * @param array $inferences Array of {@link Inference} objects.
	 */
	function __construct( $title, array $inferences = array() )
	{
		$this->title = $title;
		if ( ! empty( $inferences ))
			$this->addInference( $inferences );
	}
	
	/**
	 * Adds an inference, or array of inferences to the inference set.
	 *
	 * @param Inference|array $inference Inference or array to add.
	 * @return InferenceSet Current instance, for chaining.
	 */
	function addInference( $inference )
	{
		if ( is_array( $inference ))
			foreach ( $inference as $inf ) $this->_addInference( $inf );
		else
			$this->_addInference( $inference );
		return $this;
	}
	
	/**
	 * Gets the inferences in the inference set.
	 *
	 * @return array Array of {@link Inference} objects.
	 */
	function getInferences()
	{
		return $this->inferences;
	}
	
	/**
	 * Adds an inference to the inference set.
	 *
	 * @param Inference $inference Inference to add.
	 * @return void
	 */
	protected function _addInference( Inference $inference )
	{
		$this->inferences[] = $inference;
	}
}