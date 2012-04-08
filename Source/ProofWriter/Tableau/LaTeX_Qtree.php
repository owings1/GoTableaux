<?php
/**
 * GoTableaux. A multi-logic tableaux generator.
 * Copyright (C) 2012  Douglas Owings
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed WITHOUT ANY WARRANTY. 
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/agpl-3.0.html>.
 */
/**
 * Defines the Simple Tableau Writer class.
 * @package GoTableaux
 */

namespace GoTableaux\ProofWriter\Tableau;

use \GoTableaux\Proof as Proof;
use \GoTableaux\Proof\TableauStructure as Structure;

/**
 * Writes tableaux using the LaTeX Qtree package.
 * @package GoTableaux
 */
class LaTeX_Qtree extends \GoTableaux\ProofWriter\Tableau
{
	protected $translations = array(
		'closeMarker' 			=> '\GTcloseMarker',
		'designatedMarker' 		=> '\GTdesignatedMarker',
		'undesignatedMarker' 	=> '\GTundesignatedMarker',
		'worldSymbol' 			=> '\GTworldSymbol',
		'accessRelationSymbol' 	=> '\GTaccessRelationSymbol',
		'tickWrapper'			=> '\framebox',
	);
	
	protected $tableauxCommands = array(
		'closeMarker' 			=> '\varotimes',
		'designatedMarker' 		=> '\varoplus',
		'undesignatedMarker' 	=> '\varominus',
		'worldSymbol' 			=> 'w',
		'accessRelationSymbol' 	=> '\mathcal{R}',
	);
	
	// Do not use tick marker, use tick wrapper instead.
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
		$str = '';
		$str .= "\documentclass[11pt]{article}\n";
		$str .= "\usepackage{latexsym, qtree, stmaryrd}\n\n";
		$operatorCommands = $this->getSentenceWriter()->getOperatorSymbolCommands();
		foreach ( array_merge( $operatorCommands, $this->tableauxCommands ) as $name => $command) 
			$str .= '\newcommand{\GT' . $this->formatCommand( $name ) . '} {\ensuremath{' . $command . "}}\n";
		$str .= "\n\n\begin{document}\n\n";
		$str .= $this->writeProofBody( $tableau ) . "\n\n";
		$str .= "\end{document}";
		return $str;
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
			$string .= $structure->nodeIsTicked( $node ) 
					 ? $this->getTranslation( 'tickWrapper' ) . '{'. $nodeStr . '}' 
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
	
	/**
	 * Writes the body of the proof.
	 *
	 * @param Proof $tableau The proof whose body to write.
	 * @return string The string representation.
	 */
	public function writeProofBody( Proof $tableau )
	{
		return '\Tree[.' . parent::writeProof( $tableau ) . ' ]';
	}
	
	public function formatCommand( $command )
	{
		return $this->getSentenceWriter()->formatCommand( $command );
	}
}
