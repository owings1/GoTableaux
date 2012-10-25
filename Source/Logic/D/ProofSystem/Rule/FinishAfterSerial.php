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
 * Defines the D finish rule.
 * @package D
 */

namespace GoTableaux\Logic\D\ProofSystem\Rule;

use \GoTableaux\Proof\Tableau as Tableau;
use \GoTableaux\Utilities as Util;

/**
 * Forces finishing of the tableau.
 * @package D
 */
class FinishAfterSerial extends \GoTableaux\ProofSystem\TableauxSystem\Rule\TableauFinish
{
	/**
	 * Determines whether the rule can apply to the tableau.
	 *
	 * @param Tableau $tableau The tableau to check.
	 * @return boolean Whether the rule can apply.
	 */
	public function applies( Tableau $tableau )
	{
		Util::debug( get_class( $tableau->getLastRule() ));
		return $tableau->getLastRule() instanceof Serial;
	}
}