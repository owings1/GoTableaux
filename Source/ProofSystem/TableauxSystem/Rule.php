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
 * Defines the BranchRule interface.
 * @package GoTableaux
 */

namespace GoTableaux\ProofSystem\TableauxSystem;

use \GoTableaux\Logic as Logic;
use \GoTableaux\Proof\Tableau as Tableau;

/**
 * Represents a tableau rule that applies to a branch.
 * @package GoTableaux
 */
interface Rule
{
	/**
	 * Applies the rule to a tableau.
	 *
	 * @param Tableau $tableau The tableau to which to apply the rule.
	 * @return boolean Whether the rule did apply.
	 */
	public function apply( Tableau $tableau );
}