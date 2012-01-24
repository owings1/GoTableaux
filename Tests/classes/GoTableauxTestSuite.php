<?php

abstract class GoTableauxTestSuite extends TestSuite 
{
	public $subDirectory;
	
	public function __construct()
	{
		parent::__construct();
		$this->collect( dirname( __FILE__ ) . '/../' . $this->subDirectory, new SimplePatternCollector( '/Test.php/' ));
	}
}