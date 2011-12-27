<?php
/**
 * Defines the tableaux rules for the CPL Tableaux system.
 * @package CPL
 * @author Douglas Owings
 */

/**
 * Represents the tableaux closure rule for CPL.
 * @package CPL
 * @author Douglas Owings
 */
class CPLClosureRule implements ClosureRule
{
	public function doesApply( Branch $branch, TableauxSystem $tableauxSystem )
	{
		foreach ( $branch->getNodes() as $node ) {
			$negatedSentence = $tableauxSystem->negateSentence( $node->getSentence() );
			if ( $branch->hasSentence( $negatedSentence )) return true;
		}
		return false;
	}
}

/**
 * Represents the conjunction rule for CPL.
 * @package CPL
 * @author Douglas Owings
 */
class CPLBranchRule_Conjunction implements BranchRule
{
	public function apply( Branch $branch, TableauxSystem $tableauxSystem )
	{
		$nodes = $branch->getNodesByOperatorName( 'Conjunction', true );
		if ( empty( $nodes )) return false;
		$node = $nodes[0];
		list( $lhs, $rhs ) = $sentence->getOperands();
		$branch->createNode( $lhs )->createNode( $rhs )->tickNode( $node );
		return true;
	}
}

/**
 * Represents the negated conjunction rule for CPL.
 * @package CPL
 * @author Douglas Owings
 */
class CPLBranchRule_NegatedConjunction implements BranchRule
{
	public function apply( Branch $branch, TableauxSystem $tableauxSystem )
	{
		$nodes = $branch->getNodesByTwoOperatorNames( 'Negation', 'Conjunction', true );
		if ( empty( $nodes )) return false;
		$node = $nodes[0];
		list( $operand ) = $node->getSentence()->getOperands();
		list( $lhs, $rhs ) = array_map( array( $tableauxSystem, 'negateSentence' ), $operand->getOperands() );
		$branch->branch()->createNode( $rhs )->tickNode( $node );
		$branch->createNode( $lhs )->tickNode( $node );
		return true;
	}
}

/**
 * Represents the disjunction rule for CPL.
 * @package CPL
 * @author Douglas Owings
 */
class CPLBranchRule_Disjunction implements BranchRule
{
	public function apply( Branch $branch, TableauxSystem $tableauxSystem )
	{
		$nodes = $branch->getNodesByOperatorName( 'Disjunction', true );
		if ( empty( $nodes )) return false;
		$node = $nodes[0];
		list( $lhs, $rhs ) = $node->getSentence()->getOperands();
		$branch->branch()->createNode( $rhs )->tickNode( $node );
		$branch->createNode( $lhs )->tickNode( $node );
		return true;
	}
}

/**
 * Represents the negated disjunction rule for CPL.
 * @package CPL
 * @author Douglas Owings
 */
class CPLBranchRule_NegatedDisjunction implements BranchRule
{
	public function apply( Branch $branch, TableauxSystem $tableauxSystem )
	{
		$nodes = $branch->getNodesByTwoOperatorNames( 'Negation', 'Disjunction', true );
		if ( empty( $nodes )) return false;
		$node = $nodes[0];
		list( $operand ) = $node->getSentence()->getOperands();
		list( $lhs, $rhs ) = array_map( array( $tableauxSystem, 'negateSentence' ), $operand->getOperands() );
		$branch->createNode( $lhs )->createNode( $rhs )->tickNode( $node );
		return true;
	}
}

/**
 * Represents the material conditional rule for CPL.
 * @package CPL
 * @author Douglas Owings
 */
class CPLBranchRule_MaterialConditional implements BranchRule
{
	public function apply( Branch $branch, TableauxSystem $tableauxSystem )
	{
		$nodes = $branch->getNodesByOperatorName( 'Material Conditional', true );
		if ( empty( $nodes )) return false;
		$node = $nodes[0];
		list( $antecedent, $rhs ) = $node->getSentence()->getOperands();
		$lhs = $tableauxSystem->negateSentence( $antecedent );
		$branch->branch()->createNode( $rhs )->tickNode( $node );
		$branch->createNode( $lhs )->tickNode( $node );
		return true;
	}
}

/**
 * Represents the negated material conditional rule for CPL.
 * @package CPL
 * @author Douglas Owings
 */
class CPLBranchRule_NegatedMaterialConditional implements BranchRule
{
	public function apply( Branch $branch, TableauxSystem $tableauxSystem )
	{
		$nodes = $branch->getNodesByTwoOperatorNames( 'Negation', 'Material Conditional', true );
		if ( empty( $nodes )) return false;
		$node = $nodes[0];
		list( $operand ) = $node->getSentence()->getOperands();
		list( $lhs, $consequent ) = $operand->getOperands();
		$rhs = $tableauxSystem->negateSentence( $consequent );
		$branch->createNode( $lhs )->createNode( $rhs )->tickNode( $node );
		return true;
	}
}

/**
 * Represents the material biconditional rule for CPL.
 * @package CPL
 * @author Douglas Owings
 */
class CPLBranchRule_MaterialBiconditional implements BranchRule
{
	public function apply( Branch $branch, TableauxSystem $tableauxSystem )
	{
		$nodes = $branch->getNodesByOperatorName( 'Material Biconditional', true );
		if ( empty( $nodes )) return false;
		$node = $nodes[0];
		list( $lhs, $rhs ) = $node->getSentence()->getOperands();
		$negatedLhs = $tableauxSystem->negateSentence( $lhs );
		$negatedRhs = $tableauxSystem->negateSentence( $rhs );
		$branch->branch()->createNode( $negatedLhs )->createNode( $negatedRhs )->tickNode( $node );
		$branch->createNode( $lhs )->createNode( $rhs )->tickNode( $node );
		return true;
	}
}

/**
 * Represents the negated material biconditional rule for CPL.
 * @package CPL
 * @author Douglas Owings
 */
class CPLBranchRule_NegatedMaterialBiconditional implements BranchRule
{
	public function apply( Branch $branch, TableauxSystem $tableauxSystem )
	{
		$nodes = $branch->getNodesByTwoOperatorNames( 'Negation', 'Material Biconditional', true );
		if ( empty( $nodes )) return false;
		$node = $nodes[0];
		list( $operand ) = $node->getSentence()->getOperands();
		list( $lhs, $rhs ) = $operand->getOperands();
		$negatedLhs = $tableauxSystem->negateSentence( $lhs );
		$negatedRhs = $tableauxSystem->negateSentence( $rhs );
		$branch->branch()->createNode( $negatedLhs )->createNode( $rhs )->tickNode( $node );
		$branch->createNode( $lhs )->createNode( $negatedRhs )->tickNode( $node );
		return true;
	}
}

/**
 * Represents the double negation rule for CPL.
 * @package CPL
 * @author Douglas Owings
 */
class CPLBranchRule_DoubleNegation implements BranchRule
{
	public function apply( Branch $branch, TableauxSystem $tableauxSystem )
	{
		$nodes = $branch->getNodesByTwoOperatorNames( 'Negation', 'Negation', true );
		if ( empty( $nodes )) return false;
		$node = $nodes[0];
		list( $operand ) = $node->getSentence()->getOperands();
		list( $reduction ) = $operand->getOperands();
		$branch->createNode( $reduction )->tickNode( $node );
		return true;
	}
}