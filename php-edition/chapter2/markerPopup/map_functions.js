/* [listing 2-6] */
var centerLatitude = 37.818361;
var centerLongitude = -122.478032;
var description = 'Golden Gate Bridge';

var startZoom = 13;
var map;

function addMarker(latitude, longitude, description) {
    var marker = new GMarker(new GLatLng(latitude, longitude));

    GEvent.addListener(marker, 'click',
        function() {
            marker.openInfoWindowHtml(description);
        }
    );

    map.addOverlay(marker);
}

function init() {
    if (GBrowserIsCompatible()) {	
        map = new GMap2(document.getElementById("map"));
        map.addControl(new GSmallMapControl());
        map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);

        addMarker(centerLatitude, centerLongitude, description);
    }
}

window.onload = init;
window.onunload = GUnload;
/* [listing 2-6 end] */