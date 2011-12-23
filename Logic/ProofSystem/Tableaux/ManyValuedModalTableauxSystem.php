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
 * Loads the {@link ManyValuedModalSentenceNode} class.
 */
require_once 'Node/ManyValuedModalSentenceNode.php';

/**
 * Loads the {@link ManyValuedAccessNode} class
 */
require_once 'Node/ManyValuedAccessNode.php';

/**
 * Represents a tableaux system for a many-valued modal logic.
 * @package Tableaux
 * @author Douglas Owings
 */
class ManyValuedModalTableauxSystem extends ModalTableauxSystem
{
	protected $sentenceNodeClass = 'ManyValuedModalSentenceNode';
	
	protected $accessNodeClass = 'ManyValuedAccessNode';
	
	public function buildTrunk( Tableau $tableau, Argument $argument )
	{
		$nodes = array();
		
		$sentenceNodeClass = $this->sentenceNodeClass;
		
		$premises 	= $argument->getPremises();
		$conclusion = $argument->getConclusion();
				
		foreach ( $premises as $premise )
			$nodes[] = new $sentenceNodeClass( $premise, 0, true ));
		
		if ( !empty( $conclusion ))
			$nodes[] = new $sentenceNodeClass( $conclusion, 0, false ));
		
		$tableau->createBranch( $nodes );
	}
	
	public function induceModel( Branch $branch )
	{
		
	}
}