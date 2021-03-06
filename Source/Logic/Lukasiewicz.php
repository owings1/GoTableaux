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
 * Defines the Lukasiewicz logic.
 * @package Logics
 */

namespace GoTableaux\Logic;

/**
 * Represents Lukasiewicz 3-valued logic.
 * @package Logics
 */
class Lukasiewicz extends \GoTableaux\Logic
{
	public $title = 'Lukasiewicz 3-valued Logic';
	
	public $inheritOperatorsFrom = 'FDE';
	
	public $operatorArities = array( 'Conditional' => 2 );
	
	public $externalLinks = array(
		'Stanford Encyclopedia' => 'http://plato.stanford.edu/entries/logic-manyvalued/#LukLog'
	);
}