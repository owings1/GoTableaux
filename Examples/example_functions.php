<?php
/**
 * Functions for examples.
 * @package Examples
 * @author Douglas Owings
 */

// Load the Logic base class
require 'GoTableaux/Logic/Logic.php';

// Load the Sentence parser classes
require 'GoTableaux/Logic/Syntax/SentenceParser.php';

// Load the Tableau writer classes
require 'GoTableaux/Logic/ProofSystem/Tableaux/TableauWriter.php';

// Instantiate the sentence parser and tableau writer
$parser 		= new StandardSentenceParser;
$tableauWriter 	= new SimpleTableauWriter;

/**
 * Evaluates a single argument, and returns a summary of the results.
 *
 * @param array|string $premises An array of sentence strings to be parsed by
 *								 the {@link StandardSentenceParser standard sentence parser}.
 * @param string $conclusion The conclusion string.
 * @param string $logicName The name of the logic against which to evaluate the argument.
 * @return string The summary of the results.
 */
function evaluate_argument( $premises, $conclusion, $logicName )
{
	global $parser;
	global $tableauWriter;
	
	$summary = "Evaluating argument with $logicName...\n\n";
	
	// Get instance of logic
	$logic = Logic::getInstance( $logicName );
	
	// Create an argument
	$argument = $logic->parseArgument( $premises, $conclusion, $parser );
	
	// Build a proof for the argument from the logic's proof system
	$tableau = $logic->getProofSystem()
				   	 ->constructProofForArgument( $argument );
	
	// Print argument representation
	$summary .= "Argument:\n" . $tableauWriter->writeArgument( $argument, $logic ) . "\n";

	// Print tableau representation
	$summary .= "Tableau:\n" . $tableauWriter->writeTableau( $tableau, $logic ) . "\n";

	// Print evaluation
	$summary .= 'Result: ' . ($tableau->isValid() ? 'Valid' : 'Invalid') . "\n\n";
	
	return $summary;
}

/**
 * Evaluates many arguments and returns a summary of the results.
 *
 * Example of argument array:
 * <code>
 * $arguments = array(
 * 		'Disjunctive Syllogism' => array( array( 'A V B', '~A' ), 'B' ),
 *		'Law of Excluded Middle' => array( null, 'A V ~A' ),
 *		'Simplification' => array( 'A & B', 'B' )
 * );
 * </code>
 * @param array $arguments An array of argument strings.
 * @param string $logicName The name of the logic.
 * @return string The summary of the results.
 */
function evaluate_many_arguments( array $arguments, $logicName )
{
	global $parser;
	global $tableauWriter;
	
	$summary = "Evaluating " . count( $arguments ) . " Arguments with $logicName...\n\n";
	
	// Get the logic instance
	$logic = Logic::getInstance( $logicName );
	
	foreach ( $arguments as $name => $argumentStrings ) {
		// Create an argument
		$argument = $logic->parseArgument( $argumentStrings[0], $argumentStrings[1], $parser );
		
		// Build a proof for the argument from the logic's proof system
		$tableau = $logic->getProofSystem()
					   	 ->constructProofForArgument( $argument );

		// Print argument name and evaluation 
		$summary .= "$name (" . ($tableau->isValid() ? 'Valid' : 'Invalid') . ")\n\n"; 

		// Print argument representation
		$summary .= $tableauWriter->writeArgument( $argument, $logic ) . "\n\n";

		// Print tableau representation
		$summary .= "Tableau:\n" . $tableauWriter->writeTableau( $tableau, $logic ) . "\n";
	}
	return $summary;
}