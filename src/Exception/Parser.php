<?php
/**
 * GoTableaux. A multi-logic tableaux generator.
 * Copyright (C) 2012  Douglas Owings
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program in file LICENSE.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * Defines the Parser class.
 * @package Exceptions
 */

namespace GoTableaux\Exception;

/**
 * Represents a parsing exception.
 * @package Exceptions
 */
class Parser extends \GoTableaux\Exception
{
	/**
	 * Character position of parse error.
	 * @var integer
	 */
	protected $position = 0;
	
	/**
	 * Input string for which error was raised.
	 * @var string
	 */
	protected $input = '';
	
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