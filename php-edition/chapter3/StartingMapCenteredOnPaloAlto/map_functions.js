var centerLatitude = 37.4419;
var centerLongitude = -122.1419;
var startZoom = 12;

var map;

function init() {
     map = new GMap2(document.getElementById("map"));
     map.addControl(new GSmallMapControl());
     map.addControl(new GMapTypeControl());
     map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);
}

window.onload = init;
