<?php
/**
 * Defines the Simple Tableau Writer class.
 * @package Tableaux
 * @author Douglas Owings
 */

namespace GoTableaux\ProofWriter\Tableau;

use \GoTableaux\Proof as Proof;
use \GoTableaux\Proof\TableauStructure as Structure;

/**
 * Writes tableaux using the LaTeX Qtree package.
 * @package Tableaux
 * @author Douglas Owings
 */
class LaTeX_Qtree extends \GoTableaux\ProofWriter\Tableau
{
	/*
	public static function getTeXUsePackageStr()
	{
		return '\\usepackage{latexsym, qtree, stmaryrd}';
	}
	*/
	
	public function writeTickMarker()
	{
		return '';
	}
	
	public function writeWorldIndex( $index )
	{
		return $this->getTranslation( 'worldSymbol' ) . '_{' . $index . '}';
	}
	
	/**
	 * Constructor.
	 *
	 * Decorates the sentence writer with the LaTeX decorator; sets default
	 * LaTeX translations, and removes the tickMarker translation.
	 *
	 * @param Proof $proof The with which to initialize the writer.
	 * @param string $sentenceWriterType The sentence notation type to use.
	 */
	public function __construct( Proof $proof, $sentenceWriterType = 'Standard' )
	{
		parent::__construct( $proof, $sentenceWriterType );
		$this->decorateSentenceWriter( 'LaTeX' );
		//die( get_class( $this->getSentenceWriter() ));
		$this->addTranslations( array(
			'closeMarker' 			=> '\varotimes',
			'designatedMarker' 		=> '\varoplus',
			'undesignatedMarker' 	=> '\varominus',
			'worldSymbol' 			=> 'w',
			'accessRelationSymbol' 	=> '\mathcal{R}',
			'tickWrapper'			=> '\framebox',
		));
		$this->removeTranslation( 'tickMarker' );
	}
	
	/**
	 * Makes a string representation of a proof.
	 * 
	 * @param Proof $tableau The proof to represent.
	 * @return string The string representation.
	 */
	public function writeProof( Proof $tableau )
	{
		return '\Tree[.' . parent::writeProof( $tableau ) . ' ]';
	}	
	
	/**
	 * Makes a string representation of a tableau structure.
	 * 
	 * @param Structure $structure The tableau structure to represent.
	 * @return string The string representation of the structure.
	 */
	public function writeStructure( Structure $structure )
	{
		$string = '{';
		foreach ( $structure->getNodes() as $node ){
			$nodeStr = '$' . $this->writeNode( $node ) . '$';
			$string .= ( $structure->nodeIsTicked( $node ) ) ? $this->getTranslation( 'tickWrapper' ) . '{'. $nodeStr . '}' 
															 : $nodeStr;
			$string .= " \\\\ ";
		}
		if ( $structure->isClosed() )
			$string .= '$ ' . $this->writeCloseMarker() . ' $';
		
		$string = trim( $string, "\\ " ) . '} ';
		foreach ( $structure->getStructures() as $s )
			$string .=  '[.' . $this->writeStructure( $s ) . " ] \n";
		
		$string = trim( $string, "\n" ) ;
		return $string;
	}
}
