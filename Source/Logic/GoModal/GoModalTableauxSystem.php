<?php
/**
 * Defines the GoModalTableauxSystem class.
 * @package GoModal
 */

/**
 * Loads the {@link ManyValuedModalTableauxSystem} parent class.
 */
require_once 'GoTableaux/Logic/ProofSystem/Tableaux/ManyValuedModalTableauxSystem.php';

/**
 * Loads the {@link GoModalClosureRule} closure rule class.
 */
require_once 'tableaux_rules.php';

/**
 * Loads the {@link ModalReflexiveRule} branch rule class.
 */
require_once 'GoTableaux/Logic/ProofSystem/Tableaux/BranchRule/ModalReflexiveRule.php';

/**
 * Loads the {@link ModalTransitiveRule} branch rule class.
 */
require_once 'GoTableaux/Logic/ProofSystem/Tableaux/BranchRule/ModalTransitiveRule.php';

/**
 * Represents the GoModal tableaux system.
 *
 * @package GoModal
 */
class GoModalTableauxSystem extends ManyValuedModalTableauxSystem
{
	public $closureRuleClass = 'GoModalClosureRule';
	
	public $branchRuleClasses = array(
		'ModalReflexiveRule',
		'ModalTransitiveRule',
		'GoModalBranchRule_ArrowDes'
	);
	
	
	
	/**
	 * Constructor.
	 *
	 * Loads the branch rule classes from the file system.
	 */
	public function __construct()
	{
		foreach ( $this->branchRuleClasses as $class ) if ( !class_exists( $class ))
			require_once 'BranchRules/' . str_replace( 'GoModalBranchRule_', '', $class ) . '.php';
		parent::__construct();
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

/*
require_once 'Rule/Sentence/NegNegDes.php';
require_once 'Rule/Sentence/NegNegUndes.php';

require_once 'Rule/Sentence/ConjunctionDes.php';
require_once 'Rule/Sentence/ConjunctionUndes.php';
require_once 'Rule/Sentence/NegConjunctionDes.php';
require_once 'Rule/Sentence/NegConjunctionUndes.php';

require_once 'Rule/Sentence/DisjunctionDes.php';
require_once 'Rule/Sentence/DisjunctionUndes.php';
require_once 'Rule/Sentence/NegDisjunctionDes.php';
require_once 'Rule/Sentence/NegDisjunctionUndes.php';

require_once 'Rule/Sentence/MatCondDes.php';
require_once 'Rule/Sentence/MatCondUndes.php';
require_once 'Rule/Sentence/NegMatCondDes.php';
require_once 'Rule/Sentence/NegMatCondUndes.php';

require_once 'Rule/Sentence/MatBicondDes.php';
require_once 'Rule/Sentence/MatBicondUndes.php';
require_once 'Rule/Sentence/NegMatBicondDes.php';
require_once 'Rule/Sentence/NegMatBicondUndes.php';

require_once 'Rule/Sentence/ArrowDes.php';
require_once 'Rule/Sentence/ArrowUndes.php';
require_once 'Rule/Sentence/NegArrowDes.php';
require_once 'Rule/Sentence/NegArrowUndes.php';

require_once 'Rule/Sentence/BiarrowDes.php';
require_once 'Rule/Sentence/BiarrowUndes.php';
require_once 'Rule/Sentence/NegBiarrowDes.php';
require_once 'Rule/Sentence/NegBiarrowUndes.php';

require_once 'Rule/Sentence/BoxDes.php';
require_once 'Rule/Sentence/BoxUndes.php';
require_once 'Rule/Sentence/NegBoxDes.php';
require_once 'Rule/Sentence/NegBoxUndes.php';

require_once 'Rule/Sentence/DiamondDes.php';
require_once 'Rule/Sentence/DiamondUndes.php';
require_once 'Rule/Sentence/NegDiamondDes.php';
require_once 'Rule/Sentence/NegDiamondUndes.php';
*/

/*
$t = new Tableau( $argument );
$t->setInitialRule( new GoModal_InitialRule );
$t->setClosureRule( new GoModal_ClosureRule );

// non-branching rules

$t->addRule( new GoModal_Rule_Access_Reflexive );
$t->addRule( new GoModal_Rule_Access_Transitive );

$t->addRule( new GoModal_Rule_Sentence_NegNegDes );
$t->addRule( new GoModal_Rule_Sentence_NegNegUndes );

$t->addRule( new GoModal_Rule_Sentence_ConjunctionDes );
$t->addRule( new GoModal_Rule_Sentence_ConjunctionUndes );
$t->addRule( new GoModal_Rule_Sentence_NegConjunctionUndes );

$t->addRule( new GoModal_Rule_Sentence_DisjunctionUndes );
$t->addRule( new GoModal_Rule_Sentence_NegDisjunctionDes );
$t->addRule( new GoModal_Rule_Sentence_NegDisjunctionUndes );

$t->addRule( new GoModal_Rule_Sentence_MatCondUndes );
$t->addRule( new GoModal_Rule_Sentence_NegMatCondDes );
$t->addRule( new GoModal_Rule_Sentence_NegMatCondUndes );

$t->addRule( new GoModal_Rule_Sentence_NegMatBicondDes );
$t->addRule( new GoModal_Rule_Sentence_MatBicondDes );

$t->addRule( new GoModal_Rule_Sentence_ArrowUndes );
$t->addRule( new GoModal_Rule_Sentence_NegArrowUndes );

$t->addRule( new GoModal_Rule_Sentence_BiarrowUndes );
$t->addRule( new GoModal_Rule_Sentence_NegBiarrowUndes );

$t->addRule( new GoModal_Rule_Sentence_BoxDes );
$t->addRule( new GoModal_Rule_Sentence_BoxUndes );
$t->addRule( new GoModal_Rule_Sentence_NegBoxDes );
$t->addRule( new GoModal_Rule_Sentence_NegBoxUndes );

$t->addRule( new GoModal_Rule_Sentence_DiamondDes );
$t->addRule( new GoModal_Rule_Sentence_DiamondUndes );
$t->addRule( new GoModal_Rule_Sentence_NegDiamondDes );
$t->addRule( new GoModal_Rule_Sentence_NegDiamondUndes );

// branching rules

$t->addRule( new GoModal_Rule_Sentence_DisjunctionDes );

$t->addRule( new GoModal_Rule_Sentence_NegConjunctionDes );

$t->addRule( new GoModal_Rule_Sentence_MatCondDes );

$t->addRule( new GoModal_Rule_Sentence_NegMatBicondUndes );
$t->addRule( new GoModal_Rule_Sentence_MatBicondUndes );

$t->addRule( new GoModal_Rule_Sentence_ArrowDes );
$t->addRule( new GoModal_Rule_Sentence_NegArrowDes );

$t->addRule( new GoModal_Rule_Sentence_BiarrowDes );
$t->addRule( new GoModal_Rule_Sentence_NegBiarrowDes );

return $t;
*/