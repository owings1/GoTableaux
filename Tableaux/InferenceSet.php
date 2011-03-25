<?

class Tableuax_InferenceSet
{
	protected $title, $inferences = array();
	
	function __construct( $title, array $inferences = array() )
	{
		if ( ! empty( $inferences )){
			$this->addInference( $inferences );
		}
	}
	function addInference( $inference )
	{
		if ( is_array( $inference )){
			foreach ( $inference as $inf ){
				$this->addInference( $inf );
			}
		}
		else{
			if ( ! $inference instanceof Tableaux_Inference ){
				throw new Exception();
			}
			$this->inferences[] = $inference;
		}
	}
	function getInferences()
	{
		return $this->inferences;
	}
	
}
?>