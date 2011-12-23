<?php

class GoModal_Rule_Access_Reflexive implements Rule
{
	public function apply( Branch $branch )
	{
		if ( ! $branch instanceof GoModalBranch )
			throw new RuleException( 'branch must be a GoModalBranch instance' );

		/* 		Get i's That Should Have Reflexive Nodes on Branch 	*/
		$should = $branch->getIsAndJsOnBranch();
		
		/*		Get i's That Already Have Reflexive Nodes on Branch		*/
		$are = GoModalBranch::GetIsAndJsFromAccessNodes( $branch->getReflexiveNodes() );
		
		/*		Subtract are From should			*/
		$newIs = array_diff( $should, $are );
		
		/*		Return false if empty				*/
		if ( empty( $newIs )) return false;
	
		/*		Sort								*/
		sort( $newIs );
		
		/*		Add Node to Existing Branch		*/
		$branch->addNode( new GoModal_Node_Access( $newIs[0], $newIs[0] ) );

		return array( $branch );
	}
}
?>