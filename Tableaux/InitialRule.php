<?php

abstract class Tableaux_InitialRule
{
	abstract function apply( Argument $argument );	/*	returns array Branches or false */
}
?>