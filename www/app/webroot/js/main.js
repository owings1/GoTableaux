( function( $, webroot ) {
	$( document ).ready( function() {
		
		// Update Lexicon on logic change
		$( '#indexForm' ).on( 'click', 'input[name="data[logic]"]', function() {
			$('#Lexicon').load( webroot + '/logics/get_lexicon/' + $(this).val() )
		})
		// Select first logic as default
		if ( !$( 'input[name="data[logic]"]:checked' ).length )
			$( 'input[name="data[logic]"]:visible:eq(0)' ).prop( 'checked', true )
		
		$( 'input[name="data[logic]"]:checked' ).trigger( 'click' )
	})
	
	
})( jQuery, window.WWW )