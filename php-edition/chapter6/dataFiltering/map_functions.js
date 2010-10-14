var map;
var centerLatitude = 20;
var centerLongitude = -156; 
var startZoom = 8;
var deselectCurrent = function() {};

/* [listing 6-20] */
function initializePoint(pointData) {
	var point = new GLatLng(pointData.latitude, pointData.longitude);
	var marker = new GMarker(point);
	var listItem = document.createElement('li');
	var listItemLink = listItem.appendChild(document.createElement('a'));
	var visible = false;

	listItemLink.href = "#";
	listItemLink.innerHTML = '<strong>' + pointData.address + ' </strong><span>' + pointData.city + ', ' + pointData.state + ' (' + pointData.height + 'm)</span>';
	
	var focusPoint = function() {
		deselectCurrent();
		listItem.className = 'current';
		deselectCurrent = function() { listItem.className = ''; }
		marker.openInfoWindowHtml(pointData.address);
		map.panTo(point);
		return false;
	}

	GEvent.addListener(marker, 'click', focusPoint);	
	listItemLink.onclick = focusPoint;

	pointData.show = function() {
		if (!visible) {
			document.getElementById('sidebar-list').appendChild(listItem);
			map.addOverlay(marker);
			visible = true;
		}
	}
	pointData.hide = function() {
		if (visible) {
			document.getElementById('sidebar-list').removeChild(listItem);
			map.removeOverlay(marker);
			visible = false;
		}
	}

	pointData.show();
}
/* [listing 6-20 end] */

/* [listing 6-22] */
function initializeSortTab(type) {
	var listItem = document.createElement('li');
	var listItemLink = listItem.appendChild(document.createElement('a'));

	listItemLink.href = "#";
	listItemLink.innerHTML = type;
	listItemLink.onclick = function() {
		changeBodyClass('standby', 'loading');

		for(id in markers) {
			if (markers[id].type == type || 'All' == type)
				markers[id].show();
			else
				markers[id].hide();	
		}

		changeBodyClass('loading', 'standby');

		return false;
	}

	document.getElementById('filters').appendChild(listItem);
}
/* [listing 6-22 end] */

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
	var type;
	var allTypes = { 'All':[] };
	
	document.getElementById('button-sidebar-hide').onclick = function() { return changeBodyClass('sidebar-right', 'nosidebar'); };
	document.getElementById('button-sidebar-show').onclick = function() { return changeBodyClass('nosidebar', 'sidebar-right'); };
	handleResize();
	
	map = new GMap(document.getElementById("map"));
	map.addControl(new GSmallMapControl());
	map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);
	
	/* [listing 6-21] */
	for(id in markers) {
		initializePoint(markers[id]);
		allTypes[markers[id].type] = true;
	}

	for(type in allTypes) {
		initializeSortTab(type);
	}
	/* [listing 6-21 end] */

	changeBodyClass('loading', 'standby');
}

window.onresize = handleResize;
window.onload = init;
