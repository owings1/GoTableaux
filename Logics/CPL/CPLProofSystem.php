<?php
/**
 * Defines the CPLTableauxSystem class.
 * @package CPL
 * @author Douglas Owings
 */

namespace GoTableaux;

/**
 * Represents the Tableaux system for CPL.
 *
 * @package CPL
 * @author Douglas Owings
 */
class CPLProofSystem extends PropositionalTableauxSystem
{
	public $branchRuleClasses = array(
		'CPLBranchRule_Conjunction',
		'CPLBranchRule_NegatedConjunction',
		'CPLBranchRule_Disjunction',
		'CPLBranchRule_NegatedDisjunction',
		'CPLBranchRule_MaterialConditional',
		'CPLBranchRule_NegatedMaterialConditional',
		'CPLBranchRule_MaterialBiconditional',
		'CPLBranchRule_NegatedMaterialBiconditional',
		'CPLBranchRule_DoubleNegation'
	);
}