<?php
require_once 'Writer/LaTeX/Qtree.php';

abstract class Tableaux_Writer
{
	protected 	$tableau,
				$structure;

	protected	$closeMarker = '*';
	protected	$translations = array();
	
	public function __construct( $tableau = null )
	{
		if ( isset( $tableau )){
			$this->setTableau( $tableau );
		}
	}
	
	public function setTableau( Tableaux_Tableau $tableau )
	{
		$this->tableau = $tableau;
		$this->structure = $this->tableau->getStructure();
	}
	static function sortByStrLen( $a, $b )
	{
		return ( strlen( $a ) > strlen( $b ) ) ? -1 : ( ( strlen( $b ) > strlen( $a )) ? 1 : 0 );
	}
	function translate( $string )
	{
		foreach ( $this->translations as $old => $new ){
			$string = str_replace( $old, $new, $string );
		}
		return $string;
	}
	function write()
	{
		return $this->doWrite();
	}
	function setCloseMarker( $symbol )
	{
		$this->closeMarker = $symbol;
	}
	function getCloseMarker()
	{
		return $this->closeMarker;
	}
	function setTranslation( $old, $new = null )
	{
		if ( is_array( $old )){
			foreach ( $old as $o => $new ){
				$this->setTranslation( $o, $new );
			}
		}
		else{
			if ( array_key_exists( $new, $this->translations )){
				throw new Exception( 
					'you want to translate ' . $old . ' to ' . $new 
					. ' but you already said you want to translate ' . $new 
					. ' to ' . $this->translations[$new] 
				);
			}
			$this->translations[$old] = $new;
			uksort( $this->translations, array( __CLASS__, 'sortByStrLen' ));
		}
	}
	abstract function doWrite();	// returns string
}
?>