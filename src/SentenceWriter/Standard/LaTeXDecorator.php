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
 * Defines the LaTeX sentence writer decorator class.
 * @package GoTableaux
 * @author Douglas Owings
 */

namespace GoTableaux\SentenceWriter\Standard;

use \GoTableaux\Vocabulary as Vocabulary;
use \GoTableaux\Sentence as Sentence;
use \GoTableaux\Utilities as Utilities;
use \GoTableaux\Sentence\Atomic as AtomicSentence;
use \GoTableaux\Sentence\Molecular as MolecularSentence;

/**
 * Decorates a sentence writer for LaTeX.
 * @package GoTableaux
 * @author Douglas Owings
 */
class LaTeXDecorator extends \GoTableaux\SentenceWriter\Standard
{
	protected $sentenceWriter;
	
	protected $standardOperatorTranslations = array(
		'Conjunction' => '\wedge',
		'Disjunction' => '\vee',
		'Negation'	  => '\neg ',
		'Material Conditional' 		=> '\supset',
		'Material Biconditional' 	=> '\equiv',	
	);
	
	//protected $specialCharacters = array( '\\', '#', '$', '%', '&', '~', '_', '^', '{', '}' );
	
	public function writeSubscript( $subscript )
	{
		return '_{' . $subscript .'}';
	}
	
	/*
	public function escape( $str )
	{
		foreach ( $this->specialCharacters as $char )
			$str = str_replace( $char, '\\' . $char, $str );
		return $str;
	}
	*/
}