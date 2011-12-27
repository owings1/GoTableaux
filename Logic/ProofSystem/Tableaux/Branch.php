<?php
/**
 * Defines the Branch class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Represents a tableau branch.
 * @package Tableaux
 * @author Douglas Owings
 */
class Branch
{
	/**
	 * Holds the nodes of the branch.
	 * @var array Array of {@link Node} objects.
	 * @access private
	 */
	protected $nodes = array();
	
	/**
	 * Tracks whether the branch is closed.
	 * @var boolean
	 * @access private
	 */
	protected $closed = false;
	
	/**
	 * Holds a reference to the tableau.
	 * @var Tableau
	 * @access private
	 */
	protected $tableau;
	
	/**
	 * Constructor.
	 *
	 * Initializes the tableau.
	 *
	 * @param Tableau $tableau The tableau of the branch.
	 */
	public function __construct( Tableau $tableau )
	{
		$this->tableau = $tableau;
	}
	
	/**
	 * Gets the tableau.
	 *
	 * @return Tableau The tableau.
	 */
	public function getTableau()
	{
		return $this->tableau;
	}
	
	/**
	 * Gets the tableaux system of the tableau.
	 *
	 * @return TableauxSystem The tableaux system.
	 */
	public function getTableauxSystem()
	{
		return $this->getTableau()->getTableauxSystem();
	}
	
	/**
	 * Registers a sentence in the logic's vocabulary
	 *
	 * @param Sentence $sentence The sentence to register.
	 * @return Sentence The sentence, or the one in the registry.
	 */
	public function registerSentence( Sentence $sentence )
	{
		return $this->getTableauxSystem()->registerSentence( $sentence );
	}
	
	/**
	 * Gets an operator from the logic's vocabulary by its name.
	 *
	 * @param string $name The name of the operator.
	 * @return Operator The operator.
	 */
	public function getOperator( $name )
	{
		return $this->getTableauxSystem()->getOperator( $name );
	}
	
	/**
	 * Adds a node to the branch.
	 *
	 * @param Node|array $node The node or array of nodes to add.
	 * @return void
	 */
	protected function addNode( $nodes )
	{
		if ( is_array( $nodes )) foreach ( $nodes as $node ) $this->addNode( $node );
		elseif ( !$nodes instanceof Node ) throw new TableauException( 'Node is not instance of Node.' ); 
		else $this->nodes[] = $nodes;
		return $this;
	}
	
	/**
	 * Removes all reference of a node from the branch.
	 *
	 * @param Node $node The node to remove. If the node is on the branch in
	 *					 multiple places, each reference is removed.
	 * @return void
	 */
	protected function removeNode( Node $node )
	{
		$nodes = array();
		foreach ( $this->nodes as $oldNode )
			if ( $node !== $oldNode ) $nodes[] = $oldNode;
		$this->nodes = $nodes;
		return $this;
	}
	
	/**
	 * Gets the nodes on the branch.
	 *
	 * @return array Array of {@link Node nodes}.
	 */
	public function getNodes()
	{
		return $this->nodes;
	}
	
	/**
	 * Gets all nodes on the branch that are unticked relative to the branch.
	 *
	 * @return array Array of {@link Node nodes}.
	 */
	public function getUntickedNodes()
	{
		$nodes = array();
		foreach ( $this->getNodes() as $node )
			if ( ! $node->isTickedAtBranch( $this )) $nodes[] = $node;
		return $nodes;
	}
	
	/**
	 * Gets all nodes on the branch that are ticked relative to the branch.
	 *
	 * @return array Array of Node objects.
	 */
	public function getTickedNodes()
	{
		$nodes = array();
		foreach ( $this->getNodes() as $node )
			if ( $node->isTickedAtBranch( $this )) $nodes[] = $node;
		return $nodes;
	}
	
	/**
	 * Closes the branch.
	 *
	 * @return Branch Current instance.
	 */
	public function close()
	{
		$this->closed = true;
		return $this;
	}
	
	/**
	 * Checks whether the branch is closed.
	 *
	 * @return boolean Whether the branch is closed.
	 */
	public function isClosed()
	{
		return $this->closed;
	}
	
	/**
	 * Checks whether a node is on the branch.
	 *
	 * @param Node $node The node to check.
	 * @return boolean Whether the node is on the branch.
	 */
	public function hasNode( Node $node )
	{
		return in_array( $node, $this->getNodes(), true );
	}
	
	/**
	 * Clones the branch. Maintains references to the nodes.
	 *
	 * @return Branch The new copy.
	 */
	public function copy()
	{
		$newBranch = clone $this;
		foreach ( $this->getTickedNodes() as $node )
			$node->tickAtBranch( $newBranch );
		return $newBranch;
	}
	
	/**
	 * Branches the branch.
	 *
	 * Copies the branch, attaches the copy to the tableau, and returns the new
	 * branch.
	 *
	 * @return Branch The new branch
	 */
	public function branch()
	{
		$newBranch = $this->copy();
		$this->getTableau()->attach( $newBranch );
		return $newBranch;
	}
	
	/**
	 * Gets any {@link SentenceNode}s on the branch that have a given operator
	 * as its sentence's main connective.
	 *
	 * @param string $operatorName The name of the operator.
	 * @param boolean $untickedOnly Whether to include unticked nodes only. 
	 *								Default is false.
	 * @return array Array of {@link SentenceNode}s.
	 */
	public function getNodesByOperatorName( $operatorName, $untickedOnly = false )
	{
		$nodes = array();
		$searchNodes = $untickedOnly ? $this->getUntickedNodes() : $this->getNodes();
		foreach ( $searchNodes as $node ) 
			if ( $node->getSentence()->getOperatorName() === $operatorName ) $nodes[] = $node;
		return $nodes;
	}
	
	/**
	 * Gets any {@link SentenceNode}s by two operator names.
	 *
	 * Returns sentence nodes whose first operator is a given operator, and 
	 * whose first operand is a molecular sentence with the given second
	 * operator.
	 *
	 * @param string $firstOperatorName The name of the first operator.
	 * @param string $secondOperatorName The name of the second operator.
	 * @param boolean $untickedOnly Whether to include unticked nodes only.
	 *								Default is false.
	 */
	public function getNodesByTwoOperatorNames( $firstOperatorName, $secondOperatorName, $untickedOnly = false )
	{
		$nodes = array();
		$searchNodes = $this->getNodesByOperatorName( $firstOperatorName, $untickedOnly );
		foreach ( $searchNodes as $node ) {
			list( $firstOperand ) = $node->getSentence()->getOperands();
			if ( $firstOperand->getOperatorName() === $secondOperatorName ) $nodes[] = $node;
		}
		return $nodes;
	}
	
	/**
	 * Ticks a node relative to the branch.
	 *
	 * @param Node $node The node to tick.
	 * @return Branch Current instance.
	 */
	public function tickNode( Node $node )
	{
		$node->tickAtBranch( $this );
		return $this;
	}
	
	/**
	 * Adds a node to the branch.
	 *
	 * @param Node $node The node to add.
	 * @return void
	 */
	protected function _addNode( Node $node )
	{
		$this->nodes[] = $node;
	}
}