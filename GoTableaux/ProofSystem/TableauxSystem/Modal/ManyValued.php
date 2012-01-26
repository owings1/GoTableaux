<?php
/**
 * Defines the ManyValuedModalTableauxSystem class.
 * @package Tableaux
 * @author Douglas Owings
 */

namespace GoTableaux\ProofSystem\TableauxSystem\Modal;

use \GoTableaux\Logic as Logic;
use \GoTableaux\Argument as Argument;
use \GoTableaux\Proof\Tableau as Tableau;
use \GoTableaux\Proof\TableauBranch as Branch;

/**
 * Represents a tableaux system for a many-valued modal logic.
 * @package Tableaux
 * @author Douglas Owings
 */
class ManyValued extends \GoTableaux\ProofSystem\TableauxSystem\Modal
{
	public $branchClass = 'ManyValued';
	
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