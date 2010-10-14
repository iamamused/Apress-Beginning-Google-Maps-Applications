var map;
var centerLatitude = 19.5;
var centerLongitude = -155.5; 
var startZoom = 9;
var markerHash={};
var currentFocus=false;

function focusPoint(id){
  if (currentFocus) {
    Element.removeClassName("sidebar-item-"+currentFocus,"current");
  }
  Element.addClassName("sidebar-item-"+id,"current");
  markerHash[id].marker.openInfoWindowHtml(markerHash[id].address);
  currentFocus=id;
}

function filter(type){
	for(i=0;i<markers.length;i++) {
    var current=markers[i];
		if (current.structure_type == type || 'All' == type) {
		  Element.show("sidebar-item-"+markers[i].id)
		  if (!markerHash[current.id].visible) {
		    map.addOverlay(markerHash[current.id].marker);
		    markerHash[current.id].visible=true;
		  }
		} else {
		  if (markerHash[current.id].visible) {
		    map.removeOverlay(markerHash[current.id].marker);
		    markerHash[current.id].visible=false;
		  }
		  Element.hide("sidebar-item-"+markers[i].id)
		}
	}
}

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

function addMarker(latitude, longitude, id) {
    var marker = new GMarker(new GLatLng(latitude, longitude));
    GEvent.addListener(marker, 'click',
        function() {
           focusPoint(id);
        }
    );
    map.addOverlay(marker);
    return marker;
}

function init() {
	handleResize();
	
	map = new GMap($("map"));
	map.addControl(new GSmallMapControl());
	map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);
	
	for(i=0;i<markers.length; i++) {
    var current =markers[i];
    marker=addMarker(current.latitude, current.longitude,current.id);
    markerHash[current.id]={marker:marker,address:current.address,visible:true};
	}

  Element.hide('loading');
}

Event.observe(window, 'load', init, false);
Event.observe(window, 'resize', handleResize, false);
