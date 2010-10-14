var map;
var centerLatitude = 42.326062;
var centerLongitude = -72.290039;
var startZoom = 11;

function init() {
    map = new GMap2(document.getElementById('map'));
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

    //loop through each of the points from the global points object
    for (k in points) {
        var latlng = new GLatLng(points[k].latitude,points[k].longitude);
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
