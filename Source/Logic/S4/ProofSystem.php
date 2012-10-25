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
 * Defines the S4 Tableaux System.
 * @package S4
 */

namespace GoTableaux\Logic\S4;

use \GoTableaux\Proof\Tableau as Tableau;
use \GoTableaux\Argument as Argument;
use \GoTableaux\Logic as Logic;

/**
 * Represents the Tableaux system for S4.
 *
 * @package S4
 */
class ProofSystem extends \GoTableaux\Logic\K\ProofSystem
{
	public $ruleClasses = array(
		'K.Closure',
		'T.Reflexive',
		'Transitive',
		'K.Conjunction',
		'K.NegatedConjunction',
		'K.Disjunction',
		'K.NegatedDisjunction',
		'K.MaterialConditional',
		'K.NegatedMaterialConditional',
		'K.MaterialBiconditional',
		'K.NegatedMaterialBiconditional',
		'K.DoubleNegation',
		'K.Possibility',
		'K.NegatedPossibility',
		'K.Necessity',
		'K.NegatedNecessity',
	);
}