<?php
/**
 * Defines the FDE tableaux system class.
 * @package FDE
 * @author Douglas Owings
 */

namespace GoTableaux\Logic\FDE;

/**
 * Represents the FDE tableaux system.
 * @package FDE
 * @author Douglas Owings
 */
class ProofSystem extends \GoTableaux\ProofSystem\TableauxSystem\ManyValued
{
	public $branchRuleClasses = array(
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
}