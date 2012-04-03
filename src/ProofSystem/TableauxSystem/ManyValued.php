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
 * Defines the ManyValuedTableauxSystem class.
 * @package Tableaux
 * @author Douglas Owings
 */

namespace GoTableaux\ProofSystem\TableauxSystem;

use \GoTableaux\Logic as Logic;
use \GoTableaux\Argument as Argument;
use \GoTableaux\Proof\Tableau as Tableau;
use \GoTableaux\Proof\TableauBranch as Branch;

/**
 * Represents a many-valued propositional tableaux system.
 * @package Tableaux
 * @author Douglas Owings
 */
abstract class ManyValued extends \GoTableaux\ProofSystem\TableauxSystem
{
	
	public $branchClass = 'ManyValued';
	
	/**
	 * Builds a modal tableau trunk.
	 *
	 * @param ModalTableau $tableau The modal tableau.
	 * @param Argument $argument The argument.
	 * @param Logic $logic The logic of the proof system.
	 * @return void
	 */
	public function buildTrunk( Tableau $tableau, Argument $argument, Logic $logic )
	{
		$trunk = $tableau->createBranch();
		foreach ( $argument->getPremises() as $premise ) $trunk->createNodeWithDesignation( $premise, true );
		$trunk->createNodeWithDesignation( $argument->getConclusion(), false );
	}
	
	public function induceModel( Branch $branch )
	{
		
	}
}