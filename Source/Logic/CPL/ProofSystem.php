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
 * Defines the CPLTableauxSystem class.
 * @package Logics
 */

namespace GoTableaux\Logic\CPL;

use \GoTableaux\Proof\Tableau as Tableau;
use \GoTableaux\Argument as Argument;
use \GoTableaux\Logic as Logic;

/**
 * Represents the Tableaux system for CPL.
 * @package Logics
 */
class ProofSystem extends \GoTableaux\ProofSystem\TableauxSystem
{
	public $ruleClasses = array(
		'Closure',
		'DoubleNegation',
		'Conjunction',
		'NegatedDisjunction',
		'NegatedMaterialConditional',
		'NegatedConjunction',
		'Disjunction',
		'MaterialConditional',
		'MaterialBiconditional',
		'NegatedMaterialBiconditional',
	);
	
	/**
	 * Builds the trunk of a tableau for an argument.
	 *
	 * @param Tableau $tableau The empty tableau.
	 * @param Argument $argument The argument.
	 * @param Logic $logic The logic of the proof system.
	 * @return void
	 */
	public function buildTrunk( Tableau $tableau, Argument $argument, Logic $logic )
	{
		$trunk = $tableau->createBranch();
		foreach ( $argument->getPremises() as $sentence ) $trunk->createNode( 'Sentence', compact( 'sentence' ));
		$trunk->createNode( 'Sentence', array( 'sentence' => $logic->negate( $argument->getConclusion() )));
	}
}