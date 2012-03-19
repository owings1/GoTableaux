<?php
/**
 * Defines the Polish notation latex sentence writer decorator class.
 * @package GoTableaux
 * @author Douglas Owings
 */

namespace GoTableaux\SentenceWriter\Polish;

/**
 * Sets default operator translations for Polish notation.
 * @package GoTableaux
 * @author Douglas Owings
 */
class LaTeXDecorator extends \GoTableaux\SentenceWriter\Standard\LaTeXDecorator
{
	protected $sentenceWriter;
	
	protected $standardOperatorTranslations = array(
		'Conjunction' => '\mathsf{K}',
		'Disjunction' => '\mathsf{A}',
		'Negation'	  => '\mathsf{N}',
		'Material Conditional' 		=> '\mathsf{C}',
		'Material Biconditional' 	=> '\mathsf{E}',
	);
}