<?php
/**
 * Defines the Writer_LaTeX_Qtree class.
 * @package Tableaux
 * @author Douglas Owings
 */

/**
 * Writes trees in LaTeX using the qtree package.
 * @package Tableaux
 * @author Douglas Owings
 */
class Writer_LaTeX_Qtree extends Writer
{
	/**
	 * @var string
	 */
	protected $closeMarker = '\\varotimes ';
	
	/**
	 * @var string
	 */
	protected $conMarker = '\\vdash ';
	
	/**
	 * @var string
	 */
	protected $nonConMarker = '\\nvdash ';
	
	/**
	 * Gets the \usepackage LaTeX string to place at the head of a LaTeX file.
	 *
	 * @return string The use package string.
	 */
	public static function getTeXUsePackageStr()
	{
		return '\\usepackage{latexsym, qtree, stmaryrd}';
	}
	
	/**
	 * Strips parentheses.
	 *
	 * @param string $str The input string.
	 * @return string The resulting string.
	 */
	protected static function strip( $str )
	{
		if ( strpos( $str, '(' ) === 0 && strpos( strrev( $str ), ')' ) === 0 )
			return substr( $str, 1, -1 );
		return $str;
	}
	
	/**
	 * Implements Writer::writeArgument()
	 *
	 * @return string The string representation of the tableau's argument.
	 */
	public function writeArgument()
	{
		// add: strip parentheses
		$string = '$ ';
		$arg = $this->tableau->getArgument();
		$premises = $arg->getPremises();
		if ( count( $premises ) == 0 ){
			$string .= '\\emptyset ';
		}
		elseif ( count( $premises ) == 1 ){
			$premise = $premises[0];
			$string .= self::strip( $this->translate( $premise->__tostring() ) );
		}
		else{
			$string .= '\\{';
			foreach ( $premises as $premise ){
				$string .= self::strip( $this->translate( $premise->__tostring() ) ). ', ';
			}
			$string = trim( $string, ', ' ) . ' \\} ';

		}
		$string .= ( $this->tableau->isValid() ) ? $this->conMarker : $this->nonConMarker;
		$string .= ' ' . self::strip( $this->translate( $arg->getConclusion()->__tostring() )) . ' $';
		return $string;
	}
	
	/**
	 * Implements Writer::write()
	 *
	 * @return string The string representation of the tableau structure.
	 */
	public function write()
	{
		$string = '\Tree[.';
		$string .= self::writeStructure( $this->structure );
		$string .= ' ]';
		return $string;
	}
	
	/**
	 * Writes a structure (recursive).
	 *
	 * @param Structure $structure The structure to write.
	 * @return string The string representation of the structure.
	 */
	protected function writeStructure( Structure $structure )
	{
		$string = '{';
		foreach ( $structure->getNodes() as $node ){
			$nodeStr = '$' . $this->translate( $node->__tostring() ) . '$';
			$string .= ( $structure->nodeIsTicked( $node ) ) ? '\framebox{'. $nodeStr . '}' : $nodeStr;
			$string .= " \\\\ ";
		}
		if ( $structure->isClosed() )
			$string .= '$ ' . $this->closeMarker . ' $';
		
		$string = trim( $string, "\\ " ) . '} ';
		foreach ( $structure->getStructures() as $s )
			$string .=  '[.' . self::writeStructure( $s ) . " ] \n";
		
		$string = trim( $string, "\n" ) ;
		return $string;
	}
}