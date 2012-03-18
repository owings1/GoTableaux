
( function( $, webroot ) {
	$( document ).ready( function() {
		
		// Update Lexicon on logic change
		$( '#indexForm' ).on( 'click', 'input, a', function() {
			var $me = $(this)
			var id = $me.attr('id')
			var name = $me.attr('name')
			
			if ( name === 'data[logic]' )
				$('#Lexicon').load( webroot + '/logics/get_lexicon/' + $(this).val() )
			else if ( id == 'AddPremise') {
				var newKey = $('input[name^="data[premises]"]', $me.closest( 'form' )).length
				console.log( newKey )
				$me.before( '<div class="input"><input type="text" name="data[premises][' + newKey + ']"></div>' )
			}
				
		})
		// Select first logic as default
		if ( !$( 'input[name="data[logic]"]:checked' ).length )
			$( 'input[name="data[logic]"]:visible:eq(0)' ).prop( 'checked', true )
		
		$( 'input[name="data[logic]"]:checked' ).trigger( 'click' )
	})
	
	
})( jQuery, window.WWW )