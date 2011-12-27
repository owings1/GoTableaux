<?php
/**
 * Defines the PropositionalTableau class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link Tableau} parent class.
 */
require_once 'GoTableaux/Logic/ProofSystem/Tableaux/Tableau.php';

/**
 * Loads the {@link PropositionalBranch} class.
 */
require_once 'GoTableaux/Logic/ProofSystem/Tableaux/Branch/PropositionalBranch.php';

/**
 * Represents a tableau for a propositional logic.
 * @package Tableaux
 * @author Douglas Owings
 */
class PropositionalTableau extends Tableau
{
	protected $branchClass = 'PropositionalBranch';
}