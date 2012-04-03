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
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program in file LICENSE.  If not, see <http://www.gnu.org/licenses/>.
 */
?>
<?php $this->start( 'script' ) ?>
	<?= $this->Html->script( 'main' ) ?>
	<?= $this->Html->script( 'http://cloud.github.com/downloads/processing-js/processing-js/processing-1.3.6.min.js' ) ?>
	<?= $this->Html->script( 'processingTableauWriter' ) ?>
<?php $this->end() ?>

<?= $this->Form->create( null ) ?>
<div class="container_12">
	<div class="grid_4">
		<h3>Logic</h3>
		<?= $this->Form->input( 'logic', array( 'type' => 'radio', 'legend' => false )) ?>
	</div>
	<div class="grid_4">
		<h3>Lexicon</h3>
		<div id="Lexicon"></div>
	</div>
	<div class="grid_4">
		<h3>Argument</h3>
		
			<?= $this->Form->label( 'Premises' ) ?>
			<?php foreach( $this->data['premises'] as $key => $premise ): ?>
				<div class="input">
					<input type="text" name="data[premises][<?= $key ?>]" id="premises<?= $key ?>" value="<?= $premise ?>">
				</div>
			<?php endforeach ?>
			<a id="AddPremise" href="javascript:">Add Premise</a>
			<br><br>
			<?= $this->Form->input( 'conclusion' ) ?>
		<?= $this->Form->submit( 'Evaluate' ) ?>
	</div>
	<div class="clear"></div>
	<div class="grid_12">
		<?php if ( !empty( $argumentText )) : ?>
			<?= $argumentText ?>
			<div class="result">
				<span class="<?= $result ?>"><?= ucfirst( $result ) ?></span> in <?= $logicName ?>
			</div>
			<?php if ( !empty( $proofJSON )) : ?>
				<script type="text/javascript">
					var tableau = <?= $proofJSON ?>
				</script>
				<div class="clear"></div>
				<div class="tabs">
					<ul>
						<li><a id="DrawProofCanvas" href="#CanvasDiv">View</a></li>
						<li><a href="#LaTeXDiv">LaTeX Output</a></li>
					</ul>
					<div id="CanvasDiv">
						<canvas id="ProofCanvas"></canvas>
					</div>
					<div id="LaTeXDiv">
						<br>
						<br>
						<textarea class="output"><?= $proofLatex ?></textarea>
					</div>
				</div>
			<?php endif ?>
		<?php endif ?>
	</div>
</div>

<?= $this->Form->end() ?>