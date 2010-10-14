var map;
var centerLatitude = 42;
var centerLongitude = -72;
var startZoom = 10;

function init() {
    map = new GMap2(document.getElementById("map"));
    map.addControl(new GSmallMapControl());
    map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);

    //pass in an initial point for the center
    updateMarkers(new GLatLng(centerLatitude, centerLongitude));

    GEvent.addListener(map,'click',function(overlay,point) {
        //pass in the point for the center
        updateMarkers(point);
    });

}

function updateMarkers(point) {

    //remove the existing points
    map.clearOverlays();

    //create the boundary for the data to provide
    //initial filtering
    var bounds = map.getBounds();
    var southWest = bounds.getSouthWest();
    var northEast = bounds.getNorthEast();
    var url = '/chap_seven/nearby_towers?ne=' + northEast.toUrlValue() +
      '&sw=' + southWest.toUrlValue()+'&ll='+point.toUrlValue();
    
    //log the URL for testing
    GLog.writeUrl(url);

    //retrieve the points using Ajax
    var request = GXmlHttp.create();
    request.open('GET', url, true);
    request.onreadystatechange = function() {
         if (request.readyState == 4) {
              var data = request.responseText;
              var points = eval('('+data+')');

              //create each point from the list
              for (i in points) {
                  var point = new GLatLng(points[i].latitude,points[i].longitude);
                  var marker = createMarker(point);
                  map.addOverlay(marker);
              }
         }
    }
    request.send(null);
}

function createMarker(point) {
    var marker = new GMarker(point);
    return marker;
}

window.onload = init;
