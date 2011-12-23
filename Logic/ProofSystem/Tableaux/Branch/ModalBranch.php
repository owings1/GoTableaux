<?php
/**
 * Defines the ModalBranch class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link TableauException} class.
 */
require_once '../TableauException.php';

/**
 * Loads the {@link Branch} parent class.
 */
require_once '../Branch.php';

/**
 * Represents a modal logic tableau branch.
 * @package Tableaux
 * @author Douglas Owings
 */
class ModalBranch extends Branch
{
	/**
	 * Gets all world indexes that appear in an array of modal sentence nodes.
	 *
	 * @param array $modalSentenceNodes An array of {@link ModalSentenceNode}s 
	 *									to search.
	 * @return array Array of unique integer indexes that occur on the nodes.
	 * @throws {@link TableauException} on type error.
	 */
	public static function getIsFromSentenceNodes( array $modalSentenceNodes )
	{
		$is = array();
		foreach ( $modalSentenceNodes as $node ){
			if ( !$node instanceof ModalSentenceNode )
				throw new TableauException( 'Node must be instance of ModalSentenceNode.' );
			$is[] = $node->getI();
		}
		return array_unique( $is );
	}
	
	/**
	 * Gets all world indexes that appear in an array of modal access nodes.
	 *
	 * @param array $modalAccessNodes An array of {@link ModalAccesNode}s to search.
	 * @return array Array of unique integer indexes that occur on the nodes.
	 * @throws {@link TableauException} on type error.
	 */
	public static function getIsAndJsFromAccessNodes( array $modalAccessNodes )
	{
		$is = array();
		foreach ( $modalAccessNodes as $node ){
			if ( !$node instanceof ModalAccessNode )
				throw new TableauException( 'Node must be instance of ModalAccessNode.' );
			$is[] = $node->getI();
			$is[] = $node->getJ();
		}
		return array_unique( $is );
	}
	
	/**
	 * Gets all modal sentence nodes in an array of nodes, whose sentence's
	 * operator has a particular name.
	 *
	 * @param array $searchNodes An array of {@link Node}s to search.
	 * @param string $operatorName The name of the operator to search for.
	 * @return array Array of {@link ModalSentenceNode}s.
	 */
	public static function getNodesByOperatorName( array $searchNodes, $operatorName )
	{
		$nodes = array();
		foreach ( $searchNodes as $node )
			if ( 
				$node instanceof ModalSentenceNode &&
				$node->getSentence() instanceof MolecularSentence &&
				$node->getSentence()->getOperator()->getName() === $operatorName	
			)
				$nodes[] = $node;
		return $nodes;
	}
	
	/**
	 * Gets all modal access nodes on the branch.
	 *
	 * @return array Array of {@link ModalAccessNode}s.
	 */
	public function getAccessNodes()
	{
		$nodes = array();
		foreach ( $this->getNodes() as $node )
			if ( $node instanceof ModalAccessNode ) $nodes[] = $node;
		return $nodes;
	}
	
	/**
	 * Gets all modal access nodes that have a given first index.
	 *
	 * @param integer $i The value of the first index of the access nodes to get.
	 * @return array Array of {@link ModalAccessNode}s.
	 */
	public function getAccessNodesByI( $i )
	{
		$nodes = array();
		foreach ( $this->getAccessNodes() as $node )
			if ( $node->getI() == $i ) $nodes[] = $node;
		return $nodes;
	}
	
	/**
	 * Gets all modal access nodes that have a given second index.
	 *
	 * @param integer $j The value of the second index of the access nodes to get.
	 * @return array Array of {@link ModalAccessNode}s.
	 */
	public function getAccessNodesByJ( $j )
	{
		$nodes = array();
		foreach ( $this->getAccessNodes() as $node )
			if ( $node->getJ() == $j ) $nodes[] = $node;
		return $nodes;
	}
	
	/**
	 * Gets all modal access nodes that have the given indexes.
	 *
	 * @param integer $i The value of the first index.
	 * @param integer $j The value of the second index.
	 * @return array Array of {@link ModalAccessNode}s.
	 */
	public function getAccessNodesByIJ( $i, $j )
	{
		$nodes = array();
		foreach ( $this->getAccessNodes() as $node ){
			if ( $node->getI() === $i && $node->getJ() === $j ) $nodes[] = $node;
		return $nodes;
	}
	
	/**
	 * Gets all world indexes that appear on the branch.
	 *
	 * @return array Array of unique integer indexes that appear on the branch.
	 */
	public function getIsAndJsOnBranch()
	{
		$sentenceIs = self::getIsFromSentenceNodes( $this->getSentenceNodes() );
		$accessIJs = self::getIsAndJsFromAccessNodes( $this->getAccessNodes() );
		$allIJs = array_merge( $sentenceIs, $accessIJs );
		return array_unique( $allIJs );
	}
	
	/**
	 * Gets all the second indexes of access nodes by a particular first index.
	 *
	 * @param integer $i The first index to search for.
	 * @return array Array of unique second index integers.
	 */
	public function getJsByI( $i )
	{
		$js = array();
		foreach ( $this->getAccessNodesByI( $i ) as $node )
			$js[] = $node->getJ();
		return array_unique( $js );
	}
	
	/**
	 * Get an array representation of the world index relation on the branch.
	 *
	 * @return array The value of each key $i is the array of all $j such that
	 *				 $i "sees" $j on the branch.
	 */
	public function getAccessArray()
	{
		$r = array();
		foreach ( $this->getIsAndJsOnBranch() as $i )
			$r[$i] = $this->getJsByI( $i );
		return $r;
	}
	
	/**
	 * Get all modal access nodes whose first and second indexes are equal.
	 *
	 * @return array Array of {@link ModalAccessNodes}s.
	 */
	public function getReflexiveNodes()
	{
		$nodes = array();
		foreach ( $this->getAccessNodes() as $node )
			if ( $node->getI() == $node->getJ() ) $nodes[] = $node;
		return $nodes;
	}
	
	/**
	 * Gets all modal sentence nodes on the branch.
	 *
	 * @param boolean $untickedOnly Whether to limit search to nodes that are unticked.
	 * @return array Array of {@link ModalSentenceNode}s.
	 */
	public function getSentenceNodes( $untickedOnly = false )
	{
		$nodes = array();
		$baseNodes = ( $untickedOnly ) ? $this->getUntickedNodes() : $this->getNodes();
		foreach ( $baseNodes as $node )
			if ( $node instanceof ModalSentenceNode ) $nodes[] = $node;
		return $nodes;
	}
}