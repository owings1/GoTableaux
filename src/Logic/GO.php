<?php
/**
 * Defines the GO class.
 * @package GO
 * @author Douglas Owings
 */

namespace GoTableaux\Logic;

/**
 * Represents GO 3-valued Propositional Logic.
 * @package GO
 * @author Douglas Owings
 */
class GO extends \GoTableaux\Logic
{	
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