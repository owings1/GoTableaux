<?php
/**
 * Defines the Utilities class.
 * @package Logic
 * @author Douglas Owings
 */

namespace GoTableaux;

/**
 * Alias for {@link Utilities::debug()}.
 * @param mixed $var
 * @return void
 */
function debug()
{
	$args = func_get_args();
	return call_user_func_array( array( __NAMESPACE__ . '\Utilities', 'debug' ), $args );
}

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