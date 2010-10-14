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
