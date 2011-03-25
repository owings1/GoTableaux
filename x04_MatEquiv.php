<?php
set_time_limit( 10 );
require_once 'Vocabulary.php';
require_once 'Argument.php';
require_once 'GoModal/GoModal.php';

Doug_SimpleNotifier::allOff();
$outputDir = 'GoModal/output/';

$title = 'Material Equivalence';
$counter = 39;



$arguments = array(
	array(
		'premise' => 'B',
		'conclusion' => '(<> A A)'
	),
	array(
		'premise' => 'D',
		'conclusion' => '(V (V (<> A B) (<> A C)) (<> B C))'
	)
	
);


include ('x_Writer.php');
?>