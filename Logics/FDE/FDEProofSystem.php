<?php
/**
 * Defines the FDE tableaux system class.
 * @package FDE
 * @author Douglas Owings
 */

/**
 * Represents the FDE tableaux system.
 * @package FDE
 * @author Douglas Owings
 */
class FDEProofSystem extends ManyValuedTableauxSystem
{
	public $branchRuleClasses = array(
		'FDEBranchRule_ConjunctionDesignated',
		'FDEBranchRule_ConjunctionUndesignated',
		'FDEBranchRule_NegatedConjunctionDesignated',
		'FDEBranchRule_NegatedConjunctionUndesignated',
		'FDEBranchRule_DisjunctionDesignated',
		'FDEBranchRule_DisjunctionUndesignated',
		'FDEBranchRule_NegatedDisjunctionDesignated',
		'FDEBranchRule_NegatedDisjunctionUndesignated',
		'FDEBranchRule_MaterialConditionalDesignated',
		'FDEBranchRule_MaterialConditionalUndesignated',
		'FDEBranchRule_NegatedMaterialConditionalDesignated',
		'FDEBranchRule_NegatedMaterialConditionalUndesignated',
		'FDEBranchRule_MaterialBiconditionalDesignated',
		'FDEBranchRule_MaterialBiconditionalUndesignated',
		'FDEBranchRule_NegatedMaterialBiconditionalDesignated',
		'FDEBranchRule_NegatedMaterialBiconditionalUndesignated',
		'FDEBranchRule_DoubleNegationDesignated',
		'FDEBranchRule_DoubleNegationUndesignated'
	);
}