<?php
/**
 * Defines the GoModal logic class.
 * @package GoModalLogic
 * @author Douglas Owings
 */

/**
 * Loads the {@link Logic} base class.
 */
require_once '../../Logic/Logic.php';

require_once '../../Logic/ProofSystem/TableauxSystem.php';

require_once 'Branch.php';

require_once 'Node/Access.php';
require_once 'Node/Sentence.php';

require_once 'Rule/ClosureRule.php';
require_once 'Rule/InitialRule.php';

require_once 'Rule/Access/Reflexive.php';
require_once 'Rule/Access/Transitive.php';

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


class GoModal extends Logic
{
	public $defaultLexicon = array(
		'openMarks' => array('('),
		'closeMarks' => array(')'),
		'separators' => array(' '),
		'subscripts' => array('_'),
		'atomicSymbols' => array('A', 'B', 'C', 'D', 'E', 'F'),
		'operators' => array(
			'&' => array('name' => 'CONJUNCTION', 'arity' => 2),
			'V' => array('name' => 'DISJUNCTION', 'arity' => 2),
			'>' => array('name' => 'MATERIALCONDITIONAL', 'arity' => 2),
			'<' => array('name' => 'MATERIALBICONDITIONAL', 'arity' => 2),
			'-' => array('name' => 'ARROW', 'arity' => 2),
			'%' => array('name' => 'BIARROW', 'arity' => 2),
			'~' => array('name' => 'NEGATION', 'arity' => 1),
			'N' => array('name' => 'NECESSITY', 'arity' => 1),
			'P' => array('name' => 'POSSIBILITY', 'arity' => 1)
		)
	);
	

	protected static function createTableau( Argument $argument )
	{
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
	}
	public static function newTableau( array $premises, $conclusion )
	{
		if ( empty( self::$vocabulary )){
			self::createVocabulary();
		}
		$a = new Argument();
		foreach ( $premises as $premise ){
			$a->addPremise( self::$vocabulary->getSentence( $premise ));
		}
		$a->setConclusion( self::$vocabulary->getSentence( $conclusion ));
		
		return self::createTableau( $a );
		
	}
	public static function getVocabulary()
	{
		return self::$vocabulary;
	}
	public static function getLaTeXTranslations()
	{
		return array(
			'&' => '\\wedge ',
			'~' => '\\neg ',
			'V' => '\\vee ',
			'N' => '\\Box ',
			'P' => '\\Diamond ',
			'>' => '\\supset ',
			'<>' => '\\equiv ',
			'->' => '\\rightarrow ',
			'<->' => '\\leftrightarrow ',

			'+' => '\\varoplus ',
			'-' => '\\varominus ',

			'R' => '\\mathcal{R} '
		);
	}
	
}
?>