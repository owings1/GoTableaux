<?php
/**
 * Defines the CPLTableauxSystem class.
 * @package CPL
 * @author Douglas Owings
 */

/**
 * Loads the {@link PropositionalTableauxSystem} parent class.
 */
require_once 'GoTableaux/Logic/ProofSystem/Tableaux/PropositionalTableauxSystem.php';

/**
 * Loads the Tableaux rule class.
 */
require_once 'tableaux_rules.php';

/**
 * Represents the Tableaux system for CPL.
 *
 * @package CPL
 * @author Douglas Owings
 */
class CPLTableauxSystem extends PropositionalTableauxSystem
{
	public $closureRuleClass = 'CPLClosureRule';
	
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