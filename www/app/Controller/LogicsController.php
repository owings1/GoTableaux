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
use GoTableaux\Utilities as Utilities;
use GoTableaux\Logic as Logic;
use GoTableaux\ProofWriter as ProofWriter;
use GoTableaux\SentenceWriter as SentenceWriter;
use GoTableaux\Argument as Argument;
use GoTableaux\Proof\Tableau as Tableau;
use GoTableaux\ProofSystem\TableauxSystem\Rule\Node as NodeRule;

class LogicsController extends AppController
{
	public $uses = null;
	
	public $logics = array( 
		'CPL', 
		'FDE', 
		'LP', 
		'StrongKleene', 
		'Lukasiewicz', 
		'GO', 
		'K', 
		'T', 
		'D' 
	);
	
	public $parseNotations = array(
		'Standard',
		'Polish'
	);
	
	public $writeNotations = array(
		'Standard',
		'Polish'
	);
	
    public $helpers = array( 'Inflect' );
        
	private $exampleArguments = array(
		'Disjunctive Syllogism' 	=> array( array( 'A V B', '~B' ), 'A' ),
		'Affirming a Disjunct'		=> array( array( 'A V B', 'A' ), 'B' ),
		'Law of Excluded Middle' 	=> array( 'B', 'A V ~A' ),
		'Denying the Antecedent' 	=> array( array( 'A > B', '~A' ), 'B' ),
		'Law of Non-contradiction' 	=> array( 'A & ~A', 'B' ),
		'Identity'					=> array( null, 'A > A' ),
		'Modus Ponens' 				=> array( array( 'A > B', 'A' ), 'B' ),
		'Modus Tollens' 			=> array( array( 'A > B', '~B' ), '~A' ),
		'Syllogism'					=> array( array( 'A > B', 'B > C'), 'A > C' ),
		'DeMorgan 1' 				=> array( '~(A V B)', '~A & ~B' ),
		'DeMorgan 2' 				=> array( '~(A & B)', '~A V ~B' ),
		'DeMorgan 3' 				=> array( '~A & ~B', '~(A V B)' ),
		'DeMorgan 4' 				=> array( '~A V ~B', '~(A & B)' ),
		'Contraction'				=> array( 'A > (A > B)', 'A > B' ),
		'Pseudo Contraction'		=> array( null, '(A > (A > B)) > (A > B)' ),
	);
	
        /**
         * Home page.
         * 
         * 
	 * @postParam string logic
	 * @postParam string parse_notation
	 * @postParam string write_notation
	 * @postParam array premises
	 * @postParam string conclusion
         */
	public function index()
	{	
		$logics = $this->logics;
		$title_for_layout = 'GoTableaux Proof Generator';
		$write_notations = $this->writeNotations;
		$parse_notations = $this->parseNotations;
		$this->set( compact( 'logics', 'write_notations', 'parse_notations', 'title_for_layout' ));
		if ( !empty( $this->data )) {
			
			if ( !strlen( $this->data['logic'] ))
				return $this->Session->setFlash( 'Please choose a logic.' );
			
			$Logic = Logic::getInstance( $this->logics[$this->data['logic']] );
			
			try {
				$parseNotation = empty( $this->request->data['parse_notation'] ) ? 'Standard' : $this->parseNotations[ $this->request->data['parse_notation'] ];
				$writeNotation = empty( $this->request->data['write_notation'] ) ? 'Standard' : $this->writeNotations[ $this->request->data['write_notation'] ];
				
				$premises = array();
				foreach ( $this->data['premises'] as $premiseStr )
					if ( !empty( $premiseStr )) $premises[] = $Logic->parseSentence( $premiseStr, $parseNotation );
				$conclusion = $Logic->parseSentence( $this->data['conclusion'], $parseNotation );
				$argument = Argument::createWithPremisesAndConclusion( $premises, $conclusion );
				$proof = $Logic->constructProofForArgument( $argument );
				
				$result = $proof->isValid() ? 'valid' : 'invalid';
				
				$proofWriter = $proof->getWriter( null, $writeNotation );
				$argumentText = $proofWriter->writeArgumentOfProof( $proof );
				
				$latexProofWriter = $proof->getWriter( 'LaTeX_Qtree', $writeNotation );
				$proofLatex = $latexProofWriter->writeProof( $proof );
				
				$jsonProofWriter = $proof->getWriter( 'JSON', $writeNotation );
				$proofJSON = $jsonProofWriter->writeProof( $proof );
				
				$logicName = Inflector::humanize( $Logic->getName() );
				
				$this->set( compact( 'result', 'logicName', 'argumentText', 'proofJSON', 'proofLatex' ));
			} catch( Exception $e ) {
				return $this->Session->setFlash( $e->getMessage() );
			}
		} else {
			$this->request->data = array( 'premises' => array( '', '' ));
		}
	}
	
