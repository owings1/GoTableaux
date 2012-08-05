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
 * Defines the FDE logic class.
 * @package FDE
 */

namespace GoTableaux\Logic;

/**
 * Represents First Degree Entailment Logic.
 * @package FDE
 */
class FDE extends \GoTableaux\Logic
{	
	public $title = 'First Degree Entailment 4-valued logic';
	
	public $operatorArities = array(
		'Negation' => 1,
		'Conjunction' => 2,
		'Disjunction' => 2,
		'Material Conditional' => 2,
		'Material Biconditional' => 2,
	);
}