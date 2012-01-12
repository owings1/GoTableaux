<?php
/**
 * Defines the LP logic.
 * @package LP
 * @author Douglas Owings
 */

/**
 * Loads the {@link FDE} parent logic class.
 */
require_once 'GoTableaux/Logics/FDE/FDE.php';

/**
 * Loads the {@link LPTableaux} proof system class.
 */
require_once 'LPTableaux.php';

/**
 * Represents Logic of Paradox.
 * @package LP
 * @author Douglas Owings
 */
class LP extends FDE
{
	public $proofSystemClass = 'LPTableaux';
}