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
class LatexComponent extends Component
{
	public $pdfLatexPath = '';
	
	public $extraBinPaths = array();
	
	public $input = '';
	
	public $log = '';
	
	private $tempFiles = array();
	
	public function getPdfFileName( $input = null )
	{
		if ( empty( $input )) $input = $this->input;
		$this->validate( $input );
		
		// get temp dir
		$tempDir = sys_get_temp_dir();
		$cdPrefix = "cd $tempDir; ";
		
		// create temp latex file
		$latexFileName = tempnam( $tempDir, 'LatexOutput' );
		file_put_contents( $latexFileName, $input );
		
		// run pdf latex
		$cmd = $cdPrefix . $this->getPdfLatexPath() . " $latexFileName -halt-on-error";
		$this->log .= "Executing command $cmd\n";
		$shellOutput = exec( $cmd );
		
		// check for pdf file
		if ( !file_exists( "$latexFileName.pdf" )) {
			$this->log .= "Error: file $latexFileName.pdf was not created. Aborting.\n";
			$this->log .= "Shell output was $shellOutput\n";
			throw new Exception( $shellOutput );
		}
		
		// add to log
		$this->log .= file_get_contents( "$latexFileName.log" );
		
		// register temp files
		$this->tempFiles[] = $latexFileName;
		$this->tempFiles[] = "$latexFileName.pdf";
		$this->tempFiles[] = "$latexFileName.log";
		$this->tempFiles[] = "$latexFileName.aux";
		
		return "$latexFileName.pdf"; 
	}
	
	public function getPdfStr( $input = null )
	{
		return file_get_contents( $this->getPdfFileName( $input ));
	}
	
	public function getPdfContent( $input = null )
	{
		return $this->getPdfStr( $input );
	}
	
	public function validate( $input )
	{
		if ( !strlen( $input ))
			throw new Exception( 'LaTeX input cannot be empty.' );
	}
	
	public function getPdfLatexPath()
	{
		return empty( $this->pdfLatexPath ) ? 'pdflatex' : $this->pdfLatexPath;
	}
	
	public function getPathPrefix()
	{
		if ( empty( $this->extraBinPaths )) return '';
		return 'PATH="$PATH:' . implode( ':', $this->extraBinPaths ) . '"; ';
		
	}
	public function __destruct()
	{
		foreach ( $this->tempFiles as $fileName ) {
			$this->log .= "Deleting temp file $fileName\n";
			unlink( $fileName );	
		} 
	}
	
	public function addLibraryFile( $fileName )
	{
		if ( file_exists( $fileName )) {
			$tempDir = sys_get_temp_dir();
			$baseName = pathinfo( $fileName, PATHINFO_BASENAME );
			$tempLibraryFile = $tempDir . DS . $baseName;
			copy( $fileName, $tempLibraryFile );
			$this->tempFiles[] = $tempLibraryFile;
		}
	}
}