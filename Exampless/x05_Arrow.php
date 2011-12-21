<?php
set_time_limit( 15 );
require_once 'Vocabulary.php';
require_once 'Argument.php';
require_once 'GoModal/GoModal.php';

Doug_SimpleNotifier::allOff();
$outputDir = 'GoModal/output/';

$title = 'Conditional';
$counter = 41;



$arguments = array(
	array(
		'label' => 'Identity',
		'premise' => 'B',
		'conclusion' => '(-> A A)'
	),
	array(
		'label'	=> 'Identity - Restricted',
		'premise' => 'B',
		'conclusion' => '(-> (& A A) A)'
	),
	
	array(
		'label' => 'Modus Ponens',
		'premises' => array(
			0 => 'A',
			1 => '(-> A B)'
		),
		'conclusion' => 'B'
	),
	array(
		'label' => 'Pseudo Modus Ponens',
		'premise' => 'C',
		'conclusion' => '(-> (& A (-> A B)) B)'
	),
	array(
		'label' => 'Modus Tollens',
		'premises' => array(
			0 => '(~ B)',
			1 => '(-> A B)'
		),
		'conclusion' => '(~ A)'
	),
	array(
		'label' => 'Pseudo Modus Tollens',
		'premise' => 'C',
		'conclusion' => '(-> (& (~ B) (-> A B)) (~ A))'
	),
	array(
		'label' => 'Hypothetical Syllogism',
		'premises' => array(
			'(-> A B)',
			'(-> B C)'
		),
		'conclusion' => '(-> A C)'
	),
	array(
		'label' => 'Contraction',
		'premise' => '(-> A (-> A B))',
		'conclusion' => '(-> A B)',
		'bi' => true
	),
	array(
		'label' => 'Pseudo Contraction',
		'premise' => 'C',
		'conclusion' => '(-> (-> A (-> A B)) (-> A B))'
	),
	array(
		'label' => 'Contraposition',
		'premise' => '(-> A B)',
		'conclusion' => '(-> (~ B) (~ A))',
		'bi' => true
	),
	array(
		'label' => 'Pseudo Contraposition',
		'premise' => 'C',
		'conclusion' => '(-> (-> A B) (-> (~ B) (~ A)))'
	),
	array(
		'label'	=> 'Exportation',
		'premise' => '(-> A (-> B C))',
		'conclusion' => '(-> (& A B) C)',
		'bi' => true
	),
	array(
		'premise' => '(~ A)',
		'conclusion' => '(-> A B)'
	),
	array(
		'premise' => '(~ (-> A B))',
		'conclusion' => '(~ B)'
	),
	array(
		'premise' => 'A',
		'conclusion' => '(-> B A)'
	),
	array(
		'premise' => '(-> A B)',
		'conclusion' => '(-> (& A C) B)'
	),
	array(
		'premise' => '(-> (& A B) C)',
		'conclusion' => '(V (-> A C) (-> B C))'
	)
);


include ('x_Writer.php');
?>