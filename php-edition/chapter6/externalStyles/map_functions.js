var map;
var centerLatitude = 20;
var centerLongitude = -156; 
var startZoom = 8;

function addMarker(longitude, latitude, description) {
	var marker = new GMarker(new GLatLng(latitude, longitude));

	GEvent.addListener(marker, 'click',
		function() {
			marker.openInfoWindowHtml(description);
		}
	);

	map.addOverlay(marker);
}

function init() {
	map = new GMap(document.getElementById("map"));
	map.addControl(new GSmallMapControl());
	map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);

	for(id in markers) {
		addMarker(markers [id].longitude, markers [id].latitude, markers [id].name);
	}
}

window.onload = init;
