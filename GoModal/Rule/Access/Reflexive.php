<?php

class GoModal_Rule_Access_Reflexive extends Tableaux_Rule
{
	public function apply( Tableaux_Branch $branch )
	{
		if ( ! $branch instanceof GoModal_Branch ){
			throw new Exception( 'branch must be a GoModal instance' );
		}
		
		/* 		Get i's That Should Have Reflexive Nodes on Branch 	*/
		$should = $branch->getIsAndJsOnBranch();
		
		/*		Get i's That Already Have Reflexive Nodes on Branch		*/
		$are = GoModal_Branch::GetIsAndJsFromAccessNodes( $branch->getReflexiveNodes() );
		
		/*		Subtract are From should			*/
		$newIs = array_diff( $should, $are );
		
		/*		Return false if empty				*/
		if ( empty( $newIs )){
			return false;
		}
		
		/*		Sort								*/
		sort( $newIs );
		
		/*		Add Node to Existing Branch		*/
		$branch->addNode( new GoModal_Node_Access( $newIs[0], $newIs[0] ) );

		return array( 0 => $branch );
	}
}
?>