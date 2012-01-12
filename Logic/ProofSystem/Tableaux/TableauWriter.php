<?php
/**
 * Defines the TableauWriter base class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link WriterException} class.
 */
require_once 'GoTableaux/Logic/Exceptions/WriterException.php';

/**
 * Loads the {@link Utilities} class.
 */
require_once 'GoTableaux/Logic/Utilities.php';

/**
 * Loads the {@link SentenceWriter} class.
 */
require_once 'GoTableaux/Logic/Syntax/SentenceWriter.php';

/**
 * Loads the {@link SimpleTableauWriter} child class.
 */
require_once 'TableauWriter/SimpleTableauWriter.php';

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
	 * @var string
	 */
	protected $closeMarker = '*';
	
	/**
	 * @var string
	 */
	protected $tickMarker = '/';
	
	/**
	 * @var array
	 */
	protected $translations = array();
	
	/**
	 * Performs translations.
	 *
	 * @param string $string The string to translate.
	 * @return string The translated string.
	 */
	public function translate( $string )
	{
		foreach ( $this->translations as $old => $new )
			$string = str_replace( $old, $new, $string );
		return $string;
	}
	
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
	 * Gets the close marker.
	 *
	 * @return string The close marker.
	 */
	public function getCloseMarker()
	{
		return $this->closeMarker;
	}
	
	/**
	 * Sets the symbol with which to mark a closed branch.
	 *
	 * @param string $symbol The close marker.
	 * @return TableauWriter Current instance.
	 */
	public function setCloseMarker( $symbol )
	{
		$this->closeMarker = $symbol;
		return $this;
	}
	
	/**
	 * Gets the tick marker.
	 *
	 * @return string The tick marker.
	 */
	public function getTickMarker()
	{
		return $this->tickMarker;
	}
	
	/**
	 * Sets the symbol with which to mark a ticked node.
	 *
	 * @param string $symbol The tick marker.
	 * @return TableauWriter Current instance.
	 */
	public function setTickMarker( $symbol )
	{
		$this->tickMarker = $symbol;
		return $this;
	}
		
	/**
	 * Gets the translations.
	 *
	 * @return array The translations.
	 */
	public function getTranslations()
	{
		return $this->translations;
	}
	
	/**
	 * Sets a string translation to perform.
	 *
	 * @param string|array $from The string to translate from, or array of from
	 *							 => to pairs.
	 * @param string $to The string to translate to, or empty if $from is array.
	 * @return Writer Current instance.
	 * @throws {@link WriterException} on translation error.
	 */
	public function setTranslation( $from, $to = null )
	{
		if ( is_array( $from )) {
			foreach ( $from as $old => $new )
				$this->setTranslation( $old, $new );
		} else {
			if ( array_key_exists( $to, $this->translations ))
				throw new WriterException( 
					"You want to translate $from to $to, but you already said you want to translate $to to {$this->translations[$to]}."
				);
			$this->translations[$from] = $to;
			uksort( $this->translations, array( 'Utilities', 'sortByStrLen' ));
		}
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