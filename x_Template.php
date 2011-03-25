<?php
set_time_limit( 10 );
require_once 'Vocabulary.php';
require_once 'Argument.php';
require_once 'GoModal/GoModal.php';

Doug_SimpleNotifier::allOff();
$outputDir = 'GoModal/output/';

$title = '';
$counter = 0;



$arguments = array(
	array(
		'label' => null,
		'premise' => 'B',
		'conclusion' => '(V A (~ A))',
		'bi' => null
	)
	
);


include ('x_Writer.php');
?>