var map, geocoder = null; 
var centerLatitude = 40.6897;
var centerLongitude = -95.0446; 
var startZoom = 5;
var deselectCurrent = function() {};
var removePolyline = function() {};
var earthRadius = 6378137; // in metres

var latlngs = [];

// anticipates two 3-element arrays representing 3d vectors, returns a 3-element array representing their cross-product
/* [listing 10-4] */
function crossProduct(a, b) {
	return [(a[1] * b[2]) - (a[2] * b[1]), 
			(a[2] * b[0]) - (a[0] * b[2]), 
			(a[0] * b[1]) - (a[1] * b[0])];
}
/* [listing 10-4 end] */

// anticipates two 3-element arrays, returns scalar value
/* [listing 10-3] */
function dotProduct(a, b) {
	return (a[0] * b[0]) + (a[1] * b[1]) + (a[2] * b[2]);
}
/* [listing 10-3 end] */

/* [listing 10-5] */
function spherePointAngle(A, B, C) { // returns angle at B
    return Math.atan2(dotProduct(crossProduct(C, B), A), dotProduct(crossProduct(B, A), crossProduct(B, C)));
}
/* [listing 10-5 end] */

// returns 3-element array representing cartesian location of a point given by a GLatLng object
/* [listing 10-2] */
function cartesianCoordinates(latlng) {
    var x = Math.cos(latlng.latRadians()) * Math.sin(latlng.lngRadians());
    var y = Math.cos(latlng.latRadians()) * Math.cos(latlng.lngRadians());
    var z = Math.sin(latlng.latRadians());
	return [x, y, z];
}
/* [listing 10-2 end] */

// Calculate area inside of polygon, in square metres
/* [listing 10-6] */
function polylineArea(latlngs) {
	var id, sum = 0, pointCount = latlngs.length, cartesians = [];
	if (pointCount < 3) return 0;
	
	for (i=0; i < latlngs.length; i++) {
	    cartesians[i] = cartesianCoordinates(latlngs[i]);
	}
	
	// pad out with the first two elements
	cartesians.push(cartesians[0]);
	cartesians.push(cartesians[1]);
		
	for(id = 0; id < pointCount; id++) {
		var A = cartesians[id];
		var B = cartesians[id + 1];
		var C = cartesians[id + 2];
		sum += spherePointAngle(A, B, C);
	}

	var alpha = Math.abs(sum - (pointCount - 2) * Math.PI);
    alpha -= 2 * Math.PI * Math.floor(alpha / (2 * Math.PI));
    alpha = Math.min(alpha, 4 * Math.PI - alpha);    	
    
    return Math.round(alpha * Math.pow(earthRadius, 2));
}
/* [listing 10-6 end] */


/* [listing 10-10] */
function initializePoint(id) {
	var marker = new GMarker(latlngs[id], { draggable:true });
	var listItem = document.createElement('li');
	var listItemLink = listItem.appendChild(document.createElement('a'));
	listItemLink.href = "#";
	listItemLink.innerHTML = '<strong>' + latlngs[id].lat() + '<br />' + latlngs[id].lng() + '</strong>';
	
	var focusPoint = function() {
		deselectCurrent();
		listItem.className = 'current';
		deselectCurrent = function() { listItem.className = ''; }
		map.panTo(latlngs[id]);
		return false;
	}

	GEvent.addListener(marker, 'click', focusPoint);
	listItemLink.onclick = focusPoint;

	$('sidebar-list').appendChild(listItem);

	map.addOverlay(marker);  
	
	marker.enableDragging();
	GEvent.addListener(marker, 'dragend', function() {
    	listItemLink.innerHTML = '<strong>' + latlngs[id].lat() + '<br />' + latlngs[id].lng() + '</strong>';
		latlngs[id] = marker.getPoint();
		redrawPolyline();
	});
}
/* [listing 10-10 end] */

/* [listing 10-9] */
function handleMapClick(marker, latlng) {
	if (!marker) {
		latlngs.push(latlng);		
		initializePoint(latlngs.length - 1);
		redrawPolyline();
	}
}
/* [listing 10-9 end] */

/* [listing 10-11] */
function redrawPolyline() {
    var pointCount = latlngs.length;
    var id;
	
    // Plot polyline, adding the first element to the end, to close the loop.
    latlngs.push(latlngs[0]);
    var polyline = new GPolyline(latlngs, 'FF6633', 4, 0.8);
    map.addOverlay(polyline);
	
    // Calculate total length of polyline (length for 2, perimeter for > 2)
    if (pointCount >= 2) {
        var length = 0;
        for(id = 0; id < pointCount; id += 1) {
            length += latlngs[id].distanceFrom(latlngs[id + 1]);
        }

        if (pointCount > 2) {
            $('length-title').innerHTML = 'Perimeter';
            $('length-data').innerHTML = Math.round(length) / 1000;
        } else {
            $('length-title').innerHTML = 'Length';
            $('length-data').innerHTML = Math.round(length) / 2000;
        }
    }
    latlngs.pop();
    
    if (pointCount >= 3) {
        $('area-data').innerHTML = polylineArea(latlngs) / 1000000; // show value in square km.
	}
	
	removePolyline(); // zap old polyline
	
	// set us up to zap the current polyline when we draw the next one.
	removePolyline = function() {
	  map.removeOverlay(polyline);
	}	
}
/* [listing 10-11 end] */

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
	var height = windowHeight() - $('toolbar').offsetHeight - 30;
	$('map').style.height = height + 'px';
	$('sidebar').style.height = height + 'px';
}

/* [listing 10-8] */
function init() {
	_mSvgEnabled = false; // Firefox 1.5.0.4/Mac wasn't rendering the SVG.
	
	handleResize();
	
	map = new GMap2($("map"));
	
	map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);	
	map.addControl(new GSmallMapControl());

	GEvent.addListener(map, 'click', handleMapClick);	
}
/* [listing 10-8 end] */

Event.observe(window, 'load', init, false);
Event.observe(window, 'resize', handleResize, false);
Event.observe(window, 'unload', GUnload, false);


