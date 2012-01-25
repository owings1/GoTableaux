<?php
/**
 * Defines the Settings class.
 * @package Logic
 * @author Douglas Owings
 */

namespace GoTableaux;

/**
 * Loads the config file.
 */
require_once dirname( __FILE__) . '/../config.php';

/**
 * Stores the settings.
 * @package Logic
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