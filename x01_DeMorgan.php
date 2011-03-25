<?php
set_time_limit( 10 );
require_once 'Vocabulary.php';
require_once 'Argument.php';
require_once 'GoModal/GoModal.php';

Doug_SimpleNotifier::allOff();
$outputDir = 'GoModal/output/';

$title = 'DeMorgan';
$counter = 16;



$arguments = array(
	array(
		'premise' => '(~ (V A B))',
		'conclusion' => '(& (~ A) (~ B))',
	),
	array(
		'premise' => '(& (~ A) (~ B))',
		'conclusion' => '(~ (V A B))',
	),
	array(
		'premise' => '(~ (& A B))',
		'conclusion' => '(V (~ A) (~ B))',
	),
	array(
		'premise' => '(V (~ A) (~ B))',
		'conclusion' => '(~ (& A B))',
	),
	array(
		'premise' => '(~ (V (V A B) (V C D)))',
		'conclusion' => '(& (~ (V A B)) (~ (V C D)))',
		'bi' => true
	),
	array(
		'premise' => '(~ (& (V A B) (V C D)))',
		'conclusion' => '(V (~ (V A B)) (~ (V C D)))',
		'bi' => true
	)
);


include ('x_Writer.php');
?>