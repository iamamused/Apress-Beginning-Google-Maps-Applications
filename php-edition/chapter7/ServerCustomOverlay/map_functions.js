var map;
var centerLatitude = 19.9;
var centerLongitude = -156;
var startZoom = 7;

//Create the Detail overlay onject
function Detail(bounds, opt_weight, opt_color) {
    this.bounds_ = bounds;
    this.weight_ = opt_weight || 2;
    this.color_ = opt_color || "#000";
}
Detail.prototype = new GOverlay();

Detail.prototype.initialize = function(map) {
    // Create the DIV representing our Detail
    var div = document.createElement("div");
    div.style.border = this.weight_ + "px dotted " + this.color_;
    div.style.position = "absolute";

    // Our Detail is flat against the map, so we add our selves to the
    // MAP_PANE pane, which is at the same z-index as the map itself (i.e.,
    // below the marker shadows)
    map.getPane(G_MAP_MAP_PANE).appendChild(div);

    this.map_ = map;
    this.div_ = div;

    //load the background image
    this.loadBackground();
}

Detail.prototype.remove = function() {
    this.div_.parentNode.removeChild(this.div_);
}

Detail.prototype.copy = function() {
    return new Detail(this.bounds_, this.weight_, this.color_,
    this.backgroundColor_, this.opacity_);
}

Detail.prototype.redraw = function(force) {
    if (!force) return;

    this.bounds_ = this.map_.getBounds();

    var c1 = this.map_.fromLatLngToDivPixel(this.bounds_.getSouthWest());
    var c2 = this.map_.fromLatLngToDivPixel(this.bounds_.getNorthEast());

    this.div_.style.width = Math.abs(c2.x - c1.x) + "px";
    this.div_.style.height = Math.abs(c2.y - c1.y) + "px";
    this.div_.style.left = (Math.min(c2.x, c1.x) - this.weight_) + "px";
    this.div_.style.top = (Math.min(c2.y, c1.y) - this.weight_) + "px";

    //the position or zoom has changed so reload the background image
    this.loadBackground();
}

Detail.prototype.loadBackground = function() {

    //retrieve the bounds of the detail area
    var southWest = this.bounds_.getSouthWest();
    var northEast = this.bounds_.getNorthEast();

    //determin the pixel position of the corners
    var swPixels = this.map_.fromLatLngToDivPixel(this.bounds_.getSouthWest());
    var nePixels = this.map_.fromLatLngToDivPixel(this.bounds_.getNorthEast());

    //send the lat/lng as well as x/y and zoom to the server
    var getVars = 'ne=' + northEast.toUrlValue()
        + '&sw=' + southWest.toUrlValue()
        + '&nePixels=' + nePixels.x + ',' + nePixels.y
        + '&swPixels=' + swPixels.x + ',' + swPixels.y
        + '&z=' + this.map_.getZoom()
        + '';

    //log the URL for testing
    GLog.writeUrl('server.php?'+getVars);

    //set the background image of the div
    this.div_.style.background='transparent url(server.php?'+getVars+')';
}


function init() {
    map = new GMap2(document.getElementById("map"));
    map.addControl(new GSmallMapControl());
    map.setCenter(new GLatLng(centerLatitude, centerLongitude), startZoom);

    var bounds = map.getBounds();

    map.addOverlay(new Detail(bounds));

    alert('Note: This example has been limited to data from Hawaii');
}

window.onload = init;