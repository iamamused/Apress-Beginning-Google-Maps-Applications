var map;
var centerLatitude = 43;
var centerLongitude = -80;
var startZoom = 3;

/* [listing 9-3] */
var PromoControl = function(url) {
	this.url_ = url;
};

PromoControl.prototype = new GControl(true);

PromoControl.prototype.initialize = function(map) {
	var container = document.createElement("div");
	container.innerHTML = '<img style="cursor:pointer" src="http://googlemapsbook.com/PromoApress.png" border="0">';
	container.style.width='120px';
	container.style.height='32px';
	
	url = this.url_;
	
	GEvent.addDomListener(container, "click", function() {
		document.location = url;
	});
	
	map.getContainer().appendChild(container);
	
	return container;
};

PromoControl.prototype.getDefaultPosition = function() {
	return new GControlPosition(G_ANCHOR_BOTTOM_LEFT, new GSize(70, 0));
};
/* [listing 9-3 end] */

function init() {
	map = new GMap2(document.getElementById("map"));
	
	map.addControl(new GSmallMapControl());
	map.addControl(new GMapTypeControl());
	map.addControl(new PromoControl());
	
	map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);
	
	marker = new GMarker(new GLatLng(centerLatitude, centerLongitude));
	map.addOverlay(marker);
}

window.onload = init;
