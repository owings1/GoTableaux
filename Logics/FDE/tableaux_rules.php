<?php
/**
 * Defines the tableaux rules for the FDE tableaux system.
 * @package FDE
 * @author Douglas Owings
 */

/**
 * Represents the FDE closure rule.
 * @package FDE
 * @author Douglas Owings
 */
class FDEClosureRule implements ClosureRule
{
	public function doesApply( Branch $branch, Logic $logic )
	{
		foreach ( $branch->getDesignatedNodes() as $node ) {
			$sentence = $node->getSentence();
			if ( $branch->hasSentenceWithDesignation( $sentence, false )) return true;
			
			$negated = $logic->negate( $sentence );
			if ( $branch->hasSentenceWithDesignation( $negated, true )) return true;
		}
		return false;
	}
}

class FDEBranchRule_ConjunctionDesignated implements BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByOperatorNameAndDesignation( 'Conjunction', true ))
			return false;
		$node = $nodes[0];
		
		list( $leftConjunct, $rightConjunct ) = $node->getSentence()->getOperands();
		
		$branch->createNodeWithDesignation( $leftConjunct, true )
			   ->createNodeWithDesignation( $rightConjunct, true )
			   ->tickNode( $node );
		
		return true;
	}
}

class FDEBranchRule_NegatedConjunctionDesignated implements BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByTwoOperatorNamesAndDesignation( 'Negation', 'Conjunction', true ))
			return false;
		$node = $nodes[0];
		
		list( $negatum ) = $node->getSentence()->getOperands();
		list( $leftConjunct, $rightConjunct ) = $negatum->getOperands();
		
		$branch->branch()
			   ->createNodeWithDesignation( $logic->negate( $leftConjunct ), true )
			   ->tickNode( $node );
			
		$branch->createNodeWithDesignation( $logic->negate( $rightConjunct ), true )
			   ->tickNode( $node );
		
		return true;
	}
}

class FDEBranchRule_ConjunctionUndesignated implements BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByOperatorNameAndDesignation( 'Conjunction', false ))
			return false;
		$node = $nodes[0];
		
		list( $leftConjunct, $rightConjunct ) = $node->getSentence()->getOperands();
		
		$branch->branch()
			   ->createNodeWithDesignation( $leftConjunct, false )
			   ->tickNode( $node );
			
		$branch->createNodeWithDesignation( $rightConjunct, false )
			   ->tickNode( $node );
		
		return true;
	}
}

class FDEBranchRule_NegatedConjunctionUndesignated implements BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByTwoOperatorNamesAndDesignation( 'Negation', 'Conjunction', false ))
			return false;
		$node = $nodes[0];
		
		list( $negatum ) = $node->getSentence()->getOperands();
		list( $leftConjunct, $rightConjunct ) = $negatum->getOperands();
		
		$branch->createNodeWithDesignation( $logic->negate( $leftConjunct ), false )
  			   ->createNodeWithDesignation( $logic->negate( $rightConjunct ), false )
			   ->tickNode( $node );

		return true;
	}
}

class FDEBranchRule_DisjunctionDesignated implements BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByOperatorNameAndDesignation( 'Disjunction', true ))
			return false;
		$node = $nodes[0];
		
		list( $leftDisjunct, $rightDisjunct ) = $node->getSentence()->getOperands();
		
		$branch->branch()
			   ->createNodeWithDesignation( $leftDisjunct, true )
			   ->tickNode( $node );
			
		$branch->createNodeWithDesignation( $rightDisjunct, true )
			   ->tickNode( $node );
		
		return true;
	}
}

class FDEBranchRule_DisjunctionUndesignated implements BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByOperatorNameAndDesignation( 'Disjunction', false ))
			return false;
		$node = $nodes[0];
		
		list( $leftDisjunct, $rightDisjunct ) = $node->getSentence()->getOperands();
		
		$branch->createNodeWithDesignation( $leftDisjunct, false )
  			   ->createNodeWithDesignation( $rightDisjunct, false )
			   ->tickNode( $node );

		return true;
	}
}

class FDEBranchRule_NegatedDisjunctionDesignated implements BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByTwoOperatorNamesAndDesignation( 'Negation', 'Disjunction', true ))
			return false;
		$node = $nodes[0];
		
		list( $negatum ) = $node->getSentence()->getOperands();
		list( $leftDisjunct, $rightDisjunct ) = $negatum->getOperands();
		
		$branch->createNodeWithDesignation( $logic->negate( $leftDisjunct ), true )
  			   ->createNodeWithDesignation( $logic->negate( $rightDisjunct ), true )
			   ->tickNode( $node );

		return true;
	}
}

class FDEBranchRule_NegatedDisjunctionUndesignated implements BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByTwoOperatorNamesAndDesignation( 'Negation', 'Disjunction', false ))
			return false;
		$node = $nodes[0];
		
		list( $negatum ) = $node->getSentence()->getOperands();
		list( $leftDisjunct, $rightDisjunct ) = $negatum->getOperands();
		
		$branch->branch()
			   ->createNodeWithDesignation( $logic->negate( $leftDisjunct ), false )
			   ->tickNode( $node );
			
  		$branch->createNodeWithDesignation( $logic->negate( $rightDisjunct ), false )
			   ->tickNode( $node );

		return true;
	}
}

class FDEBranchRule_MaterialConditionalDesignated implements BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByOperatorNameAndDesignation( 'Material Conditional', true ))
			return false;
		$node = $nodes[0];

		list( $antecdent, $consequent ) = $node->getSentence()->getOperands();
		
		$branch->branch()
			   ->createNodeWithDesigation( $logic->negate( $antecedent ), true )
			   ->tickNode( $node );
			
		$branch->createNodeWithDesignation( $consequent, true )
			   ->tickNode( $node );
		
		return true;
	}
}

