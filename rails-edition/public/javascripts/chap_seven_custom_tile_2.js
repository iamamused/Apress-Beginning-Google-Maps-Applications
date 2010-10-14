var map;

var centerLatitude = 37.0625;
var centerLongitude = -95.677068;
var startZoom = 4;

//create the tile layer object
var detailLayer = new GTileLayer(new GCopyrightCollection(''));

//method to retrieve the URL of the tile
detailLayer.getTileUrl = function(tile, zoom){
    //provide the x and y position as well as the zoom
    return url="/chap_seven/get_tile?x="+tile.x+"&y="+tile.y+"&zoom="+zoom
};

detailLayer.isPng = function() {
    return true;
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
}

window.onload = init;
