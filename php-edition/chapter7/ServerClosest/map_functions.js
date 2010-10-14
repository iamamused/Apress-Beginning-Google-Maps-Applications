var map;
var centerLatitude = 20.838278;
var centerLongitude = -157.016602;
var startZoom = 7;

function init() {
    map = new GMap2(document.getElementById("map"));
    map.addControl(new GSmallMapControl());
    map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);

    //Pass in a initial point for the center
    updateMarkers(new GLatLng(centerLatitude, centerLongitude));

    GEvent.addListener(map,'click',function(overlay,point) {
        //Pass in the point for the center
        updateMarkers(point);
    });

    alert('Note: This example has been limited to data from Hawaii');
}

function updateMarkers(point) {

    //remove the existing points
    map.clearOverlays();

    //create the boundry for the data to provide
    //initial filtering
    var bounds = map.getBounds();
    var southWest = bounds.getSouthWest();
    var northEast = bounds.getNorthEast();
    var getVars = 'ne=' + northEast.toUrlValue()
    + '&sw=' + southWest.toUrlValue()
    + '&known=' + point.toUrlValue();

    //log the URL for testing
    GLog.writeUrl('server.php?'+getVars);

    //retrieve the points
    var request = GXmlHttp.create();
    request.open('GET', 'server.php?'+getVars, true);
    request.onreadystatechange = function() {
         if (request.readyState == 4) {
              var jscript = request.responseText;
              var points;
              eval(jscript);

              //create each point from the list
              for (i in points) {
                  var point = new GLatLng(points[i].lat,points[i].lng);
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