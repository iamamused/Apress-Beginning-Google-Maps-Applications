var map;
var centerLatitude = 42;
var centerLongitude = -72;
var startZoom = 8;

//Create an icon for the clusters
var iconCluster = new GIcon();
iconCluster.image = "http://googlemapsbook.com/chapter7/icons/cluster.png";
iconCluster.shadow = "http://googlemapsbook.com/chapter7/icons/cluster_shadow.png";
iconCluster.iconSize = new GSize(26, 25);
iconCluster.shadowSize = new GSize(22, 20);
iconCluster.iconAnchor = new GPoint(13, 25);
iconCluster.infoWindowAnchor = new GPoint(13, 1);
iconCluster.infoShadowAnchor = new GPoint(26, 13);

//create an icon for the pins
var iconSingle = new GIcon();
iconSingle.image = "http://googlemapsbook.com/chapter7/icons/single.png";
iconSingle.shadow = "http://googlemapsbook.com/chapter7/icons/single_shadow.png";
iconSingle.iconSize = new GSize(12, 20);
iconSingle.shadowSize = new GSize(22, 20);
iconSingle.iconAnchor = new GPoint(6, 20);
iconSingle.infoWindowAnchor = new GPoint(6, 1);
iconSingle.infoShadowAnchor = new GPoint(13, 13);


function init() {
    map = new GMap2(document.getElementById("map"));
    map.addControl(new GSmallMapControl());
    map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);

    updateMarkers();

    GEvent.addListener(map,'zoomend',function() {
        updateMarkers();
    });

    GEvent.addListener(map,'moveend',function() {
        updateMarkers();
    });

}

function updateMarkers() {

    //remove the existing points
    map.clearOverlays();

    //Mark the boundry of the data
    var allsw = new GLatLng(41.57025176609894, -73.39965820312499);
    var allne = new GLatLng(42.589488572714245, -71.751708984375);
    var allmapBounds = new GLatLngBounds(allsw,allne);
    map.addOverlay(new Rectangle(allmapBounds,4,"#F00",'<strong>Data Bounds, Zoom in for detail.</strong>'));


    //get the bounds of the viewable area
    var mapBounds = map.getBounds();
    var sw = mapBounds.getSouthWest();
    var ne = mapBounds.getNorthEast();
    var size = mapBounds.toSpan(); //returns GLatLng

    // make a grid that's 10x10 in the viewable area
    var gridSize = 10;
    var gridCellSizeLat = size.lat()/gridSize;
    var gridCellSizeLng = size.lng()/gridSize;
    var gridCells = [];

    //loop through the points and assign each one to a grid cell
    for (k in points) {
        var latlng = new GLatLng(points[k].lat,points[k].lng);

        //cehck if it is in teh viewable area,
        //it may not be when zoomed in close
        if(!mapBounds.contains(latlng)) continue;

        //find grid cell it is in:
        var testBounds = new GLatLngBounds(sw,latlng);
        var testSize = testBounds.toSpan();
        var i = Math.ceil(testSize.lat()/gridCellSizeLat);
        var j = Math.ceil(testSize.lng()/gridCellSizeLng);
        var cell = i+j;

        if( typeof gridCells[cell] == 'undefined') {
            //add it to the grid cell array
            var cellSW = new GLatLng(sw.lat()+((i-1)*gridCellSizeLat), sw.lng()+((j-1)*gridCellSizeLng));
            var cellNE = new GLatLng(cellSW.lat()+gridCellSizeLat, cellSW.lng()+gridCellSizeLng);
            gridCells[cell] = {
                GLatLngBounds : new GLatLngBounds(cellSW,cellNE),
                cluster : false,
                markers:[],
                length:0
            };

            //mark cell for testing
            map.addOverlay(new Rectangle(gridCells[cell].GLatLngBounds,1,"#00F",'<strong>Grid Cell</strong>'));

        }

        gridCells[cell].length++;

        //already in cluster mode
        if(gridCells[cell].cluster) continue;

        //only cluster if it has more than 2 points
        if(gridCells[cell].markers.length==3) {
            gridCells[cell].markers=null;
            gridCells[cell].cluster=true;
        } else {
            gridCells[cell].markers.push(latlng);
        }

    }

    for (k in gridCells) {
        if(gridCells[k].cluster == true) {
            //create a cluster marker in teh center of the grid cell
            var span = gridCells[k].GLatLngBounds.toSpan();
            var sw = gridCells[k].GLatLngBounds.getSouthWest();
            var marker = createMarker(new GLatLng(sw.lat()+(span.lat()/2),sw.lng()+(span.lng()/2)),'c');
            map.addOverlay(marker);
        } else {
            //create the single markers
            for(i in gridCells[k].markers) {
                var marker = createMarker(gridCells[k].markers[i],'p');
                map.addOverlay(marker);
            }
        }
    }
}

function createMarker(point, type) {
     //create the marker with the appropriate icon
     if(type=='c') {
         var marker = new GMarker(point,iconCluster,true);
     } else {
         var marker = new GMarker(point,iconSingle,true);
     }
    return marker;
}

window.onload = init;


/*
* Rectangle overlay for development only to mark boundries for testing...
*/
function Rectangle(bounds, opt_weight, opt_color, opt_html) {
  this.bounds_ = bounds; this.weight_ = opt_weight || 1; this.html_ = opt_html || ""; this.color_ = opt_color || "#888888";
}
Rectangle.prototype = new GOverlay();

Rectangle.prototype.initialize = function(map) {
  var div = document.createElement("div");
  div.innerHTML = this.html_;
  div.style.border = this.weight_ + "px solid " + this.color_;
  div.style.position = "absolute";
  map.getPane(G_MAP_MAP_PANE).appendChild(div);
  this.map_ = map;
  this.div_ = div;
}
Rectangle.prototype.remove = function() { this.div_.parentNode.removeChild(this.div_); }
Rectangle.prototype.copy = function() { return new Rectangle(this.bounds_, this.weight_, this.color_, this.backgroundColor_, this.opacity_); }
Rectangle.prototype.redraw = function(force) {
  if (!force) return;
  var c1 = this.map_.fromLatLngToDivPixel(this.bounds_.getSouthWest());
  var c2 = this.map_.fromLatLngToDivPixel(this.bounds_.getNorthEast());
  this.div_.style.width = Math.abs(c2.x - c1.x) + "px";
  this.div_.style.height = Math.abs(c2.y - c1.y) + "px";
  this.div_.style.left = (Math.min(c2.x, c1.x) - this.weight_) + "px";
  this.div_.style.top = (Math.min(c2.y, c1.y) - this.weight_) + "px";
}