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
 * Defines the EventHandler class.
 * @package GoTableaux
 */

namespace GoTableaux;

/**
 * Handles Events.
 *
 * @package GoTableaux
 */
class EventHandler
{
	private static $bindings = array();
	
	private static $bindingCount = 0;
	
	/**
	 * Binds a callback to an event.
	 *
	 * @param string $event The event name.
	 * @param callback $callback The callback.
	 * @return integer The listener id relative to the event.
	 */
	public static function bind( $object, $event, $callback )
	{
		$objectId = is_object( $object ) ? spl_object_hash( $object ) : $object;
		if ( !isset( self::$bindings[ $objectId ]))
			self::$bindings[ $objectId ] = array( 'object' => $object, 'listeners' => array() );
		self::$bindings[ $objectId ][ 'listeners' ][ 'listener' . self::$bindingCount ] = array( $event, $callback );
		return self::$bindingCount++;
	}
	
	/**
	 * Unbinds a listener.
	 *
	 * @param integer $listenerId The listener id returned by bind().
	 * @return void
	 */
	//public static function unbind( $listenerId )
	//{
	//	unset( self::$bindings[ 'listener' . $listenerId ]);
	//}
	
	/**
	 * Triggers an event on an object.
	 *
	 * @param object $object The object on which to trigger the event.
	 * @param string $event The event name to trigger.
	 * @return void
	 */
	public static function trigger( $object, $event, $args = array() )
	{
		$args = array_merge( array( $object ), $args );
		foreach ( self::getBindings( $object, $event ) as $binding ) 
			call_user_func_array( $binding[1], $args );
	}
	
	/**
	 * Copies event listeners for one object to another.
	 *
	 * @param object $source The source object.
	 * @param object $target The target object.
	 */
	public static function copy( $source, $target )
	{
		$targetId = spl_object_hash( $target );
		foreach ( self::getBindings( $source ) as $binding )
			self::bind( $targetId, $binding[0], $binding[1] );
	}
	
	/**
	 * Gets the bindings of an object.
	 *
	 * @param object $object The object whose bindings to get.
	 * @param string $event The event name.
	 * @return array The bindings, array( $object, $event, $callback )
	 */
	public static function getBindings( $object, $event = null )
	{
		$objectId = spl_object_hash( $object );
		if ( !isset( self::$bindings[ $objectId ])) return array();
		$bindings = self::$bindings[ $objectId ][ 'listeners' ];
		if ( $event === null ) return $bindings;
		return array_filter( $bindings, function( $binding ) use( $event ) {
			return $event === $binding[0];
		});
	}
}