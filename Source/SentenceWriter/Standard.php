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
 * Defines the Standard notation sentence writer class.
 * @package GoTableaux
 */

namespace GoTableaux\SentenceWriter;

use \GoTableaux\Sentence as Sentence;
use \GoTableaux\Exception\Writer as WriterException;
use \GoTableaux\Sentence\Molecular as MolecularSentence;

/**
 * Writes sentences in standard notation.
 * @package GoTableaux
 */
class Standard extends \GoTableaux\SentenceWriter
{
	public $atomicStrings = array( 'A', 'B', 'C', 'D', 'E' );
	
	public $operatorStrings = array(
		'Negation' => '~',
		'Conjunction' => '&',
		'Disjunction' => 'V',
		'Material Conditional' => '>',
		'Material Biconditional' => '<>',
		'Conditional' => '->',
		'Possibility' => 'P',
		'Necessity' => 'N'
	);
	
	public function writeSentence( Sentence $sentence )
	{
		$str = parent::writeSentence( $sentence );
		if ( $sentence->getArity() === 2 ) {
			$str = substr( $str, strlen( $this->openMarkString ), -strlen( $this->closeMarkString ));
		}
		return $str;
	}
	
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
		
		$operatorStr 	= $this->writeOperator( $operator );
		
		switch ( $operator->getArity() ) {
			case 1 :
				$sentenceStr = $operatorStr . parent::writeSentence( $operands[0] );
				break;
			case 2 :
				$separator	 = $this->spaceString;
				$sentenceStr = $this->openMarkString . 
									parent::writeSentence( $operands[0] ) .
							   		$separator . $operatorStr . $separator .
							   		parent::writeSentence( $operands[1] ) . 
							   $this->closeMarkString;
				break;
			default:
				throw new WriterException( 'Cannot represent sentences with operators of arity > 2.' );
				break;
		}
		return $sentenceStr;
	}
}