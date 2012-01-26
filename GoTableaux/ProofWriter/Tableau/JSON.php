<?php
/**
 * Defines the JSON Tableau Writer class.
 * @package Tableaux
 * @author Douglas Owings
 */

namespace GoTableaux\ProofWriter\Tableau;

use \GoTableaux\Logic as Logic;
use \GoTableaux\Proof as Proof;
use \GoTableaux\Argument as Argument;

/**
 * Represents a JSON tableau proof writer.
 * @package Tableaux
 * @author Douglas Owings
 */
class JSON extends \GoTableaux\ProofWriter\Tableau
{
	public function writeProof( Proof $tableau )
	{
		return json_encode( $this->getArray( $tableau ));
	}	
}