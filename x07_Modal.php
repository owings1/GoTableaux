<?php
set_time_limit( 10 );
require_once 'Vocabulary.php';
require_once 'Argument.php';
require_once 'GoModal/GoModal.php';

Doug_SimpleNotifier::allOn();
$outputDir = 'GoModal/output/';

$title = 'Modal';
$counter = 60;



$arguments = array(
	array(
		'premise' => '(N A)',
		'conclusion' => '(~ (P (~ A)))'
	),
	array(
		'premise' => '(P A)',
		'conclusion' => '(~ (N (~ A)))'
	),
	array(
		'premise' => '(~ (P (~ A)))',
		'conclusion' => '(N A)'
	),
	array(
		'premise' => '(~ (N (~ A)))',
		'conclusion' => '(P A)'
	),
	array(
		'premise' => '(N A)',
		'conclusion' => '(~ (P (~ (& A A))))',
		'bi'	=> true
	),
	array(
		'premise' => '(P A)',
		'conclusion' => '(~ (N (~ (& A A))))',
		'bi' => true
	),
	array(
		'premise' => '(P A)',
		'conclusion' => '(P (& A A))',
		'bi' => true
	),
	array(
		'premise' => '(N A)',
		'conclusion' => '(N (& A A))',
		'bi' => true
	),
	array(
		'premise' => '(N A)',
		'conclusion' => 'A'
	),
	array(
		'premise' => 'A',
		'conclusion' => '(P A)'
	),
	array(
		'premise' => '(N (N A))',
		'conclusion' => '(N A)'
	),
	array(
		'premise' => '(N A)',
		'conclusion' => '(N (N A))'
	),
	array(
		'premise' => 'B',
		'conclusion' => '(-> (N A) (P A))'
	),
	array(
		'premise' => 'B',
		'conclusion' => '(-> (N A) (N (N A)))'
	),
	array(
		'premise' => '(P A)',
		'conclusion' => '(N (P A))'
	),
	array(
		'premise' => 'B',
		'conclusion' => '(-> (N A) (N (P A)))'
	),
	//
	//
	array(
		'premise' => '(P (V A B))',
		'conclusion' => '(V (P A) (P B))'
	),
	// conditionals
	array(
		'premise' => '(P (-> A B))',
		'conclusion' => '(-> (N A) (P B))'
	),
	array(
		'premise' => '(N (~ A))',
		'conclusion' => '(N (-> A B))'
	),
	
	// iterated modalities
	array(
		'premise' => '(P A)',
		'conclusion' => '(N (P A))'
	),
	array(
		'premise' => '(N A)',
		'conclusion' => '(P (N A))'
	),
	array(
		'premise' => '(P A)',
		'conclusion' => '(P (P A))'
	),
	array(
		'premise' => '(N A)',
		'conclusion' => '(N (N A))'
	)
	
	
	
	
);


include ('x_Writer.php');
?>