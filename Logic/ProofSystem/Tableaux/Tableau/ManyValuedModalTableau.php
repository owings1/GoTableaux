<?php
/**
 * Defines the ManyValuedModalTableau class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link ModalTableau} parent class.
 */
require_once 'ModalTableau.php';

/**
 * Loads the {@link ManyValuedModalBranch} branch class.
 */
require_once 'GoTableaux/Logic/ProofSystem/Tableaux/Branch/ManyValuedModalBranch.php';

/**
 * Represents a tableau for a many-valued modal logic.
 * @package Tableaux
 * @author Douglas Owings
 */
class ManyValuedModalTableau extends ModalTableau
{
	protected $branchClass = 'ManyValuedModalBranch';
}
