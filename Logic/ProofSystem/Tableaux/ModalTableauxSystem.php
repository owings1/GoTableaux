<?php
/**
 * Defines the ModalTableauxSystem class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link TableauxSystem} parent class.
 */
require_once 'GoTableaux/Logic/ProofSystem/TableauxSystem.php';

/**
 * Loads the {@link ModalBranch} class.
 */
require_once 'Branch/ModalBranch.php';

/**
 * Represents a bivalent modal tableaux system.
 * @package Tableaux
 * @author Douglas Owings
 */
abstract class ModalTableauxSystem extends TableauxSystem
{
	
	public $branchClass = 'ModalBranch';
	
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
		foreach ( $argument->getPremises() as $premise ) $trunk->createSentenceNodeAtIndex( $premise, 0 );
		$trunk->createSentenceNodeAtIndex( $logic->negate( $argument->getConclusion() ), 0 );
	}
	
	public function induceModel( Branch $branch )
	{
		
	}
}