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
use GoTableaux\Logic as Logic;
use GoTableaux\ProofWriter as ProofWriter;
use GoTableaux\Argument as Argument;

class LogicsController extends AppController
{
	public $uses = null;
	
	public $logics = array( 'CPL', 'FDE', 'LP', 'StrongKleene', 'Lukasiewicz', 'GO' );
	
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
	
	public function index()
	{	
		$logics = $this->logics;
		$title_for_layout = 'GoTableaux Proof Generator';
		//$exampleArguments = 
		$this->set( compact( 'logics', 'title_for_layout' ));
		if ( !empty( $this->data )) {
			
			if ( !strlen( $this->data['logic'] ))
				return $this->Session->setFlash( 'Please choose a logic.' );
			
			$Logic = Logic::getInstance( $this->logics[$this->data['logic']] );
			
			try {
				$premises = array();
				foreach ( $this->data['premises'] as $premiseStr )
					if ( !empty( $premiseStr )) $premises[] = $Logic->parseSentence( $premiseStr );
				$conclusion = $Logic->parseSentence( $this->data['conclusion'] );
				$argument = Argument::createWithPremisesAndConclusion( $premises, $conclusion );
				
				$proof = $Logic->constructProofForArgument( $argument );
				
				$result = $proof->isValid() ? 'valid' : 'invalid';
				
				$proofWriter = ProofWriter::getInstance( $proof );
				$argumentText = $proofWriter->writeArgumentOfProof( $proof );
				
				$latexProofWriter = ProofWriter::getInstance( $proof, 'LaTeX_Qtree' );
				$proofLatex = $latexProofWriter->writeProof( $proof );
				
				$jsonProofWriter = ProofWriter::getInstance( $proof, 'JSON' );
				$proofJSON = $jsonProofWriter->writeProof( $proof );
				
				$logicName = Inflector::humanize( $Logic->getName() );
				
				$this->set( compact( 'result', 'logicName', 'argumentText', 'proofJSON', 'proofLatex' ));
			} catch( Exception $e ) {
				return $this->Session->setFlash( $e->getMessage() );
			}
		} else {
			$this->data = array( 'premises' => array( '', '' ));
		}
	}
	
	public function get_lexicon( $logic )
	{
		if ( is_numeric( $logic )) $logic = $this->logics[$logic];
		$Logic = GoTableaux\Logic::getInstance( $logic );
		$Vocabulary = $Logic->getVocabulary();
		$lexicon = array(
			'openMarks' => $Vocabulary->getOpenMarks(),
			'closeMarks' => $Vocabulary->getCloseMarks(),
			'atomicSymbols' => $Vocabulary->getAtomicSymbols(),
			'operatorSymbols' => $Vocabulary->getOperatorSymbols(),
			'subscriptSymbols' => $Vocabulary->getSubscriptSymbols()
		);
		$this->set( compact( 'lexicon' ));
	}
	
	/**
	 * Streams a LaTeX'd PDF using Qtree.
	 *
	 * @param string logic
	 * @param array premises
	 * @param string conclusion
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
			$proofWriter = ProofWriter::getInstance( $proof, 'LaTeX_Qtree' );
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
}