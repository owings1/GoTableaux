<?php
function g($a){
	return min( $a, 1 - $a );
}
function c($a){
	return $a - g($a);
}

function n($a){
	return 1 - $a;
}

function conj1($a, $b){
	return c( min( $a, $b ) );
}
function disj($a, $b){
	return n( conj1( n( conj1( $a, $a ) ), n( conj1( $b, $b ) ) ) );
}
function arrow($a, $b){
	return c(max( n($a), $b, g($a) + g($b) ));
}

echo '1 & 1 : ' . conj1(1, 1) . "\n";
echo '1 & .5 : ' . conj1(1, .5) . "\n";
echo '1 & 0 : ' . conj1(1, 0) . "\n";
echo '.5 & 1 : ' . conj1(.5, 1) . "\n";
echo '.5 & .5 : ' . conj1(.5, .5) . "\n";
echo '.5 & 0 : ' . conj1(.5, 0) . "\n";
echo '0 & 1 : ' . conj1(0, 1) . "\n";
echo '0 & .5 : ' . conj1(0, .5) . "\n";
echo '0 & 0 : ' . conj1(0, 0) . "\n\n\n";

echo '1 v 1 : ' . disj(1, 1) . "\n";
echo '1 v .5 : ' . disj(1, .5) . "\n";
echo '1 v 0 : ' . disj(1, 0) . "\n";
echo '.5 v 1 : ' . disj(.5, 1) . "\n";
echo '0 v 1 : ' . disj(0, 1) . "\n";
echo '.5 v .5 : ' . disj(.5, .5) . "\n";
echo '.5 v 0 : ' . disj(.5, 0) . "\n";
echo '0 v .5 : ' . disj(0, .5) . "\n";
echo '0 v 0 : ' . disj(0, 0) . "\n\n\n";

echo '1 -> 1 : ' . arrow(1, 1) . "\n";
echo '1 -> .5 : ' . arrow(1, .5) . "\n";
echo '1 -> 0 : ' . arrow(1, 0) . "\n";
echo '.5 -> 1 : ' . arrow(.5, 1) . "\n";
echo '0 -> 1 : ' . arrow(0, 1) . "\n";
echo '.5 -> .5 : ' . arrow(.5, .5) . "\n";
echo '.5 -> 0 : ' . arrow(.5, 0) . "\n";
echo '0 -> .5 : ' . arrow(0, .5) . "\n";
echo '0 -> 0 : ' . arrow(0, 0) . "\n";

?>