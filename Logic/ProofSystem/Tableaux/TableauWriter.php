<?php
/**
 * Defines the TableauWriter base class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link WriterException} class.
 */
require_once dirname( __FILE__ ) . "/../../Exceptions/WriterException.php";

/**
 * Loads the {@link Utilities} class.
 */
require_once dirname( __FILE__ ) . "/../../Utilities.php";

/**
 * Loads the {@link SentenceWriter} class.
 */
require_once dirname( __FILE__ ) . "/../../Syntax/SentenceWriter.php";

/**
 * Loads the {@link SimpleTableauWriter} child class.
 */
require_once dirname( __FILE__ ) . "/TableauWriter/SimpleTableauWriter.php";

/**
 * Represents a tableaux writer.
 * @package Tableaux
 * @author Douglas Owings
 */
abstract class TableauWriter
{
	/**
	 * @var SentenceWriter
	 */
	protected $sentenceWriter;
	
	/**
	 * Gets the sentence writer object.
	 *
	 * @return SentenceWriter The sentence writer.
	 */
	public function getSentenceWriter()
	{
		if ( empty( $this->sentenceWriter ))
			$this->sentenceWriter = new StandardSentenceWriter;
		return $this->sentenceWriter;
	}
	
	/**
	 * Sets the sentence writer object.
	 *
	 * @param SentenceWriter $sentenceWriter The sentence writer to set.
	 * @return TableauWriter Current instance.
	 */
	public function setSentenceWriter( SentenceWriter $sentenceWriter )
	{
		$this->sentenceWriter = $sentenceWriter;
		return $this;
	}
	
	/**
	 * Writes the tableau's argument.
	 *
	 * @param Argument $argument The argument to write.
	 * @param Logic $logic The operative logic (language).
	 * @return string The string representation of the tableau's argument.
	 */
	abstract public function writeArgument( Argument $argument, Logic $logic );
	
	/**
	 * Writes the tree structure.
	 *
	 * @param Tableau $tableau The tableau to write.
	 * @param Logic $logic The operative logic (language).
	 * @return string The string representation of the tableau structure.
	 */
	abstract public function writeTableau( Tableau $tableau, Logic $logic );
}