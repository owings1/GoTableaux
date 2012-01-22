<?php
/**
 * Basic FDE example.
 * @package Examples
 * @author Douglas Owings
 */

/**
 * Loads the {@link FDE FDE Logic} class.
 */
require 'GoTableaux/Logics/FDE/FDE.php';

/**
 * Loads the {@link SetenceParser Sentence parser} classes.
 */
require 'GoTableaux/Logic/Syntax/SentenceParser.php';

/**
 * Loads the {@link TableauWriter Tableau writer} classes.
 */
require 'GoTableaux/Logic/ProofSystem/Tableaux/TableauWriter.php';


// Instantiate the logic, sentence parser, and tableau writer
$logic 			= new FDE;
$parser 		= new StandardSentenceParser;
$tableauWriter 	= new SimpleTableauWriter;

// Create an argument
$argument = $logic->parseArgument( null, 'A V ~A', $parser );

// Build a proof for the argument from the logic's proof system
$tableau = $logic->getProofSystem()
			   	 ->constructProofForArgument( $argument );

// Print argument representation
echo "Argument:\n" . $tableauWriter->writeArgument( $argument, $logic ) . "\n";

// Print tableau representation
echo "Tableau:\n" . $tableauWriter->writeTableau( $tableau, $logic ) . "\n";

// Print evaluation
echo 'Result: ' . ($tableau->isValid() ? 'Valid' : 'Invalid');