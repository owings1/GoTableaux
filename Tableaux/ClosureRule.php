<?php

abstract class Tableaux_ClosureRule
{
	abstract function apply( Tableaux_Branch $branch ); // if applicable $branch->close(); returns boolean
}
?>