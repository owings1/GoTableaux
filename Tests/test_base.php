<?php

namespace GoTableaux\Test;

require_once dirname( __FILE__ ) . '/simpletest/autorun.php';
require_once dirname( __FILE__ ) . '/classes/TestSuite.php';

class BaseTest extends TestSuite 
{
	public $subDirectory = 'Logic';
}