var map;

//var startZoom = 3;
var startZoom = 14;

var latlngs = {
	'NYC': new GPoint(40.716038, -74.00528),
	'Paris': new GPoint(48.857713, 2.349358)
};


function windowHeight() {
	// Standard browsers (Mozilla, Safari, etc.)
	if (self.innerHeight)
		return self.innerHeight;
	// IE 6
	if (document.documentElement && document.documentElement.clientHeight)
		return document.documentElement.clientHeight;
	// IE 5
	if (document.body)
		return document.body.clientHeight;
	// Just in case.
	return 0;
}

function handleResize() {
	var height = windowHeight() - document.getElementById('toolbar').offsetHeight - 30;
	document.getElementById('map').style.height = height + 'px';
	document.getElementById('sidebar').style.height = height + 'px';
}

function changeBodyClass(from, to) {
     document.body.className = document.body.className.replace(from, to);
     return false;
}

function init() {
	document.getElementById('button-sidebar-hide').onclick = function() { return changeBodyClass('sidebar-right', 'nosidebar'); };
	document.getElementById('button-sidebar-show').onclick = function() { return changeBodyClass('nosidebar', 'sidebar-right'); };
	
	handleResize();	
	
	map = new GMap(document.getElementById("map"));
	map.addControl(new GSmallMapControl());
//	map.setCenter(new GLatLng((latlngs.Paris.lat() + latlngs.NYC.lat()) / 2, (latlngs.Paris.lng() + latlngs.NYC.lng()) / 2), startZoom);
	map.centerAndZoom(new GPoint((latlngs.Paris.y + latlngs.NYC.y) / 2, (latlngs.Paris.x + latlngs.NYC.x) / 2), startZoom);

	var style = {
		segmentCount: 20
	};
	map.addOverlay(new XPolyline(latlngs, style));
	
}

window.onresize = handleResize;
window.onload = init;
