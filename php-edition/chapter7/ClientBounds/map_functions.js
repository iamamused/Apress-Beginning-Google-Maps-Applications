var map;
var centerLatitude = 46.679594;
var centerLongitude = 2.988281;
var startZoom = 5;

function init() {
    map = new GMap2(document.getElementById("map"));
    map.addControl(new GSmallMapControl());
    map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);

    updateMarkers();

    GEvent.addListener(map,'zoomend',function() {
        updateMarkers();
    });
    GEvent.addListener(map,'moveend',function() {
        updateMarkers();
    });
}

function updateMarkers() {
    map.clearOverlays();
    var mapBounds = map.getBounds();

    //Loop through each of the points from the global points object
    for (k in points) {
        var latlng = new GLatLng(points[k].lat,points[k].lng);
        if(!mapBounds.contains(latlng)) continue;
        var marker = createMarker(latlng);
        map.addOverlay(marker);
    }
}

function createMarker(point) {
    var marker = new GMarker(point);
    return marker;
}

window.onload = init;