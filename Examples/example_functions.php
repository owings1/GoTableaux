<?php
/**
 * Functions for examples.
 * @package Examples
 * @author Douglas Owings
 */

namespace GoTableaux;

// Load the GoTableaux program
require dirname( __FILE__ ) . '/../GoTableaux.php';

/**
 * Evaluates a single argument, and returns a summary of the results.
 *
 * @param array|string $premises An array of sentence strings to be parsed by
 *								 the {@link StandardSentenceParser standard sentence parser}.
 * @param string $conclusion The conclusion string.
 * @param string $logicName The name of the logic against which to evaluate the argument.
 * @return string The summary of the results.
 */
function evaluate_argument( $premises, $conclusion, $logicName, $writer = 'Simple' )
{
	// Get instance of logic
	$logic = Logic::getInstance( $logicName );
	
	$summary = "Evaluating argument with $logicName...\n\n";
	
	// Create an argument
	$argument = $logic->parseArgument( $premises, $conclusion );
	
	// Build a proof for the argument from the logic's proof system
	$proof = $logic->constructProofForArgument( $argument );
	
	// Get instance of proof writer
	$proofWriter = ProofWriter::getInstance( $proof, $writer );
	
	// Print argument representation
	$summary .= "Argument: " . $proofWriter->writeArgumentOfProof( $proof ) . "\n\n";

	// Print proof representation
	$summary .= "Proof:\n\n" . $proofWriter->writeProof( $proof ) . "\n\n";

	// Print evaluation
	$summary .= 'Result: ' . ( $proof->isValid() ? 'Valid' : 'Invalid' ) . "\n\n";
	
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
function evaluate_many_arguments( array $arguments, $logicName, $writer = 'Simple' )
{
	$summary = "Evaluating " . count( $arguments ) . " Arguments with $logicName...\n\n";
	foreach ( $arguments as $name => $strings ) {	
		list( $premises, $conclusion ) = $strings;
		$summary .= evaluate_argument( $premises, $conclusion, $logicName, $writer );
	}
	return $summary;
}