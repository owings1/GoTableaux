<?php
/**
 * Defines the ModalTableau class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link Tableau} parent class.
 */
require_once 'GoTableaux/Logic/ProofSystem/Tableaux/Tableau.php';

/**
 * Loads the {@link ModalBranch} class.
 */
require_once 'GoTableaux/Logic/ProofSystem/Tableaux/Branch/ModalBranch.php';

/**
 * Represents a tableau for a modal logic.
 * @package Tableaux
 * @author Douglas Owings
 */
class ModalTableau extends Tableau
{
	protected $branchClass = 'ModalBranch';
}