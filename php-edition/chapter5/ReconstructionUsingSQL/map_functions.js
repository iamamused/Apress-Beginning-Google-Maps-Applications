
var map;
var centerLatitude = 20.3;
var centerLongitude = -156.5;
var startZoom = 7;

function addMarker(longitude, latitude, description) {
	var marker = new GMarker(new GPoint(longitude, latitude));

	GEvent.addListener(marker, 'click',
		function() {
			marker.openInfoWindowHtml(description);
		}
	);

	map.addOverlay(marker);
}

function init() {
	map = new GMap2(document.getElementById("map"));
	map.addControl(new GSmallMapControl());
	map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);

	for(id in markers) {
		addMarker(markers [id].longitude, markers [id].latitude, markers [id].name);
	}
}

window.onload = init;