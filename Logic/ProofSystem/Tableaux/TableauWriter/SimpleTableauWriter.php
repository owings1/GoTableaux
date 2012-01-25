<?php
/**
 * Defines the TableauWriter_Simple class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Represents a simple tableau proof writer.
 * @package Tableaux
 * @author Douglas Owings
 */
class SimpleTableauWriter extends TableauWriter
{
	protected $counter = 0;
	
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
		$this->counter = 0;
		$string .= $this->_writeStructure( $structure, $this->getSentenceWriter(), $logic );
		return $string;
	}
	
	/**
	 * @access private
	 */
	private function _writeStructure( Structure $structure, SentenceWriter $sentenceWriter, Logic $logic )
	{
		if ( $this->counter === 0 ) $string = 'Trunk:' . PHP_EOL;
		else $string = 'Branch ' . $this->counter . ':' . PHP_EOL;
		$this->counter++;
		foreach ( $structure->getNodes() as $node ) {
			if ( $node instanceof SentenceNode ) {
				$string .= $sentenceWriter->writeSentence( $node->getSentence(), $logic );
				if ( $node instanceof ModalNode )
					$string .= ', w' . $node->getI();
			} elseif ( $node instanceof AccessNode )
				$string .= $node->getI() . 'R' . $node->getJ();
			if ( $node instanceof ManyValuedNode )
				$string .= $node->isDesignated() ? ' designated' : ' undesignated';
			if ( $structure->nodeIsTicked( $node )) $string .= ' ticked';
			$string .= PHP_EOL;
		}
		if ( $subStructures = $structure->getStructures() ) 
			foreach ( $subStructures as $subStructure )
				$string .= $this->_writeStructure( $subStructure, $sentenceWriter, $logic );
		else $string .= ( $structure->isClosed() ? 'closed' : 'open' ) . PHP_EOL;
		return $string;
	}
}