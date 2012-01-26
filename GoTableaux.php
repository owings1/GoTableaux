<?php
/**
 * Loads the GoTableaux program.
 * @package Logic
 * @author Douglas Owings
 */
namespace GoTableaux;

/**
 * Loads the {@link Loader} class.
 */
require_once dirname( __FILE__ ) . '/GoTableaux/Loader.php';

$cpl = Logic::getInstance( 'CPL' );