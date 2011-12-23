<?php
/**
 * Defines the Writer base class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link WriterException} class.
 */
require_once 'WriterException.php';

/**
 * Loads the Writer_LaTeX_Qtree class.
 */
require_once 'Writer/LaTeX/Qtree.php';

/**
 * Represents a tableaux writer.
 * @package Tableaux
 * @author Douglas Owings
 */
abstract class Writer
{
	/**
	 * @var Tableau
	 */
	protected $tableau;
	
	/**
	 * @var Structure
	 */
	protected $structure;

	/**
	 * @var string
	 */
	protected $closeMarker = '*';
	
	/**
	 * @var array
	 */
	protected $translations = array();
	
	/**
	 * Constructor.
	 *
	 * @param Tableau $tableau The tableau to write.
	 */
	public function __construct( $tableau = null )
	{
		if ( isset( $tableau ))
			$this->setTableau( $tableau );
	}
	
	/**
	 * Sets the tableau to write.
	 *
	 * @param Tableau $tableau The tableau to write.
	 * @return Writer Current instance.
	 */
	public function setTableau( Tableau $tableau )
	{
		$this->tableau = $tableau;
		$this->structure = $this->tableau->getStructure();
		return $this;
	}
	
	/**
	 * Sorts two strings by their length.
	 *
	 * @param string $a
	 * @param string $b
	 * @return integer
	 */
	protected static function sortByStrLen( $a, $b )
	{
		return ( strlen( $a ) > strlen( $b ) ) ? -1 : ( ( strlen( $b ) > strlen( $a )) ? 1 : 0 );
	}
	
	/**
	 * Performs translations.
	 *
	 * @param string $string The string to translate.
	 * @return string The translated string.
	 */
	function translate( $string )
	{
		foreach ( $this->translations as $old => $new )
			$string = str_replace( $old, $new, $string );
		return $string;
	}
	
	/**
	 * Sets the symbol with which to mark a closed branch.
	 *
	 * @param string $symbol The close marker.
	 * @return Writer Current instance.
	 */
	function setCloseMarker( $symbol )
	{
		$this->closeMarker = $symbol;
	}
	
	/**
	 * Gets the close marker.
	 *
	 * @return string The close marker.
	 */
	function getCloseMarker()
	{
		return $this->closeMarker;
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
	function setTranslation( $from, $to = null )
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
			uksort( $this->translations, array( __CLASS__, 'sortByStrLen' ));
		}
		return $this;
	}
	
	/**
	 * Writes the tableau's argument.
	 *
	 * @return string The string representation of the tableau's argument.
	 */
	abstract public function writeArgument();
	
	/**
	 * Writes the tree structure.
	 *
	 * @return string The string representation of the tableau structure.
	 */
	abstract public function write();
}