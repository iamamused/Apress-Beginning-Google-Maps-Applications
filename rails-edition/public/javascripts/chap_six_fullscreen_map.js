var map;
var centerLatitude = 19.6;
var centerLongitude = -155.5; 
var startZoom = 9;
var markerHash={};
var currentFocus=false;

function addMarker(latitude, longitude, id) {
    var marker = new GMarker(new GLatLng(latitude, longitude));

    GEvent.addListener(marker, 'click',
        function() {
           focusPoint(id);
        }
    );

    map.addOverlay(marker);
    return marker;
}

function init() {
    map = new GMap($("map"));
    map.addControl(new GSmallMapControl());
    map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);	
    for(i=0;i<markers.length; i++) {
      var current =markers[i];
      marker=addMarker(current.latitude, current.longitude,current.id);
      markerHash[current.id]={marker:marker,address:current.address,visible:true};
    }
}
Event.observe(window, 'load', init, false);
