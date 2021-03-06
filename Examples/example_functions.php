<?php
/**
 * GoTableaux. A multi-logic tableaux generator.
 * Copyright (C) 2012  Douglas Owings
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed WITHOUT ANY WARRANTY. 
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/agpl-3.0.html>.
 */
/**
 * Functions for examples.
 * @package Examples
 */

namespace GoTableaux;

// Define DS constant
if ( !defined( 'DS' )) define( 'DS', DIRECTORY_SEPARATOR );

// Load the GoTableaux program
require __DIR__ . DS . '..' . DS . 'Source' . DS . 'Loader.php';

/**
 * Evaluates a single argument, and returns a summary of the results.
 *
 * @param array|string $premises An array of sentence strings to be parsed by
 *								 the {@link StandardSentenceParser standard sentence parser}.
 * @param string $conclusion The conclusion string.
 * @param string $logicName The name of the logic against which to evaluate the argument.
 * @param string $output The type of proof writer output to use. Default is Simple.
 * @param string $notation The sentence notation for the proof writer to use.
 *						   Default is Standard notation.
 * @return string The summary of the results.
 */
function evaluate_argument( $premises, $conclusion, $logicName, $output = 'Simple', $parseNotation = 'Standard', $writeNotation = null )
{
	// Get instance of logic
	$logic = Logic::getInstance( $logicName );
	
	$summary = "Evaluating argument with $logicName...\n\n";
	
	// Create an argument
	$argument = $logic->parseArgument( $premises, $conclusion, $parseNotation );
	
	// Build a proof for the argument from the logic's proof system
	$proof = $logic->constructProofForArgument( $argument );
	
	// Get instance of proof writer
	$proofWriter = $logic->getProofWriter( $output, empty( $writeNotation ) ? $parseNotation : $writeNotation );
	
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
 * @param string $output The type of proof writer to use. Default is Simple.
 * @param string $notation The sentence notation for the proof writer to use.
 *						   Default is Standard notation.
 * @return string The summary of the results.
 */
function evaluate_many_arguments( array $arguments, $logicName, $output = 'Simple', $parseNotation = 'Standard', $writeNotation = null )
{
	$summary = "Evaluating " . count( $arguments ) . " Arguments with $logicName...\n\n";
	foreach ( $arguments as $name => $strings ) {	
		list( $premises, $conclusion ) = $strings;
		$summary .= evaluate_argument( $premises, $conclusion, $logicName, $output, $parseNotation, $writeNotation );
	}
	return $summary;
}