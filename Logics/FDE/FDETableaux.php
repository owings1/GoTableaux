<?php
/**
 * Defines the FDE tableaux system class.
 * @package FDE
 * @author Douglas Owings
 */

/**
 * Loads the {@link ManyValuedTableauxSystem} parent class.
 */
require_once 'GoTableaux/Logic/ProofSystem/Tableaux/ManyValuedTableauxSystem.php';

/**
 * Loads the {@link BranchRule branch rule} and {@link ClosureRule closure rule} classes.
 */
require_once 'tableaux_rules.php';

/**
 * Represents the FDE tableaux system.
 * @package FDE
 * @author Douglas Owings
 */
class FDETableaux extends ManyValuedTableauxSystem
{
	public $closureRuleClass = 'FDEClosureRule';
	
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