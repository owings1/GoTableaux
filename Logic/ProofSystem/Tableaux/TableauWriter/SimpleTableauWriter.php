<?php
/**
 * Defines the TableauWriter_Simple class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Loads the {@link TableauWriter} parent class.
 */
require_once 'GoTableaux/Logic/ProofSystem/Tableaux/TableauWriter.php';

/**
 * Represents a simple tableau proof writer.
 * @package Tableaux
 * @author Douglas Owings
 */
class SimpleTableauWriter extends TableauWriter
{
	public function writeArgument( Argument $argument, Logic $logic )
	{
		$string = '';
		$sentenceWriter = $this->getSentenceWriter();
		foreach ( $argument->getPremises() as $premise ) 
			$string .= $sentenceWriter->writeSentence( $premise, $logic ) . PHP_EOL;
		$string .= '------------------------------' . PHP_EOL;
		$string .= $sentenceWriter->writeSentence( $argument->getConclusion(), $logic );
		return $string;
	}
	
	public function writeTableau( Tableau $tableau, Logic $logic )
	{
		$string = '';
		$structure = $tableau->getStructure();
		$string .= $this->_writeStructure( $structure, $this->getSentenceWriter(), $logic );
		return $string;
	}
	
	/**
	 * @access private
	 */
	private function _writeStructure( Structure $structure, SentenceWriter $sentenceWriter, Logic $logic )
	{
		$string = '';
		foreach ( $structure->getNodes() as $node ) {
			echo "Node\n";
			$string .= $sentenceWriter->writeSentence( $node->getSentence(), $logic );
			$string .= PHP_EOL;
		}
		return $string;
	}
}