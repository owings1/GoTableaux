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
 * Defines the Standard notation sentence writer class.
 * @package GoTableaux
 */

namespace GoTableaux\SentenceWriter;

use \GoTableaux\Exception\Writer as WriterException;
use \GoTableaux\Sentence\Molecular as MolecularSentence;

/**
 * Writes sentences in standard notation.
 * @package GoTableaux
 */
class Standard extends \GoTableaux\SentenceWriter
{
	/**
	 * Makes a string representation of a molecular sentence.
	 *
	 * @param MolecularSentence $sentence The molecular sentence to represent.
	 * @return string The string representation of the sentence.
	 */
	public function writeMolecular( MolecularSentence $sentence )
	{
		$operator		= $sentence->getOperator();
		$operands	 	= $sentence->getOperands();
		$vocabulary		= $this->getVocabulary();
		
		$operatorStr 	= $this->writeOperator( $operator );
		
		switch ( $operator->getArity() ) {
			case 1 :
				$sentenceStr = $operatorStr . $this->_writeSentence( $operands[0] );
				break;
			case 2 :
				$separator	 = $vocabulary->getSeparators( true );
				$sentenceStr = $vocabulary->getOpenMarks( true ) . 
									$this->_writeSentence( $operands[0] ) .
							   		$separator . $operatorStr . $separator .
							   		$this->_writeSentence( $operands[1] ) . 
							   $vocabulary->getCloseMarks( true );
				break;
			default:
				throw new WriterException( 'Cannot represent sentences with operators of arity > 2.' );
				break;
		}
		return $sentenceStr;
	}
}