class FDEBranchRule_MaterialConditionalUndesignated implements BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByOperatorNameAndDesignation( 'Material Conditional', false ))
			return false;
		$node = $nodes[0];

		list( $antecdent, $consequent ) = $node->getSentence()->getOperands();
		
		$branch->createNodeWithDesignation( $logic->negate( $antecedent ), false )
  			   ->createNodeWithDesignation( $consequent, false )
			   ->tickNode( $node );
			
		return true;
	}
}

class FDEBranchRule_NegatedMaterialConditionalDesignated implements BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByTwoOperatorNamesAndDesignation( 'Negation', 'Material Conditional', true ))
			return false;
		$node = $nodes[0];

		list( $negatum ) = $node->getSentence()->getOperands();
		list( $antecedent, $consequent ) = $negatum->getOperands();
		
		$branch->createNodeWithDesignation( $antecedent, true )
  			   ->createNodeWithDesignation( $logic->negate( $consequent ), true )
			   ->tickNode( $node );

		return true;
	}
}

class FDEBranchRule_NegatedMaterialConditionalUndesignated implements BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByTwoOperatorNamesAndDesignation( 'Negation', 'Material Conditional', false ))
			return false;
		$node = $nodes[0];

		list( $negatum ) = $node->getSentence()->getOperands();
		list( $antecedent, $consequent ) = $negatum->getOperands();
		
		$branch->branch()
			   ->createNodeWithDesignation( $antecedent, false )
			   ->tickNode( $node );
			
  		$branch->createNodeWithDesignation( $logic->negate( $consequent ), false )
			   ->tickNode( $node );

		return true;
	}
}

class FDEBranchRule_MaterialBiconditionalDesignated implements BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByOperatorNameAndDesignation( 'Material Biconditional', true ))
			return false;
		$node = $nodes[0];

		list( $lhs, $rhs ) = $node->getSentence()->getOperands();

		$branch->branch()
			   ->createNodeWithDesigation( $logic->negate( $lhs ), true )
			   ->createNodeWithDesigation( $logic->negate( $rhs ), true )
			   ->tickNode( $node );
			
		$branch->createNodeWithDesignation( $lhs, true )
			   ->createNodeWithDesignation( $rhs, true )
			   ->tickNode( $node );
			
		return true;
	}
}

class FDEBranchRule_MaterialBiconditionalUndesignated implements BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByOperatorNameAndDesignation( 'Material Biconditional', false ))
			return false;
		$node = $nodes[0];

		list( $lhs, $rhs ) = $node->getSentence()->getOperands();

		$branch->branch()
			   ->createNodeWithDesigation( $logic->negate( $lhs ), false )
			   ->createNodeWithDesigation( $rhs, false )
			   ->tickNode( $node );
			
		$branch->createNodeWithDesignation( $lhs, false )
			   ->createNodeWithDesignation( $logic->negate( $rhs ), false )
			   ->tickNode( $node );
			
		return true;
	}
}

class FDEBranchRule_NegatedMaterialBiconditionalDesignated implements BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByTwoOperatorNamesAndDesignation( 'Negation', 'Material Biconditional', true ))
			return false;
		$node = $nodes[0];

		list( $negatum ) = $node->getSentence()->getOperands();
		list( $lhs, $rhs ) = $negatum->getOperands();

		$branch->branch()
			   ->createNodeWithDesigation( $lhs, true )
			   ->createNodeWithDesigation( $logic->negate( $rhs ), true )
			   ->tickNode( $node );
			
		$branch->createNodeWithDesignation( $logic->negate( $lhs ), true )
			   ->createNodeWithDesignation( $rhs, true )
			   ->tickNode( $node );
			
		return true;
	}
}

class FDEBranchRule_NegatedMaterialBiconditionalUndesignated implements BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByTwoOperatorNamesAndDesignation( 'Negation', 'Material Biconditional', false ))
			return false;
		$node = $nodes[0];

		list( $negatum ) = $node->getSentence()->getOperands();
		list( $lhs, $rhs ) = $negatum->getOperands();

		$branch->branch()
			   ->createNodeWithDesigation( $logic->negate( $lhs ), false )
			   ->createNodeWithDesigation( $logic->negate( $rhs ), false )
			   ->tickNode( $node );
			
		$branch->createNodeWithDesignation( $lhs, false )
			   ->createNodeWithDesignation( $rhs, false )
			   ->tickNode( $node );
			
		return true;
	}
}

class FDEBranchRule_DoubleNegationDesignated implements BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByTwoOperatorNamesAndDesignation( 'Negation', 'Negation', true ))
			return false;
		$node = $nodes[0];

		list( $negatum ) = $node->getSentence()->getOperands();
		list( $doubleNegatum ) = $negatum->getOperands();

		$branch->createNodeWithDesignation( $doubleNegatum, true )
			   ->tickNode( $node );
			
		return true;
	}
}

class FDEBranchRule_DoubleNegationUndesignated implements BranchRule
{
	public function apply( Branch $branch, Logic $logic )
	{
		if ( !$nodes = $branch->getNodesByTwoOperatorNamesAndDesignation( 'Negation', 'Negation', false ))
			return false;
		$node = $nodes[0];

		list( $negatum ) = $node->getSentence()->getOperands();
		list( $doubleNegatum ) = $negatum->getOperands();

		$branch->createNodeWithDesignation( $doubleNegatum, false )
			   ->tickNode( $node );

		return true;
	}
}