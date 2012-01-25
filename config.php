<?php
/**
 * Sets the configuration.
 * @package Logic
 * @author Douglas Owings
 */

// Whether to print debug messages.
Settings::write( 'debug', false );

// Path for Logics
Settings::write( 'logicsPath', dirname( __FILE__ ) . '/Logics/' );