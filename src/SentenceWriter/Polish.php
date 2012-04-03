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
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program in file LICENSE.  If not, see <http://www.gnu.org/licenses/>.
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
	protected $standardOperatorTranslations = array(
		'Conjunction' => 'K',
		'Disjunction' => 'A',
		'Negation'	  => 'N',
		'Material Conditional' 		=> 'M',
		'Material Biconditional' 	=> 'Q',
	);
	
	public function writeAtomicSymbol( $symbol )
	{
		return strtolower( $symbol );
	}
	
	/**
	 * Makes a string representation of a molecular sentence.
	 *
	 * @param MolecularSentence $sentence The molecular sentence to represent.
	 * @return string The string representation of the sentence.
	 */
	public function writeMolecular( MolecularSentence $sentence )
	{
		$str = $this->writeOperator( $sentence->getOperator() );
		foreach ( $sentence->getOperands() as $operand )
			$str .= $this->_writeSentence( $operand );
		return $str;
	}
}