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
 * Defines the D Tableaux System.
 * @package D
 */

namespace GoTableaux\Logic\D;

use \GoTableaux\Proof\Tableau as Tableau;
use \GoTableaux\Argument as Argument;
use \GoTableaux\Logic as Logic;

/**
 * Represents the Tableaux system for D.
 *
 * @package D
 */
class ProofSystem extends \GoTableaux\Logic\K\ProofSystem
{
	public $ruleClasses = array(
		'K.Closure',
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
		// If an application of the serial rule did not then result in other
		// rules applying, force the tableau to finish to prevent infinite
		// application of the serial rule.
		'FinishAfterSerial',
		// Apply serial rule only after all other rules have been applied.
		// Otherwise, an infinite tree will result on invalid arguments.
		'Serial',
	);
}