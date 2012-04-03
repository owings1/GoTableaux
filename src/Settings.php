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
 * Defines the Settings class.
 * @package GoTableaux
 * @author Douglas Owings
 */

namespace GoTableaux;

/**
 * Loads the config file.
 */
Loader::loadConfig();

/**
 * Stores the settings.
 * @package GoTableaux
 * @author Douglas Owings
 */
class Settings
{
	/**
	 * Holds the settings.
	 * @var array
	 * @access private
	 */
	protected static $settings;
	
	/**
	 * Reads a setting.
	 *
	 * @param string $setting The setting to read.
	 * @return mixed The setting's value, or null if not set.
	 */
	public static function read( $setting )
	{
		if ( array_key_exists( $setting, self::$settings ))
			return self::$settings[$setting];
		return null;
	}
	
	/**
	 * Writes a setting.
	 *
	 * @param string $setting The setting to write.
	 * @param mixed $value The value to write.
	 * @return void
	 */
	public static function write( $setting, $value )
	{
		self::$settings[$setting] = $value;
	}
}