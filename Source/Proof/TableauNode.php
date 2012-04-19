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

use \GoTableaux\Utilities as Utilities;

/**
 * Represents a node on a branch.
 * @package GoTableaux
 */
class TableauNode
{
    
    /**
     * The node for decorators.
     * @var TableauNode
     */
    protected $node;
    
	/**
	 * Constructor. Initializes decorator.
	 *
	 * To build a node, first create an instance of TableauNode with empty
	 * arguments, then successively add decorators, each time passing the newly
	 * created node, along with the properties. 
	 *
	 * @param TableauNode $node
	 */
    public function __construct( $node = null, array $properties = array() )
    {
		if ( !empty( $node )) $this->setNode( $node );
        $this->setProperties( $properties );
    }

	/**
	 * Passes undeclared functions to decorated instance.
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
	 * @param Branch $branch The branch relative to which to tick the
	 *								  node.
	 * @return Node Current instance.
	 */
	public function tickAtBranch( TableauBranch $branch )
	{
		$branch->tickNode( $this );
		return $this;
	}
	
	/**
	 * Checks whether the node is ticked relative to a particular branch.
	 *
	 * @param Branch $branch The branch relative to which to check.
	 * @return boolean Whether the node is ticked relative to $branch.
	 */
	public function isTickedAtBranch( TableauBranch $branch )
	{
		return $branch->nodeIsTicked( $this );
	}
	
	/**
	 * Determines wether the node or its decorated instance has a given class.
	 *
	 * @param string $class The class to check.
	 * @return boolean Whether the node or its instance has the class.
	 */
	public function hasClass( $class )
	{
		$className = __NAMESPACE__ . '\TableauNode\\' . $class;
		if ( empty( $this->node )) 
			return $this instanceof $className;
		return $this instanceof $className || $this->node->hasClass( $class );
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
	 * @return boolean Wether the node passes (i.e. is not ruled out by) the conditions.
	 * @see TableauBranch::find()
	 */
	public function filter( array $conditions )
	{
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
        
    }

	/**
	 * Sets the decorated node.
	 *
	 * @param TableauNode $node The node to decorate.
	 * @return void
	 */
	private function setNode( TableauNode $node )
	{
		$this->node = $node;
	}
}