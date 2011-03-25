<?php

class GoModal_InitialRule extends Tableaux_InitialRule
{
	function apply( Argument $argument )
	{
		$premises = $argument->getPremises();
		$conclusion = $argument->getConclusion();
		
		if ( empty( $premises ) && empty( $conclusion )){
			return false;
		}
		$branch = new GoModal_Branch();
		foreach ( $premises as $premise ){
			$branch->addNode( new GoModal_Node_Sentence( $premise, 0, true ) );
		}
		if ( ! empty( $conclusion )){
			$branch->addNode( new GoModal_Node_Sentence( $conclusion, 0, false ) );
		}
		return array( 0 => $branch );
	}
}

?>