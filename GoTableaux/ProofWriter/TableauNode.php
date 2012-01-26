<?php
/**
 * Defines the TableauNode writer class.
 * @package Tableaux
 * @author Douglas Owings
 */
namespace GoTableaux\ProofWriter;

use \GoTableaux\Proof\TableauNode as Node;

/**
 * Writes a Tableau node.
 * @package Tableaux
 * @author Douglas Owings
 */
class TableauNode
{
	/**
	 * Gets an instance.
	 *
	 * @param \GoTableaux\Proof\TableauNode $node The node to write.
	 * @return GoTableaux\ProofWriter\TableauNode The node writer instance.
	 */
	public static function getInstance( Node $node )
	{
		$class = str_replace( '\\Proof\\', '\\ProofWriter\\', get_class( $node ));
		return new $class;
	}
	
	/**
	 * Writes a node.
	 *
	 * @param Node $node The node to write.
	 * @return string The string representation.
	 */
	abstract public function write( Node $node );
}