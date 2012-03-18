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