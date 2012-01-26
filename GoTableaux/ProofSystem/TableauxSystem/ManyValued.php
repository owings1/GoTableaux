<?php
/**
 * Defines the ManyValuedTableauxSystem class.
 * @package Tableaux
 * @author Douglas Owings
 */

namespace GoTableaux\ProofSystem\TableauxSystem;

use \GoTableaux\Logic as Logic;
use \GoTableaux\Argument as Argument;
use \GoTableaux\Proof\Tableau as Tableau;
use \GoTableaux\Proof\TableauBranch as Branch;

/**
 * Represents a many-valued propositional tableaux system.
 * @package Tableaux
 * @author Douglas Owings
 */
abstract class ManyValued extends \GoTableaux\ProofSystem\TableauxSystem
{
	
	public $branchClass = 'ManyValued';
	
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