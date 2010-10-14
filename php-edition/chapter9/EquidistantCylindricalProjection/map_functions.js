var map;
var centerLatitude = 43.49462;
var centerLongitude = -80.548239;
var startZoom = 3;

/* [listing 9-8] */
BlueMarbleProjection = new GProjection();

BlueMarbleProjection.mapResolutions = [256,512,1024,2048,4096,8192]

BlueMarbleProjection.fromLatLngToPixel = function(latlng,zoom) {
	var lng = parseInt(Math.floor(
		(this.mapResolutions[zoom] / 360) 
		* (latlng.lng() + 180))
	);
	var lat = parseInt(Math.floor(
		Math.abs((this.mapResolutions[zoom] / 2 / 180) 
		* (latlng.lat() - 90)))
	);
	var point = new GPoint(lng,lat);
	return point;
}

BlueMarbleProjection.fromPixelToLatLng = function(pixel,zoom,unbounded) {
	var lat = 90 - (pixel.y / (this.mapResolutions[zoom] / 2 / 180));
	var lng = (pixel.x / (this.mapResolutions[zoom] / 360)) - 180;
	var latlng = new GLatLng(lat,lng);
	return latlng;
}

BlueMarbleProjection.tileCheckRange = function(tile,zoom,tileSize){
	var rez = this.mapResolutions[zoom];
	if(tile.y < 0 || tile.y * tileSize >= rez / 2){ return false; }
	if(tile.x < 0 || tile.x * tileSize >= rez){
		var e = Math.floor( rez / tileSize );
		tile.x = tile.x % e;
		if(tile.x < 0){ tile.x += e; }
	}
	return true;
}

BlueMarbleProjection.getWrapWidth = function(zoom){
	return this.mapResolutions[zoom];
}
/* [listing 9-8 end] */

/* [listing 9-9] */
copyrights = new GCopyrightCollection('Map Imagery:');
var visibleEarth = new GCopyright(
	'nasabluemarble',
	new GLatLngBounds(new GLatLng(-90,-180),new GLatLng(90,180)),
	0,
	'<a href="http://visibleearth.nasa.gov/">NASA Visible Earth</a>'
);
copyrights.addCopyright(visibleEarth);

//tile layer for land_ocean_ice
var BlueMarbleTiles = new GTileLayer(copyrights,0,5);
BlueMarbleTiles.getTileUrl = function(tile,zoom){
	if(zoom > 5) return 'tiles/no_tiles_at_zoom_level.png';
	else return 'tiles/land_ocean_ice/tile.' + zoom + '.' +
	(tile.x + tile.y * Math.pow(2,zoom)) + '.png';
};
BlueMarbleTiles.isPng = function() { return true; }
BlueMarbleTiles.getOpacity = function() { return 1.0; }

//tile layer for land_ocean_ice_lights
var BlueMarbleNightTiles = new GTileLayer(copyrights,0,3);
BlueMarbleNightTiles.getTileUrl = function(tile,zoom){
	if(zoom > 3) return 'tiles/no_tiles_at_zoom_level.png';
	else return 'tiles/land_ocean_ice_lights/tile.' + zoom + '.' +
	(tile.x + tile.y * Math.pow(2,zoom)) + '.png';
};
BlueMarbleNightTiles.isPng = function() { return true; }
BlueMarbleNightTiles.getOpacity = function() { return 1.0; }

//tile layer for land_ocean_ice_cloud
var BlueMarbleCloudyTiles = new GTileLayer(copyrights,0,5);
BlueMarbleCloudyTiles.getTileUrl = function(tile,zoom){
	if(zoom > 5) return 'tiles/no_tiles_at_zoom_level.png';
	else return 'tiles/land_ocean_ice_cloud/tile.' + zoom + '.' +
	(tile.x + tile.y * Math.pow(2,zoom)) + '.png';
};
BlueMarbleCloudyTiles.isPng = function() { return true; }
BlueMarbleCloudyTiles.getOpacity = function() { return 1.0; }
/* [listing 9-9 end] */


/* [listing 9-10] */
var BlueMarble = new GMapType(
	[BlueMarbleTiles],
	BlueMarbleProjection,
	'Blue Marble',
	{
		shortName:'BM',
		tileSize:256,
		maxResolution:5,
		minResolution:0
	}
);
var BlueMarbleNight = new GMapType(
	[BlueMarbleNightTiles],
	BlueMarbleProjection,
	'Blue Marble Night',
	{
		shortName:'BMN',
		tileSize:256,
		maxResolution:3,
		minResolution:0
	}
);
var BlueMarbleCloudy = new GMapType(
	[BlueMarbleCloudyTiles],
	BlueMarbleProjection,
	'Blue Marble Cloudy',
	{
		shortName:'BMC',
		tileSize:256,
		maxResolution:5,
		minResolution:0
	}
);
/* [listing 9-10 end] */

function init() {
	map = new GMap2(document.getElementById("map"));

	map.addMapType(BlueMarble);
	map.addMapType(BlueMarbleNight);
	map.addMapType(BlueMarbleCloudy);
	map.addControl(new GSmallMapControl());
	map.addControl(new GMapTypeControl());

	var office = new GLatLng(centerLatitude, centerLongitude);
	map.setCenter(office, startZoom);
	map.addOverlay(new GMarker(office));

}

window.onload = init;
