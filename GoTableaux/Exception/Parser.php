<?php
/**
 * Defines the Parser class.
 * @package Exceptions
 * @author Douglas Owings
 */

namespace GoTableaux\Exception;

/**
 * Represents a parsing exception.
 * @package Exceptions
 * @author Douglas Owings
 */
class Parser extends \GoTableaux\Exception
{
	/**
	 * Character position of parse error.
	 * @var integer
	 * @access private
	 */
	protected $position;
	
	/**
	 * Input string for which error was raised.
	 * @var string
	 * @access private
	 */
	protected $input;
	
	/**
	 * Creates an instance with options array.
	 *
	 * @param array $options Associative array of options. Possible keys are
	 *						'message', 'code', 'previous', 'position', 'input'.
	 * @return Parser Created instance.
	 */
	public static function createWithOptions( array $options )
	{
		$defaults = array(
			'message'	=> 'Parse error.',
			'position' 	=> 0,
			'input' 	=> ''
		);
		$options = array_merge( $defaults, $options );
		$instance = new self( $options['message'] );
		$instance->position = $options['position'];
		$instance->input = $options['input'];
		return $instance;
	}
	
	/**
	 * Creates an instance with message, input, and position parameters.
	 *
	 * @param string $message The error message.
	 * @param string $input The current input string.
	 * @param integer $position The input string offset at which the error occurred.
	 * @return Parser Created instance.
	 */
	public static function createWithMsgInputPos( $message = '', $input = '', $position = 0 )
	{
		return self::createWithOptions( compact( 'message', 'input', 'position' ));
	}
}