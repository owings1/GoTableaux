<?php
/**
 * Defines the ManyValuedTableauxSystem class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link ManyValuedBranch} class.
 */
require_once dirname( __FILE__) . '/Branch/ManyValuedBranch.php';

/**
 * Represents a many-valued propositional tableaux system.
 * @package Tableaux
 * @author Douglas Owings
 */
abstract class ManyValuedTableauxSystem extends TableauxSystem
{
	
	public $branchClass = 'ManyValuedBranch';
	
	/**
	 * Builds a modal tableau trunk.
	 *
	 * @param ModalTableau $tableau The modal tableau.
	 * @param Argument $argument The argument.
	 * @param Logic $logic The logic of the proof system.
	 * @return void
	 */
	public function buildTrunk( Tableau $tableau, Argument $argument, Logic $logic )
	{
		$trunk = $tableau->createBranch();
		foreach ( $argument->getPremises() as $premise ) $trunk->createNodeWithDesignation( $premise, true );
		$trunk->createNodeWithDesignation( $argument->getConclusion(), false );
	}
	
	public function induceModel( Branch $branch )
	{
		
	}
	
}