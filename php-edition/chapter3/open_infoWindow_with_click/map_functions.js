
var centerLatitude = 37.4419;
var centerLongitude = -122.1419;
var startZoom = 12;

var map;

function init() {
     map = new GMap2(document.getElementById("map"));
     map.addControl(new GSmallMapControl());
     map.addControl(new GMapTypeControl());
     map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);

   //allow the user to click the map to create a marker
	GEvent.addListener(map, "click", function(overlay, latlng) {
	     map.openInfoWindow (latlng,document.createTextNode("You clicked here!"));
	});
}


window.onload = init; 
