// Loads the main Timeline object
// $timeline is the object itself, startingPoint and endingPoint are the first and
// last year, respectively of the timeline, i.e. (1800, 2000)
var loadTimeline = function($timeline, startingPoint, endingPoint, pixelsPerYear) {
	
	// Calculate the height of the timeline in pixels
	// Each year occupies the same space -- 10 pixels for example
	var height = (endingPoint - startingPoint) * pixelsPerYear;
	$timeline.css("height", height + "px");
	
	// Add the markers for the centuries, decades and possibly years
	addMarkers(startingPoint, endingPoint, pixelsPerYear);
	$timeline.fadeIn("slow");
}

// Adds the markers for centuries and decades
var addMarkers = function(startingPoint, endingPoint, pixelsPerYear) {
	$markersDiv = $("#markers");
	var range = endingPoint - startingPoint;
	
	// We need to add a century marker at some point
	if (range > 100) {
		
		// Let's figure out the location of the first century
		// For example the distanceFromCentury of 1770 is 30
		var distanceFromCentury = 100 - startingPoint.toString().substring(2);
		
		for (i = 0; (i + startingPoint) < endingPoint; i += 100) {
			
			// Creates the DOM object with the correct century, for example
			// 100 + 1770 + 30 = 1900
			$centuryMarker = $('<p class="century">' + 
				(i + startingPoint + distanceFromCentury) + "</p>");
				
			// Calculates the distance from the top of the page
			$centuryMarker.css("top", (pixelsPerYear * (distanceFromCentury + i)) + "px");
			
			// Adds the new DOM object to the div that holds the markers
			$markersDiv.append($centuryMarker);
		}
	}
	
	// Add decade markers too
	if (range > 10) {
		
	}
}

// Issues a nice warning bar
var issueWarning = function(warning) {
	alert("Warning: " + warning);
}