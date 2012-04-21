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
 * Defines the TableauxSystem base class.
 * @package GoTableaux
 */

namespace GoTableaux\ProofSystem;

use \GoTableaux\Settings as Settings;
use \GoTableaux\Logic as Logic;
use \GoTableaux\Argument as Argument;
use \GoTableaux\Proof as Proof;
use \GoTableaux\Proof\Tableau as Tableau;
use \GoTableaux\Proof\TableauBranch as Branch;
use \GoTableaux\Exception\Tableau as TableauException;
use \GoTableaux\ProofSystem\TableauxSystem\Rule as Rule;
use \GoTableaux\Utilities as Utilities;

/**
 * Represents a tableaux proof system.
 * @package GoTableaux
 */
abstract class TableauxSystem extends \GoTableaux\ProofSystem
{
	/**
	 * Defines the rule class names for the logic.
	 * @var array
	 */
	public $ruleClasses = array();
	
	/**
	 * Holds the tableau rules.
	 * @var array.
	 */
	private $_rules = array();
	
	/**
	 * Constructor.
	 *
	 * Adds tickMarker and closeMarker meta symbols.
	 *
	 * @param Logic logic The logic of the proof system.
	 */
	public function __construct( Logic $logic )
	{
		$this->metaSymbolNames[] = 'tickMarker';
		$this->metaSymbolNames[] = 'closeMarker';
		parent::__construct( $logic );
	}

	/**
	 * Adds tableau rules. Duplicate entries are ignored.
	 *
	 * @param Rule|array $rules The rule(s) to add.
	 * @return TableauxSystem Current Instance.
	 */
	protected function addRules( $rules )
	{
		if ( is_array( $rules )) {
			foreach ( $rules as $rule ) $this->addRules( $rule );
			return $this;
		}
		if ( !$rules instanceof Rule )
			throw new TableauException( 'Rule must be instance of Rule.' );
		Utilities::uniqueAdd( $rules, $this->_rules );
		return $this;
	}
	
	/**
	 * Gets the tableau rules.
	 *
         * @param string $class The class of the rules to get.
	 * @return array Array of {@link Rule}s.
	 * @throws 
	 */
	public function getRules( $filterClass = '' )
	{
		if ( empty( $this->_rules )) {
			foreach ( $this->ruleClasses as $relClass ) {
				// compatibility
				$relClass = str_replace( '/', '.', $relClass );
				if ( strpos( $relClass, '.' )) {
					list( $otherLogicName, $relClass ) = explode( '.', $relClass );
					$otherLogic = Logic::getInstance( $otherLogicName );
					$class = get_class( $otherLogic->getProofSystem() );
				} else {
					$class = get_class( $this );
				}
				$ruleNamespace = $class . '\Rule';
				$ruleClass = $ruleNamespace . '\\' . $relClass;
				$this->addRules( new $ruleClass );
			}
			if ( empty( $this->_rules )) Utilities::debug( '[NOTICE] Empty ruleset for ' . get_class( $this ));
		}
		if ( empty( $filterClass )) return $this->_rules;
        if ( $filterClass{0} !== '\\' ) $filterClass = __CLASS__ . '\Rule\\' . $filterClass;
        return array_filter( $this->_rules, function( $rule ) use( $filterClass ) {
            return $rule instanceof $filterClass;
        });
	}

	/**
	 * Determines whether at least one rule can apply to a tableau.
	 *
	 * @param Tableau $tableau The tableau to check.
	 * @return boolean Whether at least one rule can apply.
	 */
	public function ruleCanApply( Tableau $tableau )
	{
		foreach ( $this->getRules() as $rule )
			if ( $rule->applies( $tableau )) return true;
		return false;
	}
	
    /**
     * Constructs a proof for an argument.
     * 
     * @param Argument $argument The argument.
     * @return Tableau The tableau proof.
     */
	public function constructProofForArgument( Argument $argument )
	{
		$tableau = new Tableau( $argument, $this );
		$this->buildTrunk( $tableau, $argument, $this->getLogic() );
		$ruleHasApplied = false;
		while ( !$tableau->isClosed() && $this->ruleCanApply( $tableau )) {
			foreach ( $this->getRules() as $rule )
				if ( $rule->applies( $tableau )) {
					$ruleHasApplied = true;
					Utilities::debug( "Applying " . get_class( $rule ) . ' rule.' );
					$rule->apply( $tableau );
					break;
				}
		}
		if ( !$ruleHasApplied ) Utilities::debug( '[NOTICE] No rules applied to the tableau.' );
		return $tableau;
	}
	
	/**
	 * Checks whether a Tableau is a valid proof.
	 *
	 * @param Tableau $tableau The tableau whose validity to check.
	 * @return boolean Whether the tableau is a valid proof.
	 * @throws {@link ProofException} when $proof is of wrong type.
	 */
	public function isValidProof( Proof $tableau )
	{
		return $tableau->isClosed();
	}
	
	/**
	 * Gets a counterexample from a Tableau proof.
	 *
	 * A counterexample for a tableaux system is a model induced from an open
	 * branch. 
	 *
	 * @param Proof $tableau The tableau from which to build the counterexample
	 * @return Model The countermodel extracted from the proof.
	 * @throws {@link TableauException} on no open branches or type error.
	 */
	public function getCountermodel( Proof $tableau )
	{
		if ( !$tableau instanceof Tableau )
			throw new TableauException( "Proof must be a Tableau." );
		if ( !$openBranch = array_rand( $tableau->getOpenBranches() ))
			throw new TableauException( "No open branches found on tableau." );
		return $this->induceModel( $openBranch );
	}
	
	/**
	 * Induces a model from an open branch.
	 *
	 * @param Branch $branch The open branch from which to induce a model
	 * @return Model The induced model.
	 */
	abstract public function induceModel( Branch $branch );
	
	/**
	 * Constructs the initial list (trunk) for an argument.
	 *
	 * @param Tableau $tableau The tableau to attach the 
	 * @param Argument $argument The argument for which to build the trunk.
	 * @param Logic $logic The logic of the proof system.
	 * @return void
	 */
	abstract public function buildTrunk( Tableau $tableau, Argument $argument, Logic $logic );
}