        /**
         * Gets the parsing lexicon for a logic.
         * 
         * @param string $logic The logic name.
         */
	public function get_lexicon( $logic, $notation = 'Standard' )
	{
		if ( is_numeric( $logic )) $logic = $this->logics[$logic];
		if ( is_numeric( $notation )) $notation = $this->parseNotations[$notation];
		$Logic = Logic::getInstance( $logic );
		$parser = $Logic->getParser( $notation );
		$lexicon = array(
			'atomicSymbols' => $parser->atomicSymbols,
			'operatorNames' => $parser->getLogicOperatorSymbolNames(),
			'allOperatorNames' => $parser->getOperatorSymbolNames(),
		);
		$this->set( compact( 'lexicon' ));
	}
	
	/**
	 * Streams a LaTeX'd PDF using Qtree.
	 *
	 * @postParam integer The index of the logic in $this->logics
	 * @postParam array premises
	 * @postParam string conclusion
	 * @sets string pdfContent
	 */
	public function view_pdf()
	{
		if ( empty( $this->data )) {
			$this->Session->setFlash( 'No data.' );
			return $this->redirect( 'index' );
		}
		try {
			$Logic = Logic::getInstance( $this->logics[$this->data['logic']] );
			$premises = array();
			foreach ( $this->data['premises'] as $premiseStr )
				if ( !empty( $premiseStr )) $premises[] = $Logic->parseSentence( $premiseStr );
			$conclusion = $Logic->parseSentence( $this->data['conclusion'] );
			$argument = Argument::createWithPremisesAndConclusion( $premises, $conclusion );
			$proof = $Logic->constructProofForArgument( $argument );	
			$proofWriter = $proof->getWriter( 'LaTeX_Qtree', $this->writeNotations[ $this->request->data['write_notation'] ]);
			$this->Latex->addLibraryFile( APPLIBS . 'qtree.sty' );
			$this->Latex->input = $proofWriter->writeProof( $proof );
			$pdfContent = $this->Latex->getPdfContent();
		} catch( Exception $e ) {
			$this->Session->setFlash( "Error making pdf" );
			debug( array( 'message' => $e->getMessage(), 'latexLog' => $this->Latex->log ));
			CakeLog::write( 'latex', $this->Latex->log );
			return $this->redirect( 'index' );
		}
		$this->layout = 'pdf';
		CakeLog::write( 'latex', $this->Latex->log );
		$this->set( compact( 'pdfContent' ));
	}
        
	/**
	 * View details about a logic.
	 * 
	 * @param string $logic 
	 * 
	 */
	public function view( $logic )
	{
	    if ( !in_array( $logic, $this->logics )) {
	        $this->Session->setFlash( "Unknown logic $logic" );
	        return $this->redirect( 'index' ); 
	    }
	    $Logic = Logic::getInstance( $logic );
	    $title_for_layout = $logicName = $Logic->getName();
		$rules = array();
		$rule['class'] = str_replace( '\\', '.', get_class( $Logic->getProofSystem() )) . '.TrunkRule';
		$rule['name'] = 'Trunk (Initial Rule)';
		$exampleTableau = new Tableau( $Logic->getProofSystem() );
		$Logic->getProofSystem()->buildTrunk( $exampleTableau, $Logic->parseArgument( array( 'A > B', 'A' ), 'B' ), $Logic );
		$jsonProofWriter = $exampleTableau->getWriter( 'JSON' );
		$rule['tableauJSON'] = $jsonProofWriter->writeProof( $exampleTableau );
		$rules[] = $rule;
	    $sentenceWriter = SentenceWriter::getInstance( $Logic, 'Standard', 'HTML');
		foreach ( $Logic->getProofSystem()->getRules() as $Rule ) {
			$rule = array();
			$rule['class'] = str_replace( '\\', '.', get_class( $Rule ));
			$rule['name'] = Utilities::getBaseClassName( $Rule );
			if ( $Rule instanceof NodeRule ) {
				$rule['conditions'] = $Rule->getConditions();
				// Convert sentences to HTML
				if ( !empty( $rule['conditions']['sentenceForm'] )) {
					$sentence = $Logic->parseSentence( $rule['conditions']['sentenceForm'] );
					$rule['conditions']['sentenceForm'] = $sentenceWriter->writeSentence( $sentence );
				}
				if ( !empty( $rules['conditions']['sentence'] )) {
					$rule['conditions']['sentence'] = $sentenceWriter->writeSentence( $rule['conditions']['sentence'] );
				}
			}
			if ( $exampleTableau = $Rule->getExample( $Logic )) {
				$jsonProofWriter = $exampleTableau->getWriter( 'JSON' );
				$rule['tableauJSON'] = $jsonProofWriter->writeProof( $exampleTableau );
			}
			$rules[] = $rule;
		}
	    $this->set( compact( 'logicName', 'title_for_layout', 'rules' ));
	}
}