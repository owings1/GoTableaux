<?php

class Tableaux_Writer_LaTeX_Qtree extends Tableaux_Writer
{
	// redefine from parent
	protected 	$closeMarker = '\\varotimes ';
	
	
	protected	$conMarker = '\\vdash ',
				$nonConMarker = '\\nvdash ';
	
	static function getTeXUsePackageStr()
	{
		return '\\usepackage{latexsym, qtree, stmaryrd}';
	}
	function writeStructure( Tableaux_Structure $structure )
	{
		$string = '{';
		foreach ( $structure->getNodes() as $node ){
			$nodeStr = '$' . $this->translate( $node->__tostring() ) . '$';
			$string .= ( $structure->ticked( $node ) ) ? '\framebox{'. $nodeStr . '}' : $nodeStr;
			$string .= " \\\\ ";
		}
		if ( $structure->isClosed() ){
			$string .= '$ ' . $this->closeMarker . ' $';
		}
		$string = trim( $string, "\\ " ) . '} ';
		foreach ( $structure->getStructures() as $s ){
			$string .=  '[.' . self::writeStructure( $s ) . " ] \n";
		}
		$string = trim( $string, "\n" ) ;
		return $string;
	}
	function writeArgument()
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
	function doWrite()
	{
		
		$string = '\Tree[.';
		$string .= self::writeStructure( $this->structure );
		$string .= ' ]';
		return $string;
		
	}
	static function strip( $str )
	{
		if ( strpos( $str, '(' ) === 0 && strpos( strrev( $str ), ')' ) === 0 ){
			return substr( $str, 1, -1 );
		}
		return $str;
	}
	
}
?>