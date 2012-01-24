<?php
require_once dirname( __FILE__ ) . '/simpletest/autorun.php';
require_once dirname( __FILE__ ) . '/classes/GoTableauxTestSuite.php';

class BaseTest extends GoTableauxTestSuite 
{
	public $subDirectory = 'Logic';
}