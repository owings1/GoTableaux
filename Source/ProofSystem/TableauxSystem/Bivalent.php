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
 * Defines the Bivalent Tableaux System class.
 * @package GoTableaux
 */

namespace GoTableaux\ProofSystem\TableauxSystem;

use \GoTableaux\Logic as Logic;
use \GoTableaux\Argument as Argument;
use \GoTableaux\Proof\Tableau as Tableau;
use \GoTableaux\Proof\TableauBranch as Branch;

/**
 * Represents a bivalent propositional tableaux system.
 * @package GoTableaux
 */
abstract class Bivalent extends \GoTableaux\ProofSystem\TableauxSystem
{	
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
		foreach ( $argument->getPremises() as $premise ) $trunk->createNode( $premise );
		$trunk->createNode( $logic->negate( $argument->getConclusion() ));
	}
	
	public function induceModel( Branch $branch )
	{
		
	}
}