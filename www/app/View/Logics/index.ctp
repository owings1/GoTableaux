<?php $this->start( 'script' ) ?>
	<?= $this->Html->script( 'main' ) ?>
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
			<div class="input">
				<input type="text" name="data[premises][0]" id="premises0">
			</div>
			<div class="input">
				<input type="text" name="data[premises][1]" id="premises1">
			</div>
			<?= $this->Form->input( 'conclusion' ) ?>
		<?= $this->Form->submit( 'Evaluate' ) ?>
	</div>
</div>

<?= $this->Form->end() ?>