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
 * Loads the {@link ModalTableau} proof class.
 */
require_once 'Tableau/ModalTableau.php';

/**
 * Loads the {@link ModalSentenceNode} class.
 * @see $sentenceNodeClass
 */
require_once 'Node/ModalSentenceNode.php';

/**
 * Loads the {@link AccessNode} class.
 * @see $accessNodeClass
 */
require_once 'Node/AccessNode.php';

/**
 * Represents a bivalent modal tableaux system.
 * @package Tableaux
 * @author Douglas Owings
 */
abstract class ModalTableauxSystem extends TableauxSystem
{
	
	protected $proofClass = 'ModalTableau';
	
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
		foreach ( $argument->getPremises() as $premise ) $trunk->createSentenceNode( $premise, 0 );
		$negatedConclusion = Sentence::createMolecular( $this->getOperator( 'Negation' ), array( $argument->getConclusion() ));
		$trunk->createSentenceNode( $negatedConclusion, 0 );
	}
	
	public function induceModel( Branch $branch )
	{
		
	}
	
}