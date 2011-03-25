<?php

class Sentence_Molecular extends Sentence
{
	
	protected $operator, $operands = array();
	

	function setOperator( Operator $operator )
	{
		$this->operator = $operator;
	}
	function addOperand( $operand )
	{
		if ( is_array( $operand )){
			foreach ( $operand as $sentence ){
				$this->addOperand( $sentence );
			}
		}
		else{
			if ( count( $this->operands ) == $this->operator->getArity() ){
				throw new Exception( 'maximum operands reached' );
			}
			if ( ! $operand instanceof Sentence ){
				throw new Exception( 'Operand must be Sentence object' );
			}
			$this->operands[] = $operand;
		}
	}
	function getOperator()
	{
		return $this->operator;
	}
	function getOperands()
	{
		return $this->operands;
	}
	public function __tostring()
	{
		switch ( $this->operator->getArity() ) {
			case 1:
				return $this->operator->getSymbol() . $this->operands[0];
				break;
			
			case 2:
				return '(' . $this->operands[0] . ' ' . $this->operator->getSymbol() . ' ' . $this->operands[1] . ')';
				break;
				
			default:
				$string = $this->operator->getSymbol() . '(';
				foreach ( $this->operands as $operand ){
					$string .= $operand->__tostring() . '  ';
				}
				$string = trim( $string ) . ')';
				break;
		}
	}
}
?>