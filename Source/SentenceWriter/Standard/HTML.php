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
 * Defines the LaTeX sentence writer decorator class.
 * @package GoTableaux
 */

namespace GoTableaux\SentenceWriter\Standard;

use \GoTableaux\Vocabulary as Vocabulary;
use \GoTableaux\Sentence as Sentence;
use \GoTableaux\Utilities as Utilities;
use \GoTableaux\Sentence\Atomic as AtomicSentence;
use \GoTableaux\Sentence\Molecular as MolecularSentence;

/**
 * Decorates a sentence writer for HTML.
 * @package GoTableaux
 */
class HTML extends \GoTableaux\SentenceWriter\Standard
{
	protected $operatorTranslations = array(
		'Conjunction' => '&and;',
		'Disjunction' => '&or;',
		'Negation'	  => '&not;',
		'Material Conditional' 	=> '&sup;',
		'Material Biconditional' => '&equiv;',
		'Conditional' => '&rarr;',
		'Necessity' => '◻',
		'Possibility' => '⟡'
	);
	
	public function writeSubscript( $subscript )
	{
		return '<span class="subscript" style="vertical-align: sub;">' . $subscript .'</span>';
	}
}