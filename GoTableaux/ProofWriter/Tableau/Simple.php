<?php
/**
 * Defines the Simple Tableau Writer class.
 * @package Tableaux
 * @author Douglas Owings
 */

namespace GoTableaux\ProofWriter\Tableau;

use \GoTableaux\Logic as Logic;
use \GoTableaux\Proof as Proof;
use \GoTableaux\Argument as Argument;

/**
 * Represents a simple tableau proof writer.
 * @package Tableaux
 * @author Douglas Owings
 */
class Simple extends \GoTableaux\ProofWriter\Tableau
{
	public function writeProof( Proof $tableau )
	{
		return print_r( $this->getArray( $tableau ), true );
	}	
}