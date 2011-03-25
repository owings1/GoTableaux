<?php
require_once 'Doug/SimpleNotifier.php';
abstract class Tableaux_Rule
{
	abstract function apply( Tableaux_Branch $branch );	/*	returns array Branches or false */
}
?>