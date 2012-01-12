<?php
/**
 * Defines the LP tableaux system class.
 * @package LP
 * @author Douglas Owings
 */

/**
 * Loads the {@link FDETableaux} parent class.
 */
require_once 'GoTableaux/Logics/FDE/FDETableaux.php';

/**
 * Loads the {@link LPClosureRule} class.
 */
require_once 'LPClosureRule.php';

/**
 * Represents the LP tableaux system.
 * @package LP
 * @author Douglas Owings
 */
class LPTableaux extends FDETableaux
{
	public $closureRuleClass = 'LPClosureRule';
}