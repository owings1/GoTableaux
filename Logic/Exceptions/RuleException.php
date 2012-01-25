<?php
/**
 * Defines the RuleException class.
 * @package Exceptions
 * @author Douglas Owings
 */

namespace GoTableaux;

/**
 * Loads the {@link TableauException} parent class.
 */
require_once dirname( __FILE__ ) . '/TableauException.php';

/**
 * Represents a tableau rule exception.
 * @package Exceptions
 * @author Douglas Owings
 */
class RuleException extends TableauException 
{
	
}