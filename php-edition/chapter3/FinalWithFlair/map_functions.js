
var centerLatitude = 37.4419;
var centerLongitude = -122.1419;
var startZoom = 12;

var map;

function init() {
    map = new GMap2(document.getElementById("map"));
    map.addControl(new GSmallMapControl());
    map.addControl(new GMapTypeControl());
    map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);

    retrieveMarkers()

    GEvent.addListener(map, "click", function(overlay, latlng) {
        //only perform the click if the window is closed and the click was directly on the map.
        if(!overlay) {
	        //create an HTML DOM form element
	        var inputForm = document.createElement("form");
	        inputForm.setAttribute("action","");
	        inputForm.onsubmit = function() {storeMarker(); return false;};

	        //retrieve the longitude and lattitude of the click point
	        var lng = latlng.lng();
	        var lat = latlng.lat();

	        inputForm.innerHTML = '<fieldset style="width:150px;">'
	              + '<legend>New Marker</legend>'
	              + '<label for="found">Found</label>'
	              + '<input type="text" id="found" style="width:100%;"/>'
	              + '<label for="left">Left</label>'
	              + '<input type="text" id="left" style="width:100%;"/>'
	              + '<label for="left">Icon URL</label>'
	              + '<input type="text" id="icon" style="width:100%"/>'
	              + '<input type="submit" value="Save"/>'
	              + '<input type="hidden" id="longitude" value="' + lng + '"/>'
	              + '<input type="hidden" id="latitude" value="' + lat + '"/>'
	              + '</fieldset>';

	        map.openInfoWindow (latlng,inputForm);
        }
    });

}

window.onload = init;

function storeMarker(){
    var lng = document.getElementById("longitude").value;
    var lat = document.getElementById("latitude").value;

    var getVars =  "?found=" + document.getElementById("found").value
        + "&left=" + document.getElementById("left").value
        + "&icon=" + document.getElementById("icon").value
        + "&lng=" + lng
        + "&lat=" + lat ;

    var request = GXmlHttp.create();

    //open the request to storeMakres.php on your server
    request.open('GET', 'storeMarker.php' + getVars, true);
    request.onreadystatechange = function() {
       if (request.readyState == 4) {
            //the request in complete

            var xmlDoc = request.responseXML;

            //retrieve the root document element (response)
            var responseNode = xmlDoc.documentElement;

            //retrieve the type attribute of the node
            var type = responseNode.getAttribute("type");

            //retrieve the content of the responseNode
            var content = responseNode.firstChild.nodeValue;

            //check to see if it was an error or success
            if(type!='success') {
                alert(content);
            } else {
                //Create a new marker and add it's info window.
                var latlng = new GLatLng(parseFloat(lat),parseFloat(lng));

                var iconImage = responseNode.getAttribute("icon");
                var marker = createMarker(latlng, content, iconImage)

                map.addOverlay(marker);
                map.closeInfoWindow();
            }
       }
    }
    request.send(null);
    return false;
}

function createMarker(latlng, html, iconImage) {
    if(iconImage) {
        var icon = new GIcon();
        icon.image = iconImage;
        icon.iconSize = new GSize(25, 25);
        icon.iconAnchor = new GPoint(14, 25);
        icon.infoWindowAnchor = new GPoint(14, 14);
        var marker = new GMarker(latlng,icon);
    } else {
        var marker = new GMarker(latlng);
    }
    GEvent.addListener(marker, 'click', function() {
        var markerHTML = html;
        marker.openInfoWindowHtml(markerHTML);
    });
    return marker;
}

function retrieveMarkers() {
    var request = GXmlHttp.create();

    //tell the request where to retrieve data from.
    request.open('GET', 'retrieveMarkers.php', true);

    //tell the request what to do when the state changes.
    request.onreadystatechange = function() {
        if (request.readyState == 4) {
              var xmlDoc = request.responseXML;

              var markers = xmlDoc.documentElement.getElementsByTagName("marker");
              for (var i = 0; i < markers.length; i++) {
                   var lng = markers[i].getAttribute("lng");
                   var lat = markers[i].getAttribute("lat");
                   //check for lng and lat so MSIE does not error
                   //on parseFloat of a null value
                   if(lng && lat) {
                      var latlng = new GLatLng(parseFloat(lat),parseFloat(lng));

                      var html = '<div><b>Found</b> '
                            + markers[i].getAttribute("found")
                            + '</div><div><b>Left</b> '
                            + markers[i].getAttribute("left")
                            + '</div>';

                      var iconImage =  markers[i].getAttribute("icon");
                      var marker = createMarker(latlng, html, iconImage);
                      map.addOverlay(marker);
                   }
              } //for
        } //if
    } //function

    request.send(null);
}




