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
 * Defines the Polish notation latex sentence writer decorator class.
 * @package GoTableaux
 */

namespace GoTableaux\SentenceWriter\Polish;

/**
 * Sets default operator translations for Polish notation.
 * @package GoTableaux
 */
class LaTeXDecorator extends \GoTableaux\SentenceWriter\Standard\LaTeXDecorator
{
	protected $sentenceWriter;
	
	protected $operatorTranslations = array(
		'Conjunction' => '\mathsf{K}',
		'Disjunction' => '\mathsf{A}',
		'Negation'	  => '\mathsf{N}',
		'Material Conditional' 		=> '\mathsf{C}',
		'Material Biconditional' 	=> '\mathsf{E}',
		'Conditional' => '\mathsf{V}',
	);
}