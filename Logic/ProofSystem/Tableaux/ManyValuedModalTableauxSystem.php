<?php
/**
 * Defines the ManyValuedModalTableauxSystem class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link ModalTableauxSystem} parent class.
 */
require_once 'ModalTableauxSystem.php';

/**
 * Loads the {@link ManyValuedModalTableau} class.
 */
require_once 'Tableau/ManyValuedModalTableau.php';

/**
 * Represents a tableaux system for a many-valued modal logic.
 * @package Tableaux
 * @author Douglas Owings
 */
class ManyValuedModalTableauxSystem extends ModalTableauxSystem
{
	protected $proofClass = 'ManyValuedModalTableau';
	
	public function buildTrunk( Tableau $tableau, Argument $argument )
	{
		$branch = $tableau->createBranch();
		foreach ( $argument->getPremises() as $premise ) $branch->addSentenceNode( $premise, 0, true );
		$branch->addSentenceNode( $argument->getConclusion(), 0, false );
	}
	
	public function induceModel( Branch $branch )
	{
		
	}
}