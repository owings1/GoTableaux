<?php
/**
 * Defines the Loader class.
 * @package Logic
 * @author Douglas Owings
 */

namespace GoTableaux;

use \GoTableaux\Exception\Loader as LoaderException;

if ( !defined( 'DS' )) define( 'DS', DIRECTORY_SEPARATOR );

spl_autoload_register( array( __NAMESPACE__ . '\\Loader', 'loadClass' ));

/**
 * Loads class files.
 * @package Logic
 * @author Douglas Owings
 */
class Loader
{
	/**
	 * Loads a class file by parsing its namespace.
	 *
	 * Registered as an autoloader.
	 *
	 * @param string $class The class name to load.
	 * @return void
	 */
	public static function loadClass( $class )
	{
		$arr = explode( '\\', $class );
		$path = dirname( __FILE__ ) . DS . str_replace( __NAMESPACE__ . DS, '', implode( DS, $arr )) . '.php';
		if ( !file_exists( $path ))
			throw new LoaderException( "Cannot load class $class looking for $path" );
		require $path;
	}
	
	/**
	 * Loads the config file.
	 *
	 * @return void
	 */
	public static function loadConfig()
	{
		require dirname( __FILE__ ) . '/../config.php';
	}
}