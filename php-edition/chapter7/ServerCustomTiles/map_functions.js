var map;
var centerLatitude = 40;
var centerLongitude = -100;
var startZoom = 5;

//Create the tile layer object
var detailLayer = new GTileLayer(new GCopyrightCollection(''));

//Method to retrieve the URL of the tile
detailLayer.getTileUrl = function(tile, zoom){
    //pass the x and y position as wel as the zoom
    var tileURL = "server.php?x="+tile.x+"&y="+tile.y+"&zoom="+zoom;
    return tileURL;
};

detailLayer.isPng = function() {
    //The example uses GIF's
    return false;
}

//add your tiles to the normal map projection
detailMapLayers = G_NORMAL_MAP.getTileLayers();
detailMapLayers.push(detailLayer);

//add your tiles to the satellite map projection
detailMapLayers = G_SATELLITE_MAP.getTileLayers();
detailMapLayers.push(detailLayer);


function init() {
    map = new GMap2(document.getElementById("map"));
    map.addControl(new GSmallMapControl());
    map.addControl(new GMapTypeControl());
    map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);
    map.setMapType(G_SATELLITE_MAP);

}

window.onload = init;