<?php

class GoModal_InitialRule implements Tableaux_InitialRule
{
	public function apply( Argument $argument )
	{
		$premises 	= $argument->getPremises();
		$conclusion = $argument->getConclusion();
		
		if ( empty( $premises ) && empty( $conclusion ))
			throw new Tableaux_TableauException( 'Premises and conclusion cannot both be empty.' );
		
		$branch = new GoModal_Branch();
		
		foreach ( $premises as $premise )
			$branch->addNode( new GoModal_Node_Sentence( $premise, 0, true ) );
		
		if ( !empty( $conclusion ))
			$branch->addNode( new GoModal_Node_Sentence( $conclusion, 0, false ) );
		
		return array( $branch );
	}
}