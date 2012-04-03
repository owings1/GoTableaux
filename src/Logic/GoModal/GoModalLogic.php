<?php
/**
 * Defines the GoModal logic class.
 * @package GoModal
 */

/**
 * Loads the {@link Logic} base class.
 */
require_once 'GoTableaux/Logic/Logic.php';

/**
 * Loads the {@link GoModalTableauxSystem} proof system class.
 */
require_once 'GoModalTableauxSystem.php';

/**
 * Represents the GoModal language.
 * @package GoModal
 */
class GoModalLogic extends Logic
{
	public $proofSystemClass = 'GoModalTableauxSystem';
	
	public $lexicon = array(
		'openMarks' 		=> array('('),
		'closeMarks' 		=> array(')'),
		'separators' 		=> array(' '),
		'subscripts' 		=> array('_'),
		'atomicSymbols' 	=> array('A', 'B', 'C', 'D', 'E', 'F'),
		'operatorsSymbols' 	=> array(
			'~' => array( 'Negation' => 1 ),
			'&' => array( 'Conjunction' => 2 ),
			'V' => array( 'Disjunction' => 2 ),
			'>' => array( 'Material Conditional' => 2 ),
			'<' => array( 'Material Biconditional' => 2 ),
			'-' => array( 'Conditional' => 2 ),
			'%' => array( 'Biconditional' => 2 ),
			'N' => array( 'Possibility' => 2 ),
			'P' => array( 'Necessity' => 2 )
		)
	);
}

/*
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
*/