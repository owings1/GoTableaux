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
 * Defines the Settings class.
 * @package GoTableaux
 */

namespace GoTableaux;

/**
 * Loads the config file.
 */
Loader::loadConfig();

/**
 * Stores the settings.
 * @package GoTableaux
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