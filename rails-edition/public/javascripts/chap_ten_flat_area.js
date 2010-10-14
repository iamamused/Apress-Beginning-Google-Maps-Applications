/* [listing 10-1] */
var points = [
	{'x': 1, 'y': 4 },
	{'x': 4, 'y': 6 },
	{'x': 6, 'y': 5 },
	{'x': 5, 'y': 1 },
	{'x': 3, 'y': 3 },
	{'x': 2, 'y': 2 }
];

function calculateArea(points) {
	var count = points.length;
	var i;
	var tally = 0;
	
	// add the first point to the end of the array
	// Caution: "points" is passed by reference, so this affects the copy held by the
	// calling code.
	points[points.length] = points[0];
	
	for(i = 0; i < count; i++) {
		tally += points[i + 1].x * points[i].y
		tally -= points[i].x * points[i + 1].y
	}
	
	return tally * 0.5;
}
/* [listing 10-1 end] */


var scaleFactor = 50;

function init() {
	var container = document.getElementById('points');
	var i;
	
	for(i = 0; i < points.length; i++) {
		var newPoint = document.createElement('li');
		newPoint.style.top = container.offsetHeight - points[i].y * scaleFactor + 'px;';
		newPoint.style.left = points[i].x * scaleFactor + 'px; ';
		newPoint.innerHTML = '<span>' + (i + 1) + '</span>';
		container.appendChild(newPoint);
	}
	
	document.getElementById('sum').innerHTML = 'Area of figure is: ' + calculateArea(points) + ' square units.';
}

window.onload = init;