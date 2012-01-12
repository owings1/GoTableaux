<?php
/**
 * Defines the PropositionalTableauxSystem class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link TableauxSystem} parent class.
 */
require_once 'GoTableaux/Logic/ProofSystem/TableauxSystem.php';

/**
 * Loads the {@link PropositionalBranch} class.
 */
require_once 'Branch/PropositionalBranch.php';

/**
 * Represents a bivalent propositional tableaux system.
 * @package Tableaux
 * @author Douglas Owings
 */
abstract class PropositionalTableauxSystem extends TableauxSystem
{
	
	public $branchClass = 'PropositionalBranch';
	
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
		foreach ( $argument->getPremises() as $premise ) $trunk->createNode( $premise );
		$trunk->createNode( $logic->negate( $argument->getConclusion() ));
	}
	
	public function induceModel( Branch $branch )
	{
		
	}
}