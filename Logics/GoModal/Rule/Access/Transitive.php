<?php

class GoModal_Rule_Access_Transitive implements Rule
{
	public function apply( Branch $branch )
	{
		if ( ! $branch instanceof GoModalBranch )
			throw new RuleException( 'Branch must be a GoModalBranch instance' );
		
		
		// get array
		$r = $branch->getAccessArray();
		
		$should = array();
		// for each world i
		foreach ( $r as $i => $js )
			// for each world j, such that iRj
			foreach ( $js as $j )
				// i should access each world k, such that jRk
				foreach ( $r[$j] as $k )
					$should[$i] = $k;
		
		if ( empty( $should )) return false;
		
		$newR = array();
		
		// for each world j that i should access
		foreach ( $should as $i => $j )
			// if j is not in the set of worlds accessed by i
			if ( ! in_array( $j, $r[$i] ))
				// then the pair <i,j> is in newR
				$newR[] = array( $i, $j );

		
		if ( empty( $newR )) return false;
		
		// apply once
		$a = $newR[0];

		/*		Add Node to Existing Branch		*/
		$branch->addNode( new GoModal_Node_Access( $a[0], $a[1] ) );

		return true;
	}
}
?>