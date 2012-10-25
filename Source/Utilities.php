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
 * This program is distributed WITHOUT ANY WARRANTY. 
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/agpl-3.0.html>.
 */
/**
 * Defines the Utilities class.
 * @package GoTableaux
 */

namespace GoTableaux;

/**
 * Collects general PHP utilities.
 * @package GoTableaux
 */
class Utilities
{
	/**
	 * Removes an element from an array, if contained.
	 *
	 * @param mixed $element The element to remove.
	 * @param array $array The array from which to remove the element.
	 * @return void
	 */
	public static function arrayRm( $element, array &$array )
	{
		$key = array_search( $element, $array, true );
		if ( $key !== false ) array_splice( $array, $key, 1 );
	}
	
	/**
	 * Adds an element to an array, if not already contained.
	 *
	 * @param mixed $element The element to add.
	 * @param array $array The array to which to add the element.
	 * @return void
	 */
	public static function uniqueAdd( $element, array &$array )
	{
		if ( !in_array( $element, $array, true )) $array[] = $element;
	}
	/**
	 * Strictly subtracts one array from the other.
	 *
	 * @param array $a The first array.
	 * @param array $b The array of items to subtract.
	 * @return array The resulting array.
	 */
	public static function arrayDiff( array $a, array $b )
	{
		$diff = array();
		foreach ( $a as $item )
			if ( !in_array( $item, $b, true )) $diff[] = $item;
		return $diff;
	}
	
	/**
	 * Produces a strictly unique array.
	 *
	 * @param array $arr The array to make unique.
	 * @return array The unique array.
	 */
	public static function arrayUnique( array $arr )
	{
		$unique = array();
		foreach ( $arr as $item )
			if ( !in_array( $item, $unique, true )) $unique[] = $item;
		return $unique;
	}
	
	/**
	 * Compares arrays of objects against identity.
	 *
	 * @param array $arr,... Variable list of arrays to compare.
	 * @return boolean True if each array has the same keys referencing
	 *				   identical objects.
	 */
	public static function arraysAreIdentical()
	{
		$arrays = func_get_args();
		foreach ( $arrays as $arr_a )
			foreach ( $arrays as $arr_b ) {
				if ( count( $arr_a ) !== count( $arr_b ) || $arr_a !== $arr_b ) return false;
				foreach ( $arr_a as $key => $value )
					if ( $value !== $arr_b[$key] ) return false;
			}
		return true;
	}
	
	/**
	 * Sorts two strings by their length.
	 *
	 * @param string $a The first string.
	 * @param string $b The second string.
	 * @return integer 
	 */
	public static function sortByStrLen( $a, $b )
	{
		return ( strlen( $a ) > strlen( $b ) ) ? -1 : ( ( strlen( $b ) > strlen( $a )) ? 1 : 0 );
	}
	
	/**
	 * Searches a string for the first occurrence of any string in a given array.
	 *
	 * @param string $haystack The string to search.
	 * @param array $needles An array of strings to seek.
	 * @param integer $offset The offset of $haystack at which to begin.
	 * @param string &$match Holds the first match.
	 * @return integer|boolean Position of $haystack at which the first match
	 *						   was found, OR false if no match is found.
	 */
	public static function strPosArr( $haystack, array $needles, $offset = 0, &$match = null )
	{
		$position = strlen( $haystack ) + 1;
		foreach ( $needles as $needle ) {
			$pos = strpos( $haystack, $needle, $offset );
			if ( false !== $pos && $pos < $position ) {
				$position = $pos;
				$match = $needle;
			}
		}
		return ( $position < ( strlen( $haystack ) + 1 )) ? $position : false;
	}
	
    /**
     * Gets the base class name of an object.
     * 
     * @param object|string $objectOrClass The object or class whose base class name to get.
     * @return string The base class name, e.g. a object of class Space\Cadet
     *                will return 'Cadet'.
     */
    public static function getBaseClassName( $objectOrClass )
    {
		$class = is_object( $objectOrClass ) ? get_class( $objectOrClass ) : $objectOrClass;
        $nameParts = explode( '\\', $class );
		return array_pop( $nameParts );
    }
        
	/**
	 * Prints debugging information, if the debug setting is set to true.
	 * 
	 * @param mixed $var,... Variables to print information about.
	 * @return void
	 * @see config.php
	 */
	public static function debug()
	{
		if ( !Settings::read( 'debug' )) return;
		$args = func_get_args();
		switch ( func_num_args() ) {
			case 0: break;
			case 1:
				print_r( array_pop( $args ));
				break;
			default:
				print_r( $args );
				break;
		}
		echo PHP_EOL;
	}
}

/**
 * Alias for Utilities::debug()
 * @package GoTableaux
 * @param mixed $var
 * @return void
 */
function debug()
{
	$args = func_get_args();
	return call_user_func_array( array( __NAMESPACE__ . '\Utilities', 'debug' ), $args );
}