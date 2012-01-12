<?php
/**
 * Defines the Strong Kleene logic.
 * @package StrongKleene
 * @author Douglas Owings
 */

/**
 * Loads the {@link FDE} parent logic class.
 */
require_once 'GoTableaux/Logics/FDE/FDE.php';

/**
 * Loads the {@link K3} proof system class.
 */
require_once 'K3.php';

/**
 * Represents Strong Kleene Logic.
 * @package StrongKleene
 * @author Douglas Owings
 */
class StrongKleene extends FDE
{
	public $proofSystemClass = 'K3';
}