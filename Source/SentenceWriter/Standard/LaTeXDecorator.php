<?php
/**
 * GoTableaux. A multi-logic tableaux generator.
 * Copyright (C) 2012  Douglas Owings
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed WITHOUT ANY WARRANTY. 
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/agpl-3.0.html>.
 */
/**
 * Defines the LaTeX sentence writer decorator class.
 * @package GoTableaux
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
 */
class LaTeXDecorator extends \GoTableaux\SentenceWriter\Standard
{
	protected $sentenceWriter;
	
	// Generated in constructor.
	protected $operatorTranslations = array();
	
	protected $standardOperatorSymbols = array(
		'Conjunction' => '\wedge',
		'Disjunction' => '\vee',
		'Negation'	  => '\neg ',
		'Material Conditional' 	=> '\supset',
		'Material Biconditional' => '\equiv',
		'Conditional' => '\rightarrow',
	);
	
	/**
	 * Constructor.
	 *
	 * @param Vocabulary $vocabulary The vocabulary.
	 */
	protected function __construct( Vocabulary $vocabulary )
	{
		// Write operators as \GT$operatorName
		foreach ( $vocabulary->getOperatorNames() as $operatorName )
			$this->operatorTranslations[$operatorName] = '\GT'. $this->formatCommand( $operatorName );
		parent::__construct( $vocabulary );
	}
	
	public function writeSubscript( $subscript )
	{
		return '_{' . $subscript .'}';
	}
	
	public function writeOperator( $operatorOrName )
	{
		// Add extra space in case it's a unary operator.
		return $this->sentenceWriter->writeOperator( $operatorOrName ) . ' ';
	}
	
	public function getOperatorSymbolCommands()
	{
		return $this->standardOperatorSymbols;
	}
	
	public function formatCommand( $command )
	{
		return str_replace( ' ', '', $command );
	}
	/*
	protected $specialCharacters = array( '\\', '#', '$', '%', '&', '~', '_', '^', '{', '}' );
	public function escape( $str )
	{
		foreach ( $this->specialCharacters as $char )
			$str = str_replace( $char, '\\' . $char, $str );
		return $str;
	}
	*/
}