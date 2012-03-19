
int canvasWidth = 1000;
int canvasHeight = 1000;

void setup() {
    size( canvasWidth, canvasHeight, OPENGL );
	//size( canvasWidth, canvasHeight );
    background( 255 );
	fill( 0 );
	hint(ENABLE_OPENGL_4X_SMOOTH);
	PFont fontArial = loadFont( 'arial' ); 
	textFont( fontArial, 15 ); 
}

void draw() {
	stroke( 100 );
	drawStructure( tableau, (int) canvasWidth / 2, 0, canvasWidth );
	noLoop();
}

void drawStructure( structure, startX, startY, remainingWidth ) {
	int y = startY + 15;
	$.each( structure.nodes, function( n, node ) {
		y += 20;
		smooth();
		textAlign( CENTER );
		if ( node.isTicked ) {
			fill( 100 );
		} else {
			fill( 0 );
		}
		if ( node.sentenceText ) {
			String sentenceText = node.sentenceText;
			
			if ( node.classes.indexOf( 'manyValued' ) !== -1 ) {
				sentenceText += node.isDesignated ? ' +' : ' -';
			}
			text( sentenceText, startX, y);
		}
		
	});
	if ( structure.isClosed ) {
		y += 20;
		text( 'X', startX, y );
	}
	int numNodes = structure.structures.length;
	int chunkSize = (int) remainingWidth / numNodes;
	int leftStart = startX - (int) (remainingWidth / 2);
	$.each( structure.structures, function( n, struct ) {
		continueX = leftStart + chunkSize * n + (int) chunkSize / 2;
		continueY = y + 30;
		line( startX, y + 15, continueX, continueY + 30);
		drawStructure( struct, continueX, continueY, chunkSize );
	})
}