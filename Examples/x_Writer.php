<?php

$writer = new Tableaux_Writer_LaTeX_Qtree();
$writer->setTranslation( GoModal::getLaTeXTranslations() );


$TeXList = "%%%%%%%%%%%%%%%%%% ARGUMENT LIST %%%%%%%%%%%%%%%%%%%%\n\n";
$TeXList .= "\\noindent \\textbf{" . $title ."}\n";
$TeXList .= "\n\\begin{enumerate}\n\\setcounter{enumi}{\\value{enumi_saved}}\n";

$TeX = "\n\n%%%%%%%%%%%%%%%%%%%%% TABLEAUX %%%%%%%%%%%%%%%%%%%%%\n\n";

for ( $i = 0; $i < count( $arguments ); $i++ ){
	
	$argument = $arguments[$i];
	
	$counter++;
	
	/*	Check for Single Premise Argument	*/
	if ( isset( $argument['premise'] )){
		$premise = $argument['premise'];
		$premises = array();
		$premises[] =  $premise;
	}
	/*	Check for Empty Premise Set			*/
	elseif ( ! isset( $argument['premises'] )){
		$premises = array();
	}
	else{
		$premises = $argument['premises'];
	}
	/*	Make new Tableau for Argument 		*/
	$tableau = GoModal::newTableau( $premises, $argument['conclusion'] );
	
	/*	Build Tableau						*/
	$tableau->build();
	
	/*	Set Writer to tableau				*/
	$writer->setTableau( $tableau );
	
	$a = '';
	$label = ( isset( $argument['label'] ) ) ? '(' . $argument['label'] . ')' : '';
	if ( isset( $argument['bi'] )){
		if ( ! $argument['bi'] ){
			$counter--;
			$a = 'b';
		}
		else{
			$a = 'a';
			$str = "\\item " . $writer->writeArgument() . " \\hfill\\emph{ " . $label . "} " . " \n";
			$TeXList .= str_replace( '\\vdash', '\\leftvdash\rightvdash', $str );
			array_splice( $arguments, $i+1, 0, array(array( 'premise' => $argument['conclusion'], 'conclusion' => $premise, 'bi' => false )));
		}
	}
	else{
		$TeXList .= "\\item " . $writer->writeArgument() . "\\hfill\\emph{ " . $label . "} " . " \n";
	}
	
	print_r($argument);
	
	/*	Write Argument in TeX				*/
	$TeX .= "\n\n\n\\begin{quote} \n(" . ($counter . $a) . ") " . $writer->writeArgument() . "\n\\end{quote} \n\\bigskip\n\n"; 
	
	/*	Write Tableau in TeX				*/
	$TeX .= $writer->write();
	
	if ( ! $tableau->isValid( $openBranch )){
		$counterModel = GoModal_Branch::induceModel( $openBranch );
		$TeX .= "\n\n\bigskip\n\n";
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

$TeXList .= "\\setcounter{enumi_saved}{\\value{enumi}}\n\\end{enumerate}\n";

/* 	Set Output File		*/
$scriptNameArr = explode( '/', $_SERVER['PHP_SELF'] );
$i = count( $scriptNameArr ) - 1;

$fileName = str_replace( '.php', '', $scriptNameArr[$i] );

/*	Output TeX to File			*/
file_put_contents( $outputDir . $fileName . '.tex', $TeXList . $TeX );



echo $TeX;

?>