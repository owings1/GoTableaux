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
 * Defines the L3 tableaux system class.
 * @package Lukasiewicz
 */

namespace GoTableaux\Logic\Lukasiewicz;

/**
 * Represents the Lukasiewicz 3 tableaux system.
 * @package Lukasiewicz
 */
class ProofSystem extends \GoTableaux\Logic\FDE\ProofSystem
{
	public $ruleClasses = array(
		'StrongKleene.Closure',
		'FDE.Closure',
		'FDE.ConjunctionDesignated',
		'FDE.ConjunctionUndesignated',
		'FDE.NegatedConjunctionDesignated',
		'FDE.NegatedConjunctionUndesignated',
		'FDE.DisjunctionDesignated',
		'FDE.DisjunctionUndesignated',
		'FDE.NegatedDisjunctionDesignated',
		'FDE.NegatedDisjunctionUndesignated',
		'FDE.MaterialConditionalDesignated',
		'FDE.MaterialConditionalUndesignated',
		'FDE.NegatedMaterialConditionalDesignated',
		'FDE.NegatedMaterialConditionalUndesignated',
		'FDE.MaterialBiconditionalDesignated',
		'FDE.MaterialBiconditionalUndesignated',
		'FDE.NegatedMaterialBiconditionalDesignated',
		'FDE.NegatedMaterialBiconditionalUndesignated',
		'FDE.DoubleNegationDesignated',
		'FDE.DoubleNegationUndesignated',
		'ConditionalDesignated',
		'ConditionalUndesignated',
		'NegatedConditionalDesignated',
		'NegatedConditionalUndesignated',
	);
}