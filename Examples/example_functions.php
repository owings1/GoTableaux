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
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program in file LICENSE.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * Functions for examples.
 * @package Examples
 * @author Douglas Owings
 */

namespace GoTableaux;

// Load the GoTableaux program
require __DIR__ . '/../GoTableaux.php';

/**
 * Evaluates a single argument, and returns a summary of the results.
 *
 * @param array|string $premises An array of sentence strings to be parsed by
 *								 the {@link StandardSentenceParser standard sentence parser}.
 * @param string $conclusion The conclusion string.
 * @param string $logicName The name of the logic against which to evaluate the argument.
 * @param string $writer The type of proof writer to use. Default is Simple.
 * @param string $notation The sentence notation for the proof writer to use.
 *						   Default is Standard notation.
 * @return string The summary of the results.
 */
function evaluate_argument( $premises, $conclusion, $logicName, $writer = 'Simple', $notation = 'Standard' )
{
	// Get instance of logic
	$logic = Logic::getInstance( $logicName );
	
	$summary = "Evaluating argument with $logicName...\n\n";
	
	// Create an argument
	$argument = $logic->parseArgument( $premises, $conclusion, $notation );
	
	// Build a proof for the argument from the logic's proof system
	$proof = $logic->constructProofForArgument( $argument );
	
	// Get instance of proof writer
	$proofWriter = ProofWriter::getInstance( $proof, $writer, $notation );
	
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
 * @param string $writer The type of proof writer to use. Default is Simple.
 * @param string $notation The sentence notation for the proof writer to use.
 *						   Default is Standard notation.
 * @return string The summary of the results.
 */
function evaluate_many_arguments( array $arguments, $logicName, $writer = 'Simple', $notation = 'Standard' )
{
	$summary = "Evaluating " . count( $arguments ) . " Arguments with $logicName...\n\n";
	foreach ( $arguments as $name => $strings ) {	
		list( $premises, $conclusion ) = $strings;
		$summary .= evaluate_argument( $premises, $conclusion, $logicName, $writer, $notation );
	}
	return $summary;
}