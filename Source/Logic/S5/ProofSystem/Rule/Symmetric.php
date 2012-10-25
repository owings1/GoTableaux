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
namespace GoTableaux\Logic\S5\ProofSystem\Rule;

use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Proof\TableauNode as Node;
use \GoTableaux\Logic as Logic;

/**
 * @package S5
 */
class Symmetric extends \GoTableaux\ProofSystem\TableauxSystem\Rule\Branch
{
	public function appliesToBranch( Branch $branch, Logic $logic )
	{
		foreach ( $branch->getIndexes() as $i )
			foreach ( $branch->find( 'all', array( 'i' => $i, 'j' => '*' )) as $node )
				if ( !$branch->find( 'exists', array( 'i' => $node->getJ(), 'j' => $i ))) 
					return true;
		return false;
	}
	
	public function applyToBranch( Branch $branch, Logic $logic )
	{
		foreach ( $branch->getIndexes() as $i )
			foreach ( $branch->find( 'all', array( 'i' => $i, 'j' => '*' )) as $node )
				if ( !$branch->find( 'exists', array( 'i' => $node->getJ(), 'j' => $i ))) {
					$branch->createNode( 'Access', array( 'i' => $node->getJ(), 'j' => $i ));
					return;
				}
	}
	
	public function buildExample( Branch $branch, Logic $logic )
	{
		$branch->createNode( 'Access', array( 'i' => 0, 'j' => 1 ));
	}
}