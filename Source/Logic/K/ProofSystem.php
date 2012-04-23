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
 * Defines the K Tableaux System.
 * @package K
 */

namespace GoTableaux\Logic\K;

use \GoTableaux\Proof\Tableau as Tableau;
use \GoTableaux\Argument as Argument;
use \GoTableaux\Logic as Logic;

/**
 * Represents the Tableaux system for K.
 *
 * @package K
 */
class ProofSystem extends \GoTableaux\ProofSystem\TableauxSystem
{
	public $ruleClasses = array(
		'Closure',
		'Conjunction',
		'NegatedConjunction',
		'Disjunction',
		'NegatedDisjunction',
		'MaterialConditional',
		'NegatedMaterialConditional',
		'MaterialBiconditional',
		'NegatedMaterialBiconditional',
		'DoubleNegation',
		'Possibility',
		'NegatedPossibility',
		'Necessity',
		'NegatedNecessity',
	);
	
	/**
	 * Constructs the initial list (trunk) for an argument.
	 *
	 * @param Tableau $tableau The tableau to attach the 
	 * @param Argument $argument The argument for which to build the trunk.
	 * @param Logic $logic The logic of the proof system.
	 * @return void
	 */
	public function buildTrunk( Tableau $tableau, Argument $argument, Logic $logic )
	{
		$trunk = $tableau->createBranch();
		foreach ( $argument->getPremises() as $premise ) 
			$trunk->createNode( 'Modal Sentence', array( 
				'sentence' => $premise, 
				'i' => 0 
			));
		$trunk->createNode( 'Modal Sentence', array( 
			'sentence' => $logic->negate( $argument->getConclusion() ),  
			'i' => 0 
		));
	}
}