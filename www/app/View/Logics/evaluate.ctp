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
<?= $this->Form->create( null ) ?>
<div class="container_12">
	<h2>Logic Tableaux Generator</h2>
	
	<div class="grid_4">
		<h3>Select Logic</h3>
		<div class="input_radio">
			<? foreach ( $logics as $i => $logic ) : ?>
				<input id="Logic<?= $i ?>" type="radio" value="<?= $i ?>" name="data[logic]"<?= isset( $this->data['logic'] ) && $this->data['logic'] == $i ? ' checked="checked"' : '' ?>>
				<label for="Logic<?= $i ?>">
					<?= $this->Html->link( $this->Inflect->human( $logic ), array( 'action' => 'view', $logic )) ?>
				</label>
			<? endforeach ?>
		</div>
	</div>
	<div class="grid_4">
		<h3>Lexicon</h3>
		<?= $this->Form->input( 'parse_notation', array( 'label' => 'Parse notation', 'options' => $parse_notations, 'value' => isset( $this->data['parse_notation'] ) ? $this->data['parse_notation'] : 0 )) ?>
		<div id="Lexicon"></div>
	</div>
	<div class="grid_4">
		<h3>Argument</h3>
		
			<?= $this->Form->label( 'Premises' ) ?>
			<? foreach( $this->data['premises'] as $key => $premise ): ?>
				<div class="input">
					<input type="text" name="data[premises][<?= $key ?>]" id="premises<?= $key ?>" value="<?= $premise ?>">
				</div>
			<? endforeach ?>
			<a id="AddPremise" href="javascript:">Add Premise</a>
			<br><br>
			<?= $this->Form->input( 'conclusion' ) ?>
			<?= $this->Form->input( 'write_notation', array( 'label' => 'Output notation', 'options' => $write_notations, 'value' => isset( $this->data['write_notation'] ) ? $this->data['write_notation'] : 0 )) ?>
		<?= $this->Form->end( 'Generate Tableau' ) ?>
	</div>
	<div class="clear"></div>
	<div class="grid_12">
		<? if ( !empty( $argumentText )) : ?>
			<?= $argumentText ?>
			<div class="result">
				<span class="<?= $result ?>"><?= ucfirst( $result ) ?></span> in <?= $this->Inflect->human( $logicName ) ?>
			</div>
			<? if ( !empty( $proofJSON )) : ?>
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
						<?= $this->Form->create( null, array( 'action' => 'view_pdf' )) ?>
							<? foreach( $this->data['premises'] as $key => $premise ): ?>
								<input type="hidden" name="data[premises][<?= $key ?>]" id="premises<?= $key ?>" value="<?= $premise ?>">
							<? endforeach ?>
							<?= $this->Form->hidden( 'logic' ) ?>
							<?= $this->Form->hidden( 'conclusion' ) ?>
							<?= $this->Form->hidden( 'parse_notation' ) ?>
							<?= $this->Form->hidden( 'write_notation' ) ?>
							<textarea name="data[latex]" class="output"><?= $proofLatex ?></textarea>
						<?= $this->Form->end( 'View PDF' ) ?>
						
					</div>
				</div>
			<? endif ?>
		<? endif ?>
	</div>
</div>

<?= $this->Form->end() ?>

<script type="text/javascript">
	$(document).ready( function() {
		var lexUrl = '<?= $this->Html->url("/logics/get_lexicon") ?>'
		
		function updateLexicon()
		{
			var logicId = $('input[name="data[logic]"]:checked').val()
			var notationId = $('select[name="data[parse_notation]"]').val()
			$('#Lexicon').load( lexUrl + '/' + logicId + '/' + notationId )
		}
		
		$( '#evaluateForm' ).on( 'click', 'input, a', function() {
			var $me = $(this)
			var id = $me.attr('id')
			var name = $me.attr('name')
			
			//  Update Lexicon on logic change
			if ( name === 'data[logic]' ) {
				updateLexicon()
			}
			//  Add premise input box
			else if ( id == 'AddPremise') {
				var newKey = $('input[name^="data[premises]"]', $me.closest( 'form' )).length
				console.log( newKey )
				$me.before( '<div class="input"><input type="text" name="data[premises][' + newKey + ']"></div>' )
			}
				
		})
		
		$('select[name="data[parse_notation]"]').on( 'change', updateLexicon )
		
		// Select first logic as default
		if ( !$( 'input[name="data[logic]"]:checked' ).length )
			$( 'input[name="data[logic]"]:visible:eq(0)' ).prop( 'checked', true )
		
		updateLexicon()
		
		<? if ( !empty( $proofJSON )) : ?>
			// Draw proof canvas
			var canvas = document.getElementById( 'ProofCanvas' )
			var tableau = <?= $proofJSON ?>
			
			var p = new Processing( canvas, function( processing ) { 
				tableauProc( processing, {
					tableau: tableau,
					canvasHeight: 900,
					canvasWidth: 800
				}) 
			})
		<? endif ?>
		$( '.tabs' ).tabs()
	})
	
</script>