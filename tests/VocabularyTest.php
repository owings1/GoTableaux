<?php
require_once 'Vocabulary.php';

$openMark 			= '(';
$closeMark 			= ')';
$subscriptSymbol 	= '_';
$separator			= ' ';

$atomicSymbols 		= array('A', 'B', 'C', 'D', 'E');

$operatorSymbols	= array(
	'&' => array(
		'name' => 'Conjunction',
		'arity' => 2
	),
	'V' => array(
		'name' => 'Disjunction',
		'arity' => 2
	),
	'~' => array(
		'name' => 'Negation',
		'arity' => '1'
	)
);

$sentenceStrings 	= array(
	'A',
	'B',
	'A_1',
	'B_123',
	'A & B',
	'(A_1 & B) & C',
	'((A_1 & B) & C)',
	'A & ((B_1 & C) V ~(A_1 & D))'
);

$vocabulary = new Vocabulary;
$parser 	= StandardSentenceParser::createWithVocabulary( $vocabulary );

$vocabulary	->addOpenMark( $openMark )
  			->addCloseMark( $closeMark )
  			->addSubscriptSymbol( $subscriptSymbol )
  			->addSeparator( $separator );

foreach ($atomicSymbols as $symbol) 
	$vocabulary->addAtomicSymbol( $symbol );

foreach ($operatorSymbols as $symbol => $info)
	$vocabulary->createOperator( $symbol, $info['arity'], $info['name'] );

$sentences = array();

foreach ($sentenceStrings as $sentenceStr) 
	$sentences[] = $parser->stringToSentence($sentenceStr);

foreach ($sentences as $sentence) {
	print_r($sentence);
	echo "\nParser Representation: " . $parser->sentenceToString($sentence) . "\n\n";
}
?>