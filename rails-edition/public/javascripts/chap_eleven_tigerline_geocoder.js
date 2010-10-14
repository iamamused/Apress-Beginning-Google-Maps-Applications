var map;
var startZoom = 17;

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
    map = new GMap2(document.getElementById("map"));
    map.addControl(new GSmallMapControl());
    map.setCenter(new GLatLng(lat,lng), startZoom);

    var marker = new GMarker(new GLatLng(lat,lng));
    map.addOverlay(marker);

    for (i=0;i < chains.length ;i++) {
      if (false) {
        var marker = new GMarker(new GLatLng(chains[i].latitude,chains[i].longitude),
                    new GIcon(G_DEFAULT_ICON, "/images/icong"+(1+i)+".png"));
        map.addOverlay(marker);
      }
      if (i<chains.length -1) {
        var polyline = new GPolyline([
          new GLatLng(chains[i].latitude,chains[i].longitude),
          new GLatLng(chains[i+1].latitude,chains[i+1].longitude)], "#ff0000", 4);
        map.addOverlay(polyline);
      }
    }
}

window.onload = init;
window.onunload = GUnload;
