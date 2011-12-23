<?php
/**
 * Defines the Tableau proof class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link Proof} parent class.
 */
require_once '../Proof.php';

/**
 * Loads the {@link TableauException} class.
 */
require_once 'TableauException.php';

/**
 * Loads the {@link Structure} class.
 * @see Tableau::buildStructure()
 */
require_once 'Structure.php';

/**
 * Represents a tableau for an argument.
 *
 * @package Tableaux
 * @author Douglas Owings
 */
class Tableau extends Proof
{
	/**
	 * Defines the class name for the tableau's branches;
	 * @var string Class name.
	 * @see Tableau::createBranch()
	 */
	protected $branchClass = 'Branch';
	
	/**
	 * Holds the branches on the tree.
	 * @var array Array of {@link Branch} objects.
	 * @access private
	 */
	protected $branches = array();
	
	/**
	 * Holds the tree structure.
	 * @var Structure
	 * @access private
	 */	
	protected $structure;
	
	/**
	 * Creates a new branch and attaches it to the tableau.
	 *
	 * @param Node|array Node or array of nodes to add to the branch.
	 * @param boolean $attachToTableau Whether to attach the branch to the 
	 *								   tableau. Default is true.
	 * @return Branch the 
	 * @see $branchClass
	 */
	public function createBranch( $nodes = null, $attachToTableau = true )
	{
		$branchClass = $this->branchClass;
		$branch = new $branchClass;
		if ( !empty( $nodes )) $branch->addNode( $nodes );
		if ( $attachToTableau ) $this->attach( $branch );
		return $branch;
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
		if ( !$branch instanceof Branch )
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
		$branches = array();
		foreach ( $this->branches as $branch )
			if ( !$branch->isClosed() ) $branches[] = $branch;
		return $branches;
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
		$key = array_search( $branch, $this->branches, true );
		if ( $key !== false ) unset( $this->branches[$key] );
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
		if ( empty( $this->structure )) $this->_buildStructure();
		return $this->structure;
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

	/**
	 * Builds the tree structure.
	 *
	 * @return void
	 * @uses Structure
	 */	
	protected function _buildStructure()
	{
		$copy = $this->copy();
		$this->structure = Structure::getInstance( $copy );
		$this->structure->build();
	}
}