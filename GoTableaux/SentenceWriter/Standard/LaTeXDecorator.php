<?php
/**
 * Defines the LaTeX sentence writer decorator class.
 * @package GoTableaux
 * @author Douglas Owings
 */

namespace GoTableaux\SentenceWriter\Standard;

use \GoTableaux\Vocabulary as Vocabulary;
use \GoTableaux\Sentence as Sentence;
use \GoTableaux\Utilities as Utilities;
use \GoTableaux\Sentence\Atomic as AtomicSentence;
use \GoTableaux\Sentence\Molecular as MolecularSentence;

/**
 * Decorates a sentence writer for LaTeX.
 * @package GoTableaux
 * @author Douglas Owings
 */
class LaTeXDecorator extends \GoTableaux\SentenceWriter\Standard
{
	protected $sentenceWriter;
	
	protected $standardOperatorTranslations = array(
		'Conjunction' => '\wedge',
		'Disjunction' => '\vee',
		'Negation'	  => '\neg',
		'Material Conditional' 		=> '\supset',
		'Material Biconditional' 	=> '\equiv',	
	);
	
	//protected $specialCharacters = array( '\\', '#', '$', '%', '&', '~', '_', '^', '{', '}' );
	
	public function writeSubscript( $subscript )
	{
		return '_{' . $subscript .'}';
	}
	
	/*
	public function escape( $str )
	{
		foreach ( $this->specialCharacters as $char )
			$str = str_replace( $char, '\\' . $char, $str );
		return $str;
	}
	*/
}