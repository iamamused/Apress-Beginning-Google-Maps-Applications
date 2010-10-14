var map;
var centerLatitude = 49.224773;
var centerLongitude = -122.991943;
var startZoom = 4;

var existingMarkers = {};

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
    //Don't remove all the overlays!

    //map.clearOverlays();
    var mapBounds = map.getBounds();

    //loop though each of the points in memory and remove those that
    //aren't going to be shown
    for(k in existingMarkers) {
        if(!mapBounds.contains(existingMarkers[k].getPoint())) {
            map.removeOverlay(existingMarkers[k]);
            delete existingMarkers[k];
        }
    }

    //Loop through each of the points from the global points object
    //are create markers that don't exist
    for (k in points) {
         var latlng = new GLatLng(points[k].lat,points[k].lng);

        //skip it if the marker already exists
        //or is not in the viewable area
        if(!existingMarkers[k] && mapBounds.contains(latlng)) {
            existingMarkers[k] = createMarker(latlng);
            map.addOverlay(existingMarkers[k]);
        }
    }
}

function createMarker(point) {
    var marker = new GMarker(point);
    return marker;
}

window.onload = init;