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
function tableauProc( processing, _options )
{
	var options = {
		tableau: {},
		canvasWidth: 400,
		canvasHeight: 400
	}
	
	jQuery.extend( options, _options )
	
	var drawStructure = function( structure, startX, startY, remainingWidth ) {
		var y = startY + 15
		// Draw nodes.
		$.each( structure.nodes, function( n, node ) {
			var text = ''
			y += 20
			processing.fill( node.isTicked ? 100 : 0 )
			if ( node.sentenceText ) {
				text = node.sentenceText
				if ( node.classes.indexOf( 'modal' ) !== -1 )
					text += ', w' + node.index
			}
			if ( node.classes.indexOf( 'access' ) !== -1 )
				text += 'w' + node.firstIndex + ' R w' + node.secondIndex
			if ( node.classes.indexOf( 'manyValued' ) !== -1 ) 
				text += node.isDesignated ? ' +' : ' -'
			processing.text( text, startX, y )
		})
		if ( structure.isClosed ) {
			y += 20
			processing.text( 'X', startX, y )
			return
		}
		// Draw structures.
		var numNodes = structure.structures.length
		var chunkSize = Math.floor( remainingWidth / numNodes )
		var leftStart = startX - Math.floor(remainingWidth / 2)
		$.each( structure.structures, function( n, struct ) {
			continueX = leftStart + chunkSize * n + Math.floor( chunkSize / 2 )
			continueY = y + 30
			processing.line( startX, y + 15, continueX, continueY + 30)
			drawStructure( struct, continueX, continueY, chunkSize )
		})
	}
	
	processing.setup = function() {
		var font = processing.loadFont( 'arial' )
		processing.size( options.canvasWidth, options.canvasHeight, processing.OPENGL )
		processing.hint( processing.ENABLE_OPENGL_4X_SMOOTH )
		processing.textFont( font, 15 )
		processing.textAlign( processing.CENTER )
		processing.smooth()
		processing.background( 'white' )
	}
	
	processing.draw = function() {
		processing.stroke( 100 )
		drawStructure( options.tableau, Math.floor( options.canvasWidth / 2 ), 0, options.canvasWidth )
		processing.noLoop()
	}	
}