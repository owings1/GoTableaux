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
		
		
		// Draw proof canvas
		if ( $.isPlainObject( window.tableau )) {
			var canvas = document.getElementById( 'ProofCanvas' )
			var p = new Processing( canvas, tableauProc )
		}
		
		$( '.tabs' ).tabs()
	})
	
	
})( jQuery, window.WWW )

function nodeHasClass( node, className )
{
	return jQuery.inArray( className, node.classes ) !== -1
}