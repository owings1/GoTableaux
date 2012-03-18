
int canvasWidth = 942;
int canvasHeight = 1000;

void setup() {
    size( canvasWidth, canvasHeight );
    background( 255 );
	
}

void draw() {
	stroke( 200 );
	drawStructure( tableau, (int) canvasWidth / 2, 0, canvasWidth );
}

void drawStructure( structure, startX, startY, remainingWidth ) {
	int y = startY;
	$.each( structure.nodes, function( n, node ) {
		y += 40;
		ellipse( startX, y, 10, 10);
	});
	int numNodes = structure.structures.length;
	int chunkSize = (int) remainingWidth / numNodes;
	int leftStart = startX - (int) (remainingWidth / 2);
	$.each( structure.structures, function( n, struct ) {
		continueX = leftStart + chunkSize * n + (int) chunkSize / 2;
		continueY = y + 40;
		line( startX, y + 5, continueX, continueY + 40 );
		drawStructure( struct, continueX, continueY, chunkSize );
	})
}