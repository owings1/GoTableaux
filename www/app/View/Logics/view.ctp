<h2><strong><?= $this->Inflect->human( $logicName ) ?></strong></h2> 
<hr>
<h2>Tableaux Rules</h2>
<? foreach ( $rules as $rule ): ?>
	<div class="rule">
	    <h3><?= $this->Inflect->human( $rule['name'] ) ?></h3>
		<? if ( !empty( $rule['conditions'] )) : ?>
			<h4>Node Conditions</h4>
			<dl>
				<? foreach ( $rule['conditions'] as $name => $value ) : ?>
					<dt><?= $this->Inflect->human( $name ) ?></dt>
					<dd><?= $this->Inflect->varString( $value ) ?></dt>
				<? endforeach ?>
			</dl>
		<? endif ?>
		<? if ( !empty( $rule['tableauJSON'] )) : ?>
			<h4>Example</h4>
			<div style="display:none;">
				<canvas class="example" name="<?= $rule['class'] ?>"></canvas>
			</div>
		<? endif ?>
	</div>
<? endforeach ?>
<script type="text/javascript">
	$( document ).ready( function() {
		
		var p, $canvas, tableau
		
		<? foreach ( $rules as $rule ) : ?>
			<? if ( !empty( $rule['tableauJSON'] )) : ?>
			
				$canvas = $('canvas[name="<?= $rule['class'] ?>"]')
				$canvas.parent().show()
				tableau = <?= $rule['tableauJSON'] ?>
				
				p = new Processing( $canvas.get(0), function( processing ) { 
					tableauProc( processing, {
						tableau: tableau,
						canvasHeight: 200,
						canvasWidth: 300
					})
				}) 
			<? endif ?>
		<? endforeach ?>
		
	})
</script>