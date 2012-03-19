/**
 * @author Douglas Owings
 */

function tableauProc( processing )
{
	var canvasWidth = 900;
	var canvasHeight = 800;
	
	var drawStructure = function( structure, startX, startY, remainingWidth ) {
		var y = startY + 15
		// Draw nodes.
		$.each( structure.nodes, function( n, node ) {
			var sentenceText
			y += 20
			processing.fill( node.isTicked ? 100 : 0 )
			if ( node.sentenceText ) {
				sentenceText = node.sentenceText;
				if ( node.classes.indexOf( 'manyValued' ) !== -1 ) 
					sentenceText += node.isDesignated ? ' +' : ' -';
				processing.text( sentenceText, startX, y )
			}
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
		processing.size( canvasWidth, canvasHeight, processing.OPENGL )
		processing.hint( processing.ENABLE_OPENGL_4X_SMOOTH )
		processing.textFont( font, 15 )
		processing.textAlign( processing.CENTER )
		processing.smooth()
	}
	
	processing.draw = function() {
		processing.stroke( 100 )
		drawStructure( window.tableau, Math.floor( canvasWidth / 2 ), 0, canvasWidth )
		processing.noLoop()
	}	
}