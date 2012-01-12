<?php
/**
 * Defines the K3 tableaux system class.
 * @package StrongKleene
 * @author Douglas Owings
 */

/**
 * Loads the {@link FDETableaux} parent class.
 */
require_once 'GoTableaux/Logics/FDE/FDETableaux.php';

/**
 * Loads the {@link K3ClosureRule} class.
 */
require_once 'K3ClosureRule.php';

/**
 * Represents the K3 tableaux system.
 * @package StrongKleene
 * @author Douglas Owings
 */
class K3 extends FDETableaux
{
	public $closureRuleClass = 'K3ClosureRule';
}