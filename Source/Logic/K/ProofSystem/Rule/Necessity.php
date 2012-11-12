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
namespace GoTableaux\Logic\K\ProofSystem\Rule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Proof\TableauNode as Node;
use \GoTableaux\Logic as Logic;
use \GoTableaux\Utilities as Utilities;

/**
 * @package Logics
 */
class Necessity extends \GoTableaux\ProofSystem\TableauxSystem\Rule\Branch
{

	public function appliesToBranch( Branch $branch, Logic $logic )
	{
		$potentialNodes = $branch->find( 'all', array( 'sentenceForm' => 'NA', 'i' => '*', 'ticked' => false ));
		foreach ( $potentialNodes as $node ) {
			list( $sentence ) = $node->getSentence()->getOperands();
			foreach ( $branch->getIndexes() as $i ) 
				if ( 
					$branch->find( 'exists', array( 'i' => $node->getI(), 'j' => $i )) &&
					!$branch->find( 'exists', compact( 'sentence', 'i' ))
				) return true;
		}
		return false;
	}
	
	public function applyToBranch( Branch $branch, Logic $logic )
	{
		$potentialNodes = $branch->find( 'all', array( 'sentenceForm' => 'NA', 'i' => '*', 'ticked' => false ));
		foreach ( $potentialNodes as $node ) {
			list( $sentence ) = $node->getSentence()->getOperands();
			foreach ( $branch->getIndexes() as $i ) 
				if ( 
					$branch->find( 'exists', array( 'i' => $node->getI(), 'j' => $i )) &&
					!$branch->find( 'exists', compact( 'sentence', 'i' ))
				) return $this->applyToNodeForIndex( $node, $i, $branch, $logic );
		}
	}
	
	public function applyToNodeForIndex( Node $node, $i, Branch $branch, Logic $logic )
	{
		list( $sentence ) = $node->getSentence()->getOperands();
		$branch->createNode( 'Modal Sentence', compact( 'sentence', 'i' ));
	}
	
	public function buildExample( Branch $branch, Logic $logic )
	{
		$branch->createNode( 'Modal Sentence', array( 'sentence' => $logic->parseSentence( 'NA' ), 'i' => 0 ))
			   ->createNode( 'Access', array( 'i' => 0, 'j' => 1 ));
	}
}