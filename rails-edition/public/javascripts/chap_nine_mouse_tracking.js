var map;
var centerLatitude = 43.49462;
var centerLongitude = -80.548239;
var startZoom = 3;
function init() {
	map = new GMap2(document.getElementById("map"));
	map.addControl(new GSmallMapControl());
	map.addControl(new GMapTypeControl());
	map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);

	GEvent.addListener(map,'mousemove',function(latlng) {
		var pixelLocation = map.fromLatLngToDivPixel(latlng);
		GLog.write('ll:' + latlng + ' at:' + pixelLocation);
	});
}

window.onload = init;