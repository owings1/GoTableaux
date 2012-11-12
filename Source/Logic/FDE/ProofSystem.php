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
 * Defines the FDE tableaux system class.
 * @package Logics
 */

namespace GoTableaux\Logic\FDE;

use \GoTableaux\Proof\Tableau as Tableau;
use \GoTableaux\Argument as Argument;
use \GoTableaux\Logic as Logic;

/**
 * Represents the FDE tableaux system.
 * @package Logics
 */
class ProofSystem extends \GoTableaux\ProofSystem\TableauxSystem
{
	public $ruleClasses = array(
		'Closure',
		'ConjunctionDesignated',
		'ConjunctionUndesignated',
		'NegatedConjunctionDesignated',
		'NegatedConjunctionUndesignated',
		'DisjunctionDesignated',
		'DisjunctionUndesignated',
		'NegatedDisjunctionDesignated',
		'NegatedDisjunctionUndesignated',
		'MaterialConditionalDesignated',
		'MaterialConditionalUndesignated',
		'NegatedMaterialConditionalDesignated',
		'NegatedMaterialConditionalUndesignated',
		'MaterialBiconditionalDesignated',
		'MaterialBiconditionalUndesignated',
		'NegatedMaterialBiconditionalDesignated',
		'NegatedMaterialBiconditionalUndesignated',
		'DoubleNegationDesignated',
		'DoubleNegationUndesignated'
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
			$trunk->createNode( 'ManyValued Sentence', array( 
				'sentence' => $premise, 
				'designated' => true 
			));
		$trunk->createNode( 'ManyValued Sentence', array( 
			'sentence' => $argument->getConclusion(), 
			'designated' => false 
		));
	}
}