var centerLatitude = 37.4419;
var centerLongitude = -122.1419;
var startZoom = 12;

var map;

function init() {
  if (GBrowserIsCompatible()) {
    map = new GMap2(document.getElementById("map"));
    map.addControl(new GSmallMapControl());
    map.addControl(new GMapTypeControl());
    map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);
    listMarkers();

    GEvent.addListener(map, "click", function(overlay, latlng) {
      if (overlay == null) {
        //create an HTML DOM form element
        var inputForm = document.createElement("form");
        inputForm.setAttribute("action","");
        inputForm.id='geocache-input'
        inputForm.onsubmit = function() {storeMarker(); return false;};
        
        //retrieve the longitude and lattitude of the click point
        var lng = latlng.lng();
        var lat = latlng.lat();
  
        inputForm.innerHTML = '<fieldset style="width:150px;">'
            + '<legend>New Marker</legend>'
            + '<label for="found">Found</label>'
            + '<input type="text" id="found" name="found" style="width:100%;"/>'
            + '<label for="left">Left</label>'
            + '<input type="text" id="left" name="left" style="width:100%;"/>'
            + '<input type="submit" value="Save"/>'
            + '<input type="hidden" id="longitude" name="lng" value="' + lng + '"/>'
            + '<input type="hidden" id="latitude" name="lat" value="' + lat + '"/>'
            + '</fieldset>';
  
        map.openInfoWindow (latlng,inputForm);
      }
    });
  }
}

function storeMarker(){
    var lng = document.getElementById("longitude").value;
    var lat = document.getElementById("latitude").value;

    var getVars =  "?m[found]=" + document.getElementById("found").value
        + "&m[left]=" + document.getElementById("left").value
        + "&m[lng]=" + lng
        + "&m[lat]=" + lat ;

    var request = GXmlHttp.create();

    //call the store_marker action back on the server
    request.open('GET', 'create' + getVars, true);
    request.onreadystatechange = function() {
        if (request.readyState == 4) {
            //the request is complete

            var success=false;
            var content='Error contacting web service';
            try {
              //parse the result to JSON (simply by eval-ing it)
              res=eval( "(" + request.responseText + ")" );
              content=res.content;
              success=res.success;              
            }catch (e){
              success=false;
            }

            //check to see if it was an error or success
            if(!success) {
                alert(content);
            } else {
                //create a new marker and add its info window
                var latlng = new GLatLng(parseFloat(lat),parseFloat(lng));
                var marker = createMarker(latlng, content);
                map.addOverlay(marker);
                map.closeInfoWindow();
            }
        }
    }
    request.send(null);
    return false;
}

function createMarker(latlng, html) {
     var marker = new GMarker(latlng);
     GEvent.addListener(marker, 'click', function() {
          var markerHTML = html;
          marker.openInfoWindowHtml(markerHTML);
    });
    return marker;
}

function listMarkers() {
  var request = GXmlHttp.create();
  //tell the request where to retrieve data from.
  request.open('GET', 'list', true);
  //tell the request what to do when the state changes.
  request.onreadystatechange = function() {
    if (request.readyState == 4) {
      //parse the result to JSON,by eval-ing it.
      //The response is an array of markers
      markers=eval( "(" + request.responseText + ")" );
      for (var i = 0 ; i < markers.length ; i++) {
        var marker=markers[i].attributes
        var lat=marker.lat;
        var lng=marker.lng;
        //check for lat and lng so MSIE does not error
        //on parseFloat of a null value
        if (lat && lng) {
        var latlng = new GLatLng(parseFloat(lat),parseFloat(lng));
        var html = '<div><b>Found</b> ' + marker.found + '</div><div><b>Left</b> '
                  + marker.left + '</div>';
        var marker = createMarker(latlng, html);
        map.addOverlay(marker);
        } // end of if lat and lng
      } // end of for loop
    } //if
  } //function
  request.send(null);
}

window.onload = init;
window.onunload = GUnload;
