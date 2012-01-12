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
	public function doesApply( Branch $branch, Logic $logic )
	{
		foreach ( $branch->getNodes() as $node ) {
			$negated = $logic->negate( $node->getSentence() );
			if ( $branch->hasSentence( $negated )) return true;
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
	public function apply( Branch $branch, Logic $logic )
	{
		$nodes = $branch->getNodesByOperatorName( 'Conjunction', true );
		if ( empty( $nodes )) return false;
		$node = $nodes[0];
		
		list( $leftConjunct, $rightConjunct ) = $node->getSentence()->getOperands();
		$branch->createNode( $leftConjunct )
			   ->createNode( $rightConjunct )
			   ->tickNode( $node );
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
	public function apply( Branch $branch, Logic $logic )
	{
		$nodes = $branch->getNodesByTwoOperatorNames( 'Negation', 'Conjunction', true );
		if ( empty( $nodes )) return false;
		$node = $nodes[0];
		
		list( $negatum ) = $node->getSentence()->getOperands();
		list( $leftConjunct, $rightConjunct ) = $negatum->getOperands();
		$branch->branch()
			   ->createNode( $logic->negate( $leftConjunct ))
			   ->tickNode( $node );
		$branch->createNode( $logic->negate( $rightConjunct ))
			   ->tickNode( $node );
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
	public function apply( Branch $branch, Logic $logic )
	{
		$nodes = $branch->getNodesByOperatorName( 'Disjunction', true );
		if ( empty( $nodes )) return false;
		$node = $nodes[0];
		
		list( $leftDisjunct, $rightDisjunct ) = $node->getSentence()->getOperands();
		$branch->branch()
			   ->createNode( $leftDisjunct )
			   ->tickNode( $node );
		$branch->createNode( $rightDisjunct )
		       ->tickNode( $node );
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
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByTwoOperatorNames( 'Negation', 'Disjunction', true )) 
			return false;
		$node = $nodes[0];
		
		list( $negatum ) = $node->getSentence()->getOperands();
		list( $leftDisjunct, $rightDisjunct ) = $negatum->getOperands();
		$branch->createNode( $logic->negate( $leftDisjunct ))
			   ->createNode( $logic->negate( $rightDisjunct ))
			   ->tickNode( $node );
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
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByOperatorName( 'Material Conditional', true ))
			return false;
		$node = $nodes[0];
		
		list( $antecedent, $consequent ) = $node->getSentence()->getOperands();
		
		$branch->branch()
			   ->createNode( $logic->negate( $antecedent ))
			   ->tickNode( $node );
		
		$branch->createNode( $consequent )
			   ->tickNode( $node );
		
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
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByTwoOperatorNames( 'Negation', 'Material Conditional', true ))
			return false;
		$node = $nodes[0];
		
		list( $negatum ) = $node->getSentence()->getOperands();
		list( $antecedent, $consequent ) = $negatum->getOperands();
		
		$branch->createNode( $antecedent )
			   ->createNode( $logic->negate( $consequent ))
			   ->tickNode( $node );
		
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
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByOperatorName( 'Material Biconditional', true ))
			return false;
		$node = $nodes[0];
		
		list( $lhs, $rhs ) = $node->getSentence()->getOperands();
		
		$branch->branch()
			   ->createNode( $logic->negate( $lhs ))
			   ->createNode( $logic->negate( $rhs ))
			   ->tickNode( $node );
		
		$branch->createNode( $lhs )
			   ->createNode( $rhs )
			   ->tickNode( $node );
			
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
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByTwoOperatorNames( 'Negation', 'Material Biconditional', true ))
			return false;
		$node = $nodes[0];
		
		list( $negatum ) = $node->getSentence()->getOperands();
		list( $lhs, $rhs ) = $negatum->getOperands();
		
		$branch->branch()
			   ->createNode( $logic->negate( $lhs ))
			   ->createNode( $rhs )
			   ->tickNode( $node );
		
		$branch->createNode( $lhs )
			   ->createNode( $logic->negate( $rhs ))
			   ->tickNode( $node );
			
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
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByTwoOperatorNames( 'Negation', 'Negation', true ))
			return false;
		$node = $nodes[0];
		
		list( $singleNegatum ) = $node->getSentence()->getOperands();
		list( $doubleNegatum ) = $singleNegatum->getOperands();
		
		$branch->createNode( $doubleNegatum )
			   ->tickNode( $node );
		
		return true;
	}
}