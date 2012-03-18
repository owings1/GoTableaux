<?php
/**
 * Defines the Loader class.
 * @package Logic
 * @author Douglas Owings
 */

namespace GoTableaux;

use \GoTableaux\Exception\Loader as LoaderException;

// Register autload function
spl_autoload_register( array( __NAMESPACE__ . '\Loader', 'loadClass' ));

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
		$ds = DIRECTORY_SEPARATOR;
		$arr = explode( '\\', $class );
		$path = __DIR__ . $ds . str_replace( __NAMESPACE__ . $ds, '', implode( $ds, $arr )) . '.php';
		if ( !file_exists( $path )) return false;
			//throw new LoaderException( "Cannot load class $class looking for $path" );
		require $path;
	}
	
	/**
	 * Loads the config file.
	 *
	 * @return void
	 */
	public static function loadConfig()
	{
		require __DIR__ . '/../config.php';
	}
}