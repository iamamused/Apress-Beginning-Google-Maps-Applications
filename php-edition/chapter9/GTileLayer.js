var myTiles = new GTileLayer(new GCopyrightCollection(),0,10);
	myTiles.getTileUrl = function(tile,zoom){
	return 'http://example.com/tiles/' + zoom + '.' + tile.x + '.' + tile.y + '.png';
};
myTiles.isPng = function() { return true; }
myTiles.getOpacity = function() { return 1.0; }