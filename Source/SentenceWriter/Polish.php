<?php
/**
 * GoTableaux. A multi-logic tableaux generator.
 * Copyright (C) 2012  Douglas Owings
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed WITHOUT ANY WARRANTY. 
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/agpl-3.0.html>.
 */
/**
 * Defines the Standard Sentence Writer class.
 * @package GoTableaux
 */

namespace GoTableaux\SentenceWriter;

use \GoTableaux\Sentence\Molecular as MolecularSentence;

/**
 * Writes sentences in Polish notation.
 * @package GoTableaux
 */
class Polish extends \GoTableaux\SentenceWriter
{
	public $atomicStrings = array( 'a', 'b', 'c', 'd', 'e' );
	
	public $operatorStrings = array(
		'Conjunction' => 'K',
		'Disjunction' => 'A',
		'Negation'	  => 'N',
		'Material Conditional' 		=> 'C',
		'Material Biconditional' 	=> 'E',
		'Possibility' => 'M',
		'Necessity' => 'L',
		'Conditional' => 'U'
	);
	
	/**
	 * Makes a string representation of a molecular sentence.
	 *
	 * @param MolecularSentence $sentence The molecular sentence to represent.
	 * @return string The string representation of the sentence.
	 */
	public function writeMolecular( MolecularSentence $sentence )
	{
		$str = $this->operatorStrings[ $sentence->getOperatorName() ];
		foreach ( $sentence->getOperands() as $operand )
			$str .= parent::writeSentence( $operand );
		return $str;
	}
	
	public function writeSubscript( $subscript )
	{
		return "$subscript";
	}
}