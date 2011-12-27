<?php
/**
 * Defines the ManyValuedTableauxSystem class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link TableauxSystem} parent class.
 */
require_once 'GoTableaux/Logic/ProofSystem/TableauxSystem.php';

/**
 * Loads the {@link ManyValuedTableau} proof class.
 */
require_once 'Tableau/ManyValuedTableau.php';

/**
 * Loads the {@link ManyValuedSentenceNode} class.
 */
require_once 'Node/ManyValuedSentenceNode.php';

/**
 * Represents a many-valued propositional tableaux system.
 * @package Tableaux
 * @author Douglas Owings
 */
abstract class ManyValuedTableauxSystem extends TableauxSystem
{
	
	protected $proofClass = 'ManyValuedTableau';
	
	/**
	 * Builds a modal tableau trunk.
	 *
	 * @param ModalTableau $tableau The modal tableau.
	 * @param Argument $argument The argument.
	 * @return void
	 */
	public function buildTrunk( Tableau $tableau, Argument $argument )
	{
		$trunk = $tableau->createBranch();
		foreach ( $argument->getPremises() as $premise ) $trunk->createNode( $premise, true );
		$trunk->createNode( $argument->getConclusion(), false );
	}
	
	public function induceModel( Branch $branch )
	{
		
	}
	
}