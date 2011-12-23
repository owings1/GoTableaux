<?php
/**
 * Defines the GoModalTableauxSystem class.
 * @package GoModal
 * @author Douglas Owings
 */

/**
 * Loads the {@link TableauxSystem} parent class.
 */
require_once '../../Logic/ProofSystem/TableauxSystem.php';

/**
 * Loads the {@link ManyValuedModalSentenceNode} node class.
 */
require_once '../../Logic/ProofSystem/Tableaux/Node/ManyValuedModalSentenceNode.php';

/**
 * Loads the {@link AccessNode} node class.
 */
require_once '../../Logic/ProofSystem/Tableaux/Node/AccessNode.php';

/**
 * Represents the GoModal tableaux system.
 *
 * @package GoModal
 * @author Douglas Owings
 */
class GoModalTableauxSystem extends TableauxSystem
{
	/**
	 * Initializes the tableaux rules.
	 */
	public function __construct()
	{
		
	}
	
	public function buildTrunk( Tableau $tableau, Argument $argument )
	{
		$premises 	= $argument->getPremises();
		$conclusion = $argument->getConclusion();
				
		$nodes = array();
		
		foreach ( $premises as $premise )
			$nodes[] = new ManyValuedModalSentenceNode( $premise, 0, true ));
		
		if ( !empty( $conclusion ))
			$nodes[] = new ManyValuedModalSentenceNode( $conclusion, 0, false ));
		
		$tableau->createBranch( $nodes );
	}
	/**
	 * Induces a model from an open branch.
	 *
	 * @param Branch $branch The open branch.
	 * @return Model The induced model.
	 */
	public function induceModel( Branch $branch )
	{
		return new Model;
		/* 
		$ws = array();
		$R = array();
		$v = array();
		foreach ( $branch->getNodes() as $node ){
			if ( $node instanceof GoModal_Node_Access ){
				$ws[] = $node->getJ();
				$R[] = array( 0 => $node->getI(), $node->getJ() );
			}
			elseif ( $node->getSentence() instanceof AtomicSentence ){
				if ( $node->isDesignated() ){
					$newV = array( 0 => $node->getI(), $node->getSentence(), 1 );
				}
				else{
					// get vocabulary
					$vocabulary = GoModal::getVocabulary();
					
					// get negation operator
					$negation = $vocabulary->getOperatorByName( 'NEGATION' );
					
					// create new sentence
					$newSentence = new MolecularSentence();
					
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
		*/
	}
}