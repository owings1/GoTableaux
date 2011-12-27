<?php
/**
 * Defines the ManyValuedTableau class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link Tableau} parent class.
 */
require_once 'GoTableaux/Logic/ProofSystem/Tableaux/Tableau.php';

/**
 * Loads the {@link ManyValuedBranch} class.
 */
require_once 'GoTableaux/Logic/ProofSystem/Tableaux/Branch/ManyValuedBranch.php';

/**
 * Represents a tableau for a propositional logic.
 * @package Tableaux
 * @author Douglas Owings
 */
class ManyValuedTableau extends Tableau
{
	protected $branchClass = 'ManyValuedBranch';
}