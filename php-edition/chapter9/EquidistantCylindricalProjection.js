EquidistantCylindricalProjection = new GProjection();
EquidistantCylindricalProjection.mapResolutions = [256,512,1024]
EquidistantCylindricalProjection.fromLatLngToPixel = function(latlng,zoom) {
	var lng = parseInt(Math.floor((this.mapResolutions[zoom] / 360) *?
	(latlng.lng() + 180)));
	var lat = parseInt(Math.floor(Math.abs((this.mapResolutions[zoom] / 2 / 180) *?
	(latlng.lat()-90))));
	var point = new GPoint(lng,lat);
	return point;
}
EquidistantCylindricalProjection.fromPixelToLatLng =?
function(pixel,zoom,unbounded) {
	var lat = 90-(pixel.y / (this.mapResolutions[zoom] / 2 / 180));
	var lng = (pixel.x / (this.mapResolutions[zoom] / 360)) - 180;

	var latlng = new GLatLng(lat,lng);
	return latlng;
}
EquidistantCylindricalProjection.tileCheckRange = function(tile,zoom,tileSize){
	var rez = this.mapResolutions[zoom];
	//check if it is outside the latitude range
	//the height for the Blue Marble maps are always 1/2 the width
	if(tile.y < 0 || tile.y * tileSize >= rez / 2){ return false; }
	//check if it is outside the longitude range and if so, wrap the map
	//by adjusting tile x
	if(tile.x < 0 || tile.x * tileSize >= rez){
		var e = Math.floor( rez / tileSize );
		tile.x = tile.x % e;
		if(tile.x < 0){ tile.x += e; }
	}
	return true;
}
EquidistantCylindricalProjection.getWrapWidth = function(zoom){
	return this.mapResolutions[zoom];
}
