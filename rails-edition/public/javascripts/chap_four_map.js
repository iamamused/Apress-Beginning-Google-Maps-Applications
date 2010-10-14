var centerLatitude = 40.6897;
var centerLongitude = -95.0446;
var startZoom = 3;

var map;

var RonJonLogo = new GIcon();
RonJonLogo.image = 'http://googlemapsbook.com/chapter4/StoreLocationMap/ronjonsurfshoplogo.png';
RonJonLogo.iconSize = new GSize(48, 24);
RonJonLogo.iconAnchor = new GPoint(24, 14);
RonJonLogo.infoWindowAnchor = new GPoint(24, 24);

function addMarker(latitude , longitude, description) {
  var marker = new GMarker(new GLatLng(latitude, longitude), RonJonLogo);
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
    
  // loop through and add each store to the map
  for(i=0; i < points.length; i++) {
    addMarker(points[i].lat , points[i].lng, points[i].name);
  }
}

window.onload = init; 
