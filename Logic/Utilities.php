<?php
/**
 * Defines the Utilities class.
 * @package Logic
 * @author Douglas Owings
 */

/**
 * Collects general PHP utilities.
 * @package Logic
 * @author Douglas Owings
 */
class Utilities
{
	/**
	 * Strictly subtracts one array from the other.
	 *
	 * @param array $a The first array.
	 * @param array $b The array of items to subtract.
	 * @return array The resulting array.
	 */
	public static function arrayDiff( array $a, array $b )
	{
		$ret = array();
		foreach ( $a as $item )
			if ( !in_array( $item, $b, true )) $ret[] = $item;
		return $ret;
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
}