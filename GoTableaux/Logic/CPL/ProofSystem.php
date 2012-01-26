<?php
/**
 * Defines the CPLTableauxSystem class.
 * @package CPL
 * @author Douglas Owings
 */

namespace GoTableaux\Logic\CPL;

/**
 * Represents the Tableaux system for CPL.
 *
 * @package CPL
 * @author Douglas Owings
 */
class ProofSystem extends \GoTableaux\ProofSystem\TableauxSystem\Propositional
{
	public $branchRuleClasses = array(
		'Conjunction',
		'NegatedConjunction',
		'Disjunction',
		'NegatedDisjunction',
		'MaterialConditional',
		'NegatedMaterialConditional',
		'MaterialBiconditional',
		'NegatedMaterialBiconditional',
		'DoubleNegation'
	);
}