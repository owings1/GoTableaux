<?php
/**
 * Defines the CPL class.
 * @package CPL
 * @author Douglas Owings
 */

/**
 * Loads the {@link Logic} parent class.
 */
require_once 'GoTableaux/Logic/Logic.php';

/**
 * Loads the {@link CPLTableauxSystem} proof system class.
 */
require_once 'CPLTableauxSystem.php';

/**
 * Represents Classical Propositional Logic.
 * @package CPL
 * @author Douglas Owings
 */
class CPL extends Logic
{
	public $proofSystemClass = 'CPLTableauxSystem';
	
	public $lexicon = array(
		'openMarks' 		=> array('('),
		'closeMarks' 		=> array(')'),
		'separators' 		=> array(' '),
		'subscripts' 		=> array('_'),
		'atomicSymbols' 	=> array('A', 'B', 'C', 'D', 'E', 'F'),
		'operatorSymbols' 	=> array(
			'~' => array( 'Negation' => 1 ),
			'&' => array( 'Conjunction' => 2 ),
			'V' => array( 'Disjunction' => 2 ),
			'>' => array( 'Material Conditional' => 2 ),
			'<' => array( 'Material Biconditional' => 2 ),
		)
	);
}