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

/* [listing 6-8] */
function changeBodyClass(from, to) {
     document.body.className = document.body.className.replace(from, to);
     return false;
}
/* [listing 6-8 end] */

function init() {
	document.getElementById('button-sidebar-hide').onclick = function() { return changeBodyClass('sidebar-right', 'nosidebar'); };
	document.getElementById('button-sidebar-show').onclick = function() { return changeBodyClass('nosidebar', 'sidebar-right'); };
	
	map = new GMap(document.getElementById("map"));
	map.addControl(new GSmallMapControl());
	map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);

	for(id in markers) {
		addMarker(markers[id].longitude, markers[id].latitude, markers[id].name);
	}
}

window.onload = init;
