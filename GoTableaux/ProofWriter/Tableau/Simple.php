<?php
/**
 * Defines the Simple Tableau Writer class.
 * @package Tableaux
 * @author Douglas Owings
 */

namespace GoTableaux\ProofWriter\Tableau;

use \GoTableaux\Proof\TableauStructure as Structure;

/**
 * Represents a simple tableau proof writer.
 * @package Tableaux
 * @author Douglas Owings
 */
class Simple extends \GoTableaux\ProofWriter\Tableau
{
	/**
	 * Makes a string representation of a tableau structure.
	 * 
	 * @param Structure $structure The tableau structure to represent.
	 * @return string The string representation of the structure.
	 */
	public function writeStructure( Structure $structure )
	{
		return print_r( $this->getArrayForStructure( $structure ), true );
	}	
}