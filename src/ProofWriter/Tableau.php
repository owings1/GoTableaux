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
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program in file LICENSE.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * Defines the TableauWriter base class.
 * @package Tableaux
 */

namespace GoTableaux\ProofWriter;

use \GoTableaux\Logic as Logic;
use \GoTableaux\Proof as Proof;
use \GoTableaux\SentenceWriter as SentenceWriter;
use \GoTableaux\Proof\TableauStructure as TableauStructure;
use \GoTableaux\Proof\TableauNode as Node;
use \GoTableaux\Proof\TableauNode\Sentence as SentenceNode;
use \GoTableaux\Proof\TableauNode\Access as AccessNode;
use \GoTableaux\Proof\TableauNode\Modal as ModalNode;
use \GoTableaux\Proof\TableauNode\ManyValued as ManyValuedNode;

/**
 * Represents a tableaux writer.
 * @package Tableaux
 */
abstract class Tableau extends \GoTableaux\ProofWriter
{
	/**
	 * Constructor.
	 *
	 * Adds translation options. Calls parent constructor.
	 *
	 * @param Proof $proof The proof to write.
	 * @param string $sentenceWriterType The type of sentence writer to use.
	 */
	public function __construct( Proof $proof, $sentenceWriterType = 'Standard' )
	{
		parent::__construct( $proof, $sentenceWriterType );
		$this->addTranslations( array(
			'tickMarker'			=> '^',
			'closeMarker' 			=> '[><]',
			'designatedMarker' 		=> '+',
			'undesignatedMarker' 	=> '-',
			'worldSymbol' 			=> 'w',
			'accessRelationSymbol' 	=> 'R'
		));
	}
		
	public function writeCloseMarker()
	{
		return $this->getTranslation( 'closeMarker' );
	}	
	
	public function writeDesignationMarker( $isDesignated )
	{
		return $this->getTranslation( $isDesignated ? 'designatedMarker' : 'undesignatedMarker' );
	}
	
	public function writeTickMarker()
	{
		return $this->getTranslation( 'tickMarker');
	}
	
	public function writeWorldIndex( $index )
	{
		return $this->getTranslation( 'worldSymbol' ) . $index;
	}
	
	/**
	 * Writes a node based on its type.
	 *
	 * Calls the appropriate functions based on the type of node.
	 *
	 * @param Node $node The node to write.
	 * @return string The string representation of the node.
	 */
	public function writeNode( Node $node )
	{
		$str = '';
		if ( $node instanceof SentenceNode ) {
			$str .= $this->writeSentence( $node->getSentence() );
			if ( $node instanceof ModalNode )
				$str .= ', ' . $this->writeWorldIndex( $node->getI() );
		} elseif ( $node instanceof AccessNode )
			$str .= $this->writeWorldIndex( $node->getI() ) . 
					$this->getTranslation( 'accessRelationSymbol' ) . 
					$this->writeWorldIndex( $node->getJ() );
		if ( $node instanceof ManyValuedNode )
			$str .= ' ' . $this->writeDesignationMarker( $node->isDesignated() );
		if ( $node->writeAsTicked ) 
			$str .= $this->writeTickMarker();
		return $str;
	}
	
	/**
	 * Gets a formatted data array of a tableau.
	 *
	 * @param Proof $tableauOrStructure Tableau or Structure object
	 *												to get data from.
	 * @param Logic $logic The logic, required if first parameter is a Structure.
	 * @return array Formatted data array.
	 */
	public function getArray( Proof $tableau )
	{
		return $this->getArrayForStructure( $tableau->getStructure() );
	}
	
	public function writeProof( Proof $tableau )
	{
		return $this->writeStructure( $tableau->getStructure() );
	}
	
	public function getClassesForNode( Node $node )
	{
		$classes = array();
		if ( $node instanceof SentenceNode ) 
			$classes[] = 'sentence';
		if ( $node instanceof ModalNode )
			$classes[] = 'modal';
		if ( $node instanceof AccessNode )
			$classes[] = 'access';
		if ( $node instanceof ManyValuedNode )
			$classes[] = 'manyValued';
		return $classes;
	}
	
	public function getArrayForStructure( TableauStructure $structure, $n = 0 )
	{
		$subStructures 	= $structure->getStructures();
		$arr = array(
			'n'				=> $n,
			'nodes' 		=> array(),
			'structures' 	=> array(),
			'isTerminal' 	=> empty( $subStructures ),
			'isClosed'		=> $structure->isClosed(),
		);
		foreach ( $structure->getNodes() as $i => $node ) {
			$arr['nodes'][$i] = array( 
				'text'		=> $this->writeNode( $node ),
				'classes' 	=> $this->getClassesForNode( $node ),
				'isTicked' 	=> $structure->nodeIsTicked( $node )
			);
			if ( $node instanceof SentenceNode ) {
				$arr['nodes'][$i]['sentenceText'] = $this->writeSentence( $node->getSentence() );
				if ( $node instanceof ModalSentenceNode )
					$arr['nodes'][$i]['index'] = $node->getI();
			} elseif ( $node instanceof AccessNode ) {
				$arr['nodes'][$i]['firstIndex'] = $node->getI();
				$arr['nodes'][$i]['secondIndex'] = $node->getJ();
			}
			if ( $node instanceof ManyValuedNode ) 
				$arr['nodes'][$i]['isDesignated'] = $node->isDesignated();
		}
		if ( !empty( $subStructures )) 
			foreach ( $subStructures as $i => $subStructure )
				$arr['structures'][] = $this->getArrayForStructure( $subStructure, $i );
		if ( $structure->isClosed() ) 
			$arr['nodes'][$i]['text'] .= ' ' . $this->writeCloseMarker();
		return $arr;
	}
	
	abstract public function writeStructure( TableauStructure $structure );
}