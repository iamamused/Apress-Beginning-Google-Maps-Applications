var map;
var centerLatitude = 43.49462;
var centerLongitude = -80.548239;
var startZoom = 3;

function init() {
	/* [listing 9-4] */
	map = new GMap2(document.getElementById("map"));
	map.addControl(new GSmallMapControl());
	map.addControl(new GMapTypeControl());
	map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);
	marker = new GMarker(new GLatLng(centerLatitude, centerLongitude));
	map.addOverlay(marker);
	var infoTabs = [
	new GInfoWindowTab("Tab A", "This is tab Acontent"),
	new GInfoWindowTab("Tab B", "This is tab B content"),
	new GInfoWindowTab("Tab C", "This is tab C content")
	];
	marker.openInfoWindowTabsHtml(infoTabs,{
		selectedTab:1,
		maxWidth:300
	});
	GEvent.addListener(marker,'click',function() {
		marker.openInfoWindowTabsHtml(infoTabs);
	});
	/* [listing 9-4 end] */
}

window.onload = init;
