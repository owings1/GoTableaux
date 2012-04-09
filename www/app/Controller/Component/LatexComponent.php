<?php
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