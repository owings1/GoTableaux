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
 * Defines the Modal Tableaux System class.
 * @package GoTableaux
 */

namespace GoTableaux\ProofSystem\TableauxSystem;

use \GoTableaux\Logic as Logic;
use \GoTableaux\Argument as Argument;
use \GoTableaux\Proof\Tableau as Tableau;
use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Utilities as Utilities;

/**
 * Represents a bivalent modal tableaux system.
 * @package GoTableaux
 */
abstract class Modal extends \GoTableaux\ProofSystem\TableauxSystem
{
	/**
	 * Constructor.
	 *
	 * Adds world and access relation meta symbols.
	 *
	 * @param Logic logic The logic of the proof system.
	 */
	public function __construct( Logic $logic )
	{
		Utilities::uniqueAdd( 'worldSymbol', $this->metaSymbolNames );
		Utilities::uniqueAdd( 'accessRelationSymbol', $this->metaSymbolNames );
		parent::__construct( $logic );
	}
}