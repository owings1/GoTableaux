<?php
/**
 * Defines the FDE logic class.
 * @package FDE
 * @author Douglas Owings
 */

/**
 * Loads the {@link Logic} parent class.
 */
require_once 'GoTableaux/Logic/Logic.php';

/**
 * Loads the {@link FDETableaux} proof system class.
 */
require_once 'FDETableaux.php';

/**
 * Represents First Degree Entailment Logic.
 * @package FDE
 * @author Douglas Owings
 */
class FDE extends Logic
{
	public $proofSystemClass = 'FDETableaux';
	
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