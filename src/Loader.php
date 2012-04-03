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
 * Defines the Loader class.
 * @package GoTableaux
 * @author Douglas Owings
 */

namespace GoTableaux;

use \GoTableaux\Exception\Loader as LoaderException;

// Register autload function
spl_autoload_register( array( __NAMESPACE__ . '\Loader', 'loadClass' ));

/**
 * Loads class files.
 * @package GoTableaux
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