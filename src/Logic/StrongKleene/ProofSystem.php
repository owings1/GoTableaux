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
 * Defines the K3 tableaux system class.
 * @package StrongKleene
 * @author Douglas Owings
 */

namespace GoTableaux\Logic\StrongKleene;

/**
 * Represents the K3 tableaux system.
 * @package StrongKleene
 * @author Douglas Owings
 */
class ProofSystem extends \GoTableaux\Logic\FDE\ProofSystem
{
	public $branchRuleClasses = array(
		'FDE/ConjunctionDesignated',
		'FDE/ConjunctionUndesignated',
		'FDE/NegatedConjunctionDesignated',
		'FDE/NegatedConjunctionUndesignated',
		'FDE/DisjunctionDesignated',
		'FDE/DisjunctionUndesignated',
		'FDE/NegatedDisjunctionDesignated',
		'FDE/NegatedDisjunctionUndesignated',
		'FDE/MaterialConditionalDesignated',
		'FDE/MaterialConditionalUndesignated',
		'FDE/NegatedMaterialConditionalDesignated',
		'FDE/NegatedMaterialConditionalUndesignated',
		'FDE/MaterialBiconditionalDesignated',
		'FDE/MaterialBiconditionalUndesignated',
		'FDE/NegatedMaterialBiconditionalDesignated',
		'FDE/NegatedMaterialBiconditionalUndesignated',
		'FDE/DoubleNegationDesignated',
		'FDE/DoubleNegationUndesignated'
	);
}