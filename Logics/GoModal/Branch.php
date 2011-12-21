<?php

class GoModal_Branch extends Tableaux_Branch
{
	
	public function getAccessNodes( $untickedOnly = false )
	{
		$nodes = array();
		$baseNodes = ( $untickedOnly ) ? $this->getUntickedNodes() : $this->getNodes();
		foreach ( $baseNodes as $node ){
			if ( $node instanceof GoModal_Node_Access ){
				$nodes[] = $node;
			}
		}
		return $nodes;
	}
	public function getAccessNodesByI( $i )
	{
		$nodes = array();
		$baseNodes = $this->getAccessNodes();
		foreach ( $baseNodes as $node ){
			if ( $node->getI() == $i ){
				$nodes[] = $node;
			}
		}
		return $nodes;
	}
	public function getAccessNodesByJ( $j )
	{
		$nodes = array();
		$baseNodes = $this->getAccessNodes();
		foreach ( $baseNodes as $node ){
			if ( $node->getJ() == $j ){
				$nodes[] = $node;
			}
		}
		return $nodes;
	}
	public function getAccessNodesByIJ( $i, $j )
	{
		$nodes = array();
		$baseNodes = $this->getAccessNodes();
		foreach ( $baseNodes as $node ){
			if ( $node->getI() == $i && $node->getJ() == $j ){
				$nodes[] = $node;
			}
		}
		return $nodes;
	}
	public function getAccessArray()
	{
		$r = array();
		foreach ( $this->getIsAndJsOnBranch() as $i ){
			$r[$i] = $this->getJsByI( $i );
		}
		return $r;
	}
	public function getReflexiveNodes()
	{
		$nodes = array();
		foreach ( $this->getAccessNodes() as $node ){
			if ( $node->getI() == $node->getJ() ){
				$nodes[] = $node;
			}
		}
		return $nodes;
	}
	public function getSentenceNodes( $untickedOnly = false )
	{
		$nodes = array();
		$baseNodes = ( $untickedOnly ) ? $this->getUntickedNodes() : $this->getNodes();
		foreach ( $baseNodes as $node ){
			if ( $node instanceof GoModal_Node_Sentence ){
				$nodes[] = $node;
			}
		}
		return $nodes;
	}
	public function hasSentenceNodeWithAttr( Sentence $sentence, $i, $des, $untickedOnly = false )
	{
		$nodes = ( $des ) ? $this->getDesignatedNodes( $untickedOnly ) : $this->getUndesignatedNodes( $untickedOnly );
		foreach ( $nodes as $node ){
			if ( $node->getSentence()->__tostring() === $sentence->__tostring() && $node->getI() == $i ){
				return true;
			}
		}
		return false;
	}
	public function getDesignatedNodes( $untickedOnly = false )
	{
		$nodes = array();
		$sentenceNodes = $this->getSentenceNodes( $untickedOnly );
		foreach ( $sentenceNodes as $node ){
			if ( $node->isDesignated() ){
				$nodes[] = $node;
			}
		}
		return $nodes;
	}
	
	public function getUndesignatedNodes( $untickedOnly = false )
	{
		$nodes = array();
		$sentenceNodes = $this->getSentenceNodes( $untickedOnly );
		foreach ( $sentenceNodes as $node ){
			if ( ! $node->isDesignated() ){
				$nodes[] = $node;
			}
		}
		return $nodes;
	}
	public function getIsAndJsOnBranch()
	{
		$sentenceIs = self::getIsFromSentenceNodes( $this->getSentenceNodes() );
		$accessIJs = self::getIsAndJsFromAccessNodes( $this->getAccessNodes() );
		$allIJs = array_merge( $sentenceIs, $accessIJs );
		return array_unique( $allIJs );
	}
	public function getJsByI( $i )
	{
		$js = array();
		$nodes = $this->getAccessNodesByI( $i );
		foreach ( $nodes as $node ){
			$js[] = $node->getJ();
		}
		return array_unique( $js );
	}
	public static function getIsFromSentenceNodes( array $searchNodes )
	{
		$is = array();
		foreach ( $searchNodes as $node ){
			$is[] = $node->getI();
		}
		return array_unique( $is );
	}
	public static function getIsAndJsFromAccessNodes( array $searchNodes )
	{
		$is = array();
		foreach ( $searchNodes as $node ){
			$is[] = $node->getI();
			$is[] = $node->getJ();
		}
		return array_unique( $is );
	}
	public static function getNodesByOperatorName( array $searchNodes, $name )
	{
		$nodes = array();
		foreach ( $searchNodes as $node ){
			if ( 
				$node instanceof GoModal_Node_Sentence &&
				$node->getSentence() instanceof Sentence_Molecular &&
				$node->getSentence()->getOperator()->getName() == $name	
			){
				$nodes[] = $node;
			}
		}
		return $nodes;
	}
	public static function induceModel( GoModal_Branch $branch )
	{
		$ws = array();
		$R = array();
		$v = array();
		foreach ( $branch->getNodes() as $node ){
			if ( $node instanceof GoModal_Node_Access ){
				$ws[] = $node->getJ();
				$R[] = array( 0 => $node->getI(), $node->getJ() );
			}
			elseif ( $node->getSentence() instanceof Sentence_Atomic ){
				if ( $node->isDesignated() ){
					$newV = array( 0 => $node->getI(), $node->getSentence(), 1 );
				}
				else{
					// get vocabulary
					$vocabulary = GoModal::getVocabulary();
					
					// get negation operator
					$negation = $vocabulary->getOperatorByName( 'NEGATION' );
					
					// create new sentence
					$newSentence = new Sentence_Molecular();
					
					// set operator to negation
					$newSentence->setOperator( $negation );
					
					// set operand to atomic sentence
					$newSentence->addOperand( $node->getSentence() );
					
					// get instance from vocabulary
					$sentence = $vocabulary->oldOrNew( $newSentence );
					
					// check if it is on branch
					if ( $branch->hasSentenceNodeWithAttr( $sentence, $node->getI(), false ) ){
						
						
						$newV = array( 0 => $node->getI(), $node->getSentence(), '.5' );
						
						
					}
					else{
						$newV = array( 0 => $node->getI(), $node->getSentence(), 0 );
					}
					
				}
				// quick fix to avoid duplicates
				if ( ! in_array( $newV, $v )){
					$v[] = $newV;
				}
			}
			$ws[] = $node->getI();
		}
		$W = array_unique( $ws );
		return array( 'W' => $W, 'R' => $R, 'v' => $v );
	}
	
	
	
}
?>