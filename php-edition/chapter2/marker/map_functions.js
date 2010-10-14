/* [listing 2-5] */
var centerLatitude = 37.818361;
var centerLongitude = -122.478032;
var startZoom = 13;

var map;

function init()
{
    if (GBrowserIsCompatible()) {	
        map = new GMap2(document.getElementById("map"));
        map.addControl(new GSmallMapControl());
        var location = new GLatLng(centerLatitude, centerLongitude);
        map.setCenter(location, startZoom);

        var marker = new GMarker(location)
        map.addOverlay(marker);
    }
}

window.onload = init;
window.onunload = GUnload;
/* [listing 2-5 end] */