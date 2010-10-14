var map;
var centerLatitude = 20.838278;
var centerLongitude = -157.016602;
var startZoom = 7;

//Create an icon for the clusters
var iconCluster = new GIcon();
iconCluster.image = "http://googlemapsbook.com/chapter7/icons/cluster.png";
iconCluster.shadow = "http://googlemapsbook.com/chapter7/icons/cluster_shadow.png";
iconCluster.iconSize = new GSize(26, 25);
iconCluster.shadowSize = new GSize(22, 20);
iconCluster.iconAnchor = new GPoint(13, 25);
iconCluster.infoWindowAnchor = new GPoint(13, 1);
iconCluster.infoShadowAnchor = new GPoint(26, 13);

//create an icon for the pins
var iconSingle = new GIcon();
iconSingle.image = "http://googlemapsbook.com/chapter7/icons/single.png";
iconSingle.shadow = "http://googlemapsbook.com/chapter7/icons/single_shadow.png";
iconSingle.iconSize = new GSize(12, 20);
iconSingle.shadowSize = new GSize(22, 20);
iconSingle.iconAnchor = new GPoint(6, 20);
iconSingle.infoWindowAnchor = new GPoint(6, 1);
iconSingle.infoShadowAnchor = new GPoint(13, 13);


function init() {
	map = new GMap2(document.getElementById("map"));
	map.addControl(new GSmallMapControl());
	map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);

	updateMarkers();

	//Note: it is not necessary to trigger this event on
	//zoomend as moveend is automatically triggerd by zoomend.
	//GEvent.addListener(map,'zoomend',function() {
	//	updateMarkers();
	//});
	GEvent.addListener(map,'moveend',function() {
		updateMarkers();
	});

	alert('Note: This example has been limited to data from Hawaii');
}

function updateMarkers() {

	//remove the existing points
	map.clearOverlays();
	//create the boundry for the data to provide
	//initial filtering
	var bounds = map.getBounds();
	var southWest = bounds.getSouthWest();
	var northEast = bounds.getNorthEast();
	var getVars = 'ne=' + northEast.toUrlValue()
	+ '&sw=' + southWest.toUrlValue()

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
				var marker = createMarker(point,points[i].type);
				map.addOverlay(marker);
			}
		}
	}
	request.send(null);
}

function createMarker(point, type) {
	//create the marker with the appropriate icon
	if(type=='c') {
		var marker = new GMarker(point,iconCluster,true);
	} else {
		var marker = new GMarker(point,iconSingle,true);
	}
	return marker;
}

window.onload = init;