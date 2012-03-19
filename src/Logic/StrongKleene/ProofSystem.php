<?php
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