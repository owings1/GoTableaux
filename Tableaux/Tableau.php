<?php
require_once 'Doug/SimpleNotifier.php';

require_once 'Branch.php';
require_once 'InitialRule.php';
require_once 'ClosureRule.php';
require_once 'Node.php';
require_once 'Rule.php';
require_once 'Structure.php';
require_once 'Writer.php';

class Tableaux_Tableau
{
	protected 	$argument,
				$branches = array(),
				$initialRule,
				$closureRule,
				$rules = array();
	
	/*	Tree Structure	*/
	protected 	$structure;
	
	/*	Notifier		*/
	protected 	$n;
	
	public function __construct( Argument $argument )
	{
		$this->argument = $argument;
		$this->n = new Doug_SimpleNotifier( 'Tableau' );
		$this->n->notify();
	}
	function getArgument()
	{
		return $this->argument;
	}
	function setInitialRule( Tableaux_InitialRule $rule )
	{
		$this->n->notify( 'setting initial rule' );
		$this->initialRule = $rule;
	}
	function setClosureRule( Tableaux_ClosureRule $rule )
	{
		$this->n->notify( 'setting closure rule' );
		$this->closureRule = $rule;
	}
	function addRule( Tableaux_Rule $rule )
	{
		$this->n->notify( 'adding rule ' . get_class( $rule ) );
		$this->rules[] = $rule;
	}
	public function attach( $branch )
	{
		if ( empty( $branch )){
			return;
		}
		if ( is_array( $branch )){
			foreach ( $branch as $b ){
				$this->attach( $b );
			}
		}
		else{
			if ( ! $branch instanceof Tableaux_Branch ){
				throw new Exception( 'branch must be instance of Tableaux_Branch. ' . get_class( $branch ) . ' passed.' );
			}
			
			//$this->n->notify( 'attaching branch with ' . count( $branch->getNodes() ) .' nodes' );
			
			$this->branches[] = $branch;
		}
	}
	
	public function detach( $branch )
	{
		if ( empty( $branch )){
			return;
		}
		if ( is_array( $branch )){
			foreach ( $branch as $b ){
				$this->detach( $b );
			}
		}
		else{
			if ( ! $branch instanceof Tableaux_Branch ){
				throw new Exception( 'branch must be instance of Tableaux_Branch. ' . get_class( $branch ) . ' passed.' );
			}
			$branches = array();
			
			//$this->n->notify( 'detaching branch' );
			
			foreach ( $this->branches as $b ){
				if ( $branch !== $b ){
					$branches[] = $b;
				}
			}
			
			$this->branches = $branches;
		}
	}
	public function getOpenBranches()
	{
		$branches = array();
		foreach ( $this->branches as $branch ){
			if ( ! $branch->isClosed() ){
				$branches[] = $branch;
			}
		}
		return $branches;
	}
	public function getBranches()
	{
		return $this->branches;
	}
	public function getStructure()
	{
		return $this->structure;
	}
	function build()
	{
		/*		Construct Initial List			*/
		$this->constructInitialList();
		
		/*		Ensure Non-Empty Rule Set		*/
		if ( empty( $this->rules )){
			throw new Exception( 'no rules in rule set' );
		}
		
		/*		Start Pointer to First Rule				*/
		$rulePointer = 0;
		do{
			/*		Get Rule to Apply					*/
			$rule = $this->rules[$rulePointer];
			
			/*		Assume the Rule Will Not Apply		*/
			$continue = false;
			
			/*		Get All Open Branches				*/
			foreach ( $this->getOpenBranches() as $branch ){
				
				//$this->n->notify( 'applying closure rule ' . get_class( $this->closureRule ) );
				
				/*		Apply Closure Rule First			*/
				$c = $this->closureRule->apply( $branch );
				
				//$this->n->notify( 'closure rule ' . ( $c ? 'applied' : 'did not apply' ) );
				
				//$this->n->notify( 'applying rule ' . get_class( $rule ) );

				/*		Try to Apply Rule To Branch				*/
				if ( $this->applyOnceAndExtend( $rule, $branch )){

					/* 		The Rule Applied, new Branches added		*/
					$this->n->notify( 'rule ' . get_class( $rule ) . ' applied. now there are ' . count( $this->branches ) . ' branches.' );
					
					/*		Start Back at First Rule					*/
					$this->n->notify( 'starting back at beginning of rules ' );
					$rulePointer = 0;
					$continue = true;
				}
			}
			
			/* 		If the Rule Did Not Apply To Any Open Branch ...	*/
			if ( ! $continue ){
				
				//$this->n->notify( 'rule did not apply. there are still ' . count( $this->branches ) . ' branches.' );
				
				/*		Set Pointer to Next Rule						*/
				$rulePointer++;
			}
			
			/*		If there is such a Next Rule ...				*/
			if ( $rulePointer < count( $this->rules )){
				
				/*		Continue	*/
				$continue = true;
			}
		} while ($continue);
		
		/* 		Apply Closure Rule to All Open Branches		*/
		foreach ( $this->getOpenBranches() as $branch ){
			$this->closureRule->apply( $branch );
		}
		
		/*		Build Structure								*/
		$tableauCopy = clone $this;
		$tableauCopy->detach( $tableauCopy->getBranches() );
		foreach ( $this->branches as $branch ){
			$tableauCopy->attach( $branch->copy() );
		}
		$this->structure = Tableaux_Structure::getInstance( $tableauCopy );
		$this->structure->build();
		
	}
	function isValid( &$openBranch = null )
	{
		$openBranches = $this->getOpenBranches();
		if ( empty( $openBranches )){
			$openBranch = null;
			return true;
		}
		$openBranch = $openBranches[0];
		return false;
	}
	protected function applyOnceAndExtend( Tableaux_Rule $rule, Tableaux_Branch $branch )
	{
		if ( $branch->isClosed() ){
			return false;
		}
		if ( ! $branches = $rule->apply( $branch )){
			return false;
		}
		$this->detach( $branch );
		$this->attach( $branches );
		return $branches;
	}
	protected function constructInitialList()
	{
		$this->n->notify( 'constructing initial list' );
		
		if ( empty( $this->initialRule )){
			throw new Exception( 'no initial rule set' );
		}
		if ( ! $branches = $this->initialRule->apply( $this->argument )){
			throw new Exception( 'initial rule returned empty set' );
		}
		$this->attach( $branches );
	}

	
}
?>