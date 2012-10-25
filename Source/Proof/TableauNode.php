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
 * Defines the Node base class.
 * @package GoTableaux
 */

namespace GoTableaux\Proof;

use \GoTableaux\Logic as Logic;
use \GoTableaux\Utilities as Utilities;

/**
 * Represents a node on a branch.
 * @package GoTableaux
 */
class TableauNode
{
	/**
	 * Meta proof symbol names required by the node.
	 * @var array
	 */
    public static $metaSymbolNames = array( 'tickMarker' );

    /**
     * The node for decorators.
     * @var TableauNode
     */
    protected $node;
    
	/**
	 * The decorating node, if any.
	 * @var TableauNode
	 */
	protected $master;
	
	/**
	 * The child classes.
	 * @var array
	 */
	private static $childClasses = array();
	
	/**
	 * Gets all the child classes.
	 *
	 * @return array The child classes.
	 */
	public static function getChildClasses()
	{
		if ( empty( self::$childClasses )) {
			foreach ( glob( __DIR__ . DS . 'TableauNode' . DS . '*.php') as $file ) {
				$class =  __NAMESPACE__ . '\TableauNode\\' . str_replace( '.php', '', basename( $file ));
				if ( class_exists( $class )) self::$childClasses[] = '\\' . $class; 
			}
		}
		return self::$childClasses;
	}
	
	/**
	 * Induces node classes based on which conditions are set.
	 *
	 * @param array $conditions The conditions.
	 */
	public static function induceClassesFromConditions( array $conditions )
	{
		$classes = array();
		foreach ( self::getChildClasses() as $class ) {
			$forceConditions = $class::$forceClassOnConditions;
			if ( empty( $forceConditions )) continue;
			
			foreach ( $class::$forceClassOnConditions as $condition )
				if ( isset( $conditions[$condition] )) {
					$classes[] = Utilities::getBaseClassName( $class );
					break;
				}
		}
		return $classes;
	}
	
	/**
	 * Constructor. Initializes decorator.
	 *
	 * To build a node, first create an instance of TableauNode with empty
	 * arguments, then successively add decorators, each time passing the newly
	 * created node, along with the properties. 
	 *
	 * @param TableauNode $node The node to decorate.
	 * @param array $properties The properties hash of the node.
	 */
    public function __construct( $node = null, array $properties = array() )
    {
		if ( !empty( $node )) {
			$this->node = $node;
			$node->master = $this;
		}
        $this->setProperties( $properties );
    }

	/**
	 * Passes undeclared functions to decorated instance.
	 *
	 * @param string $method The name of the method invoked.
	 * @param array $args The passed arguments.
	 * @return void
	 */
	public function __call( $method, $args ) 
	{
		if ( !empty( $this->node ))
			return call_user_func_array( array( $this->node, $method ), $args );
		throw new \ErrorException( "Invalid method $method" );
  	}
	
	/**
	 * Ticks the node relative to a branch.
	 *
	 * @param TableauBranch $branch The branch relative to which to tick the
	 *								  node.
	 * @return TableauNode Current instance.
	 */
	public function tickAtBranch( TableauBranch $branch )
	{
		$branch->tickNode( $this );
		return $this;
	}
	
	/**
	 * Checks whether the node is ticked relative to a particular branch.
	 *
	 * @param TableauBranch $branch The branch relative to which to check.
	 * @return boolean Whether the node is ticked relative to $branch.
	 */
	public function isTickedAtBranch( TableauBranch $branch )
	{
		return $branch->nodeIsTicked( $this );
	}
	
	/**
	 * Gets all the classes of the node, including decorated classes.
	 *
	 * @return array The classes of the node
	 */
	public function getClasses()
	{
		$classes = array();
		for ( $node = $this->getMaster(); !empty( $node ); $node = $node->node ) 
			if ( get_class( $node ) !== __CLASS__ ) 
				Utilities::uniqueAdd( Utilities::getBaseClassName( $node ), $classes );
		return $classes;
	}
	
	/**
	 * Determines wether the node or its decorated instance has a given class.
	 *
	 * @param string $class The class to check.
	 * @return boolean Whether the node or its instance has the class.
	 */
	public function hasClass( $class )
	{
		if ( is_array( $class )) throw new \ErrorException( 'cannot pass array ' );
		$className = __NAMESPACE__ . '\TableauNode\\' . $class;
		for ( $node = $this->getMaster(); !empty( $node ); $node = $node->node )
			if ( $node instanceof $className ) return true;
		return false;
	}
	
	/**
	 * Determines whether the node passes the given conditions.
	 *
	 * This is called, for example, when querying a branch for particular nodes.
	 * Direct children should first check $this->node->filter(), and return 
	 * false if it does, otherwise continue with filtering. Further descendants
	 * should likewise check parent::filter().
	 *
	 * @param array $conditions A hash of the conditions to pass.
	 * @param Logic $logic The logic.
	 * @return boolean Wether the node passes (i.e. is not ruled out by) the conditions.
	 * @see TableauBranch::find()
	 */
	public function filter( array $conditions, Logic $logic )
	{
		$master = $this->getMaster();
		$classes = self::induceClassesFromConditions( $conditions );
		foreach ( $classes as $class ) if ( !$master->hasClass( $class )) return false;
		return true;
	}
	
	/**
	 * Called before the node is added to a branch.
	 *
	 * Implementations should always call parent::beforeAttach().
	 *
	 * @param TableauBranch $branch The branch that the node is to be added to.
	 * @return void
	 */
	public function beforeAttach( TableauBranch $branch )
	{
		return;
	}
    
	/**
	 * Called during construct for decorators.
	 *
	 * Direct children should always call $this->node->setProperties(),
	 * and further descendants should call parent::setProperties().
	 *
	 * @param array $properties A hash of properties to set.
	 * @return void
	 */
    public function setProperties( array $properties )
    {
        return;
    }

	/**
	 * Gets the master decorating node.
	 * 
	 * @return TableauNode The master node.
	 */
	protected function getMaster()
	{
		for ( $master = $this; !empty( $master->master ); $master = $master->master );
		return $master;
	}
}