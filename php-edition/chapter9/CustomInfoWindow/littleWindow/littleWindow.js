//Create the LittleInfoWindow overlay onject
function LittleInfoWindow(marker,html,width) {
    this.html_ = html;
    this.width_ = ( width ? width + 'px' : 'auto');
    this.marker_ = marker;
}

//use the GOverlay Class
LittleInfoWindow.prototype = new GOverlay();

//initialize the container and shadowContainer
LittleInfoWindow.prototype.initialize = function(map) {
    this.map_ = map;

    var container = document.createElement("div");
    container.style.display='none';
    map.getPane(G_MAP_FLOAT_PANE).appendChild(container);
    this.container_ = container;

    var shadowContainer = document.createElement("div");
    shadowContainer.style.display='none';
    map.getPane(G_MAP_FLOAT_SHADOW_PANE).appendChild(shadowContainer);
    this.shadowContainer_ = shadowContainer;
}

LittleInfoWindow.prototype.remove = function() {
    this.container_.parentNode.removeChild(this.container_);
    
    //Dont forget to remove the shadow as well
    this.shadowContainer_.parentNode.removeChild(this.shadowContainer_);
}

LittleInfoWindow.prototype.copy = function() {
    return new LittleInfoWindow(this.marker_,this.html_,this.width_);
}

LittleInfoWindow.prototype.redraw = function(force) {
    if (!force) return;

    //get the content div
    var content = document.createElement("span");
    content.innerHTML = this.html_;
    content.style.font='10px verdana';
    content.style.margin='0';
    content.style.padding='0';
    content.style.border='0';
    content.style.display='inline';

    if(!this.width_ || this.width_=='auto' || this.width_ <= 0) {
        //The width is unknown so set a rough maximum and minimum
        content.style.minWidth = '10px';
        content.style.maxWidth = '500px';
        content.style.width = 'auto';
    } else {
        //The width was set when creating the window
        content.style.width= width + 'px';
    }

    //make it invisible for now.
    content.style.visibility='hidden';
    
    //temporarily append the content to the map container
    this.map_.getContainer().appendChild(content);

	//retrieve the rendered width and height
    var contentWidth = content.offsetWidth;
    var contentHeight = content.offsetHeight;

    //remove the content from the map
    content.parentNode.removeChild(content);
    content.style.visibility='visible';

    //set the width and height to ensure they 
    //stay that size when drawn again
    content.style.width=contentWidth+'px';
    content.style.height=contentHeight+'px';

    //set up the actual position relative to your images
    content.style.position='absolute';
    content.style.left='5px';
    content.style.top='7px';
    content.style.background='white';

    //create the wrapper for the window
    var wrapper = document.createElement("div");

    //first append the content so the wrapper is above
    wrapper.appendChild(content);

    //create an object to referencing each image
    var wrapperParts = {
        tl:{l:0, t:0, w:5, h:7},
        t:{l:5, t:0, w:(contentWidth-6), h:7},
        tr:{l:(contentWidth-1), t:0, w:11, h:9},
        l:{l:0, t:7, w:5, h:contentHeight},
        r:{l:(contentWidth+5), t:9, w:5, h:(contentHeight-2)},
        bl:{l:0, t:(contentHeight+7), w:5, h:5},
        p:{l:5, t:(contentHeight+7), w:17, h:18},
        b:{l:22, t:(contentHeight+7), w:(contentWidth-17), h:5},
        br:{l:(contentWidth+5), t:(contentHeight+7), w:5, h:5}
    }

    //create the image DOM objects
    for (i in wrapperParts) {
        var img = document.createElement('img');
        
        //load the image from your local image directory
        //based on the property name of the wrapperParts object
        img.src = 'littleWindow/' + i + '.png';
        
        //set the appripriate positioning attriburtes
        img.style.position='absolute';
        img.style.top=wrapperParts[i].t+'px';
        img.style.left=wrapperParts[i].l+'px';
        img.style.width=wrapperParts[i].w+'px';
        img.style.height=wrapperParts[i].h+'px';
        wrapper.appendChild(img);
        wrapperParts[i].img = img;
    }

    //add any event handlers like the close box
    var marker = this.marker_;
    GEvent.addDomListener(wrapperParts.tr.img, "click", function() {
            marker.closeLittleInfoWidow();
    });

    //get the X,Y pixel location of the marker
    var pixelLocation = this.map_.fromLatLngToDivPixel(
        this.marker_.getPoint()
    );

    //position the container div for the window
    this.container_.style.position='absolute';
    this.container_.style.left = (pixelLocation.x-3) + "px";
    this.container_.style.top = (pixelLocation.y 
        - contentHeight 
        - 25 
        - this.marker_.getIcon().iconSize.height
    ) + "px";
    this.container_.style.border = '0';
    this.container_.style.margin = '0';
    this.container_.style.padding = '0';
    this.container_.style.display = 'block';

    //append the styled info window to the container
    this.container_.appendChild(wrapper);


    //add a shadow...
    this.shadowContainer_.style.position='absolute';
    this.shadowContainer_.style.left = (pixelLocation.x+15) + "px";
    this.shadowContainer_.style.top = (pixelLocation.y 
        - 10 
        - this.marker_.getIcon().iconSize.height
    ) + "px";
    this.shadowContainer_.style.border = '1px solid black';
    this.shadowContainer_.style.margin = '0';
    this.shadowContainer_.style.padding = '0';
    this.shadowContainer_.style.display = 'block';

    var shadowParts = {
        sl:{l:0, t:0, w:35, h:26},
        s:{l:35, t:0, w:(contentWidth-40), h:26},
        sr:{l:(contentWidth-5), t:0, w:35, h:26}
    }

    for (i in shadowParts) {
        var img = document.createElement('img');
        img.src = 'littleWindow/' + i + '.png';
        img.style.position='absolute';
        img.style.top=shadowParts[i].t+'px';
        img.style.left=shadowParts[i].l+'px';
        img.style.width=shadowParts[i].w+'px';
        img.style.height=shadowParts[i].h+'px';
        this.shadowContainer_.appendChild(img);
    }


    //pan if necessary so it shows on the screen...
    var mapNE = this.map_.fromLatLngToDivPixel(
        this.map_.getBounds().getNorthEast()
    );
    var panX=0;
    var panY=0;
    if(this.container_.offsetTop < mapNE.y) {
        //top of window is above the top edge of the map container
        panY = mapNE.y - this.container_.offsetTop;
    }
    if(this.container_.offsetLeft+contentWidth+10 > mapNE.x) {
        //right edge of window is outside the right edge of the map container
        panX = (this.container_.offsetLeft+contentWidth+10) - mapNE.x;
    }

    if(panX!=0 || panY!=0) { 
        //pan the map
        this.map_.panBy(new GSize(-panX-10,panY+30));
    }

}

//Add a new method to GMarker so you
//can use a similar API to the existing info window.
GMarker.prototype.LittleInfoWindowInstance = null;
GMarker.prototype.openLittleInfoWindow = function(content,width) {
    if(this.LittleInfoWindowInstance == null) {
        this.LittleInfoWindowInstance = new LittleInfoWindow(
            this,
            content,
            width
        );
        map.addOverlay(this.LittleInfoWindowInstance);
    }
}
GMarker.prototype.closeLittleInfoWidow = function() {
    if(this.LittleInfoWindowInstance != null) {
        map.removeOverlay(this.LittleInfoWindowInstance);
        this.LittleInfoWindowInstance = null;
    }
}
