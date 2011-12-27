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
 * Loads the {@link PropositionalTableau} proof class.
 */
require_once 'Tableau/PropositionalTableau.php';

/**
 * Loads the {@link SentenceNode} class.
 */
require_once 'Node/SentenceNode.php';

/**
 * Represents a bivalent propositional tableaux system.
 * @package Tableaux
 * @author Douglas Owings
 */
abstract class PropositionalTableauxSystem extends TableauxSystem
{
	
	protected $proofClass = 'PropositionalTableau';
	
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
		foreach ( $argument->getPremises() as $premise ) $trunk->createNode( $premise );
		$negatedConclusion = $this->negateSentence( $argument->getConclusion() );
		$trunk->createNode( $negatedConclusion );
	}
	
	public function induceModel( Branch $branch )
	{
		
	}
	
}