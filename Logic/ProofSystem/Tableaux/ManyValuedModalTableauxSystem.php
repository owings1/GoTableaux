<?php
/**
 * Defines the ManyValuedModalTableauxSystem class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link ManyValuedModalBranch} class.
 */
require_once dirname( __FILE__) . '/Branch/ManyValuedModalBranch.php';

/**
 * Represents a tableaux system for a many-valued modal logic.
 * @package Tableaux
 * @author Douglas Owings
 */
class ManyValuedModalTableauxSystem extends ModalTableauxSystem
{
	public $branchClass = 'ManyValuedModalBranch';
	
	public function buildTrunk( Tableau $tableau, Argument $argument, Logic $logic )
	{
		$trunk = $tableau->createBranch();
		foreach ( $argument->getPremises() as $premise ) $trunk->createSentenceNode( $premise, 0, true );
		$trunk->createSentenceNode( $argument->getConclusion(), 0, false );
	}
	
	public function induceModel( Branch $branch )
	{
		
	}
}