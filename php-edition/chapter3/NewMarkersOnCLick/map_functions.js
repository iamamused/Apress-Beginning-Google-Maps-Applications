
var centerLatitude = 37.4419;
var centerLongitude = -122.1419;
var startZoom = 12;

var map;

/* [listing 3-2] */
function init() {
	if (GBrowserIsCompatible()) {
		map = new GMap2(document.getElementById("map"));
		map.addControl(new GSmallMapControl());
		map.addControl(new GMapTypeControl());
		map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);

		//allow the user to click the map to create amarker
		GEvent.addListener(map, "click", function(overlay, latlng) {
			var marker = new GMarker(latlng)
			map.addOverlay(marker);
		});
	}
}
/* [listing 3-2 end] */

window.onload = init;
