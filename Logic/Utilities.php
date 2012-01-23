<?php
/**
 * Defines the Utilities class.
 * @package Logic
 * @author Douglas Owings
 */

/**
 * Alias for {@link Utilities::debug()}
 */
function debug()
{
	$args = func_get_args();
	return call_user_func_array( array( 'Utilities', 'debug' ), $args );
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
	public static function debug( $var = null )
	{
		if ( !Settings::read( 'debug' )) return;
		
		switch ( func_num_args() ) {
			case 0: break;
			case 1:
				print_r( $var );
				break;
			default:
				$args = func_get_args();
				print_r( $args );
				break;
		}
	}
}