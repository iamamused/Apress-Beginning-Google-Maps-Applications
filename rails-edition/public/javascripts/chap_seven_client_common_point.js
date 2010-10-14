var map;
var centerLatitude = 41.8;
var centerLongitude = -72.3;
var startZoom = 8;

function init() {
    map = new GMap2(document.getElementById("map"));
    map.addControl(new GSmallMapControl());
    map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);

    //pass in an initial point for the center
    updateMarkers(new GLatLng(centerLatitude, centerLongitude));

    GEvent.addListener(map,'click',function(overlay,point) {
        //pass in the point for the center
        updateMarkers(point);
    });

}

function updateMarkers(relativeTo) {

    //remove the existing points
    map.clearOverlays();

    //mark the outer boundary of the data from the points object
    var allsw = new GLatLng(41.57025176609894, -73.39965820312499);
    var allne = new GLatLng(42.589488572714245, -71.751708984375);
    var allmapBounds = new GLatLngBounds(allsw,allne);
    map.addOverlay(new Rectangle(allmapBounds,4,"#F00"));

    var distanceList = [];
    var p = 0;
    //loop through points and get the distance to each point
    for (k in points) {
        distanceList[p] = {};
        distanceList[p].glatlng = new GLatLng(points[k].latitude,points[k].longitude);
        distanceList[p].distance = distanceList[p].glatlng.distanceFrom(relativeTo);
        p++;
    }

    //sort based on the distance
    distanceList.sort(function (a,b) {
        if(a.distance > b.distance) return 1
        if(a.distance < b.distance) return -1
        return 0
    });

    //create the first 50 markers
    for (i=0 ; i<50 ; i++) {
        var marker = createMarker(distanceList[i].glatlng);
        map.addOverlay(marker);
        if(++i > 50) break;
    }
}

function createMarker(point) {
    var marker = new GMarker(point);
    return marker;
}

window.onload = init;


/*
* Rectangle overlay for testing to mark boundaries
*/
function Rectangle(bounds, opt_weight, opt_color) {
  this.bounds_ = bounds; 
  this.weight_ = opt_weight || 1; 
  this.color_ = opt_color || "#888888";
}
Rectangle.prototype = new GOverlay();

Rectangle.prototype.initialize = function(map) {
  var div = document.createElement("div");
  div.innerHTML = '<strong>Click inside area</strong>';
  div.style.border = this.weight_ + "px solid " + this.color_;
  div.style.position = "absolute";
  map.getPane(G_MAP_MAP_PANE).appendChild(div);
  this.map_ = map;
  this.div_ = div;
}
Rectangle.prototype.remove = function() { 
  this.div_.parentNode.removeChild(this.div_);
}
Rectangle.prototype.copy = function() { 
  return new Rectangle(
    this.bounds_,
    this.weight_,
    this.color_,
    this.backgroundColor_,
    this.opacity_
  );
}
Rectangle.prototype.redraw = function(force) {
  if (!force) return;
  var c1 = this.map_.fromLatLngToDivPixel(this.bounds_.getSouthWest());
  var c2 = this.map_.fromLatLngToDivPixel(this.bounds_.getNorthEast());
  this.div_.style.width = Math.abs(c2.x - c1.x) + "px";
  this.div_.style.height = Math.abs(c2.y - c1.y) + "px";
  this.div_.style.left = (Math.min(c2.x, c1.x) - this.weight_) + "px";
  this.div_.style.top = (Math.min(c2.y, c1.y) - this.weight_) + "px";
}
