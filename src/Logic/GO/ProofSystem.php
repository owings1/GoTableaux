<?php
/**
 * Defines the GOTableauxSystem class.
 * @package GO
 * @author Douglas Owings
 */

namespace GoTableaux\Logic\GO;

/**
 * Represents the Tableaux system for GO.
 *
 * @package GO
 * @author Douglas Owings
 */
class ProofSystem extends \GoTableaux\ProofSystem\TableauxSystem\ManyValued
{
	public $branchRuleClasses = array(
		'FDE/ConjunctionDesignated',
		'ConjunctionUndesignated',
		'NegatedConjunctionDesignated',
		'NegatedConjunctionUndesignated',
		'FDE/DisjunctionDesignated',
		'DisjunctionUndesignated',
		'NegatedDisjunctionDesignated',
		'NegatedDisjunctionUndesignated',
		'FDE/MaterialConditionalDesignated',
		'MaterialConditionalUndesignated',
		'NegatedMaterialConditionalDesignated',
		'NegatedMaterialConditionalUndesignated',
		'FDE/MaterialBiconditionalDesignated',
		'MaterialBiconditionalUndesignated',
		'NegatedMaterialBiconditionalDesignated',
		'NegatedMaterialBiconditionalUndesignated',
		'FDE/DoubleNegationDesignated',
		'FDE/DoubleNegationUndesignated'
	);
}