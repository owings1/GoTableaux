<?php
set_time_limit( 10 );
require_once 'Vocabulary.php';
require_once 'Argument.php';
require_once 'GoModal/GoModal.php';

Doug_SimpleNotifier::allOff();
$outputDir = 'GoModal/output/';

$title = 'Conjunction / Disjunction';
$counter = 0;



$arguments = array(
	array(
		'label' => 'LEM',
		'premise' => 'B',
		'conclusion' => '(V A (~ A))'
	),
	array(
		'label'	=> 'LEM - Restricted',
		'premise' => 'C',
		'conclusion' => '(V (V A B) (~ (V A B)))'
	),
	array(
		'label' => 'LNC',
		'premise' => '(& A (~ A))',
		'conclusion' => 'B'
	),
	array(
		'label' => 'LNC*',
		'premise' => 'B',
		'conclusion' => '(~ (& A (~ A)))'
	),
	array(
		'premise' => '(~ (V A (~ A)))',
		'conclusion' => '(& A (~ A))'
	),
	array(
		'premise' => 'B',
		'conclusion' => '(V (& A A) (& (~ A) (~ A)))'
	),
	array(
		'premise' => 'B',
		'conclusion' => '(V (V A A) (V (~ A) (~ A)))'
	),
	array(
		'label'	=> 'Commutation - Conjunction',
		'premise' => '(& A B)',
		'conclusion' => '(& B A)',
		'bi' => true
	),
	array(
		'label' => 'Commutation - Disjunction',
		'premise' => '(V A B)',
		'conclusion' => '(V B A)',
		'bi' => true
	),
	array(
		'label' => 'Association - Conjunction',
		'premise' => '(& A (& B C))',
		'conclusion' => '(& (& A B) C)',
		'bi' => true
	),
	array(
		'label' => 'Association - Disjunction',
		'premise' => '(V A (V B C))',
		'conclusion' => '(V (V A B) C)',
		'bi' => true
	),
	array(
		'label' => 'Idempotence - Conjunction',
		'premise' => 'A',
		'conclusion' => '(& A A)',
		'bi' => true
	),
	array(
		'label' => 'Idempotence - Disjunction',
		'premise' => 'A',
		'conclusion' => '(V A A)',
		'bi' => true
	),
	array(
		'label' => 'Distribution$_1$',
		'premise' => '(& A (V B C))',
		'conclusion' => '(V (& A B) (& A C))',
		'bi' => true
	),
	array(
		'label' => 'Distribution$_2$',
		'premise' => '(V A (& B C))',
		'conclusion' => '(& (V A B) (V A C))',
		'bi' => true
	),
	array(
		'label'	=> 'Disjunctive Syllogism',
		'premises' => array(
			'(V A B)',
			'(~ A)'
		),
		'conclusion' => 'B'
	)
	

);


include ('x_Writer.php');
?>