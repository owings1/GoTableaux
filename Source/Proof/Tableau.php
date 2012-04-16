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
 * Defines the Tableau proof class.
 * @package GoTableaux
 */

namespace GoTableaux\Proof;

use \GoTableaux\Exception\Tableau as TableauException;
use \GoTableaux\Utilities as Utilities;

/**
 * Represents a tableau for an argument.
 *
 * @package GoTableaux
 */
class Tableau extends \GoTableaux\Proof
{	
	/**
	 * Holds the branches on the tree.
	 * @var array
	 */
	private $branches = array();
	
	/**
	 * Creates a new branch and attaches it to the tableau.
	 *
	 * @return Branch The created instance.
	 */
	public function createBranch()
	{
		return new TableauBranch( $this );
	}
	
	/**
	 * Attaches one or more branches to the tree.
	 *
	 * Ignores branches that are already on the tree.
	 *
	 * @param Branch|array $branches The branch or array of branches to add.
	 * @return Tableau Current instance.
	 */
	public function attach( $branches )
	{
		if ( is_array( $branches )) {
			foreach ( $branches as $branch ) $this->attach( $branch );
			return $this;
		}
		$branch = $branches;
		if ( !$branch instanceof TableauBranch )
			throw new TableauException( "Branch must be instance of class Branch." );
		if ( !in_array( $branch, $this->branches, true ))
			$this->branches[] = $branch;
		return $this;
	}
	
	/**
	 * Gets all branches on the tree.
	 *
	 * @return array Array of {@link Branch}s.
	 */
	public function getBranches()
	{
		return $this->branches;
	}
	
	/**
	 * Gets all open branches on the tree.
	 *
	 * @return array Array of {@link Branch} objects.
	 */
	public function getOpenBranches()
	{
		return array_filter( $this->getBranches(), function( $branch ) { return $branch->isOpen(); });
	}
	
	/**
	 * Checks whether there are any open branches on the tree.
	 *
	 * @return boolean Whether there are any open branches.
	 */
	public function hasOpenBranches()
	{
		return (bool) $this->getOpenBranches();
	}
	
	/**
	 * Removes one or more branches from the tree.
	 *
	 * @param Branch|array $branches The branch or array of branches to remove.
	 * @return Tableau Current instance.
	 */
	public function detach( $branches )
	{
		if ( is_array( $branches )) {
			foreach ( $branches as $branch ) $this->detach( $branch );
			return $this;
		}
		Utilities::arrayRm( $branches, $this->branches );
		return $this;
	}
	
	/**
	 * Clears all branches from the tree.
	 *
	 * @return void
	 */
	public function clearBranches()
	{
		$this->branches = array();
	}
		
	/**
	 * Gets the tableau's tree structure representation.
	 *
	 * @return Structure The tree structure.
	 */
	public function getStructure()
	{
		return TableauStructure::getInstance( $this );
	}
		
	/**
	 * Copies the tree and all its branches.
	 *
	 * @return Tableau The cloned tree.
	 */
	public function copy()
	{
		$copy = clone $this;
		$copy->clearBranches();
		foreach ( $this->branches as $branch ) $copy->attach( $branch->copy() );
		return $copy;
	}
}