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
        
        public function __construct( $node = null, array $properties = array() )
        {
            $this->node = $node;
            $this->setProperties( $properties );
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
        
        public function setProperties( array $properties )
        {
            
        }
}