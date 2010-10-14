var map;
var centerLatitude = 20;
var centerLongitude = -156; 
var startZoom = 8;

function addMarker(longitude, latitude, description) {
	var marker = new GMarker(new GLatLng(latitude, longitude));

	GEvent.addListener(marker, 'click',
		function() {
			marker.openInfoWindowHtml(description);
		}
	);

	map.addOverlay(marker);
}

/* [listing 6-10] */
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
/* [listing 6-10 end] */

function changeBodyClass(from, to) {
     document.body.className = document.body.className.replace(from, to);
     return false;
}

function init() {
	document.getElementById('button-sidebar-hide').onclick = function() { return changeBodyClass('sidebar-right', 'nosidebar'); };
	document.getElementById('button-sidebar-show').onclick = function() { return changeBodyClass('nosidebar', 'sidebar-right'); };
	/* [listing 6-10] */
	handleResize();
	/* [listing 6-10 end] */
	
	map = new GMap(document.getElementById("map"));
	map.addControl(new GSmallMapControl());
	map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);
	
	for(id in markers) {
		addMarker(markers[id].longitude, markers[id].latitude, markers[id].name);
	}
}

/* [listing 6-10] */
window.onresize = handleResize;
/* [listing 6-10 end] */
window.onload = init;
