<?php
set_time_limit( 10 );
require_once 'Vocabulary.php';
require_once 'Argument.php';
require_once 'GoModal/GoModal.php';
require_once 'Tableaux/Writer.php';

Doug_SimpleNotifier::allOn();

$outputDir = 'GoModal/output/';

$fileName = 'tableau_' . time();

$arguments = array(
	array(
		'premises' => array(
			'(V A B)'
		),
		'conclusion' => '(~ (& (~ (& A A)) (~ (& B B))))'
	),
	array(
		'premises' => array(
			'(~ (& (~ (& A A)) (~ (& B B))))'
		),
		'conclusion' => '(V A B)'
	)
);
//\neg(\neg(A\vee A)\vee\neg(B\vee B))
$writer = new Tableaux_Writer_LaTeX_Qtree();
$writer->setTranslation( GoModal::getLaTeXTranslations() );



$TeX 	= 	'\\documentclass{article}' . "\n"
		.	Tableaux_Writer_LaTeX_Qtree::getTeXUsePackageStr() . "\n" 
		.	'\\usepackage{amssymb}' . "\n"
		.	'\\begin{document}' . "\n";
		

foreach ( $arguments as $i => $argument ){
	
	/*	Make new Tableau for Argument 		*/
	$tableau = GoModal::newTableau( $argument['premises'], $argument['conclusion'] );
	
	/*	Build Tableau						*/
	$tableau->build();
	
	/*	Set Writer to tableau				*/
	$writer->setTableau( $tableau );
	
	/*	Write Argument in TeX				*/
	$TeX .= "\n\n\\bigskip\n\\begin{quote} \n(" . ($i+1) . ") " . $writer->writeArgument() . "\n\\end{quote} \n\\bigskip\n\n"; 
	
	/*	Write Tableau in TeX				*/
	$TeX .= $writer->write();
	
	if ( ! $tableau->isValid( $openBranch )){
		$counterModel = GoModal_Branch::induceModel( $openBranch );
		$TeX .= "\bigskip\n\n";
		$TeX .= "Counter Model: \n\n\\bigskip\n";
		$TeX .= '\\noindent $\\mathcal{W} = \\{ ';
		foreach ( $counterModel['W'] as $w ){
			$TeX .= 'w_' . $w . ',';
		}
		$TeX = trim( $TeX, ',' ) . '\\} $\\\\' . "\n";
		$TeX .= '$\\mathcal{R} = \\{ ';
		foreach ( $counterModel['R'] as $r ){
			$TeX .= '\\langle w_' . $r[0] . ',w_' . $r[1] . ' \\rangle ,';
		}
		$TeX = trim( $TeX, ',' ) . '\\} $\\\\' . "\n";
		foreach ( $counterModel['v'] as $v ){
			$sentence = $v[1];
			$TeX .= '$ \\mathcal{\\nu}_{w_' . $v[0] . '}(' . $sentence->__tostring() . ')=' . $v[2] . ' $ ' . "\\\\\n";
		}
		$TeX .= "\\bigskip\n\n";
	}
}

$TeX .= "\n\n\n \\end{document}";

/*	Output TeX to File			*/
file_put_contents( $outputDir . $fileName . '.tex', $TeX );

/*	Build PDF					*/
system( 'pdflatex ' . $outputDir . $fileName );

sleep( 2 );

/*	Move PDF					*/
rename( $fileName . '.pdf', $outputDir . $fileName . '.pdf' );
unlink( $fileName . '.aux' );
unlink( $fileName . '.log' );

/*	Open PDF					*/
system( 'open ' . $outputDir . $fileName . '.pdf' );
echo $TeX;

?>