<?php
use GoTableaux\Logic as Logic;
use GoTableaux\ProofWriter as ProofWriter;
use GoTableaux\Argument as Argument;

class LogicsController extends AppController
{
	public $uses = null;
	
	public $logics = array( 'CPL', 'FDE', 'LP', 'StrongKleene' );
	
	public function index()
	{	
		$logics = $this->logics;
		$title_for_layout = 'GoTableaux Proof Generator';
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
				$jsonProofWriter = ProofWriter::getInstance( $proof, 'JSON' );
				$proofJSON = $jsonProofWriter->writeProof( $proof );
				$logicName = Inflector::humanize( $Logic->getName() );
				$this->set( compact( 'proof', 'result', 'proofWriter', 'logicName', 'proofJSON' ));
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
}