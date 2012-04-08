<?php
/**
 * GoTableaux. A multi-logic tableaux generator.
 * Copyright (C) 2012  Douglas Owings
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed WITHOUT ANY WARRANTY. 
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/agpl-3.0.html>.
 */
?>
<dl>
	<h4>Atomic Symbols:</h4>
	
		<ul>
			<?php foreach ( $lexicon['atomicSymbols'] as $symbol ): ?>
				<li><?= $symbol ?></li>
			<?php endforeach ?>
		</ul>
	
	<h4>Operator Symbols:</h4>
	
		<ul>
			<?php foreach ( $lexicon['operatorSymbols'] as $symbol => $arity ): ?>
				<li><?= $symbol ?></li>
			<?php endforeach ?>
		</ul>
	
	<h4>Subscript Symbol:</h4>
	
		<ul>
			<?php foreach ( $lexicon['subscriptSymbols'] as $symbol ): ?>
				<li><?= $symbol ?></li>
			<?php endforeach ?>
		</ul>
	
	<h4>Open Parenthesis:</h4>
	
		<ul>
			<?php foreach ( $lexicon['openMarks'] as $mark ): ?>
				<li><?= $mark ?></li>
			<?php endforeach ?>
		</ul>
	
	<h4>Close Parenthesis:</h4>
	
		<ul>
			<?php foreach ( $lexicon['closeMarks'] as $mark ): ?>
				<li><?= $mark ?></li>
			<?php endforeach ?>
		</ul>
	
</dl>