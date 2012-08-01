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
	protected $tableauxCommands = array(
		'closeMarker' 			=> '\times',
		'designatedMarker' 		=> '+',
		'undesignatedMarker' 	=> '-',
		'worldSymbol' 			=> 'w',
		'accessRelationSymbol' 	=> '\mathcal{R}',
		'tickMarker'			=> '\bullet',
	);
	
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
		foreach ( $this->tableauxCommands as $name => $command )
			$this->addTranslations( array( $name => "\GT$name" ));
	}
	
	/**
	 * Makes a string representation of a proof.
	 * 
	 * @param Proof $tableau The proof to represent.
	 * @return string The string representation.
	 */
	public function writeProof( Proof $tableau )
	{
		$str = "\documentclass[11pt]{article}\n";
		$str .= "\usepackage{latexsym, qtree}\n\n";
		
		$operatorStrings = $this->getSentenceWriter()->operatorStrings;
		$operatorNames = array_keys( $operatorStrings );
		$metaSymbolNames = $tableau->getMetaSymbolNames();
		
		foreach ( $operatorStrings as $name => $command ) 
			$str .= '\newcommand{\Operator_' . str_replace( ' ', '', $name ) . '} {\ensuremath{' . $command . "}}\n";
		
		foreach ( $metaSymbolNames as $name )
			$str .= '\newcommand{\Tableau_' . $name . '} {\ensuremath{' . $this->tableauxCommands[ $name ] . "}}\n";
			
		$str .= "\n\n\begin{document}\n\n";
		
		$str .= '\Tree[.' . parent::writeProof( $tableau ) . "]\n\n";
		
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
		foreach ( $structure->getNodes() as $node )
			$string .= '$' . $this->writeNode( $node ) . '$ \\\\ ';
		
		if ( $structure->isClosed() )
			$string .= '$ ' . $this->writeCloseMarker() . ' $';
		
		$string = trim( $string, "\\ " ) . '} ';
		foreach ( $structure->getStructures() as $s )
			$string .=  '[.' . $this->writeStructure( $s ) . " ] \n";
		
		$string = trim( $string, "\n" );
		return $string;
	}
}
