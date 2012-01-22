<?php
/**
 * Classical Logic example of multiple arguments.
 * @package Examples
 * @author Douglas Owings
 */

/**
 * Loads the {@link CPL Classical Propositional Logic} class.
 */
require 'GoTableaux/Logics/CPL/CPL.php';

/**
 * Loads the {@link SetenceParser Sentence parser} classes.
 */
require 'GoTableaux/Logic/Syntax/SentenceParser.php';

/**
 * Loads the {@link TableauWriter Tableau writer} classes.
 */
require 'GoTableaux/Logic/ProofSystem/Tableaux/TableauWriter.php';


// Instantiate the logic, sentence parser, and tableau writer
$logic 			= new CPL;
$parser 		= new StandardSentenceParser;
$tableauWriter 	= new SimpleTableauWriter;

// Create some arguments
$arguments = array(
	'Disjunctive Syllogism' 	=> $logic->parseArgument( array( 'A V B', '~B' ), 'A', $parser ),
	'Affirming a Disjunct'		=> $logic->parseArgument( array( 'A V B', 'A' ), 'B', $parser ),
	'Law of Excluded Middle' 	=> $logic->parseArgument( 'B', 'A V ~A', $parser ),
	'Denying the Antecedent' 	=> $logic->parseArgument( array( 'A > B', '~A' ), 'B', $parser ),
	'Law of Non-contradiction' 	=> $logic->parseArgument( 'A & ~A', 'B', $parser ),
	'Modus Ponens' 				=> $logic->parseArgument( array( 'A > B', 'A' ), 'B', $parser ),
	'Modus Tollens' 			=> $logic->parseArgument( array( 'A > B', '~B' ), '~A', $parser ),
	'DeMorgan 1' 				=> $logic->parseArgument( '~(A V B)', '~A & ~B', $parser ),
	'DeMorgan 2' 				=> $logic->parseArgument( '~(A & B)', '~A V ~B', $parser ),
	'DeMorgan 3' 				=> $logic->parseArgument( '~A & ~B', '~(A V B)', $parser ),
	'DeMorgan 4' 				=> $logic->parseArgument( '~A V ~B', '~(A & B)', $parser ),
);

// Cycle through arguments
foreach ( $arguments as $name => $argument ) {
	// Build a proof for the argument from the logic's proof system
	$tableau = $logic->getProofSystem()
				   	 ->constructProofForArgument( $argument );

	// Print argument name and evaluation 
	echo "$name (" . ($tableau->isValid() ? 'Valid' : 'Invalid') . ")\n\n"; 
	
	// Print argument representation
	echo $tableauWriter->writeArgument( $argument, $logic ) . "\n\n";

	// Print tableau representation
	echo "Tableau:\n" . $tableauWriter->writeTableau( $tableau, $logic ) . "\n";
}
