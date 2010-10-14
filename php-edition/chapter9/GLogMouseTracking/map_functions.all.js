var map;
var centerLatitude = 49.224773;
var centerLongitude = -122.991943;
var startZoom = 6;

//
function init() {
    map = new GMap(document.getElementById("map"));
    map.addControl(new GSmallMapControl());
    map.addControl(new GMapTypeControl());
    map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);


	//GMap events
	GEvent.addListener(map,'addmaptype',function(type){ GLog.write('GMap addmaptype ' + type); });
	GEvent.addListener(map,'removemaptype',function(type){ GLog.write('GMap removemaptype ' + type); });
	GEvent.addListener(map,'click',function(overlay,point){ GLog.write('GMap click at ' + point + ' on ' + overlay); });
	GEvent.addListener(map,'movestart',function(){ GLog.write('GMap movestart'); });
	GEvent.addListener(map,'move',function(){ GLog.write('GMap move'); });
	GEvent.addListener(map,'moveend',function(){ GLog.write('GMap moveend'); });
	GEvent.addListener(map,'zoomend',function(oldLevel,  newLevel){ GLog.write('GMap zoomend: was ' + oldLevel + ' now ' + newLevel); });
	GEvent.addListener(map,'maptypechanged',function(){ GLog.write('GMap maptypechanged'); });
	GEvent.addListener(map,'infowindowopen',function(){ GLog.write('GMap infowindowopen'); });
	GEvent.addListener(map,'infowindowclose',function(){ GLog.write('GMap infowindowclose'); });
	GEvent.addListener(map,'addoverlay',function(overlay){ GLog.write('GMap addoverlay ' + overlay); });
	GEvent.addListener(map,'removeoverlay',function(overlay){ GLog.write('GMap removeoverlay ' + overlay); });
	GEvent.addListener(map,'clearoverlays',function(){ GLog.write('GMap clearoverlays'); });
	GEvent.addListener(map,'mouseover',function(latlng){ GLog.write('GMap mouseover ' + latlng); });
	GEvent.addListener(map,'mouseout',function(latlng){ GLog.write('GMap mouseout ' + latlng); });
	//GEvent.addListener(map,'mousemove',function(latlng){ GLog.write('GMap mousemove ' + latlng); });
	GEvent.addListener(map,'dragstart',function(){ GLog.write('GMap dragstart'); });
	GEvent.addListener(map,'drag',function(){ GLog.write('GMap drag'); });
	GEvent.addListener(map,'dragend',function(){ GLog.write('GMap dragend'); });

	//GInfoWindow events
	infoWindow = map.getInfoWindow();
	GEvent.addListener(infoWindow,'closeclick',function(){ GLog.write('GInfoWindow closeclick'); });

	//GMarker events
	marker = new GMarker(new GLatLng(centerLatitude, centerLongitude));
	map.addOverlay(marker);
	GEvent.addListener(marker,'click',function(){ GLog.write('GMarker click'); });
	GEvent.addListener(marker,'dblclick',function(){ GLog.write('GMarker dblclick'); });
	GEvent.addListener(marker,'mousedown',function(){ GLog.write('GMarker mousedown'); });
	GEvent.addListener(marker,'mouseup',function(){ GLog.write('GMarker mouseup'); });
	GEvent.addListener(marker,'mouseover',function(){ GLog.write('GMarker mouseover'); });
	GEvent.addListener(marker,'mouseout',function(){ GLog.write('GMarker mouseout'); });
	GEvent.addListener(marker,'infowindowopen',function(){ GLog.write('GMarker infowindowopen'); });
	GEvent.addListener(marker,'infowindowclose',function(){ GLog.write('GMarker infowindowclose'); });
	GEvent.addListener(marker,'remove',function(){ GLog.write('GMarker remove'); });

}

window.onload = init;
