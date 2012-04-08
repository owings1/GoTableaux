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
 * Defines the Loader class.
 * @package GoTableaux
 */

namespace GoTableaux;

use \GoTableaux\Exception\Loader as LoaderException;

// Register autload function
spl_autoload_register( array( __NAMESPACE__ . '\Loader', 'loadClass' ));

// Define DS constant
if ( !defined( 'DS' )) define( 'DS', DIRECTORY_SEPARATOR );

/**
 * Loads class files.
 * @package GoTableaux
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
		$path = __DIR__ . DS . str_replace( __NAMESPACE__ . DS, '', implode( DS, $arr )) . '.php';
		if ( !file_exists( $path )) return false;
		require $path;
	}
	
	/**
	 * Loads the config file.
	 *
	 * @return void
	 */
	public static function loadConfig()
	{
		require __DIR__ . DS . 'config.php';
	}
}