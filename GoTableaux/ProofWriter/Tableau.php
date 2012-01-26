<?php
/**
 * Defines the TableauWriter base class.
 * @package Tableaux
 * @author Douglas Owings
 */

namespace GoTableaux\ProofWriter;

use \GoTableaux\SentenceWriter as SentenceWriter;
use \GoTableaux\Logic as Logic;
use \GoTableaux\Proof as Proof;
use \GoTableaux\Proof\TableauStructure as TableauStructure;

/**
 * Represents a tableaux writer.
 * @package Tableaux
 * @author Douglas Owings
 */
abstract class Tableau extends \GoTableaux\ProofWriter
{
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
		return $this->_getArray( $tableau->getStructure(), $tableau->getProofSystem()->getLogic() );
	}
	
	/**
	 * @access private
	 */
	protected function _getArray( TableauStructure $structure, Logic $logic )
	{
		$sentenceWriter = $this->getSentenceWriter();
		$subStructures 	= $structure->getStructures();
		$arr = array(
			'nodes' 		=> array(),
			'structures' 	=> array(),
			'isTerminal' 	=> empty( $subStructures ),
		);
		foreach ( $structure->getNodes() as $i => $node ) {
			$arr['nodes'][$i] = array( 
				'text'		=> '',
				'classes' 	=> array( 'node' ),
				'isTicked' 	=> $structure->nodeIsTicked( $node )
			);
			if ( $node instanceof \GoTableaux\Proof\TableauNode\Sentence ) {
				$arr['nodes'][$i]['classes'][] 		= 'sentence';
				$arr['nodes'][$i]['sentenceText'] 	= $sentenceWriter->writeSentence( $node->getSentence(), $logic );
				$arr['nodes'][$i]['text'] 	   	   .= $arr['nodes'][$i]['sentenceText'];
				if ( $node instanceof \GoTableaux\Proof\TableauNode\Modal ) {
					$arr['nodes'][$i]['classes'][] 	= 'modal';
					$arr['nodes'][$i]['world'] 		= $node->getI();
					$arr['nodes'][$i]['text']	   .= ', w' . $node->getI();
				}
			} elseif ( $node instanceof \GoTableaux\Proof\TableauNode\Access ) {
				$arr['nodes'][$i]['classes'][] 	 = 'modal';
				$arr['nodes'][$i]['classes'][] 	 = 'access';
				$arr['nodes'][$i]['firstIndex']  = $node->getI();
				$arr['nodes'][$i]['secondIndex'] = $node->getJ();
				$arr['nodes'][$i]['text']		.= 'w' . $node->getI() . 'R' . 'w' . $node->getJ();
			}
			if ( $node instanceof \GoTableaux\Proof\TableauNode\ManyValued ) {
				$arr['nodes'][$i]['classes'][] 		= 'manyValued';
				$arr['nodes'][$i]['isDesignated'] 	= $node->isDesignated();
				$arr['nodes'][$i]['text']		   .= $node->isDesignated() ? ' +' : ' -';
			}
		}
		if ( !empty( $subStructures )) 
			foreach ( $subStructures as $subStructure )
				$arr['structures'][] = $this->_getArray( $subStructure, $logic );
		else $arr['nodes'][$i]['text'] .= ' [X]';
		return $arr;
	}
}