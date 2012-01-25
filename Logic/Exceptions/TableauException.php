<?php
/**
 * Defines the TableauException class.
 * @package Exceptions
 * @author Douglas Owings
 */

namespace GoTableaux;

/**
 * Loads the parent {@link ProofException} parent class.
 */
require_once dirname( __FILE__ ) . '/ProofException.php';

/**
 * Represents a tableau proof exception.
 * @package Exceptions
 * @author Douglas Owings
 */
class TableauException extends ProofException
{
	
}