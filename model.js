// Loads the main Timeline object
// $timeline is the object itself, startingPoint and endingPoint are the first and
// last year, respectively of the timeline, i.e. (1800, 2000)
var loadTimeline = function($timeline, startingPoint, endingPoint, pixelsPerYear) {
	
	// Fetches the data using AJAX
	var df = new DataFetcher(startingPoint, endingPoint);
	
	// Calculate the height of the timeline in pixels
	// Each year occupies the same space -- 10 pixels for example
	// 1 + so that the timeline covers the last element as well
	var height = (endingPoint - startingPoint + 1) * pixelsPerYear;
	$timeline.height(height);
	
	// Add the markers for the centuries, decades and possibly years
	addMarkers(startingPoint, endingPoint, pixelsPerYear);
	$(".timediv").height(pixelsPerYear);
	
	// Everything past this point requires that the data be ready
	$("body").ajaxSuccess(function(e, xhr, settings) {
		console.log(settings.url);
		if (settings.url.match("fetchAll")) {
			addEvents(df, pixelsPerYear);
			$timeline.fadeIn("slow");

			$("canvas").drawArc({
				fillStyle: "red",
				x: 5, y: 5,
				radius: 5
			});
		}
	});
}

// Adds the markers for centuries and decades
var addMarkers = function(startingPoint, endingPoint, pixelsPerYear) {
	var $timeline = $("#timeline");
	var range = endingPoint - startingPoint;
	
	for (i = 0; (i + startingPoint) <= endingPoint; i++) {
		var $newDiv;
		var cssClass;
		var currentYear = i + startingPoint;
		var lastTwoDigits = currentYear.toString().slice(-2);
		var lastDigit = currentYear.toString().slice(-1);
		
		if (lastTwoDigits == "00") {
			cssClass = "century";
		} else if (lastDigit == "0") {
			cssClass = "decade";
		} else {
			cssClass = "year";
		}
		
		$newDiv = $('<div class="timediv" id="' + currentYear + '"></div>');
		$newDiv.append($('<p class="year" class="' + cssClass + '">' + currentYear + '</p>'));
		
		$timeline.append($newDiv);
	}
}

// Adds all the events in a given range
var addEvents = function(df, pixelsPerYear) {
	var currentEvent;
	while (df.hasMore()) {
		currentEvent = df.nextEvent();
		console.log(currentEvent.getYear());
		$newCanvas = $('<canvas width="10" height="10" id="' + currentEvent.getId() +
		'"></canvas>');
		$("#" + currentEvent.getYear()).append($newCanvas);
	}
}

// Fetches all events in the specified range using AJAX
function DataFetcher(startingPoint, endingPoint) {
	var eventArray = new Array();
	var newEvent;
	
	$.get("fetchAll.php", {startdate: startingPoint, enddate: endingPoint},
		function(data) {			
			for (i in data) {
				eventArray.push(new Event(data[i].id, data[i].date,
										  data[i].type, data[i].desc));
			}
	}, "json");
	
	this.hasMore = function() {
		if (eventArray.length == 0) {
			return false;
		} else {
			return true;
		}
	};
	
	// Return the data relating to the next event and then removes it
	this.nextEvent = function() {
		return eventArray.pop();
	};
}

function Event(id, date, type, desc) {
	// Initialises the variables as "private"
	var _id = id;
	var _date = new Date(date);
	var _type = type;
	var _desc = desc;
	
	this.getId = function() {
		return _id;
	}
	
	// Return the year part of the date if it contains more than that
	this.getYear = function() {
		return _date.getFullYear();
	}
	
	// Return the whole date, i.e. '27.5.2004'
	this.getFullDate = function() {
		
	}
	
	this.getDescription = function() {
		return _desc;
	}
}

// Issues a nice warning bar
var issueWarning = function(warning) {
	alert("Warning: " + warning);
}